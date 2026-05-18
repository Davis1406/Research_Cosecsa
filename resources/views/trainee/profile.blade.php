@extends('layouts.trainee')

@section('page-title', 'My Profile')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm" style="border-radius:8px; overflow:hidden;">
            <div class="card-header" style="background:#252525; color:#C9A84C; font-weight:700; padding:14px 20px;">
                <i class="fas fa-user-edit mr-2"></i> Edit My Profile
            </div>
            <div class="card-body" style="padding:24px;">
                @if(!$trainee)
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        No trainee profile is linked to your account. Please contact the administrator to set up your profile.
                    </div>
                @else
                    <form action="{{ route('trainee.profile.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label style="font-size:12px; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:0.5px;">
                                    Full Name <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $trainee->name) }}" required>
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-size:12px; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:0.5px;">
                                    Email Address
                                </label>
                                <input type="email" class="form-control" value="{{ auth()->user()->email }}" disabled
                                       style="background:#f8f9fa; color:#666;">
                                <small class="text-muted">Email cannot be changed here.</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label style="font-size:12px; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:0.5px;">Phone</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $trainee->phone) }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-size:12px; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:0.5px;">Country</label>
                                <input type="text" name="country" class="form-control" value="{{ old('country', $trainee->country) }}">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label style="font-size:12px; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:0.5px;">Institution</label>
                                <input type="text" name="institution" class="form-control" value="{{ old('institution', $trainee->institution) }}">
                            </div>
                            <div class="col-md-6 form-group">
                                <label style="font-size:12px; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:0.5px;">Registration Number</label>
                                <input type="text" name="registration_number" class="form-control" value="{{ old('registration_number', $trainee->registration_number) }}">
                            </div>
                        </div>

                        <div class="form-group">
                            <label style="font-size:12px; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:0.5px;">Specialty / Discipline</label>
                            <input type="text" name="specialty" class="form-control" value="{{ old('specialty', $trainee->specialty) }}">
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn" style="background:#a02626; color:#fff; font-weight:600; padding:8px 24px;">
                                <i class="fas fa-save mr-2"></i> Save Profile
                            </button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card shadow-sm" style="border-radius:8px; overflow:hidden;">
            <div class="card-header" style="background:#252525; color:#C9A84C; font-weight:700; padding:14px 20px;">
                <i class="fas fa-id-badge mr-2"></i> Profile Summary
            </div>
            <div class="card-body text-center" style="padding:24px;">
                <div style="width:64px; height:64px; border-radius:50%; background:#252525; color:#C9A84C; font-size:26px; font-weight:700; display:flex; align-items:center; justify-content:center; margin:0 auto 12px;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div style="font-weight:700; font-size:15px; color:#252525;">{{ auth()->user()->name }}</div>
                <div style="font-size:12px; color:#888; margin-bottom:8px;">{{ auth()->user()->email }}</div>
                @if($trainee)
                    <span class="badge" style="background:#a02626; color:#fff; font-size:11px; padding:4px 12px;">Trainee</span>
                    @if($trainee->enrollment_date)
                        <div class="mt-2" style="font-size:12px; color:#666;">
                            Enrolled: {{ \Carbon\Carbon::parse($trainee->enrollment_date)->format('M j, Y') }}
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
