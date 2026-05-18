@extends('layouts.trainee')

@section('page-title', 'My Documents')

@section('content')
<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card shadow-sm" style="border-radius:8px; overflow:hidden;">
            <div class="card-header" style="background:#252525; color:#C9A84C; font-weight:700; padding:14px 20px;">
                <i class="fas fa-upload mr-2"></i> Upload Document
            </div>
            <div class="card-body" style="padding:20px;">
                @if(!$trainee)
                    <div class="alert alert-warning" style="font-size:13px;">
                        No trainee profile linked. Contact administrator.
                    </div>
                @else
                    <form action="{{ route('trainee.documents.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label style="font-size:12px; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:0.5px;">
                                Document Type <span class="text-danger">*</span>
                            </label>
                            <select name="document_type" class="form-control" required>
                                <option value="">-- Select type --</option>
                                <option value="CV" {{ old('document_type') === 'CV' ? 'selected' : '' }}>CV / Curriculum Vitae</option>
                                <option value="Certificate" {{ old('document_type') === 'Certificate' ? 'selected' : '' }}>Certificate</option>
                                <option value="ID" {{ old('document_type') === 'ID' ? 'selected' : '' }}>ID / Passport</option>
                                <option value="Other" {{ old('document_type') === 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label style="font-size:12px; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:0.5px;">
                                File <span class="text-danger">*</span>
                            </label>
                            <input type="file" name="file" class="form-control-file" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                            <small class="text-muted">Allowed: PDF, DOC, DOCX, JPG, PNG. Max 5MB.</small>
                        </div>
                        <button type="submit" class="btn btn-block" style="background:#a02626; color:#fff; font-weight:600;">
                            <i class="fas fa-upload mr-2"></i> Upload
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card shadow-sm" style="border-radius:8px; overflow:hidden;">
            <div class="card-header d-flex align-items-center" style="background:#252525; color:#C9A84C; font-weight:700; padding:14px 20px;">
                <span><i class="fas fa-folder-open mr-2"></i> My Uploaded Documents</span>
                <span class="ml-auto" style="color:rgba(255,255,255,0.5); font-size:12px; font-weight:400;">{{ $documents->count() }} file{{ $documents->count() !== 1 ? 's' : '' }}</span>
            </div>
            <div class="card-body p-0">
                @if($documents->isEmpty())
                    <div class="text-center py-4 text-muted">
                        <i class="fas fa-folder-open fa-2x mb-2" style="color:#ddd;"></i>
                        <p style="font-size:13px;">No documents uploaded yet.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead style="background:#f8f9fa;">
                                <tr>
                                    <th style="font-size:12px; font-weight:700; color:#555; border-top:none; padding:10px 16px;">File</th>
                                    <th style="font-size:12px; font-weight:700; color:#555; border-top:none;">Type</th>
                                    <th style="font-size:12px; font-weight:700; color:#555; border-top:none;">Uploaded</th>
                                    <th style="font-size:12px; font-weight:700; color:#555; border-top:none; text-align:right; padding-right:16px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($documents as $doc)
                                    <tr>
                                        <td style="padding:10px 16px; vertical-align:middle;">
                                            <div style="font-size:13.5px; font-weight:600; color:#252525;">
                                                <i class="fas fa-file mr-1" style="color:#C9A84C;"></i>
                                                {{ $doc->original_name }}
                                            </div>
                                        </td>
                                        <td style="vertical-align:middle;">
                                            @php
                                                $typeColors = ['CV'=>'#0d6efd','Certificate'=>'#28a745','ID'=>'#a02626','Other'=>'#666'];
                                                $tc = $typeColors[$doc->document_type] ?? '#666';
                                            @endphp
                                            <span class="badge" style="background:{{ $tc }}22; color:{{ $tc }}; border:1px solid {{ $tc }}44; font-size:11px; padding:3px 8px;">
                                                {{ $doc->document_type }}
                                            </span>
                                        </td>
                                        <td style="vertical-align:middle; font-size:12px; color:#888;">
                                            {{ $doc->created_at->format('M j, Y') }}
                                        </td>
                                        <td style="vertical-align:middle; text-align:right; padding-right:16px;">
                                            <form action="{{ route('trainee.documents.destroy', $doc->id) }}" method="POST"
                                                  onsubmit="return confirm('Remove this document?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm" style="background:#f8d7da; color:#a02626; border:none; font-size:12px; padding:4px 10px; border-radius:4px;">
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
