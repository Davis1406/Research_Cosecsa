@extends('layouts.viewer')
@section('page-title', 'Facilitators')

@section('styles')
<style>
.dir-list { border-radius: 12px; border: 1.5px solid #e2e8f0; overflow: hidden; background: #fff; box-shadow: 0 2px 10px rgba(0,0,0,.04); }
.dir-row { display: flex; align-items: center; gap: 14px; padding: 11px 18px; border-bottom: 1px solid #f0f2f5; }
.dir-row:last-child { border-bottom: none; }
.dir-row-initials {
    width: 44px; height: 44px; border-radius: 50%; flex-shrink: 0;
    display: flex; align-items: center; justify-content: center;
    font-size: 17px; font-weight: 800; color: #fff;
    background: linear-gradient(135deg, #C9A84C, #9a7d2c);
    border: 2.5px solid #e8c96a;
}
.dir-row-initials.lead { background: linear-gradient(135deg, #2d3748, #1a202c); border-color: #C9A84C; }
.dir-row-avatar { width: 44px; height: 44px; border-radius: 50%; object-fit: cover; flex-shrink: 0; border: 2.5px solid #C9A84C; }
.dir-row-info { flex: 1; min-width: 0; }
.dir-row-name { font-size: 14.5px; font-weight: 800; color: #1a202c; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.dir-row-role { display: inline-flex; align-items: center; gap: 4px; font-size: 10.5px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; padding: 2px 8px; border-radius: 20px; margin-top: 3px; }
.dir-row-role.lead { background: #fef3cd; color: #9a6c00; }
.dir-row-role.reg  { background: #e8f5ee; color: #2c7a4b; }
.dir-stat-pill { display: flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 700; color: #718096; background: #f4f6f9; border-radius: 20px; padding: 3px 10px; white-space: nowrap; }
.dir-social-icon { width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; color: #a0aec0; border: 1px solid #e2e8f0; text-decoration: none; transition: background .13s, color .13s; }
.dir-social-icon:hover { background: #C9A84C; color: #fff; border-color: #C9A84C; }
.dir-search { position: relative; margin-bottom: 14px; }
.dir-search input { width: 100%; padding: 10px 14px 10px 38px; border: 1.5px solid #e2e8f0; border-radius: 10px; font-size: 14px; outline: none; transition: border-color .15s; background: #fff; }
.dir-search input:focus { border-color: #C9A84C; }
.dir-search i { position: absolute; left: 13px; top: 50%; transform: translateY(-50%); color: #a0aec0; font-size: 14px; }
@media (max-width: 768px) {
    .hide-mobile { display: none !important; }
}
</style>
@endsection

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h5 style="font-weight:800; font-size:1.1rem; margin-bottom:2px;"><i class="fas fa-chalkboard-teacher mr-2" style="color:#C9A84C;"></i>Facilitators</h5>
        <p style="color:#718096; font-size:13px; margin:0;">{{ $speakers->count() }} facilitator{{ $speakers->count() != 1 ? 's' : '' }} in the programme</p>
    </div>
</div>

<div class="dir-search">
    <i class="fas fa-search"></i>
    <input type="text" id="dirSearch" placeholder="Search by name or role…" autocomplete="off">
</div>

@if($speakers->isEmpty())
<div class="dir-list">
    <div style="padding:56px; text-align:center;">
        <i class="fas fa-users" style="font-size:40px; color:#e2e8f0; display:block; margin-bottom:12px;"></i>
        <p style="color:#a0aec0; font-size:14px;">No facilitators yet.</p>
    </div>
</div>
@else
<div class="dir-list" id="dirList">
    @foreach($speakers as $speaker)
    @php
        $roleTitle = $speaker->user?->roles?->first()?->title ?? 'Facilitator';
        $isLead    = str_contains(strtolower($roleTitle), 'lead');
    @endphp
    <div class="dir-row dir-item" data-name="{{ strtolower($speaker->name) }}" data-role="{{ strtolower($roleTitle) }}">
        @if($speaker->photo)
            <img src="{{ $speaker->photo->url }}" alt="{{ $speaker->name }}" class="dir-row-avatar">
        @else
            <div class="dir-row-initials {{ $isLead ? 'lead' : '' }}">{{ strtoupper(substr($speaker->name, 0, 1)) }}</div>
        @endif

        <div class="dir-row-info">
            <div class="dir-row-name">{{ $speaker->name }}</div>
            <div class="dir-row-role {{ $isLead ? 'lead' : 'reg' }}">
                <i class="fas fa-{{ $isLead ? 'star' : 'chalkboard-teacher' }}"></i>{{ $roleTitle }}
            </div>
        </div>

        <div class="d-flex hide-mobile" style="gap:8px; align-items:center;">
            <div class="dir-stat-pill" title="Sessions"><i class="fas fa-calendar-alt" style="color:#C9A84C;"></i>{{ $speaker->schedules_count }}</div>
            <div class="dir-stat-pill" title="Materials"><i class="fas fa-book" style="color:#2c7a4b;"></i>{{ $speaker->materials_count }}</div>
        </div>

        @php $hasSocial = $speaker->linkedin || $speaker->researchgate || $speaker->orcid || $speaker->google_scholar; @endphp
        @if($hasSocial)
        <div class="d-flex hide-mobile" style="gap:4px;">
            @if($speaker->linkedin)<a href="{{ $speaker->linkedin }}" target="_blank" class="dir-social-icon" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>@endif
            @if($speaker->researchgate)<a href="{{ $speaker->researchgate }}" target="_blank" class="dir-social-icon" title="ResearchGate" style="font-size:9px;font-weight:800;">RG</a>@endif
            @if($speaker->orcid)<a href="{{ $speaker->orcid }}" target="_blank" class="dir-social-icon" title="ORCID"><i class="fas fa-id-badge"></i></a>@endif
            @if($speaker->google_scholar)<a href="{{ $speaker->google_scholar }}" target="_blank" class="dir-social-icon" title="Scholar"><i class="fas fa-graduation-cap"></i></a>@endif
        </div>
        @endif
    </div>
    @endforeach
</div>
<div id="noResults" style="display:none; padding:40px; text-align:center; color:#a0aec0; font-size:14px;">
    <i class="fas fa-search" style="font-size:28px; display:block; margin-bottom:10px; color:#e2e8f0;"></i>No facilitators match your search.
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
    document.getElementById('noResults').style.display = shown === 0 ? 'block' : 'none';
});
</script>
@endsection
