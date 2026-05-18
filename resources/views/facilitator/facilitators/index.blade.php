@extends('layouts.facilitator')
@section('page-title', 'Facilitators')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0" style="font-weight:700; color:#2d3748; font-size:1.1rem;">
        <i class="fas fa-chalkboard-teacher mr-2" style="color:#C9A84C;"></i> Facilitators
    </h5>
    <div class="d-flex align-items-center" style="gap:10px;">
        <span class="badge" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:0.85rem; padding:6px 14px;">
            {{ $facilitators->count() }} facilitators
        </span>
        <a href="{{ route('facilitator.facilitators.create') }}"
           class="btn btn-sm" style="background:#C9A84C; color:#fff; font-weight:700; font-size:0.875rem; border-radius:5px; padding:6px 14px;">
            <i class="fas fa-plus mr-1"></i> Add Facilitator
        </a>
    </div>
</div>

@if($facilitators->isEmpty())
    <div class="alert alert-info">No facilitators found.</div>
@else
    <div class="row">
        @foreach($facilitators as $speaker)
        @php
            $user      = $speaker->user;
            $roleTitle = $user?->roles->first()?->title ?? null;
            $isLead    = $roleTitle === 'Lead Facilitator';
            $hasPortal = !is_null($user);
            $photo     = $speaker->photo?->url ?? null;
            $initial   = strtoupper(substr($speaker->name ?? 'F', 0, 1));
            $isSelf    = $hasPortal && $user->id === auth()->id();
        @endphp
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card shadow-sm h-100" style="border-radius:10px; overflow:hidden; border:1px solid {{ $isSelf ? '#C9A84C' : '#e9ecef' }};">
                <div class="card-body" style="padding:22px;">

                    {{-- Avatar + name row --}}
                    <div class="d-flex align-items-center mb-3" style="gap:14px;">
                        <div style="width:56px;height:56px;border-radius:50%;border:2px solid #C9A84C;overflow:hidden;flex-shrink:0;background:#f8f9fa;">
                            @if($photo)
                                <img src="{{ $photo }}" style="width:100%;height:100%;object-fit:cover;" alt="">
                            @else
                                <div style="width:100%;height:100%;background:#C9A84C;display:flex;align-items:center;justify-content:center;font-size:22px;font-weight:700;color:#fff;">{{ $initial }}</div>
                            @endif
                        </div>
                        <div style="min-width:0;">
                            <div style="font-weight:700; font-size:1rem; color:#2d3748; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                {{ $speaker->name }}
                                @if($isSelf) <span style="font-size:0.75rem; color:#C9A84C; margin-left:4px;">(you)</span> @endif
                            </div>
                            @if($hasPortal)
                                <div style="font-size:0.85rem; color:#718096; white-space:nowrap; overflow:hidden; text-overflow:ellipsis; margin-top:2px;">{{ $user->email }}</div>
                            @endif
                            <div class="d-flex align-items-center" style="gap:4px; margin-top:5px; flex-wrap:wrap;">
                                @if($hasPortal)
                                    <span style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; padding:2px 10px; border-radius:10px; {{ $isLead ? 'background:rgba(201,168,76,0.2);color:#9a7d2c;' : 'background:rgba(44,122,75,0.12);color:#2c7a4b;' }}">
                                        {{ $roleTitle }}
                                    </span>
                                @else
                                    <span style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; padding:2px 10px; border-radius:10px; background:#f0f0f0; color:#999;">
                                        No portal access
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Bio --}}
                    @if($speaker->description)
                        <p style="font-size:0.875rem; color:#4a5568; margin-bottom:12px; line-height:1.6;">
                            {{ Str::limit($speaker->description, 100) }}
                        </p>
                    @endif

                    {{-- Social links --}}
                    @if($speaker->twitter || $speaker->linkedin || $speaker->facebook)
                        <div class="d-flex" style="gap:10px; margin-bottom:14px;">
                            @if($speaker->twitter)
                                <a href="{{ $speaker->twitter }}" target="_blank" style="color:#1da1f2; font-size:1rem;"><i class="fab fa-twitter"></i></a>
                            @endif
                            @if($speaker->linkedin)
                                <a href="{{ $speaker->linkedin }}" target="_blank" style="color:#0077b5; font-size:1rem;"><i class="fab fa-linkedin"></i></a>
                            @endif
                            @if($speaker->facebook)
                                <a href="{{ $speaker->facebook }}" target="_blank" style="color:#1877f2; font-size:1rem;"><i class="fab fa-facebook"></i></a>
                            @endif
                        </div>
                    @endif

                    {{-- Actions — only for speakers who have a portal account --}}
                    @if($hasPortal)
                        <div class="d-flex" style="gap:8px; margin-top:auto;">
                            <a href="{{ route('facilitator.facilitators.edit', $user->id) }}"
                               class="btn btn-sm flex-grow-1" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:0.875rem; padding:5px 10px;">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            @if(!$isSelf)
                            <form action="{{ route('facilitator.facilitators.destroy', $user->id) }}" method="POST"
                                  onsubmit="return confirm('Remove {{ addslashes($speaker->name) }}\'s portal account?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#fff5f5; color:#e53e3e; border:1px solid #fed7d7; font-size:0.875rem; padding:5px 10px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection
