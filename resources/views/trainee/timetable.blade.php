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
@php
    // Determine which day to expand by default:
    // 1. Today's session day  2. Next upcoming day  3. Last day (workshop over)
    $today          = \Carbon\Carbon::today();
    $activeDayNum   = null;

    foreach ($days as $dn => $sess) {
        $d = $sess->first()->date ?? null;
        if ($d && \Carbon\Carbon::parse($d)->startOfDay()->eq($today)) {
            $activeDayNum = $dn; break;
        }
    }
    if ($activeDayNum === null) {
        foreach ($days as $dn => $sess) {
            $d = $sess->first()->date ?? null;
            if ($d && \Carbon\Carbon::parse($d)->startOfDay()->gt($today)) {
                $activeDayNum = $dn; break;
            }
        }
    }
    if ($activeDayNum === null) {
        $activeDayNum = $days->keys()->last();
    }
@endphp
    @foreach($days as $dayNumber => $sessions)
    @php
        $isOpen     = ($dayNumber == $activeDayNum);
        $collapseId = 'day-'.$dayNumber;
        $dayDate    = $sessions->first()->date ?? null;
        $isToday    = $dayDate && \Carbon\Carbon::parse($dayDate)->startOfDay()->eq($today);
        $isPast     = $dayDate && \Carbon\Carbon::parse($dayDate)->startOfDay()->lt($today);
    @endphp
    <div class="card shadow-sm mb-3" style="border-radius:8px; overflow:hidden; border:1px solid {{ $isToday ? '#C9A84C' : ($isPast ? '#e0e0e0' : '#e9ecef') }};">
        {{-- Day header — clickable to collapse --}}
        <div class="card-header d-flex align-items-center"
             data-toggle="collapse" data-target="#{{ $collapseId }}"
             aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
             style="cursor:pointer; background:{{ $isPast ? '#3a3a3a' : '#252525' }}; padding:14px 20px; opacity:{{ $isPast ? '0.72' : '1' }};">
            <span style="background:#C9A84C; color:#252525; font-weight:700; font-size:13px; border-radius:4px; padding:2px 10px; margin-right:12px;">
                Day {{ $dayNumber }}
            </span>
            @if($isToday)
                <span style="background:#C9A84C22; border:1px solid #C9A84C; color:#C9A84C; font-size:10px; font-weight:700; border-radius:10px; padding:1px 8px; margin-right:8px;">TODAY</span>
            @endif
            @if($dayDate)
                <span style="font-size:13px; color:rgba(255,255,255,0.75);">
                    {{ \Carbon\Carbon::parse($dayDate)->format('l, j F Y') }}
                </span>
            @endif
            <span class="ml-auto d-flex align-items-center" style="gap:10px;">
                <span style="font-size:12px; color:rgba(255,255,255,0.45);">
                    {{ $sessions->count() }} session{{ $sessions->count()!==1?'s':'' }}
                </span>
                <i class="fas fa-chevron-down day-chevron" style="color:#C9A84C; transition:transform 0.2s; {{ $isOpen ? 'transform:rotate(180deg);' : '' }}"></i>
            </span>
        </div>

        <div id="{{ $collapseId }}" class="collapse {{ $isOpen ? 'show' : '' }}">
            @foreach($sessions as $idx => $session)
            @php $sessId = 'tsess-'.$dayNumber.'-'.$idx; @endphp
            <div class="border-bottom" style="{{ $loop->last ? 'border-bottom:none!important;' : '' }}">
                {{-- Clickable session row --}}
                <div class="px-4 py-3 d-flex align-items-center"
                     data-toggle="collapse" data-target="#{{ $sessId }}"
                     style="cursor:pointer; {{ $session->is_completed ? 'background:#f6fff8;' : 'background:#fff;' }}">
                    <div style="min-width:90px; font-size:13px; font-weight:700; color:#C9A84C;">
                        {{ $session->start_time ? \Carbon\Carbon::parse($session->start_time)->format('H:i') : '' }}
                        @if($session->end_time)&ndash;{{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}@endif
                    </div>
                    <div class="flex-grow-1">
                        <strong style="font-size:15px; color:#252525;">{{ $session->title }}</strong>
                        @if($session->is_completed)
                            <span class="badge ml-2" style="background:#28a745; color:#fff; font-size:10px;">Done</span>
                        @endif
                    </div>
                    @if($session->speaker)
                        <span style="font-size:13px; color:#888; margin-right:14px; white-space:nowrap;">
                            <i class="fas fa-user-tie mr-1" style="color:#C9A84C;"></i>{{ $session->speaker->name }}
                        </span>
                    @endif
                    <i class="fas fa-chevron-right sess-chevron" style="color:#ccc; font-size:13px; transition:transform 0.2s;"></i>
                </div>

                {{-- Expandable detail --}}
                <div id="{{ $sessId }}" class="collapse">
                    <div class="px-4 pb-3 pt-2" style="background:#fafafa; border-top:1px solid #f0f0f0;">
                        @if($session->subtitle)
                            <p style="font-size:14px; color:#555; margin-bottom:8px;">{{ $session->subtitle }}</p>
                        @endif
                        <div class="d-flex flex-wrap" style="gap:16px; font-size:13px; color:#777; margin-bottom:8px;">
                            @if($session->speaker)
                                <span><i class="fas fa-user-tie mr-1" style="color:#C9A84C;"></i>{{ $session->speaker->name }}</span>
                            @endif
                            @if($session->location)
                                <span><i class="fas fa-map-marker-alt mr-1" style="color:#a02626;"></i>{{ $session->location }}</span>
                            @endif
                        </div>
                        @if($session->materials->count() > 0)
                            <div class="d-flex flex-wrap" style="gap:6px;">
                                @foreach($session->materials as $material)
                                    <a href="{{ route('material.view', $material->id) }}" target="_blank"
                                       class="badge"
                                       style="background:#fff8e6; color:#7a5c00; border:1px solid #C9A84C; font-size:10.5px; padding:4px 10px; text-decoration:none; border-radius:4px;">
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

@section('scripts')
<script>
document.querySelectorAll('[data-toggle="collapse"]').forEach(function(el) {
    var targetSel = el.dataset.target;
    if (!targetSel) return;
    var target = document.querySelector(targetSel);
    if (!target) return;
    target.addEventListener('show.bs.collapse', function() {
        var icon = el.querySelector('.day-chevron') || el.querySelector('.sess-chevron');
        if (icon) icon.style.transform = 'rotate(180deg)';
    });
    target.addEventListener('hide.bs.collapse', function() {
        var icon = el.querySelector('.day-chevron') || el.querySelector('.sess-chevron');
        if (icon) icon.style.transform = 'rotate(0deg)';
    });
});
</script>
@endsection
