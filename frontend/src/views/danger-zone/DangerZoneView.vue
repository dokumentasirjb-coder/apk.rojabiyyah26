<template>
  <DashboardLayout>
    <div class="space-y-6 max-w-5xl mx-auto">
      <!-- Title Header -->
      <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
          <div class="flex items-center space-x-3.5">
            <div class="w-12 h-12 rounded-2xl bg-rose-950/80 border border-rose-800/80 text-rose-400 flex items-center justify-center flex-shrink-0 shadow-inner">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <div>
              <div class="flex items-center space-x-2">
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Pusat Pembersihan Data (Danger Zone)</h2>
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black uppercase bg-rose-950 text-rose-400 border border-rose-800">
                  Super Admin Only
                </span>
              </div>
              <p class="text-xs text-slate-500 mt-0.5">
                Kelola pembersihan data operasional, inventaris, anggota relawan, maupun reset total sistem sebelum hari-H acara.
              </p>
            </div>
          </div>

          <router-link
            to="/settings"
            class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold transition self-start sm:self-auto"
          >
            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span>Kembali ke Pengaturan</span>
          </router-link>
        </div>
      </div>

      <!-- Security Notice Banner -->
      <div class="p-5 bg-rose-950/30 border border-rose-800/60 rounded-3xl text-rose-200 text-xs space-y-2 shadow-lg">
        <h3 class="font-bold text-sm text-rose-400 flex items-center space-x-2">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <span>Prosedur Keamanan Tingkat Tinggi (Sesuai PRD v2.0):</span>
        </h3>
        <ul class="list-disc list-inside space-y-1 text-slate-700">
          <li>Sistem akan membuat <strong class="text-rose-300">Snapshot Cadangan (Backup JSON Otomatis)</strong> di server secara instan sebelum tindakan hapus dijalankan.</li>
          <li>Data akun <strong>Super Admin &amp; Admin Pengelola</strong>, pengaturan branding, dan <strong>Audit Log TIDAK akan pernah terhapus</strong>.</li>
          <li>Pilih modul yang ingin dibersihkan secara fleksibel atau gunakan tombol <strong>Reset Total</strong>.</li>
          <li>Wajib memasukkan kata sandi akun Anda dan mengetik frasa konfirmasi: <code class="bg-rose-900/60 text-rose-200 px-1.5 py-0.5 rounded font-bold font-mono">RESET OPERASIONAL</code> atau <code class="bg-rose-900/60 text-rose-200 px-1.5 py-0.5 rounded font-bold font-mono">RESET</code>.</li>
        </ul>
      </div>

      <!-- Success Notification Banner -->
      <div v-if="successResult" class="p-5 bg-emerald-950/40 border border-emerald-700/60 rounded-3xl text-emerald-200 text-xs space-y-2 shadow-lg">
        <div class="flex items-center space-x-2 text-emerald-400 font-bold text-sm">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <span>Pembersihan Data Berhasil Dilakukan!</span>
        </div>
        <p class="text-slate-700">
          {{ successResult.message }}
        </p>
        <div class="flex flex-wrap items-center gap-2 pt-1 font-mono text-[11px]">
          <span class="px-2 py-1 bg-emerald-900/40 rounded border border-emerald-700 text-emerald-300">
            File Snapshot: {{ successResult.backup_file }}
          </span>
        </div>
      </div>

      <!-- Main Action Card -->
      <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-200 space-y-6 text-xs">
        <!-- Top Toolbar -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-200 gap-3">
          <div>
            <h3 class="text-sm font-bold text-slate-900">Pilih Modul yang Akan Direset &amp; Pratinjau Dampak:</h3>
            <p class="text-[11px] text-slate-500 mt-0.5">Centang modul yang ingin dihapus datanya secara permanen.</p>
          </div>

          <div class="flex items-center space-x-2">
            <button
              type="button"
              @click="selectAllModules"
              class="px-3 py-1.5 rounded-xl bg-rose-950/60 hover:bg-rose-900 border border-rose-700/70 text-rose-300 text-xs font-bold transition flex items-center space-x-1.5"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
              <span>Pilih Semua (Reset Total)</span>
            </button>
            <button
              type="button"
              @click="clearModuleSelection"
              class="px-3 py-1.5 rounded-xl bg-white hover:bg-slate-100 border border-slate-300 text-slate-500 text-xs font-semibold transition"
            >
              Hapus Pilihan
            </button>
          </div>
        </div>

        <!-- Module Checkboxes Grid (7 Modules) -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <!-- 1. DATA ANGGOTA RELAWAN -->
          <label
            class="p-4 rounded-2xl border flex items-start space-x-3 cursor-pointer transition select-none"
            :class="selectedModules.includes('members') ? 'border-rose-500 bg-rose-950/30' : 'border-slate-200 bg-slate-50 hover:border-slate-300'"
          >
            <input type="checkbox" value="members" v-model="selectedModules" class="mt-1 rounded text-rose-600 focus:ring-rose-500 bg-white border-slate-300" />
            <div class="flex-1">
              <div class="flex items-center justify-between">
                <span class="font-bold text-white text-xs block">Data Anggota Relawan &amp; Akun Anggota</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-rose-900/60 text-rose-300 border border-rose-700/60">
                  {{ previewCounts.members_count || 0 }} Anggota
                </span>
              </div>
              <span class="text-slate-500 text-[11px] block mt-1 leading-relaxed">
                Akan menghapus semua akun relawan role 'anggota', biodata profil, serta penugasan terkait. <strong class="text-rose-400">Akun Super Admin &amp; Admin tetap terlindungi.</strong>
              </span>
            </div>
          </label>

          <!-- 2. DATA BARANG & INVENTARIS GUDANG -->
          <label
            class="p-4 rounded-2xl border flex items-start space-x-3 cursor-pointer transition select-none"
            :class="selectedModules.includes('inventory') ? 'border-rose-500 bg-rose-950/30' : 'border-slate-200 bg-slate-50 hover:border-slate-300'"
          >
            <input type="checkbox" value="inventory" v-model="selectedModules" class="mt-1 rounded text-rose-600 focus:ring-rose-500 bg-white border-slate-300" />
            <div class="flex-1">
              <div class="flex items-center justify-between">
                <span class="font-bold text-white text-xs block">Katalog Barang &amp; Inventaris Gudang</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-indigo-900/60 text-indigo-300 border border-indigo-700/60">
                  {{ previewCounts.items_count || 0 }} Barang
                </span>
              </div>
              <span class="text-slate-500 text-[11px] block mt-1 leading-relaxed">
                Akan menghapus seluruh master data barang logistik gudang, stok kuantitas, dan riwayat sirkulasi peminjaman.
              </span>
            </div>
          </label>

          <!-- 3. SIRKULASI PEMINJAMAN -->
          <label
            class="p-4 rounded-2xl border flex items-start space-x-3 cursor-pointer transition select-none"
            :class="selectedModules.includes('loans') ? 'border-rose-500 bg-rose-950/30' : 'border-slate-200 bg-slate-50 hover:border-slate-300'"
          >
            <input type="checkbox" value="loans" v-model="selectedModules" class="mt-1 rounded text-rose-600 focus:ring-rose-500 bg-white border-slate-300" />
            <div class="flex-1">
              <div class="flex items-center justify-between">
                <span class="font-bold text-white text-xs block">Sirkulasi Peminjaman Logistik</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-amber-900/60 text-amber-300 border border-amber-700/60">
                  {{ previewCounts.loans_count || 0 }} Peminjaman
                </span>
              </div>
              <span class="text-slate-500 text-[11px] block mt-1 leading-relaxed">
                Akan menghapus data formulir permohonan peminjaman, surat keluar/masuk, dan mereset status reserved stok gudang kembali ke 0.
              </span>
            </div>
          </label>

          <!-- 4. DATA LEMBAGA MITRA LUAR -->
          <label
            class="p-4 rounded-2xl border flex items-start space-x-3 cursor-pointer transition select-none"
            :class="selectedModules.includes('partners') ? 'border-rose-500 bg-rose-950/30' : 'border-slate-200 bg-slate-50 hover:border-slate-300'"
          >
            <input type="checkbox" value="partners" v-model="selectedModules" class="mt-1 rounded text-rose-600 focus:ring-rose-500 bg-white border-slate-300" />
            <div class="flex-1">
              <div class="flex items-center justify-between">
                <span class="font-bold text-white text-xs block">Data Lembaga / Mitra Luar</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-teal-900/60 text-teal-300 border border-teal-700/60">
                  {{ previewCounts.partners_count || 0 }} Lembaga
                </span>
              </div>
              <span class="text-slate-500 text-[11px] block mt-1 leading-relaxed">
                Akan menghapus seluruh data mitra eksternal penyedia aset dan melepaskan tautan mitra pada inventaris.
              </span>
            </div>
          </label>

          <!-- 5. LAPORAN LAPANGAN & KENDALA POSKO -->
          <label
            class="p-4 rounded-2xl border flex items-start space-x-3 cursor-pointer transition select-none"
            :class="selectedModules.includes('reports') ? 'border-rose-500 bg-rose-950/30' : 'border-slate-200 bg-slate-50 hover:border-slate-300'"
          >
            <input type="checkbox" value="reports" v-model="selectedModules" class="mt-1 rounded text-rose-600 focus:ring-rose-500 bg-white border-slate-300" />
            <div class="flex-1">
              <div class="flex items-center justify-between">
                <span class="font-bold text-white text-xs block">Laporan Lapangan &amp; Kendala Posko</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-sky-900/60 text-sky-300 border border-sky-700/60">
                  {{ previewCounts.reports_count || 0 }} Laporan
                </span>
              </div>
              <span class="text-slate-500 text-[11px] block mt-1 leading-relaxed">
                Akan menghapus seluruh laporan kendala posko, dokumentasi foto lapangan, dan riwayat tindak lanjut respon.
              </span>
            </div>
          </label>

          <!-- 6. TRANSAKSI KAS KEUANGAN -->
          <label
            class="p-4 rounded-2xl border flex items-start space-x-3 cursor-pointer transition select-none"
            :class="selectedModules.includes('cash') ? 'border-rose-500 bg-rose-950/30' : 'border-slate-200 bg-slate-50 hover:border-slate-300'"
          >
            <input type="checkbox" value="cash" v-model="selectedModules" class="mt-1 rounded text-rose-600 focus:ring-rose-500 bg-white border-slate-300" />
            <div class="flex-1">
              <div class="flex items-center justify-between">
                <span class="font-bold text-white text-xs block">Transaksi Kas Keuangan</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-emerald-900/60 text-emerald-300 border border-emerald-700/60">
                  {{ previewCounts.cash_transactions_count || 0 }} Transaksi
                </span>
              </div>
              <span class="text-slate-500 text-[11px] block mt-1 leading-relaxed">
                Akan mereset seluruh catatan pemasukan uang, infaq, belanja operasional, dan saldo kas kepanitiaan.
              </span>
            </div>
          </label>

          <!-- 7. PENUGASAN PERSONIL POSKO -->
          <label
            class="p-4 rounded-2xl border flex items-start space-x-3 cursor-pointer transition select-none md:col-span-2"
            :class="selectedModules.includes('assignments') ? 'border-rose-500 bg-rose-950/30' : 'border-slate-200 bg-slate-50 hover:border-slate-300'"
          >
            <input type="checkbox" value="assignments" v-model="selectedModules" class="mt-1 rounded text-rose-600 focus:ring-rose-500 bg-white border-slate-300" />
            <div class="flex-1">
              <div class="flex items-center justify-between">
                <span class="font-bold text-white text-xs block">Jadwal Penugasan Personil Posko</span>
                <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-purple-900/60 text-purple-300 border border-purple-700/60">
                  {{ previewCounts.assignments_count || 0 }} Jadwal
                </span>
              </div>
              <span class="text-slate-500 text-[11px] block mt-1 leading-relaxed">
                Akan menghapus pembagian jadwal penugasan personil relawan ke posko-posko operasional kegiatan.
              </span>
            </div>
          </label>
        </div>

        <!-- Confirmation & Execution Form -->
        <form @submit.prevent="executeReset" class="p-5 sm:p-6 bg-slate-50 rounded-2xl border border-slate-200 space-y-4">
          <!-- Frasa Konfirmasi -->
          <div>
            <div class="flex items-center justify-between mb-1.5">
              <label class="block font-semibold text-slate-700 text-xs">
                Ketik Frasa Konfirmasi: <span class="text-rose-400 font-bold font-mono">RESET OPERASIONAL</span> atau <span class="text-rose-400 font-bold font-mono">RESET</span>
              </label>
              <button
                type="button"
                @click="confirmationPhrase = 'RESET OPERASIONAL'"
                class="text-[11px] text-sky-400 hover:text-sky-300 underline font-semibold flex items-center space-x-1"
              >
                <span>Klik untuk Isi Otomatis</span>
              </button>
            </div>
            <input
              v-model="confirmationPhrase"
              type="text"
              required
              placeholder="Ketik RESET OPERASIONAL atau klik isi otomatis di atas"
              class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl font-mono text-xs text-slate-800 placeholder-slate-500 focus:ring-2 focus:ring-rose-500 focus:outline-none"
            />
            <p v-if="confirmationPhrase && !isPhraseValid" class="text-[11px] text-rose-400 mt-1">
              Frasa belum sesuai. Harus tepat "RESET OPERASIONAL" atau "RESET".
            </p>
          </div>

          <!-- Password Verifikasi -->
          <div>
            <label class="block font-semibold text-slate-700 text-xs mb-1.5">
              Kata Sandi Super Admin / Akun Anda *
            </label>
            <input
              v-model="password"
              type="password"
              required
              placeholder="Masukkan password akun Anda saat ini"
              class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 placeholder-slate-500 focus:ring-2 focus:ring-rose-500 focus:outline-none"
            />
            <p class="text-[10px] text-slate-500 mt-1">
              Dibutuhkan untuk memverifikasi bahwa Anda adalah pemegang wewenang akun Super Admin.
            </p>
          </div>

          <!-- Error Alert if Any -->
          <div v-if="errorMessage" class="p-3 rounded-xl bg-rose-950/80 border border-rose-800 text-rose-200 text-xs flex items-center space-x-2">
            <svg class="w-4 h-4 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>{{ errorMessage }}</span>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="executing || selectedModules.length === 0 || !isPhraseValid || !password"
            class="w-full py-3.5 bg-[#a81c3d] hover:bg-[#be123c] text-white font-bold rounded-xl shadow-lg shadow-rose-950/60 transition disabled:opacity-40 disabled:cursor-not-allowed text-xs flex items-center justify-center space-x-2"
          >
            <svg v-if="executing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            <span>{{ executing ? 'Sedang Membuat Backup & Menjalankan Reset...' : `Eksekusi Pembersihan (${selectedModules.length} Modul Terpilih)` }}</span>
          </button>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import DashboardLayout from '../../layouts/DashboardLayout.vue';
import api from '../../services/api';

const ALL_MODULES = ['members', 'inventory', 'partners', 'loans', 'reports', 'cash', 'assignments'];

const previewCounts = ref({});
const selectedModules = ref(['loans', 'reports']);
const confirmationPhrase = ref('');
const password = ref('');
const executing = ref(false);
const errorMessage = ref('');
const successResult = ref(null);

const isPhraseValid = computed(() => {
  const p = confirmationPhrase.value.trim().toUpperCase();
  return p === 'RESET OPERASIONAL' || p === 'RESET';
});

const selectAllModules = () => {
  selectedModules.value = [...ALL_MODULES];
};

const clearModuleSelection = () => {
  selectedModules.value = [];
};

const fetchPreview = async () => {
  try {
    const res = await api.post('/danger-zone/preview');
    previewCounts.value = res.data;
  } catch (e) {
    console.error('Gagal mengambil pratinjau Danger Zone:', e);
  }
};

const executeReset = async () => {
  if (!isPhraseValid.value || !password.value || selectedModules.value.length === 0) return;

  const confirmMsg = `PERINGATAN TINGKAT TINGGI:\n\nAnda akan menghapus data dari ${selectedModules.value.length} modul yang dipilih secara permanen.\nSistem akan membuat file backup snapshot sebelum pembersihan.\n\nLanjutkan eksekusi sekarang?`;
  if (!await window.$confirm(confirmMsg, 'Konfirmasi Pembersihan Data')) return;

  executing.value = true;
  errorMessage.value = '';
  successResult.value = null;

  try {
    const res = await api.post('/danger-zone/execute', {
      modules: selectedModules.value,
      confirmation_phrase: confirmationPhrase.value.trim(),
      password: password.value,
    });

    successResult.value = res.data;
    confirmationPhrase.value = '';
    password.value = '';
    alert('Pembersihan data selesai! ' + res.data.message);
    await fetchPreview();
  } catch (err) {
    errorMessage.value = err.response?.data?.message || 'Gagal mengeksekusi reset. Pastikan kata sandi Anda benar.';
  } finally {
    executing.value = false;
  }
};

onMounted(() => {
  fetchPreview();
});
</script>
