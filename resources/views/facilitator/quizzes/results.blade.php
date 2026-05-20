@extends('layouts.facilitator')

@section('page-title', 'Results: ' . $quiz->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
        <i class="fas fa-chart-bar mr-2" style="color:#C9A84C;"></i> Results: {{ $quiz->title }}
    </h5>
    <a href="{{ route('facilitator.quizzes.show', $quiz) }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:12px;">
        <i class="fas fa-arrow-left mr-1"></i> Back to Quiz
    </a>
</div>

@if($attempts->isEmpty())
    <div class="alert alert-info">No attempts yet for this quiz.</div>
@else

@php
    $passCount = $attempts->where('passed', true)->count();
    $failCount = $attempts->where('passed', false)->count();
    $avgScore  = $attempts->whereNotNull('score')->avg('score');
@endphp

<div class="row mb-3">
    <div class="col-sm-4 mb-2">
        <div class="card shadow-sm text-center" style="border-radius:8px; border-top:3px solid #C9A84C;">
            <div class="card-body py-3">
                <div style="font-size:24px; font-weight:700; color:#C9A84C;">{{ $attempts->count() }}</div>
                <div style="font-size:11px; color:#888; font-weight:700; text-transform:uppercase;">Total Attempts</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4 mb-2">
        <div class="card shadow-sm text-center" style="border-radius:8px; border-top:3px solid #2c7a4b;">
            <div class="card-body py-3">
                <div style="font-size:24px; font-weight:700; color:#2c7a4b;">{{ $passCount }}</div>
                <div style="font-size:11px; color:#888; font-weight:700; text-transform:uppercase;">Passed</div>
            </div>
        </div>
    </div>
    <div class="col-sm-4 mb-2">
        <div class="card shadow-sm text-center" style="border-radius:8px; border-top:3px solid #2d3748;">
            <div class="card-body py-3">
                <div style="font-size:24px; font-weight:700; color:#2d3748;">{{ $avgScore !== null ? round($avgScore) . '%' : '—' }}</div>
                <div style="font-size:11px; color:#888; font-weight:700; text-transform:uppercase;">Avg Score</div>
            </div>
        </div>
    </div>
</div>

@foreach($attempts as $attempt)
<div class="card shadow-sm mb-3" style="border-radius:8px; border-left:4px solid {{ $attempt->passed ? '#2c7a4b' : '#e53e3e' }};">
    <div class="card-header d-flex justify-content-between align-items-center" style="background:#f8f9fa;">
        <div>
            <div style="width:32px;height:32px;border-radius:50%;background:#C9A84C;display:inline-flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;margin-right:8px;">
                {{ strtoupper(substr($attempt->user?->name ?? 'U', 0, 1)) }}
            </div>
            <span style="font-weight:700; font-size:13px; color:#2d3748;">{{ $attempt->user?->name ?? 'Unknown' }}</span>
        </div>
        <div style="display:flex; align-items:center; gap:12px;">
            <span style="font-size:13px; font-weight:700; color:{{ $attempt->passed ? '#2c7a4b' : '#e53e3e' }};">
                {{ $attempt->score !== null ? $attempt->score . '%' : '—' }}
            </span>
            @if($attempt->passed === true)
                <span class="badge badge-success">Passed</span>
            @elseif($attempt->passed === false)
                <span class="badge badge-danger">Failed</span>
            @else
                <span class="badge badge-secondary">Incomplete</span>
            @endif
            <span style="font-size:11px; color:#aaa;">{{ $attempt->completed_at ? $attempt->completed_at->format('M j, Y g:i A') : 'Not completed' }}</span>
        </div>
    </div>
    @if($attempt->answers->count() > 0)
    <div class="card-body" style="padding:0;">
        @foreach($attempt->answers as $ans)
        @php $q = $ans->question; @endphp
        <div style="padding:10px 16px; border-bottom:1px solid #f8f9fa; display:flex; align-items:flex-start; gap:10px;">
            <div style="flex-shrink:0; margin-top:2px;">
                @if($ans->is_correct === true)
                    <i class="fas fa-check-circle" style="color:#2c7a4b;"></i>
                @elseif($ans->is_correct === false)
                    <i class="fas fa-times-circle" style="color:#e53e3e;"></i>
                @else
                    <i class="fas fa-minus-circle" style="color:#aaa;"></i>
                @endif
            </div>
            <div>
                <div style="font-size:12px; font-weight:600; color:#2d3748;">{{ $q?->question_text }}</div>
                @if($ans->selectedOption)
                    <div style="font-size:11px; color:#555; margin-top:2px;">Answer: <strong>{{ $ans->selectedOption->option_text }}</strong></div>
                @elseif($ans->text_answer)
                    <div style="font-size:11px; color:#555; margin-top:2px;">Answer: <em>{{ $ans->text_answer }}</em></div>
                @else
                    <div style="font-size:11px; color:#aaa; margin-top:2px;">No answer given</div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endforeach
@endif
@endsection
