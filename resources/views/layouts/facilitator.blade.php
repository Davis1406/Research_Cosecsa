<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Facilitator Portal &mdash; Research Training System</title>
    <link rel="icon" type="image/png" href="{{ asset('img/cosecsa-favicon.png') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    <style>
        * { font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; box-sizing: border-box; }
        body { background: #f0f2f5; margin: 0; font-size: 14.5px; color: #1a202c; }
        h1,h2,h3,h4,h5,h6 { font-weight: 700; color: #1a202c; }
        p, .text-muted { font-size: 14px; }
        label { font-size: 13px !important; font-weight: 600 !important; color: #374151 !important; }
        .form-control { font-size: 14px; border: 1.5px solid #d1d5db; border-radius: 6px; }
        .form-control:focus { border-color: #C9A84C; box-shadow: 0 0 0 3px rgba(201,168,76,0.15); }
        .btn { font-size: 13.5px; font-weight: 600; }
        .card { border: 1.5px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,0.05); }
        .card-header { font-size: 14px; font-weight: 700; }
        .table td, .table th { font-size: 14px; }
        .badge { font-size: 11px; }

        /* ── Sidebar ── */
        .portal-sidebar {
            position: fixed;
            top: 0; left: 0;
            width: 240px;
            height: 100vh;
            background: #ffffff;
            display: flex;
            flex-direction: column;
            z-index: 200;
            overflow-y: auto;
            box-shadow: 2px 0 8px rgba(0,0,0,0.07);
            transition: transform 0.28s ease;
        }
        .portal-sidebar .sidebar-brand {
            padding: 20px 18px 16px;
            background: #f8f9fa;
            border-bottom: 1px solid rgba(201,168,76,0.3);
        }
        .portal-sidebar .sidebar-brand .brand-title {
            color: #2d3748; font-weight: 700; font-size: 13px;
            letter-spacing: 0.5px; line-height: 1.3; display: block; margin-top: 6px;
        }
        .portal-sidebar .sidebar-brand .brand-sub {
            color: #888; font-size: 11px; display: block; margin-top: 2px;
        }
        .portal-sidebar nav { padding: 8px 0; flex: 1; }
        .portal-sidebar nav a {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 20px; color: #2d3748; text-decoration: none;
            font-size: 14px; font-weight: 600;
            transition: background 0.15s, color 0.15s;
            border-left: 3px solid transparent;
        }
        .portal-sidebar nav a:hover  { background: rgba(201,168,76,0.08); color: #C9A84C; border-left-color: #C9A84C; }
        .portal-sidebar nav a.active { background: rgba(201,168,76,0.08); color: #C9A84C; border-left-color: #C9A84C; }
        .portal-sidebar nav a i { width: 18px; text-align: center; font-size: 14px; }
        .sidebar-section-label {
            padding: 8px 20px 2px; font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: 0.8px; color: #bbb;
        }
        .portal-sidebar .sidebar-logout {
            padding: 16px 18px; border-top: 1px solid #e9ecef;
        }
        .portal-sidebar .sidebar-logout a {
            display: flex; align-items: center; gap: 8px;
            color: #888; font-size: 13px; text-decoration: none;
        }
        .portal-sidebar .sidebar-logout a:hover { color: #e53e3e; }

        /* ── Overlay (mobile) ── */
        .sidebar-overlay {
            display: none;
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.45);
            z-index: 199;
            transition: opacity 0.28s ease;
        }
        .sidebar-overlay.open { display: block; }

        /* ── Main content ── */
        .portal-main {
            margin-left: 240px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .portal-topbar {
            background: #ffffff;
            padding: 0 20px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky; top: 0; z-index: 50;
            border-bottom: 2px solid #C9A84C;
            gap: 10px;
        }
        .portal-topbar .page-title {
            color: #2d3748; font-size: 15px; font-weight: 700; letter-spacing: 0.3px;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .topbar-hamburger {
            display: none;
            background: none; border: none; padding: 6px 8px;
            color: #2d3748; font-size: 18px; cursor: pointer; flex-shrink: 0;
        }
        .portal-content { padding: 24px; flex: 1; }
        .portal-footer {
            background: #f8f9fa; color: #999; font-size: 11.5px;
            text-align: center; padding: 10px;
        }
        .alert { border-radius: 6px; }
        .alert-success { background: #d4edda; border-color: #c3e6cb; color: #155724; }
        .alert-danger  { background: #f8d7da; border-color: #f5c6cb; color: #721c24; }

        /* ── Mobile breakpoint ── */
        @media (max-width: 768px) {
            .portal-sidebar {
                transform: translateX(-240px);
                box-shadow: none;
            }
            .portal-sidebar.open {
                transform: translateX(0);
                box-shadow: 4px 0 20px rgba(0,0,0,0.18);
            }
            .portal-main { margin-left: 0; }
            .topbar-hamburger { display: flex; align-items: center; justify-content: center; }
            .portal-content { padding: 16px 12px; }
            .portal-topbar { padding: 0 12px; }
            .portal-topbar .page-title { font-size: 14px; }
            /* Hide username text on very small screens */
            .topbar-username { display: none; }
        }

        /* ── Table responsive helpers ── */
        .table td, .table th { vertical-align: middle; }
        @media (max-width: 576px) {
            .card-body { padding: 14px !important; }
            h5 { font-size: 1rem !important; }
        }
    </style>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <style>
    /* ── Global Dropzone file-preview styles ───────────────────────────── */
    .dropzone { border: 2px dashed #ccc; border-radius: 8px; background: #fafafa; cursor: pointer; transition: border-color .2s, background .2s; }
    .dropzone:hover, .dropzone.dz-drag-hover { border-color: #C9A84C !important; background: #fffdf4; }
    .dropzone .dz-message { padding: 24px 16px; color: #aaa; font-size: 13px; }
    .dropzone .dz-preview { margin: 10px; }
    .dropzone .dz-preview .dz-image { width: 80px; height: 80px; border-radius: 8px; display: flex; align-items: center; justify-content: center; background: #f4f6f9; overflow: hidden; }
    .dropzone .dz-preview .dz-image img { display: none; }
    .dropzone .dz-preview .dz-file-icon { font-size: 32px; }
    .dropzone .dz-preview .dz-details { opacity: 1; background: rgba(255,255,255,.88); }
    .dropzone .dz-preview .dz-filename span, .dropzone .dz-preview .dz-size span { background: transparent; font-size: 11px; }
    .dropzone .dz-preview.dz-success .dz-success-mark { opacity: 1; }
    .dropzone .dz-preview.dz-error  .dz-error-mark   { opacity: 1; }
    .dropzone .dz-preview .dz-progress { height: 4px; background: #e9ecef; border-radius: 2px; opacity: 1 !important; top: auto; left: auto; right: auto; margin: 4px 10px 0; width: calc(100% - 20px); position: relative; }
    .dropzone .dz-preview .dz-progress .dz-upload { display: block; height: 100%; width: 0%; background: #C9A84C; border-radius: 2px; transition: width .2s ease; }
    .dropzone .dz-preview.dz-complete .dz-progress { display: none; }
    </style>
    @yield('styles')
</head>
<body>

@php
    $isLead   = auth()->user()->roles->pluck('title')->contains('Lead Facilitator');
    $facPhoto = auth()->user()->speaker?->photo?->url ?? null;
    $facInit  = strtoupper(substr(auth()->user()->name ?? 'F', 0, 1));
@endphp

{{-- Mobile sidebar overlay --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

<div class="portal-sidebar" id="portalSidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('img/cosecsa-favicon.png') }}" alt="COSECSA" style="width:36px;height:36px;border-radius:50%;border:2px solid #C9A84C;">
        <span class="brand-title">Research Training System</span>
        <span class="brand-sub">Facilitator Portal</span>
    </div>

    {{-- Sidebar user card --}}
    <div style="padding:14px 18px 10px; border-bottom:1px solid #f0f0f0; display:flex; align-items:center; gap:10px;">
        <div style="flex-shrink:0; width:42px; height:42px; border-radius:50%; border:2px solid #C9A84C; overflow:hidden; background:#f8f9fa;">
            @if($facPhoto)
                <img src="{{ $facPhoto }}" style="width:100%;height:100%;object-fit:cover;" alt="Avatar">
            @else
                <div style="width:100%;height:100%;background:#C9A84C;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;color:#fff;">{{ $facInit }}</div>
            @endif
        </div>
        <div style="min-width:0;">
            <div style="font-size:13px;font-weight:700;color:#2d3748;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
            <span style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.6px;padding:1px 8px;border-radius:10px;{{ $isLead ? 'background:rgba(201,168,76,0.2);color:#9a7d2c;' : 'background:rgba(44,122,75,0.12);color:#2c7a4b;' }}">
                {{ $isLead ? 'Lead Facilitator' : 'Facilitator' }}
            </span>
        </div>
    </div>

    <nav>
        <a href="{{ route('facilitator.dashboard') }}" class="{{ request()->routeIs('facilitator.dashboard') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="{{ route('facilitator.timetable') }}" class="{{ request()->routeIs('facilitator.timetable') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-calendar-alt"></i> Timetable
        </a>
        <a href="{{ route('facilitator.materials') }}" class="{{ request()->routeIs('facilitator.materials') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-book"></i> Materials
        </a>
        <a href="{{ route('facilitator.profile.edit') }}" class="{{ request()->routeIs('facilitator.profile*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-user-circle"></i> My Profile
        </a>
        <a href="{{ route('facilitator.presentations.index') }}" class="{{ request()->routeIs('facilitator.presentations*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-file-powerpoint"></i> Presentations
        </a>
        <a href="{{ route('facilitator.discussions.index') }}" class="{{ request()->routeIs('facilitator.discussions*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-comments"></i> Discussions
        </a>
        <a href="{{ route('facilitator.messages.index') }}" class="{{ request()->routeIs('facilitator.messages*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-envelope"></i> Messages
            @if($unreadMessages > 0)<span style="background:#e53e3e;color:#fff;font-size:9px;font-weight:700;border-radius:10px;padding:1px 6px;margin-left:4px;">{{ $unreadMessages }}</span>@endif
        </a>
        <a href="{{ route('facilitator.quizzes.index') }}" class="{{ request()->routeIs('facilitator.quizzes*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-question-circle"></i> Quizzes
        </a>
        <a href="{{ route('facilitator.directory.index') }}" class="{{ request()->routeIs('facilitator.directory*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-address-book"></i> Directory
        </a>
        <div class="sidebar-section-label">Manage</div>
        <a href="{{ route('facilitator.material-manager.index') }}" class="{{ request()->routeIs('facilitator.material-manager*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-book-open"></i> Materials
        </a>
        @if($isLead)
        <a href="{{ route('facilitator.schedule-manager.index') }}" class="{{ request()->routeIs('facilitator.schedule-manager*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-calendar-edit"></i> Timetable
        </a>
        <a href="{{ route('facilitator.trainees') }}" class="{{ request()->routeIs('facilitator.trainees*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-user-graduate"></i> Trainees
        </a>
        <a href="{{ route('facilitator.facilitators.index') }}" class="{{ request()->routeIs('facilitator.facilitators*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-chalkboard-teacher"></i> Facilitators
        </a>
        <a href="{{ route('facilitator.certificates.index') }}" class="{{ request()->routeIs('facilitator.certificates*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-certificate"></i> Certificates
        </a>
        @endif
    </nav>
    <div class="sidebar-logout">
        <a href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<div class="portal-main">
    <div class="portal-topbar">
        <div style="display:flex;align-items:center;gap:10px;min-width:0;">
            <button class="topbar-hamburger" onclick="toggleSidebar()" aria-label="Menu">
                <i class="fas fa-bars"></i>
            </button>
            <span class="page-title">@yield('page-title', 'Facilitator Portal')</span>
        </div>
        <span style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
            {{-- Notification bell (lead facilitator only) --}}
            @if($isLead)
            <div style="position:relative;" id="notif-wrapper">
                <button onclick="toggleNotif(event)" style="background:none;border:none;padding:4px 6px;cursor:pointer;position:relative;" aria-label="Notifications">
                    <i class="fas fa-bell" style="font-size:17px;color:#2d3748;"></i>
                    @if($notifCount > 0)
                    <span id="notif-badge" style="position:absolute;top:-2px;right:-2px;background:#e53e3e;color:#fff;font-size:9px;font-weight:700;border-radius:50%;width:16px;height:16px;display:flex;align-items:center;justify-content:center;line-height:1;">
                        {{ $notifCount > 9 ? '9+' : $notifCount }}
                    </span>
                    @endif
                </button>
                {{-- Dropdown panel --}}
                <div id="notif-panel" style="display:none;position:absolute;right:0;top:38px;width:320px;background:#fff;border-radius:10px;box-shadow:0 8px 30px rgba(0,0,0,0.14);z-index:999;overflow:hidden;border:1px solid #e9ecef;">
                    <div style="padding:12px 16px;border-bottom:1px solid #f0f0f0;display:flex;align-items:center;justify-content:space-between;">
                        <span style="font-weight:700;font-size:0.875rem;color:#2d3748;">Recent Activity</span>
                        @if($notifCount > 0)
                        <span id="notif-unread-pill" data-count="{{ $notifCount }}" style="font-size:0.72rem;background:#e53e3e;color:#fff;border-radius:10px;padding:1px 8px;font-weight:700;">{{ $notifCount }} unread</span>
                        @else
                        <span style="font-size:0.72rem;color:#aaa;">All caught up</span>
                        @endif
                    </div>
                    <div style="max-height:420px;overflow-y:auto;" id="notif-scroll">
                        @forelse($notifItems as $i => $n)
                        <div class="notif-item {{ $i >= 5 ? 'notif-extra' : '' }}"
                             onclick="{{ $n['new'] ? 'markNotifRead(this,\'' . $n['key'] . '\',\'' . ($n['url'] ?? '#') . '\')' : 'window.location.href=\'' . ($n['url'] ?? '#') . '\'' }}"
                             style="padding:10px 16px;border-bottom:1px solid #f8f9fa;display:flex;align-items:flex-start;gap:10px;cursor:pointer;{{ $n['new'] ? 'background:#fffdf5;' : '' }}{{ $i >= 5 ? 'display:none!important;' : '' }}">
                            <div style="flex-shrink:0;width:30px;height:30px;border-radius:50%;background:{{ $n['color'] }}18;display:flex;align-items:center;justify-content:center;margin-top:2px;">
                                <i class="fas {{ $n['icon'] }}" style="font-size:12px;color:{{ $n['color'] }};"></i>
                            </div>
                            <div style="min-width:0;flex:1;">
                                <div style="font-size:0.8rem;font-weight:600;color:#2d3748;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $n['title'] }}</div>
                                <div style="font-size:0.72rem;color:#888;margin-top:1px;">{{ $n['sub'] }}</div>
                                <div style="font-size:0.7rem;color:#bbb;margin-top:2px;">{{ $n['time']->diffForHumans() }}</div>
                            </div>
                            @if($n['new'])<span class="notif-unread-dot" style="flex-shrink:0;width:7px;height:7px;border-radius:50%;background:#e53e3e;margin-top:6px;"></span>@endif
                        </div>
                        @empty
                        <div style="padding:24px;text-align:center;color:#bbb;font-size:0.85rem;">No recent activity</div>
                        @endforelse
                        @if($notifItems->count() > 5)
                        <div style="padding:8px 16px;text-align:center;border-top:1px solid #f0f0f0;">
                            <button id="notif-show-more" onclick="toggleNotifMore()" style="background:none;border:none;color:#C9A84C;font-size:0.78rem;font-weight:700;cursor:pointer;padding:2px 8px;">
                                <i class="fas fa-chevron-down mr-1" id="notif-chevron"></i>
                                Show {{ $notifItems->count() - 5 }} more
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            @endif
            <div style="width:28px;height:28px;border-radius:50%;border:2px solid #C9A84C;overflow:hidden;flex-shrink:0;background:#C9A84C;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;">{{ $facInit }}</div>
            <span class="topbar-username" style="color:#2d3748;font-size:13px;">{{ auth()->user()->name ?? '' }}</span>
        </span>
    </div>

    <div class="portal-content">
        @if(session('message'))
            <div class="alert alert-success mb-3">{{ session('message') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mb-3">{{ session('error') }}</div>
        @endif
        @if($errors->count() > 0)
            <div class="alert alert-danger mb-3">
                <ul class="mb-0 pl-3">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </div>

    <footer class="portal-footer">
        &copy; {{ date('Y') }} COSECSA Research Training System Platform. All rights reserved.
    </footer>
</div>

<form id="logoutform" action="{{ route('logout') }}" method="POST" style="display:none;">
    {{ csrf_field() }}
</form>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script>
function toggleNotif(e) {
    e.stopPropagation();
    var panel = document.getElementById('notif-panel');
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
}
function markNotifRead(el, key, url) {
    // Immediately clear visual indicators on this item
    el.style.background = '';
    var dot = el.querySelector('.notif-unread-dot');
    if (dot) dot.remove();

    // Decrement badge
    var badge = document.getElementById('notif-badge');
    if (badge) {
        var count = parseInt(badge.textContent) - 1;
        if (count <= 0) { badge.remove(); }
        else { badge.textContent = count > 9 ? '9+' : count; }
    }
    // Update panel header pill
    var pill = document.getElementById('notif-unread-pill');
    if (pill) {
        var c = parseInt(pill.dataset.count || '1') - 1;
        if (c <= 0) {
            pill.outerHTML = '<span style="font-size:0.72rem;color:#aaa;">All caught up</span>';
        } else {
            pill.dataset.count = c;
            pill.textContent = c + ' unread';
        }
    }

    // Persist to server then navigate
    fetch("{{ route('notifications.mark-item-read') }}", {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ key: key })
    }).finally(function() { window.location.href = url; });
}
function toggleNotifMore() {
    var extras = document.querySelectorAll('.notif-extra');
    var btn = document.getElementById('notif-show-more');
    var chevron = document.getElementById('notif-chevron');
    var expanded = btn.dataset.expanded === '1';
    extras.forEach(function(el) {
        el.style.setProperty('display', expanded ? 'none' : 'flex', 'important');
    });
    btn.dataset.expanded = expanded ? '0' : '1';
    chevron.className = expanded ? 'fas fa-chevron-down mr-1' : 'fas fa-chevron-up mr-1';
    btn.querySelector ? null : null; // no-op
    // update text
    var count = extras.length;
    btn.innerHTML = (expanded ? '<i class="fas fa-chevron-down mr-1" id="notif-chevron"></i>Show ' + count + ' more' : '<i class="fas fa-chevron-up mr-1" id="notif-chevron"></i>Show less');
}
document.addEventListener('click', function(e) {
    var wrapper = document.getElementById('notif-wrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        var panel = document.getElementById('notif-panel');
        if (panel) panel.style.display = 'none';
    }
});
function toggleSidebar() {
    document.getElementById('portalSidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('open');
}
function closeSidebar() {
    document.getElementById('portalSidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('open');
}
// Close sidebar on resize to desktop
window.addEventListener('resize', function() {
    if (window.innerWidth > 768) closeSidebar();
});
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
<script>
// ── Shared Dropzone helpers (facilitator pages) ───────────────────────────
window.DZ_ICON_MAP = {
    pdf:'fa-file-pdf text-danger',
    ppt:'fa-file-powerpoint text-warning', pptx:'fa-file-powerpoint text-warning',
    doc:'fa-file-word text-primary',       docx:'fa-file-word text-primary',
    xls:'fa-file-excel text-success',      xlsx:'fa-file-excel text-success',
    mp4:'fa-file-video text-info',         mov:'fa-file-video text-info',
    mp3:'fa-file-audio text-warning',      wav:'fa-file-audio text-warning',
    ogg:'fa-file-audio text-warning',      m4a:'fa-file-audio text-warning',
    zip:'fa-file-archive text-secondary',
    png:'fa-file-image text-info', jpg:'fa-file-image text-info',
    jpeg:'fa-file-image text-info', gif:'fa-file-image text-info', webp:'fa-file-image text-info',
};
window.DZ_PREVIEW_TEMPLATE =
    '<div class="dz-preview dz-file-preview">' +
      '<div class="dz-image"><span class="dz-file-icon fas fa-file text-secondary"></span></div>' +
      '<div class="dz-details">' +
        '<div class="dz-size"><span data-dz-size></span></div>' +
        '<div class="dz-filename"><span data-dz-name></span></div>' +
      '</div>' +
      '<div class="dz-progress"><span class="dz-upload" data-dz-uploadprogress></span></div>' +
      '<div class="dz-success-mark"><i class="fas fa-check-circle" style="color:#28a745;font-size:20px;"></i></div>' +
      '<div class="dz-error-mark"><i class="fas fa-times-circle" style="color:#dc3545;font-size:20px;"></i></div>' +
      '<div class="dz-error-message"><span data-dz-errormessage></span></div>' +
    '</div>';
window.DZ_DEFAULT_MSG =
    '<i class="fas fa-cloud-upload-alt" style="font-size:32px;color:#ccc;display:block;margin-bottom:8px;"></i>' +
    'Drop file here or <strong>click to browse</strong><br>' +
    '<small style="color:#bbb;">PDF, Word, PowerPoint, Excel, images, video, audio — max 100 MB</small>';
function dzSetFileIcon(file) {
    var ext  = (file.name || '').split('.').pop().toLowerCase();
    var icon = window.DZ_ICON_MAP[ext] || 'fa-file text-secondary';
    $(file.previewElement).find('.dz-file-icon').removeClass().addClass('dz-file-icon fas ' + icon);
}
</script>
@yield('scripts')
</body>
</html>
