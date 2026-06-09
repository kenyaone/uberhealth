<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Consultation;
use App\Models\MoodLog;
use App\Models\Professional;
use App\Models\ProfessionalPayout;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ConsultationController extends Controller
{
    // ─── Patient: list their consultations ───────────────────────────────────

    public function index()
    {
        $user = auth('api')->user();
        $consultations = Consultation::with(['professional.user:id,display_name,avatar', 'payment'])
            ->where('user_id', $user->id)
            ->orderByDesc('scheduled_at')
            ->paginate(20);

        return response()->json($consultations);
    }

    // ─── Patient: create booking ─────────────────────────────────────────────

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'professional_id'  => 'required|exists:professionals,id',
            'scheduled_at'     => 'required|date|after:now',
            'duration_minutes' => 'sometimes|integer|in:30,60,90',
            'share_assessments'=> 'sometimes|boolean',
            'share_mood_logs'  => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = auth('api')->user();
        $professional = Professional::find($request->professional_id);

        if (!$professional || $professional->verification_status !== 'verified') {
            return response()->json(['error' => 'Professional not available'], 422);
        }

        $duration = $request->duration_minutes ?? 60;
        $amount = $professional->rate_per_hour * ($duration / 60);
        $consultationId = 'cons-' . bin2hex(random_bytes(4));

        $consultation = Consultation::create([
            'consultation_id'   => $consultationId,
            'user_id'           => $user->id,
            'professional_id'   => $professional->id,
            'scheduled_at'      => $request->scheduled_at,
            'duration_minutes'  => $duration,
            'status'            => 'pending',
            'amount'            => $amount,
            'jitsi_room'        => $consultationId,
            'share_assessments' => $request->boolean('share_assessments', false),
            'share_mood_logs'   => $request->boolean('share_mood_logs', false),
        ]);

        return response()->json([
            'message'      => 'Consultation booked. Proceed to payment.',
            'consultation' => $consultation->load('professional.user:id,display_name'),
        ], 201);
    }

    // ─── Join session (patient or professional) ───────────────────────────────

    public function join($id)
    {
        $user = auth('api')->user();

        $consultation = Consultation::with(['professional.user:id,display_name,avatar', 'user:id,display_name'])
            ->where(function ($q) use ($id) {
                $q->where('id', $id)->orWhere('consultation_id', $id);
            })
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('professional', fn($p) => $p->where('user_id', $user->id));
            })
            ->first();

        if (!$consultation) {
            return response()->json(['error' => 'Session not found or access denied.'], 404);
        }

        if (!in_array($consultation->status, ['confirmed', 'in_progress'])) {
            return response()->json(['error' => 'Session is not ready. Status: ' . $consultation->status], 422);
        }

        // Mark in_progress if within 15 min of start
        if ($consultation->status === 'confirmed') {
            $diff = Carbon::now()->diffInMinutes(Carbon::parse($consultation->scheduled_at), false);
            if ($diff <= 15) {
                $consultation->update(['status' => 'in_progress', 'actual_start' => now()]);
            }
        }

        $isProfessional = $consultation->professional->user_id === $user->id;
        $jitsiUrl = 'https://meet.jit.si/' . $consultation->jitsi_room;

        // Shared data the professional can see
        $sharedData = [];
        if ($isProfessional) {
            if ($consultation->share_assessments) {
                $sharedData['assessments'] = Assessment::where('user_id', $consultation->user_id)
                    ->latest()->take(5)
                    ->get(['assessment_type', 'score', 'severity', 'interpretation', 'is_crisis_flag', 'created_at']);
            }
            if ($consultation->share_mood_logs) {
                $sharedData['mood_logs'] = MoodLog::where('user_id', $consultation->user_id)
                    ->latest()->take(7)
                    ->get(['mood_score', 'note', 'logged_at']);
            }
        }

        return response()->json([
            'consultation'  => $consultation,
            'jitsi_url'     => $jitsiUrl,
            'room'          => $consultation->jitsi_room,
            'display_name'  => $user->display_name,
            'is_professional' => $isProfessional,
            'shared_data'   => $sharedData,
            'session_info'  => [
                'duration_minutes' => $consultation->duration_minutes,
                'scheduled_at'     => $consultation->scheduled_at,
                'amount'           => $consultation->amount,
            ],
        ]);
    }

    // ─── End session (professional marks complete) ────────────────────────────

    public function endSession($id, Request $request)
    {
        $user = auth('api')->user();
        $professional = Professional::where('user_id', $user->id)->first();

        if (!$professional) {
            return response()->json(['error' => 'Professional profile not found'], 404);
        }

        $consultation = Consultation::where('id', $id)
            ->where('professional_id', $professional->id)
            ->whereIn('status', ['confirmed', 'in_progress'])
            ->first();

        if (!$consultation) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        $consultation->update([
            'status'     => 'completed',
            'actual_end' => now(),
            'professional_notes' => $request->input('notes', $consultation->professional_notes),
        ]);

        $professional->increment('total_sessions');

        return response()->json(['message' => 'Session marked complete', 'consultation' => $consultation]);
    }

    // ─── Recording management ─────────────────────────────────────────────────

    public function saveRecording($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'recording_url' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = auth('api')->user();
        $consultation = $this->getOwnConsultation($id, $user->id);

        if (!$consultation) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        $consultation->update([
            'recording_url'     => $request->recording_url,
            'recording_kept'    => true,
            'recording_deleted' => false,
        ]);

        return response()->json(['message' => 'Recording saved', 'consultation' => $consultation]);
    }

    public function deleteRecording($id)
    {
        $user = auth('api')->user();
        $consultation = $this->getOwnConsultation($id, $user->id);

        if (!$consultation) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        $consultation->update([
            'recording_url'     => null,
            'recording_kept'    => false,
            'recording_deleted' => true,
        ]);

        return response()->json(['message' => 'Recording deleted']);
    }

    public function shareRecording($id)
    {
        $user = auth('api')->user();
        $consultation = $this->getOwnConsultation($id, $user->id);

        if (!$consultation || !$consultation->recording_url) {
            return response()->json(['error' => 'No recording found for this session'], 404);
        }

        return response()->json([
            'share_url'      => $consultation->recording_url,
            'consultation_id'=> $consultation->consultation_id,
            'expires_in'     => '7 days (you control the link)',
        ]);
    }

    // ─── Notes request (patient asks for full notes) ──────────────────────────

    public function requestNotes($id)
    {
        $user = auth('api')->user();
        $consultation = Consultation::where('id', $id)->where('user_id', $user->id)->first();

        if (!$consultation) {
            return response()->json(['error' => 'Session not found'], 404);
        }

        if ($consultation->status !== 'completed') {
            return response()->json(['error' => 'Notes are only available after a completed session'], 422);
        }

        $consultation->update(['notes_requested_at' => now()]);

        return response()->json([
            'message' => 'Notes request sent to your therapist. They will share within 24–48 hours.',
            'professional_notes' => $consultation->professional_notes,
        ]);
    }

    // ─── Follow-up booking ────────────────────────────────────────────────────

    public function bookFollowUp($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'scheduled_at'     => 'required|date|after:now',
            'duration_minutes' => 'sometimes|integer|in:30,60,90',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = auth('api')->user();
        $parent = Consultation::where('id', $id)->where('user_id', $user->id)->first();

        if (!$parent) {
            return response()->json(['error' => 'Original consultation not found'], 404);
        }

        $duration = $request->duration_minutes ?? $parent->duration_minutes;
        $professional = $parent->professional;
        $amount = $professional->rate_per_hour * ($duration / 60);

        $followUp = Consultation::create([
            'consultation_id'        => 'cons-' . bin2hex(random_bytes(4)),
            'user_id'                => $user->id,
            'professional_id'        => $parent->professional_id,
            'scheduled_at'           => $request->scheduled_at,
            'duration_minutes'       => $duration,
            'status'                 => 'pending',
            'amount'                 => $amount,
            'jitsi_room'             => 'cons-' . bin2hex(random_bytes(4)),
            'share_assessments'      => $parent->share_assessments,
            'share_mood_logs'        => $parent->share_mood_logs,
            'is_follow_up'           => true,
            'parent_consultation_id' => $parent->id,
        ]);

        return response()->json([
            'message'      => 'Follow-up booked. Proceed to payment.',
            'consultation' => $followUp->load('professional.user:id,display_name'),
        ], 201);
    }

    // ─── Professional: session list ───────────────────────────────────────────

    public function proList()
    {
        $user = auth('api')->user();
        $professional = Professional::where('user_id', $user->id)->first();

        if (!$professional) {
            return response()->json(['error' => 'Professional profile not found'], 404);
        }

        $consultations = Consultation::with(['user:id,display_name,avatar', 'payment'])
            ->where('professional_id', $professional->id)
            ->orderByDesc('scheduled_at')
            ->paginate(20);

        return response()->json($consultations);
    }

    // ─── Show single consultation ─────────────────────────────────────────────

    public function show($id)
    {
        $user = auth('api')->user();

        $consultation = Consultation::with(['professional.user:id,display_name,avatar', 'payment', 'user:id,display_name'])
            ->where('id', $id)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('professional', fn($p) => $p->where('user_id', $user->id));
            })
            ->first();

        if (!$consultation) {
            return response()->json(['error' => 'Consultation not found'], 404);
        }

        return response()->json(['consultation' => $consultation]);
    }

    // ─── Cancel ───────────────────────────────────────────────────────────────

    public function cancel($id)
    {
        $user = auth('api')->user();
        $consultation = Consultation::where('id', $id)->where('user_id', $user->id)->first();

        if (!$consultation) {
            return response()->json(['error' => 'Consultation not found'], 404);
        }

        if (!in_array($consultation->status, ['pending', 'confirmed'])) {
            return response()->json(['error' => 'Cannot cancel a ' . $consultation->status . ' consultation'], 422);
        }

        $isLate = $consultation->scheduled_at && now()->diffInHours($consultation->scheduled_at, false) < 24
                  && now()->lt($consultation->scheduled_at);

        $consultation->update([
            'status'            => 'cancelled',
            'late_cancellation' => $isLate,
        ]);

        return response()->json([
            'message'          => 'Consultation cancelled',
            'late_cancellation'=> $isLate,
            'consultation'     => $consultation,
        ]);
    }

    // ─── Reschedule ───────────────────────────────────────────────────────────

    public function reschedule($id, Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'scheduled_at' => 'required|date|after:now',
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = auth('api')->user();
        $consultation = Consultation::where('id', $id)
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhereHas('professional', fn($q2) => $q2->where('user_id', $user->id));
            })->first();

        if (!$consultation) {
            return response()->json(['error' => 'Consultation not found'], 404);
        }
        if (!in_array($consultation->status, ['pending', 'confirmed'])) {
            return response()->json(['error' => 'Cannot reschedule a ' . $consultation->status . ' session'], 422);
        }

        $consultation->update(['scheduled_at' => $request->scheduled_at]);

        return response()->json(['message' => 'Session rescheduled.', 'consultation' => $consultation]);
    }

    // ─── Rate ─────────────────────────────────────────────────────────────────

    public function rate($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|between:1,5',
            'review' => 'sometimes|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = auth('api')->user();
        $consultation = Consultation::where('id', $id)
            ->where('user_id', $user->id)
            ->where('status', 'completed')
            ->first();

        if (!$consultation) {
            return response()->json(['error' => 'Consultation not found or not completed'], 404);
        }

        $consultation->update([
            'user_rating' => $request->rating,
            'user_review' => $request->review,
        ]);

        $professional = $consultation->professional;
        $allRatings = Consultation::where('professional_id', $professional->id)->whereNotNull('user_rating')->pluck('user_rating');
        $professional->update(['rating' => round($allRatings->avg(), 2), 'total_reviews' => $allRatings->count()]);

        return response()->json(['message' => 'Rating submitted', 'consultation' => $consultation]);
    }

    // ─── Professional: add notes ──────────────────────────────────────────────

    public function addNotes($id, Request $request)
    {
        $validator = Validator::make($request->all(), ['notes' => 'required|string']);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = auth('api')->user();
        $professional = Professional::where('user_id', $user->id)->first();

        if (!$professional) {
            return response()->json(['error' => 'Professional profile not found'], 404);
        }

        $consultation = Consultation::where('id', $id)->where('professional_id', $professional->id)->first();

        if (!$consultation) {
            return response()->json(['error' => 'Consultation not found'], 404);
        }

        $consultation->update(['professional_notes' => $request->notes]);

        return response()->json(['message' => 'Notes saved', 'consultation' => $consultation]);
    }

    // Continuity: patient's most recently-used therapist + booking count
    public function myTherapist()
    {
        $user = auth('api')->user();
        $last = Consultation::with('professional.user:id,display_name,avatar')
            ->where('user_id', $user->id)
            ->whereIn('status', ['confirmed', 'in_progress', 'completed'])
            ->orderByDesc('scheduled_at')
            ->first();

        if (!$last || !$last->professional) {
            return response()->json(['therapist' => null]);
        }

        $count = Consultation::where('user_id', $user->id)
            ->where('professional_id', $last->professional_id)
            ->whereIn('status', ['confirmed', 'in_progress', 'completed'])
            ->count();

        $pro = $last->professional;
        return response()->json([
            'therapist' => [
                'id'               => $pro->id,
                'display_name'     => $pro->user->display_name ?? 'Therapist',
                'avatar'           => $pro->user->avatar ?? null,
                'sessions_together' => $count,
                'last_session_at'  => $last->scheduled_at,
            ],
        ]);
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    private function getOwnConsultation(int $id, int $userId): ?Consultation
    {
        return Consultation::where('id', $id)
            ->where(function ($q) use ($userId) {
                $q->where('user_id', $userId)
                  ->orWhereHas('professional', fn($p) => $p->where('user_id', $userId));
            })
            ->first();
    }
}
