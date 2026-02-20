<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Alumni SDMBW</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@500;600;700&family=Roboto:wght@400;500&display=swap" rel="stylesheet">

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

        .btn-back-home {
            position: absolute;
            top: 25px;
            left: 25px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.9);
            color: var(--color-header);
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            backdrop-filter: blur(5px);
            border: 1px solid rgba(255,255,255,0.5);
        }

        .btn-back-home:hover {
            background: var(--color-header);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(33, 52, 72, 0.15);
            overflow: hidden;
            max-width: 420px;
            width: 100%;
            border: none;
        }

        .login-header {
            background: var(--color-header);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }

        .login-header::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            border-width: 10px 10px 0;
            border-style: solid;
            border-color: var(--color-header) transparent transparent transparent;
            z-index: 1;
        }

        .login-header h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 26px;
            font-weight: 700;
            margin: 10px 0 5px 0;
            letter-spacing: 0.5px;
        }

        .login-header p {
            font-size: 0.9rem;
            opacity: 0.8;
            font-weight: 400;
        }

        .login-icon {
            font-size: 3rem;
            margin-bottom: 5px;
            color: var(--color-secondary);
        }

        .login-body {
            padding: 40px 40px 30px 40px;
        }

        .input-group-text {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-right: none;
            color: var(--color-accent);
        }

        .form-control {
            border: 1px solid #dee2e6;
            border-left: none;
            padding: 12px;
            background-color: #f8f9fa;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: var(--color-accent);
            background-color: white;
            box-shadow: none;
        }

        .input-group:focus-within {
            box-shadow: 0 0 0 4px rgba(84, 119, 146, 0.15);
            border-radius: 0.375rem;
        }

        .form-label {
            color: var(--color-header);
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }

        /* ===== TOGGLE PASSWORD BUTTON ===== */
        .btn-toggle-password {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-left: none;
            color: #adb5bd;
            padding: 12px 14px;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-toggle-password:hover {
            color: var(--color-accent);
            background-color: #f0f4f7;
        }

        .input-group:focus-within .btn-toggle-password {
            border-color: var(--color-accent);
            background-color: white;
        }

        .btn-login {
            background: var(--color-accent);
            border: none;
            color: white;
            padding: 12px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            letter-spacing: 0.5px;
            border-radius: 50px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(84, 119, 146, 0.3);
        }

        .btn-login:hover {
            background: var(--color-header);
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(33, 52, 72, 0.4);
        }

        .link-register {
            color: var(--color-accent);
            font-weight: 700;
            transition: color 0.2s;
        }

        .link-register:hover {
            color: var(--color-header);
            text-decoration: underline !important;
        }

        .alert {
            font-size: 0.9rem;
            border-radius: 10px;
            border: none;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }

        @media (max-width: 576px) {
            .login-card {
                max-width: 100%;
                box-shadow: none;
                background: rgba(255,255,255,0.95);
            }
            .btn-back-home {
                position: fixed;
                top: 15px;
                left: 15px;
            }
        }
    </style>
</head>
<body>
    <a href="{{ route('landing.index') }}" class="btn-back-home">
        <i class="bi bi-arrow-left me-1"></i> Beranda
    </a>

    <div class="login-card">
        <div class="login-header">
            <i class="bi bi-mortarboard-fill login-icon"></i>
            <h1>SISTEM ALUMNI</h1>
            <p class="mb-0">SD Muhammadiyah Birrul Walidain Kudus</p>
        </div>

        <div class="login-body">

            {{-- ── Notifikasi Sukses (misal: setelah logout) ── --}}
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                        <div>{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{--
                ── Notifikasi Error Login ──
                Tiga kondisi berbeda dengan warna & ikon yang berbeda:
                1. 'rejected' → alert merah (danger) + ikon silang
                2. 'pending'  → alert kuning (warning) + ikon jam
                3. Selain itu → alert kuning (warning) + ikon segitiga
            --}}
            @if($errors->has('username'))
                @php
                    $loginStatus = session('login_status'); // 'rejected' | 'pending' | null
                @endphp

                @if($loginStatus === 'rejected')
                    {{-- Alumni DITOLAK → merah --}}
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-x-octagon-fill me-2 fs-5 mt-1"></i>
                            <div>
                                <strong>Pendaftaran Ditolak</strong><br>
                                {{ $errors->first('username') }}
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                @elseif($loginStatus === 'pending')
                    {{-- Alumni PENDING → kuning + ikon jam --}}
                    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-hourglass-split me-2 fs-5 mt-1"></i>
                            <div>
                                <strong>Menunggu Verifikasi</strong><br>
                                {{ $errors->first('username') }}
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>

                @else
                    {{-- Error lain (username salah, password salah, dll) → kuning --}}
                    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                            <div>{{ $errors->first('username') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

            @elseif(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-x-circle-fill me-2 fs-5"></i>
                        <div>{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="username" class="form-label fw-bold">USERNAME</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
                        <input type="text"
                                class="form-control @error('username') is-invalid @enderror"
                                id="username"
                                name="username"
                                value="{{ old('username') }}"
                                placeholder="Masukkan username"
                                required
                                autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-bold">PASSWORD</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                        <input type="password"
                                class="form-control @error('password') is-invalid @enderror"
                                id="password"
                                name="password"
                                placeholder="••••••••"
                                required>
                        {{-- Tombol Show/Hide Password --}}
                        <button class="btn-toggle-password" type="button" id="togglePassword" title="Lihat/Sembunyikan Password">
                            <i class="bi bi-eye-fill" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label small text-muted" for="remember">Ingat Saya</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-login w-100">
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

    {{-- Show/Hide Password --}}
    <script>
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const toggleIcon    = document.getElementById('toggleIcon');

            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
                this.title = 'Sembunyikan Password';
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
                this.title = 'Lihat Password';
            }
        });
    </script>
</body>
</html>
