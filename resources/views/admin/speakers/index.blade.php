@extends('layouts.admin')

@section('content')
@can('speaker_create')
<div class="row mb-2">
    <div class="col-lg-12">
        <a class="btn btn-cosecsa" href="{{ route('admin.speakers.create') }}">
            <i class="fas fa-user-plus mr-1"></i> {{ trans('global.add') }} {{ trans('cruds.speaker.title_singular') }}
        </a>
    </div>
</div>
@endcan

<div class="card">
    <div class="card-header cosecsa-card-header">
        <i class="fas fa-chalkboard-teacher mr-2"></i> {{ trans('cruds.speaker.title') }}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-Speaker">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('cruds.speaker.fields.id') }}</th>
                        <th>{{ trans('cruds.speaker.fields.name') }}</th>
                        <th>{{ trans('cruds.speaker.fields.description') }}</th>
                        <th>{{ trans('cruds.speaker.fields.twitter') }}</th>
                        <th>{{ trans('cruds.speaker.fields.facebook') }}</th>
                        <th>{{ trans('cruds.speaker.fields.linkedin') }}</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($speakers as $speaker)
                    <tr data-entry-id="{{ $speaker->id }}">
                        <td></td>
                        <td>{{ $speaker->id }}</td>
                        <td>{{ $speaker->name ?? '' }}</td>
                        <td>{{ $speaker->description ?? '' }}</td>
                        <td>{{ $speaker->twitter ?? '' }}</td>
                        <td>{{ $speaker->facebook ?? '' }}</td>
                        <td>{{ $speaker->linkedin ?? '' }}</td>
                        <td class="actions-cell">
                            <button type="button" class="btn btn-xs btn-secondary action-menu-btn">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="action-menu" style="display:none;">
                                @can('speaker_show')
                                <a href="{{ route('admin.speakers.show', $speaker->id) }}">
                                    <i class="fas fa-eye text-primary"></i> View
                                </a>
                                @endcan
                                @can('speaker_edit')
                                <a href="{{ route('admin.speakers.edit', $speaker->id) }}">
                                    <i class="fas fa-pencil-alt text-info"></i> Edit
                                </a>
                                @endcan
                                @can('speaker_delete')
                                <div class="menu-divider"></div>
                                <form action="{{ route('admin.speakers.destroy', $speaker->id) }}" method="POST"
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
    @can('speaker_delete')
    dtButtons.push({
        text: '{{ trans('global.datatables.delete') }}',
        url: '{{ route('admin.speakers.massDestroy') }}',
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
    $('.datatable-Speaker').DataTable({ buttons: dtButtons, order: [[1, 'asc']] });
});
</script>
@endsection
