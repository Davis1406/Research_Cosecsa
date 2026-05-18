@extends('layouts.trainee')

@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-12 mb-3">
        <div class="card shadow-sm" style="border-left: 4px solid #C9A84C; border-radius: 8px;">
            <div class="card-body py-3">
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
                        No trainee profile is linked to your account. Please contact the administrator.
                    </p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="card shadow-sm text-center h-100" style="border-radius:8px; border-top: 3px solid #252525;">
            <div class="card-body">
                <div style="font-size:28px; color:#252525; font-weight:700;">{{ $totalSessions }}</div>
                <div style="font-size:12px; color:#666; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Total Sessions</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="card shadow-sm text-center h-100" style="border-radius:8px; border-top: 3px solid #28a745;">
            <div class="card-body">
                <div style="font-size:28px; color:#28a745; font-weight:700;">{{ $completedSessions }}</div>
                <div style="font-size:12px; color:#666; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Completed Sessions</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="card shadow-sm text-center h-100" style="border-radius:8px; border-top: 3px solid #C9A84C;">
            <div class="card-body">
                <div style="font-size:28px; color:#C9A84C; font-weight:700;">{{ $totalMaterials }}</div>
                <div style="font-size:12px; color:#666; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Available Materials</div>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3 mb-3">
        <div class="card shadow-sm text-center h-100" style="border-radius:8px; border-top: 3px solid #a02626;">
            <div class="card-body">
                <div style="font-size:28px; color:#a02626; font-weight:700;">{{ $myDocuments }}</div>
                <div style="font-size:12px; color:#666; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">My Documents</div>
            </div>
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
                @php $pct = $totalSessions > 0 ? round(($completedSessions / $totalSessions) * 100) : 0; @endphp
                <p class="mb-1" style="font-size:13px; font-weight:600;">Sessions Completed</p>
                <div class="progress mb-2" style="height:12px; border-radius:6px;">
                    <div class="progress-bar" role="progressbar"
                         style="width: {{ $pct }}%; background: linear-gradient(90deg, #C9A84C, #a02626);"
                         aria-valuenow="{{ $pct }}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <p class="text-right mb-0" style="font-size:12px; color:#666;">{{ $completedSessions }} / {{ $totalSessions }} ({{ $pct }}%)</p>
            </div>
        </div>
    </div>
</div>
@endsection
