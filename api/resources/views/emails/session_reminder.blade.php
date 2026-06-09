<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Session Reminder</title>
<style>
  body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; background: #f9fafb; margin: 0; padding: 24px; }
  .card { background: white; border-radius: 16px; max-width: 560px; margin: 0 auto; padding: 40px; box-shadow: 0 1px 8px rgba(0,0,0,.08); }
  .logo { color: #0d9488; font-size: 20px; font-weight: 700; margin-bottom: 28px; }
  h1 { font-size: 22px; color: #111827; margin: 0 0 8px; }
  p { color: #4b5563; line-height: 1.6; margin: 0 0 16px; }
  .details { background: #f0fdfa; border: 1px solid #99f6e4; border-radius: 12px; padding: 16px 20px; margin: 20px 0; }
  .details dl { margin: 0; display: grid; grid-template-columns: max-content 1fr; gap: 6px 16px; }
  .details dt { color: #6b7280; font-size: 13px; }
  .details dd { color: #111827; font-size: 13px; font-weight: 600; margin: 0; }
  .btn { display: inline-block; background: #0d9488; color: white; text-decoration: none; padding: 14px 28px; border-radius: 10px; font-weight: 600; font-size: 15px; margin: 4px 0; }
  .crisis { background: #fef2f2; border: 1px solid #fecaca; border-radius: 10px; padding: 12px 16px; margin-top: 28px; font-size: 12px; color: #b91c1c; }
  .footer { text-align: center; color: #9ca3af; font-size: 12px; margin-top: 28px; }
</style>
</head>
<body>
<div class="card">
  <div class="logo">Afya Yako Siri Yako</div>
  <h1>Your session is in {{ $hoursUntil }}</h1>
  <p>Hi {{ $recipientName }}, this is a reminder for your upcoming mental health session.</p>

  <div class="details">
    <dl>
      <dt>Session ID</dt>
      <dd>{{ $consultation->consultation_id }}</dd>
      <dt>Date &amp; Time</dt>
      <dd>{{ \Carbon\Carbon::parse($consultation->scheduled_at)->setTimezone('Africa/Nairobi')->format('D, d M Y — h:i A') }} (EAT)</dd>
      <dt>Duration</dt>
      <dd>{{ $consultation->duration_minutes }} minutes</dd>
      @if(!$isProfessional)
      <dt>Therapist</dt>
      <dd>{{ $consultation->professional->user->display_name ?? 'Your therapist' }}</dd>
      @else
      <dt>Patient</dt>
      <dd>{{ $consultation->user->display_name ?? 'Your client' }}</dd>
      @endif
    </dl>
  </div>

  <p>When it is time, click the button below to join your private, encrypted video session:</p>
  <a href="{{ config('app.frontend_url', 'https://mhapke.com') }}/session/{{ $consultation->consultation_id }}" class="btn">
    Join Session
  </a>

  <p style="font-size:13px; color:#6b7280; margin-top:16px;">
    Joining from your phone? Open the link in Chrome or Firefox. The Jitsi Meet app may be prompted — it is free to install.
  </p>

  <div class="crisis">
    <strong>In crisis?</strong> Do not wait for your session. Call Befrienders Kenya <strong>0800 723 253</strong> (free, 24/7), NACADA <strong>1192</strong>, or go to your nearest emergency department.
  </div>

  <div class="footer">
    Afya Yako Siri Yako · mhapke.com<br>
    You received this because you have a session booked on our platform.<br>
    <a href="{{ config('app.frontend_url', 'https://mhapke.com') }}/profile" style="color:#0d9488;">Manage notifications</a>
  </div>
</div>
</body>
</html>
