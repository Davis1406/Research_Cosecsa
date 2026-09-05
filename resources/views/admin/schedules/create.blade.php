@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header cosecsa-card-header">
        <i class="fas fa-plus-circle mr-2"></i> {{ trans('global.create') }} {{ trans('cruds.schedule.title_singular') }}
    </div>
    <div class="card-body">
        <form action="{{ route('admin.schedules.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="course_type">Course</label>
                <select name="course_type" id="course_type" class="form-control">
                    @foreach(config('courses.types') as $key => $meta)
                        <option value="{{ $key }}" {{ old('course_type', $courseType) == $key ? 'selected' : '' }}>{{ $meta['label'] }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-row">
                <div class="form-group col-md-3">
                    <label class="required" for="day_number">{{ trans('cruds.schedule.fields.day_number') }}</label>
                    <input type="number" id="day_number" name="day_number" class="form-control {{ $errors->has('day_number') ? 'is-invalid' : '' }}" value="{{ old('day_number', '') }}" step="1" min="1" required>
                    @if($errors->has('day_number'))
                        <div class="invalid-feedback">{{ $errors->first('day_number') }}</div>
                    @endif
                </div>
                <div class="form-group col-md-3">
                    <label for="date">{{ trans('cruds.schedule.fields.date') }}</label>
                    <input type="text" id="date" name="date" class="form-control date {{ $errors->has('date') ? 'is-invalid' : '' }}" value="{{ old('date', '') }}" placeholder="YYYY-MM-DD">
                    @if($errors->has('date'))
                        <div class="invalid-feedback">{{ $errors->first('date') }}</div>
                    @endif
                </div>
                <div class="form-group col-md-3">
                    <label class="required" for="start_time">{{ trans('cruds.schedule.fields.start_time') }}</label>
                    <input type="text" id="start_time" name="start_time" class="form-control timepicker {{ $errors->has('start_time') ? 'is-invalid' : '' }}" value="{{ old('start_time', '') }}" required>
                    @if($errors->has('start_time'))
                        <div class="invalid-feedback">{{ $errors->first('start_time') }}</div>
                    @endif
                </div>
                <div class="form-group col-md-3">
                    <label for="end_time">{{ trans('cruds.schedule.fields.end_time') }}</label>
                    <input type="text" id="end_time" name="end_time" class="form-control timepicker {{ $errors->has('end_time') ? 'is-invalid' : '' }}" value="{{ old('end_time', '') }}">
                    @if($errors->has('end_time'))
                        <div class="invalid-feedback">{{ $errors->first('end_time') }}</div>
                    @endif
                </div>
            </div>
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.schedule.fields.title') }}</label>
                <input type="text" id="title" name="title" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label for="subtitle">{{ trans('cruds.schedule.fields.subtitle') }}</label>
                <input type="text" id="subtitle" name="subtitle" class="form-control {{ $errors->has('subtitle') ? 'is-invalid' : '' }}" value="{{ old('subtitle', '') }}">
            </div>
            <div class="form-group">
                <label for="location">{{ trans('cruds.schedule.fields.location') }}</label>
                <input type="text" id="location" name="location" class="form-control {{ $errors->has('location') ? 'is-invalid' : '' }}" value="{{ old('location', '') }}" placeholder="e.g. Main Hall, Room 2B">
            </div>
            <div class="form-group">
                <label for="speaker_id">{{ trans('cruds.schedule.fields.speaker') }}</label>
                <select name="speaker_id" id="speaker_id" class="form-control select2">
                    @foreach($speakers as $id => $speaker)
                        <option value="{{ $id }}" {{ old('speaker_id') == $id ? 'selected' : '' }}>{{ $speaker }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="materials">Training Materials</label>
                <select name="materials[]" id="materials" class="form-control select2" multiple>
                    @foreach($materials as $material)
                        @php
                            $icon = $material->type === 'video' ? '🎥' : ($material->type === 'presentation' ? '📊' : '📄');
                        @endphp
                        <option value="{{ $material->id }}"
                            {{ in_array($material->id, old('materials', [])) ? 'selected' : '' }}>
                            {{ $icon }} {{ $material->title }}{{ $material->category ? ' — ' . $material->category : '' }}
                        </option>
                    @endforeach
                </select>
                <small class="text-muted">Select one or more materials to attach to this session. Hold Ctrl / Cmd to select multiple.</small>
            </div>

            <div class="form-group">
                <button class="btn btn-cosecsa" type="submit">
                    <i class="fas fa-save mr-1"></i> {{ trans('global.save') }}
                </button>
                <a class="btn btn-secondary ml-2" href="{{ route('admin.schedules.index', ['course' => $courseType]) }}">{{ trans('global.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
$(function() {
    $('.date').datetimepicker({ format: 'YYYY-MM-DD', locale: '{{ app()->getLocale() }}' });
    $('.timepicker').datetimepicker({ format: 'HH:mm:ss', locale: '{{ app()->getLocale() }}' });
});
</script>
@endsection