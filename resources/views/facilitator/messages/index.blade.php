@extends('layouts.facilitator')
@section('page-title', 'Messages')

@section('styles')
<style>
.chat-conv-item {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 16px; border-bottom: 1px solid #f0f2f5;
    text-decoration: none; color: inherit;
    transition: background .12s;
    cursor: pointer;
}
.chat-conv-item:hover { background: #f8f9fc; text-decoration: none; color: inherit; }
.chat-conv-item.unread { background: #fffef5; }
.chat-avatar {
    width: 44px; height: 44px; border-radius: 50%;
    background: linear-gradient(135deg, #C9A84C, #9a7d2c);
    display: flex; align-items: center; justify-content: center;
    font-size: 17px; font-weight: 800; color: #fff;
    flex-shrink: 0; position: relative;
}
.chat-avatar.dark { background: linear-gradient(135deg, #2d3748, #1a202c); }
.unread-dot {
    position: absolute; bottom: 1px; right: 1px;
    width: 11px; height: 11px; border-radius: 50%;
    background: #C9A84C; border: 2px solid #fff;
}
.chat-conv-name { font-size: 14px; font-weight: 700; color: #1a202c; }
.chat-conv-preview { font-size: 12.5px; color: #888; margin-top: 1px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 220px; }
.chat-conv-preview.bold { color: #2d3748; font-weight: 600; }
.chat-conv-time { font-size: 11px; color: #bbb; white-space: nowrap; }
.unread-badge {
    background: #C9A84C; color: #fff; font-size: 10px; font-weight: 700;
    border-radius: 10px; padding: 1px 6px; min-width: 18px; text-align: center;
}
.new-chat-btn {
    display: flex; align-items: center; justify-content: center; gap: 8px;
    background: #C9A84C; color: #fff; font-weight: 700; font-size: 13.5px;
    border-radius: 8px; padding: 9px 18px; border: none; text-decoration: none;
    transition: background .15s;
}
.new-chat-btn:hover { background: #a88838; color: #fff; text-decoration: none; }
.empty-state {
    padding: 60px 24px; text-align: center;
}
.empty-state i { font-size: 48px; color: #e2e8f0; display: block; margin-bottom: 14px; }
.empty-state p { color: #a0aec0; font-size: 14.5px; }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0" style="font-weight:800; color:#1a202c; font-size:1.1rem;">
            <i class="fas fa-comments mr-2" style="color:#C9A84C;"></i> Messages
        </h5>
        <p class="mb-0 mt-1" style="font-size:13px; color:#718096;">Chat with facilitators and trainees</p>
    </div>
    <button class="new-chat-btn" data-toggle="modal" data-target="#newChatModal">
        <i class="fas fa-edit"></i> New Chat
    </button>
</div>

@if(session('message'))
<div class="alert alert-success py-2">{{ session('message') }}</div>
@endif

<div class="card" style="border-radius:12px; border:1.5px solid #e2e8f0; overflow:hidden; box-shadow:0 2px 12px rgba(0,0,0,.05);">
    @if($conversations->isEmpty())
        <div class="empty-state">
            <i class="fas fa-comment-slash"></i>
            <p>No conversations yet.<br>Start one by clicking <strong>New Chat</strong>.</p>
        </div>
    @else
        @foreach($conversations as $conv)
        @php
            $other  = $conv['other'];
            $latest = $conv['latest'];
            $unread = $conv['unread'];
        @endphp
        <a href="{{ route('facilitator.messages.thread', $other) }}" class="chat-conv-item {{ $unread ? 'unread' : '' }}">
            <div class="chat-avatar {{ $unread ? '' : 'dark' }}">
                {{ strtoupper(substr($other->name ?? 'U', 0, 1)) }}
                @if($unread)<div class="unread-dot"></div>@endif
            </div>
            <div style="flex:1; min-width:0;">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="chat-conv-name">{{ $other->name ?? 'Unknown' }}</div>
                    <div class="chat-conv-time">{{ $latest->created_at->diffForHumans(null, true) }}</div>
                </div>
                <div class="chat-conv-preview {{ $unread ? 'bold' : '' }}">
                    {{ $latest->sender_id === auth()->id() ? 'You: ' : '' }}{{ Str::limit($latest->body, 60) }}
                </div>
            </div>
            @if($unread)
            <div class="unread-badge">{{ $unread }}</div>
            @endif
        </a>
        @endforeach
    @endif
</div>

{{-- New Chat Modal --}}
<div class="modal fade" id="newChatModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="border-radius:12px; border:1.5px solid #e2e8f0;">
            <div class="modal-header" style="border-bottom:2px solid #C9A84C; padding:14px 20px;">
                <h6 class="modal-title" style="font-weight:800; color:#1a202c; font-size:14px;">
                    <i class="fas fa-edit mr-2" style="color:#C9A84C;"></i>Start a Chat
                </h6>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" style="padding:16px;">
                <input type="text" id="userSearch" class="form-control" placeholder="Search name..." style="margin-bottom:10px;">
                <div id="userList" style="max-height:320px; overflow-y:auto;">
                    @foreach($users as $u)
                    <a href="{{ route('facilitator.messages.thread', $u) }}"
                       class="d-flex align-items-center gap-2 p-2 user-item"
                       style="text-decoration:none; color:inherit; border-radius:8px; gap:10px; display:flex !important;"
                       data-name="{{ strtolower($u->name) }}">
                        <div style="width:36px;height:36px;border-radius:50%;background:linear-gradient(135deg,#C9A84C,#9a7d2c);display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#fff;flex-shrink:0;">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-size:13.5px; font-weight:700; color:#1a202c;">{{ $u->name }}</div>
                            <div style="font-size:11px; color:#aaa;">{{ $u->roles->first()?->title ?? 'User' }}</div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.getElementById('userSearch').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('.user-item').forEach(function(el) {
        el.style.display = el.dataset.name.includes(q) ? 'flex' : 'none';
    });
});
</script>
@endsection
