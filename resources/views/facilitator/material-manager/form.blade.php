@extends('layouts.facilitator')
@section('page-title', $material ? 'Edit Material' : 'Add Material')

@section('content')
@php $isEdit = !is_null($material); @endphp

<div class="d-flex align-items-center mb-3" style="gap:12px;">
    <a href="{{ route('facilitator.material-manager.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6;">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
    <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
        <i class="fas fa-book-open mr-2" style="color:#C9A84C;"></i>
        {{ $isEdit ? 'Edit: ' . $material->title : 'Add New Material' }}
    </h5>
</div>

<form action="{{ $isEdit ? route('facilitator.material-manager.update', $material->id) : route('facilitator.material-manager.store') }}"
      method="POST" enctype="multipart/form-data">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm mb-3" style="border-radius:10px; overflow:hidden;">
                <div class="card-header" style="background:#fff; border-left:4px solid #C9A84C; padding:14px 20px;">
                    <strong style="font-size:14px; color:#2d3748;"><i class="fas fa-info-circle mr-2" style="color:#C9A84C;"></i>Material Details</strong>
                </div>
                <div class="card-body" style="padding:24px;">
                    <div class="row">
                        <div class="col-md-8 form-group">
                            <label class="form-label-sm">Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control"
                                   value="{{ old('title', $material->title ?? '') }}" required>
                        </div>
                        <div class="col-md-4 form-group">
                            <label class="form-label-sm">Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-control" required id="type-select">
                                <option value="">-- Select --</option>
                                @foreach(['presentation'=>'Presentation (PPT)','document'=>'Document (PDF)','video'=>'Video'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('type', $material->type ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group">
                            <label class="form-label-sm">Category</label>
                            <input type="text" name="category" class="form-control"
                                   value="{{ old('category', $material->category ?? '') }}"
                                   placeholder="e.g. Statistics, Study Design…">
                        </div>
                        <div class="col-md-6 form-group">
                            <label class="form-label-sm">Facilitator / Author</label>
                            <select name="speaker_id" class="form-control">
                                <option value="">-- None --</option>
                                @foreach($speakers as $speaker)
                                    <option value="{{ $speaker->id }}"
                                        {{ old('speaker_id', $material->speaker_id ?? '') == $speaker->id ? 'selected' : '' }}>
                                        {{ $speaker->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group mb-0">
                        <label class="form-label-sm">Description</label>
                        <textarea name="description" class="form-control" rows="2"
                                  placeholder="Brief description of this material…">{{ old('description', $material->description ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-3" style="border-radius:10px; overflow:hidden;">
                <div class="card-header" style="background:#fff; border-left:4px solid #C9A84C; padding:14px 20px;">
                    <strong style="font-size:14px; color:#2d3748;"><i class="fas fa-file-upload mr-2" style="color:#C9A84C;"></i>File</strong>
                </div>
                <div class="card-body" style="padding:24px;">
                    @if($isEdit && $material->external_url)
                        <div style="background:#f0f9f0; border:1px solid #c3e6cb; border-radius:6px; padding:10px 14px; margin-bottom:14px; font-size:13px; color:#155724;">
                            <i class="fas fa-check-circle mr-1"></i>
                            Current file: <strong>{{ basename($material->external_url) }}</strong>
                            <a href="{{ route('material.view', $material->id) }}" target="_blank" style="margin-left:8px; font-size:12px;">Preview</a>
                        </div>
                    @endif
                    <div class="form-group mb-0">
                        <label class="form-label-sm">{{ $isEdit ? 'Replace file (optional)' : 'Upload file' }}</label>
                        <input type="file" name="file" class="form-control-file" id="file-input"
                               accept=".pdf,.doc,.docx,.ppt,.pptx,.mp4,.mov,.webm">
                        <small class="text-muted d-block mt-1" id="file-hint">
                            Presentations: PPTX, PPT. Documents: PDF, DOC. Videos: MP4, MOV. Max 50MB.
                        </small>
                        <div id="file-selected" style="display:none; font-size:12px; color:#28a745; margin-top:4px;">
                            <i class="fas fa-check-circle mr-1"></i><span></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm" style="border-radius:10px; overflow:hidden;">
                <div class="card-body text-center" style="padding:28px 20px;">
                    <div id="type-icon" style="font-size:48px; color:#C9A84C; margin-bottom:14px;">
                        <i class="fas fa-file"></i>
                    </div>
                    <div style="font-size:13px; color:#888; margin-bottom:18px;">
                        {{ $isEdit ? 'Editing material' : 'New material' }}
                    </div>
                    <button type="submit" class="btn btn-block" style="background:#C9A84C; color:#fff; font-weight:700; padding:10px; border-radius:6px; font-size:14px;">
                        <i class="fas fa-save mr-2"></i>{{ $isEdit ? 'Save Changes' : 'Add Material' }}
                    </button>
                    <a href="{{ route('facilitator.material-manager.index') }}" class="btn btn-block mt-2" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:13px;">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

@section('styles')
<style>.form-label-sm { font-size:11px; font-weight:700; color:#666; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px; display:block; }</style>
@endsection

@section('scripts')
<script>
var typeIcons = { presentation:'fa-file-powerpoint', document:'fa-file-pdf', video:'fa-video' };
document.getElementById('type-select').addEventListener('change', function() {
    var icon = typeIcons[this.value] || 'fa-file';
    document.getElementById('type-icon').innerHTML = '<i class="fas ' + icon + '"></i>';
});
// Trigger on load for edit mode
document.getElementById('type-select').dispatchEvent(new Event('change'));

document.getElementById('file-input').addEventListener('change', function(e) {
    var file = e.target.files[0];
    if (!file) return;
    var sel = document.getElementById('file-selected');
    sel.style.display = 'block';
    sel.querySelector('span').textContent = file.name + ' (' + (file.size/1024/1024).toFixed(1) + ' MB)';
});
</script>
@endsection
@endsection
