<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Jurnalku'))</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --warning-color: #f72585;
            --light-color: #f8f9fa;
            --dark-color: #212529;
        }
        
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f5f7fb;
        }
        
        .sidebar {
            background: linear-gradient(180deg, #2d3748 0%, #1a202c 100%);
            min-height: 100vh;
            color: white;
        }
        
        .sidebar a {
            color: #cbd5e0;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            border-radius: 5px;
            margin-bottom: 5px;
            transition: all 0.3s;
        }
        
        .sidebar a:hover, .sidebar a.active {
            background-color: #4a5568;
            color: white;
        }
        
        .sidebar .nav-icon {
            width: 20px;
            margin-right: 10px;
            text-align: center;
        }
        
        .main-content {
            padding: 20px;
        }
        
        .card {
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            border: none;
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            font-weight: 600;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .status-submitted { background-color: #e9ecef; color: #495057; }
        .status-under_review { background-color: #fff3cd; color: #856404; }
        .status-accepted { background-color: #d1ecf1; color: #0c5460; }
        .status-published { background-color: #d4edda; color: #155724; }
        .status-rejected { background-color: #f8d7da; color: #721c24; }
        .status-revision_minor { background-color: #fff3cd; color: #856404; }
        .status-revision_major { background-color: #f8d7da; color: #721c24; }
        
        .table th {
            border-top: none;
            font-weight: 600;
            color: #6c757d;
        }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">
                <i class="fas fa-book me-2"></i> {{ config('app.name', 'Jurnalku') }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i> {{ Auth::user()->name }}
                                @if(Auth::user()->hasRole('admin'))
                                    <span class="badge bg-danger">Admin</span>
                                @elseif(Auth::user()->hasRole('editor'))
                                    <span class="badge bg-primary">Editor</span>
                                @elseif(Auth::user()->hasRole('reviewer'))
                                    <span class="badge bg-warning">Reviewer</span>
                                @elseif(Auth::user()->hasRole('author'))
                                    <span class="badge bg-success">Author</span>
                                @endif
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                </a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user me-2"></i> Profile
                                </a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="fas fa-sign-out-alt me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">Register</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            @auth
            <!-- Sidebar for authenticated users -->
            <div class="col-md-2 d-none d-md-block sidebar p-0">
                <div class="p-3">
                    <h5 class="text-center mb-4">
                        <i class="fas fa-user-circle me-2"></i> {{ Auth::user()->name }}
                    </h5>
                    
                    <div class="mb-4 text-center">
                        @if(Auth::user()->hasRole('admin'))
                            <span class="badge bg-danger">Administrator</span>
                        @elseif(Auth::user()->hasRole('editor'))
                            <span class="badge bg-primary">Editor</span>
                        @elseif(Auth::user()->hasRole('reviewer'))
                            <span class="badge bg-warning">Reviewer</span>
                        @elseif(Auth::user()->hasRole('author'))
                            <span class="badge bg-success">Author</span>
                        @endif
                    </div>
                    
                    <nav class="nav flex-column">
                        <!-- Dashboard Link -->
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                            <i class="fas fa-tachometer-alt nav-icon"></i> Dashboard
                        </a>
                        
                        <!-- Admin Menu -->
                        @if(Auth::user()->hasRole('admin'))
                            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.*') ? 'active' : '' }}">
                                <i class="fas fa-crown nav-icon"></i> Admin Panel
                            </a>
                            <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                                <i class="fas fa-users nav-icon"></i> Users
                            </a>
                            <a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                                <i class="fas fa-user-tag nav-icon"></i> Roles
                            </a>
                        @endif
                        
                        <!-- Editor Menu -->
                        @if(Auth::user()->hasRole('editor'))
                            <a href="{{ route('editor.dashboard') }}" class="{{ request()->routeIs('editor.*') ? 'active' : '' }}">
                                <i class="fas fa-edit nav-icon"></i> Editor Panel
                            </a>
                            <a href="{{ route('editor.papers.index') }}" class="{{ request()->routeIs('editor.papers.*') ? 'active' : '' }}">
                                <i class="fas fa-file-alt nav-icon"></i> Papers
                            </a>
                            <a href="{{ route('editor.issues.index') }}" class="{{ request()->routeIs('editor.issues.*') ? 'active' : '' }}">
                                <i class="fas fa-book nav-icon"></i> Issues
                            </a>
                        @endif
                        
                        <!-- Reviewer Menu -->
                        @if(Auth::user()->hasRole('reviewer'))
                            <a href="{{ route('reviewer.dashboard') }}" class="{{ request()->routeIs('reviewer.*') ? 'active' : '' }}">
                                <i class="fas fa-search nav-icon"></i> Reviewer Panel
                            </a>
                            <a href="{{ route('reviewer.assignments.pending') }}" class="{{ request()->routeIs('reviewer.assignments.*') ? 'active' : '' }}">
                                <i class="fas fa-tasks nav-icon"></i> Assignments
                            </a>
                        @endif
                        
                        <!-- Author Menu -->
                        @if(Auth::user()->hasRole('author'))
                            <a href="{{ route('author.dashboard') }}" class="{{ request()->routeIs('author.*') ? 'active' : '' }}">
                                <i class="fas fa-user-edit nav-icon"></i> Author Panel
                            </a>
                            <a href="{{ route('author.papers.index') }}" class="{{ request()->routeIs('author.papers.*') ? 'active' : '' }}">
                                <i class="fas fa-file-upload nav-icon"></i> My Papers
                            </a>
                            <a href="{{ route('author.papers.create') }}" class="{{ request()->routeIs('author.papers.create') ? 'active' : '' }}">
                                <i class="fas fa-plus-circle nav-icon"></i> Submit Paper
                            </a>
                        @endif
                        
                        <!-- Common Links -->
                        <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.*') ? 'active' : '' }}">
                            <i class="fas fa-user nav-icon"></i> Profile
                        </a>
                        
                        <a href="{{ route('home') }}">
                            <i class="fas fa-home nav-icon"></i> Public Site
                        </a>
                    </nav>
                </div>
            </div>
            
            <!-- Main Content Area -->
            <main class="col-md-10 ms-sm-auto main-content">
        @else
            <!-- Full width for non-authenticated users -->
            <main class="col-12 main-content">
        @endif
        
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <!-- Page Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1">@yield('page-title')</h2>
                        <p class="text-muted mb-0">@yield('page-description')</p>
                    </div>
                    <div>
                        @yield('page-actions')
                    </div>
                </div>
                
                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Auto-dismiss alerts after 5 seconds
        setTimeout(function() {
            $('.alert').alert('close');
        }, 5000);
        
        // Confirm before delete
        function confirmDelete(event) {
            if (!confirm('Are you sure you want to delete this item?')) {
                event.preventDefault();
                return false;
            }
        }
        
        // Format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }
    </script>
    
    @stack('scripts')
</body>
</html>
