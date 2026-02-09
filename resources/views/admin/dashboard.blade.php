@extends('layouts.admin')

@section('title', 'Dashboard Admin')
@section('page-title', 'Ringkasan Sistem')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <a href="{{ route('admin.alumni.index') }}" class="text-decoration-none">
            <div class="stats-card primary shadow-sm">
                <div class="icon">
                    <i class="bi bi-people-fill"></i>
                </div>
                <h3>{{ number_format($stats['total_alumni']) }}</h3>
                <p class="mb-0">Total Alumni</p>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('admin.alumni.index', ['status' => 'verified']) }}" class="text-decoration-none">
            <div class="stats-card success shadow-sm">
                <div class="icon">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h3>{{ number_format($stats['alumni_verified']) }}</h3>
                <p class="mb-0">Terverifikasi</p>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('admin.alumni.index', ['status' => 'pending']) }}" class="text-decoration-none">
            <div class="stats-card warning shadow-sm position-relative">
                @if($stats['alumni_pending'] > 0)
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger shadow">
                        {{ $stats['alumni_pending'] }}
                        <span class="visually-hidden">pending alumni</span>
                    </span>
                @endif
                <div class="icon">
                    <i class="bi bi-clock-fill"></i>
                </div>
                <h3>{{ number_format($stats['alumni_pending']) }}</h3>
                <p class="mb-0">Butuh Verifikasi</p>
            </div>
        </a>
    </div>

    <div class="col-md-3">
        <a href="{{ route('admin.alumni.index', ['complete' => '1']) }}" class="text-decoration-none">
            <div class="stats-card info shadow-sm">
                <div class="icon">
                    <i class="bi bi-person-check-fill"></i>
                </div>
                <h3>{{ number_format($stats['profil_lengkap']) }}</h3>
                <p class="mb-0">Profil Lengkap</p>
            </div>
        </a>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 border-bottom-0">
                <h6 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-bar-chart-fill text-primary me-2"></i>Statistik Per Angkatan
                </h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-muted small text-uppercase">
                            <tr>
                                <th>Angkatan</th>
                                <th>Tahun Ajaran</th>
                                <th>Status</th>
                                <th class="text-center">Jumlah Alumni</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($angkatanStats as $angkatan)
                            <tr>
                                <td class="fw-bold">{{ $angkatan->nama_angkatan }}</td>
                                <td>{{ $angkatan->tahun_ajaran }}</td>
                                <td>
                                    @if($angkatan->status == 'LULUS')
                                        <span class="badge rounded-pill bg-light-success text-success border border-success px-3">LULUS</span>
                                    @elseif($angkatan->status == 'AKTIF')
                                        <span class="badge rounded-pill bg-light-warning text-warning border border-warning px-3">AKTIF</span>
                                    @else
                                        <span class="badge rounded-pill bg-light text-dark border px-3">{{ $angkatan->status }}</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="fw-bold">{{ number_format($angkatan->alumni_count) }}</span> <small class="text-muted">Orang</small>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted small">Belum ada data angkatan</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom-0">
                <h6 class="mb-0 fw-bold text-dark">
                    <i class="bi bi-person-plus-fill text-primary me-2"></i>Pendaftar Terbaru
                </h6>
                <a href="{{ route('admin.alumni.index') }}" class="btn btn-sm btn-outline-primary px-3 rounded-pill">Lihat Semua</a>
            </div>
            <div class="card-body">
                @forelse($recentAlumni as $alumni)
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm me-3 bg-light rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 40px; height: 40px; overflow: hidden; border: 2px solid #f0f0f0;">
                            @php
                                $fotoUtama = $alumni->fotos->where('is_main', true)->first();
                            @endphp
                            @if($fotoUtama)
                                <img src="{{ asset('storage/' . $fotoUtama->path_file) }}"
                                     class="img-fluid"
                                     style="width: 40px; height: 40px; object-fit: cover;"
                                     alt="Foto {{ $alumni->nama_lengkap }}">
                            @else
                                <i class="bi bi-person text-secondary fs-5"></i>
                            @endif
                        </div>
                        <div>
                            <a href="{{ route('admin.alumni.show', $alumni) }}" class="text-decoration-none">
                                <h6 class="mb-0 small fw-bold text-dark">{{ $alumni->nama_lengkap }}</h6>
                            </a>
                            <small class="text-muted" style="font-size: 11px;">
                                {{ $alumni->angkatan->nama_angkatan ?? 'Tanpa Angkatan' }}
                            </small>
                        </div>
                    </div>
                    <div>
                        @if($alumni->status_verifikasi == 'verified')
                            <span class="badge bg-success small fw-normal">
                                <i class="bi bi-check-circle-fill me-1"></i>Verified
                            </span>
                        @elseif($alumni->status_verifikasi == 'pending')
                            <span class="badge bg-warning text-dark small pulse fw-normal">
                                <i class="bi bi-hourglass-split me-1"></i>Pending
                            </span>
                        @else
                            <span class="badge bg-danger small fw-normal">
                                <i class="bi bi-x-circle-fill me-1"></i>Rejected
                            </span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="text-center py-5">
                    <i class="bi bi-inbox text-muted opacity-25" style="font-size: 3rem;"></i>
                    <p class="text-muted mt-2 small">Belum ada pendaftar baru</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<div class="row mt-4 mb-3">
    <div class="col-12">
        <div class="alert alert-white border-start border-info border-4 shadow-sm py-3 mb-0">
            <div class="d-flex align-items-center">
                <i class="bi bi-info-circle-fill text-info fs-4 me-3"></i>
                <div>
                    <h6 class="mb-0 fw-bold">Ringkasan Alumni</h6>
                    <p class="mb-0 small text-muted">Sistem mencatat <strong>{{ $stats['total_alumni'] }}</strong> total alumni dengan <strong>{{ $stats['alumni_verified'] }}</strong> sudah terverifikasi dan <strong>{{ $stats['alumni_pending'] }}</strong> menunggu verifikasi.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Pulse Animation untuk Status Pending */
    .pulse { animation: pulse-animation 2s infinite; }
    @keyframes pulse-animation {
        0% { box-shadow: 0 0 0 0px rgba(255, 193, 7, 0.4); }
        70% { box-shadow: 0 0 0 8px rgba(255, 193, 7, 0); }
        100% { box-shadow: 0 0 0 0px rgba(255, 193, 7, 0); }
    }

    /* Custom Color Badge */
    .bg-light-success { background-color: rgba(25, 135, 84, 0.1); }
    .bg-light-warning { background-color: rgba(255, 193, 7, 0.1); }

    /* Stats Card Styling */
    .stats-card {
        padding: 20px;
        border-radius: 12px;
        color: white;
        transition: all 0.3s ease;
    }
    .stats-card:hover { transform: translateY(-5px); }
    .stats-card.primary { background: linear-gradient(45deg, #0d6efd, #0043a8); }
    .stats-card.success { background: linear-gradient(45deg, #198754, #0f5132); }
    .stats-card.warning { background: linear-gradient(45deg, #ffc107, #e0a800); color: #000; }
    .stats-card.info { background: linear-gradient(45deg, #0dcaf0, #0a7e8f); }
    .stats-card .icon { font-size: 2.2rem; opacity: 0.25; float: right; margin-top: -5px; }
    .stats-card h3 { font-weight: 800; margin-top: 10px; font-size: 1.8rem; }
    .stats-card p { font-size: 0.9rem; opacity: 0.9; font-weight: 500; }
</style>
@endsection
