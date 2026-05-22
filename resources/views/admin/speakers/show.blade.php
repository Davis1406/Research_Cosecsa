@extends('layouts.admin')
@section('content')

<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex align-items-center justify-content-between mb-2">
            <h5 class="m-0" style="font-weight:700; color:#2d3748;">
                <i class="fas fa-chalkboard-teacher mr-2" style="color:#a02626;"></i> Facilitator Profile
            </h5>
            <div style="display:flex; gap:8px;">
                <a href="{{ route('admin.speakers.edit', $speaker->id) }}" class="btn btn-sm" style="background:#fff8e6; color:#7a5c00; border:1px solid #C9A84C66; font-size:12px;">
                    <i class="fas fa-edit mr-1"></i> Edit
                </a>
                <a href="{{ route('admin.speakers.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:12px;">
                    <i class="fas fa-arrow-left mr-1"></i> Back to list
                </a>
            </div>
        </div>
    </div>
</div>

<section class="content">
<div class="container-fluid">
<div class="row">

    {{-- Left column: Photo + Social --}}
    <div class="col-lg-4 col-md-5 mb-4">
        <div class="card shadow-sm" style="border-radius:12px; overflow:hidden;">
            {{-- Hero banner --}}
            <div style="background:linear-gradient(135deg, #2d3748 0%, #1a202c 100%); padding:32px 20px; text-align:center;">
                @if($speaker->photo)
                    <img src="{{ $speaker->photo->getUrl() }}" alt="{{ $speaker->name }}"
                         style="width:100px;height:100px;border-radius:50%;border:3px solid #C9A84C;object-fit:cover;margin-bottom:14px;display:block;margin-left:auto;margin-right:auto;">
                @else
                    <div style="width:100px;height:100px;border-radius:50%;border:3px solid #C9A84C;background:#a02626;display:inline-flex;align-items:center;justify-content:center;font-size:40px;font-weight:700;color:#fff;margin-bottom:14px;">
                        {{ strtoupper(substr($speaker->name, 0, 1)) }}
                    </div>
                @endif
                <div style="font-size:18px; font-weight:700; color:#fff; line-height:1.3;">{{ $speaker->name }}</div>
                @php $roleTitle = $speaker->user?->roles?->first()?->title ?? 'Facilitator'; @endphp
                <span style="display:inline-block; margin-top:8px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.8px; padding:3px 14px; border-radius:20px; background:rgba(201,168,76,0.2); color:#C9A84C; border:1px solid rgba(201,168,76,0.4);">
                    {{ $roleTitle }}
                </span>
            </div>

            <div class="card-body" style="padding:20px;">
                {{-- Short bio --}}
                @if($speaker->description)
                <p style="font-size:13px; color:#555; line-height:1.6; margin-bottom:16px;">{{ $speaker->description }}</p>
                @endif

                {{-- Social links --}}
                <div style="margin-bottom:4px;">
                    <div style="font-size:10px; font-weight:700; text-transform:uppercase; color:#aaa; letter-spacing:0.5px; margin-bottom:8px;">Social Links</div>
                    <div style="display:flex; flex-wrap:wrap; gap:6px;">
                        @if($speaker->twitter)
                        <a href="{{ $speaker->twitter }}" target="_blank" class="btn btn-sm" style="background:#1da1f2; color:#fff; font-size:11px; padding:4px 12px; border-radius:20px;">
                            <i class="fab fa-twitter mr-1"></i>Twitter
                        </a>
                        @endif
                        @if($speaker->facebook)
                        <a href="{{ $speaker->facebook }}" target="_blank" class="btn btn-sm" style="background:#1877f2; color:#fff; font-size:11px; padding:4px 12px; border-radius:20px;">
                            <i class="fab fa-facebook mr-1"></i>Facebook
                        </a>
                        @endif
                        @if($speaker->linkedin)
                        <a href="{{ $speaker->linkedin }}" target="_blank" class="btn btn-sm" style="background:#0077b5; color:#fff; font-size:11px; padding:4px 12px; border-radius:20px;">
                            <i class="fab fa-linkedin mr-1"></i>LinkedIn
                        </a>
                        @endif
                        @if($speaker->researchgate)
                        <a href="{{ $speaker->researchgate }}" target="_blank" class="btn btn-sm" style="background:#40ba9b; color:#fff; font-size:11px; padding:4px 12px; border-radius:20px;">
                            <i class="fas fa-flask mr-1"></i>ResearchGate
                        </a>
                        @endif
                        @if($speaker->google_scholar)
                        <a href="{{ $speaker->google_scholar }}" target="_blank" class="btn btn-sm" style="background:#4285f4; color:#fff; font-size:11px; padding:4px 12px; border-radius:20px;">
                            <i class="fas fa-graduation-cap mr-1"></i>Scholar
                        </a>
                        @endif
                        @if($speaker->orcid)
                        <a href="{{ $speaker->orcid }}" target="_blank" class="btn btn-sm" style="background:#a6ce39; color:#fff; font-size:11px; padding:4px 12px; border-radius:20px;">
                            <i class="fas fa-id-badge mr-1"></i>ORCID
                        </a>
                        @endif
                        @if($speaker->web_of_science)
                        <a href="{{ $speaker->web_of_science }}" target="_blank" class="btn btn-sm" style="background:#e84c1e; color:#fff; font-size:11px; padding:4px 12px; border-radius:20px;">
                            <i class="fas fa-atom mr-1"></i>Web of Science
                        </a>
                        @endif
                        @if(!$speaker->twitter && !$speaker->facebook && !$speaker->linkedin && !$speaker->researchgate && !$speaker->google_scholar && !$speaker->orcid && !$speaker->web_of_science)
                        <p class="text-muted mb-0" style="font-size:12px;">No social links added.</p>
                        @endif
                    </div>
                </div>

                {{-- Portal account info --}}
                @if($speaker->user)
                <hr style="margin:16px 0;">
                <div style="font-size:10px; font-weight:700; text-transform:uppercase; color:#aaa; letter-spacing:0.5px; margin-bottom:6px;">Portal Account</div>
                <div style="font-size:12px; color:#2d3748;">
                    <i class="fas fa-envelope mr-1" style="color:#C9A84C;"></i> {{ $speaker->user->email }}
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right column: Bio + Sessions + Materials --}}
    <div class="col-lg-8 col-md-7">

        {{-- Full Biography --}}
        @if($speaker->full_description)
        <div class="card shadow-sm mb-3" style="border-radius:12px; overflow:hidden;">
            <div class="card-header" style="background:#fff; border-left:4px solid #a02626; padding:12px 16px;">
                <strong style="font-size:13px; color:#2d3748;"><i class="fas fa-user mr-2" style="color:#a02626;"></i>About</strong>
            </div>
            <div class="card-body">
                <p style="font-size:13.5px; color:#444; line-height:1.75; margin:0;">{!! $speaker->full_description !!}</p>
            </div>
        </div>
        @endif

        {{-- Sessions --}}
        <div class="card shadow-sm mb-3" style="border-radius:12px; overflow:hidden;">
            <div class="card-header" style="background:#fff; border-left:4px solid #C9A84C; padding:12px 16px;">
                <strong style="font-size:13px; color:#2d3748;">
                    <i class="fas fa-calendar-alt mr-2" style="color:#C9A84C;"></i>
                    Sessions
                    <span style="font-size:11px; font-weight:400; color:#888; margin-left:6px;">{{ $speaker->schedules->count() }} assigned</span>
                </strong>
            </div>
            <div class="card-body" style="padding:0;">
                @forelse($speaker->schedules as $s)
                <div style="padding:10px 16px; border-bottom:1px solid #f8f9fa; display:flex; align-items:center; gap:10px;">
                    <div style="width:28px;height:28px;border-radius:50%;background:#C9A84C22;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas fa-calendar-day" style="font-size:10px; color:#C9A84C;"></i>
                    </div>
                    <div style="min-width:0; flex:1;">
                        <div style="font-size:13px; font-weight:600; color:#2d3748;">{{ $s->title }}</div>
                        <div style="font-size:11px; color:#888;">Day {{ $s->day_number }}@if($s->date) &bull; {{ $s->date }}@endif</div>
                    </div>
                    @if($s->is_completed)
                    <span class="badge badge-success" style="font-size:10px;">Completed</span>
                    @endif
                </div>
                @empty
                <div class="text-muted p-3" style="font-size:13px; text-align:center;">
                    <i class="fas fa-calendar-times mb-1" style="display:block; font-size:20px; color:#ddd;"></i>
                    No sessions assigned.
                </div>
                @endforelse
            </div>
        </div>

        {{-- Materials --}}
        <div class="card shadow-sm" style="border-radius:12px; overflow:hidden;">
            <div class="card-header" style="background:#fff; border-left:4px solid #2c7a4b; padding:12px 16px;">
                <strong style="font-size:13px; color:#2d3748;">
                    <i class="fas fa-book mr-2" style="color:#2c7a4b;"></i>
                    Materials
                    <span style="font-size:11px; font-weight:400; color:#888; margin-left:6px;">{{ $speaker->materials->count() }} uploaded</span>
                </strong>
            </div>
            <div class="card-body" style="padding:0;">
                @forelse($speaker->materials as $m)
                <div style="padding:10px 16px; border-bottom:1px solid #f8f9fa; display:flex; justify-content:space-between; align-items:center;">
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:28px;height:28px;border-radius:6px;background:#2c7a4b18;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            @php
                                $mIcon = $m->type === 'presentation' ? 'fa-file-powerpoint' : ($m->type === 'video' ? 'fa-video' : 'fa-file-pdf');
                            @endphp
                            <i class="fas {{ $mIcon }}" style="font-size:11px; color:#2c7a4b;"></i>
                        </div>
                        <div>
                            <div style="font-size:13px; font-weight:600; color:#2d3748;">{{ $m->title }}</div>
                            <div style="font-size:11px; color:#888;">{{ ucfirst($m->type) }}</div>
                        </div>
                    </div>
                    <a href="{{ route('material.view', $m) }}" class="btn btn-sm" style="background:#f0f7f3; color:#2c7a4b; border:1px solid #2c7a4b44; font-size:11px;">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
                @empty
                <div class="text-muted p-3" style="font-size:13px; text-align:center;">
                    <i class="fas fa-book-open mb-1" style="display:block; font-size:20px; color:#ddd;"></i>
                    No materials uploaded.
                </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
</div>
</section>
@endsection
