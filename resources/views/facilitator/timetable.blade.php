@extends('layouts.facilitator')

@section('page-title', 'Programme')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4" style="flex-wrap:wrap; gap:10px;">
    <h5 class="mb-0" style="font-weight:700; color:#2d3748; font-size:1.1rem;">
        <i class="fas fa-calendar-alt mr-2" style="color:#C9A84C;"></i> Programme
    </h5>
    <div class="d-flex align-items-center" style="gap:6px; flex-shrink:0;">
        <span style="font-size:0.8rem; color:#888; margin-right:2px;">View:</span>
        <button id="btn-all" onclick="filterSessions('all')"
            class="btn btn-sm"
            style="font-size:0.8rem; font-weight:700; border-radius:20px; padding:4px 14px; background:#2d3748; color:#fff; border:1px solid #2d3748;">
            All
        </button>
        <button id="btn-mine" onclick="filterSessions('mine')"
            class="btn btn-sm"
            style="font-size:0.8rem; font-weight:700; border-radius:20px; padding:4px 14px; background:#fff; color:#C9A84C; border:1px solid #C9A84C;">
            Mine
        </button>
    </div>
</div>

@if($days->isEmpty())
    <div class="alert alert-info">No sessions have been scheduled yet.</div>
@else
@php
    $today        = \Carbon\Carbon::today();
    $activeDayNum = null;

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
    <div class="day-card card shadow-sm mb-3" style="border-radius:8px; overflow:hidden; border:1px solid {{ $isToday ? '#C9A84C' : ($isPast ? '#e0e0e0' : '#e9ecef') }};">
        <div class="card-header d-flex align-items-center"
             data-toggle="collapse" data-target="#{{ $collapseId }}"
             aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
             style="cursor:pointer; background:{{ $isPast ? '#f8f9fa' : '#fff' }}; padding:14px 20px; border-bottom:2px solid {{ $isToday ? '#C9A84C' : ($isPast ? '#e0e0e0' : '#e9ecef') }}; opacity:{{ $isPast ? '0.78' : '1' }};">
            <span style="background:#C9A84C; color:#fff; font-weight:700; font-size:0.85rem; border-radius:4px; padding:2px 10px; margin-right:12px;">
                Day {{ $dayNumber }}
            </span>
            @if($isToday)
                <span style="background:#fff8e1; border:1px solid #C9A84C; color:#856404; font-size:10px; font-weight:700; border-radius:10px; padding:1px 8px; margin-right:8px;">TODAY</span>
            @endif
            @if($dayDate)
                <span style="font-size:0.9rem; color:{{ $isPast ? '#aaa' : '#555' }};">{{ \Carbon\Carbon::parse($dayDate)->format('l, j F Y') }}</span>
            @endif
            <span class="ml-auto d-flex align-items-center" style="gap:10px;">
                <span class="day-count" style="font-size:0.8rem; color:#999;">{{ $sessions->count() }} session{{ $sessions->count()!==1?'s':'' }}</span>
                <i class="fas fa-chevron-down day-chevron" style="color:#C9A84C; transition:transform 0.2s; {{ $isOpen ? 'transform:rotate(180deg);' : '' }}"></i>
            </span>
        </div>
        <div id="{{ $collapseId }}" class="collapse {{ $isOpen ? 'show' : '' }}">
            @foreach($sessions as $idx => $session)
            @php $sessId = 'sess-'.$dayNumber.'-'.$idx; @endphp
            <div class="session-row border-bottom"
                 data-speaker="{{ $session->speaker_id ?? '' }}"
                 style="{{ $loop->last ? 'border-bottom:none!important;' : '' }}">
                <!-- Clickable summary row -->
                <div class="px-4 py-3 d-flex align-items-center session-toggle"
                     data-toggle="collapse" data-target="#{{ $sessId }}"
                     style="cursor:pointer; {{ $session->is_completed ? 'background:#f6fff8;' : 'background:#fff;' }}">
                    <div style="min-width:110px; padding-right:16px; font-size:0.875rem; font-weight:700; color:#C9A84C; flex-shrink:0;">
                        {{ $session->start_time ? \Carbon\Carbon::parse($session->start_time)->format('H:i') : '' }}
                        @if($session->end_time)&ndash;{{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}@endif
                    </div>
                    <div class="flex-grow-1">
                        <strong style="font-size:1rem; color:#2d3748;">{{ $session->title }}</strong>
                        @if($session->is_completed)
                            <span class="badge ml-2" style="background:#28a745; color:#fff; font-size:0.7rem;">Done</span>
                        @endif
                    </div>
                    @if($session->speaker)
                        <span style="font-size:0.875rem; color:#888; margin-right:14px;"><i class="fas fa-user-tie mr-1" style="color:#C9A84C;"></i>{{ $session->speaker->name }}</span>
                    @endif
                    <i class="fas fa-chevron-right sess-chevron" style="color:#ccc; font-size:0.85rem; transition:transform 0.2s;"></i>
                </div>
                <!-- Expandable detail -->
                <div id="{{ $sessId }}" class="collapse">
                    <div class="px-4 pb-3 pt-1" style="background:#fafafa; border-top:1px solid #f0f0f0;">
                        @if($session->subtitle)
                            <p style="font-size:0.9rem; color:#555; margin-bottom:8px;">{{ $session->subtitle }}</p>
                        @endif
                        <div class="d-flex flex-wrap" style="gap:16px; font-size:0.875rem; color:#777; margin-bottom:8px;">
                            @if($session->speaker)
                                <span><i class="fas fa-user-tie mr-1" style="color:#C9A84C;"></i>{{ $session->speaker->name }}</span>
                            @endif
                            @if($session->location)
                                <span><i class="fas fa-map-marker-alt mr-1" style="color:#e53e3e;"></i>{{ $session->location }}</span>
                            @endif
                        </div>
                        @if($session->materials->count() > 0)
                            <div class="d-flex flex-wrap" style="gap:6px;">
                                @foreach($session->materials as $material)
                                    <a href="{{ route('material.view', $material->id) }}" target="_blank"
                                       class="badge"
                                       style="background:#fff8e6; color:#7a5c00; border:1px solid #C9A84C; font-size:0.75rem; padding:4px 10px; text-decoration:none; border-radius:4px;">
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
var mySpeakerId = '{{ $mySpeakerId ?? '' }}';

function filterSessions(mode) {
    var btnAll  = document.getElementById('btn-all');
    var btnMine = document.getElementById('btn-mine');

    if (mode === 'mine') {
        btnMine.style.background = '#C9A84C'; btnMine.style.color = '#fff'; btnMine.style.borderColor = '#C9A84C';
        btnAll.style.background  = '#fff';    btnAll.style.color  = '#2d3748'; btnAll.style.borderColor = '#dee2e6';
    } else {
        btnAll.style.background  = '#2d3748'; btnAll.style.color  = '#fff'; btnAll.style.borderColor = '#2d3748';
        btnMine.style.background = '#fff';    btnMine.style.color = '#C9A84C'; btnMine.style.borderColor = '#C9A84C';
    }

    document.querySelectorAll('.day-card').forEach(function(dayCard) {
        var rows = dayCard.querySelectorAll('.session-row');
        var visibleCount = 0;

        rows.forEach(function(row) {
            var speakerId = row.dataset.speaker;
            var show = (mode === 'all') || (speakerId && speakerId === mySpeakerId.toString());
            row.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        // Hide the whole day card if no sessions are visible
        dayCard.style.display = (visibleCount === 0) ? 'none' : '';

        // Update the session count badge
        var countEl = dayCard.querySelector('.day-count');
        if (countEl) {
            countEl.textContent = visibleCount + ' session' + (visibleCount !== 1 ? 's' : '');
        }
    });
}

// Rotate chevrons on collapse toggle
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
