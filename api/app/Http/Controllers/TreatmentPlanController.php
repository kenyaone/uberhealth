<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\TreatmentPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatmentPlanController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'consultation_id' => 'required|exists:consultations,id',
            'description' => 'required|string|min:20',
            'duration_weeks' => 'required|integer|min:1',
            'sessions_per_week' => 'required|integer|min:1',
            'cost_per_session' => 'required|numeric|min:100',
            'schedule_details' => 'nullable|json',
        ]);

        $consultation = Consultation::findOrFail($request->consultation_id);

        if ($consultation->professional_id !== Auth::user()->professional->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $totalCost = $request->duration_weeks * $request->sessions_per_week * $request->cost_per_session;

        $plan = TreatmentPlan::create([
            'consultation_id' => $consultation->id,
            'professional_id' => $consultation->professional_id,
            'user_id' => $consultation->user_id,
            'description' => $request->description,
            'duration_weeks' => $request->duration_weeks,
            'sessions_per_week' => $request->sessions_per_week,
            'cost_per_session' => $request->cost_per_session,
            'total_cost' => $totalCost,
            'status' => 'draft',
            'schedule_details' => $request->schedule_details ? json_decode($request->schedule_details, true) : null,
        ]);

        return response()->json([
            'success' => true,
            'plan' => $plan,
        ]);
    }

    public function show($consultationId)
    {
        $consultation = Consultation::findOrFail($consultationId);
        $plan = TreatmentPlan::where('consultation_id', $consultationId)->first();

        if (!$plan) {
            return response()->json(['success' => false, 'message' => 'No treatment plan found'], 404);
        }

        if ($consultation->user_id !== Auth::id() && $consultation->professional_id !== Auth::user()->professional?->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        return response()->json(['success' => true, 'plan' => $plan]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'sometimes|string|min:20',
            'duration_weeks' => 'sometimes|integer|min:1',
            'sessions_per_week' => 'sometimes|integer|min:1',
            'cost_per_session' => 'sometimes|numeric|min:100',
        ]);

        $plan = TreatmentPlan::findOrFail($id);

        if ($plan->professional_id !== Auth::user()->professional->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($plan->status !== 'draft') {
            return response()->json(['success' => false, 'message' => 'Can only edit draft plans'], 422);
        }

        $plan->fill($request->only(['description', 'duration_weeks', 'sessions_per_week', 'cost_per_session']));

        if ($request->filled('duration_weeks') || $request->filled('sessions_per_week') || $request->filled('cost_per_session')) {
            $plan->total_cost = ($plan->duration_weeks ?? $plan->duration_weeks) * ($plan->sessions_per_week ?? $plan->sessions_per_week) * ($plan->cost_per_session ?? $plan->cost_per_session);
        }

        $plan->save();

        return response()->json(['success' => true, 'plan' => $plan]);
    }

    public function myPrescribedPlans()
    {
        $user = Auth::user();
        if (!$user->professional) {
            return response()->json(['success' => false, 'message' => 'Not a professional'], 403);
        }

        $plans = TreatmentPlan::where('professional_id', $user->professional->id)
            ->with(['user:id,display_name,avatar', 'consultation'])
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['success' => true, 'plans' => $plans]);
    }

    public function destroy($id)
    {
        $plan = TreatmentPlan::findOrFail($id);

        if ($plan->professional_id !== Auth::user()->professional->id) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($plan->status !== 'draft') {
            return response()->json(['success' => false, 'message' => 'Can only delete draft plans'], 422);
        }

        $plan->delete();

        return response()->json(['success' => true, 'message' => 'Treatment plan deleted']);
    }
}
