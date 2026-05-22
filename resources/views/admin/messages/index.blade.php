@extends('layouts.admin')

@section('styles')
<style>
.chat-conv-item {
    display: flex; align-items: center; gap: 12px;
    padding: 12px 16px; border-bottom: 1px solid #f0f2f5;
    text-decoration: none; color: inherit;
    transition: background .12s; cursor: pointer;
    position: relative;
}
.chat-conv-item:hover { background: #f8f9fc; text-decoration: none; color: inherit; }
.chat-conv-item.unread { background: #fffef5; }
.chat-avatar {
    width: 42px; height: 42px; border-radius: 50%;
    background: linear-gradient(135deg, #C9A84C, #9a7d2c);
    display: flex; align-items: center; justify-content: center;
    font-size: 16px; font-weight: 800; color: #fff;
    flex-shrink: 0; position: relative;
}
.chat-avatar.dark   { background: linear-gradient(135deg, #2d3748, #1a202c); }
.chat-avatar.others { background: linear-gradient(135deg, #a02626, #7a1a1a); }
.unread-dot   { position: absolute; bottom: 1px; right: 1px; width: 10px; height: 10px; border-radius: 50%; background: #C9A84C; border: 2px solid #fff; }
.unread-badge { background: #C9A84C; color: #fff; font-size: 10px; font-weight: 700; border-radius: 10px; padding: 1px 6px; min-width: 18px; text-align: center; }
.conv-name    { font-size: 14px; font-weight: 700; color: #1a202c; }
.conv-preview { font-size: 12px; color: #888; margin-top: 1px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 240px; }
.conv-preview.bold { color: #2d3748; font-weight: 600; }
.conv-time    { font-size: 11px; color: #bbb; white-space: nowrap; }
.conv-delete-btn {
    opacity: 0; transition: opacity .15s;
    background: none; border: none; color: #e53e3e; padding: 4px 8px; cursor: pointer; border-radius: 4px; font-size: 13px;
}
.chat-conv-item:hover .conv-delete-btn { opacity: 1; }
.section-label {
    padding: 8px 16px; font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: .6px; color: #aaa; background: #f8f9fa; border-bottom: 1px solid #eee;
}
</style>
@endsection

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="m-0" style="font-size:1.2rem; font-weight:800;">
                <i class="fas fa-comments mr-2" style="color:#C9A84C;"></i>All Conversations
            </h1>
            <button class="btn btn-sm" style="background:#C9A84C; color:#fff; font-weight:700;"
                    data-toggle="modal" data-target="#newChatModal">
                <i class="fas fa-edit mr-1"></i> New Chat
            </button>
        </div>
    </div>
</div>

<section class="content">
<div class="container-fluid">

@if(session('message'))
<div class="alert alert-success alert-dismissible fade show py-2">
    {{ session('message') }}<button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
@endif

<div class="card" style="border-radius:10px; overflow:hidden;">
    @if($conversations->isEmpty())
        <div style="padding:60px; text-align:center;">
            <i class="fas fa-comment-slash" style="font-size:40px; color:#e2e8f0; display:block; margin-bottom:14px;"></i>
            <p style="color:#adb5bd; font-size:14px;">No conversations in the system yet.</p>
        </div>
    @else
        @php
            $myConvs    = $conversations->filter(fn($c) => $c['isOwn']);
            $otherConvs = $conversations->filter(fn($c) => !$c['isOwn']);
        @endphp

        {{-- My conversations --}}
        @if($myConvs->isNotEmpty())
        <div class="section-label"><i class="fas fa-user mr-1"></i> My Conversations</div>
        @foreach($myConvs as $conv)
        @php $other = $conv['other']; $latest = $conv['latest']; $unread = $conv['unread']; @endphp
        <div class="chat-conv-item {{ $unread ? 'unread' : '' }}" style="gap:12px;">
            <a href="{{ route('admin.messages.thread', $other) }}" style="display:flex;align-items:center;gap:12px;flex:1;min-width:0;text-decoration:none;color:inherit;">
                <div class="chat-avatar {{ $unread ? '' : 'dark' }}">
                    {{ strtoupper(substr($other->name ?? 'U', 0, 1)) }}
                    @if($unread)<div class="unread-dot"></div>@endif
                </div>
                <div style="flex:1;min-width:0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="conv-name">{{ $other->name ?? 'Unknown' }}</div>
                        <div class="conv-time">{{ $latest->created_at->diffForHumans(null, true) }}</div>
                    </div>
                    <div class="conv-preview {{ $unread ? 'bold' : '' }}">
                        {{ (int)$latest->sender_id === auth()->id() ? 'You: ' : '' }}{{ Str::limit($latest->body, 60) }}
                    </div>
                </div>
                @if($unread)<div class="unread-badge">{{ $unread }}</div>@endif
            </a>
            <form method="POST" action="{{ route('admin.messages.delete-thread') }}"
                  onsubmit="return confirm('Delete this conversation? This cannot be undone.')">
                @csrf @method('DELETE')
                <input type="hidden" name="sender_id"   value="{{ $conv['sender_id'] }}">
                <input type="hidden" name="receiver_id" value="{{ $conv['receiver_id'] }}">
                <button type="submit" class="conv-delete-btn" title="Delete conversation">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
        </div>
        @endforeach
        @endif

        {{-- Other users' conversations --}}
        @if($otherConvs->isNotEmpty())
        <div class="section-label"><i class="fas fa-users mr-1"></i> Other Conversations ({{ $otherConvs->count() }})</div>
        @foreach($otherConvs as $conv)
        @php
            $sender   = $conv['sender'];
            $receiver = $conv['receiver'];
            $latest   = $conv['latest'];
        @endphp
        <div class="chat-conv-item" style="gap:12px;">
            <a href="{{ route('admin.messages.view-thread', [$sender, $receiver]) }}"
               style="display:flex;align-items:center;gap:12px;flex:1;min-width:0;text-decoration:none;color:inherit;">
                <div style="display:flex;gap:-6px;flex-shrink:0;">
                    <div class="chat-avatar others" style="width:38px;height:38px;font-size:14px;z-index:2;">
                        {{ strtoupper(substr($sender->name ?? 'U', 0, 1)) }}
                    </div>
                    <div class="chat-avatar dark" style="width:38px;height:38px;font-size:14px;margin-left:-10px;">
                        {{ strtoupper(substr($receiver->name ?? 'U', 0, 1)) }}
                    </div>
                </div>
                <div style="flex:1;min-width:0;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="conv-name" style="font-size:13px;">
                            {{ $sender->name ?? '?' }} &harr; {{ $receiver->name ?? '?' }}
                        </div>
                        <div class="conv-time">{{ $latest->created_at->diffForHumans(null, true) }}</div>
                    </div>
                    <div class="conv-preview">{{ Str::limit($latest->body, 65) }}</div>
                </div>
                <span style="font-size:10px;color:#aaa;white-space:nowrap;flex-shrink:0;">
                    {{ $conv['count'] }} msg{{ $conv['count'] !== 1 ? 's' : '' }}
                    <i class="fas fa-eye ml-1"></i>
                </span>
            </a>
            <form method="POST" action="{{ route('admin.messages.delete-thread') }}"
                  onsubmit="return confirm('Delete this conversation? This cannot be undone.')">
                @csrf @method('DELETE')
                <input type="hidden" name="sender_id"   value="{{ $conv['sender_id'] }}">
                <input type="hidden" name="receiver_id" value="{{ $conv['receiver_id'] }}">
                <button type="submit" class="conv-delete-btn" title="Delete conversation">
                    <i class="fas fa-trash-alt"></i>
                </button>
            </form>
        </div>
        @endforeach
        @endif
    @endif
</div>

</div>
</section>

{{-- New Chat Modal --}}
<div class="modal fade" id="newChatModal" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content" style="border-radius:10px;">
            <div class="modal-header" style="border-bottom:2px solid #C9A84C; padding:12px 18px;">
                <h6 class="modal-title" style="font-weight:800; font-size:14px;">
                    <i class="fas fa-edit mr-2" style="color:#C9A84C;"></i>Start a Chat
                </h6>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body" style="padding:14px;">
                <input type="text" id="userSearch" class="form-control form-control-sm" placeholder="Search name…" style="margin-bottom:10px; border-radius:6px;">
                <div id="userList" style="max-height:320px; overflow-y:auto;">
                    @foreach($users as $u)
                    <a href="{{ route('admin.messages.thread', $u) }}"
                       class="d-flex align-items-center user-item"
                       style="text-decoration:none; color:inherit; border-radius:8px; padding:8px; gap:10px;"
                       data-name="{{ strtolower($u->name) }}"
                       onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background=''">
                        <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#C9A84C,#9a7d2c);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0;">
                            {{ strtoupper(substr($u->name, 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-size:13px; font-weight:700; color:#1a202c;">{{ $u->name }}</div>
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
@parent
<script>
document.getElementById('userSearch').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    document.querySelectorAll('.user-item').forEach(function(el) {
        el.style.display = el.dataset.name.includes(q) ? 'flex' : 'none';
    });
});
</script>
@endsection
