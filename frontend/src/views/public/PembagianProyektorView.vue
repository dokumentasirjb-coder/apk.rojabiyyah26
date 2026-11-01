<template>
  <div class="min-h-screen bg-[#f8fafc] text-slate-800 flex flex-col font-sans selection:bg-emerald-500 selection:text-white">
    <!-- 1. Top Navbar -->
    <header class="w-full bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50 transition-all shadow-sm print:hidden">
      <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between">
        <!-- Brand & Back Button -->
        <div class="flex items-center space-x-2 sm:space-x-4 min-w-0 pr-2">
          <router-link
            to="/"
            class="inline-flex items-center space-x-1.5 px-2.5 sm:px-3 py-1.5 sm:py-2 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-xs font-bold transition active:scale-95 flex-shrink-0 shadow-xs"
            title="Kembali ke Beranda Utama"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            <span class="hidden sm:inline">Beranda</span>
          </router-link>

          <div class="h-6 w-px bg-slate-200 hidden sm:block"></div>

          <router-link to="/" class="flex items-center space-x-2 sm:space-x-3 group min-w-0">
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl sm:rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-center shadow-xs overflow-hidden group-hover:border-emerald-500 transition flex-shrink-0">
              <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div class="min-w-0">
              <div class="font-extrabold text-xs sm:text-sm tracking-wider uppercase text-slate-900 flex items-center space-x-1.5 truncate">
                <span class="truncate">PEMBAGIAN PROYEKTOR</span>
                <span class="px-1.5 py-0.2 rounded text-[9px] bg-[#f97316] text-white font-mono border border-white/20 flex-shrink-0 font-bold">DETAIL</span>
              </div>
              <div class="hidden sm:block text-[11px] font-bold text-emerald-700 tracking-wide truncate">Wilayah, Korwil &amp; Jalur Kabel</div>
            </div>
          </router-link>
        </div>

        <!-- Action Tools -->
        <div class="flex items-center space-x-2.5 sm:space-x-3">
          <router-link
            to="/jadwal-kerja"
            class="hidden md:inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-emerald-700 text-xs font-bold transition active:scale-95 shadow-xs"
          >
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h18"/></svg>
            <span>Buka Jadwal Kerja Tim →</span>
          </router-link>

          <button
            @click="printPage"
            class="inline-flex items-center space-x-1.5 px-3.5 sm:px-4 py-2 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:brightness-105 text-white text-xs font-bold shadow-md shadow-emerald-600/20 transition active:scale-95"
            title="Cetak Lembar Pembagian Proyektor"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
            <span>Cetak PDF</span>
          </button>
        </div>
      </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-grow max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full space-y-8">
      <!-- 2. Header Banner & Title -->
      <section class="relative rounded-3xl overflow-hidden bg-gradient-to-r from-[#059669] to-[#047857] p-6 sm:p-8 shadow-md text-white">
        <div class="relative z-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
          <div class="space-y-3 max-w-3xl">
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-white/20 border border-white/30 text-white text-xs font-bold uppercase tracking-wider backdrop-blur-sm">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
              <span>Format Resmi Google Sheet Haul 2026</span>
            </div>
            <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight">
              Pembagian Team Proyektor, Koordinator Wilayah &amp; Jalur Kabel
            </h1>
            <p class="text-xs sm:text-sm text-emerald-100 leading-relaxed">
              Daftar pembagian titik operator, penanggung jawab proyektor, dan instalasi kabel audio-video terpusat Ponpes Al-Muhibbin Tambakberas.
            </p>
          </div>

          <!-- Quick Stats Counter -->
          <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 flex-shrink-0">
            <div class="p-3.5 rounded-2xl bg-white/15 border border-white/25 backdrop-blur-md">
              <span class="text-[10px] font-mono text-emerald-100 uppercase block font-semibold">Total Titik</span>
              <span class="text-xl sm:text-2xl font-black text-white font-mono">{{ proyektorTeamsList.length }}</span>
              <span class="text-[10px] text-emerald-200 block">Titik Posko</span>
            </div>
            <div class="p-3.5 rounded-2xl bg-white/15 border border-white/25 backdrop-blur-md">
              <span class="text-[10px] font-mono text-emerald-100 uppercase block font-semibold">Total Unit</span>
              <span class="text-xl sm:text-2xl font-black text-white font-mono">{{ totalDevicesCount }}</span>
              <span class="text-[10px] text-emerald-200 block">LCD &amp; Proyektor</span>
            </div>
            <div class="p-3.5 rounded-2xl bg-white/15 border border-white/25 backdrop-blur-md">
              <span class="text-[10px] font-mono text-emerald-100 uppercase block font-semibold">Korwil Lapangan</span>
              <span class="text-xl sm:text-2xl font-black text-white font-mono">{{ uniqueKorwilCount }}</span>
              <span class="text-[10px] text-emerald-200 block">Personil</span>
            </div>
            <div class="p-3.5 rounded-2xl bg-white/15 border border-white/25 backdrop-blur-md">
              <span class="text-[10px] font-mono text-emerald-100 uppercase block font-semibold">Skema Bagan</span>
              <span class="text-xl sm:text-2xl font-black text-white font-mono">{{ proyektorPhotosList.length }}</span>
              <span class="text-[10px] text-emerald-200 block">Foto Bagan</span>
            </div>
          </div>
        </div>
      </section>

      <!-- 3. Bagan & Skema Jalur Proyektor Lapangan (Jika Ada) -->
      <section v-if="proyektorPhotosList.length > 0" class="space-y-3">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <h2 class="text-xs sm:text-sm font-bold text-slate-800 uppercase tracking-wider font-mono">
              Bagan Skema Kabel FO &amp; Denah Posisi Layar Lapangan
            </h2>
          </div>
          <span class="text-[11px] font-mono text-emerald-700 font-bold">{{ proyektorPhotosList.length }} Skema Tersedia</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <div
            v-for="(photo, idx) in proyektorPhotosList"
            :key="idx"
            class="group relative rounded-2xl overflow-hidden bg-white border border-slate-200 shadow-sm cursor-pointer hover:border-emerald-500 transition-all duration-300"
            @click="openLightbox(getPhotoUrl(photo), 'Skema Jalur Proyektor #' + (idx + 1))"
          >
            <div class="h-56 overflow-hidden bg-slate-100">
              <img
                :src="getPhotoUrl(photo)"
                :alt="'Skema Proyektor #' + (idx + 1)"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              />
            </div>
            <div class="p-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
              <div>
                <p class="text-xs font-bold text-slate-900">Skema Proyektor #{{ idx + 1 }}</p>
                <p class="text-[10px] font-mono text-slate-500">Klik untuk melihat peta resolusi tinggi</p>
              </div>
              <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 font-mono text-[10px] font-bold flex items-center space-x-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                <span>Lihat Bagan</span>
              </span>
            </div>
          </div>
        </div>
      </section>

      <!-- 4. Interactive Quick Filter Bar -->
      <section class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm space-y-4 print:hidden">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
          <!-- Search Input -->
          <div class="relative w-full lg:w-96">
            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35"/></svg>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari korwil, petugas, kabel, titik atau nomor proyektor..."
              class="w-full pl-10 pr-4 py-2.5 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:bg-white transition"
            />
          </div>

          <!-- Total Counter -->
          <div class="text-xs text-slate-600 font-mono flex items-center space-x-2">
            <span>Menampilkan:</span>
            <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 font-bold border border-emerald-200">
              {{ filteredTeams.length }} Titik Wilayah
            </span>
          </div>
        </div>

        <!-- Filter Chips: Titik A sampai F -->
        <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-slate-200">
          <span class="text-[11px] font-mono text-slate-500 mr-1">Pilih Titik:</span>
          <button
            @click="selectedTitik = 'Semua'"
            :class="[
              'px-3 py-1 rounded-lg text-xs font-semibold font-mono transition',
              selectedTitik === 'Semua'
                ? 'bg-[#f97316] text-white font-bold shadow-xs'
                : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200'
            ]"
          >
            Semua Titik
          </button>

          <button
            v-for="t in proyektorTeamsList"
            :key="t.titik"
            @click="selectedTitik = t.titik"
            :class="[
              'px-3 py-1 rounded-lg text-xs font-semibold font-mono transition',
              selectedTitik === t.titik
                ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white font-bold shadow-xs'
                : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200'
            ]"
          >
            {{ t.titik }}
          </button>

          <button
            v-if="selectedTitik !== 'Semua' || searchQuery"
            @click="resetFilter"
            class="ml-auto text-[11px] text-[#f97316] hover:text-orange-700 font-semibold underline"
          >
            Reset Filter
          </button>
        </div>
      </section>

      <!-- 5. Master Table Cards per Titik (Google Sheet Authentic Style) -->
      <section class="space-y-6">
        <!-- Empty State -->
        <div v-if="filteredTeams.length === 0" class="p-12 rounded-3xl bg-slate-50 border border-slate-200 text-center">
          <svg class="w-12 h-12 mx-auto text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          <p class="text-base font-bold text-slate-800">Tidak Ada Data Titik Proyektor</p>
          <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
            {{ searchQuery ? 'Tidak ada hasil yang sesuai dengan "' + searchQuery + '"' : 'Data pembagian proyektor belum diinput di Super Admin.' }}
          </p>
        </div>

        <div
          v-for="(team, tIdx) in filteredTeams"
          :key="tIdx"
          class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-md transition hover:border-emerald-400"
        >
          <!-- Emerald Gradient Header Bar with Orange Titik Pill (Flyer Style) -->
          <div class="bg-gradient-to-r from-[#059669] to-[#047857] p-4 sm:p-5 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm border-b border-emerald-800">
            <div class="flex items-center space-x-3.5">
              <span class="w-10 h-10 rounded-2xl bg-[#f97316] text-white flex items-center justify-center font-black text-base shadow-md">
                {{ (team.titik || 'A').replace('Titik ', '') }}
              </span>
              <div>
                <h3 class="text-base font-black uppercase tracking-wider text-white">{{ team.titik || ('Titik ' + (tIdx + 1)) }}</h3>
                <div class="text-xs text-emerald-100 flex items-center space-x-1.5 mt-0.5">
                  <span>Koordinator Wilayah (Korwil):</span>
                  <strong class="text-white font-bold underline font-mono text-sm">{{ team.korwil || '-' }}</strong>
                </div>
              </div>
            </div>

            <!-- Kabel Team Box -->
            <div class="px-4 py-2 rounded-xl bg-white/15 border border-white/20 text-xs text-white backdrop-blur-sm flex sm:flex-col sm:items-end justify-between items-center">
              <span class="text-emerald-200 text-[10px] uppercase font-bold tracking-wider">Tim Instalasi Kabel:</span>
              <span class="font-bold text-white font-mono text-sm">{{ team.kabel || '-' }}</span>
            </div>
          </div>

          <!-- Sub Table of Units -->
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
              <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 font-mono text-[10px] uppercase tracking-wider font-bold">
                <tr>
                  <th class="py-3 px-4 w-12 text-center">#</th>
                  <th class="py-3 px-4 w-36">KODE PERANGKAT</th>
                  <th class="py-3 px-4 min-w-[220px]">WILAYAH / TITIK LOKASI</th>
                  <th class="py-3 px-4 min-w-[220px] text-emerald-800">PETUGAS BERTANGGUNG JAWAB</th>
                  <th class="py-3 px-4 text-right pr-6">STATUS LAPANGAN</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 bg-white">
                <tr
                  v-for="(item, iIdx) in team.items"
                  :key="iIdx"
                  class="hover:bg-slate-50 transition"
                >
                  <td class="py-3 px-4 text-center font-mono text-slate-400">{{ iIdx + 1 }}</td>
                  <!-- Kode Perangkat -->
                  <td class="py-3 px-4 font-mono font-bold text-emerald-700">
                    <span class="px-2.5 py-1 rounded bg-slate-100 border border-slate-200 text-slate-800 inline-block">
                      {{ item.kode }}
                    </span>
                  </td>
                  <!-- Wilayah / Titik -->
                  <td class="py-3 px-4 font-semibold text-slate-900">
                    {{ item.wilayah }}
                  </td>
                  <!-- Petugas Proyektor -->
                  <td class="py-3 px-4 font-bold text-emerald-700">
                    <div class="flex items-center space-x-1.5">
                      <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                      <span>{{ item.petugas }}</span>
                    </div>
                  </td>
                  <!-- Status Unit -->
                  <td class="py-3 px-4 text-right pr-6">
                    <span class="inline-flex items-center space-x-1.5 text-[10px] text-emerald-700 font-semibold font-mono px-2 py-0.5 rounded bg-emerald-50 border border-emerald-200">
                      <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                      <span>TERJADWAL</span>
                    </span>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- 6. Instruksi Darurat & Troubleshooting Proyektor Lapangan -->
      <section class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-3">
        <h3 class="text-xs sm:text-sm font-bold text-emerald-800 uppercase tracking-wider font-mono flex items-center space-x-2">
          <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          <span>SOP Troubleshooting &amp; Prosedur Cepat Kendala Proyektor</span>
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs text-slate-600">
          <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
            <span class="text-emerald-700 font-bold block mb-1">1. Layar Mati / 'No Signal'</span>
            Periksa kabel HDMI/VGA dari splitter cabang. Jika kabel FO, pastikan konverter FO menyala indikator hijau.
          </div>
          <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
            <span class="text-emerald-700 font-bold block mb-1">2. Gambar Kedip / Flicker</span>
            Ganti kabel extender atau turunkan resolusi output switcher di tenda pusat menjadi 1080i / 720p.
          </div>
          <div class="p-3.5 rounded-xl bg-slate-50 border border-slate-200">
            <span class="text-emerald-700 font-bold block mb-1">3. Kontak Darurat Koordinator</span>
            Hubungi Koordinator Proyektor melalui handy talky (HT) Channel 3 Media atau Posko Pusat.
          </div>
        </div>
      </section>
    </main>

    <!-- Modal Lightbox Preview Gambar -->
    <div
      v-if="lightboxImage"
      class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-md flex items-center justify-center p-4"
      @click.self="lightboxImage = null"
    >
      <div class="relative max-w-5xl w-full bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-2xl p-4">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200 mb-3">
          <h4 class="text-sm font-bold text-slate-900">{{ lightboxCaption || 'Preview Gambar' }}</h4>
          <button
            @click="lightboxImage = null"
            class="p-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
        <div class="flex items-center justify-center max-h-[80vh] overflow-auto">
          <img :src="lightboxImage" class="max-w-full max-h-[75vh] object-contain rounded-xl" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import api from '../../services/api';
import { useSettingsStore } from '../../stores/settings';

const settingsStore = useSettingsStore();

const searchQuery = ref('');
const selectedTitik = ref('Semua');

const proyektorTeamsList = ref([]);
const proyektorPhotosList = ref([]);

const lightboxImage = ref(null);
const lightboxCaption = ref('');

const openLightbox = (url, caption) => {
  lightboxImage.value = url;
  lightboxCaption.value = caption || '';
};

const getPhotoUrl = (path) => {
  if (!path) return '';
  if (path.startsWith('http://') || path.startsWith('https://')) return path;
  return path.startsWith('/') ? path : '/' + path;
};

const resetFilter = () => {
  searchQuery.value = '';
  selectedTitik.value = 'Semua';
};

const totalDevicesCount = computed(() => {
  let count = 0;
  (proyektorTeamsList.value || []).forEach((t) => {
    count += (t.items || []).length;
  });
  return count;
});

const uniqueKorwilCount = computed(() => {
  const set = new Set();
  (proyektorTeamsList.value || []).forEach((t) => {
    if (t.korwil && t.korwil !== '-') set.add(t.korwil);
  });
  return set.size;
});

const filteredTeams = computed(() => {
  let list = proyektorTeamsList.value || [];

  if (selectedTitik.value !== 'Semua') {
    list = list.filter((t) => t.titik === selectedTitik.value);
  }

  if (searchQuery.value.trim()) {
    const q = searchQuery.value.toLowerCase().trim();
    list = list
      .map((t) => {
        const matchTeam =
          (t.titik || '').toLowerCase().includes(q) ||
          (t.korwil || '').toLowerCase().includes(q) ||
          (t.kabel || '').toLowerCase().includes(q);

        const matchedItems = (t.items || []).filter((item) => {
          return (
            (item.kode || '').toLowerCase().includes(q) ||
            (item.wilayah || '').toLowerCase().includes(q) ||
            (item.petugas || '').toLowerCase().includes(q)
          );
        });

        if (matchTeam) return t;
        if (matchedItems.length > 0) {
          return { ...t, items: matchedItems };
        }
        return null;
      })
      .filter(Boolean);
  }

  return list;
});

const printPage = () => {
  window.print();
};

const fetchProyektorData = async () => {
  try {
    const res = await api.get('/public/landing');
    const data = res.data?.data || {};
    proyektorTeamsList.value = data.proyektor_teams || [];
    proyektorPhotosList.value = data.proyektor_photos || [];
  } catch (err) {
    console.error('Failed to load proyektor data', err);
  }
};

onMounted(() => {
  fetchProyektorData();
});
</script>
