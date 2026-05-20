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
                        <th style="font-size:11px; font-weight:700; color:#555; border-top:none; padding:12px 16px; text-transform:uppercase; letter-spacing:0.4px; width:30px;"></th>
                        <th style="font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px;">Name</th>
                        <th class="d-none d-md-table-cell" style="font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px;">Institution</th>
                        <th class="d-none d-lg-table-cell" style="font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px;">Country</th>
                        <th class="d-none d-lg-table-cell" style="font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px;">Specialty</th>
                        <th class="d-none d-md-table-cell" style="font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px;">Reg. No.</th>
                        <th style="font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px; text-align:center;">Presentations</th>
                        <th style="font-size:11px; font-weight:700; color:#555; border-top:none; text-transform:uppercase; letter-spacing:0.4px; text-align:right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($traineeList as $i => $trainee)
                    @php
                        $presentations = $trainee->documents->where('document_type', 'Presentation');
                        $otherDocs = $trainee->documents->where('document_type', '!=', 'Presentation');
                        $hasPresentations = $presentations->isNotEmpty();
                        $expandId = 'expand-' . $trainee->id;
                    @endphp
                        <tr style="cursor:{{ $hasPresentations ? 'pointer' : 'default' }};"
                            @if($hasPresentations) onclick="toggleTraineeDetail('{{ $expandId }}')" @endif>
                            <td style="padding:10px 16px; vertical-align:middle; text-align:center; width:30px;">
                                @if($hasPresentations)
                                <i class="fas fa-chevron-right" id="chevron-{{ $trainee->id }}"
                                   style="font-size:10px; color:#C9A84C; transition:transform 0.2s;"></i>
                                @endif
                            </td>
                            <td style="vertical-align:middle;">
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
                            <td class="d-none d-md-table-cell" style="vertical-align:middle; font-size:13px; color:#555;">{{ $trainee->institution ?: '—' }}</td>
                            <td class="d-none d-lg-table-cell" style="vertical-align:middle; font-size:13px; color:#555;">{{ $trainee->country ?: '—' }}</td>
                            <td class="d-none d-lg-table-cell" style="vertical-align:middle; font-size:13px; color:#555;">{{ $trainee->specialty ?: '—' }}</td>
                            <td class="d-none d-md-table-cell" style="vertical-align:middle; font-size:13px; color:#555;">{{ $trainee->registration_number ?: '—' }}</td>
                            <td style="vertical-align:middle; text-align:center;">
                                @if($hasPresentations)
                                    <span class="badge" style="background:rgba(201,168,76,0.15); color:#9a7d2c; border:1px solid #C9A84C55; font-size:11px; padding:3px 8px;">
                                        <i class="fas fa-file-powerpoint mr-1"></i>{{ $presentations->count() }}
                                    </span>
                                @else
                                    <span style="font-size:12px; color:#ccc;">—</span>
                                @endif
                            </td>
                            <td style="vertical-align:middle; text-align:right; padding-right:16px;" onclick="event.stopPropagation()">
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

                        {{-- Expandable detail row --}}
                        <tr id="{{ $expandId }}" style="display:none; background:#fafcff;">
                            <td colspan="8" style="padding:0; border-top:none;">
                                <div style="padding:14px 20px 14px 76px; border-left:3px solid #C9A84C; margin-left:0;">

                                    {{-- Trainee extra details --}}
                                    <div class="row mb-3" style="font-size:12px; color:#555;">
                                        @if($trainee->phone)
                                        <div class="col-sm-4 mb-1">
                                            <i class="fas fa-phone mr-1" style="color:#C9A84C; width:14px;"></i>
                                            {{ $trainee->phone }}
                                        </div>
                                        @endif
                                        @if($trainee->enrollment_date)
                                        <div class="col-sm-4 mb-1">
                                            <i class="fas fa-calendar mr-1" style="color:#C9A84C; width:14px;"></i>
                                            Enrolled: {{ \Carbon\Carbon::parse($trainee->enrollment_date)->format('M j, Y') }}
                                        </div>
                                        @endif
                                        @if($trainee->notes)
                                        <div class="col-sm-12 mb-1">
                                            <i class="fas fa-sticky-note mr-1" style="color:#C9A84C; width:14px;"></i>
                                            {{ $trainee->notes }}
                                        </div>
                                        @endif
                                    </div>

                                    {{-- Presentations with assigned reviewers --}}
                                    @if($hasPresentations)
                                    <div style="margin-bottom:8px;">
                                        <span style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:#aaa;">
                                            <i class="fas fa-file-powerpoint mr-1" style="color:#C9A84C;"></i> Presentations
                                        </span>
                                    </div>
                                    <div class="row">
                                        @foreach($presentations as $pres)
                                        @php
                                            $ext = strtolower(pathinfo($pres->original_name, PATHINFO_EXTENSION));
                                            $commentCount = $pres->comments ? $pres->comments->count() : 0;
                                        @endphp
                                        <div class="col-md-6 mb-2">
                                            <div style="background:#fff; border:1px solid #e9ecef; border-radius:8px; padding:12px 14px; border-left:3px solid #C9A84C;">
                                                <div style="font-weight:700; font-size:12.5px; color:#2d3748; margin-bottom:6px;">
                                                    <i class="fas fa-file-powerpoint mr-1" style="color:#C9A84C;"></i>
                                                    {{ $pres->title ?: $pres->original_name }}
                                                </div>
                                                <div style="font-size:11px; color:#aaa; margin-bottom:8px;">
                                                    Uploaded {{ $pres->created_at->format('M j, Y') }}
                                                    &bull;
                                                    <a href="{{ route('facilitator.presentations.view', $pres->id) }}"
                                                       style="color:#C9A84C; font-weight:600; text-decoration:none;"
                                                       onclick="event.stopPropagation()">
                                                        <i class="fas fa-eye mr-1"></i>Review
                                                    </a>
                                                </div>

                                                {{-- Assigned reviewers --}}
                                                @if($pres->reviewers && $pres->reviewers->isNotEmpty())
                                                <div style="display:flex; flex-wrap:wrap; gap:4px; align-items:center;">
                                                    <span style="font-size:10px; color:#888;"><i class="fas fa-user-check mr-1" style="color:#2c7a4b;"></i>Reviewers:</span>
                                                    @foreach($pres->reviewers as $rv)
                                                    <span style="font-size:10.5px; font-weight:600; background:rgba(44,122,75,0.1); color:#2c7a4b; border-radius:10px; padding:1px 8px;">
                                                        {{ $rv->name }}
                                                    </span>
                                                    @endforeach
                                                    @if($commentCount > 0)
                                                    <span class="badge ml-1" style="background:#d4edda; color:#155724; border:1px solid #c3e6cb; font-size:10px; padding:2px 6px;">
                                                        <i class="fas fa-comment-alt mr-1"></i>{{ $commentCount }}
                                                    </span>
                                                    @endif
                                                </div>
                                                @else
                                                <div style="font-size:10.5px; color:#bbb; font-style:italic;">
                                                    <i class="fas fa-user-slash mr-1"></i> No reviewers assigned yet
                                                    @if($commentCount > 0)
                                                    <span class="badge ml-1" style="background:#d4edda; color:#155724; border:1px solid #c3e6cb; font-size:10px; padding:2px 6px;">
                                                        <i class="fas fa-comment-alt mr-1"></i>{{ $commentCount }}
                                                    </span>
                                                    @endif
                                                </div>
                                                @endif
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif

                                    {{-- Other documents --}}
                                    @if($otherDocs->isNotEmpty())
                                    <div style="margin-top:8px; margin-bottom:4px;">
                                        <span style="font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; color:#aaa;">
                                            <i class="fas fa-paperclip mr-1"></i> Other Documents
                                        </span>
                                    </div>
                                    <div class="d-flex flex-wrap" style="gap:5px;">
                                        @foreach($otherDocs as $doc)
                                        <span class="badge" style="background:#fff8e6; color:#7a5c00; border:1px solid #C9A84C44; font-size:10.5px; padding:3px 8px; border-radius:4px;">
                                            <i class="fas fa-file mr-1"></i>{{ $doc->document_type }}: {{ Str::limit($doc->original_name, 28) }}
                                        </span>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection

@section('scripts')
<script>
function toggleTraineeDetail(id) {
    var row = document.getElementById(id);
    var traineeId = id.replace('expand-', '');
    var chevron = document.getElementById('chevron-' + traineeId);
    if (!row) return;
    var isOpen = row.style.display !== 'none';
    row.style.display = isOpen ? 'none' : 'table-row';
    if (chevron) {
        chevron.style.transform = isOpen ? 'rotate(0deg)' : 'rotate(90deg)';
    }
}
</script>
@endsection
