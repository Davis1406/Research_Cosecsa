<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate — {{ $certificate->trainee?->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400;1,700&family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
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
            .no-print { display: none !important; }
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

@php
    $pngName = $certificate->trainee?->name ? preg_replace('/[^A-Za-z0-9]+/', '-', $certificate->trainee->name) : 'Certificate';
    $design  = in_array(request('design'), ['classic', 'centered', 'slide'], true) ? request('design') : 'classic';
    $isCentered = in_array($design, ['centered', 'slide'], true);
    $isSlide    = $design === 'slide';
@endphp

<div style="display:flex; gap:12px; margin-bottom:20px; align-items:center; flex-wrap:wrap; justify-content:space-between; width:100%; max-width:900px;">
    <div style="display:flex; gap:12px;">
        <button class="print-btn" style="margin-bottom:0;" onclick="window.print()">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px; vertical-align:middle;"><polyline points="6 9 6 2 18 2 18 9"></polyline><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path><rect x="6" y="14" width="12" height="8"></rect></svg>
            Print / Download as PDF
        </button>
        <button class="print-btn" id="png-btn" style="margin-bottom:0;" onclick="downloadPng()">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right:6px; vertical-align:middle;"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg>
            Download as PNG
        </button>
    </div>
    <div class="no-print" style="font-size:13px; color:#888;">
        Design:
        <a href="?design=classic" style="text-decoration:none; font-weight:700; color:{{ $design === 'classic' ? '#a02626' : '#999' }};">Classic</a>
        <span style="color:#ccc;">|</span>
        <a href="?design=centered" style="text-decoration:none; font-weight:700; color:{{ $design === 'centered' ? '#a02626' : '#999' }};">Centered</a>
        <span style="color:#ccc;">|</span>
        <a href="?design=slide" style="text-decoration:none; font-weight:700; color:{{ $design === 'slide' ? '#a02626' : '#999' }};">Slide 16:9</a>
    </div>
</div>

<div style="width:100%; overflow-x:auto; -webkit-overflow-scrolling:touch;">
<div class="certificate {{ $isSlide ? 'certificate--slide' : '' }}" style="margin:0 auto;">
    <div class="cert-top-bar"></div>
    <div class="cert-inner">
        @if($isCentered)
        {{-- Centered design: logo on top, then the COSECSA heading --}}
        <div class="cert-header-center">
            @if($certificate->logo_path)
                <img src="{{ asset('storage/' . $certificate->logo_path) }}" alt="Logo">
            @else
                <img src="{{ asset('img/cosecsa-logo.png') }}" alt="COSECSA">
            @endif
            <div class="cert-org-center">{{ $certificate->org_name ?? 'College of Surgeons of East, Central & Southern Africa' }}</div>
            @if($certificate->logo2_path || $certificate->logo3_path)
            <div class="cert-header-partners">
                @if($certificate->logo3_path)<img src="{{ asset('storage/' . $certificate->logo3_path) }}" alt="Logo 3">@endif
                @if($certificate->logo2_path)<img src="{{ asset('storage/' . $certificate->logo2_path) }}" alt="Logo 2">@endif
            </div>
            @endif
        </div>
        @else
        {{-- Classic design: logo on the left, heading beside it --}}
        <div class="cert-logos">
            <div class="logo-left">
                @if($certificate->logo_path)
                    <img src="{{ asset('storage/' . $certificate->logo_path) }}" alt="Logo">
                @else
                    <img src="{{ asset('img/cosecsa-logo.png') }}" alt="COSECSA">
                @endif
                <div class="cert-org">{{ $certificate->org_name ?? 'College of Surgeons of East, Central & Southern Africa' }}</div>
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
                    {{-- Small spacer so the heading extends almost to the right edge --}}
                    <div style="width:24px;"></div>
                @endif
            </div>
        </div>
        @endif

        <div class="cert-divider"></div>

        <div class="cert-heading">Certificate of Completion</div>
        <div class="cert-subtitle">This is to certify that</div>

        <div class="cert-name">{{ $certificate->trainee?->name ?? '&mdash;' }}</div>

        <div class="cert-name-rule"></div>

        <div class="cert-body-text">has successfully completed the</div>

        <div class="cert-course">{{ $certificate->course_name }}</div>

        {{-- "Held in [city]" — only for the physical workshop (online has no venue line) --}}
        @if(($certificate->course_type ?? 'physical') === 'physical')
        <div class="cert-body-text" style="margin-top:10px;">Held in</div>
        @endif

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

        {{-- Bottom row below the signatures: COSECSA gold CPD points badge (left) + verification QR (right) --}}
        <div class="cert-bottom-row">
            @if($certificate->cpd_points)
            <div class="cert-cpd">
                <div class="cert-cpd-badge">
                    <div class="cert-cpd-value">{{ $certificate->cpd_points }}</div>
                    <div class="cert-cpd-bottom">CPD Points</div>
                </div>
            </div>
            @endif

            @if($qr = certificate_verification_qr($certificate))
            <div class="cert-verify">
                <img src="{{ $qr }}" alt="Scan to verify">
                <div class="cert-verify-label">Scan to verify</div>
            </div>
            @endif
        </div>
    </div>
</div>
</div>

</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
// Pre-render the certificate in the background (after fonts/images load) so
// the Download PNG button responds instantly.
var previewCanvasCache = null;
window.addEventListener('load', function () {
    Promise.resolve((typeof document.fonts !== 'undefined') ? document.fonts.ready : true).then(function () {
        setTimeout(function () {
            html2canvas(document.querySelector('.certificate'), {
                scale: 2,
                useCORS: true,
                backgroundColor: '#ffffff',
                logging: false
            }).then(function (canvas) {
                previewCanvasCache = canvas;
            });
        }, 200);
    });
});

function downloadPng() {
    var btn = document.getElementById('png-btn');
    var original = btn.textContent;
    var finish = function (canvas) {
        var a = document.createElement('a');
        a.download = 'COSECSA-Certificate-{{ $pngName }}.png';
        a.href = canvas.toDataURL('image/png');
        document.body.appendChild(a);
        a.click();
        a.remove();
        btn.disabled = false;
        btn.textContent = original;
    };

    btn.disabled = true;
    btn.textContent = 'Rendering…';
    if (previewCanvasCache) {
        finish(previewCanvasCache);
        return;
    }
    html2canvas(document.querySelector('.certificate'), {
        scale: 2,
        useCORS: true,
        backgroundColor: '#ffffff',
        logging: false
    }).then(finish).catch(function () {
        alert('Sorry, the PNG could not be generated.');
        btn.disabled = false;
        btn.textContent = original;
    });
}
</script>
</html>
