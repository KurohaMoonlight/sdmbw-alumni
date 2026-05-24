<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="/favicon.svg" />
    <link rel="shortcut icon" href="/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="Alumni SDMBW" />
    <link rel="manifest" href="/site.webmanifest" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') - SDMBW Alumni</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=DM+Serif+Display:ital@0;1&display=swap" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/print.css') }}" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        :root {
            --color-primary:       #1B3A52;
            --color-primary-light: #2a5378;
            --color-primary-dark:  #112534;
            --color-accent:        #EAE0CF;
            --color-accent-soft:   rgba(232,200,122,0.12);
            --color-bg:            #EEF2F7;
            --color-surface:       #ffffff;
            --color-danger:        #e53e3e;
            --sidebar-width:       268px;
            --topbar-height:       66px;
            --transition:          all 0.27s cubic-bezier(0.4,0,0.2,1);
            --shadow-card:         0 2px 0 rgba(255,255,255,0.8) inset, 0 6px 24px rgba(27,58,82,0.08);
            --radius:              14px;
        }


        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--color-bg);
            color: #1e293b;
            min-height: 100vh;
        }

        /* ──────────────────────────────────────────
           SIDEBAR
        ────────────────────────────────────────── */
        .sidebar {
            position: fixed;
            top: 0; left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: var(--color-primary-dark);
            color: white;
            z-index: 1050;
            transition: var(--transition);
            overflow-y: auto;
            overflow-x: hidden;
            box-shadow: 4px 0 28px rgba(0,0,0,0.16);
            display: flex;
            flex-direction: column;
        }

        /* noise texture */
        .sidebar::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.75' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
            pointer-events: none;
            opacity: 0.5;
        }

        .sidebar-brand {
            padding: 2rem 1.5rem 1.75rem;
            text-align: center;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            position: relative;
            flex-shrink: 0;
        }

        .sidebar-logo-wrap {
            width: 52px; height: 52px;
            background: rgba(232,200,122,0.15);
            border: 1px solid rgba(232,200,122,0.3);
            border-radius: 16px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 12px;
        }

        .sidebar-logo-wrap i {
            font-size: 1.5rem;
            color: var(--color-accent);
        }

        .sidebar-brand h4 {
            font-family: 'DM Serif Display', serif;
            font-weight: 400;
            font-size: 1.1rem;
            margin: 0 0 5px;
            color: #fff;
            letter-spacing: 0.3px;
        }

        .sidebar-brand small {
            display: block;
            font-size: 0.7rem;
            font-weight: 500;
            color: rgba(255,255,255,0.35);
            letter-spacing: 0.5px;
        }

        /* nav section label */
        .nav-section-label {
            font-size: 0.62rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: rgba(255,255,255,0.22);
            padding: 0 1.5rem;
            margin: 1.4rem 0 0.4rem;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0.5rem 0;
            margin: 0;
            flex: 1;
            position: relative;
        }

        .sidebar-menu li { margin: 2px 0.7rem; }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 0.76rem 1rem;
            color: rgba(255,255,255,0.58);
            text-decoration: none;
            border-radius: 10px;
            transition: var(--transition);
            font-weight: 500;
            font-size: 0.875rem;
            gap: 12px;
        }

        .sidebar-menu li a i {
            font-size: 1.05rem;
            width: 20px;
            text-align: center;
            flex-shrink: 0;
            transition: var(--transition);
        }

        .sidebar-menu li a:hover {
            background: rgba(255,255,255,0.07);
            color: #fff;
            transform: translateX(3px);
        }

        .sidebar-menu li a.active {
            background: var(--color-accent);
            color: var(--color-primary-dark);
            font-weight: 700;
            box-shadow: 0 4px 14px rgba(232,200,122,0.22);
        }

        .sidebar-menu li a.active i { color: var(--color-primary-dark); }

        .sidebar-divider {
            height: 1px;
            background: rgba(255,255,255,0.07);
            margin: 0.75rem 1.25rem;
        }

        /* ──────────────────────────────────────────
           MAIN CONTENT
        ────────────────────────────────────────── */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: var(--transition);
        }

        /* ──────────────────────────────────────────
           TOPBAR
        ────────────────────────────────────────── */
        .topbar {
            background: var(--color-surface);
            padding: 0 2rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 1px 0 rgba(0,0,0,0.04), 0 4px 16px rgba(0,0,0,0.03);
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: var(--topbar-height);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .topbar-title {
            font-family: 'DM Serif Display', serif;
            font-weight: 400;
            font-size: 1.4rem;
            color: var(--color-primary);
            margin: 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .topbar-title::before {
            content: '';
            display: inline-block;
            width: 4px; height: 1.1em;
            background: var(--color-accent);
            border-radius: 2px;
            vertical-align: middle;
        }

        .content-body { padding: 2rem 2.5rem; }

        /* user menu */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .user-chip {
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 5px 14px 5px 7px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 50px;
            transition: var(--transition);
        }

        .user-chip:hover { background: #f1f5f9; border-color: #cbd5e1; }

        .user-avatar {
            width: 32px; height: 32px;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%);
            color: white;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-weight: 800;
            font-size: 0.8rem;
            flex-shrink: 0;
        }

        .user-chip span {
            font-weight: 600;
            font-size: 0.85rem;
            color: var(--color-primary);
        }

        .btn-logout {
            display: flex;
            align-items: center;
            gap: 7px;
            background: white;
            color: var(--color-danger);
            border: 1.5px solid rgba(229,62,62,0.25);
            padding: 7px 14px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.83rem;
            transition: var(--transition);
            cursor: pointer;
        }

        .btn-logout:hover {
            background: var(--color-danger);
            color: white;
            border-color: var(--color-danger);
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(229,62,62,0.2);
        }

        /* ──────────────────────────────────────────
           ALERTS
        ────────────────────────────────────────── */
        .alert {
            border: none;
            border-radius: var(--radius);
            padding: 0.9rem 1.25rem;
            font-size: 0.875rem;
            font-weight: 500;
            box-shadow: var(--shadow-card);
            margin-bottom: 1.25rem;
        }

        .alert-success {
            background: #f0fdf4;
            color: #166534;
            border-left: 4px solid #22c55e;
        }

        .alert-danger {
            background: #fef2f2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .alert-warning {
            background: #fffbeb;
            color: #92400e;
            border-left: 4px solid #f59e0b;
        }

        /* ──────────────────────────────────────────
           CARD OVERRIDES
        ────────────────────────────────────────── */
        .card {
            border: 1px solid rgba(226,232,240,0.8) !important;
            border-radius: var(--radius) !important;
            box-shadow: var(--shadow-card) !important;
            overflow: hidden;
        }

        .card-header {
            background: var(--color-surface) !important;
            padding: 1.1rem 1.5rem !important;
            border-bottom: 1px solid #f1f5f9 !important;
            font-weight: 700;
            color: var(--color-primary);
        }

        /* ──────────────────────────────────────────
           MOBILE TOGGLE BUTTON
        ────────────────────────────────────────── */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 14px; left: 14px;
            z-index: 1100;
            background: var(--color-primary);
            color: white;
            border: none;
            width: 44px; height: 44px;
            border-radius: 12px;
            box-shadow: 0 4px 16px rgba(27,58,82,0.35);
            cursor: pointer;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }

        .sidebar-toggle:hover { background: var(--color-primary-light); }

        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(10,18,28,0.55);
            backdrop-filter: blur(6px);
            -webkit-backdrop-filter: blur(6px);
            z-index: 1040;
        }

        /* ──────────────────────────────────────────
            RESPONSIVE
        ────────────────────────────────────────── */
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; }
            .sidebar-toggle { display: flex; }
            .sidebar-overlay.show { display: block; }
            .content-body { padding: 1.25rem; }
            .topbar { padding: 0 1.25rem; padding-left: 4.5rem; }
        }

        @media (max-width: 575.98px) {
            .topbar {
                flex-direction: column;
                height: auto;
                padding: 0.9rem 1rem 0.9rem 4.5rem;
                gap: 0.75rem;
                align-items: flex-start;
            }
            .user-menu { width: 100%; justify-content: space-between; }
            .user-chip span {
                max-width: 90px;
                overflow: hidden;
                text-overflow: ellipsis;
                white-space: nowrap;
            }
            .content-body { padding: 1rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
        <i class="bi bi-list fs-4"></i>
    </button>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <aside class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <div class="sidebar-logo-wrap">
                <i class="bi bi-mortarboard-fill"></i>
            </div>
            <h4>Sistem Alumni</h4>
            <small>SD Muhammadiyah BW</small>
        </div>

        <div class="nav-section-label">Manajemen</div>

        <nav>
            <ul class="sidebar-menu">
                @if(auth()->user()->isAdmin())
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->isKepalaSekolah())
                <li>
                    <a href="{{ route('kepala_sekolah.dashboard') }}"
                        class="{{ request()->routeIs('kepala_sekolah.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-grid-1x2-fill"></i>
                        <span>Dashboard Kepala Sekolah</span>
                    </a>
                </li>
                @endif
                <li>
                    <a href="{{ route('admin.alumni.index') }}"
                        class="{{ request()->routeIs('admin.alumni.*') ? 'active' : '' }}">
                        <i class="bi bi-people-fill"></i>
                        <span>Kelola Alumni</span>
                    </a>
                </li>
                @if(auth()->user()->isAdmin())
                <li>
                    <a href="{{ route('admin.users.index') }}"
                       class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="bi bi-person-badge"></i>
                        <span>Kelola Pengguna</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.angkatan.index') }}"
                       class="{{ request()->routeIs('admin.angkatan.*') ? 'active' : '' }}">
                        <i class="bi bi-calendar3"></i>
                        <span>Kelola Angkatan</span>
                    </a>
                </li>
                @endif

                @if(auth()->user()->isAdmin())
                <div class="sidebar-divider"></div>
                <div class="nav-section-label" style="margin-top:0.25rem;">CMS Publik</div>
                
                <li>
                    <a href="{{ route('admin.faqs.index') }}"
                        class="{{ request()->routeIs('admin.faqs.*') ? 'active' : '' }}">
                        <i class="bi bi-question-circle-fill"></i>
                        <span>Kelola FAQ</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.testimonis.index') }}"
                        class="{{ request()->routeIs('admin.testimonis.*') ? 'active' : '' }}">
                        <i class="bi bi-chat-quote-fill"></i>
                        <span>Kelola Testimoni</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.beritas.index') }}"
                        class="{{ request()->routeIs('admin.beritas.*') ? 'active' : '' }}">
                        <i class="bi bi-newspaper"></i>
                        <span>Kelola Berita</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.forum.index') }}"
                        class="{{ request()->routeIs('admin.forum.*') ? 'active' : '' }}">
                        <i class="bi bi-chat-square-text-fill"></i>
                        <span>Moderasi Forum</span>
                        @php
                            $newThreadsCount = \App\Models\ForumThread::where('created_at', '>=', now()->subDay())->count();
                        @endphp
                        @if($newThreadsCount > 0)
                            <span class="badge bg-danger ms-auto rounded-pill">{{ $newThreadsCount }}</span>
                        @endif
                    </a>
                </li>
                @endif

                <div class="sidebar-divider"></div>
                <div class="nav-section-label" style="margin-top:0.25rem;">Laporan & Log</div>

                <li>
                    <a href="{{ route('admin.laporan.index') }}"
                        class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-bar-graph-fill"></i>
                        <span>Laporan</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.logs.index') }}"
                        class="{{ request()->routeIs('admin.logs.*') ? 'active' : '' }}">
                        <i class="bi bi-clock-history"></i>
                        <span>Activity Logs</span>
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <div id="app">
        <div class="main-content">

            <header class="topbar">
                <h5 class="topbar-title">@yield('page-title', 'Dashboard')</h5>

                <div class="user-menu">
                    <div class="user-chip">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->username, 0, 1)) }}
                        </div>
                        <span>{{ Auth::user()->username }}</span>
                    </div>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <i class="bi bi-box-arrow-right"></i>
                            <span class="d-none d-sm-inline">Keluar</span>
                        </button>
                    </form>
                </div>
            </header>

            <div class="content-body">
                @if(session('new_password'))
                    <div class="alert alert-success d-flex align-items-center justify-content-between mb-4 shadow-sm" style="border: 1px solid #16a34a;">
                        <div>
                            <strong><i class="bi bi-key-fill me-2"></i> Password Berhasil Direset!</strong><br>
                            Password baru untuk alumni ini adalah: <code class="fs-5 ms-2 fw-bold text-dark px-2 py-1 bg-light rounded">{{ session('new_password') }}</code>
                            <small class="d-block mt-1 text-muted">Mohon salin dan berikan password ini ke alumni yang bersangkutan. Password ini hanya ditampilkan sekali.</small>
                        </div>
                        <button class="btn btn-sm btn-success fw-bold px-3 py-2" onclick="navigator.clipboard.writeText('{{ session('new_password') }}'); this.innerHTML='<i class=\'bi bi-check2-all\'></i> Tersalin!'; setTimeout(()=>this.innerHTML='<i class=\'bi bi-clipboard\'></i> Salin',2000);"><i class="bi bi-clipboard"></i> Salin</button>
                    </div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

    {{-- Toast Container --}}
    <div id="toast-container" class="toast-container"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Sidebar Toggle Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const toggle  = document.getElementById('sidebarToggle');
            const overlay = document.getElementById('sidebarOverlay');

            function toggleSidebar() {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
                document.body.style.overflow = sidebar.classList.contains('show') ? 'hidden' : '';
            }

            toggle.addEventListener('click', toggleSidebar);
            overlay.addEventListener('click', toggleSidebar);

            if (window.innerWidth < 992) {
                document.querySelectorAll('.sidebar-menu a').forEach(link => {
                    link.addEventListener('click', toggleSidebar);
                });
            }
        });
    </script>

    {{-- Toast Notification Script --}}
    <script>
    function showToast(message, type = 'success', duration = 3000) {
        const container = document.getElementById('toast-container');

        const icons = {
            success: '<i class="bi bi-check-circle-fill"></i>',
            error:   '<i class="bi bi-x-circle-fill"></i>',
            warning: '<i class="bi bi-exclamation-triangle-fill"></i>',
            info:    '<i class="bi bi-info-circle-fill"></i>'
        };

        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <span class="toast-icon">${icons[type] || icons.success}</span>
            <div class="toast-content">${message}</div>
            <button class="toast-close" onclick="this.parentElement.remove()">×</button>
        `;

        container.appendChild(toast);

        setTimeout(() => toast.classList.add('show'), 100);

        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => toast.remove(), 300);
        }, duration);
    }

    @if(session('success'))
        showToast("{{ session('success') }}", 'success');
    @endif

    @if(session('error'))
        showToast("{{ session('error') }}", 'error');
    @endif

    @if(session('warning'))
        showToast("{{ session('warning') }}", 'warning');
    @endif

    @if(session('info'))
        showToast("{{ session('info') }}", 'info');
    @endif

    @if($errors->any())
        @foreach($errors->all() as $error)
            showToast("{{ $error }}", 'error', 5000);
        @endforeach
    @endif
    </script>

    {{-- PWA Service Worker Registration --}}
    <script>
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', function() {
            navigator.serviceWorker.register('/sw.js').then(function(registration) {
                console.log('ServiceWorker registration successful with scope: ', registration.scope);
            }, function(err) {
                console.log('ServiceWorker registration failed: ', err);
            });
        });
    }
    </script>

    {{-- Loading State Script --}}
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');

        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"]');

                if (submitBtn && !submitBtn.classList.contains('btn-loading')) {
                    submitBtn.classList.add('btn-loading');
                    submitBtn.disabled = true;

                    const originalText = submitBtn.innerHTML;
                    submitBtn.setAttribute('data-original-text', originalText);

                    const icon = submitBtn.querySelector('i');
                    if (icon) {
                        submitBtn.innerHTML = '<i class="bi bi-arrow-clockwise spinning me-2"></i> Memproses...';
                    } else {
                        submitBtn.innerHTML = 'Memproses...';
                    }
                }
            });
        });
    });
    </script>

    @stack('scripts')
</body>
</html>
