<template>
  <div class="min-h-screen bg-white text-white flex flex-col font-sans selection:bg-[#059669] selection:text-white">
    <!-- Top Navbar -->
    <header class="w-full bg-white/90 backdrop-blur-md border-b border-emerald-200 sticky top-0 z-40">
      <div class="max-w-4xl mx-auto px-4 sm:px-6 h-20 flex items-center justify-between">
        <router-link to="/" class="flex items-center space-x-3.5 group">
          <div class="w-10 h-10 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-center shadow-lg shadow-black/40 group-hover:border-[#10b981] transition">
            <svg class="w-5 h-5 text-[#34d399]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          </div>
          <div>
            <div class="font-extrabold text-sm sm:text-base tracking-wider uppercase text-slate-900 flex items-center space-x-1.5">
              <span>MEDIA ROJABIYYAH</span>
            </div>
            <div class="text-[11px] font-semibold text-emerald-700">Portal Formulir Pendaftaran Anggota &amp; Relawan</div>
          </div>
        </router-link>

        <div class="flex items-center space-x-2.5">
          <router-link
            to="/"
            class="hidden sm:inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-[#101728] hover:bg-slate-100 border border-slate-300/60 text-slate-700 text-xs font-semibold transition"
          >
            <span>&larr;</span>
            <span>Beranda</span>
          </router-link>

          <button
            @click="shareLink"
            class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-[#101728] hover:bg-slate-100 border border-slate-300/60 text-slate-700 text-xs font-semibold transition"
          >
            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg>
            <span>Bagikan</span>
          </button>

          <button
            @click="showLoginModal = true"
            class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-xs font-bold transition shadow-md"
          >
            <span>Masuk Petugas</span>
          </button>
        </div>
      </div>
    </header>

    <!-- Content Container -->
    <main class="flex-1 max-w-4xl w-full mx-auto px-4 py-8 sm:py-12">
      <!-- Success Banner -->
      <div v-if="successData" class="mb-8 p-6 bg-[#0f1d2f] border border-teal-500/60 rounded-3xl text-center space-y-3 shadow-sm">
        <div class="w-12 h-12 rounded-full bg-teal-500/20 text-teal-400 flex items-center justify-center mx-auto">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <h3 class="text-lg font-bold text-slate-900">Alhamdulillah, Pendaftaran Berhasil!</h3>
        <p class="text-xs text-slate-700">
          Terima kasih <strong>{{ successData.name }}</strong> telah mendaftar sebagai panitia Rojabiyyah 2026.
        </p>
        <div class="inline-block px-4 py-2 bg-teal-950/80 rounded-xl border border-teal-600/50 text-teal-300 font-mono font-bold text-sm">
          Nomor Anggota: {{ successData.member_no }}
        </div>
        <div class="pt-2">
          <button @click="resetForm" class="text-xs text-slate-500 hover:text-white underline">
            Daftarkan Anggota Lainnya
          </button>
        </div>
      </div>

      <!-- Registration Card -->
      <div v-else class="bg-emerald-50 border border-emerald-200 rounded-3xl p-6 sm:p-10 shadow-sm relative">
        <!-- Top Badge -->
        <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-emerald-950/80 border border-emerald-200 text-emerald-700 text-xs font-semibold mb-4">
          <span>✨</span>
          <span>Formulir Pendataan Anggota &amp; Divisi</span>
        </div>

        <h2 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight mb-2">
          Formulir Registrasi Anggota
        </h2>
        <p class="text-xs sm:text-sm text-slate-500 leading-relaxed mb-8">
          Silakan isi formulir di bawah ini dengan lengkap untuk pendataan penugasan panitia, pendidikan, domisili, serta ukuran seragam panitia. Anggota <strong class="text-amber-400 font-bold">tidak diwajibkan memiliki akun login</strong>, formulir ini murni untuk pendataan panitia.
        </p>

        <!-- Error Alert -->
        <div v-if="error" class="mb-6 p-3 bg-rose-950/60 border border-rose-800 text-rose-300 text-xs rounded-xl">
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
              class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-rose-500 transition"
            />
          </div>

          <!-- Divisi & Pendidikan -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1.5">Divisi / Bidang Tugas Panitia</label>
              <select
                v-model="form.division_id"
                class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-rose-500 transition"
              >
                <option :value="null">Pilih Bidang / Divisi</option>
                <option v-for="d in options.divisions" :key="d.id" :value="d.id">{{ d.name }}</option>
              </select>
            </div>

            <div>
              <label class="block font-semibold text-slate-700 mb-1.5">Pendidikan / Kampus / Sekolah</label>
              <select
                v-model="form.education"
                class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-rose-500 transition"
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
                class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-rose-500 transition"
              />
            </div>

            <div>
              <label class="block font-semibold text-slate-700 mb-1.5">Domisili / Pondok / Asrama</label>
              <select
                v-model="form.domicile"
                class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-rose-500 transition"
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
                  'py-3 px-3 rounded-xl font-bold flex items-center justify-center space-x-1.5 border transition',
                  form.uniform_size === size
                    ? 'bg-[#0284c7] text-white border-[#38bdf8] shadow-lg shadow-sky-900/50'
                    : 'bg-white text-slate-700 border-slate-300 hover:border-slate-500'
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
                  class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-rose-500 transition pl-10"
                />
                <span class="absolute left-3.5 top-3.5 text-slate-500 font-semibold text-xs">
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                </span>
              </div>
            </div>

            <div>
              <label class="block font-semibold text-slate-700 mb-1.5">Wilayah Operasional (Opsional)</label>
              <select
                v-model="form.region_id"
                class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-rose-500 transition"
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
              class="w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-rose-500 transition"
            ></textarea>
          </div>

          <!-- Prompt: Ingin Buat Akun Login? -->
          <div class="p-4 sm:p-5 rounded-2xl bg-white border border-emerald-200 flex flex-col sm:flex-row items-center justify-between gap-4">
            <div class="flex items-center space-x-3.5">
              <div class="w-10 h-10 rounded-xl bg-emerald-950/60 border border-emerald-800/60 text-[#34d399] flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
              </div>
              <div>
                <h4 class="font-bold text-slate-900 text-xs">Ingin membuat Akun Login ke Portal Anggota?</h4>
                <p class="text-[11px] text-slate-700">Pendaftaran akun pengguna dipisahkan dari formulir data di atas.</p>
              </div>
            </div>

            <router-link
              to="/register-account"
              class="px-4 py-2 bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold rounded-xl whitespace-nowrap shadow-md transition"
            >
              Daftar Akun Login &rarr;
            </router-link>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="submitting"
            class="w-full py-4 px-6 bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold text-sm rounded-2xl shadow-sm shadow-emerald-950/60 flex items-center justify-center space-x-2 transition disabled:opacity-50"
          >
            <span>{{ submitting ? 'Mengirim Data...' : 'Kirimkan Data Pendaftaran' }}</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
          </button>
        </form>
      </div>
    </main>

    <!-- Footer -->
    <footer class="h-14 border-t border-emerald-200 bg-white flex items-center justify-center px-4 text-center text-xs text-slate-500">
      &copy; 2026 Aplikasi Administrasi Haul Ke-5 &amp; Rojabiyyah 2026.
    </footer>

    <!-- Login Modal -->
    <LoginModal :show="showLoginModal" @close="showLoginModal = false" />
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import LoginModal from '../../components/LoginModal.vue';
import api from '../../services/api';

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
});
</script>

