@extends('layouts.facilitator')
@section('page-title', 'Manage Materials')

@section('content')

@if(session('message'))
<div class="alert alert-success alert-dismissible fade show py-2" role="alert">
    <i class="fas fa-check-circle mr-2"></i> {{ session('message') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif
@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
</div>
@endif

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
        <i class="fas fa-book-open mr-2" style="color:#C9A84C;"></i> Training Materials
    </h5>
    <a href="{{ route('facilitator.material-manager.create') }}"
       class="btn btn-sm" style="background:#C9A84C; color:#fff; font-weight:700; font-size:13px; border-radius:5px;">
        <i class="fas fa-plus mr-1"></i> Add Material
    </a>
</div>

@php
    $grouped = $materials->groupBy('type');
    $typeLabels = [
        'presentation' => 'Presentations',
        'document'     => 'Documents',
        'spreadsheet'  => 'Spreadsheets',
        'video'        => 'Videos',
        'audio'        => 'Audio',
        'image'        => 'Images',
        'youtube'      => 'External Links',
    ];
    $typeIcons  = [
        'presentation' => 'fa-file-powerpoint',
        'document'     => 'fa-file-pdf',
        'spreadsheet'  => 'fa-file-excel',
        'video'        => 'fa-video',
        'audio'        => 'fa-headphones',
        'image'        => 'fa-image',
        'youtube'      => 'fa-link',
    ];
    $typeColors = [
        'presentation' => '#C9A84C',
        'document'     => '#e53e3e',
        'spreadsheet'  => '#1d6f42',
        'video'        => '#0d6efd',
        'audio'        => '#7952b3',
        'image'        => '#0dcaf0',
        'youtube'      => '#888',
    ];
@endphp

@foreach(array_keys($typeLabels) as $type)
@if($grouped->has($type))
<div class="card shadow-sm mb-4" style="border-radius:10px; overflow:hidden;">
    <div class="card-header d-flex align-items-center" style="background:#f8f9fa; border-bottom:2px solid {{ $typeColors[$type] }}; padding:12px 20px;">
        <i class="fas {{ $typeIcons[$type] }} mr-2" style="color:{{ $typeColors[$type] }};"></i>
        <strong style="font-size:14px; color:#2d3748;">{{ $typeLabels[$type] }}</strong>
        <span class="ml-auto badge" style="background:{{ $typeColors[$type] }}22; color:{{ $typeColors[$type] }}; border:1px solid {{ $typeColors[$type] }}55; font-size:11px; padding:3px 10px;">
            {{ $grouped[$type]->count() }}
        </span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead style="background:#fafafa;">
                <tr>
                    <th class="th-sm">Title</th>
                    <th class="th-sm th-sm-hide">Category</th>
                    <th class="th-sm th-sm-hide">Facilitator</th>
                    <th class="th-sm th-sm-hide">File</th>
                    <th class="th-sm" style="text-align:right; padding-right:16px;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($grouped[$type] as $mat)
                @php $canEdit = $editableIds->contains($mat->id); @endphp
                <tr style="{{ $canEdit ? '' : 'opacity:.72;' }}">
                    <td style="vertical-align:middle; padding:10px 16px;">
                        <div style="font-weight:600; font-size:13.5px; color:#2d3748; display:flex; align-items:center; gap:6px;">
                            {{ $mat->title }}
                            @if(!$canEdit)
                                <span title="You are not the assigned facilitator for this material's sessions"
                                      style="font-size:10px; color:#aaa; font-weight:400; display:inline-flex; align-items:center; gap:3px;">
                                    <i class="fas fa-lock" style="font-size:9px;"></i> view only
                                </span>
                            @endif
                        </div>
                        @if($mat->description)
                            <div style="font-size:11.5px; color:#888; margin-top:1px;">{{ Str::limit($mat->description, 60) }}</div>
                        @endif
                    </td>
                    <td class="td-hide" style="vertical-align:middle; font-size:13px; color:#555;">
                        @if($mat->category)
                            <span style="background:#fef3cd; color:#856404; border-radius:4px; padding:2px 8px; font-size:11px; font-weight:600;">{{ $mat->category }}</span>
                        @else
                            <span style="color:#ccc;">—</span>
                        @endif
                    </td>
                    <td class="td-hide" style="vertical-align:middle; font-size:13px; color:#555;">{{ $mat->facilitator?->name ?? '—' }}</td>
                    <td class="td-hide" style="vertical-align:middle;">
                        @if($mat->external_url || $mat->file)
                            <a href="{{ route('material.view', $mat->id) }}" target="_blank"
                               style="font-size:12px; color:#C9A84C; text-decoration:none;">
                                <i class="fas fa-eye mr-1"></i>Preview
                            </a>
                        @else
                            <span style="font-size:12px; color:#ccc;">No file</span>
                        @endif
                    </td>
                    <td style="vertical-align:middle; text-align:right; padding-right:16px;">
                        @if($canEdit)
                            <button type="button" class="btn btn-sm btn-replace-toggle"
                                    data-id="{{ $mat->id }}"
                                    style="background:#fff8e1; color:#856404; border:1px solid #ffe082; font-size:12px; margin-right:4px;"
                                    title="Replace file">
                                <i class="fas fa-retweet"></i>
                            </button>
                            <a href="{{ route('facilitator.material-manager.edit', $mat->id) }}"
                               class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:12px; margin-right:4px;">
                                <i class="fas fa-edit"></i>
                            </a>
                            @if(auth()->user()->roles->pluck('title')->contains('Lead Facilitator'))
                            <form action="{{ route('facilitator.material-manager.destroy', $mat->id) }}" method="POST" style="display:inline;"
                                  onsubmit="return confirm('Delete \'{{ addslashes($mat->title) }}\'?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#fff5f5; color:#e53e3e; border:1px solid #fed7d7; font-size:12px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        @else
                            <span title="Not assigned to your sessions" style="font-size:11px; color:#ccc; padding:0 8px;">
                                <i class="fas fa-lock"></i>
                            </span>
                        @endif
                    </td>
                </tr>
                {{-- Replace-file panel row (only renders for editable materials) --}}
                @if($canEdit)
                <tr id="replace-row-{{ $mat->id }}" style="display:none; background:#fffdf5;">
                    <td colspan="5" style="padding:14px 20px; border-top:2px dashed #ffe082;">
                        <div style="font-size:12px; font-weight:700; color:#856404; margin-bottom:10px; text-transform:uppercase; letter-spacing:.4px;">
                            <i class="fas fa-retweet mr-1"></i> Replace File — {{ $mat->title }}
                        </div>
                        <div class="needsclick dropzone fac-replace-dz"
                             id="fac-dz-{{ $mat->id }}"
                             style="min-height:80px; border:2px dashed #ccc; border-radius:6px; background:#fff; cursor:pointer; margin-bottom:8px;">
                        </div>
                        {{-- Linear upload progress --}}
                        <div id="fac-progress-wrap-{{ $mat->id }}" style="display:none; margin-bottom:8px;">
                            <div style="display:flex; justify-content:space-between; font-size:10px; color:#888; margin-bottom:3px;">
                                <span>Uploading…</span><span id="fac-pct-{{ $mat->id }}">0%</span>
                            </div>
                            <div style="height:5px; background:#e9ecef; border-radius:3px; overflow:hidden;">
                                <div id="fac-bar-{{ $mat->id }}" style="height:100%; width:0%; background:#C9A84C; border-radius:3px; transition:width .15s ease;"></div>
                            </div>
                        </div>
                        <div id="fac-status-{{ $mat->id }}" style="font-size:12px; color:#888; margin-bottom:8px;"></div>
                        <form id="fac-replace-form-{{ $mat->id }}"
                              action="{{ route('facilitator.material-manager.replaceFile', $mat->id) }}"
                              method="POST" style="display:none;">
                            @csrf
                            <input type="hidden" name="file" id="fac-file-{{ $mat->id }}" value="">
                        </form>
                        <div style="display:flex; gap:8px;">
                            <button type="button" id="fac-save-{{ $mat->id }}"
                                    onclick="facSubmitReplace({{ $mat->id }})"
                                    class="btn btn-sm" style="background:#C9A84C; color:#fff; display:none;">
                                <i class="fas fa-save mr-1"></i> Save New File
                            </button>
                            <button type="button" onclick="facCloseReplace({{ $mat->id }})"
                                    class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6;">
                                Cancel
                            </button>
                        </div>
                    </td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endforeach
@endsection

@section('styles')
<style>
.th-sm { font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px; padding:9px 16px; }
@media (max-width: 576px) {
    .th-sm-hide, .td-hide { display: none; }
    .table td, .table th { padding: 8px 10px; }
}
.fac-replace-dz .dz-message { padding: 16px; font-size:12px; color:#aaa; text-align:center; }
.fac-replace-dz .dz-preview .dz-image { width:52px; height:52px; border-radius:6px; }
.fac-replace-dz .dz-preview .dz-image img { width:100%; height:100%; object-fit:cover; max-width:none; }
.fac-replace-dz .dz-preview .dz-details { opacity:1; background:rgba(255,255,255,.9); }
.fac-replace-dz .dz-preview .dz-filename span,
.fac-replace-dz .dz-preview .dz-size span { background:transparent; font-size:10px; }
.fac-replace-dz .dz-preview.dz-success .dz-success-mark { opacity:1; }
</style>
@endsection

@section('scripts')
@parent
<script>
Dropzone.autoDiscover = false;

var facDropzones = {};

function facOpenReplace(id) {
    $('#replace-row-' + id).show();
    // Init Dropzone lazily on first open
    if (!facDropzones[id]) {
        facDropzones[id] = new Dropzone('#fac-dz-' + id, {
            url: '{{ route('facilitator.material-manager.storeMedia') }}',
            maxFilesize: 100,
            maxFiles: 1,
            addRemoveLinks: true,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            previewTemplate: window.DZ_PREVIEW_TEMPLATE,
            dictDefaultMessage: window.DZ_DEFAULT_MSG,
            sending: function() {
                $('#fac-progress-wrap-' + id).show();
                $('#fac-bar-' + id).css('width','0%');
                $('#fac-pct-' + id).text('0%');
            },
            uploadprogress: function(file, progress) {
                var pct = Math.round(progress) + '%';
                $('#fac-bar-' + id).css('width', pct);
                $('#fac-pct-' + id).text(pct);
            },
            success: function(file, response) {
                $('#fac-bar-' + id).css('width','100%');
                $('#fac-pct-' + id).text('100%');
                setTimeout(function(){ $('#fac-progress-wrap-' + id).hide(); }, 600);
                $('#fac-file-' + id).val(response.name);
                $('#fac-save-' + id).show();
                $('#fac-status-' + id).text('✔ Ready: ' + response.original_name).css('color','#2d8a4e');
            },
            removedfile: function(file) {
                file.previewElement.remove();
                $('#fac-file-' + id).val('');
                $('#fac-save-' + id).hide();
                $('#fac-status-' + id).text('');
                $('#fac-progress-wrap-' + id).hide();
            },
            error: function(file, msg, xhr) {
                var err = typeof msg === 'string' ? msg : (msg.error || msg.message || 'Upload failed');
                if (xhr && xhr.status === 422) {
                    try { var p = JSON.parse(xhr.responseText); err = p.errors ? Object.values(p.errors).flat().join(' ') : (p.message || err); } catch(e){}
                }
                $('#fac-status-' + id).text('Error: ' + err).css('color','#c0392b');
            },
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 1) this.removeFile(this.files[0]);
                    dzSetFileIcon(file);
                });
            }
        });
    }
}

function facCloseReplace(id) {
    $('#replace-row-' + id).hide();
    $('#fac-status-' + id).text('');
    $('#fac-save-' + id).hide();
    $('#fac-file-' + id).val('');
    $('#fac-progress-wrap-' + id).hide();
    $('#fac-bar-' + id).css('width','0%');
    if (facDropzones[id]) {
        facDropzones[id].removeAllFiles(true);
    }
}

function facSubmitReplace(id) {
    if (!$('#fac-file-' + id).val()) {
        $('#fac-status-' + id).text('Please upload a file first.').css('color','#c0392b');
        return;
    }
    $('#fac-status-' + id).text('Saving…').css('color','#888');
    $('#fac-replace-form-' + id).submit();
}

// Toggle on button click
$(document).on('click', '.btn-replace-toggle', function() {
    var id = $(this).data('id');
    var $row = $('#replace-row-' + id);
    if ($row.is(':visible')) {
        facCloseReplace(id);
    } else {
        facOpenReplace(id);
    }
});
</script>
@endsection
