<template>
  <div class="min-h-screen bg-[#f8fafc] text-slate-800 flex flex-col font-sans selection:bg-emerald-500 selection:text-white scroll-smooth">
    <!-- 1. Top Navbar (Clean White & Emerald Accent Theme) -->
    <header class="w-full bg-white/95 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50 transition-all shadow-sm">
      <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 h-16 sm:h-20 flex items-center justify-between">
        <!-- Brand -->
        <router-link to="/" class="flex items-center space-x-2.5 sm:space-x-3.5 group min-w-0 pr-2">
          <div class="h-9 sm:h-11 flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition">
            <img
              v-if="settingsStore.settings?.logo_url && settingsStore.settings?.logo_url !== '/logo.png'"
              :src="settingsStore.settings.logo_url"
              alt="Logo"
              class="h-8 sm:h-10 w-auto object-contain"
            />
            <div v-else class="w-9 h-9 sm:w-11 sm:h-11 rounded-xl bg-gradient-to-br from-[#059669] to-[#10b981] flex items-center justify-center text-white">
              <svg class="w-5 h-5 sm:w-6 sm:h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
          </div>
          <div class="min-w-0">
            <div class="font-black text-[11px] sm:text-base tracking-normal sm:tracking-wider uppercase text-slate-900 flex items-center space-x-1.5">
              <span class="whitespace-nowrap">{{ settingsStore.settings?.app_name || 'MEDIA ROJABIYYAH' }}</span>
              <span class="px-1.5 py-0.5 rounded text-[9px] bg-[#f97316] text-white font-mono border border-white/20 flex-shrink-0 font-bold">2026</span>
            </div>
            <div class="hidden sm:block text-[11px] font-bold text-emerald-700 tracking-wide truncate">{{ settingsStore.settings?.app_sub_name || 'Aktif, Komunikatif, dan Transparan' }}</div>
          </div>
        </router-link>

        <!-- Desktop Navigation Links -->
        <nav class="hidden md:flex items-center space-x-4 lg:space-x-6 text-xs font-semibold text-slate-600">
          <a href="#denah" class="hover:text-emerald-700 transition flex items-center space-x-1.5 group">
            <svg class="w-4 h-4 text-emerald-600 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
            <span class="whitespace-nowrap font-bold">Denah Proyektor</span>
          </a>
          <a href="#kalender" class="hover:text-emerald-700 transition flex items-center space-x-1.5 group">
            <svg class="w-4 h-4 text-emerald-600 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01"/></svg>
            <span class="whitespace-nowrap font-bold">Kalender Acara</span>
          </a>
          <router-link to="/jadwal-kerja" class="hover:text-emerald-700 transition flex items-center space-x-1.5 group">
            <svg class="w-4 h-4 text-emerald-600 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01"/></svg>
            <span class="whitespace-nowrap font-bold">Jadwal Kerja Tim</span>
          </router-link>
          <router-link to="/pembagian-proyektor" class="hover:text-emerald-700 transition flex items-center space-x-1.5 group">
            <svg class="w-4 h-4 text-emerald-600 group-hover:scale-110 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <span class="whitespace-nowrap font-bold">Pembagian Proyektor</span>
          </router-link>
        </nav>

        <!-- Desktop Navbar Action Buttons -->
        <div class="hidden md:flex items-center space-x-2.5 sm:space-x-3 flex-shrink-0">
          <router-link
            to="/register-member"
            class="inline-flex items-center space-x-1.5 px-3 sm:px-4 py-2 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:brightness-105 text-white text-xs font-bold shadow-md shadow-emerald-600/20 transition active:scale-95"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            <span>Daftar Panitia</span>
          </router-link>

          <button
            @click="showLoginModal = true"
            class="inline-flex items-center space-x-1.5 px-3 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold transition active:scale-95 shadow-sm"
          >
            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
            <span>Masuk Petugas</span>
          </button>
        </div>

        <!-- Mobile Controls (< md) -->
        <div class="flex md:hidden items-center space-x-1.5 flex-shrink-0">
          <button
            @click="showLoginModal = true"
            class="inline-flex items-center space-x-1 px-2.5 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-700 text-[11px] font-bold shadow-xs active:scale-95 transition"
          >
            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14"/></svg>
            <span>Masuk</span>
          </button>

          <button
            @click="mobileMenuOpen = !mobileMenuOpen"
            class="p-1.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 hover:text-slate-900 transition active:scale-95"
            aria-label="Toggle navigation menu"
          >
            <svg v-if="!mobileMenuOpen" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <svg v-else class="w-5 h-5 text-[#f97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>
      </div>

      <!-- Mobile Dropdown Navigation Drawer -->
      <div
        v-if="mobileMenuOpen"
        class="md:hidden bg-white border-b border-slate-200 px-4 py-3 space-y-2 shadow-xl animate-in fade-in slide-in-from-top-2"
      >
        <a
          href="#denah"
          @click="mobileMenuOpen = false"
          class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700"
        >
          <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
          <span>Denah Proyektor Global</span>
        </a>

        <a
          href="#kalender"
          @click="mobileMenuOpen = false"
          class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700"
        >
          <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h18"/></svg>
          <span>Kalender Acara &amp; Agenda Haul</span>
        </a>

        <router-link
          to="/jadwal-kerja"
          @click="mobileMenuOpen = false"
          class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700"
        >
          <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h18"/></svg>
          <span>Jadwal Kerja Tim (Halaman Lengkap) ↗</span>
        </router-link>

        <router-link
          to="/pembagian-proyektor"
          @click="mobileMenuOpen = false"
          class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs font-semibold text-slate-700 hover:bg-emerald-50 hover:text-emerald-700"
        >
          <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          <span>Pembagian Tim Proyektor (Halaman Lengkap) ↗</span>
        </router-link>

        <div class="pt-2 border-t border-slate-200">
          <router-link
            to="/register-member"
            @click="mobileMenuOpen = false"
            class="w-full flex items-center justify-center space-x-2 px-3.5 py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] text-white text-xs font-bold shadow-md shadow-emerald-600/20"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            <span>Daftar Panitia Rojabiyyah</span>
          </router-link>
        </div>
      </div>
    </header>

    <!-- 2. Main Hero Section with Carousel -->
    <section class="relative flex items-center justify-center overflow-hidden min-h-[70vh] sm:min-h-[80vh] py-10 sm:py-20">
      <!-- Background Image Slider with Bright Light Overlay -->
      <div class="absolute inset-0 z-0">
        <img
          :src="getHeroSlideImage(currentSlideData.image)"
          :alt="currentSlideData.title"
          @error="handleImageError($event, currentSlideData)"
          class="w-full h-full object-cover object-center filter brightness-[0.4] contrast-105 scale-100 transition-all duration-700 ease-in-out"
        />
        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-900/60 to-slate-950/70"></div>
      </div>

      <!-- Arrow Controls -->
      <button
        @click="prevSlide"
        class="hidden sm:flex absolute left-4 sm:left-8 z-30 w-11 h-11 rounded-full bg-white/20 hover:bg-white text-white hover:text-slate-900 items-center justify-center border border-white/30 backdrop-blur-md transition shadow-lg"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
      </button>

      <button
        @click="nextSlide"
        class="hidden sm:flex absolute right-4 sm:right-8 z-30 w-11 h-11 rounded-full bg-white/20 hover:bg-white text-white hover:text-slate-900 items-center justify-center border border-white/30 backdrop-blur-md transition shadow-lg"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
      </button>

      <!-- Center Hero Content -->
      <div class="relative z-10 max-w-4xl mx-auto px-4 text-center py-6 sm:py-12">
        <div class="mb-3 sm:mb-4 inline-flex items-center space-x-2 px-3.5 sm:px-4 py-1 sm:py-1.5 rounded-full bg-[#f97316] text-white font-black text-[10px] sm:text-xs tracking-wider uppercase shadow-lg border border-white/20 max-w-[92vw] truncate">
          <span class="w-2 h-2 rounded-full bg-white animate-pulse flex-shrink-0"></span>
          <span class="truncate">{{ settingsStore.settings?.sub_theme || "Haul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad & Rojabiyyah 2026" }}</span>
        </div>

        <h1 class="text-2xl sm:text-4xl lg:text-6xl font-black text-white tracking-tight leading-tight sm:leading-tight mb-2 sm:mb-3 drop-shadow-lg">
          {{ settingsStore.settings?.hero_title_white || 'Media Rojabiyyah' }} <span class="bg-gradient-to-r from-emerald-300 via-teal-200 to-white bg-clip-text text-transparent">{{ settingsStore.settings?.hero_title_gradient || 'Haul Ke-5 & Rojabiyyah 2026' }}</span>
        </h1>

        <div v-if="currentSlideData.subtitle" class="mt-1.5 sm:mt-2 mb-2 sm:mb-3">
          <span class="inline-block text-[11px] sm:text-sm font-extrabold tracking-widest text-emerald-300 uppercase font-mono px-3.5 py-1.5 rounded-xl bg-black/40 border border-emerald-400/30 backdrop-blur-md shadow-md">
            {{ currentSlideData.subtitle }}
          </span>
        </div>

        <p class="text-xs sm:text-sm text-slate-200 font-medium max-w-2xl mx-auto leading-relaxed drop-shadow px-2 mb-6">
          {{ currentSlideData.subtext }}
        </p>
      </div>
    </section>

    <!-- ===================================================================== -->
    <!-- BAGIAN 1: DENAH PROYEKTOR GLOBAL (1 BESAR BISA BERGANTIAN)           -->
    <!-- ===================================================================== -->
    <section id="denah" class="py-12 sm:py-16 bg-white border-t border-slate-200">
      <div class="max-w-6xl mx-auto px-4">
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-6 sm:mb-8 gap-3 sm:gap-4">
          <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-[10px] sm:text-xs font-bold mb-2 sm:mb-3 uppercase tracking-wider">
              <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
              <span>Bagian 1: Informasi Lapangan</span>
            </div>
            <h2 class="text-xl sm:text-3xl font-black text-slate-900 tracking-tight">
              Denah Proyektor Global &amp; Tata Letak
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-2xl leading-relaxed">
              Foto udara dan peta denah persebaran titik proyektor, layar LCD, serta instalasi kabel di seluruh area Pondok Pesantren Al-Muhibbin Tambakberas Jombang.
            </p>
          </div>

          <!-- Quick Indicator -->
          <div class="flex items-center space-x-2 self-start md:self-auto">
            <span class="px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-emerald-700 font-mono text-[11px] sm:text-xs font-bold flex items-center space-x-2 shadow-sm">
              <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
              <span>Denah {{ activeDenahIndex + 1 }} dari {{ denahPhotosList.length }}</span>
            </span>
          </div>
        </div>

        <!-- 1 BESAR: Featured Large Display Frame yang Bisa Bergantian -->
        <div class="space-y-4">
          <div class="relative w-full h-72 sm:h-[450px] lg:h-[540px] rounded-2xl sm:rounded-3xl overflow-hidden bg-slate-900 border-2 border-slate-200 shadow-xl group">
            <!-- Featured Image -->
            <img
              :src="getPhotoUrl(activeDenahPhoto)"
              :alt="'Denah Proyektor #' + (activeDenahIndex + 1)"
              @click="openLightbox(getPhotoUrl(activeDenahPhoto), 'Denah Proyektor Global #' + (activeDenahIndex + 1))"
              class="w-full h-full object-cover object-center cursor-pointer transition-all duration-700 group-hover:scale-[1.02]"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-transparent to-black/30 pointer-events-none"></div>

            <!-- Arrow Buttons (Prev / Next) -->
            <button
              v-if="denahPhotosList.length > 1"
              @click="prevDenah"
              class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-slate-900/80 hover:bg-slate-800 text-white flex items-center justify-center border border-slate-700/60 backdrop-blur-md shadow-xl transition active:scale-95 z-20"
              title="Denah sebelumnya"
            >
              <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </button>

            <button
              v-if="denahPhotosList.length > 1"
              @click="nextDenah"
              class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 w-9 h-9 sm:w-11 sm:h-11 rounded-full bg-slate-900/80 hover:bg-slate-800 text-white flex items-center justify-center border border-slate-700/60 backdrop-blur-md shadow-xl transition active:scale-95 z-20"
              title="Denah berikutnya"
            >
              <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </button>

            <!-- Top Left Floating Badge -->
            <div class="absolute top-3 left-3 sm:top-4 sm:left-4 z-10 flex items-center space-x-2">
              <span class="inline-flex items-center space-x-1.5 px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-xl bg-black/75 backdrop-blur-md border border-white/20 text-white font-mono font-bold text-[10px] sm:text-xs shadow-lg">
                <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3" stroke-width="2"/></svg>
                <span>Peta Titik #{{ activeDenahIndex + 1 }}</span>
              </span>
            </div>

            <!-- Top Right Zoom Action Pill -->
            <button
              @click="openLightbox(getPhotoUrl(activeDenahPhoto), 'Denah Proyektor Global #' + (activeDenahIndex + 1))"
              class="absolute top-3 right-3 sm:top-4 sm:right-4 z-10 px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-xl bg-white text-slate-950 font-extrabold text-[10px] sm:text-xs shadow-lg hover:bg-slate-100 flex items-center space-x-1.5 transition active:scale-95"
            >
              <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
              <span>Perbesar HD</span>
            </button>

            <!-- Bottom Caption & Info -->
            <div class="absolute bottom-3 sm:bottom-5 left-3 sm:left-5 right-3 sm:right-5 z-10 flex flex-col sm:flex-row sm:items-end justify-between gap-2 sm:gap-3 pointer-events-none">
              <div>
                <span class="px-2 py-0.5 rounded-md bg-emerald-500 text-slate-950 font-black text-[9px] sm:text-[11px] shadow">
                  KOMPLEKS PONDOK PESANTREN
                </span>
                <h3 class="text-sm sm:text-xl font-black text-white drop-shadow-md mt-1 leading-snug">
                  Peta Sebaran Titik Proyektor &amp; Multi-Kamera Haul
                </h3>
                <p class="hidden sm:block text-xs sm:text-sm text-slate-300 drop-shadow-sm mt-0.5 max-w-xl">
                  Klik foto di atas atau gunakan tombol navigasi kiri/kanan untuk menjelajah peta tata letak layar dan jalur kabel.
                </p>
              </div>

              <div class="flex items-center space-x-2 pointer-events-auto">
                <span class="text-[10px] sm:text-[11px] font-mono text-slate-300">Ganti Foto:</span>
                <div class="flex space-x-1 sm:space-x-1.5">
                  <button
                    v-for="(p, pIdx) in denahPhotosList"
                    :key="pIdx"
                    @click="activeDenahIndex = pIdx"
                    :class="[
                      'h-2 rounded-full transition-all duration-300',
                      activeDenahIndex === pIdx ? 'w-5 sm:w-6 bg-emerald-400' : 'w-2 bg-slate-500 hover:bg-slate-300'
                    ]"
                  ></button>
                </div>
              </div>
            </div>
          </div>

          <!-- Bottom Thumbnails Selector Strip -->
          <div v-if="denahPhotosList.length > 1" class="pt-2">
            <div class="flex items-center justify-between text-xs text-slate-600 font-semibold mb-2">
              <span>Pilih Sudut Pandang Denah (Klik Thumbnail):</span>
              <span class="text-[11px] font-mono text-slate-500">{{ denahPhotosList.length }} Foto Tersedia</span>
            </div>

            <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-3">
              <div
                v-for="(photo, idx) in denahPhotosList"
                :key="idx"
                @click="activeDenahIndex = idx"
                :class="[
                  'relative h-20 sm:h-24 rounded-2xl overflow-hidden cursor-pointer transition-all duration-300 border-2',
                  activeDenahIndex === idx
                    ? 'border-emerald-600 scale-[1.03] shadow-md ring-2 ring-emerald-500/20'
                    : 'border-slate-200 opacity-70 hover:opacity-100 hover:border-slate-300'
                ]"
              >
                <img :src="getPhotoUrl(photo)" class="w-full h-full object-cover object-center" />
                <div class="absolute inset-0 bg-black/10"></div>
                <div class="absolute bottom-1.5 left-1.5 px-2 py-0.5 rounded bg-black/80 backdrop-blur-xs text-white text-[10px] font-mono font-bold">
                  #{{ idx + 1 }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ===================================================================== -->
    <!-- BAGIAN 2: JADWAL KERJA TIM (FOTOGRAFER, VIDEOGRAFER & SHOOTING)       -->
    <!-- ===================================================================== -->
    <section id="jadwal" class="py-14 bg-[#f8fafc] border-t border-slate-200">
      <div class="max-w-6xl mx-auto px-4">
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-6 sm:mb-8 gap-3.5 sm:gap-4">
          <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-[10px] sm:text-xs font-bold mb-2 sm:mb-3 uppercase tracking-wider">
              <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h18"/></svg>
              <span>Bagian 2: Manajemen Waktu Tim</span>
            </div>
            <h2 class="text-xl sm:text-3xl font-black text-slate-900 tracking-tight">
              Jadwal Kerja Tim Dokumentasi &amp; Shooting
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-2xl leading-relaxed">
              Jadwal pembagian tugas fotografer, videografer, dan kameramen multicam sesuai format resmi Haul &amp; Rojabiyyah.
            </p>
          </div>

          <!-- Direct Link to Dedicated Full Detail Page -->
          <div class="w-full sm:w-auto flex-shrink-0">
            <router-link
              to="/jadwal-kerja"
              class="w-full sm:w-auto inline-flex items-center justify-center space-x-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:brightness-105 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition transform active:scale-95"
            >
              <span>Buka Halaman Lengkap Jadwal Kerja</span>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </router-link>
          </div>
        </div>

        <!-- Optional Uploaded Schedule Photos Display (Read-Only di Publik) -->
        <div v-if="publicData.jadwal_photos && publicData.jadwal_photos.length > 0" class="mb-8">
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <div
              v-for="(jPhoto, jIdx) in publicData.jadwal_photos"
              :key="jIdx"
              class="relative rounded-2xl overflow-hidden bg-white border border-slate-200 shadow-sm group cursor-pointer"
              @click="openLightbox(getPhotoUrl(jPhoto), 'Foto Jadwal Kerja Tim #' + (jIdx + 1))"
            >
              <img :src="getPhotoUrl(jPhoto)" alt="Foto Jadwal" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300" />
              <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                <span class="px-3 py-1 rounded-full bg-white text-slate-900 font-bold text-xs">Perbesar Gambar</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Interactive Schedule Sub-Tab Switcher -->
        <div class="flex flex-wrap items-center gap-2 mb-6 border-b border-slate-200 pb-3">
          <button
            @click="activeJadwalTab = 'liputan'"
            :class="[
              'px-3.5 sm:px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2',
              activeJadwalTab === 'liputan'
                ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20'
                : 'bg-white text-slate-600 hover:text-emerald-700 hover:bg-slate-50 border border-slate-200'
            ]"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="4" stroke-width="2"/></svg>
            <span>Tim Liputan (Foto &amp; Video) ({{ publicData.jadwal_tim?.length || 0 }})</span>
          </button>

          <button
            @click="activeJadwalTab = 'shooting'"
            :class="[
              'px-3.5 sm:px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2',
              activeJadwalTab === 'shooting'
                ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20'
                : 'bg-white text-slate-600 hover:text-emerald-700 hover:bg-slate-50 border border-slate-200'
            ]"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
            <span>Tim Shooting Multicam ({{ publicData.jadwal_shooting?.length || 0 }})</span>
          </button>
        </div>

        <!-- Table 1: Tim Liputan (Google Sheet Match) -->
        <div v-if="activeJadwalTab === 'liputan'" class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
          <!-- Mobile Swipe Hint -->
          <div class="sm:hidden px-3.5 py-1.5 bg-slate-50 border-b border-slate-200 text-[10px] text-slate-500 flex items-center justify-between font-mono">
            <span>👉 Geser ke kanan untuk rincian lengkap</span>
            <span class="text-emerald-700 font-bold">Google Sheet</span>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
              <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[11px] tracking-wider font-mono">
                <tr>
                  <th class="py-3.5 px-4 w-24">HARI</th>
                  <th class="py-3.5 px-4 w-24">WAKTU</th>
                  <th class="py-3.5 px-4 min-w-[180px]">MOMEN KEGIATAN</th>
                  <th class="py-3.5 px-4 min-w-[200px]">WILAYAH / LOKASI</th>
                  <th class="py-3.5 px-4 min-w-[200px] text-emerald-800">FOTOGRAFER</th>
                  <th class="py-3.5 px-4 min-w-[180px] text-teal-800">VIDEOGRAFER</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-if="!publicData.jadwal_tim || publicData.jadwal_tim.length === 0">
                  <td colspan="6" class="py-10 text-center text-slate-400 text-xs font-medium">
                    Belum ada data jadwal penugasan tim liputan yang dimasukkan.
                  </td>
                </tr>
                <tr
                  v-for="(row, rIdx) in publicData.jadwal_tim"
                  :key="rIdx"
                  class="hover:bg-slate-50 transition"
                  :class="row.hari === 'Kamis' && row.waktu === 'Malam' ? 'bg-amber-50/40' : ''"
                >
                  <td class="py-3 px-4 font-bold">
                    <span
                      class="px-2 py-0.5 rounded text-[10px] font-mono font-bold"
                      :class="row.hari === 'Rabu' ? 'bg-orange-100 text-orange-800 border border-orange-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200'"
                    >
                      {{ row.hari }}
                    </span>
                  </td>
                  <td class="py-3 px-4 text-slate-600 font-mono text-[11px]">
                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200">
                      {{ row.waktu }}
                    </span>
                  </td>
                  <td class="py-3 px-4 font-bold text-slate-900">{{ row.momen }}</td>
                  <td class="py-3 px-4 text-slate-600">{{ row.wilayah }}</td>
                  <td class="py-3 px-4 font-semibold text-emerald-700">{{ row.fotografer || '-' }}</td>
                  <td class="py-3 px-4 font-semibold text-teal-700">{{ row.videografer || '-' }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Table 2: Tim Shooting Multicam (Google Sheet Match) -->
        <div v-else class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
          <!-- Mobile Swipe Hint -->
          <div class="sm:hidden px-3.5 py-1.5 bg-slate-50 border-b border-slate-200 text-[10px] text-slate-500 flex items-center justify-between font-mono">
            <span>👉 Geser ke kanan untuk rincian shooting</span>
            <span class="text-emerald-700 font-bold">Multicam</span>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
              <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[11px] tracking-wider font-mono">
                <tr>
                  <th class="py-3.5 px-4 w-24">HARI</th>
                  <th class="py-3.5 px-4 w-24">WAKTU</th>
                  <th class="py-3.5 px-4 min-w-[180px]">MOMEN KEGIATAN</th>
                  <th class="py-3.5 px-4 min-w-[200px]">WILAYAH / LOKASI</th>
                  <th class="py-3.5 px-4 min-w-[180px] text-orange-800">POSISI SHOOTING</th>
                  <th class="py-3.5 px-4 min-w-[200px] text-emerald-800">KAMERAMEN</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100">
                <tr v-if="!publicData.jadwal_shooting || publicData.jadwal_shooting.length === 0">
                  <td colspan="6" class="py-10 text-center text-slate-400 text-xs font-medium">
                    Belum ada data jadwal penugasan shooting multicam yang dimasukkan.
                  </td>
                </tr>
                <tr
                  v-for="(sRow, sIdx) in publicData.jadwal_shooting"
                  :key="sIdx"
                  class="hover:bg-slate-50 transition"
                >
                  <td class="py-3 px-4 font-bold">
                    <span
                      class="px-2 py-0.5 rounded text-[10px] font-mono font-bold"
                      :class="sRow.hari === 'Rabu' ? 'bg-orange-100 text-orange-800 border border-orange-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200'"
                    >
                      {{ sRow.hari }}
                    </span>
                  </td>
                  <td class="py-3 px-4 text-slate-600 font-mono text-[11px]">
                    <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 border border-slate-200">
                      {{ sRow.waktu }}
                    </span>
                  </td>
                  <td class="py-3 px-4 font-bold text-slate-900">{{ sRow.momen }}</td>
                  <td class="py-3 px-4 text-slate-600">{{ sRow.wilayah }}</td>
                  <td class="py-3 px-4 font-mono font-bold text-orange-700">
                    <span class="px-2 py-0.5 rounded bg-orange-50 border border-orange-200">
                      {{ sRow.shooting }}
                    </span>
                  </td>
                  <td class="py-3 px-4 font-bold text-emerald-700">{{ sRow.kameramen }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </section>

    <!-- ===================================================================== -->
    <!-- BAGIAN 3: PEMBAGIAN TEAM PROYEKTOR (PERSIS FORMAT GOOGLE SHEET)      -->
    <!-- ===================================================================== -->
    <section id="proyektor" class="py-14 bg-white border-t border-slate-200">
      <div class="max-w-6xl mx-auto px-4">
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-8 gap-4">
          <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-[10px] sm:text-xs font-bold mb-2 sm:mb-3 uppercase tracking-wider">
              <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
              <span>Bagian 3: Pembagian Wilayah Proyektor</span>
            </div>
            <h2 class="text-xl sm:text-3xl font-black text-slate-900 tracking-tight">
              Pembagian Team Proyektor &amp; Jalur Kabel
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-2xl leading-relaxed">
              Format resmi pembagian titik operator, penanggung jawab proyektor, dan tim instalasi kabel Haul Ke-5 &amp; Rojabiyyah 2026.
            </p>
          </div>

          <!-- Direct Link to Dedicated Full Detail Page & Search -->
          <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 flex-shrink-0">
            <router-link
              to="/pembagian-proyektor"
              class="inline-flex items-center justify-center space-x-2 px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:brightness-105 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition transform hover:-translate-y-0.5 active:scale-95"
            >
              <span>Buka Halaman Lengkap Proyektor</span>
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            </router-link>

            <!-- Quick Search Filter in Table -->
            <div class="relative w-full sm:w-56">
              <svg class="w-3.5 h-3.5 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="11" cy="11" r="7" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35"/></svg>
              <input
                v-model="proyektorSearch"
                type="text"
                placeholder="Cari korwil, titik..."
                class="w-full pl-9 pr-3 py-2 text-xs bg-slate-50 border border-slate-300 rounded-xl text-slate-900 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:bg-white transition"
              />
            </div>
          </div>
        </div>

        <!-- Optional Uploaded Proyektor Scheme Photos (Read-Only di Publik) -->
        <div v-if="publicData.proyektor_photos && publicData.proyektor_photos.length > 0" class="mb-8">
          <div class="mb-3 flex items-center space-x-2">
            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
            <span class="text-xs font-bold text-slate-700 uppercase tracking-wider font-mono">Bagan &amp; Skema Jalur Proyektor Lapangan</span>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            <div
              v-for="(pPhoto, pIdx) in publicData.proyektor_photos"
              :key="pIdx"
              class="relative rounded-2xl overflow-hidden bg-white border border-slate-200 shadow-sm group cursor-pointer"
              @click="openLightbox(getPhotoUrl(pPhoto), 'Foto Skema Proyektor #' + (pIdx + 1))"
            >
              <img :src="getPhotoUrl(pPhoto)" alt="Foto Skema Proyektor" class="w-full h-48 object-cover group-hover:scale-105 transition duration-300" />
              <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                <span class="px-3 py-1 rounded-full bg-white text-slate-900 font-bold text-xs flex items-center space-x-1.5">
                  <svg class="w-3.5 h-3.5 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                  <span>Perbesar Skema HD</span>
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Master Table: Format Sama Persis Seperti di Google Sheet -->
        <div class="space-y-6">
          <!-- Empty State -->
          <div v-if="filteredProyektorTeams.length === 0" class="p-10 rounded-3xl bg-slate-50 border border-slate-200 text-center">
            <svg class="w-12 h-12 mx-auto text-slate-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            <p class="text-sm font-semibold text-slate-800">Belum Ada Data Pembagian Tim Proyektor</p>
            <p class="text-xs text-slate-500 mt-1 max-w-md mx-auto">
              {{ proyektorSearch ? 'Tidak ada hasil yang cocok dengan pencarian "' + proyektorSearch + '"' : 'Data titik operator dan jalur kabel belum dimasukkan atau dipublikasikan oleh Super Admin.' }}
            </p>
          </div>

          <div
            v-for="(team, tIdx) in filteredProyektorTeams"
            :key="tIdx"
            class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-md"
          >
            <!-- Emerald Gradient Header Bar with Orange Titik Badge (Flyer Style) -->
            <div class="bg-gradient-to-r from-[#059669] to-[#047857] p-4 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-3 shadow-sm border-b border-emerald-800">
              <div class="flex items-center space-x-3">
                <span class="w-8 h-8 rounded-xl bg-[#f97316] text-white flex items-center justify-center font-black text-sm shadow-md">
                  {{ (team.titik || 'A').replace('Titik ', '') }}
                </span>
                <div>
                  <h3 class="text-sm font-black uppercase tracking-wider text-white">{{ team.titik || ('Titik ' + (tIdx + 1)) }}</h3>
                  <div class="text-xs text-emerald-100 flex items-center space-x-1">
                    <span>Koordinator Wilayah (Korwil):</span>
                    <strong class="text-white font-bold underline">{{ team.korwil || '-' }}</strong>
                  </div>
                </div>
              </div>

              <!-- Kabel Team Box -->
              <div class="px-3.5 py-1.5 rounded-xl bg-white/15 border border-white/20 text-xs text-white backdrop-blur-sm">
                <span class="text-emerald-200 text-[10px] uppercase font-bold block">Tim Petugas Kabel:</span>
                <span class="font-bold text-white font-mono">{{ team.kabel || '-' }}</span>
              </div>
            </div>

            <!-- Sub Table per Titik -->
            <!-- Mobile Swipe Hint -->
            <div class="sm:hidden px-3.5 py-1.5 bg-slate-50 border-b border-slate-200 text-[10px] text-slate-500 flex items-center justify-between font-mono">
              <span>👉 Geser ke kanan untuk data petugas &amp; status</span>
              <span class="text-emerald-700 font-bold">{{ (team.titik || 'A').toUpperCase() }}</span>
            </div>
            <div class="overflow-x-auto">
              <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 font-mono text-[10px] uppercase tracking-wider font-bold">
                  <tr>
                    <th class="py-2.5 px-4 w-36">KODE PERANGKAT</th>
                    <th class="py-2.5 px-4 min-w-[220px]">WILAYAH / TITIK LOKASI</th>
                    <th class="py-2.5 px-4 min-w-[220px] text-emerald-800">PETUGAS PROYEKTOR</th>
                    <th class="py-2.5 px-4 text-right pr-6">STATUS UNIT</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  <tr
                    v-for="(item, iIdx) in team.items"
                    :key="iIdx"
                    class="hover:bg-slate-50 transition"
                  >
                    <!-- Kode -->
                    <td class="py-3 px-4 font-mono font-bold text-emerald-700">
                      <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200 text-slate-800">
                        {{ item.kode }}
                      </span>
                    </td>
                    <!-- Wilayah -->
                    <td class="py-3 px-4 font-semibold text-slate-900">
                      {{ item.wilayah }}
                    </td>
                    <!-- Petugas Proyektor -->
                    <td class="py-3 px-4 font-bold text-emerald-700">
                      {{ item.petugas }}
                    </td>
                    <!-- Status -->
                    <td class="py-3 px-4 text-right pr-6">
                      <span class="inline-flex items-center space-x-1 text-[10px] text-emerald-700 font-semibold font-mono bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        <span>TERJADWAL</span>
                      </span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- ===================================================================== -->
    <!-- BAGIAN 4: KALENDER ACARA & AGENDA KEGIATAN HAUL (VRISTO CALENDAR)     -->
    <!-- ===================================================================== -->
    <section id="kalender" class="py-12 sm:py-16 bg-[#f8fafc] border-t border-slate-200">
      <div class="max-w-6xl mx-auto px-4">
        <!-- Section Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-6 sm:mb-8 gap-3 sm:gap-4">
          <div>
            <div class="inline-flex items-center space-x-2 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-800 text-[10px] sm:text-xs font-bold mb-2 sm:mb-3 uppercase tracking-wider">
              <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01"/></svg>
              <span>Bagian 4: Kalender Agenda &amp; Waktu</span>
            </div>
            <h2 class="text-xl sm:text-3xl font-black text-slate-900 tracking-tight">
              Kalender Acara Haul &amp; Rojabiyyah 2026
            </h2>
            <p class="text-xs sm:text-sm text-slate-600 mt-1 max-w-2xl leading-relaxed">
              Jadwal pelaksanaan rangkaian acara Haul Ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad, rapat panitia, gladi bersih media, serta agenda sholawat ISHARI. Klik tanggal atau judul agenda untuk rincian lengkap.
            </p>
          </div>

          <!-- Quick Indicator -->
          <div class="flex items-center space-x-2 self-start md:self-auto">
            <span class="px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-emerald-700 font-mono text-[11px] sm:text-xs font-bold flex items-center space-x-2 shadow-sm">
              <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
              <span>{{ (publicData.calendar_events || []).length }} Agenda Terjadwal</span>
            </span>
          </div>
        </div>

        <!-- Interactive Vristo Calendar -->
        <VristoCalendar
          :events="publicData.calendar_events || []"
          :editable="false"
        />
      </div>
    </section>

    <!-- 4. Footer Publik -->
    <footer class="border-t border-slate-200 bg-white py-8 text-xs text-slate-600">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-4 text-center sm:text-left">
        <div>
          <div class="font-bold text-slate-900 text-sm">Media Rojabiyyah 2026</div>
          <p class="text-[11px] text-slate-500 mt-0.5">Pondok Pesantren Al-Muhibbin Tambakberas Jombang, Jawa Timur</p>
        </div>

        <div class="flex items-center space-x-4">
          <router-link to="/register-member" class="text-slate-600 hover:text-emerald-700 transition">Formulir Anggota</router-link>
          <span>&bull;</span>
          <router-link to="/register-account" class="text-slate-600 hover:text-emerald-700 transition">Registrasi Akun</router-link>
          <span>&bull;</span>
          <button @click="showLoginModal = true" class="text-slate-600 hover:text-emerald-700 transition">Login Petugas</button>
        </div>

        <div class="text-[11px] text-slate-500 font-mono">
          {{ settingsStore.settings?.footer_copyright || '© 2026 Haul Ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad' }}
        </div>
      </div>
    </footer>

    <!-- Image Lightbox Modal (Zoom Fullscreen) -->
    <div
      v-if="lightbox.show"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/90 backdrop-blur-md"
      @click="lightbox.show = false"
    >
      <div class="relative max-w-5xl w-full max-h-[90vh] flex flex-col items-center" @click.stop>
        <button
          @click="lightbox.show = false"
          class="absolute -top-12 right-0 text-white hover:text-rose-400 text-2xl font-bold p-2 transition"
        >
          ✕
        </button>
        <img
          :src="lightbox.url"
          :alt="lightbox.title"
          class="max-w-full max-h-[82vh] object-contain rounded-2xl border border-slate-800 shadow-2xl"
        />
        <p class="text-xs text-slate-300 font-semibold mt-3 text-center">{{ lightbox.title }}</p>
      </div>
    </div>

    <!-- Login Modal -->
    <LoginModal :show="showLoginModal" @close="showLoginModal = false" />
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import LoginModal from '../../components/LoginModal.vue';
import VristoCalendar from '../../components/VristoCalendar.vue';
import { useSettingsStore } from '../../stores/settings';
import api from '../../services/api';

const settingsStore = useSettingsStore();
const showLoginModal = ref(false);
const mobileMenuOpen = ref(false);

const denahFileInputRef = ref(null);
const jadwalFileInputRef = ref(null);
const uploadingDenah = ref(false);
const uploadingJadwal = ref(false);

const activeJadwalTab = ref('liputan'); // 'liputan' | 'shooting'
const proyektorSearch = ref('');

const lightbox = ref({
  show: false,
  url: '',
  title: '',
});

const openLightbox = (url, title = 'Pratinjau Foto') => {
  lightbox.value = {
    show: true,
    url,
    title,
  };
};

const publicData = ref({
  stats: { total_volunteers: 0, total_regions: 0, total_items: 0 },
  regions: [],
  denah_photos: [
    '/uploads/denah/denah_proyektor_1.jpg',
    'https://images.unsplash.com/photo-1517457373958-b7bdd4587205?auto=format&fit=crop&w=1200&q=80',
    'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=80',
  ],
  jadwal_photos: [],
  proyektor_teams: [],
  jadwal_tim: [],
  jadwal_shooting: [],
  calendar_events: [],
});

const denahPhotosList = computed(() => {
  const p = publicData.value.denah_photos || [];
  return p.length > 0 ? p : ['/uploads/denah/denah_proyektor_1.jpg'];
});

const activeDenahIndex = ref(0);
const activeDenahPhoto = computed(() => {
  return denahPhotosList.value[activeDenahIndex.value] || denahPhotosList.value[0];
});

const nextDenah = () => {
  if (denahPhotosList.value.length === 0) return;
  activeDenahIndex.value = (activeDenahIndex.value + 1) % denahPhotosList.value.length;
};

const prevDenah = () => {
  if (denahPhotosList.value.length === 0) return;
  activeDenahIndex.value = (activeDenahIndex.value - 1 + denahPhotosList.value.length) % denahPhotosList.value.length;
};

const filteredProyektorTeams = computed(() => {
  const q = (proyektorSearch.value || '').toLowerCase().trim();
  const list = publicData.value.proyektor_teams || [];
  if (!q) return list;

  return list
    .map((team) => {
      const matchTitik = ((team.titik || '') + ' ' + (team.korwil || '') + ' ' + (team.kabel || ''))
        .toLowerCase()
        .includes(q);
      const matchedItems = (team.items || []).filter((item) => {
        return (
          (item.kode || '').toLowerCase().includes(q) ||
          (item.wilayah || '').toLowerCase().includes(q) ||
          (item.petugas || '').toLowerCase().includes(q)
        );
      });

      if (matchTitik) return team;
      if (matchedItems.length > 0) {
        return { ...team, items: matchedItems };
      }
      return null;
    })
    .filter(Boolean);
});

// Photo URL Helper (Safe for local dev & production)
const getPhotoUrl = (img) => {
  if (!img) return '/hero-bg.png';
  if (img.startsWith('data:') || img.startsWith('http://') || img.startsWith('https://')) return img;
  const backendBase = import.meta.env.VITE_API_URL?.replace(/\/api\/v1\/?$/, '') || 'http://127.0.0.1:8000';
  return backendBase + (img.startsWith('/') ? img : '/' + img);
};

// Carousel Slides
const getHeroSlideImage = (img) => getPhotoUrl(img);

const defaultSlides = [
  {
    image: '/uploads/denah/denah_proyektor_1.jpg',
    title: 'Media Rojabiyyah Haul Ke-5 & Rojabiyyah 2026',
    subtitle: 'TIM LOGISTIK & DOKUMENTASI',
    subtext: 'Haul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad & Isro\' Mi\'roj 1447 H',
  },
  {
    image: 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1600&q=80',
    title: 'Koordinasi Tim & Posko Lapangan',
    subtitle: 'DIVISI MULTIMEDIA & DOKUMENTASI',
    subtext: 'Live Streaming Multi-Kamera, Dokumentasi Foto & Audio Sound System',
  },
  {
    image: 'https://images.unsplash.com/photo-1523580494863-6f3031224c94?auto=format&fit=crop&w=1600&q=80',
    title: 'Inventaris & Logistik Terpadu',
    subtitle: 'PENGELOLAAN ASET RESMI BER-QR CODE',
    subtext: 'Pencatatan Peminjaman Digital, Cepat, dan Akuntabel',
  },
];

const slides = ref([...defaultSlides]);
const currentSlide = ref(0);
const currentSlideData = computed(() => slides.value[currentSlide.value] || defaultSlides[0]);
let autoSlideTimer = null;

const nextSlide = () => {
  if (slides.value.length === 0) return;
  currentSlide.value = (currentSlide.value + 1) % slides.value.length;
};

const prevSlide = () => {
  if (slides.value.length === 0) return;
  currentSlide.value = (currentSlide.value - 1 + slides.value.length) % slides.value.length;
};

const compressImage = (file, maxWidth = 1600, quality = 0.85) => {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = (event) => {
      const img = new Image();
      img.src = event.target.result;
      img.onload = () => {
        let width = img.width;
        let height = img.height;
        if (width > maxWidth || height > maxWidth) {
          if (width > height) {
            height = Math.round((height * maxWidth) / width);
            width = maxWidth;
          } else {
            width = Math.round((width * maxWidth) / height);
            height = maxWidth;
          }
        }
        const canvas = document.createElement('canvas');
        canvas.width = width;
        canvas.height = height;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(img, 0, 0, width, height);
        resolve(canvas.toDataURL('image/jpeg', quality));
      };
      img.onerror = (err) => reject(err);
    };
    reader.onerror = (err) => reject(err);
  });
};

const handleUploadDenah = async (e) => {
  const files = Array.from(e.target.files);
  if (!files.length) return;
  uploadingDenah.value = true;
  try {
    const promises = files.map((file) => compressImage(file, 1600, 0.85));
    const base64List = await Promise.all(promises);
    const res = await api.post('/public/upload-denah', { photos: base64List });
    publicData.value.denah_photos = res.data.denah_photos;
    alert('Foto denah proyektor berhasil diunggah!');
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal mengunggah foto denah.');
  } finally {
    uploadingDenah.value = false;
    if (denahFileInputRef.value) denahFileInputRef.value.value = '';
  }
};

const deleteDenah = async (index) => {
  if (confirm('Yakin ingin menghapus foto denah ini?')) {
    try {
      const res = await api.post('/public/delete-denah', { index });
      publicData.value.denah_photos = res.data.denah_photos;
    } catch (err) {
      alert('Gagal menghapus foto denah.');
    }
  }
};

const handleUploadJadwal = async (e) => {
  const files = Array.from(e.target.files);
  if (!files.length) return;
  uploadingJadwal.value = true;
  try {
    const promises = files.map((file) => compressImage(file, 1600, 0.85));
    const base64List = await Promise.all(promises);
    const res = await api.post('/public/upload-jadwal', { photos: base64List });
    publicData.value.jadwal_photos = res.data.jadwal_photos;
    alert('Foto jadwal kerja berhasil diunggah!');
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal mengunggah foto jadwal.');
  } finally {
    uploadingJadwal.value = false;
    if (jadwalFileInputRef.value) jadwalFileInputRef.value.value = '';
  }
};

const deleteJadwal = async (index) => {
  if (confirm('Yakin ingin menghapus foto jadwal ini?')) {
    try {
      const res = await api.post('/public/delete-jadwal', { index });
      publicData.value.jadwal_photos = res.data.jadwal_photos;
    } catch (err) {
      alert('Gagal menghapus foto jadwal.');
    }
  }
};

const fetchPublicOverview = async () => {
  try {
    const res = await api.get('/public/overview');
    if (res.data) {
      publicData.value = {
        ...publicData.value,
        ...res.data,
        proyektor_teams: res.data.proyektor_teams || [],
        jadwal_tim: res.data.jadwal_tim || [],
        jadwal_shooting: res.data.jadwal_shooting || [],
        proyektor_photos: res.data.proyektor_photos || [],
        jadwal_photos: res.data.jadwal_photos || [],
        denah_photos: res.data.denah_photos || [],
        calendar_events: res.data.calendar_events || [],
      };
    }

    if (res.data?.sliders && res.data.sliders.length > 0) {
      slides.value = res.data.sliders.map((s) => ({
        id: s.id,
        image: s.image_path,
        title: s.title || 'Media Rojabiyyah 2026',
        subtitle: s.title || 'DOKUMENTASI ROJABIYYAH 2026',
        subtext: s.description || 'Haul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad & Rojabiyyah 2026',
      }));
      currentSlide.value = 0;
    }
  } catch (err) {
    console.warn('Gagal memuat overview publik:', err);
  }
};

const handleImageError = (e, slide) => {
  if (slide?.image?.startsWith('/uploads/') && !e.target.src.includes(':8000')) {
    e.target.src = 'http://127.0.0.1:8000' + slide.image;
  }
};

onMounted(() => {
  settingsStore.fetchSettings();
  fetchPublicOverview();
  autoSlideTimer = setInterval(nextSlide, 7000);
});

onUnmounted(() => {
  if (autoSlideTimer) clearInterval(autoSlideTimer);
});
</script>
