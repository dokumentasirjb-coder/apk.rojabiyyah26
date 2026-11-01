<template>
  <DashboardLayout>
    <div class="space-y-6">

      <!-- ========================================================================= -->
      <!-- MODE 1: PENGATURAN SISTEM & BRANDING (Khusus Super Admin & Admin)          -->
      <!-- ========================================================================= -->
      <div v-if="currentMode === 'system'" class="space-y-6">
        <!-- Top Header Card -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-sm">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div class="flex items-center space-x-3.5">
              <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center flex-shrink-0 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </div>
              <div>
                <div class="flex items-center space-x-2">
                  <h2 class="text-xl font-bold text-slate-800 tracking-tight">Pengaturan Sistem &amp; Branding</h2>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                  Ubah identitas sistem, judul aplikasi, logo, sub-tema acara, master kategori, wilayah kerja, dan kolom dinamis.
                </p>
              </div>
            </div>

            <!-- PRD Download Shortcut -->
            <a
              href="/PRD.md"
              download="PRD-Administrasi-Rojabiyyah-2026.md"
              class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-sky-50 hover:bg-sky-100 border border-sky-200 text-sky-700 text-xs font-semibold transition shadow-sm"
            >
              <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              <span>Unduh Dokumen PRD (.docx)</span>
            </a>
          </div>

          <!-- Top Navigation Tabs (Bright White & Emerald Active) -->
          <div class="flex items-center space-x-2 overflow-x-auto pt-6 border-t border-slate-100 mt-5 scrollbar-thin">
            <!-- Tab 1 -->
            <button
              @click="adminTab = 'branding'"
              :class="adminTab === 'branding' ? 'bg-emerald-600 text-white border-emerald-600 shadow' : 'bg-slate-50 text-slate-600 border-slate-200 hover:text-slate-900 hover:bg-slate-100'"
              class="px-3.5 py-2 rounded-xl text-xs font-bold border transition flex items-center space-x-1.5 whitespace-nowrap"
            >
              <svg class="w-3.5 h-3.5" :class="adminTab === 'branding' ? 'text-white' : 'text-sky-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
              <span>Identitas, Judul &amp; Logo</span>
            </button>

            <!-- Tab 2: Master Kategori -->
            <button
              @click="adminTab = 'categories'"
              :class="adminTab === 'categories' ? 'bg-emerald-600 text-white border-emerald-600 shadow' : 'bg-slate-50 text-slate-600 border-slate-200 hover:text-slate-900 hover:bg-slate-100'"
              class="px-3.5 py-2 rounded-xl text-xs font-bold border transition flex items-center space-x-1.5 whitespace-nowrap"
            >
              <svg class="w-3.5 h-3.5" :class="adminTab === 'categories' ? 'text-white' : 'text-indigo-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
              <span>Master Kategori</span>
            </button>

            <!-- Tab Divisi Tugas Panitia -->
            <button
              @click="adminTab = 'divisions'"
              :class="adminTab === 'divisions' ? 'bg-emerald-600 text-white border-emerald-600 shadow' : 'bg-slate-50 text-slate-600 border-slate-200 hover:text-slate-900 hover:bg-slate-100'"
              class="px-3.5 py-2 rounded-xl text-xs font-bold border transition flex items-center space-x-1.5 whitespace-nowrap"
            >
              <svg class="w-3.5 h-3.5" :class="adminTab === 'divisions' ? 'text-white' : 'text-amber-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
              <span>Divisi Tugas Panitia ({{ divisions.length }})</span>
            </button>

            <!-- Tab 3 -->
            <button
              @click="adminTab = 'regions'"
              :class="adminTab === 'regions' ? 'bg-emerald-600 text-white border-emerald-600 shadow' : 'bg-slate-50 text-slate-600 border-slate-200 hover:text-slate-900 hover:bg-slate-100'"
              class="px-3.5 py-2 rounded-xl text-xs font-bold border transition flex items-center space-x-1.5 whitespace-nowrap"
            >
              <svg class="w-3.5 h-3.5" :class="adminTab === 'regions' ? 'text-white' : 'text-teal-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              <span>Wilayah Operasional</span>
            </button>

            <!-- Tab 4 -->
            <button
              @click="adminTab = 'partners'"
              :class="adminTab === 'partners' ? 'bg-emerald-600 text-white border-emerald-600 shadow' : 'bg-slate-50 text-slate-600 border-slate-200 hover:text-slate-900 hover:bg-slate-100'"
              class="px-3.5 py-2 rounded-xl text-xs font-bold border transition flex items-center space-x-1.5 whitespace-nowrap"
            >
              <svg class="w-3.5 h-3.5" :class="adminTab === 'partners' ? 'text-white' : 'text-amber-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
              <span>Lembaga / Mitra Luar</span>
            </button>

            <!-- Tab 5 -->
            <button
              @click="adminTab = 'custom_fields'"
              :class="adminTab === 'custom_fields' ? 'bg-emerald-600 text-white border-emerald-600 shadow' : 'bg-slate-50 text-slate-600 border-slate-200 hover:text-slate-900 hover:bg-slate-100'"
              class="px-3.5 py-2 rounded-xl text-xs font-bold border transition flex items-center space-x-1.5 whitespace-nowrap"
            >
              <svg class="w-3.5 h-3.5" :class="adminTab === 'custom_fields' ? 'text-white' : 'text-pink-500'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
              <span>Kolom Kustom Dinamis</span>
            </button>

            <!-- Tab 6: Admins -->
            <button
              @click="adminTab = 'admins'"
              :class="adminTab === 'admins' ? 'bg-emerald-600 text-white border-emerald-600 shadow' : 'bg-slate-50 text-slate-600 border-slate-200 hover:text-slate-900 hover:bg-slate-100'"
              class="px-3.5 py-2 rounded-xl text-xs font-bold border transition flex items-center space-x-1.5 whitespace-nowrap"
            >
              <svg class="w-3.5 h-3.5" :class="adminTab === 'admins' ? 'text-white' : 'text-emerald-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
              <span>Kelola Pengelola Admin ({{ adminList.length || 3 }})</span>
            </button>

            <!-- Tab 7: Reset -->
            <button
              @click="adminTab = 'reset'"
              :class="adminTab === 'reset' ? 'bg-rose-600 text-white border-rose-600 shadow' : 'bg-slate-50 text-rose-600 border-slate-200 hover:bg-rose-50'"
              class="px-3.5 py-2 rounded-xl text-xs font-bold border transition flex items-center space-x-1.5 whitespace-nowrap"
            >
              <svg class="w-3.5 h-3.5" :class="adminTab === 'reset' ? 'text-white' : 'text-rose-600'" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              <span>Reset Data Operasional</span>
            </button>
          </div>
        </div>

        <!-- ===================================================================== -->
        <!-- TAB 1: IDENTITAS, JUDUL & LOGO (Exact Image 8 Layout)                 -->
        <!-- ===================================================================== -->
        <div v-if="adminTab === 'branding'" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
          <!-- Left Column: Formulir Kustomisasi Judul, Logo & Teks (7 cols) -->
          <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-sm">
            <div class="mb-5">
              <h3 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                <span>Formulir Kustomisasi Judul, Logo &amp; Teks</span>
              </h3>
              <p class="text-xs text-slate-500 mt-1 leading-relaxed">
                Sesuaikan tema dan logo sesuai tema kegiatan saat ini, otomatis tampil di halaman depan/landing, header, footer, &amp; laporan.
              </p>
            </div>

            <form @submit.prevent="saveSystemSettings" class="space-y-5 text-xs">
              <!-- Logo Upload Box -->
              <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                <label class="block font-semibold text-slate-700">Logo Aplikasi / Organisasi *</label>
                <div class="flex items-center space-x-4">
                  <div class="w-16 h-16 rounded-2xl bg-white border-2 border-slate-200 overflow-hidden flex items-center justify-center p-2 flex-shrink-0 shadow-inner">
                    <img v-if="systemForm.logo_url" :src="systemForm.logo_url" alt="Logo" class="w-full h-full object-contain" />
                    <span v-else class="text-xs font-black text-rose-400">LOGO</span>
                  </div>
                  <div class="space-y-1.5 flex-1">
                    <input type="file" ref="logoInputRef" accept="image/*" @change="handleLogoUpload" class="hidden" />
                    <div class="flex items-center space-x-2">
                      <button
                        type="button"
                        @click="$refs.logoInputRef?.click()"
                        class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-teal-500/50 text-teal-300 text-xs font-bold transition shadow"
                      >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        <span>Unggah File Logo</span>
                      </button>
                      <button
                        type="button"
                        @click="resetDefaultLogo"
                        class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold transition"
                      >
                        <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        <span>Logo Bawaan</span>
                      </button>
                    </div>
                    <p class="text-[10px] text-slate-500">Pilih file logo baru (Format: PNG transparan, JPG, SVG, atau WEBP)</p>
                  </div>
                </div>
              </div>

              <!-- Header Navbar Titles -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">Nama Aplikasi / Judul Header *</label>
                  <input
                    v-model="systemForm.app_name"
                    type="text"
                    required
                    class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 uppercase font-bold focus:outline-none focus:border-emerald-500"
                  />
                </div>
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">Sub-Judul Header Navbar</label>
                  <input
                    v-model="systemForm.app_sub_name"
                    type="text"
                    class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
                  />
                </div>
              </div>

              <!-- Sub-Tema Acara (Badge Pill) -->
              <div>
                <label class="block font-semibold text-slate-700 mb-1">
                  Sub-Tema Acara (Badge Pill di Atas Judul Halaman Depan) *
                </label>
                <input
                  v-model="systemForm.sub_theme"
                  type="text"
                  required
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
                />
                <p class="text-[10px] text-slate-500 mt-1">Ditampilkan pada badge pill emas di halaman depan, surat jalan, dan kop bukti peminjaman.</p>
              </div>

              <!-- Hero Heading (2 cols) -->
              <div>
                <label class="block font-semibold text-slate-700 mb-1">Format Judul Utama Halaman Depan (Hero Heading)</label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                  <div>
                    <span class="text-[10px] text-slate-500 block mb-0.5">Teks Judul Depan (Warna Putih)</span>
                    <input
                      v-model="systemForm.hero_title_white"
                      type="text"
                      class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 font-bold"
                    />
                  </div>
                  <div>
                    <span class="text-[10px] text-slate-500 block mb-0.5">Teks Sorotan (Warna Gradient A)</span>
                    <input
                      v-model="systemForm.hero_title_gradient"
                      type="text"
                      class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-rose-300 focus:outline-none focus:border-emerald-500 font-bold"
                    />
                  </div>
                </div>
              </div>

              <!-- Footer Copyright -->
              <div>
                <label class="block font-semibold text-slate-700 mb-1">Teks Hak Cipta / Footer Bawah</label>
                <input
                  v-model="systemForm.footer_copyright"
                  type="text"
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
                />
              </div>

              <!-- Section: KOP DOKUMEN CETAK -->
              <div class="space-y-3.5 pt-4 border-t border-slate-200">
                <div class="flex items-center space-x-2">
                  <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                  <h4 class="font-bold text-slate-900 uppercase text-[11px] tracking-wider">KOP DOKUMEN CETAK</h4>
                  <span class="text-[10px] text-slate-500">(Surat Jalan &amp; Stiker Bukti Peminjaman)</span>
                </div>
                <p class="text-[11px] text-slate-500">Ubah sekali, otomatis berlaku untuk semua dokumen yang dicetak.</p>

                <!-- Logo Khusus Kop Dokumen & Stiker Upload Box -->
                <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-2.5">
                  <label class="block font-semibold text-slate-700 text-xs">Logo Kop Surat, Bukti, &amp; Stiker F4</label>
                  <div class="flex items-center space-x-3.5">
                    <div class="w-14 h-14 rounded-2xl bg-white border-2 border-slate-200 overflow-hidden flex items-center justify-center p-1.5 flex-shrink-0 shadow-inner">
                      <img v-if="systemForm.kop_logo_url || systemForm.logo_url" :src="systemForm.kop_logo_url || systemForm.logo_url" alt="Logo Kop" class="w-full h-full object-contain" />
                      <span v-else class="text-[10px] font-black text-amber-400">LOGO KOP</span>
                    </div>
                    <div class="space-y-1.5 flex-1">
                      <input type="file" ref="kopLogoInputRef" accept="image/*" @change="handleKopLogoUpload" class="hidden" />
                      <div class="flex flex-wrap items-center gap-1.5">
                        <button
                          type="button"
                          @click="triggerKopLogoInput"
                          class="px-2.5 py-1.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-bold text-xs flex items-center space-x-1 shadow transition"
                        >
                          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                          <span>Ganti Logo Kop &amp; Stiker</span>
                        </button>
                        <button
                          type="button"
                          @click="useAppLogoForKop"
                          class="px-2.5 py-1.5 rounded-xl bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 font-semibold text-xs flex items-center space-x-1 transition"
                          title="Gunakan logo yang sama dengan logo aplikasi di atas"
                        >
                          <span>Samakan Logo Aplikasi</span>
                        </button>
                        <button
                          type="button"
                          @click="resetKopLogo"
                          class="px-2 py-1.5 rounded-xl bg-white hover:bg-slate-100 border border-slate-300 text-slate-500 hover:text-white text-xs transition"
                          title="Reset ke logo bawaan sistem"
                        >
                          <span>Reset</span>
                        </button>
                      </div>
                      <p class="text-[10px] text-slate-500">Logo ini otomatis tampil di Kop Surat Jalan, Lembar Stiker F4 (Besar &amp; Sedang), Bukti Tanda Terima, dan PDF.</p>
                    </div>
                  </div>
                </div>

                <div>
                  <label class="block font-semibold text-slate-700 mb-1">Nama Kop (Baris Utama Tebal)</label>
                  <input
                    v-model="systemForm.kop_name"
                    type="text"
                    class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 font-bold uppercase focus:outline-none focus:border-emerald-500"
                  />
                  <span class="text-[10px] text-slate-500">Teks ini tampil di header semua dokumen cetak (uppercase otomatis).</span>
                </div>

                <div>
                  <label class="block font-semibold text-slate-700 mb-1">Sub-Judul Kop / Tema Acara (Lengkap)</label>
                  <input
                    v-model="systemForm.kop_sub_theme"
                    type="text"
                    class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
                  />
                </div>

                <div>
                  <label class="block font-semibold text-slate-700 mb-1">Sub-Judul Singkat (Untuk Stiker &amp; QR Code)</label>
                  <input
                    v-model="systemForm.kop_sub_short"
                    type="text"
                    class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
                  />
                </div>

                <!-- White Box Preview inside Left Form -->
                <div class="bg-white text-slate-900 rounded-xl p-3.5 border border-slate-300 shadow-md">
                  <div class="flex items-center space-x-3 text-left pb-2 border-b border-slate-200">
                    <div class="w-12 h-12 flex-shrink-0 bg-slate-50 border border-slate-200 rounded-lg p-1 flex items-center justify-center overflow-hidden">
                      <img :src="systemForm.kop_logo_url || systemForm.logo_url || '/logo.png'" alt="Logo Kop" class="w-full h-full object-contain" />
                    </div>
                    <div class="flex-1 min-w-0">
                      <div class="font-black text-xs uppercase tracking-wide text-slate-950 truncate">{{ systemForm.kop_name }}</div>
                      <div class="text-[10px] text-slate-700 font-medium leading-tight mt-0.5">{{ systemForm.kop_sub_theme }}</div>
                      <div class="text-[9px] text-slate-500 font-mono mt-0.5">Label Stiker: {{ systemForm.kop_sub_short }}</div>
                    </div>
                  </div>
                  <div class="text-[9px] text-slate-500 mt-1.5 text-center">Preview KOP Dokumen Cetak &amp; Stiker F4</div>
                </div>
              </div>

              <!-- Section: LINK GOOGLE DRIVE -->
              <div class="space-y-3 pt-4 border-t border-slate-200">
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                    <h4 class="font-bold text-slate-900 uppercase text-[11px] tracking-wider">LINK GOOGLE DRIVE PUSAT BERKAS</h4>
                  </div>
                  <span class="px-2 py-0.5 rounded-full bg-slate-800 text-slate-700 text-[10px]">Admin Upload &amp; Anggota Download</span>
                </div>
                <p class="text-[11px] text-slate-500">
                  Folder Google Drive resmi untuk upload foto, video, desain materi, dan dokumen kegiatan yang dapat diakses oleh anggota.
                </p>

                <div>
                  <label class="block font-semibold text-slate-700 mb-1">URL / Tautan Folder Google Drive</label>
                  <div class="flex items-center space-x-2">
                    <input
                      v-model="systemForm.gdrive_folder_url"
                      type="url"
                      required
                      class="flex-1 px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 font-mono text-[11px] focus:outline-none focus:border-emerald-500"
                    />
                    <a
                      :href="systemForm.gdrive_folder_url"
                      target="_blank"
                      class="px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 border border-sky-500/50 text-sky-300 font-bold text-xs whitespace-nowrap transition"
                    >
                      Tes Buka
                    </a>
                  </div>
                  <p class="text-[10px] text-slate-500 mt-1">
                    Pastikan setelan izin akses folder Google Drive disetel ke "Siapa saja yang memiliki link (Viewer / Pelihat)" agar anggota dapat mengunduh berkas.
                  </p>
                </div>
              </div>

              <!-- Section: INFORMASI KONTAK & SEKRETARIAT -->
              <div class="space-y-3 pt-4 border-t border-slate-200">
                <h4 class="font-bold text-slate-900 uppercase text-[11px] tracking-wider">Informasi Kontak &amp; Sekretariat (Untuk Surat &amp; Cetak)</h4>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                  <div>
                    <label class="block font-semibold text-slate-700 mb-1">Nomor Kontak / WhatsApp</label>
                    <input
                      v-model="systemForm.contact_wa"
                      type="text"
                      class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
                    />
                  </div>
                  <div>
                    <label class="block font-semibold text-slate-700 mb-1">Email Resmi</label>
                    <input
                      v-model="systemForm.contact_email"
                      type="email"
                      class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
                    />
                  </div>
                </div>
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">Alamat Lengkap</label>
                  <textarea
                    v-model="systemForm.contact_address"
                    rows="2"
                    class="w-full px-3.5 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
                  ></textarea>
                </div>
              </div>

              <!-- Submit Button -->
              <div class="pt-4 border-t border-slate-200 flex justify-end">
                <button
                  type="submit"
                  :disabled="savingSystem"
                  class="w-full sm:w-auto px-6 py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold shadow-lg shadow-emerald-950/60 transition disabled:opacity-50"
                >
                  {{ savingSystem ? 'Menyimpan...' : 'Simpan Perubahan Judul, Logo & Teks' }}
                </button>
              </div>
            </form>
          </div>

          <!-- Right Column: PRATINJAU LANGSUNG HALAMAN DEPAN (5 cols - Image 8 Match) -->
          <div class="lg:col-span-5 space-y-5">
            <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-sm space-y-4">
              <div class="flex items-center justify-between pb-3 border-b border-slate-200">
                <div class="flex items-center space-x-2 text-xs font-bold text-white">
                  <span class="w-2 h-2 rounded-full bg-cyan-400"></span>
                  <span>PRATINJAU LANGSUNG HALAMAN DEPAN</span>
                </div>
                <span class="px-2 py-0.5 rounded-full bg-cyan-950 text-cyan-300 border border-cyan-800 text-[10px] font-bold">
                  Update Real-Time
                </span>
              </div>

              <!-- Landing Page Hero Live Mockup -->
              <div class="relative rounded-2xl bg-gradient-to-b from-[#070b14] via-[#ffffff] to-[#070b14] border border-slate-200 p-4 text-center overflow-hidden shadow-inner">
                <!-- Mini Navbar -->
                <div class="flex items-center justify-between pb-3 border-b border-slate-200 mb-6 text-left">
                  <div class="flex items-center space-x-2">
                    <img :src="systemForm.logo_url" alt="Logo" class="w-6 h-6 object-contain rounded" />
                    <div>
                      <div class="text-[9px] font-black text-white tracking-wider uppercase">{{ systemForm.app_name }}</div>
                      <div class="text-[8px] text-amber-400">{{ systemForm.app_sub_name }}</div>
                    </div>
                  </div>
                  <span class="px-2 py-0.5 rounded-lg bg-white text-[9px] text-slate-700 border border-slate-300">Masuk</span>
                </div>

                <!-- Gold Sub-theme Pill -->
                <div class="inline-flex items-center space-x-1 px-3 py-1 rounded-full bg-[#1c1810] border border-amber-500/50 text-amber-300 text-[9px] font-bold mb-3 shadow">
                  <span>✦</span>
                  <span class="truncate max-w-[280px]">{{ systemForm.sub_theme }}</span>
                </div>

                <!-- Big Main Title -->
                <h3 class="text-base sm:text-lg font-extrabold text-slate-900 leading-tight mb-4">
                  {{ systemForm.hero_title_white }}
                  <span class="bg-gradient-to-r from-rose-400 to-amber-300 bg-clip-text text-transparent block sm:inline">
                    {{ systemForm.hero_title_gradient }}
                  </span>
                </h3>

                <!-- Buttons Mockup -->
                <div class="flex items-center justify-center space-x-2 mb-4">
                  <span class="px-3 py-1 rounded-lg bg-[#059669] text-white text-[9px] font-bold shadow">
                    Pendaftaran Anggota
                  </span>
                  <span class="px-3 py-1 rounded-lg bg-white text-slate-700 border border-slate-300 text-[9px]">
                    Masuk Petugas
                  </span>
                </div>

                <!-- Footer Mockup -->
                <div class="text-[8px] text-slate-500 border-t border-slate-200 pt-2">
                  {{ systemForm.footer_copyright }}
                </div>
              </div>
            </div>

            <!-- Card 2: Live Kop Surat Jalan & Stiker Label (Image 8 Match) -->
            <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-sm space-y-3">
              <div class="flex items-center justify-between pb-2 border-b border-slate-200">
                <div class="text-xs font-bold text-white flex items-center space-x-2">
                  <span class="w-2 h-2 rounded-full bg-teal-400"></span>
                  <span>PRATINJAU KOP SURAT JALAN &amp; STIKER LABEL</span>
                </div>
                <span class="px-2 py-0.5 rounded-full bg-teal-950 text-teal-300 border border-teal-800 text-[10px] font-bold">
                  Live Preview
                </span>
              </div>

              <!-- Surat Jalan Kop Preview -->
              <div class="bg-white text-slate-900 rounded-xl p-3 border border-slate-300 shadow">
                <div class="flex items-center space-x-2 border-b border-slate-300 pb-2">
                  <div class="w-7 h-7 rounded bg-[#059669] text-white flex items-center justify-center font-bold text-xs flex-shrink-0">
                    MR
                  </div>
                  <div class="flex-1 leading-tight">
                    <div class="font-black text-[11px] uppercase tracking-wide">{{ systemForm.kop_name }}</div>
                    <div class="text-[9px] text-slate-600 font-medium">{{ systemForm.kop_sub_theme }}</div>
                  </div>
                </div>
              </div>

              <!-- Stiker Barcode Mockup -->
              <div class="bg-[#0e172a] rounded-xl p-3 border border-slate-300 flex items-center justify-between text-xs">
                <div>
                  <div class="font-bold text-white text-[11px] uppercase">{{ systemForm.kop_name }}</div>
                  <div class="text-[9px] text-slate-500">{{ systemForm.kop_sub_short }}</div>
                </div>
                <span class="px-2 py-1 rounded bg-emerald-950 border border-emerald-700 text-emerald-300 font-mono text-[9px] font-bold">
                  KODE: RJB-01
                </span>
              </div>

              <!-- Bottom Note -->
              <div class="p-3 rounded-xl bg-slate-50 border border-slate-200 flex items-start space-x-2 text-[10px] text-slate-500">
                <svg class="w-3.5 h-3.5 text-amber-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <p>Setelah Anda menekan tombol Simpan Pengaturan Branding, semua dokumen (Surat Jalan, Lembar Stiker A4, Bukti Peminjaman) dan tampilan halaman utama akan langsung tersinkronisasi.</p>
              </div>
            </div>
          </div>
        </div>

        <!-- ===================================================================== -->
        <!-- TAB 2: MASTER KATEGORI (Exact Image 9 Layout)                         -->
        <!-- ===================================================================== -->
        <div v-if="adminTab === 'categories'" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
          <!-- Left Card: + Tambah Kategori Baru (5 cols) -->
          <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center space-x-2">
              <span class="text-rose-400 font-black">+</span>
              <span>Tambah Kategori Baru</span>
            </h3>

            <form @submit.prevent="submitAddCategory" class="space-y-4 text-xs">
              <div>
                <label class="block font-semibold text-slate-700 mb-1">Nama Kategori</label>
                <input
                  v-model="catForm.name"
                  type="text"
                  required
                  placeholder="misal: Sound System, Infaq..."
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-emerald-500"
                />
              </div>

              <div>
                <label class="block font-semibold text-slate-700 mb-1">Tipe Modul</label>
                <select
                  v-model="catForm.type"
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
                >
                  <option value="INVENTORY">Inventaris Barang</option>
                  <option value="TRANSACTION_INCOME">Transaksi Kas (Masuk)</option>
                  <option value="TRANSACTION_EXPENSE">Transaksi Kas (Keluar)</option>
                </select>
              </div>

              <div>
                <label class="block font-semibold text-slate-700 mb-1">Keterangan (Opsional)</label>
                <textarea
                  v-model="catForm.description"
                  rows="3"
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-emerald-500"
                ></textarea>
              </div>

              <button
                type="submit"
                :disabled="savingCategory"
                class="w-full py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold shadow-lg shadow-emerald-950/60 transition disabled:opacity-50"
              >
                {{ savingCategory ? 'Menyimpan...' : 'Simpan Kategori' }}
              </button>
            </form>
          </div>

          <!-- Right Card: Daftar Kategori Aktif (7 cols) -->
          <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-4">
              <h3 class="text-sm font-bold text-slate-900">Daftar Kategori Aktif ({{ categoryList.length }})</h3>
            </div>

            <div class="space-y-3">
              <div
                v-for="cat in categoryList"
                :key="cat.id"
                class="p-4 rounded-2xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition flex items-center justify-between gap-3"
              >
                <div>
                  <div class="flex items-center space-x-2">
                    <span class="font-bold text-slate-800 text-xs uppercase tracking-wide">{{ cat.name }}</span>
                    <span
                      :class="[
                        'px-2 py-0.5 rounded text-[9px] font-mono font-bold',
                        cat.type === 'TRANSACTION_INCOME' ? 'bg-emerald-950/80 text-emerald-400 border border-emerald-800' :
                        cat.type === 'TRANSACTION_EXPENSE' ? 'bg-amber-950/80 text-amber-400 border border-amber-800' :
                        'bg-sky-950/80 text-sky-400 border border-sky-800'
                      ]"
                    >
                      {{ cat.type || 'INVENTORY' }}
                    </span>
                  </div>
                  <p class="text-[11px] text-slate-500 mt-1">{{ cat.description || '-' }}</p>
                </div>

                <button
                  @click="deleteCategory(cat)"
                  title="Hapus Kategori"
                  class="text-slate-500 hover:text-rose-400 p-2 rounded-xl hover:bg-white transition flex-shrink-0"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- ===================================================================== -->
        <!-- TAB: DIVISI TUGAS KEPANITIAAN                                         -->
        <!-- ===================================================================== -->
        <div v-if="adminTab === 'divisions'" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
          <!-- Left Card: Form Tambah / Edit Divisi (5 cols) -->
          <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center space-x-2">
              <span class="text-amber-400 font-black">{{ isEditingDivision ? '✎' : '+' }}</span>
              <span>{{ isEditingDivision ? 'Edit Divisi Panitia' : 'Tambah Divisi Baru' }}</span>
            </h3>

            <form @submit.prevent="submitSaveDivision" class="space-y-4 text-xs">
              <div>
                <label class="block font-semibold text-slate-700 mb-1">Nama Divisi Panitia *</label>
                <input
                  v-model="divisionForm.name"
                  type="text"
                  required
                  placeholder="misal: Divisi Acara, Divisi Logistik..."
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-amber-500 transition"
                />
                <p class="text-[10px] text-slate-500 mt-1">
                  Nama divisi akan otomatis muncul di form data anggota &amp; formulir pendaftaran relawan.
                </p>
              </div>

              <div>
                <label class="block font-semibold text-slate-700 mb-1">Deskripsi / Ruang Lingkup (Opsional)</label>
                <textarea
                  v-model="divisionForm.description"
                  rows="3"
                  placeholder="Rincian tanggung jawab & tugas divisi..."
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-amber-500 transition"
                ></textarea>
              </div>

              <div class="flex items-center space-x-2 pt-2">
                <button
                  type="submit"
                  :disabled="savingDivision"
                  class="flex-1 py-2.5 rounded-xl bg-gradient-to-r from-amber-600 to-rose-600 hover:from-amber-500 hover:to-rose-500 text-white font-bold shadow-lg shadow-emerald-950/60 transition disabled:opacity-50"
                >
                  {{ savingDivision ? 'Menyimpan...' : (isEditingDivision ? 'Simpan Perubahan' : 'Tambah Divisi') }}
                </button>
                <button
                  v-if="isEditingDivision"
                  type="button"
                  @click="cancelEditDivision"
                  class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 font-semibold border border-slate-300 transition"
                >
                  Batal
                </button>
              </div>
            </form>
          </div>

          <!-- Right Card: Daftar Divisi Aktif (7 cols) -->
          <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-4">
              <div>
                <h3 class="text-sm font-bold text-slate-900">Daftar Divisi Kepanitiaan ({{ divisions.length }})</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Struktur divisi kepanitiaan resmi Haul &amp; Rojabiyyah 2026.</p>
              </div>
            </div>

            <div class="space-y-3">
              <div
                v-for="div in divisions"
                :key="div.id"
                class="p-4 rounded-2xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition flex items-center justify-between gap-3 group"
              >
                <div class="min-w-0 flex-1">
                  <div class="flex items-center space-x-2">
                    <span class="w-6 h-6 rounded-lg bg-amber-950/60 border border-amber-700/60 text-amber-400 flex items-center justify-center font-bold text-xs flex-shrink-0">
                      {{ div.name.charAt(0) }}
                    </span>
                    <span class="font-bold text-slate-800 text-xs tracking-wide truncate">{{ div.name }}</span>
                    <span class="px-2 py-0.5 rounded-full bg-teal-950/80 text-teal-300 border border-teal-800 text-[10px] font-mono font-bold flex-shrink-0">
                      {{ div.members_count || 0 }} Anggota
                    </span>
                  </div>
                  <p v-if="div.description" class="text-[11px] text-slate-500 mt-1 pl-8 line-clamp-2">
                    {{ div.description }}
                  </p>
                  <p v-else class="text-[11px] text-slate-600 italic mt-1 pl-8">
                    Tidak ada deskripsi khusus
                  </p>
                </div>

                <div class="flex items-center space-x-1 flex-shrink-0">
                  <button
                    @click="editDivision(div)"
                    title="Edit Nama / Deskripsi Divisi"
                    class="text-slate-500 hover:text-white p-2 rounded-xl hover:bg-white transition"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                  </button>
                  <button
                    @click="deleteDivision(div)"
                    title="Hapus Divisi"
                    class="text-slate-500 hover:text-rose-400 p-2 rounded-xl hover:bg-white transition"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- ===================================================================== -->
        <!-- TAB 3: WILAYAH OPERASIONAL (Exact Image 10 Layout)                     -->
        <!-- ===================================================================== -->
        <div v-if="adminTab === 'regions'" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
          <!-- Left Card: + Tambah Wilayah Baru (5 cols) -->
          <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center space-x-2">
              <span class="text-rose-400 font-black">+</span>
              <span>Tambah Wilayah Baru</span>
            </h3>

            <form @submit.prevent="submitAddRegion" class="space-y-4 text-xs">
              <div>
                <label class="block font-semibold text-slate-700 mb-1">Nama Wilayah</label>
                <input
                  v-model="regionForm.name"
                  type="text"
                  required
                  placeholder="misal: Wilayah Utara"
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-emerald-500"
                />
              </div>

              <div>
                <label class="block font-semibold text-slate-700 mb-1">Kode Unik Wilayah</label>
                <input
                  v-model="regionForm.code"
                  type="text"
                  required
                  placeholder="MISAL: REG-UTR"
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 uppercase font-mono placeholder-slate-500 focus:outline-none focus:border-emerald-500"
                />
              </div>

              <div>
                <label class="block font-semibold text-slate-700 mb-1">Keterangan Wilayah</label>
                <textarea
                  v-model="regionForm.description"
                  rows="3"
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-emerald-500"
                ></textarea>
              </div>

              <button
                type="submit"
                :disabled="savingRegion"
                class="w-full py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold shadow-lg shadow-emerald-950/60 transition disabled:opacity-50"
              >
                {{ savingRegion ? 'Menyimpan...' : 'Simpan Wilayah' }}
              </button>
            </form>
          </div>

          <!-- Right Card: Daftar Wilayah Operasional (7 cols) -->
          <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-4">
              <h3 class="text-sm font-bold text-slate-900">Daftar Wilayah Operasional ({{ regionList.length }})</h3>
            </div>

            <div class="space-y-3">
              <div
                v-for="reg in regionList"
                :key="reg.id"
                class="p-4 rounded-2xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition flex items-center justify-between gap-3"
              >
                <div>
                  <div class="flex items-center space-x-2">
                    <span class="font-bold text-slate-800 text-xs">{{ reg.name }}</span>
                    <span class="px-2 py-0.5 rounded bg-sky-950/80 border border-sky-800 text-sky-400 font-mono text-[9px] font-bold">
                      {{ reg.code || 'REG' }}
                    </span>
                  </div>
                  <p class="text-[11px] text-slate-500 mt-1">{{ reg.description || '-' }}</p>
                </div>

                <button
                  @click="deleteRegion(reg)"
                  title="Hapus Wilayah"
                  class="text-slate-500 hover:text-rose-400 p-2 rounded-xl hover:bg-white transition flex-shrink-0"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- ===================================================================== -->
        <!-- TAB 4: LEMBAGA / MITRA LUAR (Exact Screenshot 4 Layout)               -->
        <!-- ===================================================================== -->
        <div v-if="adminTab === 'partners'" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
          <!-- Left Card: + Tambah Lembaga / Mitra (5 cols) -->
          <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center space-x-2">
              <span class="text-rose-400 font-black">+</span>
              <span>Tambah Lembaga / Mitra</span>
            </h3>

            <form @submit.prevent="submitAddPartner" class="space-y-4 text-xs">
              <div>
                <label class="block font-semibold text-slate-700 mb-1">Nama Lembaga / Mitra *</label>
                <input
                  v-model="partnerForm.name"
                  type="text"
                  required
                  placeholder="misal: SMK TI Annuriyyah"
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-emerald-500"
                />
              </div>

              <div>
                <label class="block font-semibold text-slate-700 mb-1">Nama Kontak / PIC</label>
                <input
                  v-model="partnerForm.contact"
                  type="text"
                  placeholder="misal: Ustadz M. Syakir"
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-emerald-500"
                />
              </div>

              <div>
                <label class="block font-semibold text-slate-700 mb-1">No. Telepon / WhatsApp</label>
                <input
                  v-model="partnerForm.phone"
                  type="text"
                  placeholder="misal: 081234567890"
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-emerald-500"
                />
              </div>

              <div>
                <label class="block font-semibold text-slate-700 mb-1">Keterangan / Alamat</label>
                <textarea
                  v-model="partnerForm.description"
                  rows="3"
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-emerald-500"
                ></textarea>
              </div>

              <button
                type="submit"
                :disabled="savingPartner"
                class="w-full py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold shadow-lg shadow-emerald-950/60 transition disabled:opacity-50"
              >
                {{ savingPartner ? 'Menyimpan...' : 'Simpan Lembaga / Mitra' }}
              </button>
            </form>
          </div>

          <!-- Right Card: Daftar Lembaga Mitra (7 cols) -->
          <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-4">
              <h3 class="text-sm font-bold text-slate-900">Daftar Lembaga Mitra Terdaftar ({{ partnerList.length }})</h3>
            </div>

            <div class="space-y-3">
              <div
                v-for="partner in partnerList"
                :key="partner.id"
                class="p-4 rounded-2xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition flex items-center justify-between gap-3"
              >
                <div>
                  <div class="font-bold text-white text-xs">{{ partner.name }}</div>
                  <div class="flex items-center space-x-3 text-[11px] text-slate-500 mt-1">
                    <span v-if="partner.contact">PIC: {{ partner.contact }}</span>
                    <span v-if="partner.phone" class="font-mono text-emerald-400">{{ partner.phone }}</span>
                  </div>
                </div>

                <button
                  @click="deletePartner(partner)"
                  title="Hapus Mitra"
                  class="text-slate-500 hover:text-rose-400 p-2 rounded-xl hover:bg-white transition flex-shrink-0"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- ===================================================================== -->
        <!-- TAB 5: KOLOM KUSTOM DINAMIS (Exact Screenshot 5 Layout)               -->
        <!-- ===================================================================== -->
        <div v-if="adminTab === 'custom_fields'" class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
          <!-- Left Card: + Tambah Kolom Kustom (5 cols) -->
          <div class="lg:col-span-5 bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <h3 class="text-sm font-bold text-slate-900 mb-4 flex items-center space-x-2">
              <span class="text-rose-400 font-black">+</span>
              <span>Tambah Kolom Kustom</span>
            </h3>

            <form @submit.prevent="submitAddCustomField" class="space-y-4 text-xs">
              <div>
                <label class="block font-semibold text-slate-700 mb-1">Pilih Modul</label>
                <select
                  v-model="fieldForm.module"
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
                >
                  <option value="Inventaris">Inventaris Barang</option>
                  <option value="Anggota">Data Anggota</option>
                  <option value="Peminjaman">Peminjaman Alat</option>
                </select>
              </div>

              <div>
                <label class="block font-semibold text-slate-700 mb-1">Nama Kolom / Field Key</label>
                <input
                  v-model="fieldForm.name"
                  type="text"
                  required
                  placeholder="misal: serial_number, live_stream_url"
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 font-mono placeholder-slate-500 focus:outline-none focus:border-emerald-500"
                />
              </div>

              <div>
                <label class="block font-semibold text-slate-700 mb-1">Label Tampilan</label>
                <input
                  v-model="fieldForm.label"
                  type="text"
                  required
                  placeholder="misal: Nomor Seri Pabrik (S/N)"
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-emerald-500"
                />
              </div>

              <div>
                <label class="block font-semibold text-slate-700 mb-1">Tipe Input</label>
                <select
                  v-model="fieldForm.type"
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
                >
                  <option value="Text">Teks Singkat</option>
                  <option value="Number">Angka / Nominal</option>
                  <option value="URL">Tautan URL</option>
                  <option value="Dropdown">Pilihan Dropdown</option>
                </select>
              </div>

              <button
                type="submit"
                class="w-full py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold shadow-lg shadow-emerald-950/60 transition"
              >
                Buat Kolom Baru
              </button>
            </form>
          </div>

          <!-- Right Card: Kolom Dinamis Aktif (7 cols) -->
          <div class="lg:col-span-7 bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
            <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-4">
              <h3 class="text-sm font-bold text-slate-900">Kolom Dinamis Aktif ({{ customFieldList.length }})</h3>
            </div>

            <div class="space-y-3">
              <div
                v-for="(cf, idx) in customFieldList"
                :key="idx"
                class="p-4 rounded-2xl bg-slate-50 border border-slate-200 hover:border-slate-300 transition flex items-center justify-between gap-3"
              >
                <div>
                  <div class="flex items-center space-x-2">
                    <span class="font-bold text-slate-800 text-xs">{{ cf.label }}</span>
                    <span class="px-2 py-0.5 rounded bg-pink-950/80 border border-pink-800 text-pink-300 font-mono text-[9px]">
                      {{ cf.type }}
                    </span>
                  </div>
                  <p class="text-[11px] text-slate-500 mt-1">
                    Modul: <strong class="text-slate-700">{{ cf.module }}</strong> • Field: <code class="text-cyan-400">{{ cf.name }}</code>
                  </p>
                </div>

                <button
                  @click="deleteCustomField(idx)"
                  title="Hapus Kolom"
                  class="text-slate-500 hover:text-rose-400 p-2 rounded-xl hover:bg-white transition flex-shrink-0"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- ===================================================================== -->
        <!-- TAB 6: KELOLA PENGELOLA ADMIN (Tab 6 Match)                           -->
        <!-- ===================================================================== -->
        <div v-if="adminTab === 'admins'" class="space-y-6">
          <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
              <h3 class="text-base font-bold text-slate-900">Daftar Akun Pengelola Admin</h3>
              <p class="text-xs text-slate-500 mt-0.5">Pengguna dengan kewenangan mengatur sistem, logistik, dan transaksi kas.</p>
            </div>
            <button
              @click="openAddAdminModal"
              class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white text-xs font-bold transition shadow-lg"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
              <span>+ Tambah Admin Baru</span>
            </button>
          </div>

          <!-- Table of Admins -->
          <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
              <table class="w-full text-left text-xs">
                <thead class="bg-[#0e172a] border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
                  <tr>
                    <th class="py-3.5 px-4">PENGELOLA ADMIN</th>
                    <th class="py-3.5 px-4">ROLE / HAK AKSES</th>
                    <th class="py-3.5 px-4">NO. WHATSAPP</th>
                    <th class="py-3.5 px-4 text-center">STATUS</th>
                    <th class="py-3.5 px-4 text-center">AKSI</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                  <tr v-for="adm in adminList" :key="adm.id" class="hover:bg-slate-50 transition">
                    <td class="py-3.5 px-4">
                      <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-xl bg-slate-100 border border-slate-300/60 flex items-center justify-center font-bold text-white text-xs">
                          {{ adm.name.charAt(0).toUpperCase() }}
                        </div>
                        <div>
                          <div class="font-bold text-white">{{ adm.name }}</div>
                          <div class="text-[11px] text-slate-500 font-mono">{{ adm.email }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="py-3.5 px-4">
                      <span
                        :class="[
                          'px-2.5 py-0.5 rounded-full text-[10px] font-bold border',
                          adm.role === 'super_admin' ? 'bg-rose-950/70 text-rose-300 border-rose-800' :
                          adm.role === 'admin' ? 'bg-teal-950/70 text-teal-300 border-teal-800' :
                          'bg-amber-950/70 text-amber-300 border-amber-800'
                        ]"
                      >
                        {{ adm.role === 'super_admin' ? 'Super Admin' : adm.role === 'admin' ? 'Admin Operasional' : 'Bendahara' }}
                      </span>
                    </td>
                    <td class="py-3.5 px-4 font-mono text-slate-700">{{ adm.phone_wa || '-' }}</td>
                    <td class="py-3.5 px-4 text-center">
                      <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full bg-emerald-950/70 text-emerald-300 border border-emerald-800 text-[10px] font-bold">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                        <span>Aktif</span>
                      </span>
                    </td>
                    <td class="py-3.5 px-4 text-center">
                      <button
                        v-if="adm.id !== authStore.user?.id"
                        @click="deleteAdmin(adm)"
                        title="Hapus Admin"
                        class="text-slate-500 hover:text-rose-400 p-1.5 rounded-lg hover:bg-white transition"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                      </button>
                      <span v-else class="text-[10px] text-slate-600 font-mono">Saya</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- ===================================================================== -->
        <!-- TAB 7: RESET DATA OPERASIONAL & PEMBERSIHAN DATA (DANGER ZONE)         -->
        <!-- ===================================================================== -->
        <div v-if="adminTab === 'reset'" class="space-y-6">
          <!-- Danger Header & Security Notice -->
          <div class="p-5 bg-rose-950/30 border border-rose-800/60 rounded-3xl text-rose-200 text-xs space-y-2 shadow-lg">
            <h3 class="font-bold text-sm text-rose-400 flex items-center space-x-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
              <span>Pusat Pembersihan Data &amp; Reset Operasional (Danger Zone)</span>
            </h3>
            <ul class="list-disc list-inside space-y-1 text-slate-700">
              <li>Sistem akan otomatis membuat <strong class="text-rose-300">File Snapshot Cadangan (JSON Backup)</strong> di server sebelum data dihapus.</li>
              <li>Akun <strong>Super Admin &amp; Admin Pengelola</strong>, pengaturan branding, dan <strong>Audit Log TIDAK akan terhapus</strong>.</li>
              <li>Pilih modul yang ingin dibersihkan (Anggota Relawan, Barang Inventaris, Lembaga Mitra, Peminjaman, Kas, Laporan, atau Jadwal Tugas).</li>
              <li>Wajib memasukkan kata sandi akun Anda dan mengetik frasa konfirmasi: <code class="bg-rose-900/60 text-rose-200 px-1.5 py-0.5 rounded font-bold font-mono">RESET OPERASIONAL</code> atau <code class="bg-rose-900/60 text-rose-200 px-1.5 py-0.5 rounded font-bold font-mono">RESET</code>.</li>
            </ul>
          </div>

          <!-- Success Notification Banner -->
          <div v-if="dangerSuccessResult" class="p-5 bg-emerald-950/40 border border-emerald-700/60 rounded-3xl text-emerald-200 text-xs space-y-2 shadow-lg">
            <div class="flex items-center space-x-2 text-emerald-400 font-bold text-sm">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              <span>Pembersihan Data Berhasil Dilakukan!</span>
            </div>
            <p class="text-slate-700">{{ dangerSuccessResult.message }}</p>
            <div class="pt-1 font-mono text-[11px]">
              <span class="px-2 py-1 bg-emerald-900/40 rounded border border-emerald-700 text-emerald-300">
                File Snapshot Cadangan: {{ dangerSuccessResult.backup_file }}
              </span>
            </div>
          </div>

          <!-- Module Selector Card -->
          <div class="bg-white rounded-3xl p-6 sm:p-7 shadow-sm border border-slate-200 space-y-6 text-xs">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-4 border-b border-slate-200 gap-3">
              <div>
                <h3 class="text-sm font-bold text-slate-900">Pilih Modul yang Akan Direset &amp; Pratinjau Dampak:</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Pilih modul spesifik atau gunakan tombol Reset Total di sebelah kanan.</p>
              </div>

              <div class="flex items-center space-x-2">
                <button
                  type="button"
                  @click="selectAllDangerModules"
                  class="px-3 py-1.5 rounded-xl bg-rose-950/60 hover:bg-rose-900 border border-rose-700/70 text-rose-300 text-xs font-bold transition flex items-center space-x-1.5"
                >
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                  <span>Pilih Semua (Reset Total)</span>
                </button>
                <button
                  type="button"
                  @click="clearDangerModuleSelection"
                  class="px-3 py-1.5 rounded-xl bg-white hover:bg-slate-100 border border-slate-300 text-slate-500 text-xs font-semibold transition"
                >
                  Hapus Pilihan
                </button>
              </div>
            </div>

            <!-- Checkboxes Grid (7 modules) -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- 1. DATA ANGGOTA -->
              <label
                class="p-4 rounded-2xl border flex items-start space-x-3 cursor-pointer transition select-none"
                :class="dangerSelectedModules.includes('members') ? 'border-emerald-500 bg-rose-950/30' : 'border-slate-200 bg-slate-50 hover:border-slate-300'"
              >
                <input type="checkbox" value="members" v-model="dangerSelectedModules" class="mt-1 rounded text-rose-600 focus:ring-rose-500 bg-white border-slate-300" />
                <div class="flex-1">
                  <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-800 text-xs block">Data Anggota Relawan &amp; Akun Anggota</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-rose-900/60 text-rose-300 border border-rose-700/60">
                      {{ dangerPreviewCounts.members_count || 0 }} Anggota
                    </span>
                  </div>
                  <span class="text-slate-500 text-[11px] block mt-1 leading-relaxed">
                    Akan menghapus semua akun relawan role 'anggota', profil identitas, serta sirkulasi peminjaman/tugas anggota. <strong class="text-rose-400">Akun Super Admin &amp; Admin tetap terlindungi.</strong>
                  </span>
                </div>
              </label>

              <!-- 2. DATA BARANG INVENTARIS -->
              <label
                class="p-4 rounded-2xl border flex items-start space-x-3 cursor-pointer transition select-none"
                :class="dangerSelectedModules.includes('inventory') ? 'border-emerald-500 bg-rose-950/30' : 'border-slate-200 bg-slate-50 hover:border-slate-300'"
              >
                <input type="checkbox" value="inventory" v-model="dangerSelectedModules" class="mt-1 rounded text-rose-600 focus:ring-rose-500 bg-white border-slate-300" />
                <div class="flex-1">
                  <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-800 text-xs block">Katalog Barang &amp; Inventaris Gudang</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-indigo-900/60 text-indigo-300 border border-indigo-700/60">
                      {{ dangerPreviewCounts.items_count || 0 }} Barang
                    </span>
                  </div>
                  <span class="text-slate-500 text-[11px] block mt-1 leading-relaxed">
                    Akan menghapus seluruh master barang logistik gudang, kuantitas stok, dan riwayat sirkulasi peminjaman.
                  </span>
                </div>
              </label>

              <!-- 3. SIRKULASI PEMINJAMAN -->
              <label
                class="p-4 rounded-2xl border flex items-start space-x-3 cursor-pointer transition select-none"
                :class="dangerSelectedModules.includes('loans') ? 'border-emerald-500 bg-rose-950/30' : 'border-slate-200 bg-slate-50 hover:border-slate-300'"
              >
                <input type="checkbox" value="loans" v-model="dangerSelectedModules" class="mt-1 rounded text-rose-600 focus:ring-rose-500 bg-white border-slate-300" />
                <div class="flex-1">
                  <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-800 text-xs block">Sirkulasi Peminjaman Logistik</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-amber-900/60 text-amber-300 border border-amber-700/60">
                      {{ dangerPreviewCounts.loans_count || 0 }} Peminjaman
                    </span>
                  </div>
                  <span class="text-slate-500 text-[11px] block mt-1 leading-relaxed">
                    Akan menghapus formulir permohonan peminjaman dan mereset reserved stok gudang kembali ke 0.
                  </span>
                </div>
              </label>

              <!-- 4. LEMBAGA MITRA -->
              <label
                class="p-4 rounded-2xl border flex items-start space-x-3 cursor-pointer transition select-none"
                :class="dangerSelectedModules.includes('partners') ? 'border-emerald-500 bg-rose-950/30' : 'border-slate-200 bg-slate-50 hover:border-slate-300'"
              >
                <input type="checkbox" value="partners" v-model="dangerSelectedModules" class="mt-1 rounded text-rose-600 focus:ring-rose-500 bg-white border-slate-300" />
                <div class="flex-1">
                  <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-800 text-xs block">Data Lembaga / Mitra Luar</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-teal-900/60 text-teal-300 border border-teal-700/60">
                      {{ dangerPreviewCounts.partners_count || 0 }} Lembaga
                    </span>
                  </div>
                  <span class="text-slate-500 text-[11px] block mt-1 leading-relaxed">
                    Akan menghapus seluruh mitra eksternal penyedia aset dan melepaskan kaitan mitra pada barang.
                  </span>
                </div>
              </label>

              <!-- 5. LAPORAN LAPANGAN -->
              <label
                class="p-4 rounded-2xl border flex items-start space-x-3 cursor-pointer transition select-none"
                :class="dangerSelectedModules.includes('reports') ? 'border-emerald-500 bg-rose-950/30' : 'border-slate-200 bg-slate-50 hover:border-slate-300'"
              >
                <input type="checkbox" value="reports" v-model="dangerSelectedModules" class="mt-1 rounded text-rose-600 focus:ring-rose-500 bg-white border-slate-300" />
                <div class="flex-1">
                  <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-800 text-xs block">Laporan Lapangan &amp; Kendala Posko</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-sky-900/60 text-sky-300 border border-sky-700/60">
                      {{ dangerPreviewCounts.reports_count || 0 }} Laporan
                    </span>
                  </div>
                  <span class="text-slate-500 text-[11px] block mt-1 leading-relaxed">
                    Akan menghapus seluruh laporan kendala posko, dokumentasi foto, dan tanggapan respon.
                  </span>
                </div>
              </label>

              <!-- 6. TRANSAKSI KAS -->
              <label
                class="p-4 rounded-2xl border flex items-start space-x-3 cursor-pointer transition select-none"
                :class="dangerSelectedModules.includes('cash') ? 'border-emerald-500 bg-rose-950/30' : 'border-slate-200 bg-slate-50 hover:border-slate-300'"
              >
                <input type="checkbox" value="cash" v-model="dangerSelectedModules" class="mt-1 rounded text-rose-600 focus:ring-rose-500 bg-white border-slate-300" />
                <div class="flex-1">
                  <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-800 text-xs block">Transaksi Kas Keuangan</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-emerald-900/60 text-emerald-300 border border-emerald-700/60">
                      {{ dangerPreviewCounts.cash_transactions_count || 0 }} Transaksi
                    </span>
                  </div>
                  <span class="text-slate-500 text-[11px] block mt-1 leading-relaxed">
                    Akan mereset seluruh catatan penerimaan kas, infaq shodaqoh, dan rekap pengeluaran operasional.
                  </span>
                </div>
              </label>

              <!-- 7. PENUGASAN PERSONIL -->
              <label
                class="p-4 rounded-2xl border flex items-start space-x-3 cursor-pointer transition select-none md:col-span-2"
                :class="dangerSelectedModules.includes('assignments') ? 'border-emerald-500 bg-rose-950/30' : 'border-slate-200 bg-slate-50 hover:border-slate-300'"
              >
                <input type="checkbox" value="assignments" v-model="dangerSelectedModules" class="mt-1 rounded text-rose-600 focus:ring-rose-500 bg-white border-slate-300" />
                <div class="flex-1">
                  <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-800 text-xs block">Jadwal Penugasan Personil Posko</span>
                    <span class="px-2 py-0.5 rounded text-[10px] font-mono font-bold bg-purple-900/60 text-purple-300 border border-purple-700/60">
                      {{ dangerPreviewCounts.assignments_count || 0 }} Jadwal
                    </span>
                  </div>
                  <span class="text-slate-500 text-[11px] block mt-1 leading-relaxed">
                    Akan menghapus pembagian jadwal penugasan personil relawan ke posko-posko operasional kegiatan.
                  </span>
                </div>
              </label>
            </div>

            <!-- Confirmation & Execution Form -->
            <form @submit.prevent="executeDangerReset" class="p-5 sm:p-6 bg-slate-50 rounded-2xl border border-slate-200 space-y-4">
              <!-- Frasa Konfirmasi -->
              <div>
                <div class="flex items-center justify-between mb-1.5">
                  <label class="block font-semibold text-slate-700 text-xs">
                    Ketik Frasa Konfirmasi: <span class="text-rose-400 font-bold font-mono">RESET OPERASIONAL</span> atau <span class="text-rose-400 font-bold font-mono">RESET</span>
                  </label>
                  <button
                    type="button"
                    @click="dangerConfirmationPhrase = 'RESET OPERASIONAL'"
                    class="text-[11px] text-sky-400 hover:text-sky-300 underline font-semibold flex items-center space-x-1"
                  >
                    <span>Klik untuk Isi Otomatis</span>
                  </button>
                </div>
                <input
                  v-model="dangerConfirmationPhrase"
                  type="text"
                  required
                  placeholder="Ketik RESET OPERASIONAL atau klik isi otomatis di atas"
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl font-mono text-xs text-slate-800 placeholder-slate-500 focus:ring-2 focus:ring-rose-500 focus:outline-none"
                />
                <p v-if="dangerConfirmationPhrase && !isDangerPhraseValid" class="text-[11px] text-rose-400 mt-1">
                  Frasa belum sesuai. Harus tepat "RESET OPERASIONAL" atau "RESET".
                </p>
              </div>

              <!-- Password Verifikasi -->
              <div>
                <label class="block font-semibold text-slate-700 text-xs mb-1.5">
                  Kata Sandi Super Admin / Akun Anda *
                </label>
                <input
                  v-model="dangerPassword"
                  type="password"
                  required
                  placeholder="Masukkan password akun Anda saat ini"
                  class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-xs text-slate-800 placeholder-slate-500 focus:ring-2 focus:ring-rose-500 focus:outline-none"
                />
              </div>

              <!-- Error Alert if Any -->
              <div v-if="dangerErrorMessage" class="p-3 rounded-xl bg-rose-950/80 border border-rose-800 text-rose-200 text-xs flex items-center space-x-2">
                <svg class="w-4 h-4 text-rose-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ dangerErrorMessage }}</span>
              </div>

              <!-- Submit Button -->
              <button
                type="submit"
                :disabled="dangerExecuting || dangerSelectedModules.length === 0 || !isDangerPhraseValid || !dangerPassword"
                class="w-full py-3.5 bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold rounded-xl shadow-lg shadow-emerald-950/60 transition disabled:opacity-40 disabled:cursor-not-allowed text-xs flex items-center justify-center space-x-2"
              >
                <svg v-if="dangerExecuting" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                <span>{{ dangerExecuting ? 'Sedang Membuat Backup & Menjalankan Reset...' : `Eksekusi Pembersihan (${dangerSelectedModules.length} Modul Terpilih)` }}</span>
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- MODAL: TAMBAH ADMIN BARU                                                  -->
      <!-- ========================================================================= -->
      <div v-if="showAddAdminModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md p-4">
        <div class="relative w-full max-w-md bg-white border border-slate-200 rounded-3xl p-6 shadow-2xl text-slate-700">
          <div class="flex items-center justify-between pb-3 border-b border-slate-200 mb-4">
            <div>
              <h3 class="text-base font-bold text-slate-900">Tambah Pengelola Admin Baru</h3>
              <p class="text-[11px] text-slate-500 mt-0.5">Pilih anggota terdaftar atau daftarkan akun baru.</p>
            </div>
            <button @click="showAddAdminModal = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">✕</button>
          </div>

          <form @submit.prevent="submitAddAdmin" class="space-y-4 text-xs">
            <!-- PILIHAN OTOMATIS ANGGOTA TERDAFTAR -->
            <div class="p-3.5 rounded-2xl bg-emerald-50/80 border border-emerald-200 space-y-1.5">
              <label class="block font-bold text-emerald-950 text-xs flex items-center space-x-1.5">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span>Pilih Anggota yang Sudah Login/Terdaftar:</span>
              </label>
              <select
                v-model="selectedCandidateId"
                @change="onCandidateSelected"
                class="w-full px-3 py-2 bg-white border border-emerald-300 rounded-xl text-xs font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500"
              >
                <option value="">-- Ketik Manual / Buat Akun Baru --</option>
                <option
                  v-for="user in adminCandidates"
                  :key="user.id"
                  :value="user.id"
                >
                  {{ user.name }} ({{ user.member_no || 'Anggota' }}) - {{ user.email }}
                </option>
              </select>
              <p class="text-[11px] text-emerald-700">
                Pilih nama anggota di atas agar data nama, email, dan no WhatsApp terisi otomatis untuk langsung diangkat menjadi <strong>Super Admin</strong>.
              </p>
            </div>

            <div>
              <div class="flex items-center justify-between mb-1">
                <label class="font-semibold text-slate-700">Nama Lengkap *</label>
                <span v-if="selectedCandidateId" class="text-[10px] font-bold text-emerald-700 bg-emerald-100/80 px-2 py-0.5 rounded-full border border-emerald-200">Terisi Otomatis</span>
              </div>
              <input
                v-model="newAdminForm.name"
                type="text"
                required
                :readonly="!!selectedCandidateId"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
                :class="selectedCandidateId ? 'bg-slate-50 cursor-not-allowed text-slate-700 font-bold' : ''"
              />
            </div>

            <div>
              <div class="flex items-center justify-between mb-1">
                <label class="font-semibold text-slate-700">Email Akun *</label>
                <span v-if="selectedCandidateId" class="text-[10px] font-bold text-emerald-700 bg-emerald-100/80 px-2 py-0.5 rounded-full border border-emerald-200">Akun Terdaftar</span>
              </div>
              <input
                v-model="newAdminForm.email"
                type="email"
                required
                :readonly="!!selectedCandidateId"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
                :class="selectedCandidateId ? 'bg-slate-50 cursor-not-allowed font-medium' : ''"
              />
            </div>

            <div>
              <label class="block font-semibold text-slate-700 mb-1">Role / Hak Akses *</label>
              <select v-model="newAdminForm.role" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 font-bold focus:outline-none focus:border-emerald-500">
                <option value="super_admin">⭐ Super Admin (Akses Penuh Seluruh Sistem)</option>
                <option value="admin">Admin Operasional</option>
                <option value="bendahara">Bendahara</option>
              </select>
            </div>

            <div>
              <label class="block font-semibold text-slate-700 mb-1">No. WhatsApp</label>
              <input
                v-model="newAdminForm.phone_wa"
                type="text"
                placeholder="081234567890"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
              />
            </div>

            <div>
              <div class="flex items-center justify-between mb-1">
                <label class="font-semibold text-slate-700">
                  {{ selectedCandidateId ? 'Ganti Kata Sandi (Opsional)' : 'Kata Sandi Awal *' }}
                </label>
                <span v-if="selectedCandidateId" class="text-[10px] text-slate-400">Kosongkan jika tetap memakai kata sandi lama</span>
              </div>
              <input
                v-model="newAdminForm.password"
                type="password"
                :required="!selectedCandidateId"
                :minlength="selectedCandidateId ? undefined : 6"
                placeholder="••••••••••••"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
              />
            </div>

            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-200">
              <button type="button" @click="showAddAdminModal = false" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 rounded-xl text-slate-700 font-semibold transition">Batal</button>
              <button type="submit" :disabled="savingAdmin" class="px-5 py-2 bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold rounded-xl shadow transition disabled:opacity-50">
                {{ savingAdmin ? 'Menyimpan...' : (selectedCandidateId ? 'Angkat Menjadi Pengelola' : 'Simpan Akun Baru') }}
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- MODE 2: PENGATURAN PROFIL & AKUN (Khusus Anggota)                          -->
      <!-- ========================================================================= -->
      <div v-else-if="currentMode === 'profile'" class="space-y-6">
        <!-- Profile Banner -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div class="flex items-center space-x-3.5">
            <div class="w-12 h-12 rounded-2xl bg-[#1c0f1c] border border-rose-800/80 text-rose-400 flex items-center justify-center flex-shrink-0 shadow-inner">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>
            <div>
              <div class="flex items-center space-x-2">
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Pengaturan Profil &amp; Akun</h2>
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-950/80 border border-emerald-700/60 text-emerald-300 font-mono font-bold text-[10px]">
                  {{ profileForm.member_no || authStore.user?.member_no || 'RJB-001' }}
                </span>
              </div>
              <p class="text-xs text-slate-500 mt-0.5">Kelola identitas resmi, foto profil anggota, nomor kontak, serta keamanan akun Anda di portal Administrasi Rojabiyyah.</p>
            </div>
          </div>

          <div class="flex items-center space-x-2 bg-slate-50 p-1 rounded-2xl border border-slate-200 text-xs">
            <button
              @click="profileTab = 'data'"
              :class="profileTab === 'data' ? 'bg-[#059669] text-white shadow-md' : 'text-slate-500 hover:text-white'"
              class="px-4 py-2 rounded-xl font-bold flex items-center space-x-1.5 transition"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
              <span>Data Profil &amp; Foto</span>
            </button>
            <button
              @click="profileTab = 'security'"
              :class="profileTab === 'security' ? 'bg-[#059669] text-white shadow-md' : 'text-slate-500 hover:text-white'"
              class="px-4 py-2 rounded-xl font-bold flex items-center space-x-1.5 transition"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
              <span>Keamanan Kata Sandi</span>
            </button>
          </div>
        </div>

        <!-- TAB 1: DATA PROFIL & FOTO -->
        <div v-if="profileTab === 'data'" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
          <!-- LEFT COLUMN: KARTU IDENTITAS ANGGOTA -->
          <div class="lg:col-span-5 space-y-4">
            <div class="bg-gradient-to-b from-[#1c0f1c] via-[#101424] to-[#ffffff] border border-slate-200 rounded-3xl p-6 shadow-sm relative overflow-hidden text-center">
              <div class="flex items-center justify-between pb-4 border-b border-slate-200/70 mb-6 text-left">
                <div class="flex items-center space-x-2">
                  <div class="w-8 h-8 rounded-xl bg-[#090e1a] border border-slate-300 flex items-center justify-center text-teal-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                  </div>
                  <div>
                    <div class="text-[10px] font-black tracking-wider uppercase text-white">ROJABIYYAAH 2026</div>
                    <div class="text-[9px] text-amber-400 font-bold uppercase tracking-wider">KARTU IDENTITAS ANGGOTA</div>
                  </div>
                </div>
                <span class="px-2 py-0.5 rounded-full bg-white border border-slate-300 text-slate-700 font-mono font-bold text-[10px]">
                  {{ profileForm.member_no || authStore.user?.member_no || 'RJB-001' }}
                </span>
              </div>

              <div class="relative inline-block mx-auto mb-4">
                <div class="w-28 h-28 rounded-3xl bg-[#059669] text-white flex items-center justify-center text-3xl font-black shadow-sm shadow-emerald-950/60 overflow-hidden border-2 border-slate-300">
                  <img
                    v-if="profileForm.photo_path"
                    :src="profileForm.photo_path"
                    alt="Foto Profil"
                    class="w-full h-full object-cover"
                  />
                  <span v-else>{{ userInitial }}</span>
                </div>
                <button
                  type="button"
                  @click="$refs.profilePhotoRef?.click()"
                  class="absolute -bottom-1 -right-1 w-8 h-8 rounded-full bg-white border border-slate-300 text-slate-700 hover:text-white flex items-center justify-center shadow-lg transition"
                >
                  <svg class="w-4 h-4 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </button>
              </div>

              <h3 class="text-base font-extrabold text-slate-900 tracking-wide">
                {{ profileForm.name || authStore.user?.name || 'M. Fahri Tamamul Qomar' }}
              </h3>
              <p class="text-xs text-amber-300 font-medium mt-0.5">
                {{ selectedDivisionName }}
              </p>

              <div class="mt-3">
                <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-emerald-950/70 border border-emerald-600/60 text-emerald-300 font-bold text-xs">
                  <span class="w-2 h-2 rounded-full bg-emerald-400"></span>
                  <span>Anggota Aktif</span>
                </span>
              </div>

              <div class="mt-6 pt-4 border-t border-slate-200 flex items-center justify-between text-left text-xs">
                <div>
                  <span class="text-[10px] text-slate-500 uppercase tracking-wider block">Username Akun</span>
                  <span class="text-white font-mono font-bold text-xs">@{{ authStore.user?.username || 'Fahri' }}</span>
                </div>
                <div class="w-7 h-7 text-slate-500 flex items-center justify-center">
                  <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M3 3h8v8H3V3zm2 2v4h4V5H5zm8-2h8v8h-8V3zm2 2v4h4V5h-4zM3 13h8v8H3v-8zm2 2v4h4v-4H5zm13-2h3v2h-3v-2zm-5 0h2v3h-2v-3zm2 3h3v2h-3v-2zm3 2h3v3h-3v-3zm-5 1h2v2h-2v-2zm2 2h3v2h-3v-2z"/></svg>
                </div>
              </div>
            </div>

            <!-- Upload Box -->
            <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-sm text-xs space-y-2">
              <div class="flex items-center space-x-2 text-slate-700 font-bold">
                <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>UPLOAD FOTO PROFIL</span>
              </div>
              <p class="text-slate-500 text-[11px] leading-relaxed">
                Gunakan foto portrait resmi atau semi-formal yang jelas. Format JPG, PNG, atau WEBP (otomatis dioptimalkan).
              </p>

              <input
                ref="profilePhotoRef"
                type="file"
                accept="image/*"
                class="hidden"
                @change="handleProfilePhotoUpload"
              />

              <div class="flex items-center space-x-2 pt-2">
                <button
                  type="button"
                  @click="$refs.profilePhotoRef?.click()"
                  class="flex-1 py-2.5 px-4 rounded-xl bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 font-bold flex items-center justify-center space-x-2 transition"
                >
                  <svg class="w-4 h-4 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                  <span>{{ profileForm.photo_path ? 'Ganti Foto Profil' : 'Pilih Foto dari Galeri' }}</span>
                </button>
                <button
                  v-if="profileForm.photo_path"
                  type="button"
                  @click="removeProfilePhoto"
                  class="py-2.5 px-3 rounded-xl bg-rose-950/60 hover:bg-rose-900 border border-rose-800 text-rose-300 font-bold text-xs transition"
                  title="Hapus Foto Profil"
                >
                  Hapus
                </button>
              </div>
            </div>
          </div>

          <!-- RIGHT COLUMN: INFORMASI PROFIL & PENUGASAN -->
          <div class="lg:col-span-7">
            <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-sm">
              <div class="mb-5">
                <div class="flex items-center space-x-2 font-bold text-white text-sm">
                  <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                  <span>Informasi Profil &amp; Penugasan</span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">
                  Pastikan nomor WhatsApp dan data penugasan Anda terisi dengan benar.
                </p>
              </div>

              <form @submit.prevent="saveProfile" class="space-y-4 text-xs">
                <div>
                  <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
                  <input
                    v-model="profileForm.name"
                    type="text"
                    required
                    class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 transition"
                  />
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block font-semibold text-slate-700 mb-1">Email Akun</label>
                    <input
                      v-model="profileForm.email"
                      type="email"
                      class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 transition"
                    />
                  </div>
                  <div>
                    <label class="block font-semibold text-slate-700 mb-1">No. WhatsApp / HP</label>
                    <input
                      v-model="profileForm.phone_wa"
                      type="text"
                      placeholder="081234567890"
                      class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 transition"
                    />
                  </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block font-semibold text-slate-700 mb-1">Divisi Penugasan</label>
                    <select
                      v-model="profileForm.division_id"
                      class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 transition"
                    >
                      <option :value="null">-- Pilih Divisi Tugas --</option>
                      <option v-for="d in divisions" :key="d.id" :value="d.id">{{ d.name }}</option>
                    </select>
                  </div>
                  <div>
                    <label class="block font-semibold text-slate-700 mb-1">Domisili Asrama / Pondok</label>
                    <input
                      v-model="profileForm.domicile"
                      type="text"
                      class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 transition"
                    />
                  </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                  <div>
                    <label class="block font-semibold text-slate-700 mb-1">Pendidikan / Instansi</label>
                    <input
                      v-model="profileForm.education"
                      type="text"
                      class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 transition"
                    />
                  </div>
                  <div>
                    <label class="block font-semibold text-slate-700 mb-1">Kelas / Tingkat / Angkatan</label>
                    <input
                      v-model="profileForm.class"
                      type="text"
                      placeholder="misal: Abiturent 2024, Semester 6"
                      class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 transition"
                    />
                  </div>
                </div>

                <div>
                  <label class="block font-semibold text-slate-700 mb-2">Ukuran Seragam / Kaos</label>
                  <div class="flex items-center space-x-2">
                    <button
                      v-for="sz in ['S', 'M', 'L', 'XL', 'XXL', 'XXXL']"
                      :key="sz"
                      type="button"
                      @click="profileForm.uniform_size = sz"
                      :class="profileForm.uniform_size === sz ? 'bg-sky-500 text-slate-950 font-bold border-sky-400' : 'bg-white text-slate-500 border-slate-300 hover:text-white'"
                      class="w-11 py-2 rounded-xl text-xs font-semibold border transition"
                    >
                      {{ sz }}
                    </button>
                  </div>
                </div>

                <div>
                  <label class="block font-semibold text-slate-700 mb-1">Alamat Lengkap / Asal</label>
                  <textarea
                    v-model="profileForm.address"
                    rows="2"
                    placeholder="Desa, Kecamatan, Kabupaten asal"
                    class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-emerald-500 transition"
                  ></textarea>
                </div>

                <div class="pt-4 border-t border-slate-200 flex items-center justify-between">
                  <span class="text-slate-500 text-[11px]">Perubahan foto dan data profil akan langsung tersimpan.</span>
                  <button
                    type="submit"
                    :disabled="saving"
                    class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold shadow-lg shadow-emerald-950/60 transition disabled:opacity-50"
                  >
                    <span>Simpan Perubahan Profil</span>
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>

        <!-- TAB 2: KEAMANAN KATA SANDI -->
        <div v-else class="max-w-xl mx-auto bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-sm">
          <h3 class="text-base font-bold text-slate-900 mb-4">Ubah Kata Sandi Akun</h3>
          <form @submit.prevent="changePassword" class="space-y-4 text-xs">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Kata Sandi Saat Ini</label>
              <input v-model="passForm.current_password" type="password" required class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500" />
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Kata Sandi Baru</label>
              <input v-model="passForm.password" type="password" required minlength="6" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500" />
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Konfirmasi Kata Sandi Baru</label>
              <input v-model="passForm.password_confirmation" type="password" required minlength="6" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500" />
            </div>
            <div class="pt-3 border-t border-slate-200 flex justify-end">
              <button type="submit" class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold shadow-lg transition">
                Simpan Kata Sandi
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- MODE 3: GOOGLE DRIVE UNDUHAN (Akses Berkas untuk Anggota & Petugas)         -->
      <!-- ========================================================================= -->
      <div v-else-if="currentMode === 'drive'" class="space-y-6">
        <!-- Top Header Card -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 sm:p-7 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-4">
          <div>
            <div class="flex items-center space-x-2 mb-2.5">
              <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-[#0e2230] border border-cyan-800/60 text-cyan-300">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
                <span>Pusat Unduhan &amp; Dokumentasi</span>
              </span>
              <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-[#181a38] border border-indigo-800/60 text-indigo-300">
                Hak Akses: Lihat &amp; Download
              </span>
            </div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Berkas &amp; Dokumentasi Resmi</h2>
            <p class="text-xs text-slate-500 mt-1 max-w-2xl leading-relaxed">
              Akses dan unduh materi resmi kegiatan seperti foto dokumentasi, video haul, desain media, serta panduan tugas lapangan yang telah diunggah oleh tim admin &amp; panitia.
            </p>
          </div>

          <div class="flex flex-wrap items-center gap-2 self-start md:self-auto">
            <button
              @click="copyDriveLink"
              class="px-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold flex items-center space-x-1.5 transition"
              title="Salin tautan folder Google Drive"
            >
              <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
              <span>{{ copiedLink ? 'Tersalin!' : 'Salin Link' }}</span>
            </button>
            <button
              @click="refreshDrive"
              class="px-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold flex items-center space-x-1.5 transition"
              title="Muat ulang pratinjau Drive"
            >
              <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
              <span>Refresh</span>
            </button>
            <a
              :href="gdriveFolderUrl"
              target="_blank"
              class="px-5 py-2.5 rounded-xl bg-[#be123c] hover:bg-[#9f1239] text-white text-xs font-bold shadow-lg shadow-emerald-950/60 flex items-center space-x-1.5 transition"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
              <span>Buka &amp; Download di Google Drive</span>
              <svg class="w-3.5 h-3.5 opacity-80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            </a>
          </div>
        </div>

        <!-- Main Storage Container Card -->
        <div class="bg-white rounded-3xl border border-slate-200 p-5 sm:p-7 shadow-sm space-y-5">
          <div class="flex items-center justify-between pb-3 border-b border-slate-200">
            <div class="flex items-center space-x-2.5">
              <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/></svg>
              <h3 class="text-sm font-bold text-slate-900 tracking-wide">Jelajahi Berkas &amp; Unduh File</h3>
            </div>
            <span class="text-xs text-slate-500 font-medium">Pratinjau Cloud Storage</span>
          </div>

          <!-- Live Embedded Google Drive Canvas (Exact Match to Screenshots) -->
          <div class="w-full bg-white rounded-2xl overflow-hidden shadow-inner border border-slate-300/60 relative">
            <iframe
              :key="driveRefreshKey"
              :src="gdriveEmbedUrl"
              class="w-full h-[540px] sm:h-[620px] border-0 bg-white"
              allow="autoplay"
            ></iframe>
          </div>

          <!-- Bottom Info Cards -->
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-1">
            <!-- Left Info Card: Cara Download -->
            <div class="p-4 rounded-2xl bg-[#080e1a] border border-slate-200 flex items-start space-x-3 text-xs leading-relaxed">
              <svg class="w-4 h-4 text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <p>
                <strong class="text-white font-bold">Cara Download:</strong>
                <span class="text-slate-500"> Klik dua kali pada file di pratinjau di atas, atau klik tombol <strong class="text-slate-700">"Buka &amp; Download di Google Drive"</strong> di kanan atas untuk mengunduh banyak file sekaligus.</span>
              </p>
            </div>

            <!-- Right Info Card: Perlu File Khusus? -->
            <div class="p-4 rounded-2xl bg-[#080e1a] border border-slate-200 flex items-start space-x-3 text-xs leading-relaxed">
              <svg class="w-4 h-4 text-sky-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <p>
                <strong class="text-white font-bold">Perlu File Khusus?</strong>
                <span class="text-slate-500"> Jika Anda membutuhkan materi foto mentah atau rekaman resolusi tinggi yang belum ada, hubungi tim koordinator dokumentasi.</span>
              </p>
            </div>
          </div>
        </div>
      </div>

    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import DashboardLayout from '../../layouts/DashboardLayout.vue';
import { useAuthStore } from '../../stores/auth';
import { useSettingsStore } from '../../stores/settings';
import api from '../../services/api';

const route = useRoute();
const authStore = useAuthStore();
const settingsStore = useSettingsStore();

const isAdminUser = computed(() => ['super_admin', 'admin', 'bendahara'].includes(authStore.user?.role));

// Mode: Admin ('system'), Member ('profile'), Shared ('drive')
const currentMode = computed(() => {
  const tab = route.query.tab;
  if (tab === 'drive') return 'drive';
  if (tab === 'admins') return 'system';
  if (tab === 'divisions') return 'system';
  if (tab === 'profile') return 'profile';
  if (tab === 'system') return 'system';

  // Default fallback based on role:
  return isAdminUser.value ? 'system' : 'profile';
});

// Admin Sub-Tabs
const adminTab = ref('branding'); // 'branding', 'categories', 'regions', 'partners', 'custom_fields', 'admins', 'reset'

// Member Sub-Tabs
const profileTab = ref('data'); // 'data', 'security'

// =========================================================================
// TAB 1: BRANDING & SYSTEM CONFIGURATION
// =========================================================================
const savingSystem = ref(false);
const logoInputRef = ref(null);
const kopLogoInputRef = ref(null);
const systemForm = ref({
  app_name: 'MEDIA ROJABIYYAH',
  app_sub_name: 'Aktif, Komunikatif, dan Transparan',
  sub_theme: 'Haul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad & Rojabiyyah 2026',
  hero_title_white: 'Media Rojabiyyah',
  hero_title_gradient: 'Haul Ke-5 & Rojabiyyah 2026',
  footer_copyright: '© 2026 Aplikasi Administrasi Haul Ke-5 & Rojabiyyah 2026.',
  kop_name: 'DOKUMENTASI ROJABIYYAH 2026',
  kop_sub_theme: 'Haul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad & Rojabiyyah 2026',
  kop_sub_short: 'Haul Ke-5 & Rojabiyyah 2026',
  kop_logo_url: '/logo.png',
  gdrive_folder_url: 'https://drive.google.com/drive/folders/1l91WsFBV_sZ89DlvzvSXlgedX0pOwsfa?usp=sharing',
  contact_wa: '081230951971',
  contact_email: 'dokumentasirjb@gmail.com',
  contact_address: 'Bumi Damai Al-Muhibbin Tambakberas Jombang',
  logo_url: '/logo.png',
  letterhead_text: 'DOKUMENTASI ROJABIYYAH 2026\nHaul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad & Rojabiyyah 2026',
});

const handleLogoUpload = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = (event) => {
    systemForm.value.logo_url = event.target.result;
  };
  reader.readAsDataURL(file);
};

const resetDefaultLogo = () => {
  systemForm.value.logo_url = '/logo.png';
};

const triggerKopLogoInput = () => {
  kopLogoInputRef.value?.click();
};

const handleKopLogoUpload = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = (event) => {
    systemForm.value.kop_logo_url = event.target.result;
  };
  reader.readAsDataURL(file);
};

const useAppLogoForKop = () => {
  systemForm.value.kop_logo_url = systemForm.value.logo_url || '/logo.png';
};

const resetKopLogo = () => {
  systemForm.value.kop_logo_url = '/logo.png';
};

const fetchSettings = async () => {
  try {
    const res = await api.get('/settings');
    if (res.data) {
      Object.keys(res.data).forEach((key) => {
        if (key in systemForm.value) {
          systemForm.value[key] = res.data[key];
        }
      });
      if (res.data.custom_fields) {
        try {
          customFieldList.value = JSON.parse(res.data.custom_fields);
        } catch {
          // ignore
        }
      }
    }
  } catch (err) {
    console.error('Gagal mengambil pengaturan sistem:', err);
  }
};

const saveSystemSettings = async () => {
  savingSystem.value = true;
  try {
    const payload = {
      ...systemForm.value,
      custom_fields: JSON.stringify(customFieldList.value),
    };
    await api.put('/settings', payload);

    // Sync settingsStore immediately
    settingsStore.settings = { ...settingsStore.settings, ...systemForm.value };
    localStorage.setItem('app_settings', JSON.stringify(settingsStore.settings));
    document.title = systemForm.value.app_name || 'MEDIA ROJABIYYAH';

    alert('Pengaturan sistem dan branding berhasil disimpan!');
    await fetchSettings();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan pengaturan sistem.');
  } finally {
    savingSystem.value = false;
  }
};

// =========================================================================
// TAB 2: MASTER KATEGORI
// =========================================================================
const categoryList = ref([
  { id: 1, name: 'ALAT KOMUNIKASI & HT', type: 'INVENTORY', description: 'Handy Talky, Headset HT, Charger HT' },
  { id: 2, name: 'AUDIO & SOUND SYSTEM', type: 'INVENTORY', description: 'Mic Wireless, Mixer, Kabel XLR, Speaker' },
  { id: 3, name: 'INFAQ & SHODAQOH', type: 'TRANSACTION_INCOME', description: 'Pemasukan sukarela anggota & jamaah' },
  { id: 4, name: 'INFRASTRUKTUR & PERKABELAN', type: 'INVENTORY', description: 'Kabel Listrik, Rol, HDMI, Splitter, SDI' },
  { id: 5, name: 'KONSUMSI & LOGISTIK', type: 'TRANSACTION_EXPENSE', description: 'Biaya konsumsi panitia, snack, air mineral' },
  { id: 6, name: 'DOKUMENTASI & MULTIMEDIA', type: 'INVENTORY', description: 'Kamera, Lensa, Tripod, Drone, Lighting' },
]);
const savingCategory = ref(false);
const catForm = ref({
  name: '',
  type: 'INVENTORY',
  description: '',
});

const fetchCategories = async () => {
  try {
    const res = await api.get('/categories');
    if (Array.isArray(res.data) && res.data.length > 0) {
      categoryList.value = res.data;
    }
  } catch (err) {
    console.error('Gagal mengambil kategori:', err);
  }
};

const submitAddCategory = async () => {
  savingCategory.value = true;
  try {
    await api.post('/inventory-categories', catForm.value);
    alert('Kategori berhasil ditambahkan!');
    catForm.value = { name: '', type: 'INVENTORY', description: '' };
    await fetchCategories();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menambahkan kategori.');
  } finally {
    savingCategory.value = false;
  }
};

const deleteCategory = async (cat) => {
  if (!await window.$confirm(`Hapus kategori "${cat.name}"?`)) return;
  try {
    await api.delete(`/inventory-categories/${cat.id}`);
    categoryList.value = categoryList.value.filter((c) => c.id !== cat.id);
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menghapus kategori.');
  }
};

// =========================================================================
// TAB 3: WILAYAH OPERASIONAL
// =========================================================================
const regionList = ref([
  { id: 1, name: 'Fattah Hasyim Putri', code: 'FH PI', description: 'ada 2 proyektor' },
  { id: 2, name: 'Kantor BDM', code: 'KNTR BDM', description: '1 TV LCD' },
  { id: 3, name: 'KJA Center', code: 'KJA', description: '1 Proyektor Di dalam' },
  { id: 4, name: 'MA Fattah Hasyim', code: 'MA FH', description: 'Proyektor di LT 2 dan LT 3' },
  { id: 5, name: 'Masjid Bumi Damai Al-Muhibbin', code: 'MSJD BDM', description: '4 Proyektor' },
  { id: 6, name: 'MTS Fattah Hasyim', code: 'MTS FH', description: 'Ada 3 Proyektor dilokasi situ' },
]);
const savingRegion = ref(false);
const regionForm = ref({
  name: '',
  code: '',
  description: '',
});

const fetchRegions = async () => {
  try {
    const res = await api.get('/regions');
    if (Array.isArray(res.data) && res.data.length > 0) {
      regionList.value = res.data;
    }
  } catch (err) {
    console.error('Gagal mengambil wilayah:', err);
  }
};

const submitAddRegion = async () => {
  savingRegion.value = true;
  try {
    await api.post('/regions', regionForm.value);
    alert('Wilayah operasional berhasil ditambahkan!');
    regionForm.value = { name: '', code: '', description: '' };
    await fetchRegions();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menambahkan wilayah.');
  } finally {
    savingRegion.value = false;
  }
};

const deleteRegion = async (reg) => {
  if (!await window.$confirm(`Hapus wilayah "${reg.name}"?`)) return;
  try {
    await api.delete(`/regions/${reg.id}`);
    regionList.value = regionList.value.filter((r) => r.id !== reg.id);
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menghapus wilayah.');
  }
};

// =========================================================================
// TAB 4: LEMBAGA / MITRA LUAR
// =========================================================================
const partnerList = ref([
  { id: 1, name: 'SMK TI Annuriyyah', contact: 'Ustadz M. Syakir', phone: '081234567890', description: 'Peminjaman proyektor & mixer audio' },
  { id: 2, name: 'UNWAHA Jombang', contact: 'Bpk. Rahmat Fauzi', phone: '085678901234', description: 'Dukungan lab multimedia' },
  { id: 3, name: 'Ponpes Al-Muhibbin', contact: 'Gus Hilmy', phone: '082134567891', description: 'Fasilitas sound system lapangan' },
]);
const savingPartner = ref(false);
const partnerForm = ref({
  name: '',
  contact: '',
  phone: '',
  description: '',
});

const fetchPartners = async () => {
  try {
    const res = await api.get('/partners');
    if (Array.isArray(res.data) && res.data.length > 0) {
      partnerList.value = res.data;
    }
  } catch (err) {
    console.error('Gagal mengambil mitra:', err);
  }
};

const submitAddPartner = async () => {
  savingPartner.value = true;
  try {
    await api.post('/partners', partnerForm.value);
    alert('Lembaga mitra berhasil ditambahkan!');
    partnerForm.value = { name: '', contact: '', phone: '', description: '' };
    await fetchPartners();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menambahkan mitra.');
  } finally {
    savingPartner.value = false;
  }
};

const deletePartner = async (p) => {
  if (!await window.$confirm(`Hapus lembaga mitra "${p.name}"?`)) return;
  try {
    await api.delete(`/partners/${p.id}`);
    partnerList.value = partnerList.value.filter((item) => item.id !== p.id);
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menghapus mitra.');
  }
};

// =========================================================================
// TAB 5: KOLOM KUSTOM DINAMIS
// =========================================================================
const customFieldList = ref([
  { module: 'Inventaris', name: 'serial_number', label: 'Nomor Seri Pabrik (S/N)', type: 'Text' },
  { module: 'Inventaris', name: 'live_stream_url', label: 'Tautan Live Monitoring', type: 'URL' },
  { module: 'Anggota', name: 'rompi_size', label: 'Ukuran Rompi / Kaos', type: 'Text' },
]);
const fieldForm = ref({
  module: 'Inventaris',
  name: '',
  label: '',
  type: 'Text',
});

const submitAddCustomField = async () => {
  if (!fieldForm.value.name || !fieldForm.value.label) return;
  customFieldList.value.push({ ...fieldForm.value });
  fieldForm.value = { module: 'Inventaris', name: '', label: '', type: 'Text' };
  try {
    await api.put('/settings', { custom_fields: JSON.stringify(customFieldList.value) });
    alert('Kolom kustom dinamis berhasil ditambahkan!');
  } catch (err) {
    console.error(err);
  }
};

const deleteCustomField = async (index) => {
  customFieldList.value.splice(index, 1);
  try {
    await api.put('/settings', { custom_fields: JSON.stringify(customFieldList.value) });
  } catch (err) {
    console.error(err);
  }
};

// =========================================================================
// TAB 6: ADMIN USERS MANAGEMENT
// =========================================================================
const adminList = ref([]);
const adminCandidates = ref([]);
const selectedCandidateId = ref('');
const showAddAdminModal = ref(false);
const savingAdmin = ref(false);
const newAdminForm = ref({
  user_id: null,
  name: '',
  email: '',
  role: 'super_admin',
  phone_wa: '',
  password: '',
});

const fetchAdmins = async () => {
  try {
    const res = await api.get('/admins');
    adminList.value = res.data;
  } catch (err) {
    console.error('Gagal mengambil daftar admin:', err);
  }
};

const fetchAdminCandidates = async () => {
  try {
    const res = await api.get('/admins/candidates');
    adminCandidates.value = res.data || [];
  } catch (err) {
    console.error('Gagal mengambil daftar anggota kandidat admin:', err);
  }
};

const openAddAdminModal = async () => {
  selectedCandidateId.value = '';
  newAdminForm.value = {
    user_id: null,
    name: '',
    email: '',
    role: 'super_admin',
    phone_wa: '',
    password: '',
  };
  showAddAdminModal.value = true;
  await fetchAdminCandidates();
};

const onCandidateSelected = () => {
  if (!selectedCandidateId.value) {
    newAdminForm.value.user_id = null;
    newAdminForm.value.name = '';
    newAdminForm.value.email = '';
    newAdminForm.value.phone_wa = '';
    newAdminForm.value.role = 'super_admin';
    newAdminForm.value.password = '';
    return;
  }
  const candidate = adminCandidates.value.find((c) => c.id === Number(selectedCandidateId.value));
  if (candidate) {
    newAdminForm.value.user_id = candidate.id;
    newAdminForm.value.name = candidate.name;
    newAdminForm.value.email = candidate.email;
    newAdminForm.value.phone_wa = candidate.phone_wa || '';
    newAdminForm.value.role = 'super_admin';
    newAdminForm.value.password = '';
  }
};

const submitAddAdmin = async () => {
  if (!newAdminForm.value.user_id && !newAdminForm.value.password) {
    alert('Kata sandi awal minimal 6 karakter wajib diisi untuk pembuatan akun baru.');
    return;
  }
  savingAdmin.value = true;
  try {
    const res = await api.post('/admins', newAdminForm.value);
    alert(res.data?.message || 'Akun pengelola admin berhasil disimpan!');
    showAddAdminModal.value = false;
    newAdminForm.value = { user_id: null, name: '', email: '', role: 'super_admin', phone_wa: '', password: '' };
    selectedCandidateId.value = '';
    await fetchAdmins();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan pengelola admin.');
  } finally {
    savingAdmin.value = false;
  }
};

const deleteAdmin = async (adm) => {
  if (!await window.$confirm(`Hapus akun pengelola "${adm.name}"?`)) return;
  try {
    await api.delete(`/admins/${adm.id}`);
    adminList.value = adminList.value.filter((a) => a.id !== adm.id);
    alert('Pengelola admin berhasil dihapus!');
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menghapus admin.');
  }
};

// =========================================================================
// TAB 7: DANGER ZONE RESET
// =========================================================================
const ALL_DANGER_MODULES = ['members', 'inventory', 'partners', 'loans', 'reports', 'cash', 'assignments'];
const dangerPreviewCounts = ref({});
const dangerSelectedModules = ref(['loans', 'reports']);
const dangerConfirmationPhrase = ref('');
const dangerPassword = ref('');
const dangerExecuting = ref(false);
const dangerErrorMessage = ref('');
const dangerSuccessResult = ref(null);

const isDangerPhraseValid = computed(() => {
  const p = dangerConfirmationPhrase.value.trim().toUpperCase();
  return p === 'RESET OPERASIONAL' || p === 'RESET';
});

const selectAllDangerModules = () => {
  dangerSelectedModules.value = [...ALL_DANGER_MODULES];
};

const clearDangerModuleSelection = () => {
  dangerSelectedModules.value = [];
};

const fetchDangerPreview = async () => {
  try {
    const res = await api.post('/danger-zone/preview');
    dangerPreviewCounts.value = res.data;
  } catch (e) {
    console.error('Gagal mengambil pratinjau Danger Zone:', e);
  }
};

const executeDangerReset = async () => {
  if (!isDangerPhraseValid.value || !dangerPassword.value || dangerSelectedModules.value.length === 0) return;

  const confirmMsg = `PERINGATAN TINGKAT TINGGI:\n\nAnda akan menghapus data dari ${dangerSelectedModules.value.length} modul yang dipilih secara permanen.\nSistem akan membuat file backup snapshot sebelum pembersihan.\n\nLanjutkan eksekusi sekarang?`;
  if (!await window.$confirm(confirmMsg, 'Konfirmasi Pembersihan Data')) return;

  dangerExecuting.value = true;
  dangerErrorMessage.value = '';
  dangerSuccessResult.value = null;

  try {
    const res = await api.post('/danger-zone/execute', {
      modules: dangerSelectedModules.value,
      confirmation_phrase: dangerConfirmationPhrase.value.trim(),
      password: dangerPassword.value,
    });

    dangerSuccessResult.value = res.data;
    dangerConfirmationPhrase.value = '';
    dangerPassword.value = '';
    alert('Pembersihan data selesai! ' + res.data.message);
    await fetchDangerPreview();
  } catch (err) {
    dangerErrorMessage.value = err.response?.data?.message || 'Gagal mengeksekusi reset. Pastikan kata sandi Anda benar.';
  } finally {
    dangerExecuting.value = false;
  }
};

// =========================================================================
// MEMBER PROFILE & SHARED UTILS
// =========================================================================
const saving = ref(false);
const copiedLink = ref(false);
const profilePhotoRef = ref(null);
const divisions = ref([]);

const gdriveFolderUrl = computed(() => {
  return systemForm.value.gdrive_folder_url || settingsStore.settings?.gdrive_folder_url || 'https://drive.google.com/drive/folders/1l91WsFBV_sZ89DlvzvSXlgedX0pOwsfa?usp=sharing';
});

const driveRefreshKey = ref(0);
const refreshDrive = () => {
  driveRefreshKey.value++;
};

const gdriveEmbedUrl = computed(() => {
  const url = gdriveFolderUrl.value;
  if (!url) return '';
  const match = url.match(/folders\/([a-zA-Z0-9_-]+)/) || url.match(/id=([a-zA-Z0-9_-]+)/);
  if (match && match[1]) {
    return `https://drive.google.com/embeddedfolderview?id=${match[1]}#grid`;
  }
  return url;
});

const userInitial = computed(() => {
  const name = profileForm.value.name || authStore.user?.name;
  return name ? name.charAt(0).toUpperCase() : 'M';
});

const selectedDivisionName = computed(() => {
  if (profileForm.value.division_id) {
    const d = divisions.value.find((div) => String(div.id) === String(profileForm.value.division_id));
    if (d) return d.name;
  }
  return authStore.user?.member?.division?.name || (isAdminUser.value ? 'Super Admin' : 'Anggota Relawan');
});

const profileForm = ref({
  member_no: authStore.user?.member_no || 'RJB-001',
  name: authStore.user?.name || '',
  email: authStore.user?.email || '',
  phone_wa: authStore.user?.phone_wa || '',
  division_id: authStore.user?.member?.division_id || null,
  domicile: authStore.user?.member?.domicile || 'Pondok Pesantren Induk',
  education: authStore.user?.member?.education || 'UNWAHA',
  class: authStore.user?.member?.class || 'Semester 6',
  uniform_size: authStore.user?.member?.uniform_size || 'L',
  address: authStore.user?.member?.address || '',
  photo_path: authStore.user?.member?.photo_path || null,
});

const passForm = ref({
  current_password: '',
  password: '',
  password_confirmation: '',
});

// =========================================================================
// TAB DIVISI TUGAS KEPANITIAAN (Settings Sub-Tab)
// =========================================================================
const savingDivision = ref(false);
const editingDivisionId = ref(null);
const isEditingDivision = ref(false);
const divisionForm = ref({
  name: '',
  description: '',
});

const fetchDivisions = async () => {
  try {
    const res = await api.get('/divisions');
    divisions.value = res.data;
  } catch (err) {
    console.error('Gagal mengambil divisi:', err);
  }
};

const submitSaveDivision = async () => {
  if (!divisionForm.value.name.trim()) return;
  savingDivision.value = true;
  try {
    if (isEditingDivision.value) {
      await api.put(`/divisions/${editingDivisionId.value}`, divisionForm.value);
      alert('Divisi panitia berhasil diperbarui!');
    } else {
      await api.post('/divisions', divisionForm.value);
      alert('Divisi panitia baru berhasil ditambahkan!');
    }
    cancelEditDivision();
    await fetchDivisions();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan divisi.');
  } finally {
    savingDivision.value = false;
  }
};

const editDivision = (div) => {
  isEditingDivision.value = true;
  editingDivisionId.value = div.id;
  divisionForm.value = {
    name: div.name,
    description: div.description || '',
  };
};

const cancelEditDivision = () => {
  isEditingDivision.value = false;
  editingDivisionId.value = null;
  divisionForm.value = { name: '', description: '' };
};

const deleteDivision = async (div) => {
  const memberNote = div.members_count > 0
    ? `\n\nPerhatian: Ada ${div.members_count} anggota di divisi ini. Status penugasan anggota akan dikosongkan tanpa menghapus akun/biodata mereka.`
    : '';
  if (!confirm(`Hapus divisi "${div.name}"?${memberNote}`)) return;

  try {
    await api.delete(`/divisions/${div.id}`);
    alert(`Divisi "${div.name}" berhasil dihapus.`);
    if (editingDivisionId.value === div.id) {
      cancelEditDivision();
    }
    await fetchDivisions();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menghapus divisi.');
  }
};

const compressImage = (file, maxWidth = 800, quality = 0.82) => {
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

const handleProfilePhotoUpload = async (e) => {
  const file = e.target.files[0];
  if (!file) return;
  try {
    const compressed = await compressImage(file, 800, 0.82);
    profileForm.value.photo_path = compressed;
  } catch (err) {
    console.error('Gagal memproses foto:', err);
    const reader = new FileReader();
    reader.onload = (ev) => {
      profileForm.value.photo_path = ev.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const removeProfilePhoto = () => {
  profileForm.value.photo_path = null;
};

const loadUserProfile = async () => {
  if (!authStore.user?.id) return;
  try {
    const res = await api.get(`/members/${authStore.user.id}`);
    const u = res.data;
    if (u) {
      authStore.user = u;
      localStorage.setItem('user', JSON.stringify(u));
      profileForm.value.member_no = u.member_no || profileForm.value.member_no;
      profileForm.value.name = u.name || '';
      profileForm.value.email = u.email || '';
      profileForm.value.phone_wa = u.phone_wa || '';
      if (u.member) {
        profileForm.value.division_id = u.member.division_id ?? null;
        profileForm.value.domicile = u.member.domicile || '';
        profileForm.value.education = u.member.education || '';
        profileForm.value.class = u.member.class || '';
        profileForm.value.uniform_size = u.member.uniform_size || 'L';
        profileForm.value.address = u.member.address || '';
        profileForm.value.photo_path = u.member.photo_path || null;
      }
    }
  } catch (err) {
    console.error('Gagal memuat profil anggota:', err);
  }
};

const saveProfile = async () => {
  if (!authStore.user?.id) return;
  saving.value = true;
  try {
    const payload = { ...profileForm.value };
    if (!payload.email || payload.email.trim() === '') {
      payload.email = null;
    }
    const res = await api.put(`/members/${authStore.user.id}`, payload);
    if (res.data?.user) {
      authStore.user = res.data.user;
      localStorage.setItem('user', JSON.stringify(res.data.user));
    }
    alert('Profil dan foto anggota berhasil disimpan!');
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan profil.');
  } finally {
    saving.value = false;
  }
};

const changePassword = async () => {
  try {
    await api.post('/password/change', passForm.value);
    alert('Kata sandi berhasil diubah!');
    passForm.value = { current_password: '', password: '', password_confirmation: '' };
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal mengubah kata sandi.');
  }
};

const copyDriveLink = () => {
  navigator.clipboard.writeText(gdriveFolderUrl.value);
  copiedLink.value = true;
  setTimeout(() => (copiedLink.value = false), 2000);
};

onMounted(() => {
  if (route.query.tab) {
    if (route.query.tab === 'divisions') adminTab.value = 'divisions';
    else if (route.query.tab === 'admins') adminTab.value = 'admins';
    else if (['branding', 'categories', 'regions', 'partners', 'custom_fields', 'reset'].includes(route.query.tab)) {
      adminTab.value = route.query.tab;
    }
  }

  fetchDivisions();
  fetchSettings();
  loadUserProfile();
  if (isAdminUser.value) {
    fetchCategories();
    fetchRegions();
    fetchPartners();
    fetchAdmins();
    fetchDangerPreview();
  }
});
</script>
