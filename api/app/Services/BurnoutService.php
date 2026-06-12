<?php

namespace App\Services;

use App\Models\BurnoutAssessment;
use Anthropic\Anthropic;

class BurnoutService
{
    private Anthropic $client;

    public function __construct()
    {
        $this->client = new Anthropic([
            'apiKey' => config('services.anthropic.key'),
        ]);
    }

    public function generateReport(BurnoutAssessment $assessment): string
    {
        $prompt = $this->buildPrompt($assessment);

        $response = $this->client->messages->create([
            'model' => 'claude-sonnet-4-6',
            'max_tokens' => 2000,
            'system' => $this->getSystemPrompt(),
            'messages' => [
                ['role' => 'user', 'content' => $prompt],
            ],
        ]);

        return $response->content[0]->text;
    }

    private function buildPrompt(BurnoutAssessment $assessment): string
    {
        $demographicsText = implode("\n", array_filter([
            $assessment->assessor_type ? "Role: {$assessment->assessor_type}" : null,
            $assessment->specialization ? "Specialization: {$assessment->specialization}" : null,
            $assessment->years_experience ? "Years of Experience: {$assessment->years_experience}" : null,
            $assessment->age ? "Age: {$assessment->age}" : null,
            $assessment->gender ? "Gender: {$assessment->gender}" : null,
            $assessment->caseload_size ? "Weekly Caseload: {$assessment->caseload_size} clients" : null,
        ]));

        $responsesText = json_encode($assessment->responses, JSON_PRETTY_PRINT);

        return <<<PROMPT
Please analyze this ProQOL-5 (Professional Quality of Life Scale) burnout assessment for a healthcare professional.

## Professional Demographics
$demographicsText

## Assessment Responses
30-item ProQOL-5 responses (0=Never, 1=Rarely, 2=Sometimes, 3=Often, 4=Very Often):
$responsesText

## Scoring Results
- Compassion Satisfaction (CS) Score: {$assessment->cs_score}/50 ({$assessment->cs_zone} zone)
- Burnout (BO) Score: {$assessment->bo_score}/50 ({$assessment->bo_zone} zone)
- Secondary Traumatic Stress (STS) Score: {$assessment->sts_score}/50 ({$assessment->sts_zone} zone)
- Overall Zone: {$assessment->overall_zone}

Please provide a comprehensive, evidence-based report with the following structure:

## Executive Summary
Summarize the overall burnout status and zone assignment (green/yellow/orange/red). Be direct and clear about the severity level.

## Subscale Analysis
- **Compassion Satisfaction**: Interpret the CS score in context of the professional's role and caseload. High CS indicates ability to find meaning in work.
- **Burnout**: Analyze the BO score. High BO indicates emotional exhaustion, depersonalization, reduced effectiveness. Contextualize for their role.
- **Secondary Traumatic Stress**: Interpret STS score. High STS indicates vicarious trauma from client exposure.

## Risk Indicators
List 3-5 specific assessment responses that are concerning or that contributed to the zone classification. Reference the response values.

## Recommended Interventions (Role-Specific)
Based on their role ({$assessment->assessor_type}) and specialization, recommend:
- Peer supervision or case consultation strategies
- Organizational supports needed
- Workload adjustments if applicable
- Self-care protocols specific to their role
- Training or development opportunities (e.g., trauma-informed decompression)

## Self-Care Action Plan
Provide 5 concrete, evidence-based next steps the professional can implement immediately:
1. Immediate action (this week)
2. Short-term (within 1 month)
3. Medium-term support strategy
4. Organizational accommodation to request
5. Professional development opportunity

## Supervisor/Manager Summary
Provide a concise 2-paragraph summary for organizational leadership, framing findings professionally and emphasizing the need for support/intervention if in orange/red zones.

---

Use evidence-based language. Reference WHO ProQOL-5 norms and burnout research. Be compassionate but direct. If in red zone (high BO AND high STS), emphasize urgency of intervention.
PROMPT;
    }

    private function getSystemPrompt(): string
    {
        return <<<SYSTEM
You are an expert occupational health psychologist specializing in healthcare professional burnout assessment and intervention. You have deep knowledge of:

1. ProQOL-5 (Professional Quality of Life Scale) assessment and interpretation
2. Burnout in healthcare professions (counsellors, psychologists, doctors, social workers, nurses)
3. Vicarious trauma and compassion fatigue
4. Evidence-based interventions for burnout in Kenya and Sub-Saharan Africa
5. Organizational and individual burnout prevention strategies

Your role is to:
- Interpret ProQOL-5 scores within international and regional context
- Identify specific risk factors and protective factors
- Provide role-appropriate, culturally-aware recommendations
- Frame findings with compassion while being clinically direct
- Generate reports suitable for professional and organizational use

Key guidelines:
- RED ZONE (BO High AND STS High): Emphasize urgency, recommend immediate professional support, therapy, and organizational accommodation
- ORANGE ZONE (BO High OR STS High): Recommend structured intervention, peer support, workload review
- YELLOW ZONE (One subscale concerning): Recommend preventive strategies, monitoring, support options
- GREEN ZONE (CS High, BO/STS Low/Average): Affirm positive functioning while identifying maintenance strategies

All recommendations must be evidence-based and practical for implementation in a healthcare context.
SYSTEM;
    }

    public function calculateZones(int $csScore, int $boScore, int $stsScore): array
    {
        $csZone = $this->getZone($csScore);
        $boZone = $this->getZone($boScore);
        $stsZone = $this->getZone($stsScore);

        if ($boScore >= 43 && $stsScore >= 43) {
            $overallZone = 'red';
        } elseif ($boScore >= 43 || $stsScore >= 43) {
            $overallZone = 'orange';
        } elseif ($boScore >= 36 || $stsScore >= 36 || $csScore < 43) {
            $overallZone = 'yellow';
        } else {
            $overallZone = 'green';
        }

        return [
            'cs_zone' => $csZone,
            'bo_zone' => $boZone,
            'sts_zone' => $stsZone,
            'overall_zone' => $overallZone,
        ];
    }

    private function getZone(int $score): string
    {
        if ($score < 43) {
            return 'low';
        } elseif ($score <= 56) {
            return 'average';
        } else {
            return 'high';
        }
    }
}
