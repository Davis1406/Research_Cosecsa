@extends('layouts.facilitator')

@section('page-title', 'Facilitator Dashboard')

@section('content')
<div class="row">
    <div class="col-12 mb-3">
        <div class="card shadow-sm" style="border-left: 4px solid #C9A84C; border-radius: 8px;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="mb-1" style="color: #252525; font-weight: 700;">
                            Welcome, {{ $speaker ? $speaker->name : auth()->user()->name }}!
                        </h5>
                        <p class="mb-0 text-muted" style="font-size:13px;">
                            @if($speaker && $speaker->description)
                                {{ Str::limit($speaker->description, 100) }}
                            @else
                                COSECSA Research Training Workshop
                            @endif
                        </p>
                    </div>
                    <span class="badge" style="font-size:12px; padding:6px 14px; {{ $isLead ? 'background:#C9A84C; color:#252525;' : 'background:#a02626; color:#fff;' }}">
                        <i class="fas fa-{{ $isLead ? 'star' : 'chalkboard-teacher' }} mr-1"></i>
                        {{ $isLead ? 'Lead Facilitator' : 'Facilitator' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-4 mb-3">
        <div class="card shadow-sm text-center h-100" style="border-radius:8px; border-top: 3px solid #252525;">
            <div class="card-body">
                <div style="font-size:28px; color:#252525; font-weight:700;">{{ $mySessions }}</div>
                <div style="font-size:12px; color:#666; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">
                    My Sessions
                </div>
            </div>
        </div>
    </div>
    <div class="col-sm-4 mb-3">
        <div class="card shadow-sm text-center h-100" style="border-radius:8px; border-top: 3px solid #C9A84C;">
            <div class="card-body">
                <div style="font-size:28px; color:#C9A84C; font-weight:700;">{{ $totalMaterials }}</div>
                <div style="font-size:12px; color:#666; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">
                    Total Materials
                </div>
            </div>
        </div>
    </div>
    @if($isLead)
    <div class="col-sm-4 mb-3">
        <div class="card shadow-sm text-center h-100" style="border-radius:8px; border-top: 3px solid #a02626;">
            <div class="card-body">
                <div style="font-size:28px; color:#a02626; font-weight:700;">{{ $totalTrainees }}</div>
                <div style="font-size:12px; color:#666; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">
                    Enrolled Trainees
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<div class="row mt-2">
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm" style="border-radius:8px;">
            <div class="card-header" style="background:#252525; color:#C9A84C; font-weight:700; border-radius:8px 8px 0 0;">
                <i class="fas fa-rocket mr-2"></i> Quick Links
            </div>
            <div class="card-body">
                <a href="{{ route('facilitator.timetable') }}" class="btn btn-block mb-2" style="background:#252525; color:#C9A84C; font-weight:600; border:1px solid #C9A84C;">
                    <i class="fas fa-calendar-alt mr-2"></i> View My Timetable
                </a>
                <a href="{{ route('facilitator.materials') }}" class="btn btn-block mb-2" style="background:#a02626; color:#fff; font-weight:600;">
                    <i class="fas fa-book mr-2"></i> Manage Materials
                </a>
                @if($isLead)
                <a href="{{ route('facilitator.trainees') }}" class="btn btn-block" style="background:#f4f6f9; color:#252525; font-weight:600; border:1px solid #ddd;">
                    <i class="fas fa-users mr-2"></i> View Trainees
                </a>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card shadow-sm" style="border-radius:8px;">
            <div class="card-header" style="background:#252525; color:#C9A84C; font-weight:700; border-radius:8px 8px 0 0;">
                <i class="fas fa-info-circle mr-2"></i> Your Role
            </div>
            <div class="card-body">
                <div class="d-flex align-items-center mb-2">
                    <div style="width:40px; height:40px; border-radius:50%; background:{{ $isLead ? '#C9A84C' : '#a02626' }}; display:flex; align-items:center; justify-content:center; margin-right:12px;">
                        <i class="fas fa-{{ $isLead ? 'star' : 'chalkboard-teacher' }}" style="color:#fff; font-size:16px;"></i>
                    </div>
                    <div>
                        <div style="font-weight:700; font-size:14px;">{{ $isLead ? 'Lead Facilitator' : 'Facilitator' }}</div>
                        <div style="font-size:12px; color:#888;">
                            @if($isLead)
                                Full access to all sessions, materials, and trainee management.
                            @else
                                Access to your assigned sessions and materials.
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
