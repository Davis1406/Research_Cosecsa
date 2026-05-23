@extends('layouts.admin')
@section('content')

<div class="card" style="max-width:680px;">
    <div class="card-header cosecsa-card-header">
        <i class="fas fa-user-edit mr-2"></i> {{ trans('global.edit') }} {{ trans('cruds.user.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route('admin.users.update', [$user->id]) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div class="form-group">
                <label for="name">{{ trans('cruds.user.fields.name') }} *</label>
                <input type="text" id="name" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                       value="{{ old('name', $user->name) }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                @endif
            </div>

            {{-- Email --}}
            <div class="form-group">
                <label for="email">{{ trans('cruds.user.fields.email') }} *</label>
                <input type="email" id="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}"
                       value="{{ old('email', $user->email) }}" required>
                @if($errors->has('email'))
                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                @endif
            </div>

            {{-- Password --}}
            <div class="form-group">
                <label for="password">{{ trans('cruds.user.fields.password') }}
                    <small class="text-muted font-weight-normal ml-1">(leave blank to keep current)</small>
                </label>
                <input type="password" id="password" name="password"
                       class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}">
                @if($errors->has('password'))
                    <div class="invalid-feedback">{{ $errors->first('password') }}</div>
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
