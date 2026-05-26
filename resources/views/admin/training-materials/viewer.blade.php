@extends('layouts.admin')

@section('styles')
<style>
/* Font */
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

/* Replace-file panel inside sidebar */
#replace-panel {
    padding: 12px 14px;
    border-top: 1px solid #f0f0f0;
    background: #fafafa;
    flex-shrink: 0;
}
#replace-panel .panel-title {
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #a02626;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
/* Dropzone inside replace panel */
#replace-dropzone {
    min-height: 70px !important;
    border: 2px dashed #ccc !important;
    border-radius: 6px !important;
    background: #fff !important;
    padding: 6px !important;
    cursor: pointer;
}
#replace-dropzone:hover { border-color: #a02626 !important; }
#replace-dropzone .dz-message {
    margin: 0 !important;
    padding: 8px !important;
    font-size: 12px !important;
    color: #aaa !important;
    text-align: center;
}
/* Dropzone preview items — ensure visibility */
#replace-dropzone .dz-preview {
    margin: 4px !important;
    min-height: 70px !important;
}
#replace-dropzone .dz-preview .dz-image {
    width: 60px !important;
    height: 60px !important;
    border-radius: 4px !important;
}
#replace-dropzone .dz-preview .dz-details {
    opacity: 1 !important;
    font-size: 10px !important;
    padding: 4px !important;
}
#replace-dropzone .dz-preview .dz-filename span { font-size: 10px !important; }
#replace-dropzone .dz-preview .dz-size span    { font-size: 10px !important; }
#replace-dropzone .dz-preview.dz-success .dz-success-mark { opacity: 1 !important; }
#replace-dropzone .dz-preview.dz-success .dz-error-mark   { opacity: 0 !important; }
#replace-save-btn { display: none; }

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

/* Stata code viewer */
.stata-wrap {
    flex: 1;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    background: #1e1e2e;
}
.stata-toolbar {
    background: #2d2d3e;
    padding: 8px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
    border-bottom: 1px solid #44445a;
}
.stata-toolbar span { color: #aaa; font-size: 12px; }
.stata-code-wrap { flex: 1; overflow: auto; padding: 20px 24px; }
.stata-code {
    margin: 0;
    color: #cdd6f4;
    font-family: 'Consolas', 'Monaco', 'Courier New', monospace;
    font-size: 13px;
    line-height: 1.7;
    white-space: pre;
    tab-size: 4;
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
</style>
@endsection

@php
    // URL-encode the file path for use in src/href attributes.
    // For absolute URLs (Spatie MediaLibrary) only encode the path portion.
    $fileUrlEncoded = null;
    if ($fileUrl) {
        if (str_starts_with($fileUrl, 'http')) {
            $p = parse_url($fileUrl);
            $encodedPath = implode('/', array_map('rawurlencode', explode('/', $p['path'] ?? '')));
            $fileUrlEncoded = ($p['scheme'] ?? 'https') . '://' . ($p['host'] ?? '') . $encodedPath;
        } else {
            $fileUrlEncoded = implode('/', array_map('rawurlencode', explode('/', $fileUrl)));
        }
    }

    // Detect file type by actual extension first, fall back to material type
    $fileExt = $fileUrl ? strtolower(pathinfo($fileUrl, PATHINFO_EXTENSION)) : '';
    $isPptx  = in_array($fileExt, ['pptx', 'ppt'])
               || ($trainingMaterial->type === 'presentation' && !in_array($fileExt, ['pdf','doc','docx','mp4','mov','mp3','m4a','wav','ogg']));
    $isPdf   = $fileExt === 'pdf'
               || ($trainingMaterial->type === 'document' && !$isPptx && !in_array($fileExt, ['mp4','mov','mp3']));

    // Spreadsheet (Excel) — Office Online embed
    $isXlsx = in_array($fileExt, ['xls', 'xlsx']) || $trainingMaterial->type === 'spreadsheet';
    $officeViewerUrlXlsx = null;
    if ($isXlsx && $fileUrl && !$isPptx) {
        $absoluteXlsxUrl = str_starts_with($fileUrl, 'http')
            ? $fileUrl
            : rtrim(config('app.url'), '/') . '/' . ltrim($fileUrl, '/');
        $officeViewerUrlXlsx = 'https://view.officeapps.live.com/op/embed.aspx?src=' . rawurlencode($absoluteXlsxUrl);
    }

    // Stata files
    $isStataScript = $fileExt === 'do';
    $isStataData   = $fileExt === 'dta';
    $stataCode     = null;
    if ($isStataScript && $fileUrl) {
        if (str_starts_with($fileUrl, 'http')) {
            $p         = parse_url($fileUrl);
            $decoded   = rawurldecode($p['path'] ?? '');
            $localPath = public_path(ltrim($decoded, '/'));
        } else {
            $relative  = ltrim(preg_replace('#^/[^/]+/#', '', $fileUrl), '/');
            $localPath = public_path($relative);
        }
        if (!empty($localPath) && file_exists($localPath) && filesize($localPath) < 500000) {
            $stataCode = file_get_contents($localPath);
        }
    }

    // Office Online embed URL for PPTX
    $officeViewerUrl = null;
    if ($isPptx && $fileUrl) {
        $absoluteUrl = str_starts_with($fileUrl, 'http')
            ? $fileUrl
            : rtrim(config('app.url'), '/') . '/' . ltrim($fileUrl, '/');
        $officeViewerUrl = 'https://view.officeapps.live.com/op/embed.aspx?src=' . rawurlencode($absoluteUrl);
    }
@endphp

@section('content')

@if(session('message'))
<div class="alert alert-success alert-dismissible fade show py-2 mb-2" role="alert">
    <i class="fas fa-check-circle mr-2"></i> {{ session('message') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

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
                @elseif($trainingMaterial->type === 'stata')
                    <i class="fas fa-database"></i> Stata
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

        {{-- Replace file panel (hidden until triggered) --}}
        @can('training_material_edit')
        <div id="replace-panel" style="display:none;">
            <div class="panel-title">
                <span><i class="fas fa-retweet mr-1"></i> Replace File</span>
                <button type="button" onclick="toggleReplacePanel(false)"
                        style="background:none;border:none;color:#aaa;cursor:pointer;padding:0;font-size:14px;" title="Cancel">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="needsclick dropzone" id="replace-dropzone"></div>
            {{-- Linear upload progress --}}
            <div id="replace-progress-wrap" style="display:none; margin-top:8px;">
                <div style="display:flex; justify-content:space-between; font-size:10px; color:#888; margin-bottom:3px;">
                    <span>Uploading…</span><span id="replace-pct">0%</span>
                </div>
                <div style="height:5px; background:#e9ecef; border-radius:3px; overflow:hidden;">
                    <div id="replace-progress-bar" style="height:100%; width:0%; background:#a02626; border-radius:3px; transition:width .15s ease;"></div>
                </div>
            </div>
            <button id="replace-save-btn" class="btn btn-cosecsa btn-sm w-100 mt-2"
                    onclick="submitReplace()">
                <i class="fas fa-save mr-1"></i> Save New File
            </button>
            <div id="replace-status" style="font-size:11px; color:#888; margin-top:4px; text-align:center;"></div>
            {{-- Hidden form for submitting the replacement --}}
            <form id="replace-form"
                  action="{{ route('admin.training-materials.update', $trainingMaterial->id) }}"
                  method="POST" style="display:none;">
                @csrf
                @method('PUT')
                {{-- preserve required fields --}}
                <input type="hidden" name="title"      value="{{ $trainingMaterial->title }}">
                <input type="hidden" name="type"       value="{{ $trainingMaterial->type }}">
                <input type="hidden" name="remove_file" value="0">
                <input type="hidden" name="_from"      value="viewer">
                <input type="hidden" name="file" id="replace-file-input" value="">
            </form>
        </div>
        @endcan

        <div class="sidebar-actions">
            @if($fileUrl)
            <a href="{{ $fileUrlEncoded }}" download class="btn btn-sm btn-cosecsa w-100">
                <i class="fas fa-download mr-1"></i> Download
            </a>
            <a href="{{ $fileUrlEncoded }}" target="_blank" class="btn btn-sm btn-outline-secondary w-100">
                <i class="fas fa-external-link-alt mr-1"></i> Open in New Tab
            </a>
            @endif
            @can('training_material_edit')
            <button type="button" class="btn btn-sm btn-outline-warning w-100" onclick="toggleReplacePanel(true)">
                <i class="fas fa-retweet mr-1"></i> Replace File
            </button>
            @endcan
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
            {{-- PPTX: Microsoft Office Online embed --}}
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

        @elseif($isXlsx)
            {{-- Excel / Spreadsheet — Microsoft Office Online embed --}}
            @if($officeViewerUrlXlsx)
                <iframe class="viewer-frame" src="{{ $officeViewerUrlXlsx }}" frameborder="0" title="{{ $trainingMaterial->title }}"></iframe>
            @else
                <div class="nofile-wrap">
                    <div class="nofile-card">
                        <i class="fas fa-file-excel" style="font-size:48px; color:#1d6f42; display:block; margin-bottom:16px;"></i>
                        <h5 style="font-weight:700; color:#2d2d2d;">Preview unavailable</h5>
                        <p style="color:#888; font-size:13px;">Could not build an Office Online preview URL.</p>
                        @if($fileUrlEncoded)<a href="{{ $fileUrlEncoded }}" download class="btn btn-cosecsa btn-sm mt-2"><i class="fas fa-download mr-1"></i> Download</a>@endif
                    </div>
                </div>
            @endif

        @elseif($isPdf)
            {{-- PDF: browser native iframe --}}
            <iframe class="viewer-frame"
                src="{{ $fileUrlEncoded }}#toolbar=1&view=FitH"
                title="{{ $trainingMaterial->title }}">
            </iframe>

        @elseif($isStataScript)
            {{-- .do file: plain-text Stata script --}}
            <div class="stata-wrap">
                <div class="stata-toolbar">
                    <i class="fas fa-file-code" style="color:#7c8cf8; font-size:14px;"></i>
                    <span>{{ basename($fileUrl) }}</span>
                    <span style="margin-left:auto; background:#3a3a55; border-radius:4px; padding:2px 10px; color:#9ca3d4; font-size:11px; font-weight:700;">Stata Script (.do)</span>
                </div>
                <div class="stata-code-wrap">
                    @if($stataCode !== null)
                        <pre class="stata-code">{{ $stataCode }}</pre>
                    @else
                        <div style="text-align:center; padding:60px 20px;">
                            <i class="fas fa-file-code" style="font-size:48px; color:#44445a; display:block; margin-bottom:16px;"></i>
                            <p style="color:#6b6b8a; font-size:13px;">Could not load file content for preview.</p>
                            @if($fileUrlEncoded)
                            <a href="{{ $fileUrlEncoded }}" download class="btn btn-cosecsa btn-sm mt-2">
                                <i class="fas fa-download mr-1"></i> Download .do File
                            </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

        @elseif($isStataData)
            {{-- .dta file: binary Stata data file --}}
            <div class="nofile-wrap">
                <div class="nofile-card">
                    <i class="fas fa-database" style="font-size:52px; color:#5a6678; display:block; margin-bottom:16px;"></i>
                    <h5 style="font-weight:700; color:#2d2d2d;">Stata Data File (.dta)</h5>
                    <p style="color:#888; font-size:13px; margin-bottom:20px;">
                        Binary Stata datasets cannot be previewed in the browser.<br>
                        Open this file in Stata, or use R / Python (<code>haven</code> package).
                    </p>
                    @if($fileUrlEncoded)
                    <a href="{{ $fileUrlEncoded }}" download class="btn btn-cosecsa btn-sm">
                        <i class="fas fa-download mr-2"></i>Download .dta File
                    </a>
                    @endif
                </div>
            </div>

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
Dropzone.autoDiscover = false;

$(function () {

    // ── Fullscreen toggle ────────────────────────────────────────────────
    var $btn    = $('#btn-fullscreen');
    var $target = $('.viewer-main')[0];

    if ($btn.length) {
        function enterFS() {
            if ($target.requestFullscreen)            $target.requestFullscreen();
            else if ($target.webkitRequestFullscreen) $target.webkitRequestFullscreen();
            else if ($target.mozRequestFullScreen)    $target.mozRequestFullScreen();
        }
        function exitFS() {
            if (document.exitFullscreen)            document.exitFullscreen();
            else if (document.webkitExitFullscreen) document.webkitExitFullscreen();
            else if (document.mozCancelFullScreen)  document.mozCancelFullScreen();
        }
        $btn.on('click', function () {
            var isFS = !!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement);
            if (isFS) exitFS(); else enterFS();
        });
        $(document).on('fullscreenchange webkitfullscreenchange mozfullscreenchange', function () {
            var isFS = !!(document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement);
            $btn.html(isFS
                ? '<i class="fas fa-compress-alt mr-1"></i> Exit full screen'
                : '<i class="fas fa-expand-alt mr-1"></i> Full screen');
        });
    }

    // ── Replace-file Dropzone ────────────────────────────────────────────
    @can('training_material_edit')
    var replaceDropzone = new Dropzone('#replace-dropzone', {
        url: '{{ route('admin.training-materials.storeMedia') }}',
        maxFilesize: 100,
        maxFiles: 1,
        addRemoveLinks: true,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        params: { size: 100 },
        previewTemplate: window.DZ_PREVIEW_TEMPLATE,
        dictDefaultMessage: window.DZ_DEFAULT_MSG,
        sending: function () {
            $('#replace-progress-wrap').show();
            $('#replace-progress-bar').css('width', '0%');
            $('#replace-pct').text('0%');
        },
        uploadprogress: function (file, progress) {
            var pct = Math.round(progress) + '%';
            $('#replace-progress-bar').css('width', pct);
            $('#replace-pct').text(pct);
        },
        success: function (file, response) {
            $('#replace-progress-bar').css('width', '100%');
            $('#replace-pct').text('100%');
            setTimeout(function(){ $('#replace-progress-wrap').hide(); }, 600);
            $('#replace-file-input').val(response.name);
            $('#replace-save-btn').show();
            $('#replace-status').text('✔ Ready: ' + response.original_name).css('color', '#2d8a4e');
        },
        removedfile: function (file) {
            file.previewElement.remove();
            $('#replace-file-input').val('');
            $('#replace-save-btn').hide();
            $('#replace-status').text('');
            $('#replace-progress-wrap').hide();
        },
        error: function (file, msg, xhr) {
            var errText = typeof msg === 'string' ? msg : (msg.error || msg.message || JSON.stringify(msg));
            if (xhr && xhr.status === 422) {
                try {
                    var parsed = JSON.parse(xhr.responseText);
                    errText = parsed.errors ? Object.values(parsed.errors).flat().join(' ') : (parsed.message || errText);
                } catch(e) {}
            }
            $('#replace-status').text('Upload error: ' + errText).css('color', '#c0392b');
        },
        init: function () {
            this.on('addedfile', function (file) {
                if (this.files.length > 1) this.removeFile(this.files[0]);
                dzSetFileIcon(file);
            });
        }
    });
    @endcan

});

// Toggle replace panel visibility
function toggleReplacePanel(show) {
    var $panel   = $('#replace-panel');
    var $actions = $('.sidebar-actions');
    if (show) {
        $panel.slideDown(150);
        // Scroll sidebar to show the panel
        $panel[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    } else {
        $panel.slideUp(150);
        $('#replace-save-btn').hide();
        $('#replace-status').text('');
    }
}

// Submit the replacement form
function submitReplace() {
    var newFile = $('#replace-file-input').val();
    if (!newFile) {
        $('#replace-status').text('Please upload a file first.').css('color', '#c0392b');
        return;
    }
    $('#replace-status').text('Saving…').css('color', '#888');
    $('#replace-form').submit();
}
</script>
@endsection
