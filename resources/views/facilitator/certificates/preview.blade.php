<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate — {{ $certificate->trainee?->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <link href="{{ asset('css/certificate.css') }}" rel="stylesheet">
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
            background: #a02626;
            color: #fff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 700;
            cursor: pointer;
            letter-spacing: 0.5px;
        }
        .print-btn:hover { background: #7e1e1e; }

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
        {{-- Logos: left logo | optional centre logo | right logo --}}
        <div class="cert-logos">
            <div class="logo-left">
                @if($certificate->logo_path)
                    <img src="{{ asset('storage/' . $certificate->logo_path) }}" alt="Logo">
                @else
                    <img src="{{ asset('img/cosecsa-logo.png') }}" alt="COSECSA">
                @endif
            </div>
            @if($certificate->logo3_path)
            <div class="logo-center">
                <img src="{{ asset('storage/' . $certificate->logo3_path) }}" alt="Logo 3">
            </div>
            @endif
            <div class="logo-right">
                @if($certificate->logo2_path)
                    <img src="{{ asset('storage/' . $certificate->logo2_path) }}" alt="Logo 2">
                @else
                    {{-- Placeholder space to keep layout balanced when no second logo --}}
                    <div style="width:130px;"></div>
                @endif
            </div>
        </div>

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
            {{ $certificate->venue ? $certificate->venue . ' • ' : '' }}{{ $certificate->event_date }}
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

        <div class="cert-footer">COSECSA &copy; {{ date('Y') }}</div>
    </div>
</div>

</body>
</html>
