<template>
  <div class="min-h-screen bg-[#f1f5f9] text-slate-800 flex items-center justify-center p-3 sm:p-6 lg:p-8 font-sans selection:bg-[#059669] selection:text-white relative overflow-hidden">
    <!-- Ambient Background Lighting -->
    <div class="absolute -top-40 -left-40 w-[500px] h-[500px] rounded-full bg-emerald-500/15 blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -right-40 w-[500px] h-[500px] rounded-full bg-indigo-500/15 blur-3xl pointer-events-none"></div>

    <!-- Main Vristo Auth Cover Card -->
    <div class="max-w-[1100px] w-full bg-white rounded-3xl shadow-2xl shadow-slate-900/10 border border-slate-200/80 overflow-hidden flex flex-col lg:flex-row relative z-10 min-h-[640px]">
      
      <!-- ===================================================================== -->
      <!-- LEFT SIDE: COVER PANEL (VRISTO SLANTED HIGH-CONTRAST BRANDING)       -->
      <!-- ===================================================================== -->
      <div class="relative w-full lg:w-[48%] xl:w-[49%] bg-gradient-to-br from-[#022c22] via-[#064e3b] to-[#1e1b4b] p-7 sm:p-10 lg:p-12 text-white flex flex-col justify-between overflow-hidden [clip-path:none] lg:[clip-path:polygon(0_0,100%_0,88%_100%,0%_100%)]">
        <!-- Background Decorative Elements -->
        <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full bg-emerald-400/20 blur-2xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-20 w-72 h-72 rounded-full bg-indigo-500/20 blur-3xl pointer-events-none"></div>
        
        <!-- Subtle Pattern Grid Overlay -->
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:16px_16px] pointer-events-none"></div>

        <!-- 1. Top Brand Header -->
        <div class="relative z-10 flex items-center space-x-3.5">
          <div class="w-12 h-12 rounded-2xl bg-white p-2 flex items-center justify-center shadow-xl shadow-black/20 flex-shrink-0 group hover:scale-105 transition">
            <img
              v-if="settingsStore.settings?.logo_url && settingsStore.settings?.logo_url !== '/logo.png'"
              :src="settingsStore.settings.logo_url"
              alt="Logo Rojabiyyah"
              class="w-full h-full object-contain"
            />
            <div v-else class="w-full h-full rounded-xl bg-gradient-to-br from-[#059669] to-[#10b981] flex items-center justify-center text-white">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
          </div>
          <div>
            <div class="text-base sm:text-lg font-black tracking-wider uppercase leading-tight text-white flex items-center space-x-2">
              <span>{{ settingsStore.settings?.app_name || 'MEDIA ROJABIYYAH' }}</span>
            </div>
            <p class="text-[11px] text-emerald-300 font-bold tracking-wide mt-0.5">
              {{ settingsStore.settings?.app_sub_name || 'Haul Ke-5 & Rojabiyyah 2026' }}
            </p>
          </div>
        </div>

        <!-- 2. Center Vector Illustration (Vristo Style Secure Portal Graphic) -->
        <div class="relative z-10 py-8 lg:py-4 flex flex-col items-center justify-center">
          <div class="relative w-64 sm:w-72 max-w-full">
            <!-- Floating Security Shield Badge -->
            <div class="absolute -top-3 -right-2 z-20 w-11 h-11 rounded-2xl bg-gradient-to-tr from-emerald-400 to-teal-200 text-emerald-950 flex items-center justify-center shadow-lg shadow-emerald-500/30 animate-bounce duration-1000">
              <svg class="w-6 h-6 text-emerald-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            </div>

            <!-- Main Auth Card Graphic -->
            <div class="bg-white/95 backdrop-blur-md rounded-2xl p-5 shadow-2xl border border-white/30 text-slate-800 space-y-4">
              <!-- Window Bar -->
              <div class="flex items-center space-x-1.5 pb-2.5 border-b border-slate-100">
                <span class="w-2.5 h-2.5 rounded-full bg-rose-400"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400"></span>
                <span class="text-[9px] text-slate-400 font-mono ml-2">portal-petugas.auth</span>
              </div>

              <!-- Avatar & Status -->
              <div class="flex items-center space-x-3">
                <div class="w-11 h-11 rounded-full bg-gradient-to-br from-[#059669] to-[#10b981] flex items-center justify-center text-white shadow-md">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                  <div class="h-3 w-28 bg-slate-200 rounded"></div>
                  <div class="h-2 w-16 bg-emerald-200 rounded mt-1.5"></div>
                </div>
              </div>

              <!-- Password Mockup Dots -->
              <div class="bg-slate-100 rounded-xl p-2.5 flex items-center justify-between border border-slate-200">
                <div class="flex space-x-1.5">
                  <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                  <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                  <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                  <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                  <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                </div>
                <span class="text-[10px] text-emerald-600 font-mono font-bold">TERKUNCI</span>
              </div>

              <!-- Button Pill -->
              <div class="h-7 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] flex items-center justify-center text-white text-[10px] font-bold shadow-sm">
                Akses Terverifikasi
              </div>
            </div>
          </div>
        </div>

        <!-- 3. Bottom Cover Footer -->
        <div class="relative z-10 pt-4 border-t border-white/15">
          <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-emerald-400/20 border border-emerald-300/30 text-emerald-200 text-[10px] font-bold uppercase tracking-wider mb-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
            <span>Sistem Operasional Lapangan</span>
          </div>
          <p class="text-xs text-slate-300 font-medium leading-relaxed">
            Pondok Pesantren Al-Muhibbin Tambakberas Jombang. Transparansi dan akuntabilitas khidmah Haul Ke-5 Masyayikh.
          </p>
        </div>
      </div>

      <!-- ===================================================================== -->
      <!-- RIGHT SIDE: SIGN IN FORM (CLEAN WHITE, HIGH CONTRAST)                -->
      <!-- ===================================================================== -->
      <div class="w-full lg:w-[52%] xl:w-[51%] p-6 sm:p-10 lg:p-12 xl:p-14 flex flex-col justify-between bg-white">
        
        <!-- Top Action Navigation -->
        <div class="flex items-center justify-between pb-6">
          <div class="inline-flex items-center space-x-2 text-xs font-bold text-emerald-700 font-mono uppercase tracking-wider">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span>Portal Petugas</span>
          </div>

          <router-link
            to="/"
            class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold transition shadow-xs group"
          >
            <span class="group-hover:-translate-x-0.5 transition-transform">&larr;</span>
            <span>Beranda Utama</span>
          </router-link>
        </div>

        <!-- Center Form Container -->
        <div class="max-w-md w-full mx-auto space-y-6">
          <!-- Title & Subtitle -->
          <div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
              SIGN IN
            </h1>
            <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">
              Masukkan username atau email dan kata sandi Anda untuk masuk ke sistem administrasi.
            </p>
          </div>

          <!-- Error Alert Banner -->
          <div
            v-if="error"
            class="p-3.5 bg-rose-50 border border-rose-200 text-rose-700 text-xs rounded-2xl flex items-start space-x-2.5 animate-in fade-in"
          >
            <svg class="w-4 h-4 text-rose-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div class="flex-1 font-medium">{{ error }}</div>
          </div>

          <!-- Main Login Form -->
          <form @submit.prevent="handleLogin" class="space-y-4 text-xs">
            <!-- Field 1: Email / Username -->
            <div>
              <label class="block font-bold text-slate-700 mb-1.5 text-xs">
                Email atau Username *
              </label>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <input
                  v-model="form.login"
                  type="text"
                  required
                  placeholder="superadmin@rojabiyyah.id atau username"
                  class="w-full pl-10 pr-4 py-2.5 sm:py-3 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition text-xs font-medium"
                />
              </div>
            </div>

            <!-- Field 2: Password -->
            <div>
              <div class="flex items-center justify-between mb-1.5">
                <label class="block font-bold text-slate-700 text-xs">
                  Kata Sandi *
                </label>
                <router-link to="/register-account" class="text-[11px] font-semibold text-emerald-600 hover:text-emerald-700">
                  Lupa sandi?
                </router-link>
              </div>
              <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <input
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  required
                  placeholder="Masukkan kata sandi..."
                  class="w-full pl-10 pr-11 py-2.5 sm:py-3 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-500/20 transition text-xs font-medium"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-600 transition"
                  title="Tampilkan / Sembunyikan Sandi"
                >
                  <svg v-if="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  <svg v-else class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l18 18"/></svg>
                </button>
              </div>
            </div>

            <!-- Remember Me (Vristo Checkbox Position) -->
            <div class="flex items-center justify-between text-xs pt-0.5">
              <label class="flex items-center space-x-2 cursor-pointer select-none">
                <input
                  v-model="rememberMe"
                  type="checkbox"
                  class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 border-slate-300"
                />
                <span class="text-slate-600 font-medium text-[11px]">Ingat saya di perangkat ini</span>
              </label>
            </div>

            <!-- Submit Button (High-Contrast Emerald-Teal Gradient) -->
            <button
              type="submit"
              :disabled="loading"
              class="w-full py-3 sm:py-3.5 px-4 bg-gradient-to-r from-[#059669] via-[#047857] to-[#10b981] hover:brightness-105 text-white font-extrabold text-xs uppercase tracking-wider rounded-xl shadow-lg shadow-emerald-600/30 transition transform hover:-translate-y-0.5 active:scale-95 flex items-center justify-center space-x-2 disabled:opacity-50 mt-2"
            >
              <span v-if="loading" class="w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
              <span v-else class="flex items-center space-x-2">
                <span>MASUK KE SISTEM</span>
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
              </span>
            </button>
          </form>

          <!-- OR Divider (Vristo Style) -->
          <div class="relative flex items-center justify-center my-4">
            <div class="absolute inset-0 flex items-center">
              <div class="w-full border-t border-slate-200"></div>
            </div>
            <div class="relative bg-white px-3 text-[10px] uppercase font-bold text-slate-400 tracking-wider">
              OR
            </div>
          </div>

          <!-- Quick Test Demo & Fast Action Buttons -->
          <div>
            <div class="flex items-center justify-between mb-2">
              <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400 font-mono">Uji Coba Kredensial Cepat:</span>
              <span class="text-[10px] text-slate-400">1-Klik Isi Form</span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-[11px]">
              <button
                type="button"
                @click="quickFill('superadmin@rojabiyyah.id', 'password123')"
                class="py-2 px-3 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:border-emerald-300 border border-slate-200 text-slate-700 hover:text-emerald-800 font-semibold flex items-center justify-center space-x-1.5 transition active:scale-95 shadow-2xs group"
              >
                <span class="w-2 h-2 rounded-full bg-emerald-500 group-hover:scale-125 transition-transform"></span>
                <span>Super Admin</span>
              </button>
              <button
                type="button"
                @click="quickFill('ahmad', 'password123')"
                class="py-2 px-3 rounded-xl bg-slate-50 hover:bg-emerald-50 hover:border-emerald-300 border border-slate-200 text-slate-700 hover:text-emerald-800 font-semibold flex items-center justify-center space-x-1.5 transition active:scale-95 shadow-2xs group"
              >
                <span class="w-2 h-2 rounded-full bg-amber-500 group-hover:scale-125 transition-transform"></span>
                <span>Anggota (Ahmad)</span>
              </button>
            </div>
          </div>

          <!-- Registration Links -->
          <div class="pt-2 text-center text-xs text-slate-500 space-y-1.5">
            <p>
              Belum memiliki akun login?
              <router-link to="/register-account" class="text-emerald-700 hover:text-emerald-800 font-bold ml-1 hover:underline">
                Daftar Akun Anggota &rarr;
              </router-link>
            </p>
            <p class="text-[11px] text-slate-400">
              Atau isi formulir anggota relawan:
              <router-link to="/register-member" class="text-slate-600 hover:text-emerald-700 font-semibold ml-1 underline">
                Formulir Panitia
              </router-link>
            </p>
          </div>
        </div>

        <!-- Bottom Copyright -->
        <div class="pt-6 text-center text-[11px] text-slate-400 font-mono">
          {{ settingsStore.settings?.footer_copyright || '© 2026 Media Rojabiyyah. All Rights Reserved.' }}
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { useAuthStore } from '../../stores/auth';
import { useSettingsStore } from '../../stores/settings';

const router = useRouter();
const route = useRoute();
const authStore = useAuthStore();
const settingsStore = useSettingsStore();

const form = ref({ login: '', password: '' });
const rememberMe = ref(true);
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
    error.value = err.response?.data?.message || 'Username/email atau kata sandi tidak sesuai.';
  } finally {
    loading.value = false;
  }
};

onMounted(() => {
  settingsStore.fetchSettings();
});
</script>
