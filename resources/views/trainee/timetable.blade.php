@extends('layouts.trainee')

@section('page-title', 'Programme / Timetable')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#252525;">
        <i class="fas fa-calendar-alt mr-2" style="color:#C9A84C;"></i> Workshop Programme
    </h5>
</div>

@if($days->isEmpty())
    <div class="alert alert-info">No sessions have been scheduled yet. Please check back later.</div>
@else
    @foreach($days as $dayNumber => $sessions)
        @php $firstSession = $sessions->first(); @endphp
        <div class="card shadow-sm mb-4" style="border-radius:8px; overflow:hidden;">
            <div class="card-header d-flex align-items-center" style="background:#252525; color:#fff; padding: 12px 20px;">
                <span style="background:#C9A84C; color:#252525; font-weight:700; font-size:13px; border-radius:4px; padding:2px 10px; margin-right:12px;">
                    Day {{ $dayNumber }}
                </span>
                @if($firstSession->date)
                    <span style="font-size:13px; color:rgba(255,255,255,0.75);">
                        {{ \Carbon\Carbon::parse($firstSession->date)->format('l, j F Y') }}
                    </span>
                @endif
                <span class="ml-auto" style="font-size:12px; color:rgba(255,255,255,0.5);">
                    {{ $sessions->count() }} session{{ $sessions->count() !== 1 ? 's' : '' }}
                </span>
            </div>
            <div class="card-body p-0">
                @foreach($sessions as $session)
                    <div class="px-4 py-3 {{ !$loop->last ? 'border-bottom' : '' }}" style="{{ $session->is_completed ? 'background:#f9fdf9;' : '' }}">
                        <div class="row align-items-start">
                            <div class="col-auto" style="min-width:90px;">
                                <div style="font-size:12px; font-weight:700; color:#a02626; white-space:nowrap;">
                                    {{ $session->start_time ? \Carbon\Carbon::parse($session->start_time)->format('H:i') : '' }}
                                    @if($session->end_time)
                                        &ndash; {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}
                                    @endif
                                </div>
                            </div>
                            <div class="col">
                                <div class="d-flex align-items-center flex-wrap gap-2 mb-1">
                                    <strong style="font-size:14px; color:#252525;">{{ $session->title }}</strong>
                                    @if($session->is_completed)
                                        <span class="badge" style="background:#28a745; color:#fff; font-size:10px; margin-left:6px;">Completed</span>
                                    @endif
                                </div>
                                @if($session->subtitle)
                                    <div style="font-size:12px; color:#666; margin-bottom:4px;">{{ $session->subtitle }}</div>
                                @endif
                                <div class="d-flex flex-wrap" style="gap:12px; font-size:12px; color:#888;">
                                    @if($session->speaker)
                                        <span><i class="fas fa-user-tie mr-1" style="color:#C9A84C;"></i>{{ $session->speaker->name }}</span>
                                    @endif
                                    @if($session->location)
                                        <span><i class="fas fa-map-marker-alt mr-1" style="color:#a02626;"></i>{{ $session->location }}</span>
                                    @endif
                                </div>
                                @if($session->materials->count() > 0)
                                    <div class="mt-2 d-flex flex-wrap" style="gap:6px;">
                                        @foreach($session->materials as $material)
                                            <a href="{{ route('admin.training-materials.viewer', $material->id) }}"
                                               target="_blank"
                                               class="badge"
                                               style="background:#f0e6c8; color:#7a5c00; border:1px solid #C9A84C; font-size:10.5px; padding:3px 8px; text-decoration:none; border-radius:4px;">
                                                <i class="fas fa-file-alt mr-1"></i>{{ $material->title }}
                                            </a>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endforeach
@endif
@endsection
