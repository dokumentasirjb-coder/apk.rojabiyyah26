<template>
  <DashboardLayout>
    <div class="space-y-6">
      <!-- Header -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <div class="flex items-center space-x-2.5">
            <div class="w-9 h-9 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 shadow-xs">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <div>
              <h2 class="text-xl font-bold text-slate-800 tracking-tight">Kelola Halaman Utama Publik</h2>
              <p class="text-xs text-slate-500 mt-0.5">Pusat kendali mandiri Super Admin untuk mengatur seluruh konten informasi, denah, jadwal, dan pembagian tim yang tampil di halaman depan.</p>
            </div>
          </div>
        </div>

        <div class="flex items-center space-x-2">
          <router-link
            to="/"
            target="_blank"
            class="inline-flex items-center space-x-2 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold shadow-sm transition"
          >
            <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><line x1="2" y1="12" x2="22" y2="12" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
            <span>Buka Halaman Depan</span>
            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
          </router-link>
        </div>
      </div>

      <!-- Main Navigation Tabs (Clean Light SaaS Style) -->
      <div class="flex items-center space-x-2 border-b border-slate-200 pb-2 overflow-x-auto">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          @click="activeTab = tab.id"
          :class="[
            'px-4 py-2.5 rounded-2xl text-xs font-bold transition flex items-center space-x-2 whitespace-nowrap shadow-xs',
            activeTab === tab.id
              ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-emerald-500/20'
              : 'text-slate-600 hover:text-slate-900 bg-white border border-slate-200 hover:bg-slate-50'
          ]"
        >
          <!-- Dynamic Crisp Line SVG Icons -->
          <svg v-if="tab.id === 'denah'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
          <svg v-else-if="tab.id === 'slider'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15l-5-5L5 21"/></svg>
          <svg v-else-if="tab.id === 'kalender'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01"/></svg>
          <svg v-else-if="tab.id === 'proyektor'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
          <svg v-else-if="tab.id === 'jadwal'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01"/></svg>
          <svg v-else-if="tab.id === 'hero'" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>

          <span>{{ tab.name }}</span>
        </button>
      </div>

      <!-- =================================================================== -->
      <!-- TAB 1: DENAH PROYEKTOR GLOBAL (1 BESAR BERGANTIAN + UPLOAD)        -->
      <!-- =================================================================== -->
      <div v-if="activeTab === 'denah'" class="space-y-6">
        <!-- Top Action & Info -->
        <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[11px] font-bold mb-2">
              <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
              <span>Kelola Galeri Foto Denah Global</span>
            </div>
            <h3 class="text-base font-bold text-slate-800">Foto Denah Peta &amp; Titik Proyektor Lapangan</h3>
            <p class="text-xs text-slate-500 mt-1">Unggah minimal 3 foto resolusi tinggi (foto udara drone, peta sebaran kabel, titik panggung). Tampilan di halaman utama akan berbentuk 1 foto besar yang bisa bergantian.</p>
          </div>

          <div class="flex items-center space-x-3 flex-shrink-0">
            <input
              type="file"
              ref="denahFileInputRef"
              accept="image/*"
              multiple
              class="hidden"
              @change="handleUploadDenah"
            />
            <button
              @click="$refs.denahFileInputRef.click()"
              :disabled="uploadingDenah"
              class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold text-xs shadow-md shadow-emerald-500/20 flex items-center space-x-2 transition disabled:opacity-50"
            >
              <span v-if="uploadingDenah" class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
              <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="4" stroke-width="2"/></svg>
              <span>{{ uploadingDenah ? 'Mengunggah...' : '+ Unggah Foto Denah Baru' }}</span>
            </button>
          </div>
        </div>

        <!-- Live Large Interactive Viewer -->
        <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-sm space-y-4">
          <div class="flex items-center justify-between text-xs">
            <div class="flex items-center space-x-2 text-emerald-700 font-bold uppercase tracking-wider text-[11px]">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              <span>Pratinjau Foto Denah Utama (Tampilan Besar Halaman Depan):</span>
            </div>
            <span class="text-slate-500 font-mono text-[11px]">
              Foto {{ currentDenahIndex + 1 }} dari {{ denahList.length }}
            </span>
          </div>

          <!-- Featured Large Display Frame -->
          <div class="relative w-full h-72 sm:h-96 md:h-[460px] rounded-2xl overflow-hidden bg-slate-100 border border-slate-200">
            <img
              v-if="currentDenahPhoto"
              :src="getPhotoUrl(currentDenahPhoto)"
              alt="Denah Proyektor Utama"
              class="w-full h-full object-cover object-center transition-all duration-500"
            />
            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-black/20 pointer-events-none"></div>

            <!-- Arrow Buttons -->
            <button
              @click="prevDenah"
              class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 hover:bg-white text-slate-800 flex items-center justify-center border border-slate-200 backdrop-blur-md shadow-md transition"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button
              @click="nextDenah"
              class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 rounded-full bg-white/90 hover:bg-white text-slate-800 flex items-center justify-center border border-slate-200 backdrop-blur-md shadow-md transition"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>

            <!-- Bottom Left Info -->
            <div class="absolute bottom-4 left-4 z-10">
              <span class="px-2.5 py-1 rounded-lg bg-emerald-500 text-white font-black text-[11px] shadow">
                Denah Aktif #{{ currentDenahIndex + 1 }}
              </span>
              <p class="text-white font-bold text-sm drop-shadow-md mt-1">Peta Persebaran Titik Proyektor &amp; Kabel Ponpes Al-Muhibbin</p>
            </div>
          </div>

          <!-- Bottom Thumbnails Selector -->
          <div>
            <p class="text-[11px] text-slate-500 font-semibold mb-2">Pilih Thumbnail untuk Berganti Tampilan:</p>
            <div class="flex items-center space-x-3 overflow-x-auto pb-2">
              <div
                v-for="(photo, idx) in denahList"
                :key="idx"
                @click="currentDenahIndex = idx"
                :class="[
                  'relative w-28 h-20 rounded-xl overflow-hidden cursor-pointer flex-shrink-0 transition border-2 shadow-xs',
                  currentDenahIndex === idx ? 'border-emerald-500 scale-105 shadow-md shadow-emerald-500/20' : 'border-slate-200 opacity-70 hover:opacity-100 hover:border-slate-300'
                ]"
              >
                <img :src="getPhotoUrl(photo)" class="w-full h-full object-cover" />
                <span class="absolute bottom-1 right-1 px-1.5 py-0.2 bg-black/70 text-white text-[9px] font-mono rounded">
                  #{{ idx + 1 }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Daftar & Hapus Foto Denah -->
        <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-sm space-y-4">
          <div class="flex items-center justify-between">
            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Daftar Foto Denah Tersimpan ({{ denahList.length }} Foto)</h4>
            <span class="text-[11px] text-slate-500">Minimal 1 foto denah aktif</span>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <div
              v-for="(photo, idx) in denahList"
              :key="idx"
              class="p-3 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between space-x-3"
            >
              <div class="flex items-center space-x-3 min-w-0">
                <div class="w-14 h-14 rounded-xl overflow-hidden bg-slate-200 flex-shrink-0">
                  <img :src="getPhotoUrl(photo)" class="w-full h-full object-cover" />
                </div>
                <div class="min-w-0">
                  <p class="text-xs font-bold text-slate-800 truncate">Denah Proyektor #{{ idx + 1 }}</p>
                  <p class="text-[10px] font-mono text-slate-500 truncate">{{ photo }}</p>
                </div>
              </div>

              <button
                v-if="denahList.length > 1"
                @click="deleteDenah(idx)"
                class="p-2 rounded-xl text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-200 transition flex-shrink-0"
                title="Hapus foto ini"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- =================================================================== -->
      <!-- TAB 2: BANNER SLIDER BERANDA                                       -->
      <!-- =================================================================== -->
      <div v-if="activeTab === 'slider'" class="space-y-6">
        <div class="flex items-center justify-between">
          <div>
            <h3 class="text-base font-bold text-slate-800">Banner Slider Halaman Depan</h3>
            <p class="text-xs text-slate-500 mt-0.5">Atur foto-foto banner dokumentasi yang berputar otomatis di carousel bagian atas beranda.</p>
          </div>
          <router-link
            to="/sliders"
            class="px-4 py-2 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white text-xs font-bold transition flex items-center space-x-1.5 shadow-md shadow-emerald-500/20"
          >
            <span>+ Tambah &amp; Atur Slider Detail</span>
          </router-link>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <div
            v-for="(s, idx) in sliders"
            :key="s.id"
            class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm flex flex-col justify-between"
          >
            <div class="h-44 bg-slate-100 relative overflow-hidden">
              <img :src="getPhotoUrl(s.image_path)" class="w-full h-full object-cover" />
              <div class="absolute top-3 left-3 px-2 py-0.5 bg-[#f97316] text-white font-black text-[10px] rounded shadow">
                #{{ s.sort_order || idx + 1 }}
              </div>
            </div>
            <div class="p-4 space-y-1 flex-1 flex flex-col justify-between">
              <div>
                <h4 class="font-bold text-slate-800 text-xs truncate">{{ s.title || 'Tanpa Judul' }}</h4>
                <p class="text-[11px] text-slate-500 truncate">{{ s.description || 'Tanpa Keterangan' }}</p>
              </div>
              <div class="pt-2 text-[10px] font-mono text-emerald-700">ID: {{ s.id }}</div>
            </div>
          </div>
        </div>
      </div>

      <!-- =================================================================== -->
      <!-- TAB: KALENDER AGENDA (VRISTO CALENDAR)                             -->
      <!-- =================================================================== -->
      <div v-if="activeTab === 'kalender'" class="space-y-6">
        <!-- Top Action & Info -->
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[11px] font-bold mb-2">
              <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01"/></svg>
              <span>Kalender Interaktif Template Vristo</span>
            </div>
            <h3 class="text-base font-bold text-slate-800">Kelola Kalender Agenda &amp; Jadwal Kegiatan</h3>
            <p class="text-xs text-slate-500 mt-1">
              Atur seluruh agenda acara, rapat koordinasi, gladi bersih, dan instalasi proyektor. Data otomatis sinkron dengan kalender di halaman utama publik.
            </p>
          </div>

          <div class="flex items-center space-x-3 flex-shrink-0">
            <button
              @click="saveCalendarToServer"
              :disabled="savingCalendar"
              class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold text-xs shadow-md shadow-emerald-500/20 flex items-center space-x-2 transition disabled:opacity-50"
            >
              <span v-if="savingCalendar" class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
              <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
              <span>{{ savingCalendar ? 'Menyimpan...' : 'Simpan Seluruh Kalender' }}</span>
            </button>
          </div>
        </div>

        <!-- Interactive Vristo Calendar Component in Admin Mode -->
        <VristoCalendar
          :events="calendarEvents"
          :editable="true"
          @save="handleCalendarSave"
          @delete="handleCalendarDelete"
        />
      </div>

      <!-- =================================================================== -->
      <!-- TAB 3: PEMBAGIAN TIM PROYEKTOR (FORM + GAMBAR SKEMA)                -->
      <!-- =================================================================== -->
      <div v-if="activeTab === 'proyektor'" class="space-y-6">
        <!-- 1. Top Section: Upload Foto / Bagan Skema Jalur Proyektor & Kabel -->
        <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[11px] font-bold mb-2">
              <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15l-5-5L5 21"/></svg>
              <span>Foto / Skema Bagan Jalur Proyektor</span>
            </div>
            <h3 class="text-base font-bold text-slate-800">Unggah Gambar Skema &amp; Jalur Kabel Proyektor</h3>
            <p class="text-xs text-slate-500 mt-1">Unggah bagan layout, skema kabel FO/LAN, atau denah posisi LCD &amp; proyektor agar tampil di halaman utama publik.</p>
          </div>

          <div class="flex items-center space-x-3 flex-shrink-0">
            <input
              type="file"
              ref="proyektorFileInputRef"
              accept="image/*"
              multiple
              class="hidden"
              @change="handleUploadProyektorPhoto"
            />
            <button
              @click="$refs.proyektorFileInputRef.click()"
              :disabled="uploadingProyektorPhoto"
              class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold text-xs shadow-md shadow-emerald-500/20 flex items-center space-x-2 transition disabled:opacity-50"
            >
              <span v-if="uploadingProyektorPhoto" class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
              <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="4" stroke-width="2"/></svg>
              <span>{{ uploadingProyektorPhoto ? 'Mengunggah...' : '+ Unggah Gambar Skema' }}</span>
            </button>
          </div>
        </div>

        <!-- Daftar Gambar Skema Proyektor Terunggah -->
        <div v-if="proyektorPhotos.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <div
            v-for="(photo, idx) in proyektorPhotos"
            :key="idx"
            class="bg-white border border-slate-200 rounded-2xl overflow-hidden p-3 flex items-center justify-between space-x-3 shadow-xs"
          >
            <div class="flex items-center space-x-3 min-w-0">
              <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0">
                <img :src="getPhotoUrl(photo)" class="w-full h-full object-cover" />
              </div>
              <div class="min-w-0">
                <p class="text-xs font-bold text-slate-800 truncate">Skema Proyektor #{{ idx + 1 }}</p>
                <p class="text-[10px] font-mono text-slate-500 truncate">{{ photo }}</p>
              </div>
            </div>
            <button
              @click="deleteProyektorPhoto(idx)"
              class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition"
              title="Hapus foto skema ini"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
          </div>
        </div>

        <!-- 2. Bottom Section: Isi Form Titik A sampai Titik F (Format Google Sheet) -->
        <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[11px] font-bold mb-2">
              <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
              <span>Isi Form Master Titik Proyektor &amp; Korwil</span>
            </div>
            <h3 class="text-base font-bold text-slate-800">Data Titik A sampai Titik F (Format Google Sheet)</h3>
            <p class="text-xs text-slate-500 mt-1">Kelola nama Koordinator Wilayah (Korwil), Tim Kabel, dan penugasan per perangkat (LCD &amp; Proyektor) yang sinkron langsung dengan halaman utama publik.</p>
            <div class="mt-2 flex flex-wrap items-center gap-2">
              <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-[11px] font-semibold">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span>Terintegrasi otomatis dengan <strong>{{ uniqueMemberNames.length }} Anggota</strong> terdaftar (Akun Login &amp; Formulir)</span>
              </span>
              <button
                type="button"
                @click="refreshMembers"
                :disabled="loadingMembers"
                class="inline-flex items-center space-x-1 text-[11px] text-slate-500 hover:text-emerald-700 transition"
                title="Muat ulang data anggota terbaru"
              >
                <svg class="w-3 h-3" :class="{ 'animate-spin': loadingMembers }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                <span>{{ loadingMembers ? 'Menyinkron...' : 'Sinkronkan Anggota' }}</span>
              </button>
            </div>
          </div>

          <div class="flex flex-wrap items-center gap-2.5">
            <router-link
              to="/pembagian-proyektor"
              target="_blank"
              class="px-3.5 py-2.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-bold text-xs flex items-center space-x-1.5 shadow-sm transition"
            >
              <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
              <span>Halaman Publik ↗</span>
            </router-link>

            <button
              @click="addProyektorTitik"
              class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 border border-slate-200 font-bold text-xs flex items-center space-x-1.5 transition"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
              <span>+ Tambah Titik Baru</span>
            </button>

            <button
              @click="saveProyektorTeams"
              :disabled="savingProyektor"
              class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold text-xs shadow-md shadow-emerald-500/20 flex items-center space-x-2 transition disabled:opacity-50"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
              <span>{{ savingProyektor ? 'Menyimpan...' : 'Simpan Pembagian Proyektor' }}</span>
            </button>
          </div>
        </div>

        <!-- Accordion / Titik Cards Editor -->
        <div class="space-y-4">
          <div
            v-for="(team, tIdx) in proyektorTeams"
            :key="tIdx"
            class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm"
          >
            <!-- Emerald Gradient Header Bar with Orange Titik Badge (Flyer Theme) -->
            <div class="bg-gradient-to-r from-[#059669] to-[#10b981] px-5 py-3.5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-white border-b border-emerald-600">
              <div class="flex items-center space-x-3">
                <span class="w-8 h-8 rounded-xl bg-[#f97316] text-white font-black text-sm flex items-center justify-center shadow-md">{{ team.titik.replace('Titik ', '') }}</span>
                <div>
                  <h4 class="font-extrabold text-sm tracking-wide">{{ team.titik }}</h4>
                  <p class="text-[11px] text-emerald-100">Total {{ team.items?.length || 0 }} Perangkat &amp; Posisi</p>
                </div>
              </div>

              <!-- Inline Korwil & Kabel Inputs & WhatsApp Triggers -->
              <div class="flex flex-wrap items-center gap-2 text-xs">
                <div class="flex items-center space-x-1.5 bg-black/20 px-3 py-1.5 rounded-xl border border-white/20">
                  <span class="text-white font-bold">Korwil:</span>
                  <input
                    v-model="team.korwil"
                    type="text"
                    list="registeredMembersList"
                    placeholder="Nama Korwil"
                    class="bg-transparent border-b border-white/60 focus:outline-none text-white font-semibold text-xs px-1 w-28 sm:w-36 placeholder-white/70"
                  />
                  <button
                    v-if="team.korwil"
                    type="button"
                    @click="notifyViaWhatsApp(team.korwil, `Koordinator Wilayah (Korwil) ${team.titik}`, { titik: team.titik, role: 'Korwil' })"
                    class="p-1 rounded bg-emerald-600 hover:bg-emerald-500 text-white transition flex items-center justify-center shadow-xs"
                    :title="`Kirim Notifikasi WA ke Korwil (${team.korwil})`"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.652c1.746.951 3.71 1.452 5.711 1.453h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                  </button>
                </div>

                <div class="flex items-center space-x-1.5 bg-black/20 px-3 py-1.5 rounded-xl border border-white/20">
                  <span class="text-white font-bold">Kabel:</span>
                  <input
                    v-model="team.kabel"
                    type="text"
                    list="registeredMembersList"
                    placeholder="Petugas Kabel"
                    class="bg-transparent border-b border-white/60 focus:outline-none text-white font-semibold text-xs px-1 w-32 sm:w-44 placeholder-white/70"
                  />
                  <button
                    v-if="team.kabel"
                    type="button"
                    @click="notifyViaWhatsApp(team.kabel, `Tim Jalur Kabel & FO ${team.titik}`, { titik: team.titik, role: 'Tim Kabel & FO' })"
                    class="p-1 rounded bg-emerald-600 hover:bg-emerald-500 text-white transition flex items-center justify-center shadow-xs"
                    :title="`Kirim Notifikasi WA ke Tim Kabel (${team.kabel})`"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.652c1.746.951 3.71 1.452 5.711 1.453h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                  </button>
                </div>

                <!-- Quick Broadcast / Pilih Petugas di Titik -->
                <button
                  type="button"
                  @click="notifyAllInTitik(team)"
                  class="p-1.5 bg-white/20 hover:bg-white text-white hover:text-emerald-800 rounded-xl transition flex items-center space-x-1 text-[11px] font-bold"
                  :title="`Kirim Notifikasi WA ke salah satu petugas di ${team.titik}`"
                >
                  <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.652c1.746.951 3.71 1.452 5.711 1.453h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                  <span class="hidden md:inline">Kirim WA Titik</span>
                </button>

                <button
                  v-if="proyektorTeams.length > 1"
                  @click="removeProyektorTitik(tIdx)"
                  class="p-1.5 text-white/80 hover:text-white hover:bg-rose-600 rounded-lg transition"
                  title="Hapus seluruh titik ini"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
              </div>
            </div>

            <!-- Items Table in Titik -->
            <div class="p-4 overflow-x-auto">
              <table class="w-full text-left text-xs border-collapse">
                <thead>
                  <tr class="text-[11px] font-bold text-slate-500 border-b border-slate-200 pb-2">
                    <th class="py-2 px-3 w-12">#</th>
                    <th class="py-2 px-3 w-36">Kode Perangkat</th>
                    <th class="py-2 px-3">Wilayah / Titik Lokasi</th>
                    <th class="py-2 px-3">Petugas Bertanggung Jawab</th>
                    <th class="py-2 px-3 text-right w-20">Aksi</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  <tr v-for="(item, iIdx) in team.items" :key="iIdx" class="hover:bg-slate-50/80 transition">
                    <td class="py-2.5 px-3 font-mono text-slate-400">{{ iIdx + 1 }}</td>
                    <td class="py-2.5 px-3">
                      <input
                        v-model="item.kode"
                        type="text"
                        placeholder="LCD 1 / Proyektor 1"
                        class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-800 font-mono text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                      />
                    </td>
                    <td class="py-2.5 px-3">
                      <input
                        v-model="item.wilayah"
                        type="text"
                        placeholder="Nama Lokasi / Area"
                        class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-800 text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                      />
                    </td>
                    <td class="py-2.5 px-3">
                      <div class="flex items-center space-x-1.5">
                        <input
                          v-model="item.petugas"
                          type="text"
                          list="registeredMembersList"
                          placeholder="Nama Relawan / Petugas"
                          class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-800 text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                        />
                        <button
                          v-if="item.petugas"
                          type="button"
                          @click="notifyViaWhatsApp(item.petugas, `Petugas Display Layar (${item.kode || 'Perangkat'})`, { titik: team.titik, lokasi: item.wilayah, kode: item.kode })"
                          class="p-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white border border-emerald-200 transition flex items-center justify-center flex-shrink-0 shadow-xs"
                          :title="`Kirim Notifikasi WA ke ${item.petugas}`"
                        >
                          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.652c1.746.951 3.71 1.452 5.711 1.453h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        </button>
                      </div>
                    </td>
                    <td class="py-2.5 px-3 text-right">
                      <button
                        @click="removeProyektorItem(tIdx, iIdx)"
                        class="p-1.5 text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition"
                        title="Hapus baris ini"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>

              <div class="mt-3 flex justify-end">
                <button
                  @click="addProyektorItem(tIdx)"
                  class="px-3 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-emerald-700 text-xs font-semibold flex items-center space-x-1 transition"
                >
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                  <span>+ Tambah Perangkat di {{ team.titik }}</span>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- 3. Instruksi & SOP Troubleshooting Proyektor Section -->
        <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-sm space-y-4">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
              <div class="w-8 h-8 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center font-bold text-sm">
                ℹ️
              </div>
              <div>
                <h4 class="text-sm font-bold text-slate-800">SOP Troubleshooting &amp; Prosedur Cepat Kendala Proyektor</h4>
                <p class="text-xs text-slate-500">Poin instruksi panduan teknis yang tampil di bagian bawah halaman Pembagian Proyektor Publik.</p>
              </div>
            </div>
            <button
              @click="addProyektorSop"
              class="px-3 py-1.5 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 text-xs font-bold border border-amber-200 flex items-center space-x-1 transition"
            >
              <span>+ Tambah Poin SOP</span>
            </button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
            <div
              v-for="(sop, sIdx) in proyektorSop"
              :key="sIdx"
              class="p-4 rounded-2xl bg-amber-50/40 border border-amber-200 space-y-3 relative group"
            >
              <div class="flex items-center justify-between">
                <span class="text-[11px] font-black uppercase text-amber-700 tracking-wider">Poin {{ sIdx + 1 }}</span>
                <button
                  @click="removeProyektorSop(sIdx)"
                  class="text-rose-500 hover:text-rose-700 text-xs font-bold"
                  title="Hapus SOP"
                >
                  ✕
                </button>
              </div>
              <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Judul SOP / Kendala:</label>
                <input
                  v-model="sop.title"
                  type="text"
                  placeholder="misal: Layar Mati / No Signal"
                  class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 font-bold focus:outline-none focus:border-amber-500"
                />
              </div>
              <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Deskripsi &amp; Langkah Penanganan:</label>
                <textarea
                  v-model="sop.desc"
                  rows="3"
                  placeholder="Langkah penanganan..."
                  class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-amber-500 resize-none leading-relaxed"
                ></textarea>
              </div>
            </div>
          </div>

          <div class="flex justify-end pt-2">
            <button
              @click="saveProyektorTeams"
              :disabled="savingProyektor"
              class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition disabled:opacity-50 flex items-center space-x-1.5"
            >
              <span>{{ savingProyektor ? 'Menyimpan...' : 'Simpan SOP &amp; Pembagian Proyektor' }}</span>
            </button>
          </div>
        </div>
      </div>

      <!-- =================================================================== -->
      <!-- TAB 4: JADWAL KERJA TIM (FORM LENGKAP + GAMBAR POSTER)              -->
      <!-- =================================================================== -->
      <div v-if="activeTab === 'jadwal'" class="space-y-6">
        <!-- 1. Top Section: Unggah Poster Visual Jadwal Kerja (Gambar) -->
        <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
          <div>
            <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-indigo-50 border border-indigo-200 text-indigo-700 text-[11px] font-bold mb-2">
              <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h18"/></svg>
              <span>Poster Visual Jadwal Kerja</span>
            </div>
            <h3 class="text-base font-bold text-slate-800">Unggah Gambar / Desain Jadwal</h3>
            <p class="text-xs text-slate-500 mt-1">Jika memiliki poster atau infografis resmi jadwal haul, unggah di sini agar jamaah dan tim dapat mengunduh atau melihat langsung di beranda.</p>
          </div>

          <div class="flex items-center space-x-3 flex-shrink-0">
            <input
              type="file"
              ref="jadwalFileInputRef"
              accept="image/*"
              multiple
              class="hidden"
              @change="handleUploadJadwal"
            />
            <button
              @click="$refs.jadwalFileInputRef.click()"
              :disabled="uploadingJadwal"
              class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold text-xs shadow-md shadow-emerald-500/20 flex items-center space-x-2 transition disabled:opacity-50"
            >
              <span v-if="uploadingJadwal" class="w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
              <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15l-5-5L5 21"/></svg>
              <span>{{ uploadingJadwal ? 'Mengunggah...' : '+ Unggah Gambar Jadwal' }}</span>
            </button>
          </div>
        </div>

        <!-- Poster Jadwal List -->
        <div v-if="jadwalPhotos.length > 0" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <div
            v-for="(photo, idx) in jadwalPhotos"
            :key="idx"
            class="bg-white border border-slate-200 rounded-2xl overflow-hidden p-3 flex items-center justify-between space-x-3 shadow-xs"
          >
            <div class="flex items-center space-x-3 min-w-0">
              <div class="w-16 h-16 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0">
                <img :src="getPhotoUrl(photo)" class="w-full h-full object-cover" />
              </div>
              <div class="min-w-0">
                <p class="text-xs font-bold text-slate-800 truncate">Poster Jadwal #{{ idx + 1 }}</p>
                <p class="text-[10px] font-mono text-slate-500 truncate">{{ photo }}</p>
              </div>
            </div>
            <button
              @click="deleteJadwal(idx)"
              class="p-2 text-rose-600 hover:bg-rose-50 rounded-xl transition"
              title="Hapus poster jadwal ini"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
          </div>
        </div>

        <!-- 2. Main Section: Isi Form Master Jadwal Kerja Terpadu -->
        <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-sm space-y-5">
          <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 border-b border-slate-200 pb-4">
            <div>
              <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[11px] font-bold mb-2">
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                <span>Isi Form Master Jadwal Kerja</span>
              </div>
              <h3 class="text-base font-bold text-slate-800">Tabel Form Jadwal Kerja Tim Dokumentasi &amp; Shooting</h3>
              <p class="text-xs text-slate-500 mt-1">Isi dan sesuaikan jadwal tugas hari Rabu &amp; Kamis untuk Fotografer, Videografer, dan Kameramen Multicam.</p>
              <div class="mt-2 flex flex-wrap items-center gap-2">
                <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-800 text-[11px] font-semibold">
                  <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                  <span>Terintegrasi otomatis dengan <strong>{{ uniqueMemberNames.length }} Anggota</strong> terdaftar (Akun Login &amp; Formulir)</span>
                </span>
                <button
                  type="button"
                  @click="refreshMembers"
                  :disabled="loadingMembers"
                  class="inline-flex items-center space-x-1 text-[11px] text-slate-500 hover:text-emerald-700 transition"
                  title="Muat ulang data anggota terbaru"
                >
                  <svg class="w-3 h-3" :class="{ 'animate-spin': loadingMembers }" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                  <span>{{ loadingMembers ? 'Menyinkron...' : 'Sinkronkan Anggota' }}</span>
                </button>
              </div>
            </div>

            <div class="flex flex-wrap items-center gap-2.5 self-start md:self-auto">
              <router-link
                to="/jadwal-kerja"
                target="_blank"
                class="px-3.5 py-2.5 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 font-bold text-xs flex items-center space-x-1.5 shadow-sm transition"
              >
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                <span>Halaman Publik ↗</span>
              </router-link>

              <button
                @click="saveJadwalTim"
                :disabled="savingJadwal"
                class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold text-xs shadow-md shadow-emerald-500/20 flex items-center space-x-2 transition disabled:opacity-50"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                <span>{{ savingJadwal ? 'Menyimpan...' : 'Simpan Seluruh Jadwal' }}</span>
              </button>
            </div>
          </div>

          <!-- Switcher Sub-Tab Form -->
          <div class="flex items-center space-x-2">
            <button
              @click="activeJadwalSubTab = 'liputan'"
              :class="[
                'px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs',
                activeJadwalSubTab === 'liputan'
                  ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-emerald-500/20'
                  : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50'
              ]"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><circle cx="12" cy="13" r="4" stroke-width="2"/></svg>
              <span>Tim Liputan (Fotografer &amp; Videografer) ({{ jadwalTim.length }})</span>
            </button>

            <button
              @click="activeJadwalSubTab = 'shooting'"
              :class="[
                'px-4 py-2 rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs',
                activeJadwalSubTab === 'shooting'
                  ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-emerald-500/20'
                  : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-50'
              ]"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
              <span>Tim Shooting Multicam (Kameramen) ({{ jadwalShooting.length }})</span>
            </button>
          </div>

          <!-- FORM TABEL 1: TIM LIPUTAN -->
          <div v-if="activeJadwalSubTab === 'liputan'" class="space-y-4">
            <div class="overflow-x-auto rounded-2xl border border-slate-200">
              <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[11px] tracking-wider font-mono">
                  <tr>
                    <th class="py-3 px-3 w-12 text-center">#</th>
                    <th class="py-3 px-3 w-28">Hari</th>
                    <th class="py-3 px-3 w-28">Waktu</th>
                    <th class="py-3 px-3 min-w-[170px]">Momen Kegiatan</th>
                    <th class="py-3 px-3 min-w-[170px]">Wilayah / Lokasi</th>
                    <th class="py-3 px-3 min-w-[200px] text-emerald-700">Fotografer</th>
                    <th class="py-3 px-3 min-w-[170px] text-teal-700">Videografer</th>
                    <th class="py-3 px-3 text-right w-16">Hapus</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                  <tr v-for="(row, rIdx) in jadwalTim" :key="rIdx" class="hover:bg-slate-50/80 transition">
                    <td class="py-2.5 px-3 text-center font-mono text-slate-400">{{ rIdx + 1 }}</td>
                    <td class="py-2.5 px-2">
                      <select
                        v-model="row.hari"
                        class="w-full px-2 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-800 font-mono text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                      >
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Ahad">Ahad</option>
                      </select>
                    </td>
                    <td class="py-2.5 px-2">
                      <input
                        v-model="row.waktu"
                        type="text"
                        placeholder="Pagi / Malam"
                        class="w-full px-2 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-800 text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                      />
                    </td>
                    <td class="py-2.5 px-2">
                      <input
                        v-model="row.momen"
                        type="text"
                        placeholder="Momen kegiatan..."
                        class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-800 text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                      />
                    </td>
                    <td class="py-2.5 px-2">
                      <input
                        v-model="row.wilayah"
                        type="text"
                        placeholder="Lokasi..."
                        class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-800 text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                      />
                    </td>
                    <td class="py-2.5 px-2">
                      <div class="flex items-center space-x-1">
                        <input
                          v-model="row.fotografer"
                          type="text"
                          list="registeredMembersList"
                          placeholder="Nama fotografer..."
                          class="w-full px-2 py-1.5 bg-white border border-slate-300 rounded-lg text-emerald-700 text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 font-medium"
                        />
                        <button
                          v-if="row.fotografer"
                          type="button"
                          @click="notifyViaWhatsApp(row.fotografer, 'Fotografer Liputan', { hari: row.hari, waktu: row.waktu, momen: row.momen, lokasi: row.wilayah, role: 'Fotografer' })"
                          class="p-1.5 rounded-lg bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white border border-emerald-200 transition flex items-center justify-center flex-shrink-0 shadow-xs"
                          :title="`Kirim Notifikasi WA ke Fotografer (${row.fotografer})`"
                        >
                          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.652c1.746.951 3.71 1.452 5.711 1.453h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        </button>
                      </div>
                    </td>
                    <td class="py-2.5 px-2">
                      <div class="flex items-center space-x-1">
                        <input
                          v-model="row.videografer"
                          type="text"
                          list="registeredMembersList"
                          placeholder="Nama videografer..."
                          class="w-full px-2 py-1.5 bg-white border border-slate-300 rounded-lg text-teal-700 text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 font-medium"
                        />
                        <button
                          v-if="row.videografer"
                          type="button"
                          @click="notifyViaWhatsApp(row.videografer, 'Videografer Liputan', { hari: row.hari, waktu: row.waktu, momen: row.momen, lokasi: row.wilayah, role: 'Videografer' })"
                          class="p-1.5 rounded-lg bg-teal-50 hover:bg-teal-600 text-teal-600 hover:text-white border border-teal-200 transition flex items-center justify-center flex-shrink-0 shadow-xs"
                          :title="`Kirim Notifikasi WA ke Videografer (${row.videografer})`"
                        >
                          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.652c1.746.951 3.71 1.452 5.711 1.453h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        </button>
                      </div>
                    </td>
                    <td class="py-2.5 px-3 text-right">
                      <button
                        @click="removeJadwalTimRow(rIdx)"
                        class="p-1.5 text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition"
                        title="Hapus baris jadwal ini"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="flex justify-between items-center">
              <span class="text-xs text-slate-500">Total: {{ jadwalTim.length }} baris penugasan liputan</span>
              <button
                @click="addJadwalTimRow"
                class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center space-x-1.5 transition"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Tambah Baris Jadwal Liputan</span>
              </button>
            </div>
          </div>

          <!-- FORM TABEL 2: TIM SHOOTING MULTICAM -->
          <div v-else class="space-y-4">
            <div class="overflow-x-auto rounded-2xl border border-slate-200">
              <table class="w-full text-left text-xs border-collapse">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-700 uppercase font-bold text-[11px] tracking-wider font-mono">
                  <tr>
                    <th class="py-3 px-3 w-12 text-center">#</th>
                    <th class="py-3 px-3 w-28">Hari</th>
                    <th class="py-3 px-3 w-28">Waktu</th>
                    <th class="py-3 px-3 min-w-[170px]">Momen Kegiatan</th>
                    <th class="py-3 px-3 min-w-[170px]">Wilayah / Lokasi</th>
                    <th class="py-3 px-3 min-w-[160px] text-amber-700">Posisi Shooting</th>
                    <th class="py-3 px-3 min-w-[190px] text-rose-700">Kameramen</th>
                    <th class="py-3 px-3 text-right w-16">Hapus</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                  <tr v-for="(sRow, sIdx) in jadwalShooting" :key="sIdx" class="hover:bg-slate-50/80 transition">
                    <td class="py-2.5 px-3 text-center font-mono text-slate-400">{{ sIdx + 1 }}</td>
                    <td class="py-2.5 px-2">
                      <select
                        v-model="sRow.hari"
                        class="w-full px-2 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-800 font-mono text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                      >
                        <option value="Rabu">Rabu</option>
                        <option value="Kamis">Kamis</option>
                        <option value="Jumat">Jumat</option>
                        <option value="Sabtu">Sabtu</option>
                        <option value="Ahad">Ahad</option>
                      </select>
                    </td>
                    <td class="py-2.5 px-2">
                      <input
                        v-model="sRow.waktu"
                        type="text"
                        placeholder="Pagi / Malam"
                        class="w-full px-2 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-800 text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                      />
                    </td>
                    <td class="py-2.5 px-2">
                      <input
                        v-model="sRow.momen"
                        type="text"
                        placeholder="Momen acara..."
                        class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-800 text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                      />
                    </td>
                    <td class="py-2.5 px-2">
                      <input
                        v-model="sRow.wilayah"
                        type="text"
                        placeholder="Lokasi panggung..."
                        class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-800 text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                      />
                    </td>
                    <td class="py-2.5 px-2">
                      <input
                        v-model="sRow.shooting"
                        type="text"
                        placeholder="Tengah / Kiri / Kanan"
                        class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-amber-700 font-mono text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                      />
                    </td>
                    <td class="py-2.5 px-2">
                      <div class="flex items-center space-x-1">
                        <input
                          v-model="sRow.kameramen"
                          type="text"
                          list="registeredMembersList"
                          placeholder="Nama kameramen..."
                          class="w-full px-2.5 py-1.5 bg-white border border-slate-300 rounded-lg text-slate-800 font-semibold text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                        />
                        <button
                          v-if="sRow.kameramen"
                          type="button"
                          @click="notifyViaWhatsApp(sRow.kameramen, `Kameramen Shooting Multicam (${sRow.shooting || 'Kamera'})`, { hari: sRow.hari, waktu: sRow.waktu, momen: sRow.momen, lokasi: sRow.wilayah, posisi: sRow.shooting, role: 'Kameramen Multicam' })"
                          class="p-1.5 rounded-lg bg-purple-50 hover:bg-purple-600 text-purple-600 hover:text-white border border-purple-200 transition flex items-center justify-center flex-shrink-0 shadow-xs"
                          :title="`Kirim Notifikasi WA ke Kameramen (${sRow.kameramen})`"
                        >
                          <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.652c1.746.951 3.71 1.452 5.711 1.453h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                        </button>
                      </div>
                    </td>
                    <td class="py-2.5 px-3 text-right">
                      <button
                        @click="removeJadwalShootingRow(sIdx)"
                        class="p-1.5 text-rose-600 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition"
                        title="Hapus baris shooting ini"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="flex justify-between items-center">
              <span class="text-xs text-slate-500">Total: {{ jadwalShooting.length }} baris posisi multicam</span>
              <button
                @click="addJadwalShootingRow"
                class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center space-x-1.5 transition"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>+ Tambah Baris Tim Shooting</span>
              </button>
            </div>
          </div>
        </div>

        <!-- 3. Instruksi Teknis Tim Dokumentasi Section -->
        <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-sm space-y-4">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
              <div class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center font-bold text-sm">
                📋
              </div>
              <div>
                <h4 class="text-sm font-bold text-slate-800">Instruksi Teknis Petugas Dokumentasi &amp; Shooting Lapangan</h4>
                <p class="text-xs text-slate-500">Poin instruksi SOP yang tampil di bagian bawah halaman Jadwal Kerja Tim.</p>
              </div>
            </div>
            <button
              @click="addJadwalInstruksi"
              class="px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 text-emerald-800 text-xs font-bold border border-emerald-200 flex items-center space-x-1 transition"
            >
              <span>+ Tambah Poin Instruksi</span>
            </button>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-2">
            <div
              v-for="(inst, iIdx) in jadwalInstruksi"
              :key="iIdx"
              class="p-4 rounded-2xl bg-emerald-50/40 border border-emerald-200 space-y-3 relative group"
            >
              <div class="flex items-center justify-between">
                <span class="text-[11px] font-black uppercase text-emerald-700 tracking-wider">Poin {{ iIdx + 1 }}</span>
                <button
                  @click="removeJadwalInstruksi(iIdx)"
                  class="text-rose-500 hover:text-rose-700 text-xs font-bold"
                  title="Hapus Instruksi"
                >
                  ✕
                </button>
              </div>
              <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Judul Instruksi:</label>
                <input
                  v-model="inst.title"
                  type="text"
                  placeholder="misal: Briefing &amp; Standby Posisi"
                  class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 font-bold focus:outline-none focus:border-emerald-500"
                />
              </div>
              <div>
                <label class="block text-[11px] font-semibold text-slate-700 mb-1">Deskripsi Arahan:</label>
                <textarea
                  v-model="inst.desc"
                  rows="3"
                  placeholder="Detail instruksi kerja..."
                  class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs text-slate-700 focus:outline-none focus:border-emerald-500 resize-none leading-relaxed"
                ></textarea>
              </div>
            </div>
          </div>

          <div class="flex justify-end pt-2">
            <button
              @click="saveJadwalTim"
              :disabled="savingJadwal"
              class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md transition disabled:opacity-50 flex items-center space-x-1.5"
            >
              <span>{{ savingJadwal ? 'Menyimpan...' : 'Simpan Instruksi &amp; Jadwal Kerja' }}</span>
            </button>
          </div>
        </div>
      </div>

      <!-- =================================================================== -->
      <!-- TAB 5: PENGATURAN TEKS HERO & INFORMASI                            -->
      <!-- =================================================================== -->
      <!-- TAB 6: TEKS HERO UTAMA BERANDA & SUBTEMA                           -->
      <!-- =================================================================== -->
      <div v-if="activeTab === 'hero'" class="space-y-6">
        <!-- Live Realtime Preview (Sesuai Tampilan Asli Halaman Depan) -->
        <div class="bg-white border border-slate-200 rounded-3xl p-5 shadow-sm space-y-3">
          <div class="flex items-center justify-between text-xs">
            <div class="flex items-center space-x-2 text-emerald-700 font-bold uppercase tracking-wider text-[11px]">
              <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
              <span>LIVE PREVIEW TEKS HERO HALAMAN DEPAN:</span>
            </div>
            <span class="text-slate-400 font-mono text-[11px]">Teks berubah langsung saat Anda mengetik</span>
          </div>

          <!-- Hero Section Box Preview -->
          <div class="relative w-full rounded-2xl overflow-hidden bg-slate-950 p-6 sm:p-10 text-center border border-slate-800 shadow-inner">
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-900/60 to-slate-950/70 pointer-events-none"></div>

            <div class="relative z-10 max-w-2xl mx-auto space-y-3">
              <!-- 1. Pita Oranye -->
              <div class="inline-flex items-center space-x-2 px-3.5 py-1 rounded-full bg-[#f97316] text-white font-black text-[10px] sm:text-xs tracking-wider uppercase shadow-lg border border-white/20 max-w-[90vw] truncate">
                <span class="w-2 h-2 rounded-full bg-white animate-pulse flex-shrink-0"></span>
                <span class="truncate">{{ heroForm.sub_theme || 'Peringatan Haul & Isro\' Mi\'roj' }}</span>
              </div>

              <!-- 2. Judul Hero -->
              <h1 class="text-xl sm:text-3xl lg:text-4xl font-black text-white tracking-tight leading-tight drop-shadow-lg">
                {{ heroForm.hero_title_white || 'Media Rojabiyyah' }}
                <span class="bg-gradient-to-r from-emerald-300 via-teal-200 to-white bg-clip-text text-transparent">
                  {{ heroForm.hero_title_gradient || 'Haul Ke-5 & Rojabiyyah 2026' }}
                </span>
              </h1>

              <!-- 3. Subtitle Badge (Badge Gelap) -->
              <div v-if="heroForm.hero_subtitle" class="pt-1">
                <span class="inline-block text-[10px] sm:text-xs font-extrabold tracking-widest text-emerald-300 uppercase font-mono px-3.5 py-1.5 rounded-xl bg-black/50 border border-emerald-400/30 backdrop-blur-md shadow-md">
                  {{ heroForm.hero_subtitle }}
                </span>
              </div>

              <!-- 4. Deskripsi Hero -->
              <p v-if="heroForm.hero_desc" class="text-xs text-slate-300 font-medium max-w-xl mx-auto leading-relaxed pt-1">
                {{ heroForm.hero_desc }}
              </p>
            </div>
          </div>
        </div>

        <!-- Edit Form -->
        <div class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm space-y-5">
          <div>
            <div class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-[11px] font-bold mb-2">
              <span>✍️</span>
              <span>Edit Semua Tulisan Hero Bagian Atas</span>
            </div>
            <h3 class="text-base font-bold text-slate-800">Formulir Kustomisasi Teks Banner Utama</h3>
            <p class="text-xs text-slate-500 mt-1">Seluruh 5 elemen tulisan di banner beranda utama dapat Anda ubah dan sesuaikan di bawah ini.</p>
          </div>

          <form @submit.prevent="saveHeroSettings" class="space-y-4 text-xs">
            <!-- 1. Subtema Pita Oranye -->
            <div>
              <label class="block font-semibold text-slate-700 mb-1">
                1. Subtema / Pita Oranye (Paling Atas) *
              </label>
              <input
                v-model="heroForm.sub_theme"
                type="text"
                required
                placeholder="misal: PERINGATAN HAUL MASYAYIKH & ISRO' MI'ROJ 1447 H / 2026 M"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 font-medium"
              />
              <p class="text-[10px] text-slate-400 mt-1">Ditampilkan di dalam kapsul oranye menyala di atas judul utama.</p>
            </div>

            <!-- 2 & 3. Judul Utama Depan & Sorotan -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block font-semibold text-slate-700 mb-1">2. Judul Depan (Teks Warna Putih) *</label>
                <input
                  v-model="heroForm.hero_title_white"
                  type="text"
                  required
                  placeholder="misal: Media Rojabiyyah"
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 font-bold"
                />
              </div>

              <div>
                <label class="block font-semibold text-slate-700 mb-1">3. Judul Sorotan (Teks Gradien Hijau/Teal) *</label>
                <input
                  v-model="heroForm.hero_title_gradient"
                  type="text"
                  required
                  placeholder="misal: Haul Ke-5 &amp; Rojabiyyah 2026"
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 font-bold"
                />
              </div>
            </div>

            <!-- 4. Subtitle Badge (Badge Gelap) -->
            <div>
              <label class="block font-semibold text-slate-700 mb-1">
                4. Badge Sub-Judul (Kapsul Gelap di Bawah Judul)
              </label>
              <input
                v-model="heroForm.hero_subtitle"
                type="text"
                placeholder="misal: DOKUMENTASI HAUL & ROJABIYYAH 2026"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 font-mono"
              />
              <p class="text-[10px] text-slate-400 mt-1">Ditampilkan di dalam badge gelap font monospasi di bawah judul.</p>
            </div>

            <!-- 5. Deskripsi Teks Bawah -->
            <div>
              <label class="block font-semibold text-slate-700 mb-1">
                5. Deskripsi / Keterangan Teks Bawah Hero
              </label>
              <textarea
                v-model="heroForm.hero_desc"
                rows="2"
                placeholder="misal: Momen Kebersamaan & Khidmah Panitia"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
              ></textarea>
              <p class="text-[10px] text-slate-400 mt-1">Ditampilkan sebagai kalimat penjelas di bawah badge sub-judul.</p>
            </div>

            <div class="flex justify-end pt-3 border-t border-slate-100">
              <button
                type="submit"
                :disabled="savingHero"
                class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold shadow-md shadow-emerald-500/20 transition active:scale-95 disabled:opacity-50"
              >
                {{ savingHero ? 'Menyimpan Perubahan...' : '💾 Simpan Seluruh Teks Hero' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Datalist Autocomplete Seluruh Anggota Terdaftar (Akun Login & Formulir) -->
    <datalist id="registeredMembersList">
      <option
        v-for="mem in uniqueMemberNames"
        :key="mem.id"
        :value="mem.name"
      >
        {{ mem.label }}
      </option>
    </datalist>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import DashboardLayout from '../../layouts/DashboardLayout.vue';
import VristoCalendar from '../../components/VristoCalendar.vue';
import api from '../../services/api';

const activeTab = ref('denah');

const tabs = [
  { id: 'denah', name: 'Denah Proyektor Global' },
  { id: 'slider', name: 'Banner Slider Beranda' },
  { id: 'kalender', name: 'Kalender Agenda (Vristo)' },
  { id: 'proyektor', name: 'Pembagian Tim Proyektor' },
  { id: 'jadwal', name: 'Jadwal Kerja Tim' },
  { id: 'hero', name: 'Teks Hero & Tema' },
];

const denahList = ref([]);
const currentDenahIndex = ref(0);
const uploadingDenah = ref(false);
const denahFileInputRef = ref(null);

const sliders = ref([]);
const calendarEvents = ref([]);
const savingCalendar = ref(false);
const proyektorTeams = ref([]);
const proyektorPhotos = ref([]);
const uploadingProyektorPhoto = ref(false);
const proyektorFileInputRef = ref(null);
const savingProyektor = ref(false);

const defaultProyektorSop = [
  {
    title: 'Layar Mati / "No Signal"',
    desc: 'Periksa kabel HDMI/VGA dari splitter cabang. Jika kabel FO, pastikan konverter bertenaga adaptor 5V aktif. Hubungi Koordinator Jalur FO jika lampu indikator padam.',
  },
  {
    title: 'Gambar Kedip / Flicker',
    desc: 'Ganti kabel extender atau turunkan resolusi output switcher di tenda pusat. Pastikan grounding terminal listrik di panggung posko aman & tidak bersentuhan dengan sound.',
  },
  {
    title: 'Kontak Darurat Koordinator',
    desc: 'Hubungi Koordinator Proyektor melalui handy talky (HT) Channel 3 Media atau telepon darurat panitia proyektor bila kendala > 3 menit belum tertangani.',
  },
];

const defaultJadwalInstruksi = [
  {
    title: 'Briefing & Standby Posisi',
    desc: 'Seluruh kameramen dan tim liputan wajib standby di posko masing-masing 30 menit sebelum sesi dimulai. Lakukan pengecekan baterai, SD Card, dan audio input.',
  },
  {
    title: 'Komunikasi & Intercom HT',
    desc: 'Gunakan HT Channel 1 Media (Shooting Live) atau Channel 2 (Liputan & Drone). Dilarang meninggalkan posisi utama tanpa konfirmasi ke Koordinator Lapangan.',
  },
  {
    title: 'Penyerahan File & Backup',
    desc: 'Setelah sesi selesai, segera serahkan SD Card / transfer footage ke Tim Editor Posko Utama untuk proses ingest cepat dan backup ganda ke server media.',
  },
];

const proyektorSop = ref([...defaultProyektorSop]);
const jadwalInstruksi = ref([...defaultJadwalInstruksi]);

const addProyektorSop = () => {
  proyektorSop.value.push({ title: '', desc: '' });
};

const removeProyektorSop = (idx) => {
  proyektorSop.value.splice(idx, 1);
};

const addJadwalInstruksi = () => {
  jadwalInstruksi.value.push({ title: '', desc: '' });
};

const removeJadwalInstruksi = (idx) => {
  jadwalInstruksi.value.splice(idx, 1);
};

const jadwalPhotos = ref([]);
const uploadingJadwal = ref(false);
const jadwalFileInputRef = ref(null);

const jadwalTim = ref([]);
const jadwalShooting = ref([]);
const activeJadwalSubTab = ref('liputan');
const savingJadwal = ref(false);

// Integrasi Master Data Anggota (Akun Login & Formulir Pendaftaran)
const registeredMembers = ref([]);
const loadingMembers = ref(false);

const memberOptions = computed(() => {
  return registeredMembers.value
    .filter((m) => m && m.name && m.name.trim().length > 0)
    .map((m) => {
      const divisionName = m.member?.division?.name || '';
      const sourceLabel = m.registration_source === 'account' ? 'Akun Login' : 'Formulir';
      return {
        id: m.id,
        name: m.name.trim(),
        member_no: m.member_no || '',
        division: divisionName,
        source: sourceLabel,
        label: `${m.name.trim()}${divisionName ? ' • ' + divisionName : ''} (${sourceLabel})`,
      };
    })
    .sort((a, b) => a.name.localeCompare(b.name));
});

const uniqueMemberNames = computed(() => {
  const map = new Map();
  memberOptions.value.forEach((item) => {
    if (!map.has(item.name)) {
      map.set(item.name, item);
    }
  });
  return Array.from(map.values());
});

const refreshMembers = async () => {
  loadingMembers.value = true;
  try {
    const res = await api.get('/members', { params: { per_page: 1000 } });
    registeredMembers.value = Array.isArray(res.data) ? res.data : (res.data?.data || []);
  } catch (e) {
    console.error('Failed to refresh members list', e);
  } finally {
    loadingMembers.value = false;
  }
};

const heroForm = ref({
  hero_title_white: 'Media Rojabiyyah',
  hero_title_gradient: 'Haul Ke-5 & Rojabiyyah 2026',
  sub_theme: 'PERINGATAN HAUL MASYAYIKH & ISRO\' MI\'ROJ 1447 H / 2026 M',
  hero_subtitle: 'DOKUMENTASI HAUL & ROJABIYYAH 2026',
  hero_desc: 'Momen Kebersamaan & Khidmah Panitia',
});
const savingHero = ref(false);

const currentDenahPhoto = computed(() => {
  return denahList.value[currentDenahIndex.value] || denahList.value[0];
});

const nextDenah = () => {
  if (denahList.value.length === 0) return;
  currentDenahIndex.value = (currentDenahIndex.value + 1) % denahList.value.length;
};

const prevDenah = () => {
  if (denahList.value.length === 0) return;
  currentDenahIndex.value = (currentDenahIndex.value - 1 + denahList.value.length) % denahList.value.length;
};

const getPhotoUrl = (path) => {
  if (!path) return '/hero-bg.png';
  if (path.startsWith('data:') || path.startsWith('http://') || path.startsWith('https://')) return path;
  const backendBase = import.meta.env.VITE_API_URL?.replace(/\/api\/v1\/?$/, '') || 'http://127.0.0.1:8000';
  return backendBase + (path.startsWith('/') ? path : '/' + path);
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

const fetchData = async () => {
  try {
    const [overviewRes, sliderRes, settingsRes, membersRes] = await Promise.all([
      api.get('/public/overview'),
      api.get('/sliders'),
      api.get('/settings/public'),
      api.get('/members', { params: { per_page: 1000 } }).catch(() => ({ data: { data: [] } })),
    ]);

    if (membersRes?.data) {
      registeredMembers.value = Array.isArray(membersRes.data)
        ? membersRes.data
        : (membersRes.data.data || []);
    }

    denahList.value = overviewRes.data.denah_photos || ['/uploads/denah/denah_proyektor_1.jpg'];
    jadwalPhotos.value = overviewRes.data.jadwal_photos || [];
    proyektorPhotos.value = overviewRes.data.proyektor_photos || [];
    proyektorTeams.value = overviewRes.data.proyektor_teams || [];
    jadwalTim.value = overviewRes.data.jadwal_tim || [];
    jadwalShooting.value = overviewRes.data.jadwal_shooting || [];
    calendarEvents.value = overviewRes.data.calendar_events || [];
    sliders.value = sliderRes.data || [];

    if (overviewRes.data.proyektor_sop?.length) {
      proyektorSop.value = overviewRes.data.proyektor_sop;
    }
    if (overviewRes.data.jadwal_instruksi?.length) {
      jadwalInstruksi.value = overviewRes.data.jadwal_instruksi;
    }

    if (settingsRes.data) {
      heroForm.value.hero_title_white = settingsRes.data.hero_title_white || 'Media Rojabiyyah';
      heroForm.value.hero_title_gradient = settingsRes.data.hero_title_gradient || 'Haul Ke-5 & Rojabiyyah 2026';
      heroForm.value.sub_theme = settingsRes.data.sub_theme || 'PERINGATAN HAUL MASYAYIKH & ISRO\' MI\'ROJ 1447 H / 2026 M';
      heroForm.value.hero_subtitle = settingsRes.data.hero_subtitle || 'DOKUMENTASI HAUL & ROJABIYYAH 2026';
      heroForm.value.hero_desc = settingsRes.data.hero_desc || 'Momen Kebersamaan & Khidmah Panitia';
    }
  } catch (err) {
    console.error('Gagal mengambil data:', err);
  }
};

const handleUploadDenah = async (e) => {
  const files = Array.from(e.target.files);
  if (!files.length) return;
  uploadingDenah.value = true;
  try {
    const promises = files.map((file) => compressImage(file, 1600, 0.85));
    const base64List = await Promise.all(promises);
    const res = await api.post('/admin/landing/upload-denah', { photos: base64List });
    denahList.value = res.data.denah_photos;
    currentDenahIndex.value = denahList.value.length - 1;
    alert('Foto denah berhasil diunggah!');
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal mengunggah foto denah.');
  } finally {
    uploadingDenah.value = false;
    if (denahFileInputRef.value) denahFileInputRef.value.value = '';
  }
};

const deleteDenah = async (idx) => {
  if (confirm(`Hapus foto denah #${idx + 1}?`)) {
    try {
      const res = await api.post('/admin/landing/delete-denah', { index: idx });
      denahList.value = res.data.denah_photos;
      if (currentDenahIndex.value >= denahList.value.length) {
        currentDenahIndex.value = Math.max(0, denahList.value.length - 1);
      }
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
    const res = await api.post('/admin/landing/upload-jadwal', { photos: base64List });
    jadwalPhotos.value = res.data.jadwal_photos;
    alert('Foto jadwal kerja berhasil diunggah!');
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal mengunggah foto jadwal.');
  } finally {
    uploadingJadwal.value = false;
    if (jadwalFileInputRef.value) jadwalFileInputRef.value.value = '';
  }
};

const deleteJadwal = async (idx) => {
  if (confirm(`Hapus foto jadwal #${idx + 1}?`)) {
    try {
      const res = await api.post('/admin/landing/delete-jadwal', { index: idx });
      jadwalPhotos.value = res.data.jadwal_photos;
    } catch (err) {
      alert('Gagal menghapus foto jadwal.');
    }
  }
};

const handleUploadProyektorPhoto = async (e) => {
  const files = Array.from(e.target.files);
  if (!files.length) return;
  uploadingProyektorPhoto.value = true;
  try {
    const promises = files.map((file) => compressImage(file, 1600, 0.85));
    const base64List = await Promise.all(promises);
    const res = await api.post('/admin/landing/upload-proyektor-photo', { photos: base64List });
    proyektorPhotos.value = res.data.proyektor_photos;
    alert('Foto skema proyektor berhasil diunggah!');
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal mengunggah foto skema proyektor.');
  } finally {
    uploadingProyektorPhoto.value = false;
    if (proyektorFileInputRef.value) proyektorFileInputRef.value.value = '';
  }
};

const deleteProyektorPhoto = async (idx) => {
  if (confirm(`Hapus foto skema proyektor #${idx + 1}?`)) {
    try {
      const res = await api.post('/admin/landing/delete-proyektor-photo', { index: idx });
      proyektorPhotos.value = res.data.proyektor_photos;
    } catch (err) {
      alert('Gagal menghapus foto skema proyektor.');
    }
  }
};

const addProyektorTitik = () => {
  const charCode = 65 + proyektorTeams.value.length;
  const nextTitikName = `Titik ${String.fromCharCode(charCode)}`;
  proyektorTeams.value.push({
    titik: nextTitikName,
    korwil: '',
    kabel: '',
    items: [
      { kode: `Proyektor ${proyektorTeams.value.length + 20}`, wilayah: 'Area Baru', petugas: 'Nama Petugas', kabel: '-' }
    ]
  });
};

const removeProyektorTitik = (tIdx) => {
  if (confirm(`Hapus seluruh ${proyektorTeams.value[tIdx].titik}?`)) {
    proyektorTeams.value.splice(tIdx, 1);
  }
};

const addProyektorItem = (tIdx) => {
  proyektorTeams.value[tIdx].items.push({
    kode: 'Proyektor Baru',
    wilayah: 'Area Baru',
    petugas: 'Nama Petugas',
    kabel: '-',
  });
};

const removeProyektorItem = (tIdx, iIdx) => {
  proyektorTeams.value[tIdx].items.splice(iIdx, 1);
};

// ==========================================
// Integrasi Notifikasi Tugas via WhatsApp (WA)
// ==========================================
const findMemberByName = (name) => {
  if (!name || !name.trim()) return null;
  const clean = name.trim().toLowerCase();
  return registeredMembers.value.find((m) => {
    if (!m) return false;
    const mName = (m.name || '').trim().toLowerCase();
    const mUsername = (m.username || '').trim().toLowerCase();
    return mName === clean || mUsername === clean || mName.includes(clean) || clean.includes(mName);
  });
};

const notifyViaWhatsApp = (personName, dutyType, details = {}) => {
  if (!personName || !personName.trim()) {
    alert('Nama petugas belum diisi.');
    return;
  }
  const member = findMemberByName(personName);
  let phone = member?.phone_wa || member?.member?.phone_wa || '';

  if (!phone) {
    phone = prompt(`Nomor WhatsApp untuk "${personName}" tidak ditemukan di database anggota.\nMasukkan nomor WhatsApp tujuan (contoh: 08123456789):`, '');
    if (!phone) return;
  }

  const cleanPhone = phone.replace(/\D/g, '').replace(/^0/, '62');

  let msg = `*PEMBERITAHUAN PENUGASAN RESMI*\n`;
  msg += `*PANITIA ROJABIYYAH & HAUL 2026*\n`;
  msg += `_Pondok Pesantren Al-Muhibbin Tambakberas_\n\n`;
  msg += `Assalamu'alaikum Wr. Wb.\n`;
  msg += `Yth. Sdr. *${personName}*\n\n`;
  msg += `Anda telah ditugaskan secara resmi oleh Koordinator Media & IT:\n`;
  msg += `📋 *Penugasan*: ${dutyType}\n`;
  if (details.titik) msg += `📍 *Wilayah/Titik*: ${details.titik}\n`;
  if (details.lokasi) msg += `📍 *Lokasi*: ${details.lokasi}\n`;
  if (details.kode) msg += `📺 *Perangkat Display*: ${details.kode}\n`;
  if (details.hari) msg += `📅 *Hari*: ${details.hari}\n`;
  if (details.waktu) msg += `⏰ *Waktu/Sesi*: ${details.waktu}\n`;
  if (details.momen) msg += `🎯 *Momen Acara*: ${details.momen}\n`;
  if (details.posisi) msg += `🎥 *Posisi Kamera*: ${details.posisi}\n`;
  if (details.role) msg += `🎖️ *Peran*: ${details.role}\n`;

  msg += `\nMohon hadir dan standby di posko / lokasi 30 menit sebelum jadwal dimulai.\n`;
  msg += `Silakan login dan cek penugasan Anda di Portal Panitia:\n`;
  msg += `${window.location.origin}/dashboard\n\n`;
  msg += `Terima kasih atas dedikasi dan khidmah panjenengan.\n_Wassalamu'alaikum Wr. Wb._\n_Media & IT Support Panitia Haul 2026_`;

  window.open(`https://wa.me/${cleanPhone}?text=${encodeURIComponent(msg)}`, '_blank');
};

const notifyAllInTitik = (team) => {
  const people = [];
  if (team.korwil) people.push({ name: team.korwil, role: `Korwil ${team.titik}`, details: { titik: team.titik, role: 'Koordinator Wilayah' } });
  if (team.kabel) people.push({ name: team.kabel, role: `Tim Kabel ${team.titik}`, details: { titik: team.titik, role: 'Jalur Kabel & FO' } });
  if (team.items && Array.isArray(team.items)) {
    team.items.forEach((item) => {
      if (item.petugas) {
        people.push({
          name: item.petugas,
          role: `Petugas Display ${item.kode || ''}`,
          details: { titik: team.titik, lokasi: item.wilayah, kode: item.kode }
        });
      }
    });
  }

  if (people.length === 0) {
    alert(`Belum ada petugas yang diisi pada ${team.titik}.`);
    return;
  }

  const listNames = people.map((p, idx) => `${idx + 1}. ${p.name} (${p.role})`).join('\n');
  const pick = prompt(`Pilih nomor petugas di ${team.titik} untuk dikirim pesan WhatsApp:\n\n${listNames}\n\nKetik nomor (1-${people.length}):`, '1');
  if (pick) {
    const idx = parseInt(pick, 10) - 1;
    if (people[idx]) {
      notifyViaWhatsApp(people[idx].name, people[idx].role, people[idx].details);
    }
  }
};

const saveProyektorTeams = async () => {
  savingProyektor.value = true;
  try {
    await api.post('/admin/landing/save-proyektor', {
      teams: proyektorTeams.value,
      sop: proyektorSop.value,
    });
    alert('Pembagian tim proyektor & SOP troubleshooting berhasil disimpan dan sinkron dengan beranda!');
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan data tim proyektor.');
  } finally {
    savingProyektor.value = false;
  }
};

const addJadwalTimRow = () => {
  jadwalTim.value.push({
    hari: 'Kamis',
    waktu: 'Malam',
    momen: 'Pengajian Umum',
    wilayah: 'Halaman Al-Muhibbin',
    fotografer: '',
    videografer: '',
  });
};

const removeJadwalTimRow = (idx) => {
  jadwalTim.value.splice(idx, 1);
};

const addJadwalShootingRow = () => {
  jadwalShooting.value.push({
    hari: 'Kamis',
    waktu: 'Malam',
    momen: 'Pengajian Umum',
    wilayah: 'Halaman Al-Muhibbin',
    shooting: 'Tengah (Center Stage)',
    kameramen: '',
  });
};

const removeJadwalShootingRow = (idx) => {
  jadwalShooting.value.splice(idx, 1);
};

const saveJadwalTim = async () => {
  savingJadwal.value = true;
  try {
    await api.post('/admin/landing/save-jadwal', {
      jadwal_tim: jadwalTim.value,
      jadwal_shooting: jadwalShooting.value,
      instruksi: jadwalInstruksi.value,
    });
    alert('Jadwal kerja tim & instruksi teknis berhasil disimpan dan sinkron dengan beranda!');
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan jadwal tim.');
  } finally {
    savingJadwal.value = false;
  }
};

const saveHeroSettings = async () => {
  savingHero.value = true;
  try {
    await api.post('/admin/landing/save-hero', heroForm.value);
    alert('Pengaturan teks halaman utama berhasil disimpan!');
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan teks hero.');
  } finally {
    savingHero.value = false;
  }
};

const handleCalendarSave = async (eventData, isEdit) => {
  if (isEdit) {
    const idx = calendarEvents.value.findIndex((e) => e.id === eventData.id);
    if (idx !== -1) {
      calendarEvents.value[idx] = { ...eventData };
    }
  } else {
    calendarEvents.value.push({ ...eventData });
  }
  await saveCalendarToServer();
};

const handleCalendarDelete = async (id) => {
  calendarEvents.value = calendarEvents.value.filter((e) => e.id !== id);
  await saveCalendarToServer();
};

const saveCalendarToServer = async () => {
  savingCalendar.value = true;
  try {
    const res = await api.post('/admin/landing/save-calendar', { events: calendarEvents.value });
    calendarEvents.value = res.data.calendar_events || calendarEvents.value;
    alert('Jadwal kalender agenda berhasil disimpan dan sinkron dengan beranda!');
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan data agenda kalender.');
  } finally {
    savingCalendar.value = false;
  }
};

onMounted(() => {
  fetchData();
});
</script>

