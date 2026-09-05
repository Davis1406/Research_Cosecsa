@extends('layouts.admin')

@section('styles')
<style>
/* ── Font: Nunito on text elements, but NOT on FA icon pseudo-elements ── */
body, p, span, div, td, th, li, a, button, input, select, textarea, label,
h1, h2, h3, h4, h5, h6, small, strong, em {
    font-family: 'Nunito', sans-serif;
}
/* Keep Font Awesome working */
.fa, .fas, .far, .fab, .fal,
[class^="fa-"], [class*=" fa-"] {
    font-family: 'Font Awesome 5 Free', 'Font Awesome 5 Brands', 'Font Awesome 5 Solid' !important;
}

/* ── Day card ── */
.day-card { border:1px solid #e0e0e0; border-radius:8px; overflow:hidden; box-shadow:0 1px 6px rgba(0,0,0,.05); }
.day-header {
    cursor:pointer; background:#fff; border-bottom:2px solid #a02626;
    display:flex; align-items:center; justify-content:space-between;
    padding:12px 20px; user-select:none;
}
.day-header:hover { background:#fafafa; }
.day-badge { background:#a02626; color:#fff; font-weight:700; font-size:12px; padding:3px 12px; border-radius:20px; }
.day-chevron { color:#a02626; transition:transform .2s; }
.day-chevron.open { transform:rotate(180deg); }

/* ── Progress bar ── */
.day-progress-track { height:5px; background:#eee; border-radius:3px; overflow:hidden; width:120px; }
.day-progress-bar   { height:100%; border-radius:3px; transition:width .4s; }

/* ── Session table ── */
.session-table { width:100%; border-collapse:collapse; }
.session-table th {
    background:#fafafa; color:#777; font-size:12px; font-weight:700;
    text-transform:uppercase; letter-spacing:.4px;
    padding:8px 16px; border-bottom:1px solid #ebebeb;
}
.session-table td { padding:11px 16px; border-bottom:1px solid #f0f0f0; vertical-align:middle; }
.session-table tr:last-child td { border-bottom:none; }
.session-table tr.is-break td { background:#fafafa; }
.session-table tr.is-done td { opacity:.55; }

/* ── Time cell ── */
.time-cell { white-space:nowrap; font-size:13px; color:#888; width:118px; }

/* ── Title ── */
.session-title { font-weight:600; font-size:14.5px; color:#2d2d2d; }
.session-title.done-text { text-decoration:line-through; color:#aaa !important; }
.session-subtitle { font-size:12.5px; color:#aaa; margin-top:2px; }
.break-title { font-weight:400; color:#999; font-size:14px; }

/* ── Materials chips ── */
.materials-wrap { display:flex; flex-wrap:wrap; gap:5px; margin-top:6px; }
.material-chip {
    display:inline-flex; align-items:center; gap:5px;
    background:#f5f5f5; border:1px solid #e0e0e0; border-radius:4px;
    padding:4px 10px; font-size:12.5px; color:#555;
    text-decoration:none; transition:background .15s, border-color .15s;
    max-width:280px;
}
.material-chip:hover { background:#fff3e0; border-color:#C9A84C; color:#333; text-decoration:none; }
.material-chip .chip-icon { font-size:12px; flex-shrink:0; }
.material-chip .chip-name { overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }

/* ── Facilitator cell ── */
.facilitator-cell { font-size:13px; color:#666; white-space:nowrap; width:210px; }

/* ── Done button ── */
.btn-done { background:none; border:none; cursor:pointer; padding:2px 6px; font-size:20px; line-height:1; transition:transform .15s; }
.btn-done:hover { transform:scale(1.25); }
.done-col { width:56px; text-align:center; }

/* ── Action buttons ── */
.action-col { width:80px; text-align:right; white-space:nowrap; }
</style>
@endsection

@section('content')

@include('admin.partials.course-tabs', ['courseRoute' => 'admin.schedules.index'])

{{-- Page header --}}
<div class="d-flex align-items-center justify-content-between mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#2d2d2d;">
        <i class="fas fa-calendar-alt mr-2" style="color:#a02626;"></i>
        {{ config("courses.types.$courseType.subtitle") }} &mdash; Timetable
    </h5>
    @can('schedule_create')
    <a class="btn btn-cosecsa btn-sm" href="{{ route('admin.schedules.create', ['course' => $courseType]) }}">
        <i class="fas fa-plus mr-1"></i> Add Session
    </a>
    @endcan
</div>

@php
    // Determine which day to auto-expand: today → next upcoming → last day
    $today        = \Carbon\Carbon::today();
    $activeDayNum = null;
    foreach ($days as $dn => $sess) {
        $d = $sess->first()->date ?? null;
        if ($d && \Carbon\Carbon::parse($d)->startOfDay()->eq($today)) { $activeDayNum = $dn; break; }
    }
    if ($activeDayNum === null) {
        foreach ($days as $dn => $sess) {
            $d = $sess->first()->date ?? null;
            if ($d && \Carbon\Carbon::parse($d)->startOfDay()->gt($today)) { $activeDayNum = $dn; break; }
        }
    }
    if ($activeDayNum === null) { $activeDayNum = $days->keys()->last(); }
@endphp

@if($days->isEmpty())
<div class="text-center text-muted py-5">
    <i class="fas fa-calendar-times fa-2x mb-2" style="opacity:.4;"></i>
    <p class="mb-0">No sessions scheduled yet for {{ config("courses.types.$courseType.label") }}.</p>
    @can('schedule_create')
    <a class="btn btn-cosecsa btn-sm mt-2" href="{{ route('admin.schedules.create', ['course' => $courseType]) }}">
        <i class="fas fa-plus mr-1"></i> Add the first session
    </a>
    @endcan
</div>
@endif

@foreach($days as $dayNumber => $sessions)
@php
    $firstSession    = $sessions->first();
    $date            = $firstSession->date;
    $dateFormatted   = \Carbon\Carbon::parse($date)->format('l, F j, Y');
    // Exclude breaks/tea from the progress count
    $realSessions    = $sessions->filter(fn($s) => !preg_match('/break|lunch|tea/i', $s->title));
    $total           = $realSessions->count();
    $done            = $realSessions->where('is_completed', true)->count();
    $pct             = $total > 0 ? round(($done/$total)*100) : 0;
    $allDone         = $done === $total && $total > 0;
    $collapseId      = 'day-collapse-' . $dayNumber;
    $isOpen          = ($dayNumber == $activeDayNum);
    $isToday         = $date && \Carbon\Carbon::parse($date)->startOfDay()->eq($today);
    $isPast          = $date && \Carbon\Carbon::parse($date)->startOfDay()->lt($today);
@endphp

<div class="day-card mb-3" style="{{ $isPast ? 'opacity:.72;' : '' }} {{ $isToday ? 'border-color:#C9A84C;' : '' }}">

    {{-- Day header --}}
    <div class="day-header" data-toggle="collapse" data-target="#{{ $collapseId }}" data-day="{{ $dayNumber }}"
         style="{{ $isPast ? 'background:#fafafa; border-bottom-color:#ddd;' : '' }} {{ $isToday ? 'border-bottom-color:#C9A84C;' : '' }}">
        <div style="display:flex; align-items:center; gap:14px;">
            <span class="day-badge">{{ $courseType === 'online' ? 'Week' : 'Day' }} {{ $dayNumber }}</span>
            @if($isToday)
                <span style="background:#fff8e1; border:1px solid #C9A84C; color:#856404; font-size:10px; font-weight:700; border-radius:10px; padding:1px 8px;">TODAY</span>
            @endif
            <div>
                <span style="font-weight:700; font-size:14.5px; color:{{ $isPast ? '#aaa' : '#2d2d2d' }};">{{ $dateFormatted }}</span>
                <span style="font-size:12px; color:#aaa; margin-left:8px;">{{ $total }} sessions</span>
            </div>
        </div>
        <div style="display:flex; align-items:center; gap:16px;">
            <div>
                <div style="font-size:11px; color:#aaa; margin-bottom:3px; text-align:right;">
                    <span class="day-done-label" data-day="{{ $dayNumber }}">{{ $done }}/{{ $total }}</span> done
                    @if($allDone) <span style="color:#28a745; margin-left:4px;">✓ Complete</span>@endif
                </div>
                <div class="day-progress-track">
                    <div class="day-progress-bar" data-day="{{ $dayNumber }}"
                         style="width:{{ $pct }}%; background:{{ $allDone ? '#28a745' : '#a02626' }};"></div>
                </div>
            </div>
            <i class="fas fa-chevron-down day-chevron {{ $isOpen ? 'open' : '' }}" id="chev-{{ $dayNumber }}"></i>
        </div>
    </div>

    {{-- Sessions table --}}
    <div id="{{ $collapseId }}" class="collapse {{ $isOpen ? 'show' : '' }}">
        <table class="session-table">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Session</th>
                    <th>Facilitator</th>
                    @can('schedule_edit')<th class="done-col">Done</th>@endcan
                    <th class="action-col"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($sessions as $schedule)
                @php
                    $isBreak    = preg_match('/break|lunch/i', $schedule->title);
                    $hasMats    = $schedule->materials->isNotEmpty();
                @endphp
                <tr id="row-{{ $schedule->id }}"
                    class="{{ $isBreak ? 'is-break' : '' }} {{ $schedule->is_completed ? 'is-done' : '' }}">

                    {{-- Time --}}
                    <td class="time-cell">
                        {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                        @if($schedule->end_time)
                        – {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                        @endif
                    </td>

                    {{-- Title + materials --}}
                    <td>
                        @if($isBreak)
                            <span class="break-title">
                                <i class="fas fa-coffee mr-1" style="color:#C9A84C;"></i>{{ $schedule->title }}
                            </span>
                        @else
                            <div class="session-title {{ $schedule->is_completed ? 'done-text' : '' }}">
                                {{ $schedule->title }}
                            </div>
                            @if($schedule->subtitle)
                                <div class="session-subtitle">{{ $schedule->subtitle }}</div>
                            @endif

                            {{-- Attached materials --}}
                            @if($hasMats)
                            <div class="materials-wrap">
                                @foreach($schedule->materials as $mat)
                                @php
                                    $icon = $mat->type === 'video'
                                        ? 'fa-video'
                                        : ($mat->type === 'presentation' ? 'fa-file-powerpoint' : 'fa-file-pdf');
                                    $iconColor = $mat->type === 'video'
                                        ? '#dc3545'
                                        : ($mat->type === 'presentation' ? '#0d6efd' : '#28a745');
                                @endphp
                                <a href="{{ route('admin.training-materials.viewer', $mat->id) }}"
                                   class="material-chip"
                                   title="{{ $mat->title }}">
                                    <i class="fas {{ $icon }} chip-icon" style="color:{{ $iconColor }};"></i>
                                    <span class="chip-name">{{ $mat->title }}</span>
                                </a>
                                @endforeach
                            </div>
                            @endif
                        @endif
                    </td>

                    {{-- Facilitator --}}
                    <td class="facilitator-cell">
                        @if($schedule->speaker)
                            <i class="fas fa-user-tie mr-1" style="color:#a02626; font-size:11px;"></i>
                            {{ $schedule->speaker->name }}
                        @else
                            <span style="color:#ccc;">—</span>
                        @endif
                    </td>

                    {{-- Done toggle --}}
                    @can('schedule_edit')
                    <td class="done-col">
                        @if(!$isBreak)
                        <button class="btn-done"
                                data-id="{{ $schedule->id }}"
                                data-url="{{ route('admin.schedules.toggleComplete', $schedule->id) }}"
                                title="{{ $schedule->is_completed ? 'Mark pending' : 'Mark done' }}">
                            {{ $schedule->is_completed ? '✅' : '⬜' }}
                        </button>
                        @endif
                    </td>
                    @endcan

                    {{-- Edit / Delete --}}
                    <td class="action-col">
                        @can('schedule_edit')
                        <a class="btn btn-xs btn-outline-secondary" href="{{ route('admin.schedules.edit', $schedule->id) }}">
                            <i class="fas fa-pencil-alt"></i>
                        </a>
                        @endcan
                        @can('schedule_delete')
                        <form action="{{ route('admin.schedules.destroy', $schedule->id) }}" method="POST"
                              onsubmit="return confirm('Delete this session?');" style="display:inline-block; margin-left:3px;">
                            @csrf @method('DELETE')
                            <button class="btn btn-xs btn-outline-danger" type="submit">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endforeach

@endsection

@section('scripts')
@parent
<script>
$(function () {

    // Chevron rotation on collapse
    $('[data-toggle="collapse"]').on('click', function () {
        var dayNum = $(this).data('day');
        var chev   = $('#chev-' + dayNum);
        var target = $(this).data('target');
        var isOpen = $(target).hasClass('show');
        chev.toggleClass('open', !isOpen);
    });

    // Mark session done/pending via AJAX
    $(document).on('click', '.btn-done', function () {
        var btn    = $(this);
        var id     = btn.data('id');
        var url    = btn.data('url');
        var row    = $('#row-' + id);
        var dayNum = row.closest('.collapse').attr('id').replace('day-collapse-','');

        $.ajax({
            url:    url,
            method: 'POST',
            data:   { _token: $('meta[name="csrf-token"]').attr('content') },
            success: function (res) {
                if (res.is_completed) {
                    btn.text('✅').attr('title','Mark pending');
                    row.addClass('is-done');
                    row.find('.session-title').addClass('done-text');
                } else {
                    btn.text('⬜').attr('title','Mark done');
                    row.removeClass('is-done');
                    row.find('.session-title').removeClass('done-text');
                }
                refreshProgress(dayNum);
            }
        });
    });

    function refreshProgress(dayNum) {
        var panel   = $('#day-collapse-' + dayNum);
        var total   = panel.find('.btn-done').length;
        var done    = panel.find('.btn-done:contains("✅")').length;
        var pct     = total > 0 ? Math.round((done/total)*100) : 0;
        var allDone = done === total && total > 0;
        var color   = allDone ? '#28a745' : '#a02626';

        panel.prev('.day-header').find('.day-progress-bar').css({ width: pct + '%', background: color });
        panel.prev('.day-header').find('.day-done-label').text(done + '/' + total);
    }
});
</script>
@endsection
