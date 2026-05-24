<template>
  <div class="login-card-premium animate__animated animate__zoomIn">
    <div class="login-header text-center py-5 position-relative overflow-hidden">
      <!-- Decorative Background -->
      <div class="header-glow position-absolute top-0 start-50 translate-middle-x"></div>
      
      <div class="position-relative z-1">
        <div class="logo-wrap-premium mx-auto mb-4 d-flex align-items-center justify-content-center shadow-lg">
          <i class="bi bi-shield-lock-fill fs-2 text-white"></i>
        </div>
        <h2 class="h4 fw-bold text-white mb-1">Akses Sistem Alumni</h2>
        <p class="small text-white text-opacity-50 mb-0">Silakan masuk dengan akun terdaftar Anda</p>
      </div>
    </div>

    <div class="login-body p-4 p-md-5 bg-white">
      <!-- Error Alerts with Premium Style -->
      <div v-if="errorMsg" class="alert-premium-danger d-flex align-items-center gap-3 mb-4">
        <div class="alert-icon"><i class="bi bi-exclamation-triangle-fill"></i></div>
        <div class="small fw-bold">{{ errorMsg }}</div>
      </div>

      <form @submit.prevent="handleSubmit">
        <div class="mb-4">
          <label class="form-label small fw-bold text-muted text-uppercase letter-spacing-1 mb-2">Username</label>
          <div class="input-group-premium">
            <span class="input-icon"><i class="bi bi-person-fill"></i></span>
            <input v-model="form.username" type="text" class="form-control-premium" placeholder="Username Anda" required autofocus>
          </div>
        </div>

        <div class="mb-4">
          <label class="form-label small fw-bold text-muted text-uppercase letter-spacing-1 mb-2">Password</label>
          <div class="input-group-premium">
            <span class="input-icon"><i class="bi bi-lock-fill"></i></span>
            <input v-model="form.password" :type="showPassword ? 'text' : 'password'" class="form-control-premium" placeholder="••••••••" required>
            <button @click="showPassword = !showPassword" type="button" class="btn-password-toggle">
              <i :class="showPassword ? 'bi bi-eye-slash-fill' : 'bi bi-eye-fill'"></i>
            </button>
          </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
          <div class="form-check custom-checkbox">
            <input v-model="form.remember" type="checkbox" class="form-check-input" id="remember">
            <label class="form-check-label small text-muted cursor-pointer" for="remember">Ingat Sesi Saya</label>
          </div>
        </div>

        <button type="submit" class="btn btn-primary-premium w-100 py-3 rounded-pill fw-bold shadow-lg mb-4" :disabled="loading">
          <template v-if="loading">
            <span class="spinner-border spinner-border-sm me-2"></span>
            Memproses...
          </template>
          <template v-else>
            Masuk Sekarang <i class="bi bi-arrow-right-short ms-1 fs-5"></i>
          </template>
        </button>
      </form>

        <div class="mt-4 pt-2 text-center">
          <p class="small text-muted mb-2">Butuh bantuan? Hubungi Admin via WhatsApp:</p>
          <div class="d-flex flex-column gap-2 align-items-center">
            <a :href="waUrl('Halo Admin, saya lupa password akun alumni SDMBW saya. Mohon bantuannya 🙏')" target="_blank" class="text-success small fw-bold text-decoration-none d-inline-flex align-items-center gap-2 link-hover-move">
              <i class="bi bi-whatsapp fs-5"></i> 🔑 Saya Lupa Password
            </a>
            <a :href="waUrl('Halo Admin, saya ingin konsultasi mengenai portal alumni SDMBW. Mohon bantuannya 🙏')" target="_blank" class="text-success small fw-bold text-decoration-none d-inline-flex align-items-center gap-2 link-hover-move">
              <i class="bi bi-whatsapp fs-5"></i> 💬 Mau Konsultasi
            </a>
            <a :href="waUrl('Halo Admin, saya ingin mendaftar sebagai alumni SDMBW di portal ini. Mohon bantuannya 🙏')" target="_blank" class="text-success small fw-bold text-decoration-none d-inline-flex align-items-center gap-2 link-hover-move">
              <i class="bi bi-whatsapp fs-5"></i> 📋 Belum Punya Akun
            </a>
          </div>
        </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';

const props = defineProps({
  actionUrl: { type: String, required: true },
  registerUrl: { type: String, required: true },
  csrfToken: { type: String, required: true },
  initialError: { type: String, default: '' }
});

const form = ref({
  username: '',
  password: '',
  remember: false
});

const loading = ref(false);
const errorMsg = ref(props.initialError);
const showPassword = ref(false);

const waUrl = (message) => {
  return 'https://wa.me/6281248076886?text=' + encodeURIComponent(message);
};

const handleSubmit = async () => {
  loading.value = true;
  errorMsg.value = '';

  try {
    const f = document.createElement('form');
    f.method = 'POST'; f.action = props.actionUrl;
    
    const csrf = document.createElement('input'); csrf.type = 'hidden'; csrf.name = '_token'; csrf.value = props.csrfToken; f.appendChild(csrf);
    const u = document.createElement('input'); u.type = 'hidden'; u.name = 'username'; u.value = form.value.username; f.appendChild(u);
    const p = document.createElement('input'); p.type = 'hidden'; p.name = 'password'; p.value = form.value.password; f.appendChild(p);

    if (form.value.remember) {
      const r = document.createElement('input'); r.type = 'hidden'; r.name = 'remember'; r.value = 'on'; f.appendChild(r);
    }

    document.body.appendChild(f);
    f.submit();
  } catch (err) {
    errorMsg.value = 'Terjadi kesalahan sistem.';
    loading.value = false;
  }
};
</script>

<style scoped>
.login-card-premium { width: 100%; max-width: 440px; background: white; border-radius: 30px; box-shadow: 0 25px 60px rgba(0,0,0,0.12); overflow: hidden; }
.login-header { background: #1B3A52; }
.header-glow { width: 300px; height: 300px; background: radial-gradient(circle, rgba(232, 200, 122, 0.2) 0%, transparent 70%); }

.logo-wrap-premium { width: 64px; height: 64px; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); border-radius: 20px; }
.z-1 { z-index: 1; }
.letter-spacing-1 { letter-spacing: 1px; }

.input-group-premium { position: relative; display: flex; align-items: center; }
.input-icon { position: absolute; left: 16px; color: #94a3b8; font-size: 1.1rem; }
.form-control-premium { 
  width: 100%; padding: 0.8rem 1rem 0.8rem 3rem; background: #f8fafc; border: 1.5px solid #e2e8f0; 
  border-radius: 16px; transition: all 0.2s ease; outline: none;
}
.form-control-premium:focus { border-color: #1B3A52; background: white; box-shadow: 0 0 0 4px rgba(27, 58, 82, 0.1); }

.btn-password-toggle { position: absolute; right: 12px; background: transparent; border: none; color: #94a3b8; padding: 5px; }

.btn-primary-premium { background: #1B3A52; color: white; border: none; transition: all 0.3s ease; }
.btn-primary-premium:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(27, 58, 82, 0.25); }

.btn-outline-primary-premium { color: #1B3A52; border: 1.5px solid #1B3A52; background: transparent; transition: all 0.3s ease; }
.btn-outline-primary-premium:hover { background: rgba(27, 58, 82, 0.05); transform: translateY(-1px); }

.alert-premium-danger { background: #fee2e2; border-left: 4px solid #ef4444; padding: 1rem; border-radius: 12px; color: #991b1b; }
.alert-icon { font-size: 1.25rem; }

.link-hover-move:hover { transform: translateX(5px); transition: all 0.3s ease; }
.cursor-pointer { cursor: pointer; }
</style>
