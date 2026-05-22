@extends('layouts.admin')

@section('styles')
<link href="{{ asset('js/pptxjs/pptxjs.css') }}" rel="stylesheet" />
<style>
/* Font — use Inter to match the rest of the admin panel */
body, p, span, div, td, th, li, a, button, input, select, textarea, label,
h1, h2, h3, h4, h5, h6, small, strong {
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}
.fa, .fas, .far, .fab, .fal,
[class^="fa-"], [class*=" fa-"] {
    font-family: 'Font Awesome 5 Free', 'Font Awesome 5 Brands', 'Font Awesome 5 Solid' !important;
}

/* Layout */
.viewer-layout {
    display: flex;
    gap: 0;
    height: calc(100vh - 115px);
    min-height: 500px;
}

/* Sidebar */
.viewer-sidebar {
    width: 270px;
    min-width: 270px;
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 8px 0 0 8px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}
.sidebar-header {
    background: #a02626;
    color: #fff;
    padding: 14px 16px;
    flex-shrink: 0;
}
.mat-type-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: rgba(255,255,255,.18);
    border-radius: 12px;
    padding: 2px 10px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: 8px;
}
.sidebar-header h6 {
    font-size: 13.5px;
    font-weight: 700;
    margin: 0;
    line-height: 1.4;
    color: #fff;
}
.sidebar-body {
    padding: 14px;
    flex: 1;
    overflow-y: auto;
}
.meta-label {
    font-size: 10px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #bbb;
    margin-bottom: 4px;
    display: block;
}
.meta-row { margin-bottom: 14px; }
.meta-value { font-size: 13px; color: #333; }

.session-item {
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
    background: #a02626;
    color: #fff;
    font-size: 10px;
    font-weight: 700;
    width: 22px;
    min-width: 22px;
    height: 22px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 1px;
}
.session-title-sm { font-size: 12px; font-weight: 600; color: #2d2d2d; line-height: 1.3; }
.session-time-sm  { font-size: 11px; color: #aaa; margin-top: 1px; }

.sidebar-actions {
    padding: 12px 14px;
    border-top: 1px solid #f0f0f0;
    display: flex;
    flex-direction: column;
    gap: 6px;
    flex-shrink: 0;
}

/* Main viewer */
.viewer-main {
    flex: 1;
    background: #f0f0f0;
    border: 1px solid #e0e0e0;
    border-left: none;
    border-radius: 0 8px 8px 0;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    min-width: 0;
}
.viewer-topbar {
    background: #fff;
    border-bottom: 1px solid #e8e8e8;
    padding: 8px 14px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
}
.viewer-topbar-title { font-size: 13px; font-weight: 600; color: #2d2d2d; }
.viewer-body {
    flex: 1;
    overflow: auto;
    display: flex;
    flex-direction: column;
}

/* PDF iframe */
.viewer-frame {
    flex: 1;
    border: none;
    width: 100%;
    height: 100%;
    display: block;
}

/* PPTX renderer */
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
#pptx-container .slide {
    box-shadow: 0 4px 20px rgba(0,0,0,.4);
    border-radius: 4px;
    overflow: hidden;
    max-width: 100%;
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
    max-height: 100%;
    border-radius: 6px;
    box-shadow: 0 8px 32px rgba(0,0,0,.4);
}

/* No-file card */
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
    box-shadow: 0 4px 20px rgba(0,0,0,.07);
    text-align: center;
    padding: 48px 40px;
    max-width: 420px;
    width: 100%;
}

/* Fullscreen mode */
.viewer-main:-webkit-full-screen,
.viewer-main:-moz-full-screen,
.viewer-main:fullscreen {
    border-radius: 0;
    height: 100vh;
    width: 100vw;
}
.viewer-main:fullscreen .viewer-topbar { padding: 10px 20px; }

/* Slide nav bar */
#slide-nav {
    background: #333;
    padding: 8px 16px;
    display: flex;
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
    font-family: 'Inter', system-ui, -apple-system, sans-serif;
}
#slide-nav button:hover { background: rgba(255,255,255,.2); }
#slide-nav button:disabled { opacity: .4; cursor: default; }
</style>
@endsection

@php
    // URL-encode the file path for use in src/href attributes (handle spaces in filenames)
    $fileUrlEncoded = $fileUrl ? implode('/', array_map('rawurlencode', explode('/', $fileUrl))) : null;

    // Detect actual file type by extension (overrides the stored 'type' column for display)
    $fileExt     = $fileUrl ? strtolower(pathinfo($fileUrl, PATHINFO_EXTENSION)) : '';
    $isPptx      = in_array($fileExt, ['pptx', 'ppt']) || $trainingMaterial->type === 'presentation';
    $isPdf       = $fileExt === 'pdf' || ($trainingMaterial->type === 'document' && !$isPptx);

    // Office Online embed URL for PPTX (works regardless of stored 'type' column)
    $officeViewerUrl = null;
    if ($isPptx && $fileUrl) {
        $absoluteUrl = str_starts_with($fileUrl, 'http')
            ? $fileUrl
            : rtrim(config('app.url'), '/') . '/' . ltrim($fileUrl, '/');
        $officeViewerUrl = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode($absoluteUrl);
    }
@endphp

@section('content')

{{-- Breadcrumb --}}
<div class="d-flex align-items-center mb-3" style="gap:8px; font-size:13px; color:#888;">
    <a href="{{ route('admin.training-materials.index') }}" style="color:#a02626; text-decoration:none;">
        <i class="fas fa-book mr-1"></i> Training Materials
    </a>
    <i class="fas fa-chevron-right" style="font-size:10px;"></i>
    <span style="color:#555; font-weight:600;">{{ $trainingMaterial->title }}</span>
</div>

<div class="viewer-layout">

    {{-- ── Sidebar ── --}}
    <div class="viewer-sidebar">
        <div class="sidebar-header">
            <div class="mat-type-badge">
                @if($trainingMaterial->type === 'video')
                    <i class="fas fa-video"></i> Video
                @elseif($trainingMaterial->type === 'presentation')
                    <i class="fas fa-file-powerpoint"></i> Presentation
                @else
                    <i class="fas fa-file-pdf"></i> Document
                @endif
            </div>
            <h6>{{ $trainingMaterial->title }}</h6>
        </div>

        <div class="sidebar-body">
            @if($trainingMaterial->category)
            <div class="meta-row">
                <span class="meta-label">Category</span>
                <span style="background:#f0e9d6; color:#8a6a00; border-radius:4px; padding:2px 8px; font-size:12px; font-weight:600; display:inline-block;">
                    {{ $trainingMaterial->category }}
                </span>
            </div>
            @endif

            @if($trainingMaterial->facilitator)
            <div class="meta-row">
                <span class="meta-label">Facilitator</span>
                <span class="meta-value">
                    <i class="fas fa-user-tie mr-1" style="color:#a02626; font-size:11px;"></i>
                    {{ $trainingMaterial->facilitator->name }}
                </span>
            </div>
            @endif

            @if($trainingMaterial->description)
            <div class="meta-row">
                <span class="meta-label">About</span>
                <span class="meta-value" style="font-size:12.5px; color:#666; line-height:1.5;">
                    {{ $trainingMaterial->description }}
                </span>
            </div>
            @endif

            @if($trainingMaterial->schedules->isNotEmpty())
            <div class="meta-row">
                <span class="meta-label" style="margin-bottom:8px;">Used in Sessions</span>
                @foreach($trainingMaterial->schedules->sortBy('day_number') as $session)
                <div class="session-item">
                    <div class="day-dot">D{{ $session->day_number }}</div>
                    <div>
                        <div class="session-title-sm">{{ $session->title }}</div>
                        <div class="session-time-sm">
                            {{ \Carbon\Carbon::parse($session->start_time)->format('H:i') }}
                            @if($session->end_time) – {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}@endif
                            &bull; {{ \Carbon\Carbon::parse($session->date)->format('M j') }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>

        <div class="sidebar-actions">
            @if($fileUrl)
            <a href="{{ $fileUrlEncoded }}" download class="btn btn-sm btn-cosecsa w-100">
                <i class="fas fa-download mr-1"></i> Download
            </a>
            <a href="{{ $fileUrlEncoded }}" target="_blank" class="btn btn-sm btn-outline-secondary w-100">
                <i class="fas fa-external-link-alt mr-1"></i> Open in New Tab
            </a>
            @endif
            <a href="{{ route('admin.training-materials.index') }}" class="btn btn-sm btn-light w-100">
                <i class="fas fa-arrow-left mr-1"></i> Back to Materials
            </a>
        </div>
    </div>

    {{-- ── Main viewer ── --}}
    <div class="viewer-main">

        <div class="viewer-topbar">
            <span class="viewer-topbar-title">
                <i class="fas fa-eye mr-1" style="color:#a02626;"></i>
                {{ $trainingMaterial->title }}
            </span>
            @if($fileUrl)
            <div style="display:flex; gap:6px;">
                <a href="{{ $fileUrlEncoded }}" download class="btn btn-xs btn-outline-secondary">
                    <i class="fas fa-download mr-1"></i> Download
                </a>
                <button id="btn-fullscreen" class="btn btn-xs btn-outline-secondary" title="Full screen">
                    <i class="fas fa-expand-alt mr-1"></i> Full screen
                </button>
            </div>
            @endif
        </div>

        <div class="viewer-body">
        @if(!$fileUrl)
            <div class="nofile-wrap">
                <div class="nofile-card">
                    <i class="fas fa-folder-open" style="font-size:48px; color:#ddd; margin-bottom:16px; display:block;"></i>
                    <h5 style="font-weight:700; color:#2d2d2d;">No file available</h5>
                    <p style="color:#888; font-size:13px;">This material does not have a file attached yet.</p>
                    @can('training_material_edit')
                    <a href="{{ route('admin.training-materials.edit', $trainingMaterial->id) }}" class="btn btn-cosecsa btn-sm">
                        <i class="fas fa-upload mr-1"></i> Upload File
                    </a>
                    @endcan
                </div>
            </div>

        @elseif($isPptx)
            {{-- PPTX: Microsoft Office Online embed (full theme fidelity) --}}
            @if($officeViewerUrl)
                <iframe class="viewer-frame"
                        src="{{ $officeViewerUrl }}"
                        frameborder="0"
                        title="{{ $trainingMaterial->title }}">
                </iframe>
            @else
                <div class="nofile-wrap">
                    <div class="nofile-card">
                        <i class="fas fa-file-powerpoint" style="font-size:48px; color:#ddd; margin-bottom:16px; display:block;"></i>
                        <h5 style="font-weight:700; color:#2d2d2d;">Preview unavailable</h5>
                        <p style="color:#888; font-size:13px;">The file URL could not be resolved for preview.</p>
                        @if($fileUrlEncoded)
                        <a href="{{ $fileUrlEncoded }}" download class="btn btn-cosecsa btn-sm mt-2">
                            <i class="fas fa-download mr-1"></i> Download File
                        </a>
                        @endif
                    </div>
                </div>
            @endif

        @elseif($trainingMaterial->type === 'video')
            {{-- Video player --}}
            <div class="video-wrap">
                <video controls preload="metadata" style="max-height:calc(100vh - 200px); max-width:100%;">
                    <source src="{{ $fileUrlEncoded }}" type="video/quicktime">
                    <source src="{{ $fileUrlEncoded }}" type="video/mp4">
                    <source src="{{ $fileUrlEncoded }}" type="video/x-m4v">
                    <p style="color:#ccc; text-align:center;">
                        Your browser cannot play this video.<br>
                        <a href="{{ $fileUrlEncoded }}" class="btn btn-cosecsa btn-sm mt-2">Download Video</a>
                    </p>
                </video>
            </div>

        @elseif($isPdf)
            {{-- PDF: browser native iframe --}}
            <iframe class="viewer-frame"
                src="{{ $fileUrlEncoded }}#toolbar=1&view=FitH"
                title="{{ $trainingMaterial->title }}">
            </iframe>

        @else
            {{-- Other file type: download prompt --}}
            <div class="nofile-wrap">
                <div class="nofile-card">
                    <i class="fas fa-file fa-3x mb-3" style="color:#a02626; display:block;"></i>
                    <h6 style="font-weight:700; color:#2d3748; margin-bottom:8px;">{{ $trainingMaterial->title }}</h6>
                    <p style="font-size:13px; color:#888; margin-bottom:16px;">This file type cannot be previewed in the browser.</p>
                    @if($fileUrlEncoded)
                    <a href="{{ $fileUrlEncoded }}" download class="btn btn-cosecsa btn-sm">
                        <i class="fas fa-download mr-2"></i>Download to View
                    </a>
                    @endif
                </div>
            </div>
        @endif
        </div>
    </div>
</div>

@endsection

@section('scripts')
@parent

<script>
// Fullscreen toggle — expands the viewer-main panel using the Fullscreen API
$(function () {
    var $btn = $('#btn-fullscreen');
    if (!$btn.length) return;

    var $target = $('.viewer-main')[0];

    function enterFS() {
        if ($target.requestFullscreen)           $target.requestFullscreen();
        else if ($target.webkitRequestFullscreen) $target.webkitRequestFullscreen();
        else if ($target.mozRequestFullScreen)    $target.mozRequestFullScreen();
    }
    function exitFS() {
        if (document.exitFullscreen)             document.exitFullscreen();
        else if (document.webkitExitFullscreen)  document.webkitExitFullscreen();
        else if (document.mozCancelFullScreen)   document.mozCancelFullScreen();
    }

    $btn.on('click', function () {
        var isFS = !!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement);
        if (isFS) exitFS(); else enterFS();
    });

    // Swap icon when entering/leaving fullscreen
    $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange', function () {
        var isFS = !!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement);
        $btn.html(isFS
            ? '<i class="fas fa-compress-alt mr-1"></i> Exit full screen'
            : '<i class="fas fa-expand-alt mr-1"></i> Full screen');
    });
});
</script>
@endsection
