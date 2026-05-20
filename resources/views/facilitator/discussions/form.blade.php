@extends('layouts.facilitator')

@section('page-title', 'Start Discussion')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
        <i class="fas fa-comments mr-2" style="color:#C9A84C;"></i> Start a Discussion
    </h5>
    <a href="{{ route('facilitator.discussions.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:13px;">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
</div>

<div class="card shadow-sm" style="border-radius:8px; max-width:700px;">
    <div class="card-body">
        <form action="{{ route('facilitator.discussions.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label style="font-size:12px; font-weight:700; color:#555;">Title *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title') }}" required placeholder="Discussion title...">
            </div>
            <div class="form-group">
                <label style="font-size:12px; font-weight:700; color:#555;">Message *</label>
                <textarea name="body" class="form-control" rows="5" required placeholder="Share your thoughts...">{{ old('body') }}</textarea>
            </div>
            <div class="form-group">
                <label style="font-size:12px; font-weight:700; color:#555;">Linked Session (optional)</label>
                <select name="schedule_id" class="form-control">
                    <option value="">— Not linked to a session —</option>
                    @foreach($sessions as $s)
                    <option value="{{ $s->id }}" {{ old('schedule_id') == $s->id ? 'selected' : '' }}>
                        Day {{ $s->day_number }}: {{ $s->title }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="is_general" name="is_general" {{ old('is_general') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="is_general" style="font-size:13px;">
                        Mark as General Discussion (not session-specific)
                    </label>
                </div>
            </div>
            <div class="d-flex justify-content-end" style="gap:8px;">
                <a href="{{ route('facilitator.discussions.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6;">Cancel</a>
                <button type="submit" class="btn btn-sm" style="background:#C9A84C; color:#fff; font-weight:700; padding:8px 20px;">
                    <i class="fas fa-paper-plane mr-1"></i> Start Discussion
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
