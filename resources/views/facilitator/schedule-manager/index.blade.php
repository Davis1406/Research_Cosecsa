@extends('layouts.facilitator')
@section('page-title', 'Manage Timetable')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
        <i class="fas fa-calendar-edit mr-2" style="color:#C9A84C;"></i> Timetable Sessions
    </h5>
    <a href="{{ route('facilitator.schedule-manager.create') }}"
       class="btn btn-sm" style="background:#C9A84C; color:#fff; font-weight:700; font-size:13px; border-radius:5px;">
        <i class="fas fa-plus mr-1"></i> Add Session
    </a>
</div>

@if($days->isEmpty())
    <div class="alert alert-info">No sessions yet. <a href="{{ route('facilitator.schedule-manager.create') }}">Add the first session.</a></div>
@else
    @foreach($days as $dayNumber => $sessions)
    <div class="card shadow-sm mb-4" style="border-radius:10px; overflow:hidden; border:1px solid #e9ecef;">
        <div class="card-header d-flex align-items-center" style="background:#f8f9fa; border-bottom:2px solid #C9A84C; padding:12px 20px;">
            <span style="background:#C9A84C; color:#fff; font-weight:700; font-size:13px; border-radius:4px; padding:2px 12px; margin-right:12px;">Day {{ $dayNumber }}</span>
            @if($sessions->first()->date)
                <span style="font-size:13px; color:#555;">{{ \Carbon\Carbon::parse($sessions->first()->date)->format('l, j F Y') }}</span>
            @endif
            <span class="ml-auto" style="font-size:12px; color:#aaa;">{{ $sessions->count() }} session{{ $sessions->count()!==1?'s':'' }}</span>
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#fafafa;">
                    <tr>
                        <th class="th-sm">Time</th>
                        <th class="th-sm">Title</th>
                        <th class="th-sm">Facilitator</th>
                        <th class="th-sm">Materials</th>
                        <th class="th-sm">Status</th>
                        <th class="th-sm" style="text-align:right; padding-right:16px;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sessions as $session)
                    <tr style="{{ $session->is_completed ? 'background:#f6fff8;' : '' }}">
                        <td style="vertical-align:middle; padding:10px 16px; white-space:nowrap;">
                            <span style="font-size:13px; font-weight:700; color:#C9A84C;">
                                {{ $session->start_time ? \Carbon\Carbon::parse($session->start_time)->format('H:i') : '—' }}
                                @if($session->end_time) – {{ \Carbon\Carbon::parse($session->end_time)->format('H:i') }} @endif
                            </span>
                        </td>
                        <td style="vertical-align:middle;">
                            <div style="font-weight:600; font-size:13.5px; color:#2d3748;">{{ $session->title }}</div>
                            @if($session->subtitle)
                                <div style="font-size:11.5px; color:#888;">{{ Str::limit($session->subtitle, 60) }}</div>
                            @endif
                            @if($session->location)
                                <div style="font-size:11px; color:#aaa;"><i class="fas fa-map-marker-alt mr-1"></i>{{ $session->location }}</div>
                            @endif
                        </td>
                        <td style="vertical-align:middle; font-size:13px; color:#555;">{{ $session->speaker?->name ?? '—' }}</td>
                        <td style="vertical-align:middle;">
                            @if($session->materials->count() > 0)
                                <div class="d-flex flex-wrap" style="gap:4px;">
                                    @foreach($session->materials as $mat)
                                        <span style="background:#fff8e6; color:#7a5c00; border:1px solid #C9A84C44; font-size:10px; padding:2px 6px; border-radius:3px;">
                                            {{ Str::limit($mat->title, 20) }}
                                        </span>
                                    @endforeach
                                </div>
                            @else
                                <span style="color:#ccc; font-size:12px;">None</span>
                            @endif
                        </td>
                        <td style="vertical-align:middle;">
                            <form action="{{ route('facilitator.schedule-manager.toggle', $session->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm" style="font-size:11px; padding:3px 10px; {{ $session->is_completed ? 'background:#d4edda; color:#155724; border:1px solid #c3e6cb;' : 'background:#f8f9fa; color:#555; border:1px solid #dee2e6;' }}">
                                    <i class="fas fa-{{ $session->is_completed ? 'check-circle' : 'circle' }} mr-1"></i>
                                    {{ $session->is_completed ? 'Done' : 'Pending' }}
                                </button>
                            </form>
                        </td>
                        <td style="vertical-align:middle; text-align:right; padding-right:16px; white-space:nowrap;">
                            <a href="{{ route('facilitator.schedule-manager.edit', $session->id) }}"
                               class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:12px; margin-right:4px;">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('facilitator.schedule-manager.destroy', $session->id) }}" method="POST" style="display:inline;"
                                  onsubmit="return confirm('Delete session \'{{ addslashes($session->title) }}\'?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#fff5f5; color:#e53e3e; border:1px solid #fed7d7; font-size:12px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
@endif
@endsection

@section('styles')
<style>.th-sm { font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px; padding:9px 16px; }</style>
@endsection
