@extends('layouts.admin')

@section('styles')
<link href="{{ asset('css/certificate.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,700&display=swap" rel="stylesheet">
<style>
    #certPreviewModal .cert-heading,
    #certPreviewModal .cert-name {
        font-family: 'Playfair Display', serif !important;
    }
    #certPreviewModal .certificate {
        margin: 0 auto;
    }
    #certPreviewModal .modal-body {
        background: #f4f4f4;
    }
    #certPreviewModal .pv-design-btn {
        background: #fff;
        border: 1.5px solid #d1d5db;
        color: #666;
        font-weight: 600;
        padding: 3px 14px;
    }
    #certPreviewModal .pv-design-btn.active {
        background: #a02626;
        border-color: #a02626;
        color: #fff;
    }
</style>
@endsection

@section('content')
<div class="row mb-2">
    <div class="col-lg-12 d-flex justify-content-between align-items-center">
        <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
            <i class="fas fa-certificate mr-2" style="color:#C9A84C;"></i> Issue Certificate
        </h5>
        <a href="{{ route('admin.certificates.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:13px;">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
    </div>
</div>

<form id="certForm" action="{{ route('admin.certificates.store') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="card shadow-sm mb-3" style="border-radius:8px;">
        <div class="card-header cosecsa-card-header">
            Event Details
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label style="font-size:12px; font-weight:700; color:#555;">Course Name *</label>
                    <input type="text" name="course_name" class="form-control" value="{{ old('course_name', 'Fundamentals of Surgical Research Course') }}" required>
                </div>
                @if(course_type() === 'physical')
                <div class="col-md-6 mb-3">
                    <label style="font-size:12px; font-weight:700; color:#555;">City</label>
                    <input type="text" name="venue" class="form-control" value="{{ old('venue') }}" placeholder="e.g. Nairobi, Kenya">
                </div>
                @endif
                <div class="col-md-6 mb-3">
                    <label style="font-size:12px; font-weight:700; color:#555;">Event Date *</label>
                    <input type="text" name="event_date" class="form-control" value="{{ old('event_date') }}" required placeholder="e.g. 20–25 May 2026">
                </div>
                <div class="col-md-6 mb-3">
                    <label style="font-size:12px; font-weight:700; color:#555;">CPD Points</label>
                    <input type="text" name="cpd_points" class="form-control" value="{{ old('cpd_points') }}" placeholder="e.g. 12">
                    <small class="text-muted">Shown on the golden CPD badge (optional).</small>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3" style="border-radius:8px;">
        <div class="card-header cosecsa-card-header">
            Organisation &amp; Branding
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label style="font-size:12px; font-weight:700; color:#555;">Organisation Name</label>
                    <input type="text" name="org_name" class="form-control" value="{{ old('org_name', 'College of Surgeons of East, Central & Southern Africa') }}" placeholder="Issuing organisation">
                    <small class="text-muted">Appears at the top of the certificate.</small>
                </div>
                <div class="col-md-3 mb-3">
                    <label style="font-size:12px; font-weight:700; color:#555;">Logo Image</label>
                    <input type="file" name="logo_image" class="form-control-file" accept="image/*" onchange="previewImg(this,'logo-prev')">
                    <small class="text-muted">PNG/JPG, shown at top centre.</small>
                    <div id="logo-prev" class="mt-2"></div>
                </div>
                <div class="col-md-3 mb-3">
                    <label style="font-size:12px; font-weight:700; color:#555;">Official Stamp / Seal</label>
                    <input type="file" name="stamp_image" class="form-control-file" accept="image/*" onchange="previewImg(this,'stamp-prev')">
                    <small class="text-muted">PNG with transparency recommended.</small>
                    <div id="stamp-prev" class="mt-2"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-3" style="border-radius:8px;">
        <div class="card-header cosecsa-card-header">
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
        <div class="card-header d-flex justify-content-between align-items-center cosecsa-card-header">
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
                        <input type="checkbox" class="custom-control-input trainee-check" id="t{{ $trainee->id }}" name="trainee_ids[]" value="{{ $trainee->id }}" data-name="{{ $trainee->name }}"
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
        <a href="{{ route('admin.certificates.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6;">Cancel</a>
        <button type="button" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6;" onclick="openCertPreview(false)">
            <i class="fas fa-eye mr-1"></i> Preview
        </button>
        <button type="submit" class="btn btn-cosecsa btn-sm" style="padding:8px 20px;">
            <i class="fas fa-certificate mr-1"></i> Generate Certificate(s)
        </button>
    </div>
</form>

{{-- Live certificate preview modal — opened by "Preview" or before confirming "Generate Certificate(s)" --}}
<div class="modal fade" id="certPreviewModal" tabindex="-1" role="dialog" aria-labelledby="certPreviewModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="certPreviewModalLabel" style="font-weight:700; color:#2d3748;">
                    <i class="fas fa-certificate mr-2" style="color:#C9A84C;"></i> Certificate Preview
                </h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body text-center">
                <div id="pv-note" class="small text-muted mb-2"></div>
                <div class="mb-2" style="font-size:13px; color:#888;">
                    Design:
                    <button type="button" id="pv-design-classic" class="btn btn-sm pv-design-btn active" onclick="setPreviewDesign('classic')">Classic</button>
                    <button type="button" id="pv-design-centered" class="btn btn-sm pv-design-btn" onclick="setPreviewDesign('centered')">Centered</button>
                    <button type="button" id="pv-design-slide" class="btn btn-sm pv-design-btn" onclick="setPreviewDesign('slide')">Slide 16:9</button>
                </div>
                <div style="overflow-x:auto; padding: 8px 0;">
                    <div class="certificate">
                        <div class="cert-top-bar"></div>
                        <div class="cert-inner">
                            <div id="pv-header-classic">
                                <div class="cert-logos">
                                    <div class="logo-left">
                                        <img id="pv-logo-left" src="{{ asset('img/cosecsa-logo.png') }}" alt="Logo">
                                        <div class="cert-org" id="pv-org">College of Surgeons of East, Central &amp; Southern Africa</div>
                                    </div>
                                    <div class="logo-center" id="pv-logo-center" style="display:none;">
                                        <img id="pv-logo-center-img" src="" alt="Logo 3">
                                    </div>
                                    <div class="logo-right">
                                        <img id="pv-logo-right" src="" alt="Logo 2" style="display:none;">
                                        <div id="pv-logo-right-ph" style="width:24px;"></div>
                                    </div>
                                </div>
                            </div>

                            <div id="pv-header-centered" style="display:none;">
                                <div class="cert-header-center">
                                    <img id="pv-logo-top" src="{{ asset('img/cosecsa-logo.png') }}" alt="Logo">
                                    <div class="cert-org-center" id="pv-org-center">College of Surgeons of East, Central &amp; Southern Africa</div>
                                </div>
                            </div>

                            <div class="cert-divider"></div>

                            <div class="cert-heading">Certificate of Completion</div>
                            <div class="cert-subtitle">This is to certify that</div>

                            <div class="cert-name" id="pv-name">Trainee Name</div>

                            <div class="cert-name-rule"></div>

                            <div class="cert-body-text">has successfully completed the</div>

                            <div class="cert-course" id="pv-course">Fundamentals of Surgical Research Course</div>

                            @if(course_type() === 'physical')
                            <div class="cert-body-text" style="margin-top:10px;">Held in</div>
                            @endif

                            <div class="cert-venue-date" id="pv-venue-date">City &bull; Date</div>

                            <div class="cert-divider"></div>

                            <div class="cert-sigs">
                                <div class="sig-block" id="pv-sig1" style="display:none;">
                                    <img id="pv-sig1-img" src="" alt="Signature 1" style="display:none;">
                                    <div class="sig-line"></div>
                                    <div class="sig-name" id="pv-sig1-name">Signature</div>
                                    <div class="sig-title" id="pv-sig1-title"></div>
                                </div>
                                <div class="sig-block" id="pv-sig2" style="display:none;">
                                    <img id="pv-sig2-img" src="" alt="Signature 2" style="display:none;">
                                    <div class="sig-line"></div>
                                    <div class="sig-name" id="pv-sig2-name">Signature</div>
                                    <div class="sig-title" id="pv-sig2-title"></div>
                                </div>
                            </div>

                            <div id="pv-stamp" style="display:none; margin-top:18px;">
                                <img id="pv-stamp-img" src="" alt="Official Stamp" style="max-height:80px; max-width:80px; opacity:0.85;">
                            </div>

                            {{-- Bottom row below the signatures: COSECSA gold CPD points badge (left) + verification QR (right) --}}
                            <div class="cert-bottom-row">
                                <div class="cert-cpd">
                                    <div class="cert-cpd-badge">
                                        <div class="cert-cpd-value" id="pv-cpd">CPD</div>
                                        <div class="cert-cpd-bottom">CPD Points</div>
                                    </div>
                                </div>

                                <div class="cert-verify">
                                    <div class="cert-verify-placeholder">
                                        <i class="fas fa-qrcode"></i>
                                    </div>
                                    <div class="cert-verify-label">Scan to verify</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6;" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-sm" id="pv-png-btn" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6;" onclick="downloadPreviewPng()">
                    <i class="fas fa-download mr-1"></i> Download PNG
                </button>
                <button type="button" class="btn btn-cosecsa btn-sm" id="pv-confirm-btn" style="display:none; padding:8px 20px;">
                    <i class="fas fa-check mr-1"></i> Confirm &amp; Generate
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
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

// ── Live certificate preview ──────────────────────────────────────────────
function certField(name) {
    var el = document.querySelector('#certForm [name="' + name + '"]');
    return el ? String(el.value || '').trim() : '';
}
function certHasFile(name) {
    var el = document.querySelector('#certForm [name="' + name + '"]');
    return !!(el && el.files && el.files[0]);
}
function certReadImage(inputName, imgId) {
    var input = document.querySelector('#certForm [name="' + inputName + '"]');
    var img = document.getElementById(imgId);
    if (input && input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) { img.src = e.target.result; img.style.display = ''; };
        reader.readAsDataURL(input.files[0]);
    }
}
function certFillSignature(n, prefix) {
    var name   = certField(prefix + '_name');
    var hasImg = certHasFile(prefix + '_image');
    var block  = document.getElementById('pv-sig' + n);
    if (name || hasImg) {
        block.style.display = '';
        document.getElementById('pv-sig' + n + '-name').textContent  = name || 'Signature';
        document.getElementById('pv-sig' + n + '-title').textContent = certField(prefix + '_title');
        var img = document.getElementById('pv-sig' + n + '-img');
        if (hasImg) {
            certReadImage(prefix + '_image', 'pv-sig' + n + '-img');
        } else {
            img.style.display = 'none';
            img.removeAttribute('src');
        }
    } else {
        block.style.display = 'none';
    }
}
function fillCertificatePreview() {
    var orgText = certField('org_name') || 'College of Surgeons of East, Central & Southern Africa';
    document.getElementById('pv-org').textContent = orgText;
    document.getElementById('pv-org-center').textContent = orgText;
    document.getElementById('pv-course').textContent =
        certField('course_name') || 'Fundamentals of Surgical Research Course';

    var venue = certField('venue');
    var date  = certField('event_date');
    var venueFallback = @json(course_type() === 'physical' ? 'City • Date' : 'Date');
    document.getElementById('pv-venue-date').textContent =
        (venue && date) ? venue + ' • ' + date : (venue || date || venueFallback);

    document.getElementById('pv-cpd').textContent = certField('cpd_points') || 'CPD';

    var checks    = document.querySelectorAll('.trainee-check:checked');
    var firstName = checks.length ? (checks[0].getAttribute('data-name') || 'Trainee') : '';
    document.getElementById('pv-name').textContent = firstName || 'Trainee Name';

    // Logos
    if (certHasFile('logo_image')) {
        certReadImage('logo_image', 'pv-logo-left');
        certReadImage('logo_image', 'pv-logo-top');
    } else {
        var defaultLogo = '{{ asset('img/cosecsa-logo.png') }}';
        var logoLeft = document.getElementById('pv-logo-left');
        logoLeft.src = defaultLogo;
        logoLeft.style.display = '';
        document.getElementById('pv-logo-top').src = defaultLogo;
    }
    var lc = document.getElementById('pv-logo-center');
    if (certHasFile('logo3_image')) { lc.style.display = 'flex'; certReadImage('logo3_image', 'pv-logo-center-img'); }
    else { lc.style.display = 'none'; }

    var lr   = document.getElementById('pv-logo-right');
    var lrPh = document.getElementById('pv-logo-right-ph');
    if (certHasFile('logo2_image')) { lr.style.display = ''; lrPh.style.display = 'none'; certReadImage('logo2_image', 'pv-logo-right'); }
    else { lr.style.display = 'none'; lrPh.style.display = ''; }

    // Stamp / seal
    var st = document.getElementById('pv-stamp');
    if (certHasFile('stamp_image')) { st.style.display = 'block'; certReadImage('stamp_image', 'pv-stamp-img'); }
    else { st.style.display = 'none'; }

    // Signatures
    certFillSignature(1, 'sig1');
    certFillSignature(2, 'sig2');
}
var previewDesign = 'classic';
function setPreviewDesign(design) {
    previewDesign = design;
    var centered = (design === 'centered' || design === 'slide');
    document.getElementById('pv-header-classic').style.display  = centered ? 'none' : '';
    document.getElementById('pv-header-centered').style.display = centered ? '' : 'none';
    document.querySelector('#certPreviewModal .certificate')
        .classList.toggle('certificate--slide', design === 'slide');
    document.getElementById('pv-design-classic').classList.toggle('active', design === 'classic');
    document.getElementById('pv-design-centered').classList.toggle('active', design === 'centered');
    document.getElementById('pv-design-slide').classList.toggle('active', design === 'slide');
    previewCanvasCache = null;
    if (document.getElementById('certPreviewModal').classList.contains('show')) {
        renderPreviewCanvas();
    }
}

function openCertPreview(fromGenerate) {
    var form = document.getElementById('certForm');
    if (fromGenerate) {
        if (!form.checkValidity()) { form.reportValidity(); return; }
        var selected = document.querySelectorAll('.trainee-check:checked').length;
        if (selected === 0) { alert('Please select at least one trainee.'); return; }
    }

    fillCertificatePreview();

    var note  = document.getElementById('pv-note');
    var btn   = document.getElementById('pv-confirm-btn');
    var count = document.querySelectorAll('.trainee-check:checked').length;
    if (fromGenerate) {
        btn.style.display = '';
        note.textContent = count === 1
            ? 'This certificate will be issued to 1 trainee. Review it, then click "Confirm & Generate" to finalise.'
            : 'These certificates will be issued to ' + count + ' trainees. Review the first trainee shown, then click "Confirm & Generate" to finalise.';
    } else {
        btn.style.display = 'none';
        note.textContent = count > 0
            ? 'Live preview (first selected trainee shown). Fill in or change details, then reopen to update.'
            : 'Live preview of the certificate template — no details filled in yet.';
    }

    $('#certPreviewModal').modal('show');
}

// Pre-render the certificate to a canvas in the background (while the user
// reviews the preview) so the Download PNG button responds instantly.
var previewCanvasCache = null;
var previewRenderPending = false;
function renderPreviewCanvas() {
    if (previewRenderPending) return;
    previewRenderPending = true;
    html2canvas(document.querySelector('#certPreviewModal .certificate'), {
        scale: 2,
        useCORS: true,
        backgroundColor: '#ffffff',
        logging: false
    }).then(function (canvas) {
        previewCanvasCache = canvas;
    }).finally(function () {
        previewRenderPending = false;
    });
}
$('#certPreviewModal').on('shown.bs.modal', function () {
    previewCanvasCache = null;
    renderPreviewCanvas();
});

// Download the live certificate preview as a PNG image
function downloadPreviewPng() {
    var btn = document.getElementById('pv-png-btn');
    var original = btn.innerHTML;
    var finish = function (canvas) {
        var a = document.createElement('a');
        a.download = 'COSECSA-Certificate-Preview.png';
        a.href = canvas.toDataURL('image/png');
        document.body.appendChild(a);
        a.click();
        a.remove();
        btn.disabled = false;
        btn.innerHTML = original;
    };

    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Rendering…';
    if (previewCanvasCache) {
        finish(previewCanvasCache);
        return;
    }
    html2canvas(document.querySelector('#certPreviewModal .certificate'), {
        scale: 2,
        useCORS: true,
        backgroundColor: '#ffffff',
        logging: false
    }).then(finish).catch(function () {
        alert('Sorry, the PNG could not be generated.');
        btn.disabled = false;
        btn.innerHTML = original;
    });
}

// Intercept submit → show confirm preview instead of saving immediately
document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('certForm').addEventListener('submit', function (e) {
        e.preventDefault();
        openCertPreview(true);
    });
    document.getElementById('pv-confirm-btn').addEventListener('click', function () {
        var form = document.getElementById('certForm');
        if (!form.checkValidity()) { form.reportValidity(); return; }
        if (document.querySelectorAll('.trainee-check:checked').length === 0) {
            alert('Please select at least one trainee.');
            return;
        }
        form.submit();
    });
});
</script>
@endsection
