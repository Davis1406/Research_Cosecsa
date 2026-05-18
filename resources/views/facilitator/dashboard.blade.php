@extends('layouts.facilitator')

@section('page-title', 'Facilitator Dashboard')

@section('content')
<div class="row">
    <div class="col-12 mb-3">
        <div class="card shadow-sm" style="border-left: 4px solid #C9A84C; border-radius: 8px;">
            <div class="card-body py-3">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center" style="gap:16px;">
                        {{-- Avatar --}}
                        <div style="width:56px;height:56px;border-radius:50%;border:3px solid #C9A84C;overflow:hidden;flex-shrink:0;background:#f8f9fa;">
                            @if($speaker && $speaker->photo)
                                <img src="{{ $speaker->photo->url }}" style="width:100%;height:100%;object-fit:cover;" alt="Avatar">
                            @else
                                <div style="width:100%;height:100%;background:#C9A84C;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:700;color:#fff;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>
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
                    </div>
                    <span class="badge d-none d-sm-inline-block" style="font-size:12px; padding:6px 14px; {{ $isLead ? 'background:#C9A84C; color:#252525;' : 'background:#2c7a4b; color:#fff;' }}">
                        <i class="fas fa-{{ $isLead ? 'star' : 'chalkboard-teacher' }} mr-1"></i>
                        {{ $isLead ? 'Lead Facilitator' : 'Facilitator' }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- My Sessions --}}
    <div class="{{ $isLead ? 'col-6 col-md-3' : 'col-6 col-sm-4' }} mb-3">
        <a href="{{ route('facilitator.timetable') }}" style="text-decoration:none;">
            <div class="card shadow-sm text-center h-100" style="border-radius:8px; border-top:3px solid #252525; transition:box-shadow 0.15s;">
                <div class="card-body py-4">
                    <div style="font-size:2rem; color:#252525; font-weight:700; line-height:1;">{{ $mySessions }}</div>
                    <div style="font-size:11px; color:#666; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-top:6px;">
                        <i class="fas fa-calendar-check mr-1" style="color:#252525;"></i> My Sessions
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- My Materials --}}
    <div class="{{ $isLead ? 'col-6 col-md-3' : 'col-6 col-sm-4' }} mb-3">
        <a href="{{ route('facilitator.materials') }}" style="text-decoration:none;">
            <div class="card shadow-sm text-center h-100" style="border-radius:8px; border-top:3px solid #2c7a4b; transition:box-shadow 0.15s;">
                <div class="card-body py-4">
                    <div style="font-size:2rem; color:#2c7a4b; font-weight:700; line-height:1;">{{ $myMaterials }}</div>
                    <div style="font-size:11px; color:#666; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-top:6px;">
                        <i class="fas fa-book mr-1" style="color:#2c7a4b;"></i> My Materials
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- Total Materials --}}
    <div class="{{ $isLead ? 'col-6 col-md-3' : 'col-6 col-sm-4' }} mb-3">
        <a href="{{ route('facilitator.material-manager.index') }}" style="text-decoration:none;">
            <div class="card shadow-sm text-center h-100" style="border-radius:8px; border-top:3px solid #C9A84C; transition:box-shadow 0.15s;">
                <div class="card-body py-4">
                    <div style="font-size:2rem; color:#C9A84C; font-weight:700; line-height:1;">{{ $totalMaterials }}</div>
                    <div style="font-size:11px; color:#666; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-top:6px;">
                        <i class="fas fa-layer-group mr-1" style="color:#C9A84C;"></i> All Materials
                    </div>
                </div>
            </div>
        </a>
    </div>

    {{-- Enrolled Trainees (Lead only) --}}
    @if($isLead)
    <div class="col-6 col-md-3 mb-3">
        <a href="{{ route('facilitator.trainees') }}" style="text-decoration:none;">
            <div class="card shadow-sm text-center h-100" style="border-radius:8px; border-top:3px solid #a02626; transition:box-shadow 0.15s;">
                <div class="card-body py-4">
                    <div style="font-size:2rem; color:#a02626; font-weight:700; line-height:1;">{{ $totalTrainees }}</div>
                    <div style="font-size:11px; color:#666; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; margin-top:6px;">
                        <i class="fas fa-user-graduate mr-1" style="color:#a02626;"></i> Trainees
                    </div>
                </div>
            </div>
        </a>
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
