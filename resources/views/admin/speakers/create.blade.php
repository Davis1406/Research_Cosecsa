@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header cosecsa-card-header">
        <i class="fas fa-chalkboard-teacher mr-2"></i> {{ trans('global.create') }} {{ trans('cruds.speaker.title_singular') }}
    </div>
    <div class="card-body">
        <form action="{{ route('admin.speakers.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.speaker.fields.name') }}</label>
                <input type="text" id="name" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))<div class="invalid-feedback">{{ $errors->first('name') }}</div>@endif
            </div>
            <div class="form-group">
                <label for="twitter">{{ trans('cruds.speaker.fields.twitter') }}</label>
                <input type="text" id="twitter" name="twitter" class="form-control {{ $errors->has('twitter') ? 'is-invalid' : '' }}" value="{{ old('twitter', '') }}" placeholder="e.g. Cardiothoracic Surgery, Paediatric Surgery">
                @if($errors->has('twitter'))<div class="invalid-feedback">{{ $errors->first('twitter') }}</div>@endif
            </div>
            <div class="form-group">
                <label for="facebook">{{ trans('cruds.speaker.fields.facebook') }}</label>
                <input type="text" id="facebook" name="facebook" class="form-control {{ $errors->has('facebook') ? 'is-invalid' : '' }}" value="{{ old('facebook', '') }}" placeholder="e.g. Muhimbili National Hospital">
                @if($errors->has('facebook'))<div class="invalid-feedback">{{ $errors->first('facebook') }}</div>@endif
            </div>
            <div class="form-group">
                <label for="description">{{ trans('cruds.speaker.fields.description') }}</label>
                <textarea id="description" name="description" class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" rows="3">{{ old('description', '') }}</textarea>
                @if($errors->has('description'))<div class="invalid-feedback">{{ $errors->first('description') }}</div>@endif
            </div>
            <div class="form-group">
                <label for="full_description">{{ trans('cruds.speaker.fields.full_description') }}</label>
                <textarea id="full_description" name="full_description" class="form-control {{ $errors->has('full_description') ? 'is-invalid' : '' }}" rows="5">{{ old('full_description', '') }}</textarea>
                @if($errors->has('full_description'))<div class="invalid-feedback">{{ $errors->first('full_description') }}</div>@endif
            </div>
            <div class="form-group">
                <label for="photo">{{ trans('cruds.speaker.fields.photo') }}</label>
                <div class="needsclick dropzone {{ $errors->has('photo') ? 'is-invalid' : '' }}" id="photo-dropzone"></div>
                @if($errors->has('photo'))<div class="invalid-feedback">{{ $errors->first('photo') }}</div>@endif
            </div>
            <div class="form-group">
                <label for="linkedin">{{ trans('cruds.speaker.fields.linkedin') }}</label>
                <input type="text" id="linkedin" name="linkedin" class="form-control {{ $errors->has('linkedin') ? 'is-invalid' : '' }}" value="{{ old('linkedin', '') }}" placeholder="LinkedIn profile URL">
                @if($errors->has('linkedin'))<div class="invalid-feedback">{{ $errors->first('linkedin') }}</div>@endif
            </div>
            {{-- Portal Access --}}
            <hr>
            <h6 style="font-weight:700; color:#2d3748; margin-bottom:16px;">
                <i class="fas fa-key mr-2" style="color:#C9A84C;"></i> Portal Access <small class="text-muted font-weight-normal">(optional — creates a login account)</small>
            </h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="portal_email">Email Address</label>
                        <input type="email" id="portal_email" name="portal_email" class="form-control" value="{{ old('portal_email') }}" placeholder="facilitator@cosecsa.org">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="portal_role_id">Role</label>
                        <select id="portal_role_id" name="portal_role_id" class="form-control">
                            <option value="">— Select role —</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}" {{ old('portal_role_id') == $role->id ? 'selected' : '' }}>{{ $role->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="portal_password">Password</label>
                        <input type="password" id="portal_password" name="portal_password" class="form-control" placeholder="Min. 8 characters">
                        <small class="text-muted">Required to create a new portal account.</small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <button class="btn btn-cosecsa" type="submit">
                    <i class="fas fa-save mr-1"></i> {{ trans('global.save') }}
                </button>
                <a class="btn btn-secondary ml-2" href="{{ route('admin.speakers.index') }}">{{ trans('global.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    Dropzone.options.photoDropzone = {
    url: '{{ route('admin.speakers.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
      width: 4096,
      height: 4096
    },
    success: function (file, response) {
      $('form').find('input[name="photo"]').remove()
      $('form').append('<input type="hidden" name="photo" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="photo"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
@if(isset($speaker) && $speaker->photo)
      var file = {!! json_encode($speaker->photo) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, file.url)
      file.previewElement.classList.add('dz-complete')
      $('form').append('<input type="hidden" name="photo" value="' + file.file_name + '">')
      this.options.maxFiles = this.options.maxFiles - 1
@endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
</script>
@stop