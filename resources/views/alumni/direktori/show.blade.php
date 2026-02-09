@extends('layouts.alumni')

@section('title', 'Profil ' . $alumni->nama_lengkap)

@section('content')
<div class="container-fluid">

    {{-- BACK: Perbaikan Route agar tidak error 500 --}}
    <div class="mb-4">
        {{-- PERBAIKAN: Menggunakan alumni.direktori.index agar sesuai dengan web.php --}}
        <a href="{{ route('alumni.direktori.index') }}" class="fw-bold text-decoration-none" style="color:#213448;">
            <i class="bi bi-arrow-left"></i> Kembali ke Direktori
        </a>
    </div>

    <div class="row">

        {{-- ================= LEFT PROFILE ================= --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm text-center p-4 mb-4" style="border-radius:15px;">
                <div class="card-body">

                    {{-- FOTO PROFIL --}}
                    <div class="mb-3">
                        @php
                            $fotoUtama = $alumni->fotos->where('is_main', true)->first();
                        @endphp
                        @if($fotoUtama)
                            <img
                                src="{{ asset('storage/' . $fotoUtama->path_file) }}"
                                class="rounded-circle border shadow-sm"
                                style="width:150px;height:150px;object-fit:cover;"
                                alt="Foto {{ $alumni->nama_lengkap }}">
                        @else
                            <div
                                class="rounded-circle d-flex align-items-center justify-content-center mx-auto border"
                                style="width:150px;height:150px;background:#f8f9fa;">
                                <span class="display-3 fw-bold" style="color:#213448;">
                                    {{ strtoupper(substr($alumni->nama_lengkap, 0, 1)) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    <h4 class="fw-bold mb-1" style="color:#213448;">
                        {{ $alumni->nama_lengkap }}
                    </h4>

                    <span class="badge px-3 py-2" style="background:#213448;">
                        {{ $alumni->angkatan->nama_angkatan ?? 'Alumni' }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ================= RIGHT CONTENT ================= --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm" style="border-radius:15px;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0" style="color:#213448;">Informasi Lengkap</h5>
                </div>

                <div class="card-body p-4">
                    <div class="row g-4">

                        <div class="col-md-6">
                            <label class="small text-muted">Nama Lengkap</label>
                            <div class="fw-bold">{{ $alumni->nama_lengkap }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="small text-muted">Angkatan</label>
                            <div class="fw-bold">{{ $alumni->angkatan->nama_angkatan }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="small text-muted">Tahun Ajaran</label>
                            <div class="fw-bold">{{ $alumni->angkatan->tahun_ajaran }}</div>
                        </div>

                        <div class="col-md-6">
                            <label class="small text-muted">Status Kelulusan</label>
                            <div>
                                <span class="badge {{ ($alumni->angkatan->status ?? '') === 'LULUS' ? 'bg-success' : 'bg-warning text-dark' }}">
                                    {{ $alumni->angkatan->status ?? '-' }}
                                </span>
                            </div>
                        </div>

                        {{-- ================= KONTAK ================= --}}
                        <div class="col-12">
                            <hr class="opacity-25">
                            <label class="small text-muted mb-2 d-block">Kontak</label>

                            <div class="d-flex gap-2 flex-wrap">
                                {{-- Fallback Email ke Username jika email kosong --}}
                                @php $displayEmail = $alumni->email ?? ($alumni->user->username ?? null); @endphp
                                @if($displayEmail)
                                    <a href="mailto:{{ $displayEmail }}"
                                       class="badge bg-light text-dark border p-2 text-decoration-none">
                                        <i class="bi bi-envelope me-1"></i> {{ $displayEmail }}
                                    </a>
                                @endif

                                {{-- Tampilkan Nomor HP/WA hanya jika show_no_hp = 1 dan no_hp tidak kosong --}}
                                @if($alumni->show_no_hp && !empty($alumni->no_hp))
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $alumni->no_hp) }}"
                                       target="_blank"
                                       class="badge bg-success p-2 text-decoration-none text-white">
                                        <i class="bi bi-whatsapp me-1"></i> {{ $alumni->no_hp }}
                                    </a>
                                @endif

                                @if(!$displayEmail && !($alumni->show_no_hp && !empty($alumni->no_hp)))
                                    <span class="text-muted fst-italic small">
                                        Informasi kontak tidak tersedia.
                                    </span>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ================= PENDIDIKAN LANJUTAN (MULTIPLE) ================= --}}
    @if($alumni->pendidikan->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius:15px;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0" style="color:#213448;">Pendidikan Lanjutan</h5>
                </div>
                <div class="card-body p-4">
                    @foreach($alumni->pendidikan as $edu)
                        <div class="{{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                            <div class="fw-bold text-dark">{{ $edu->nama_instansi }}</div>
                            <div class="small text-muted">
                                {{ $edu->jenjang }} •
                                @if($edu->is_ongoing)
                                    <span class="text-primary fw-bold">Masih Belajar (Aktif)</span>
                                @else
                                    Lulus Tahun {{ $edu->tahun_lulus ?? '-' }}
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ================= PEKERJAAN (MULTIPLE) ================= --}}
    @if($alumni->pekerjaan->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius:15px;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0" style="color:#213448;">Riwayat Pekerjaan</h5>
                </div>
                <div class="card-body p-4">
                    @foreach($alumni->pekerjaan as $job)
                        <div class="{{ !$loop->last ? 'mb-3 pb-3 border-bottom' : '' }}">
                            <div class="fw-bold text-dark">{{ $job->nama_perusahaan }}</div>
                            <div class="small text-muted">{{ $job->jabatan }}</div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ================= HARAPAN ================= --}}
    @if($alumni->harapan)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm" style="border-radius:15px;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0" style="color:#213448;">Harapan</h5>
                </div>
                <div class="card-body p-4" style="line-height:1.7;">
                    {{ $alumni->harapan }}
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

<style>
label {
    text-transform: uppercase;
    letter-spacing: .5px;
}
.bg-success { background-color: #198754 !important; }
</style>
@endsection
