@extends('layouts.admin')

@section('content')
@can('role_create')
<div class="row mb-2">
    <div class="col-lg-12">
        <a class="btn btn-cosecsa" href="{{ route('admin.roles.create') }}">
            <i class="fas fa-plus mr-1"></i> {{ trans('global.add') }} {{ trans('cruds.role.title_singular') }}
        </a>
    </div>
</div>
@endcan

<div class="card">
    <div class="card-header cosecsa-card-header">
        <i class="fas fa-briefcase mr-2"></i> {{ trans('cruds.role.title_singular') }} {{ trans('global.list') }}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-Role">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('cruds.role.fields.id') }}</th>
                        <th>{{ trans('cruds.role.fields.title') }}</th>
                        <th>{{ trans('cruds.role.fields.permissions') }}</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                    <tr data-entry-id="{{ $role->id }}">
                        <td></td>
                        <td>{{ $role->id }}</td>
                        <td>{{ $role->title ?? '' }}</td>
                        <td>
                            @foreach($role->permissions as $perm)
                                <span class="badge badge-info">{{ $perm->title }}</span>
                            @endforeach
                        </td>
                        <td class="actions-cell">
                            <button type="button" class="btn btn-xs btn-secondary action-menu-btn">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="action-menu" style="display:none;">
                                @can('role_show')
                                <a href="{{ route('admin.roles.show', $role->id) }}">
                                    <i class="fas fa-eye text-primary"></i> View
                                </a>
                                @endcan
                                @can('role_edit')
                                <a href="{{ route('admin.roles.edit', $role->id) }}">
                                    <i class="fas fa-pencil-alt text-info"></i> Edit
                                </a>
                                @endcan
                                @can('role_delete')
                                <div class="menu-divider"></div>
                                <form action="{{ route('admin.roles.destroy', $role->id) }}" method="POST"
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
    @can('role_delete')
    dtButtons.push({
        text: '{{ trans('global.datatables.delete') }}',
        url: '{{ route('admin.roles.massDestroy') }}',
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
    $('.datatable-Role').DataTable({ buttons: dtButtons, order: [[1, 'asc']] });
});
</script>
@endsection
