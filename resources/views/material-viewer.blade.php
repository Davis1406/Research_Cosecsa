<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $material->title }} — Viewer</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', system-ui, -apple-system, sans-serif; box-sizing: border-box; }
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

        /* YouTube */
        .youtube-wrap {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #111;
            padding: 20px;
        }
        .youtube-wrap iframe {
            width: 100%;
            max-width: 960px;
            height: 540px;
            border: none;
            border-radius: 6px;
            box-shadow: 0 8px 32px rgba(0,0,0,.5);
        }
        @media (max-width: 768px) {
            .youtube-wrap iframe { height: 240px; }
        }

        /* Audio */
        .audio-wrap {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            background: #f0f2f5;
            padding: 40px 20px;
            gap: 20px;
        }
        .audio-card {
            background: #fff;
            border-radius: 14px;
            padding: 36px 40px;
            box-shadow: 0 4px 20px rgba(0,0,0,.10);
            text-align: center;
            max-width: 480px;
            width: 100%;
        }
        .audio-card audio {
            width: 100%;
            margin-top: 16px;
            border-radius: 6px;
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

    // Detect by extension so PPTX stored as type='document' still gets Office Online
    $fileExt     = $fileUrl ? strtolower(pathinfo($fileUrl, PATHINFO_EXTENSION)) : '';
    $isPptx      = in_array($fileExt, ['pptx', 'ppt']) || $material->type === 'presentation';

    // Office Online embed URL for PPTX (preserves full theme / background)
    $officeViewerUrl = null;
    if ($isPptx && $fileUrl) {
        $absoluteFileUrl = str_starts_with($fileUrl, 'http')
            ? $fileUrl
            : rtrim(config('app.url'), '/') . '/' . ltrim($fileUrl, '/');
        $officeViewerUrl = 'https://view.officeapps.live.com/op/embed.aspx?src=' . rawurlencode($absoluteFileUrl);
    }

    // Convert YouTube URL to embed URL
    $youtubeEmbedUrl = null;
    if ($material->type === 'youtube' && $fileUrl) {
        $ytUrl = $fileUrl;
        // Handle youtu.be short links
        if (preg_match('#youtu\.be/([A-Za-z0-9_-]+)#', $ytUrl, $m)) {
            $youtubeEmbedUrl = 'https://www.youtube.com/embed/' . $m[1];
        }
        // Handle watch?v=
        elseif (preg_match('#[?&]v=([A-Za-z0-9_-]+)#', $ytUrl, $m)) {
            $youtubeEmbedUrl = 'https://www.youtube.com/embed/' . $m[1];
        }
        // Already embed URL
        elseif (str_contains($ytUrl, '/embed/')) {
            $youtubeEmbedUrl = $ytUrl;
        } else {
            $youtubeEmbedUrl = $ytUrl; // passthrough
        }
    }
@endphp

<div class="viewer-shell">

    {{-- Sidebar --}}
    <div class="v-sidebar">
        <div class="v-sidebar-head">
            <div class="type-badge">
                @if($material->type === 'video') <i class="fas fa-video mr-1"></i>Video
                @elseif($material->type === 'youtube') <i class="fab fa-youtube mr-1"></i>YouTube
                @elseif($material->type === 'audio') <i class="fas fa-headphones mr-1"></i>Audio
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
            @if($fileUrl && $material->type === 'youtube')
            <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-gold w-100">
                <i class="fab fa-youtube mr-1"></i> Open on YouTube
            </a>
            @elseif($fileUrl && $material->type !== 'youtube')
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
                @if($fileUrl && $material->type === 'youtube')
                <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                    <i class="fab fa-youtube mr-1"></i> YouTube
                </a>
                @elseif($fileUrl && $material->type !== 'youtube')
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

            @elseif($isPptx)
                {{-- PPTX detected by extension — use Office Online regardless of stored type --}}
                @if($officeViewerUrl)
                    <iframe class="viewer-frame" src="{{ $officeViewerUrl }}" frameborder="0" title="{{ $material->title }}"></iframe>
                @else
                    <div class="nofile-wrap"><div class="nofile-card">
                        <i class="fas fa-file-powerpoint" style="font-size:48px; color:#ddd; display:block; margin-bottom:16px;"></i>
                        <h5 style="font-weight:700; color:#2d3748;">Preview unavailable</h5>
                        <p style="color:#888; font-size:13px;">Could not build a preview URL.</p>
                        @if($fileUrlEncoded)<a href="{{ $fileUrlEncoded }}" download class="btn btn-sm btn-gold mt-2"><i class="fas fa-download mr-1"></i> Download</a>@endif
                    </div></div>
                @endif

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

            @elseif($material->type === 'youtube')
                <div class="youtube-wrap">
                    @if($youtubeEmbedUrl)
                        <iframe src="{{ $youtubeEmbedUrl }}"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen
                                title="{{ $material->title }}">
                        </iframe>
                    @else
                        <div class="nofile-card">
                            <i class="fab fa-youtube" style="font-size:48px; color:#e53e3e; display:block; margin-bottom:16px;"></i>
                            <h5 style="font-weight:700; color:#2d3748;">Invalid YouTube URL</h5>
                            <p style="color:#888; font-size:13px;">The stored URL could not be converted to an embed link.</p>
                            @if($fileUrl)
                            <a href="{{ $fileUrl }}" target="_blank" class="btn btn-sm btn-gold">Open on YouTube</a>
                            @endif
                        </div>
                    @endif
                </div>

            @elseif($material->type === 'audio')
                <div class="audio-wrap">
                    <div class="audio-card">
                        <i class="fas fa-headphones" style="font-size:52px; color:#C9A84C; display:block; margin-bottom:12px;"></i>
                        <h6 style="font-weight:700; color:#2d3748; font-size:15px;">{{ $material->title }}</h6>
                        @if($material->description)
                        <p style="color:#888; font-size:13px; margin-top:6px;">{{ $material->description }}</p>
                        @endif
                        <audio controls preload="metadata" style="width:100%; margin-top:20px;">
                            <source src="{{ $fileUrlEncoded }}" type="audio/mpeg">
                            <source src="{{ $fileUrlEncoded }}" type="audio/ogg">
                            <source src="{{ $fileUrlEncoded }}" type="audio/wav">
                            <source src="{{ $fileUrlEncoded }}" type="audio/mp4">
                            <p style="color:#888; font-size:13px; margin-top:12px;">
                                Your browser doesn't support audio playback.
                                <a href="{{ $fileUrlEncoded }}" class="btn btn-gold btn-sm mt-1">Download Audio</a>
                            </p>
                        </audio>
                    </div>
                </div>

            @endif
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>


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
