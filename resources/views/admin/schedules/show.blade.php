@extends('layouts.admin')
@section('content')
<div class="card">
    <div class="card-header cosecsa-card-header">
        <i class="fas fa-calendar-day mr-2"></i> {{ trans('cruds.schedule.title_singular') }} {{ trans('global.detail') }}
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <tbody>
                <tr>
                    <th style="width:220px">{{ trans('cruds.schedule.fields.id') }}</th>
                    <td>{{ $schedule->id }}</td>
                </tr>
                <tr>
                    <th>{{ trans('cruds.schedule.fields.day_number') }}</th>
                    <td>Day {{ $schedule->day_number }}</td>
                </tr>
                <tr>
                    <th>{{ trans('cruds.schedule.fields.date') }}</th>
                    <td>{{ $schedule->date }}</td>
                </tr>
                <tr>
                    <th>{{ trans('cruds.schedule.fields.start_time') }}</th>
                    <td>{{ $schedule->start_time }}</td>
                </tr>
                <tr>
                    <th>{{ trans('cruds.schedule.fields.end_time') }}</th>
                    <td>{{ $schedule->end_time }}</td>
                </tr>
                <tr>
                    <th>{{ trans('cruds.schedule.fields.title') }}</th>
                    <td>{{ $schedule->title }}</td>
                </tr>
                <tr>
                    <th>{{ trans('cruds.schedule.fields.subtitle') }}</th>
                    <td>{{ $schedule->subtitle }}</td>
                </tr>
                <tr>
                    <th>{{ trans('cruds.schedule.fields.location') }}</th>
                    <td>{{ $schedule->location }}</td>
                </tr>
                <tr>
                    <th>{{ trans('cruds.schedule.fields.speaker') }}</th>
                    <td>{{ $schedule->speaker->name ?? '—' }}</td>
                </tr>
            </tbody>
        </table>
        <a class="btn btn-cosecsa mt-3" href="{{ route('admin.schedules.index') }}">
            <i class="fas fa-arrow-left mr-1"></i> {{ trans('global.back_to_list') }}
        </a>
    </div>
</div>
@endsection