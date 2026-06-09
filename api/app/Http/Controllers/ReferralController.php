<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\Professional;
use App\Models\Referral;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    // Professional creates a referral for a patient
    public function store(Request $request)
    {
        $user = auth('api')->user();
        $pro  = Professional::where('user_id', $user->id)->first();
        if (!$pro) return response()->json(['error' => 'Not a professional.'], 403);

        $request->validate([
            'patient_id'      => 'required|integer|exists:users,id',
            'consultation_id' => 'nullable|integer|exists:consultations,id',
            'type'            => 'required|in:internal,external',
            'referred_to_professional_id' => 'required_if:type,internal|nullable|integer|exists:professionals,id',
            'referred_to_name' => 'required_if:type,external|nullable|string|max:255',
            'referred_to_org'  => 'nullable|string|max:255',
            'reason'           => 'required|string|max:1000',
            'notes'            => 'nullable|string|max:1000',
        ]);

        $referral = Referral::create([
            'professional_id'             => $pro->id,
            'patient_id'                  => $request->patient_id,
            'consultation_id'             => $request->consultation_id,
            'type'                        => $request->type,
            'referred_to_professional_id' => $request->referred_to_professional_id,
            'referred_to_name'            => $request->referred_to_name,
            'referred_to_org'             => $request->referred_to_org,
            'reason'                      => $request->reason,
            'notes'                       => $request->notes,
        ]);

        // Notify the patient
        Notification::send(
            $request->patient_id,
            'referral',
            'Your therapist has made a referral',
            $request->type === 'internal'
                ? 'You have been referred to another professional on this platform.'
                : "You have been referred to {$request->referred_to_name}" . ($request->referred_to_org ? " at {$request->referred_to_org}" : '') . '.',
            ['referral_id' => $referral->id, 'reason' => $request->reason]
        );

        return response()->json(['referral' => $referral], 201);
    }

    // Professional's own referral history
    public function index()
    {
        $user = auth('api')->user();
        $pro  = Professional::where('user_id', $user->id)->first();
        if (!$pro) return response()->json(['error' => 'Not a professional.'], 403);

        $referrals = Referral::with(['patient:id,display_name'])
            ->where('professional_id', $pro->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json($referrals);
    }

    // Patient sees their referrals
    public function myReferrals()
    {
        $user = auth('api')->user();
        $referrals = Referral::where('patient_id', $user->id)
            ->orderByDesc('created_at')
            ->get();
        return response()->json(['referrals' => $referrals]);
    }
}
