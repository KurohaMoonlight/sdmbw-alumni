<template>
  <div class="alumni-table-container">
    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-header bg-white border-0 py-4 px-4 border-bottom">
        <div class="d-flex flex-column flex-xl-row justify-content-between align-items-xl-center gap-4">
          
          <div class="d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
               <i class="bi bi-people-fill fs-5"></i>
            </div>
            <div>
              <h5 class="mb-1 fw-bold text-dark letter-spacing-1">Data Alumni</h5>
              <p class="mb-0 text-muted small"><span class="badge bg-primary bg-opacity-10 text-primary rounded-pill me-1">{{ totalRows }}</span> alumni terdaftar dalam sistem</p>
            </div>
          </div>
          
          <div class="d-flex flex-wrap align-items-center gap-3">
            <!-- Search Box -->
            <div class="search-box position-relative" style="flex: 1; min-width: 300px;">
              <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
              <input 
                v-model="search" 
                type="text" 
                class="form-control ps-5 py-2 rounded-3 border-0 bg-light focus-ring-primary shadow-none" 
                placeholder="Cari Nama atau NISN..."
                @keyup.enter="handleFilter"
              >
            </div>
            
            <!-- Filters -->
            <div class="d-flex align-items-center gap-2">
              <div class="filter-box" style="width: 180px;">
                <select v-model="filterAngkatan" class="form-select py-2 rounded-3 border-0 bg-light focus-ring-primary cursor-pointer text-muted shadow-none" @change="handleFilter">
                  <option value="">Semua Angkatan</option>
                  <option v-for="a in angkatans" :key="a.id" :value="a.id">{{ a.nama_angkatan }}</option>
                </select>
              </div>

              <div class="filter-box" style="width: 140px;">
                <select v-model="filterGender" class="form-select py-2 rounded-3 border-0 bg-light focus-ring-primary cursor-pointer text-muted shadow-none" @change="handleFilter">
                  <option value="">Semua JK</option>
                  <option value="L">Laki-laki (L)</option>
                  <option value="P">Perempuan (P)</option>
                </select>
              </div>
            </div>
            
            <!-- Action Buttons -->
            <div class="d-flex gap-2 ms-xl-auto">
              <a v-if="importUrl" :href="importUrl" class="btn btn-light-premium rounded-3 px-3 py-2 fw-bold d-flex align-items-center border-0 shadow-sm">
                <i class="bi bi-cloud-arrow-up-fill me-2 text-primary"></i> Import
              </a>
              <a v-if="exportUrl" :href="exportUrl" class="btn btn-primary-premium rounded-3 px-3 py-2 fw-bold d-flex align-items-center shadow-sm">
                <i class="bi bi-file-earmark-excel-fill me-2"></i> Export
              </a>
              <button v-if="deleteAllUrl" type="button" class="btn btn-danger-premium rounded-3 px-3 py-2 fw-bold d-flex align-items-center shadow-sm" @click="showDeleteAllModal = true">
                <i class="bi bi-trash3-fill me-2"></i> Hapus Semua
              </button>
            </div>
          </div>
          
        </div>
      </div>

      <div class="table-responsive">
        <table class="table table-hover align-middle mb-0 custom-table">
          <thead class="bg-light-subtle">
            <tr>
              <th class="ps-4 py-3 text-muted small text-uppercase fw-bold letter-spacing-1">NISN & Nama</th>
              <th class="py-3 text-muted small text-uppercase fw-bold letter-spacing-1">Angkatan</th>
              <th class="py-3 text-muted small text-uppercase fw-bold letter-spacing-1 text-center">JK</th>
              <th class="py-3 text-muted small text-uppercase fw-bold letter-spacing-1">Kontak</th>
              <th class="pe-4 py-3 text-muted small text-uppercase fw-bold letter-spacing-1 text-end">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-if="loading">
              <td colspan="5" class="text-center py-5">
                <div class="spinner-border text-primary" role="status"></div>
                <p class="text-muted small mt-2">Memuat data alumni...</p>
              </td>
            </tr>
            <tr v-else-if="rows.length === 0">
              <td colspan="5" class="text-center py-5">
                <i class="bi bi-inbox fs-1 text-muted opacity-25"></i>
                <p class="text-muted mt-2">Data alumni tidak ditemukan</p>
              </td>
            </tr>
            <tr v-else v-for="row in rows" :key="row.id">
              <td class="ps-4 py-3">
                <div class="d-flex align-items-center gap-3">
                  <div class="avatar-sm-premium rounded-circle bg-primary-subtle text-primary fw-bold">
                    {{ row.nama_lengkap?.charAt(0).toUpperCase() }}
                  </div>
                  <div>
                    <div class="fw-bold text-dark">
                      {{ row.nama_lengkap }}
                      <span v-if="row.nama_panggilan" class="text-muted fw-normal small">({{ row.nama_panggilan }})</span>
                    </div>
                    <div class="small text-muted">
                      NISN: {{ row.nisn }} 
                      <span v-if="row.nipd" class="ms-1 px-1 bg-light border rounded">NIPD: {{ row.nipd }}</span>
                    </div>
                  </div>
                </div>
              </td>
              <td class="py-3">
                <span class="badge-angkatan px-3 py-1 rounded-pill">
                  {{ row.angkatan?.nama_angkatan || '-' }}
                </span>
                <div class="small text-muted mt-1">{{ row.tahun_lulus }}</div>
              </td>
              <td class="py-3 text-center">
                <span v-if="row.jenis_kelamin" :class="['badge rounded-circle p-2', row.jenis_kelamin === 'L' ? 'bg-primary-subtle text-primary' : 'bg-danger-subtle text-danger']" style="width: 32px; height: 32px; display: inline-flex; align-items: center; justify-content: center;">
                  {{ row.jenis_kelamin }}
                </span>
                <span v-else class="text-muted">-</span>
              </td>
              <td class="py-3">
                <div class="small mb-1"><i class="bi bi-whatsapp text-success me-1"></i> {{ row.no_hp || '-' }}</div>
                <div class="small text-muted text-truncate" style="max-width: 150px;">
                  <i class="bi bi-envelope me-1"></i> {{ row.email || row.user?.email || '-' }}
                </div>
              </td>
              <td class="pe-4 py-3 text-end" style="overflow: visible;">
                <div class="dropdown">
                  <button 
                    class="btn btn-light btn-sm rounded-pill p-2" 
                    type="button" 
                    data-bs-toggle="dropdown" 
                  >
                    <i class="bi bi-three-dots-vertical"></i>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 p-2">
                    <li><a class="dropdown-item rounded-3 py-2" :href="`${detailUrl}/${row.id}`"><i class="bi bi-eye me-2 text-primary"></i> Detail</a></li>
                    <template v-if="canEdit">
                      <li><a class="dropdown-item rounded-3 py-2" :href="`${detailUrl}/${row.id}/edit`"><i class="bi bi-pencil me-2 text-warning"></i> Edit</a></li>
                      <li><hr class="dropdown-divider"></li>
                      <li><button class="dropdown-item rounded-3 py-2 text-danger" @click="handleDelete(row.id, row.nama_lengkap)"><i class="bi bi-trash me-2"></i> Hapus</button></li>
                    </template>
                  </ul>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="card-footer bg-white border-0 py-4 px-4 d-flex justify-content-between align-items-center">
        <div class="small text-muted">Menampilkan {{ rows.length }} dari {{ totalRows }} data</div>
        <slot name="pagination"></slot>
      </div>
    </div>

    <!-- MODAL HAPUS SEMUA (SAFETY FIRST) -->
    <div v-if="showDeleteAllModal" class="modal-backdrop fade show"></div>
    <div v-if="showDeleteAllModal" class="modal fade show d-block" tabindex="-1">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
          <div class="modal-header bg-danger text-white rounded-top-4 py-3">
            <h5 class="modal-title fw-bold"><i class="bi bi-exclamation-triangle-fill me-2"></i> Konfirmasi Hapus Masal</h5>
            <button type="button" class="btn-close btn-close-white" @click="showDeleteAllModal = false"></button>
          </div>
          <div class="modal-body p-4">
            <div class="alert alert-warning border-0 rounded-3 small mb-4">
              <i class="bi bi-info-circle-fill me-2"></i>
              Tindakan ini akan menghapus <strong>SELURUH DATA ALUMNI</strong> dan <strong>AKUN LOGIN</strong> terkait secara permanen. Data yang sudah dihapus tidak dapat dikembalikan.
            </div>
            
            <p class="mb-3 fw-bold">Ketik teks berikut untuk mengonfirmasi:</p>
            <div class="bg-light p-2 text-center fw-bold text-danger mb-3 rounded border border-danger border-opacity-25 letter-spacing-1">
              HAPUS SEMUA DATA
            </div>
            
            <input v-model="confirmText" type="text" class="form-control rounded-3 py-2" placeholder="Ketik di sini...">
          </div>
          <div class="modal-footer border-0 p-4 pt-0">
            <button type="button" class="btn btn-light rounded-pill px-4" @click="showDeleteAllModal = false">Batal</button>
            <button 
              type="button" 
              class="btn btn-danger rounded-pill px-4 fw-bold" 
              :disabled="confirmText !== 'HAPUS SEMUA DATA' || deletingAll"
              @click="executeDeleteAll"
            >
              <span v-if="deletingAll" class="spinner-border spinner-border-sm me-1"></span>
              Ya, Hapus Semua Sekarang
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  rows: { type: Array, required: true },
  totalRows: { type: Number, required: true },
  angkatans: { type: Array, required: true },
  loading: { type: Boolean, default: false },
  detailUrl: { type: String, required: true },
  exportUrl: { type: String, default: '' },
  importUrl: { type: String, default: '' },
  deleteAllUrl: { type: String, default: '' },
  canEdit: { type: Boolean, default: true }
});

const emit = defineEmits(['filter', 'delete', 'verify']);

const search = ref('');
const filterAngkatan = ref('');
const filterGender = ref('');
const showDeleteAllModal = ref(false);
const confirmText = ref('');
const deletingAll = ref(false);

// Filter disatukan dalam satu fungsi
const handleFilter = () => {
  const params = new URLSearchParams(window.location.search);
  
  if (search.value) params.set('search', search.value);
  else params.delete('search');
  
  if (filterAngkatan.value) params.set('angkatan_id', filterAngkatan.value);
  else params.delete('angkatan_id');
  
  if (filterGender.value) params.set('jenis_kelamin', filterGender.value);
  else params.delete('jenis_kelamin');
  
  // Reset ke halaman 1 saat filter berubah
  params.delete('page');
  
  window.location.search = params.toString();
};

// Inisialisasi filter dari URL agar tetap terpilih saat reload
const initFilters = () => {
  const params = new URLSearchParams(window.location.search);
  search.value = params.get('search') || '';
  filterAngkatan.value = params.get('angkatan_id') || '';
  filterGender.value = params.get('jenis_kelamin') || '';
};

initFilters();


const handleDelete = (id, name) => {
  if (!confirm(`Apakah Anda yakin ingin menghapus data alumni "${name}"? Tindakan ini permanen.`)) return;
  
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = `${props.detailUrl}/${id}`;
  
  const csrf = document.createElement('input');
  csrf.type = 'hidden';
  csrf.name = '_token';
  csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  form.appendChild(csrf);
  
  const method = document.createElement('input');
  method.type = 'hidden';
  method.name = '_method';
  method.value = 'DELETE';
  form.appendChild(method);
  
  document.body.appendChild(form);
  form.submit();
};

const executeDeleteAll = () => {
  if (confirmText.value !== 'HAPUS SEMUA DATA') return;
  
  deletingAll.value = true;
  
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = props.deleteAllUrl;
  
  const csrf = document.createElement('input');
  csrf.type = 'hidden';
  csrf.name = '_token';
  csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  form.appendChild(csrf);
  
  const input = document.createElement('input');
  input.type = 'hidden';
  input.name = 'confirmation';
  input.value = confirmText.value;
  form.appendChild(input);
  
  document.body.appendChild(form);
  form.submit();
};

const capitalize = (s) => s.charAt(0).toUpperCase() + s.slice(1);
</script>

<style scoped>
.letter-spacing-1 { letter-spacing: 1px; }

.custom-table thead th {
  border-top: none;
  background-color: #f8fafc;
}

.table-responsive {
  overflow: initial !important;
}

@media (max-width: 991.98px) {
  .table-responsive {
    overflow-x: auto !important;
    -webkit-overflow-scrolling: touch;
  }
}

.custom-table tbody tr {
  transition: all 0.2s ease;
}
.custom-table tbody tr:hover {
  background-color: rgba(248, 250, 252, 0.8);
}

.avatar-sm-premium {
  width: 40px; height: 40px;
  display: flex; align-items: center; justify-content: center;
  font-size: 0.9rem;
}

.badge-angkatan {
  background: #f1f5f9;
  color: #1B3A52;
  font-weight: 700;
  font-size: 0.7rem;
}

.bg-primary-subtle { background: rgba(27, 58, 82, 0.1); }

.btn-light-premium {
  background: #ffffff;
  color: #1B3A52;
  border: 1px solid #e2e8f0;
  transition: all 0.3s ease;
}
.btn-light-premium:hover {
  background: #f8fafc;
  transform: translateY(-2px);
  box-shadow: 0 8px 15px rgba(27, 58, 82, 0.05);
  border-color: #cbd5e1;
}

.btn-primary-premium {
  background: #1B3A52;
  color: #fff;
  border: none;
  transition: all 0.3s ease;
}
.btn-primary-premium:hover {
  background: #112534;
  transform: translateY(-2px);
  box-shadow: 0 8px 15px rgba(27, 58, 82, 0.2);
}

.btn-danger-premium {
  background: #dc3545;
  color: #fff;
  border: none;
  transition: all 0.3s ease;
}
.btn-danger-premium:hover {
  background: #bb2d3b;
  transform: translateY(-2px);
  box-shadow: 0 8px 15px rgba(220, 53, 69, 0.2);
}

.focus-ring-primary:focus {
  border-color: #1B3A52 !important;
  box-shadow: 0 0 0 0.25rem rgba(27, 58, 82, 0.1) !important;
  outline: none;
}

.cursor-pointer {
  cursor: pointer;
}

.dropdown-item:active {
  background-color: #f1f5f9;
  color: #112534;
}

.fade-in {
  animation: fadeIn 0.4s ease-out forwards;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(5px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
