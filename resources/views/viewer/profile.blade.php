@extends('layouts.viewer')

@section('page-title', 'My Profile')

@section('content')
<div class="row">
    <div class="col-lg-7">
        <div class="card shadow-sm" style="border-radius:8px; overflow:hidden;">
            <div class="card-header" style="background:#252525; color:#C9A84C; font-weight:700; padding:14px 20px;">
                <i class="fas fa-user-edit mr-2"></i> Edit My Profile
            </div>
            <div class="card-body" style="padding:24px;">
                @if(session('message'))
                    <div class="alert alert-success py-2 mb-3">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('message') }}
                    </div>
                @endif

                <form action="{{ route('viewer.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label style="font-size:12px; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:0.5px;">
                            Full Name <span class="text-danger">*</span>
                        </label>
                        <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                               value="{{ old('name', $user->name) }}" required>
                        @if($errors->has('name'))
                            <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label style="font-size:12px; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:0.5px;">
                            Email Address
                        </label>
                        <input type="email" class="form-control" value="{{ $user->email }}" disabled
                               style="background:#f8f9fa; color:#666;">
                        <small class="text-muted">Email cannot be changed here.</small>
                    </div>

                    <hr style="margin:20px 0;">
                    <div style="font-size:12px; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:14px;">
                        Change Password <span class="font-weight-normal text-muted">(optional)</span>
                    </div>

                    <div class="form-group">
                        <label style="font-size:12px; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:0.5px;">New Password</label>
                        <input type="password" name="password" class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}"
                               placeholder="Leave blank to keep current password">
                        @if($errors->has('password'))
                            <div class="invalid-feedback">{{ $errors->first('password') }}</div>
                        @endif
                    </div>

                    <div class="form-group">
                        <label style="font-size:12px; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:0.5px;">Confirm New Password</label>
                        <input type="password" name="password_confirmation" class="form-control"
                               placeholder="Repeat new password">
                    </div>

                    <div class="text-right">
                        <button type="submit" class="btn" style="background:#a02626; color:#fff; font-weight:600; padding:8px 24px;">
                            <i class="fas fa-save mr-2"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card shadow-sm" style="border-radius:8px; overflow:hidden;">
            <div class="card-header" style="background:#252525; color:#C9A84C; font-weight:700; padding:14px 20px;">
                <i class="fas fa-id-badge mr-2"></i> Account Summary
            </div>
            <div class="card-body text-center" style="padding:28px;">
                <div style="width:64px; height:64px; border-radius:50%; background:#252525; color:#C9A84C; font-size:26px; font-weight:700;
                            display:flex; align-items:center; justify-content:center; margin:0 auto 14px;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div style="font-weight:700; font-size:15px; color:#252525;">{{ $user->name }}</div>
                <div style="font-size:12px; color:#888; margin-bottom:10px;">{{ $user->email }}</div>
                <span class="badge" style="background:#C9A84C; color:#252525; font-size:11px; padding:4px 14px; font-weight:700;">
                    <i class="fas fa-eye mr-1"></i> Viewer
                </span>
            </div>
        </div>
    </div>
</div>
@endsection
