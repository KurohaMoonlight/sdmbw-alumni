<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Alumni SDMBW</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Roboto:wght@400;500&display=swap" rel="stylesheet">

    <style>
        :root {
            --color-bg: #EAE0CF;
            --color-secondary: #94B4C1;
            --color-accent: #547792;
            --color-header: #213448;
        }

        body {
            background: linear-gradient(135deg, var(--color-bg) 0%, var(--color-secondary) 100%);
            font-family: 'Roboto', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            overflow: hidden;
            max-width: 400px;
            width: 100%;
        }

        .login-header {
            background: var(--color-header);
            color: white;
            padding: 30px;
            text-align: center;
        }

        .login-header h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 24px;
            margin: 0 0 10px 0;
            letter-spacing: 1px;
        }

        .login-body {
            padding: 30px;
        }

        .btn-login {
            background: var(--color-accent);
            border: none;
            color: white;
            padding: 12px;
            font-weight: 500;
            transition: all 0.3s;
        }

        .btn-login:hover {
            background: var(--color-header);
            color: white;
            transform: translateY(-2px);
        }

        .btn-back-home {
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            background: white;
            color: var(--color-header);
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 500;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }

        .btn-back-home:hover {
            background: var(--color-header);
            color: white;
            transform: translateY(-2px);
        }

        /* Styling tambahan untuk alert agar lebih rapi */
        .alert {
            font-size: 0.9rem;
            border-radius: 10px;
            border: none;
        }

        /* Input focus styling */
        .form-control:focus {
            border-color: var(--color-accent);
            box-shadow: 0 0 0 0.2rem rgba(84, 119, 146, 0.25);
        }

        /* Link styling */
        a {
            transition: all 0.3s;
        }
    </style>
</head>
<body>
    <a href="{{ route('landing.index') }}" class="btn-back-home">
        <i class="bi bi-arrow-left"></i>
        <span>Beranda</span>
    </a>

    <div class="login-card">
        <div class="login-header">
            <h1>SISTEM ALUMNI</h1>
            <p class="mb-0">SD Muhammadiyah Birrul Walidain Kudus</p>
        </div>

        <div class="login-body">

            {{-- 1. Alert Success (Setelah Register atau Reset Password) --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- 2. Alert Error/Warning (Gagal Login atau Belum Aktif) --}}
            {{-- Kita prioritaskan pesan dari controller (is_active check) --}}
            @if($errors->has('username'))
                <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ $errors->first('username') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @elseif(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <i class="bi bi-x-circle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="username" class="form-label text-secondary small fw-bold">USERNAME</label>
                    <input type="text"
                           class="form-control @error('username') is-invalid @enderror"
                           id="username"
                           name="username"
                           value="{{ old('username') }}"
                           placeholder="Masukkan username"
                           required
                           autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label text-secondary small fw-bold">PASSWORD</label>
                    <input type="password"
                           class="form-control @error('password') is-invalid @enderror"
                           id="password"
                           name="password"
                           placeholder="••••••••"
                           required>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-login w-100 rounded-pill">
                    Masuk Sekarang <i class="bi bi-box-arrow-in-right ms-2"></i>
                </button>
            </form>

            <hr class="my-4 text-muted">

            <div class="text-center">
                <p class="small text-muted mb-1">Belum punya akun?</p>
                <a href="{{ route('register') }}" class="fw-bold text-decoration-none" style="color: var(--color-accent);">
                    Daftar Sebagai Alumni
                </a>
            </div>

            <div class="text-center mt-3">
                <p class="small text-muted mb-0">Lupa password?</p>
                <a href="https://wa.me/6281248076886" target="_blank" class="small text-decoration-none" rel="noopener noreferrer">
                    <i class="bi bi-whatsapp me-1"></i> Hubungi Admin Sekolah
                </a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
