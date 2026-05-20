@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header cosecsa-card-header">
        <i class="fas fa-comments mr-2"></i> Discussions
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-Discussion">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Session</th>
                        <th>Replies</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($discussions as $discussion)
                    <tr data-entry-id="{{ $discussion->id }}">
                        <td></td>
                        <td>{{ $discussion->title }}</td>
                        <td>{{ $discussion->user?->name ?? '—' }}</td>
                        <td>{{ $discussion->schedule?->title ?? ($discussion->is_general ? 'General' : '—') }}</td>
                        <td>{{ $discussion->replies_count ?? $discussion->replies->count() }}</td>
                        <td>{{ $discussion->created_at->format('M j, Y') }}</td>
                        <td class="actions-cell">
                            <button type="button" class="btn btn-xs btn-secondary action-menu-btn">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="action-menu" style="display:none;">
                                <a href="{{ route('admin.discussions.show', $discussion->id) }}">
                                    <i class="fas fa-eye text-primary"></i> View
                                </a>
                                <div class="menu-divider"></div>
                                <form action="{{ route('admin.discussions.destroy', $discussion->id) }}" method="POST"
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
    var dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons);
    $('.datatable-Discussion').DataTable({ buttons: dtButtons, order: [[5, 'desc']] });
});
</script>
@endsection
