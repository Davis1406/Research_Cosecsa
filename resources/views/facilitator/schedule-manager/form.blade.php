@extends('layouts.facilitator')
@section('page-title', isset($session) ? 'Edit Session' : 'Add Session')

@section('content')
@php $isEdit = isset($session) && !is_null($session); @endphp

<div class="d-flex align-items-center mb-3" style="gap:12px;">
    <a href="{{ route('facilitator.schedule-manager.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6;">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
    <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
        <i class="fas fa-calendar-edit mr-2" style="color:#C9A84C;"></i>
        {{ $isEdit ? 'Edit Session: ' . $session->title : 'Add New Session' }}
    </h5>
</div>

<form action="{{ $isEdit ? route('facilitator.schedule-manager.update', $session->id) : route('facilitator.schedule-manager.store') }}"
      method="POST">
    @csrf
    @if($isEdit) @method('PUT') @endif

    <div class="row">
        <div class="col-lg-8">
            {{-- Basic info --}}
            <div class="card shadow-sm mb-3" style="border-radius:10px; overflow:hidden;">
                <div class="card-header" style="background:#fff; border-left:4px solid #C9A84C; padding:14px 20px;">
                    <strong style="font-size:14px; color:#2d3748;"><i class="fas fa-info-circle mr-2" style="color:#C9A84C;"></i>Session Details</strong>
                </div>
                <div class="card-body" style="padding:24px;">
                    <div class="form-group">
                        <label class="form-label-sm">Session Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control"
                               value="{{ old('title', $session->title ?? '') }}" required
                               placeholder="e.g. Introduction to Research Methods">
                    </div>
                    <div class="form-group">
                        <label class="form-label-sm">Subtitle / Topic</label>
                        <input type="text" name="subtitle" class="form-control"
                               value="{{ old('subtitle', $session->subtitle ?? '') }}"
                               placeholder="Optional sub-heading or topic">
                    </div>
                    <div class="row">
                        <div class="col-md-3 form-group">
                            <label class="form-label-sm">Day Number <span class="text-danger">*</span></label>
                            <input type="number" name="day_number" class="form-control" min="1"
                                   value="{{ old('day_number', $session->day_number ?? ($maxDay + 1 ?? 1)) }}" required>
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="form-label-sm">Date</label>
                            <input type="date" name="date" class="form-control"
                                   value="{{ old('date', $session && $session->date ? \Carbon\Carbon::parse($session->date)->format('Y-m-d') : '') }}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="form-label-sm">Start Time</label>
                            <input type="time" name="start_time" class="form-control"
                                   value="{{ old('start_time', $session && $session->start_time ? \Carbon\Carbon::parse($session->start_time)->format('H:i') : '') }}">
                        </div>
                        <div class="col-md-3 form-group">
                            <label class="form-label-sm">End Time</label>
                            <input type="time" name="end_time" class="form-control"
                                   value="{{ old('end_time', $session && $session->end_time ? \Carbon\Carbon::parse($session->end_time)->format('H:i') : '') }}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 form-group mb-0">
                            <label class="form-label-sm">Location / Room</label>
                            <input type="text" name="location" class="form-control"
                                   value="{{ old('location', $session->location ?? '') }}"
                                   placeholder="e.g. Conference Room A">
                        </div>
                        <div class="col-md-6 form-group mb-0">
                            <label class="form-label-sm">Facilitator / Speaker</label>
                            <select name="speaker_id" class="form-control">
                                <option value="">-- None / TBA --</option>
                                @foreach($speakers as $speaker)
                                    <option value="{{ $speaker->id }}"
                                        {{ old('speaker_id', $session->speaker_id ?? '') == $speaker->id ? 'selected' : '' }}>
                                        {{ $speaker->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Materials --}}
            <div class="card shadow-sm mb-3" style="border-radius:10px; overflow:hidden;">
                <div class="card-header" style="background:#fff; border-left:4px solid #C9A84C; padding:14px 20px;">
                    <strong style="font-size:14px; color:#2d3748;"><i class="fas fa-book mr-2" style="color:#C9A84C;"></i>Linked Materials</strong>
                    <small class="text-muted ml-2" style="font-size:12px;">Select which materials belong to this session</small>
                </div>
                <div class="card-body" style="padding:20px 24px;">
                    @php
                        $selectedIds = old('materials', $isEdit ? $session->materials->pluck('id')->toArray() : []);
                        $matGroups = $materials->groupBy('type');
                    @endphp
                    @forelse($materials as $mat)
                    @if($loop->first || $mat->type !== $materials[$loop->index-1]->type ?? '')
                        @if(!$loop->first) </div> @endif
                        <div style="font-size:10px; font-weight:700; text-transform:uppercase; color:#aaa; letter-spacing:0.6px; margin:12px 0 6px;">
                            {{ ['presentation'=>'Presentations','document'=>'Documents','video'=>'Videos'][$mat->type] ?? $mat->type }}
                        </div>
                        <div>
                    @endif
                    <div class="form-check" style="margin-bottom:6px; padding-left:22px;">
                        <input class="form-check-input" type="checkbox" name="materials[]"
                               id="mat-{{ $mat->id }}" value="{{ $mat->id }}"
                               {{ in_array($mat->id, $selectedIds) ? 'checked' : '' }}>
                        <label class="form-check-label" for="mat-{{ $mat->id }}"
                               style="font-size:13px; color:#2d3748; cursor:pointer;">
                            {{ $mat->title }}
                            @if($mat->category)
                                <span style="font-size:10px; color:#aaa; margin-left:4px;">({{ $mat->category }})</span>
                            @endif
                        </label>
                    </div>
                    @if($loop->last) </div> @endif
                    @empty
                        <p style="font-size:13px; color:#aaa;">No materials available yet. <a href="{{ route('facilitator.material-manager.create') }}">Add some first.</a></p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow-sm" style="border-radius:10px; overflow:hidden;">
                <div class="card-body text-center" style="padding:28px 20px;">
                    <div style="font-size:48px; color:#C9A84C; margin-bottom:14px;">
                        <i class="fas fa-calendar-alt"></i>
                    </div>
                    <div style="font-size:13px; color:#888; margin-bottom:18px;">
                        {{ $isEdit ? 'Editing session' : 'New session' }}
                    </div>
                    <button type="submit" class="btn btn-block" style="background:#C9A84C; color:#fff; font-weight:700; padding:10px; border-radius:6px; font-size:14px;">
                        <i class="fas fa-save mr-2"></i>{{ $isEdit ? 'Save Changes' : 'Create Session' }}
                    </button>
                    <a href="{{ route('facilitator.schedule-manager.index') }}" class="btn btn-block mt-2" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:13px;">
                        Cancel
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

@section('styles')
<style>.form-label-sm { font-size:11px; font-weight:700; color:#666; text-transform:uppercase; letter-spacing:0.5px; margin-bottom:4px; display:block; }</style>
@endsection
@endsection
