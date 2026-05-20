@extends('layouts.admin')

@section('content')
<div class="card">
    <div class="card-header cosecsa-card-header">
        <i class="fas fa-envelope mr-2"></i> All Platform Messages
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-Message">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>From</th>
                        <th>To</th>
                        <th>Subject</th>
                        <th>Has Material</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($messages as $message)
                    <tr data-entry-id="{{ $message->id }}">
                        <td></td>
                        <td>{{ $message->sender?->name ?? '—' }}</td>
                        <td>{{ $message->receiver?->name ?? '—' }}</td>
                        <td>
                            {{ $message->subject }}
                            @if(!$message->read_at)
                                <span class="badge badge-warning ml-1" style="font-size:9px;">Unread</span>
                            @endif
                        </td>
                        <td>
                            @if($message->material_id)
                                <span class="badge badge-info" style="font-size:10px;"><i class="fas fa-paperclip mr-1"></i>Yes</span>
                            @else
                                <span style="color:#aaa; font-size:12px;">—</span>
                            @endif
                        </td>
                        <td>{{ $message->created_at->format('M j, Y g:i A') }}</td>
                        <td class="actions-cell">
                            <button type="button" class="btn btn-xs btn-secondary action-menu-btn">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="action-menu" style="display:none;">
                                <a href="{{ route('admin.messages.show', $message->id) }}">
                                    <i class="fas fa-eye text-primary"></i> View
                                </a>
                                <div class="menu-divider"></div>
                                <form action="{{ route('admin.messages.destroy', $message->id) }}" method="POST"
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
    $('.datatable-Message').DataTable({ buttons: dtButtons, order: [[5, 'desc']] });
});
</script>
@endsection
