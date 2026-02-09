{{-- 1. Modal Detail Profil Alumni --}}
<div class="modal fade" id="modalDetail{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom-0 py-3">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-person-badge me-2 text-primary"></i>Detail Profil Alumni
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Nama Lengkap</small>
                        <p class="fw-bold h6 text-dark mb-0">{{ $item->nama_lengkap }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">NISN</small>
                        <p class="fw-bold h6 text-primary mb-0">{{ $item->nisn }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Angkatan</small>
                        <p class="fw-bold h6 mb-0">{{ $item->angkatan->nama_angkatan ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Tahun Lulus</small>
                        <p class="fw-bold h6 mb-0">{{ $item->tahun_lulus ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Status Verifikasi</small>
                        <div>
                            @if($item->status_verifikasi == 'verified')
                                <span class="badge bg-success">
                                    <i class="bi bi-check-circle-fill me-1"></i>Terverifikasi
                                </span>
                            @elseif($item->status_verifikasi == 'pending')
                                <span class="badge bg-warning text-dark">
                                    <i class="bi bi-hourglass-split me-1"></i>Menunggu
                                </span>
                            @else
                                <span class="badge bg-danger">
                                    <i class="bi bi-x-circle-fill me-1"></i>Ditolak
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <small class="text-muted d-block mb-1 text-uppercase fw-bold" style="font-size: 0.75rem;">Kelengkapan Profil</small>
                        <p class="fw-bold h6 mb-0 {{ $item->is_profile_complete ? 'text-success' : 'text-muted' }}">
                            <i class="bi {{ $item->is_profile_complete ? 'bi-check-circle-fill' : 'bi-dash-circle' }} me-1"></i>
                            {{ $item->is_profile_complete ? 'Lengkap' : 'Belum Lengkap' }}
                        </p>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-top-0 py-3">
                <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Tutup</button>
                @if($item->status_verifikasi == 'pending')
                    <a href="{{ route('admin.alumni.show', $item) }}" class="btn btn-primary px-4 fw-bold shadow-sm">
                        <i class="bi bi-eye me-1"></i>Lihat Detail & Verifikasi
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- 2. Modal Reset Password --}}
<div class="modal fade" id="modalReset{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom-0 py-3">
                <h5 class="modal-title fw-bold">
                    <i class="bi bi-key me-2 text-warning"></i>Reset Password
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-muted mb-3">Reset password <strong>{{ $item->nama_lengkap }}</strong>?</p>

                <div class="alert alert-light border-2 border-warning shadow-sm small mb-4">
                    <i class="bi bi-info-circle me-1 text-warning"></i>
                    <strong>Password akan menjadi:</strong> <code class="text-primary fw-bold">{{ $item->nisn }}</code>
                </div>

                <form action="{{ route('admin.alumni.resetPassword', $item) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" value="reset">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-warning fw-bold text-white shadow-sm py-2" onclick="return confirm('Yakin reset password?')">
                            <i class="bi bi-check-circle me-1"></i>Ya, Reset Password ke NISN
                        </button>
                        <button type="button" class="btn btn-light fw-bold text-muted" data-bs-dismiss="modal">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- 3. Modal Delete Permanen --}}
<div class="modal fade" id="modalDelete{{ $item->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-danger bg-opacity-10 border-bottom-0 py-3">
                <h5 class="modal-title fw-bold text-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Hapus Data Alumni
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <p class="text-dark mb-3">Anda yakin ingin menghapus data <strong>{{ $item->nama_lengkap }}</strong> secara permanen?</p>

                <div class="alert alert-danger alert-opacity-25 border-2 border-danger shadow-sm small mb-4">
                    <i class="bi bi-exclamation-circle me-1"></i>
                    <strong>Peringatan:</strong> Tindakan ini TIDAK DAPAT DIBATALKAN. Semua data terkait (riwayat pendidikan, pekerjaan, foto) akan dihapus permanen.
                </div>

                <form action="{{ route('admin.alumni.destroy', $item) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-danger fw-bold shadow-sm py-2" onclick="return confirm('Data akan dihapus permanen. Anda YAKIN?')">
                            <i class="bi bi-trash-fill me-1"></i>Ya, Hapus Permanen
                        </button>
                        <button type="button" class="btn btn-light fw-bold text-muted" data-bs-dismiss="modal">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
