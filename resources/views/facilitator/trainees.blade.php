@extends('layouts.facilitator')

@section('page-title', 'Trainees')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
        <i class="fas fa-user-graduate mr-2" style="color:#C9A84C;"></i> Enrolled Trainees
    </h5>
    <div class="d-flex align-items-center" style="gap:8px;">
        <span class="badge" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:12px; padding:5px 12px;">
            {{ $traineeList->count() }} trainees
        </span>
        <a href="{{ route('facilitator.trainees.create') }}"
           class="btn btn-sm" style="background:#C9A84C; color:#fff; font-weight:700; font-size:13px; border-radius:5px;">
            <i class="fas fa-plus mr-1"></i> Add Trainee
        </a>
    </div>
</div>

@if($traineeList->isEmpty())
    <div class="alert alert-info">
        No trainees are enrolled yet.
        <a href="{{ route('facilitator.trainees.create') }}" class="alert-link">Add the first trainee.</a>
    </div>
@else
    <div class="card shadow-sm" style="border-radius:8px; overflow:hidden;">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#f8f9fa; border-bottom:2px solid #C9A84C;">
                    <tr>
                        <th style="font-size:11px; font-weight:700; color:#555; border-top:none; padding:12px 16px; text-transform:uppercase; letter-spacing:0.4px;">#</th>
                        <th style="font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px;">Name</th>
                        <th style="font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px;">Institution</th>
                        <th style="font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px;">Country</th>
                        <th style="font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px;">Specialty</th>
                        <th style="font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px;">Reg. No.</th>
                        <th style="font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px; text-align:center;">Docs</th>
                        <th style="font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px; text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($traineeList as $i => $trainee)
                        <tr>
                            <td style="padding:10px 16px; vertical-align:middle; color:#aaa; font-size:12px;">{{ $i + 1 }}</td>
                            <td style="vertical-align:middle;">
                                {{-- Avatar initials --}}
                                <div class="d-flex align-items-center" style="gap:10px;">
                                    <div style="width:34px;height:34px;border-radius:50%;background:#C9A84C;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0;">
                                        {{ strtoupper(substr($trainee->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div style="font-weight:700; font-size:13.5px; color:#2d3748;">{{ $trainee->name }}</div>
                                        <div style="font-size:11.5px; color:#888;">{{ $trainee->email }}</div>
                                    </div>
                                </div>
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
                                    <span style="font-size:12px; color:#ccc;">—</span>
                                @endif
                            </td>
                            <td style="vertical-align:middle; text-align:right; padding-right:16px;">
                                <a href="{{ route('facilitator.trainees.edit', $trainee->id) }}"
                                   class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:12px; padding:3px 10px; margin-right:4px;">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('facilitator.trainees.destroy', $trainee->id) }}" method="POST" style="display:inline;"
                                      onsubmit="return confirm('Remove {{ addslashes($trainee->name) }} and their login account?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm" style="background:#fff5f5; color:#e53e3e; border:1px solid #fed7d7; font-size:12px; padding:3px 10px;">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>

                        {{-- Documents row --}}
                        @if($trainee->documents->count() > 0)
                            <tr style="background:#fafafa;">
                                <td colspan="8" style="padding:4px 16px 8px 76px; border-top:none;">
                                    <div class="d-flex flex-wrap" style="gap:5px;">
                                        @foreach($trainee->documents as $doc)
                                            <span class="badge" style="background:#fff8e6; color:#7a5c00; border:1px solid #C9A84C; font-size:10.5px; padding:3px 8px; border-radius:4px;">
                                                <i class="fas fa-paperclip mr-1"></i>{{ $doc->document_type }}: {{ Str::limit($doc->original_name, 28) }}
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
