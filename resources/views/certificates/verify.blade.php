<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>COSECSA Verified — Certificate {{ $certificate->verification_token ? substr($certificate->verification_token, 0, 8) : '' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Inter', system-ui, sans-serif; }
        body { background: #f0f2f5; min-height: 100vh; display: flex; flex-direction: column; align-items: center; padding: 40px 16px; }
        .verify-card { background: #fff; border-radius: 14px; box-shadow: 0 6px 30px rgba(0,0,0,0.10); width: 100%; max-width: 560px; overflow: hidden; }
        .verify-top { background: #a02626; padding: 30px 24px 24px; text-align: center; }
        .verify-top img { width: 78px; height: 78px; border-radius: 50%; object-fit: cover; border: 3px solid #C9A84C; box-shadow: 0 2px 12px rgba(0,0,0,0.3); }
        .verify-top .org { color: #fff; font-size: 18px; font-weight: 800; margin-top: 12px; letter-spacing: 0.3px; }
        .verify-top .sub { color: rgba(255,255,255,0.78); font-size: 11px; margin-top: 3px; text-transform: uppercase; letter-spacing: 1.2px; }
        .verify-body { padding: 30px 28px 26px; text-align: center; }
        .verify-badge { display: inline-flex; align-items: center; gap: 10px; background: #eafaf1; border: 1.5px solid #34c77b; color: #1c8a50; border-radius: 30px; padding: 8px 18px; font-weight: 700; font-size: 14px; }
        .verify-badge .dot { width: 9px; height: 9px; border-radius: 50%; background: #34c77b; }
        .verify-heading { font-size: 22px; font-weight: 800; color: #1a202c; margin: 18px 0 6px; }
        .verify-lede { font-size: 14px; color: #666; max-width: 420px; margin: 0 auto 24px; line-height: 1.6; }
        .verify-details { background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 10px; padding: 18px 22px; text-align: left; }
        .verify-row { display: flex; justify-content: space-between; gap: 16px; padding: 9px 0; border-bottom: 1px dashed #e5e7eb; }
        .verify-row:last-child { border-bottom: none; }
        .verify-row .k { font-size: 12px; font-weight: 700; color: #999; text-transform: uppercase; letter-spacing: 0.5px; }
        .verify-row .v { font-size: 14px; font-weight: 600; color: #2d3748; text-align: right; }
        .verify-note { margin-top: 20px; font-size: 12px; color: #aaa; line-height: 1.6; }
        .verify-note strong { color: #888; }
        .verify-footer { border-top: 1px solid #f0f0f0; background: #fafafa; padding: 14px 28px 18px; text-align: center; font-size: 11px; color: #bbb; }
        .verify-footer a { color: #a02626; text-decoration: none; font-weight: 600; }
    </style>
</head>
<body>

<div class="verify-card">
    <div class="verify-top">
        <img src="{{ asset('img/cosecsa-logo.png') }}" alt="COSECSA">
        <div class="org">College of Surgeons of East, Central &amp; Southern Africa</div>
        <div class="sub">COSECSA Research Training System</div>
    </div>

    <div class="verify-body">
        <span class="verify-badge"><span class="dot"></span> Certificate Verified</span>

        <h1 class="verify-heading">This certificate is genuine</h1>
        <p class="verify-lede">
            The certificate below was issued by COSECSA for its Research Methodology
            training. Scanning this QR code confirms the certificate exists on our
            records and has not been altered.
        </p>

        <div class="verify-details">
            <div class="verify-row">
                <span class="k">Issued to</span>
                <span class="v">{{ $certificate->trainee?->name ?? '—' }}</span>
            </div>
            <div class="verify-row">
                <span class="k">Course</span>
                <span class="v">{{ $certificate->course_name }}</span>
            </div>
            <div class="verify-row">
                <span class="k">Course type</span>
                <span class="v">{{ ucfirst($certificate->course_type ?? 'physical') }}</span>
            </div>
            <div class="verify-row">
                <span class="k">Event</span>
                <span class="v">{{ $certificate->event_name }}</span>
            </div>
            @if($certificate->venue)
            <div class="verify-row">
                <span class="k">Venue</span>
                <span class="v">{{ $certificate->venue }}</span>
            </div>
            @endif
            <div class="verify-row">
                <span class="k">Event date</span>
                <span class="v">{{ $certificate->event_date }}</span>
            </div>
            <div class="verify-row">
                <span class="k">Issued on</span>
                <span class="v">{{ $certificate->generated_at?->format('F j, Y') }}</span>
            </div>
            <div class="verify-row">
                <span class="k">Certificate ID</span>
                <span class="v" style="font-family:ui-monospace,monospace; font-size:12px;">{{ $certificate->verification_token ? substr($certificate->verification_token, 0, 12) . '…' : '—' }}</span>
            </div>
        </div>

        <p class="verify-note">
            If you received this certificate by any other means, please verify its
            authenticity by scanning the QR code printed on the certificate.
            <strong>COSECSA does not charge for certificate verification.</strong>
        </p>
    </div>

    <div class="verify-footer">
        &copy; {{ date('Y') }} College of Surgeons of East, Central &amp; Southern Africa (COSECSA) &middot;
        <a href="https://www.cosecsa.org" rel="noopener">cosecsa.org</a>
    </div>
</div>

</body>
</html>