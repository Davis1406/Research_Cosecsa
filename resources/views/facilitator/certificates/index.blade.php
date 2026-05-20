@extends('layouts.facilitator')

@section('page-title', 'Certificates')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
        <i class="fas fa-certificate mr-2" style="color:#C9A84C;"></i> Issued Certificates
    </h5>
    <a href="{{ route('facilitator.certificates.create') }}" class="btn btn-sm" style="background:#C9A84C; color:#fff; font-weight:700; font-size:13px; border-radius:5px;">
        <i class="fas fa-plus mr-1"></i> Issue Certificate
    </a>
</div>

@if($certificates->isEmpty())
    <div class="alert alert-info">No certificates issued yet. <a href="{{ route('facilitator.certificates.create') }}" class="alert-link">Issue the first one.</a></div>
@else
<div class="card shadow-sm" style="border-radius:8px; overflow:hidden;">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead style="background:#f8f9fa; border-bottom:2px solid #C9A84C;">
                <tr>
                    <th style="font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px;">Trainee</th>
                    <th style="font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px;">Event</th>
                    <th style="font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px;">Date</th>
                    <th style="font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px;">Issued By</th>
                    <th style="font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px;">Generated</th>
                    <th style="font-size:11px; font-weight:700; color:#555; border-top:none; text-align:right;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($certificates as $cert)
                <tr>
                    <td style="font-size:13px; font-weight:600; color:#2d3748;">{{ $cert->trainee?->name ?? '—' }}</td>
                    <td style="font-size:13px; color:#555;">{{ $cert->event_name }}</td>
                    <td style="font-size:13px; color:#555;">{{ $cert->event_date }}</td>
                    <td style="font-size:13px; color:#555;">{{ $cert->issuedBy?->name ?? '—' }}</td>
                    <td style="font-size:12px; color:#888;">{{ $cert->generated_at ? $cert->generated_at->format('M j, Y') : '—' }}</td>
                    <td style="text-align:right; padding-right:16px;">
                        <a href="{{ route('facilitator.certificates.preview', $cert) }}" target="_blank"
                           class="btn btn-sm" style="background:#fff8e6; color:#9a7d2c; border:1px solid #C9A84C55; font-size:11px; margin-right:4px;">
                            <i class="fas fa-eye mr-1"></i> Preview
                        </a>
                        <form action="{{ route('facilitator.certificates.destroy', $cert) }}" method="POST" style="display:inline;"
                              onsubmit="return confirm('Delete this certificate?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-sm" style="background:#fff5f5; color:#e53e3e; border:1px solid #fed7d7; font-size:11px;">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
