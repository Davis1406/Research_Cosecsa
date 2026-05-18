@extends('layouts.facilitator')
@section('page-title', 'Facilitators')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4" style="flex-wrap:wrap; gap:10px;">
    <h5 class="mb-0" style="font-weight:700; color:#2d3748; font-size:1.1rem;">
        <i class="fas fa-chalkboard-teacher mr-2" style="color:#C9A84C;"></i> Facilitators
    </h5>
    <div class="d-flex align-items-center" style="gap:8px;">
        <span class="badge" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:0.8rem; padding:5px 12px;">
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
<div class="card shadow-sm" style="border-radius:10px; overflow:hidden;">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead style="background:#f8f9fa;">
                <tr>
                    <th style="font-size:0.75rem; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:0.4px; border-top:none; padding:10px 16px; width:40%;">Facilitator</th>
                    <th style="font-size:0.75rem; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:0.4px; border-top:none;" class="d-none d-md-table-cell">Role</th>
                    <th style="font-size:0.75rem; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:0.4px; border-top:none;" class="d-none d-lg-table-cell">Social</th>
                    <th style="font-size:0.75rem; font-weight:700; color:#555; text-transform:uppercase; letter-spacing:0.4px; border-top:none; text-align:right; padding-right:16px;">Actions</th>
                </tr>
            </thead>
            <tbody>
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
                <tr style="{{ $isSelf ? 'background:rgba(201,168,76,0.04);' : '' }}">
                    {{-- Name + email --}}
                    <td style="padding:12px 16px; vertical-align:middle;">
                        <div class="d-flex align-items-center" style="gap:12px;">
                            <div style="flex-shrink:0; width:38px; height:38px; border-radius:50%; border:2px solid #C9A84C; overflow:hidden; background:#f8f9fa;">
                                @if($photo)
                                    <img src="{{ $photo }}" style="width:100%;height:100%;object-fit:cover;" alt="">
                                @else
                                    <div style="width:100%;height:100%;background:#C9A84C;display:flex;align-items:center;justify-content:center;font-size:15px;font-weight:700;color:#fff;">{{ $initial }}</div>
                                @endif
                            </div>
                            <div style="min-width:0;">
                                <div style="font-weight:700; font-size:0.9rem; color:#2d3748; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                                    {{ $speaker->name }}
                                    @if($isSelf)<span style="font-size:0.7rem; color:#C9A84C; margin-left:4px;">(you)</span>@endif
                                </div>
                                @if($hasPortal)
                                    <div style="font-size:0.8rem; color:#888; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $user->email }}</div>
                                @else
                                    <div style="font-size:0.75rem; color:#bbb;">No portal access</div>
                                @endif
                            </div>
                        </div>
                    </td>

                    {{-- Role --}}
                    <td style="vertical-align:middle;" class="d-none d-md-table-cell">
                        @if($hasPortal)
                            <span style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.4px; padding:3px 10px; border-radius:10px; white-space:nowrap;
                                {{ $isLead ? 'background:rgba(201,168,76,0.18);color:#9a7d2c;' : 'background:rgba(44,122,75,0.12);color:#2c7a4b;' }}">
                                {{ $roleTitle }}
                            </span>
                        @else
                            <span style="font-size:0.72rem; color:#bbb;">—</span>
                        @endif
                    </td>

                    {{-- Social --}}
                    <td style="vertical-align:middle;" class="d-none d-lg-table-cell">
                        <div class="d-flex" style="gap:10px;">
                            @if($speaker->twitter)
                                <a href="{{ $speaker->twitter }}" target="_blank" style="color:#1da1f2; font-size:0.95rem;"><i class="fab fa-twitter"></i></a>
                            @endif
                            @if($speaker->linkedin)
                                <a href="{{ $speaker->linkedin }}" target="_blank" style="color:#0077b5; font-size:0.95rem;"><i class="fab fa-linkedin"></i></a>
                            @endif
                            @if($speaker->facebook)
                                <a href="{{ $speaker->facebook }}" target="_blank" style="color:#1877f2; font-size:0.95rem;"><i class="fab fa-facebook"></i></a>
                            @endif
                            @if(!$speaker->twitter && !$speaker->linkedin && !$speaker->facebook)
                                <span style="color:#ddd; font-size:0.8rem;">—</span>
                            @endif
                        </div>
                    </td>

                    {{-- Actions --}}
                    <td style="vertical-align:middle; text-align:right; padding-right:16px; white-space:nowrap;">
                        @if($hasPortal)
                            <a href="{{ route('facilitator.facilitators.edit', $user->id) }}"
                               class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:0.8rem; padding:4px 10px;">
                                <i class="fas fa-edit mr-1"></i> Edit
                            </a>
                            @if(!$isSelf)
                            <form action="{{ route('facilitator.facilitators.destroy', $user->id) }}" method="POST"
                                  style="display:inline;" onsubmit="return confirm('Remove {{ addslashes($speaker->name) }}\'s portal access?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm" style="background:#fff5f5; color:#e53e3e; border:1px solid #fed7d7; font-size:0.8rem; padding:4px 10px;">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        @else
                            <span style="font-size:0.8rem; color:#bbb;">No account</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endsection
