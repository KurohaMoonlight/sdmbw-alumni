@extends('layouts.alumni')

@section('title', 'Direktori Alumni')

@section('content')
<div class="mb-4">
    <h3 class="fw-bold" style="color: var(--color-primary);">Direktori Alumni</h3>
    <p class="text-muted">Temukan dan terhubung dengan rekan alumni lainnya.</p>
</div>

<div class="card-custom border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h6 class="mb-3 fw-bold text-uppercase" style="color: var(--color-primary); letter-spacing: 1px;">
            <i class="bi bi-funnel-fill me-2"></i>Filter Pencarian
        </h6>
        <form action="{{ route('landing.direktori') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label small fw-bold text-muted">ANGKATAN</label>
                    <select name="angkatan_id" class="form-select custom-input">
                        <option value="">Semua Angkatan</option>
                        @foreach($angkatans as $angkatan)
                            <option value="{{ $angkatan->id }}" {{ request('angkatan_id') == $angkatan->id ? 'selected' : '' }}>
                                {{ $angkatan->nama_angkatan }} ({{ $angkatan->tahun_ajaran }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold text-muted">CARI NAMA / NISN</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0 custom-input">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 custom-input"
                               placeholder="Contoh: Siyatma Raka..."
                               value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary-custom w-100 py-2 shadow-sm">
                        Cari Alumni
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

@if($alumnis->count() > 0)
    <div class="row g-4 mb-5">
        @foreach($alumnis as $alumni)
        <div class="col-md-6 col-lg-4">
            <div class="card-custom h-100 border-0 shadow-sm hover-card">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-shrink-0">
                            <div class="avatar-circle">
                                <i class="bi bi-person-fill text-white"></i>
                            </div>
                        </div>
                        <div class="ms-3">
                            <h5 class="mb-1 fw-bold text-dark text-truncate" style="max-width: 180px;">
                                {{ $alumni->nama_lengkap }}
                            </h5>
                            <span class="badge bg-soft-primary text-primary border border-primary-subtle px-3 py-2">
                                {{ $alumni->angkatan->nama_angkatan ?? '-' }}
                            </span>
                        </div>
                    </div>

                    <hr class="opacity-10 mb-4">

                    <div class="info-list mb-4">
                        <div class="d-flex mb-3">
                            <div class="icon-box text-primary bg-light me-3">
                                <i class="bi bi-mortarboard"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">PENDIDIKAN</small>
                                <span class="text-dark small fw-semibold">{{ $alumni->pendidikan_lanjutan ?? 'Tidak diisi' }}</span>
                            </div>
                        </div>
                        <div class="d-flex mb-2">
                            <div class="icon-box text-success bg-light me-3">
                                <i class="bi bi-briefcase"></i>
                            </div>
                            <div>
                                <small class="text-muted d-block fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">PEKERJAAN</small>
                                <span class="text-dark small fw-semibold">{{ $alumni->pekerjaan ?? 'Tidak diisi' }}</span>
                            </div>
                        </div>
                    </div>

                    <a href="{{ route('landing.profil', $alumni->id) }}"
                       class="btn btn-outline-primary-custom w-100 py-2 rounded-3 fw-bold">
                        Detail Profil <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="d-flex justify-content-center pb-5">
        {{ $alumnis->links() }}
    </div>
@else
    <div class="text-center py-5">
        <img src="https://illustrations.popsy.co/gray/searching.svg" alt="Not found" style="width: 200px;" class="mb-4">
        <h4 class="fw-bold text-muted">Yah, Data Tidak Ditemukan</h4>
        <p class="text-muted mb-4">Kami tidak menemukan alumni dengan kriteria tersebut.</p>
        <a href="{{ route('landing.direktori') }}" class="btn btn-primary-custom px-4">
            <i class="bi bi-arrow-clockwise"></i> Reset Pencarian
        </a>
    </div>
@endif

<style>
    /* Global Styling Variables (Pastikan sudah didefinisikan di layout atau tambahkan di sini) */
    :root {
        --color-primary: #1e3a5f;
        --color-primary-light: #2c5282;
    }

    /* Input & Form Styling */
    .custom-input {
        border: 1px solid #e2e8f0 !important;
        padding: 0.75rem 1rem !important;
        background-color: #f8fafc !important;
    }

    .custom-input:focus {
        background-color: #fff !important;
        border-color: var(--color-primary) !important;
        box-shadow: 0 0 0 4px rgba(30, 58, 95, 0.05) !important;
    }

    /* Card Styling */
    .hover-card {
        transition: all 0.3s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.08) !important;
    }

    .avatar-circle {
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.8rem;
    }

    .icon-box {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1rem;
    }

    /* Badge & Button Custom */
    .bg-soft-primary {
        background-color: rgba(30, 58, 95, 0.08);
    }

    .btn-primary-custom {
        background: var(--color-primary);
        border: none;
        color: white;
        font-weight: 600;
        border-radius: 8px;
    }

    .btn-primary-custom:hover {
        background: var(--color-primary-light);
        color: white;
    }

    .btn-outline-primary-custom {
        border: 2px solid var(--color-primary);
        color: var(--color-primary);
        transition: all 0.2s;
    }

    .btn-outline-primary-custom:hover {
        background: var(--color-primary);
        color: white;
    }

    /* Pagination Styling */
    .pagination .page-link {
        border-radius: 8px !important;
        margin: 0 3px;
        border: none;
        color: var(--color-primary);
        font-weight: 600;
    }

    .pagination .page-item.active .page-link {
        background-color: var(--color-primary);
        color: white;
    }
</style>
@endsection
