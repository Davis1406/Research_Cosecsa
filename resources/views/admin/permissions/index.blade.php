@extends('layouts.admin')

@section('content')
@can('permission_create')
<div class="row mb-2">
    <div class="col-lg-12">
        <a class="btn btn-cosecsa" href="{{ route('admin.permissions.create') }}">
            <i class="fas fa-plus mr-1"></i> {{ trans('global.add') }} {{ trans('cruds.permission.title_singular') }}
        </a>
    </div>
</div>
@endcan

<div class="card">
    <div class="card-header cosecsa-card-header">
        <i class="fas fa-unlock-alt mr-2"></i> {{ trans('cruds.permission.title_singular') }} {{ trans('global.list') }}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-Permission">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('cruds.permission.fields.id') }}</th>
                        <th>{{ trans('cruds.permission.fields.title') }}</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permissions as $permission)
                    <tr data-entry-id="{{ $permission->id }}">
                        <td></td>
                        <td>{{ $permission->id }}</td>
                        <td>{{ $permission->title ?? '' }}</td>
                        <td class="actions-cell">
                            <button type="button" class="btn btn-xs btn-secondary action-menu-btn">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="action-menu" style="display:none;">
                                @can('permission_show')
                                <a href="{{ route('admin.permissions.show', $permission->id) }}">
                                    <i class="fas fa-eye text-primary"></i> View
                                </a>
                                @endcan
                                @can('permission_edit')
                                <a href="{{ route('admin.permissions.edit', $permission->id) }}">
                                    <i class="fas fa-pencil-alt text-info"></i> Edit
                                </a>
                                @endcan
                                @can('permission_delete')
                                <div class="menu-divider"></div>
                                <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST"
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
    var extraBtns = [];
    @can('permission_delete')
    extraBtns.push({
        text: '{{ trans('global.datatables.delete') }}',
        url: '{{ route('admin.permissions.massDestroy') }}',
        className: 'btn btn-sm btn-danger mr-1',
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
    window.dtInit('.datatable-Permission', extraBtns, [[1, 'asc']]);
});
</script>
@endsection
