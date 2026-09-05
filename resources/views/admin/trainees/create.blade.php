@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header cosecsa-card-header">
        <i class="fas fa-user-plus mr-2"></i> {{ trans('global.add') }} {{ trans('cruds.trainee.title_singular') }}
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.trainees.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="course_type">Course</label>
                <select name="course_type" id="course_type" class="form-control">
                    @foreach(config('courses.types') as $key => $meta)
                        <option value="{{ $key }}" {{ old('course_type', $courseType) == $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.trainee.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <div class="invalid-feedback">{{ $errors->first('name') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label class="required" for="email">{{ trans('cruds.trainee.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email" name="email" id="email" value="{{ old('email', '') }}" required>
                @if($errors->has('email'))
                    <div class="invalid-feedback">{{ $errors->first('email') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label for="phone">{{ trans('cruds.trainee.fields.phone') }}</label>
                <input class="form-control {{ $errors->has('phone') ? 'is-invalid' : '' }}" type="text" name="phone" id="phone" value="{{ old('phone', '') }}">
                @if($errors->has('phone'))
                    <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label for="institution">{{ trans('cruds.trainee.fields.institution') }}</label>
                <input class="form-control {{ $errors->has('institution') ? 'is-invalid' : '' }}" type="text" name="institution" id="institution" value="{{ old('institution', '') }}">
                @if($errors->has('institution'))
                    <div class="invalid-feedback">{{ $errors->first('institution') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label for="registration_number">{{ trans('cruds.trainee.fields.registration_number') }}</label>
                <input class="form-control {{ $errors->has('registration_number') ? 'is-invalid' : '' }}" type="text" name="registration_number" id="registration_number" value="{{ old('registration_number', '') }}">
                @if($errors->has('registration_number'))
                    <div class="invalid-feedback">{{ $errors->first('registration_number') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label for="country">{{ trans('cruds.trainee.fields.country') }}</label>
                <input class="form-control {{ $errors->has('country') ? 'is-invalid' : '' }}" type="text" name="country" id="country" value="{{ old('country', '') }}">
                @if($errors->has('country'))
                    <div class="invalid-feedback">{{ $errors->first('country') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label for="specialty">{{ trans('cruds.trainee.fields.specialty') }}</label>
                <input class="form-control {{ $errors->has('specialty') ? 'is-invalid' : '' }}" type="text" name="specialty" id="specialty" value="{{ old('specialty', '') }}" placeholder="e.g. Surgery Year 1, Orthopaedics">
                @if($errors->has('specialty'))
                    <div class="invalid-feedback">{{ $errors->first('specialty') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label for="enrollment_date">{{ trans('cruds.trainee.fields.enrollment_date') }}</label>
                <input class="form-control date {{ $errors->has('enrollment_date') ? 'is-invalid' : '' }}" type="text" name="enrollment_date" id="enrollment_date" value="{{ old('enrollment_date', '') }}">
                @if($errors->has('enrollment_date'))
                    <div class="invalid-feedback">{{ $errors->first('enrollment_date') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label for="notes">{{ trans('cruds.trainee.fields.notes') }}</label>
                <textarea class="form-control {{ $errors->has('notes') ? 'is-invalid' : '' }}" name="notes" id="notes" rows="3">{{ old('notes') }}</textarea>
                @if($errors->has('notes'))
                    <div class="invalid-feedback">{{ $errors->first('notes') }}</div>
                @endif
            </div>
            <div class="form-group">
                <button class="btn btn-cosecsa" type="submit">
                    <i class="fas fa-save mr-1"></i> {{ trans('global.save') }}
                </button>
                <a class="btn btn-secondary ml-2" href="{{ route('admin.trainees.index', ['course' => $courseType]) }}">{{ trans('global.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    Dropzone.autoDiscover = false;
    var uploadedFiles = {};
    $(function() {
        $('.date').datetimepicker({ format: 'YYYY-MM-DD', locale: '{{ app()->getLocale() }}' });
    });
</script>
@endsection
