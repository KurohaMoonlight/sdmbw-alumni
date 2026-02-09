@extends('layouts.alumni')

@section('title', 'Dashboard Alumni')

@section('content')
<div class="row">
    {{-- ================= LEFT CONTENT ================= --}}
    <div class="col-md-8">
        {{-- STATUS AKUN --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Status Akun</h5>
                <div class="row g-3">
                    {{-- Status Verifikasi --}}
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">Status Verifikasi</small>
                        @switch($alumni->status_verifikasi)
                            @case('verified')
                                <span class="badge bg-success py-2 px-3 rounded-pill">
                                    <i class="bi bi-check-circle-fill me-1"></i> Terverifikasi
                                </span>
                                @break
                            @case('pending')
                                <span class="badge bg-secondary py-2 px-3 rounded-pill">
                                    <i class="bi bi-clock-fill me-1"></i> Menunggu Verifikasi
                                </span>
                                @break
                            @default
                                <span class="badge bg-danger py-2 px-3 rounded-pill">
                                    <i class="bi bi-x-circle-fill me-1"></i> Ditolak
                                </span>
                        @endswitch
                    </div>

                    {{-- Kelengkapan Profil --}}
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1">Kelengkapan Profil</small>
                        @if($alumni->is_profile_complete)
                            <span class="badge bg-primary py-2 px-3 rounded-pill">
                                <i class="bi bi-check-all me-1"></i> Data Lengkap
                            </span>
                        @else
                            <span class="badge bg-warning text-dark py-2 px-3 rounded-pill">
                                <i class="bi bi-exclamation-triangle-fill me-1"></i> Belum Lengkap
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Alert Pending Verifikasi --}}
                @if($alumni->status_verifikasi === 'pending')
                    <div class="alert alert-info border-0 shadow-sm mt-3 mb-0 d-flex align-items-center">
                        <i class="bi bi-info-circle-fill fs-4 me-3"></i>
                        <div><strong>Info:</strong> Akun Anda sedang ditinjau Admin. Silakan lengkapi biodata.</div>
                    </div>
                @endif

                {{-- Alert Profil Belum Lengkap --}}
                @if(!$alumni->is_profile_complete)
                    <div class="alert alert-warning border-0 shadow-sm mt-3 mb-0 d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill fs-4 me-3"></i>
                        <div>
                            <strong>Perhatian:</strong> Profil Anda belum lengkap.
                            <a href="{{ route('alumni.profile.edit') }}" class="fw-bold text-dark text-decoration-none">
                                Lengkapi Sekarang <i class="bi bi-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- BIODATA --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-person-lines-fill me-2"></i> Biodata Diri
                </h6>
            </div>
            <div class="card-body">
                {{-- NISN --}}
                <div class="row mb-3 border-bottom pb-2">
                    <div class="col-md-4 text-secondary">NISN</div>
                    <div class="col-md-8 fw-bold">{{ $alumni->nisn }}</div>
                </div>

                {{-- Nama Lengkap --}}
                <div class="row mb-3 border-bottom pb-2">
                    <div class="col-md-4 text-secondary">Nama Lengkap</div>
                    <div class="col-md-8 fw-bold">{{ $alumni->nama_lengkap }}</div>
                </div>

                {{-- Angkatan --}}
                <div class="row mb-3 border-bottom pb-2">
                    <div class="col-md-4 text-secondary">Angkatan</div>
                    <div class="col-md-8">
                        <span class="badge bg-light text-dark border">
                            {{ $alumni->angkatan->nama_angkatan ?? '-' }}
                        </span>
                        <small class="text-muted ms-1">
                            ({{ $alumni->angkatan->tahun_ajaran ?? '-' }})
                        </small>
                    </div>
                </div>

                {{-- Tahun Lulus Sekolah --}}
                <div class="row mb-3 border-bottom pb-2">
                    <div class="col-md-4 text-secondary">Tahun Lulus Sekolah</div>
                    <div class="col-md-8 fw-bold">{{ $alumni->tahun_lulus }}</div>
                </div>

                {{-- Alamat --}}
                <div class="row mb-3 border-bottom pb-2">
                    <div class="col-md-4 text-secondary">Alamat</div>
                    <div class="col-md-8">{{ $alumni->alamat ?? '-' }}</div>
                </div>

                {{-- No HP --}}
                <div class="row mb-3 border-bottom pb-2">
                    <div class="col-md-4 text-secondary">No HP</div>
                    <div class="col-md-8">{{ $alumni->no_hp ?? '-' }}</div>
                </div>

                {{-- Email --}}
                <div class="row mb-3 border-bottom pb-2">
                    <div class="col-md-4 text-secondary">Email</div>
                    <div class="col-md-8 fw-bold">
                        {{ $alumni->email ?? ($alumni->user->username ?? '-') }}
                    </div>
                </div>

                {{-- Harapan --}}
                <div class="row mb-3 border-bottom pb-2">
                    <div class="col-md-4 text-secondary">Harapan</div>
                    <div class="col-md-8">
                        <em class="text-muted">{{ $alumni->harapan ?? '-' }}</em>
                    </div>
                </div>

                {{-- RIWAYAT PENDIDIKAN --}}
                <div class="row mb-3 border-bottom pb-2">
                    <div class="col-md-4 text-secondary">Riwayat Pendidikan</div>
                    <div class="col-md-8">
                        @forelse($alumni->pendidikan as $edu)
                            <div class="mb-3">
                                {{-- Jenjang: Nama Instansi --}}
                                <div class="fw-bold">{{ $edu->jenjang }}: {{ $edu->nama_instansi }}</div>

                                {{-- Status Pendidikan --}}
                                @if($edu->is_ongoing)
                                    {{-- AKTIF: Masih Belajar --}}
                                    <span class="badge bg-primary text-white" style="font-size: 0.85rem;">
                                        <i class="bi bi-play-circle-fill me-1"></i> Aktif
                                    </span>
                                    <small class="text-muted ms-1">Tahun Masuk {{ $edu->tahun_masuk }}</small>
                                @else
                                    {{-- LULUS: Sudah Lulus --}}
                                    <span class="badge bg-success text-white" style="font-size: 0.85rem;">
                                        <i class="bi bi-check-circle-fill me-1"></i> Lulus
                                    </span>
                                    @if($edu->tahun_lulus)
                                        <small class="text-muted ms-1">Tahun {{ $edu->tahun_lulus }}</small>
                                    @endif
                                @endif

                                {{-- Program Studi (hanya untuk Perguruan Tinggi) --}}
                                @if($edu->jenjang === 'Perguruan Tinggi' && $edu->program_studi)
                                    <div class="small text-secondary mt-1">
                                        <i class="bi bi-mortarboard-fill me-1"></i> Prodi: {{ $edu->program_studi }}
                                    </div>
                                @endif
                            </div>
                        @empty
                            <span class="text-muted">-</span>
                        @endforelse
                    </div>
                </div>

                {{-- RIWAYAT PEKERJAAN --}}
                <div class="row mb-3">
                    <div class="col-md-4 text-secondary">Riwayat Pekerjaan</div>
                    <div class="col-md-8">
                        @forelse($alumni->pekerjaan as $job)
                            <div class="mb-2">
                                <i class="bi bi-briefcase me-1 text-success"></i>
                                <strong>{{ $job->nama_perusahaan }}</strong>
                                <small class="text-muted">({{ $job->jabatan }})</small>
                            </div>
                        @empty
                            <span class="text-muted">-</span>
                        @endforelse
                    </div>
                </div>

                {{-- Edit Profil Button --}}
                <div class="text-end mt-4">
                    <a href="{{ route('alumni.profile.edit') }}" class="btn btn-primary px-4">
                        <i class="bi bi-pencil-square me-2"></i> Edit Profil
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= RIGHT SIDEBAR ================= --}}
    <div class="col-md-4">
        {{-- PROFILE CARD --}}
        <div class="card border-0 shadow-sm mb-4 text-center">
            <div class="card-body py-5">
                <div class="mb-3">
                    @php
                        $fotoUtama = $alumni->fotos->where('is_main', true)->first();
                    @endphp

                    @if($fotoUtama)
                        <img src="{{ asset('storage/' . $fotoUtama->path_file) }}"
                             class="rounded-circle shadow-sm"
                             style="width: 110px; height: 110px; object-fit: cover;"
                             alt="Foto Profil">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto shadow-sm"
                             style="width: 110px; height: 110px; font-size: 40px; font-weight: bold;">
                            {{ strtoupper(substr($alumni->nama_lengkap, 0, 1)) }}
                        </div>
                    @endif
                </div>

                <h5 class="fw-bold mb-1">{{ $alumni->nama_lengkap }}</h5>
                <p class="text-muted mb-2">{{ $alumni->angkatan->nama_angkatan ?? 'Alumni' }}</p>
                <span class="badge bg-light text-secondary border">
                    {{ $alumni->email ?? (Auth::user()->username ?? 'User') }}
                </span>
            </div>
        </div>

        {{-- ACCOUNT INFO CARD --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-shield-lock me-2"></i> Informasi Akun
                </h6>
            </div>
            <div class="card-body small">
                <ul class="list-unstyled mb-0">
                    {{-- Tanggal Registrasi --}}
                    <li class="mb-3 d-flex align-items-center">
                        <i class="bi bi-calendar-check text-success fs-5 me-3"></i>
                        <div>
                            <span class="d-block text-muted">Tanggal Registrasi</span>
                            <strong>{{ optional($alumni->created_at)->format('d M Y') }}</strong>
                        </div>
                    </li>

                    {{-- Update Terakhir --}}
                    <li class="mb-3 d-flex align-items-center">
                        <i class="bi bi-arrow-repeat text-primary fs-5 me-3"></i>
                        <div>
                            <span class="d-block text-muted">Update Terakhir</span>
                            <strong>{{ optional($alumni->updated_at)->format('d M Y H:i') }}</strong>
                        </div>
                    </li>

                    {{-- Login Terakhir --}}
                    @if(Auth::user()->last_login_at)
                        <li class="d-flex align-items-center">
                            <i class="bi bi-box-arrow-in-right text-warning fs-5 me-3"></i>
                            <div>
                                <span class="d-block text-muted">Login Terakhir</span>
                                <strong>
                                    {{ \Carbon\Carbon::parse(Auth::user()->last_login_at)->format('d M Y H:i') }}
                                </strong>
                            </div>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
