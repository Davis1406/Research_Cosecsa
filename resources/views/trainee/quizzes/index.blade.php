@extends('layouts.trainee')

@section('page-title', 'Quizzes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#252525;">
        <i class="fas fa-question-circle mr-2" style="color:#C9A84C;"></i> Quizzes
    </h5>
    <span class="badge" style="background:#252525; color:#C9A84C; font-size:12px; padding:6px 12px;">
        {{ $quizzes->count() }} quiz{{ $quizzes->count() === 1 ? '' : 'zes' }}
    </span>
</div>

@if($quizzes->isEmpty())
    <div class="empty-state">
        <i class="fas fa-question-circle empty-state-icon"></i>
        <p class="empty-state-text">No quizzes are available yet.</p>
    </div>
@else
    <div class="card shadow-sm mb-4" style="border-radius:8px; overflow:hidden;">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead style="background:#f8f9fa;">
                    <tr>
                        <th style="font-size:12px; font-weight:700; color:#555; border-top:none; padding:10px 16px;">Quiz</th>
                        <th style="font-size:12px; font-weight:700; color:#555; border-top:none;">Questions</th>
                        <th style="font-size:12px; font-weight:700; color:#555; border-top:none;">Pass Mark</th>
                        <th style="font-size:12px; font-weight:700; color:#555; border-top:none;">Your Best Score</th>
                        <th style="font-size:12px; font-weight:700; color:#555; border-top:none; text-align:right; padding-right:16px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quizzes as $quiz)
                    @php $attempt = $bestAttempts->get($quiz->id); @endphp
                    <tr>
                        <td style="padding:10px 16px; vertical-align:middle;">
                            <div style="font-weight:600; font-size:13.5px; color:#252525;">{{ $quiz->title }}</div>
                            @if($quiz->description)
                                <div style="font-size:11.5px; color:#888; margin-top:2px;">{{ Str::limit($quiz->description, 80) }}</div>
                            @endif
                        </td>
                        <td style="vertical-align:middle; font-size:13px; color:#555;">{{ $quiz->questions_count }}</td>
                        <td style="vertical-align:middle; font-size:13px; color:#555;">{{ $quiz->pass_score }}%</td>
                        <td style="vertical-align:middle;">
                            @if($attempt)
                                <span class="badge {{ $attempt->passed ? 'badge-status-success' : 'badge-status-danger' }}">
                                    {{ $attempt->score }}% — {{ $attempt->passed ? 'Passed' : 'Not passed' }}
                                </span>
                            @else
                                <span style="color:#aaa; font-size:12px;">Not attempted</span>
                            @endif
                        </td>
                        <td style="vertical-align:middle; text-align:right; padding-right:16px;">
                            @if($attempt && $attempt->passed)
                                <a href="{{ route('trainee.quizzes.result', $quiz) }}" class="btn btn-sm btn-secondary" style="font-size:12px; padding:4px 12px;">
                                    <i class="fas fa-eye mr-1"></i> View Result
                                </a>
                            @else
                                <a href="{{ route('trainee.quizzes.show', $quiz) }}"
                                   class="btn btn-sm"
                                   style="background:#252525; color:#C9A84C; font-size:12px; border:1px solid #C9A84C; padding:4px 12px; border-radius:4px;">
                                    <i class="fas fa-play mr-1"></i> {{ $attempt ? 'Try Again' : 'Start' }}
                                </a>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
