<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\CrisisEvent;
use App\Services\AssessmentEngine;
use App\Services\CrisisDetector;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AssessmentController extends Controller
{
    // ─── Question Bank ────────────────────────────────────────────────────────

    private array $questions = [

        'phq9' => [
            'title'       => 'PHQ-9 — Depression Screening',
            'description' => 'Over the last 2 weeks, how often have you been bothered by any of the following problems?',
            'scale'       => ['Not at all', 'Several days', 'More than half the days', 'Nearly every day'],
            'questions'   => [
                ['key' => 'q1', 'text' => 'Little interest or pleasure in doing things'],
                ['key' => 'q2', 'text' => 'Feeling down, depressed, or hopeless'],
                ['key' => 'q3', 'text' => 'Trouble falling/staying asleep, or sleeping too much'],
                ['key' => 'q4', 'text' => 'Feeling tired or having little energy'],
                ['key' => 'q5', 'text' => 'Poor appetite or overeating'],
                ['key' => 'q6', 'text' => 'Feeling bad about yourself — or that you have let yourself or your family down'],
                ['key' => 'q7', 'text' => 'Trouble concentrating on things, such as reading or watching TV'],
                ['key' => 'q8', 'text' => 'Moving or speaking so slowly others noticed — or being fidgety/restless'],
                ['key' => 'q9', 'text' => 'Thoughts that you would be better off dead, or thoughts of hurting yourself',
                 'crisis_if_nonzero' => true,
                 'crisis_message'    => 'You are not alone. Many people experience these thoughts. Please read the important message below before continuing.'],
            ],
            'branching' => [
                ['condition' => ['q1' => 0, 'q2' => 0], 'skip_to' => 'q4', 'message' => 'Skipping sleep question (not applicable).'],
            ],
        ],

        'gad7' => [
            'title'       => 'GAD-7 — Anxiety Screening',
            'description' => 'Over the last 2 weeks, how often have you been bothered by any of the following?',
            'scale'       => ['Not at all', 'Several days', 'More than half the days', 'Nearly every day'],
            'questions'   => [
                ['key' => 'q1', 'text' => 'Feeling nervous, anxious, or on edge'],
                ['key' => 'q2', 'text' => 'Not being able to stop or control worrying'],
                ['key' => 'q3', 'text' => 'Worrying too much about different things'],
                ['key' => 'q4', 'text' => 'Trouble relaxing'],
                ['key' => 'q5', 'text' => 'Being so restless that it is hard to sit still'],
                ['key' => 'q6', 'text' => 'Becoming easily annoyed or irritable'],
                ['key' => 'q7', 'text' => 'Feeling afraid, as if something awful might happen'],
            ],
        ],

        'audit' => [
            'title'       => 'AUDIT — Alcohol Use Screening',
            'description' => 'The following questions are about your use of alcohol over the past year.',
            'questions'   => [
                ['key' => 'q1', 'text' => 'How often do you have a drink containing alcohol?',
                 'scale' => ['Never', 'Monthly or less', '2–4 times a month', '2–3 times a week', '4+ times a week'],
                 'skip_if_zero' => true, 'skip_message' => 'Since you do not drink, the remaining questions are not applicable.'],
                ['key' => 'q2', 'text' => 'How many standard drinks do you have on a typical drinking day?',
                 'scale' => ['1–2', '3–4', '5–6', '7–9', '10 or more']],
                ['key' => 'q3', 'text' => 'How often do you have 6 or more drinks on one occasion?',
                 'scale' => ['Never', 'Less than monthly', 'Monthly', 'Weekly', 'Daily or almost daily']],
                ['key' => 'q4', 'text' => 'How often during the last year have you found you were unable to stop drinking once you had started?',
                 'scale' => ['Never', 'Less than monthly', 'Monthly', 'Weekly', 'Daily or almost daily']],
                ['key' => 'q5', 'text' => 'How often during the last year have you failed to do what was normally expected because of drinking?',
                 'scale' => ['Never', 'Less than monthly', 'Monthly', 'Weekly', 'Daily or almost daily']],
                ['key' => 'q6', 'text' => 'How often during the last year have you needed a first drink in the morning to get yourself going?',
                 'scale' => ['Never', 'Less than monthly', 'Monthly', 'Weekly', 'Daily or almost daily']],
                ['key' => 'q7', 'text' => 'How often during the last year have you had a feeling of guilt or remorse after drinking?',
                 'scale' => ['Never', 'Less than monthly', 'Monthly', 'Weekly', 'Daily or almost daily']],
                ['key' => 'q8', 'text' => 'How often during the last year have you been unable to remember what happened the night before because of drinking?',
                 'scale' => ['Never', 'Less than monthly', 'Monthly', 'Weekly', 'Daily or almost daily']],
                ['key' => 'q9', 'text' => 'Have you or someone else been injured as a result of your drinking?',
                 'scale' => ['No', 'Yes, but not in the last year', 'Yes, during the last year']],
                ['key' => 'q10', 'text' => 'Has a relative, friend, doctor, or other health worker been concerned about your drinking or suggested you cut down?',
                 'scale' => ['No', 'Yes, but not in the last year', 'Yes, during the last year']],
            ],
        ],

        'pgsi' => [
            'title'       => 'PGSI — Problem Gambling Severity',
            'description' => 'Thinking about the last 12 months, how often have you...',
            'scale'       => ['Never', 'Sometimes', 'Most of the time', 'Almost always'],
            'questions'   => [
                ['key' => 'q1', 'text' => 'Bet more than you could really afford to lose?'],
                ['key' => 'q2', 'text' => 'Needed to gamble with larger amounts of money to get the same feeling of excitement?'],
                ['key' => 'q3', 'text' => 'Gone back another day to try to win back money you lost?'],
                ['key' => 'q4', 'text' => 'Borrowed money or sold anything to get money to gamble?'],
                ['key' => 'q5', 'text' => 'Felt that you might have a problem with gambling?'],
                ['key' => 'q6', 'text' => 'Felt that gambling has caused you health problems, including stress or anxiety?'],
                ['key' => 'q7', 'text' => 'People criticized your betting or told you that you had a gambling problem?'],
                ['key' => 'q8', 'text' => 'Felt that gambling has caused you financial problems for you or your household?'],
                ['key' => 'q9', 'text' => 'Felt guilty about the way you gamble or what happens when you gamble?'],
            ],
        ],

        'ftnd' => [
            'title'       => 'FTND — Nicotine Dependence',
            'description' => 'The following questions help assess your level of nicotine dependence.',
            'questions'   => [
                ['key' => 'q1', 'text' => 'How soon after you wake up do you smoke your first cigarette?',
                 'scale' => ['Within 5 minutes', '6–30 minutes', '31–60 minutes', 'After 60 minutes']],
                ['key' => 'q2', 'text' => 'Do you find it difficult to refrain from smoking in places where it is forbidden?',
                 'scale' => ['No', 'Yes']],
                ['key' => 'q3', 'text' => 'Which cigarette would you hate most to give up?',
                 'scale' => ['The first one in the morning', 'Any other']],
                ['key' => 'q4', 'text' => 'How many cigarettes per day do you smoke?',
                 'scale' => ['10 or less', '11–20', '21–30', '31 or more']],
                ['key' => 'q5', 'text' => 'Do you smoke more frequently during the first hours after waking than during the rest of the day?',
                 'scale' => ['No', 'Yes']],
                ['key' => 'q6', 'text' => 'Do you smoke if you are so ill that you are in bed most of the day?',
                 'scale' => ['No', 'Yes']],
            ],
        ],
    ];

    // ─── Questions endpoint ───────────────────────────────────────────────────

    public function questions(string $type)
    {
        if (!array_key_exists($type, $this->questions)) {
            return response()->json(['error' => "Unknown assessment type: {$type}"], 404);
        }
        return response()->json($this->questions[$type]);
    }

    // ─── Matching recommendation ──────────────────────────────────────────────

    public function recommend(Request $request)
    {
        $user = auth('api')->user();

        $assessmentType = $request->input('type');
        $language       = $request->input('language');
        $gender         = $request->input('gender');

        // Map assessment types to DB specialization names (must match SpecializationSeeder exactly)
        $specMap = [
            'phq9'  => ['Depression & Mood', 'Anxiety & Stress', 'Trauma & PTSD'],
            'gad7'  => ['Anxiety & Stress', 'Depression & Mood', 'Trauma & PTSD'],
            'audit' => ['Addiction & Substance Use', 'Depression & Mood', 'Anxiety & Stress'],
            'pgsi'  => ['Gambling Recovery', 'Addiction & Substance Use', 'Anxiety & Stress'],
            'ftnd'  => ['Tobacco Cessation', 'Addiction & Substance Use'],
        ];

        $targetSpecs = $specMap[$assessmentType] ?? [];

        $professionals = \App\Models\Professional::with(['user:id,display_name,avatar', 'specializations', 'languages'])
            ->where('verification_status', 'verified')
            ->where('is_accepting_new_patients', true)
            ->get();

        // Max possible score = 60 (primary spec) + 15+5 (secondary/tertiary) + 15 (rating) + 10 (exp) = 105
        // Normalise against 95 so a good match (primary spec + solid rating + some exp) reads 90%+
        $NORM = 95.0;

        $topId = null;
        $scored = $professionals->map(function ($pro) use ($targetSpecs, $language, $gender, $NORM) {
            $proSpecs = $pro->specializations->pluck('name')->toArray();
            $proLangs = $pro->languages->pluck('name')->toArray();

            // Specialization score: primary=60, secondary=15, tertiary=5
            $specWeights = [60, 15, 5];
            $specScore = 0;
            foreach ($targetSpecs as $i => $spec) {
                // Case-insensitive partial match so minor naming differences don't break it
                $matched = collect($proSpecs)->contains(
                    fn($ps) => stripos($ps, $spec) !== false || stripos($spec, $ps) !== false
                );
                if ($matched) {
                    $specScore += $specWeights[$i] ?? 5;
                }
            }

            // Rating (0–15): use 4.0 as neutral default if null
            $ratingScore = (($pro->rating ?? 4.0) / 5) * 15;

            // Experience (0–10)
            $expScore = min((int)($pro->years_experience ?? 0), 10) / 10 * 10;

            // Language bonus (+5 if filter set and matched)
            $langScore = ($language && in_array($language, $proLangs)) ? 5 : 0;

            // Gender bonus (+3 if filter set and matched)
            $genderScore = ($gender && $pro->gender === $gender) ? 3 : 0;

            $total = $specScore + $ratingScore + $expScore + $langScore + $genderScore;
            $matchPct = (int) min(100, round($total / $NORM * 100));

            $reasons = [];
            if ($specScore >= 60) $reasons[] = 'Specializes in your area';
            elseif ($specScore > 0) $reasons[] = 'Related specialization';
            if (($pro->rating ?? 0) >= 4.0) $reasons[] = 'Highly rated (' . number_format($pro->rating, 1) . '★)';
            if ((int)($pro->years_experience ?? 0) >= 3) $reasons[] = ($pro->years_experience) . ' yrs experience';
            if ($language && in_array($language, $proLangs)) $reasons[] = 'Speaks ' . $language;
            if (empty($reasons)) $reasons[] = 'Verified professional';

            return [
                'id'               => $pro->id,
                'display_name'     => $pro->user->display_name,
                'avatar'           => $pro->user->avatar,
                'bio'              => $pro->bio,
                'kmpdc_license'    => $pro->kmpdc_license,
                'rate_per_hour'    => $pro->rate_per_hour,
                'rating'           => $pro->rating,
                'total_reviews'    => $pro->total_reviews,
                'years_experience' => $pro->years_experience,
                'gender'           => $pro->gender,
                'specializations'  => $pro->specializations,
                'languages'        => $pro->languages,
                'match_score'      => round($total, 1),
                'match_pct'        => $matchPct,
                'match_reasons'    => array_values(array_filter($reasons)),
                'is_top_match'     => false,
            ];
        })->sortByDesc('match_score')->values();

        // Tag top match by id
        if ($scored->count() > 0) {
            $topId = $scored->first()['id'];
        }

        $scored = $scored->map(function ($item) use ($topId) {
            $item['is_top_match'] = $item['id'] === $topId;
            return $item;
        });

        return response()->json([
            'assessment_type' => $assessmentType,
            'matches'         => $scored->take(5),
            'total_available' => $scored->count(),
        ]);
    }

    // ─── CRUD ─────────────────────────────────────────────────────────────────

    public function index()
    {
        $user = auth('api')->user();
        $assessments = Assessment::where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json($assessments);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'assessment_type' => 'required|in:phq9,gad7,audit,pgsi,ftnd',
            'responses'       => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $user = auth('api')->user();
        $engine = new AssessmentEngine();
        $crisisDetector = new CrisisDetector();

        try {
            $result = $engine->run($request->assessment_type, $request->responses);
        } catch (\InvalidArgumentException $e) {
            return response()->json(['error' => $e->getMessage()], 422);
        }

        $assessment = Assessment::create([
            'user_id'         => $user->id,
            'assessment_type' => $request->assessment_type,
            'score'           => $result['score'],
            'severity'        => $result['severity'],
            'interpretation'  => $result['interpretation'],
            'recommendations' => $result['recommendations'],
            'responses'       => $request->responses,
            'is_crisis_flag'  => $result['is_crisis_flag'],
        ]);

        if ($result['is_crisis_flag']) {
            CrisisEvent::create([
                'user_id'           => $user->id,
                'trigger_source'    => 'assessment',
                'content'           => "Assessment: {$request->assessment_type} — Score: {$result['score']}",
                'severity'          => 'critical',
                'keywords_detected' => ['crisis_score'],
                'response_action'   => 'Hotlines provided',
                'resolved'          => false,
            ]);
        }

        return response()->json([
            'assessment' => $assessment,
            'crisis'     => $result['is_crisis_flag'],
        ], 201);
    }

    public function show($id)
    {
        $user = auth('api')->user();
        $assessment = Assessment::where('id', $id)->where('user_id', $user->id)->first();

        if (!$assessment) {
            return response()->json(['error' => 'Assessment not found'], 404);
        }

        return response()->json(['assessment' => $assessment]);
    }
}
