@extends('layouts.admin')

@section('title', 'Laporan')
@section('page-title', 'Laporan & Statistik')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-4">
                <i class="bi bi-people text-primary fs-2 mb-2"></i>
                <h3 class="fw-bold mb-1">{{ number_format($stats['total_alumni']) }}</h3>
                <p class="text-muted mb-0 small">Total Alumni</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-4">
                <i class="bi bi-patch-check text-success fs-2 mb-2"></i>
                <h3 class="fw-bold mb-1 text-success">{{ number_format($stats['alumni_verified']) }}</h3>
                <p class="text-muted mb-0 small">Alumni Verified</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-4">
                <i class="bi bi-clock-history text-warning fs-2 mb-2"></i>
                <h3 class="fw-bold mb-1 text-warning">{{ number_format($stats['alumni_pending']) }}</h3>
                <p class="text-muted mb-0 small">Menunggu Verifikasi</p>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center py-4">
                <i class="bi bi-file-earmark-check text-primary fs-2 mb-2"></i>
                <h3 class="fw-bold mb-1 text-primary">{{ number_format($stats['profil_lengkap']) }}</h3>
                <p class="text-muted mb-0 small">Profil Lengkap</p>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <h6 class="mb-0 fw-bold">
            <i class="bi bi-calendar-event me-2"></i>Statistik per Angkatan
        </h6>
        <button onclick="window.print()" class="btn btn-sm btn-primary no-print px-3">
            <i class="bi bi-printer me-1"></i> <span class="d-none d-sm-inline">Cetak Laporan</span>
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Angkatan</th>
                        <th class="d-none d-md-table-cell">Tahun Ajaran</th>
                        <th class="d-none d-sm-table-cell">Status</th>
                        <th class="text-center">Alumni</th>
                        <th class="text-center d-none d-lg-table-cell">Verified</th>
                        <th class="text-center d-none d-lg-table-cell">Pending</th>
                        <th class="text-center d-none d-xl-table-cell">Lengkap</th>
                        <th class="text-end pe-4 no-print">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($angkatanStats as $angkatan)
                    <tr>
                        <td class="ps-4"><strong>{{ $angkatan->nama_angkatan }}</strong></td>
                        <td class="d-none d-md-table-cell text-muted">{{ $angkatan->tahun_ajaran }}</td>
                        <td class="d-none d-sm-table-cell">
                            @if($angkatan->status == 'LULUS')
                                <span class="badge bg-success-subtle text-success border border-success-subtle px-2">LULUS</span>
                            @elseif($angkatan->status == 'AKTIF')
                                <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2">AKTIF</span>
                            @endif
                        </td>
                        <td class="text-center fw-bold">{{ $angkatan->alumni_count }}</td>
                        <td class="text-center d-none d-lg-table-cell">
                            <span class="badge rounded-pill bg-success px-3">{{ $angkatan->verified_count }}</span>
                        </td>
                        <td class="text-center d-none d-lg-table-cell">
                            <span class="badge rounded-pill bg-warning text-dark px-3">{{ $angkatan->pending_count }}</span>
                        </td>
                        <td class="text-center d-none d-xl-table-cell">
                            <span class="badge rounded-pill bg-primary px-3">{{ $angkatan->complete_count }}</span>
                        </td>
                        <td class="text-end pe-4 no-print">
                            <a href="{{ route('admin.laporan.angkatan', $angkatan->id) }}" class="btn btn-sm btn-info text-white shadow-sm">
                                <i class="bi bi-eye"></i><span class="d-none d-md-inline ms-1"> Detail</span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light border-top">
                    <tr class="fw-bold">
                        <td colspan="3" class="ps-4">TOTAL KESELURUHAN</td>
                        <td class="text-center">{{ number_format($stats['total_alumni']) }}</td>
                        <td class="text-center d-none d-lg-table-cell">{{ number_format($stats['alumni_verified']) }}</td>
                        <td class="text-center d-none d-lg-table-cell">{{ number_format($stats['alumni_pending']) }}</td>
                        <td class="text-center d-none d-xl-table-cell">{{ number_format($stats['profil_lengkap']) }}</td>
                        <td class="no-print pe-4"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-mortarboard me-2"></i>Top 10 Pendidikan Lanjutan
                </h6>
            </div>
            <div class="card-body">
                @if($pendidikanStats->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="text-muted small">
                                <tr>
                                    <th>Sekolah/Universitas</th>
                                    <th class="text-center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendidikanStats as $item)
                                <tr>
                                    <td class="py-2">{{ $item->pendidikan_lanjutan }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3">{{ $item->total }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox text-muted display-6 d-block mb-2"></i>
                        <p class="text-muted mb-0">Belum ada data pendidikan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-12 col-lg-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold text-success">
                    <i class="bi bi-briefcase me-2"></i>Top 10 Pekerjaan
                </h6>
            </div>
            <div class="card-body">
                @if($pekerjaanStats->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-sm table-hover align-middle">
                            <thead class="text-muted small">
                                <tr>
                                    <th>Bidang Pekerjaan</th>
                                    <th class="text-center">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pekerjaanStats as $item)
                                <tr>
                                    <td class="py-2">{{ $item->pekerjaan }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3">{{ $item->total }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-inbox text-muted display-6 d-block mb-2"></i>
                        <p class="text-muted mb-0">Belum ada data pekerjaan</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print { display: none !important; }
        .card { border: 1px solid #ddd !important; box-shadow: none !important; }
        .badge { border: 1px solid #000 !important; color: #000 !important; background: transparent !important; }
    }
</style>
@endsection
