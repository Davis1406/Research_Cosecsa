@extends('layouts.trainee')

@section('page-title', 'Quiz Result')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#252525;">
        <i class="fas fa-question-circle mr-2" style="color:#C9A84C;"></i> {{ $quiz->title }} — Result
    </h5>
    <a href="{{ route('trainee.quizzes.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:13px;">
        <i class="fas fa-arrow-left mr-1"></i> Back to Quizzes
    </a>
</div>

<div class="card shadow-sm mb-4" style="border-radius:8px; border-top:4px solid {{ $attempt->passed ? '#28a745' : '#dc3545' }};">
    <div class="card-body text-center py-4">
        <div style="font-size:42px; font-weight:800; color:{{ $attempt->passed ? '#28a745' : '#dc3545' }};">
            {{ $attempt->score }}%
        </div>
        <div style="font-size:14px; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:0.5px;">
            {{ $attempt->passed ? 'Passed' : 'Not Passed' }} · Pass mark {{ $quiz->pass_score }}%
        </div>
        @if(!$attempt->passed)
        <a href="{{ route('trainee.quizzes.show', $quiz) }}" class="btn btn-sm mt-3"
           style="background:#252525; color:#C9A84C; border:1px solid #C9A84C;">
            <i class="fas fa-redo mr-1"></i> Try Again
        </a>
        @endif
    </div>
</div>

@foreach($attempt->answers as $answer)
<div class="card shadow-sm mb-2" style="border-radius:8px;">
    <div class="card-body py-3">
        <div style="font-weight:600; font-size:13.5px; color:#252525;">{{ $answer->question->question_text }}</div>
        <div style="font-size:13px; margin-top:6px;">
            @if($answer->selectedOption)
                Your answer: <strong>{{ $answer->selectedOption->option_text }}</strong>
            @elseif($answer->text_answer)
                Your answer: <em>{{ $answer->text_answer }}</em>
            @else
                <span class="text-muted">No answer given</span>
            @endif
            <span class="badge ml-2 {{ $answer->is_correct ? 'badge-status-success' : 'badge-status-danger' }}">
                {{ $answer->is_correct ? 'Correct' : 'Incorrect' }}
            </span>
        </div>
    </div>
</div>
@endforeach
@endsection
