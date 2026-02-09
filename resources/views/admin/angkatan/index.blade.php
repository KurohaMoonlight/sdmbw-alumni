@extends('layouts.admin')

@section('title', 'Kelola Angkatan')
@section('page-title', 'Kelola Angkatan')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
        <h6 class="mb-0 fw-bold text-dark">
            <i class="bi bi-calendar-event me-2 text-primary"></i>Daftar Angkatan
        </h6>
        <a href="{{ route('admin.angkatan.create') }}" class="btn btn-primary btn-sm px-3 shadow-sm rounded-pill">
            <i class="bi bi-plus-circle me-1"></i> Tambah Angkatan
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-muted small uppercase">
                    <tr>
                        <th width="5%" class="ps-3">No</th>
                        <th>Nama Angkatan</th>
                        <th>Tahun Ajaran</th>
                        <th>Status</th>
                        <th class="text-center">Jumlah Alumni</th>
                        <th width="15%" class="text-center pe-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($angkatans as $index => $angkatan)
                    <tr>
                        <td class="ps-3">{{ $index + 1 }}</td>
                        <td>
                            <span class="fw-bold text-dark">{{ $angkatan->nama_angkatan }}</span>
                        </td>
                        <td>{{ $angkatan->tahun_ajaran }}</td>
                        <td>
                            @if($angkatan->status == 'LULUS')
                                <span class="badge rounded-pill bg-light-success text-success border border-success px-3">LULUS</span>
                            @elseif($angkatan->status == 'AKTIF')
                                <span class="badge rounded-pill bg-light-warning text-warning border border-warning px-3">AKTIF</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3">{{ $angkatan->alumni_count }} Alumni</span>
                        </td>
                        <td class="text-center pe-3">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.angkatan.edit', $angkatan) }}"
                                   class="btn btn-warning text-white shadow-sm"
                                   title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </a>

                                @if($angkatan->alumni_count == 0)
                                    <form action="{{ route('admin.angkatan.destroy', $angkatan) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Yakin ingin menghapus angkatan ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger shadow-sm" title="Hapus">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-secondary opacity-50 shadow-sm" disabled title="Tidak bisa dihapus (ada alumni)">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <div class="py-4">
                                <i class="bi bi-inbox display-1 d-block mb-3 opacity-25 text-muted"></i>
                                <p class="text-muted fw-500">Belum ada data angkatan</p>
                                <a href="{{ route('admin.angkatan.create') }}" class="btn btn-sm btn-outline-primary mt-2">Buat Angkatan Pertama</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    <div class="alert alert-info border-0 shadow-sm bg-white py-3">
        <div class="d-flex">
            <div class="me-3">
                <i class="bi bi-info-circle-fill text-info fs-4"></i>
            </div>
            <div>
                <strong class="text-dark">Informasi Pengelolaan:</strong>
                <ul class="mb-0 mt-2 small text-muted">
                    <li>Angkatan dengan status <strong>LULUS</strong> diizinkan untuk melakukan registrasi akun alumni.</li>
                    <li>Angkatan dengan status <strong>AKTIF</strong> tetap tercatat di sistem namun akses login alumni mungkin dibatasi.</li>
                    <li>Sistem melarang penghapusan Angkatan yang sudah memiliki data alumni untuk menjaga <strong>Integritas Data</strong>.</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-light-success { background-color: rgba(25, 135, 84, 0.1); }
    .bg-light-warning { background-color: rgba(255, 193, 7, 0.1); }
</style>
@endsection
