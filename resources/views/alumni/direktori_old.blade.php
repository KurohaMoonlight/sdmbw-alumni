@extends('layouts.alumni')

@section('title', 'Direktori Alumni')

@section('content')
<!-- Filter & Search -->
<div class="card-custom mb-4">
    <div class="card-body">
        <h5 class="mb-3" style="color: var(--color-primary); font-weight: 700;">
            <i class="bi bi-funnel"></i> Filter & Pencarian
        </h5>
        <form action="{{ route('alumni.direktori') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Angkatan</label>
                    <select name="angkatan_id" class="form-select">
                        <option value="">Semua Angkatan</option>
                        @foreach($angkatans as $angkatan)
                            <option value="{{ $angkatan->id }}" {{ request('angkatan_id') == $angkatan->id ? 'selected' : '' }}>
                                {{ $angkatan->nama_angkatan }} ({{ $angkatan->tahun_ajaran }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Cari Alumni</label>
                    <input type="text" name="search" class="form-control"
                           placeholder="Cari nama atau NISN..."
                           value="{{ request('search') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label d-none d-md-block">&nbsp;</label>
                    <button type="submit" class="btn btn-primary-custom w-100">
                        <i class="bi bi-search"></i> Cari
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Daftar Alumni -->
@if($alumnis->count() > 0)
    <div class="row g-4 mb-4">
        @foreach($alumnis as $alumni)
        <div class="col-md-6 col-lg-4">
            <div class="card-custom h-100">
                <div class="card-body text-center">
                    <!-- Avatar -->
                    <div class="mb-3">
                        <div style="width: 80px; height: 80px; margin: 0 auto; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                            <i class="bi bi-person-fill" style="font-size: 2.5rem; color: white;"></i>
                        </div>
                    </div>

                    <!-- Nama -->
                    <h5 class="mb-2" style="color: var(--color-primary); font-weight: 700;">
                        {{ $alumni->nama_lengkap }}
                    </h5>

                    <!-- Badge Angkatan -->
                    <div class="mb-3">
                        <span class="badge" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.85rem;">
                            {{ $alumni->angkatan->nama_angkatan ?? '-' }}
                        </span>
                    </div>

                    <!-- Info Pendidikan & Pekerjaan -->
                    <div class="text-start mb-3">
                        @if($alumni->pendidikan_lanjutan)
                            <p class="text-muted mb-2" style="font-size: 0.9rem;">
                                <i class="bi bi-book-fill text-primary"></i>
                                <strong>Pendidikan:</strong><br>
                                <span class="ms-4">{{ Str::limit($alumni->pendidikan_lanjutan, 30) }}</span>
                            </p>
                        @endif

                        @if($alumni->pekerjaan)
                            <p class="text-muted mb-0" style="font-size: 0.9rem;">
                                <i class="bi bi-briefcase-fill text-success"></i>
                                <strong>Pekerjaan:</strong><br>
                                <span class="ms-4">{{ Str::limit($alumni->pekerjaan, 30) }}</span>
                            </p>
                        @endif
                    </div>

                    <!-- Button Lihat Profil -->
                    <a href="{{ route('alumni.profil.show', $alumni->id) }}"
                        class="btn btn-primary-custom btn-sm w-100">
                        <i class="bi bi-eye"></i> Lihat Profil
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $alumnis->links() }}
    </div>
@else
    <div class="card-custom">
        <div class="card-body text-center py-5">
            <div style="width: 100px; height: 100px; margin: 0 auto; background: rgba(108, 117, 125, 0.1); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 1.5rem;">
                <i class="bi bi-inbox" style="font-size: 3rem; color: #6c757d;"></i>
            </div>
            <h5 class="text-muted mb-2">Tidak ada alumni yang ditemukan</h5>
            <p class="text-muted mb-3">Coba ubah filter atau kata kunci pencarian Anda</p>
            <a href="{{ route('alumni.direktori') }}" class="btn btn-primary-custom">
                <i class="bi bi-arrow-clockwise"></i> Reset Filter
            </a>
        </div>
    </div>
@endif

<!-- Styling tambahan untuk pagination -->
<style>
    .pagination {
        margin: 0;
    }

    .pagination .page-link {
        color: var(--color-primary);
        border: 1px solid #dee2e6;
        padding: 0.5rem 0.75rem;
        border-radius: 8px;
        margin: 0 0.25rem;
        transition: all 0.3s ease;
    }

    .pagination .page-link:hover {
        background: var(--color-primary);
        color: white;
        border-color: var(--color-primary);
        transform: translateY(-2px);
    }

    .pagination .page-item.active .page-link {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%);
        border-color: var(--color-primary);
        box-shadow: 0 4px 12px rgba(33, 52, 72, 0.2);
    }

    .pagination .page-item.disabled .page-link {
        color: #6c757d;
        background: #f8f9fa;
    }

    .form-label {
        color: var(--color-primary);
        margin-bottom: 0.5rem;
    }

    .form-select, .form-control {
        border: 2px solid #e9ecef;
        border-radius: 8px;
        padding: 0.6rem 0.75rem;
        transition: all 0.3s ease;
    }

    .form-select:focus, .form-control:focus {
        border-color: var(--color-primary);
        box-shadow: 0 0 0 0.2rem rgba(33, 52, 72, 0.1);
    }

    /* Responsive adjustments */
    @media (max-width: 767px) {
        .card-custom .card-body {
            padding: 1.5rem 1rem;
        }

        h5 {
            font-size: 1rem;
        }

        .pagination {
            font-size: 0.9rem;
        }

        .pagination .page-link {
            padding: 0.4rem 0.6rem;
        }
    }
</style>
@endsection
