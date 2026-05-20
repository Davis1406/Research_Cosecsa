@extends('layouts.facilitator')

@section('page-title', 'Discussions')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
        <i class="fas fa-comments mr-2" style="color:#C9A84C;"></i> Group Discussions
    </h5>
    <a href="{{ route('facilitator.discussions.create') }}" class="btn btn-sm" style="background:#C9A84C; color:#fff; font-weight:700; font-size:13px; border-radius:5px;">
        <i class="fas fa-plus mr-1"></i> Start Discussion
    </a>
</div>

{{-- Filter tabs --}}
<div class="mb-3">
    <div style="display:inline-flex; background:#f8f9fa; border-radius:6px; padding:3px; border:1px solid #dee2e6;">
        <a href="{{ request()->fullUrlWithQuery(['tab' => 'all']) }}" class="btn btn-sm {{ !request('tab') || request('tab') === 'all' ? '' : '' }}"
           style="font-size:12px; font-weight:600; border-radius:4px; {{ !request('tab') || request('tab') === 'all' ? 'background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.1); color:#2d3748;' : 'background:none; color:#888;' }}">
            All ({{ $discussions->count() }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['tab' => 'session']) }}" class="btn btn-sm"
           style="font-size:12px; font-weight:600; border-radius:4px; {{ request('tab') === 'session' ? 'background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.1); color:#2d3748;' : 'background:none; color:#888;' }}">
            Session ({{ $discussions->whereNotNull('schedule_id')->count() }})
        </a>
        <a href="{{ request()->fullUrlWithQuery(['tab' => 'general']) }}" class="btn btn-sm"
           style="font-size:12px; font-weight:600; border-radius:4px; {{ request('tab') === 'general' ? 'background:#fff; box-shadow:0 1px 3px rgba(0,0,0,0.1); color:#2d3748;' : 'background:none; color:#888;' }}">
            General ({{ $discussions->where('is_general', true)->count() }})
        </a>
    </div>
</div>

@php
    $tab = request('tab', 'all');
    $filtered = $discussions;
    if ($tab === 'session') $filtered = $discussions->whereNotNull('schedule_id')->values();
    if ($tab === 'general') $filtered = $discussions->where('is_general', true)->values();
@endphp

@if($filtered->isEmpty())
    <div class="alert alert-info">No discussions found. <a href="{{ route('facilitator.discussions.create') }}" class="alert-link">Start one!</a></div>
@else
    @foreach($filtered as $discussion)
    <div class="card shadow-sm mb-2" style="border-radius:8px; border-left:4px solid {{ $discussion->schedule_id ? '#C9A84C' : '#2c7a4b' }};">
        <div class="card-body py-3">
            <div class="d-flex justify-content-between align-items-start">
                <div style="flex:1; min-width:0;">
                    <div class="d-flex align-items-center flex-wrap" style="gap:6px; margin-bottom:4px;">
                        <a href="{{ route('facilitator.discussions.show', $discussion) }}" style="font-size:14px; font-weight:700; color:#2d3748; text-decoration:none;">
                            {{ $discussion->title }}
                        </a>
                        @if($discussion->is_general)
                            <span class="badge" style="background:#e8f5e9; color:#2c7a4b; font-size:10px;">General</span>
                        @endif
                        @if($discussion->schedule)
                            <span class="badge" style="background:#fff8e6; color:#9a7d2c; font-size:10px;">
                                <i class="fas fa-calendar-alt mr-1"></i> {{ $discussion->schedule->title }}
                            </span>
                        @endif
                    </div>
                    <p class="mb-1 text-muted" style="font-size:12px;">{{ Str::limit($discussion->body, 120) }}</p>
                    <div style="font-size:11px; color:#aaa;">
                        <i class="fas fa-user mr-1"></i> {{ $discussion->user?->name ?? 'Unknown' }}
                        &bull; {{ $discussion->created_at->diffForHumans() }}
                        &bull; <i class="fas fa-reply mr-1"></i> {{ $discussion->replies_count }} {{ Str::plural('reply', $discussion->replies_count) }}
                        @if($discussion->latestReply)
                        &bull; Last reply by {{ $discussion->latestReply->user?->name ?? '?' }} {{ $discussion->latestReply->created_at->diffForHumans() }}
                        @endif
                    </div>
                </div>
                <div style="display:flex; align-items:center; gap:6px; flex-shrink:0; margin-left:12px;">
                    <a href="{{ route('facilitator.discussions.show', $discussion) }}" class="btn btn-sm" style="background:#f8f9fa; color:#2d3748; border:1px solid #dee2e6; font-size:12px;">
                        View
                    </a>
                    @if(auth()->id() === $discussion->user_id || auth()->user()->roles->pluck('title')->contains('Lead Facilitator'))
                    <form action="{{ route('facilitator.discussions.destroy', $discussion) }}" method="POST" style="display:inline;"
                          onsubmit="return confirm('Delete this discussion?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm" style="background:#fff5f5; color:#e53e3e; border:1px solid #fed7d7; font-size:12px;">
                            <i class="fas fa-trash"></i>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
@endif
@endsection
