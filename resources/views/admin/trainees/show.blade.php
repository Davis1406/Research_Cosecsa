@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header cosecsa-card-header">
        <i class="fas fa-user mr-2"></i> {{ trans('cruds.trainee.title_singular') }} {{ trans('global.detail') }}
    </div>
    <div class="card-body">
        <div class="form-group">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th style="width:220px">{{ trans('cruds.trainee.fields.id') }}</th>
                        <td>{{ $trainee->id }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.trainee.fields.name') }}</th>
                        <td>{{ $trainee->name }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.trainee.fields.email') }}</th>
                        <td>{{ $trainee->email }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.trainee.fields.phone') }}</th>
                        <td>{{ $trainee->phone }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.trainee.fields.institution') }}</th>
                        <td>{{ $trainee->institution }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.trainee.fields.registration_number') }}</th>
                        <td>{{ $trainee->registration_number }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.trainee.fields.country') }}</th>
                        <td>{{ $trainee->country }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.trainee.fields.specialty') }}</th>
                        <td>{{ $trainee->specialty }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.trainee.fields.enrollment_date') }}</th>
                        <td>{{ $trainee->enrollment_date ? $trainee->enrollment_date->format('Y-m-d') : '' }}</td>
                    </tr>
                    <tr>
                        <th>{{ trans('cruds.trainee.fields.notes') }}</th>
                        <td>{{ $trainee->notes }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <a class="btn btn-cosecsa" href="{{ route('admin.trainees.index') }}">
            <i class="fas fa-arrow-left mr-1"></i> {{ trans('global.back_to_list') }}
        </a>
    </div>
</div>
@endsection
