<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate — {{ $certificate->trainee?->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Nunito', sans-serif;
            background: #f4f4f4;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 100vh;
            padding: 30px 20px;
        }
        .print-btn {
            margin-bottom: 20px;
            padding: 10px 28px;
            background: #C9A84C;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: 0.5px;
        }
        .print-btn:hover { background: #a88838; }

        .certificate {
            width: 900px;
            max-width: 100%;
            background: #fff;
            border: 12px solid #C9A84C;
            box-shadow: 0 8px 40px rgba(0,0,0,0.16);
            padding: 0;
            position: relative;
            overflow: hidden;
        }

        /* Top gold bar */
        .cert-top-bar {
            height: 10px;
            background: linear-gradient(90deg, #9a7d2c, #C9A84C, #9a7d2c);
        }

        /* Inner content */
        .cert-inner {
            padding: 44px 60px 40px;
            text-align: center;
            position: relative;
        }

        /* Corner ornaments */
        .cert-inner::before, .cert-inner::after {
            content: '';
            position: absolute;
            width: 60px;
            height: 60px;
            border: 3px solid #C9A84C;
            opacity: 0.3;
        }
        .cert-inner::before { top: 16px; left: 16px; border-right: none; border-bottom: none; }
        .cert-inner::after  { bottom: 16px; right: 16px; border-left: none; border-top: none; }

        .cert-logo {
            width: 72px;
            height: 72px;
            margin: 0 auto 10px;
        }

        .cert-org {
            font-size: 13px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #2d3748;
            margin-bottom: 6px;
        }

        .cert-divider {
            width: 80px;
            height: 2px;
            background: linear-gradient(90deg, transparent, #C9A84C, transparent);
            margin: 12px auto;
        }

        .cert-heading {
            font-family: 'Playfair Display', serif;
            font-size: 38px;
            font-weight: 700;
            color: #2d3748;
            margin: 6px 0;
            letter-spacing: 1px;
        }

        .cert-subtitle {
            font-size: 13px;
            color: #888;
            margin-bottom: 22px;
            letter-spacing: 0.5px;
        }

        .cert-body-text {
            font-size: 15px;
            color: #555;
            margin: 10px 0;
            letter-spacing: 0.3px;
        }

        .cert-name {
            font-family: 'Playfair Display', serif;
            font-size: 36px;
            font-weight: 700;
            color: #C9A84C;
            margin: 8px 0 6px;
            letter-spacing: 1px;
            text-decoration: underline;
            text-decoration-color: #C9A84C44;
            text-underline-offset: 4px;
        }

        .cert-course {
            font-size: 16px;
            font-weight: 700;
            color: #2d3748;
            margin: 6px 0;
        }

        .cert-event {
            font-size: 14px;
            color: #555;
        }

        .cert-venue-date {
            font-size: 13px;
            color: #888;
            margin-top: 4px;
            margin-bottom: 28px;
        }

        /* Signatures */
        .cert-sigs {
            display: flex;
            justify-content: space-around;
            align-items: flex-end;
            margin-top: 10px;
            gap: 40px;
        }
        .sig-block {
            flex: 1;
            text-align: center;
        }
        .sig-block img {
            max-height: 60px;
            max-width: 150px;
            margin-bottom: 4px;
        }
        .sig-line {
            border-top: 1.5px solid #2d3748;
            margin: 6px auto 4px;
            width: 80%;
        }
        .sig-name {
            font-weight: 700;
            font-size: 12px;
            color: #2d3748;
        }
        .sig-title {
            font-size: 11px;
            color: #888;
        }

        .cert-footer {
            margin-top: 24px;
            font-size: 11px;
            color: #bbb;
            letter-spacing: 0.5px;
        }

        /* Print styles */
        @media print {
            body { background: #fff; padding: 0; }
            .print-btn { display: none; }
            .certificate {
                width: 100%;
                box-shadow: none;
                border-width: 8px;
            }
            .cert-inner { padding: 32px 48px 32px; }
        }

        @page { size: landscape; margin: 1cm; }
    </style>
</head>
<body>

<button class="print-btn" onclick="window.print()">
    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px; vertical-align:middle;"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
    Print / Download as PDF
</button>

<div class="certificate">
    <div class="cert-top-bar"></div>
    <div class="cert-inner">
        {{-- Logo --}}
        @if($certificate->logo_path)
            <img src="{{ asset('storage/' . $certificate->logo_path) }}" alt="Logo" class="cert-logo" style="object-fit:contain;">
        @else
            <img src="{{ asset('img/cosecsa-favicon.png') }}" alt="COSECSA" class="cert-logo">
        @endif

        <div class="cert-org">{{ $certificate->org_name ?? 'College of Surgeons of East, Central & Southern Africa' }}</div>
        <div class="cert-divider"></div>

        <div class="cert-heading">Certificate of Completion</div>
        <div class="cert-subtitle">This is to certify that</div>

        <div class="cert-name">{{ $certificate->trainee?->name ?? '&mdash;' }}</div>

        <div class="cert-body-text">has successfully completed the</div>

        <div class="cert-course">{{ $certificate->course_name }}</div>

        <div class="cert-body-text" style="margin-top:10px;">Held at the</div>

        <div class="cert-event" style="font-weight:700;">{{ $certificate->event_name }}</div>

        <div class="cert-venue-date">
            {{ $certificate->venue ? $certificate->venue . ' &bull; ' : '' }}{{ $certificate->event_date }}
        </div>

        <div class="cert-divider"></div>

        {{-- Signatures --}}
        <div class="cert-sigs">
            @if($certificate->sig1_name || $certificate->sig1_path)
            <div class="sig-block">
                @if($certificate->sig1_path)
                <img src="{{ asset('storage/' . $certificate->sig1_path) }}" alt="Signature 1">
                @endif
                <div class="sig-line"></div>
                <div class="sig-name">{{ $certificate->sig1_name }}</div>
                @if($certificate->sig1_title)
                <div class="sig-title">{{ $certificate->sig1_title }}</div>
                @endif
            </div>
            @endif

            @if($certificate->sig2_name || $certificate->sig2_path)
            <div class="sig-block">
                @if($certificate->sig2_path)
                <img src="{{ asset('storage/' . $certificate->sig2_path) }}" alt="Signature 2">
                @endif
                <div class="sig-line"></div>
                <div class="sig-name">{{ $certificate->sig2_name }}</div>
                @if($certificate->sig2_title)
                <div class="sig-title">{{ $certificate->sig2_title }}</div>
                @endif
            </div>
            @endif
        </div>

        {{-- Stamp / Seal --}}
        @if($certificate->stamp_path)
        <div style="margin-top:18px;">
            <img src="{{ asset('storage/' . $certificate->stamp_path) }}" alt="Official Stamp" style="max-height:80px; max-width:80px; opacity:0.85;">
        </div>
        @endif

        <div class="cert-footer">{{ $certificate->org_name ?? 'COSECSA' }} &copy; {{ date('Y') }} &mdash; Research Training System</div>
    </div>
</div>

</body>
</html>
