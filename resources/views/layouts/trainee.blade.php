<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Trainee Portal &mdash; COSECSA Research Training</title>
    <link rel="icon" type="image/png" href="{{ asset('img/cosecsa-favicon.png') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet" />
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet" />
    <style>
        * { font-family: 'Nunito', sans-serif; }
        body { background: #f4f6f9; margin: 0; }

        /* Sidebar */
        .portal-sidebar {
            position: fixed;
            top: 0; left: 0;
            width: 240px;
            height: 100vh;
            background: #252525;
            display: flex;
            flex-direction: column;
            z-index: 100;
            overflow-y: auto;
        }
        .portal-sidebar .sidebar-brand {
            padding: 20px 18px 16px;
            border-bottom: 1px solid rgba(201,168,76,0.3);
        }
        .portal-sidebar .sidebar-brand .brand-title {
            color: #C9A84C;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 0.5px;
            line-height: 1.3;
            display: block;
            margin-top: 6px;
        }
        .portal-sidebar .sidebar-brand .brand-sub {
            color: rgba(255,255,255,0.5);
            font-size: 11px;
            display: block;
            margin-top: 2px;
        }
        .portal-sidebar .sidebar-role-badge {
            margin: 12px 18px;
            background: rgba(160,38,38,0.25);
            border: 1px solid rgba(160,38,38,0.4);
            border-radius: 4px;
            padding: 5px 10px;
            color: #f5c6c6;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            text-align: center;
        }
        .portal-sidebar nav { padding: 8px 0; flex: 1; }
        .portal-sidebar nav a {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            color: rgba(255,255,255,0.75);
            text-decoration: none;
            font-size: 13.5px;
            font-weight: 600;
            transition: background 0.15s, color 0.15s;
            border-left: 3px solid transparent;
        }
        .portal-sidebar nav a:hover,
        .portal-sidebar nav a.active {
            background: rgba(201,168,76,0.1);
            color: #C9A84C;
            border-left-color: #C9A84C;
        }
        .portal-sidebar nav a i { width: 18px; text-align: center; font-size: 14px; }
        .portal-sidebar .sidebar-logout {
            padding: 16px 18px;
            border-top: 1px solid rgba(255,255,255,0.08);
        }
        .portal-sidebar .sidebar-logout a {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(255,255,255,0.5);
            font-size: 13px;
            text-decoration: none;
        }
        .portal-sidebar .sidebar-logout a:hover { color: #e74c3c; }

        /* Main content */
        .portal-main {
            margin-left: 240px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .portal-topbar {
            background: #252525;
            padding: 0 24px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
            border-bottom: 2px solid #a02626;
        }
        .portal-topbar .page-title {
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .portal-topbar .user-info {
            color: rgba(255,255,255,0.8);
            font-size: 13px;
        }
        .portal-content {
            padding: 24px;
            flex: 1;
        }
        .portal-footer {
            background: #252525;
            color: rgba(255,255,255,0.4);
            font-size: 11.5px;
            text-align: center;
            padding: 10px;
        }

        /* Alert styles */
        .alert { border-radius: 6px; }
        .alert-success { background: #d4edda; border-color: #c3e6cb; color: #155724; }
        .alert-danger  { background: #f8d7da; border-color: #f5c6cb; color: #721c24; }
    </style>
    @yield('styles')
</head>
<body>

@php $traineeInit = strtoupper(substr(auth()->user()->name ?? 'T', 0, 1)); @endphp

<div class="portal-sidebar">
    <div class="sidebar-brand">
        <img src="{{ asset('img/cosecsa-favicon.png') }}" alt="COSECSA" style="width:36px;height:36px;border-radius:50%;border:2px solid #C9A84C;">
        <span class="brand-title">COSECSA Research Training</span>
        <span class="brand-sub">Trainee Portal</span>
    </div>

    {{-- Sidebar user card --}}
    <div style="padding:14px 18px 10px; border-bottom:1px solid rgba(255,255,255,0.08); display:flex; align-items:center; gap:10px;">
        <div style="flex-shrink:0; width:42px; height:42px; border-radius:50%; border:2px solid #C9A84C; overflow:hidden; background:#333;">
            <div style="width:100%;height:100%;background:#C9A84C;display:flex;align-items:center;justify-content:center;font-size:18px;font-weight:700;color:#fff;">{{ $traineeInit }}</div>
        </div>
        <div style="min-width:0;">
            <div style="font-size:13px;font-weight:700;color:#fff;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ auth()->user()->name }}</div>
            <span style="font-size:10px;font-weight:600;text-transform:uppercase;letter-spacing:0.6px;padding:1px 8px;border-radius:10px;background:rgba(160,38,38,0.35);color:#f5c6c6;">
                Trainee
            </span>
        </div>
    </div>

    <nav>
        <a href="{{ route('trainee.dashboard') }}" class="{{ request()->routeIs('trainee.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="{{ route('trainee.timetable') }}" class="{{ request()->routeIs('trainee.timetable') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i> Programme
        </a>
        <a href="{{ route('trainee.materials') }}" class="{{ request()->routeIs('trainee.materials') ? 'active' : '' }}">
            <i class="fas fa-book"></i> Materials
        </a>
        <a href="{{ route('trainee.profile.edit') }}" class="{{ request()->routeIs('trainee.profile.*') ? 'active' : '' }}">
            <i class="fas fa-user-edit"></i> My Profile
        </a>
        <a href="{{ route('trainee.documents.index') }}" class="{{ request()->routeIs('trainee.documents.*') ? 'active' : '' }}">
            <i class="fas fa-folder-open"></i> My Documents
        </a>
    </nav>
    <div class="sidebar-logout">
        <a href="#" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<div class="portal-main">
    <div class="portal-topbar">
        <span class="page-title">@yield('page-title', 'Trainee Portal')</span>
        <span class="user-info" style="display:flex;align-items:center;gap:8px;">
            <div style="width:28px;height:28px;border-radius:50%;border:2px solid rgba(201,168,76,0.7);overflow:hidden;flex-shrink:0;background:#a02626;">
                <div style="width:100%;height:100%;background:#C9A84C;display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;">{{ $traineeInit }}</div>
            </div>
            <span>{{ auth()->user()->name ?? '' }}</span>
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
@yield('scripts')
</body>
</html>
