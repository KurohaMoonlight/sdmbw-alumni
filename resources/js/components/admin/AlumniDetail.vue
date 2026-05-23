<template>
  <div class="row g-4">
    <!-- LEFT: DATA -->
    <div class="col-lg-8">
      <!-- Data Pribadi -->
      <div class="card-section mb-4">
        <div class="card-section-header">
          <div class="card-section-title">
            <i class="bi bi-person-vcard-fill me-2"></i> Data Pribadi
          </div>
        </div>
        <div class="card-section-body p-4">
          <div class="bio-row border-bottom py-2 d-flex">
            <span class="bio-label text-muted fw-bold small text-uppercase" style="width: 150px;">NISN</span>
            <span class="bio-value fw-bold">{{ alumni.nisn }}</span>
          </div>
          <div v-if="alumni.nipd" class="bio-row border-bottom py-2 d-flex">
            <span class="bio-label text-muted fw-bold small text-uppercase" style="width: 150px;">NIPD</span>
            <span class="bio-value fw-bold">{{ alumni.nipd }}</span>
          </div>
          <div class="bio-row border-bottom py-2 d-flex">
            <span class="bio-label text-muted fw-bold small text-uppercase" style="width: 150px;">Nama Lengkap</span>
            <span class="bio-value fw-bold">{{ alumni.nama_lengkap }}</span>
          </div>
          <div class="bio-row border-bottom py-2 d-flex">
            <span class="bio-label text-muted fw-bold small text-uppercase" style="width: 150px;">Nama Panggilan</span>
            <span class="bio-value">{{ alumni.nama_panggilan || '-' }}</span>
          </div>
          <div class="bio-row border-bottom py-2 d-flex">
            <span class="bio-label text-muted fw-bold small text-uppercase" style="width: 150px;">Jenis Kelamin</span>
            <span class="bio-value">
              <template v-if="alumni.jenis_kelamin">
                <i :class="['bi me-1', alumni.jenis_kelamin === 'L' ? 'bi-gender-male text-primary' : 'bi-gender-female text-danger']"></i>
                {{ alumni.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
              </template>
              <template v-else>-</template>
            </span>
          </div>
          <div class="bio-row border-bottom py-2 d-flex align-items-center">
            <span class="bio-label text-muted fw-bold small text-uppercase" style="width: 150px;">Angkatan</span>
            <div class="bio-value">
              <span class="bio-angkatan-tag bg-warning bg-opacity-10 text-warning-emphasis px-2 py-1 rounded small fw-bold">
                {{ alumni.angkatan?.nama_angkatan || '-' }}
              </span>
              <span class="text-muted small ms-2">({{ alumni.angkatan?.tahun_ajaran || '-' }})</span>
            </div>
          </div>
          <div class="bio-row border-bottom py-2 d-flex">
            <span class="bio-label text-muted fw-bold small text-uppercase" style="width: 150px;">Tahun Lulus</span>
            <span class="bio-value fw-bold">{{ alumni.tahun_lulus }}</span>
          </div>
          <div class="bio-row border-bottom py-2 d-flex">
            <span class="bio-label text-muted fw-bold small text-uppercase" style="width: 150px;">Alamat</span>
            <span :class="['bio-value', !alumni.alamat ? 'text-muted fst-italic' : '']">{{ alumni.alamat || '-' }}</span>
          </div>
          <div class="bio-row border-bottom py-2 d-flex">
            <span class="bio-label text-muted fw-bold small text-uppercase" style="width: 150px;">No. HP / WA</span>
            <span class="bio-value">
              {{ alumni.no_hp }}
              <span v-if="alumni.show_no_hp" class="ms-2 badge bg-success bg-opacity-10 text-success border border-success border-opacity-25">
                <i class="bi bi-eye-fill"></i> Publik
              </span>
            </span>
          </div>
          <div class="bio-row border-bottom py-2 d-flex">
            <span class="bio-label text-muted fw-bold small text-uppercase" style="width: 150px;">Email</span>
            <span class="bio-value">{{ alumni.email || alumni.user?.email || '-' }}</span>
          </div>
        </div>
      </div>

      <!-- Riwayat Pendidikan -->
      <div v-if="alumni.pendidikan?.length" class="card-section mb-4">
        <div class="card-section-header">
          <div class="card-section-title"><i class="bi bi-mortarboard-fill me-2"></i> Riwayat Pendidikan</div>
        </div>
        <div class="card-section-body p-4">
          <div v-for="edu in alumni.pendidikan" :key="edu.id" class="timeline-item p-3 mb-2 rounded-3 border bg-light">
            <div class="d-flex gap-3">
              <div class="timeline-icon text-primary"><i class="bi bi-mortarboard-fill"></i></div>
              <div class="timeline-content">
                <h6 class="fw-bold mb-1">{{ edu.jenjang }}: {{ edu.nama_instansi }}</h6>
                <p v-if="edu.fakultas" class="small text-muted mb-0"><i class="bi bi-building me-1"></i> Fakultas: {{ edu.fakultas }}</p>
                <p v-if="edu.program_studi" class="small text-muted mb-1"><i class="bi bi-book me-1"></i> Prodi: {{ edu.program_studi }}</p>
                <p class="small text-muted mb-2">
                  <i class="bi bi-calendar3 me-1"></i>
                  {{ edu.tahun_masuk || '-' }} — {{ edu.is_ongoing ? 'Sekarang' : (edu.tahun_lulus || '-') }}
                </p>
                <span v-if="edu.is_ongoing" class="badge bg-info">Aktif</span>
                <span v-else class="badge bg-success">Lulus</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Riwayat Pekerjaan -->
      <div v-if="alumni.pekerjaan?.length" class="card-section mb-4">
        <div class="card-section-header">
          <div class="card-section-title"><i class="bi bi-briefcase-fill me-2"></i> Riwayat Pekerjaan</div>
        </div>
        <div class="card-section-body p-4">
          <div v-for="job in alumni.pekerjaan" :key="job.id" class="timeline-item p-3 mb-2 rounded-3 border bg-light">
            <div class="d-flex gap-3">
              <div :class="['timeline-icon', job.is_current ? 'text-info' : 'text-success']">
                <i class="bi bi-briefcase-fill"></i>
              </div>
              <div class="timeline-content">
                <h6 class="fw-bold mb-1">{{ job.nama_perusahaan }}</h6>
                <p class="small text-muted mb-1">{{ job.jabatan }}</p>
                <p v-if="job.tahun_mulai" class="small text-muted mb-2"><i class="bi bi-calendar3 me-1"></i> Mulai: {{ job.tahun_mulai }}</p>
                <span v-if="job.is_current" class="badge bg-info">Sekarang</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Informasi Akun -->
      <div class="card-section mb-4">
        <div class="card-section-header">
          <div class="card-section-title"><i class="bi bi-shield-lock-fill me-2"></i> Informasi Akun</div>
        </div>
        <div class="card-section-body p-4">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="small text-muted text-uppercase fw-bold d-block mb-1">Username</label>
              <div class="p-2 bg-light rounded fw-bold">{{ alumni.user?.username || '-' }}</div>
            </div>
            <div class="col-md-6">
              <label class="small text-muted text-uppercase fw-bold d-block mb-1">Status Akun</label>
              <div class="p-2 bg-light rounded">
                <span v-if="alumni.user?.is_active" class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Aktif</span>
                <span v-else class="text-danger fw-bold"><i class="bi bi-x-circle-fill me-1"></i> Non-aktif</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- RIGHT: SIDEBAR -->
    <div class="col-lg-4">
      <!-- Foto Profil -->
      <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
        <div class="card-header bg-primary text-white py-3 fw-bold">
          <i class="bi bi-image-fill me-2"></i> Foto Profil
        </div>
        <div class="card-body text-center p-4">
          <div class="avatar-container mx-auto mb-3 bg-light rounded-4 d-flex align-items-center justify-content-center overflow-hidden" style="width: 100%; aspect-ratio: 1;">
            <img v-if="fotoUtama" :src="`/storage/${fotoUtama.path_file}`" class="w-100 h-100 object-fit-cover shadow-sm">
            <i v-else class="bi bi-person-bounding-box text-muted opacity-25" style="font-size: 5rem;"></i>
          </div>
          <div v-if="fotoUtama" class="text-muted small">
            <i class="bi bi-calendar3 me-1"></i> Diunggah {{ new Date(fotoUtama.created_at).toLocaleDateString('id-ID') }}
          </div>
        </div>
      </div>

      <!-- Status Alumni -->
      <div class="card shadow-sm border-0 mb-4 rounded-4 overflow-hidden">
        <div class="card-header bg-primary text-white py-3 fw-bold">
          <i class="bi bi-info-circle-fill me-2"></i> Status Alumni
        </div>
        <div class="card-body p-4">
          <div class="mb-4">
            <label class="small text-muted text-uppercase fw-bold d-block mb-2">Verifikasi</label>
            <span v-if="alumni.status_verifikasi === 'verified'" class="badge bg-success px-3 py-2 rounded-pill w-100 fs-6">
              <i class="bi bi-patch-check-fill me-1"></i> Terverifikasi
            </span>
            <span v-else-if="alumni.status_verifikasi === 'pending'" class="badge bg-warning px-3 py-2 rounded-pill w-100 fs-6">
              <i class="bi bi-clock-fill me-1"></i> Menunggu
            </span>
            <span v-else class="badge bg-danger px-3 py-2 rounded-pill w-100 fs-6">
              <i class="bi bi-x-circle-fill me-1"></i> Ditolak
            </span>
          </div>

          <div class="alert alert-secondary border-0 small mb-0">
            <template v-if="alumni.status_verifikasi === 'pending'">
              <strong>Pending:</strong> Menunggu keputusan admin.
            </template>
            <template v-else-if="alumni.status_verifikasi === 'verified'">
              <strong>Terverifikasi:</strong> Alumni aktif dan dapat login.
            </template>
            <template v-else>
              <strong>Ditolak:</strong> Alumni tidak dapat mengakses akun.
            </template>
          </div>
        </div>
      </div>

      <!-- Aksi -->
      <div v-if="canEdit" class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-primary text-white py-3 fw-bold">
          <i class="bi bi-gear-fill me-2"></i> Aksi
        </div>
        <div class="card-body p-4">
          <div class="d-grid gap-2">
            <template v-if="alumni.status_verifikasi === 'pending'">
              <button @click="verifyAlumni('verified')" class="btn btn-success fw-bold py-2">
                <i class="bi bi-check-circle-fill me-1"></i> Setujui Alumni
              </button>
              <button @click="verifyAlumni('rejected')" class="btn btn-danger fw-bold py-2">
                <i class="bi bi-x-circle-fill me-1"></i> Tolak Alumni
              </button>
            </template>
            
            <div v-else class="p-3 bg-light rounded text-center small fw-bold mb-2">
              <i class="bi bi-info-circle me-1"></i>
              {{ alumni.status_verifikasi === 'verified' ? 'Alumni sudah diverifikasi' : 'Alumni sudah ditolak' }}
            </div>

            <hr class="my-2 opacity-50">

            <button @click="resetPassword" class="btn btn-danger fw-bold text-white py-2">
              <i class="bi bi-key-fill me-1"></i> Reset Password
            </button>
            <a :href="`/admin/alumni/${alumni.id}/edit`" class="btn btn-primary fw-bold py-2">
              <i class="bi bi-pencil-fill me-1"></i> Edit Data
            </a>
            <button @click="confirmDelete" class="btn btn-outline-danger fw-bold py-2">
              <i class="bi bi-trash3-fill me-1"></i> Hapus Alumni
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  alumni: { type: Object, required: true },
  verifyUrl: { type: String, required: true },
  resetPasswordUrl: { type: String, required: true },
  deleteUrl: { type: String, required: true },
  canEdit: { type: Boolean, default: true }
});

const fotoUtama = computed(() => props.alumni.fotos?.find(f => f.is_main));

const submitForm = (url, method, params = {}) => {
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = url;

  const csrf = document.createElement('input');
  csrf.type = 'hidden';
  csrf.name = '_token';
  csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  form.appendChild(csrf);

  const methodInput = document.createElement('input');
  methodInput.type = 'hidden';
  methodInput.name = '_method';
  methodInput.value = method;
  form.appendChild(methodInput);

  Object.keys(params).forEach(key => {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = key;
    input.value = params[key];
    form.appendChild(input);
  });

  document.body.appendChild(form);
  form.submit();
};

const verifyAlumni = (status) => {
  if (confirm(`Yakin ingin ${status === 'verified' ? 'menyetujui' : 'menolak'} alumni ini?`)) {
    submitForm(props.verifyUrl, 'PUT', { status });
  }
};

const resetPassword = () => {
  if (confirm('Reset password alumni ini ke NISN mereka?')) {
    submitForm(props.resetPasswordUrl, 'POST');
  }
};

const confirmDelete = () => {
  if (confirm('YAKIN INGIN MENGHAPUS ALUMNI INI? \n\nSeluruh data (akun, pendidikan, pekerjaan, foto) akan terhapus permanen dan tidak dapat dikembalikan.')) {
    submitForm(props.deleteUrl, 'DELETE');
  }
};
</script>

<style scoped>
.card-section { background: white; border-radius: 14px; border: 1px solid rgba(226,232,240,0.8); overflow: hidden; }
.card-section-header { background: #1B3A52; padding: 0.9rem 1.5rem; color: white; font-weight: 700; font-size: 0.85rem; border-left: 3px solid #EAE0CF; }
.timeline-icon { font-size: 1.2rem; }
</style>
