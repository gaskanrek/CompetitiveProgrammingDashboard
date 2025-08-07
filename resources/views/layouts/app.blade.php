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
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(145deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 0.75rem 1rem;
            margin: 0.25rem 0;
            border-radius: 0.5rem;
            transition: all 0.3s ease;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            transform: translateX(5px);
        }
        .main-content {
            background-color: #f8f9fa;
            min-height: 100vh;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.75rem;
        }
        .card-header {
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            border-radius: 0.75rem 0.75rem 0 0 !important;
        }
        .btn-primary {
            background: linear-gradient(145deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(145deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-1px);
        }
        .navbar {
            background: white !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
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
