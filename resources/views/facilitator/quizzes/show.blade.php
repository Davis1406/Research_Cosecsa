@extends('layouts.facilitator')

@section('page-title', 'Quiz: ' . $quiz->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
        <i class="fas fa-question-circle mr-2" style="color:#C9A84C;"></i> {{ $quiz->title }}
    </h5>
    <div style="display:flex; gap:8px;">
        <a href="{{ route('facilitator.quizzes.results', $quiz) }}" class="btn btn-sm" style="background:#2c7a4b; color:#fff; font-size:12px; font-weight:700;">
            <i class="fas fa-chart-bar mr-1"></i> Results
        </a>
        <a href="{{ route('facilitator.quizzes.edit', $quiz) }}" class="btn btn-sm" style="background:#C9A84C; color:#fff; font-size:12px; font-weight:700;">
            <i class="fas fa-edit mr-1"></i> Edit
        </a>
        <a href="{{ route('facilitator.quizzes.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:12px;">
            <i class="fas fa-arrow-left mr-1"></i> Back
        </a>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-8">
        <div class="card shadow-sm" style="border-radius:8px; border-left:4px solid #C9A84C;">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4 mb-2">
                        <div style="font-size:10px; font-weight:700; text-transform:uppercase; color:#aaa; letter-spacing:0.5px;">Status</div>
                        @if($quiz->is_published)
                            <span class="badge badge-success">Published</span>
                        @else
                            <span class="badge badge-secondary">Draft</span>
                        @endif
                    </div>
                    <div class="col-sm-4 mb-2">
                        <div style="font-size:10px; font-weight:700; text-transform:uppercase; color:#aaa; letter-spacing:0.5px;">Session</div>
                        <div style="font-size:13px; color:#2d3748;">{{ $quiz->schedule?->title ?? 'General' }}</div>
                    </div>
                    <div class="col-sm-4 mb-2">
                        <div style="font-size:10px; font-weight:700; text-transform:uppercase; color:#aaa; letter-spacing:0.5px;">Pass Score</div>
                        <div style="font-size:13px; color:#2d3748;">{{ $quiz->pass_score }}%</div>
                    </div>
                    @if($quiz->time_limit)
                    <div class="col-sm-4 mb-2">
                        <div style="font-size:10px; font-weight:700; text-transform:uppercase; color:#aaa; letter-spacing:0.5px;">Time Limit</div>
                        <div style="font-size:13px; color:#2d3748;">{{ $quiz->time_limit }} minutes</div>
                    </div>
                    @endif
                    <div class="col-sm-4 mb-2">
                        <div style="font-size:10px; font-weight:700; text-transform:uppercase; color:#aaa; letter-spacing:0.5px;">Questions</div>
                        <div style="font-size:13px; color:#2d3748;">{{ $quiz->questions->count() }}</div>
                    </div>
                    <div class="col-sm-4 mb-2">
                        <div style="font-size:10px; font-weight:700; text-transform:uppercase; color:#aaa; letter-spacing:0.5px;">Total Attempts</div>
                        <div style="font-size:13px; color:#2d3748;">{{ $quiz->attempts->count() }}</div>
                    </div>
                </div>
                @if($quiz->description)
                <p class="mb-0 mt-2 text-muted" style="font-size:13px;">{{ $quiz->description }}</p>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card shadow-sm mb-3" style="border-radius:8px;">
    <div class="card-header" style="background:#fff; border-bottom:2px solid #C9A84C; font-weight:700; font-size:13px; color:#2d3748;">
        Questions ({{ $quiz->questions->count() }})
    </div>
    <div class="card-body" style="padding:0;">
        @foreach($quiz->questions as $qi => $q)
        <div style="padding:14px 16px; border-bottom:1px solid #f8f9fa;">
            <div class="d-flex justify-content-between align-items-start">
                <div style="flex:1;">
                    <span style="font-size:11px; font-weight:700; color:#C9A84C;">Q{{ $qi + 1 }}</span>
                    <span class="badge badge-light ml-1" style="font-size:10px;">{{ ucfirst(str_replace('_', ' ', $q->type)) }}</span>
                    <span class="badge ml-1" style="background:#f8f9fa; color:#555; font-size:10px;">{{ $q->points }} pt{{ $q->points != 1 ? 's' : '' }}</span>
                    <div style="font-size:13px; color:#2d3748; margin-top:4px;">{{ $q->question_text }}</div>
                    @if($q->options->count() > 0)
                    <ul class="mb-0 mt-2" style="font-size:12px; padding-left:18px;">
                        @foreach($q->options as $opt)
                        <li style="color: {{ $opt->is_correct ? '#2c7a4b' : '#666' }}; font-weight: {{ $opt->is_correct ? '700' : '400' }};">
                            {{ $opt->option_text }}
                            @if($opt->is_correct) <i class="fas fa-check ml-1"></i> @endif
                        </li>
                        @endforeach
                    </ul>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@if($quiz->attempts->count() > 0)
<div class="card shadow-sm" style="border-radius:8px;">
    <div class="card-header" style="background:#fff; border-bottom:2px solid #C9A84C; font-weight:700; font-size:13px; color:#2d3748;">
        Recent Attempts
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th style="font-size:11px; font-weight:700; color:#555; border-top:none;">Trainee</th>
                    <th style="font-size:11px; font-weight:700; color:#555; border-top:none;">Score</th>
                    <th style="font-size:11px; font-weight:700; color:#555; border-top:none;">Result</th>
                    <th style="font-size:11px; font-weight:700; color:#555; border-top:none;">Date</th>
                </tr>
            </thead>
            <tbody>
                @foreach($quiz->attempts->take(10) as $attempt)
                <tr>
                    <td style="font-size:13px;">{{ $attempt->user?->name ?? '—' }}</td>
                    <td style="font-size:13px;">{{ $attempt->score !== null ? $attempt->score . '%' : '—' }}</td>
                    <td>
                        @if($attempt->passed === true)
                            <span class="badge badge-success">Passed</span>
                        @elseif($attempt->passed === false)
                            <span class="badge badge-danger">Failed</span>
                        @else
                            <span class="badge badge-secondary">Pending</span>
                        @endif
                    </td>
                    <td style="font-size:12px; color:#888;">{{ $attempt->completed_at ? $attempt->completed_at->format('M j, Y') : '—' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
