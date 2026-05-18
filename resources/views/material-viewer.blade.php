<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $material->title }} — Viewer</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Nunito', sans-serif; box-sizing: border-box; }
        html, body { margin: 0; padding: 0; height: 100vh; overflow: hidden; background: #f0f2f5; }

        .viewer-shell {
            display: flex;
            height: 100vh;
        }

        /* ── Sidebar ── */
        .v-sidebar {
            width: 260px;
            min-width: 260px;
            background: #fff;
            border-right: 1px solid #e9ecef;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .v-sidebar-head {
            background: #C9A84C;
            color: #fff;
            padding: 16px;
            flex-shrink: 0;
        }
        .v-sidebar-head .type-badge {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .6px;
            background: rgba(255,255,255,.25);
            border-radius: 10px;
            padding: 2px 10px;
            display: inline-block;
            margin-bottom: 8px;
        }
        .v-sidebar-head h6 {
            font-size: 13.5px;
            font-weight: 700;
            margin: 0;
            line-height: 1.4;
        }
        .v-sidebar-body {
            padding: 14px;
            flex: 1;
            overflow-y: auto;
        }
        .meta-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: .5px;
            color: #aaa;
            display: block;
            margin-bottom: 4px;
        }
        .meta-row { margin-bottom: 14px; }
        .meta-value { font-size: 13px; color: #444; }
        .session-chip {
            display: flex;
            align-items: flex-start;
            gap: 8px;
            padding: 7px 10px;
            border-radius: 6px;
            background: #f9f9f9;
            border: 1px solid #eee;
            margin-bottom: 6px;
        }
        .day-dot {
            width: 22px; min-width: 22px; height: 22px;
            border-radius: 50%;
            background: #C9A84C;
            color: #fff;
            font-size: 9px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 1px;
        }
        .v-sidebar-foot {
            padding: 12px;
            border-top: 1px solid #f0f0f0;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        /* ── Main viewer ── */
        .v-main {
            flex: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
            background: #f0f2f5;
        }
        .v-topbar {
            background: #fff;
            border-bottom: 1px solid #e9ecef;
            padding: 0 16px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-shrink: 0;
        }
        .v-topbar .v-title { font-size: 14px; font-weight: 700; color: #2d3748; }
        .v-body {
            flex: 1;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* PDF */
        .viewer-frame { flex: 1; border: none; width: 100%; height: 100%; display: block; }

        /* Video */
        .video-wrap {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #111;
            padding: 20px;
        }
        .video-wrap video {
            max-width: 100%;
            max-height: calc(100vh - 100px);
            border-radius: 6px;
            box-shadow: 0 8px 32px rgba(0,0,0,.5);
        }

        /* PPTX */
        #slide-nav {
            background: #2d3748;
            padding: 8px 16px;
            display: none;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-shrink: 0;
            color: #ccc;
            font-size: 13px;
        }
        #slide-nav button {
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.2);
            color: #fff;
            border-radius: 4px;
            padding: 4px 12px;
            cursor: pointer;
            font-size: 12px;
            font-family: 'Nunito', sans-serif;
        }
        #slide-nav button:hover { background: rgba(255,255,255,.2); }
        #slide-nav button:disabled { opacity:.4; cursor:default; }
        #pptx-container {
            flex: 1;
            overflow: auto;
            background: #555;
            padding: 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
        }
        .pptx-loading {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px;
            color: #ccc;
            gap: 14px;
            width: 100%;
        }
        .pptx-loading .spinner {
            width: 40px; height: 40px;
            border: 4px solid rgba(255,255,255,.2);
            border-top-color: #C9A84C;
            border-radius: 50%;
            animation: spin .8s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }
        .pptx-error {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px;
            color: #ccc;
            gap: 12px;
            width: 100%;
            text-align: center;
        }

        /* No file */
        .nofile-wrap {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }
        .nofile-card {
            background: #fff;
            border-radius: 12px;
            border: 1px solid #e0e0e0;
            text-align: center;
            padding: 48px 40px;
            max-width: 400px;
        }

        .btn-gold { background: #C9A84C; color: #fff; border: none; font-weight: 600; }
        .btn-gold:hover { background: #b8963c; color: #fff; }
    </style>
</head>
<body>

@php
    $fileUrlEncoded = $fileUrl ? implode('/', array_map('rawurlencode', explode('/', $fileUrl))) : null;
    $backUrl = url()->previous();
@endphp

<div class="viewer-shell">

    {{-- Sidebar --}}
    <div class="v-sidebar">
        <div class="v-sidebar-head">
            <div class="type-badge">
                @if($material->type === 'video') <i class="fas fa-video mr-1"></i>Video
                @elseif($material->type === 'presentation') <i class="fas fa-file-powerpoint mr-1"></i>Presentation
                @else <i class="fas fa-file-pdf mr-1"></i>Document
                @endif
            </div>
            <h6>{{ $material->title }}</h6>
        </div>

        <div class="v-sidebar-body">
            @if($material->category)
            <div class="meta-row">
                <span class="meta-label">Category</span>
                <span style="background:#fef3cd; color:#856404; border-radius:4px; padding:2px 8px; font-size:12px; font-weight:600; display:inline-block;">
                    {{ $material->category }}
                </span>
            </div>
            @endif

            @if($material->facilitator)
            <div class="meta-row">
                <span class="meta-label">Facilitator</span>
                <span class="meta-value">
                    <i class="fas fa-user-tie mr-1" style="color:#C9A84C; font-size:11px;"></i>
                    {{ $material->facilitator->name }}
                </span>
            </div>
            @endif

            @if($material->description)
            <div class="meta-row">
                <span class="meta-label">About</span>
                <span class="meta-value" style="font-size:12.5px; color:#666; line-height:1.5;">{{ $material->description }}</span>
            </div>
            @endif

            @if($material->schedules->isNotEmpty())
            <div class="meta-row">
                <span class="meta-label" style="margin-bottom:8px;">Used in Sessions</span>
                @foreach($material->schedules->sortBy('day_number') as $session)
                <div class="session-chip">
                    <div class="day-dot">D{{ $session->day_number }}</div>
                    <div>
                        <div style="font-size:12px; font-weight:600; color:#2d2d2d; line-height:1.3;">{{ $session->title }}</div>
                        <div style="font-size:11px; color:#aaa; margin-top:1px;">
                            {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}
                            @if($session->end_time) – {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}@endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="v-sidebar-foot">
            @if($fileUrl)
            <a href="{{ $fileUrlEncoded }}" download class="btn btn-sm btn-gold w-100">
                <i class="fas fa-download mr-1"></i> Download
            </a>
            <a href="{{ $fileUrlEncoded }}" target="_blank" class="btn btn-sm btn-outline-secondary w-100">
                <i class="fas fa-external-link-alt mr-1"></i> Open in New Tab
            </a>
            @endif
            <a href="{{ $backUrl }}" class="btn btn-sm btn-light w-100">
                <i class="fas fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </div>

    {{-- Main viewer --}}
    <div class="v-main">
        <div class="v-topbar">
            <span class="v-title"><i class="fas fa-eye mr-2" style="color:#C9A84C;"></i>{{ $material->title }}</span>
            <div style="display:flex; gap:6px; align-items:center;">
                @if($fileUrl)
                <a href="{{ $fileUrlEncoded }}" download class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-download mr-1"></i> Download
                </a>
                @endif
                <button id="btn-fullscreen" class="btn btn-sm btn-outline-secondary">
                    <i class="fas fa-expand-alt mr-1"></i> Full screen
                </button>
            </div>
        </div>

        <div class="v-body">
            @if(!$fileUrl)
                <div class="nofile-wrap">
                    <div class="nofile-card">
                        <i class="fas fa-folder-open" style="font-size:48px; color:#ddd; display:block; margin-bottom:16px;"></i>
                        <h5 style="font-weight:700; color:#2d3748;">No file available</h5>
                        <p style="color:#888; font-size:13px;">This material does not have a file attached yet.</p>
                    </div>
                </div>

            @elseif($material->type === 'document')
                <iframe class="viewer-frame" src="{{ $fileUrlEncoded }}#toolbar=1&view=FitH" title="{{ $material->title }}"></iframe>

            @elseif($material->type === 'video')
                <div class="video-wrap">
                    <video controls preload="metadata">
                        <source src="{{ $fileUrlEncoded }}" type="video/mp4">
                        <source src="{{ $fileUrlEncoded }}" type="video/quicktime">
                        <source src="{{ $fileUrlEncoded }}" type="video/x-m4v">
                        <source src="{{ $fileUrlEncoded }}" type="video/webm">
                        <p style="color:#ccc; text-align:center;">
                            Your browser cannot play this video inline.<br>
                            <a href="{{ $fileUrlEncoded }}" class="btn btn-gold btn-sm mt-2">Download to view</a>
                        </p>
                    </video>
                </div>

            @elseif($material->type === 'presentation')
                <div id="slide-nav">
                    <button id="btn-prev" disabled>&#8592; Prev</button>
                    <span id="slide-counter">Loading…</span>
                    <button id="btn-next">Next &#8594;</button>
                </div>
                <div id="pptx-container">
                    <div class="pptx-loading" id="pptx-loading">
                        <div class="spinner"></div>
                        <span>Rendering slides…</span>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>

@if($material->type === 'presentation')
<script>
$(function () {
    var renderUrl = "{{ route('material.render-slides', $material->id) }}";
    var fileUrl   = "{{ $fileUrlEncoded }}";
    var slides = [], current = 0;

    function showSlide(idx) {
        $('#pptx-container .pptx-slide').hide();
        $('#pptx-container .pptx-slide[data-slide="' + (idx+1) + '"]').show();
        current = idx;
        $('#slide-counter').text('Slide ' + (idx+1) + ' / ' + slides.length);
        $('#btn-prev').prop('disabled', idx === 0);
        $('#btn-next').prop('disabled', idx === slides.length - 1);
        $('#pptx-container').scrollTop(0);
    }

    function showError(msg) {
        var isProtected = msg.indexOf('Package not found') !== -1 || msg.indexOf('magic number') !== -1 || msg.indexOf('decompressing') !== -1 || msg.indexOf('protected') !== -1;
        var friendly = isProtected
            ? 'This presentation appears to be <strong>password-protected or in an incompatible format</strong>. Please open it in PowerPoint/LibreOffice directly.'
            : msg;
        $('#pptx-loading').remove();
        $('#pptx-container').html(
            '<div class="pptx-error">' +
            '<i class="fas fa-lock" style="font-size:36px; color:#e67e22;"></i>' +
            '<p style="color:#ccc; font-size:14px; margin-top:10px; max-width:380px; text-align:center; line-height:1.6;">' + friendly + '</p>' +
            (fileUrl ? '<a href="' + fileUrl + '" download class="btn btn-gold btn-sm mt-2"><i class="fas fa-download mr-1"></i>Download File</a>' : '') +
            '</div>'
        );
    }

    $.ajax({
        url: renderUrl, method: 'GET', timeout: 30000,
        success: function(data) {
            if (data.error) { showError(data.error); return; }
            if (!data.slides || !data.slides.length) { showError('No slides found in this presentation.'); return; }
            slides = data.slides;
            $('#pptx-loading').remove();
            var $c = $('#pptx-container').css({'align-items':'center','justify-content':'center'});
            slides.forEach(function(html, i) {
                var $s = $(html);
                if (i !== 0) $s.hide();
                $s.css({'box-shadow':'0 6px 24px rgba(0,0,0,.5)','border-radius':'4px','margin':'auto'});
                $c.append($s);
            });
            if (slides.length > 1) {
                $('#slide-nav').css('display','flex');
                $('#btn-prev').on('click', function() { if (current > 0) showSlide(current-1); });
                $('#btn-next').on('click', function() { if (current < slides.length-1) showSlide(current+1); });
            }
            $('#slide-counter').text('Slide 1 / ' + slides.length);
            $('#btn-next').prop('disabled', slides.length <= 1);
            $(document).on('keydown', function(e) {
                if (e.key === 'ArrowRight' || e.key === 'ArrowDown') { if (current < slides.length-1) showSlide(current+1); }
                else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') { if (current > 0) showSlide(current-1); }
            });
        },
        error: function() { showError('Could not load slides. Please download to view.'); }
    });
});
</script>
@endif

<script>
$(function() {
    var $btn = $('#btn-fullscreen');
    var target = document.querySelector('.v-main');
    $btn.on('click', function() {
        var isFS = !!(document.fullscreenElement || document.webkitFullscreenElement);
        if (isFS) { (document.exitFullscreen || document.webkitExitFullscreen).call(document); }
        else { (target.requestFullscreen || target.webkitRequestFullscreen).call(target); }
    });
    $(document).on('fullscreenchange webkitfullscreenchange', function() {
        var isFS = !!(document.fullscreenElement || document.webkitFullscreenElement);
        $btn.html(isFS ? '<i class="fas fa-compress-alt mr-1"></i> Exit full screen' : '<i class="fas fa-expand-alt mr-1"></i> Full screen');
    });
});
</script>
</body>
</html>
