<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Sistem Alumni</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Roboto:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Custom CSS -->
    <style>
        :root {
            --color-primary: #213448;
            --color-primary-light: #2d4a65;
            --color-primary-dark: #1a2838;
            --color-bg: #f8f9fa;
            --color-text: #333333;
            --sidebar-width: 260px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--color-bg);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar */
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(180deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            color: white;
            padding: 0;
            position: fixed;
            top: 0;
            left: 0;
            width: var(--sidebar-width);
            z-index: 1000;
            box-shadow: 4px 0 12px rgba(0,0,0,0.15);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.2) transparent;
        }

        .sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }

        .sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(255,255,255,0.2);
            border-radius: 20px;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            background: rgba(0,0,0,0.2);
            border-bottom: 1px solid rgba(255,255,255,0.15);
        }

        .sidebar-header h5 {
            margin: 0;
            font-weight: 700;
            font-size: 1.25rem;
            font-family: 'Poppins', sans-serif;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .sidebar-header h5 i {
            font-size: 1.4rem;
        }

        .sidebar-header small {
            display: block;
            margin-top: 0.5rem;
            opacity: 0.85;
            font-size: 0.9rem;
        }

        .nav-item {
            margin: 0.3rem 0.75rem;
        }

        .nav-link {
            color: rgba(255,255,255,0.9);
            padding: 0.85rem 1.2rem;
            border-radius: 10px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            font-weight: 500;
            border: 2px solid transparent;
        }

        .nav-link:hover {
            background: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(5px);
            border-left-color: rgba(255,255,255,0.5);
        }

        .nav-link.active {
            background: rgba(255,255,255,0.2);
            color: white;
            font-weight: 600;
            border-left-color: #0d6efd;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .nav-link i {
            margin-right: 0.85rem;
            font-size: 1.15rem;
            width: 24px;
            text-align: center;
        }

        .nav-divider {
            height: 1px;
            background: rgba(255,255,255,0.1);
            margin: 1rem 1.5rem;
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1100;
            background: var(--color-primary);
            color: white;
            border: none;
            padding: 0.7rem 0.9rem;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(33, 52, 72, 0.3);
            transition: all 0.3s ease;
        }

        .mobile-menu-toggle:hover {
            background: var(--color-primary-light);
            transform: scale(1.05);
        }

        .mobile-menu-toggle i {
            font-size: 1.5rem;
        }

        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 2rem;
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Topbar */
        .topbar {
            background: white;
            padding: 1.25rem 2rem;
            margin: -2rem -2rem 2rem -2rem;
            box-shadow: 0 2px 12px rgba(33, 52, 72, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            border-left: 4px solid var(--color-primary);
            border-radius: 0 0 12px 12px;
        }

        .topbar h4 {
            margin: 0;
            color: var(--color-primary);
            font-weight: 700;
            font-size: 1.6rem;
            font-family: 'Poppins', sans-serif;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .user-info {
            text-align: right;
        }

        .user-info .name {
            font-weight: 600;
            color: var(--color-primary);
            margin: 0;
            font-size: 1rem;
        }

        .user-info .role {
            font-size: 0.85rem;
            color: #6c757d;
            margin: 0;
        }

        /* Cards */
        .card-custom {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(33, 52, 72, 0.08);
            margin-bottom: 1.5rem;
            background: white;
            transition: all 0.3s ease;
        }

        .card-custom:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 16px rgba(33, 52, 72, 0.12);
        }

        .card-header-custom {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%);
            color: white;
            border-radius: 12px 12px 0 0 !important;
            padding: 1rem 1.5rem;
            font-weight: 600;
        }

        /* Status Cards */
        .status-card {
            background: white;
            border-radius: 12px;
            padding: 1.5rem;
            box-shadow: 0 2px 12px rgba(33, 52, 72, 0.08);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }

        .status-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 16px rgba(33, 52, 72, 0.12);
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 0.6rem 1.2rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .status-verified {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }

        .status-pending {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
        }

        .status-rejected {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }

        /* Buttons */
        .btn-primary-custom {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%);
            border: none;
            color: white;
            padding: 0.7rem 1.8rem;
            border-radius: 10px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(33, 52, 72, 0.2);
        }

        .btn-primary-custom:hover {
            background: linear-gradient(135deg, var(--color-primary-dark) 0%, var(--color-primary) 100%);
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(33, 52, 72, 0.3);
            color: white;
        }

        /* Alerts */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
        }

        .alert-warning {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            color: #856404;
        }

        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            box-shadow: 0 8px 20px rgba(33, 52, 72, 0.15);
            border-radius: 12px;
            padding: 0.5rem;
        }

        .dropdown-item {
            border-radius: 8px;
            padding: 0.7rem 1.2rem;
            transition: all 0.2s ease;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background-color: rgba(33, 52, 72, 0.08);
            transform: translateX(5px);
        }

        .dropdown-item.text-danger:hover {
            background-color: rgba(220, 53, 69, 0.1);
        }

        /* ================= RESPONSIVE DESIGN ================= */

        /* Tablet Responsiveness (992px and down) */
        @media (max-width: 991px) {
            :root {
                --sidebar-width: 240px;
            }

            .sidebar-header h5 {
                font-size: 1.15rem;
            }

            .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.95rem;
            }

            .topbar h4 {
                font-size: 1.4rem;
            }

            .main-content {
                padding: 1.75rem;
            }
        }

        /* Mobile Responsiveness (768px and down) */
        @media (max-width: 767px) {
            .mobile-menu-toggle {
                display: block;
            }

            .sidebar {
                width: 280px;
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
                box-shadow: 5px 0 25px rgba(0,0,0,0.3);
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(33, 52, 72, 0.7);
                z-index: 999;
                backdrop-filter: blur(4px);
                opacity: 0;
                transition: opacity 0.3s ease;
            }

            .sidebar-overlay.show {
                display: block;
                opacity: 1;
            }

            .main-content {
                margin-left: 0;
                padding: 1.5rem 1rem;
            }

            .topbar {
                margin: -1.5rem -1rem 1.5rem -1rem;
                padding: 1rem;
                padding-left: 60px;
            }

            .topbar h4 {
                font-size: 1.25rem;
                flex: 1;
                margin: 0;
            }

            .user-info {
                display: none;
            }

            .user-menu {
                display: none;
            }

            .card-custom {
                margin-bottom: 1rem;
            }

            .status-card {
                padding: 1.2rem;
                margin-bottom: 1rem;
            }

            /* Bootstrap grid adjust */
            .row {
                margin: 0 !important;
            }

            .col-md-8,
            .col-md-4 {
                padding: 0 !important;
                margin-bottom: 1rem;
            }

            .col-md-8 {
                width: 100% !important;
            }

            .col-md-4 {
                width: 100% !important;
            }

            .col-md-6 {
                padding: 0.5rem !important;
            }
        }

        /* Small Mobile (576px and down) */
        @media (max-width: 575px) {
            .sidebar {
                width: 260px;
            }

            .mobile-menu-toggle {
                padding: 0.6rem 0.8rem;
                top: 0.75rem;
                left: 0.75rem;
            }

            .main-content {
                padding: 1rem 0.75rem;
            }

            .topbar {
                margin: -1rem -0.75rem 1rem -0.75rem;
                padding: 0.85rem;
                padding-left: 55px;
            }

            .topbar h4 {
                font-size: 1.1rem;
            }

            .btn-primary-custom {
                padding: 0.6rem 1.2rem;
                font-size: 0.9rem;
            }

            .status-card {
                padding: 1rem;
            }

            .card {
                margin-bottom: 1rem !important;
            }

            .btn {
                font-size: 0.85rem !important;
                padding: 0.5rem 1rem !important;
            }

            .form-control,
            .form-control-sm {
                font-size: 0.9rem !important;
            }

            h5, h6 {
                font-size: 1rem !important;
            }
        }

        /* Extra Small (375px and down) */
        @media (max-width: 375px) {
            .sidebar {
                width: 240px;
            }

            .mobile-menu-toggle {
                padding: 0.5rem 0.7rem;
            }

            .topbar h4 {
                font-size: 0.95rem;
            }

            .sidebar-header h5 {
                font-size: 1rem;
            }

            .sidebar-header h5 span {
                display: none;
            }

            .sidebar-header h5 i {
                font-size: 1.2rem;
            }

            .nav-link span {
                display: none;
            }

            .nav-link {
                justify-content: center;
                padding: 0.75rem;
            }

            .btn-xs {
                padding: 0.2rem 0.4rem !important;
                font-size: 0.6rem !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle Menu">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar Overlay (Mobile) -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-header">
            <h5>
                <i class="bi bi-mortarboard-fill"></i>
                <span>Dashboard Alumni</span>
            </h5>
            <small class="text-white-50">SD Muhammadiyah BWK</small>
        </div>
        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('alumni.dashboard') ? 'active' : '' }}"
                   href="{{ route('alumni.dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('alumni.profile.*') ? 'active' : '' }}"
                   href="{{ route('alumni.profile.edit') }}">
                    <i class="bi bi-person-circle"></i>
                    <span>Edit Profil</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('alumni.direktori.*') ? 'active' : '' }}"
                href="{{ route('alumni.direktori.index') }}">
                    <i class="bi bi-people"></i>
                    <span>Direktori Alumni</span>
                </a>
            </li>

            <div class="nav-divider"></div>

            <li class="nav-item">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-link text-start w-100 border-0 bg-transparent">
                        <i class="bi bi-box-arrow-right"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <h4>@yield('title', 'Dashboard')</h4>
            <div class="user-menu">
                <div class="user-info">
                    <p class="name">{{ Auth::user()->alumni->nama_lengkap ?? Auth::user()->username }}</p>
                    <p class="role">Alumni</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none p-0" type="button" data-bs-toggle="dropdown" aria-label="User Menu">
                        <i class="bi bi-person-circle" style="font-size: 2.2rem; color: var(--color-primary);"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li>
                            <a class="dropdown-item" href="{{ route('alumni.profile.edit') }}">
                                <i class="bi bi-person"></i> Profil Saya
                            </a>
                        </li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="bi bi-box-arrow-right"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Alert Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Content -->
        @yield('content')
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Mobile Menu Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebar = document.getElementById('sidebar');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            const body = document.body;

            function toggleSidebar(show) {
                if (show) {
                    sidebar.classList.add('show');
                    sidebarOverlay.classList.add('show');
                    if (window.innerWidth < 768) {
                        body.style.overflow = 'hidden';
                    }
                } else {
                    sidebar.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                    body.style.overflow = '';
                }
            }

            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isOpen = sidebar.classList.contains('show');
                    toggleSidebar(!isOpen);
                });

                sidebarOverlay.addEventListener('click', function() {
                    toggleSidebar(false);
                });

                // Close sidebar when clicking a link on mobile
                const sidebarLinks = sidebar.querySelectorAll('.nav-link, button[type="submit"]');
                sidebarLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth < 768) {
                            toggleSidebar(false);
                        }
                    });
                });

                // Reset on window resize
                window.addEventListener('resize', function() {
                    if (window.innerWidth >= 768) {
                        sidebar.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                        body.style.overflow = '';
                    }
                });
            }
        });
    </script>

    @stack('scripts')
</body>
</html>
