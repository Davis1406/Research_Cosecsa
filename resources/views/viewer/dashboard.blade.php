@extends('layouts.viewer')
@section('page-title', 'Dashboard')

@section('content')
<div class="mb-4">
    <h5 style="font-weight:800; font-size:1.1rem; margin-bottom:4px;">
        Welcome, {{ auth()->user()->name }} <span style="font-size:14px; color:#C9A84C;">👋</span>
    </h5>
    <p style="color:#718096; font-size:13.5px; margin:0;">Here's an overview of the COSECSA Research Training Programme.</p>
</div>

<div class="row">
    <div class="col-6 col-md-3 mb-3">
        <div class="card" style="text-align:center; padding:22px 12px;">
            <div style="font-size:32px; font-weight:800; color:#C9A84C;">{{ $speakerCount }}</div>
            <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#a0aec0; margin-top:4px;">Facilitators</div>
            <a href="{{ route('viewer.facilitators') }}" style="font-size:12px; color:#C9A84C; text-decoration:none; margin-top:6px; display:block;">View all →</a>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="card" style="text-align:center; padding:22px 12px;">
            <div style="font-size:32px; font-weight:800; color:#2c7a4b;">{{ $traineeCount }}</div>
            <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#a0aec0; margin-top:4px;">Trainees</div>
            <a href="{{ route('viewer.trainees') }}" style="font-size:12px; color:#2c7a4b; text-decoration:none; margin-top:6px; display:block;">View all →</a>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="card" style="text-align:center; padding:22px 12px;">
            <div style="font-size:32px; font-weight:800; color:#2d3748;">{{ $sessionCount }}</div>
            <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#a0aec0; margin-top:4px;">Sessions</div>
            <a href="{{ route('viewer.timetable') }}" style="font-size:12px; color:#2d3748; text-decoration:none; margin-top:6px; display:block;">View timetable →</a>
        </div>
    </div>
    <div class="col-6 col-md-3 mb-3">
        <div class="card" style="text-align:center; padding:22px 12px;">
            <div style="font-size:32px; font-weight:800; color:#2c7a4b;">{{ $completedCount }}</div>
            <div style="font-size:12px; font-weight:700; text-transform:uppercase; letter-spacing:.5px; color:#a0aec0; margin-top:4px;">Completed</div>
            @if($sessionCount > 0)
            <div style="font-size:12px; color:#a0aec0; margin-top:6px;">{{ round($completedCount / $sessionCount * 100) }}% done</div>
            @endif
        </div>
    </div>
</div>

<div class="row mt-2">
    <div class="col-md-6 mb-3">
        <div class="card" style="padding:20px;">
            <h6 style="font-weight:800; margin-bottom:14px;"><i class="fas fa-bolt mr-2" style="color:#C9A84C;"></i>Quick Links</h6>
            <div style="display:flex; flex-direction:column; gap:8px;">
                <a href="{{ route('viewer.facilitators') }}" style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:#f8f9fc;border-radius:8px;text-decoration:none;color:#1a202c;font-weight:600;font-size:13.5px;border:1.5px solid #e2e8f0;transition:border-color .13s;" onmouseover="this.style.borderColor='#C9A84C'" onmouseout="this.style.borderColor='#e2e8f0'">
                    <i class="fas fa-chalkboard-teacher" style="color:#C9A84C;width:16px;"></i> Facilitator Directory
                </a>
                <a href="{{ route('viewer.trainees') }}" style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:#f8f9fc;border-radius:8px;text-decoration:none;color:#1a202c;font-weight:600;font-size:13.5px;border:1.5px solid #e2e8f0;transition:border-color .13s;" onmouseover="this.style.borderColor='#C9A84C'" onmouseout="this.style.borderColor='#e2e8f0'">
                    <i class="fas fa-user-graduate" style="color:#2c7a4b;width:16px;"></i> Trainee List
                </a>
                <a href="{{ route('viewer.trainees') }}?export=csv" style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:#f8f9fc;border-radius:8px;text-decoration:none;color:#1a202c;font-weight:600;font-size:13.5px;border:1.5px solid #e2e8f0;transition:border-color .13s;" onmouseover="this.style.borderColor='#C9A84C'" onmouseout="this.style.borderColor='#e2e8f0'">
                    <i class="fas fa-download" style="color:#2c7a4b;width:16px;"></i> Export Trainees CSV
                </a>
                <a href="{{ route('viewer.timetable') }}" style="display:flex;align-items:center;gap:10px;padding:10px 14px;background:#f8f9fc;border-radius:8px;text-decoration:none;color:#1a202c;font-weight:600;font-size:13.5px;border:1.5px solid #e2e8f0;transition:border-color .13s;" onmouseover="this.style.borderColor='#C9A84C'" onmouseout="this.style.borderColor='#e2e8f0'">
                    <i class="fas fa-calendar-alt" style="color:#2d3748;width:16px;"></i> Programme
                </a>
            </div>
        </div>
    </div>
    <div class="col-md-6 mb-3">
        <div class="card" style="padding:20px;">
            <h6 style="font-weight:800; margin-bottom:14px;"><i class="fas fa-info-circle mr-2" style="color:#C9A84C;"></i>Your Access</h6>
            <p style="font-size:13.5px; color:#4a5568; line-height:1.7; margin:0;">
                You have <strong>read-only</strong> access to the COSECSA Research Training System.
                You can browse facilitator profiles, view and export the trainee list, and explore
                the full workshop timetable with session materials.
            </p>
            <div style="margin-top:12px; font-size:12px; color:#a0aec0;">
                <i class="fas fa-lock mr-1"></i> No editing or administrative actions available.
            </div>
        </div>
    </div>
</div>
@endsection
