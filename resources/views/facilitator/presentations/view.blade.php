@extends('layouts.facilitator')

@section('page-title', 'Review Presentation')

@section('content')
<div class="d-flex align-items-center mb-3" style="gap:12px;">
    <a href="{{ route('facilitator.presentations.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6;">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
    <div>
        <h5 class="mb-0" style="font-weight:700; color:#2d3748; font-size:15px;">
            <i class="fas fa-file-powerpoint mr-2" style="color:#C9A84C;"></i>{{ $document->title ?: $document->original_name }}
        </h5>
        <div style="font-size:12px; color:#888; margin-top:2px;">
            by <strong>{{ $document->trainee->name }}</strong>
            @if($document->trainee->institution) &bull; {{ $document->trainee->institution }} @endif
            &bull; uploaded {{ $document->created_at->format('M j, Y') }}
        </div>
    </div>
</div>

<div class="row">
    {{-- Slide Viewer --}}
    <div class="col-lg-8 mb-4">
        <div class="card shadow-sm" style="border-radius:10px; overflow:hidden;">
            <div class="card-header d-flex align-items-center justify-content-between" style="background:#2d3748; padding:12px 16px;">
                <span style="color:#C9A84C; font-weight:700; font-size:13px;">
                    <i class="fas fa-desktop mr-2"></i>Presentation Viewer
                </span>
                <a href="{{ $document->download_url }}" download
                   class="btn btn-sm" style="background:rgba(255,255,255,0.1); color:#fff; border:1px solid rgba(255,255,255,0.2); font-size:11px;">
                    <i class="fas fa-download mr-1"></i> Download PPTX
                </a>
            </div>

            {{-- Slide nav --}}
            <div id="slide-nav" style="background:#374151; padding:8px 16px; display:none; align-items:center; justify-content:center; gap:12px;">
                <button id="btn-prev" disabled style="background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.2); color:#fff; border-radius:4px; padding:4px 12px; cursor:pointer; font-size:12px;">
                    &#8592; Prev
                </button>
                <span id="slide-counter" style="font-size:13px; color:#ccc;">Loading…</span>
                <button id="btn-next" style="background:rgba(255,255,255,.1); border:1px solid rgba(255,255,255,.2); color:#fff; border-radius:4px; padding:4px 12px; cursor:pointer; font-size:12px;">
                    Next &#8594;
                </button>
            </div>

            {{-- Slide container --}}
            <div id="pptx-container" style="background:#555; min-height:420px; padding:20px; display:flex; flex-direction:column; align-items:center; justify-content:center; gap:12px; overflow:auto;">
                <div id="pptx-loading" style="display:flex; flex-direction:column; align-items:center; gap:14px; color:#ccc;">
                    <div style="width:40px;height:40px;border:4px solid rgba(255,255,255,.2);border-top-color:#C9A84C;border-radius:50%;animation:spin .8s linear infinite;"></div>
                    <span style="font-size:14px;">Loading slides…</span>
                </div>
            </div>
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
                                  placeholder="Write your feedback or comments on this presentation…"
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
                    <div style="padding:14px 16px; {{ !$loop->last ? 'border-bottom:1px solid #f0f0f0;' : '' }}">
                        <div class="d-flex align-items-center mb-1" style="gap:8px;">
                            <div style="width:28px;height:28px;border-radius:50%;background:#C9A84C;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0;">
                                {{ strtoupper(substr($comment->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-size:12px; font-weight:700; color:#2d3748;">{{ $comment->user->name }}</div>
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
@endsection

@section('styles')
<style>
@keyframes spin { to { transform:rotate(360deg); } }
#pptx-container img { max-width:100%; border-radius:4px; box-shadow:0 4px 16px rgba(0,0,0,.4); }
</style>
@endsection

@section('scripts')
<script>
$(function() {
    var renderUrl = "{{ route('trainee-document.render-slides', $document->id) }}";
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
        $('#pptx-loading').remove();
        $('#pptx-container').css({'justify-content':'center','align-items':'center'}).html(
            '<div style="text-align:center; color:#ccc; padding:30px;">' +
            '<i class="fas fa-exclamation-triangle" style="font-size:32px; color:#e67e22; display:block; margin-bottom:12px;"></i>' +
            '<p style="font-size:13px;">' + msg + '</p>' +
            '<a href="{{ $document->download_url }}" download class="btn btn-sm" style="background:#C9A84C;color:#fff;font-size:12px;margin-top:6px;">Download to view</a>' +
            '</div>'
        );
    }

    $.ajax({
        url: renderUrl, method: 'GET', timeout: 60000,
        success: function(data) {
            if (data.error) { showError(data.error); return; }
            if (!data.slides || !data.slides.length) { showError('No slides found in this presentation.'); return; }
            slides = data.slides;
            $('#pptx-loading').remove();
            $('#pptx-container').css({'align-items':'center','justify-content':'center','padding':'20px'});
            slides.forEach(function(html, i) {
                var $s = $(html).attr('data-slide', i+1);
                if (i !== 0) $s.hide();
                $s.css({'box-shadow':'0 6px 24px rgba(0,0,0,.5)','border-radius':'4px','margin':'auto','max-width':'100%'});
                $('#pptx-container').append($s);
            });
            if (slides.length > 1) {
                $('#slide-nav').css('display','flex');
                $('#btn-prev').on('click', function() { if (current > 0) showSlide(current-1); });
                $('#btn-next').on('click', function() { if (current < slides.length-1) showSlide(current+1); });
            }
            $('#slide-counter').text('Slide 1 / ' + slides.length);
            $('#btn-next').prop('disabled', slides.length <= 1);
            $(document).on('keydown', function(e) {
                if (e.key === 'ArrowRight') { if (current < slides.length-1) showSlide(current+1); }
                else if (e.key === 'ArrowLeft') { if (current > 0) showSlide(current-1); }
            });
        },
        error: function() { showError('Could not load slides. Try downloading the file to view locally.'); }
    });
});
</script>
@endsection
