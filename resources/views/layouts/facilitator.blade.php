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
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    <style>
        * { font-family: 'Nunito', sans-serif; box-sizing: border-box; }
        body { background: #f4f6f9; margin: 0; }

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
            font-size: 13.5px; font-weight: 600;
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
                        <span style="font-size:0.75rem;color:#aaa;">Last 7 days highlighted</span>
                    </div>
                    <div style="max-height:420px;overflow-y:auto;" id="notif-scroll">
                        @forelse($notifItems as $i => $n)
                        <a href="{{ $n['url'] ?? '#' }}" style="text-decoration:none;color:inherit;">
                        <div class="notif-item {{ $i >= 5 ? 'notif-extra' : '' }}"
                             style="padding:10px 16px;border-bottom:1px solid #f8f9fa;display:flex;align-items:flex-start;gap:10px;{{ $n['new'] ? 'background:#fffdf5;' : '' }}{{ $i >= 5 ? 'display:none!important;' : '' }}">
                            <div style="flex-shrink:0;width:30px;height:30px;border-radius:50%;background:{{ $n['color'] }}18;display:flex;align-items:center;justify-content:center;margin-top:2px;">
                                <i class="fas {{ $n['icon'] }}" style="font-size:12px;color:{{ $n['color'] }};"></i>
                            </div>
                            <div style="min-width:0;flex:1;">
                                <div style="font-size:0.8rem;font-weight:600;color:#2d3748;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $n['title'] }}</div>
                                <div style="font-size:0.72rem;color:#888;margin-top:1px;">{{ $n['sub'] }}</div>
                                <div style="font-size:0.7rem;color:#bbb;margin-top:2px;">{{ $n['time']->diffForHumans() }}</div>
                            </div>
                            @if($n['new'])<span style="flex-shrink:0;width:7px;height:7px;border-radius:50%;background:#e53e3e;margin-top:6px;"></span>@endif
                        </div>
                        </a>
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
@yield('scripts')
</body>
</html>
