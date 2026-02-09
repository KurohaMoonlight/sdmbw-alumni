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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&family=Inter:wght@400;500;600&display=swap" rel="stylesheet">
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
            padding: 40px 0;
            display: flex;
            align-items: center;
        }

        .register-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(33, 52, 72, 0.2);
            overflow: hidden;
            max-width: 550px;
            margin: 0 auto;
            border: none;
        }

        .register-header {
            background: var(--color-header);
            color: white;
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }

        .btn-back-home {
            position: absolute;
            top: 20px;
            left: 20px;
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-size: 0.85rem;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .btn-back-home:hover {
            color: white;
            transform: translateX(-3px);
        }

        .register-header h1 {
            font-family: 'Poppins', sans-serif;
            font-size: 22px;
            font-weight: 600;
            margin: 0 0 8px 0;
            letter-spacing: 1px;
        }

        .register-header p {
            margin: 0;
            font-size: 13px;
            opacity: 0.8;
            font-weight: 400;
        }

        .register-body {
            padding: 40px;
        }

        .section-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 700;
            color: var(--color-accent);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #eee;
        }

        .form-label {
            font-weight: 600;
            color: var(--color-header);
            font-size: 0.9rem;
            margin-bottom: 8px;
        }

        .form-control,
        .form-select {
            padding: 12px 15px;
            border-radius: 10px;
            border: 1.5px solid #eee;
            font-size: 0.95rem;
            transition: all 0.3s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--color-accent);
            box-shadow: 0 0 0 4px rgba(84, 119, 146, 0.1);
        }

        .input-group-text {
            border-radius: 10px 0 0 10px;
            border: 1.5px solid #eee;
            border-right: none;
        }

        .input-group .form-control {
            border-radius: 0 10px 10px 0;
        }

        .btn-register {
            background: var(--color-header);
            border: none;
            color: white;
            padding: 14px;
            font-weight: 600;
            border-radius: 12px;
            margin-top: 10px;
            transition: all 0.3s;
        }

        .btn-register:hover {
            background: var(--color-header-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            color: white;
        }

        .required {
            color: #dc3545;
        }

        .info-box {
            background-color: #f8f9fa;
            border-left: 4px solid var(--color-accent);
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .alert {
            border-radius: 10px;
            border: none;
        }

        a {
            transition: all 0.3s;
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
            <h1>REGISTRASI ALUMNI</h1>
            <p>SD Muhammadiyah Birrul Walidain Kudus</p>
        </header>

        <div class="register-body">

            @if($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Terjadi kesalahan:</strong>
                    <ul class="mb-0 mt-1 ps-3 small">
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

                <!-- NISN (FINAL FIX) -->
                <div class="mb-3">
                    <label for="nisn" class="form-label">NISN <span class="required">*</span></label>
                    <input type="text"
                            class="form-control @error('nisn') is-invalid @enderror"
                            id="nisn"
                            name="nisn"
                            value="{{ old('nisn') }}"
                            placeholder="Masukkan NISN 10 Anda"
                            maxlength="10"
                            inputmode="numeric"
                            pattern="[0-9]{10}"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                            required>
                    @error('nisn')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <div class="form-text" style="font-size:0.75rem;">
                        NISN harus terdiri dari 10 digit angka
                    </div>
                </div>

                <div class="mb-3">
                    <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="required">*</span></label>
                    <input type="text"
                            class="form-control @error('nama_lengkap') is-invalid @enderror"
                            id="nama_lengkap"
                            name="nama_lengkap"
                            value="{{ old('nama_lengkap') }}"
                            placeholder="Masukkan nama lengkap sesuai ijazah"
                            required>
                    @error('nama_lengkap')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
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
                        @error('angkatan_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                        @error('tahun_lulus')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="section-title mt-4">Kredensial Akun</div>

                <div class="mb-3">
                    <label for="username" class="form-label">Username <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0">
                            <i class="bi bi-person"></i>
                        </span>
                        <input type="text"
                               class="form-control border-start-0 @error('username') is-invalid @enderror"
                               id="username"
                               name="username"
                               value="{{ old('username') }}"
                               placeholder="Buat username login"
                               required>
                    </div>
                    @error('username')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password <span class="required">*</span></label>
                        <input type="password"
                               class="form-control @error('password') is-invalid @enderror"
                               id="password"
                               name="password"
                               placeholder="Min. 6 karakter"
                               required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="password_confirmation" class="form-label">Konfirmasi <span class="required">*</span></label>
                        <input type="password"
                               class="form-control"
                               id="password_confirmation"
                               name="password_confirmation"
                               placeholder="Ulangi password"
                               required>
                    </div>
                </div>

                <div class="info-box mt-2">
                    <div class="d-flex gap-2">
                        <i class="bi bi-info-circle-fill text-primary"></i>
                        <small class="text-muted">
                            Akun Anda akan berstatus <strong>Pending</strong>.
                            Admin akan melakukan verifikasi sebelum profil muncul.
                        </small>
                    </div>
                </div>

                <button type="submit" class="btn btn-register w-100 mb-4">
                    <i class="bi bi-check-circle me-2"></i>Selesaikan Registrasi
                </button>
            </form>

            <div class="text-center">
                <p class="text-muted small">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="fw-bold text-decoration-none" style="color: var(--color-accent);">
                        Masuk Sekarang
                    </a>
                </p>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
