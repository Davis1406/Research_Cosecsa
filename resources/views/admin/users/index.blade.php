@extends('layouts.admin')

@section('content')
@can('user_create')
<div class="row mb-2">
    <div class="col-lg-12">
        <a class="btn btn-cosecsa" href="{{ route('admin.users.create') }}">
            <i class="fas fa-user-plus mr-1"></i> {{ trans('global.add') }} {{ trans('cruds.user.title_singular') }}
        </a>
    </div>
</div>
@endcan

<div class="card">
    <div class="card-header cosecsa-card-header">
        <i class="fas fa-user mr-2"></i> {{ trans('cruds.user.title_singular') }} {{ trans('global.list') }}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-User">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('cruds.user.fields.id') }}</th>
                        <th>{{ trans('cruds.user.fields.name') }}</th>
                        <th>{{ trans('cruds.user.fields.email') }}</th>
                        <th>{{ trans('cruds.user.fields.email_verified_at') }}</th>
                        <th>{{ trans('cruds.user.fields.roles') }}</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr data-entry-id="{{ $user->id }}">
                        <td></td>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name ?? '' }}</td>
                        <td>{{ $user->email ?? '' }}</td>
                        <td>{{ $user->email_verified_at ?? '' }}</td>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge badge-info">{{ $role->title }}</span>
                            @endforeach
                        </td>
                        <td class="actions-cell">
                            <button type="button" class="btn btn-xs btn-secondary action-menu-btn">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="action-menu" style="display:none;">
                                @can('user_show')
                                <a href="{{ route('admin.users.show', $user->id) }}">
                                    <i class="fas fa-eye text-primary"></i> View
                                </a>
                                @endcan
                                @can('user_edit')
                                <a href="{{ route('admin.users.edit', $user->id) }}">
                                    <i class="fas fa-pencil-alt text-info"></i> Edit
                                </a>
                                @endcan
                                @can('user_delete')
                                <div class="menu-divider"></div>
                                <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
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
    @can('user_delete')
    dtButtons.push({
        text: '{{ trans('global.datatables.delete') }}',
        url: '{{ route('admin.users.massDestroy') }}',
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
    $('.datatable-User').DataTable({ buttons: dtButtons, order: [[1, 'asc']] });
});
</script>
@endsection
