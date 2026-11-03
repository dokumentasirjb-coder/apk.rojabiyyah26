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
            <div class="h-8 sm:h-10 w-auto flex items-center justify-center group-hover:scale-105 transition flex-shrink-0">
              <img
                :src="settingsStore.settings?.logo_url || '/logo.png'"
                alt="Logo RJB"
                class="h-8 sm:h-10 w-auto object-contain drop-shadow-sm"
              />
            </div>
            <div class="min-w-0">
              <div class="font-extrabold text-xs sm:text-sm tracking-wider uppercase text-slate-900 flex items-center space-x-1.5 truncate">
                <span class="truncate">JADWAL KERJA TIM</span>
                <span class="px-1.5 py-0.2 rounded text-[9px] bg-[#f97316] text-white font-mono border border-white/20 flex-shrink-0 font-bold">DETAIL</span>
              </div>
              <div class="hidden sm:block text-[11px] font-bold text-emerald-700 tracking-wide truncate">Dokumentasi &amp; Shooting Haul 2026</div>
            </div>
          </router-link>
        </div>

        <!-- Action Tools -->
        <div class="flex items-center space-x-2.5 sm:space-x-3">
          <router-link
            to="/pembagian-proyektor"
            class="hidden md:inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-emerald-700 text-xs font-bold transition active:scale-95 shadow-xs"
          >
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <span>Buka Tim Proyektor →</span>
          </router-link>

          <button
            @click="printSchedule"
            class="inline-flex items-center space-x-1.5 px-3.5 sm:px-4 py-2 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:brightness-105 text-white text-xs font-bold shadow-md shadow-emerald-600/20 transition active:scale-95"
            title="Cetak atau Simpan PDF Jadwal Resmi"
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
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h18"/></svg>
              <span>Manajemen Waktu Penugasan Resmi</span>
            </div>
            <h1 class="text-2xl sm:text-4xl font-black text-white tracking-tight leading-tight">
              Jadwal Kerja Tim Dokumentasi, Liputan &amp; Multicam Shooting
            </h1>
            <p class="text-xs sm:text-sm text-emerald-100 leading-relaxed">
              Jadwal pembagian penugasan resmi fotografer, videografer, dan kameramen multicam haul akbar. Sinkron otomatis dengan data panitia pusat.
            </p>
          </div>

          <!-- Quick Stats Counter -->
          <div class="grid grid-cols-2 sm:grid-cols-3 gap-3 flex-shrink-0">
            <div class="p-3.5 rounded-2xl bg-white/15 border border-white/25 backdrop-blur-md">
              <span class="text-[10px] font-mono text-emerald-100 uppercase block font-semibold">Tim Liputan</span>
              <span class="text-xl sm:text-2xl font-black text-white font-mono">{{ jadwalTimList.length }}</span>
              <span class="text-[10px] text-emerald-200 block">Sesi Tugas</span>
            </div>
            <div class="p-3.5 rounded-2xl bg-white/15 border border-white/25 backdrop-blur-md">
              <span class="text-[10px] font-mono text-emerald-100 uppercase block font-semibold">Tim Shooting</span>
              <span class="text-xl sm:text-2xl font-black text-white font-mono">{{ jadwalShootingList.length }}</span>
              <span class="text-[10px] text-emerald-200 block">Posisi Kamera</span>
            </div>
            <div class="p-3.5 rounded-2xl bg-white/15 border border-white/25 backdrop-blur-md col-span-2 sm:col-span-1">
              <span class="text-[10px] font-mono text-emerald-100 uppercase block font-semibold">Poster Visual</span>
              <span class="text-xl sm:text-2xl font-black text-white font-mono">{{ jadwalPhotosList.length }}</span>
              <span class="text-[10px] text-emerald-200 block">Desain Terunggah</span>
            </div>
          </div>
        </div>
      </section>

      <!-- 3. Poster Infografis / Gambar Jadwal HD (Jika Ada) -->
      <section v-if="jadwalPhotosList.length > 0" class="space-y-3">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <h2 class="text-xs sm:text-sm font-bold text-slate-800 uppercase tracking-wider font-mono">
              Poster Visual &amp; Infografis Jadwal Resmi HD
            </h2>
          </div>
          <span class="text-[11px] font-mono text-emerald-700 font-bold">{{ jadwalPhotosList.length }} Gambar Tersedia</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <div
            v-for="(photo, idx) in jadwalPhotosList"
            :key="idx"
            class="group relative rounded-2xl overflow-hidden bg-white border border-slate-200 shadow-sm cursor-pointer hover:border-emerald-500 transition-all duration-300"
            @click="openLightbox(getPhotoUrl(photo), 'Poster Jadwal Kerja #' + (idx + 1))"
          >
            <div class="h-56 overflow-hidden bg-slate-100">
              <img
                :src="getPhotoUrl(photo)"
                :alt="'Poster Jadwal #' + (idx + 1)"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500"
              />
            </div>
            <div class="p-3.5 bg-slate-50 border-t border-slate-200 flex items-center justify-between">
              <div>
                <p class="text-xs font-bold text-slate-900">Poster Jadwal #{{ idx + 1 }}</p>
                <p class="text-[10px] font-mono text-slate-500">Klik untuk melihat resolusi tinggi</p>
              </div>
              <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-200 font-mono text-[10px] font-bold flex items-center space-x-1">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                <span>Lihat HD</span>
              </span>
            </div>
          </div>
        </div>
      </section>

      <!-- 4. Interactive Navigation & Filter Bar -->
      <section class="bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm space-y-4 print:hidden">
        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-4">
          <!-- Main Tab Switcher -->
          <div class="flex flex-wrap items-center gap-2">
            <button
              @click="activeSubTab = 'liputan'"
              :class="[
                'px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center space-x-2',
                activeSubTab === 'liputan'
                  ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 ring-2 ring-emerald-500/20'
                  : 'bg-slate-50 text-slate-700 hover:text-emerald-700 hover:bg-slate-100 border border-slate-200'
              ]"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="4" stroke-width="2"/></svg>
              <span>Tim Liputan (Foto &amp; Video) ({{ filteredLiputanRows.length }})</span>
            </button>

            <button
              @click="activeSubTab = 'shooting'"
              :class="[
                'px-4 py-2.5 rounded-xl text-xs font-bold transition flex items-center space-x-2',
                activeSubTab === 'shooting'
                  ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 ring-2 ring-emerald-500/20'
                  : 'bg-slate-50 text-slate-700 hover:text-emerald-700 hover:bg-slate-100 border border-slate-200'
              ]"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
              <span>Tim Shooting Multicam (Kameramen) ({{ filteredShootingRows.length }})</span>
            </button>
          </div>

          <!-- Search Input -->
          <div class="relative w-full lg:w-72 flex-shrink-0">
            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35"/></svg>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari nama petugas, momen, lokasi..."
              class="w-full pl-10 pr-4 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:bg-white transition"
            />
          </div>
        </div>

        <!-- Filter Chips: Hari & Waktu -->
        <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-slate-200">
          <span class="text-[11px] font-mono text-slate-500 mr-1">Filter Hari:</span>
          <button
            v-for="day in availableDays"
            :key="day"
            @click="selectedDay = day"
            :class="[
              'px-2.5 py-1 rounded-lg text-xs font-semibold font-mono transition',
              selectedDay === day
                ? 'bg-[#f97316] text-white font-bold shadow-xs'
                : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200'
            ]"
          >
            {{ day }}
          </button>

          <span class="text-[11px] font-mono text-slate-500 ml-3 mr-1">Filter Waktu:</span>
          <button
            v-for="time in availableTimes"
            :key="time"
            @click="selectedTime = time"
            :class="[
              'px-2.5 py-1 rounded-lg text-xs font-semibold font-mono transition',
              selectedTime === time
                ? 'bg-emerald-600 text-white font-bold shadow-xs'
                : 'bg-slate-100 text-slate-700 hover:bg-slate-200 border border-slate-200'
            ]"
          >
            {{ time }}
          </button>

          <button
            v-if="selectedDay !== 'Semua' || selectedTime !== 'Semua' || searchQuery"
            @click="resetFilters"
            class="ml-auto text-[11px] text-[#f97316] hover:text-orange-700 font-semibold underline"
          >
            Reset Filter
          </button>
        </div>
      </section>

      <!-- 5. TABLE TAB 1: TIM LIPUTAN (FOTOGRAFER & VIDEOGRAFER) -->
      <section v-if="activeSubTab === 'liputan'" class="space-y-4">
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
          <!-- Table Header Flyer Style Authentic -->
          <div class="bg-gradient-to-r from-[#059669] to-[#047857] p-4 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-emerald-800">
            <div class="flex items-center space-x-3">
              <span class="w-8 h-8 rounded-xl bg-[#f97316] text-white flex items-center justify-center shadow-md">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="4" stroke-width="2"/></svg>
              </span>
              <div>
                <h3 class="text-sm font-black uppercase tracking-wider text-white">Tabel Penugasan Tim Liputan Lapangan</h3>
                <p class="text-xs text-emerald-100">Format resmi fotografer &amp; videografer kegiatan Haul &amp; Rojabiyyah</p>
              </div>
            </div>

            <div class="text-right">
              <span class="px-3 py-1 rounded-xl bg-white/20 border border-white/30 font-mono text-xs text-white font-bold">
                {{ filteredLiputanRows.length }} Baris Terjadwal
              </span>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
              <thead class="bg-slate-50 border-b-2 border-slate-200 text-slate-700 uppercase font-bold text-[11px] tracking-wider font-mono">
                <tr>
                  <th class="py-3.5 px-4 w-12 text-center">#</th>
                  <th class="py-3.5 px-4 w-28">HARI</th>
                  <th class="py-3.5 px-4 w-28">WAKTU</th>
                  <th class="py-3.5 px-4 min-w-[190px]">MOMEN KEGIATAN</th>
                  <th class="py-3.5 px-4 min-w-[200px]">WILAYAH / LOKASI</th>
                  <th class="py-3.5 px-4 min-w-[200px] text-emerald-800">FOTOGRAFER</th>
                  <th class="py-3.5 px-4 min-w-[180px] text-teal-800">VIDEOGRAFER</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 bg-white">
                <tr v-if="filteredLiputanRows.length === 0">
                  <td colspan="7" class="py-12 text-center text-slate-400">
                    <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs font-semibold">Tidak ada jadwal liputan yang sesuai dengan filter.</p>
                  </td>
                </tr>

                <tr
                  v-for="(row, rIdx) in filteredLiputanRows"
                  :key="rIdx"
                  class="hover:bg-slate-50 transition"
                  :class="row.hari === 'Kamis' && row.waktu === 'Malam' ? 'bg-amber-50/40' : ''"
                >
                  <td class="py-3 px-4 text-center font-mono text-slate-400">{{ rIdx + 1 }}</td>
                  <td class="py-3 px-4 font-bold">
                    <span
                      class="px-2.5 py-1 rounded-md text-[10px] font-mono font-bold"
                      :class="row.hari === 'Rabu' ? 'bg-orange-100 text-orange-800 border border-orange-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200'"
                    >
                      {{ row.hari }}
                    </span>
                  </td>
                  <td class="py-3 px-4 font-mono text-slate-700 text-[11px]">
                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200">
                      {{ row.waktu }}
                    </span>
                  </td>
                  <td class="py-3 px-4 font-bold text-slate-900">{{ row.momen }}</td>
                  <td class="py-3 px-4 text-slate-600">{{ row.wilayah }}</td>
                  <td class="py-3 px-4 font-semibold text-emerald-700">
                    <div class="flex items-center space-x-1.5">
                      <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                      <span>{{ row.fotografer || '-' }}</span>
                    </div>
                  </td>
                  <td class="py-3 px-4 font-semibold text-teal-700">
                    <div class="flex items-center space-x-1.5">
                      <span class="w-1.5 h-1.5 rounded-full bg-teal-500 flex-shrink-0"></span>
                      <span>{{ row.videografer || '-' }}</span>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- 6. TABLE TAB 2: TIM SHOOTING MULTICAM (KAMERAMEN) -->
      <section v-if="activeSubTab === 'shooting'" class="space-y-4">
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
          <!-- Table Header -->
          <div class="bg-gradient-to-r from-[#059669] to-[#047857] p-4 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-3 border-b border-emerald-800">
            <div class="flex items-center space-x-3">
              <span class="w-8 h-8 rounded-xl bg-[#f97316] text-white flex items-center justify-center shadow-md">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
              </span>
              <div>
                <h3 class="text-sm font-black uppercase tracking-wider text-white">Tabel Penugasan Tim Shooting Multicam Live</h3>
                <p class="text-xs text-emerald-100">Pembagian posisi angle panggung &amp; operator kamera live streaming</p>
              </div>
            </div>

            <div class="text-right">
              <span class="px-3 py-1 rounded-xl bg-white/20 border border-white/30 font-mono text-xs text-white font-bold">
                {{ filteredShootingRows.length }} Baris Terjadwal
              </span>
            </div>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
              <thead class="bg-slate-50 border-b-2 border-slate-200 text-slate-700 uppercase font-bold text-[11px] tracking-wider font-mono">
                <tr>
                  <th class="py-3.5 px-4 w-12 text-center">#</th>
                  <th class="py-3.5 px-4 w-28">HARI</th>
                  <th class="py-3.5 px-4 w-28">WAKTU</th>
                  <th class="py-3.5 px-4 min-w-[190px]">MOMEN KEGIATAN</th>
                  <th class="py-3.5 px-4 min-w-[200px]">WILAYAH / LOKASI</th>
                  <th class="py-3.5 px-4 min-w-[180px] text-orange-800">POSISI SHOOTING</th>
                  <th class="py-3.5 px-4 min-w-[200px] text-emerald-800">KAMERAMEN</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 bg-white">
                <tr v-if="filteredShootingRows.length === 0">
                  <td colspan="7" class="py-12 text-center text-slate-400">
                    <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs font-semibold">Tidak ada jadwal shooting multicam yang sesuai dengan filter.</p>
                  </td>
                </tr>

                <tr
                  v-for="(sRow, sIdx) in filteredShootingRows"
                  :key="sIdx"
                  class="hover:bg-slate-50 transition"
                >
                  <td class="py-3 px-4 text-center font-mono text-slate-400">{{ sIdx + 1 }}</td>
                  <td class="py-3 px-4 font-bold">
                    <span
                      class="px-2.5 py-1 rounded-md text-[10px] font-mono font-bold"
                      :class="sRow.hari === 'Rabu' ? 'bg-orange-100 text-orange-800 border border-orange-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200'"
                    >
                      {{ sRow.hari }}
                    </span>
                  </td>
                  <td class="py-3 px-4 font-mono text-slate-700 text-[11px]">
                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200">
                      {{ sRow.waktu }}
                    </span>
                  </td>
                  <td class="py-3 px-4 font-bold text-slate-900">{{ sRow.momen }}</td>
                  <td class="py-3 px-4 text-slate-600">{{ sRow.wilayah }}</td>
                  <td class="py-3 px-4 font-mono font-bold text-orange-700">
                    <span class="px-2.5 py-1 rounded bg-orange-50 border border-orange-200">
                      {{ sRow.shooting }}
                    </span>
                  </td>
                  <td class="py-3 px-4 font-bold text-emerald-700">
                    <div class="flex items-center space-x-1.5">
                      <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 flex-shrink-0"></span>
                      <span>{{ sRow.kameramen }}</span>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </section>

      <!-- 7. SOP Briefing Catatan Lapangan -->
      <section class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm space-y-3">
        <div class="flex items-center justify-between flex-wrap gap-2">
          <h3 class="text-xs sm:text-sm font-bold text-emerald-800 uppercase tracking-wider font-mono flex items-center space-x-2">
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>Instruksi Teknis Petugas Dokumentasi &amp; Shooting Lapangan</span>
          </h3>
          <button
            v-if="authStore.isAuthenticated"
            @click="openEditInstruksiModal"
            class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-bold transition shadow-xs active:scale-95"
            title="Kelola & edit instruksi teknis dokumentasi lapangan"
          >
            <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
            <span>Edit Instruksi Teknis</span>
          </button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs text-slate-600">
          <div
            v-for="(item, iIdx) in jadwalInstruksiList"
            :key="'instruksi-' + iIdx"
            class="p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-1"
          >
            <span class="text-emerald-700 font-bold block">{{ item.title }}</span>
            <p class="text-slate-600 leading-relaxed whitespace-pre-line">{{ item.desc }}</p>
          </div>
        </div>
      </section>
    </main>

    <!-- Modal Edit Instruksi Teknis Jadwal -->
    <div
      v-if="showEditInstruksiModal"
      class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-md flex items-center justify-center p-4 overflow-y-auto"
      @click.self="showEditInstruksiModal = false"
    >
      <div class="relative max-w-2xl w-full bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-2xl p-5 sm:p-6 my-6 max-h-[90vh] flex flex-col text-xs text-slate-700">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200 mb-4">
          <div class="flex items-center space-x-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <h3 class="text-sm sm:text-base font-bold text-slate-900">Edit Instruksi Teknis Petugas Dokumentasi &amp; Shooting</h3>
          </div>
          <button @click="showEditInstruksiModal = false" class="text-slate-400 hover:text-slate-700 text-lg">✕</button>
        </div>

        <div class="flex-1 overflow-y-auto space-y-3.5 pr-1">
          <div
            v-for="(item, idx) in editingInstruksiList"
            :key="'edit-instruksi-' + idx"
            class="p-3.5 bg-slate-50 border border-slate-200 rounded-2xl space-y-2 relative"
          >
            <div class="flex items-center justify-between">
              <span class="font-bold text-emerald-800 text-[11px]">Poin Instruksi #{{ idx + 1 }}</span>
              <button
                type="button"
                @click="removeInstruksiItem(idx)"
                class="text-rose-600 hover:text-rose-700 font-bold text-[11px] flex items-center space-x-1"
              >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>Hapus</span>
              </button>
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1 text-[11px]">Judul Instruksi *</label>
              <input
                v-model="item.title"
                type="text"
                placeholder="misal: 1. Kehadiran & Koordinasi"
                class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl font-bold text-slate-900 focus:outline-none focus:border-emerald-500"
              />
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1 text-[11px]">Penjelasan Petunjuk Teknis *</label>
              <textarea
                v-model="item.desc"
                rows="2"
                placeholder="Tuliskan petunjuk operasional di lapangan..."
                class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
              ></textarea>
            </div>
          </div>

          <button
            type="button"
            @click="addInstruksiItem"
            class="w-full py-2.5 rounded-2xl border-2 border-dashed border-emerald-300 hover:border-emerald-500 bg-emerald-50/50 hover:bg-emerald-50 text-emerald-800 font-bold text-xs flex items-center justify-center space-x-1.5 transition"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>+ Tambah Poin Instruksi Baru</span>
          </button>
        </div>

        <div class="flex items-center justify-end space-x-2 pt-4 border-t border-slate-200 mt-4">
          <button
            type="button"
            @click="showEditInstruksiModal = false"
            class="px-4 py-2 bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 rounded-xl font-semibold transition"
          >
            Batal
          </button>
          <button
            type="button"
            @click="saveInstruksiChanges"
            :disabled="savingInstruksi"
            class="px-5 py-2 bg-gradient-to-r from-[#059669] to-[#10b981] hover:brightness-105 text-white font-bold rounded-xl transition flex items-center space-x-1.5 disabled:opacity-50"
          >
            <span v-if="savingInstruksi" class="inline-block w-3 h-3 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
            <span>{{ savingInstruksi ? 'Menyimpan...' : 'Simpan Perubahan Instruksi' }}</span>
          </button>
        </div>
      </div>
    </div>

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
import { useAuthStore } from '../../stores/auth';

const settingsStore = useSettingsStore();
const authStore = useAuthStore();

const activeSubTab = ref('liputan');
const searchQuery = ref('');
const selectedDay = ref('Semua');
const selectedTime = ref('Semua');

const availableDays = ['Semua', 'Rabu', 'Kamis', 'Jumat'];
const availableTimes = ['Semua', 'Pagi', 'Siang', 'Malam'];

const jadwalTimList = ref([]);
const jadwalShootingList = ref([]);
const jadwalPhotosList = ref([]);

const defaultJadwalInstruksi = [
  {
    title: "1. Kehadiran & Koordinasi",
    desc: "Seluruh personil wajib hadir di Posko Media 30 menit sebelum sesi kegiatan dimulai untuk pengecekan baterai, kartu memori, dan briefing."
  },
  {
    title: "2. Penyetoran Bahan / Footage",
    desc: "Selesai tiap sesi, memory card langsung diserahkan kepada tim editing/ingest di kantor media untuk backup data instan."
  },
  {
    title: "3. Live Streaming & Multicam",
    desc: "Kameramen multicam memastikan komunikasi intercom terhubung aktif dan mematuhi aba-aba Switcher Director."
  }
];
const jadwalInstruksiList = ref([...defaultJadwalInstruksi]);
const showEditInstruksiModal = ref(false);
const editingInstruksiList = ref([]);
const savingInstruksi = ref(false);

const openEditInstruksiModal = () => {
  editingInstruksiList.value = JSON.parse(JSON.stringify(jadwalInstruksiList.value));
  showEditInstruksiModal.value = true;
};

const addInstruksiItem = () => {
  editingInstruksiList.value.push({
    title: `${editingInstruksiList.value.length + 1}. Judul Instruksi Baru`,
    desc: 'Deskripsi teknis petunjuk penugasan...'
  });
};

const removeInstruksiItem = (idx) => {
  editingInstruksiList.value.splice(idx, 1);
};

const saveInstruksiChanges = async () => {
  savingInstruksi.value = true;
  try {
    const res = await api.post('/admin/landing/save-jadwal', { instruksi: editingInstruksiList.value });
    jadwalInstruksiList.value = res.data.jadwal_instruksi || editingInstruksiList.value;
    showEditInstruksiModal.value = false;
    alert('Instruksi teknis jadwal berhasil disimpan!');
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan instruksi teknis.');
  } finally {
    savingInstruksi.value = false;
  }
};

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

const resetFilters = () => {
  searchQuery.value = '';
  selectedDay.value = 'Semua';
  selectedTime.value = 'Semua';
};

const filteredLiputanRows = computed(() => {
  let list = jadwalTimList.value || [];
  if (selectedDay.value !== 'Semua') {
    list = list.filter((r) => r.hari === selectedDay.value);
  }
  if (selectedTime.value !== 'Semua') {
    list = list.filter((r) => r.waktu === selectedTime.value);
  }
  if (searchQuery.value.trim()) {
    const q = searchQuery.value.toLowerCase().trim();
    list = list.filter(
      (r) =>
        (r.momen || '').toLowerCase().includes(q) ||
        (r.wilayah || '').toLowerCase().includes(q) ||
        (r.fotografer || '').toLowerCase().includes(q) ||
        (r.videografer || '').toLowerCase().includes(q)
    );
  }
  return list;
});

const filteredShootingRows = computed(() => {
  let list = jadwalShootingList.value || [];
  if (selectedDay.value !== 'Semua') {
    list = list.filter((r) => r.hari === selectedDay.value);
  }
  if (selectedTime.value !== 'Semua') {
    list = list.filter((r) => r.waktu === selectedTime.value);
  }
  if (searchQuery.value.trim()) {
    const q = searchQuery.value.toLowerCase().trim();
    list = list.filter(
      (r) =>
        (r.momen || '').toLowerCase().includes(q) ||
        (r.wilayah || '').toLowerCase().includes(q) ||
        (r.shooting || '').toLowerCase().includes(q) ||
        (r.kameramen || '').toLowerCase().includes(q)
    );
  }
  return list;
});

const printSchedule = () => {
  window.print();
};

const fetchJadwalData = async () => {
  try {
    const res = await api.get('/public/overview');
    const data = res.data?.data || res.data || {};
    jadwalTimList.value = data.jadwal_tim || [];
    jadwalShootingList.value = data.jadwal_shooting || [];
    jadwalPhotosList.value = data.jadwal_photos || [];
    if (data.jadwal_instruksi && Array.isArray(data.jadwal_instruksi) && data.jadwal_instruksi.length > 0) {
      jadwalInstruksiList.value = data.jadwal_instruksi;
    }
  } catch (err) {
    console.error('Failed to load jadwal kerja data', err);
  }
};

onMounted(() => {
  fetchJadwalData();
});
</script>
