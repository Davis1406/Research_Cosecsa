@extends('layouts.facilitator')

@section('page-title', $trainee ? 'Edit Trainee' : 'Add Trainee')

@section('content')
@php $isEdit = !is_null($trainee); @endphp

<div class="d-flex align-items-center mb-3" style="gap:12px;">
    <a href="{{ route('facilitator.trainees') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6;">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
    <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
        <i class="fas fa-user-graduate mr-2" style="color:#C9A84C;"></i>
        {{ $isEdit ? 'Edit Trainee: ' . $trainee->name : 'Add New Trainee' }}
    </h5>
</div>

<form action="{{ $isEdit ? route('facilitator.trainees.update', $trainee->id) : route('facilitator.trainees.store') }}"
      method="POST">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="row">
        <div class="col-lg-8">
            {{-- Account Details --}}
            <div class="card shadow-sm mb-3" style="border-radius:10px; overflow:hidden;">
                <div class="card-header" style="background:#fff; border-left:4px solid #C9A84C; padding:14px 20px;">
                    <strong style="color:#2d3748; font-size:14px;"><i class="fas fa-user mr-2" style="color:#C9A84C;"></i>Account Details</strong>
                </div>
                <div class="card-body" style="padding:24px;">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label-sm">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $trainee->name ?? '') }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label-sm">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $trainee->email ?? $traineeUser->email ?? '') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label-sm">{{ $isEdit ? 'New Password' : 'Password' }} {{ $isEdit ? '' : '*' }}</label>
                            <input type="password" name="password" class="form-control" placeholder="{{ $isEdit ? 'Leave blank to keep current' : 'Min. 8 characters' }}" {{ $isEdit ? '' : 'required' }}>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label-sm">Confirm Password {{ $isEdit ? '' : '*' }}</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat password" {{ $isEdit ? '' : 'required' }}>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Professional Details --}}
            <div class="card shadow-sm mb-3" style="border-radius:10px; overflow:hidden;">
                <div class="card-header" style="background:#fff; border-left:4px solid #C9A84C; padding:14px 20px;">
                    <strong style="color:#2d3748; font-size:14px;"><i class="fas fa-id-badge mr-2" style="color:#C9A84C;"></i>Professional Details</strong>
                </div>
                <div class="card-body" style="padding:24px;">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label-sm">Institution / Hospital</label>
                            <input type="text" name="institution" class="form-control" value="{{ old('institution', $trainee->institution ?? '') }}" placeholder="e.g. Kenyatta National Hospital">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label-sm">Specialty</label>
                            <input type="text" name="specialty" class="form-control" value="{{ old('specialty', $trainee->specialty ?? '') }}" placeholder="e.g. General Surgery">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="form-label-sm">Country</label>
                            <input type="text" name="country" class="form-control" value="{{ old('country', $trainee->country ?? '') }}" placeholder="e.g. Kenya">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label-sm">Phone</label>
                            <input type="text" name="phone" class="form-control" value="{{ old('phone', $trainee->phone ?? '') }}" placeholder="+254...">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label-sm">Registration No.</label>
                            <input type="text" name="registration_number" class="form-control" value="{{ old('registration_number', $trainee->registration_number ?? '') }}" placeholder="e.g. COSECSA/2025/001">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label-sm">Enrollment Date</label>
                            <input type="date" name="enrollment_date" class="form-control" value="{{ old('enrollment_date', $trainee && $trainee->enrollment_date ? $trainee->enrollment_date->format('Y-m-d') : '') }}">
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label-sm">Notes (internal)</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Any internal notes...">{{ old('notes', $trainee->notes ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm" style="border-radius:10px; overflow:hidden;">
                <div class="card-body text-center" style="padding:28px 20px;">
                    <div style="width:72px;height:72px;border-radius:50%;background:#C9A84C;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:700;color:#fff;margin:0 auto 14px;">
                        {{ strtoupper(substr(old('name', $trainee->name ?? 'T'), 0, 1)) }}
                    </div>
                    <div style="font-size:13px;color:#888;margin-bottom:18px;">{{ $isEdit ? 'Editing existing trainee' : 'New trainee account' }}</div>
                    <button type="submit" class="btn btn-block" style="background:#C9A84C; color:#fff; font-weight:700; padding:10px; border-radius:6px; font-size:14px;">
                        <i class="fas fa-save mr-2"></i>{{ $isEdit ? 'Save Changes' : 'Create Account' }}
                    </button>
                    <a href="{{ route('facilitator.trainees') }}" class="btn btn-block mt-2" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:13px;">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

@section('styles')
<style>
.form-label-sm { font-size:11px; font-weight:700; color:#666; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px; display:block; }
</style>
@endsection
@endsection
