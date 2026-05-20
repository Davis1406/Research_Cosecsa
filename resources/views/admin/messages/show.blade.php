@extends('layouts.admin')

@section('content')
<div class="row mb-2">
    <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
            <i class="fas fa-envelope-open mr-2" style="color:#C9A84C;"></i> Message
        </h5>
        <a href="{{ route('admin.messages.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:12px;">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
    </div>
</div>

<div class="card shadow-sm" style="border-radius:8px; max-width:760px;">
    <div class="card-header" style="background:#f8f9fa; border-bottom:1px solid #e9ecef;">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <div style="font-size:16px; font-weight:700; color:#2d3748; margin-bottom:6px;">{{ $message->subject }}</div>
                <div style="font-size:12px; color:#666;">
                    <i class="fas fa-user mr-1"></i>
                    <strong>From:</strong> {{ $message->sender?->name ?? 'Unknown' }}
                    &nbsp;&bull;&nbsp;
                    <strong>To:</strong> {{ $message->receiver?->name ?? 'Unknown' }}
                </div>
            </div>
            <div style="font-size:11px; color:#aaa; text-align:right;">
                {{ $message->created_at->format('M j, Y \a\t g:i A') }}
                @if($message->read_at)
                <br><span style="color:#2c7a4b;"><i class="fas fa-check-double mr-1"></i>Read {{ $message->read_at->diffForHumans() }}</span>
                @else
                <br><span style="color:#aaa;"><i class="fas fa-clock mr-1"></i>Unread</span>
                @endif
            </div>
        </div>
    </div>
    <div class="card-body">
        <p style="font-size:14px; color:#444; line-height:1.7; white-space:pre-wrap; margin-bottom:0;">{{ $message->body }}</p>

        @if($message->material)
        <div style="margin-top:16px; padding:12px 14px; background:#fff8e6; border:1px solid #C9A84C44; border-radius:6px; display:flex; align-items:center; gap:10px;">
            <i class="fas fa-paperclip" style="color:#C9A84C;"></i>
            <div>
                <div style="font-size:12px; font-weight:700; color:#2d3748;">Attached Material</div>
                <a href="{{ route('material.view', $message->material) }}" style="font-size:13px; color:#C9A84C; text-decoration:none; font-weight:600;">
                    {{ $message->material->title }}
                    <span style="color:#888; font-weight:400;">({{ ucfirst($message->material->type) }})</span>
                </a>
            </div>
        </div>
        @endif
    </div>
    <div class="card-footer" style="background:#f8f9fa; border-top:1px solid #e9ecef; text-align:right;">
        <form action="{{ route('admin.messages.destroy', $message) }}" method="POST" style="display:inline;"
              onsubmit="return confirm('Delete this message?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm" style="background:#fff5f5; color:#e53e3e; border:1px solid #fed7d7; font-size:12px;">
                <i class="fas fa-trash mr-1"></i> Delete
            </button>
        </form>
    </div>
</div>
@endsection
