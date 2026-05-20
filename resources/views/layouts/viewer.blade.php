<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>COSECSA Viewer — @yield('page-title', 'Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('img/cosecsa-favicon.png') }}">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { font-family: 'Inter', system-ui, sans-serif; box-sizing: border-box; }
        body { background: #f0f2f5; margin: 0; font-size: 14.5px; color: #1a202c; }

        .viewer-sidebar {
            position: fixed; top: 0; left: 0;
            width: 230px; height: 100vh;
            background: #fff;
            display: flex; flex-direction: column;
            z-index: 200;
            box-shadow: 2px 0 8px rgba(0,0,0,.06);
            border-right: 1.5px solid #e2e8f0;
        }
        .viewer-brand {
            padding: 18px 16px 14px;
            background: #f8f9fa;
            border-bottom: 1px solid rgba(201,168,76,.3);
            display: flex; align-items: center; gap: 10px;
        }
        .viewer-brand img { width: 36px; height: 36px; border-radius: 50%; border: 2px solid #C9A84C; object-fit: cover; }
        .viewer-brand-text { font-size: 13px; font-weight: 700; color: #2d3748; line-height: 1.2; }
        .viewer-brand-sub  { font-size: 10.5px; color: #a0aec0; }

        .viewer-nav { padding: 8px 0; flex: 1; }
        .viewer-nav a {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 18px; color: #4a5568; text-decoration: none;
            font-size: 13.5px; font-weight: 600;
            border-left: 3px solid transparent;
            transition: background .12s, color .12s;
        }
        .viewer-nav a:hover  { background: rgba(201,168,76,.08); color: #C9A84C; border-left-color: #C9A84C; text-decoration: none; }
        .viewer-nav a.active { background: rgba(201,168,76,.08); color: #C9A84C; border-left-color: #C9A84C; }
        .viewer-nav a i { width: 18px; text-align: center; font-size: 14px; }
        .viewer-nav-label {
            padding: 8px 18px 2px; font-size: 10px; font-weight: 700;
            text-transform: uppercase; letter-spacing: .7px; color: #cbd5e0;
        }

        .viewer-logout {
            padding: 14px 16px; border-top: 1px solid #e9ecef;
        }
        .viewer-logout a {
            display: flex; align-items: center; gap: 8px;
            color: #a0aec0; font-size: 13px; text-decoration: none; font-weight: 600;
        }
        .viewer-logout a:hover { color: #e53e3e; }

        .viewer-main { margin-left: 230px; min-height: 100vh; display: flex; flex-direction: column; }
        .viewer-topbar {
            background: #fff; height: 54px;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 22px;
            border-bottom: 2px solid #C9A84C;
            position: sticky; top: 0; z-index: 50;
        }
        .viewer-topbar .page-title { font-size: 15px; font-weight: 700; color: #1a202c; }
        .viewer-content { padding: 24px; flex: 1; }
        .viewer-footer { background: #f8f9fa; color: #a0aec0; font-size: 11.5px; text-align: center; padding: 10px; }

        .card { border: 1.5px solid #e2e8f0; box-shadow: 0 2px 8px rgba(0,0,0,.04); border-radius: 10px; }
        .table td, .table th { font-size: 14px; vertical-align: middle; }
        .badge { font-size: 11px; }

        /* Read-only banner */
        .viewer-badge {
            background: #fef3cd; color: #9a6c00;
            font-size: 11px; font-weight: 700; padding: 3px 10px;
            border-radius: 20px; border: 1px solid #e8c96a;
            display: flex; align-items: center; gap: 5px;
        }

        @media (max-width: 768px) {
            .viewer-sidebar { display: none; }
            .viewer-main { margin-left: 0; }
        }

        @yield('styles')
    </style>
</head>
<body>

<aside class="viewer-sidebar">
    <div class="viewer-brand">
        <img src="{{ asset('img/cosecsa-logo.png') }}" alt="COSECSA">
        <div>
            <div class="viewer-brand-text">COSECSA</div>
            <div class="viewer-brand-sub">Viewer Access</div>
        </div>
    </div>

    <nav class="viewer-nav">
        <div class="viewer-nav-label">Overview</div>
        <a href="{{ route('viewer.dashboard') }}" class="{{ request()->routeIs('viewer.dashboard') ? 'active' : '' }}">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>

        <div class="viewer-nav-label">People</div>
        <a href="{{ route('viewer.facilitators') }}" class="{{ request()->routeIs('viewer.facilitators') ? 'active' : '' }}">
            <i class="fas fa-chalkboard-teacher"></i> Facilitators
        </a>
        <a href="{{ route('viewer.trainees') }}" class="{{ request()->routeIs('viewer.trainees') ? 'active' : '' }}">
            <i class="fas fa-user-graduate"></i> Trainees
        </a>

        <div class="viewer-nav-label">Programme</div>
        <a href="{{ route('viewer.timetable') }}" class="{{ request()->routeIs('viewer.timetable') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i> Timetable
        </a>
    </nav>

    <div class="viewer-logout">
        <a href="#" onclick="event.preventDefault(); document.getElementById('viewer-logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Sign Out
        </a>
        <form id="viewer-logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
    </div>
</aside>

<div class="viewer-main">
    <div class="viewer-topbar">
        <div class="page-title">@yield('page-title', 'Dashboard')</div>
        <div class="viewer-badge">
            <i class="fas fa-eye"></i> View Only
        </div>
    </div>

    <div class="viewer-content">
        @if(session('message'))
        <div class="alert alert-success py-2 mb-3">{{ session('message') }}</div>
        @endif
        @yield('content')
    </div>

    <div class="viewer-footer">&copy; {{ date('Y') }} COSECSA Research Training System &mdash; Read-Only Access</div>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/js/bootstrap.min.js"></script>
@yield('scripts')
</body>
</html>
