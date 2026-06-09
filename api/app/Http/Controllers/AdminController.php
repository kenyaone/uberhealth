<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\CrisisEvent;
use App\Models\GroupMessage;
use App\Models\Payment;
use App\Models\Professional;
use App\Models\SupportGroup;
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

    // ─── Support Group Management ─────────────────────────────────────────────

    public function listGroups()
    {
        $groups = SupportGroup::orderBy('name')->get();
        return response()->json(['groups' => $groups]);
    }

    public function createGroup(Request $request)
    {
        $v = Validator::make($request->all(), [
            'name'        => 'required|string|max:100',
            'description' => 'required|string',
            'category'    => 'required|string|max:50',
            'icon'        => 'nullable|string|max:10',
            'is_active'   => 'boolean',
        ]);
        if ($v->fails()) return response()->json(['error' => $v->errors()->first()], 422);

        $slug = \Illuminate\Support\Str::slug($request->name);
        $base = $slug;
        $i = 1;
        while (SupportGroup::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        $group = SupportGroup::create([
            'name'        => $request->name,
            'slug'        => $slug,
            'description' => $request->description,
            'category'    => $request->category,
            'icon'        => $request->icon ?? '💬',
            'is_active'   => $request->boolean('is_active', true),
            'member_count'=> 0,
        ]);

        return response()->json(['message' => 'Group created.', 'group' => $group], 201);
    }

    public function updateGroup(Request $request, int $id)
    {
        $group = SupportGroup::findOrFail($id);
        $group->update($request->only(['name', 'description', 'category', 'icon', 'is_active']));
        return response()->json(['message' => 'Group updated.', 'group' => $group]);
    }

    public function deleteGroup(int $id)
    {
        $group = SupportGroup::findOrFail($id);
        $group->update(['is_active' => false]);
        return response()->json(['message' => 'Group deactivated.']);
    }

    // ─── Moderation Queue ─────────────────────────────────────────────────────

    public function flaggedMessages(Request $request)
    {
        $messages = GroupMessage::with(['group:id,name', 'user:id,display_name,username'])
            ->where('is_moderated', false)
            ->whereRaw("content REGEXP 'kill|suicide|overdose|end my life|hurt myself|self.harm|die|dead'")
            ->orderByDesc('created_at')
            ->paginate(30);

        return response()->json($messages);
    }

    public function moderateMessage(int $groupId, int $msgId)
    {
        $msg = GroupMessage::where('id', $msgId)->where('group_id', $groupId)->firstOrFail();
        $msg->update(['is_moderated' => true, 'moderated_at' => now()]);
        return response()->json(['message' => 'Message hidden.']);
    }

    // ─── SHA Accreditation Report ─────────────────────────────────────────────

    public function shaReport(Request $request)
    {
        $from = $request->query('from', now()->subMonths(3)->toDateString());
        $to   = $request->query('to', now()->toDateString());

        $consultations = \App\Models\Consultation::with(['user:id,display_name', 'professional:id,user_id,kmpdc_license', 'professional.user:id,display_name'])
            ->where('status', 'completed')
            ->whereBetween('scheduled_at', [$from . ' 00:00:00', $to . ' 23:59:59'])
            ->orderBy('scheduled_at')
            ->get();

        // ICD-10 mapping from last assessment
        $icd10Map = [
            'phq9'  => 'F32.9',
            'gad7'  => 'F41.1',
            'audit' => 'F10.10',
            'dast10'=> 'F19.10',
            'pgsi'  => 'F63.0',
        ];

        $rows = $consultations->map(function ($c) use ($icd10Map) {
            $assessment = \App\Models\Assessment::where('user_id', $c->user_id)
                ->where('created_at', '<=', $c->scheduled_at)
                ->orderByDesc('created_at')->first();

            return [
                'date'          => $c->scheduled_at->toDateString(),
                'session_id'    => $c->consultation_id,
                'patient'       => $c->user?->display_name ?? 'N/A',
                'therapist'     => $c->professional?->user?->display_name ?? 'N/A',
                'kmpdc_license' => $c->professional?->kmpdc_license ?? 'N/A',
                'icd10_code'    => $icd10Map[$assessment?->assessment_type] ?? 'Z04.6',
                'diagnosis'     => $assessment?->severity ?? 'N/A',
                'duration_mins' => 60,
                'platform'      => 'Afya Yako Siri Yako — mhapke.com',
            ];
        });

        $csv = implode("\n", array_merge(
            [implode(',', ['Date', 'Session ID', 'Patient', 'Therapist', 'KMPDC License', 'ICD-10', 'Diagnosis', 'Duration (mins)', 'Platform'])],
            $rows->map(fn($r) => implode(',', array_values($r)))->toArray()
        ));

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="sha_report_' . $from . '_' . $to . '.csv"',
        ]);
    }
}
