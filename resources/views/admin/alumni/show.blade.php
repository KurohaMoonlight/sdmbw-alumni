@extends('layouts.admin')

@section('title', 'Detail Alumni')
@section('page-title', 'Detail Alumni')

@section('content')
<div class="row">
    <div class="col-md-8">
        <!-- Data Pribadi -->
        <div class="card mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="bi bi-person-badge"></i> Data Pribadi
                </h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">NISN</div>
                    <div class="col-md-8"><strong>{{ $alumni->nisn }}</strong></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Nama Lengkap</div>
                    <div class="col-md-8"><strong>{{ $alumni->nama_lengkap }}</strong></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Angkatan</div>
                    <div class="col-md-8">
                        <span class="badge bg-primary">
                            {{ $alumni->angkatan->nama_angkatan ?? '-' }}
                        </span>
                        <small class="text-muted">({{ $alumni->angkatan->tahun_ajaran ?? '-' }})</small>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Tahun Lulus</div>
                    <div class="col-md-8"><strong>{{ $alumni->tahun_lulus }}</strong></div>
                </div>
                <hr>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Alamat</div>
                    <div class="col-md-8">{{ $alumni->alamat ?? '-' }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">No. HP / WhatsApp</div>
                    <div class="col-md-8">
                        {{ $alumni->no_hp ?? '-' }}
                        @if($alumni->no_hp && $alumni->show_no_hp)
                            <span class="badge bg-success ms-2">
                                <i class="bi bi-eye"></i> Public
                            </span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Email</div>
                    <div class="col-md-8">{{ $alumni->email ?? ($alumni->user->email ?? '-') }}</div>
                </div>
                <div class="row">
                    <div class="col-md-4 text-muted">Harapan & Pesan</div>
                    <div class="col-md-8">{{ $alumni->harapan ?? '-' }}</div>
                </div>
            </div>
        </div>

        <!-- Riwayat Pendidikan -->
        @if($alumni->pendidikan->count() > 0)
        <div class="card mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="bi bi-mortarboard-fill"></i> Riwayat Pendidikan
                </h6>
            </div>
            <div class="card-body">
                @foreach($alumni->pendidikan as $edu)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-0 fw-bold">{{ $edu->jenjang }}</h6>
                            @if($edu->is_ongoing)
                                <span class="badge bg-primary">
                                    <i class="bi bi-play-circle-fill"></i> Aktif
                                </span>
                            @else
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle-fill"></i> Lulus
                                </span>
                            @endif
                        </div>
                        <p class="mb-2">
                            <strong>{{ $edu->nama_instansi }}</strong>
                        </p>
                        @if($edu->program_studi && $edu->jenjang === 'Perguruan Tinggi')
                            <p class="mb-2">
                                <small class="text-muted">
                                    <i class="bi bi-book"></i> Program Studi: <strong>{{ $edu->program_studi }}</strong>
                                </small>
                            </p>
                        @endif
                        <p class="mb-0">
                            <small class="text-muted">
                                <i class="bi bi-calendar"></i>
                                @if($edu->is_ongoing)
                                    Tahun Masuk: {{ $edu->tahun_masuk ?? '-' }}
                                @else
                                    Tahun {{ $edu->tahun_masuk ?? '-' }} - {{ $edu->tahun_lulus ?? '-' }}
                                @endif
                            </small>
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Riwayat Pekerjaan -->
        @if($alumni->pekerjaan->count() > 0)
        <div class="card mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="bi bi-briefcase-fill"></i> Riwayat Pekerjaan
                </h6>
            </div>
            <div class="card-body">
                @foreach($alumni->pekerjaan as $job)
                    <div class="mb-3 pb-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h6 class="mb-0 fw-bold">{{ $job->nama_perusahaan }}</h6>
                            @if($job->is_current)
                                <span class="badge bg-info">
                                    <i class="bi bi-star-fill"></i> Sekarang
                                </span>
                            @endif
                        </div>
                        <p class="mb-0">
                            <small class="text-muted">
                                <i class="bi bi-person-badge"></i> Jabatan: <strong>{{ $job->jabatan }}</strong>
                            </small>
                        </p>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Info Akun -->
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="bi bi-person-circle"></i> Informasi Akun
                </h6>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Username</div>
                    <div class="col-md-8"><strong>{{ $alumni->user->username ?? '-' }}</strong></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Role</div>
                    <div class="col-md-8">
                        <span class="badge bg-info">{{ $alumni->user->role ?? '-' }}</span>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Status Akun</div>
                    <div class="col-md-8">
                        @if($alumni->user->is_active)
                            <span class="badge bg-success">
                                <i class="bi bi-check-circle"></i> Aktif
                            </span>
                        @else
                            <span class="badge bg-secondary">
                                <i class="bi bi-x-circle"></i> Non-aktif
                            </span>
                        @endif
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Terdaftar</div>
                    <div class="col-md-8">{{ $alumni->created_at->format('d M Y H:i') }}</div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4 text-muted">Update Terakhir</div>
                    <div class="col-md-8">{{ $alumni->updated_at->format('d M Y H:i') }}</div>
                </div>
                @if($alumni->user->last_login_at)
                <div class="row">
                    <div class="col-md-4 text-muted">Login Terakhir</div>
                    <div class="col-md-8">{{ $alumni->user->last_login_at->format('d M Y H:i') }}</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Foto Alumni -->
        <div class="card mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="bi bi-image"></i> Foto Profil
                </h6>
            </div>
            <div class="card-body text-center">
                @php
                    $fotoUtama = $alumni->fotos->where('is_main', true)->first();
                @endphp

                @if($fotoUtama)
                    <img src="{{ asset('storage/' . $fotoUtama->path_file) }}"
                            class="img-fluid rounded shadow-sm"
                            style="max-width: 100%; max-height: 300px; object-fit: cover;"
                            alt="Foto Profil {{ $alumni->nama_lengkap }}">
                    <p class="text-muted small mt-2">
                        <i class="bi bi-calendar"></i> {{ $fotoUtama->created_at->format('d M Y') }}
                    </p>
                @else
                    <div class="bg-light rounded p-5 text-muted">
                        <i class="bi bi-image" style="font-size: 3rem; opacity: 0.5;"></i>
                        <p class="mt-3 mb-0">Belum ada foto profil</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Status Card -->
        <div class="card mb-3">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="bi bi-info-circle"></i> Status
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Status Verifikasi</label>
                    <div>
                        @if($alumni->status_verifikasi == 'verified')
                            <span class="badge bg-success" style="font-size: 0.9rem; padding: 0.6rem 1rem;">
                                <i class="bi bi-check-circle"></i> Terverifikasi
                            </span>
                        @elseif($alumni->status_verifikasi == 'pending')
                            <span class="badge bg-warning text-dark" style="font-size: 0.9rem; padding: 0.6rem 1rem;">
                                <i class="bi bi-clock"></i> Menunggu Verifikasi
                            </span>
                        @else
                            <span class="badge bg-danger" style="font-size: 0.9rem; padding: 0.6rem 1rem;">
                                <i class="bi bi-x-circle"></i> Ditolak
                            </span>
                        @endif
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Kelengkapan Profil</label>
                    <div>
                        @if($alumni->is_profile_complete)
                            <span class="badge bg-success" style="font-size: 0.9rem; padding: 0.6rem 1rem;">
                                <i class="bi bi-check-circle"></i> Lengkap
                            </span>
                        @else
                            <span class="badge bg-secondary" style="font-size: 0.9rem; padding: 0.6rem 1rem;">
                                <i class="bi bi-exclamation-circle"></i> Belum Lengkap
                            </span>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="alert alert-info small mb-0">
                    <i class="bi bi-info-circle"></i>
                    @if($alumni->status_verifikasi == 'pending')
                        <strong>Pending:</strong> Tunggu admin verifikasi
                    @elseif($alumni->status_verifikasi == 'verified')
                        <strong>Terverifikasi:</strong> Alumni aktif dan bisa login
                    @else
                        <strong>Ditolak:</strong> Alumni tidak bisa akses akun
                    @endif
                </div>
            </div>
        </div>

        <!-- Aksi -->
        <div class="card">
            <div class="card-header bg-white">
                <h6 class="mb-0">
                    <i class="bi bi-gear"></i> Aksi
                </h6>
            </div>
            <div class="card-body">
                @if($alumni->status_verifikasi == 'pending')
                    <!-- Verifikasi -->
                    <form action="{{ route('admin.alumni.verify', $alumni) }}" method="POST" class="mb-2">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="verified">
                        <button type="submit" class="btn btn-success w-100" onclick="return confirm('Verifikasi alumni ini?')">
                            <i class="bi bi-check-circle"></i> Setujui Alumni
                        </button>
                    </form>

                    <form action="{{ route('admin.alumni.verify', $alumni) }}" method="POST" class="mb-2">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="status" value="rejected">
                        <button type="submit" class="btn btn-danger w-100" onclick="return confirm('Tolak alumni ini?')">
                            <i class="bi bi-x-circle"></i> Tolak Alumni
                        </button>
                    </form>
                @else
                    <!-- Status Badge -->
                    <div class="alert alert-secondary text-center mb-2" style="font-size: 0.9rem;">
                        <i class="bi bi-info-circle"></i>
                        @if($alumni->status_verifikasi == 'verified')
                            Sudah Diverifikasi
                        @else
                            Sudah Ditolak
                        @endif
                    </div>
                @endif

                <!-- Reset Password -->
                <form action="{{ route('admin.alumni.resetPassword', $alumni) }}" method="POST" class="mb-2">
                    @csrf
                    @method('PUT')
                    <button type="submit" class="btn btn-warning w-100" onclick="return confirm('Reset password ke NISN?')">
                        <i class="bi bi-key"></i> Reset Password
                    </button>
                </form>

                <!-- Edit -->
                <a href="{{ route('admin.alumni.edit', $alumni) }}" class="btn btn-primary w-100 mb-2">
                    <i class="bi bi-pencil-square"></i> Edit Data
                </a>

                <!-- Hapus -->
                <form action="{{ route('admin.alumni.destroy', $alumni) }}" method="POST" class="mb-2">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger w-100" onclick="return confirm('Yakin ingin menghapus alumni ini secara permanen?')">
                        <i class="bi bi-trash"></i> Hapus Alumni
                    </button>
                </form>

                <hr>

                <a href="{{ route('admin.alumni.index') }}" class="btn btn-secondary w-100">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
