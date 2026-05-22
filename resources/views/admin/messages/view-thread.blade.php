@extends('layouts.admin')
@section('page-title', $sender->name . ' ↔ ' . $receiver->name)

@section('styles')
<style>
/* ── Chat layout ── */
.chat-wrap {
    display: flex;
    flex-direction: column;
    height: calc(100vh - 200px);
    min-height: 500px;
    background: #fff;
    border-radius: 14px;
    border: 1.5px solid #e2e8f0;
    box-shadow: 0 2px 12px rgba(0,0,0,.06);
    overflow: hidden;
}

/* ── Header ── */
.chat-header {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 18px;
    border-bottom: 1.5px solid #e2e8f0;
    background: #fff;
    flex-shrink: 0;
}
.chat-header-avatar {
    width: 38px; height: 38px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 15px; font-weight: 800; color: #fff;
}
.chat-header-name { font-weight: 800; font-size: 15px; color: #1a202c; }
.chat-header-sub  { font-size: 12px; color: #a0aec0; }

/* ── Message area ── */
.chat-body {
    flex: 1;
    overflow-y: auto;
    padding: 20px 18px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    background: #f8f9fc;
}

/* ── Date divider ── */
.chat-date-divider {
    text-align: center;
    font-size: 11px;
    color: #a0aec0;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    margin: 10px 0 4px;
}

/* ── Bubble ── */
.bubble-row {
    display: flex;
    align-items: flex-end;
    gap: 8px;
}
.bubble-row.right { flex-direction: row-reverse; }

.bubble {
    max-width: 68%;
    padding: 10px 14px;
    border-radius: 18px;
    font-size: 14px;
    line-height: 1.55;
    word-break: break-word;
    position: relative;
}
.bubble.left-bubble {
    background: #fff;
    color: #1a202c;
    border: 1.5px solid #e8ecf0;
    border-bottom-left-radius: 4px;
}
.bubble.right-bubble {
    background: linear-gradient(135deg, #a02626, #7a1a1a);
    color: #fff;
    border-bottom-right-radius: 4px;
}
.bubble-time {
    font-size: 10.5px;
    margin-top: 4px;
    text-align: right;
    opacity: .65;
}
.bubble.left-bubble .bubble-time { text-align: left; }

.bubble-mini-avatar {
    width: 28px; height: 28px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; font-weight: 800; color: #fff;
    flex-shrink: 0; margin-bottom: 2px;
}

/* ── Attachment / material preview in bubble ── */
.bubble-attachment {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-top: 8px;
    padding: 8px 10px;
    border-radius: 8px;
    font-size: 12.5px;
    font-weight: 600;
}
.bubble.left-bubble .bubble-attachment  { background: #f0f2f5; color: #2d3748; }
.bubble.right-bubble .bubble-attachment { background: rgba(255,255,255,.18); color: #fff; }
.bubble-attachment i { font-size: 16px; }

/* ── Read-only banner ── */
.readonly-banner {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    padding: 10px 18px;
    background: #fffef5;
    border-top: 1.5px solid #e2e8f0;
    font-size: 12px; color: #a0aec0; font-weight: 600;
    flex-shrink: 0;
}

/* responsive */
@media (max-width: 576px) {
    .bubble { max-width: 88%; }
    .chat-wrap { height: calc(100vh - 130px); }
}
</style>
@endsection

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between">
            <a href="{{ route('admin.messages.index') }}" style="font-size:13px; color:#718096; text-decoration:none; font-weight:600;">
                <i class="fas fa-arrow-left mr-1"></i> Back to Messages
            </a>
            <form method="POST" action="{{ route('admin.messages.delete-thread') }}"
                  onsubmit="return confirm('Delete this entire conversation? This cannot be undone.')">
                @csrf @method('DELETE')
                @php $ids = [(int)$sender->id, (int)$receiver->id]; sort($ids); @endphp
                <input type="hidden" name="sender_id"   value="{{ $ids[0] }}">
                <input type="hidden" name="receiver_id" value="{{ $ids[1] }}">
                <button type="submit" class="btn btn-sm"
                        style="background:#fee2e2; color:#c53030; border:none; font-weight:700; border-radius:8px; font-size:12px;">
                    <i class="fas fa-trash-alt mr-1"></i> Delete Conversation
                </button>
            </form>
        </div>
    </div>
</div>

<section class="content"><div class="container-fluid">
<div class="chat-wrap">

    {{-- Header --}}
    <div class="chat-header">
        {{-- Sender avatar --}}
        <div style="display:flex; align-items:center; gap:-4px;">
            <div class="chat-header-avatar"
                 style="background:linear-gradient(135deg,#a02626,#7a1a1a); z-index:2;">
                {{ strtoupper(substr($sender->name ?? 'U', 0, 1)) }}
            </div>
            <div class="chat-header-avatar"
                 style="background:linear-gradient(135deg,#2d3748,#1a202c); margin-left:-8px; z-index:1;">
                {{ strtoupper(substr($receiver->name ?? 'U', 0, 1)) }}
            </div>
        </div>
        <div style="flex:1;">
            <div class="chat-header-name">
                {{ $sender->name ?? 'Unknown' }}
                <span style="color:#a0aec0; font-weight:500; font-size:13px;"> ↔ </span>
                {{ $receiver->name ?? 'Unknown' }}
            </div>
            <div class="chat-header-sub">{{ $messages->count() }} message{{ $messages->count() !== 1 ? 's' : '' }}</div>
        </div>
        <span style="font-size:11px; background:#f0f4ff; color:#667eea; padding:3px 10px; border-radius:20px; font-weight:700; letter-spacing:.4px;">
            <i class="fas fa-eye mr-1"></i> READ-ONLY
        </span>
    </div>

    {{-- Messages --}}
    <div class="chat-body" id="chatBody">
        @php $lastDate = null; @endphp
        @forelse($messages as $msg)
            @php
                $isSender  = (int)$msg->sender_id === (int)$sender->id;
                $author    = $isSender ? $sender : $receiver;
                $msgDate   = $msg->created_at->format('Y-m-d');
            @endphp

            @if($msgDate !== $lastDate)
            <div class="chat-date-divider">
                {{ $msg->created_at->isToday() ? 'Today' : ($msg->created_at->isYesterday() ? 'Yesterday' : $msg->created_at->format('M j, Y')) }}
            </div>
            @php $lastDate = $msgDate; @endphp
            @endif

            <div class="bubble-row {{ $isSender ? '' : 'right' }}" data-id="{{ $msg->id }}">
                {{-- Mini avatar --}}
                <div class="bubble-mini-avatar"
                     style="background:{{ $isSender ? 'linear-gradient(135deg,#a02626,#7a1a1a)' : 'linear-gradient(135deg,#2d3748,#1a202c)' }};">
                    {{ strtoupper(substr($author->name ?? 'U', 0, 1)) }}
                </div>

                <div class="bubble {{ $isSender ? 'left-bubble' : 'right-bubble' }}">
                    {{-- Sender label (small) --}}
                    <div style="font-size:10px; font-weight:700; opacity:.6; margin-bottom:3px;">
                        {{ $author->name ?? 'Unknown' }}
                    </div>

                    <div>{{ $msg->body }}</div>

                    @if($msg->material)
                    <div class="bubble-attachment">
                        <i class="fas fa-paperclip"></i>
                        <a href="{{ route('admin.training-materials.viewer', $msg->material) }}"
                           target="_blank"
                           style="color:inherit; text-decoration:underline;">
                            {{ $msg->material->title }}
                            <span style="opacity:.7; font-weight:400;">({{ ucfirst($msg->material->type) }})</span>
                        </a>
                    </div>
                    @endif

                    @if($msg->attachment_path)
                    @php $mime = $msg->attachment_mime ?? ''; @endphp
                    <div class="bubble-attachment">
                        <i class="fas fa-{{ str_contains($mime,'image') ? 'image' : (str_contains($mime,'pdf') ? 'file-pdf' : (str_contains($mime,'video') ? 'film' : (str_contains($mime,'audio') ? 'music' : 'file'))) }}"></i>
                        <a href="{{ asset('storage/'.$msg->attachment_path) }}"
                           target="_blank"
                           download="{{ $msg->attachment_name }}"
                           style="color:inherit; text-decoration:underline; word-break:break-all;">
                            {{ $msg->attachment_name }}
                        </a>
                    </div>
                    @if(str_contains($mime,'image'))
                    <img src="{{ asset('storage/'.$msg->attachment_path) }}"
                         alt="{{ $msg->attachment_name }}"
                         style="max-width:220px; border-radius:8px; margin-top:6px; display:block;">
                    @endif
                    @endif

                    <div class="bubble-time">{{ $msg->created_at->format('g:i A') }}</div>
                </div>
            </div>
        @empty
            <div style="text-align:center; padding:60px 20px;">
                <i class="fas fa-comment-slash" style="font-size:40px; color:#e2e8f0; display:block; margin-bottom:12px;"></i>
                <p style="color:#a0aec0; font-size:14px;">No messages in this conversation.</p>
            </div>
        @endforelse
    </div>

    {{-- Read-only footer --}}
    <div class="readonly-banner">
        <i class="fas fa-lock" style="color:#C9A84C;"></i>
        You are viewing this conversation as an administrator. You cannot reply.
    </div>

</div>
</div></section>
@endsection

@section('scripts')
<script>
// Scroll to bottom on load
(function() {
    var body = document.getElementById('chatBody');
    if (body) body.scrollTop = body.scrollHeight;
})();
</script>
@endsection
