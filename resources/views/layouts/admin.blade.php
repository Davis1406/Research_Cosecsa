<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ trans('panel.site_title') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('img/cosecsa-favicon.png') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/select/1.3.0/css/select.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/1.2.4/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.css" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />

    <style>
        /* ── Base ── */
        * { font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif !important; box-sizing: border-box; }
        .fa, .fas, .far, .fab, .fal,
        [class^="fa-"], [class*=" fa-"] { font-family: 'Font Awesome 5 Free', 'Font Awesome 5 Brands', 'Font Awesome 5 Solid' !important; }
        body { background: #f0f2f5; margin: 0; font-size: 14.5px; color: #1a202c; }
        h1,h2,h3,h4,h5,h6 { font-weight: 700; color: #1a202c; }
        p, .text-muted { font-size: 14px; }
        label { font-size: 13px !important; font-weight: 600 !important; color: #374151 !important; }
        .form-control { font-size: 14px !important; border: 1.5px solid #d1d5db !important; border-radius: 6px !important; }
        .form-control:focus { border-color: #a02626 !important; box-shadow: 0 0 0 3px rgba(160,38,38,0.12) !important; }
        .btn { font-size: 13.5px !important; font-weight: 600 !important; }
        .card { border: 1.5px solid #e2e8f0 !important; box-shadow: 0 2px 8px rgba(0,0,0,0.05) !important; }
        .card-header { font-size: 14px !important; font-weight: 700 !important; }
        .table td, .table th { font-size: 14px !important; vertical-align: middle !important; }
        .badge { font-size: 11px !important; }
        .small, small { font-size: 12.5px !important; }
        .alert { border-radius: 6px !important; }
        .alert-success { background: #d4edda !important; border-color: #c3e6cb !important; color: #155724 !important; }
        .alert-danger  { background: #f8d7da !important; border-color: #f5c6cb !important; color: #721c24 !important; }

        /* Burgundy button / badge helpers */
        .btn-cosecsa { background: #a02626 !important; color: #fff !important; border: none !important; }
        .btn-cosecsa:hover { background: #7a1a1a !important; color: #fff !important; }
        .cosecsa-card-header { background: #a02626 !important; color: #fff !important; }

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
        .sidebar-brand {
            padding: 18px 18px 14px;
            background: #f8f9fa;
            border-bottom: 1px solid rgba(160,38,38,0.2);
            flex-shrink: 0;
        }
        .sidebar-brand .brand-title {
            color: #2d3748; font-weight: 700; font-size: 13px;
            letter-spacing: 0.4px; line-height: 1.3; display: block; margin-top: 6px;
        }
        .sidebar-brand .brand-sub {
            color: #999; font-size: 10.5px; display: block; margin-top: 2px;
        }
        .sidebar-user {
            padding: 12px 16px 10px;
            border-bottom: 1px solid #f0f0f0;
            display: flex; align-items: center; gap: 10px;
            flex-shrink: 0;
        }
        .sidebar-user-avatar {
            width: 40px; height: 40px; border-radius: 50%;
            border: 2px solid #a02626; overflow: hidden; flex-shrink: 0;
            background: #a02626; display: flex; align-items: center;
            justify-content: center; font-size: 16px; font-weight: 700; color: #fff;
        }
        .portal-sidebar nav { padding: 6px 0; flex: 1; }
        .portal-sidebar nav a {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 18px; color: #4a5568; text-decoration: none;
            font-size: 13.5px; font-weight: 600;
            transition: background 0.14s, color 0.14s;
            border-left: 3px solid transparent;
        }
        .portal-sidebar nav a:hover  { background: rgba(160,38,38,.07); color: #a02626; border-left-color: #a02626; text-decoration: none; }
        .portal-sidebar nav a.active { background: rgba(160,38,38,.10); color: #a02626; border-left-color: #a02626; }
        .portal-sidebar nav a i { width: 18px; text-align: center; font-size: 13px; color: inherit; }
        .sidebar-section-label {
            padding: 10px 18px 2px;
            font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .7px; color: #bbb;
        }
        /* Submenu (treeview) */
        .sidebar-submenu { display: none; background: #f8f9fa; }
        .sidebar-submenu.open { display: block; }
        .sidebar-submenu a {
            padding: 8px 18px 8px 42px !important;
            font-size: 13px !important;
        }
        .portal-sidebar .sidebar-footer {
            padding: 14px 16px;
            border-top: 1px solid #e9ecef;
            flex-shrink: 0;
        }
        .portal-sidebar .sidebar-footer a {
            display: flex; align-items: center; gap: 8px;
            color: #888; font-size: 13px; text-decoration: none; font-weight: 600;
        }
        .portal-sidebar .sidebar-footer a:hover { color: #e53e3e; }

        /* ── Mobile overlay ── */
        .sidebar-overlay {
            display: none; position: fixed; inset: 0;
            background: rgba(0,0,0,0.45); z-index: 199;
        }
        .sidebar-overlay.open { display: block; }

        /* ── Main area ── */
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
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 150;
            border-bottom: 2px solid #a02626;
            gap: 10px;
        }
        .portal-topbar .page-title {
            color: #2d3748; font-size: 15px; font-weight: 700;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .topbar-hamburger {
            display: none;
            background: none; border: none; padding: 6px 8px;
            color: #2d3748; font-size: 18px; cursor: pointer; flex-shrink: 0;
        }
        .portal-content { padding: 22px 24px; flex: 1; }

        /* content-header + section.content shims (keeps admin views unchanged) */
        .content-header { padding: 0 0 12px 0 !important; }
        .content-header h1 { font-size: 1.2rem !important; font-weight: 800 !important; margin: 0 !important; }
        section.content, .content { padding: 0 !important; }
        .container-fluid { padding-left: 0 !important; padding-right: 0 !important; }

        .portal-footer {
            background: #f8f9fa; color: #999; font-size: 11.5px;
            text-align: center; padding: 10px; border-top: 1px solid #e9ecef;
        }

        /* ── Topbar right ── */
        .topbar-username { color: #2d3748; font-size: 13px; font-weight: 600; }
        .topbar-user-avatar {
            width: 30px; height: 30px; border-radius: 50%;
            border: 2px solid #a02626; background: #a02626;
            display: flex; align-items: center; justify-content: center;
            font-size: 12px; font-weight: 700; color: #fff; flex-shrink: 0;
        }

        /* ── Mobile ── */
        @media (max-width: 768px) {
            .portal-sidebar { transform: translateX(-240px); box-shadow: none; }
            .portal-sidebar.open { transform: translateX(0); box-shadow: 4px 0 20px rgba(0,0,0,0.18); }
            .portal-main { margin-left: 0; }
            .topbar-hamburger { display: flex; align-items: center; justify-content: center; }
            .portal-content { padding: 16px 12px; }
            .topbar-username { display: none; }
        }

        /* ── DataTable & Select2 accent tweaks ── */
        .dataTables_wrapper .dataTables_paginate .paginate_button.current,
        .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
            background: #a02626 !important; color: #fff !important; border-color: #a02626 !important;
        }
        .select2-container--default .select2-results__option--highlighted { background: #a02626 !important; }
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--focus .select2-selection--multiple {
            border-color: #a02626 !important;
        }
    </style>
    @yield('styles')
</head>
<body>

@php
    $adminInit = strtoupper(substr(auth()->user()->name ?? 'A', 0, 1));
    $isUserMgmt = request()->is('admin/permissions*') || request()->is('admin/roles*') || request()->is('admin/users*');
@endphp

{{-- Mobile overlay --}}
<div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>

{{-- ── Sidebar ── --}}
<aside class="portal-sidebar" id="portalSidebar">

    {{-- Brand --}}
    <div class="sidebar-brand">
        <img src="{{ asset('img/cosecsa-favicon.png') }}" alt="COSECSA"
             style="width:36px;height:36px;border-radius:50%;border:2px solid #a02626;">
        <span class="brand-title">Research Training System</span>
        <span class="brand-sub">Admin Portal</span>
    </div>

    {{-- User card --}}
    <div class="sidebar-user">
        <div class="sidebar-user-avatar">{{ $adminInit }}</div>
        <div style="min-width:0;">
            <div style="font-size:13px;font-weight:700;color:#2d3748;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                {{ auth()->user()->name ?? 'Admin' }}
            </div>
            <span style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;padding:1px 8px;border-radius:10px;background:rgba(160,38,38,0.12);color:#a02626;">
                {{ auth()->user()->roles->first()?->title ?? 'Admin' }}
            </span>
        </div>
    </div>

    {{-- Navigation --}}
    <nav>
        <a href="{{ route('admin.home') }}"
           class="{{ request()->routeIs('admin.home') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>

        @can('trainee_access')
        <a href="{{ route('admin.trainees.index') }}"
           class="{{ request()->is('admin/trainees*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-user-graduate"></i> Trainees
        </a>
        @endcan

        @can('speaker_access')
        <a href="{{ route('admin.speakers.index') }}"
           class="{{ request()->is('admin/speakers*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-chalkboard-teacher"></i> Facilitators
        </a>
        @endcan

        @can('training_material_access')
        <a href="{{ route('admin.training-materials.index') }}"
           class="{{ request()->is('admin/training-materials*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-book"></i> Materials
        </a>
        @endcan

        @can('schedule_access')
        <a href="{{ route('admin.schedules.index') }}"
           class="{{ request()->is('admin/schedules*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-calendar-alt"></i> Timetable
        </a>
        @endcan

        <a href="{{ route('admin.quizzes.index') }}"
           class="{{ request()->is('admin/quizzes*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-question-circle"></i> Quizzes
        </a>

        <a href="{{ route('admin.discussions.index') }}"
           class="{{ request()->is('admin/discussions*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-comments"></i> Discussions
        </a>

        <a href="{{ route('admin.messages.index') }}"
           class="{{ request()->is('admin/messages*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-envelope"></i> Messages
            @if(!empty($unreadMessages) && $unreadMessages > 0)
            <span style="background:#e53e3e;color:#fff;font-size:9px;font-weight:700;border-radius:10px;padding:1px 6px;margin-left:auto;">{{ $unreadMessages }}</span>
            @endif
        </a>

        <a href="{{ route('admin.certificates.index') }}"
           class="{{ request()->is('admin/certificates*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-certificate"></i> Certificates
        </a>

        <a href="{{ route('admin.directory.index') }}"
           class="{{ request()->is('admin/directory*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-address-book"></i> Directory
        </a>

        @can('user_management_access')
        <div class="sidebar-section-label">System</div>
        <a href="#" onclick="toggleSubmenu(event,'user-mgmt')"
           class="{{ $isUserMgmt ? 'active' : '' }}" style="justify-content:space-between;">
            <span style="display:flex;align-items:center;gap:10px;">
                <i class="fas fa-users-cog"></i> User Management
            </span>
            <i class="fas fa-chevron-right" id="user-mgmt-chevron"
               style="font-size:10px;transition:transform .2s;{{ $isUserMgmt ? 'transform:rotate(90deg)' : '' }}"></i>
        </a>
        <div class="sidebar-submenu {{ $isUserMgmt ? 'open' : '' }}" id="user-mgmt-submenu">
            @can('user_access')
            <a href="{{ route('admin.users.index') }}"
               class="{{ request()->is('admin/users*') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="fas fa-user"></i> Users
            </a>
            @endcan
            @can('role_access')
            <a href="{{ route('admin.roles.index') }}"
               class="{{ request()->is('admin/roles*') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="fas fa-briefcase"></i> Roles
            </a>
            @endcan
            @can('permission_access')
            <a href="{{ route('admin.permissions.index') }}"
               class="{{ request()->is('admin/permissions*') ? 'active' : '' }}" onclick="closeSidebar()">
                <i class="fas fa-unlock-alt"></i> Permissions
            </a>
            @endcan
        </div>
        @endcan

        @can('setting_access')
        <a href="{{ route('admin.settings.index') }}"
           class="{{ request()->is('admin/settings*') ? 'active' : '' }}" onclick="closeSidebar()">
            <i class="fas fa-cogs"></i> Settings
        </a>
        @endcan
    </nav>

    <div class="sidebar-footer">
        <a href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</aside>

{{-- ── Main ── --}}
<div class="portal-main">

    {{-- Topbar --}}
    <div class="portal-topbar">
        <div style="display:flex;align-items:center;gap:10px;min-width:0;">
            <button class="topbar-hamburger" onclick="toggleSidebar()" aria-label="Menu">
                <i class="fas fa-bars"></i>
            </button>
            <span class="page-title">
                <img src="{{ asset('img/cosecsa-favicon.png') }}" alt=""
                     style="width:20px;height:20px;border-radius:50%;border:1px solid #a02626;margin-right:6px;vertical-align:middle;">
                COSECSA Research Training System
            </span>
        </div>

        <div style="display:flex;align-items:center;gap:14px;flex-shrink:0;">
            {{-- Notification bell --}}
            <div style="position:relative;" id="admin-notif-wrapper">
                <button onclick="toggleAdminNotif(event)"
                        style="background:none;border:none;padding:4px 6px;cursor:pointer;position:relative;" aria-label="Notifications">
                    <i class="fas fa-bell" style="font-size:17px;color:#2d3748;"></i>
                    @if(!empty($notifCount) && $notifCount > 0)
                    <span id="notif-badge" style="position:absolute;top:-2px;right:-2px;background:#e53e3e;color:#fff;font-size:9px;font-weight:700;border-radius:50%;width:16px;height:16px;display:flex;align-items:center;justify-content:center;line-height:1;">
                        {{ $notifCount > 9 ? '9+' : $notifCount }}
                    </span>
                    @endif
                </button>
                {{-- Notification dropdown --}}
                <div id="admin-notif-panel" style="display:none;position:absolute;right:0;top:42px;width:320px;background:#fff;border-radius:10px;box-shadow:0 8px 30px rgba(0,0,0,0.14);z-index:9999;overflow:hidden;border:1px solid #e9ecef;">
                    <div style="padding:10px 16px;border-bottom:1px solid #f0f0f0;display:flex;align-items:center;justify-content:space-between;background:#f8f9fa;">
                        <span style="font-weight:700;font-size:0.85rem;color:#2d3748;">Recent Activity</span>
                        @if(!empty($notifCount) && $notifCount > 0)
                        <span id="admin-notif-unread-pill" data-count="{{ $notifCount }}" style="font-size:0.72rem;background:#a02626;color:#fff;border-radius:10px;padding:1px 8px;font-weight:700;">{{ $notifCount }} unread</span>
                        @else
                        <span style="font-size:0.72rem;color:#aaa;">All caught up</span>
                        @endif
                    </div>
                    <div style="max-height:380px;overflow-y:auto;" id="admin-notif-scroll">
                        @forelse($notifItems ?? [] as $i => $n)
                        <div class="admin-notif-item {{ $i >= 5 ? 'admin-notif-extra' : '' }}"
                             onclick="@if($n['new'] ?? false)markAdminNotifRead(this,'{{ $n['key'] }}','{{ $n['url'] ?? '#' }}')@else window.location.href='{{ $n['url'] ?? '#' }}'@endif"
                             style="padding:10px 16px;border-bottom:1px solid #f8f9fa;display:flex;align-items:flex-start;gap:10px;cursor:pointer;{{ ($n['new'] ?? false) ? 'background:#fff5f5;' : '' }}{{ $i >= 5 ? 'display:none!important;' : '' }}">
                            <div style="flex-shrink:0;width:28px;height:28px;border-radius:50%;background:{{ $n['color'] }}18;display:flex;align-items:center;justify-content:center;margin-top:2px;">
                                <i class="fas {{ $n['icon'] }}" style="font-size:11px;color:{{ $n['color'] }};"></i>
                            </div>
                            <div style="min-width:0;flex:1;">
                                <div style="font-size:0.78rem;font-weight:600;color:#2d3748;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $n['title'] }}</div>
                                <div style="font-size:0.7rem;color:#888;margin-top:1px;">{{ $n['sub'] }}</div>
                                <div style="font-size:0.68rem;color:#bbb;margin-top:2px;">{{ $n['time']->diffForHumans() }}</div>
                            </div>
                            @if($n['new'] ?? false)<span class="admin-notif-unread-dot" style="flex-shrink:0;width:6px;height:6px;border-radius:50%;background:#a02626;margin-top:7px;"></span>@endif
                        </div>
                        @empty
                        <div style="padding:24px;text-align:center;color:#bbb;font-size:0.85rem;">No recent activity</div>
                        @endforelse
                        @if(!empty($notifItems) && $notifItems->count() > 5)
                        <div style="padding:8px 16px;text-align:center;border-top:1px solid #f0f0f0;">
                            <button id="admin-notif-show-more" onclick="toggleAdminNotifMore()" style="background:none;border:none;color:#a02626;font-size:0.78rem;font-weight:700;cursor:pointer;padding:2px 8px;">
                                <i class="fas fa-chevron-down mr-1" id="admin-notif-chevron"></i>
                                Show {{ $notifItems->count() - 5 }} more
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- User badge --}}
            <div class="topbar-user-avatar">{{ $adminInit }}</div>
            <span class="topbar-username">{{ auth()->user()->name ?? 'Admin' }}</span>
        </div>
    </div>

    {{-- Content --}}
    <div class="portal-content">
        @if(session('message'))
            <div class="alert alert-success alert-dismissible fade show mb-3">
                {{ session('message') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show mb-3">
                {{ session('error') }}
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
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

{{-- Scripts --}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/select/1.3.0/js/dataTables.select.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.2.4/js/buttons.flash.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.2.4/js/buttons.colVis.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
<script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/11.0.1/classic/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.1/min/dropzone.min.js"></script>
<script src="{{ asset('js/main.js') }}"></script>

<script>
// ── DataTables global config ──────────────────────────────────────────────
$(function () {
    let copyButtonTrans  = '{{ trans('global.datatables.copy') }}'
    let csvButtonTrans   = '{{ trans('global.datatables.csv') }}'
    let excelButtonTrans = '{{ trans('global.datatables.excel') }}'
    let pdfButtonTrans   = '{{ trans('global.datatables.pdf') }}'
    let printButtonTrans = '{{ trans('global.datatables.print') }}'
    let colvisButtonTrans= '{{ trans('global.datatables.colvis') }}'
    let languages = { 'en': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/English.json' };

    $.extend(true, $.fn.dataTable.Buttons.defaults.dom.button, { className: 'btn' });
    $.extend(true, $.fn.dataTable.defaults, {
        language: { url: languages['{{ app()->getLocale() }}'] },
        columnDefs: [
            { orderable: false, className: 'select-checkbox', targets: 0 },
            { orderable: false, searchable: false, targets: -1 }
        ],
        select: { style: 'multi+shift', selector: 'td:first-child' },
        order: [], scrollX: true, pageLength: 100,
        dom: 'lBfrtip<"actions">',
        buttons: [
            { extend: 'copy',   className: 'btn-default', text: copyButtonTrans,   exportOptions: { columns: ':visible' } },
            { extend: 'csv',    className: 'btn-default', text: csvButtonTrans,    exportOptions: { columns: ':visible' } },
            { extend: 'excel',  className: 'btn-default', text: excelButtonTrans,  exportOptions: { columns: ':visible' } },
            { extend: 'pdf',    className: 'btn-default', text: pdfButtonTrans,    exportOptions: { columns: ':visible' } },
            { extend: 'print',  className: 'btn-default', text: printButtonTrans,  exportOptions: { columns: ':visible' } },
            { extend: 'colvis', className: 'btn-default', text: colvisButtonTrans, exportOptions: { columns: ':visible' } }
        ]
    });
    $.fn.dataTable.ext.classes.sPageButton = '';

    // ── Action menu (DataTables-compatible) ──────────────────────────────
    $(document).on('click', '.action-menu-btn', function (e) {
        e.stopPropagation();
        var $menu = $(this).closest('td').find('.action-menu');
        var wasVisible = $menu.is(':visible');
        $('.action-menu').hide();
        if (!wasVisible) {
            var r = this.getBoundingClientRect(), mw = 145;
            var left = r.right - mw; if (left < 4) left = r.left;
            $menu.css({ top: r.bottom + 2, left: left }).show();
        }
    });
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.action-menu, .action-menu-btn').length) $('.action-menu').hide();
    });
    $(window).on('scroll resize', function () { $('.action-menu').hide(); });
});

// ── Sidebar toggle ────────────────────────────────────────────────────────
function toggleSidebar() {
    document.getElementById('portalSidebar').classList.toggle('open');
    document.getElementById('sidebarOverlay').classList.toggle('open');
}
function closeSidebar() {
    document.getElementById('portalSidebar').classList.remove('open');
    document.getElementById('sidebarOverlay').classList.remove('open');
}
window.addEventListener('resize', function () { if (window.innerWidth > 768) closeSidebar(); });

// ── Submenu (User Management) ─────────────────────────────────────────────
function toggleSubmenu(e, id) {
    e.preventDefault();
    var sub = document.getElementById(id + '-submenu');
    var chev = document.getElementById(id + '-chevron');
    sub.classList.toggle('open');
    chev.style.transform = sub.classList.contains('open') ? 'rotate(90deg)' : '';
}

// ── Notification bell ─────────────────────────────────────────────────────
function toggleAdminNotif(e) {
    e.preventDefault(); e.stopPropagation();
    var panel = document.getElementById('admin-notif-panel');
    panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
}
function markAdminNotifRead(el, key, url) {
    el.style.background = '';
    var dot = el.querySelector('.admin-notif-unread-dot');
    if (dot) dot.remove();

    var badge = document.getElementById('notif-badge');
    if (badge) {
        var count = parseInt(badge.textContent) - 1;
        if (count <= 0) badge.remove();
        else badge.textContent = count > 9 ? '9+' : count;
    }
    var pill = document.getElementById('admin-notif-unread-pill');
    if (pill) {
        var c = parseInt(pill.dataset.count || '1') - 1;
        if (c <= 0) pill.outerHTML = '<span style="font-size:0.72rem;color:#aaa;">All caught up</span>';
        else { pill.dataset.count = c; pill.textContent = c + ' unread'; }
    }
    fetch("{{ route('notifications.mark-item-read') }}", {
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' },
        body: JSON.stringify({ key: key })
    }).finally(function () { window.location.href = url; });
}
function toggleAdminNotifMore() {
    var extras = document.querySelectorAll('.admin-notif-extra');
    var btn = document.getElementById('admin-notif-show-more');
    var expanded = btn.dataset.expanded === '1';
    extras.forEach(function (el) { el.style.setProperty('display', expanded ? 'none' : 'flex', 'important'); });
    btn.dataset.expanded = expanded ? '0' : '1';
    var count = extras.length;
    btn.innerHTML = expanded
        ? '<i class="fas fa-chevron-down mr-1" id="admin-notif-chevron"></i>Show ' + count + ' more'
        : '<i class="fas fa-chevron-up mr-1" id="admin-notif-chevron"></i>Show less';
}
document.addEventListener('click', function (e) {
    var wrapper = document.getElementById('admin-notif-wrapper');
    if (wrapper && !wrapper.contains(e.target)) {
        var panel = document.getElementById('admin-notif-panel');
        if (panel) panel.style.display = 'none';
    }
});
</script>
@yield('scripts')
</body>
</html>
