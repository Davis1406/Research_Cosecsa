@extends('layouts.admin')
@section('content')
@can('sponsor_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.sponsors.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.sponsor.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.sponsor.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Sponsor">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.sponsor.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.sponsor.fields.name') }}
                        </th>
                        <th>
                            {{ trans('cruds.sponsor.fields.logo') }}
                        </th>
                        <th>
                            {{ trans('cruds.sponsor.fields.link') }}
                        </th>
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sponsors as $key => $sponsor)
                        <tr data-entry-id="{{ $sponsor->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $sponsor->id ?? '' }}
                            </td>
                            <td>
                                {{ $sponsor->name ?? '' }}
                            </td>
                            <td>
                                @if($sponsor->logo)
                                    <a href="{{ $sponsor->logo->getUrl() }}" target="_blank">
                                        <img src="{{ $sponsor->logo->getUrl() }}" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{ $sponsor->link ?? '' }}
                            </td>
                            <td>
                                @can('sponsor_show')
                                    <a class="btn btn-xs btn-primary" href="{{ route('admin.sponsors.show', $sponsor->id) }}">
                                        {{ trans('global.view') }}
                                    </a>
                                @endcan

                                @can('sponsor_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.sponsors.edit', $sponsor->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('sponsor_delete')
                                    <form action="{{ route('admin.sponsors.destroy', $sponsor->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan

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
  var extraBtns = []
@can('sponsor_delete')
  extraBtns.push({
    text: '{{ trans(\'global.datatables.delete\') }}',
    url: "{{ route('admin.sponsors.massDestroy') }}",
    className: 'btn btn-sm btn-danger mr-1',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });
      if (ids.length === 0) { alert('{{ trans(\'global.datatables.zero_selected\') }}'); return; }
      if (confirm('{{ trans(\'global.areYouSure\') }}')) {
        $.ajax({ headers: {'x-csrf-token': _token}, method: 'POST', url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  });
@endcan
  window.dtInit('.datatable-Sponsor:not(.ajaxTable)', extraBtns)
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection