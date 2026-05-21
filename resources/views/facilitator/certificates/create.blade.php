@extends('layouts.facilitator')

@section('page-title', 'Issue Certificate')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
        <i class="fas fa-certificate mr-2" style="color:#C9A84C;"></i> Issue Certificate
    </h5>
    <a href="{{ route('facilitator.certificates.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:13px;">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
</div>

<form action="{{ route('facilitator.certificates.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="card shadow-sm mb-3" style="border-radius:8px;">
        <div class="card-header" style="background:#fff; border-bottom:2px solid #C9A84C; font-weight:700; font-size:13px; color:#2d3748;">
            Event Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label style="font-size:12px; font-weight:700; color:#555;">Course Name *</label>
                    <input type="text" name="course_name" class="form-control" value="{{ old('course_name', 'Fundamentals of Surgical Research Course') }}" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-size:12px; font-weight:700; color:#555;">Event Name *</label>
                    <input type="text" name="event_name" class="form-control" value="{{ old('event_name') }}" required placeholder="e.g. COSECSA Annual Workshop 2026">
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-size:12px; font-weight:700; color:#555;">Venue</label>
                    <input type="text" name="venue" class="form-control" value="{{ old('venue') }}" placeholder="e.g. Nairobi, Kenya">
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-size:12px; font-weight:700; color:#555;">Event Date *</label>
                    <input type="text" name="event_date" class="form-control" value="{{ old('event_date') }}" required placeholder="e.g. 20–25 May 2026">
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3" style="border-radius:8px;">
        <div class="card-header" style="background:#fff; border-bottom:2px solid #C9A84C; font-weight:700; font-size:13px; color:#2d3748;">
            Organisation &amp; Branding
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label style="font-size:12px; font-weight:700; color:#555;">Organisation Name</label>
                    <input type="text" name="org_name" class="form-control" value="{{ old('org_name', 'College of Surgeons of East, Central & Southern Africa') }}" placeholder="Issuing organisation">
                    <small class="text-muted">Appears at the top of the certificate.</small>
                </div>
                <div class="col-md-2 mb-3">
                    <label style="font-size:12px; font-weight:700; color:#555;">Logo 1</label>
                    <input type="file" name="logo_image" class="form-control-file" accept="image/*" onchange="previewImg(this,'logo-prev')">
                    <small class="text-muted">Main logo (centre)</small>
                    <div id="logo-prev" class="mt-2"></div>
                </div>
                <div class="col-md-2 mb-3">
                    <label style="font-size:12px; font-weight:700; color:#555;">Logo 2</label>
                    <input type="file" name="logo2_image" class="form-control-file" accept="image/*" onchange="previewImg(this,'logo2-prev')">
                    <small class="text-muted">Optional partner logo</small>
                    <div id="logo2-prev" class="mt-2"></div>
                </div>
                <div class="col-md-2 mb-3">
                    <label style="font-size:12px; font-weight:700; color:#555;">Logo 3</label>
                    <input type="file" name="logo3_image" class="form-control-file" accept="image/*" onchange="previewImg(this,'logo3-prev')">
                    <small class="text-muted">Optional partner logo</small>
                    <div id="logo3-prev" class="mt-2"></div>
                </div>
                <div class="col-md-2 mb-3">
                    <label style="font-size:12px; font-weight:700; color:#555;">Official Stamp / Seal</label>
                    <input type="file" name="stamp_image" class="form-control-file" accept="image/*" onchange="previewImg(this,'stamp-prev')">
                    <small class="text-muted">PNG with transparency recommended.</small>
                    <div id="stamp-prev" class="mt-2"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3" style="border-radius:8px;">
        <div class="card-header" style="background:#fff; border-bottom:2px solid #C9A84C; font-weight:700; font-size:13px; color:#2d3748;">
            Signatures
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <div style="background:#f8f9fa; border-radius:6px; padding:14px; border:1px solid #e9ecef;">
                        <div style="font-size:11px; font-weight:700; text-transform:uppercase; color:#aaa; margin-bottom:8px; letter-spacing:0.5px;">Signatory 1</div>
                        <div class="form-group mb-2">
                            <label style="font-size:12px; font-weight:700; color:#555;">Name</label>
                            <input type="text" name="sig1_name" class="form-control form-control-sm" value="{{ old('sig1_name') }}" placeholder="Full name">
                        </div>
                        <div class="form-group mb-2">
                            <label style="font-size:12px; font-weight:700; color:#555;">Title / Position</label>
                            <input type="text" name="sig1_title" class="form-control form-control-sm" value="{{ old('sig1_title') }}" placeholder="e.g. COSECSA President">
                        </div>
                        <div class="form-group mb-0">
                            <label style="font-size:12px; font-weight:700; color:#555;">Signature Image</label>
                            <input type="file" name="sig1_image" class="form-control-file" accept="image/*">
                            <small class="text-muted">PNG with transparent background recommended.</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <div style="background:#f8f9fa; border-radius:6px; padding:14px; border:1px solid #e9ecef;">
                        <div style="font-size:11px; font-weight:700; text-transform:uppercase; color:#aaa; margin-bottom:8px; letter-spacing:0.5px;">Signatory 2</div>
                        <div class="form-group mb-2">
                            <label style="font-size:12px; font-weight:700; color:#555;">Name</label>
                            <input type="text" name="sig2_name" class="form-control form-control-sm" value="{{ old('sig2_name') }}" placeholder="Full name">
                        </div>
                        <div class="form-group mb-2">
                            <label style="font-size:12px; font-weight:700; color:#555;">Title / Position</label>
                            <input type="text" name="sig2_title" class="form-control form-control-sm" value="{{ old('sig2_title') }}" placeholder="e.g. Course Coordinator">
                        </div>
                        <div class="form-group mb-0">
                            <label style="font-size:12px; font-weight:700; color:#555;">Signature Image</label>
                            <input type="file" name="sig2_image" class="form-control-file" accept="image/*">
                            <small class="text-muted">PNG with transparent background recommended.</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3" style="border-radius:8px;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background:#fff; border-bottom:2px solid #C9A84C; font-weight:700; font-size:13px; color:#2d3748;">
            <span>Select Trainees *</span>
            <div>
                <button type="button" onclick="selectAll()" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:11px;">Select All</button>
                <button type="button" onclick="clearAll()" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:11px; margin-left:4px;">Clear</button>
            </div>
        </div>
        <div class="card-body" style="max-height:300px; overflow-y:auto;">
            @if($trainees->isEmpty())
                <div class="text-muted" style="font-size:13px;">No trainees available.</div>
            @else
            <div class="row">
                @foreach($trainees as $trainee)
                <div class="col-md-6 col-lg-4 mb-2">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input trainee-check" id="t{{ $trainee->id }}" name="trainee_ids[]" value="{{ $trainee->id }}"
                               {{ in_array($trainee->id, old('trainee_ids', [])) ? 'checked' : '' }}>
                        <label class="custom-control-label" for="t{{ $trainee->id }}" style="font-size:13px;">
                            {{ $trainee->name }}
                            @if($trainee->institution)
                            <span class="text-muted" style="font-size:11px; display:block;">{{ $trainee->institution }}</span>
                            @endif
                        </label>
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    <div class="d-flex justify-content-end" style="gap:8px;">
        <a href="{{ route('facilitator.certificates.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6;">Cancel</a>
        <button type="submit" class="btn btn-sm" style="background:#C9A84C; color:#fff; font-weight:700; padding:8px 20px;">
            <i class="fas fa-certificate mr-1"></i> Generate Certificate(s)
        </button>
    </div>
</form>
@endsection

@section('scripts')
<script>
function selectAll() {
    document.querySelectorAll('.trainee-check').forEach(function(c) { c.checked = true; });
}
function clearAll() {
    document.querySelectorAll('.trainee-check').forEach(function(c) { c.checked = false; });
}
function previewImg(input, targetId) {
    var target = document.getElementById(targetId);
    target.innerHTML = '';
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            var img = document.createElement('img');
            img.src = e.target.result;
            img.style.cssText = 'max-height:60px; max-width:100%; border-radius:4px; border:1px solid #dee2e6;';
            target.appendChild(img);
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
