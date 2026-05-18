@extends('layouts.facilitator')
@section('page-title', 'My Profile')
@section('content')

<form action="{{ route('facilitator.profile.update') }}" method="POST" enctype="multipart/form-data">
@csrf @method('PUT')
<div class="row">

  {{-- LEFT: Avatar + summary --}}
  <div class="col-lg-4 mb-4">
    <div class="card shadow-sm" style="border-radius:10px; overflow:hidden;">
      <div class="card-body text-center" style="padding:32px 20px;">
        {{-- Avatar — always an <img> so JS live-preview always works --}}
        <div style="position:relative; display:inline-block; margin-bottom:18px;">
          <img id="avatar-preview-img"
               src="{{ ($speaker && $speaker->photo) ? $speaker->photo->getUrl() : '' }}"
               alt="Avatar"
               class="avatar-preview"
               style="width:110px; height:110px; border-radius:50%; object-fit:cover; border:3px solid #C9A84C; background:#f0f0f0; {{ ($speaker && $speaker->photo) ? '' : 'display:none;' }}">
          {{-- Initials fallback shown when no photo --}}
          <div id="avatar-initials"
               style="width:110px; height:110px; border-radius:50%; background:#C9A84C; color:#fff; font-size:40px; font-weight:700; display:{{ ($speaker && $speaker->photo) ? 'none' : 'flex' }}; align-items:center; justify-content:center; border:3px solid #e9ecef;">
            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
          </div>
          {{-- Camera icon overlay --}}
          <label for="avatar" style="position:absolute; bottom:4px; right:4px; width:28px; height:28px; border-radius:50%; background:#fff; border:2px solid #C9A84C; cursor:pointer; display:flex; align-items:center; justify-content:center; margin:0; box-shadow:0 1px 4px rgba(0,0,0,.15);">
            <i class="fas fa-camera" style="font-size:11px; color:#C9A84C;"></i>
          </label>
        </div>
        <div style="font-weight:700; font-size:16px; color:#2d3748; margin-bottom:2px;">{{ auth()->user()->name }}</div>
        <div style="font-size:13px; color:#888; margin-bottom:10px;">{{ auth()->user()->email }}</div>
        <span class="badge" style="background:#C9A84C; color:#fff; font-size:12px; padding:5px 16px; border-radius:20px;">
          {{ auth()->user()->roles->first()->title ?? 'Facilitator' }}
        </span>

        {{-- Avatar upload (hidden input triggered by camera icon above) --}}
        <div class="mt-4 text-left">
          <input type="file" class="d-none" id="avatar" name="avatar" accept="image/*">
          <div id="avatar-filename" style="font-size:12px; color:#888; margin-top:6px; display:none;">
            <i class="fas fa-check-circle mr-1" style="color:#28a745;"></i><span></span>
          </div>
          <small class="text-muted d-block mt-2">Click the camera icon to change photo.<br>JPG, PNG or GIF — max 2MB</small>
        </div>
      </div>
    </div>
  </div>

  {{-- RIGHT: Edit form --}}
  <div class="col-lg-8 mb-4">
    <div class="card shadow-sm" style="border-radius:10px; overflow:hidden;">
      <div class="card-header" style="background:#fff; border-left:4px solid #C9A84C; padding:14px 20px;">
        <strong style="color:#2d3748; font-size:14px;"><i class="fas fa-user-edit mr-2" style="color:#C9A84C;"></i>Basic Information</strong>
      </div>
      <div class="card-body" style="padding:24px;">
        <div class="row">
          <div class="col-md-6 form-group">
            <label class="form-label-sm">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control" value="{{ old('name', auth()->user()->name) }}" required>
          </div>
          <div class="col-md-6 form-group">
            <label class="form-label-sm">Email Address <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control" value="{{ old('email', auth()->user()->email) }}" required>
          </div>
        </div>
        <div class="form-group">
          <label class="form-label-sm">Bio / Short Description</label>
          <textarea name="description" class="form-control" rows="3" placeholder="Brief professional bio...">{{ old('description', $speaker->description ?? '') }}</textarea>
        </div>
        <div class="row">
          <div class="col-md-4 form-group">
            <label class="form-label-sm"><i class="fab fa-twitter mr-1" style="color:#1da1f2;"></i>Twitter URL</label>
            <input type="text" name="twitter" class="form-control" value="{{ old('twitter', $speaker->twitter ?? '') }}" placeholder="https://twitter.com/...">
          </div>
          <div class="col-md-4 form-group">
            <label class="form-label-sm"><i class="fab fa-linkedin mr-1" style="color:#0077b5;"></i>LinkedIn URL</label>
            <input type="text" name="linkedin" class="form-control" value="{{ old('linkedin', $speaker->linkedin ?? '') }}" placeholder="https://linkedin.com/in/...">
          </div>
          <div class="col-md-4 form-group">
            <label class="form-label-sm"><i class="fab fa-facebook mr-1" style="color:#1877f2;"></i>Facebook URL</label>
            <input type="text" name="facebook" class="form-control" value="{{ old('facebook', $speaker->facebook ?? '') }}" placeholder="https://facebook.com/...">
          </div>
        </div>
      </div>
    </div>

    <div class="card shadow-sm mt-3" style="border-radius:10px; overflow:hidden;">
      <div class="card-header" style="background:#fff; border-left:4px solid #e53e3e; padding:14px 20px;">
        <strong style="color:#2d3748; font-size:14px;"><i class="fas fa-lock mr-2" style="color:#e53e3e;"></i>Change Password</strong>
      </div>
      <div class="card-body" style="padding:24px;">
        <div class="row">
          <div class="col-md-6 form-group">
            <label class="form-label-sm">New Password</label>
            <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
          </div>
          <div class="col-md-6 form-group">
            <label class="form-label-sm">Confirm New Password</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Repeat new password">
          </div>
        </div>
      </div>
    </div>

    <div class="text-right mt-3">
      <button type="submit" class="btn" style="background:#C9A84C; color:#fff; font-weight:700; padding:10px 28px; border-radius:6px; font-size:14px;">
        <i class="fas fa-save mr-2"></i>Save Changes
      </button>
    </div>
  </div>

</div>
</form>

@section('styles')
<style>
.form-label-sm { font-size:11px; font-weight:700; color:#666; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px; display:block; }
</style>
@endsection

@section('scripts')
<script>
document.getElementById('avatar').addEventListener('change', function(e) {
    var file = e.target.files[0];
    if (!file) return;

    // Show filename confirmation
    var fnDiv = document.getElementById('avatar-filename');
    fnDiv.style.display = 'block';
    fnDiv.querySelector('span').textContent = file.name;

    // Live preview
    var reader = new FileReader();
    reader.onload = function(evt) {
        var img = document.getElementById('avatar-preview-img');
        var initials = document.getElementById('avatar-initials');
        img.src = evt.target.result;
        img.style.display = 'block';
        if (initials) initials.style.display = 'none';
    };
    reader.readAsDataURL(file);
});
</script>
@endsection

@endsection
