@extends('layouts.facilitator')

@section('page-title', 'Review Presentation')

@section('content')
@php
    $ext    = strtolower(pathinfo($document->original_name, PATHINFO_EXTENSION));
    $isPdf  = $ext === 'pdf';
    $isPptx = in_array($ext, ['pptx', 'ppt']);
    $isDock = in_array($ext, ['doc', 'docx']);
    $fileUrl = $document->download_url;

    // Office Online embed URL (files are publicly stored via storage symlink)
    $officeViewerUrl = null;
    if ($isPptx && $fileUrl) {
        $absoluteUrl = str_starts_with($fileUrl, 'http')
            ? $fileUrl
            : rtrim(config('app.url'), '/') . '/' . ltrim($fileUrl, '/');
        $officeViewerUrl = 'https://view.officeapps.live.com/op/embed.aspx?src=' . rawurlencode($absoluteUrl);
    }

    $typeIcon  = $isPdf ? 'fa-file-pdf' : ($isPptx ? 'fa-file-powerpoint' : 'fa-file');
    $typeLabel = $isPdf ? 'PDF Document' : ($isPptx ? 'PowerPoint Presentation' : strtoupper($ext) . ' File');
@endphp

<div class="d-flex align-items-center mb-3" style="gap:12px;">
    <a href="{{ route('facilitator.presentations.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6;">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
    <div>
        <h5 class="mb-0" style="font-weight:700; color:#2d3748; font-size:15px;">
            <i class="fas {{ $typeIcon }} mr-2" style="color:#C9A84C;"></i>{{ $document->title ?: $document->original_name }}
        </h5>
        <div style="font-size:12px; color:#888; margin-top:2px;">
            <span class="badge" style="background:#f0e6c8; color:#7a5c00; border:1px solid #C9A84C55; font-size:10px; margin-right:4px;">{{ $typeLabel }}</span>
            by <strong>{{ $document->trainee->name }}</strong>
            @if($document->trainee->institution) &bull; {{ $document->trainee->institution }} @endif
            &bull; uploaded {{ $document->created_at->format('M j, Y') }}
        </div>
    </div>
</div>

<div class="row">
    {{-- Viewer Column --}}
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm" style="border-radius:10px; overflow:hidden;">
            <div class="card-header d-flex align-items-center justify-content-between" style="background:#2d3748; padding:12px 16px;">
                <span style="color:#C9A84C; font-weight:700; font-size:13px;">
                    <i class="fas fa-desktop mr-2"></i>{{ $isPdf ? 'Document Viewer' : 'Slide Viewer' }}
                </span>
                <a href="{{ $fileUrl }}" download
                   class="btn btn-sm" style="background:rgba(255,255,255,0.1); color:#fff; border:1px solid rgba(255,255,255,0.2); font-size:11px;">
                    <i class="fas fa-download mr-1"></i> Download
                </a>
            </div>

            @if($isPdf)
                {{-- PDF: embed directly in iframe --}}
                <iframe src="{{ $fileUrl }}#toolbar=1&view=FitH"
                        style="width:100%; height:600px; border:none; display:block;"
                        title="{{ $document->original_name }}"></iframe>

            @elseif($isPptx)
                {{-- PPTX: Microsoft Office Online embed (full theme fidelity) --}}
                @if($officeViewerUrl)
                    <iframe src="{{ $officeViewerUrl }}"
                            style="width:100%; height:600px; border:none; display:block;"
                            frameborder="0"
                            title="{{ $document->original_name }}"></iframe>
                @else
                    <div style="padding:60px 40px; text-align:center; background:#f8f9fa;">
                        <i class="fas fa-file-powerpoint fa-3x mb-3" style="color:#C9A84C; display:block;"></i>
                        <p style="font-size:13px; color:#888; margin-bottom:16px;">Preview not available.</p>
                        <a href="{{ $fileUrl }}" download class="btn btn-sm" style="background:#C9A84C;color:#fff;font-weight:700;">
                            <i class="fas fa-download mr-1"></i> Download to View
                        </a>
                    </div>
                @endif

            @else
                {{-- Other file types: download prompt --}}
                <div style="padding:60px 40px; text-align:center; background:#f8f9fa;">
                    <i class="fas fa-file fa-3x mb-3" style="color:#C9A84C; display:block;"></i>
                    <h6 style="font-weight:700; color:#2d3748; margin-bottom:8px;">{{ $document->original_name }}</h6>
                    <p style="font-size:13px; color:#888; margin-bottom:16px;">This file type cannot be previewed in the browser.</p>
                    <a href="{{ $fileUrl }}" download class="btn" style="background:#C9A84C; color:#fff; font-weight:700; font-size:13px;">
                        <i class="fas fa-download mr-2"></i>Download to View
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Comments Panel --}}
    <div class="col-lg-4 mb-4">
        {{-- Add comment form --}}
        <div class="card shadow-sm mb-3" style="border-radius:10px; overflow:hidden;">
            <div class="card-header" style="background:#fff; border-left:4px solid #C9A84C; padding:14px 16px;">
                <strong style="font-size:13px; color:#2d3748;"><i class="fas fa-comment-alt mr-2" style="color:#C9A84C;"></i>Add Feedback</strong>
            </div>
            <div class="card-body" style="padding:16px;">
                <form action="{{ route('facilitator.presentations.comment', $document->id) }}" method="POST">
                    @csrf
                    <div class="form-group mb-2">
                        <textarea name="comment" class="form-control" rows="4"
                                  placeholder="Write your feedback or comments on this submission…"
                                  required style="font-size:13px; resize:vertical;"></textarea>
                    </div>
                    <button type="submit" class="btn btn-block btn-sm" style="background:#C9A84C; color:#fff; font-weight:700; font-size:13px;">
                        <i class="fas fa-paper-plane mr-1"></i> Post Comment
                    </button>
                </form>
            </div>
        </div>

        {{-- Existing comments --}}
        <div class="card shadow-sm" style="border-radius:10px; overflow:hidden;">
            <div class="card-header" style="background:#f8f9fa; padding:12px 16px; border-bottom:1px solid #e9ecef;">
                <strong style="font-size:13px; color:#2d3748;">
                    <i class="fas fa-comments mr-2" style="color:#C9A84C;"></i>
                    Comments ({{ $document->comments->count() }})
                </strong>
            </div>
            <div class="card-body" style="padding:0; max-height:450px; overflow-y:auto;">
                @if($document->comments->isEmpty())
                    <div class="text-center py-4" style="color:#aaa;">
                        <i class="fas fa-comment-slash fa-2x mb-2" style="display:block;"></i>
                        <span style="font-size:13px;">No comments yet. Be the first!</span>
                    </div>
                @else
                    @foreach($document->comments as $comment)
                    @php $isMine = $comment->user_id === auth()->id(); @endphp
                    <div style="padding:14px 16px; {{ !$loop->last ? 'border-bottom:1px solid #f0f0f0;' : '' }}">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <div class="d-flex align-items-center" style="gap:8px;">
                                <div style="width:28px;height:28px;border-radius:50%;background:#C9A84C;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0;">
                                    {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div style="font-size:12px; font-weight:700; color:#2d3748;">{{ $comment->user->name }}</div>
                                    <div style="font-size:10px; color:#aaa;">
                                        {{ $comment->created_at->format('M j, Y \a\t H:i') }}
                                        @if($comment->updated_at->gt($comment->created_at->addMinute()))
                                            <span style="font-style:italic;">(edited)</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($isMine)
                            <button type="button"
                                    onclick="toggleEditComment({{ $comment->id }})"
                                    style="background:none;border:none;padding:2px 6px;cursor:pointer;color:#aaa;font-size:11px;"
                                    title="Edit comment">
                                <i class="fas fa-pencil-alt"></i>
                            </button>
                            @endif
                        </div>

                        {{-- Read view --}}
                        <div id="comment-text-{{ $comment->id }}" style="font-size:13px; color:#333; line-height:1.55; padding-left:36px;">
                            {{ $comment->comment }}
                        </div>

                        {{-- Inline edit form (hidden by default) --}}
                        @if($isMine)
                        <div id="comment-edit-{{ $comment->id }}" style="display:none; padding-left:36px; margin-top:8px;">
                            <form action="{{ route('facilitator.presentations.comment.update', $comment->id) }}" method="POST">
                                @csrf @method('PUT')
                                <textarea name="comment" rows="3"
                                          class="form-control mb-2"
                                          style="font-size:13px; resize:vertical;">{{ $comment->comment }}</textarea>
                                <div style="display:flex; gap:6px;">
                                    <button type="submit" class="btn btn-sm"
                                            style="background:#C9A84C; color:#fff; font-weight:700; font-size:12px;">
                                        <i class="fas fa-check mr-1"></i> Save
                                    </button>
                                    <button type="button" class="btn btn-sm"
                                            onclick="toggleEditComment({{ $comment->id }})"
                                            style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:12px;">
                                        Cancel
                                    </button>
                                </div>
                            </form>
                        </div>
                        @endif
                    </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function toggleEditComment(id) {
    var textEl = document.getElementById('comment-text-' + id);
    var editEl = document.getElementById('comment-edit-' + id);
    var isEditing = editEl.style.display !== 'none';
    textEl.style.display = isEditing ? 'block' : 'none';
    editEl.style.display = isEditing ? 'none'  : 'block';
    if (!isEditing) {
        // Focus the textarea when opening
        editEl.querySelector('textarea').focus();
    }
}
</script>
@endsection

