@extends('layouts.admin')

@section('title', 'Edit Alumni')
@section('page-title', 'Edit Data Alumni')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-4">
                <h5 class="mb-0 fw-bold text-primary">
                    <i class="bi bi-pencil-square me-2"></i> Edit Data Alumni
                </h5>
                <small class="text-muted">Update informasi lengkap alumni</small>
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm mb-4">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                            <div>
                                <strong>Terjadi Kesalahan!</strong>
                                <ul class="mb-0 mt-2 small">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('admin.alumni.update', $alumni) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Data Identitas Section -->
                    <h6 class="text-muted small fw-bold text-uppercase mb-3">
                        <i class="bi bi-person-badge me-1"></i> Data Identitas
                    </h6>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">NISN <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('nisn') is-invalid @enderror"
                                   name="nisn"
                                   value="{{ old('nisn', $alumni->nisn) }}"
                                   required>
                            @error('nisn')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nama Lengkap <span class="text-danger">*</span></label>
                            <input type="text"
                                   class="form-control @error('nama_lengkap') is-invalid @enderror"
                                   name="nama_lengkap"
                                   value="{{ old('nama_lengkap', $alumni->nama_lengkap) }}"
                                   required>
                            @error('nama_lengkap')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Angkatan <span class="text-danger">*</span></label>
                            <select class="form-select @error('angkatan_id') is-invalid @enderror"
                                    name="angkatan_id"
                                    required>
                                <option value="">-- Pilih Angkatan --</option>
                                @foreach($angkatans as $angkatan)
                                    <option value="{{ $angkatan->id }}"
                                            {{ old('angkatan_id', $alumni->angkatan_id) == $angkatan->id ? 'selected' : '' }}>
                                        {{ $angkatan->nama_angkatan }} ({{ $angkatan->tahun_ajaran }})
                                    </option>
                                @endforeach
                            </select>
                            @error('angkatan_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Tahun Lulus <span class="text-danger">*</span></label>
                            <input type="number"
                                   class="form-control @error('tahun_lulus') is-invalid @enderror"
                                   name="tahun_lulus"
                                   value="{{ old('tahun_lulus', $alumni->tahun_lulus) }}"
                                   min="1950"
                                   max="{{ date('Y') }}"
                                   required>
                            @error('tahun_lulus')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Data Kontak Section -->
                    <h6 class="text-muted small fw-bold text-uppercase mb-3">
                        <i class="bi bi-telephone me-1"></i> Data Kontak
                    </h6>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Alamat</label>
                        <textarea class="form-control @error('alamat') is-invalid @enderror"
                                  name="alamat"
                                  rows="3"
                                  placeholder="Masukkan alamat lengkap">{{ old('alamat', $alumni->alamat) }}</textarea>
                        @error('alamat')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">No. HP / WhatsApp</label>
                            <input type="text"
                                   class="form-control @error('no_hp') is-invalid @enderror"
                                   name="no_hp"
                                   value="{{ old('no_hp', $alumni->no_hp) }}"
                                   placeholder="08xxxxxxxxxx">
                            @error('no_hp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email"
                                   class="form-control @error('email') is-invalid @enderror"
                                   name="email"
                                   value="{{ old('email', $alumni->email) }}"
                                   placeholder="email@example.com">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <hr class="my-4">

                    <!-- Harapan Section -->
                    <h6 class="text-muted small fw-bold text-uppercase mb-3">
                        <i class="bi bi-chat-left-text me-1"></i> Pesan & Harapan
                    </h6>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Pesan Alumni & Harapan Untuk Sekolah</label>
                        <textarea class="form-control @error('harapan') is-invalid @enderror"
                                  name="harapan"
                                  rows="3"
                                  placeholder="Tuliskan harapan atau pesan untuk sekolah">{{ old('harapan', $alumni->harapan) }}</textarea>
                        @error('harapan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex gap-2 justify-content-between">
                        <a href="{{ route('admin.alumni.show', $alumni) }}" class="btn btn-secondary px-4">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Info Card -->
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-info-circle me-2"></i> Informasi
                </h6>
            </div>
            <div class="card-body small">
                <ul class="list-unstyled mb-0">
                    <li class="mb-3 pb-3 border-bottom">
                        <strong>Nama:</strong><br>
                        <span class="text-muted">{{ $alumni->nama_lengkap }}</span>
                    </li>
                    <li class="mb-3 pb-3 border-bottom">
                        <strong>NISN:</strong><br>
                        <code class="bg-light p-2 rounded d-inline-block">{{ $alumni->nisn }}</code>
                    </li>
                    <li class="mb-3 pb-3 border-bottom">
                        <strong>Status Verifikasi:</strong><br>
                        @if($alumni->status_verifikasi == 'verified')
                            <span class="badge bg-success">Terverifikasi</span>
                        @elseif($alumni->status_verifikasi == 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @else
                            <span class="badge bg-danger">Ditolak</span>
                        @endif
                    </li>
                    <li class="mb-0">
                        <strong>Kelengkapan Profil:</strong><br>
                        <span class="text-muted">
                            {{ $alumni->is_profile_complete ? '✓ Lengkap' : '✗ Belum Lengkap' }}
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Alert Info -->
        <div class="alert alert-info border-0 shadow-sm">
            <i class="bi bi-info-circle-fill me-2"></i>
            <strong>Catatan:</strong> Form ini hanya untuk data dasar. Data pendidikan dan pekerjaan diupdate oleh alumni melalui profil mereka.
        </div>
    </div>
</div>
@endsection
