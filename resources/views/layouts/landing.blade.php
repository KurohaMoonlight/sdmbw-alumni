<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Sistem Alumni') - SD Muhammadiyah Birrul Walidain Kudus</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --color-primary: #213448;
            --color-primary-light: #2d4a65;
            --color-primary-dark: #152230;
            --color-accent: #EAE0CF;
            --color-bg: #ffffff;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--color-bg);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar */
        .navbar-custom {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%);
            padding: 1rem 0;
            border-bottom: 3px solid var(--color-accent);
            box-shadow: 0 2px 10px rgba(33, 52, 72, 0.15);
        }

        .navbar-custom .navbar-brand {
            color: #ffffff !important;
            font-weight: 700;
            font-size: 1.3rem;
            transition: all 0.3s ease;
        }

        .navbar-custom .navbar-brand:hover {
            transform: scale(1.05);
        }

        .navbar-custom .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .navbar-custom .nav-link:hover {
            color: var(--color-accent) !important;
            background: rgba(255, 255, 255, 0.1);
        }

        .btn-navbar-cream {
            background-color: var(--color-accent);
            color: var(--color-primary-dark) !important;
            font-weight: 600;
            border-radius: 8px;
            padding: 0.6rem 1.5rem !important;
            border: none;
            text-decoration: none;
            transition: all 0.3s ease;
            box-shadow: 0 2px 8px rgba(234, 224, 207, 0.3);
        }

        .btn-navbar-cream:hover {
            background-color: #d4cab5;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(234, 224, 207, 0.5);
        }

        .navbar-toggler {
            border: none;
            padding: 0.5rem;
        }

        .navbar-toggler:focus {
            box-shadow: none;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, var(--color-primary-dark) 0%, #0a1420 100%);
            color: #ffffff !important;
            padding: 4rem 0 0 0;
            margin-top: auto;
            border-top: 3px solid var(--color-accent);
        }

        .footer h5 {
            color: var(--color-accent) !important;
            font-weight: 700;
            margin-bottom: 1.5rem;
            font-family: 'Poppins', sans-serif;
            font-size: 1.2rem;
        }

        .footer p, .footer li {
            color: #d1d8df !important;
            line-height: 1.8;
        }

        .footer a {
            color: #d1d8df;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-block;
        }

        .footer a:hover {
            color: var(--color-accent);
            transform: translateX(5px);
        }

        .footer ul {
            padding-left: 0;
        }

        .footer ul li {
            margin-bottom: 0.5rem;
        }

        .footer-bottom {
            background: rgba(0, 0, 0, 0.3);
            padding: 1.5rem 0;
            margin-top: 3rem;
            border-top: 1px solid rgba(234, 224, 207, 0.1);
        }

        .footer-bottom p {
            margin: 0;
            opacity: 0.8;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .navbar-collapse {
                background: rgba(0, 0, 0, 0.1);
                padding: 1rem;
                border-radius: 10px;
                margin-top: 1rem;
            }
            .btn-navbar-cream { width: 100%; margin-top: 0.5rem; }
        }
    </style>
    @stack('styles')
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-custom sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('landing.index') }}">
                <i class="bi bi-mortarboard-fill me-2 fs-3" style="color: var(--color-accent)"></i>
                <span>ALUMNI SDMBW</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list text-white fs-1"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('landing.index') }}">Beranda</a></li>
                    @guest
                        <li class="nav-item"><a class="nav-link px-3" href="{{ route('login') }}">Masuk</a></li>
                        <li class="nav-item"><a href="{{ route('register') }}" class="btn btn-navbar-cream">Daftar Sekarang</a></li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('alumni.dashboard') }}">Dashboard</a></li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <main>
        @yield('content')
    </main>

    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5>Sistem Alumni</h5>
                    <p>Sebagai wadah resmi alumni SD Muhammadiyah Birrul Walidain Kudus, platform ini bertujuan mempererat silaturahmi serta mendorong kontribusi positif antaralumni.</p>
                </div>
                <div class="col-lg-2 ms-lg-auto">
                    <h5>Navigasi</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('landing.index') }}">Beranda</a></li>
                        <li><a href="{{ route('login') }}">Login</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5>Kontak Kami</h5>
                    <ul class="list-unstyled">
                        <li class="d-flex mb-3">
                            <i class="bi bi-geo-alt-fill me-3" style="color: var(--color-accent)"></i>
                            <span>Jl. HOS Cokroaminoto, Ds. Mlatinorowito, Gg. 10, RT 03 RW 09, Kab. Kudus, Provinsi Jawa Tengah.</span>
                        </li>
                        <li class="d-flex mb-3">
                            <i class="bi bi-telephone-fill me-3" style="color: var(--color-accent)"></i>
                            <span>(0812) 48076886 / (0291) 4248302 </span>
                        </li>
                        <li class="d-flex mb-3">
                            <i class="bi bi-envelope-fill me-3" style="color: var(--color-accent)"></i>
                            <span>info@sdmbwkudus.sch.id</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom text-center">
                <p class="mb-0">&copy; {{ date('Y') }} SD Muhammadiyah Birrul Walidain Kudus.</p>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
