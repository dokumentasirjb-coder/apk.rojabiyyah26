<template>
  <div class="min-h-screen bg-slate-50 text-slate-800 flex flex-col justify-center py-8 sm:px-6 lg:px-8 font-sans selection:bg-[#059669] selection:text-white relative overflow-hidden">
    <!-- Ambient Glow Backgrounds -->
    <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-emerald-400/10 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-96 h-96 rounded-full bg-teal-400/10 blur-3xl pointer-events-none"></div>

    <div class="max-w-5xl mx-auto w-full px-4 sm:px-6 relative z-10">
      <!-- Back to Public Portal Link -->
      <div class="mb-6 flex items-center justify-between">
        <router-link
          to="/"
          class="inline-flex items-center space-x-2 text-xs font-semibold text-slate-600 hover:text-emerald-700 transition group"
        >
          <span class="group-hover:-translate-x-1 transition-transform">&larr;</span>
          <span>Kembali ke Portal Informasi Publik</span>
        </router-link>

        <router-link
          to="/register-member"
          class="text-xs font-semibold text-emerald-700 hover:text-emerald-800 transition"
        >
          Pendaftaran Panitia Lapangan &rarr;
        </router-link>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
        <!-- Kolom Kiri: Informasi Publik Acara (Event Public Card) -->
        <div class="lg:col-span-6 space-y-6">
          <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold mb-3 uppercase tracking-wider">
              <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
              <span>Portal Masuk Petugas &amp; Panitia</span>
            </div>

            <h1 class="text-2xl sm:text-4xl font-black text-slate-900 tracking-tight leading-tight">
              Pusat Administrasi &amp; Kendali Haul Ke-5
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 mt-2 leading-relaxed">
              Pondok Pesantren Al-Muhibbin Tambakberas Jombang. Sistem terpadu pencatatan peminjaman logistik, direktori posko relawan, dan pelaporan situasi lapangan secara realtime.
            </p>
          </div>

          <!-- Micro Stats Synchronized -->
          <div class="grid grid-cols-3 gap-3">
            <div class="p-3.5 rounded-2xl bg-white border border-slate-200 shadow-sm">
              <span class="text-[10px] text-slate-500 uppercase font-bold block">Relawan</span>
              <span class="text-lg font-black text-slate-900 font-mono mt-0.5 block">100+</span>
              <span class="text-[9px] text-emerald-600 font-semibold">Siaga Lapangan</span>
            </div>

            <div class="p-3.5 rounded-2xl bg-white border border-slate-200 shadow-sm">
              <span class="text-[10px] text-slate-500 uppercase font-bold block">Posko</span>
              <span class="text-lg font-black text-amber-600 font-mono mt-0.5 block">Aktif</span>
              <span class="text-[9px] text-slate-500 font-semibold">Terpantau</span>
            </div>

            <div class="p-3.5 rounded-2xl bg-white border border-slate-200 shadow-sm">
              <span class="text-[10px] text-slate-500 uppercase font-bold block">Logistik</span>
              <span class="text-lg font-black text-emerald-600 font-mono mt-0.5 block">QR Scan</span>
              <span class="text-[9px] text-slate-500 font-semibold">Akuntabel</span>
            </div>
          </div>

          <!-- Public Notice Box -->
          <div class="p-4 rounded-2xl bg-white border border-slate-200 text-xs text-slate-600 space-y-1.5 shadow-sm">
            <div class="font-bold text-slate-800 flex items-center space-x-1.5">
              <span>ℹ️</span>
              <span>Bukan Petugas / Tidak Punya Akun Login?</span>
            </div>
            <p class="text-[11px] leading-relaxed text-slate-500">
              Masyarakat umum dan relawan yang mendaftar via formulir tidak perlu login untuk melihat informasi publik. Silakan buka halaman beranda atau isi formulir pendaftaran seragam.
            </p>
          </div>
        </div>

        <!-- Kolom Kanan: Card Login Petugas -->
        <div class="lg:col-span-6">
          <div class="bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-md relative">
            <div class="flex items-center space-x-3 pb-4 border-b border-slate-100 mb-5">
              <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 font-bold text-lg">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
              </div>
              <div>
                <h3 class="text-base font-bold text-slate-900">Login Masuk Sistem</h3>
                <p class="text-[11px] text-slate-500">Gunakan akun resmi panitia yang telah didaftarkan</p>
              </div>
            </div>

            <!-- Error message -->
            <div v-if="error" class="mb-4 p-3 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-xl flex items-center space-x-2">
              <svg class="w-4 h-4 text-rose-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
              <span>{{ error }}</span>
            </div>

            <!-- Login Form -->
            <form @submit.prevent="handleLogin" class="space-y-4 text-xs">
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email atau Username *</label>
                <input
                  v-model="form.login"
                  type="text"
                  required
                  placeholder="superadmin@rojabiyyah.id atau nama pengguna"
                  class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition text-xs"
                />
              </div>

              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Kata Sandi (Password) *</label>
                <div class="relative">
                  <input
                    v-model="form.password"
                    :type="showPassword ? 'text' : 'password'"
                    required
                    placeholder="••••••••"
                    class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition text-xs pr-10"
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
                class="w-full py-3 px-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold rounded-xl shadow-md shadow-emerald-500/20 transition flex items-center justify-center space-x-1.5 disabled:opacity-50 mt-2 active:scale-95"
              >
                <span v-if="loading" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                <span>{{ loading ? 'Memproses Masuk...' : 'Masuk ke Portal Petugas' }}</span>
              </button>
            </form>

            <!-- Quick Demo Selector -->
            <div class="mt-5 pt-4 border-t border-slate-100 text-center">
              <p class="text-[10px] text-slate-500 mb-2">Pilih akun pengujian cepat (Development Demo):</p>
              <div class="grid grid-cols-2 gap-2 text-[11px]">
                <button
                  type="button"
                  @click="quickFill('superadmin@rojabiyyah.id', 'password123')"
                  class="py-2 px-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-semibold flex items-center justify-center space-x-1.5 transition"
                >
                  <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                  <span>Super Admin</span>
                </button>
                <button
                  type="button"
                  @click="quickFill('ahmad', 'password123')"
                  class="py-2 px-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 font-semibold flex items-center justify-center space-x-1.5 transition"
                >
                  <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                  <span>Anggota (Ahmad)</span>
                </button>
              </div>
            </div>

            <!-- Register Link -->
            <div class="mt-4 text-center text-xs text-slate-500">
              Belum punya akses akun login?
              <router-link to="/register-account" class="text-emerald-600 hover:underline font-semibold ml-1">
                Daftar Akun Anggota
              </router-link>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '../../stores/auth';

const router = useRouter();
const route = useRoute();
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
    const redirect = route.query.redirect || '/dashboard';
    router.push(redirect);
  } catch (err) {
    error.value = err.response?.data?.message || 'Kredensial login tidak valid.';
  } finally {
    loading.value = false;
  }
};
</script>
