@extends('layouts.admin')

@section('title', 'Import Data Alumni')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 pb-0 px-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-file-earmark-excel-fill text-success me-2"></i>Import Data Alumni
                        </h5>
                        <a href="{{ route('admin.alumni.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                    </div>
                </div>
                
                <div class="card-body p-4">
                    <div class="alert alert-info border-0 rounded-4 mb-4" style="background: rgba(8, 145, 178, 0.08); border-left: 4px solid #0891b2 !important;">
                        <h6 class="fw-bold mb-3 text-info-emphasis"><i class="bi bi-info-circle-fill me-2"></i>Panduan Import Data Alumni</h6>
                        <ul class="mb-0 small ps-3 text-secondary" style="line-height: 1.6;">
                            <li>Gunakan format file <strong>.xlsx</strong> (Excel) untuk hasil terbaik.</li>
                            <li>Sangat disarankan menggunakan <strong>Template Resmi</strong> yang telah disediakan di bawah.</li>
                            <li>Sistem akan otomatis mendeteksi baris data Anda (biasanya dimulai setelah judul kolom).</li>
                            <li>Kolom wajib: <strong>Nama Lengkap, NISN (10 digit), Angkatan,</strong> dan <strong>Tahun Lulus</strong>.</li>
                            <li>Sistem akan <strong>otomatis membuatkan akun login</strong> untuk setiap alumni.</li>
                            <li>Username & Password default adalah <strong>NISN</strong> masing-masing alumni.</li>
                        </ul>
                        <div class="mt-4">
                            <a href="{{ route('admin.alumni.downloadTemplate') }}" class="btn btn-info text-white fw-bold px-4 rounded-pill shadow-sm">
                                <i class="bi bi-file-earmark-spreadsheet-fill me-2"></i> Download Template Excel (.xlsx)
                            </a>
                        </div>
                    </div>

                    <form id="importForm" action="{{ route('admin.alumni.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label for="file" class="form-label fw-bold">Pilih File Excel <span class="text-danger">*</span></label>
                            <input class="form-control form-control-lg @error('file') is-invalid @enderror" type="file" id="file" name="file" accept=".xlsx, .xls, .csv" required>
                            @error('file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div id="submitContainer" class="d-grid gap-2">
                            <button type="submit" id="importBtn" class="btn btn-primary btn-lg rounded-pill fw-bold">
                                <i class="bi bi-cloud-upload me-2"></i> Proses Import
                            </button>
                        </div>

                        {{-- Progress Bar (Hidden by Default) --}}
                        <div id="importProgress" class="mt-2 d-none">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-primary fw-bold small">
                                    <i class="bi bi-gear-fill spinning me-2"></i>Sedang Mengimpor...
                                </span>
                                <span id="progressPercentage" class="badge bg-primary rounded-pill">0%</span>
                            </div>
                            <div class="progress rounded-pill shadow-sm" style="height: 12px; background: rgba(27, 58, 82, 0.05);">
                                <div id="importProgressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" 
                                     role="progressbar" style="width: 0%; transition: width 0.4s ease;"></div>
                            </div>
                            <div class="mt-3 p-3 bg-light rounded-4 border-0 shadow-sm small">
                                <ul class="mb-0 list-unstyled" style="font-size: 0.8rem;">
                                    <li class="mb-2 text-success"><i class="bi bi-check-circle-fill me-2"></i>Validasi format file...</li>
                                    <li id="step-processing" class="mb-2 text-secondary"><i class="bi bi-circle me-2"></i>Memproses data alumni...</li>
                                    <li id="step-finalizing" class="text-secondary"><i class="bi bi-circle me-2"></i>Menyimpan ke sistem...</li>
                                </ul>
                            </div>
                            <p class="text-muted small mt-3 mb-0 text-center">
                                <i class="bi bi-exclamation-triangle-fill text-warning me-1"></i> Jangan tutup halaman ini hingga selesai.
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const importForm = document.getElementById('importForm');
    const importBtn = document.getElementById('importBtn');
    const submitContainer = document.getElementById('submitContainer');
    const progressDiv = document.getElementById('importProgress');
    const progressBar = document.getElementById('importProgressBar');
    const progressText = document.getElementById('progressPercentage');
    const stepProcessing = document.getElementById('step-processing');
    const stepFinalizing = document.getElementById('step-finalizing');

    importForm.addEventListener('submit', function() {
        // Sembunyikan tombol utama
        submitContainer.classList.add('d-none');
        // Tampilkan area progress
        progressDiv.classList.remove('d-none');

        // Simulasi progress bar
        let width = 0;
        const interval = setInterval(function() {
            if (width < 35) {
                // Tahap 1: Validasi (Cepat)
                width += Math.random() * 8;
            } else if (width < 85) {
                // Tahap 2: Processing (Sedang)
                width += Math.random() * 3;
                stepProcessing.innerHTML = '<i class="bi bi-arrow-repeat spinning me-2 text-primary"></i>Memproses data alumni (bisa memakan waktu)...';
                stepProcessing.classList.remove('text-secondary');
                stepProcessing.classList.add('text-primary', 'fw-bold');
            } else if (width < 98) {
                // Tahap 3: Finalizing (Lambat)
                width += 0.5;
                stepProcessing.innerHTML = '<i class="bi bi-check-circle-fill me-2 text-success"></i>Data alumni berhasil diproses.';
                stepProcessing.classList.remove('text-primary', 'fw-bold');
                stepProcessing.classList.add('text-success');

                stepFinalizing.innerHTML = '<i class="bi bi-arrow-repeat spinning me-2 text-primary"></i>Menyimpan ke sistem...';
                stepFinalizing.classList.remove('text-secondary');
                stepFinalizing.classList.add('text-primary', 'fw-bold');
            }

            // Batasi di 99% sampai page reload
            if (width >= 99) {
                width = 99;
                clearInterval(interval);
            }

            progressBar.style.width = width + '%';
            progressText.innerText = Math.floor(width) + '%';
        }, 600);
    });
});
</script>
@endpush