@extends('layouts.facilitator')

@section('page-title', 'Compose Message')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
        <i class="fas fa-pen mr-2" style="color:#C9A84C;"></i> Compose Message
    </h5>
    <a href="{{ route('facilitator.messages.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:13px;">
        <i class="fas fa-arrow-left mr-1"></i> Back
    </a>
</div>

<div class="card shadow-sm" style="border-radius:8px; max-width:700px;">
    <div class="card-body">
        <form action="{{ route('facilitator.messages.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label style="font-size:12px; font-weight:700; color:#555;">To *</label>
                <select name="receiver_id" class="form-control" required>
                    <option value="">— Select recipient —</option>
                    @foreach($recipients as $r)
                    <option value="{{ $r->id }}" {{ old('receiver_id', $toUser?->id) == $r->id ? 'selected' : '' }}>
                        {{ $r->name }} ({{ $r->email }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label style="font-size:12px; font-weight:700; color:#555;">Subject *</label>
                <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" required placeholder="Message subject...">
            </div>
            <div class="form-group">
                <label style="font-size:12px; font-weight:700; color:#555;">Message *</label>
                <textarea name="body" class="form-control" rows="6" required placeholder="Write your message...">{{ old('body') }}</textarea>
            </div>
            <div class="form-group">
                <label style="font-size:12px; font-weight:700; color:#555;">Attach Material (optional)</label>
                <select name="material_id" class="form-control">
                    <option value="">— No attachment —</option>
                    @foreach($materials as $m)
                    <option value="{{ $m->id }}" {{ old('material_id') == $m->id ? 'selected' : '' }}>
                        {{ $m->title }} ({{ ucfirst($m->type) }})
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="d-flex justify-content-end" style="gap:8px;">
                <a href="{{ route('facilitator.messages.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6;">Cancel</a>
                <button type="submit" class="btn btn-sm" style="background:#C9A84C; color:#fff; font-weight:700; padding:8px 20px;">
                    <i class="fas fa-paper-plane mr-1"></i> Send Message
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
