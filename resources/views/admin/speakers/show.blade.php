@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header cosecsa-card-header">
        <i class="fas fa-chalkboard-teacher mr-2"></i> {{ trans('cruds.speaker.title_singular') }} {{ trans('global.detail') }}
    </div>

    <div class="card-body">
        <div class="mb-2">
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.speaker.fields.id') }}
                        </th>
                        <td>
                            {{ $speaker->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.speaker.fields.name') }}
                        </th>
                        <td>
                            {{ $speaker->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.speaker.fields.description') }}
                        </th>
                        <td>
                            {!! $speaker->description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.speaker.fields.full_description') }}
                        </th>
                        <td>
                            {!! $speaker->full_description !!}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.speaker.fields.photo') }}
                        </th>
                        <td>
                            @if($speaker->photo)
                                <a href="{{ $speaker->photo->getUrl() }}" target="_blank">
                                    <img src="{{ $speaker->photo->getUrl() }}" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th><i class="fab fa-twitter mr-1" style="color:#1da1f2;"></i> {{ trans('cruds.speaker.fields.twitter') }}</th>
                        <td>
                            @if($speaker->twitter)
                                <a href="{{ $speaker->twitter }}" target="_blank">{{ $speaker->twitter }}</a>
                            @else —
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th><i class="fab fa-facebook mr-1" style="color:#1877f2;"></i> {{ trans('cruds.speaker.fields.facebook') }}</th>
                        <td>
                            @if($speaker->facebook)
                                <a href="{{ $speaker->facebook }}" target="_blank">{{ $speaker->facebook }}</a>
                            @else —
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th><i class="fab fa-linkedin mr-1" style="color:#0077b5;"></i> {{ trans('cruds.speaker.fields.linkedin') }}</th>
                        <td>
                            @if($speaker->linkedin)
                                <a href="{{ $speaker->linkedin }}" target="_blank">{{ $speaker->linkedin }}</a>
                            @else —
                            @endif
                        </td>
                    </tr>
                </tbody>
            </table>
            <a style="margin-top:20px;" class="btn btn-default" href="{{ url()->previous() }}">
                {{ trans('global.back_to_list') }}
            </a>
        </div>

        <nav class="mb-3">
            <div class="nav nav-tabs">

            </div>
        </nav>
        <div class="tab-content">

        </div>
    </div>
</div>
@endsection