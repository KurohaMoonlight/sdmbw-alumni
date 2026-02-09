    @extends('layouts.alumni')

    @section('title', 'Edit Profil')

    @section('content')
    <div class="row">
        <div class="col-md-8">
            <div class="card card-custom border-0 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h6 class="mb-0 fw-bold text-primary">
                        <i class="bi bi-pencil-square me-2"></i> Edit Profil Alumni
                    </h6>
                </div>
                <div class="card-body p-4">
                    {{-- ERROR MESSAGES --}}
                    @if($errors->any())
                        <div class="alert alert-danger border-0 shadow-sm">
                            <div class="d-flex">
                                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                                <strong>Terjadi kesalahan:</strong>
                            </div>
                            <ul class="mb-0 mt-2 small">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('alumni.profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- DATA IDENTITAS (READ-ONLY) --}}
                        <h6 class="text-muted small fw-bold text-uppercase mb-3">Data Identitas (Read-Only)</h6>
                        <div class="row mb-3">
                            <div class="col-md-6 mb-2">
                                <label class="form-label small fw-bold text-secondary">NISN</label>
                                <input type="text" class="form-control bg-light" value="{{ $alumni->nisn }}" disabled>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label small fw-bold text-secondary">Nama Lengkap</label>
                                <input type="text" class="form-control bg-light" value="{{ $alumni->nama_lengkap }}" disabled>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 mb-2">
                                <label class="form-label small fw-bold text-secondary">Angkatan</label>
                                <input type="text" class="form-control bg-light"
                                        value="{{ $alumni->angkatan->nama_angkatan ?? '-' }}" disabled>
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label small fw-bold text-secondary">Tahun Lulus Sekolah</label>
                                <input type="text" class="form-control bg-light" value="{{ $alumni->tahun_lulus }}" disabled>
                            </div>
                        </div>

                        <hr class="my-4 opacity-50">

                        {{-- DATA KONTAK & DASAR --}}
                        <h6 class="text-muted small fw-bold text-uppercase mb-3">Data Kontak & Dasar</h6>

                        {{-- Foto Profil --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Foto Profil</label>
                            <input type="file"
                                    class="form-control @error('foto') is-invalid @enderror"
                                    name="foto"
                                    accept="image/*"
                                    onchange="previewImage(event)">
                            @error('foto')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text mt-1" style="font-size: 0.75rem;">
                                Format: JPG, PNG, WEBP • Maksimal Ukuran: 2MB
                            </div>
                        </div>

                        {{-- Alamat Saat Ini --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold">
                                Alamat Saat Ini <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('alamat') is-invalid @enderror"
                                        name="alamat"
                                        rows="3"
                                        required
                                        placeholder="Masukkan alamat lengkap domisili sekarang">{{ old('alamat', $alumni->alamat) }}</textarea>
                            @error('alamat')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Nomor HP / WhatsApp (1 Field Saja) --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold">
                                Nomor HP / WhatsApp <span class="text-danger">*</span>
                            </label>
                            <div class="input-group input-group-sm mb-2">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-phone"></i>
                                </span>
                                <input type="tel"
                                        class="form-control @error('no_hp') is-invalid @enderror border-start-0"
                                        name="no_hp"
                                        value="{{ old('no_hp', $alumni->no_hp) }}"
                                        required
                                        oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                                        maxlength="14"
                                        placeholder="08xxxxxxxxxx (HP atau WA)">
                            </div>
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" name="show_no_hp" id="show_no_hp" value="1" {{ old('show_no_hp', $alumni->show_no_hp) ? 'checked' : '' }}>
                                <label class="form-check-label small fw-bold text-muted" for="show_no_hp">
                                    Tampilkan nomor di profil alumni
                                </label>
                            </div>
                            @error('no_hp')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Email Aktif --}}
                        <div class="mb-3">
                            <label class="form-label small fw-bold">Email Aktif</label>
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-light border-end-0">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input type="email"
                                        class="form-control border-start-0"
                                        name="email"
                                        value="{{ old('email', $alumni->user->email ?? $alumni->email) }}"
                                        placeholder="nama@email.com">
                            </div>
                        </div>

                        <hr class="my-4 opacity-50">

                        {{-- RIWAYAT PENDIDIKAN --}}
                        <h6 class="text-muted small fw-bold text-uppercase mb-3">Riwayat Pendidikan</h6>
                        <div id="education-container">
                            @php
                                $jenjangWajib = ['SMP/MTS', 'SMA/MA/SMK', 'Perguruan Tinggi'];
                            @endphp

                            @foreach($jenjangWajib as $key => $jenjang)
                                @php
                                    $eduData = $alumni->pendidikan->where('jenjang', $jenjang)->first();
                                    $isOngoingValue = old("pendidikan.$key.is_ongoing", $eduData->is_ongoing ?? 0);
                                @endphp
                                <div class="education-item border p-3 mb-3 rounded bg-white shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <h6 class="fw-bold text-primary small mb-0">
                                            <i class="bi bi-mortarboard-fill me-1"></i> {{ $jenjang }}
                                        </h6>
                                        <div class="form-check form-switch">
                                            <input type="hidden" name="pendidikan[{{ $key }}][is_ongoing]" value="0">
                                            <input class="form-check-input status-studi"
                                                    type="checkbox"
                                                    name="pendidikan[{{ $key }}][is_ongoing]"
                                                    value="1"
                                                    id="ongoing-{{ $key }}"
                                                    data-index="{{ $key }}"
                                                    {{ $isOngoingValue == 1 ? 'checked' : '' }}>
                                            <label class="form-check-label small fw-bold" for="ongoing-{{ $key }}">
                                                Masih Belajar
                                            </label>
                                        </div>
                                    </div>

                                    <input type="hidden" name="pendidikan[{{ $key }}][jenjang]" value="{{ $jenjang }}">

                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="small fw-bold text-secondary">Nama Sekolah/Kampus</label>
                                            <input type="text"
                                                    name="pendidikan[{{ $key }}][nama_instansi]"
                                                    class="form-control form-control-sm instansi-input"
                                                    data-jenjang="{{ $jenjang }}"
                                                    value="{{ old("pendidikan.$key.nama_instansi", $eduData->nama_instansi ?? '') }}"
                                                    placeholder="Masukkan nama sekolah/kampus">
                                        </div>

                                        <div class="col-md-3 mb-2">
                                            <label class="small fw-bold text-secondary">Tahun Masuk</label>
                                            <input type="number"
                                                name="pendidikan[{{ $key }}][tahun_masuk]"
                                                class="form-control form-control-sm"
                                                value="{{ old("pendidikan.$key.tahun_masuk", $eduData->tahun_masuk ?? '') }}"
                                                placeholder="YYYY"
                                                min="1950"
                                                max="{{ date('Y') }}">
                                        </div>

                                        <div class="col-md-3 mb-2">
                                            <label class="small fw-bold text-secondary" id="label-lulus-{{ $key }}">
                                                {{ $isOngoingValue == 1 ? 'Perkiraan Lulus' : 'Tahun Lulus' }}
                                            </label>
                                            <input type="number"
                                                name="pendidikan[{{ $key }}][tahun_lulus]"
                                                class="form-control form-control-sm tahun-lulus"
                                                id="tahun-lulus-{{ $key }}"
                                                value="{{ old("pendidikan.$key.tahun_lulus", $eduData->tahun_lulus ?? '') }}"
                                                placeholder="YYYY"
                                                min="1950"
                                                max="{{ date('Y') + 10 }}">
                                        </div>

                                        @if($jenjang === 'Perguruan Tinggi')
                                            <div class="col-md-12 mb-2 {{ (empty($eduData->nama_instansi) && !old('pendidikan.'.$key.'.nama_instansi')) ? 'd-none' : '' }}"
                                                    id="prodi-wrapper-{{ $key }}">
                                                <label class="small fw-bold text-secondary">Program Studi</label>
                                                <input type="text"
                                                        name="pendidikan[{{ $key }}][program_studi]"
                                                        class="form-control form-control-sm"
                                                        value="{{ old("pendidikan.$key.program_studi", $eduData->program_studi ?? '') }}"
                                                        placeholder="Contoh: Teknik Informatika">
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <hr class="my-4 opacity-50">

                        {{-- RIWAYAT PEKERJAAN --}}
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="text-muted small fw-bold text-uppercase mb-0">Riwayat Pekerjaan</h6>
                            <button type="button" id="add-work" class="btn btn-sm btn-outline-success rounded-pill">
                                <i class="bi bi-plus-circle me-1"></i> Tambah
                            </button>
                        </div>

                        <div id="work-container">
                            @forelse($alumni->pekerjaan as $index => $job)
                                <div class="work-item border p-3 mb-3 rounded bg-white shadow-sm">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div class="form-check form-switch">
                                            <input type="hidden" name="pekerjaan[{{ $index }}][is_current]" value="0">
                                            <input class="form-check-input" type="checkbox"
                                                name="pekerjaan[{{ $index }}][is_current]"
                                                value="1"
                                                id="is_current_{{ $index }}"
                                                {{ $job->is_current ? 'checked' : '' }}>
                                            <label class="form-check-label small fw-bold text-muted" for="is_current_{{ $index }}">
                                                Pekerjaan Saat Ini
                                            </label>
                                        </div>
                                        <button type="button"
                                                class="btn btn-xs btn-outline-danger p-1 px-2"
                                                style="font-size: 0.7rem;"
                                                onclick="deletePekerjaan({{ $job->id }}, this)">
                                            <i class="bi bi-trash"></i> Hapus
                                        </button>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="small fw-bold text-secondary">Nama Perusahaan</label>
                                            <input type="text"
                                                name="pekerjaan[{{ $index }}][nama_perusahaan]"
                                                class="form-control form-control-sm"
                                                value="{{ $job->nama_perusahaan }}"
                                                required>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="small fw-bold text-secondary">Jabatan/Posisi</label>
                                            <input type="text"
                                                name="pekerjaan[{{ $index }}][jabatan]"
                                                class="form-control form-control-sm"
                                                value="{{ $job->jabatan }}"
                                                required>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div id="work-empty-placeholder" class="text-center py-4 text-muted border rounded mb-3 bg-light">
                                    <i class="bi bi-briefcase d-block fs-2 mb-2 opacity-25"></i>
                                    <small>Kosongkan jika saat ini belum bekerja atau masih fokus studi.</small>
                                </div>
                            @endforelse
                        </div>

                        <div class="mb-3 mt-4">
                            <label class="form-label small fw-bold">Pesan Alumni & Harapan Untuk Sekolah</label>
                            <textarea class="form-control"
                                    name="harapan"
                                    rows="3"
                                    placeholder="Tuliskan kesan pesan Anda untuk kemajuan sekolah...">{{ old('harapan', $alumni->harapan) }}</textarea>
                        </div>

                        <div class="d-flex justify-content-between mt-5 pt-3 border-top">
                            <a href="{{ route('alumni.dashboard') }}" class="btn btn-light border px-4">Batal</a>
                            <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                <i class="bi bi-save me-2"></i> Simpan Profil
                            </button>
                        </div>
                    </form>

                    <form id="delete-pekerjaan-form" action="" method="POST" style="display:none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-custom mb-3 text-center border-0 shadow-sm">
                <div class="card-body py-4">
                    @php
                        $fotoUtama = $alumni->fotos->where('is_main', true)->first();
                    @endphp
                    <div class="position-relative d-inline-block">
                        <img id="previewFoto"
                            src="{{ $fotoUtama ? asset('storage/' . $fotoUtama->path_file) : asset('assets/img/default-user.png') }}"
                            class="rounded-circle shadow-sm"
                            style="width: 150px; height: 150px; object-fit: cover; border: 5px solid #fff;">
                    </div>
                    <h6 class="mt-3 fw-bold mb-0 text-dark">{{ $alumni->nama_lengkap }}</h6>
                    <p class="text-muted small">Preview Foto Profil</p>
                </div>
            </div>

            <div class="card card-custom shadow-sm border-0 mb-3">
                <div class="card-body p-4">
                    <h6 class="fw-bold mb-3">
                        <i class="bi bi-shield-lock me-2 text-warning"></i> Keamanan Akun
                    </h6>
                    <form action="{{ route('alumni.profile.updatePassword') }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-2">
                            <input type="password" name="current_password" class="form-control form-control-sm @error('current_password') is-invalid @enderror" placeholder="Password Saat Ini" required>
                            @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="mb-2">
                            <input type="password" name="password" class="form-control form-control-sm @error('password') is-invalid @enderror" placeholder="Password Baru" required>
                        </div>
                        <div class="mb-3">
                            <input type="password" name="password_confirmation" class="form-control form-control-sm" placeholder="Ulangi Password Baru" required>
                        </div>
                        <button type="submit" class="btn btn-warning btn-sm w-100 fw-bold shadow-sm">
                            Perbarui Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = () => {
                document.getElementById('previewFoto').src = reader.result;
            };
            if (event.target.files[0]) {
                reader.readAsDataURL(event.target.files[0]);
            }
        }

        function deletePekerjaan(id, btn) {
            if (confirm('Yakin ingin menghapus riwayat pekerjaan ini secara permanen?')) {
                const form = document.getElementById('delete-pekerjaan-form');
                form.action = "{{ url('alumni/riwayat-pekerjaan') }}/" + id;
                form.submit();
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            initializeProgramStudiLogic();
            initializeStatusStudiLogic();
        });

        function initializeProgramStudiLogic() {
            document.querySelectorAll('.instansi-input[data-jenjang="Perguruan Tinggi"]').forEach((ptInput) => {
                const index = ptInput.closest('.education-item').querySelector('[data-index]')?.getAttribute('data-index');
                const prodiWrapper = document.getElementById(`prodi-wrapper-${index}`);

                if (prodiWrapper) {
                    ptInput.addEventListener('input', function() {
                        this.value.trim() !== "" ? prodiWrapper.classList.remove('d-none') : prodiWrapper.classList.add('d-none');
                    });
                }
            });
        }

        function initializeStatusStudiLogic() {
            document.querySelectorAll('.status-studi').forEach((checkbox) => {
                const index = checkbox.getAttribute('data-index');
                const labelLulus = document.getElementById(`label-lulus-${index}`);
                const tahunLulusInput = document.getElementById(`tahun-lulus-${index}`);

                updateTahunFields(checkbox.checked, tahunLulusInput, labelLulus, true);

                checkbox.addEventListener('change', function() {
                    updateTahunFields(this.checked, tahunLulusInput, labelLulus, false);
                });
            });
        }

        function updateTahunFields(isOngoing, tahunLulusInput, labelLulus, isInitial) {
            if (isOngoing) {
                labelLulus.innerText = 'Perkiraan Lulus';
                tahunLulusInput.placeholder = 'YYYY (Opsional)';
            } else {
                labelLulus.innerText = 'Tahun Lulus';
                tahunLulusInput.placeholder = 'YYYY';
            }
        }

        let workIndex = {{ $alumni->pekerjaan->count() }};
        document.getElementById('add-work').addEventListener('click', function() {
            const container = document.getElementById('work-container');
            const placeholder = document.getElementById('work-empty-placeholder');
            if (placeholder) placeholder.remove();

            const html = `
                <div class="work-item border p-3 mb-3 rounded bg-white shadow-sm">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <div class="form-check form-switch">
                            <input type="hidden" name="pekerjaan[${workIndex}][is_current]" value="0">
                            <input class="form-check-input" type="checkbox"
                                name="pekerjaan[${workIndex}][is_current]"
                                value="1"
                                id="is_current_${workIndex}">
                            <label class="form-check-label small fw-bold text-muted" for="is_current_${workIndex}">
                                Pekerjaan Saat Ini
                            </label>
                        </div>
                        <button type="button" class="btn btn-xs btn-outline-danger remove-item p-1 px-2" style="font-size: 0.7rem;">
                            <i class="bi bi-trash"></i> Hapus
                        </button>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <label class="small fw-bold text-secondary">Nama Perusahaan</label>
                            <input type="text" name="pekerjaan[${workIndex}][nama_perusahaan]" class="form-control form-control-sm" required placeholder="Contoh: PT. Maju Jaya">
                        </div>
                        <div class="col-md-6 mb-2">
                            <label class="small fw-bold text-secondary">Jabatan/Posisi</label>
                            <input type="text" name="pekerjaan[${workIndex}][jabatan]" class="form-control form-control-sm" required placeholder="Staff">
                        </div>
                    </div>
                </div>`;

            container.insertAdjacentHTML('beforeend', html);
            workIndex++;
        });

        document.addEventListener('click', function(e) {
            if (e.target.closest('.remove-item')) {
                e.target.closest('.work-item').remove();
                if (document.querySelectorAll('.work-item').length === 0) {
                    document.getElementById('work-container').innerHTML = `
                        <div id="work-empty-placeholder" class="text-center py-4 text-muted border rounded mb-3 bg-light">
                            <i class="bi bi-briefcase d-block fs-2 mb-2 opacity-25"></i>
                            <small>Kosongkan jika saat ini belum bekerja.</small>
                        </div>`;
                }
            }
        });
    </script>

    <style>
        .btn-xs { padding: 0.25rem 0.5rem; font-size: 0.75rem; }
        .card-custom { border-radius: 12px; }
        .form-control:focus { box-shadow: none; border-color: #0d6efd; }
        .form-check-input:checked { background-color: #0d6efd; border-color: #0d6efd; }
        .form-control:disabled { background-color: #f8f9fa; cursor: not-allowed; opacity: 0.8; }
    </style>
    @endsection
