<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'CP Dashboard') }} - @yield('title', 'Dashboard')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #6366f1;
            --primary-hover: #4f46e5;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --warning-color: #f59e0b;
            --danger-color: #ef4444;
            --info-color: #3b82f6;
            --light-bg: #f8fafc;
            --card-bg: #ffffff;
            --border-color: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #64748b;
            --sidebar-bg: #1e293b;
            --sidebar-hover: #334155;
        }

        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--sidebar-bg) 0%, #0f172a 100%);
            border-right: 1px solid var(--border-color);
        }
        
        .sidebar .nav-link {
            color: #cbd5e1;
            padding: 0.875rem 1.25rem;
            margin: 0.25rem 0.75rem;
            border-radius: 0.5rem;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid transparent;
        }
        
        .sidebar .nav-link:hover {
            color: #ffffff;
            background-color: var(--sidebar-hover);
            border-color: rgba(255, 255, 255, 0.1);
            transform: translateX(3px);
            box-shadow: 0 2px 4px -1px rgba(0, 0, 0, 0.1);
        }
        
        .sidebar .nav-link.active {
            color: #ffffff;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            border-color: var(--primary-color);
            box-shadow: 0 2px 4px 0 rgba(99, 102, 241, 0.2);
        }
        
        .main-content {
            background-color: var(--light-bg);
            min-height: 100vh;
        }
        
        .card {
            border: 1px solid var(--border-color);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            border-radius: 0.75rem;
            background-color: var(--card-bg);
            transition: box-shadow 0.2s ease, transform 0.2s ease;
            backface-visibility: hidden;
            -webkit-font-smoothing: antialiased;
        }
        
        .card:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transform: translateY(-1px);
        }
        
        .card-header {
            background-color: var(--card-bg);
            border-bottom: 1px solid var(--border-color);
            border-radius: 0.75rem 0.75rem 0 0 !important;
            padding: 1rem 1.25rem;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%);
            border: 1px solid var(--primary-color);
            color: white;
            transition: background 0.2s ease, transform 0.2s ease, box-shadow 0.2s ease;
            backface-visibility: hidden;
            -webkit-font-smoothing: antialiased;
        }
        
        .btn-primary:hover {
            background: linear-gradient(135deg, var(--primary-hover) 0%, #3730a3 100%);
            border-color: var(--primary-hover);
            transform: translateY(-1px);
            box-shadow: 0 2px 4px 0 rgba(99, 102, 241, 0.2);
        }
        
        .btn-outline-primary {
            color: var(--primary-color);
            border-color: var(--primary-color);
            transition: all 0.3s ease;
        }
        
        .btn-outline-primary:hover {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            transform: translateY(-1px);
        }
        
        .btn-success {
            background-color: var(--success-color);
            border-color: var(--success-color);
        }
        
        .btn-success:hover {
            background-color: #059669;
            border-color: #059669;
            transform: translateY(-1px);
        }
        
        .btn-warning {
            background-color: var(--warning-color);
            border-color: var(--warning-color);
        }
        
        .btn-warning:hover {
            background-color: #d97706;
            border-color: #d97706;
            transform: translateY(-1px);
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border-color: var(--danger-color);
        }
        
        .btn-danger:hover {
            background-color: #dc2626;
            border-color: #dc2626;
            transform: translateY(-1px);
        }
        
        .navbar {
            background-color: var(--card-bg) !important;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        
        .bg-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-hover) 100%) !important;
        }
        
        .bg-success {
            background-color: var(--success-color) !important;
        }
        
        .bg-warning {
            background-color: var(--warning-color) !important;
        }
        
        .bg-danger {
            background-color: var(--danger-color) !important;
        }
        
        .bg-info {
            background-color: var(--info-color) !important;
        }
        
        .text-primary {
            color: var(--primary-color) !important;
        }
        
        .border-primary {
            border-color: var(--primary-color) !important;
        }
        
        .alert {
            border: none;
            border-radius: 0.75rem;
            border-left: 4px solid;
        }
        
        .alert-success {
            background-color: #f0fdf4;
            border-left-color: var(--success-color);
            color: #166534;
        }
        
        .alert-danger {
            background-color: #fef2f2;
            border-left-color: var(--danger-color);
            color: #991b1b;
        }
        
        .alert-warning {
            background-color: #fffbeb;
            border-left-color: var(--warning-color);
            color: #92400e;
        }
        
        .alert-info {
            background-color: #eff6ff;
            border-left-color: var(--info-color);
            color: #1e40af;
        }
        
        .badge {
            font-weight: 500;
            border-radius: 0.375rem;
        }
        
        .table {
            border-collapse: separate;
            border-spacing: 0;
        }
        
        .table th {
            background-color: #f8fafc;
            color: var(--text-primary);
            font-weight: 600;
            border-bottom: 2px solid var(--border-color);
            padding: 0.875rem;
        }
        
        .table td {
            padding: 0.875rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }
        
        .table tbody tr {
            transition: background-color 0.15s ease;
            backface-visibility: hidden;
            -webkit-font-smoothing: antialiased;
        }
        
        .table-hover tbody tr:hover {
            background-color: #f1f5f9;
        }
        
        /* Dropdown styling */
        .dropdown-menu {
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        
        .dropdown-item {
            color: var(--text-primary);
            transition: background-color 0.15s ease, color 0.15s ease;
        }
        
        .dropdown-item:hover {
            background-color: #f1f5f9;
            color: var(--primary-color);
        }
        
        /* Form controls */
        .form-control {
            border: 1px solid var(--border-color);
            border-radius: 0.5rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }
        
        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        /* Rating colors remain the same for consistency */
        .rating-newbie { color: #808080; }
        .rating-pupil { color: #008000; }
        .rating-specialist { color: #03a89e; }
        .rating-expert { color: #0000ff; }
        .rating-candidate-master { color: #aa00aa; }
        .rating-master { color: #ff8c00; }
        .rating-international-master { color: #ff8c00; }
        .rating-grandmaster { color: #ff0000; }
        .rating-international-grandmaster { color: #ff0000; }
        .rating-legendary-grandmaster { color: #ff0000; }
        
        /* Smooth transitions for interactive elements */
        a, button {
            transition: color 0.15s ease, background-color 0.15s ease, transform 0.15s ease;
        }
        
        /* Progress bars */
        .progress {
            background-color: #e2e8f0;
            border-radius: 0.5rem;
        }
        
        .progress-bar {
            background: linear-gradient(90deg, var(--primary-color) 0%, var(--primary-hover) 100%);
        }
    </style>
    
    @stack('styles')
</head>
<body>
    @auth
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <div class="text-center mb-4">
                        <h4 class="text-white fw-bold">CP Dashboard</h4>
                        <small class="text-white-50">{{ auth()->user()->role->display_name ?? 'User' }}</small>
                    </div>
                    
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        
                        @if(auth()->user()->isMahasiswa())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('mahasiswa.profile') ? 'active' : '' }}" href="{{ route('mahasiswa.profile') }}">
                                    <i class="bi bi-person-circle me-2"></i>
                                    Profil & Handle
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('mahasiswa.activity') ? 'active' : '' }}" href="{{ route('mahasiswa.activity') }}">
                                    <i class="bi bi-activity me-2"></i>
                                    Aktivitas Saya
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('mahasiswa.recommendations') ? 'active' : '' }}" href="{{ route('mahasiswa.recommendations') }}">
                                    <i class="bi bi-lightbulb me-2"></i>
                                    Rekomendasi Soal
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('mahasiswa.contests') ? 'active' : '' }}" href="{{ route('mahasiswa.contests') }}">
                                    <i class="bi bi-trophy me-2"></i>
                                    Kontes Mendatang
                                </a>
                            </li>
                        @endif

                        @if(auth()->user()->isPendamping())
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('pendamping.mahasiswa*') ? 'active' : '' }}" href="{{ route('pendamping.mahasiswa') }}">
                                    <i class="bi bi-people me-2"></i>
                                    Mahasiswa
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('pendamping.leaderboard') ? 'active' : '' }}" href="{{ route('pendamping.leaderboard') }}">
                                    <i class="bi bi-award me-2"></i>
                                    Leaderboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('pendamping.activities') ? 'active' : '' }}" href="{{ route('pendamping.activities') }}">
                                    <i class="bi bi-clock-history me-2"></i>
                                    Aktivitas Terkini
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('pendamping.contests') ? 'active' : '' }}" href="{{ route('pendamping.contests') }}">
                                    <i class="bi bi-calendar-event me-2"></i>
                                    Manajemen Kontes
                                </a>
                            </li>
                        @endif
                        
                        <hr class="text-white-50">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}" href="{{ route('profile.show') }}">
                                <i class="bi bi-gear me-2"></i>
                                Pengaturan
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                <!-- Top Navbar -->
                <nav class="navbar navbar-expand-lg navbar-light sticky-top mb-4">
                    <div class="container-fluid">
                        <button class="navbar-toggler d-md-none" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        
                        <div class="navbar-nav ms-auto">
                            <div class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                    <div class="me-2">
                                        <div class="fw-bold">{{ auth()->user()->name }}</div>
                                        <small class="text-muted">{{ auth()->user()->email }}</small>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                                        <i class="bi bi-person me-2"></i>Profil
                                    </a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Content -->
                <div class="container-fluid px-0">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    @else
    <div class="container-fluid">
        @yield('content')
    </div>
    @endauth

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>
