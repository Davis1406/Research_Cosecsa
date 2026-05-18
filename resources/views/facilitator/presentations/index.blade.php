@extends('layouts.facilitator')

@section('page-title', 'Trainee Presentations')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
        <i class="fas fa-file-powerpoint mr-2" style="color:#C9A84C;"></i> Trainee Presentations
    </h5>
    <span class="badge" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:12px; padding:5px 12px;">
        {{ $trainees->count() }} trainee{{ $trainees->count()!==1?'s':'' }} with submissions
    </span>
</div>

@if($trainees->isEmpty())
    <div class="card shadow-sm" style="border-radius:8px;">
        <div class="card-body text-center py-5">
            <i class="fas fa-file-powerpoint fa-3x mb-3" style="color:#ddd;"></i>
            <h5 style="color:#888; font-weight:600; font-size:15px;">No presentations uploaded yet</h5>
            <p style="color:#aaa; font-size:13px;">Trainees will upload their presentations here. You can review and comment on each one.</p>
        </div>
    </div>
@else
    @foreach($trainees as $trainee)
    <div class="card shadow-sm mb-4" style="border-radius:10px; overflow:hidden; border:1px solid #e9ecef;">
        <div class="card-header d-flex align-items-center" style="background:#f8f9fa; border-bottom:2px solid #C9A84C; padding:14px 20px;">
            {{-- Trainee avatar + name --}}
            <div style="width:36px;height:36px;border-radius:50%;background:#C9A84C;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;color:#fff;flex-shrink:0;margin-right:12px;">
                {{ strtoupper(substr($trainee->name, 0, 1)) }}
            </div>
            <div>
                <div style="font-weight:700; font-size:14px; color:#2d3748;">{{ $trainee->name }}</div>
                <div style="font-size:12px; color:#888;">
                    {{ $trainee->institution ?: 'No institution' }}
                    @if($trainee->specialty) &bull; {{ $trainee->specialty }} @endif
                </div>
            </div>
            <span class="ml-auto badge" style="background:#C9A84C22; color:#9a7d2c; border:1px solid #C9A84C55; font-size:11px; padding:4px 10px;">
                {{ $trainee->documents->count() }} presentation{{ $trainee->documents->count()!==1?'s':'' }}
            </span>
        </div>
        <div class="card-body" style="padding:16px 20px;">
            <div class="row">
                @foreach($trainee->documents as $doc)
                @php
                    $commentCount = $doc->comments->count();
                    $ext = strtolower(pathinfo($doc->original_name, PATHINFO_EXTENSION));
                    $docIcon = $ext === 'pdf' ? 'fa-file-pdf' : (in_array($ext,['pptx','ppt']) ? 'fa-file-powerpoint' : 'fa-file');
                    $docColor = $ext === 'pdf' ? '#e53e3e' : '#C9A84C';
                @endphp
                <div class="col-md-6 mb-3">
                    <div class="card h-100" style="border-radius:8px; border:1px solid #e9ecef; overflow:hidden;">
                        <div class="card-body" style="padding:14px;">
                            <div style="font-weight:700; font-size:13px; color:#2d3748; margin-bottom:4px;">
                                <i class="fas {{ $docIcon }} mr-1" style="color:{{ $docColor }};"></i>
                                {{ $doc->title ?: $doc->original_name }}
                            </div>
                            <div style="font-size:11px; color:#aaa; margin-bottom:10px;">
                                <span class="badge" style="background:{{ $docColor }}18; color:{{ $docColor }}; border:1px solid {{ $docColor }}44; font-size:9px; padding:1px 6px; text-transform:uppercase; margin-right:4px;">{{ strtoupper($ext) }}</span>
                                {{ $doc->original_name }} &bull; {{ $doc->created_at->format('M j, Y') }}
                            </div>

                            {{-- Comment count badge --}}
                            <div class="d-flex align-items-center" style="gap:8px; margin-bottom:12px;">
                                @if($commentCount > 0)
                                    <span class="badge" style="background:#d4edda; color:#155724; border:1px solid #c3e6cb; font-size:11px; padding:3px 8px;">
                                        <i class="fas fa-comment-alt mr-1"></i>{{ $commentCount }} comment{{ $commentCount!==1?'s':'' }}
                                    </span>
                                @else
                                    <span class="badge" style="background:#fff3cd; color:#856404; border:1px solid #ffc10744; font-size:11px; padding:3px 8px;">
                                        <i class="fas fa-comment-slash mr-1"></i>No feedback yet
                                    </span>
                                @endif
                            </div>

                            <a href="{{ route('facilitator.presentations.view', $doc->id) }}"
                               class="btn btn-sm btn-block" style="background:#C9A84C; color:#fff; font-weight:700; font-size:12px;">
                                <i class="fas fa-eye mr-1"></i> Review & Comment
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach
@endif
@endsection
