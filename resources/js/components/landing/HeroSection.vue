<template>
  <section class="hero-section position-relative min-vh-100 d-flex align-items-center">
    <!-- Background Elements -->
    <div class="hero-bg position-absolute inset-0">
      <img :src="heroImage" alt="Hero Background" class="hero-img w-100 h-100 object-fit-cover" @error="handleImageError">
      <div class="hero-gradient-overlay"></div>
      <div class="hero-mesh-gradient"></div>
    </div>

    <div class="container position-relative z-1">
      <div class="row align-items-center">
        <div class="col-lg-7 text-start">
          <div class="hero-badge-container mb-4 fade-in-up">
            <span class="premium-badge">
              <i class="bi bi-stars text-accent me-2"></i>
              Wadah Resmi Alumni SD Muhammadiyah Birrul Walidain
            </span>
          </div>

          <h1 class="hero-title display-2 fw-800 text-white mb-3 fade-in-up" style="animation-delay: 0.1s">
            Temukan Kembali<br>
            <span class="text-accent text-glow">Jejaring Alumni</span> Anda
          </h1>

          <p class="hero-subtitle fs-5 text-white text-opacity-80 mb-5 fade-in-up" style="animation-delay: 0.2s; max-width: 600px;">
            Bersama membangun masa depan yang lebih cerah melalui kolaborasi, inspirasi, dan silaturahmi yang tak terputus.
          </p>

          <div class="hero-cta d-flex flex-wrap gap-3 fade-in-up" style="animation-delay: 0.3s">
            <template v-if="user">
              <a :href="dashboardUrl" class="btn btn-accent-premium btn-lg px-5 py-3 rounded-pill fw-bold">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard Panel
              </a>
            </template>
            <template v-else>
              <a :href="loginUrl" class="btn btn-accent-premium btn-lg px-5 py-3 rounded-pill fw-bold">
                <i class="bi bi-box-arrow-in-right me-2"></i> Masuk Akun Alumni
              </a>
            </template>
          </div>
        </div>

        <div class="col-lg-5 d-none d-lg-block fade-in-up" style="animation-delay: 0.4s">
          <div class="hero-floating-card glass-card p-4 text-center p-5">
            <div class="card-icon-circle mx-auto mb-4">
              <i class="bi bi-mortarboard fs-1 text-primary"></i>
            </div>
            <h3 class="fw-bold mb-2">Pendidikan Berkualitas</h3>
            <p class="text-muted small">Mencetak generasi unggul yang berakhlak mulia dan berprestasi.</p>
            <div class="d-flex justify-content-center gap-2 mt-4">
              <div class="dot active"></div>
              <div class="dot"></div>
              <div class="dot"></div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="scroll-indicator position-absolute bottom-0 start-50 translate-middle-x mb-5">
      <div class="mouse-icon"></div>
    </div>
  </section>

  <!-- STATS SECTION -->
  <section class="stats-grid py-5 mt-n5 position-relative z-2">
    <div class="container">
      <div class="row g-4 justify-content-center">
        <div class="col-6 col-lg-3" v-for="(stat, i) in statsItems" :key="i">
          <div class="stat-card-premium fade-in-up" :style="`animation-delay: ${0.2 + i*0.1}s`">
            <div class="stat-inner">
              <div class="stat-icon-wrap" :class="stat.bg">
                <i :class="['bi', stat.icon, stat.color]"></i>
              </div>
              <div class="stat-content">
                <div class="stat-number">{{ stat.value }}</div>
                <div class="stat-label text-uppercase">{{ stat.label }}</div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  user:        { type: Object, default: null },
  stats:       { type: Object, default: () => ({ total_alumni: 0, total_angkatan: 0, profil_lengkap: 0, total_instansi: 0 }) },
  heroImage:   { type: String, required: true },
  dashboardUrl:{ type: String, required: true },
  loginUrl:    { type: String, required: true },
});

const handleImageError = (e) => { e.target.style.display = 'none'; };

const statsItems = computed(() => [
  { label: 'Alumni',        value: props.stats?.total_alumni   || 0, icon: 'bi-people-fill',      color: 'text-primary', bg: 'bg-primary-subtle' },
  { label: 'Angkatan',      value: props.stats?.total_angkatan || 0, icon: 'bi-mortarboard-fill', color: 'text-accent',  bg: 'bg-accent-subtle' },
  { label: 'Profil Lengkap',value: props.stats?.profil_lengkap || 0, icon: 'bi-check-circle-fill',color: 'text-success', bg: 'bg-success-subtle' },
  { label: 'Instansi',      value: props.stats?.total_instansi || 0, icon: 'bi-building-fill',    color: 'text-danger',  bg: 'bg-danger-subtle' },
]);
</script>

<style scoped>
.fw-800 { font-weight: 800; }
.inset-0 { top: 0; right: 0; bottom: 0; left: 0; }
.z-1 { z-index: 1; }
.z-2 { z-index: 2; }
.mt-n5 { margin-top: -5rem; }
.text-glow { text-shadow: 0 0 20px rgba(232, 200, 122, 0.4); }

.hero-section { min-height: 100vh; overflow: hidden; background: #112534; }
.hero-img { filter: brightness(0.4) contrast(1.1); }
.hero-gradient-overlay {
  position: absolute; inset: 0;
  background: linear-gradient(to right, rgba(17,37,52,0.9) 0%, rgba(17,37,52,0.6) 50%, rgba(17,37,52,0.2) 100%);
}
.hero-mesh-gradient {
  position: absolute; top: -50%; right: -20%; width: 100%; height: 200%;
  background: radial-gradient(circle at center, rgba(42,83,120,0.2) 0%, transparent 60%);
  transform: rotate(-15deg);
}

.premium-badge {
  background: rgba(255,255,255,0.1);
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255,255,255,0.15);
  color: #fff;
  padding: 0.6rem 1.4rem;
  border-radius: 50px;
  font-weight: 700;
  font-size: 0.8rem;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.btn-accent-premium {
  background: var(--accent, #E8C87A);
  color: #112534;
  border: none;
  transition: all 0.3s ease;
  box-shadow: 0 10px 20px rgba(232, 200, 122, 0.2);
}
.btn-accent-premium:hover {
  background: #d9b75e;
  transform: translateY(-4px);
  box-shadow: 0 15px 30px rgba(232, 200, 122, 0.3);
}

.hero-floating-card {
  border-radius: 30px;
  transform: perspective(1000px) rotateY(-5deg);
  box-shadow: 20px 20px 60px rgba(0,0,0,0.3);
  transition: all 0.5s ease;
}
.hero-floating-card:hover {
  transform: perspective(1000px) rotateY(0deg) translateY(-10px);
}
.card-icon-circle {
  width: 100px; height: 100px;
  background: #f1f5f9;
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
}

.stat-card-premium {
  background: #fff;
  border-radius: 24px;
  padding: 2rem;
  box-shadow: 0 15px 35px rgba(0,0,0,0.06);
  transition: all 0.3s ease;
}
.stat-card-premium:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.1);
}
.stat-inner { display: flex; align-items: center; gap: 1.5rem; }
.stat-icon-wrap {
  width: 60px; height: 60px;
  border-radius: 18px;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.5rem;
}
.stat-number { font-size: 2rem; font-weight: 800; color: #1B3A52; line-height: 1; margin-bottom: 0.2rem; }
.stat-label { color: #64748b; font-size: 0.75rem; font-weight: 700; letter-spacing: 1px; }

.bg-primary-subtle { background: rgba(27, 58, 82, 0.1); }
.bg-accent-subtle  { background: rgba(232, 200, 122, 0.1); }
.bg-success-subtle { background: rgba(25, 135, 84, 0.1); }
.bg-danger-subtle  { background: rgba(220, 53, 69, 0.1); }

.mouse-icon {
  width: 30px; height: 50px;
  border: 2px solid rgba(255,255,255,0.3);
  border-radius: 20px;
  position: relative;
}
.mouse-icon::after {
  content: '';
  position: absolute; top: 8px; left: 50%;
  width: 4px; height: 8px;
  background: #fff;
  border-radius: 2px;
  transform: translateX(-50%);
  animation: mouseWheel 2s infinite;
}
@keyframes mouseWheel {
  0%   { opacity: 1; top: 8px; }
  100% { opacity: 0; top: 30px; }
}

.dot { width: 8px; height: 8px; border-radius: 50%; background: #e2e8f0; }
.dot.active { background: #E8C87A; width: 24px; border-radius: 4px; }
</style>
