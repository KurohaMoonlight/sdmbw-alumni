@extends('layouts.alumni')

@section('title', 'Profil Alumni')

@section('content')
<div class="mb-4">
    <a href="{{ route('alumni.direktori') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Direktori
    </a>
</div>

<div class="row">
    <div class="col-lg-4 mb-4">
        <div class="card-custom text-center">
            <div class="card-body">
                <div class="mb-3">
                    @php $fotoUtama = $alumni->fotos->where('is_main', true)->first(); @endphp
                    @if($fotoUtama)
                        <img src="{{ asset('storage/' . $fotoUtama->path_file) }}" class="rounded-circle shadow-sm" style="width:120px;height:120px;object-fit:cover;" alt="Foto Profil">
                    @else
                        <div style="width: 120px; height: 120px; margin: 0 auto; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-light) 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 12px rgba(33, 52, 72, 0.2);">
                            <i class="bi bi-person-fill" style="font-size: 4rem; color: white;"></i>
                        </div>
                    @endif
                </div>

                <h4 class="mb-2" style="color: var(--color-primary); font-weight: 700; font-family: 'Poppins', sans-serif;">
                    {{ $alumni->nama_lengkap }}
                </h4>

                <div class="mb-3">
                    <span class="badge mb-2" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); padding: 0.6rem 1.2rem; border-radius: 10px; font-size: 0.9rem;">
                        <i class="bi bi-mortarboard-fill"></i> {{ $alumni->angkatan->nama_angkatan ?? '-' }}
                    </span>
                    <br>
                    @if($alumni->status_verifikasi === 'verified')
                        <span class="badge" style="background: linear-gradient(135deg, #28a745 0%, #20c997 100%); padding: 0.5rem 1rem; border-radius: 8px; font-size: 0.85rem;">
                            <i class="bi bi-check-circle-fill"></i> Terverifikasi
                        </span>
                    @endif
                </div>

                <p class="text-muted mb-0">
                    <i class="bi bi-calendar-check"></i> Tahun Lulus: <strong>{{ $alumni->tahun_lulus }}</strong>
                </p>
            </div>
        </div>

        <div class="card-custom mt-4">
            <div class="card-header-custom">
                <i class="bi bi-info-circle"></i> Informasi Akun
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <small class="text-muted d-block mb-1"><i class="bi bi-calendar-plus"></i> Terdaftar:</small>
                    <strong>{{ $alumni->created_at->format('d M Y') }}</strong>
                </div>
                <div>
                    <small class="text-muted d-block mb-1"><i class="bi bi-clock-history"></i> Update Terakhir:</small>
                    <strong>{{ $alumni->updated_at->format('d M Y H:i') }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-8">
        <div class="card-custom mb-4">
            <div class="card-header-custom">
                <i class="bi bi-person-vcard"></i> Data Pribadi
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6"><div class="info-item"><label class="info-label">NISN</label><p class="info-value">{{ $alumni->nisn }}</p></div></div>
                    <div class="col-md-6"><div class="info-item"><label class="info-label">Nama Lengkap</label><p class="info-value">{{ $alumni->nama_lengkap }}</p></div></div>
                    <div class="col-md-6"><div class="info-item"><label class="info-label">Angkatan</label><p class="info-value"><span class="badge bg-primary">{{ $alumni->angkatan->nama_angkatan ?? '-' }}</span> ({{ $alumni->angkatan->tahun_ajaran ?? '-' }})</p></div></div>
                    <div class="col-md-6"><div class="info-item"><label class="info-label">Tahun Lulus</label><p class="info-value">{{ $alumni->tahun_lulus }}</p></div></div>
                    <div class="col-md-12"><div class="info-item"><label class="info-label">Harapan Untuk Sekolah</label><p class="info-value"><em>{{ $alumni->harapan ?? '-' }}</em></p></div></div>
                </div>
            </div>
        </div>

        <div class="card-custom mb-4">
            <div class="card-header-custom">
                <i class="bi bi-telephone"></i> Informasi Kontak
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12"><div class="info-item"><label class="info-label">Alamat</label><p class="info-value">{{ $alumni->alamat ?? '-' }}</p></div></div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <label class="info-label">No. HP</label>
                            <p class="info-value">
                                @if($alumni->no_hp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $alumni->no_hp) }}" class="text-decoration-none" target="_blank">
                                        <i class="bi bi-whatsapp text-success"></i> {{ $alumni->no_hp }}
                                    </a>
                                @else - @endif
                            </p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="info-item">
                            <label class="info-label">Email</label>
                            <p class="info-value">
                                @if($alumni->email)
                                    <a href="mailto:{{ $alumni->email }}" class="text-decoration-none"><i class="bi bi-envelope"></i> {{ $alumni->email }}</a>
                                @else <span class="text-muted fst-italic">Tidak tersedia</span> @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-custom">
            <div class="card-header-custom">
                <i class="bi bi-mortarboard"></i> Riwayat Pendidikan & Karir
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="info-label">Pendidikan Lanjutan</label>
                        @forelse($alumni->pendidikan as $edu)
                            <div class="info-item mb-2">
                                <strong>{{ $edu->jenjang }}:</strong> {{ $edu->nama_instansi }}
                                @if($edu->is_ongoing)
                                    <span class="badge bg-info text-dark ms-2">Aktif</span>
                                @else
                                    <span class="badge bg-light text-success border ms-2">Lulus ({{ $edu->tahun_lulus }})</span>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted small">Belum ada data pendidikan lanjutan.</p>
                        @endforelse
                    </div>
                    <div class="col-md-12 mt-3">
                        <label class="info-label">Pekerjaan Saat Ini</label>
                        @forelse($alumni->pekerjaan as $job)
                            <div class="info-item mb-2">
                                <i class="bi bi-briefcase text-success"></i> <strong>{{ $job->nama_perusahaan }}</strong> - {{ $job->jabatan }}
                            </div>
                        @empty
                            <p class="text-muted small">Belum ada data pekerjaan.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .info-item { background: rgba(33, 52, 72, 0.02); padding: 1rem; border-radius: 10px; border-left: 3px solid var(--color-primary); transition: all 0.3s ease; }
    .info-item:hover { background: rgba(33, 52, 72, 0.05); transform: translateX(5px); }
    .info-label { display: block; font-weight: 600; color: var(--color-primary); font-size: 0.85rem; margin-bottom: 0.5rem; text-transform: uppercase; letter-spacing: 0.5px; }
    .info-value { margin: 0; color: #333; font-size: 1rem; font-weight: 500; }
    .btn-outline-secondary { border: 2px solid #6c757d; color: #6c757d; padding: 0.6rem 1.5rem; border-radius: 10px; font-weight: 600; transition: all 0.3s ease; }
    .btn-outline-secondary:hover { background: #6c757d; color: white; transform: translateY(-2px); }
</style>
@endsection
