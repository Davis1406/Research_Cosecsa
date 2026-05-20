@extends('layouts.facilitator')
@section('page-title', 'Facilitator Directory')

@section('styles')
<style>
.dir-list { border-radius: 12px; border: 1.5px solid #e2e8f0; overflow: hidden; background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,.05); }

.dir-row {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 11px 18px;
    border-bottom: 1px solid #f0f2f5;
    transition: background .12s;
    text-decoration: none;
    color: inherit;
}
.dir-row:last-child { border-bottom: none; }
.dir-row:hover { background: #f8f9fc; text-decoration: none; color: inherit; }

/* Avatar */
.dir-row-avatar {
    width: 44px; height: 44px; border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
    border: 2.5px solid #C9A84C;
}
.dir-row-initials {
    width: 44px; height: 44px; border-radius: 50%;
    background: linear-gradient(135deg, #C9A84C 0%, #9a7d2c 100%);
    display: flex; align-items: center; justify-content: center;
    font-size: 17px; font-weight: 800; color: #fff;
    flex-shrink: 0;
    border: 2.5px solid #e8c96a;
}
.dir-row-initials.lead {
    background: linear-gradient(135deg, #2d3748 0%, #1a202c 100%);
    border-color: #C9A84C;
}

/* Name block */
.dir-row-info { flex: 1; min-width: 0; }
.dir-row-name {
    font-size: 14.5px; font-weight: 800; color: #1a202c;
    white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
}
.dir-row-role {
    display: inline-flex; align-items: center; gap: 4px;
    font-size: 10.5px; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.6px; padding: 2px 8px; border-radius: 20px;
    margin-top: 3px;
}
.dir-row-role.lead { background: #fef3cd; color: #9a6c00; }
.dir-row-role.reg  { background: #e8f5ee; color: #2c7a4b; }

/* Stats pills */
.dir-stats-group { display: flex; gap: 8px; align-items: center; flex-shrink: 0; }
.dir-stat-pill {
    display: flex; align-items: center; gap: 4px;
    font-size: 12px; font-weight: 700; color: #718096;
    background: #f4f6f9; border-radius: 20px;
    padding: 3px 10px; white-space: nowrap;
}
.dir-stat-pill i { font-size: 11px; }

/* Social icons row */
.dir-social-row { display: flex; gap: 4px; align-items: center; flex-shrink: 0; }
.dir-social-icon {
    width: 26px; height: 26px; border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 11px; color: #a0aec0;
    border: 1px solid #e2e8f0;
    text-decoration: none;
    transition: background .13s, color .13s, border-color .13s;
}
.dir-social-icon:hover { background: #C9A84C; color: #fff; border-color: #C9A84C; }

/* Action buttons */
.dir-actions-row { display: flex; gap: 6px; flex-shrink: 0; }
.dir-act-btn {
    padding: 5px 12px; border-radius: 7px;
    font-size: 12px; font-weight: 700;
    text-decoration: none; white-space: nowrap;
    display: flex; align-items: center; gap: 5px;
    transition: all .13s;
    border: 1.5px solid transparent;
}
.dir-act-btn.primary { background: #2d3748; color: #fff; border-color: #2d3748; }
.dir-act-btn.primary:hover { background: #1a202c; color: #fff; text-decoration: none; }
.dir-act-btn.outline { background: #fff; color: #C9A84C; border-color: #C9A84C; }
.dir-act-btn.outline:hover { background: #C9A84C; color: #fff; text-decoration: none; }

/* Search bar */
.dir-search {
    position: relative; margin-bottom: 14px;
}
.dir-search input {
    width: 100%; padding: 10px 14px 10px 38px;
    border: 1.5px solid #e2e8f0; border-radius: 10px;
    font-size: 14px; background: #fff;
    outline: none; transition: border-color .15s;
}
.dir-search input:focus { border-color: #C9A84C; }
.dir-search i {
    position: absolute; left: 13px; top: 50%; transform: translateY(-50%);
    color: #a0aec0; font-size: 14px;
}

/* hide stats/social on small screens */
@media (max-width: 768px) {
    .dir-stats-group, .dir-social-row { display: none; }
    .dir-act-btn span { display: none; }
    .dir-act-btn { padding: 5px 9px; }
}
@media (max-width: 480px) {
    .dir-row { padding: 10px 12px; gap: 10px; }
}
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 class="mb-0" style="font-weight:800; color:#1a202c; font-size:1.1rem;">
            <i class="fas fa-address-book mr-2" style="color:#C9A84C;"></i> Facilitator Directory
        </h5>
        <p class="mb-0 mt-1" style="font-size:13px; color:#718096;">
            {{ $speakers->count() }} facilitator{{ $speakers->count() != 1 ? 's' : '' }}
        </p>
    </div>
</div>

{{-- Search --}}
<div class="dir-search">
    <i class="fas fa-search"></i>
    <input type="text" id="dirSearch" placeholder="Search by name or role…" autocomplete="off">
</div>

@if($speakers->isEmpty())
    <div class="dir-list">
        <div style="padding:56px; text-align:center;">
            <i class="fas fa-users" style="font-size:40px; color:#e2e8f0; display:block; margin-bottom:12px;"></i>
            <p style="color:#a0aec0; font-size:14px;">No facilitators in the directory yet.</p>
        </div>
    </div>
@else
<div class="dir-list" id="dirList">
    @foreach($speakers as $speaker)
    @php
        $roleTitle = $speaker->user?->roles?->first()?->title ?? 'Facilitator';
        $isLead    = str_contains(strtolower($roleTitle), 'lead');
    @endphp
    <div class="dir-row dir-item"
         data-name="{{ strtolower($speaker->name) }}"
         data-role="{{ strtolower($roleTitle) }}">

        {{-- Avatar --}}
        @if($speaker->photo)
            <img src="{{ $speaker->photo->url }}" alt="{{ $speaker->name }}" class="dir-row-avatar">
        @else
            <div class="dir-row-initials {{ $isLead ? 'lead' : '' }}">{{ strtoupper(substr($speaker->name, 0, 1)) }}</div>
        @endif

        {{-- Name + role --}}
        <div class="dir-row-info">
            <div class="dir-row-name">{{ $speaker->name }}</div>
            <div class="dir-row-role {{ $isLead ? 'lead' : 'reg' }}">
                <i class="fas fa-{{ $isLead ? 'star' : 'chalkboard-teacher' }}"></i>
                {{ $roleTitle }}
            </div>
        </div>

        {{-- Stats --}}
        <div class="dir-stats-group">
            <div class="dir-stat-pill" title="Sessions">
                <i class="fas fa-calendar-alt" style="color:#C9A84C;"></i>
                {{ $speaker->schedules_count }}
            </div>
            <div class="dir-stat-pill" title="Materials">
                <i class="fas fa-book" style="color:#2c7a4b;"></i>
                {{ $speaker->materials_count }}
            </div>
        </div>

        {{-- Social icons --}}
        @php $hasSocial = $speaker->linkedin || $speaker->researchgate || $speaker->orcid || $speaker->google_scholar; @endphp
        @if($hasSocial)
        <div class="dir-social-row">
            @if($speaker->linkedin)
            <a href="{{ $speaker->linkedin }}" target="_blank" class="dir-social-icon" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
            @endif
            @if($speaker->researchgate)
            <a href="{{ $speaker->researchgate }}" target="_blank" class="dir-social-icon" title="ResearchGate" style="font-size:9px; font-weight:800;">RG</a>
            @endif
            @if($speaker->orcid)
            <a href="{{ $speaker->orcid }}" target="_blank" class="dir-social-icon" title="ORCID"><i class="fas fa-id-badge"></i></a>
            @endif
            @if($speaker->google_scholar)
            <a href="{{ $speaker->google_scholar }}" target="_blank" class="dir-social-icon" title="Google Scholar"><i class="fas fa-graduation-cap"></i></a>
            @endif
        </div>
        @else
        <div class="dir-social-row"></div>
        @endif

        {{-- Actions --}}
        <div class="dir-actions-row">
            <a href="{{ route('facilitator.directory.show', $speaker) }}" class="dir-act-btn primary">
                <i class="fas fa-user"></i><span>Profile</span>
            </a>
            @if($speaker->user_id)
            <a href="{{ route('facilitator.messages.thread', $speaker->user_id) }}" class="dir-act-btn outline">
                <i class="fas fa-comment"></i><span>Chat</span>
            </a>
            @endif
        </div>
    </div>
    @endforeach
</div>
<div id="noResults" style="display:none; padding:40px; text-align:center; color:#a0aec0; font-size:14px;">
    <i class="fas fa-search" style="font-size:28px; display:block; margin-bottom:10px; color:#e2e8f0;"></i>
    No facilitators match your search.
</div>
@endif
@endsection

@section('scripts')
<script>
document.getElementById('dirSearch').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    var items = document.querySelectorAll('.dir-item');
    var shown = 0;
    items.forEach(function(el) {
        var match = el.dataset.name.includes(q) || el.dataset.role.includes(q);
        el.style.display = match ? '' : 'none';
        if (match) shown++;
    });
    var noRes = document.getElementById('noResults');
    if (noRes) noRes.style.display = shown === 0 ? 'block' : 'none';
});
</script>
@endsection
