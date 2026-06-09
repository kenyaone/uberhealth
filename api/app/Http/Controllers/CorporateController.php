<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\EapSubscription;
use App\Models\EapTier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CorporateController extends Controller
{
    public function tiers()
    {
        $tiers = EapTier::where('is_active', true)->get();
        return response()->json(['tiers' => $tiers]);
    }

    public function apply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'           => 'required|string|max:200',
            'contact_name'   => 'required|string|max:100',
            'contact_email'  => 'required|email',
            'contact_phone'  => 'required|string|max:15',
            'industry'       => 'sometimes|nullable|string|max:100',
            'employee_count' => 'required|integer|min:1',
            'kra_pin'        => 'sometimes|nullable|string|max:20',
            'address'        => 'sometimes|nullable|string',
            'eap_tier_id'    => 'required|exists:eap_tiers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $tier = EapTier::find($request->eap_tier_id);

        DB::beginTransaction();
        try {
            $company = Company::create([
                'name'           => $request->name,
                'contact_name'   => $request->contact_name,
                'contact_email'  => $request->contact_email,
                'contact_phone'  => $request->contact_phone,
                'industry'       => $request->industry,
                'employee_count' => $request->employee_count,
                'kra_pin'        => $request->kra_pin,
                'address'        => $request->address,
                'is_active'      => false,
            ]);

            $sessionsTotal = $tier->sessions_per_employee * min($request->employee_count, $tier->max_employees);

            $eapSub = EapSubscription::create([
                'company_id'      => $company->id,
                'eap_tier_id'     => $tier->id,
                'admin_user_id'   => auth('api')->id(),
                'status'          => 'pending',
                'employee_limit'  => $tier->max_employees,
                'sessions_total'  => $sessionsTotal,
                'sessions_used'   => 0,
                'amount_paid'     => 0,
            ]);

            DB::commit();

            return response()->json([
                'message'          => 'EAP application submitted. Our team will contact you shortly.',
                'company'          => $company,
                'eap_subscription' => $eapSub->load('eapTier'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Application failed: ' . $e->getMessage()], 500);
        }
    }
}
