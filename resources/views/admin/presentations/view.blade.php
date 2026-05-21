@extends('layouts.admin')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0" style="font-size:1.2rem; font-weight:700;">Review Presentation</h1>
            </div>
        </div>
    </div>
</div>
<section class="content">
<div class="container-fluid">

@php
    $ext      = strtolower(pathinfo($document->original_name, PATHINFO_EXTENSION));
    $isPdf    = $ext === 'pdf';
    $isPptx   = in_array($ext, ['pptx', 'ppt']);
    $fileUrl  = $document->download_url;
    $fileUrlEncoded = $fileUrl ? implode('/', array_map('rawurlencode', explode('/', $fileUrl))) : null;

    // Office Online embed for PPTX (files are publicly stored)
    $officeViewerUrl = null;
    if ($isPptx && $fileUrl) {
        $absoluteUrl = str_starts_with($fileUrl, 'http')
            ? $fileUrl
            : rtrim(config('app.url'), '/') . '/' . ltrim($fileUrl, '/');
        $officeViewerUrl = 'https://view.officeapps.live.com/op/embed.aspx?src=' . urlencode($absoluteUrl);
    }

    $typeIcon  = $isPdf ? 'fa-file-pdf' : ($isPptx ? 'fa-file-powerpoint' : 'fa-file');
    $typeLabel = $isPdf ? 'PDF Document' : ($isPptx ? 'PowerPoint Presentation' : strtoupper($ext) . ' File');
@endphp

{{-- Header --}}
<div class="d-flex align-items-center mb-3" style="gap:12px; flex-wrap:wrap;">
    <a href="javascript:history.back()" class="btn btn-sm btn-light">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
    <div>
        <h5 class="mb-0" style="font-weight:700; color:#2d3748; font-size:15px;">
            <i class="fas {{ $typeIcon }} mr-2" style="color:#a02626;"></i>{{ $document->title ?: $document->original_name }}
        </h5>
        <div style="font-size:12px; color:#888; margin-top:2px;">
            <span class="badge badge-warning" style="font-size:10px; margin-right:4px;">{{ $typeLabel }}</span>
            by <strong>{{ $document->trainee->name }}</strong>
            @if($document->trainee->institution) &bull; {{ $document->trainee->institution }} @endif
            &bull; uploaded {{ $document->created_at->format('M j, Y') }}
        </div>
    </div>
    @if($fileUrlEncoded)
    <a href="{{ $fileUrlEncoded }}" download class="btn btn-sm btn-outline-secondary ml-auto">
        <i class="fas fa-download mr-1"></i> Download
    </a>
    @endif
</div>

<div class="row">
    {{-- Viewer --}}
    <div class="col-lg-8 mb-4">
        <div class="card" style="border-radius:10px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,.08);">
            <div class="card-header d-flex align-items-center" style="background:#2d3748; padding:12px 16px;">
                <span style="color:#C9A84C; font-weight:700; font-size:13px;">
                    <i class="fas fa-desktop mr-2"></i>{{ $isPdf ? 'Document Viewer' : 'Slide Viewer' }}
                </span>
            </div>

            @if($isPdf)
                <iframe src="{{ $fileUrlEncoded }}#toolbar=1&view=FitH"
                        style="width:100%; height:620px; border:none; display:block;"
                        title="{{ $document->original_name }}"></iframe>

            @elseif($isPptx && $officeViewerUrl)
                <iframe src="{{ $officeViewerUrl }}"
                        style="width:100%; height:620px; border:none; display:block;"
                        frameborder="0"
                        title="{{ $document->original_name }}"></iframe>

            @else
                <div style="padding:60px 40px; text-align:center; background:#f8f9fa;">
                    <i class="fas {{ $typeIcon }} fa-3x mb-3" style="color:#a02626; display:block;"></i>
                    <h6 style="font-weight:700; color:#2d3748; margin-bottom:8px;">{{ $document->original_name }}</h6>
                    <p style="font-size:13px; color:#888; margin-bottom:16px;">This file type cannot be previewed in the browser.</p>
                    @if($fileUrlEncoded)
                    <a href="{{ $fileUrlEncoded }}" download class="btn btn-sm" style="background:#a02626; color:#fff; font-weight:700;">
                        <i class="fas fa-download mr-2"></i>Download to View
                    </a>
                    @endif
                </div>
            @endif
        </div>
    </div>

    {{-- Comments --}}
    <div class="col-lg-4 mb-4">
        {{-- Post comment --}}
        <div class="card mb-3" style="border-radius:10px; overflow:hidden;">
            <div class="card-header" style="background:#fff; border-left:4px solid #a02626; padding:14px 16px;">
                <strong style="font-size:13px; color:#2d3748;"><i class="fas fa-comment-alt mr-2" style="color:#a02626;"></i>Add Feedback</strong>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.presentations.comment', $document->id) }}" method="POST">
                    @csrf
                    <div class="form-group mb-2">
                        <textarea name="comment" class="form-control" rows="4"
                                  placeholder="Write your feedback on this submission…"
                                  required style="font-size:13px; resize:vertical;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-block btn-sm" style="background:#a02626; color:#fff; font-weight:700;">
                        <i class="fas fa-paper-plane mr-1"></i> Post Comment
                    </button>
                </form>
            </div>
        </div>

        {{-- Existing comments --}}
        <div class="card" style="border-radius:10px; overflow:hidden;">
            <div class="card-header" style="background:#f8f9fa; padding:12px 16px; border-bottom:1px solid #e9ecef;">
                <strong style="font-size:13px; color:#2d3748;">
                    <i class="fas fa-comments mr-2" style="color:#a02626;"></i>
                    Comments ({{ $document->comments->count() }})
                </strong>
            </div>
            <div class="card-body" style="padding:0; max-height:460px; overflow-y:auto;">
                @if($document->comments->isEmpty())
                    <div class="text-center py-4" style="color:#aaa;">
                        <i class="fas fa-comment-slash fa-2x mb-2" style="display:block;"></i>
                        <span style="font-size:13px;">No comments yet.</span>
                    </div>
                @else
                    @foreach($document->comments as $comment)
                    <div style="padding:14px 16px; {{ !$loop->last ? 'border-bottom:1px solid #f0f0f0;' : '' }}">
                        <div class="d-flex align-items-center mb-1" style="gap:8px;">
                            <div style="width:28px;height:28px;border-radius:50%;background:#a02626;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0;">
                                {{ strtoupper(substr($comment->user->name ?? '?', 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-size:12px; font-weight:700; color:#2d3748;">{{ $comment->user->name ?? 'Unknown' }}</div>
                                <div style="font-size:10px; color:#aaa;">{{ $comment->created_at->format('M j, Y \a\t H:i') }}</div>
                            </div>
                        </div>
                        <div style="font-size:13px; color:#333; line-height:1.55; padding-left:36px;">{{ $comment->comment }}</div>
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

</div>
</section>
@endsection
