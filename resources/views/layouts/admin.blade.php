<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - SDMBW Alumni</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Roboto:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --color-primary: #213448;
            --color-primary-light: #2d4a63;
            --color-primary-dark: #1a2937;
            --color-bg: #f8f9fa;
            --color-secondary: #6c757d;
            --color-accent: #0d6efd;
            --sidebar-width: 260px;
            --topbar-height: 70px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background: var(--color-bg);
            overflow-x: hidden;
            min-height: 100vh;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            color: white;
            padding: 20px 0;
            z-index: 1050;
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-y: auto;
            box-shadow: 2px 0 15px rgba(0, 0, 0, 0.1);
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,0.2) transparent;
        }

        .sidebar::-webkit-scrollbar { width: 6px; }
        .sidebar::-webkit-scrollbar-track { background: transparent; }
        .sidebar::-webkit-scrollbar-thumb {
            background-color: rgba(255,255,255,0.2);
            border-radius: 20px;
        }

        .sidebar-brand {
            padding: 0 25px 25px 25px;
            border-bottom: 1px solid rgba(255,255,255,0.15);
            margin-bottom: 20px;
        }

        .sidebar-brand h4 {
            font-family: 'Poppins', sans-serif;
            font-size: 20px;
            font-weight: 700;
            margin: 0;
            letter-spacing: 0.5px;
            color: #ffffff;
        }

        .sidebar-brand small {
            font-size: 12px;
            opacity: 0.8;
            color: #e0e0e0;
        }

        .sidebar-menu { list-style: none; padding: 0; }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 14px 25px;
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            border-left: 4px solid transparent;
            font-weight: 500;
        }

        .sidebar-menu li a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: #0d6efd;
            padding-left: 30px;
        }

        .sidebar-menu li a.active {
            background: rgba(13, 110, 253, 0.2);
            color: white;
            border-left-color: #0d6efd;
            font-weight: 600;
        }

        .sidebar-menu li a i {
            margin-right: 12px;
            font-size: 1.15rem;
            width: 25px;
            text-align: center;
        }

        /* ===== MAIN CONTENT ===== */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 25px;
            min-height: 100vh;
            transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* ===== TOPBAR ===== */
        .topbar {
            background: white;
            padding: 18px 25px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(33, 52, 72, 0.08);
            margin-bottom: 25px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            min-height: var(--topbar-height);
            border-left: 4px solid var(--color-primary);
        }

        .topbar h5 {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            color: var(--color-primary);
            font-size: 1.25rem;
            font-weight: 700;
        }

        .user-menu {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--color-primary);
            font-size: 0.95rem;
            font-weight: 500;
        }

        .btn-logout {
            background: var(--color-primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            box-shadow: 0 2px 8px rgba(33, 52, 72, 0.2);
            cursor: pointer;
        }

        .btn-logout:hover {
            background: var(--color-primary-light);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(33, 52, 72, 0.3);
        }

        /* ===== MOBILE ELEMENTS ===== */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 18px;
            left: 18px;
            z-index: 1100;
            background: var(--color-primary);
            color: white;
            border: none;
            width: 45px;
            height: 45px;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(33, 52, 72, 0.3);
            cursor: pointer;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }

        .sidebar-toggle:hover {
            background: var(--color-primary-light);
            transform: scale(1.05);
        }

        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(33, 52, 72, 0.7);
            z-index: 1040;
            backdrop-filter: blur(4px);
            opacity: 0;
            transition: opacity 0.3s;
        }

        /* ===== ALERTS ===== */
        .alert {
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 25px;
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

        .alert-info {
            background: linear-gradient(135deg, #d1ecf1 0%, #bee5eb 100%);
            color: #0c5460;
        }

        /* ===== CARDS ===== */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(33, 52, 72, 0.08);
            transition: all 0.3s;
        }

        .card:hover {
            box-shadow: 0 4px 16px rgba(33, 52, 72, 0.12);
        }

        .card-header {
            border-bottom: 1px solid #e9ecef;
            background: white;
            border-radius: 12px 12px 0 0;
        }

        .card-header h6 {
            font-weight: 600;
            color: var(--color-primary);
            margin: 0;
        }

        /* ===== TABLES ===== */
        .table {
            font-size: 0.95rem;
        }

        .table th {
            font-weight: 600;
            color: var(--color-primary);
            border-top: none;
            background: #f8f9fa;
        }

        .table td {
            vertical-align: middle;
            border-color: #e9ecef;
        }

        .table tbody tr:hover {
            background: rgba(13, 110, 253, 0.03);
        }

        /* ===== BUTTONS ===== */
        .btn {
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: var(--color-primary);
            border-color: var(--color-primary);
        }

        .btn-primary:hover {
            background: var(--color-primary-light);
            border-color: var(--color-primary-light);
            transform: translateY(-2px);
        }

        /* ===== FORMS ===== */
        .form-control, .form-select {
            border-radius: 8px;
            border-color: #dee2e6;
            font-size: 0.95rem;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--color-primary);
            box-shadow: 0 0 0 0.2rem rgba(33, 52, 72, 0.15);
        }

        .form-label {
            font-weight: 600;
            color: var(--color-primary);
            margin-bottom: 8px;
        }

        /* ===== BADGE ===== */
        .badge {
            padding: 0.5rem 0.75rem;
            font-weight: 600;
            font-size: 0.85rem;
        }

        /* ===== RESPONSIVE ===== */

        /* Tablet & Mobile (Max 991px) */
        @media (max-width: 991.98px) {
            :root {
                --sidebar-width: 280px;
            }

            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
                box-shadow: 5px 0 25px rgba(33, 52, 72, 0.3);
            }

            .main-content {
                margin-left: 0;
                padding: 20px;
                padding-top: 80px;
            }

            .sidebar-toggle {
                display: flex;
            }

            .sidebar-overlay.show {
                display: block;
                opacity: 1;
            }

            .topbar {
                padding: 15px 20px;
                flex-wrap: wrap;
                gap: 12px;
            }

            .topbar h5 {
                font-size: 1.1rem;
            }

            .user-menu {
                width: 100%;
                justify-content: space-between;
            }
        }

        /* Mobile Phone (Max 576px) */
        @media (max-width: 575.98px) {
            :root {
                --sidebar-width: 260px;
            }

            .sidebar {
                width: var(--sidebar-width);
            }

            .sidebar-brand {
                padding: 0 15px 15px 15px;
            }

            .sidebar-brand h4 {
                font-size: 16px;
            }

            .sidebar-menu li a {
                padding: 12px 15px;
                font-size: 0.9rem;
            }

            .main-content {
                padding: 15px;
                padding-top: 70px;
            }

            .topbar {
                flex-direction: column;
                align-items: stretch;
                gap: 15px;
                padding: 15px;
                padding-left: 65px;
                min-height: auto;
            }

            .topbar h5 {
                font-size: 1rem;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .user-menu {
                justify-content: space-between;
                width: 100%;
                border-top: 1px solid #e9ecef;
                padding-top: 12px;
            }

            .user-info {
                font-size: 0.85rem;
                gap: 8px;
            }

            .user-info i {
                font-size: 1.3rem;
            }

            .user-info span {
                max-width: 100px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .btn-logout {
                padding: 8px 12px;
                font-size: 0.8rem;
                gap: 4px;
            }

            .btn-logout i {
                font-size: 0.9rem;
            }

            .card {
                margin-bottom: 15px;
            }

            .card-body {
                padding: 15px;
            }

            .table {
                font-size: 0.85rem;
            }

            .table th {
                padding: 10px;
            }

            .table td {
                padding: 10px;
            }

            .btn {
                padding: 0.5rem 0.75rem;
                font-size: 0.8rem;
            }

            .alert {
                padding: 12px;
                font-size: 0.9rem;
            }

            .alert i {
                font-size: 1.1rem;
            }
        }

        /* Extra Small (Max 375px) */
        @media (max-width: 375px) {
            :root {
                --sidebar-width: 240px;
            }

            .sidebar {
                width: var(--sidebar-width);
            }

            .sidebar-brand h4 {
                font-size: 14px;
            }

            .sidebar-brand small {
                font-size: 10px;
            }

            .sidebar-menu li a {
                padding: 10px 12px;
            }

            .sidebar-menu li a i {
                font-size: 1rem;
                margin-right: 8px;
            }

            .sidebar-menu li a span {
                display: none;
            }

            .sidebar-menu li a.active span,
            .sidebar-menu li a:hover span {
                display: inline;
            }

            .sidebar-toggle {
                width: 40px;
                height: 40px;
                top: 12px;
                left: 12px;
            }

            .topbar {
                padding: 12px;
                padding-left: 55px;
            }

            .topbar h5 {
                font-size: 0.9rem;
            }

            .btn-logout {
                padding: 6px 10px;
                font-size: 0.75rem;
            }

            .main-content {
                padding: 12px;
                padding-top: 65px;
            }
        }
    </style>

    @stack('styles')
</head>
<body id="body-pd">

    <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle Sidebar">
        <i class="bi bi-list fs-4"></i>
    </button>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <h4>ADMIN PANEL</h4>
            <small>SD Muhammadiyah BWK</small>
        </div>

        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" title="Dashboard">
                    <i class="bi bi-speedometer2"></i> <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.alumni.index') }}" class="{{ request()->routeIs('admin.alumni.*') ? 'active' : '' }}" title="Kelola Alumni">
                    <i class="bi bi-people"></i> <span>Kelola Alumni</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.angkatan.index') }}" class="{{ request()->routeIs('admin.angkatan.*') ? 'active' : '' }}" title="Kelola Angkatan">
                    <i class="bi bi-calendar-event"></i> <span>Kelola Angkatan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.laporan.index') }}" class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}" title="Laporan">
                    <i class="bi bi-file-earmark-text"></i> <span>Laporan</span>
                </a>
            </li>
            <li>
                <a href="{{ route('admin.logs.index') }}" class="{{ request()->routeIs('admin.logs.*') ? 'active' : '' }}" title="Activity Logs">
                    <i class="bi bi-clock-history"></i> <span>Activity Logs</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="main-content">
        <div class="topbar">
            <h5>@yield('page-title', 'Dashboard')</h5>

            <div class="user-menu">
                <div class="user-info">
                    <i class="bi bi-person-circle"></i>
                    <span>{{ Auth::user()->username }}</span>
                </div>

                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-logout" title="Logout">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="d-none d-sm-inline">Logout</span>
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                <strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Error!</strong> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-circle me-2"></i>
                <strong>Perhatian!</strong> {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');
            const body = document.body;

            function toggleMenu(show) {
                if (show) {
                    sidebar.classList.add('show');
                    overlay.classList.add('show');
                    if(window.innerWidth < 992) {
                        body.style.overflow = 'hidden';
                    }
                } else {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    body.style.overflow = '';
                }
            }

            if(toggle) {
                toggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isOpen = sidebar.classList.contains('show');
                    toggleMenu(!isOpen);
                });
            }

            if(overlay) {
                overlay.addEventListener('click', () => toggleMenu(false));
            }

            const menuLinks = document.querySelectorAll('.sidebar-menu a');
            menuLinks.forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 992) {
                        toggleMenu(false);
                    }
                });
            });

            window.addEventListener('resize', () => {
                if (window.innerWidth >= 992) {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                    body.style.overflow = '';
                }
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
