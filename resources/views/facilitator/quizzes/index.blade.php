@extends('layouts.facilitator')

@section('page-title', 'Quizzes')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
        <i class="fas fa-question-circle mr-2" style="color:#C9A84C;"></i> Quizzes
    </h5>
    <a href="{{ route('facilitator.quizzes.create') }}" class="btn btn-sm" style="background:#C9A84C; color:#fff; font-weight:700; font-size:13px; border-radius:5px;">
        <i class="fas fa-plus mr-1"></i> Create Quiz
    </a>
</div>

@if($quizzes->isEmpty())
    <div class="alert alert-info">No quizzes yet. <a href="{{ route('facilitator.quizzes.create') }}" class="alert-link">Create the first quiz.</a></div>
@else
    @foreach($quizzes as $sessionId => $group)
    @php
        $sessionLabel = $sessionId === 'general' ? 'General / Not Linked to a Session' : optional($group->first()->schedule)->title;
    @endphp
    <div class="mb-4">
        <div style="font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; color:#aaa; margin-bottom:8px;">
            <i class="fas fa-calendar-alt mr-1" style="color:#C9A84C;"></i> {{ $sessionLabel }}
        </div>
        <div class="row">
            @foreach($group as $quiz)
            <div class="col-md-6 col-lg-4 mb-3">
                <div class="card shadow-sm h-100" style="border-radius:8px; border-left:4px solid {{ $quiz->is_published ? '#2c7a4b' : '#aaa' }};">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-0" style="font-weight:700; color:#2d3748;">{{ $quiz->title }}</h6>
                            @if($quiz->is_published)
                                <span class="badge badge-success" style="font-size:10px;">Published</span>
                            @else
                                <span class="badge badge-secondary" style="font-size:10px;">Draft</span>
                            @endif
                        </div>
                        @if($quiz->description)
                        <p class="text-muted mb-2" style="font-size:12px;">{{ Str::limit($quiz->description, 80) }}</p>
                        @endif
                        <div class="d-flex" style="gap:12px; font-size:11px; color:#888; margin-bottom:12px;">
                            <span><i class="fas fa-list-ul mr-1" style="color:#C9A84C;"></i>{{ $quiz->questions_count }} questions</span>
                            <span><i class="fas fa-users mr-1" style="color:#2c7a4b;"></i>{{ $quiz->attempts_count }} attempts</span>
                            @if($quiz->time_limit)
                            <span><i class="fas fa-clock mr-1"></i>{{ $quiz->time_limit }}min</span>
                            @endif
                        </div>
                        <div class="d-flex flex-wrap" style="gap:4px;">
                            <a href="{{ route('facilitator.quizzes.results', $quiz) }}" class="btn btn-sm" style="background:#f8f9fa; color:#2d3748; border:1px solid #dee2e6; font-size:11px;">
                                <i class="fas fa-chart-bar mr-1"></i> Results
                            </a>
                            <a href="{{ route('facilitator.quizzes.edit', $quiz) }}" class="btn btn-sm" style="background:#fff8e6; color:#9a7d2c; border:1px solid #C9A84C55; font-size:11px;">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            <form action="{{ route('facilitator.quizzes.toggle-publish', $quiz) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm" style="background:{{ $quiz->is_published ? '#fff5f5' : '#e8f5e9' }}; color:{{ $quiz->is_published ? '#e53e3e' : '#2c7a4b' }}; border:1px solid {{ $quiz->is_published ? '#fed7d7' : '#c8e6c9' }}; font-size:11px;">
                                    <i class="fas fa-{{ $quiz->is_published ? 'eye-slash' : 'eye' }} mr-1"></i>
                                    {{ $quiz->is_published ? 'Unpublish' : 'Publish' }}
                                </button>
                            </form>
                            <form action="{{ route('facilitator.quizzes.destroy', $quiz) }}" method="POST" style="display:inline;"
                                  onsubmit="return confirm('Delete quiz: {{ addslashes($quiz->title) }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#fff5f5; color:#e53e3e; border:1px solid #fed7d7; font-size:11px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
@endif
@endsection
