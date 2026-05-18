@extends('layouts.facilitator')

@section('page-title', $facilitator ? 'Edit Facilitator' : 'Add Facilitator')

@section('content')
@php $isEdit = !is_null($facilitator); @endphp

<div class="d-flex align-items-center mb-3" style="gap:12px;">
    <a href="{{ route('facilitator.facilitators.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6;">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
    <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
        <i class="fas fa-chalkboard-teacher mr-2" style="color:#C9A84C;"></i>
        {{ $isEdit ? 'Edit Facilitator: ' . $facilitator->name : 'Add New Facilitator' }}
    </h5>
</div>

<form action="{{ $isEdit ? route('facilitator.facilitators.update', $facilitator->id) : route('facilitator.facilitators.store') }}"
      method="POST">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="row">
        <div class="col-lg-8">
            {{-- Account --}}
            <div class="card shadow-sm mb-3" style="border-radius:10px; overflow:hidden;">
                <div class="card-header" style="background:#fff; border-left:4px solid #C9A84C; padding:14px 20px;">
                    <strong style="color:#2d3748; font-size:1rem;"><i class="fas fa-user mr-2" style="color:#C9A84C;"></i>Account Details</strong>
                </div>
                <div class="card-body" style="padding:24px;">
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label-sm">Full Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $facilitator->name ?? '') }}" required>
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label-sm">Email Address <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $facilitator->email ?? '') }}" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label-sm">Role <span class="text-danger">*</span></label>
                            <select name="role_id" class="form-control" required>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}"
                                        {{ old('role_id', $isEdit ? $facilitator->roles->first()?->id : '') == $role->id ? 'selected' : '' }}>
                                        {{ $role->title }}
                                    </option>
                                @endforeach
                            </select>
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

            {{-- Profile / Speaker info --}}
            <div class="card shadow-sm mb-3" style="border-radius:10px; overflow:hidden;">
                <div class="card-header" style="background:#fff; border-left:4px solid #C9A84C; padding:14px 20px;">
                    <strong style="color:#2d3748; font-size:1rem;"><i class="fas fa-id-card mr-2" style="color:#C9A84C;"></i>Profile Information</strong>
                </div>
                <div class="card-body" style="padding:24px;">
                    <div class="form-group">
                        <label class="form-label-sm">Bio / Short Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Brief professional bio...">{{ old('description', $isEdit ? $facilitator->speaker?->description : '') }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-4 form-group">
                            <label class="form-label-sm"><i class="fab fa-twitter mr-1" style="color:#1da1f2;"></i>Twitter URL</label>
                            <input type="text" name="twitter" class="form-control" value="{{ old('twitter', $isEdit ? $facilitator->speaker?->twitter : '') }}" placeholder="https://twitter.com/...">
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label-sm"><i class="fab fa-linkedin mr-1" style="color:#0077b5;"></i>LinkedIn URL</label>
                            <input type="text" name="linkedin" class="form-control" value="{{ old('linkedin', $isEdit ? $facilitator->speaker?->linkedin : '') }}" placeholder="https://linkedin.com/in/...">
                        </div>
                        <div class="col-md-4 form-group mb-0">
                            <label class="form-label-sm"><i class="fab fa-facebook mr-1" style="color:#1877f2;"></i>Facebook URL</label>
                            <input type="text" name="facebook" class="form-control" value="{{ old('facebook', $isEdit ? $facilitator->speaker?->facebook : '') }}" placeholder="https://facebook.com/...">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm" style="border-radius:10px; overflow:hidden;">
                <div class="card-body text-center" style="padding:28px 20px;">
                    @php
                        $previewPhoto = $isEdit ? ($facilitator->speaker?->photo?->url ?? null) : null;
                        $previewInit  = strtoupper(substr(old('name', $isEdit ? $facilitator->name : 'F'), 0, 1));
                    @endphp
                    <div style="width:72px;height:72px;border-radius:50%;overflow:hidden;border:3px solid #C9A84C;margin:0 auto 14px;">
                        @if($previewPhoto)
                            <img src="{{ $previewPhoto }}" style="width:100%;height:100%;object-fit:cover;" alt="">
                        @else
                            <div style="width:100%;height:100%;background:#C9A84C;display:flex;align-items:center;justify-content:center;font-size:28px;font-weight:700;color:#fff;">{{ $previewInit }}</div>
                        @endif
                    </div>
                    <div style="font-size:0.875rem;color:#888;margin-bottom:18px;">{{ $isEdit ? 'Editing facilitator account' : 'New facilitator account' }}</div>
                    <button type="submit" class="btn btn-block" style="background:#C9A84C; color:#fff; font-weight:700; padding:10px; border-radius:6px; font-size:1rem;">
                        <i class="fas fa-save mr-2"></i>{{ $isEdit ? 'Save Changes' : 'Create Account' }}
                    </button>
                    <a href="{{ route('facilitator.facilitators.index') }}" class="btn btn-block mt-2" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:0.9rem;">
                        Cancel
                    </a>
                    @if($isEdit && $facilitator->speaker)
                        <div style="margin-top:14px; padding-top:14px; border-top:1px solid #f0f0f0; font-size:0.8rem; color:#aaa;">
                            Profile photo can be updated from the facilitator's own Profile page.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</form>

@section('styles')
<style>
.form-label-sm { font-size:0.8rem; font-weight:700; color:#666; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px; display:block; }
</style>
@endsection
@endsection
