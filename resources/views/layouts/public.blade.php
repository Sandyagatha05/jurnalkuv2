<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>@yield('title', config('app.name', 'Jurnalku'))</title>
    <meta name="description" content="@yield('description', 'Academic Journal Management System')">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-color: #193366;
            --secondary-color: #E8BA30;
            --background: #ffffff;
            --foreground: #1e293b;
            --muted: #f1f5f9;
            --border: #e2e8f0;
        }

        body {
            font-family: 'Figtree', sans-serif;
            background-color: var(--background);
            color: var(--foreground);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .journal-container {
            width: 100%;
            max-width: 1280px;
            margin-left: auto;
            margin-right: auto;
            padding-left: 1.5rem;
            padding-right: 1.5rem;
        }

        /* Header */
        .header-sticky {
            position: sticky;
            top: 0;
            z-index: 1020;
            background-color: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(15px);
            border-bottom: 1px solid var(--border);
        }

        .nav-link-desktop {
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            font-weight: 500;
            font-size: 0.9rem;
            text-decoration: none;
            display: inline-block;

            color: var(--foreground);
            background-color: transparent;

            transition: background-color 0.25s ease, color 0.25s ease;
        }

        /* ACTIVE */
        .nav-link-desktop.active {
            color: var(--primary-color);
            background-color: #19336613;
        }

        /* HOVER (fade abu-abu transparan) */
        .nav-link-desktop:hover {
            background-color: #1933660d;
        }

        /* Footer */
        .footer-primary {
            background-color: var(--primary-color);
            color: white;
        }

        .footer-link {
            color: rgba(255, 255, 255, 0.8);
            text-decoration: none;
            transition: color 0.2s;
        }

        .footer-link:hover {
            color: var(--secondary-color);
        }

        .footer-heading {
            font-weight: 600;
            font-size: 1.125rem;
            margin-bottom: 1rem;
        }

        .footer-icon {
            width: 1.25rem;
            height: 1.25rem;
            color: var(--secondary-color);
            flex-shrink: 0;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            line-height: 1;
        }

        .main-content {
            flex: 1;
        }

        .card {
            border: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .card:hover {
            transform: translateY(-5px);
        }

        .auth-login {
            background-color: transparent !important;
            color: var(--foreground) !important;
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
        }

        .auth-login:hover {
            background-color: var(--secondary-color) !important; /* #E8BA30 */
            color: var(--primary-color) !important;             /* #193366 */
        }

        /* Register: primary bg, darker on hover + subtle scale */
        .auth-register {
            background-color: var(--primary-color) !important;
            color: white !important;
            border-radius: 0.375rem;
            padding: 0.5rem 1rem;
            transition: all 0.25s ease;
        }

        .auth-register:hover {
            background-color: #152950 !important; /* sedikit lebih gelap dari #193366 */
            transform: scale(1.02);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header-sticky">
        <nav class="journal-container">
            <div class="d-flex align-items-center justify-content-between h-16 py-3">
                <!-- Logo (kiri) - sesuai header.tsx -->
                <a href="{{ route('home') }}" class="d-flex align-items-center gap-3 text-decoration-none">
                    <div class="d-flex align-items-center justify-content-center rounded-lg" style="width: 2.5rem; height: 2.5rem; background-color: var(--primary-color); color: white;">
                        <i class="fas fa-book-open fa-sm"></i>
                    </div>
                    <div>
                        <div class="fw-bold" style="font-size: 1.25rem; line-height: 1.2; color: var(--primary-color);">Jurnalku</div>
                        <div class="text-muted" style="font-size: 0.9rem; line-height: 1.2; color: var(--foreground);">Academic Journal</div>
                    </div>
                </a>

                <!-- Desktop Nav (center) - sesuai header.tsx -->
                <div class="d-none d-md-flex align-items-center justify-content-center flex-grow-1">
                    <ul class="nav mb-0">
                        <li class="nav-item">
                            <a class="nav-link-desktop {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link-desktop {{ request()->routeIs('issues.index') ? 'active' : '' }}" href="{{ route('issues.index') }}">Issues</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link-desktop {{ request()->routeIs('papers.index') ? 'active' : '' }}" href="{{ route('papers.index') }}">Papers</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link-desktop {{ request()->routeIs('about') ? 'active' : '' }}" href="{{ route('about') }}">About</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link-desktop {{ request()->routeIs('guidelines') ? 'active' : '' }}" href="{{ route('guidelines') }}">Guidelines</a>
                        </li>
                    </ul>
                </div>

                <!-- Auth (kanan) - sesuai header.tsx -->
                <div class="d-none d-md-flex align-items-center gap-1">
                    @auth
                        <a href="{{ route('dashboard') }}" class="nav-link-desktop d-flex align-items-center">
                            <i class="fas fa-chart-line me-1"></i> Dashboard
                        </a>
                    @else
                        <!-- Login: plain → hover: yellow square + blue text -->
                        <a href="{{ route('login') }}" class="nav-link-desktop auth-login">Login</a>

                        <!-- Register: primary → hover: darker + subtle scale -->
                        <a href="{{ route('register') }}" class="nav-link-desktop auth-register">Register</a>
                    @endauth
                </div>

                <!-- Mobile toggle -->
                <button
                    class="d-md-none btn btn-link text-muted p-0 ms-auto"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#mobileMenu"
                    aria-expanded="false"
                    aria-controls="mobileMenu"
                >
                    <i class="fas fa-bars fa-lg"></i>
                </button>
            </div>

            <!-- Mobile Nav - sesuai header.tsx -->
            <div class="collapse d-md-none" id="mobileMenu">
                <div class="pt-3 pb-4 border-top border-secondary-subtle">
                    <div class="d-flex flex-column gap-1">
                        <a href="{{ route('home') }}" class="nav-link-desktop {{ request()->routeIs('home') ? 'active' : '' }}">Home</a>
                        <a href="{{ route('issues.index') }}" class="nav-link-desktop {{ request()->routeIs('issues.index') ? 'active' : '' }}">Issues</a>
                        <a href="{{ route('papers.index') }}" class="nav-link-desktop {{ request()->routeIs('papers.index') ? 'active' : '' }}">Papers</a>
                        <a href="{{ route('about') }}" class="nav-link-desktop {{ request()->routeIs('about') ? 'active' : '' }}">About</a>
                        <a href="{{ route('guidelines') }}" class="nav-link-desktop {{ request()->routeIs('guidelines') ? 'active' : '' }}">Guidelines</a>

                        @auth
                            <a href="{{ route('dashboard') }}" class="nav-link-desktop d-flex align-items-center">
                                <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="nav-link-desktop">Login</a>
                            <a href="{{ route('register') }}" class="nav-link-desktop">Register</a>
                        @endauth

                        <div class="d-grid gap-2 mt-4 pt-3 border-top border-secondary-subtle px-3">
                            @unless(auth()->check())
                                <a href="{{ route('login') }}" class="btn btn-outline-primary btn-sm">Login</a>
                                <a href="{{ route('register') }}" class="btn btn-primary btn-sm">Register</a>
                            @endunless
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main -->
    <main class="main-content">
        @include('components.alert')
        @yield('content')
    </main>

    



    <!-- Footer - sesuai footer.tsx -->
    <footer class="footer-primary mt-auto">
        <div class="journal-container py-5">
            <div class="row g-4">
                <!-- Brand -->
                <div class="col-12 col-md-6">
                    <a href="{{ route('home') }}" class="d-flex align-items-center gap-3 mb-4 text-decoration-none">
                        <div class="d-flex align-items-center justify-content-center rounded-lg" style="width: 2.5rem; height: 2.5rem; background-color: var(--secondary-color); color: var(--foreground);">
                            <i class="fas fa-book-open fa-sm"></i>
                        </div>
                        <span class="fw-bold" style="font-size: 1.5rem; color: white;">Jurnalku</span>
                    </a>
                    <p class="text-white-75" style="font-size: 0.875rem; max-width: 30rem;">
                        Academic Journal Management System providing efficient publishing workflow for researchers and scholars.
                    </p>
                </div>

                <!-- Quick Links -->
                <div class="col-6 col-md-3">
                    <h3 class="footer-heading text-white">Quick Links</h3>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('home') }}" class="footer-link">Home</a></li>
                        <li class="mb-2"><a href="{{ route('issues.index') }}" class="footer-link">Issues</a></li>
                        <li class="mb-2"><a href="{{ route('papers.index') }}" class="footer-link">Papers</a></li>
                        <li class="mb-2"><a href="{{ route('about') }}" class="footer-link">About</a></li>
                    </ul>
                </div>

                <!-- Contact -->
                <div class="col-6 col-md-3">
                    <h3 class="footer-heading text-white">Contact</h3>
                    <ul class="list-unstyled">
                        <li class="d-flex gap-3 mb-3 align-items-start">
                            <i class="fas fa-map-marker-alt footer-icon"></i>
                            <span class="text-white-75" style="font-size: 0.875rem;">
                                Jakarta, Indonesia
                            </span>
                        </li>

                        <li class="d-flex gap-3 mb-3 align-items-start">
                            <i class="fas fa-envelope footer-icon"></i>
                            <a href="mailto:contact@jurnalku.com"
                            class="footer-link text-white-75"
                            style="font-size: 0.875rem;">
                                contact@jurnalku.com
                            </a>
                        </li>

                        <li class="d-flex gap-3 align-items-start">
                            <i class="fas fa-phone footer-icon"></i>
                            <span class="text-white-75" style="font-size: 0.875rem;">
                                +62 123 456 789
                            </span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-5 pt-4 border-top" style="border-color: rgba(255, 255, 255, 0.2);">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                    <p class="text-white-50 mb-0" style="font-size: 0.875rem;">
                        &copy; {{ date('Y') }} Jurnalku. All rights reserved.
                    </p>
                    
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>