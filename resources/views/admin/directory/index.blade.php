@extends('layouts.admin')

@section('styles')
<style>
.dir-list { border-radius: 10px; border: 1.5px solid #dee2e6; overflow: hidden; background: #fff; box-shadow: 0 2px 8px rgba(0,0,0,.04); }
.dir-row { display: flex; align-items: center; gap: 14px; padding: 11px 18px; border-bottom: 1px solid #f0f2f5; transition: background .12s; }
.dir-row:last-child { border-bottom: none; }
.dir-row:hover { background: #f8f9fc; }
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
.dir-row-name { font-size: 14px; font-weight: 700; color: #1a202c; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
.dir-row-role { display: inline-flex; align-items: center; gap: 4px; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; padding: 2px 8px; border-radius: 20px; margin-top: 3px; }
.dir-row-role.lead { background: #fef3cd; color: #9a6c00; }
.dir-row-role.reg  { background: #e8f5ee; color: #2c7a4b; }
.stat-pill { display: inline-flex; align-items: center; gap: 4px; font-size: 12px; font-weight: 700; color: #718096; background: #f4f6f9; border-radius: 20px; padding: 3px 10px; }
.dir-social-icon { width: 26px; height: 26px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 11px; color: #a0aec0; border: 1px solid #dee2e6; text-decoration: none; transition: background .13s, color .13s; }
.dir-social-icon:hover { background: #C9A84C; color: #fff; border-color: #C9A84C; }
.dir-act-btn { padding: 5px 12px; border-radius: 7px; font-size: 12px; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 5px; border: 1.5px solid transparent; transition: all .12s; white-space: nowrap; }
.dir-act-btn.primary { background: #2d3748; color: #fff; border-color: #2d3748; }
.dir-act-btn.primary:hover { background: #1a202c; color: #fff; text-decoration: none; }
.dir-act-btn.gold { background: #fff; color: #C9A84C; border-color: #C9A84C; }
.dir-act-btn.gold:hover { background: #C9A84C; color: #fff; text-decoration: none; }
</style>
@endsection

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <h1 class="m-0" style="font-size:1.2rem; font-weight:800;">
                <i class="fas fa-address-book mr-2" style="color:#C9A84C;"></i>Facilitator Directory
            </h1>
            <span style="background:#fff; color:#2d3748; border:1.5px solid #dee2e6; font-size:13px; font-weight:700; padding:5px 14px; border-radius:20px;">
                {{ $speakers->count() }} facilitator{{ $speakers->count() != 1 ? 's' : '' }}
            </span>
        </div>
    </div>
</div>

<section class="content">
<div class="container-fluid">

    @if(session('message'))
    <div class="alert alert-success alert-dismissible fade show py-2">
        {{ session('message') }}<button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    @endif

    {{-- Search --}}
    <div style="position:relative; margin-bottom:14px;">
        <i class="fas fa-search" style="position:absolute;left:13px;top:50%;transform:translateY(-50%);color:#adb5bd;font-size:14px;"></i>
        <input type="text" id="dirSearch" placeholder="Search by name or role…"
               class="form-control" style="padding-left:38px; border-radius:8px; border:1.5px solid #dee2e6;"
               onfocus="this.style.borderColor='#C9A84C'" onblur="this.style.borderColor='#dee2e6'">
    </div>

    @if($speakers->isEmpty())
    <div class="dir-list">
        <div style="padding:60px; text-align:center;">
            <i class="fas fa-users" style="font-size:40px; color:#e2e8f0; display:block; margin-bottom:12px;"></i>
            <p style="color:#adb5bd; font-size:14px;">No facilitators in the directory yet.</p>
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

            <div class="d-none d-md-flex" style="gap:8px; align-items:center;">
                <div class="stat-pill" title="Sessions"><i class="fas fa-calendar-alt" style="color:#C9A84C;"></i>{{ $speaker->schedules_count }}</div>
                <div class="stat-pill" title="Materials"><i class="fas fa-book" style="color:#2c7a4b;"></i>{{ $speaker->materials_count }}</div>
            </div>

            @php $hasSocial = $speaker->linkedin || $speaker->researchgate || $speaker->orcid || $speaker->google_scholar; @endphp
            @if($hasSocial)
            <div class="d-none d-lg-flex" style="gap:4px;">
                @if($speaker->linkedin)<a href="{{ $speaker->linkedin }}" target="_blank" class="dir-social-icon"><i class="fab fa-linkedin-in"></i></a>@endif
                @if($speaker->researchgate)<a href="{{ $speaker->researchgate }}" target="_blank" class="dir-social-icon" style="font-size:9px;font-weight:800;">RG</a>@endif
                @if($speaker->orcid)<a href="{{ $speaker->orcid }}" target="_blank" class="dir-social-icon"><i class="fas fa-id-badge"></i></a>@endif
                @if($speaker->google_scholar)<a href="{{ $speaker->google_scholar }}" target="_blank" class="dir-social-icon"><i class="fas fa-graduation-cap"></i></a>@endif
            </div>
            @endif

            <div style="display:flex; gap:6px; flex-shrink:0;">
                <a href="{{ route('admin.directory.show', $speaker) }}" class="dir-act-btn primary">
                    <i class="fas fa-user"></i><span class="d-none d-sm-inline">Profile</span>
                </a>
                <a href="{{ route('admin.speakers.edit', $speaker) }}" class="dir-act-btn gold">
                    <i class="fas fa-edit"></i><span class="d-none d-sm-inline">Edit</span>
                </a>
            </div>
        </div>
        @endforeach
    </div>
    <div id="noResults" style="display:none; padding:40px; text-align:center; color:#adb5bd; font-size:14px;">
        <i class="fas fa-search" style="font-size:28px; display:block; margin-bottom:10px; color:#e2e8f0;"></i>
        No facilitators match your search.
    </div>
    @endif

</div>
</section>
@endsection

@section('scripts')
<script>
document.getElementById('dirSearch').addEventListener('input', function() {
    var q = this.value.toLowerCase();
    var shown = 0;
    document.querySelectorAll('.dir-item').forEach(function(el) {
        var match = el.dataset.name.includes(q) || el.dataset.role.includes(q);
        el.style.display = match ? '' : 'none';
        if (match) shown++;
    });
    document.getElementById('noResults').style.display = shown === 0 ? 'block' : 'none';
});
</script>
@endsection
