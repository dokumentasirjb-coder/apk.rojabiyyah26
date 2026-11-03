<template>
  <div class="min-h-screen bg-[#f8fafc] text-slate-800 flex flex-col font-sans selection:bg-[#059669] selection:text-white">
    <!-- Top Navbar -->
    <header class="w-full bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40 shadow-sm">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between">
        <router-link to="/" class="flex items-center space-x-3 group">
          <div class="h-10 w-10 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
            <img
              :src="settingsStore.settings?.logo_url || '/logo.png'"
              alt="Logo RJB"
              class="h-10 w-10 object-contain drop-shadow-sm"
            />
          </div>
          <div>
            <div class="font-black text-sm sm:text-base tracking-wider uppercase text-slate-900 flex items-center space-x-1.5">
              <span>{{ settingsStore.settings?.app_name || 'MEDIA ROJABIYYAH' }}</span>
            </div>
            <div class="text-[11px] font-semibold text-emerald-700">Portal Formulir Pendaftaran Anggota &amp; Relawan</div>
          </div>
        </router-link>

        <div class="flex items-center space-x-2 sm:space-x-2.5">
          <router-link
            to="/"
            class="hidden sm:inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 text-xs font-semibold transition"
          >
            <span>&larr;</span>
            <span>Beranda</span>
          </router-link>

          <button
            @click="shareLink"
            class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 text-xs font-semibold transition active:scale-95"
          >
            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
            <span>Bagikan</span>
          </button>

          <router-link
            to="/login"
            class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-xs font-bold transition shadow-sm"
          >
            <span>Masuk Petugas</span>
          </router-link>
        </div>
      </div>
    </header>

    <!-- Content Container -->
    <main class="flex-1 max-w-4xl w-full mx-auto px-4 py-8 sm:py-12">
      <!-- Success Banner -->
      <div v-if="successData" class="mb-8 p-6 sm:p-8 bg-emerald-50 border border-emerald-300 rounded-3xl text-center space-y-3 shadow-md">
        <div class="w-14 h-14 rounded-full bg-emerald-100 text-emerald-600 border border-emerald-200 flex items-center justify-center mx-auto shadow-sm">
          <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h3 class="text-xl font-bold text-slate-900">Alhamdulillah, Pendaftaran Berhasil!</h3>
        <p class="text-sm text-slate-600">
          Terima kasih <strong>{{ successData.name }}</strong> telah mendaftar sebagai panitia Rojabiyyah 2026.
        </p>
        <div class="inline-block px-5 py-2.5 bg-emerald-100 rounded-2xl border border-emerald-300 text-emerald-900 font-mono font-bold text-base shadow-sm">
          Nomor Anggota: {{ successData.member_no }}
        </div>
        <div class="pt-3">
          <button @click="resetForm" class="text-xs text-emerald-700 hover:text-emerald-800 font-semibold underline">
            + Daftarkan Anggota Lainnya
          </button>
        </div>
      </div>

      <!-- Registration Card -->
      <div v-else class="bg-white border border-emerald-200 rounded-3xl p-6 sm:p-10 shadow-sm relative">
        <!-- Top Badge -->
        <div class="inline-flex items-center space-x-1.5 px-3.5 py-1 rounded-full bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold mb-4 shadow-xs">
          <span class="text-emerald-600">✨</span>
          <span>Formulir Pendataan Anggota &amp; Divisi</span>
        </div>

        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-2">
          Formulir Registrasi Anggota
        </h2>
        <p class="text-xs sm:text-sm text-slate-600 leading-relaxed mb-8">
          Silakan isi formulir di bawah ini dengan lengkap untuk pendataan penugasan panitia, pendidikan, domisili, serta ukuran seragam panitia. Anggota <span class="bg-amber-100 text-amber-900 border border-amber-300 font-bold px-1.5 py-0.5 rounded">tidak diwajibkan memiliki akun login</span>, formulir ini murni untuk pendataan panitia.
        </p>

        <!-- Error Alert -->
        <div v-if="error" class="mb-6 p-3.5 bg-rose-50 border border-rose-300 text-rose-700 text-xs rounded-xl font-medium">
          {{ error }}
        </div>

        <!-- Form -->
        <form @submit.prevent="submitRegistration" class="space-y-5 text-xs">
          <!-- Nama Lengkap -->
          <div>
            <label class="block font-semibold text-slate-700 mb-1.5">Nama Lengkap *</label>
            <input
              v-model="form.name"
              type="text"
              required
              placeholder="Contoh: M. Nasrul Wathon / Azzam Wahyu"
              class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
            />
          </div>

          <!-- Divisi & Pendidikan -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1.5">Divisi / Bidang Tugas Panitia</label>
              <select
                v-model="form.division_id"
                class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
              >
                <option :value="null">Pilih Bidang / Divisi</option>
                <option v-for="d in options.divisions" :key="d.id" :value="d.id">{{ d.name }}</option>
              </select>
            </div>

            <div>
              <label class="block font-semibold text-slate-700 mb-1.5">Pendidikan / Kampus / Sekolah</label>
              <select
                v-model="form.education"
                class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
              >
                <option value="">Pilih Jenjang / Kampus</option>
                <option v-for="edu in options.educations" :key="edu" :value="edu">{{ edu }}</option>
              </select>
            </div>
          </div>

          <!-- Kelas & Domisili -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1.5">Kelas / Tingkat / Angkatan</label>
              <input
                v-model="form.class"
                type="text"
                placeholder="Contoh: XII IPA 1 / Semester 4"
                class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
              />
            </div>

            <div>
              <label class="block font-semibold text-slate-700 mb-1.5">Domisili / Pondok / Asrama</label>
              <select
                v-model="form.domicile"
                class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
              >
                <option value="">Pilih Domisili / Pondok</option>
                <option v-for="dom in options.domiciles" :key="dom" :value="dom">{{ dom }}</option>
              </select>
            </div>
          </div>

          <!-- Ukuran Seragam Selector -->
          <div>
            <label class="block font-semibold text-slate-700 mb-2">Ukuran Seragam Panitia / Organisasi</label>
            <div class="grid grid-cols-3 sm:grid-cols-6 gap-2 sm:gap-3">
              <button
                v-for="size in options.uniform_sizes"
                :key="size"
                type="button"
                @click="form.uniform_size = size"
                :class="[
                  'py-3 px-3 rounded-xl font-bold flex items-center justify-center space-x-1.5 border transition cursor-pointer',
                  form.uniform_size === size
                    ? 'bg-emerald-600 text-white border-emerald-600 shadow-md shadow-emerald-700/20'
                    : 'bg-white text-slate-700 border-slate-300 hover:border-emerald-400'
                ]"
              >
                <svg class="w-4 h-4 opacity-70" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20.38 3.46L16 2a4 4 0 01-8 0L3.62 3.46a2 2 0 00-1.34 2.23l.58 3.47a1 1 0 00.99.84H6v10a2 2 0 002 2h8a2 2 0 002-2V10h2.15a1 1 0 00.99-.84l.58-3.47a2 2 0 00-1.34-2.23z"/></svg>
                <span>{{ size }}</span>
              </button>
            </div>
          </div>

          <!-- No WA & Wilayah Operasional -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1.5">No. WhatsApp (No WA) *</label>
              <div class="relative">
                <input
                  v-model="form.phone_wa"
                  type="text"
                  required
                  placeholder="812xxxxxxxx"
                  class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition pl-10"
                />
                <span class="absolute left-3.5 top-3.5 text-slate-400 font-semibold text-xs">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </span>
              </div>
            </div>

            <div>
              <label class="block font-semibold text-slate-700 mb-1.5">Wilayah Operasional (Opsional)</label>
              <select
                v-model="form.region_id"
                class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
              >
                <option :value="null">Pilih Wilayah Operasional</option>
                <option v-for="r in options.regions" :key="r.id" :value="r.id">{{ r.name }}</option>
              </select>
            </div>
          </div>

          <!-- Alamat Lengkap / Keterangan Tambahan -->
          <div>
            <label class="block font-semibold text-slate-700 mb-1.5">Alamat Lengkap / Keterangan Tambahan</label>
            <textarea
              v-model="form.notes"
              rows="3"
              placeholder="Alamat asal atau catatan khusus lainnya (opsional)"
              class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
            ></textarea>
          </div>

          <!-- Prompt: Ingin Buat Akun Login? -->
          <div class="p-4 sm:p-5 rounded-2xl bg-emerald-50/70 border border-emerald-200 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-3.5">
              <div class="w-10 h-10 rounded-xl bg-emerald-100 border border-emerald-300 text-emerald-700 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
              </div>
              <div>
                <h4 class="font-bold text-slate-900 text-xs">Ingin membuat Akun Login ke Portal Anggota?</h4>
                <p class="text-[11px] text-slate-600">Pendaftaran akun pengguna dipisahkan dari formulir data di atas.</p>
              </div>
            </div>

            <router-link
              to="/register-account"
              class="px-4 py-2 bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold rounded-xl whitespace-nowrap shadow-sm transition text-xs"
            >
              Daftar Akun Login &rarr;
            </router-link>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="submitting"
            class="w-full py-4 px-6 bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold text-sm rounded-2xl shadow-md shadow-emerald-700/20 flex items-center justify-center space-x-2 transition disabled:opacity-50 cursor-pointer"
          >
            <span>{{ submitting ? 'Mengirim Data...' : 'Kirimkan Data Pendaftaran' }}</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
          </button>
        </form>
      </div>
    </main>

    <!-- Footer -->
    <footer class="h-14 border-t border-slate-200 bg-white flex items-center justify-center px-4 text-center text-xs text-slate-500">
      &copy; 2026 Aplikasi Administrasi Haul Ke-5 &amp; Rojabiyyah 2026.
    </footer>

    <!-- Login Modal -->
    <LoginModal :show="showLoginModal" @close="showLoginModal = false" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import LoginModal from '../../components/LoginModal.vue';
import { useSettingsStore } from '../../stores/settings';
import api from '../../services/api';

const settingsStore = useSettingsStore();
const showLoginModal = ref(false);
const submitting = ref(false);
const error = ref('');
const successData = ref(null);

const options = ref({
  divisions: [],
  regions: [],
  educations: [],
  domiciles: [],
  uniform_sizes: ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'],
});

const form = ref({
  name: '',
  division_id: null,
  education: '',
  class: '',
  domicile: '',
  uniform_size: 'L',
  phone_wa: '',
  region_id: null,
  notes: '',
});

const fetchOptions = async () => {
  try {
    const res = await api.get('/public/options');
    options.value = res.data;
  } catch (e) {
    //
  }
};

const submitRegistration = async () => {
  submitting.value = true;
  error.value = '';
  try {
    const res = await api.post('/public/register-member', form.value);
    successData.value = res.data;
  } catch (err) {
    error.value = err.response?.data?.message || 'Gagal mengirimkan formulir pendaftaran.';
  } finally {
    submitting.value = false;
  }
};

const resetForm = () => {
  successData.value = null;
  form.value = {
    name: '',
    division_id: null,
    education: '',
    class: '',
    domicile: '',
    uniform_size: 'L',
    phone_wa: '',
    region_id: null,
    notes: '',
  };
};

const shareLink = () => {
  const url = window.location.href;
  navigator.clipboard.writeText(url);
  alert('Tautan formulir pendaftaran berhasil disalin!');
};

onMounted(() => {
  fetchOptions();
  if (!settingsStore.settings?.app_name) {
    settingsStore.fetchSettings();
  }
});
</script>

