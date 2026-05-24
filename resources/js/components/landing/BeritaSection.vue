<template>
  <section v-if="beritas && beritas.length > 0" class="berita-section py-5 my-3 bg-white position-relative">
    <div class="container py-5">
      <div class="text-center mb-5 fade-in-up">
        <div class="hero-badge-container mb-3">
          <span class="premium-badge text-primary bg-primary-subtle border-0">
            <i class="bi bi-newspaper text-primary me-2"></i> Berita &amp; Informasi
          </span>
        </div>
        <h2 class="section-title h1 fw-bold mb-3">Kabar Terbaru Sekolah &amp; Alumni</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">Ikuti berita, pengumuman, dan agenda kegiatan terbaru seputar SD Muhammadiyah Birrul Walidain.</p>
      </div>

      <div class="row g-4 justify-content-center">
        <div class="col-md-6 col-lg-4" v-for="(berita, i) in beritas" :key="berita.id">
          <div
            class="berita-card glass-card h-100 d-flex flex-column fade-in-up overflow-hidden position-relative"
            :class="{ 'featured-card-highlight': berita.is_featured }"
            :style="`animation-delay: ${0.2 + (i%3)*0.1}s`"
          >
            <div v-if="berita.is_featured" class="featured-badge-modern">
              <i class="bi bi-star-fill me-1"></i> Unggulan
            </div>
            <div v-if="berita.image_url" class="berita-img-wrapper overflow-hidden" style="height: 200px; border-bottom: 1px solid rgba(226, 232, 240, 0.6); position: relative;">
              <img :src="berita.image_url" class="w-100 h-100 object-fit-cover berita-img-thumb" alt="Gambar Berita">
            </div>
            <div class="p-4 d-flex flex-column flex-grow-1">
              <div class="d-flex align-items-center justify-content-between mb-3 text-muted small">
                <span><i class="bi bi-calendar3 me-1"></i>{{ formatDate(berita.created_at) }}</span>
                <span class="d-flex align-items-center gap-1">
                  <i class="bi bi-eye me-1"></i>{{ berita.views_count || 0 }} dibaca
                </span>
              </div>
              <h5 class="fw-bold mb-3 text-primary text-line-clamp-2">{{ berita.title }}</h5>
              <p class="text-muted small mb-4 flex-grow-1 text-line-clamp-4">{{ stripHtml(berita.excerpt || berita.content) }}</p>
              <a :href="'/berita/' + berita.slug" class="btn btn-outline-primary rounded-pill px-4 mt-auto align-self-start fw-bold shadow-sm">
                Baca Selengkapnya <i class="bi bi-arrow-right ms-1"></i>
              </a>
            </div>
          </div>
        </div>
      </div>

      <div class="text-center mt-5 fade-in-up" style="animation-delay: 0.4s">
        <a href="/berita" class="btn btn-accent-premium btn-lg px-5 py-3 rounded-pill fw-bold">
          Lihat Semua Berita <i class="bi bi-arrow-right ms-2"></i>
        </a>
      </div>
    </div>
  </section>
</template>

<script setup>
defineProps({
  beritas: { type: Array, default: () => [] },
});

const formatDate = (dateStr) => {
  if (!dateStr) return '';
  return new Date(dateStr).toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
};

const stripHtml = (html) => {
  if (!html) return '';
  const doc = new DOMParser().parseFromString(html, 'text/html');
  return doc.body.textContent || '';
};
</script>

<style scoped>
.text-line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.text-line-clamp-4 {
  display: -webkit-box;
  -webkit-line-clamp: 4;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
.berita-card {
  border-radius: 24px;
  background: white;
  border: 1px solid rgba(226, 232, 240, 0.8);
  box-shadow: 0 10px 30px rgba(0,0,0,0.03);
  transition: all 0.3s ease;
}
.berita-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.08);
  border-color: rgba(27, 58, 82, 0.3);
}
.berita-img-thumb { transition: transform 0.4s ease; }
.berita-card:hover .berita-img-thumb { transform: scale(1.05); }

.featured-badge-modern {
  position: absolute; top: 14px; left: 14px; z-index: 5;
  background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
  color: white; font-size: 0.72rem; font-weight: 800;
  padding: 5px 12px; border-radius: 50px;
  letter-spacing: 0.8px; text-transform: uppercase;
  box-shadow: 0 4px 10px rgba(217,119,6,0.3);
  display: flex; align-items: center; gap: 5px;
}
.berita-card.featured-card-highlight {
  border: 1.5px solid rgba(232, 200, 122, 0.6);
  background: linear-gradient(to bottom, #fff 0%, #fffbf2 100%);
}
.premium-badge {
  padding: 0.5rem 1.2rem;
  border-radius: 50px;
  font-weight: 700; font-size: 0.8rem;
  text-transform: uppercase; letter-spacing: 1px;
}
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
