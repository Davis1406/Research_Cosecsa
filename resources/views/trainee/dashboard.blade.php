@extends('layouts.trainee')

@section('page-title', 'Dashboard')

@section('content')
@if($onlineCertificate)
<div class="row">
    <div class="col-12 mb-3">
        <div class="card shadow-sm" style="border-radius:8px; border:1px solid #C9A84C; background:linear-gradient(135deg,#fffaf0,#fff);">
            <div class="card-body py-3 d-flex align-items-center justify-content-between flex-wrap" style="gap:12px;">
                <div class="d-flex align-items-center" style="gap:14px;">
                    <div style="font-size:32px; color:#C9A84C;"><i class="fas fa-award"></i></div>
                    <div>
                        <div style="font-weight:700; font-size:15px; color:#252525;">🎓 Congratulations — your certificate is ready!</div>
                        <div style="font-size:12.5px; color:#777;">You completed every material and quiz in the {{ config('courses.types.online.label') }}.</div>
                    </div>
                </div>
                <a href="{{ route('certificate.view', $onlineCertificate) }}" target="_blank" class="btn"
                   style="background:#252525; color:#C9A84C; font-weight:700; border:1px solid #C9A84C; padding:8px 20px;">
                    <i class="fas fa-download mr-1"></i> View / Print Certificate
                </a>
            </div>
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-12 mb-3">
        <div class="card shadow-sm" style="border-left: 4px solid #C9A84C; border-radius: 8px;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center" style="gap:16px;">
                    {{-- Avatar (initials) --}}
                    <div style="width:56px;height:56px;border-radius:50%;border:3px solid #C9A84C;overflow:hidden;flex-shrink:0;background:#252525;">
                        <div style="width:100%;height:100%;background:#C9A84C;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:700;color:#fff;">
                            {{ strtoupper(substr(auth()->user()->name ?? 'T', 0, 1)) }}
                        </div>
                    </div>
                    <div>
                        <h5 class="mb-1" style="color: #252525; font-weight: 700;">
                            Welcome back, {{ $trainee->name ?? auth()->user()->name }}!
                        </h5>
                        @if($trainee)
                            <p class="mb-0 text-muted" style="font-size:13px;">
                                @if($trainee->institution) <span><i class="fas fa-hospital-alt mr-1"></i>{{ $trainee->institution }}</span> @endif
                                @if($trainee->specialty) &nbsp;&bull;&nbsp; <span>{{ $trainee->specialty }}</span> @endif
                                @if($trainee->enrollment_date) &nbsp;&bull;&nbsp; <span>Enrolled: {{ \Carbon\Carbon::parse($trainee->enrollment_date)->format('M Y') }}</span> @endif
                            </p>
                        @else
                            <p class="mb-0 text-warning" style="font-size:13px;">
                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                No trainee profile linked. Please contact the administrator.
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="stat-tile" style="--tile-accent:#252525;">
            <div class="stat-tile-number">{{ $totalSessions }}</div>
            <div class="stat-tile-label">Total Sessions</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="stat-tile" style="--tile-accent:#28a745;">
            <div class="stat-tile-number">{{ $completedSessions }}</div>
            <div class="stat-tile-label">Completed Sessions</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="stat-tile" style="--tile-accent:#C9A84C;">
            <div class="stat-tile-number">{{ $totalMaterials }}</div>
            <div class="stat-tile-label">Available Materials</div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="stat-tile" style="--tile-accent:#a02626;">
            <div class="stat-tile-number">{{ $myDocuments }}</div>
            <div class="stat-tile-label">My Documents</div>
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm" style="border-radius:8px;">
            <div class="card-header" style="background:#252525; color:#C9A84C; font-weight:700; border-radius:8px 8px 0 0;">
                <i class="fas fa-rocket mr-2"></i> Quick Links
            </div>
            <div class="card-body">
                <a href="{{ route('trainee.timetable') }}" class="btn btn-block mb-2" style="background:#252525; color:#C9A84C; font-weight:600; border:1px solid #C9A84C;">
                    <i class="fas fa-calendar-alt mr-2"></i> View Programme / Timetable
                </a>
                <a href="{{ route('trainee.materials') }}" class="btn btn-block mb-2" style="background:#a02626; color:#fff; font-weight:600;">
                    <i class="fas fa-book mr-2"></i> Browse Materials
                </a>
                <a href="{{ route('trainee.quizzes.index') }}" class="btn btn-block mb-2" style="background:#2c7a4b; color:#fff; font-weight:600;">
                    <i class="fas fa-question-circle mr-2"></i> Take Quizzes
                </a>
                <a href="{{ route('trainee.documents.index') }}" class="btn btn-block" style="background:#f4f6f9; color:#252525; font-weight:600; border:1px solid #ddd;">
                    <i class="fas fa-folder-open mr-2"></i> Upload Documents
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm" style="border-radius:8px;">
            <div class="card-header" style="background:#252525; color:#C9A84C; font-weight:700; border-radius:8px 8px 0 0;">
                <i class="fas fa-info-circle mr-2"></i> Progress Overview
            </div>
            <div class="card-body">
                @php
                    $pct = $totalSessions > 0 ? round(($completedSessions / $totalSessions) * 100) : 0;
                    $qpct = $quizCount > 0 ? round(($quizPassed / $quizCount) * 100) : 0;
                @endphp
                <p class="mb-1" style="font-size:13px; font-weight:600;">Sessions Completed</p>
                <div class="progress mb-1" style="height:8px; border-radius:4px;">
                    <div class="progress-bar" role="progressbar"
                         style="width: {{ $pct }}%; background:#C9A84C;"
                         aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <p class="text-right mb-3" style="font-size:12px; color:#666;">{{ $completedSessions }} / {{ $totalSessions }} ({{ $pct }}%)</p>

                <p class="mb-1" style="font-size:13px; font-weight:600;">Quizzes Passed</p>
                <div class="progress mb-1" style="height:8px; border-radius:4px;">
                    <div class="progress-bar" role="progressbar"
                         style="width: {{ $qpct }}%; background:#2c7a4b;"
                         aria-valuenow="{{ $qpct }}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <p class="text-right mb-{{ $onlineProgress ? 3 : 0 }}" style="font-size:12px; color:#666;">{{ $quizPassed }} / {{ $quizCount }} ({{ $qpct }}%)</p>

                @if($onlineProgress)
                @php $mpct = $onlineProgress['totalMaterials'] > 0 ? round(($onlineProgress['viewedMaterials'] / $onlineProgress['totalMaterials']) * 100) : 0; @endphp
                <p class="mb-1" style="font-size:13px; font-weight:600;">Materials Viewed</p>
                <div class="progress mb-1" style="height:8px; border-radius:4px;">
                    <div class="progress-bar" role="progressbar"
                         style="width: {{ $mpct }}%; background:#a02626;"
                         aria-valuenow="{{ $mpct }}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <p class="text-right mb-0" style="font-size:12px; color:#666;">{{ $onlineProgress['viewedMaterials'] }} / {{ $onlineProgress['totalMaterials'] }} ({{ $mpct }}%)</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
