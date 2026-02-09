@extends('layouts.landing')

@section('title', 'Profil ' . $alumni->nama_lengkap)

@section('content')

{{-- ================= HEADER ================= --}}
<section class="bg-white py-5 border-bottom">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-3">
                <li class="breadcrumb-item"><a href="{{ route('landing.direktori') }}" class="text-decoration-none text-secondary">Direktori</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $alumni->nama_lengkap }}</li>
            </ol>
        </nav>

        <div class="row align-items-center">
            <div class="col-md-8">
                <h2 class="fw-bold mb-2">{{ $alumni->nama_lengkap }}</h2>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-primary px-3 py-2">
                        <i class="bi bi-mortarboard-fill me-1"></i> {{ $alumni->angkatan->nama_angkatan ?? 'Tanpa Angkatan' }}
                    </span>

                    @if ($alumni->status_verifikasi === 'verified')
                        <span class="badge bg-success px-3 py-2">
                            <i class="bi bi-check-circle-fill me-1"></i> Akun Terverifikasi
                        </span>
                    @endif
                </div>
            </div>

            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <i class="bi bi-person-circle" style="font-size: 5rem; color: #dee2e6;"></i>
            </div>
        </div>
    </div>
</section>

{{-- ================= PROFIL DETAIL ================= --}}
<section class="py-5 bg-light">
    <div class="container">
        <div class="row g-4">

            {{-- ===== KONTEN UTAMA ===== --}}
            <div class="col-lg-8">
                {{-- Data Pribadi --}}
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 d-flex align-items-center">
                            <i class="bi bi-person-badge text-primary me-2"></i> Identitas Alumni
                        </h5>

                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted">NISN</div>
                            <div class="col-sm-8 fw-bold">{{ $alumni->nisn ?? '-' }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted">Tahun Ajaran</div>
                            <div class="col-sm-8">{{ $alumni->angkatan->tahun_ajaran ?? '-' }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-sm-4 text-muted">Tahun Lulus Sekolah</div>
                            <div class="col-sm-8 fw-bold">{{ $alumni->tahun_lulus }}</div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-sm-4 text-muted">Status Profil</div>
                            <div class="col-sm-8">
                                @if($alumni->is_profile_complete)
                                    <span class="text-success"><i class="bi bi-check2-all"></i> Data Lengkap</span>
                                @else
                                    <span class="text-warning"><i class="bi bi-exclamation-triangle"></i> Data Belum Lengkap</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Pendidikan & Pekerjaan --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-4 d-flex align-items-center">
                            <i class="bi bi-briefcase text-primary me-2"></i> Riwayat & Kesibukan
                        </h5>

                        <div class="row mb-4">
                            <div class="col-sm-4 text-muted">Pendidikan Lanjutan</div>
                            <div class="col-sm-8">
                                <p class="fw-bold mb-1">{{ $alumni->pendidikan_lanjutan ?? '-' }}</p>
                                <small class="text-muted">Instansi/Universitas setelah lulus.</small>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-sm-4 text-muted">Pekerjaan Saat Ini</div>
                            <div class="col-sm-8">
                                <p class="fw-bold mb-1">{{ $alumni->pekerjaan ?? 'Belum bekerja / Mencari Pekerjaan' }}</p>
                                <small class="text-muted">Bidang pekerjaan yang sedang ditekuni.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== SIDEBAR ===== --}}
            <div class="col-lg-4">
                {{-- Harapan --}}
                <div class="card border-0 shadow-sm mb-4 bg-primary text-white">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3 d-flex align-items-center">
                            <i class="bi bi-chat-quote me-2 text-white"></i> Harapan & Pesan
                        </h6>
                        <div class="bg-white text-dark rounded p-3 shadow-sm">
                            {{-- Perbaikan: Menggunakan trim dan pengecekan yang lebih kuat --}}
                            @php $isiHarapan = trim($alumni->harapan); @endphp

                            @if (!empty($isiHarapan))
                                <p class="fst-italic mb-0" style="line-height: 1.6;">
                                    "{{ $isiHarapan }}"
                                </p>
                            @else
                                <p class="text-muted small mb-0">Alumni ini belum menuliskan pesan atau harapan.</p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Kontak --}}
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <h6 class="fw-bold mb-3">Hubungi Alumni</h6>
                        @if ($alumni->email)
                            <a href="mailto:{{ $alumni->email }}" class="btn btn-primary w-100 mb-2">
                                <i class="bi bi-envelope-at me-2"></i> Kirim Email
                            </a>
                        @else
                            <div class="alert alert-light border-0 mb-0 py-2 text-center text-muted small">
                                Email tidak dicantumkan publik.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
