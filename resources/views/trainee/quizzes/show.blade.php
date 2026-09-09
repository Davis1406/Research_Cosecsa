@extends('layouts.trainee')

@section('page-title', 'Take Quiz')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#252525;">
        <i class="fas fa-question-circle mr-2" style="color:#C9A84C;"></i> {{ $quiz->title }}
    </h5>
    <a href="{{ route('trainee.quizzes.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:13px;">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
</div>

@if($quiz->description)
    <div class="alert alert-info mb-3">{{ $quiz->description }}</div>
@endif

<form action="{{ route('trainee.quizzes.submit', $quiz) }}" method="POST">
    @csrf
    @foreach($quiz->questions as $i => $question)
    <div class="card shadow-sm mb-3" style="border-radius:8px; border-left:3px solid #C9A84C;">
        <div class="card-body">
            <div style="font-weight:700; font-size:14px; color:#252525; margin-bottom:12px;">
                {{ $i + 1 }}. {{ $question->question_text }}
                <span style="font-weight:400; font-size:11px; color:#999;">({{ $question->points }} pt{{ $question->points == 1 ? '' : 's' }})</span>
            </div>

            @if(in_array($question->type, ['multiple_choice', 'true_false']))
                @foreach($question->options as $option)
                <div class="form-check mb-2">
                    <input class="form-check-input" type="radio"
                           name="answers[{{ $question->id }}]" id="opt-{{ $option->id }}"
                           value="{{ $option->id }}" required>
                    <label class="form-check-label" for="opt-{{ $option->id }}" style="font-size:13.5px;">
                        {{ $option->option_text }}
                    </label>
                </div>
                @endforeach
            @else
                <textarea name="answers[{{ $question->id }}]" class="form-control" rows="3" placeholder="Type your answer..." required></textarea>
            @endif
        </div>
    </div>
    @endforeach

    <button type="submit" class="btn"
            style="background:#252525; color:#C9A84C; border:1px solid #C9A84C; font-weight:700; padding:10px 24px; border-radius:6px;">
        <i class="fas fa-paper-plane mr-1"></i> Submit Quiz
    </button>
</form>
@endsection
