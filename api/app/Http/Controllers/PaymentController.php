<?php

namespace App\Http\Controllers;

use App\Models\Consultation;
use App\Models\Payment;
use App\Models\Professional;
use App\Models\ProfessionalPayout;
use App\Models\Subscription;
use App\Models\SubscriptionPayment;
use App\Models\Plan;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PaymentController extends Controller
{
    const COMMISSION_RATE = 0.20;

    public function initiate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'consultation_id' => 'required|string|exists:consultations,consultation_id',
            'phone'           => 'required|string|min:9',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = auth('api')->user();
        $consultation = Consultation::where('consultation_id', $request->consultation_id)
            ->where('user_id', $user->id)
            ->first();

        if (!$consultation) {
            return response()->json(['error' => 'Consultation not found'], 404);
        }

        if ($consultation->status !== 'pending') {
            return response()->json(['error' => 'Consultation already paid or cancelled'], 422);
        }

        // Check if payment already exists
        if ($consultation->payment) {
            return response()->json(['error' => 'Payment already initiated for this consultation'], 422);
        }

        try {
            $mpesa = new MpesaService();
            $result = $mpesa->stkPush(
                $request->phone,
                $consultation->amount,
                $consultation->consultation_id,
                'UberHealth Consultation'
            );

            $payment = Payment::create([
                'consultation_id'   => $consultation->id,
                'amount'            => $consultation->amount,
                'phone'             => $request->phone,
                'mpesa_checkout_id' => $result['CheckoutRequestID'] ?? null,
                'status'            => 'pending',
            ]);

            return response()->json([
                'message'          => 'STK Push sent. Enter your M-Pesa PIN.',
                'payment'          => $payment,
                'checkout_request' => $result['CheckoutRequestID'] ?? null,
            ]);
        } catch (\Exception $e) {
            Log::error('Payment initiation failed', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Payment initiation failed: ' . $e->getMessage()], 500);
        }
    }

    public function callback(Request $request)
    {
        Log::info('M-Pesa STK Callback', $request->all());

        $body = $request->input('Body.stkCallback', []);
        $resultCode = $body['ResultCode'] ?? -1;
        $checkoutId = $body['CheckoutRequestID'] ?? null;

        if (!$checkoutId) {
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }

        $payment = Payment::where('mpesa_checkout_id', $checkoutId)->first();

        if (!$payment) {
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }

        if ($resultCode == 0) {
            // Success
            $items = collect($body['CallbackMetadata']['Item'] ?? []);
            $txId = $items->firstWhere('Name', 'MpesaReceiptNumber')['Value'] ?? null;

            $payment->update([
                'status'                => 'completed',
                'mpesa_transaction_id'  => $txId,
                'mpesa_result_code'     => (string) $resultCode,
                'mpesa_result_desc'     => $body['ResultDesc'] ?? 'Success',
                'completed_at'          => now(),
            ]);

            // Confirm consultation
            $consultation = $payment->consultation;
            $consultation->update(['status' => 'confirmed']);

            // Create professional payout (80% of amount)
            $payoutAmount = $consultation->amount * (1 - self::COMMISSION_RATE);
            ProfessionalPayout::create([
                'professional_id' => $consultation->professional_id,
                'consultation_id' => $consultation->id,
                'amount'          => $payoutAmount,
                'status'          => 'pending',
            ]);

            // Increment professional total_sessions
            $consultation->professional->increment('total_sessions');
        } else {
            $payment->update([
                'status'            => 'failed',
                'mpesa_result_code' => (string) $resultCode,
                'mpesa_result_desc' => $body['ResultDesc'] ?? 'Failed',
            ]);
        }

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    public function subscriptionInitiate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'plan_id' => 'required|exists:plans,id',
            'phone'   => 'required|string|min:9',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = auth('api')->user();
        $plan = Plan::find($request->plan_id);

        if (!$plan || !$plan->is_active) {
            return response()->json(['error' => 'Plan not available'], 422);
        }

        // Create subscription record (pending activation)
        $subscription = Subscription::create([
            'user_id'    => $user->id,
            'plan_id'    => $plan->id,
            'status'     => 'active', // will activate on successful payment
            'started_at' => now(),
            'expires_at' => $plan->interval === 'annual'
                ? now()->addYear()
                : now()->addMonth(),
            'amount_paid'=> $plan->price_kes,
        ]);

        try {
            $mpesa = new MpesaService();
            $result = $mpesa->stkPush(
                $request->phone,
                $plan->price_kes,
                'SUB-' . $subscription->id,
                'UberHealth ' . $plan->name . ' Subscription'
            );

            $subPayment = SubscriptionPayment::create([
                'subscription_id'   => $subscription->id,
                'amount'            => $plan->price_kes,
                'phone'             => $request->phone,
                'mpesa_checkout_id' => $result['CheckoutRequestID'] ?? null,
                'status'            => 'pending',
            ]);

            return response()->json([
                'message'      => 'STK Push sent.',
                'subscription' => $subscription,
                'payment'      => $subPayment,
            ]);
        } catch (\Exception $e) {
            $subscription->delete();
            return response()->json(['error' => 'Payment initiation failed'], 500);
        }
    }

    public function subscriptionCallback(Request $request)
    {
        Log::info('M-Pesa Subscription Callback', $request->all());

        $body = $request->input('Body.stkCallback', []);
        $resultCode = $body['ResultCode'] ?? -1;
        $checkoutId = $body['CheckoutRequestID'] ?? null;

        if (!$checkoutId) {
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }

        $subPayment = SubscriptionPayment::where('mpesa_checkout_id', $checkoutId)->first();

        if (!$subPayment) {
            return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
        }

        if ($resultCode == 0) {
            $items = collect($body['CallbackMetadata']['Item'] ?? []);
            $txId = $items->firstWhere('Name', 'MpesaReceiptNumber')['Value'] ?? null;

            $subPayment->update([
                'status'              => 'completed',
                'mpesa_transaction_id'=> $txId,
            ]);

            $subPayment->subscription->update([
                'status'              => 'active',
                'mpesa_transaction_id'=> $txId,
            ]);
        } else {
            $subPayment->update(['status' => 'failed']);
            $subPayment->subscription->update(['status' => 'cancelled']);
        }

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    public function b2cResult(Request $request)
    {
        Log::info('M-Pesa B2C Result', $request->all());

        $result = $request->input('Result', []);
        $resultCode = $result['ResultCode'] ?? -1;
        $conversationId = $result['ConversationID'] ?? null;

        if ($resultCode == 0) {
            Log::info('B2C payout successful', ['conversation_id' => $conversationId]);
        } else {
            Log::warning('B2C payout failed', [
                'result_code' => $resultCode,
                'result_desc' => $result['ResultDesc'] ?? 'Unknown error',
            ]);
        }

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }

    public function b2cTimeout(Request $request)
    {
        Log::warning('M-Pesa B2C Timeout', $request->all());
        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Accepted']);
    }
}
