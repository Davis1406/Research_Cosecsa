@extends('layouts.admin')

@section('styles')
<style>
/* ── Hero banner ── */
.trainee-hero {
    background: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
    border-radius: 12px 12px 0 0;
    padding: 28px 28px 22px;
    position: relative;
    overflow: hidden;
}
.trainee-hero::before {
    content: '';
    position: absolute;
    top: -30px; right: -30px;
    width: 160px; height: 160px;
    border-radius: 50%;
    background: rgba(201,168,76,.08);
}
.trainee-hero-avatar {
    width: 64px; height: 64px; border-radius: 50%;
    background: linear-gradient(135deg, #C9A84C, #9a7d2c);
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; font-weight: 800; color: #fff;
    flex-shrink: 0; border: 3px solid rgba(255,255,255,.15);
}
.trainee-hero-name {
    font-size: 20px; font-weight: 800; color: #fff;
    line-height: 1.2; margin-bottom: 4px;
}
.hero-chip {
    display: inline-flex; align-items: center; gap: 5px;
    background: rgba(255,255,255,.1);
    border: 1px solid rgba(255,255,255,.15);
    color: rgba(255,255,255,.85);
    border-radius: 20px; padding: 3px 10px;
    font-size: 11px; font-weight: 600;
    margin-right: 4px; margin-top: 4px;
}

/* ── Info cards ── */
.info-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 16px;
}
.info-card-head {
    padding: 12px 16px;
    border-bottom: 1px solid #f0f2f5;
    font-size: 11px; font-weight: 800;
    text-transform: uppercase; letter-spacing: .6px; color: #aaa;
    background: #fafbfc;
}
.info-row {
    display: flex; align-items: flex-start;
    padding: 10px 16px;
    border-bottom: 1px solid #f7f8fa;
    font-size: 13px;
}
.info-row:last-child { border-bottom: none; }
.info-label { color: #a0aec0; font-weight: 600; min-width: 120px; flex-shrink: 0; }
.info-value { color: #2d3748; font-weight: 500; flex: 1; }

/* ── Presentation cards ── */
.pres-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
    margin-bottom: 16px;
    transition: box-shadow .15s;
}
.pres-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.1); }
.pres-card-head {
    padding: 12px 16px;
    background: #2d3748;
    display: flex; align-items: center; gap: 10px;
}
.pres-type-badge {
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    padding: 2px 8px; border-radius: 10px;
    background: rgba(201,168,76,.25); color: #C9A84C;
    border: 1px solid rgba(201,168,76,.35);
}

/* ── Comment thread ── */
.comment-item {
    padding: 12px 16px;
    border-bottom: 1px solid #f5f5f5;
}
.comment-item:last-child { border-bottom: none; }
.comment-avatar {
    width: 30px; height: 30px; border-radius: 50%;
    background: linear-gradient(135deg, #a02626, #7a1a1a);
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; font-weight: 800; color: #fff;
    flex-shrink: 0;
}

/* ── No presentations ── */
.empty-pres {
    text-align: center; padding: 50px 20px;
    background: #fff; border: 1px solid #e2e8f0;
    border-radius: 10px;
}

/* ── Action buttons ── */
.action-btn {
    display: flex; align-items: center; gap: 8px;
    padding: 9px 14px; border-radius: 8px;
    font-size: 13px; font-weight: 700;
    text-decoration: none; border: none; cursor: pointer;
    width: 100%; margin-bottom: 6px;
    transition: opacity .15s;
}
.action-btn:hover { opacity: .85; text-decoration: none; }
</style>
@endsection

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between flex-wrap" style="gap:8px;">
            <a href="{{ route('admin.trainees.index') }}" style="font-size:13px; color:#718096; text-decoration:none; font-weight:600;">
                <i class="fas fa-arrow-left mr-1"></i> Back to Trainees
            </a>
            <div style="display:flex; gap:8px;">
                @can('trainee_edit')
                <a href="{{ route('admin.trainees.edit', $trainee) }}"
                   class="btn btn-sm" style="background:#C9A84C; color:#fff; font-weight:700;">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                @endcan
                @if($trainee->user)
                <a href="{{ route('admin.messages.thread', $trainee->user) }}"
                   class="btn btn-sm" style="background:#2d3748; color:#fff; font-weight:700;">
                    <i class="fas fa-comment mr-1"></i> Message
                </a>
                @endif
            </div>
        </div>
    </div>
</div>

<section class="content">
<div class="container-fluid">

@if(session('message'))
<div class="alert alert-success alert-dismissible fade show py-2 mb-3">
    {{ session('message') }}<button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif

{{-- ── Hero ── --}}
<div style="background:#fff; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; margin-bottom:20px; box-shadow:0 2px 10px rgba(0,0,0,.06);">
    <div class="trainee-hero">
        <div class="d-flex align-items-center" style="gap:16px;">
            <div class="trainee-hero-avatar">{{ strtoupper(substr($trainee->name, 0, 1)) }}</div>
            <div style="flex:1; min-width:0;">
                <div class="trainee-hero-name">{{ $trainee->name }}</div>
                <div style="display:flex; flex-wrap:wrap; margin-top:4px;">
                    @if($trainee->registration_number)
                    <span class="hero-chip"><i class="fas fa-id-badge"></i> {{ $trainee->registration_number }}</span>
                    @endif
                    @if($trainee->country)
                    <span class="hero-chip"><i class="fas fa-globe-africa"></i> {{ $trainee->country }}</span>
                    @endif
                    @if($trainee->specialty)
                    <span class="hero-chip"><i class="fas fa-stethoscope"></i> {{ $trainee->specialty }}</span>
                    @endif
                    @if($trainee->enrollment_date)
                    <span class="hero-chip"><i class="fas fa-calendar-check"></i> Enrolled {{ $trainee->enrollment_date->format('M Y') }}</span>
                    @endif
                </div>
            </div>
            {{-- Stats --}}
            <div class="d-none d-md-flex" style="gap:20px; text-align:center; flex-shrink:0;">
                <div>
                    <div style="font-size:22px; font-weight:800; color:#C9A84C;">{{ $trainee->documents->count() }}</div>
                    <div style="font-size:10px; color:rgba(255,255,255,.6); text-transform:uppercase; letter-spacing:.4px;">Presentations</div>
                </div>
                <div>
                    <div style="font-size:22px; font-weight:800; color:#C9A84C;">{{ $trainee->documents->sum(fn($d) => $d->comments->count()) }}</div>
                    <div style="font-size:10px; color:rgba(255,255,255,.6); text-transform:uppercase; letter-spacing:.4px;">Comments</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tab nav --}}
    <ul class="nav" style="border-bottom:2px solid #f0f2f5; padding:0 16px; background:#fff;">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#tab-details"
               style="font-weight:700; font-size:13px; color:#a02626; border-bottom:2px solid #a02626; margin-bottom:-2px; padding:12px 14px;">
                <i class="fas fa-user mr-1"></i> Profile
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tab-presentations"
               style="font-weight:700; font-size:13px; color:#718096; padding:12px 14px;">
                <i class="fas fa-file-powerpoint mr-1"></i> Presentations
                @if($trainee->documents->isNotEmpty())
                <span style="background:#C9A84C; color:#fff; border-radius:10px; padding:1px 7px; font-size:10px; font-weight:800; margin-left:4px;">{{ $trainee->documents->count() }}</span>
                @endif
            </a>
        </li>
    </ul>
</div>

{{-- ── Tab content ── --}}
<div class="tab-content">

    {{-- Profile tab --}}
    <div class="tab-pane fade show active" id="tab-details">
        <div class="row">
            <div class="col-md-6">
                <div class="info-card">
                    <div class="info-card-head"><i class="fas fa-address-card mr-1"></i> Contact & Identity</div>
                    <div class="info-row">
                        <div class="info-label">Full Name</div>
                        <div class="info-value">{{ $trainee->name ?: '—' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email</div>
                        <div class="info-value">
                            @if($trainee->email)
                                <a href="mailto:{{ $trainee->email }}" style="color:#a02626;">{{ $trainee->email }}</a>
                            @else —
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Phone</div>
                        <div class="info-value">{{ $trainee->phone ?: '—' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Country</div>
                        <div class="info-value">{{ $trainee->country ?: '—' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Reg. Number</div>
                        <div class="info-value">
                            @if($trainee->registration_number)
                            <span style="font-family:monospace; background:#f7f8fa; border:1px solid #e2e8f0; border-radius:4px; padding:1px 6px; font-size:12px;">{{ $trainee->registration_number }}</span>
                            @else —
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="info-card">
                    <div class="info-card-head"><i class="fas fa-hospital mr-1"></i> Professional Details</div>
                    <div class="info-row">
                        <div class="info-label">Institution</div>
                        <div class="info-value">{{ $trainee->institution ?: '—' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Specialty</div>
                        <div class="info-value">{{ $trainee->specialty ?: '—' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Enrolled</div>
                        <div class="info-value">
                            @if($trainee->enrollment_date)
                                {{ $trainee->enrollment_date->format('F j, Y') }}
                                <span style="color:#aaa; font-size:11px;">({{ $trainee->enrollment_date->diffForHumans() }})</span>
                            @else —
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Portal Access</div>
                        <div class="info-value">
                            @if($trainee->user)
                                <span style="background:#d4edda; color:#155724; border-radius:4px; padding:2px 8px; font-size:11px; font-weight:700;">
                                    <i class="fas fa-check-circle mr-1"></i> Active
                                </span>
                                <span style="color:#aaa; font-size:11px; margin-left:6px;">{{ $trainee->user->email }}</span>
                            @else
                                <span style="background:#fff3cd; color:#856404; border-radius:4px; padding:2px 8px; font-size:11px; font-weight:700;">
                                    <i class="fas fa-exclamation-circle mr-1"></i> No account
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Added</div>
                        <div class="info-value" style="color:#718096;">{{ $trainee->created_at->format('M j, Y') }}</div>
                    </div>
                </div>

                @if($trainee->notes)
                <div class="info-card">
                    <div class="info-card-head"><i class="fas fa-sticky-note mr-1"></i> Notes</div>
                    <div style="padding:14px 16px; font-size:13px; color:#2d3748; line-height:1.65; white-space:pre-line;">{{ $trainee->notes }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Presentations tab --}}
    <div class="tab-pane fade" id="tab-presentations">
        @if($trainee->documents->isEmpty())
            <div class="empty-pres">
                <i class="fas fa-file-powerpoint" style="font-size:44px; color:#e2e8f0; display:block; margin-bottom:14px;"></i>
                <p style="color:#adb5bd; font-size:14px; margin:0;">No presentations uploaded by this trainee yet.</p>
            </div>
        @else
            @foreach($trainee->documents as $doc)
            @php
                $ext       = strtolower(pathinfo($doc->original_name, PATHINFO_EXTENSION));
                $isPdf     = $ext === 'pdf';
                $isPptx    = in_array($ext, ['pptx', 'ppt']);
                $fileUrl   = $doc->download_url;
                // Encode only path segments so absolute URLs (https://...) stay valid
                if ($fileUrl && str_starts_with($fileUrl, 'http')) {
                    $parsed = parse_url($fileUrl);
                    $segs   = array_map('rawurlencode', explode('/', ltrim($parsed['path'] ?? '', '/')));
                    $fileUrlEncoded = ($parsed['scheme'] ?? 'https') . '://' . ($parsed['host'] ?? '') . '/' . implode('/', $segs);
                } elseif ($fileUrl) {
                    $fileUrlEncoded = implode('/', array_map('rawurlencode', explode('/', $fileUrl)));
                } else {
                    $fileUrlEncoded = null;
                }
                $docIcon   = $isPdf ? 'fa-file-pdf' : ($isPptx ? 'fa-file-powerpoint' : 'fa-file');
                $docColor  = $isPdf ? '#e53e3e' : '#C9A84C';
                $typeLabel = $isPdf ? 'PDF' : ($isPptx ? 'PPTX' : strtoupper($ext));

                // Office Online embed
                $officeViewerUrl = null;
                if ($isPptx && $fileUrl) {
                    $absoluteUrl = str_starts_with($fileUrl, 'http')
                        ? $fileUrl
                        : rtrim(config('app.url'), '/') . '/' . ltrim($fileUrl, '/');
                    $officeViewerUrl = 'https://view.officeapps.live.com/op/embed.aspx?src=' . rawurlencode($absoluteUrl);
                }
            @endphp

            <div class="pres-card">
                {{-- Card header --}}
                <div class="pres-card-head">
                    <i class="fas {{ $docIcon }}" style="color:{{ $docColor }}; font-size:16px;"></i>
                    <div style="flex:1; min-width:0;">
                        <div style="font-weight:700; font-size:14px; color:#fff; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                            {{ $doc->title ?: $doc->original_name }}
                        </div>
                        <div style="font-size:11px; color:rgba(255,255,255,.5);">{{ $doc->created_at->format('M j, Y \a\t H:i') }}</div>
                    </div>
                    <span class="pres-type-badge">{{ $typeLabel }}</span>
                    @if($fileUrlEncoded)
                    <a href="{{ $fileUrlEncoded }}" download
                       style="color:rgba(255,255,255,.7); font-size:12px; margin-left:8px; text-decoration:none;"
                       title="Download">
                        <i class="fas fa-download"></i>
                    </a>
                    @endif
                    <a href="{{ route('admin.presentations.view', $doc) }}"
                       class="btn btn-sm ml-2"
                       style="background:#C9A84C; color:#fff; font-weight:700; font-size:11px; white-space:nowrap;">
                        <i class="fas fa-external-link-alt mr-1"></i> Full view
                    </a>
                </div>

                {{-- Reviewer badges --}}
                @if($doc->reviewers->isNotEmpty())
                <div style="padding:8px 16px; background:#f9fafb; border-bottom:1px solid #f0f2f5; display:flex; flex-wrap:wrap; gap:6px; align-items:center;">
                    <span style="font-size:10px; font-weight:700; color:#aaa; text-transform:uppercase; letter-spacing:.4px; margin-right:2px;">
                        <i class="fas fa-user-check mr-1" style="color:#2c7a4b;"></i>Reviewers:
                    </span>
                    @foreach($doc->reviewers as $rv)
                    <span style="font-size:11px; font-weight:600; background:rgba(44,122,75,0.1); color:#2c7a4b; border-radius:10px; padding:2px 10px; border:1px solid rgba(44,122,75,.2);">
                        {{ $rv->name }}
                    </span>
                    @endforeach
                </div>
                @endif

                <div class="row no-gutters">
                    {{-- Viewer panel --}}
                    <div class="col-lg-7" style="border-right:1px solid #f0f2f5;">
                        @if($isPdf)
                            <iframe src="{{ $fileUrlEncoded }}#toolbar=1&view=FitH"
                                    style="width:100%; height:520px; border:none; display:block;"
                                    title="{{ $doc->original_name }}"></iframe>

                        @elseif($isPptx && $officeViewerUrl)
                            <iframe src="{{ $officeViewerUrl }}"
                                    style="width:100%; height:520px; border:none; display:block;"
                                    frameborder="0"
                                    title="{{ $doc->original_name }}"></iframe>

                        @else
                            <div style="padding:60px 30px; text-align:center; background:#f8f9fa; height:520px; display:flex; flex-direction:column; align-items:center; justify-content:center;">
                                <i class="fas {{ $docIcon }} fa-3x mb-3" style="color:{{ $docColor }}; display:block;"></i>
                                <h6 style="font-weight:700; color:#2d3748; margin-bottom:8px;">{{ $doc->original_name }}</h6>
                                <p style="font-size:13px; color:#888; margin-bottom:16px;">This file type cannot be previewed in the browser.</p>
                                @if($fileUrlEncoded)
                                <a href="{{ $fileUrlEncoded }}" download class="btn btn-sm" style="background:#2d3748; color:#fff; font-weight:700;">
                                    <i class="fas fa-download mr-1"></i> Download to View
                                </a>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Comments panel --}}
                    <div class="col-lg-5" style="display:flex; flex-direction:column;">
                        {{-- Add comment --}}
                        <div style="padding:14px 16px; border-bottom:1px solid #f0f2f5; background:#fff;">
                            <div style="font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:.5px; color:#aaa; margin-bottom:8px;">
                                <i class="fas fa-comment-alt mr-1" style="color:#a02626;"></i> Add Feedback
                            </div>
                            <form action="{{ route('admin.presentations.comment', $doc->id) }}" method="POST">
                                @csrf
                                <textarea name="comment" class="form-control form-control-sm" rows="3"
                                          placeholder="Write your feedback on this submission…"
                                          required style="font-size:12px; resize:none; border-radius:6px;"></textarea>
                                <button type="submit" class="btn btn-sm btn-block mt-2"
                                        style="background:#a02626; color:#fff; font-weight:700; font-size:12px;">
                                    <i class="fas fa-paper-plane mr-1"></i> Post Comment
                                </button>
                            </form>
                        </div>

                        {{-- Comments list --}}
                        <div style="flex:1; overflow-y:auto; max-height:380px; background:#fff;">
                            @if($doc->comments->isEmpty())
                                <div style="text-align:center; padding:36px 20px; color:#aaa;">
                                    <i class="fas fa-comment-slash fa-2x mb-2" style="display:block;"></i>
                                    <span style="font-size:12px;">No feedback yet.</span>
                                </div>
                            @else
                                @foreach($doc->comments as $comment)
                                <div class="comment-item">
                                    <div class="d-flex align-items-center" style="gap:8px; margin-bottom:6px;">
                                        <div class="comment-avatar">
                                            {{ strtoupper(substr($comment->user->name ?? '?', 0, 1)) }}
                                        </div>
                                        <div>
                                            <div style="font-size:12px; font-weight:700; color:#2d3748;">{{ $comment->user->name ?? 'Unknown' }}</div>
                                            <div style="font-size:10px; color:#bbb;">{{ $comment->created_at->format('M j, Y · H:i') }}</div>
                                        </div>
                                    </div>
                                    <div style="font-size:13px; color:#333; line-height:1.55; padding-left:38px;">{{ $comment->comment }}</div>
                                </div>
                                @endforeach
                            @endif
                        </div>

                        {{-- Comment count footer --}}
                        <div style="padding:8px 16px; background:#f8f9fa; border-top:1px solid #f0f2f5; display:flex; align-items:center; justify-content:space-between;">
                            <span style="font-size:11px; color:#aaa; font-weight:600;">
                                <i class="fas fa-comments mr-1"></i>
                                {{ $doc->comments->count() }} comment{{ $doc->comments->count() !== 1 ? 's' : '' }}
                            </span>
                            <a href="{{ route('admin.presentations.view', $doc) }}"
                               style="font-size:11px; color:#a02626; font-weight:700; text-decoration:none;">
                                Open full view <i class="fas fa-arrow-right ml-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif
    </div>

</div>

</div>
</section>
@endsection

@section('scripts')
@parent
<script>
// Activate tab from URL hash on page load
$(function () {
    var hash = window.location.hash;
    if (hash) {
        $('[data-toggle="tab"][href="' + hash + '"]').tab('show');
    }

    // Update URL hash when tab changes
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        history.replaceState(null, null, $(e.target).attr('href'));
    });

    // Re-style active tab link
    $('a[data-toggle="tab"]').on('show.bs.tab', function () {
        $(this).closest('ul').find('.nav-link')
            .css({'color': '#718096', 'border-bottom': '2px solid transparent'});
    }).on('shown.bs.tab', function () {
        $(this).css({'color': '#a02626', 'border-bottom': '2px solid #a02626'});
    });
});
</script>
@endsection
