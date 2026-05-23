<template>
  <div>
    <!-- STAT CARDS -->
    <div class="row g-3 mb-4">
      <div v-for="stat in statCards" :key="stat.label" class="col-6 col-lg-3">
        <div class="stat-card shadow-sm">
          <div :class="['stat-card-icon', stat.iconClass]"><i :class="stat.icon"></i></div>
          <div>
            <div :class="['stat-card-num', stat.numClass]">{{ formatNumber(stat.value) }}</div>
            <div class="stat-card-label">{{ stat.label }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- TABLE ANGKATAN -->
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
      <div class="card-header bg-primary text-white py-3 d-flex align-items-center justify-content-between">
        <div class="fw-bold"><i class="bi bi-bar-chart-fill me-2"></i> Statistik per Angkatan</div>
        <div class="d-flex gap-2 no-print">
          <a :href="exportPdfUrl" class="btn btn-danger btn-sm fw-bold rounded-pill px-3">
            <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export PDF
          </a>
          <button @click="printPage" class="btn btn-warning btn-sm fw-bold rounded-pill px-3">
            <i class="bi bi-printer-fill me-1"></i> Cetak Laporan
          </button>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
          <thead class="table-light text-uppercase small letter-spacing-1">
            <tr>
              <th class="ps-4">Angkatan</th>
              <th class="text-center">Alumni</th>
              <th class="text-center">Verified</th>
              <th class="text-center">Pending</th>
              <th class="text-center">Lengkap</th>
              <th class="text-end pe-4 no-print">Aksi</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="a in angkatanStats" :key="a.id">
              <td class="ps-4">
                <div class="fw-bold text-primary">{{ a.nama_angkatan }}</div>
                <div class="small text-muted">{{ a.tahun_ajaran }}</div>
              </td>
              <td class="text-center"><span class="badge bg-light text-primary border px-2">{{ a.alumni_count }}</span></td>
              <td class="text-center"><span class="badge bg-success bg-opacity-10 text-success px-2">{{ a.verified_count }}</span></td>
              <td class="text-center"><span class="badge bg-warning bg-opacity-10 text-warning px-2">{{ a.pending_count }}</span></td>
              <td class="text-center"><span class="badge bg-info bg-opacity-10 text-info px-2">{{ a.complete_count }}</span></td>
              <td class="text-end pe-4 no-print">
                <a :href="`/admin/laporan/angkatan/${a.id}`" class="btn btn-outline-info btn-sm rounded-pill px-3 fw-bold">
                  <i class="bi bi-eye"></i> Detail
                </a>
              </td>
            </tr>
          </tbody>
          <tfoot class="table-light fw-bold">
            <tr>
              <td class="ps-4">TOTAL KESELURUHAN</td>
              <td class="text-center">{{ formatNumber(stats.total_alumni) }}</td>
              <td class="text-center">{{ formatNumber(stats.alumni_verified) }}</td>
              <td class="text-center">{{ formatNumber(stats.alumni_pending) }}</td>
              <td class="text-center">{{ formatNumber(stats.profil_lengkap) }}</td>
              <td class="no-print"></td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>

    <!-- MINI TABLES -->
    <div class="row g-4">
      <!-- Top 10 Pendidikan -->
      <div class="col-lg-6">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden h-100">
          <div class="card-header bg-primary text-white py-3">
            <i class="bi bi-mortarboard-fill me-2"></i> Top 10 Pendidikan Lanjutan
          </div>
          <div class="card-body p-4">
            <div v-if="pendidikanStats.length" class="chart-list">
              <div v-for="(p, index) in pendidikanStats" :key="index" class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                  <span class="small fw-bold text-dark"><span :class="['rank-badge me-2', index < 3 ? 'top3' : '']">{{ index + 1 }}</span> {{ p.pendidikan_lanjutan }}</span>
                  <span class="small fw-bold text-primary">{{ p.total }} Alumni</span>
                </div>
                <div class="progress" style="height: 8px;">
                  <div class="progress-bar bg-primary rounded-pill" role="progressbar" :style="{ width: getPercentage(p.total, pendidikanStats[0].total) + '%' }"></div>
                </div>
              </div>
            </div>
            <div v-else class="p-5 text-center text-muted">
              <i class="bi bi-inbox fs-1 opacity-25"></i>
              <p class="mt-2">Belum ada data pendidikan</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Top 10 Pekerjaan -->
      <div class="col-lg-6">
        <div class="card shadow-sm border-0 rounded-4 overflow-hidden h-100">
          <div class="card-header bg-primary text-white py-3">
            <i class="bi bi-briefcase-fill me-2"></i> Top 10 Pekerjaan
          </div>
          <div class="card-body p-4">
            <div v-if="pekerjaanStats.length" class="chart-list">
              <div v-for="(j, index) in pekerjaanStats" :key="index" class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                  <span class="small fw-bold text-dark"><span :class="['rank-badge me-2', index < 3 ? 'top3' : '']">{{ index + 1 }}</span> {{ j.pekerjaan }}</span>
                  <span class="small fw-bold text-success">{{ j.total }} Alumni</span>
                </div>
                <div class="progress" style="height: 8px;">
                  <div class="progress-bar bg-success rounded-pill" role="progressbar" :style="{ width: getPercentage(j.total, pekerjaanStats[0].total) + '%' }"></div>
                </div>
              </div>
            </div>
            <div v-else class="p-5 text-center text-muted">
              <i class="bi bi-inbox fs-1 opacity-25"></i>
              <p class="mt-2">Belum ada data pekerjaan</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  stats: { type: Object, required: true },
  angkatanStats: { type: Array, required: true },
  pendidikanStats: { type: Array, required: true },
  pekerjaanStats: { type: Array, required: true },
  exportPdfUrl: { type: String, default: '' }
});

const statCards = computed(() => [
  { label: 'Total Alumni', value: props.stats.total_alumni, icon: 'bi bi-people-fill', iconClass: 'icon-primary', numClass: 'text-primary' },
  { label: 'Verified', value: props.stats.alumni_verified, icon: 'bi bi-patch-check-fill', iconClass: 'icon-success', numClass: 'text-success' },
  { label: 'Pending', value: props.stats.alumni_pending, icon: 'bi bi-clock-history', iconClass: 'icon-warning', numClass: 'text-warning' },
  { label: 'Lengkap', value: props.stats.profil_lengkap, icon: 'bi bi-file-earmark-check-fill', iconClass: 'icon-info', numClass: 'text-info' }
]);

const formatNumber = (num) => new Intl.NumberFormat('id-ID').format(num);

const printPage = () => window.print();

const getPercentage = (value, max) => {
  if (!max || max === 0) return 0;
  return Math.round((value / max) * 100);
};

</script>

<style scoped>
.stat-card { background: white; border-radius: 14px; padding: 1.25rem; display: flex; align-items: center; gap: 1rem; }
.stat-card-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }
.icon-primary { background: rgba(27,58,82,0.1); color: #1B3A52; }
.icon-success { background: rgba(22,163,74,0.1); color: #16a34a; }
.icon-warning { background: rgba(217,119,6,0.1); color: #d97706; }
.icon-info { background: rgba(8,145,178,0.1); color: #0891b2; }
.stat-card-num { font-weight: 800; font-size: 1.5rem; line-height: 1; }
.stat-card-label { font-size: 0.75rem; color: #94a3b8; font-weight: 600; text-uppercase: uppercase; }

.letter-spacing-1 { letter-spacing: 1px; }

.rank-badge {
  display: inline-flex; align-items: center; justify-content: center;
  width: 24px; height: 24px; border-radius: 50%; background: #f1f5f9; color: #94a3b8; font-size: 0.7rem; font-weight: 800;
}
.rank-badge.top3 { background: #EAE0CF; color: #7a5c1e; }

@media print {
  .no-print { display: none !important; }
}
</style>
