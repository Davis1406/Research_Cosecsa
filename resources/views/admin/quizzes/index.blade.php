@extends('layouts.admin')

@section('content')
<div class="row mb-2">
    <div class="col-lg-12">
        <a class="btn btn-cosecsa" href="{{ route('admin.quizzes.create') }}">
            <i class="fas fa-plus mr-1"></i> Create Quiz
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header cosecsa-card-header">
        <i class="fas fa-question-circle mr-2"></i> Quizzes
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-Quiz">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>Title</th>
                        <th>Session</th>
                        <th>Questions</th>
                        <th>Attempts</th>
                        <th>Status</th>
                        <th>Created By</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($quizzes as $quiz)
                    <tr data-entry-id="{{ $quiz->id }}">
                        <td></td>
                        <td>{{ $quiz->title }}</td>
                        <td>{{ $quiz->schedule?->title ?? '—' }}</td>
                        <td>{{ $quiz->questions_count ?? $quiz->questions->count() }}</td>
                        <td>{{ $quiz->attempts_count ?? $quiz->attempts->count() }}</td>
                        <td>
                            @if($quiz->is_published)
                                <span class="badge badge-success">Published</span>
                            @else
                                <span class="badge badge-secondary">Draft</span>
                            @endif
                        </td>
                        <td>{{ $quiz->creator?->name ?? '—' }}</td>
                        <td class="actions-cell">
                            <button type="button" class="btn btn-xs btn-secondary action-menu-btn">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="action-menu" style="display:none;">
                                <a href="{{ route('admin.quizzes.show', $quiz->id) }}">
                                    <i class="fas fa-eye text-primary"></i> View / Results
                                </a>
                                <a href="{{ route('admin.quizzes.edit', $quiz->id) }}">
                                    <i class="fas fa-pencil-alt text-info"></i> Edit
                                </a>
                                <form action="{{ route('admin.quizzes.toggle-publish', $quiz->id) }}" method="POST" style="margin:0;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="{{ $quiz->is_published ? 'text-warning' : 'text-success' }}">
                                        <i class="fas fa-{{ $quiz->is_published ? 'eye-slash' : 'globe' }}"></i>
                                        {{ $quiz->is_published ? 'Unpublish' : 'Publish' }}
                                    </button>
                                </form>
                                <div class="menu-divider"></div>
                                <form action="{{ route('admin.quizzes.destroy', $quiz->id) }}" method="POST"
                                      onsubmit="return confirm('Are you sure?');" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@parent
<script>
$(function () {
    var extraBtns = [];
    window.dtInit('.datatable-Quiz', extraBtns, [[1, 'asc']]);
});
</script>
@endsection
