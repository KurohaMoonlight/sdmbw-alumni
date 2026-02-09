@extends('layouts.alumni')

@section('title', 'Direktori Alumni')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0" style="color: #213448;">Direktori Alumni</h4>
        <span class="badge px-3 py-2" style="background-color: #213448;">{{ $alumni->total() }} Alumni Terdaftar</span>
    </div>

    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px;">
        <div class="card-body p-4">
            {{-- PERBAIKAN: Menggunakan rute alumni.direktori.index --}}
            <form action="{{ route('alumni.direktori.index') }}" method="GET" class="row g-3">
                <div class="col-md-5">
                    <label class="small fw-bold text-muted">Cari Nama</label>
                    <input type="text" name="search" class="form-control"
                           placeholder="Masukkan nama alumni..." value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <label class="small fw-bold text-muted">Pilih Angkatan</label>
                    <select name="angkatan" class="form-select">
                        <option value="">Semua Angkatan</option>
                        @foreach($angkatan as $a)
                            <option value="{{ $a->id }}" {{ request('angkatan') == $a->id ? 'selected' : '' }}>
                                {{ $a->nama_angkatan }} ({{ $a->tahun_ajaran }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 d-grid align-items-end">
                    <button type="submit" class="btn text-white fw-bold" style="background-color: #213448;">
                        <i class="bi bi-filter"></i> Filter Data
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="row g-4">
        @forelse($alumni as $item)
            <div class="col-md-6 col-lg-4 col-xl-3">
                <div class="card h-100 border-0 shadow-sm hover-card transition" style="border-radius: 12px;">
                    <div class="card-body text-center p-4">
                        <div class="mb-3">
                            {{-- PERBAIKAN: Logika pemanggilan foto utama dari relasi fotos --}}
                            @php
                                $fotoUtama = $item->fotos->where('is_main', true)->first();
                            @endphp

                            @if($fotoUtama)
                                <img src="{{ asset('storage/' . $fotoUtama->path_file) }}"
                                     class="rounded-circle border"
                                     style="width: 85px; height: 85px; object-fit: cover;"
                                     alt="{{ $item->nama_lengkap }}">
                            @else
                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center border"
                                     style="width: 85px; height: 85px; background-color: #f8f9fa;">
                                    <span class="fs-3 fw-bold" style="color: #213448;">
                                        {{ strtoupper(substr($item->nama_lengkap, 0, 1)) }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <h6 class="fw-bold mb-1" style="color: #213448;">{{ $item->nama_lengkap }}</h6>
                        <p class="text-muted small mb-3">
                             {{ $item->angkatan->nama_angkatan ?? 'Tanpa Angkatan' }}
                        </p>

                        <div class="d-grid">
                            <a href="{{ route('alumni.direktori.show', $item->id) }}"
                               class="btn btn-sm rounded-pill transition btn-view-profile"
                               style="border: 1px solid #213448; color: #213448; font-weight: 500;">
                                Lihat Profil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="text-muted mb-3">Data alumni tidak ditemukan.</div>
                {{-- PERBAIKAN: Reset pencarian ke rute yang benar --}}
                <a href="{{ route('alumni.direktori.index') }}" class="btn btn-link p-0" style="color: #213448; text-decoration: none;">
                    <i class="bi bi-arrow-clockwise"></i> Reset Pencarian
                </a>
            </div>
        @endforelse
    </div>

    {{-- Pagination dengan filter tetap terjaga (appends) --}}
    <div class="mt-5 d-flex justify-content-center">
        {{ $alumni->appends(request()->query())->links() }}
    </div>
</div>

<style>
    .hover-card:hover {
        transform: translateY(-7px);
        box-shadow: 0 10px 20px rgba(33, 52, 72, 0.15) !important;
    }
    .transition {
        transition: all 0.3s ease-in-out;
    }
    .btn-view-profile:hover {
        background-color: #213448 !important;
        color: white !important;
    }
    /* Sinkronisasi Warna Pagination Laravel/Bootstrap */
    .page-item.active .page-link {
        background-color: #213448 !important;
        border-color: #213448 !important;
        color: white !important;
    }
    .page-link {
        color: #213448;
    }
    .page-link:hover {
        color: #162533;
    }
</style>
@endsection
