@extends('layouts.admin')
@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert" style="max-width:680px;">
    <i class="fas fa-check-circle mr-2"></i> {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="card" style="max-width:680px;">
    <div class="card-header cosecsa-card-header">
        <i class="fas fa-user-edit mr-2"></i> {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route('admin.users.update', [$user->id]) }}" method="POST" autocomplete="off">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div class="form-group">
                <label for="name">{{ trans('cruds.user.fields.name') }} *</label>
                <input type="text" id="name" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                       value="{{ old('name', $user->name) }}" required autocomplete="off">
                @if($errors->has('name'))
                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                @endif
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label for="email">{{ trans('cruds.user.fields.email') }} *</label>
                <input type="email" id="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                       value="{{ old('email', $user->email) }}" required autocomplete="off">
                @if($errors->has('email'))
                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                @endif
            </div>

            {{-- Roles — checkbox pills --}}
            <div class="form-group {{ $errors->has('roles') ? 'has-error' : '' }}">
                <label class="d-block mb-1">{{ trans('cruds.user.fields.roles') }} *</label>
                <div class="roles-picker">
                    <div class="mb-2" style="display:flex; gap:6px;">
                        <button type="button" class="btn btn-sm btn-outline-secondary roles-select-all">
                            <i class="fas fa-check-double mr-1"></i>Select All
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-secondary roles-deselect-all">
                            <i class="fas fa-times mr-1"></i>Deselect All
                        </button>
                    </div>
                    <div class="roles-checkbox-group">
                        @foreach($roles as $id => $roleName)
                        <label class="roles-checkbox-label {{ (in_array($id, old('roles', $user->roles->pluck('id')->toArray()))) ? 'roles-checked' : '' }}">
                            <input type="checkbox" name="roles[]" value="{{ $id }}"
                                   {{ (in_array($id, old('roles', $user->roles->pluck('id')->toArray()))) ? 'checked' : '' }}>
                            {{ $roleName }}
                        </label>
                        @endforeach
                    </div>
                </div>
                @if($errors->has('roles'))
                    <div class="text-danger mt-1" style="font-size:12px;">{{ $errors->first('roles') }}</div>
                @endif
            </div>

            <div class="mt-3">
                <button type="submit" class="btn btn-cosecsa">
                    <i class="fas fa-save mr-1"></i> {{ trans('global.save') }}
                </button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-light ml-2">
                    <i class="fas fa-arrow-left mr-1"></i> Back
                </a>
            </div>
        </form>
    </div>
</div>

{{-- Reset Password Card --}}
<div class="card mt-3" style="max-width:680px; border-left:4px solid #e67e22;">
    <div class="card-header" style="background:#fff8f2; border-bottom:1px solid #fde8d0;">
        <h5 class="mb-0" style="color:#e67e22; font-size:15px;">
            <i class="fas fa-key mr-2"></i> Reset Password for {{ $user->name }}
        </h5>
    </div>
    <div class="card-body">
        @if($errors->hasBag('default') && $errors->has('new_password'))
            <div class="alert alert-danger py-2">{{ $errors->first('new_password') }}</div>
        @endif
        <form action="{{ route('admin.users.resetPassword', $user->id) }}" method="POST" autocomplete="off">
            @csrf
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="new_password" style="font-size:13px;">New Password <span class="text-danger">*</span></label>
                    <input type="password" id="new_password" name="new_password"
                           class="form-control form-control-sm {{ $errors->has('new_password') ? 'is-invalid' : '' }}"
                           placeholder="Min. 6 characters" autocomplete="new-password">
                    @if($errors->has('new_password'))
                        <div class="invalid-feedback">{{ $errors->first('new_password') }}</div>
                    @endif
                </div>
                <div class="form-group col-md-6">
                    <label for="new_password_confirmation" style="font-size:13px;">Confirm Password <span class="text-danger">*</span></label>
                    <input type="password" id="new_password_confirmation" name="new_password_confirmation"
                           class="form-control form-control-sm"
                           placeholder="Repeat password" autocomplete="new-password">
                </div>
            </div>
            <button type="submit" class="btn btn-sm btn-warning">
                <i class="fas fa-key mr-1"></i> Set New Password
            </button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
$(function () {
    // Toggle checked state visually
    $(document).on('change', '.roles-checkbox-label input[type=checkbox]', function () {
        $(this).closest('.roles-checkbox-label').toggleClass('roles-checked', this.checked);
    });
    // Select / Deselect all
    $('.roles-select-all').on('click', function () {
        $('.roles-checkbox-label input[type=checkbox]').prop('checked', true)
            .closest('.roles-checkbox-label').addClass('roles-checked');
    });
    $('.roles-deselect-all').on('click', function () {
        $('.roles-checkbox-label input[type=checkbox]').prop('checked', false)
            .closest('.roles-checkbox-label').removeClass('roles-checked');
    });
});
</script>
@endsection
