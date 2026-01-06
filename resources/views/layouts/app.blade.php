<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Jurnalku'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Remix Icon -->
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.5.0/fonts/remixicon.css" rel="stylesheet">


    <style>
        :root {
            --primary-color: #193366;
            --secondary-color: #E8BA30;
            --background: #f5f7fb;
            --foreground: #1e293b;
            --border: #e2e8f0;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--background);
            color: var(--foreground);
        }

        /* Layout */
        .dashboard-wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background-color: white;
            border-right: 1px solid var(--border);
            display: flex;
            flex-direction: column;
            position: fixed;
            inset: 0 auto 0 0;
            z-index: 1030;
        }

        /* Sidebar Header (logo public-style) */
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid var(--border);
        }

        .sidebar-header a {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .logo-box {
            width: 40px;
            height: 40px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .logo-title {
            font-weight: 700;
            font-size: 1.25rem;
            color: var(--primary-color);
            line-height: 1.2;
        }

        .logo-subtitle {
            font-size: 0.875rem;
            color: var(--foreground);
        }

        /* User Info */
        .sidebar-user {
            padding: 16px 20px;
            border-bottom: 1px solid var(--border);
        }

        .avatar {
            width: 40px;
            height: 40px;
            background-color: var(--primary-color);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
        }

        .avatar-title {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            font-size: 2rem;
            line-height: 1;
        }

        /* Navigation */
        .sidebar nav a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            margin: 4px 10px;
            border-radius: 8px;
            text-decoration: none;
            color: var(--foreground);
            font-size: 0.9rem;
            transition: background-color .2s ease, color .2s ease;
        }

        .sidebar nav a i {
            width: 18px;
            text-align: center;
        }

        .sidebar nav a:hover {
            background-color: #1933660d;
            color: var(--primary-color);
        }

        .sidebar nav a.active {
            background-color: #19336613;
            color: var(--primary-color);
            font-weight: 500;
        }

        /* Logout */
        .sidebar-footer {
            padding: 16px;
            border-top: 1px solid var(--border);
        }

        /* Main */
        .main {
            margin-left: 260px;
            flex: 1;
            padding: 28px;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.06);
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main {
                margin-left: 0;
            }
        }

        .pagination .page-link {
            border-radius: .5rem;
            border: 1px solid var(--border);
            color: var(--foreground);
            padding: .5rem .75rem;
            background: white;
        }

        .pagination .page-item.active .page-link {
            background: var(--primary-color);
            color: white;
            border-color: var(--primary-color);
        }

        .pagination .page-item.disabled .page-link {
            opacity: .4;
            pointer-events: none;
        }


        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: #fff;
        }

        .btn-primary:hover,
        .btn-primary:focus {
            background-color: #152950; /* versi lebih gelap */
            border-color: #152950;
            color: #fff;
        }

        .btn-primary:active {
            background-color: #0f1f3f;
            border-color: #0f1f3f;
        }
    </style>

    @stack('styles')
</head>
<body>

<div class="dashboard-wrapper">

@auth
<aside class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-header">
        <a href="{{ route('home') }}">
            <div class="logo-box">
                <i class="fas fa-book-open fa-sm"></i>
            </div>
            <div>
                <div class="logo-title">Jurnalku</div>
                <div class="logo-subtitle">Academic Journal</div>
            </div>
        </a>
    </div>

    <!-- User -->
    <div class="sidebar-user d-flex align-items-center gap-3">
        <div class="avatar">
            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div>
            <div class="fw-semibold">{{ Auth::user()->name }}</div>
            <small class="text-muted">
                {{ ucfirst(Auth::user()->roles->first()->name ?? '') }}
            </small>
        </div>
    </div>

    <!-- Menu -->
    <nav class="flex-grow-1 mt-2">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-chart-line"></i> Dashboard
        </a>

        @if(Auth::user()->hasRole('admin'))
            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="fas fa-users"></i> Users
            </a>
        @endif

        @if(Auth::user()->hasRole('editor'))
            <a href="{{ route('editor.papers.index') }}" class="{{ request()->routeIs('editor.papers.*') ? 'active' : '' }}">
                <i class="fas fa-file-alt"></i> Papers
            </a>
            <a href="{{ route('editor.issues.index') }}" class="{{ request()->routeIs('editor.issues.*') ? 'active' : '' }}">
                <i class="fas fa-book nav-icon"></i> Issues
            </a>
        @endif

        @if(Auth::user()->hasRole('author'))
            <a href="{{ route('author.papers.create') }}" class="{{ request()->routeIs('author.papers.create') ? 'active' : '' }}">
                <i class="fas fa-upload"></i> Submit Paper
            </a>
        @endif

        <a href="{{ route('profile.show') }}"
            class="{{ request()->routeIs('profile.show') || request()->routeIs('profile.edit') ? 'active' : '' }}">
                <i class="fas fa-user"></i> Profile
        </a>

        <a href="{{ route('home') }}">
            <i class="fas fa-globe"></i> Public Site
        </a>
    </nav>

    <!-- Logout Bottom -->
    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="btn btn-outline-danger w-100">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </button>
        </form>
    </div>
</aside>
@endauth

<main class="main">
    @yield('content')
</main>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@stack('scripts')
</body>
</html>
