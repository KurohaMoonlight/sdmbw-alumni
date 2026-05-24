<template>
  <div class="landing-wrapper overflow-hidden">
    <!-- HERO + STATS -->
    <HeroSection
      :user="user"
      :stats="stats"
      :hero-image="heroImage"
      :dashboard-url="dashboardUrl"
      :login-url="loginUrl"
    />

    <!-- FITUR LAYANAN -->
    <section class="features-section py-5 my-5">
      <div class="container py-5">
        <div class="text-center mb-5 fade-in-up">
          <h2 class="section-title h1 fw-bold mb-3">Layanan Untuk Alumni</h2>
          <p class="text-muted mx-auto" style="max-width: 600px;">Beberapa fitur utama yang dapat Anda akses setelah bergabung dalam sistem alumni kami.</p>
        </div>
        <div class="row g-4">
          <div class="col-md-4" v-for="(feature, i) in features" :key="i">
            <a :href="i === 0 ? '/direktori-alumni' : (user ? dashboardUrl : loginUrl)" class="text-decoration-none h-100 d-block">
              <div class="feature-card glass-card p-5 h-100 fade-in-up" :style="`animation-delay: ${0.4 + i*0.1}s`">
                <div class="feature-icon mb-4" :class="feature.bg">
                  <i :class="['bi', feature.icon]"></i>
                </div>
                <h4 class="fw-bold mb-3 text-dark">{{ feature.title }}</h4>
                <p class="text-muted mb-0 lh-lg">{{ feature.desc }}</p>
              </div>
            </a>
          </div>
        </div>
      </div>
    </section>

    <!-- BERITA -->
    <BeritaSection :beritas="beritas" />

    <!-- TESTIMONI -->
    <TestimonialSection :testimonis="testimonis" />

    <!-- FAQ -->
    <FaqSection :faqs="faqs" :login-url="loginUrl" />

    <!-- CTA SECTION -->
    <section class="cta-section-modern py-5 mb-5">
      <div class="container py-4">
        <div class="cta-banner rounded-5 overflow-hidden position-relative p-5 text-center">
          <div class="cta-bg position-absolute inset-0"></div>
          <div class="position-relative z-1 py-4">
            <h2 class="display-5 fw-bold text-white mb-4">Siap Untuk Terhubung Kembali?</h2>
            <p class="text-white text-opacity-75 mb-5 mx-auto fs-5" style="max-width: 650px;">
              Jangan lewatkan kesempatan untuk saling berbagi peluang kerja, informasi pendidikan, dan silaturahmi.
            </p>
            <template v-if="!user">
              <a :href="loginUrl" class="btn btn-accent-premium btn-lg px-5 py-3 rounded-pill fw-bold shadow-lg">
                Masuk ke Akun Anda <i class="bi bi-chevron-right ms-2"></i>
              </a>
            </template>
            <template v-else>
              <div class="welcome-tag d-inline-flex align-items-center gap-3 px-4 py-3 rounded-pill glass-card border-white border-opacity-20 text-white">
                <div class="avatar-sm rounded-circle bg-accent text-primary fw-bold d-flex align-items-center justify-content-center">
                  {{ user.username?.charAt(0).toUpperCase() }}
                </div>
                <span>Selamat datang kembali, <strong>{{ user.username }}</strong>!</span>
              </div>
            </template>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script setup>
import HeroSection       from './HeroSection.vue';
import BeritaSection     from './BeritaSection.vue';
import TestimonialSection from './TestimonialSection.vue';
import FaqSection        from './FaqSection.vue';

defineProps({
  user:         { type: Object, default: null },
  stats:        { type: Object, default: () => ({ total_alumni: 0, total_angkatan: 0, profil_lengkap: 0, total_instansi: 0 }) },
  faqs:         { type: Array,  default: () => [] },
  testimonis:   { type: Array,  default: () => [] },
  beritas:      { type: Array,  default: () => [] },
  heroImage:    { type: String, required: true },
  dashboardUrl: { type: String, required: true },
  loginUrl:     { type: String, required: true },
});

const features = [
  { title: 'Direktori Alumni',  desc: 'Cari dan temukan teman lama berdasarkan angkatan, lokasi, atau nama dengan mudah.', icon: 'bi-search',    bg: 'bg-primary' },
  { title: 'Update Profil',     desc: 'Kelola data diri, riwayat pendidikan, dan pekerjaan Anda agar tetap terhubung dengan sekolah.', icon: 'bi-person-gear', bg: 'bg-accent' },
  { title: 'Berbagi Peluang',   desc: 'Bagikan informasi peluang karir, pendidikan lanjutan, dan kontribusi nyata untuk almamater.', icon: 'bi-briefcase', bg: 'bg-success' },
];
</script>

<style scoped>
.inset-0 { top: 0; right: 0; bottom: 0; left: 0; }
.z-1 { z-index: 1; }
.avatar-sm { width: 40px; height: 40px; }

.feature-card {
  border-radius: 24px;
  transition: all 0.3s ease;
}
.feature-card:hover {
  background: #fff;
  transform: translateY(-10px);
  box-shadow: 0 20px 50px rgba(0,0,0,0.05);
}
.feature-icon {
  width: 50px; height: 50px;
  border-radius: 12px;
  display: flex; align-items: center; justify-content: center;
  color: #fff; font-size: 1.25rem;
}
.bg-primary { background: #1B3A52; }
.bg-accent  { background: #E8C87A; }
.bg-success { background: #198754; }

.cta-banner { background: #112534; }
.cta-bg { background: radial-gradient(circle at top right, rgba(232,200,122,0.1), transparent); }

.btn-accent-premium {
  background: var(--accent, #E8C87A);
  color: #112534; border: none;
  transition: all 0.3s ease;
  box-shadow: 0 10px 20px rgba(232, 200, 122, 0.2);
}
.btn-accent-premium:hover {
  background: #d9b75e;
  transform: translateY(-4px);
  box-shadow: 0 15px 30px rgba(232, 200, 122, 0.3);
}
</style>
