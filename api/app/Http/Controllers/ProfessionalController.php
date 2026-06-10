<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Professional;
use App\Models\ProfessionalAvailability;
use App\Models\Specialization;
use App\Models\Language;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProfessionalController extends Controller
{
    public function index(Request $request)
    {
        $query = Professional::with(['user:id,display_name,avatar', 'specializations', 'languages'])
            ->where('verification_status', 'verified');

        if ($request->filled('specialization')) {
            $query->whereHas('specializations', function ($q) use ($request) {
                $q->where('slug', $request->specialization)
                  ->orWhere('name', 'like', '%' . $request->specialization . '%');
            });
        }

        if ($request->filled('language')) {
            $query->whereHas('languages', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->language . '%');
            });
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->gender);
        }

        $professionals = $query->orderByDesc('rating')->paginate(20);

        // Compute match scores if user is authenticated
        $userConditions  = [];
        $userLanguage    = null;
        $authUser = auth('api')->user();
        if ($authUser) {
            $latest = Assessment::where('user_id', $authUser->id)
                ->orderByDesc('created_at')
                ->first();
            if ($latest) {
                $userConditions = [$latest->type]; // e.g. 'phq9', 'audit'
            }
            $userLanguage = $authUser->preferred_language ?? null;
        }

        $conditionSpecMap = [
            'phq9'  => ['depression', 'mood'],
            'gad7'  => ['anxiety', 'stress'],
            'audit' => ['alcohol', 'addiction', 'substance'],
            'dast10'=> ['substance', 'drug', 'addiction'],
            'pgsi'  => ['gambling', 'addiction'],
        ];

        $professionals->getCollection()->transform(function ($prof) use ($userConditions, $userLanguage, $conditionSpecMap) {
            $safe = $this->safeProfessional($prof);
            // Match score: specialization 40 + language 20 + rating 25 + experience 15
            $score = 0;
            $profSpecs = array_map('strtolower', array_column($prof->specializations->toArray(), 'name'));
            foreach ($userConditions as $cond) {
                $keywords = $conditionSpecMap[$cond] ?? [];
                foreach ($keywords as $kw) {
                    foreach ($profSpecs as $spec) {
                        if (str_contains($spec, $kw)) { $score += 40; break 2; }
                    }
                }
            }
            if ($userLanguage) {
                $profLangs = array_map('strtolower', array_column($prof->languages->toArray(), 'name'));
                foreach ($profLangs as $lang) {
                    if (str_contains(strtolower($userLanguage), $lang) || str_contains($lang, strtolower($userLanguage))) {
                        $score += 20; break;
                    }
                }
            } else {
                $score += 10; // neutral if no preference
            }
            $score += min(25, (float)($prof->rating ?? 0) / 5 * 25);
            $score += min(15, (int)($prof->years_experience ?? 0) / 10 * 15);
            $safe['match_score'] = (int) min(100, $score);
            return $safe;
        });

        return response()->json($professionals);
    }

    public function show($id)
    {
        $professional = Professional::with(['user:id,display_name,avatar', 'specializations', 'languages', 'availability'])
            ->where('id', $id)
            ->where('verification_status', 'verified')
            ->first();

        if (!$professional) {
            return response()->json(['error' => 'Professional not found'], 404);
        }

        return response()->json(['professional' => $this->safeProfessional($professional)]);
    }

    public function register(Request $request)
    {
        $user = auth('api')->user();

        if (Professional::where('user_id', $user->id)->exists()) {
            return response()->json(['error' => 'You already have a professional profile.'], 422);
        }

        $validator = Validator::make($request->all(), [
            'kmpdc_license'    => 'required|string|unique:professionals,kmpdc_license',
            'bio'              => 'required|string|min:20',
            'years_experience' => 'required|integer|min:0',
            'gender'           => 'required|in:male,female,other',
            'rate_per_hour'    => 'sometimes|numeric|min:0',
            'mpesa_number'     => 'required|string|max:15',
            'specialization_ids' => 'sometimes|array',
            'language_ids'     => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        DB::beginTransaction();
        try {
            $user->update(['role' => 'professional']);

            $professional = Professional::create([
                'user_id'          => $user->id,
                'kmpdc_license'    => $request->kmpdc_license,
                'bio'              => $request->bio,
                'years_experience' => $request->years_experience,
                'gender'           => $request->gender,
                'rate_per_hour'    => $request->rate_per_hour ?? 2000,
                'mpesa_number'     => $request->mpesa_number,
            ]);

            if ($request->filled('specialization_ids')) {
                $professional->specializations()->attach($request->specialization_ids);
            }

            if ($request->filled('language_ids')) {
                $professional->languages()->attach($request->language_ids);
            }

            DB::commit();

            return response()->json([
                'message'      => 'Application submitted. Pending verification.',
                'user'         => $user->fresh(),
                'professional' => $professional->load(['specializations', 'languages']),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Submission failed: ' . $e->getMessage()], 500);
        }
    }

    public function apply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username'        => 'required|string|min:3|unique:users,username',
            'display_name'    => 'required|string|max:100',
            'password'        => 'required|string|min:6',
            'email'           => 'nullable|email',
            'phone'           => 'nullable|string|max:15',
            'kmpdc_license'   => 'required|string|unique:professionals,kmpdc_license',
            'bio'             => 'required|string',
            'years_experience'=> 'required|integer|min:0',
            'gender'          => 'required|in:male,female,other',
            'rate_per_hour'   => 'sometimes|numeric|min:0',
            'specializations' => 'sometimes|array',
            'languages'       => 'sometimes|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        DB::beginTransaction();
        try {
            $user = User::create([
                'username'     => $request->username,
                'display_name' => $request->display_name,
                'password'     => $request->password,
                'email'        => $request->email,
                'phone'        => $request->phone,
                'role'         => 'professional',
            ]);

            $professional = Professional::create([
                'user_id'          => $user->id,
                'kmpdc_license'    => $request->kmpdc_license,
                'bio'              => $request->bio,
                'years_experience' => $request->years_experience,
                'gender'           => $request->gender,
                'rate_per_hour'    => $request->rate_per_hour ?? 2000,
            ]);

            if ($request->filled('specializations')) {
                $specIds = Specialization::whereIn('name', $request->specializations)->pluck('id');
                $professional->specializations()->attach($specIds);
            }

            if ($request->filled('languages')) {
                $langIds = Language::whereIn('name', $request->languages)->pluck('id');
                $professional->languages()->attach($langIds);
            }

            DB::commit();

            return response()->json([
                'message'      => 'Application submitted. Pending verification.',
                'professional' => $professional->load(['user:id,display_name', 'specializations', 'languages']),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => 'Application failed: ' . $e->getMessage()], 500);
        }
    }

    public function updateAvailability(Request $request)
    {
        $user = auth('api')->user();
        $professional = Professional::where('user_id', $user->id)->first();

        if (!$professional) {
            return response()->json(['error' => 'Professional profile not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'slots'              => 'required|array',
            'slots.*.day_of_week'=> 'required|integer|between:0,6',
            'slots.*.start_time' => 'required|date_format:H:i',
            'slots.*.end_time'   => 'required|date_format:H:i',
            'slots.*.is_active'  => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        // Replace existing availability
        $professional->availability()->delete();

        $slots = [];
        foreach ($request->slots as $slot) {
            $slots[] = ProfessionalAvailability::create([
                'professional_id' => $professional->id,
                'day_of_week'     => $slot['day_of_week'],
                'start_time'      => $slot['start_time'],
                'end_time'        => $slot['end_time'],
                'is_active'       => $slot['is_active'] ?? true,
            ]);
        }

        return response()->json(['message' => 'Availability updated', 'slots' => $slots]);
    }

    public function dashboard(Request $request)
    {
        $user = auth('api')->user();
        $professional = Professional::where('user_id', $user->id)
            ->with(['specializations', 'languages', 'availability'])
            ->first();

        if (!$professional) {
            return response()->json(['error' => 'Professional profile not found'], 404);
        }

        $upcomingConsultations = $professional->consultations()
            ->with('user:id,display_name,username,avatar')
            ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
            ->where('scheduled_at', '>', now()->subHours(3))
            ->orderBy('scheduled_at')
            ->limit(10)
            ->get();

        $pendingPayouts = $professional->payouts()
            ->where('status', 'pending')
            ->sum('amount');

        return response()->json([
            'professional'         => $this->safeProfessional($professional),
            'upcoming_consultations'=> $upcomingConsultations,
            'pending_payouts'      => $pendingPayouts,
        ]);
    }

    private function safeProfessional(Professional $prof): array
    {
        return [
            'id'                       => $prof->id,
            'display_name'             => $prof->user->display_name ?? null,
            'avatar'                   => $prof->user->avatar ?? null,
            'bio'                      => $prof->bio,
            'kmpdc_license'            => $prof->kmpdc_license,
            'verification_status'      => $prof->verification_status,
            'rate_per_hour'            => $prof->rate_per_hour,
            'years_experience'         => $prof->years_experience,
            'gender'                   => $prof->gender,
            'rating'                   => $prof->rating,
            'total_sessions'           => $prof->total_sessions,
            'total_reviews'            => $prof->total_reviews,
            'is_available_online'      => $prof->is_available_online,
            'is_accepting_new_patients'=> $prof->is_accepting_new_patients,
            'profile_photo'            => $prof->profile_photo,
            'specializations'          => $prof->relationLoaded('specializations') ? $prof->specializations : [],
            'languages'                => $prof->relationLoaded('languages') ? $prof->languages : [],
            'availability'             => $prof->relationLoaded('availability') ? $prof->availability : [],
        ];
    }
}
