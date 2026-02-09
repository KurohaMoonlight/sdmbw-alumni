@extends('layouts.admin')

@section('title', 'Kelola Alumni')
@section('page-title', 'Kelola Alumni')

@section('content')
<div class="card mb-4 border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="{{ route('admin.alumni.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Status Verifikasi</label>
                    <select name="status" class="form-select border-secondary-subtle">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verified (Aktif)</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Angkatan</label>
                    <select name="angkatan_id" class="form-select border-secondary-subtle">
                        <option value="">Semua Angkatan</option>
                        @foreach($angkatans as $angkatan)
                            <option value="{{ $angkatan->id }}" {{ request('angkatan_id') == $angkatan->id ? 'selected' : '' }}>
                                {{ $angkatan->nama_angkatan }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold text-muted text-uppercase">Profil</label>
                    <select name="complete" class="form-select border-secondary-subtle">
                        <option value="">Semua</option>
                        <option value="1" {{ request('complete') == '1' ? 'selected' : '' }}>Lengkap</option>
                        <option value="0" {{ request('complete') == '0' ? 'selected' : '' }}>Belum Lengkap</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold text-muted text-uppercase">Search</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-secondary-subtle"><i class="bi bi-person-search"></i></span>
                        <input type="text" name="search" class="form-control border-secondary-subtle" placeholder="NISN atau Nama..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100 shadow-sm" title="Cari Data">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 border-bottom-0">
        <div class="d-flex justify-content-between align-items-center">
            <h6 class="mb-0 fw-bold text-dark">
                <i class="bi bi-people-fill text-primary me-2"></i> Daftar Alumni
                <span class="badge bg-primary-subtle text-primary ms-2 fw-normal">{{ number_format($alumnis->total()) }} Total</span>
            </h6>
            @if(request()->anyFilled(['status', 'angkatan_id', 'complete', 'search']))
                <a href="{{ route('admin.alumni.index') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-x-circle me-1"></i> Bersihkan Filter
                </a>
            @endif
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase">
                    <tr>
                        <th class="ps-4" width="5%">No</th>
                        <th width="8%">Foto</th>
                        <th width="12%">NISN</th>
                        <th>Nama Lengkap</th>
                        <th>Angkatan</th>
                        <th>Status Akun</th>
                        <th>Profil</th>
                        <th width="15%" class="text-center pe-4">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($alumnis as $index => $alumni)
                    <tr>
                        <td class="ps-4 text-muted small">{{ $alumnis->firstItem() + $index }}</td>
                        <td>
                            @php
                                $fotoUtama = $alumni->fotos->where('is_main', true)->first();
                            @endphp
                            @if($fotoUtama)
                                <img src="{{ asset('storage/' . $fotoUtama->path_file) }}"
                                     class="rounded-circle shadow-sm"
                                     style="width: 45px; height: 45px; object-fit: cover; border: 2px solid #f0f0f0;"
                                     alt="Foto {{ $alumni->nama_lengkap }}"
                                     title="{{ $alumni->nama_lengkap }}">
                            @else
                                <div class="rounded-circle bg-light d-flex align-items-center justify-content-center text-muted"
                                     style="width: 45px; height: 45px; border: 2px solid #f0f0f0;">
                                    <i class="bi bi-person" style="font-size: 1.2rem;"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <code class="fw-bold px-2 py-1 bg-light rounded" style="color: #d63384; font-size: 0.85rem;">
                                {{ $alumni->nisn }}
                            </code>
                        </td>
                        <td>
                            <div class="fw-bold text-dark text-truncate" style="max-width: 250px;">{{ $alumni->nama_lengkap }}</div>
                            <small class="text-muted">
                                <i class="bi bi-person-badge me-1"></i>{{ $alumni->user->username ?? '-' }}
                            </small>
                        </td>
                        <td>
                            <span class="badge rounded-pill bg-white text-primary border border-primary-subtle px-3 py-2">
                                {{ $alumni->angkatan->nama_angkatan ?? '-' }}
                            </span>
                        </td>
                        <td>
                            @if($alumni->status_verifikasi == 'verified')
                                <span class="badge bg-success-subtle text-success border border-success px-2 py-1">
                                    <i class="bi bi-patch-check-fill me-1"></i> Aktif
                                </span>
                            @elseif($alumni->status_verifikasi == 'pending')
                                <span class="badge bg-warning-subtle text-warning-emphasis border border-warning px-2 py-1">
                                    <i class="bi bi-hourglass-split me-1"></i> Menunggu
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger px-2 py-1">
                                    <i class="bi bi-x-octagon-fill me-1"></i> Ditolak
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($alumni->is_profile_complete)
                                <div class="text-success small fw-bold">
                                    <i class="bi bi-check-circle-fill me-1"></i>Lengkap
                                </div>
                            @else
                                <div class="text-muted small">
                                    <i class="bi bi-circle me-1 opacity-50"></i>Belum
                                </div>
                            @endif
                        </td>
                        <td class="text-center pe-4">
                            <div class="btn-group btn-group-sm shadow-sm">
                                <a href="{{ route('admin.alumni.show', $alumni) }}"
                                   class="btn btn-outline-info"
                                   title="Lihat Detail">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button type="button" class="btn btn-outline-warning"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalReset{{ $alumni->id }}"
                                        title="Reset Password">
                                    <i class="bi bi-shield-lock"></i>
                                </button>
                                <button type="button" class="btn btn-outline-danger"
                                        data-bs-toggle="modal"
                                        data-bs-target="#modalDelete{{ $alumni->id }}"
                                        title="Hapus Permanen">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @include('admin.alumni.partials.modals', ['item' => $alumni])
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <div class="text-muted">
                                <i class="bi bi-folder2-open display-4 opacity-25"></i>
                                <p class="mt-2 fw-semibold">Data alumni tidak ditemukan dalam kriteria ini.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white border-top-0 py-3">
        <div class="row align-items-center">
            <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                <small class="text-muted">
                    Menampilkan <strong>{{ $alumnis->firstItem() ?? 0 }}</strong> sampai <strong>{{ $alumnis->lastItem() ?? 0 }}</strong> dari <strong>{{ $alumnis->total() }}</strong> alumni
                </small>
            </div>
            <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
                {{ $alumnis->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
