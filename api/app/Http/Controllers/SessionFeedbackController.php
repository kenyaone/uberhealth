<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\FollowUpSurvey;
use App\Models\Notification;
use App\Models\Professional;
use App\Models\SessionFeedback;
use Illuminate\Http\Request;

class SessionFeedbackController extends Controller
{
    // Patient submits post-session feedback
    public function store(Request $request, string $consultationId)
    {
        $user = auth('api')->user();
        $consultation = Consultation::where('consultation_id', $consultationId)
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->first();

        if (!$consultation) {
            return response()->json(['error' => 'Session not found or not completed.'], 404);
        }

        if (SessionFeedback::where('consultation_id', $consultation->id)->exists()) {
            return response()->json(['error' => 'Feedback already submitted.'], 422);
        }

        $request->validate([
            'overall_rating'       => 'required|integer|min:1|max:5',
            'communication_rating' => 'required|integer|min:1|max:5',
            'felt_heard'           => 'required|boolean',
            'would_recommend'      => 'required|boolean',
            'felt_safe'            => 'required|boolean',
            'comment'              => 'sometimes|nullable|string|max:1000',
        ]);

        $feedback = SessionFeedback::create([
            'consultation_id'      => $consultation->id,
            'user_id'              => $user->id,
            'overall_rating'       => $request->overall_rating,
            'communication_rating' => $request->communication_rating,
            'felt_heard'           => $request->felt_heard,
            'would_recommend'      => $request->would_recommend,
            'felt_safe'            => $request->felt_safe,
            'comment'              => $request->comment,
        ]);

        // Update professional's rating (average across all feedback)
        $pro = Professional::find($consultation->professional_id);
        if ($pro) {
            $avg = SessionFeedback::whereHas(
                'consultation', fn($q) => $q->where('professional_id', $pro->id)
            )->avg('overall_rating');
            $count = SessionFeedback::whereHas(
                'consultation', fn($q) => $q->where('professional_id', $pro->id)
            )->count();
            $pro->update(['rating' => round($avg, 2), 'total_reviews' => $count]);
        }

        // Schedule 3-day and 1-week follow-up surveys
        $this->scheduleFollowUpSurveys($consultation->id, $user->id);

        // Notify professional
        if ($pro) {
            Notification::send(
                $pro->user_id,
                'new_feedback',
                'New session feedback received',
                "A patient rated your session {$request->overall_rating}/5.",
                ['consultation_id' => $consultation->id, 'overall_rating' => $request->overall_rating]
            );
        }

        return response()->json(['feedback' => $feedback], 201);
    }

    // Professional sees their aggregated feedback
    public function myFeedback()
    {
        $user = auth('api')->user();
        $pro  = Professional::where('user_id', $user->id)->first();
        if (!$pro) return response()->json(['error' => 'Not a professional.'], 403);

        $feedbacks = SessionFeedback::with('consultation:id,consultation_id,scheduled_at')
            ->whereHas('consultation', fn($q) => $q->where('professional_id', $pro->id))
            ->orderByDesc('created_at')
            ->paginate(20);

        $stats = [
            'avg_overall'       => round(SessionFeedback::whereHas('consultation', fn($q) => $q->where('professional_id', $pro->id))->avg('overall_rating') ?? 0, 2),
            'avg_communication' => round(SessionFeedback::whereHas('consultation', fn($q) => $q->where('professional_id', $pro->id))->avg('communication_rating') ?? 0, 2),
            'felt_heard_pct'    => $this->pct($pro->id, 'felt_heard'),
            'would_recommend_pct' => $this->pct($pro->id, 'would_recommend'),
            'felt_safe_pct'     => $this->pct($pro->id, 'felt_safe'),
            'total'             => $feedbacks->total(),
        ];

        return response()->json(['stats' => $stats, 'feedbacks' => $feedbacks]);
    }

    private function pct(int $proId, string $col): int
    {
        $total = SessionFeedback::whereHas('consultation', fn($q) => $q->where('professional_id', $proId))->count();
        if (!$total) return 0;
        $yes = SessionFeedback::whereHas('consultation', fn($q) => $q->where('professional_id', $proId))->where($col, true)->count();
        return (int) round($yes / $total * 100);
    }

    private function scheduleFollowUpSurveys(int $consultationId, int $userId): void
    {
        $now = now();
        foreach (['3day' => 3, '1week' => 7, '1month' => 30] as $type => $days) {
            FollowUpSurvey::firstOrCreate(
                ['consultation_id' => $consultationId, 'survey_type' => $type],
                ['user_id' => $userId, 'due_at' => $now->copy()->addDays($days)]
            );
        }
    }
}
