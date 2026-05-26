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
      method="POST" id="fac-mat-form">
    @csrf
    @if($isEdit) @method('PUT') @endif

    {{-- ── Material Details ───────────────────────────────────────────── --}}
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
                        <option value="">-- Select type --</option>
                        @php
                            $typeOptions = [
                                'presentation' => 'Presentation (PPT / PPTX)',
                                'document'     => 'Document (PDF / Word / ZIP)',
                                'spreadsheet'  => 'Spreadsheet (Excel / CSV)',
                                'video'        => 'Video (MP4 / MOV)',
                                'audio'        => 'Audio (MP3 / WAV)',
                                'image'        => 'Image / Diagram',
                                'stata'        => 'Stata File (.do / .dta)',
                                'youtube'      => 'YouTube / External Link',
                            ];
                        @endphp
                        @foreach($typeOptions as $val => $label)
                            <option value="{{ $val }}" {{ old('type', $material->type ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 form-group">
                    <label class="form-label-sm">Category</label>
                    @php $currentCat = old('category', $material->category ?? ''); @endphp
                    <select id="category-select" class="form-control mb-1" onchange="handleCategoryChange(this)">
                        <option value="">— Select a category —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ $currentCat === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                        @endforeach
                        <option value="__new__" {{ $currentCat && !$categories->contains($currentCat) ? 'selected' : '' }}>
                            ＋ Add new category…
                        </option>
                    </select>
                    <div id="new-category-wrap" style="display:{{ ($currentCat && !$categories->contains($currentCat)) ? 'flex' : 'none' }}; gap:6px; align-items:center;">
                        <input type="text" id="new-category-input" class="form-control"
                               placeholder="Type new category name"
                               value="{{ ($currentCat && !$categories->contains($currentCat)) ? $currentCat : '' }}">
                        <button type="button" onclick="cancelNewCategory()"
                                class="btn btn-sm" style="background:#f8f9fa; color:#888; border:1px solid #dee2e6; white-space:nowrap; flex-shrink:0;">✕</button>
                    </div>
                    <input type="hidden" name="category" id="category-value" value="{{ $currentCat }}">
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

    {{-- ── File / URL ──────────────────────────────────────────────────── --}}
    <div class="card shadow-sm mb-3" style="border-radius:10px; overflow:hidden;">
        <div class="card-header" style="background:#fff; border-left:4px solid #C9A84C; padding:14px 20px;">
            <strong style="font-size:14px; color:#2d3748;"><i class="fas fa-file-upload mr-2" style="color:#C9A84C;"></i>File / URL</strong>
        </div>
        <div class="card-body" style="padding:24px;">
            @if($isEdit && $material->external_url)
                <div style="background:#f0f9f0; border:1px solid #c3e6cb; border-radius:6px; padding:10px 14px; margin-bottom:14px; font-size:13px; color:#155724;">
                    <i class="fas fa-check-circle mr-1"></i>
                    @if(($material->type ?? '') === 'youtube')
                        Current link: <strong>{{ $material->external_url }}</strong>
                    @else
                        Current file: <strong>{{ basename($material->external_url) }}</strong>
                    @endif
                    <a href="{{ route('material.view', $material->id) }}" target="_blank" style="margin-left:8px; font-size:12px;">Preview</a>
                </div>
            @endif

            {{-- YouTube / external URL --}}
            <div id="youtube-url-wrap" style="display:none;">
                <div class="form-group mb-0">
                    <label class="form-label-sm">YouTube / External URL</label>
                    <input type="url" name="youtube_url" id="youtube-url-input" class="form-control"
                           placeholder="https://www.youtube.com/watch?v=..."
                           value="{{ old('youtube_url', (in_array($material->type ?? '', ['youtube'])) ? $material->external_url : '') }}">
                    <small class="text-muted d-block mt-1">Paste a YouTube, Vimeo, or any public video/resource URL.</small>
                </div>
            </div>

            {{-- Dropzone file upload --}}
            <div id="file-upload-wrap">
                <div class="form-group mb-0">
                    <label class="form-label-sm">{{ $isEdit ? 'Replace file (optional)' : 'Upload file' }}</label>
                    <div class="needsclick dropzone" id="fac-mat-dropzone"></div>
                    <div id="fac-mat-progress-wrap" style="display:none; margin-top:8px;">
                        <div style="display:flex; justify-content:space-between; font-size:10px; color:#888; margin-bottom:3px;">
                            <span>Uploading…</span><span id="fac-mat-pct">0%</span>
                        </div>
                        <div style="height:5px; background:#e9ecef; border-radius:3px; overflow:hidden;">
                            <div id="fac-mat-bar" style="height:100%; width:0%; background:#C9A84C; border-radius:3px; transition:width .15s ease;"></div>
                        </div>
                    </div>
                    <div id="fac-mat-status" style="font-size:12px; color:#888; margin-top:6px;"></div>
                    <input type="hidden" name="file" id="fac-mat-file-token" value="">
                    <small class="text-muted d-block mt-1">
                        PPT/PPTX &bull; PDF &bull; DOC/DOCX &bull; XLS/XLSX &bull; CSV &bull; MP4/MOV &bull; MP3/WAV &bull; JPG/PNG &bull; ZIP &bull; Stata .do/.dta &mdash; max 100 MB
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Action buttons — always visible at the bottom ─────────────── --}}
    <div class="card shadow-sm mb-4" style="border-radius:10px; overflow:hidden;">
        <div class="card-body" style="padding:18px 24px;">
            <div class="d-flex align-items-center" style="gap:10px;">
                <button type="submit" class="btn" style="background:#C9A84C; color:#fff; font-weight:700; font-size:14px; padding:10px 28px; border-radius:6px; min-width:160px;">
                    <i class="fas fa-save mr-2"></i>{{ $isEdit ? 'Save Changes' : 'Add Material' }}
                </button>
                <a href="{{ route('facilitator.material-manager.index') }}"
                   class="btn" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:13px; padding:10px 20px; border-radius:6px;">
                    Cancel
                </a>
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
Dropzone.autoDiscover = false;

// ── Type icon + file/URL panel toggle ──────────────────────────────────────
var typeIconsFa = {
    presentation: 'fas fa-file-powerpoint',
    document:     'fas fa-file-pdf',
    spreadsheet:  'fas fa-file-excel',
    video:        'fas fa-video',
    audio:        'fas fa-headphones',
    image:        'fas fa-image',
    youtube:      'fab fa-youtube',
};

document.getElementById('type-select').addEventListener('change', function() {
    var val      = this.value;
    var ytWrap   = document.getElementById('youtube-url-wrap');
    var fileWrap = document.getElementById('file-upload-wrap');
    if (val === 'youtube') {
        ytWrap.style.display   = 'block';
        fileWrap.style.display = 'none';
    } else {
        ytWrap.style.display   = 'none';
        fileWrap.style.display = 'block';
    }
});
document.getElementById('type-select').dispatchEvent(new Event('change'));

// ── Dropzone ───────────────────────────────────────────────────────────────
$(function() {
    new Dropzone('#fac-mat-dropzone', {
        url: '{{ route('facilitator.material-manager.storeMedia') }}',
        maxFilesize: 100,
        maxFiles: 1,
        addRemoveLinks: true,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        previewTemplate: window.DZ_PREVIEW_TEMPLATE,
        dictDefaultMessage: window.DZ_DEFAULT_MSG,
        sending: function() {
            $('#fac-mat-progress-wrap').show();
            $('#fac-mat-bar').css('width', '0%');
            $('#fac-mat-pct').text('0%');
        },
        uploadprogress: function(file, pct) {
            var p = Math.round(pct) + '%';
            $('#fac-mat-bar').css('width', p);
            $('#fac-mat-pct').text(p);
        },
        success: function(file, response) {
            $('#fac-mat-bar').css('width', '100%');
            $('#fac-mat-pct').text('100%');
            setTimeout(function(){ $('#fac-mat-progress-wrap').hide(); }, 600);
            $('#fac-mat-file-token').val(response.name);
            $('#fac-mat-status').text('✔ Ready: ' + response.original_name).css('color', '#2d8a4e');
        },
        removedfile: function(file) {
            file.previewElement.remove();
            $('#fac-mat-file-token').val('');
            $('#fac-mat-status').text('');
            $('#fac-mat-progress-wrap').hide();
            $('#fac-mat-bar').css('width', '0%');
        },
        error: function(file, msg, xhr) {
            var err = typeof msg === 'string' ? msg : (msg.error || msg.message || 'Upload failed');
            if (xhr && xhr.status === 422) {
                try { var p = JSON.parse(xhr.responseText); err = p.errors ? Object.values(p.errors).flat().join(' ') : (p.message || err); } catch(e){}
            }
            $('#fac-mat-status').text('Error: ' + err).css('color', '#c0392b');
        },
        init: function() {
            this.on('addedfile', function(file) {
                if (this.files.length > 1) this.removeFile(this.files[0]);
                dzSetFileIcon(file);
            });
        }
    });
});

// ── Category dropdown ──────────────────────────────────────────────────────
function handleCategoryChange(sel) {
    var wrap   = document.getElementById('new-category-wrap');
    var input  = document.getElementById('new-category-input');
    var hidden = document.getElementById('category-value');
    if (sel.value === '__new__') {
        wrap.style.display = 'flex';
        input.focus();
        hidden.value = '';
    } else {
        wrap.style.display = 'none';
        input.value  = '';
        hidden.value = sel.value;
    }
}
function cancelNewCategory() {
    document.getElementById('category-select').value = '';
    document.getElementById('new-category-wrap').style.display = 'none';
    document.getElementById('new-category-input').value = '';
    document.getElementById('category-value').value = '';
}
document.querySelector('form').addEventListener('submit', function() {
    var wrap = document.getElementById('new-category-wrap');
    if (wrap.style.display !== 'none') {
        document.getElementById('category-value').value =
            document.getElementById('new-category-input').value.trim();
    }
});
</script>
@endsection
@endsection
