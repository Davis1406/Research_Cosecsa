@extends('layouts.admin')

@section('styles')
<style>
.dir-card {
    background: #fff;
    border-radius: 14px;
    border: 1px solid #e8ecf0;
    box-shadow: 0 2px 12px rgba(0,0,0,.05);
    transition: box-shadow .2s, transform .2s;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    height: 100%;
}
.dir-card:hover {
    box-shadow: 0 8px 28px rgba(0,0,0,.10);
    transform: translateY(-2px);
}
.dir-card-banner {
    height: 6px;
    background: linear-gradient(90deg, #C9A84C, #e8c96a, #C9A84C);
}
.dir-card-body { padding: 22px 20px 18px; flex: 1; }
.dir-avatar-wrap {
    position: relative;
    display: inline-block;
    margin-bottom: 14px;
}
.dir-avatar {
    width: 80px; height: 80px; border-radius: 50%;
    border: 3px solid #fff;
    box-shadow: 0 0 0 3px #C9A84C;
    object-fit: cover;
    display: block;
}
.dir-avatar-initials {
    width: 80px; height: 80px; border-radius: 50%;
    background: linear-gradient(135deg, #C9A84C 0%, #9a7d2c 100%);
    display: flex; align-items: center; justify-content: center;
    font-size: 30px; font-weight: 800; color: #fff;
    box-shadow: 0 0 0 3px #fff, 0 0 0 5px #C9A84C;
}
.dir-name {
    font-size: 16px; font-weight: 800; color: #1a202c;
    margin-bottom: 2px; line-height: 1.3;
}
.dir-role-badge {
    display: inline-block;
    font-size: 10px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.8px; padding: 2px 10px; border-radius: 20px;
    margin-bottom: 8px;
}
.dir-bio {
    font-size: 12.5px; color: #718096; line-height: 1.5;
    margin-bottom: 12px;
    display: -webkit-box; -webkit-line-clamp: 2;
    -webkit-box-orient: vertical; overflow: hidden;
}
.dir-stats {
    display: flex; gap: 0;
    border-top: 1px solid #f0f0f0;
    border-bottom: 1px solid #f0f0f0;
    margin: 0 -20px 16px; padding: 10px 20px;
    background: #fafbfc;
}
.dir-stat { flex: 1; text-align: center; }
.dir-stat-num { font-size: 18px; font-weight: 800; color: #2d3748; line-height: 1; }
.dir-stat-label { font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #a0aec0; margin-top: 2px; }
.dir-stat + .dir-stat { border-left: 1px solid #e8ecf0; }
.dir-social { display: flex; gap: 6px; margin-bottom: 14px; flex-wrap: wrap; }
.dir-social a {
    width: 28px; height: 28px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 12px; text-decoration: none;
    border: 1px solid #e8ecf0; color: #718096;
    transition: background .15s, color .15s, border-color .15s;
}
.dir-social a:hover { background: #C9A84C; color: #fff; border-color: #C9A84C; }
.dir-actions { display: flex; gap: 8px; }
.dir-btn {
    flex: 1; text-align: center; padding: 8px 10px;
    font-size: 12px; font-weight: 700; border-radius: 8px;
    text-decoration: none; transition: all .15s;
}
.dir-btn-primary {
    background: #2d3748; color: #fff; border: 1.5px solid #2d3748;
}
.dir-btn-primary:hover { background: #1a202c; color: #fff; }
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0" style="font-weight:800; color:#1a202c; font-size:1.15rem;">
            <i class="fas fa-address-book mr-2" style="color:#C9A84C;"></i> Facilitator Directory
        </h5>
        <p class="mb-0 mt-1" style="font-size:13px; color:#718096;">Browse profiles, sessions and materials of all facilitators</p>
    </div>
    <span style="background:#fff; color:#2d3748; border:1.5px solid #e8ecf0; font-size:13px; font-weight:700; padding:6px 16px; border-radius:20px;">
        {{ $speakers->count() }} facilitator{{ $speakers->count() != 1 ? 's' : '' }}
    </span>
</div>

@if($speakers->isEmpty())
    <div class="card" style="border-radius:12px; border:1px solid #e8ecf0; padding:48px; text-align:center;">
        <i class="fas fa-users" style="font-size:40px; color:#e2e8f0; margin-bottom:12px; display:block;"></i>
        <p style="color:#a0aec0; font-size:14px;">No facilitators found in the directory yet.</p>
    </div>
@else
<div class="row">
    @foreach($speakers as $speaker)
    @php
        $roleTitle = $speaker->user?->roles?->first()?->title ?? 'Facilitator';
        $isLead = str_contains(strtolower($roleTitle), 'lead');
    @endphp
    <div class="col-sm-6 col-lg-4 mb-4">
        <div class="dir-card">
            <div class="dir-card-banner" style="{{ $isLead ? 'background: linear-gradient(90deg,#9a7d2c,#C9A84C,#e8c96a,#C9A84C,#9a7d2c);' : '' }}"></div>
            <div class="dir-card-body">
                {{-- Avatar --}}
                <div class="text-center" style="margin-bottom:4px;">
                    @if($speaker->photo)
                        <img src="{{ $speaker->photo->url }}" alt="{{ $speaker->name }}" class="dir-avatar">
                    @else
                        <div class="dir-avatar-initials mx-auto">{{ strtoupper(substr($speaker->name, 0, 1)) }}</div>
                    @endif
                </div>

                {{-- Name & role --}}
                <div class="text-center">
                    <div class="dir-name">{{ $speaker->name }}</div>
                    <span class="dir-role-badge" style="{{ $isLead ? 'background:#fef3cd; color:#9a6c00;' : 'background:#e8f5ee; color:#2c7a4b;' }}">
                        <i class="fas fa-{{ $isLead ? 'star' : 'chalkboard-teacher' }} mr-1"></i>{{ $roleTitle }}
                    </span>
                </div>

                {{-- Bio/specialty --}}
                @if($speaker->twitter || $speaker->description)
                <div class="dir-bio text-center">{{ $speaker->twitter ?: $speaker->description }}</div>
                @endif

                {{-- Stats --}}
                <div class="dir-stats">
                    <div class="dir-stat">
                        <div class="dir-stat-num" style="color:#C9A84C;">{{ $speaker->schedules_count }}</div>
                        <div class="dir-stat-label">Sessions</div>
                    </div>
                    <div class="dir-stat">
                        <div class="dir-stat-num" style="color:#2c7a4b;">{{ $speaker->materials_count }}</div>
                        <div class="dir-stat-label">Materials</div>
                    </div>
                </div>

                {{-- Social icons --}}
                @php $hasSocial = $speaker->linkedin || $speaker->facebook || $speaker->researchgate || $speaker->orcid || $speaker->google_scholar; @endphp
                @if($hasSocial)
                <div class="dir-social justify-content-center">
                    @if($speaker->linkedin)
                    <a href="{{ $speaker->linkedin }}" target="_blank" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    @endif
                    @if($speaker->facebook)
                    <a href="{{ $speaker->facebook }}" target="_blank" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    @endif
                    @if($speaker->researchgate)
                    <a href="{{ $speaker->researchgate }}" target="_blank" title="ResearchGate" style="font-size:10px; font-weight:800;">RG</a>
                    @endif
                    @if($speaker->orcid)
                    <a href="{{ $speaker->orcid }}" target="_blank" title="ORCID"><i class="fas fa-id-badge"></i></a>
                    @endif
                    @if($speaker->google_scholar)
                    <a href="{{ $speaker->google_scholar }}" target="_blank" title="Google Scholar"><i class="fas fa-graduation-cap"></i></a>
                    @endif
                </div>
                @endif

                {{-- Actions --}}
                <div class="dir-actions">
                    <a href="{{ route('admin.directory.show', $speaker) }}" class="dir-btn dir-btn-primary">
                        <i class="fas fa-user mr-1"></i> View Profile
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection
