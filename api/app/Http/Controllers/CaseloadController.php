<?php
namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Consultation;
use App\Models\MoodLog;
use App\Models\Professional;
use App\Models\RecoveryGoal;
use App\Models\SobrietyTracker;
use App\Models\User;
use Illuminate\Http\Request;

class CaseloadController extends Controller
{
    public function index()
    {
        $pro = auth('api')->user()->professional;
        if (!$pro) return response()->json(['error' => 'Not a professional'], 403);

        // Get all unique patients who have had at least one consultation
        $patientIds = Consultation::where('professional_id', $pro->id)
            ->whereIn('status', ['confirmed','completed','in_progress'])
            ->distinct()->pluck('user_id');

        $patients = User::whereIn('id', $patientIds)
            ->select('id','display_name','username','created_at')
            ->get()
            ->map(function ($u) use ($pro) {
                // Latest assessment
                $assessment = Assessment::where('user_id', $u->id)
                    ->orderByDesc('created_at')->first();

                // Recent mood trend (last 7 days)
                $moods = MoodLog::where('user_id', $u->id)
                    ->where('logged_at', '>=', now()->subDays(7))
                    ->orderBy('logged_at')
                    ->pluck('mood_score')->toArray();
                $avgMood = count($moods) ? round(array_sum($moods) / count($moods), 1) : null;

                // Next session
                $nextSession = Consultation::where('professional_id', $pro->id)
                    ->where('user_id', $u->id)
                    ->where('scheduled_at', '>', now())
                    ->whereIn('status', ['confirmed','pending'])
                    ->orderBy('scheduled_at')->first();

                // Session count
                $sessionCount = Consultation::where('professional_id', $pro->id)
                    ->where('user_id', $u->id)
                    ->where('status', 'completed')->count();

                // Active goals
                $goalsActive = RecoveryGoal::where('user_id', $u->id)
                    ->where('status', 'active')->count();

                return [
                    'id'             => $u->id,
                    'display_name'   => $u->display_name,
                    'username'       => $u->username,
                    'sessions_done'  => $sessionCount,
                    'avg_mood_7d'    => $avgMood,
                    'mood_logs_7d'   => count($moods),
                    'active_goals'   => $goalsActive,
                    'latest_assessment' => $assessment ? [
                        'type'     => $assessment->type,
                        'score'    => $assessment->score,
                        'severity' => $assessment->severity,
                        'date'     => $assessment->created_at,
                    ] : null,
                    'next_session'   => $nextSession ? $nextSession->scheduled_at : null,
                ];
            });

        return response()->json(['patients' => $patients]);
    }

    public function patient(int $patientId)
    {
        $pro = auth('api')->user()->professional;
        if (!$pro) return response()->json(['error' => 'Not a professional'], 403);

        $hasConsult = Consultation::where('professional_id', $pro->id)
            ->where('user_id', $patientId)->exists();
        if (!$hasConsult) return response()->json(['error' => 'No relationship'], 403);

        $user = User::findOrFail($patientId);

        $assessments = Assessment::where('user_id', $patientId)
            ->orderByDesc('created_at')->limit(10)->get();

        $moods = MoodLog::where('user_id', $patientId)
            ->orderByDesc('logged_at')->limit(30)
            ->get(['mood_score', 'note', 'logged_at']);

        $goals = RecoveryGoal::where('user_id', $patientId)->get();

        $sessions = Consultation::where('professional_id', $pro->id)
            ->where('user_id', $patientId)
            ->orderByDesc('scheduled_at')->limit(10)->get();

        $sobriety = SobrietyTracker::where('user_id', $patientId)
            ->where('is_active', true)->get(['substance','days_sober','start_date']);

        return response()->json([
            'patient'     => ['id' => $user->id, 'display_name' => $user->display_name],
            'assessments' => $assessments,
            'moods'       => $moods,
            'goals'       => $goals,
            'sessions'    => $sessions,
            'sobriety'    => $sobriety,
        ]);
    }

    // Professional payout statement
    public function payoutStatement()
    {
        $pro = auth('api')->user()->professional;
        if (!$pro) return response()->json(['error' => 'Not a professional'], 403);

        $payouts = \App\Models\ProfessionalPayout::where('professional_id', $pro->id)
            ->with('consultation:id,consultation_id,scheduled_at,amount')
            ->orderByDesc('created_at')
            ->get();

        $totalEarned = $payouts->where('status', 'paid')->sum('amount');
        $totalPending = $payouts->where('status', 'pending')->sum('amount');

        return response()->json([
            'payouts'       => $payouts,
            'total_earned'  => (float) $totalEarned,
            'total_pending' => (float) $totalPending,
            'commission_pct'=> 20,
        ]);
    }
}
