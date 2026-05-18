@extends('layouts.facilitator')

@section('page-title', 'Trainees')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#252525;">
        <i class="fas fa-users mr-2" style="color:#C9A84C;"></i> Enrolled Trainees
    </h5>
    <span class="badge" style="background:#252525; color:#C9A84C; font-size:12px; padding:6px 12px;">
        {{ $traineeList->count() }} trainees
    </span>
</div>

@if($traineeList->isEmpty())
    <div class="alert alert-info">No trainees are enrolled yet.</div>
@else
    <div class="card shadow-sm" style="border-radius:8px; overflow:hidden;">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#252525;">
                    <tr>
                        <th style="font-size:12px; font-weight:700; color:#C9A84C; border-top:none; padding:12px 16px;">#</th>
                        <th style="font-size:12px; font-weight:700; color:#C9A84C; border-top:none;">Name</th>
                        <th style="font-size:12px; font-weight:700; color:#C9A84C; border-top:none;">Institution</th>
                        <th style="font-size:12px; font-weight:700; color:#C9A84C; border-top:none;">Country</th>
                        <th style="font-size:12px; font-weight:700; color:#C9A84C; border-top:none;">Specialty</th>
                        <th style="font-size:12px; font-weight:700; color:#C9A84C; border-top:none;">Registration No.</th>
                        <th style="font-size:12px; font-weight:700; color:#C9A84C; border-top:none; text-align:center;">Documents</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($traineeList as $i => $trainee)
                        <tr>
                            <td style="padding:10px 16px; vertical-align:middle; color:#888; font-size:12px;">{{ $i + 1 }}</td>
                            <td style="vertical-align:middle;">
                                <div style="font-weight:700; font-size:13.5px; color:#252525;">{{ $trainee->name }}</div>
                                <div style="font-size:11.5px; color:#888;">{{ $trainee->email }}</div>
                            </td>
                            <td style="vertical-align:middle; font-size:13px; color:#555;">{{ $trainee->institution ?: '—' }}</td>
                            <td style="vertical-align:middle; font-size:13px; color:#555;">{{ $trainee->country ?: '—' }}</td>
                            <td style="vertical-align:middle; font-size:13px; color:#555;">{{ $trainee->specialty ?: '—' }}</td>
                            <td style="vertical-align:middle; font-size:13px; color:#555;">{{ $trainee->registration_number ?: '—' }}</td>
                            <td style="vertical-align:middle; text-align:center;">
                                @php $docCount = $trainee->documents->count(); @endphp
                                @if($docCount > 0)
                                    <span class="badge" style="background:#d4edda; color:#155724; border:1px solid #c3e6cb; font-size:11px; padding:3px 8px;">
                                        <i class="fas fa-file mr-1"></i>{{ $docCount }}
                                    </span>
                                @else
                                    <span class="badge" style="background:#f8f9fa; color:#888; font-size:11px; padding:3px 8px;">None</span>
                                @endif
                            </td>
                        </tr>

                        {{-- Show documents inline if any --}}
                        @if($trainee->documents->count() > 0)
                            <tr style="background:#fafafa;">
                                <td colspan="7" style="padding:6px 16px 10px 52px; border-top:none;">
                                    <div class="d-flex flex-wrap" style="gap:6px;">
                                        @foreach($trainee->documents as $doc)
                                            <span class="badge" style="background:#f0e6c8; color:#7a5c00; border:1px solid #C9A84C; font-size:10.5px; padding:3px 8px; border-radius:4px;">
                                                <i class="fas fa-paperclip mr-1"></i>{{ $doc->document_type }}: {{ Str::limit($doc->original_name, 30) }}
                                            </span>
                                        @endforeach
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
@endsection
