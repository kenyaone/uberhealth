<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\CrisisEvent;
use App\Models\Payment;
use App\Models\Professional;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function stats()
    {
        $totalUsers = User::where('role', 'user')->count();
        $totalProfessionals = Professional::where('verification_status', 'verified')->count();
        $totalConsultations = Consultation::count();
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');
        $crisisEvents = CrisisEvent::count();
        $unresolvedCrisis = CrisisEvent::where('resolved', false)->count();

        return response()->json([
            'total_users'          => $totalUsers,
            'total_professionals'  => $totalProfessionals,
            'total_consultations'  => $totalConsultations,
            'total_revenue_kes'    => (float) $totalRevenue,
            'crisis_events_total'  => $crisisEvents,
            'crisis_events_unresolved' => $unresolvedCrisis,
        ]);
    }

    public function professionals(Request $request)
    {
        $query = Professional::with(['user:id,username,display_name,email,phone', 'specializations', 'languages'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('verification_status', $request->status);
        }

        $professionals = $query->paginate(50);

        return response()->json($professionals);
    }

    // ─── Manually confirm a consultation payment (for testing / support) ────────
    public function confirmConsultation($id)
    {
        $consultation = Consultation::find($id);
        if (!$consultation) {
            return response()->json(['error' => 'Consultation not found'], 404);
        }
        $consultation->update(['status' => 'confirmed']);
        return response()->json(['message' => 'Consultation confirmed', 'consultation' => $consultation]);
    }

    // ─── List all consultations ───────────────────────────────────────────────
    public function consultations(Request $request)
    {
        $consultations = Consultation::with(['user:id,display_name,username', 'professional.user:id,display_name'])
            ->orderByDesc('created_at')
            ->paginate(30);
        return response()->json($consultations);
    }

    public function verifyProfessional($id, Request $request)
    {
        $professional = Professional::find($id);

        if (!$professional) {
            return response()->json(['error' => 'Professional not found'], 404);
        }

        $action = $request->input('action');
        $statusMap = ['approve' => 'verified', 'reject' => 'rejected', 'pending' => 'pending'];

        $newStatus = $statusMap[$action] ?? $request->input('verification_status');

        if (!in_array($newStatus, ['verified', 'rejected', 'pending'])) {
            return response()->json(['error' => 'Invalid action. Use approve, reject, or pending.'], 422);
        }

        $professional->update(['verification_status' => $newStatus]);

        return response()->json([
            'message'      => 'Status updated to ' . $newStatus,
            'professional' => $professional->load('user:id,display_name,email'),
        ]);
    }

    public function workload()
    {
        $weekStart = now()->startOfWeek();
        $weekEnd   = now()->endOfWeek();

        $professionals = \App\Models\Professional::with('user:id,display_name')
            ->where('verification_status', 'verified')
            ->get()
            ->map(function ($pro) use ($weekStart, $weekEnd) {
                $bookings = \App\Models\Consultation::where('professional_id', $pro->id)
                    ->whereBetween('scheduled_at', [$weekStart, $weekEnd])
                    ->whereIn('status', ['confirmed', 'in_progress'])
                    ->count();
                $cap = $pro->max_clients_per_week ?: 20;
                return [
                    'id'                    => $pro->id,
                    'display_name'          => $pro->user->display_name ?? 'Unknown',
                    'bookings_this_week'    => $bookings,
                    'max_clients_per_week'  => $cap,
                    'load_pct'              => (int) min(100, round($bookings / $cap * 100)),
                    'is_overloaded'         => $bookings >= $cap,
                ];
            })->sortByDesc('load_pct')->values();

        return response()->json(['workload' => $professionals]);
    }

    public function confirmConsultation(int $id)
    {
        $consultation = \App\Models\Consultation::find($id);
        if (!$consultation) return response()->json(['error' => 'Not found'], 404);
        $consultation->update(['status' => 'confirmed']);
        return response()->json(['message' => 'Confirmed.', 'consultation' => $consultation]);
    }
}
