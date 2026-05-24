<template>
  <section v-if="faqs && faqs.length > 0" class="faq-section py-5 my-3">
    <div class="container py-5">
      <div class="row align-items-center">
        <div class="col-lg-5 mb-5 mb-lg-0 fade-in-up">
          <div class="hero-badge-container mb-3">
            <span class="premium-badge text-primary bg-primary-subtle border-0">
              <i class="bi bi-question-circle-fill text-primary me-2"></i> FAQ
            </span>
          </div>
          <h2 class="display-6 fw-bold mb-4">Pertanyaan Seputar<br><span class="text-primary">Alumni SDMBW</span></h2>
          <p class="text-muted mb-4 lh-lg">Temukan jawaban dari pertanyaan yang sering diajukan mengenai login sistem, pembaruan data profil, dan cara memberikan testimoni alumni.</p>
          <a :href="loginUrl" class="btn btn-outline-primary rounded-pill px-4 py-2 fw-bold shadow-sm">
            Masuk ke Sistem <i class="bi bi-arrow-right ms-2"></i>
          </a>
        </div>

        <div class="col-lg-7 fade-in-up" style="animation-delay: 0.2s">
          <div class="accordion accordion-flush custom-accordion" id="faqAccordion">
            <div class="accordion-item" v-for="(faq, index) in faqs" :key="faq.id">
              <h2 class="accordion-header" :id="'heading' + index">
                <button
                  class="accordion-button fw-bold"
                  :class="{ 'collapsed': activeIndex !== index }"
                  type="button"
                  @click="activeIndex = activeIndex === index ? null : index"
                >
                  {{ faq.question }}
                </button>
              </h2>
              <div class="accordion-collapse" v-show="activeIndex === index">
                <div class="accordion-body text-muted lh-lg">
                  {{ faq.answer }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref } from 'vue';

defineProps({
  faqs:     { type: Array,  default: () => [] },
  loginUrl: { type: String, required: true },
});

const activeIndex = ref(null);
</script>

<style scoped>
.premium-badge {
  padding: 0.5rem 1.2rem;
  border-radius: 50px;
  font-weight: 700; font-size: 0.8rem;
  text-transform: uppercase; letter-spacing: 1px;
}

.custom-accordion .accordion-item {
  border: none;
  background: transparent;
  margin-bottom: 1rem;
}
.custom-accordion .accordion-button {
  background: white;
  border-radius: 16px !important;
  box-shadow: 0 4px 15px rgba(0,0,0,0.02);
  padding: 1.25rem 1.5rem;
  color: #1B3A52;
  transition: all 0.3s;
  border: 1px solid rgba(226, 232, 240, 0.6);
}
.custom-accordion .accordion-button:focus {
  box-shadow: 0 0 0 3px rgba(27,58,82,0.1);
}
.custom-accordion .accordion-button:not(.collapsed) {
  background: #1B3A52;
  color: white;
  box-shadow: 0 8px 25px rgba(27,58,82,0.15);
  border-color: #1B3A52;
}
.custom-accordion .accordion-button::after        { filter: contrast(0.5); }
.custom-accordion .accordion-button:not(.collapsed)::after { filter: brightness(0) invert(1); }
.custom-accordion .accordion-collapse {
  background: white;
  border-radius: 0 0 16px 16px;
  margin-top: -10px;
  padding-top: 15px;
  border: 1px solid rgba(226, 232, 240, 0.6);
  border-top: none;
  box-shadow: 0 10px 15px rgba(0,0,0,0.02);
}
</style>
