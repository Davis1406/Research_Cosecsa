@extends('layouts.admin')

@section('content')
@can('training_material_create')
<div class="row mb-2">
    <div class="col-lg-12">
        <a class="btn btn-cosecsa" href="{{ route('admin.training-materials.create') }}">
            <i class="fas fa-upload mr-1"></i> {{ trans('global.add') }} {{ trans('cruds.trainingMaterial.title_singular') }}
        </a>
    </div>
</div>
@endcan

<div class="card">
    <div class="card-header cosecsa-card-header">
        <i class="fas fa-book mr-2"></i> {{ trans('cruds.trainingMaterial.title') }} {{ trans('global.list') }}
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-hover datatable datatable-TrainingMaterial">
                <thead>
                    <tr>
                        <th width="10"></th>
                        <th>{{ trans('cruds.trainingMaterial.fields.id') }}</th>
                        <th>{{ trans('cruds.trainingMaterial.fields.title') }}</th>
                        <th>{{ trans('cruds.trainingMaterial.fields.category') }}</th>
                        <th>{{ trans('cruds.trainingMaterial.fields.type') }}</th>
                        <th>Facilitator</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($trainingMaterials as $material)
                    <tr data-entry-id="{{ $material->id }}">
                        <td></td>
                        <td>{{ $material->id }}</td>
                        <td>{{ $material->title ?? '' }}</td>
                        <td>{{ $material->category ?? '' }}</td>
                        <td>
                            @if($material->type === 'presentation')
                                <span class="badge badge-primary"><i class="fas fa-file-powerpoint mr-1"></i> Presentation</span>
                            @elseif($material->type === 'video')
                                <span class="badge badge-danger"><i class="fas fa-video mr-1"></i> Video</span>
                            @elseif($material->type === 'document')
                                <span class="badge badge-info"><i class="fas fa-file-pdf mr-1"></i> Document</span>
                            @elseif($material->type === 'image')
                                <span class="badge badge-warning"><i class="fas fa-image mr-1"></i> Image</span>
                            @else
                                {{ $material->type }}
                            @endif
                        </td>
                        <td>{{ $material->facilitator->name ?? '—' }}</td>
                        <td class="actions-cell">
                            <button type="button" class="btn btn-xs btn-secondary action-menu-btn">
                                <i class="fas fa-ellipsis-v"></i>
                            </button>
                            <div class="action-menu" style="display:none;">
                                @can('training_material_show')
                                <a href="{{ route('admin.training-materials.viewer', $material->id) }}">
                                    <i class="fas fa-eye text-primary"></i> View
                                </a>
                                @endcan
                                @can('training_material_edit')
                                <a href="{{ route('admin.training-materials.edit', $material->id) }}">
                                    <i class="fas fa-pencil-alt text-info"></i> Edit
                                </a>
                                @endcan
                                @can('training_material_delete')
                                <div class="menu-divider"></div>
                                <form action="{{ route('admin.training-materials.destroy', $material->id) }}" method="POST"
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
    @can('training_material_delete')
    extraBtns.push({
        text: '{{ trans('global.datatables.delete') }}',
        url: '{{ route('admin.training-materials.massDestroy') }}',
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
    window.dtInit('.datatable-TrainingMaterial', extraBtns, [[1, 'asc']]);
});
</script>
@endsection
