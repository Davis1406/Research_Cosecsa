@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.speaker.title_singular') }}
    </div>

    <div class="card-body">
        <form action="{{ route("admin.speakers.update", [$speaker->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
                <label for="name">{{ trans('cruds.speaker.fields.name') }}*</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', isset($speaker) ? $speaker->name : '') }}" required>
                @if($errors->has('name'))
                    <p class="help-block">
                        {{ $errors->first('name') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.speaker.fields.name_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                <label for="description">{{ trans('cruds.speaker.fields.description') }}</label>
                <textarea id="description" name="description" class="form-control ">{{ old('description', isset($speaker) ? $speaker->description : '') }}</textarea>
                @if($errors->has('description'))
                    <p class="help-block">
                        {{ $errors->first('description') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.speaker.fields.description_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('full_description') ? 'has-error' : '' }}">
                <label for="full_description">{{ trans('cruds.speaker.fields.full_description') }}</label>
                <textarea id="full_description" name="full_description" class="form-control ">{{ old('full_description', isset($speaker) ? $speaker->full_description : '') }}</textarea>
                @if($errors->has('full_description'))
                    <p class="help-block">
                        {{ $errors->first('full_description') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.speaker.fields.full_description_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('photo') ? 'has-error' : '' }}">
                <label for="photo">{{ trans('cruds.speaker.fields.photo') }}</label>
                <div class="needsclick dropzone" id="photo-dropzone">

                </div>
                @if($errors->has('photo'))
                    <p class="help-block">
                        {{ $errors->first('photo') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.speaker.fields.photo_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('twitter') ? 'has-error' : '' }}">
                <label for="twitter">{{ trans('cruds.speaker.fields.twitter') }}</label>
                <input type="text" id="twitter" name="twitter" class="form-control" value="{{ old('twitter', isset($speaker) ? $speaker->twitter : '') }}">
                @if($errors->has('twitter'))
                    <p class="help-block">
                        {{ $errors->first('twitter') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.speaker.fields.twitter_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('facebook') ? 'has-error' : '' }}">
                <label for="facebook">{{ trans('cruds.speaker.fields.facebook') }}</label>
                <input type="text" id="facebook" name="facebook" class="form-control" value="{{ old('facebook', isset($speaker) ? $speaker->facebook : '') }}">
                @if($errors->has('facebook'))
                    <p class="help-block">
                        {{ $errors->first('facebook') }}
                    </p>
                @endif
                <p class="helper-block">
                    {{ trans('cruds.speaker.fields.facebook_helper') }}
                </p>
            </div>
            <div class="form-group {{ $errors->has('linkedin') ? 'has-error' : '' }}">
                <label for="linkedin">{{ trans('cruds.speaker.fields.linkedin') }}</label>
                <input type="text" id="linkedin" name="linkedin" class="form-control" value="{{ old('linkedin', isset($speaker) ? $speaker->linkedin : '') }}">
                @if($errors->has('linkedin'))
                    <p class="help-block">{{ $errors->first('linkedin') }}</p>
                @endif
            </div>
            {{-- Academic Profile Links --}}
            <hr>
            <h6 style="font-weight:700; color:#2d3748; margin-bottom:14px;">
                <i class="fas fa-graduation-cap mr-2" style="color:#C9A84C;"></i> Academic Profiles <small class="text-muted font-weight-normal">(optional)</small>
            </h6>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="researchgate"><i class="fas fa-flask mr-1" style="color:#00d0af;"></i> ResearchGate URL</label>
                        <input type="url" id="researchgate" name="researchgate" class="form-control" value="{{ old('researchgate', $speaker->researchgate ?? '') }}" placeholder="https://www.researchgate.net/profile/...">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="orcid"><i class="fas fa-id-badge mr-1" style="color:#a6ce39;"></i> ORCID URL</label>
                        <input type="url" id="orcid" name="orcid" class="form-control" value="{{ old('orcid', $speaker->orcid ?? '') }}" placeholder="https://orcid.org/0000-0000-0000-0000">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="web_of_science"><i class="fas fa-atom mr-1" style="color:#1d4e89;"></i> Web of Science URL</label>
                        <input type="url" id="web_of_science" name="web_of_science" class="form-control" value="{{ old('web_of_science', $speaker->web_of_science ?? '') }}" placeholder="https://www.webofscience.com/...">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="google_scholar"><i class="fas fa-book-reader mr-1" style="color:#4285f4;"></i> Google Scholar URL</label>
                        <input type="url" id="google_scholar" name="google_scholar" class="form-control" value="{{ old('google_scholar', $speaker->google_scholar ?? '') }}" placeholder="https://scholar.google.com/citations?user=...">
                    </div>
                </div>
            </div>
            {{-- Portal Access --}}
            <hr>
            <h6 style="font-weight:700; color:#2d3748; margin-bottom:4px;">
                <i class="fas fa-key mr-2" style="color:#C9A84C;"></i> Portal Access
            </h6>
            @if($speaker->user)
                <p class="text-muted mb-3" style="font-size:13px;">
                    <i class="fas fa-check-circle text-success mr-1"></i>
                    Portal account exists: <strong>{{ $speaker->user->email }}</strong>
                    &mdash; current role: <strong>{{ $speaker->user->roles->first()?->title ?? '—' }}</strong>
                </p>
            @else
                <p class="text-muted mb-3" style="font-size:13px;">No portal account yet. Fill in email + password + role below to create one.</p>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="portal_email">Email Address</label>
                        <input type="email" id="portal_email" name="portal_email" class="form-control"
                               value="{{ old('portal_email', $speaker->user?->email) }}"
                               placeholder="facilitator@cosecsa.org">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="portal_role_id">Role</label>
                        <select id="portal_role_id" name="portal_role_id" class="form-control">
                            <option value="">— Select role —</option>
                            @foreach($roles as $role)
                                @php $currentRoleId = $speaker->user?->roles->first()?->id; @endphp
                                <option value="{{ $role->id }}" {{ (old('portal_role_id', $currentRoleId) == $role->id) ? 'selected' : '' }}>{{ $role->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="portal_password">{{ $speaker->user ? 'New Password' : 'Password' }}</label>
                        <input type="password" id="portal_password" name="portal_password" class="form-control"
                               placeholder="{{ $speaker->user ? 'Leave blank to keep current' : 'Required to create account' }}">
                    </div>
                </div>
            </div>

            <div>
                <input class="btn btn-cosecsa" type="submit" value="{{ trans('global.save') }}">
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