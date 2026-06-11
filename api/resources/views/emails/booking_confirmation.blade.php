<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Session Booked</title>
<style>
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 24px; }
  .card { background: white; border-radius: 16px; max-width: 560px; margin: 0 auto; padding: 40px; box-shadow: 0 1px 8px rgba(0,0,0,.08); }
  .logo { color: #0d9488; font-size: 20px; font-weight: 700; margin-bottom: 28px; }
  .badge { display: inline-block; background: #f0fdfa; color: #0d9488; border: 1px solid #99f6e4; border-radius: 20px; padding: 4px 14px; font-size: 13px; font-weight: 600; margin-bottom: 20px; }
  h1 { font-size: 22px; color: #111827; margin: 0 0 8px; }
  p { color: #4b5563; line-height: 1.6; margin: 0 0 16px; }
  .details { background: #f0fdfa; border: 1px solid #99f6e4; border-radius: 12px; padding: 16px 20px; margin: 20px 0; }
  .details dl { margin: 0; display: grid; grid-template-columns: max-content 1fr; gap: 6px 16px; }
  .details dt { color: #6b7280; font-size: 13px; }
  .details dd { color: #111827; font-size: 13px; font-weight: 600; margin: 0; }
  .btn { display: inline-block; background: #0d9488; color: white; text-decoration: none; padding: 14px 28px; border-radius: 10px; font-weight: 600; font-size: 15px; margin: 4px 0; }
  .next-steps { background: #fafafa; border: 1px solid #e5e7eb; border-radius: 10px; padding: 16px 20px; margin: 20px 0; }
  .next-steps ol { margin: 8px 0 0; padding-left: 20px; color: #4b5563; font-size: 13px; line-height: 2; }
  .crisis { background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 12px 16px; margin-top: 28px; font-size: 12px; color: #b91c1c; }
  .footer { text-align: center; color: #9ca3af; font-size: 12px; margin-top: 28px; }
</style>
</head>
<body>
<div class="card">
  <div class="logo">Afya Yako Siri Yako</div>
  <div class="badge">✓ Session Confirmed</div>
  <h1>Your session is booked, {{ $recipientName }}!</h1>
  <p>
    @if($isProfessional)
      A patient has booked a session with you. Please be available at the scheduled time.
    @else
      You're all set. Your session has been successfully booked with your mental health professional.
    @endif
  </p>

  <div class="details">
    <dl>
      <dt>Session ID</dt>
      <dd>{{ $consultation->consultation_id }}</dd>
      <dt>Date &amp; Time</dt>
      <dd>{{ \Carbon\Carbon::parse($consultation->scheduled_at)->setTimezone('Africa/Nairobi')->format('l, d M Y — h:i A') }} (EAT)</dd>
      <dt>Duration</dt>
      <dd>{{ $consultation->duration_minutes }} minutes</dd>
      @if(!$isProfessional)
      <dt>Therapist</dt>
      <dd>{{ $consultation->professional->user->display_name ?? 'Your therapist' }}</dd>
      @else
      <dt>Patient</dt>
      <dd>{{ $consultation->user->display_name ?? 'Your patient' }}</dd>
      @endif
      <dt>Format</dt>
      <dd>Private encrypted video call</dd>
    </dl>
  </div>

  <div class="next-steps">
    <strong style="font-size:13px; color:#111827;">What to do next:</strong>
    <ol>
      <li>Add this session to your calendar — you will also receive a reminder 24 hours and 1 hour before.</li>
      <li>At session time, open the link below or go to <strong>My Sessions</strong> on the platform.</li>
      <li>Ensure you are in a private, quiet place with a stable internet connection.</li>
    </ol>
  </div>

  <p>When it is time, click the button below to join your encrypted video session:</p>
  <a href="{{ config('app.frontend_url', 'https://mhapke.com') }}/session/{{ $consultation->consultation_id }}" class="btn">
    Join Session
  </a>

  <div class="crisis">
    <strong>In crisis before your session?</strong> Do not wait. Call Befrienders Kenya <strong>0800 723 253</strong> (free, 24/7) or NACADA <strong>1192</strong>, or visit the nearest emergency department.
  </div>

  <div class="footer">
    Afya Yako Siri Yako · mhapke.com<br>
    This email confirms your booking. Your session link is private — do not share it.<br>
    <a href="{{ config('app.frontend_url', 'https://mhapke.com') }}/profile" style="color:#0d9488;">Manage notifications</a>
  </div>
</div>
</body>
</html>
