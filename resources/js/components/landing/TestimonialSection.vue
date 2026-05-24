<template>
  <section v-if="testimonis && testimonis.length > 0" class="testimonials-section py-5 bg-light position-relative">
    <div class="container py-5">
      <div class="text-center mb-5 fade-in-up">
        <h2 class="section-title h1 fw-bold mb-3">Apa Kata Alumni?</h2>
        <p class="text-muted mx-auto" style="max-width: 600px;">Cerita inspiratif dari mereka yang telah lulus dan berkarya.</p>
      </div>

      <div class="row g-4">
        <div class="col-md-4" v-for="(testimoni, i) in testimonis" :key="testimoni.id">
          <div class="testimonial-card p-4 h-100 fade-in-up" :style="`animation-delay: ${0.2 + (i%3)*0.1}s`">
            <div class="d-flex align-items-center gap-3 mb-4">
              <div class="avatar-md rounded-circle overflow-hidden bg-white shadow-sm border" style="width: 55px; height: 55px; flex-shrink: 0;">
                <img
                  v-if="testimoni.alumni.fotos && testimoni.alumni.fotos.length > 0"
                  :src="`/storage/${testimoni.alumni.fotos[0].path_file}`"
                  class="w-100 h-100 object-fit-cover"
                  alt="Foto Alumni"
                >
                <div v-else class="w-100 h-100 d-flex align-items-center justify-content-center bg-primary text-white fw-bold fs-5">
                  {{ testimoni.alumni.nama_lengkap.charAt(0).toUpperCase() }}
                </div>
              </div>
              <div>
                <h6 class="fw-bold mb-1 text-primary">{{ testimoni.alumni.nama_lengkap }}</h6>
                <p class="text-muted small mb-0">
                  <i class="bi bi-mortarboard-fill me-1 text-accent"></i>
                  {{ testimoni.alumni.angkatan?.nama_angkatan || '-' }}
                </p>
              </div>
            </div>
            <div class="quote-icon mb-2"><i class="bi bi-quote fs-2 text-black-50 opacity-25"></i></div>
            <p class="fst-italic text-muted mb-0 lh-lg">"{{ testimoni.content }}"</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
defineProps({
  testimonis: { type: Array, default: () => [] },
});
</script>

<style scoped>
.testimonial-card {
  border-radius: 24px;
  background: white;
  border: 1px solid rgba(226, 232, 240, 0.8);
  box-shadow: 0 10px 30px rgba(0,0,0,0.03);
  transition: all 0.3s ease;
  position: relative; z-index: 1;
}
.testimonial-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.08);
  border-color: rgba(232,200,122,0.5);
}
.quote-icon { margin-top: -15px; }
.text-accent { color: #E8C87A; }
</style>
