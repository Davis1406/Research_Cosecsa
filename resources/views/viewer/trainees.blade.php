@extends('layouts.viewer')
@section('page-title', 'Trainees')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4" style="flex-wrap:wrap; gap:10px;">
    <div>
        <h5 style="font-weight:800; font-size:1.1rem; margin-bottom:2px;"><i class="fas fa-user-graduate mr-2" style="color:#2c7a4b;"></i>Trainees</h5>
        <p style="color:#718096; font-size:13px; margin:0;">{{ $trainees->count() }} enrolled trainee{{ $trainees->count() != 1 ? 's' : '' }}</p>
    </div>
    <a href="{{ route('viewer.trainees') }}?export=csv"
       style="display:inline-flex;align-items:center;gap:7px;background:#2c7a4b;color:#fff;font-size:13px;font-weight:700;padding:8px 18px;border-radius:8px;text-decoration:none;transition:background .13s;"
       onmouseover="this.style.background='#1e5c38'" onmouseout="this.style.background='#2c7a4b'">
        <i class="fas fa-download"></i> Export CSV
    </a>
</div>

@if($trainees->isEmpty())
<div class="card" style="padding:56px; text-align:center;">
    <i class="fas fa-user-graduate" style="font-size:40px; color:#e2e8f0; display:block; margin-bottom:12px;"></i>
    <p style="color:#a0aec0; font-size:14px;">No trainees enrolled yet.</p>
</div>
@else

{{-- Search --}}
<div style="position:relative; margin-bottom:14px;">
    <i class="fas fa-search" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#a0aec0;font-size:14px;"></i>
    <input type="text" id="traineeSearch" placeholder="Search by name, institution or country…"
           style="width:100%;padding:10px 14px 10px 38px;border:1.5px solid #e2e8f0;border-radius:10px;font-size:14px;outline:none;background:#fff;"
           onfocus="this.style.borderColor='#C9A84C'" onblur="this.style.borderColor='#e2e8f0'">
</div>

<div class="card" style="overflow:hidden;">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead style="background:#f8f9fa; border-bottom:2px solid #C9A84C;">
                <tr>
                    <th style="font-size:11px;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:.4px;border-top:none;">#</th>
                    <th style="font-size:11px;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:.4px;border-top:none;">Name</th>
                    <th style="font-size:11px;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:.4px;border-top:none;">Email</th>
                    <th style="font-size:11px;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:.4px;border-top:none;">Institution</th>
                    <th style="font-size:11px;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:.4px;border-top:none;">Country</th>
                    <th style="font-size:11px;font-weight:700;color:#555;text-transform:uppercase;letter-spacing:.4px;border-top:none;">Specialty</th>
                </tr>
            </thead>
            <tbody id="traineeTable">
                @foreach($trainees as $i => $t)
                <tr class="trainee-row" data-search="{{ strtolower($t->name . ' ' . ($t->institution ?? '') . ' ' . ($t->country ?? '')) }}">
                    <td style="color:#a0aec0;font-size:13px;">{{ $i + 1 }}</td>
                    <td>
                        <div style="font-weight:700;color:#1a202c;font-size:14px;">{{ $t->name }}</div>
                        @if($t->email)
                        <div style="font-size:12px;color:#a0aec0;">{{ $t->email }}</div>
                        @endif
                    </td>
                    <td style="font-size:13.5px;color:#4a5568;">{{ $t->email ?? '—' }}</td>
                    <td style="font-size:13.5px;color:#4a5568;">{{ $t->institution ?? '—' }}</td>
                    <td style="font-size:13.5px;color:#4a5568;">{{ $t->country ?? '—' }}</td>
                    <td style="font-size:13.5px;color:#4a5568;">{{ $t->specialty ?? '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div id="noTrainees" style="display:none;padding:40px;text-align:center;color:#a0aec0;font-size:14px;">
    <i class="fas fa-search" style="font-size:28px;display:block;margin-bottom:10px;color:#e2e8f0;"></i>No trainees match your search.
</div>
@endif
@endsection

@section('scripts')
<script>
var searchInput = document.getElementById('traineeSearch');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        var q = this.value.toLowerCase();
        var rows = document.querySelectorAll('.trainee-row');
        var shown = 0;
        rows.forEach(function(row) {
            var match = row.dataset.search.includes(q);
            row.style.display = match ? '' : 'none';
            if (match) shown++;
        });
        document.getElementById('noTrainees').style.display = shown === 0 ? 'block' : 'none';
    });
}
</script>
@endsection
