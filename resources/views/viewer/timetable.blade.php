@extends('layouts.viewer')
@section('page-title', 'Timetable')

@section('content')
<div class="mb-4">
    <h5 style="font-weight:800; font-size:1.1rem; margin-bottom:2px;"><i class="fas fa-calendar-alt mr-2" style="color:#C9A84C;"></i>Workshop Timetable</h5>
    <p style="color:#718096; font-size:13px; margin:0;">Full schedule with sessions and corresponding materials</p>
</div>

@if($days->isEmpty())
    <div class="card" style="padding:48px; text-align:center;">
        <i class="fas fa-calendar" style="font-size:40px; color:#e2e8f0; display:block; margin-bottom:12px;"></i>
        <p style="color:#a0aec0; font-size:14px;">No sessions scheduled yet.</p>
    </div>
@else
    @foreach($days as $dayNumber => $sessions)
    @php $collapseId = 'day-' . $dayNumber; @endphp
    <div class="card mb-3" style="overflow:hidden;">
        <div class="d-flex align-items-center"
             data-toggle="collapse" data-target="#{{ $collapseId }}"
             style="cursor:pointer; padding:14px 20px; border-bottom:2px solid #C9A84C; background:#fff;">
            <span style="background:#C9A84C;color:#fff;font-weight:700;font-size:12px;border-radius:5px;padding:3px 12px;margin-right:12px;">Day {{ $dayNumber }}</span>
            @if($sessions->first()->date ?? false)
            <span style="font-size:14px; color:#4a5568; font-weight:600;">{{ \Carbon\Carbon::parse($sessions->first()->date)->format('l, j F Y') }}</span>
            @endif
            <span class="ml-auto d-flex align-items-center" style="gap:10px;">
                <span style="font-size:12px; color:#a0aec0;">{{ $sessions->count() }} session{{ $sessions->count()!=1?'s':'' }}</span>
                <i class="fas fa-chevron-down" style="color:#C9A84C; font-size:12px;"></i>
            </span>
        </div>
        <div id="{{ $collapseId }}" class="collapse {{ $loop->first ? 'show' : '' }}">
            @foreach($sessions as $idx => $session)
            @php $sessId = 'sess-' . $dayNumber . '-' . $idx; @endphp
            <div style="border-bottom:{{ $loop->last ? 'none' : '1px solid #f0f2f5' }};">
                <div class="d-flex align-items-center px-4 py-3"
                     data-toggle="collapse" data-target="#{{ $sessId }}"
                     style="cursor:pointer; background:{{ $session->is_completed ? '#f6fff8' : '#fff' }};">
                    <div style="min-width:90px; font-size:13.5px; font-weight:700; color:#C9A84C;">
                        {{ $session->start_time ? \Carbon\Carbon::parse($session->start_time)->format('H:i') : '' }}
                        @if($session->end_time)–{{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }}@endif
                    </div>
                    <div style="flex:1;">
                        <strong style="font-size:14px; color:#1a202c;">{{ $session->title }}</strong>
                        @if($session->is_completed)
                        <span class="badge ml-2" style="background:#28a745;color:#fff;font-size:10px;">Done</span>
                        @endif
                    </div>
                    @if($session->speaker)
                    <span style="font-size:13px; color:#718096; margin-right:14px;">
                        <i class="fas fa-user-tie mr-1" style="color:#C9A84C;"></i>{{ $session->speaker->name }}
                    </span>
                    @endif
                    <i class="fas fa-chevron-right" style="color:#d1d5db; font-size:12px;"></i>
                </div>
                <div id="{{ $sessId }}" class="collapse">
                    <div style="padding:12px 20px 16px 110px; background:#fafbfc; border-top:1px solid #f0f2f5;">
                        @if($session->subtitle)
                        <p style="font-size:13.5px; color:#555; margin-bottom:10px;">{{ $session->subtitle }}</p>
                        @endif
                        @if($session->location)
                        <div style="font-size:13px; color:#718096; margin-bottom:10px;">
                            <i class="fas fa-map-marker-alt mr-1" style="color:#e53e3e;"></i>{{ $session->location }}
                        </div>
                        @endif
                        @if($session->materials->count() > 0)
                        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#a0aec0;margin-bottom:8px;">Materials</div>
                        <div style="display:flex; flex-wrap:wrap; gap:6px;">
                            @foreach($session->materials as $material)
                            <a href="{{ route('material.view', $material->id) }}" target="_blank"
                               style="display:inline-flex;align-items:center;gap:5px;background:#fff8e6;color:#7a5c00;border:1px solid #C9A84C;font-size:12px;padding:4px 12px;border-radius:5px;text-decoration:none;font-weight:600;">
                                <i class="fas fa-file-alt"></i>{{ $material->title }}
                            </a>
                            @endforeach
                        </div>
                        @else
                        <div style="font-size:13px; color:#a0aec0;">No materials attached to this session.</div>
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
