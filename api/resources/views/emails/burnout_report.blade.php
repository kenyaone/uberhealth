<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Your Confidential Burnout Assessment Report</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { border-bottom: 3px solid #2d3748; margin-bottom: 30px; }
        .header h1 { color: #1a202c; margin: 0; }
        .zone-green { background: #c6f6d5; border-left: 4px solid #22863a; padding: 15px; margin: 20px 0; }
        .zone-yellow { background: #feebc8; border-left: 4px solid #e67e22; padding: 15px; margin: 20px 0; }
        .zone-orange { background: #fed7d7; border-left: 4px solid #e74c3c; padding: 15px; margin: 20px 0; }
        .zone-red { background: #fed7d7; border-left: 4px solid #c0392b; padding: 15px; margin: 20px 0; font-weight: bold; }
        .score-box { background: #f7fafc; border: 1px solid #cbd5e0; padding: 15px; margin: 15px 0; border-radius: 4px; }
        .score-label { color: #718096; font-size: 0.9em; }
        .score-value { color: #2d3748; font-size: 1.8em; font-weight: bold; }
        .report-section { margin: 30px 0; }
        .report-section h3 { color: #1a202c; border-bottom: 2px solid #cbd5e0; padding-bottom: 10px; }
        .footer { border-top: 1px solid #cbd5e0; margin-top: 40px; padding-top: 20px; font-size: 0.85em; color: #718096; }
        ul { margin: 10px 0; padding-left: 20px; }
        li { margin: 8px 0; }
        .confidential { background: #fff5f5; color: #742a2a; padding: 10px; margin: 20px 0; border-radius: 4px; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Your Burnout Assessment Report</h1>
            <p style="color: #718096; margin: 10px 0 0 0;">Confidential Assessment — ProQOL-5 Results</p>
        </div>

        <div class="confidential">
            ⚕️ <strong>This report is confidential.</strong> It has been generated specifically for your use and to support your wellbeing. Please keep it secure.
        </div>

        @if($assessment->overall_zone === 'green')
            <div class="zone-green">
                <strong>Overall Status: ✅ GREEN ZONE (Thriving)</strong>
                <p>You are maintaining healthy levels of compassion satisfaction with manageable burnout and secondary traumatic stress. You are coping well.</p>
            </div>
        @elseif($assessment->overall_zone === 'yellow')
            <div class="zone-yellow">
                <strong>Overall Status: ⚠️ YELLOW ZONE (Monitor)</strong>
                <p>One of your subscales shows some concern. This is a good time to implement preventive strategies and increase self-care.</p>
            </div>
        @elseif($assessment->overall_zone === 'orange')
            <div class="zone-orange">
                <strong>Overall Status: 🟠 ORANGE ZONE (Action Needed)</strong>
                <p>Your burnout or secondary traumatic stress is elevated. Professional support is strongly recommended.</p>
            </div>
        @else
            <div class="zone-red">
                <strong>Overall Status: 🔴 RED ZONE (Urgent Support Needed)</strong>
                <p>Both burnout and secondary traumatic stress are high. Please seek professional support immediately—therapy, supervision, or organizational accommodations are essential.</p>
            </div>
        @endif

        <div class="report-section">
            <h3>Your Scores</h3>
            <div class="score-box">
                <div class="score-label">Compassion Satisfaction (CS)</div>
                <div class="score-value">{{ $assessment->cs_score }}/50</div>
                <p style="margin: 8px 0 0 0; color: #718096;">{{ ucfirst($assessment->cs_zone) }} — How much satisfaction you find in helping others</p>
            </div>

            <div class="score-box">
                <div class="score-label">Burnout (BO)</div>
                <div class="score-value">{{ $assessment->bo_score }}/50</div>
                <p style="margin: 8px 0 0 0; color: #718096;">{{ ucfirst($assessment->bo_zone) }} — Your emotional exhaustion and depersonalization</p>
            </div>

            <div class="score-box">
                <div class="score-label">Secondary Traumatic Stress (STS)</div>
                <div class="score-value">{{ $assessment->sts_score }}/50</div>
                <p style="margin: 8px 0 0 0; color: #718096;">{{ ucfirst($assessment->sts_zone) }} — Your vicarious trauma from client exposure</p>
            </div>
        </div>

        <div class="report-section">
            <h3>Your Full Report</h3>
            <div style="background: #f7fafc; padding: 15px; border-radius: 4px; white-space: pre-wrap; font-family: 'Courier New', monospace; font-size: 0.9em; line-height: 1.8;">{{ $assessment->ai_report }}</div>
        </div>

        <div class="report-section">
            <h3>Next Steps</h3>
            <ul>
                @if($assessment->overall_zone === 'red' || $assessment->overall_zone === 'orange')
                    <li><strong>Seek professional support:</strong> Connect with a therapist, clinical supervisor, or occupational health specialist.</li>
                    <li><strong>Review your workload:</strong> Discuss with your manager or organization about reducing caseload or adjusting responsibilities.</li>
                    <li><strong>Access organizational support:</strong> Use your Employee Assistance Program (EAP) if available.</li>
                    <li><strong>Schedule regular check-ins:</strong> Plan follow-up burnout assessments in 3 months to track progress.</li>
                @else
                    <li><strong>Maintain self-care:</strong> Continue the strategies that are working well for you.</li>
                    <li><strong>Monitor your wellbeing:</strong> Re-take this assessment in 6 months to ensure you remain in a healthy zone.</li>
                    <li><strong>Support peers:</strong> Share resources and coping strategies with colleagues.</li>
                @endif
                <li><strong>Share with your supervisor:</strong> Consider sharing this report with your clinical supervisor or manager for organizational support.</li>
            </ul>
        </div>

        <div class="footer">
            <p><strong>About this assessment:</strong></p>
            <p>This ProQOL-5 (Professional Quality of Life Scale, Version 5) assessment is validated worldwide and used by healthcare organizations to identify burnout and secondary traumatic stress in helping professionals. Your results are confidential and for your use only.</p>

            <p><strong>Assessment Details:</strong><br>
            Professional Role: {{ $assessment->assessor_type }}<br>
            @if($assessment->specialization) Specialization: {{ $assessment->specialization }}<br> @endif
            @if($assessment->years_experience) Years of Experience: {{ $assessment->years_experience }}<br> @endif
            Generated: {{ $assessment->created_at->format('F j, Y') }}</p>

            <p style="margin-top: 30px;">
                Afya Yako Siri Yako — Mental Health Platform<br>
                <a href="https://uberhealth.co.ke" style="color: #3182ce;">uberhealth.co.ke</a><br>
                📧 info@uberhealth.co.ke
            </p>
        </div>
    </div>
</body>
</html>
