<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Home') - Alumni SDMBW</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --color-bg: #EAE0CF;
            --color-accent: #547792;
            --color-header: #213448;
        }

        body { font-family: 'Roboto', sans-serif; background: var(--color-bg); margin: 0; }
        .navbar-landing { background: var(--color-header); box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .navbar-brand { font-family: 'Poppins', sans-serif; color: white !important; font-weight: 700; }

        .btn-login {
            background: var(--color-accent);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 5px;
            transition: 0.3s;
        }
        .btn-login:hover { background: white; color: var(--color-header); }

        .hero {
            background: linear-gradient(135deg, var(--color-header) 0%, var(--color-accent) 100%);
            color: white;
            padding: 100px 0;
            text-align: center;
        }
        .footer { background: var(--color-header); color: white; padding: 40px 0 20px; margin-top: 80px; }
    </style>
    @stack('styles')
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-landing">
        <div class="container">
            <a class="navbar-brand" href="{{ route('landing.index') }}">
                <i class="bi bi-mortarboard-fill"></i> SDMBW Alumni
            </a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="{{ route('landing.index') }}">Home</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('alumni.direktori') }}">Direktori</a></li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold text-white" href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('alumni.dashboard') }}">
                                Dashboard <i class="bi bi-speedometer2"></i>
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('login') }}" class="btn btn-login ms-3">Login</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    @yield('content')

    <footer class="footer">
        <div class="container text-center text-md-start">
            <div class="row">
                <div class="col-md-8">
                    <h5>SD Muhammadiyah Birrul Walidain Kudus</h5>
                    <p class="small opacity-75">Jl. HOS Cokroaminoto, Ds. Mlatinorowito, Gg. 10, RT 03 RW 09, Kab. Kudus, Provinsi Jawa Tengah.</p>
                </div>
                <div class="col-md-4 text-md-end">
                    <p class="small">&copy; {{ date('Y') }} Alumni SDMBW.</p>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
</body>
</html>
