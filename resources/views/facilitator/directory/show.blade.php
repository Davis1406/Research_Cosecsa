@extends('layouts.facilitator')

@section('page-title', $speaker->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0" style="font-weight:700; color:#2d3748;">
        <i class="fas fa-address-book mr-2" style="color:#C9A84C;"></i> Facilitator Profile
    </h5>
    <a href="{{ route('facilitator.directory.index') }}" class="btn btn-sm" style="background:#f8f9fa; color:#555; border:1px solid #dee2e6; font-size:13px;">
        <i class="fas fa-arrow-left mr-1"></i> Back to Directory
    </a>
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <div class="card shadow-sm" style="border-radius:10px; overflow:hidden;">
            <div style="background:linear-gradient(135deg, #2d3748 0%, #1a202c 100%); padding:28px; text-align:center;">
                @if($speaker->photo)
                <img src="{{ $speaker->photo->url }}" alt="{{ $speaker->name }}"
                     style="width:90px;height:90px;border-radius:50%;border:3px solid #C9A84C;object-fit:cover;margin-bottom:12px;">
                @else
                <div style="width:90px;height:90px;border-radius:50%;border:3px solid #C9A84C;background:#C9A84C;display:inline-flex;align-items:center;justify-content:center;font-size:36px;font-weight:700;color:#fff;margin-bottom:12px;">
                    {{ strtoupper(substr($speaker->name, 0, 1)) }}
                </div>
                @endif
                <div style="font-size:17px; font-weight:700; color:#fff;">{{ $speaker->name }}</div>
                @if($speaker->description)
                <div style="font-size:12px; color:#aaa; margin-top:4px;">{{ $speaker->description }}</div>
                @endif
            </div>
            <div class="card-body">
                {{-- Social links --}}
                <div style="margin-bottom:12px;">
                    <div style="font-size:10px; font-weight:700; text-transform:uppercase; color:#aaa; letter-spacing:0.5px; margin-bottom:6px;">Social & Academic</div>
                    <div style="display:flex; flex-wrap:wrap; gap:6px;">
                        @if($speaker->twitter)
                        <a href="{{ $speaker->twitter }}" target="_blank" class="btn btn-sm" style="background:#1da1f2; color:#fff; font-size:11px; padding:3px 10px;">
                            <i class="fab fa-twitter mr-1"></i>Twitter
                        </a>
                        @endif
                        @if($speaker->linkedin)
                        <a href="{{ $speaker->linkedin }}" target="_blank" class="btn btn-sm" style="background:#0077b5; color:#fff; font-size:11px; padding:3px 10px;">
                            <i class="fab fa-linkedin mr-1"></i>LinkedIn
                        </a>
                        @endif
                        @if($speaker->researchgate)
                        <a href="{{ $speaker->researchgate }}" target="_blank" class="btn btn-sm" style="background:#40ba9b; color:#fff; font-size:11px; padding:3px 10px;">
                            <i class="fas fa-flask mr-1"></i>ResearchGate
                        </a>
                        @endif
                        @if($speaker->google_scholar)
                        <a href="{{ $speaker->google_scholar }}" target="_blank" class="btn btn-sm" style="background:#4285f4; color:#fff; font-size:11px; padding:3px 10px;">
                            <i class="fas fa-graduation-cap mr-1"></i>Scholar
                        </a>
                        @endif
                        @if($speaker->orcid)
                        <a href="{{ $speaker->orcid }}" target="_blank" class="btn btn-sm" style="background:#a6ce39; color:#fff; font-size:11px; padding:3px 10px;">
                            <i class="fas fa-id-badge mr-1"></i>ORCID
                        </a>
                        @endif
                        @if($speaker->web_of_science)
                        <a href="{{ $speaker->web_of_science }}" target="_blank" class="btn btn-sm" style="background:#e84c1e; color:#fff; font-size:11px; padding:3px 10px;">
                            <i class="fas fa-atom mr-1"></i>Web of Science
                        </a>
                        @endif
                    </div>
                </div>

                @if($speaker->user_id)
                <a href="{{ route('facilitator.messages.compose', ['to' => $speaker->user_id]) }}"
                   class="btn btn-block" style="background:#C9A84C; color:#fff; font-weight:700; font-size:13px;">
                    <i class="fas fa-envelope mr-2"></i> Send Message
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-8">
        @if($speaker->full_description)
        <div class="card shadow-sm mb-3" style="border-radius:8px;">
            <div class="card-header" style="background:#fff; border-bottom:2px solid #C9A84C; font-weight:700; font-size:13px; color:#2d3748;">
                About
            </div>
            <div class="card-body">
                <p style="font-size:13.5px; color:#444; line-height:1.7;">{{ $speaker->full_description }}</p>
            </div>
        </div>
        @endif

        <div class="card shadow-sm mb-3" style="border-radius:8px;">
            <div class="card-header" style="background:#fff; border-bottom:2px solid #C9A84C; font-weight:700; font-size:13px; color:#2d3748;">
                <i class="fas fa-calendar-alt mr-2" style="color:#C9A84C;"></i> Sessions ({{ $speaker->schedules->count() }})
            </div>
            <div class="card-body" style="padding:0;">
                @forelse($speaker->schedules as $s)
                <div style="padding:10px 16px; border-bottom:1px solid #f8f9fa; font-size:13px; color:#2d3748;">
                    <span style="font-weight:700;">Day {{ $s->day_number }}:</span> {{ $s->title }}
                    @if($s->date)
                    <span class="text-muted ml-2" style="font-size:11px;">{{ $s->date }}</span>
                    @endif
                    @if($s->is_completed)
                    <span class="badge badge-success ml-2" style="font-size:10px;">Completed</span>
                    @endif
                </div>
                @empty
                <div class="text-muted p-3" style="font-size:13px;">No sessions assigned.</div>
                @endforelse
            </div>
        </div>

        <div class="card shadow-sm" style="border-radius:8px;">
            <div class="card-header" style="background:#fff; border-bottom:2px solid #C9A84C; font-weight:700; font-size:13px; color:#2d3748;">
                <i class="fas fa-book mr-2" style="color:#C9A84C;"></i> Materials ({{ $speaker->materials->count() }})
            </div>
            <div class="card-body" style="padding:0;">
                @forelse($speaker->materials as $m)
                <div style="padding:10px 16px; border-bottom:1px solid #f8f9fa; display:flex; justify-content:space-between; align-items:center;">
                    <div>
                        <div style="font-size:13px; font-weight:600; color:#2d3748;">{{ $m->title }}</div>
                        <div style="font-size:11px; color:#888;">{{ ucfirst($m->type) }}</div>
                    </div>
                    <a href="{{ route('material.view', $m) }}" class="btn btn-sm" style="background:#fff8e6; color:#9a7d2c; border:1px solid #C9A84C55; font-size:11px;">
                        <i class="fas fa-eye"></i>
                    </a>
                </div>
                @empty
                <div class="text-muted p-3" style="font-size:13px;">No materials uploaded.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
