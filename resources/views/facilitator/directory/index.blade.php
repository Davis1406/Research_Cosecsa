@extends('layouts.facilitator')

@section('page-title', 'Facilitator Directory')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
        <i class="fas fa-address-book mr-2" style="color:#C9A84C;"></i> Facilitator Directory
    </h5>
    <span class="badge" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:12px; padding:5px 12px;">
        {{ $speakers->count() }} facilitator{{ $speakers->count() != 1 ? 's' : '' }}
    </span>
</div>

@if($speakers->isEmpty())
    <div class="alert alert-info">No facilitators found.</div>
@else
<div class="row">
    @foreach($speakers as $speaker)
    <div class="col-md-6 col-lg-4 mb-3">
        <div class="card shadow-sm h-100" style="border-radius:10px; overflow:hidden;">
            <div style="background:linear-gradient(135deg, #2d3748 0%, #1a202c 100%); padding:20px; text-align:center;">
                @if($speaker->photo)
                <img src="{{ $speaker->photo->url }}" alt="{{ $speaker->name }}"
                     style="width:70px;height:70px;border-radius:50%;border:3px solid #C9A84C;object-fit:cover;margin-bottom:10px;">
                @else
                <div style="width:70px;height:70px;border-radius:50%;border:3px solid #C9A84C;background:#C9A84C;display:inline-flex;align-items:center;justify-content:center;font-size:28px;font-weight:700;color:#fff;margin-bottom:10px;">
                    {{ strtoupper(substr($speaker->name, 0, 1)) }}
                </div>
                @endif
                <div style="font-size:15px; font-weight:700; color:#fff;">{{ $speaker->name }}</div>
                @if($speaker->twitter)
                <div style="font-size:11px; color:#C9A84C; margin-top:2px;">{{ $speaker->twitter }}</div>
                @elseif($speaker->description)
                <div style="font-size:11px; color:#aaa; margin-top:2px;">{{ Str::limit($speaker->description, 50) }}</div>
                @endif
            </div>
            <div class="card-body py-3">
                <div class="d-flex justify-content-around text-center mb-3">
                    <div>
                        <div style="font-size:18px; font-weight:700; color:#C9A84C;">{{ $speaker->schedules_count }}</div>
                        <div style="font-size:10px; color:#888; font-weight:600; text-transform:uppercase;">Sessions</div>
                    </div>
                    <div>
                        <div style="font-size:18px; font-weight:700; color:#2c7a4b;">{{ $speaker->materials_count }}</div>
                        <div style="font-size:10px; color:#888; font-weight:600; text-transform:uppercase;">Materials</div>
                    </div>
                </div>
                <div class="d-flex" style="gap:6px;">
                    <a href="{{ route('facilitator.directory.show', $speaker) }}" class="btn btn-sm flex-fill" style="background:#2d3748; color:#fff; font-size:12px; font-weight:700;">
                        <i class="fas fa-user mr-1"></i> Profile
                    </a>
                    @if($speaker->user_id)
                    <a href="{{ route('facilitator.messages.compose', ['to' => $speaker->user_id]) }}" class="btn btn-sm flex-fill" style="background:#C9A84C; color:#fff; font-size:12px; font-weight:700;">
                        <i class="fas fa-envelope mr-1"></i> Message
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
