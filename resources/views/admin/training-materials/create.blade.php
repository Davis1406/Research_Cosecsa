@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header cosecsa-card-header">
        <i class="fas fa-upload mr-2"></i> {{ trans('global.add') }} {{ trans('cruds.trainingMaterial.title_singular') }}
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.training-materials.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.trainingMaterial.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <div class="invalid-feedback">{{ $errors->first('title') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label for="category">{{ trans('cruds.trainingMaterial.fields.category') }}</label>
                <input class="form-control {{ $errors->has('category') ? 'is-invalid' : '' }}" type="text" name="category" id="category" value="{{ old('category', '') }}" placeholder="e.g. Surgery, Anatomy, Clinical Skills, Research Methods">
                @if($errors->has('category'))
                    <div class="invalid-feedback">{{ $errors->first('category') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label class="required" for="type">{{ trans('cruds.trainingMaterial.fields.type') }}</label>
                <select class="form-control {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type" required>
                    <option value="">{{ trans('global.pleaseSelect') }}</option>
                    <option value="document" {{ old('type') == 'document' ? 'selected' : '' }}>Document / PDF</option>
                    <option value="presentation" {{ old('type') == 'presentation' ? 'selected' : '' }}>Presentation (PPT/Slides)</option>
                    <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Video</option>
                    <option value="image" {{ old('type') == 'image' ? 'selected' : '' }}>Image / Diagram</option>
                </select>
                @if($errors->has('type'))
                    <div class="invalid-feedback">{{ $errors->first('type') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.trainingMaterial.fields.description') }}</label>
                <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" name="description" id="description" rows="3">{{ old('description') }}</textarea>
                @if($errors->has('description'))
                    <div class="invalid-feedback">{{ $errors->first('description') }}</div>
                @endif
            </div>
            <div class="form-group">
                <label for="speaker_id">{{ trans('cruds.trainingMaterial.fields.speaker') }}</label>
                <select class="form-control select2 {{ $errors->has('speaker_id') ? 'is-invalid' : '' }}" name="speaker_id" id="speaker_id">
                    @foreach($facilitators as $id => $name)
                        <option value="{{ $id }}" {{ old('speaker_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                    @endforeach
                </select>
                @if($errors->has('speaker_id'))
                    <div class="invalid-feedback">{{ $errors->first('speaker_id') }}</div>
                @endif
            </div>
            <div class="form-group" id="file-upload-group">
                <label for="file">{{ trans('cruds.trainingMaterial.fields.file') }}</label>
                <div class="needsclick dropzone {{ $errors->has('file') ? 'is-invalid' : '' }}" id="file-dropzone">
                </div>
                @if($errors->has('file'))
                    <div class="invalid-feedback">{{ $errors->first('file') }}</div>
                @endif
            </div>
            <div class="form-group" id="external-url-group">
                <label for="external_url">{{ trans('cruds.trainingMaterial.fields.external_url') }}</label>
                <input class="form-control {{ $errors->has('external_url') ? 'is-invalid' : '' }}" type="url" name="external_url" id="external_url" value="{{ old('external_url', '') }}" placeholder="https://www.youtube.com/watch?v=...">
                <small class="form-text text-muted">Use this for video links (YouTube, Vimeo, etc.)</small>
                @if($errors->has('external_url'))
                    <div class="invalid-feedback">{{ $errors->first('external_url') }}</div>
                @endif
            </div>
            <div class="form-group">
                <button class="btn btn-cosecsa" type="submit">
                    <i class="fas fa-save mr-1"></i> {{ trans('global.save') }}
                </button>
                <a class="btn btn-secondary ml-2" href="{{ route('admin.training-materials.index') }}">{{ trans('global.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
@section('scripts')
@parent
<script>
    Dropzone.autoDiscover = false;
    var uploadedFileMap = {};
    $(function() {
        var fileDropZone = new Dropzone('#file-dropzone', {
            url: '{{ route('admin.training-materials.storeMedia') }}',
            maxFilesize: 100,
            addRemoveLinks: true,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            params: { size: 100 },
            previewTemplate: window.DZ_PREVIEW_TEMPLATE,
            dictDefaultMessage: window.DZ_DEFAULT_MSG,
            success: function (file, response) {
                $('form').append('<input type="hidden" name="file" value="' + response.name + '">');
                uploadedFileMap[file.name] = response.name;
            },
            removedfile: function (file) {
                file.previewElement.remove();
                var name = typeof file.file_name !== 'undefined' ? file.file_name : uploadedFileMap[file.name];
                $('form').find('input[name="file"][value="' + name + '"]').remove();
            },
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 1) this.removeFile(this.files[0]);
                    dzSetFileIcon(file);
                });
            }
        });
    });
</script>
@endsection
