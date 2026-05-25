@extends('layouts.admin')
@section('content')

{{-- Welcome Banner --}}
<div class="row mb-3">
    <div class="col-12">
        <div class="card" style="background:#fff; border:none; border-left:4px solid #a02626; border-bottom:2px solid #C9A84C; box-shadow:0 2px 8px rgba(0,0,0,0.07);">
            <div class="card-body py-3 px-4">
                <div class="d-flex align-items-center">
                    <img src="{{ asset('img/cosecsa-logo.png') }}" alt="COSECSA"
                         style="width:52px; height:52px; border-radius:50%; object-fit:cover; margin-right:16px; flex-shrink:0; border:2px solid #C9A84C;">
                    <div>
                        <h5 style="color:#2d2d2d; margin:0; font-weight:700;">COSECSA Research Training System</h5>
                        <small style="color:#777;">College of Surgeons of East, Central and Southern Africa &mdash; Training Management Platform</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Stats Row --}}
<div class="row">
    <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
        <div class="stat-card" style="border-left-color:#a02626;">
            <div class="stat-icon" style="background:#a02626;">
                <i class="fas fa-user-graduate"></i>
            </div>
            <div class="stat-body">
                <div class="stat-label">Trainees Enrolled</div>
                <div class="stat-number">{{ $stats['trainees'] }}</div>
                <div class="stat-hint">&nbsp;</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
        <div class="stat-card" style="border-left-color:#C9A84C;">
            <div class="stat-icon" style="background:#C9A84C;">
                <i class="fas fa-chalkboard-teacher"></i>
            </div>
            <div class="stat-body">
                <div class="stat-label">Facilitators</div>
                <div class="stat-number">{{ $stats['facilitators'] }}</div>
                <div class="stat-hint">&nbsp;</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
        <div class="stat-card" style="border-left-color:#6c757d;">
            <div class="stat-icon" style="background:#6c757d;">
                <i class="fas fa-book"></i>
            </div>
            <div class="stat-body">
                <div class="stat-label">Training Materials</div>
                <div class="stat-number">{{ $stats['materials'] }}</div>
                <div class="stat-hint">{{ $stats['documents'] }} docs &bull; {{ $stats['presentations'] }} slides &bull; {{ $stats['videos'] }} videos</div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-6 col-12 mb-3">
        <div class="stat-card" style="border-left-color:#495057;">
            <div class="stat-icon" style="background:#495057;">
                <i class="fas fa-calendar-alt"></i>
            </div>
            <div class="stat-body">
                <div class="stat-label">Timetable Sessions</div>
                <div class="stat-number">{{ $stats['sessions'] }}</div>
                <div class="stat-hint">&nbsp;</div>
            </div>
        </div>
    </div>
</div>

{{-- Recent Activity Row --}}
<div class="row">
    {{-- Recent Trainees --}}
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header" style="border-bottom:2px solid #a02626;">
                <h3 class="card-title">
                    <i class="fas fa-user-graduate mr-1" style="color:#a02626;"></i> Recent Trainees
                </h3>
                @can('trainee_access')
                <div class="card-tools">
                    <a href="{{ route('admin.trainees.index') }}" class="btn btn-xs btn-default">View all</a>
                </div>
                @endcan
            </div>
            <div class="card-body p-0">
                @forelse($recentTrainees as $trainee)
                <div class="d-flex align-items-center px-3 py-2 border-bottom">
                    <span class="info-box-icon bg-light" style="width:34px; height:34px; min-width:34px; border-radius:50%; margin-right:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="fas fa-user text-muted"></i>
                    </span>
                    <div style="min-width:0;">
                        <div class="font-weight-bold text-truncate">{{ $trainee->name }}</div>
                        <small class="text-muted">{{ $trainee->institution ?? $trainee->country ?? '—' }}</small>
                    </div>
                </div>
                @empty
                <p class="text-center text-muted p-3 mb-0">No trainees enrolled yet.</p>
                @endforelse
            </div>
            @can('trainee_create')
            <div class="card-footer text-right">
                <a href="{{ route('admin.trainees.create') }}" class="btn btn-sm btn-cosecsa">
                    <i class="fas fa-plus"></i> Add Trainee
                </a>
            </div>
            @endcan
        </div>
    </div>

    {{-- Recent Materials --}}
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header" style="border-bottom:2px solid #C9A84C;">
                <h3 class="card-title">
                    <i class="fas fa-book mr-1" style="color:#C9A84C;"></i> Recent Materials
                </h3>
                @can('training_material_access')
                <div class="card-tools">
                    <a href="{{ route('admin.training-materials.index') }}" class="btn btn-xs btn-default">View all</a>
                </div>
                @endcan
            </div>
            <div class="card-body p-0">
                @forelse($recentMaterials as $material)
                <div class="d-flex align-items-center px-3 py-2 border-bottom">
                    <span class="info-box-icon bg-light" style="width:34px; height:34px; min-width:34px; border-radius:6px; margin-right:10px; display:flex; align-items:center; justify-content:center;">
                        <i class="fas {{ $material->type === 'video' ? 'fa-video text-danger' : ($material->type === 'presentation' ? 'fa-file-powerpoint text-primary' : 'fa-file-pdf text-success') }}"></i>
                    </span>
                    <div style="min-width:0;">
                        <div class="font-weight-bold text-truncate">{{ $material->title }}</div>
                        <small class="text-muted">{{ $material->category ?? ucfirst($material->type) }}</small>
                    </div>
                </div>
                @empty
                <p class="text-center text-muted p-3 mb-0">No materials uploaded yet.</p>
                @endforelse
            </div>
            @can('training_material_create')
            <div class="card-footer text-right">
                <a href="{{ route('admin.training-materials.create') }}" class="btn btn-sm btn-cosecsa">
                    <i class="fas fa-upload"></i> Upload Material
                </a>
            </div>
            @endcan
        </div>
    </div>

    {{-- Timetable Sessions --}}
    <div class="col-md-4">
        <div class="card shadow-sm h-100">
            <div class="card-header" style="border-bottom:2px solid #6c757d;">
                <h3 class="card-title">
                    <i class="fas fa-calendar-alt mr-1 text-secondary"></i> Timetable Sessions
                </h3>
                @can('schedule_access')
                <div class="card-tools">
                    <a href="{{ route('admin.schedules.index') }}" class="btn btn-xs btn-default">View all</a>
                </div>
                @endcan
            </div>
            <div class="card-body p-0">
                @forelse($upcomingSessions as $session)
                <div class="d-flex align-items-center px-3 py-2 border-bottom">
                    <span class="badge badge-warning mr-2" style="font-size:11px; min-width:28px;">D{{ $session->day_number }}</span>
                    <div style="min-width:0;">
                        <div class="font-weight-bold text-truncate">{{ $session->title }}</div>
                        <small class="text-muted">
                            {{ $session->start_time }}{{ $session->end_time ? ' – '.$session->end_time : '' }}
                            {{ $session->speaker ? ' · '.$session->speaker->name : '' }}
                        </small>
                    </div>
                </div>
                @empty
                <p class="text-center text-muted p-3 mb-0">No sessions scheduled yet.</p>
                @endforelse
            </div>
            @can('schedule_create')
            <div class="card-footer text-right">
                <a href="{{ route('admin.schedules.create') }}" class="btn btn-sm btn-cosecsa">
                    <i class="fas fa-plus"></i> Add Session
                </a>
            </div>
            @endcan
        </div>
    </div>
</div>

{{-- Today's Visitors --}}
<div class="row mt-3">
    <div class="col-12">
        <div class="card shadow-sm">
            <div class="card-header d-flex align-items-center justify-content-between" style="border-bottom:2px solid #2d6fa8;">
                <h3 class="card-title mb-0">
                    <i class="fas fa-sign-in-alt mr-1" style="color:#2d6fa8;"></i>
                    Today's Visitors
                    <span class="badge ml-2" style="background:#2d6fa8; color:#fff; font-size:11px; border-radius:10px; padding:3px 8px;">
                        {{ $recentLogins->count() }} login{{ $recentLogins->count() === 1 ? '' : 's' }} in last 24 h
                    </span>
                </h3>
            </div>
            <div class="card-body p-0">
                @if($recentLogins->isEmpty())
                    <p class="text-center text-muted p-3 mb-0">No logins recorded in the last 24 hours.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm table-hover mb-0">
                            <thead style="background:#f4f6f9;">
                                <tr>
                                    <th style="width:36px;"></th>
                                    <th>Name</th>
                                    <th>Role</th>
                                    <th>IP Address</th>
                                    <th>Time</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentLogins as $log)
                                @php
                                    $roles = $log->user?->roles->pluck('title')->implode(', ') ?? '—';
                                    $roleSlug = strtolower($log->user?->roles->first()?->title ?? '');
                                    $dotColor = match(true) {
                                        str_contains($roleSlug, 'admin')            => '#a02626',
                                        str_contains($roleSlug, 'lead')             => '#2c7a4b',
                                        str_contains($roleSlug, 'facilitator')      => '#C9A84C',
                                        str_contains($roleSlug, 'trainee')          => '#2d6fa8',
                                        default                                     => '#adb5bd',
                                    };
                                @endphp
                                <tr>
                                    <td class="text-center">
                                        <span style="display:inline-block; width:10px; height:10px; border-radius:50%; background:{{ $dotColor }};"></span>
                                    </td>
                                    <td class="font-weight-bold">{{ $log->user?->name ?? '(deleted)' }}</td>
                                    <td><small class="text-muted">{{ $roles }}</small></td>
                                    <td><small class="text-muted font-monospace">{{ $log->ip_address ?? '—' }}</small></td>
                                    <td>
                                        <small class="text-muted" title="{{ $log->logged_in_at->format('Y-m-d H:i:s') }}">
                                            {{ $log->logged_in_at->diffForHumans() }}
                                        </small>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
@parent
@endsection
