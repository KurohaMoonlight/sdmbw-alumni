@extends('layouts.admin')

@section('title', 'Laporan Angkatan')
@section('page-title', 'Laporan ' . $angkatan->nama_angkatan)

@section('content')
<div class="card border-0 shadow-sm mb-3">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <h5 class="mb-1 fw-bold text-dark">{{ $angkatan->nama_angkatan }}</h5>
            <small class="text-muted">
                <i class="bi bi-calendar3 me-1"></i> Tahun Ajaran: {{ $angkatan->tahun_ajaran }}
            </small>
        </div>
        <div class="no-print">
            <a href="{{ route('admin.laporan.index') }}" class="btn btn-sm btn-secondary px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </a>
            <button onclick="window.print()" class="btn btn-sm btn-primary px-3 shadow-sm">
                <i class="bi bi-printer me-1"></i> Print Laporan
            </button>
        </div>
    </div>
    <div class="card-body p-4">
        <!-- Statistik Cards -->
        <div class="row g-3 mb-4 text-center">
            <div class="col-6 col-md-2">
                <div class="p-2 rounded bg-light border">
                    <h4 class="fw-bold mb-0 text-dark">{{ $stats['total'] }}</h4>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 10px;">Total</small>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="p-2 rounded bg-light border">
                    <h4 class="fw-bold mb-0 text-success">{{ $stats['verified'] }}</h4>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 10px;">Verified</small>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="p-2 rounded bg-light border">
                    <h4 class="fw-bold mb-0 text-warning">{{ $stats['pending'] }}</h4>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 10px;">Pending</small>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="p-2 rounded bg-light border">
                    <h4 class="fw-bold mb-0 text-danger">{{ $stats['rejected'] }}</h4>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 10px;">Rejected</small>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="p-2 rounded bg-light border">
                    <h4 class="fw-bold mb-0 text-primary">{{ $stats['lengkap'] }}</h4>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 10px;">Profil Lengkap</small>
                </div>
            </div>
            <div class="col-6 col-md-2">
                <div class="p-2 rounded bg-light border">
                    <h4 class="fw-bold mb-0 text-secondary">{{ $stats['belum_lengkap'] }}</h4>
                    <small class="text-muted text-uppercase fw-bold" style="font-size: 10px;">Belum Lengkap</small>
                </div>
            </div>
        </div>

        <hr class="my-4">

        <div class="d-flex align-items-center mb-3">
            <i class="bi bi-people-fill text-primary me-2"></i>
            <h6 class="mb-0 fw-bold">Daftar Alumni Angkatan</h6>
        </div>

        <div class="table-responsive">
            <table class="table table-sm table-hover align-middle border">
                <thead class="table-light">
                    <tr>
                        <th class="text-center" width="50">No</th>
                        <th>NISN</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>No. HP</th>
                        <th>Pendidikan Terakhir</th>
                        <th>Pekerjaan Terkini</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alumniFormatted as $index => $item)
                    <tr>
                        <td class="text-center text-muted small">{{ $index + 1 }}</td>
                        <td class="fw-bold">
                            <code class="bg-light px-2 py-1 rounded" style="color: #d63384; font-size: 0.85rem;">
                                {{ $item['nisn'] }}
                            </code>
                        </td>
                        <td>{{ $item['nama_lengkap'] }}</td>
                        <td class="text-muted">{{ $item['username'] }}</td>
                        <td>{{ $item['no_hp'] }}</td>
                        <td class="small">{{ $item['pendidikan_terakhir'] }}</td>
                        <td class="small">{{ $item['pekerjaan_terkini'] }}</td>
                        <td class="text-center">
                            @if($item['status_verifikasi'] == 'verified')
                                <span class="badge bg-success px-2 py-1">Verified</span>
                            @elseif($item['status_verifikasi'] == 'pending')
                                <span class="badge bg-warning text-dark px-2 py-1">Pending</span>
                            @else
                                <span class="badge bg-danger px-2 py-1">Rejected</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4 text-muted">
                            <i class="bi bi-inbox fs-2 d-block mb-2"></i>
                            Belum ada alumni di angkatan ini
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        /* Sembunyikan elemen UI dashboard agar hasil print seperti dokumen resmi */
        .sidebar,
        .topbar,
        .btn,
        .no-print,
        footer,
        .navbar {
            display: none !important;
        }

        /* Hilangkan padding/margin sidebar jika ada */
        .main-content, #content-wrapper, body {
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Pastikan tabel mencakup seluruh lebar kertas */
        .card {
            border: none !important;
        }

        .table {
            width: 100% !important;
            border-collapse: collapse !important;
        }

        .table td, .table th {
            font-size: 10pt !important;
            border: 1px solid #dee2e6 !important;
        }

        .badge {
            border: 1px solid #000 !important;
            color: #000 !important;
            background-color: transparent !important;
        }
    }
</style>
@endpush
@endsection
