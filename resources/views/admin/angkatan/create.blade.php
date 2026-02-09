@extends('layouts.admin')

@section('title', 'Tambah Angkatan')
@section('page-title', 'Tambah Angkatan Baru')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-plus-circle me-2 text-primary"></i>Form Tambah Angkatan
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.angkatan.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="nama_angkatan" class="form-label fw-bold">
                            Nama Angkatan <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control bg-light @error('nama_angkatan') is-invalid @enderror"
                               id="nama_angkatan"
                               name="nama_angkatan"
                               value="{{ old('nama_angkatan', 'Angkatan ' . $nextNumber) }}"
                               placeholder="Angkatan {{ $nextNumber }}"
                               readonly
                               required>
                        @error('nama_angkatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text mt-2">
                            Nama angkatan dihasilkan otomatis oleh sistem: <strong>Angkatan {{ $nextNumber }}</strong>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="tahun_ajaran" class="form-label fw-bold">
                            Tahun Ajaran <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('tahun_ajaran') is-invalid @enderror"
                               id="tahun_ajaran"
                               name="tahun_ajaran"
                               value="{{ old('tahun_ajaran') }}"
                               placeholder="Contoh: 2026-2027"
                               required>
                        @error('tahun_ajaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text mt-2">
                            Format yang disarankan: <strong>YYYY-YYYY</strong> (contoh: 2026-2027)
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="status" class="form-label fw-bold">
                            Status <span class="text-danger">*</span>
                        </label>
                        <select class="form-select @error('status') is-invalid @enderror"
                                id="status"
                                name="status"
                                required>
                            <option value="">-- Pilih Status --</option>
                            <option value="AKTIF" {{ old('status') == 'AKTIF' ? 'selected' : '' }}>
                                AKTIF
                            </option>
                            <option value="LULUS" {{ old('status') == 'LULUS' ? 'selected' : '' }}>
                                LULUS
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text mt-2">
                            Pilih <strong>AKTIF</strong> untuk angkatan yang masih sekolah, atau <strong>LULUS</strong> untuk yang sudah tamat.
                        </div>
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.angkatan.index') }}" class="btn btn-light px-4 border">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-save me-1"></i> Simpan Angkatan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="alert alert-info border-0 shadow-sm bg-white p-4 text-dark">
            <h6 class="fw-bold text-info"><i class="bi bi-info-circle-fill me-2"></i>Panduan Pengisian</h6>
            <hr class="my-3">
            <ul class="mb-0 small text-muted lh-lg">
                <li>Sistem menetapkan <strong>Nama Angkatan</strong> secara berurutan untuk menjaga konsistensi data.</li>
                <li>Pastikan <strong>Tahun Ajaran</strong> diinput dengan benar untuk keperluan filter laporan nantinya.</li>
                <li>Hanya angkatan dengan status <strong>LULUS</strong> yang dapat mengakses fitur Tracer Study secara penuh.</li>
                <li>Status angkatan dapat diperbarui kapan saja melalui menu edit setelah siswa dinyatakan lulus.</li>
            </ul>
        </div>
    </div>
</div>
@endsection
