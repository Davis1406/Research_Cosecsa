@extends('layouts.admin')

@section('content')
@can('trainee_create')
<div class="row mb-2">
    <div class="col-lg-12">
        <a class="btn btn-cosecsa" href="{{ route('admin.trainees.create') }}">
            <i class="fas fa-user-plus mr-1"></i> {{ trans('global.add') }} {{ trans('cruds.trainee.title_singular') }}
        </a>
    </div>
</div>
@endcan

<div class="card">
    <div class="card-header cosecsa-card-header">
        <i class="fas fa-users mr-2"></i> {{ trans('cruds.trainee.title') }} {{ trans('global.list') }}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-Trainee">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('cruds.trainee.fields.id') }}</th>
                        <th>{{ trans('cruds.trainee.fields.name') }}</th>
                        <th>{{ trans('cruds.trainee.fields.email') }}</th>
                        <th>{{ trans('cruds.trainee.fields.institution') }}</th>
                        <th>{{ trans('cruds.trainee.fields.specialty') }}</th>
                        <th>{{ trans('cruds.trainee.fields.country') }}</th>
                        <th>{{ trans('cruds.trainee.fields.enrollment_date') }}</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trainees as $trainee)
                    <tr data-entry-id="{{ $trainee->id }}">
                        <td></td>
                        <td>{{ $trainee->id }}</td>
                        <td>{{ $trainee->name ?? '' }}</td>
                        <td>{{ $trainee->email ?? '' }}</td>
                        <td>{{ $trainee->institution ?? '' }}</td>
                        <td>{{ $trainee->specialty ?? '' }}</td>
                        <td>{{ $trainee->country ?? '' }}</td>
                        <td>{{ $trainee->enrollment_date ? $trainee->enrollment_date->format('Y-m-d') : '' }}</td>
                        <td class="actions-cell">
                            <button type="button" class="btn btn-xs btn-secondary action-menu-btn">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="action-menu" style="display:none;">
                                @can('trainee_show')
                                <a href="{{ route('admin.trainees.show', $trainee->id) }}">
                                    <i class="fas fa-eye text-primary"></i> View
                                </a>
                                @endcan
                                @can('trainee_edit')
                                <a href="{{ route('admin.trainees.edit', $trainee->id) }}">
                                    <i class="fas fa-pencil-alt text-info"></i> Edit
                                </a>
                                @endcan
                                @can('trainee_delete')
                                <div class="menu-divider"></div>
                                <form action="{{ route('admin.trainees.destroy', $trainee->id) }}" method="POST"
                                      onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-danger">
                                        <i class="fas fa-trash"></i> Delete
                                    </button>
                                </form>
                                @endcan
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
    @can('trainee_delete')
    dtButtons.push({
        text: '{{ trans('global.datatables.delete') }}',
        url: '{{ route('admin.trainees.massDestroy') }}',
        className: 'btn-danger',
        action: function (e, dt, node, config) {
            var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
                return $(entry).data('entry-id');
            });
            if (!ids.length) { alert('{{ trans('global.datatables.zero_selected') }}'); return; }
            if (confirm('{{ trans('global.areYouSure') }}')) {
                $.ajax({ headers: { 'x-csrf-token': _token }, method: 'POST', url: config.url,
                    data: { ids: ids, _method: 'DELETE' }}).done(function () { location.reload(); });
            }
        }
    });
    @endcan
    $('.datatable-Trainee').DataTable({ buttons: dtButtons, order: [[1, 'asc']] });
});
</script>
@endsection
