@extends('layouts.trainee')

@section('page-title', 'My Documents & Presentations')

@section('content')
<div class="row">
    {{-- Upload Panel --}}
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm" style="border-radius:8px; overflow:hidden;">
            <div class="card-header" style="background:#252525; color:#C9A84C; font-weight:700; padding:14px 20px;">
                <i class="fas fa-upload mr-2"></i> Upload File
            </div>
            <div class="card-body" style="padding:20px;">
                @if(!$trainee)
                    <div class="alert alert-warning" style="font-size:13px;">
                        No trainee profile linked. Contact administrator.
                    </div>
                @else
                <form action="{{ route('trainee.documents.store') }}" method="POST" id="doc-upload-form">
                    @csrf
                    <div class="form-group">
                        <label class="doc-label">Type <span class="text-danger">*</span></label>
                        <select name="document_type" class="form-control" required id="doc-type-select">
                            <option value="">-- Select type --</option>
                            <option value="Presentation" {{ old('document_type') === 'Presentation' ? 'selected' : '' }}>📊 Presentation (PPT/PPTX)</option>
                            <option value="CV" {{ old('document_type') === 'CV' ? 'selected' : '' }}>CV / Curriculum Vitae</option>
                            <option value="Certificate" {{ old('document_type') === 'Certificate' ? 'selected' : '' }}>Certificate</option>
                            <option value="ID" {{ old('document_type') === 'ID' ? 'selected' : '' }}>ID / Passport</option>
                            <option value="Other" {{ old('document_type') === 'Other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>
                    <div class="form-group" id="title-field">
                        <label class="doc-label">Presentation Title</label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" placeholder="e.g. My Research Synopsis">
                    </div>
                    <div class="form-group">
                        <label class="doc-label">File <span class="text-danger">*</span></label>
                        <div class="needsclick dropzone" id="doc-dropzone"></div>
                        {{-- progress bar --}}
                        <div id="doc-progress-wrap" style="display:none; margin-top:8px;">
                            <div style="display:flex; justify-content:space-between; font-size:10px; color:#888; margin-bottom:3px;">
                                <span>Uploading…</span><span id="doc-pct">0%</span>
                            </div>
                            <div style="height:5px; background:#e9ecef; border-radius:3px; overflow:hidden;">
                                <div id="doc-bar" style="height:100%; width:0%; background:#C9A84C; border-radius:3px; transition:width .15s ease;"></div>
                            </div>
                        </div>
                        <div id="doc-status" style="font-size:12px; color:#888; margin-top:6px;"></div>
                        {{-- hidden token set after upload completes --}}
                        <input type="hidden" name="file" id="doc-file-token" value="">
                    </div>
                    <button type="submit" id="doc-submit-btn" class="btn btn-block" style="background:#C9A84C; color:#fff; font-weight:700; font-size:13px;" disabled>
                        <i class="fas fa-cloud-upload-alt mr-2"></i> Upload
                    </button>
                    <small class="text-muted d-block text-center mt-1">Select a file above first</small>
                </form>
                @endif
            </div>
        </div>

        {{-- Info card --}}
        <div class="card shadow-sm mt-3" style="border-radius:8px; overflow:hidden; border-left:4px solid #C9A84C;">
            <div class="card-body" style="padding:16px 18px;">
                <div style="font-size:12px; font-weight:700; color:#C9A84C; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:8px;">
                    <i class="fas fa-info-circle mr-1"></i> About Presentations
                </div>
                <p style="font-size:12px; color:#666; margin:0; line-height:1.6;">
                    Upload your presentation slides here. Facilitators will be able to preview your slides and leave feedback comments directly on each submission.
                </p>
            </div>
        </div>
    </div>

    {{-- Documents list --}}
    <div class="col-lg-8">

        @php
            $presentations = $documents->where('document_type', 'Presentation');
            $otherDocs     = $documents->where('document_type', '!=', 'Presentation');
        @endphp

        {{-- Presentations section --}}
        @if($presentations->isNotEmpty())
        <div class="mb-4">
            <h6 style="font-weight:700; color:#2d3748; margin-bottom:12px; font-size:14px;">
                <i class="fas fa-file-powerpoint mr-2" style="color:#C9A84C;"></i>My Presentations
            </h6>
            @foreach($presentations as $doc)
            <div class="card shadow-sm mb-3" style="border-radius:8px; overflow:hidden; border-left:3px solid #C9A84C;">
                <div class="card-body" style="padding:16px 20px;">
                    <div class="d-flex align-items-start justify-content-between">
                        <div style="flex:1; min-width:0;">
                            <div style="font-weight:700; font-size:14px; color:#2d3748; margin-bottom:2px;">
                                <i class="fas fa-file-powerpoint mr-2" style="color:#C9A84C;"></i>
                                {{ $doc->title ?: $doc->original_name }}
                            </div>
                            <div style="font-size:11.5px; color:#888; margin-bottom:8px;">
                                {{ $doc->original_name }} &bull; Uploaded {{ $doc->created_at->format('M j, Y') }}
                            </div>

                            {{-- Facilitator comments --}}
                            @if($doc->comments->isNotEmpty())
                            <div style="background:#fffbf0; border:1px solid #f0e0a0; border-radius:6px; padding:10px 14px; margin-top:8px;">
                                <div style="font-size:11px; font-weight:700; text-transform:uppercase; color:#C9A84C; letter-spacing:0.5px; margin-bottom:8px;">
                                    <i class="fas fa-comments mr-1"></i> Facilitator Feedback ({{ $doc->comments->count() }})
                                </div>
                                @foreach($doc->comments as $comment)
                                <div style="margin-bottom:8px; padding-bottom:8px; {{ !$loop->last ? 'border-bottom:1px solid #f0e0a0;' : '' }}">
                                    <div style="font-size:11.5px; font-weight:700; color:#555; margin-bottom:2px;">
                                        <i class="fas fa-user-tie mr-1" style="color:#C9A84C;"></i>{{ $comment->user->name }}
                                        <span style="font-weight:400; color:#aaa; font-size:11px; margin-left:6px;">{{ $comment->created_at->format('M j, Y H:i') }}</span>
                                    </div>
                                    <div style="font-size:13px; color:#333; line-height:1.5;">{{ $comment->comment }}</div>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div style="font-size:12px; color:#aaa; font-style:italic; margin-top:4px;">
                                <i class="fas fa-clock mr-1"></i> Awaiting facilitator feedback
                            </div>
                            @endif
                        </div>

                        <div class="ml-3 d-flex flex-column" style="gap:6px; flex-shrink:0;">
                            <a href="{{ $doc->download_url }}" download
                               class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:11px; white-space:nowrap;">
                                <i class="fas fa-download mr-1"></i> Download
                            </a>
                            <form action="{{ route('trainee.documents.destroy', $doc->id) }}" method="POST"
                                  onsubmit="return confirm('Remove this presentation?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-block" style="background:#fff5f5; color:#e53e3e; border:1px solid #fed7d7; font-size:11px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Other documents --}}
        <div class="card shadow-sm" style="border-radius:8px; overflow:hidden;">
            <div class="card-header d-flex align-items-center" style="background:#252525; color:#C9A84C; font-weight:700; padding:14px 20px;">
                <span><i class="fas fa-folder-open mr-2"></i> Other Documents</span>
                <span class="ml-auto" style="color:rgba(255,255,255,0.5); font-size:12px; font-weight:400;">{{ $otherDocs->count() }} file{{ $otherDocs->count()!==1?'s':'' }}</span>
            </div>
            <div class="card-body p-0">
                @if($otherDocs->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-folder-open fa-2x mb-2" style="color:#ddd; display:block;"></i>
                        <p style="font-size:13px;">No other documents uploaded yet.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background:#f8f9fa;">
                                <tr>
                                    <th style="font-size:11px; font-weight:700; color:#555; border-top:none; padding:10px 16px; text-transform:uppercase; letter-spacing:0.4px;">File</th>
                                    <th style="font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px;">Type</th>
                                    <th style="font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px;">Date</th>
                                    <th style="font-size:11px; font-weight:700; color:#555; border-top:none; text-align:right; padding-right:16px; text-transform:uppercase; letter-spacing:0.4px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($otherDocs as $doc)
                                <tr>
                                    <td style="padding:10px 16px; vertical-align:middle;">
                                        <div style="font-size:13px; font-weight:600; color:#252525;">
                                            <i class="fas fa-file mr-1" style="color:#C9A84C;"></i>
                                            {{ $doc->original_name }}
                                        </div>
                                    </td>
                                    <td style="vertical-align:middle;">
                                        @php $typeColors = ['CV'=>'#0d6efd','Certificate'=>'#28a745','ID'=>'#a02626','Other'=>'#666']; $tc = $typeColors[$doc->document_type] ?? '#666'; @endphp
                                        <span class="badge" style="background:{{ $tc }}22; color:{{ $tc }}; border:1px solid {{ $tc }}44; font-size:11px; padding:3px 8px;">
                                            {{ $doc->document_type }}
                                        </span>
                                    </td>
                                    <td style="vertical-align:middle; font-size:12px; color:#888;">{{ $doc->created_at->format('M j, Y') }}</td>
                                    <td style="vertical-align:middle; text-align:right; padding-right:16px;">
                                        <form action="{{ route('trainee.documents.destroy', $doc->id) }}" method="POST"
                                              onsubmit="return confirm('Remove this document?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm" style="background:#f8d7da; color:#a02626; border:none; font-size:12px; padding:3px 9px; border-radius:4px;">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
.doc-label { font-size:11px; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px; display:block; }
</style>
@endsection

@section('scripts')
<script>
Dropzone.autoDiscover = false;

// Show/hide title field based on type
var typeSelect = document.getElementById('doc-type-select');
var titleField = document.getElementById('title-field');
function toggleTitle() {
    titleField.style.display = (typeSelect.value === 'Presentation') ? 'block' : 'none';
}
typeSelect.addEventListener('change', toggleTitle);
toggleTitle();

// Dropzone
$(function() {
    var docDz = new Dropzone('#doc-dropzone', {
        url: '{{ route('trainee.documents.storeMedia') }}',
        maxFilesize: 20,
        maxFiles: 1,
        addRemoveLinks: true,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        previewTemplate: window.DZ_PREVIEW_TEMPLATE,
        dictDefaultMessage: window.DZ_DEFAULT_MSG,
        sending: function() {
            $('#doc-progress-wrap').show();
            $('#doc-bar').css('width', '0%');
            $('#doc-pct').text('0%');
            $('#doc-submit-btn').prop('disabled', true);
        },
        uploadprogress: function(file, progress) {
            var pct = Math.round(progress) + '%';
            $('#doc-bar').css('width', pct);
            $('#doc-pct').text(pct);
        },
        success: function(file, response) {
            $('#doc-bar').css('width', '100%');
            $('#doc-pct').text('100%');
            setTimeout(function(){ $('#doc-progress-wrap').hide(); }, 600);
            $('#doc-file-token').val(response.name);
            $('#doc-status').text('✔ Ready: ' + response.original_name).css('color', '#2d8a4e');
            $('#doc-submit-btn').prop('disabled', false).closest('form').find('small').text('');
        },
        removedfile: function(file) {
            file.previewElement.remove();
            $('#doc-file-token').val('');
            $('#doc-submit-btn').prop('disabled', true);
            $('#doc-status').text('');
            $('#doc-progress-wrap').hide();
            $('#doc-bar').css('width', '0%');
        },
        error: function(file, msg, xhr) {
            var err = typeof msg === 'string' ? msg : (msg.error || msg.message || 'Upload failed');
            if (xhr && xhr.status === 422) {
                try { var p = JSON.parse(xhr.responseText); err = p.errors ? Object.values(p.errors).flat().join(' ') : (p.message || err); } catch(e){}
            }
            $('#doc-status').text('Error: ' + err).css('color', '#c0392b');
            $('#doc-submit-btn').prop('disabled', true);
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
