<template>
  <div class="min-h-screen bg-[#f8fafc] text-slate-800 flex flex-col justify-center py-10 sm:px-6 lg:px-8 font-sans selection:bg-[#059669] selection:text-white">
    <div class="sm:mx-auto sm:w-full sm:max-w-md px-4">
      <div class="bg-white border border-emerald-200 rounded-3xl p-6 sm:p-8 shadow-sm relative">
        <!-- Top Logo RJB Resmi -->
        <div class="text-center mb-5">
          <router-link to="/" class="inline-block group">
            <div class="w-16 h-16 rounded-2xl bg-emerald-50/50 border border-emerald-200 p-2 shadow-sm mx-auto flex items-center justify-center group-hover:scale-105 transition">
              <img
                :src="settingsStore.settings?.logo_url || '/logo.png'"
                alt="Logo RJB"
                class="w-full h-full object-contain"
              />
            </div>
          </router-link>
          <h2 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight mt-3">
            Pendaftaran <span class="bg-gradient-to-r from-emerald-600 to-teal-600 bg-clip-text text-transparent">Akun Login Anggota</span>
          </h2>
          <p class="text-xs text-slate-600 mt-1">
            Khusus anggota yang ingin memiliki akun login ke Portal Sistem Rojabiyyah
          </p>
        </div>

        <!-- Error Alert -->
        <div v-if="error" class="mb-4 p-3 bg-rose-50 border border-rose-300 text-rose-700 text-xs rounded-xl font-medium">
          {{ error }}
        </div>

        <!-- Form -->
        <form @submit.prevent="handleRegister" class="space-y-4 text-xs">
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
            <input
              v-model="form.name"
              type="text"
              required
              placeholder="Misal: Fulan bin Fulan"
              class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
            />
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Username Login</label>
              <input
                v-model="form.username"
                type="text"
                placeholder="ahmad"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
              />
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Email *</label>
              <input
                v-model="form.email"
                type="email"
                required
                placeholder="nama@email.com"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
              />
            </div>
          </div>

          <div>
            <label class="block font-semibold text-slate-700 mb-1">Kata Sandi *</label>
            <div class="relative">
              <input
                v-model="form.password"
                :type="showPassword ? 'text' : 'password'"
                required
                minlength="6"
                placeholder="••••••••"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition pr-10"
              />
              <button
                type="button"
                @click="showPassword = !showPassword"
                class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600"
              >
                <svg v-if="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
              </button>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">No. WhatsApp / HP *</label>
              <input
                v-model="form.phone_wa"
                type="text"
                required
                placeholder="812xxxxxxxx"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
              />
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Pilihan Wilayah</label>
              <select
                v-model="form.domicile"
                class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
              >
                <option value="">Pilih Wilayah Domisili</option>
                <option v-for="dom in domiciles" :key="dom" :value="dom">{{ dom }}</option>
              </select>
            </div>
          </div>

          <div>
            <label class="block font-semibold text-slate-700 mb-1">Alamat Singkat</label>
            <textarea
              v-model="form.notes"
              rows="2"
              placeholder="Alamat domisili atau kelurahan..."
              class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
            ></textarea>
          </div>

          <button
            type="submit"
            :disabled="loading"
            class="w-full py-3 px-4 bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold rounded-xl shadow-md shadow-emerald-700/20 flex items-center justify-center space-x-2 transition disabled:opacity-50 mt-2 cursor-pointer"
          >
            <span>{{ loading ? 'Mendaftarkan Akun...' : 'Daftar Sekarang & Masuk' }}</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
          </button>
        </form>

        <!-- Footer Links -->
        <div class="mt-6 pt-5 border-t border-slate-200 text-center space-y-2 text-[11px]">
          <div>
            <span class="text-slate-500">Sudah memiliki akun pengurus atau anggota?</span>
            <router-link to="/login" class="text-emerald-600 hover:underline font-bold ml-1">
              Masuk di Sini
            </router-link>
          </div>
          <div>
            <span class="text-slate-500">Hanya ingin isi data pendaftaran anggota / seragam tanpa akun?</span><br />
            <router-link to="/register-member" class="text-emerald-600 hover:underline font-bold">
              Buka Formulir Pendaftaran
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- Login Modal -->
    <LoginModal :show="showLoginModal" @close="showLoginModal = false" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../../stores/auth';
import { useSettingsStore } from '../../stores/settings';
import LoginModal from '../../components/LoginModal.vue';
import api from '../../services/api';

const router = useRouter();
const authStore = useAuthStore();
const settingsStore = useSettingsStore();
const showLoginModal = ref(false);
const showPassword = ref(false);
const loading = ref(false);
const error = ref('');

const domiciles = ref([
  'Pondok Putra',
  'Pondok Putri',
  'Ndalem',
  'Santri Kalong',
  'Alumni / Luar',
]);

const form = ref({
  name: '',
  username: '',
  email: '',
  password: '',
  phone_wa: '',
  domicile: '',
  notes: '',
});

onMounted(async () => {
  if (!settingsStore.settings?.app_name) {
    settingsStore.fetchSettings();
  }
  try {
    const res = await api.get('/public/options');
    if (res.data?.domiciles?.length) {
      domiciles.value = res.data.domiciles;
    }
  } catch (err) {
    // fallback to default domiciles
  }
});

const handleRegister = async () => {
  loading.value = true;
  error.value = '';
  try {
    const res = await api.post('/public/register-account', form.value);
    authStore.token = res.data.token;
    authStore.user = res.data.user;
    localStorage.setItem('token', res.data.token);
    localStorage.setItem('user', JSON.stringify(res.data.user));
    alert('Akun berhasil dibuat! Anda sekarang masuk ke Portal Rojabiyyah.');
    router.push('/dashboard');
  } catch (err) {
    error.value = err.response?.data?.message || 'Gagal mendaftar akun.';
  } finally {
    loading.value = false;
  }
};
</script>
