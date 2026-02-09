@extends('layouts.admin')

@section('title', 'Reset Password Alumni')
@section('page-title', 'Reset Password Alumni by NISN')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-4">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-key me-2"></i> Reset Password Alumni
                </h5>
                <small class="text-muted">Cari alumni berdasarkan NISN dan reset password mereka</small>
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm mb-4">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                            <div>
                                <strong>Terjadi Kesalahan!</strong>
                                <ul class="mb-0 mt-2 small">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.alumni.resetPasswordByNisn') }}" method="POST">
                    @csrf

                    <!-- NISN Input -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-key-fill me-1"></i> NISN Alumni
                            <span class="text-danger">*</span>
                        </label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-light border-end-0">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text"
                                   class="form-control border-start-0 @error('nisn') is-invalid @enderror"
                                   name="nisn"
                                   placeholder="Contoh: 0051069834"
                                   value="{{ old('nisn') }}"
                                   required>
                            @error('nisn')
                                <div class="invalid-feedback d-block">
                                    <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <small class="text-muted mt-2 d-block">
                            <i class="bi bi-info-circle me-1"></i> Masukkan NISN alumni yang password-nya ingin direset
                        </small>
                    </div>

                    <!-- Password Baru -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-lock-fill me-1"></i> Password Baru
                            <span class="text-danger">*</span>
                        </label>
                        <input type="password"
                               class="form-control form-control-lg @error('password') is-invalid @enderror"
                               name="password"
                               placeholder="Minimal 6 karakter"
                               required>
                        @error('password')
                            <div class="invalid-feedback d-block">
                                <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                        <small class="text-muted mt-2 d-block">
                            <i class="bi bi-info-circle me-1"></i> Password minimal 6 karakter
                        </small>
                    </div>

                    <!-- Konfirmasi Password -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="bi bi-lock-fill me-1"></i> Konfirmasi Password
                            <span class="text-danger">*</span>
                        </label>
                        <input type="password"
                               class="form-control form-control-lg @error('password_confirmation') is-invalid @enderror"
                               name="password_confirmation"
                               placeholder="Ulangi password baru"
                               required>
                        @error('password_confirmation')
                            <div class="invalid-feedback d-block">
                                <i class="bi bi-exclamation-circle me-1"></i> {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <!-- Alert Info -->
                    <div class="alert alert-info border-0 shadow-sm mb-4">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <strong>Catatan:</strong> Password akan langsung berubah setelah form ini disubmit. Alumni dapat login dengan password baru ini.
                    </div>

                    <!-- Buttons -->
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                            <i class="bi bi-check-circle me-2"></i> Reset Password
                        </button>
                        <a href="{{ route('admin.alumni.index') }}" class="btn btn-secondary btn-lg fw-bold">
                            <i class="bi bi-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-question-circle me-2"></i> Bantuan
                </h6>
            </div>
            <div class="card-body small">
                <p class="mb-2">
                    <strong>📌 Bagaimana cara menggunakan fitur ini?</strong>
                </p>
                <ol class="ps-3 mb-0">
                    <li class="mb-2">Masukkan NISN alumni yang ingin direset passwordnya</li>
                    <li class="mb-2">Masukkan password baru (minimal 6 karakter)</li>
                    <li class="mb-2">Konfirmasi password yang sama</li>
                    <li>Klik tombol "Reset Password" untuk menyimpan</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<style>
    .form-control-lg, .input-group-lg .form-control, .input-group-lg .input-group-text {
        font-size: 1rem;
        padding: 12px 16px;
    }

    .btn-lg {
        padding: 12px 24px;
        font-size: 1rem;
    }

    @media (max-width: 576px) {
        .col-md-6 {
            padding: 0 10px;
        }

        .card-body {
            padding: 20px !important;
        }

        .form-label {
            font-size: 0.95rem;
        }

        .btn-lg {
            padding: 10px 16px;
            font-size: 0.9rem;
        }
    }
</style>
@endsection
