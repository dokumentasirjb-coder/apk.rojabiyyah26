<template>
  <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm p-4">
    <div class="relative w-full max-w-sm bg-white border border-slate-200 rounded-3xl p-6 sm:p-7 shadow-2xl text-slate-700">
      <!-- Header with Close Button -->
      <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
        <h3 class="text-sm font-bold text-slate-900 tracking-wide">Portal Masuk Petugas</h3>
        <button @click="$emit('close')" class="text-slate-400 hover:text-slate-700 transition p-1 rounded-lg hover:bg-slate-100">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
      </div>

      <!-- Brand Logo & Title -->
      <div class="text-center mb-5">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-200 mb-3 shadow-sm text-emerald-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
        <h4 class="text-xs font-extrabold text-slate-900 tracking-widest uppercase">Media Rojabiyyah</h4>
        <p class="text-[11px] text-slate-500 mt-0.5">Masuk sebagai Super Admin atau Anggota yang memiliki akun</p>
      </div>

      <!-- Error message -->
      <div v-if="error" class="mb-4 p-2.5 bg-rose-50 border border-rose-200 text-rose-700 text-[11px] rounded-xl text-center">
        {{ error }}
      </div>

      <!-- Login Form -->
      <form @submit.prevent="handleLogin" class="space-y-4 text-xs">
        <div>
          <label class="block text-[11px] font-medium text-slate-700 mb-1">Username atau Email</label>
          <input
            v-model="form.login"
            type="text"
            required
            placeholder="ahmad atau email@domain.com"
            class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition text-xs"
          />
        </div>

        <div>
          <label class="block text-[11px] font-medium text-slate-700 mb-1">Kata Sandi</label>
          <div class="relative">
            <input
              v-model="form.password"
              :type="showPassword ? 'text' : 'password'"
              required
              placeholder="••••••••"
              class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition text-xs pr-10"
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

        <button
          type="submit"
          :disabled="loading"
          class="w-full py-2.5 px-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold rounded-xl shadow-md shadow-emerald-500/20 transition flex items-center justify-center space-x-1.5 disabled:opacity-50 mt-2"
        >
          <span>{{ loading ? 'Memproses Masuk...' : 'Masuk ke Akun' }}</span>
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
        </button>
      </form>

      <!-- Quick Demo Selector -->
      <div class="mt-5 pt-4 border-t border-slate-100 text-center">
        <p class="text-[10px] text-slate-500 mb-2">Pilih akun demo untuk pengujian cepat:</p>
        <div class="grid grid-cols-2 gap-2 text-[11px]">
          <button
            type="button"
            @click="quickFill('superadmin@rojabiyyah.id', 'password123')"
            class="py-1.5 px-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-semibold flex items-center justify-center space-x-1.5 transition"
          >
            <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
            <span>Super Admin</span>
          </button>
          <button
            type="button"
            @click="quickFill('ahmad', 'password123')"
            class="py-1.5 px-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-semibold flex items-center justify-center space-x-1.5 transition"
          >
            <span class="w-2 h-2 rounded-full bg-amber-500"></span>
            <span>Anggota (Ahmad)</span>
          </button>
        </div>
      </div>

      <!-- Register Link -->
      <div class="mt-4 text-center text-[11px] text-slate-500">
        Belum memiliki akun login?
        <router-link to="/register-account" @click="$emit('close')" class="text-emerald-600 hover:underline font-semibold ml-1">
          Daftar Akun Anggota
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';

defineProps({
  show: {
    type: Boolean,
    default: false,
  },
});

const emit = defineEmits(['close']);
const router = useRouter();
const authStore = useAuthStore();

const form = ref({ login: '', password: '' });
const showPassword = ref(false);
const loading = ref(false);
const error = ref('');

const quickFill = (login, password) => {
  form.value.login = login;
  form.value.password = password;
};

const handleLogin = async () => {
  loading.value = true;
  error.value = '';
  try {
    await authStore.login(form.value);
    emit('close');
    router.push('/dashboard');
  } catch (err) {
    error.value = err.response?.data?.message || 'Kredensial login tidak valid.';
  } finally {
    loading.value = false;
  }
};
</script>
