@extends('layouts.admin')

@section('title', 'Activity Logs')
@section('page-title', 'Activity Logs')

@section('content')
<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form action="{{ route('admin.logs.index') }}" method="GET">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Action</label>
                    <select name="action" class="form-select form-select-sm">
                        <option value="">Semua Action</option>
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                {{ ucfirst(str_replace('_', ' ', $action)) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Tanggal</label>
                    <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
                </div>
                <div class="col-md-5">
                    <label class="form-label small fw-bold">Search</label>
                    <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari deskripsi..." value="{{ request('search') }}">
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button type="submit" class="btn btn-sm btn-primary w-100 shadow-sm">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h6 class="mb-0 fw-bold">
            <i class="bi bi-clock-history me-1"></i> Activity Logs ({{ number_format($logs->total()) }})
        </h6>
        @if($logs->total() > 0)
            <button type="button" class="btn btn-danger btn-sm px-3" data-bs-toggle="modal" data-bs-target="#clearAllModal">
                <i class="bi bi-trash me-1"></i> Hapus Semua Log
            </button>
        @endif
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                <thead class="bg-light text-muted uppercase">
                    <tr>
                        <th class="ps-3" width="5%">No</th>
                        <th width="15%">Waktu</th>
                        <th width="15%">Admin</th>
                        <th width="15%">Action</th>
                        <th>Deskripsi</th>
                        <th class="text-center" width="8%">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $index => $log)
                    <tr>
                        <td class="ps-3 text-muted">{{ $logs->firstItem() + $index }}</td>
                        <td>
                            <div class="fw-bold text-dark">{{ $log->created_at->format('d M Y') }}</div>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ $log->created_at->format('H:i:s') }}</small>
                        </td>
                        <td>
                            <span class="text-primary fw-semibold">
                                <i class="bi bi-person-circle me-1"></i>{{ $log->admin->username ?? 'System' }}
                            </span>
                        </td>
                        <td>
                            @php
                                $badgeClass = match($log->action) {
                                    'create_angkatan' => 'bg-success',
                                    'update_angkatan' => 'bg-info',
                                    'delete_angkatan' => 'bg-danger',
                                    'verify_alumni'   => 'bg-success',
                                    'reset_password'  => 'bg-warning text-dark',
                                    'delete_alumni'   => 'bg-danger',
                                    default           => 'bg-secondary'
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} py-2 px-3 w-100" style="font-size: 10px; letter-spacing: 0.5px;">
                                {{ strtoupper(str_replace('_', ' ', $log->action)) }}
                            </span>
                        </td>
                        <td>
                            <div class="text-wrap" style="max-width: 400px;">
                                {{ $log->description }}
                                @if($log->target_type && $log->target_id)
                                    <div class="mt-1">
                                        <span class="badge bg-light text-muted border" style="font-size: 10px;">
                                            Target: {{ class_basename($log->target_type) }} #{{ $log->target_id }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </td>
                        <td class="text-center">
                            <form action="{{ route('admin.logs.destroy', $log) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus log ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger border-0" title="Hapus">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-inbox text-muted display-4"></i>
                            <p class="mt-2 text-muted fw-semibold">Belum ada activity log yang ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($logs->hasPages())
    <div class="card-footer bg-white py-3">
        {{ $logs->links() }}
    </div>
    @endif
</div>

<div class="modal fade" id="clearAllModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 bg-danger text-white">
                <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle me-2"></i>Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.logs.clearAll') }}" method="POST">
                @csrf
                @method('DELETE')
                <div class="modal-body text-center py-4">
                    <p class="mb-0">Yakin ingin menghapus seluruh riwayat aktivitas sebanyak <strong>{{ $logs->total() }} data</strong>?</p>
                    <small class="text-danger">Tindakan ini tidak dapat dibatalkan.</small>
                </div>
                <div class="modal-footer border-0 justify-content-center">
                    <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger px-4 shadow-sm">Ya, Hapus Permanen</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="alert alert-info border-0 shadow-sm mt-3 d-flex align-items-center">
    <i class="bi bi-info-circle-fill fs-4 me-3"></i>
    <div>
        <strong>Informasi Keamanan:</strong> Activity logs mencatat setiap perubahan data krusial untuk keperluan audit sistem (Tambah, Verifikasi, Hapus, dan Reset).
    </div>
</div>
@endsection
