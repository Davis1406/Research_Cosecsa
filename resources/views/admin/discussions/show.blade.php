@extends('layouts.admin')

@section('content')
<div class="row mb-2">
    <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
            <i class="fas fa-comments mr-2" style="color:#C9A84C;"></i> {{ $discussion->title }}
        </h5>
        <a href="{{ route('admin.discussions.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:13px;">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
    </div>
</div>

{{-- Original post --}}
<div class="card shadow-sm mb-3" style="border-radius:8px; border-left:4px solid #C9A84C;">
    <div class="card-body">
        <div class="d-flex align-items-center mb-2" style="gap:10px;">
            <div style="width:38px;height:38px;border-radius:50%;background:#C9A84C;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;color:#fff;flex-shrink:0;">
                {{ strtoupper(substr($discussion->user?->name ?? 'U', 0, 1)) }}
            </div>
            <div>
                <div style="font-weight:700; font-size:13px; color:#2d3748;">{{ $discussion->user?->name ?? 'Unknown' }}</div>
                <div style="font-size:11px; color:#aaa;">{{ $discussion->created_at->format('M j, Y \a\t g:i A') }}</div>
            </div>
            <div class="ml-auto d-flex align-items-center" style="gap:6px;">
                @if($discussion->is_general)
                    <span class="badge" style="background:#e8f5e9; color:#2c7a4b; font-size:10px;">General</span>
                @endif
                @if($discussion->schedule)
                    <span class="badge" style="background:#fff8e6; color:#9a7d2c; font-size:10px;">
                        <i class="fas fa-calendar-alt mr-1"></i>{{ $discussion->schedule->title }}
                    </span>
                @endif
                <form action="{{ route('admin.discussions.destroy', $discussion) }}" method="POST" style="margin:0;"
                      onsubmit="return confirm('Delete this discussion and all its replies?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-sm" style="background:#fff5f5; color:#e53e3e; border:1px solid #fed7d7; font-size:11px;">
                        <i class="fas fa-trash mr-1"></i> Delete Discussion
                    </button>
                </form>
            </div>
        </div>
        <p style="font-size:14px; color:#444; line-height:1.6; white-space:pre-wrap; margin-bottom:0;">{{ $discussion->body }}</p>
    </div>
</div>

{{-- Replies --}}
<div class="mb-2" style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:#aaa;">
    {{ $discussion->replies->count() }} {{ Str::plural('Reply', $discussion->replies->count()) }}
</div>

@foreach($discussion->replies as $reply)
<div class="card shadow-sm mb-2" style="border-radius:8px; margin-left:20px;">
    <div class="card-body py-3">
        <div class="d-flex align-items-center mb-2" style="gap:8px;">
            <div style="width:32px;height:32px;border-radius:50%;background:#2d3748;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;color:#fff;flex-shrink:0;">
                {{ strtoupper(substr($reply->user?->name ?? 'U', 0, 1)) }}
            </div>
            <div style="flex:1;">
                <div style="font-weight:700; font-size:12.5px; color:#2d3748;">{{ $reply->user?->name ?? 'Unknown' }}</div>
                <div style="font-size:11px; color:#aaa;">{{ $reply->created_at->diffForHumans() }}</div>
            </div>
        </div>
        <p style="font-size:13px; color:#444; line-height:1.6; white-space:pre-wrap; margin-bottom:0;">{{ $reply->body }}</p>
    </div>
</div>
@endforeach

{{-- Admin reply form --}}
<div class="card shadow-sm mt-3" style="border-radius:8px; border-left:4px solid #2c7a4b;">
    <div class="card-header" style="background:#f8f9fa; font-weight:700; font-size:13px; color:#2d3748; border-bottom:1px solid #e9ecef;">
        <i class="fas fa-reply mr-2" style="color:#2c7a4b;"></i> Admin Reply
    </div>
    <div class="card-body">
        <form action="{{ route('admin.discussions.reply', $discussion) }}" method="POST">
            @csrf
            <div class="form-group mb-2">
                <textarea name="body" class="form-control" rows="3" required placeholder="Write your reply...">{{ old('body') }}</textarea>
            </div>
            <div class="text-right">
                <button type="submit" class="btn btn-sm" style="background:#2c7a4b; color:#fff; font-weight:700; padding:7px 18px;">
                    <i class="fas fa-paper-plane mr-1"></i> Post Reply
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
