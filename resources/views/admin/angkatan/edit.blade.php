@extends('layouts.admin')

@section('title', 'Edit Angkatan')
@section('page-title', 'Edit Angkatan')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-pencil me-2 text-warning"></i>Form Edit Angkatan
                </h6>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.angkatan.update', $angkatan) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="nama_angkatan" class="form-label fw-bold">
                            Nama Angkatan <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('nama_angkatan') is-invalid @enderror"
                               id="nama_angkatan"
                               name="nama_angkatan"
                               value="{{ old('nama_angkatan', $angkatan->nama_angkatan) }}"
                               placeholder="Contoh: Angkatan 11"
                               required>
                        @error('nama_angkatan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="tahun_ajaran" class="form-label fw-bold">
                            Tahun Ajaran <span class="text-danger">*</span>
                        </label>
                        <input type="text"
                               class="form-control @error('tahun_ajaran') is-invalid @enderror"
                               id="tahun_ajaran"
                               name="tahun_ajaran"
                               value="{{ old('tahun_ajaran', $angkatan->tahun_ajaran) }}"
                               placeholder="Contoh: 2026-2027"
                               required>
                        @error('tahun_ajaran')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                            <option value="BELUM_LULUS"
                                    {{ old('status', $angkatan->status) == 'BELUM_LULUS' ? 'selected' : '' }}>
                                BELUM LULUS
                            </option>
                            <option value="LULUS"
                                    {{ old('status', $angkatan->status) == 'LULUS' ? 'selected' : '' }}>
                                LULUS
                            </option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text mt-2">
                            Ubah status ke <strong>LULUS</strong> jika angkatan sudah menyelesaikan masa pendidikan.
                        </div>
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.angkatan.index') }}" class="btn btn-light px-4 border">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="bi bi-save me-1"></i> Update Angkatan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="alert alert-warning border-0 shadow-sm p-4 mb-4">
            <h6 class="fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i>Perhatian</h6>
            <hr class="my-3">
            <ul class="mb-0 small lh-lg">
                <li>Hati-hati saat mengubah status angkatan.</li>
                <li>Status <strong>BELUM LULUS</strong> akan membatasi akses login bagi alumni terkait.</li>
                <li>Status <strong>LULUS</strong> akan memberikan akses penuh ke sistem bagi akun alumni.</li>
            </ul>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">Info Angkatan</h6>
                <hr class="my-3 opacity-25">
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0 small">
                        <tr>
                            <td class="text-muted" width="40%">ID Angkatan</td>
                            <td class="fw-bold">: {{ $angkatan->id }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Dibuat</td>
                            <td class="fw-bold">: {{ $angkatan->created_at->format('d M Y H:i') }}</td>
                        </tr>
                        <tr>
                            <td class="text-muted">Update Terakhir</td>
                            <td class="fw-bold">: {{ $angkatan->updated_at->format('d M Y H:i') }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
