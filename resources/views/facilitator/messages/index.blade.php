@extends('layouts.facilitator')

@section('page-title', 'Messages')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
        <i class="fas fa-envelope mr-2" style="color:#C9A84C;"></i> Messages
        @if($unreadCount > 0)
        <span style="background:#e53e3e; color:#fff; font-size:10px; font-weight:700; border-radius:10px; padding:1px 7px; margin-left:6px;">{{ $unreadCount }}</span>
        @endif
    </h5>
    <a href="{{ route('facilitator.messages.compose') }}" class="btn btn-sm" style="background:#C9A84C; color:#fff; font-weight:700; font-size:13px; border-radius:5px;">
        <i class="fas fa-pen mr-1"></i> Compose
    </a>
</div>

<ul class="nav nav-tabs mb-3" id="msgTabs">
    <li class="nav-item">
        <a class="nav-link active" id="inbox-tab" data-toggle="tab" href="#inbox" style="font-weight:600; font-size:13px;">
            Inbox
            @if($unreadCount > 0)
            <span style="background:#e53e3e; color:#fff; font-size:9px; font-weight:700; border-radius:10px; padding:0 5px; margin-left:4px;">{{ $unreadCount }}</span>
            @endif
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="sent-tab" data-toggle="tab" href="#sent" style="font-weight:600; font-size:13px;">
            Sent ({{ $sent->count() }})
        </a>
    </li>
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="inbox">
        @if($inbox->isEmpty())
            <div class="alert alert-info">Your inbox is empty.</div>
        @else
        <div class="card shadow-sm" style="border-radius:8px; overflow:hidden;">
            @foreach($inbox as $msg)
            <a href="{{ route('facilitator.messages.show', $msg) }}" style="text-decoration:none; color:inherit; display:block;">
                <div style="padding:12px 16px; border-bottom:1px solid #f0f0f0; background:{{ $msg->read_at ? '#fff' : '#fffdf5' }}; display:flex; align-items:center; gap:12px; transition:background 0.15s;"
                     onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='{{ $msg->read_at ? '#fff' : '#fffdf5' }}'">
                    <div style="width:36px;height:36px;border-radius:50%;background:#C9A84C;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#fff;flex-shrink:0;">
                        {{ strtoupper(substr($msg->sender?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div class="d-flex justify-content-between">
                            <span style="font-weight:{{ $msg->read_at ? '600' : '700' }}; font-size:13px; color:#2d3748;">{{ $msg->sender?->name ?? 'Unknown' }}</span>
                            <span style="font-size:11px; color:#aaa; flex-shrink:0; margin-left:8px;">{{ $msg->created_at->diffForHumans() }}</span>
                        </div>
                        <div style="font-size:12.5px; color:#444; font-weight:{{ $msg->read_at ? '400' : '700' }};">{{ $msg->subject }}</div>
                        <div style="font-size:11.5px; color:#888;">{{ Str::limit($msg->body, 80) }}</div>
                    </div>
                    @if(!$msg->read_at)
                    <div style="width:8px;height:8px;border-radius:50%;background:#C9A84C;flex-shrink:0;"></div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>

    <div class="tab-pane fade" id="sent">
        @if($sent->isEmpty())
            <div class="alert alert-info">No sent messages.</div>
        @else
        <div class="card shadow-sm" style="border-radius:8px; overflow:hidden;">
            @foreach($sent as $msg)
            <a href="{{ route('facilitator.messages.show', $msg) }}" style="text-decoration:none; color:inherit; display:block;">
                <div style="padding:12px 16px; border-bottom:1px solid #f0f0f0; display:flex; align-items:center; gap:12px; transition:background 0.15s;"
                     onmouseover="this.style.background='#f8f9fa'" onmouseout="this.style.background='#fff'">
                    <div style="width:36px;height:36px;border-radius:50%;background:#2d3748;display:flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:#fff;flex-shrink:0;">
                        {{ strtoupper(substr($msg->receiver?->name ?? 'U', 0, 1)) }}
                    </div>
                    <div style="flex:1; min-width:0;">
                        <div class="d-flex justify-content-between">
                            <span style="font-weight:600; font-size:13px; color:#2d3748;">To: {{ $msg->receiver?->name ?? 'Unknown' }}</span>
                            <span style="font-size:11px; color:#aaa; flex-shrink:0; margin-left:8px;">{{ $msg->created_at->diffForHumans() }}</span>
                        </div>
                        <div style="font-size:12.5px; color:#444;">{{ $msg->subject }}</div>
                        <div style="font-size:11.5px; color:#888;">{{ Str::limit($msg->body, 80) }}</div>
                    </div>
                    @if($msg->read_at)
                    <i class="fas fa-check-double" style="color:#2c7a4b; font-size:12px;" title="Read"></i>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection
