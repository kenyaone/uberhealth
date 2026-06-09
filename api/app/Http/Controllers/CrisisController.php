<?php

namespace App\Http\Controllers;

use App\Models\CrisisEvent;
use App\Services\CrisisDetector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CrisisController extends Controller
{
    public function report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'content'        => 'required|string',
            'trigger_source' => 'sometimes|in:assessment,mood_log,craving_log',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = auth('api')->user();
        $crisisDetector = new CrisisDetector();
        $source = $request->trigger_source ?? 'mood_log';

        $isCrisis = $crisisDetector->checkText($request->content);
        $keywords = $crisisDetector->getDetectedKeywords($request->content);
        $severity = $isCrisis
            ? $crisisDetector->detectSeverity(true, $source)
            : 'medium'; // manual report is at least medium

        $event = CrisisEvent::create([
            'user_id'           => $user->id,
            'trigger_source'    => $source,
            'content'           => $request->content,
            'severity'          => $severity,
            'keywords_detected' => $keywords,
            'response_action'   => 'Manual report — Hotlines provided',
            'resolved'          => false,
        ]);

        return response()->json([
            'message'  => 'Crisis reported. Please contact a helpline immediately.',
            'event'    => $event,
            'hotlines' => $this->getHotlinesList(),
        ], 201);
    }

    public function hotlines()
    {
        return response()->json([
            'hotlines' => $this->getHotlinesList(),
        ]);
    }

    private function getHotlinesList(): array
    {
        return [
            [
                'name'      => 'Befrienders Kenya',
                'phone'     => '0800 723 253',
                'available' => '24/7',
            ],
            [
                'name'      => 'NACADA',
                'phone'     => '1192',
                'available' => '24/7',
            ],
            [
                'name'      => 'Kenya Red Cross',
                'phone'     => '1199',
                'available' => '24/7',
            ],
        ];
    }
}
