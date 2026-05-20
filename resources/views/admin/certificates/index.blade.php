@extends('layouts.admin')

@section('content')
<div class="row mb-2">
    <div class="col-lg-12">
        <a class="btn btn-cosecsa" href="{{ route('admin.certificates.create') }}">
            <i class="fas fa-certificate mr-1"></i> Issue Certificate
        </a>
    </div>
</div>

<div class="card">
    <div class="card-header cosecsa-card-header">
        <i class="fas fa-certificate mr-2"></i> Certificates
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-Certificate">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>Trainee</th>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Issued By</th>
                        <th>Generated At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($certificates as $cert)
                    <tr data-entry-id="{{ $cert->id }}">
                        <td></td>
                        <td>{{ $cert->trainee?->name ?? '—' }}</td>
                        <td>{{ $cert->event_name ?? '—' }}</td>
                        <td>{{ $cert->event_date ?? '—' }}</td>
                        <td>{{ $cert->issuedBy?->name ?? '—' }}</td>
                        <td>{{ $cert->created_at->format('M j, Y') }}</td>
                        <td class="actions-cell">
                            <button type="button" class="btn btn-xs btn-secondary action-menu-btn">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="action-menu" style="display:none;">
                                <a href="{{ route('admin.certificates.preview', $cert->id) }}" target="_blank">
                                    <i class="fas fa-eye text-primary"></i> Preview
                                </a>
                                <div class="menu-divider"></div>
                                <form action="{{ route('admin.certificates.destroy', $cert->id) }}" method="POST"
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
    $('.datatable-Certificate').DataTable({ buttons: dtButtons, order: [[5, 'desc']] });
});
</script>
@endsection
