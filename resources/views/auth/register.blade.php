<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Registrasi Alumni - Sistem Alumni SDMBW</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" crossorigin="anonymous">

    <style>
        :root {
            --color-bg: #EAE0CF;
            --color-secondary: #94B4C1;
            --color-accent: #547792;
            --color-header: #213448;
            --color-header-dark: #152230;
        }

        body {
            background: linear-gradient(135deg, var(--color-bg) 0%, var(--color-secondary) 100%);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            padding: 60px 0;
            display: flex;
            align-items: center;
        }

        .register-card {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(33, 52, 72, 0.15);
            overflow: hidden;
            max-width: 600px;
            margin: 0 auto;
            border: none;
        }

        .register-header {
            background: var(--color-header);
            color: white;
            padding: 50px 30px;
            text-align: center;
            position: relative;
        }

        .btn-back-home {
            position: absolute;
            top: 25px;
            left: 25px;
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 8px 16px;
            border-radius: 50px;
            text-decoration: none;
            font-size: 0.8rem;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 8px;
            backdrop-filter: blur(4px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-back-home:hover {
            background: white;
            color: var(--color-header);
            transform: translateX(-5px);
        }

        .register-header h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 24px;
            font-weight: 700;
            margin: 10px 0 5px 0;
            letter-spacing: 1px;
        }

        .register-header p {
            margin: 0;
            font-size: 14px;
            opacity: 0.85;
            font-weight: 400;
        }

        .register-body { padding: 45px; }

        .section-title {
            font-size: 0.8rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            font-weight: 700;
            color: var(--color-accent);
            margin: 30px 0 20px 0;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .section-title:first-child { margin-top: 0; }

        .section-title::after {
            content: "";
            flex: 1;
            height: 2px;
            background: #f0f0f0;
        }

        .form-label {
            font-weight: 600;
            color: var(--color-header);
            font-size: 0.85rem;
            margin-bottom: 8px;
        }

        .input-group-text {
            background-color: #fcfcfc;
            border: 1.5px solid #eee;
            color: #adb5bd;
            border-radius: 12px 0 0 12px;
            padding-left: 15px;
        }

        .form-control, .form-select {
            padding: 12px 16px;
            border-radius: 12px;
            border: 1.5px solid #eee;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background-color: #fcfcfc;
        }

        .input-group .form-control {
            border-radius: 0;
            border-left: none;
        }

        /* Field tanpa tombol toggle → border-radius kanan */
        .input-group .form-control.no-toggle {
            border-radius: 0 12px 12px 0;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--color-accent);
            background-color: white;
            box-shadow: 0 0 0 4px rgba(84, 119, 146, 0.1);
        }

        /* ===== TOGGLE PASSWORD BUTTON ===== */
        .btn-toggle-password {
            background-color: #fcfcfc;
            border: 1.5px solid #eee;
            border-left: none;
            border-radius: 0 12px 12px 0;
            color: #adb5bd;
            padding: 12px 14px;
            cursor: pointer;
            transition: all 0.2s;
            line-height: 1;
        }

        .btn-toggle-password:hover {
            color: var(--color-accent);
            background-color: #f0f4f7;
        }

        .input-group:focus-within .btn-toggle-password {
            border-color: var(--color-accent);
            background-color: white;
        }

        .input-group:focus-within .input-group-text,
        .input-group:focus-within .form-control {
            border-color: var(--color-accent);
            background-color: white;
        }

        .btn-register {
            background: var(--color-header);
            border: none;
            color: white;
            padding: 16px;
            font-weight: 600;
            font-family: 'Poppins', sans-serif;
            font-size: 1rem;
            border-radius: 14px;
            margin-top: 15px;
            transition: all 0.3s;
            letter-spacing: 0.5px;
        }

        .btn-register:hover {
            background: var(--color-header-dark);
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(21, 34, 48, 0.25);
            color: white;
        }

        .required { color: #e63946; }

        .info-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-left: 4px solid var(--color-accent);
            padding: 18px;
            border-radius: 12px;
            margin: 25px 0;
        }

        .form-text {
            font-size: 0.75rem;
            color: #6c757d;
            margin-top: 6px;
        }

        .alert {
            border-radius: 14px;
            border: none;
            padding: 15px 20px;
            font-size: 0.9rem;
        }

        @media (max-width: 576px) {
            body { padding: 20px 0; }
            .register-body { padding: 25px; }
            .register-header { padding: 40px 20px; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="register-card">
        <header class="register-header">
            <a href="{{ route('landing.index') }}" class="btn-back-home">
                <i class="bi bi-arrow-left"></i> Beranda
            </a>
            <i class="bi bi-person-badge-fill fs-1 mb-2 opacity-50"></i>
            <h1>REGISTRASI ALUMNI</h1>
            <p>SD Muhammadiyah Birrul Walidain Kudus</p>
        </header>

        <div class="register-body">

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-exclamation-octagon-fill me-2 fs-5"></i>
                        <strong>Mohon periksa kembali:</strong>
                    </div>
                    <ul class="mb-0 ps-3 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="section-title">Data Pribadi</div>

                <div class="mb-3">
                    <label for="nisn" class="form-label">NISN <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-hash"></i></span>
                        <input type="text"
                                class="form-control no-toggle @error('nisn') is-invalid @enderror"
                                id="nisn"
                                name="nisn"
                                value="{{ old('nisn') }}"
                                placeholder="Masukkan 10 digit NISN"
                                maxlength="10"
                                inputmode="numeric"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                required>
                    </div>
                    <div class="form-text">Pastikan NISN sesuai dengan data Dapodik sekolah.</div>
                </div>

                <div class="mb-3">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person-vcard"></i></span>
                        <input type="text"
                                class="form-control no-toggle @error('nama_lengkap') is-invalid @enderror"
                                id="nama_lengkap"
                                name="nama_lengkap"
                                value="{{ old('nama_lengkap') }}"
                                placeholder="Nama lengkap sesuai ijazah"
                                required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-7 mb-3">
                        <label for="angkatan_id" class="form-label">Angkatan <span class="required">*</span></label>
                        <select class="form-select @error('angkatan_id') is-invalid @enderror"
                                id="angkatan_id"
                                name="angkatan_id"
                                required>
                            <option value="">Pilih Angkatan</option>
                            @foreach($angkatans as $angkatan)
                                <option value="{{ $angkatan->id }}" {{ old('angkatan_id') == $angkatan->id ? 'selected' : '' }}>
                                    {{ $angkatan->nama_angkatan }} ({{ $angkatan->tahun_ajaran }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-5 mb-3">
                        <label for="tahun_lulus" class="form-label">Tahun Lulus <span class="required">*</span></label>
                        <input type="number"
                                class="form-control @error('tahun_lulus') is-invalid @enderror"
                                id="tahun_lulus"
                                name="tahun_lulus"
                                value="{{ old('tahun_lulus', date('Y')) }}"
                                min="2010"
                                max="{{ date('Y') + 1 }}"
                                required>
                    </div>
                </div>

                <div class="section-title">Kredensial Akun</div>

                <div class="mb-3">
                    <label for="username" class="form-label">Username <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-at"></i></span>
                        <input type="text"
                                class="form-control no-toggle @error('username') is-invalid @enderror"
                                id="username"
                                name="username"
                                value="{{ old('username') }}"
                                placeholder="Buat username (unique, tanpa spasi)"
                                required>
                    </div>
                </div>

                <div class="row">
                    {{-- Password --}}
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-shield-lock"></i></span>
                            <input type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    id="password"
                                    name="password"
                                    placeholder="Min. 6 karakter"
                                    required>
                            {{-- Tombol Show/Hide Password --}}
                            <button class="btn-toggle-password" type="button"
                                    id="togglePassword" title="Lihat/Sembunyikan Password">
                                <i class="bi bi-eye-fill" id="toggleIconPassword"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi <span class="required">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                            <input type="password"
                                    class="form-control"
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    placeholder="Ulangi password"
                                    required>
                            {{-- ✅ Tombol Show/Hide Konfirmasi --}}
                            <button class="btn-toggle-password" type="button"
                                    id="toggleConfirm" title="Lihat/Sembunyikan Password">
                                <i class="bi bi-eye-fill" id="toggleIconConfirm"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="info-box">
                    <div class="d-flex gap-3">
                        <i class="bi bi-info-circle-fill fs-5" style="color: var(--color-accent);"></i>
                        <div class="small text-muted">
                            Setelah mendaftar, akun Anda akan berstatus <strong>Pending</strong>.
                            Harap tunggu admin melakukan verifikasi ijazah/data siswa sebelum profil Anda aktif.
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-register w-100 mb-4">
                    Selesaikan Registrasi <i class="bi bi-arrow-right-short ms-1"></i>
                </button>
            </form>

            <div class="text-center pt-2">
                <p class="text-muted small">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="fw-bold text-decoration-none ms-1" style="color: var(--color-accent);">
                        Masuk Sekarang
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

{{-- Script Show/Hide Password --}}
<script>
    function togglePasswordVisibility(inputId, iconId, btnId) {
        const input  = document.getElementById(inputId);
        const icon   = document.getElementById(iconId);
        const btn    = document.getElementById(btnId);

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('bi-eye-fill', 'bi-eye-slash-fill');
            btn.title = 'Sembunyikan Password';
        } else {
            input.type = 'password';
            icon.classList.replace('bi-eye-slash-fill', 'bi-eye-fill');
            btn.title = 'Lihat Password';
        }
    }

    document.getElementById('togglePassword').addEventListener('click', function() {
        togglePasswordVisibility('password', 'toggleIconPassword', 'togglePassword');
    });

    document.getElementById('toggleConfirm').addEventListener('click', function() {
        togglePasswordVisibility('password_confirmation', 'toggleIconConfirm', 'toggleConfirm');
    });
</script>
</body>
</html>
