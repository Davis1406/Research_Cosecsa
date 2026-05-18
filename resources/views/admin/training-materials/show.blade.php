@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header cosecsa-card-header">
        <i class="fas fa-book mr-2"></i> {{ trans('cruds.trainingMaterial.title_singular') }} {{ trans('global.detail') }}
    </div>
    <div class="card-body">
        <div class="form-group">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th style="width:220px">{{ trans('cruds.trainingMaterial.fields.id') }}</th>
                        <td>{{ $trainingMaterial->id }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.trainingMaterial.fields.title') }}</th>
                        <td>{{ $trainingMaterial->title }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.trainingMaterial.fields.category') }}</th>
                        <td>{{ $trainingMaterial->category }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.trainingMaterial.fields.type') }}</th>
                        <td>
                            @if($trainingMaterial->type === 'presentation')
                                <span class="badge badge-primary"><i class="fas fa-file-powerpoint mr-1"></i> Presentation</span>
                            @elseif($trainingMaterial->type === 'video')
                                <span class="badge badge-danger"><i class="fas fa-video mr-1"></i> Video</span>
                            @elseif($trainingMaterial->type === 'document')
                                <span class="badge badge-info"><i class="fas fa-file-pdf mr-1"></i> Document</span>
                            @elseif($trainingMaterial->type === 'image')
                                <span class="badge badge-warning"><i class="fas fa-image mr-1"></i> Image</span>
                            @else
                                {{ $trainingMaterial->type }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.trainingMaterial.fields.description') }}</th>
                        <td>{{ $trainingMaterial->description }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.trainingMaterial.fields.speaker') }}</th>
                        <td>{{ $trainingMaterial->facilitator->name ?? '—' }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.trainingMaterial.fields.file') }}</th>
                        <td>
                            @if($trainingMaterial->file)
                                <a href="{{ $trainingMaterial->file->url }}" target="_blank" class="btn btn-sm btn-success">
                                    <i class="fas fa-download mr-1"></i> Download {{ $trainingMaterial->file->file_name }}
                                </a>
                            @else
                                <span class="text-muted">No file uploaded</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.trainingMaterial.fields.external_url') }}</th>
                        <td>
                            @if($trainingMaterial->external_url)
                                <a href="{{ $trainingMaterial->external_url }}" target="_blank" class="btn btn-sm btn-danger">
                                    <i class="fas fa-external-link-alt mr-1"></i> Open Link
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <a class="btn btn-cosecsa" href="{{ route('admin.training-materials.index') }}">
            <i class="fas fa-arrow-left mr-1"></i> {{ trans('global.back_to_list') }}
        </a>
    </div>
</div>
@endsection
