<template>
  <DashboardLayout>
    <div class="space-y-6">
      <!-- Page Title & Actions -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <div class="flex items-center space-x-2.5">
            <div class="w-8 h-8 rounded-xl bg-teal-50 border border-teal-200 flex items-center justify-center text-teal-600">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </div>
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">Manajemen Data Anggota</h2>
          </div>
          <p class="text-xs text-slate-500 mt-1">Data keanggotaan, penugasan divisi panitia, pendidikan, domisili, seragam, dan penugasan anggota.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
          <!-- Ekspor Excel -->
          <button
            @click="exportExcel"
            :disabled="exporting"
            class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 border border-emerald-200 text-emerald-700 text-xs font-semibold shadow-sm transition disabled:opacity-50"
            :title="'Unduh berkas Excel untuk: ' + (activeTab === 'account' ? 'Khusus Akun Login' : activeTab === 'google_form' ? 'Khusus Google Formulir' : 'Semua Panitia')"
          >
            <span v-if="exporting" class="inline-block w-3.5 h-3.5 border-2 border-emerald-600 border-t-transparent rounded-full animate-spin"></span>
            <svg v-else class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            <span>{{ exporting ? 'Mengunduh...' : 'Ekspor Excel' }}</span>
          </button>

          <!-- Impor Excel -->
          <button
            v-if="['super_admin', 'admin'].includes(authStore.user?.role)"
            @click="showImportModal = true"
            class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 border border-indigo-200 text-indigo-700 text-xs font-semibold shadow-sm transition"
          >
            <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
            <span>Impor Spreadsheet</span>
          </button>

          <!-- Tambah Anggota -->
          <button
            v-if="['super_admin', 'admin'].includes(authStore.user?.role)"
            @click="openAddModal"
            class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white text-xs font-bold shadow-md shadow-emerald-500/20 transition active:scale-95"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>+ Tambah Anggota</span>
          </button>
        </div>
      </div>

      <!-- KONTROL & STATISTIK PEMISAHAN DATA (Modern Clean Glass Cards) -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
        <!-- Card 1: Semua Panitia -->
        <div
          @click="setTab('')"
          :class="[
            'p-4 rounded-3xl border transition-all cursor-pointer flex flex-col justify-between shadow-sm relative overflow-hidden group',
            activeTab === ''
              ? 'bg-rose-50/60 border-rose-300 ring-2 ring-rose-400/30'
              : 'bg-white border-slate-200 hover:border-slate-300 hover:-translate-y-0.5'
          ]"
        >
          <div class="flex items-center justify-between">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Total Seluruh Panitia</span>
            <div class="w-9 h-9 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-600 text-base shadow-xs">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="9" cy="7" r="4" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-2a4 4 0 014-4h4a4 4 0 014 4v2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3.13a4 4 0 010 7.75M21 21v-2a4 4 0 00-3-3.85"/></svg>
            </div>
          </div>
          <div class="my-2">
            <div class="text-2xl sm:text-3xl font-black text-slate-900 font-mono">{{ stats.total }}</div>
            <div class="text-[11px] text-slate-500 mt-0.5">Semua relawan &amp; panitia terdaftar</div>
          </div>
          <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-1">
            <div class="bg-gradient-to-r from-rose-500 to-pink-500 h-full rounded-full w-full"></div>
          </div>
        </div>

        <!-- Card 2: Akun Login Anggota -->
        <div
          @click="setTab('account')"
          :class="[
            'p-4 rounded-3xl border transition-all cursor-pointer flex flex-col justify-between shadow-sm relative overflow-hidden group',
            activeTab === 'account'
              ? 'bg-emerald-50/70 border-emerald-400 ring-2 ring-emerald-400/30'
              : 'bg-white border-slate-200 hover:border-slate-300 hover:-translate-y-0.5'
          ]"
        >
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-1.5">
              <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
              <span class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider">Akun Login Portal</span>
            </div>
            <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 text-base shadow-xs">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            </div>
          </div>
          <div class="my-2">
            <div class="text-2xl sm:text-3xl font-black text-emerald-700 font-mono">{{ stats.account }}</div>
            <div class="text-[11px] text-emerald-700/80 mt-0.5">
              {{ accountPercentage }}% panitia memiliki akses aplikasi
            </div>
          </div>
          <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-1">
            <div class="bg-gradient-to-r from-emerald-500 to-teal-400 h-full rounded-full transition-all duration-500" :style="{ width: accountPercentage + '%' }"></div>
          </div>
        </div>

        <!-- Card 3: Pendaftar Google Formulir -->
        <div
          @click="setTab('google_form')"
          :class="[
            'p-4 rounded-3xl border transition-all cursor-pointer flex flex-col justify-between shadow-sm relative overflow-hidden group',
            activeTab === 'google_form'
              ? 'bg-indigo-50/70 border-indigo-400 ring-2 ring-indigo-400/30'
              : 'bg-white border-slate-200 hover:border-slate-300 hover:-translate-y-0.5'
          ]"
        >
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-1.5">
              <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
              <span class="text-[10px] font-bold text-indigo-700 uppercase tracking-wider">Google Formulir</span>
            </div>
            <div class="w-9 h-9 rounded-xl bg-indigo-50 border border-indigo-200 flex items-center justify-center text-indigo-600 text-base shadow-xs">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            </div>
          </div>
          <div class="my-2">
            <div class="text-2xl sm:text-3xl font-black text-indigo-700 font-mono">{{ stats.google_form }}</div>
            <div class="text-[11px] text-indigo-700/80 mt-0.5">
              {{ googleFormPercentage }}% pendataan seragam (belum buat akun)
            </div>
          </div>
          <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden mt-1">
            <div class="bg-gradient-to-r from-indigo-500 to-cyan-400 h-full rounded-full transition-all duration-500" :style="{ width: googleFormPercentage + '%' }"></div>
          </div>
        </div>
      </div>

      <!-- Tab Switcher Navigation -->
      <div class="flex flex-wrap items-center justify-between gap-3 border-b border-slate-200 pb-3">
        <div class="flex items-center space-x-2">
          <button
            @click="setTab('')"
            :class="[
              'px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs',
              activeTab === '' ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-emerald-500/20' : 'bg-white border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-50'
            ]"
          >
            <span>Semua Data</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-slate-100 text-slate-700 font-mono">{{ stats.total }}</span>
          </button>

          <button
            @click="setTab('account')"
            :class="[
              'px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs',
              activeTab === 'account' ? 'bg-emerald-600 text-white shadow-emerald-500/20' : 'bg-white border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-50'
            ]"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            <span>Akun Login (Aplikasi)</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-emerald-100 text-emerald-800 font-mono">{{ stats.account }}</span>
          </button>

          <button
            @click="setTab('google_form')"
            :class="[
              'px-3.5 py-1.5 rounded-xl text-xs font-bold transition flex items-center space-x-2 shadow-xs',
              activeTab === 'google_form' ? 'bg-indigo-600 text-white shadow-indigo-500/20' : 'bg-white border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-50'
            ]"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
            <span>Google Formulir / Formulir Publik</span>
            <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-indigo-100 text-indigo-800 font-mono">{{ stats.google_form }}</span>
          </button>
        </div>

        <!-- Info banner based on active tab -->
        <div class="text-[11px] text-slate-500 flex items-center space-x-1.5">
          <span v-if="activeTab === 'account'" class="text-emerald-700 font-semibold">Menampilkan panitia yang memiliki akun &amp; password login portal</span>
          <span v-else-if="activeTab === 'google_form'" class="text-indigo-700 font-semibold">Menampilkan relawan dari Google Form (bisa dibuatkan akun dengan tombol 🔑)</span>
          <span v-else class="text-slate-500">Menampilkan seluruh data anggota &amp; panitia</span>
        </div>
      </div>

      <!-- Filters & Search Bar (Modern Clean Style) -->
      <div class="bg-white p-4 rounded-2xl border border-slate-200 flex flex-wrap gap-3 items-center shadow-sm">
        <div class="flex-1 min-w-[240px] relative">
          <span class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          </span>
          <input
            v-model="filters.search"
            @input="debounceSearch"
            type="text"
            placeholder="Cari nama, nomor anggota, divisi, domisili, no WA..."
            class="w-full pl-10 pr-8 py-2.5 text-xs bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
          />
          <button
            v-if="filters.search"
            @click="filters.search = ''; fetchMembers(1)"
            class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600 text-xs p-1"
            title="Hapus pencarian"
          >
            ✕
          </button>
        </div>

        <select
          v-model="filters.division_id"
          @change="fetchMembers(1)"
          class="px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
        >
          <option value="">Semua Divisi Panitia</option>
          <option v-for="div in divisions" :key="div.id" :value="div.id">{{ div.name }}</option>
        </select>

        <select
          v-model="filters.domicile"
          @change="fetchMembers(1)"
          class="px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
        >
          <option value="">Semua Domisili</option>
          <option v-for="dom in domicilesList" :key="dom" :value="dom">{{ dom }}</option>
        </select>

        <select
          v-model="filters.is_active"
          @change="fetchMembers(1)"
          class="px-3.5 py-2.5 text-xs bg-white border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
        >
          <option value="">Semua Status</option>
          <option value="1">Aktif</option>
          <option value="0">Nonaktif</option>
        </select>

        <button
          v-if="hasActiveFilters"
          @click="resetFilters"
          class="px-3.5 py-2.5 text-xs bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl transition border border-slate-200 flex items-center space-x-1.5"
          title="Reset semua filter"
        >
          <span>↺</span>
          <span>Reset</span>
        </button>
      </div>

      <!-- Members Table -->
      <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase font-bold text-[10px] tracking-wider">
              <tr>
                <th class="py-3.5 px-4 w-12 text-center">NO</th>
                <th class="py-3.5 px-4 min-w-[200px]">NAMA &amp; STATUS LOGIN</th>
                <th class="py-3.5 px-4">DEV (DIVISI)</th>
                <th class="py-3.5 px-4">PENDIDIKAN</th>
                <th class="py-3.5 px-4">KELAS</th>
                <th class="py-3.5 px-4">DOMISILI</th>
                <th class="py-3.5 px-4 text-center">SERAGAM</th>
                <th class="py-3.5 px-4">NO WA</th>
                <th v-if="['super_admin', 'admin'].includes(authStore.user?.role)" class="py-3.5 px-4 text-center min-w-[130px]">AKSI / KONTROL</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-if="loading" class="text-center">
                <td colspan="9" class="py-8 text-slate-400">
                  <div class="flex items-center justify-center space-x-2">
                    <span class="inline-block w-4 h-4 border-2 border-emerald-600 border-t-transparent rounded-full animate-spin"></span>
                    <span>Memuat data panitia...</span>
                  </div>
                </td>
              </tr>
              <tr v-else-if="members.length === 0" class="text-center">
                <td colspan="9" class="py-12">
                  <div class="flex flex-col items-center justify-center space-y-2">
                    <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center">
                      <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                    </div>
                    <div class="text-sm font-semibold text-slate-800">Tidak Ada Data Anggota</div>
                    <div class="text-xs text-slate-500">
                      {{ activeTab === 'account' ? 'Belum ada anggota dengan akun login pada filter ini.' : activeTab === 'google_form' ? 'Belum ada pendaftar Google Formulir pada filter ini.' : 'Coba ubah kata kunci filter atau tambahkan anggota baru.' }}
                    </div>
                  </div>
                </td>
              </tr>
              <tr v-for="(user, index) in members" :key="user.id" class="hover:bg-slate-50/80 transition">
                <td class="py-3.5 px-4 text-center text-slate-400 font-mono">
                  {{ (pagination.current_page - 1) * pagination.per_page + index + 1 }}
                </td>
                <td class="py-3.5 px-4">
                  <div class="flex items-start space-x-3">
                    <!-- Photo or Avatar Initial -->
                    <div class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 overflow-hidden flex items-center justify-center text-slate-700 font-bold flex-shrink-0 mt-0.5">
                      <img
                        v-if="user.member?.photo_path"
                        :src="user.member.photo_path"
                        :alt="user.name"
                        class="w-full h-full object-cover"
                      />
                      <span v-else>{{ user.name ? user.name.charAt(0).toUpperCase() : 'M' }}</span>
                    </div>
                    <div>
                      <div class="font-bold text-slate-800 flex items-center space-x-1.5 flex-wrap">
                        <span>{{ user.name }}</span>
                        <span v-if="!user.is_active" class="px-1.5 py-0.2 text-[9px] bg-rose-50 text-rose-600 border border-rose-200 rounded">Nonaktif</span>
                      </div>

                      <div class="flex items-center space-x-2 text-[11px] font-mono text-emerald-700 mt-0.5">
                        <span>{{ user.member_no || '-' }}</span>
                      </div>

                      <!-- LENCANA KONTROL SUMBER DATA (Akun Login vs Google Form) -->
                      <div class="mt-1 flex items-center space-x-1.5 flex-wrap">
                        <template v-if="hasLoginAccount(user)">
                          <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            <span>🔑</span>
                            <span>Akun Login ({{ user.role }})</span>
                          </span>
                          <span class="text-[10px] text-slate-500 font-mono">{{ user.email }}</span>
                        </template>
                        <template v-else>
                          <span class="inline-flex items-center space-x-1 px-2 py-0.5 rounded-full text-[9px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                            <span>📋</span>
                            <span>Google Formulir</span>
                          </span>
                          <span class="text-[10px] text-amber-600 italic font-sans">Tanpa Akun Login</span>
                        </template>
                      </div>
                    </div>
                  </div>
                </td>
                <td class="py-3.5 px-4 text-slate-700">
                  <span class="font-semibold">{{ user.member?.division?.name || '-' }}</span>
                </td>
                <td class="py-3.5 px-4 text-slate-600">
                  {{ user.member?.education || '-' }}
                </td>
                <td class="py-3.5 px-4 text-slate-600">
                  {{ user.member?.class || '-' }}
                </td>
                <td class="py-3.5 px-4 text-slate-600">
                  <div class="flex items-center space-x-1.5">
                    <svg class="w-3.5 h-3.5 text-slate-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                    <span>{{ user.member?.domicile || '-' }}</span>
                  </div>
                </td>
                <td class="py-3.5 px-4 text-center">
                  <span
                    v-if="user.member?.uniform_size"
                    class="px-2 py-0.5 rounded-md bg-sky-50 border border-sky-200 text-sky-700 text-[10px] font-bold font-mono"
                  >
                    {{ user.member.uniform_size }}
                  </span>
                  <span v-else class="text-slate-400">-</span>
                </td>
                <td class="py-3.5 px-4 font-mono text-slate-600">
                  <a
                    v-if="user.phone_wa"
                    :href="'https://wa.me/' + cleanPhone(user.phone_wa)"
                    target="_blank"
                    class="hover:text-emerald-600 hover:underline inline-flex items-center space-x-1 font-semibold"
                    title="Kirim pesan WhatsApp"
                  >
                    <span>{{ user.phone_wa }}</span>
                  </a>
                  <span v-else>-</span>
                </td>
                <td v-if="['super_admin', 'admin'].includes(authStore.user?.role)" class="py-3.5 px-4 text-center">
                  <div class="flex items-center justify-center space-x-1.5 flex-wrap">
                    <!-- Tombol Kontrol: Buatkan Akun Login jika dari Google Form -->
                    <button
                      v-if="!hasLoginAccount(user)"
                      @click="openCreateAccountModal(user)"
                      title="Aktifkan &amp; Buatkan Akun Login Portal"
                      class="px-2 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 border border-emerald-200 rounded-lg text-[10px] font-bold transition flex items-center space-x-1"
                    >
                      <span>🔑</span>
                      <span>Beri Akun</span>
                    </button>

                    <!-- Tombol Kontrol: Cabut Akun Login jika sudah ada akun -->
                    <button
                      v-else-if="user.role !== 'super_admin'"
                      @click="revokeAccount(user)"
                      title="Cabut Akses Login (Ubah jadi pendaftar Google Formulir saja)"
                      class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg border border-transparent hover:border-amber-200 transition"
                    >
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    </button>

                    <!-- Tombol Edit Data -->
                    <button
                      @click="openEditModal(user)"
                      title="Edit Data Panitia"
                      class="p-1.5 text-slate-500 hover:text-emerald-700 hover:bg-emerald-50 rounded-lg transition"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>

                    <!-- Tombol Hapus -->
                    <button
                      v-if="user.role !== 'super_admin'"
                      @click="deleteMember(user)"
                      title="Hapus Data"
                      class="p-1.5 text-slate-500 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-slate-200 flex items-center justify-between text-xs text-slate-500 bg-slate-50/50">
          <div>
            Menampilkan {{ (pagination.current_page - 1) * pagination.per_page + 1 }} -
            {{ Math.min(pagination.current_page * pagination.per_page, pagination.total) }} dari
            {{ pagination.total }} anggota
          </div>
          <div class="flex items-center space-x-2">
            <button
              :disabled="pagination.current_page <= 1"
              @click="changePage(pagination.current_page - 1)"
              class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 disabled:opacity-40 text-slate-700 hover:bg-slate-50 shadow-xs transition"
            >
              Sebelumnya
            </button>
            <button
              :disabled="pagination.current_page >= pagination.last_page"
              @click="changePage(pagination.current_page + 1)"
              class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 disabled:opacity-40 text-slate-700 hover:bg-slate-50 shadow-xs transition"
            >
              Selanjutnya
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- MODAL: Tambah / Edit Anggota Baru (Clean White Theme Match + Foto Upload) -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4 overflow-y-auto">
      <div class="relative w-full max-w-2xl bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-2xl text-slate-800 my-8 max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-6">
          <h3 class="text-base font-bold text-slate-800 tracking-wide">
            {{ isEditing ? 'Edit Data Anggota' : 'Tambah Anggota Baru' }}
          </h3>
          <button @click="showModal = false" class="text-slate-400 hover:text-slate-700 transition p-1 rounded-lg hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <!-- Form -->
        <form @submit.prevent="saveMember" class="space-y-4 text-xs">
          <!-- Row 1: ID Anggota & Nama Lengkap -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">
                ID Anggota <span class="text-slate-400 font-normal">(Opsional, otomatis jika kosong)</span>
              </label>
              <input
                v-model="form.member_no"
                type="text"
                placeholder="RJB-XXX"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 font-mono transition"
              />
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Nama Lengkap *</label>
              <input
                v-model="form.name"
                type="text"
                required
                placeholder="misal: M. Nasrul Wathon"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
              />
            </div>
          </div>

          <!-- Row 2: Divisi & Pendidikan -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <div class="flex items-center justify-between mb-1">
                <label class="block font-semibold text-slate-700">Divisi Tugas</label>
                <router-link
                  v-if="['super_admin', 'admin'].includes(authStore.user?.role)"
                  to="/settings?tab=divisions"
                  target="_blank"
                  class="text-[10px] text-emerald-600 hover:text-emerald-700 font-semibold flex items-center space-x-0.5"
                  title="Buka menu Pengaturan Sistem untuk menambah / mengedit daftar divisi"
                >
                  <span>Kelola Divisi</span>
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                </router-link>
              </div>
              <select
                v-model="form.division_id"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
              >
                <option :value="null">Pilih Divisi Tugas</option>
                <option v-for="d in divisions" :key="d.id" :value="d.id">{{ d.name }}</option>
              </select>
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Pendidikan</label>
              <input
                v-model="form.education"
                type="text"
                placeholder="misal: Abiturent, UNWAHA, IAIBAFA, MAN 3..."
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
              />
            </div>
          </div>

          <!-- Row 3: Kelas & Domisili -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Kelas / Tingkat</label>
              <input
                v-model="form.class"
                type="text"
                placeholder="misal: XII IPA 1, Semester 4"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
              />
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Domisili / Pondok</label>
              <input
                v-model="form.domicile"
                type="text"
                placeholder="misal: Al-Muhibbin, Al-Amanah, Gresik"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
              />
            </div>
          </div>

          <!-- Row 4: Ukuran Seragam & No WA -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Ukuran Seragam</label>
              <div class="grid grid-cols-6 gap-1.5">
                <button
                  v-for="size in uniformSizes"
                  :key="size"
                  type="button"
                  @click="form.uniform_size = size"
                  :class="[
                    'py-2 px-1 text-center font-bold rounded-xl border transition text-xs',
                    form.uniform_size === size
                      ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm'
                      : 'bg-slate-50 text-slate-700 border-slate-200 hover:border-slate-300'
                  ]"
                >
                  {{ size }}
                </button>
              </div>
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">No. WhatsApp</label>
              <input
                v-model="form.phone_wa"
                type="text"
                placeholder="0812xxxxxxxx"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
              />
            </div>
          </div>

          <!-- Row 5: Wilayah Penempatan & Status Keanggotaan -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Wilayah Penempatan</label>
              <select
                v-model="form.region_id"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
              >
                <option :value="null">Pilih Wilayah Operasional</option>
                <option v-for="r in regions" :key="r.id" :value="r.id">{{ r.name }}</option>
              </select>
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Status Keanggotaan</label>
              <select
                v-model="form.is_active"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
              >
                <option :value="true">Aktif</option>
                <option :value="false">Nonaktif</option>
              </select>
            </div>
          </div>

          <!-- Alamat Lengkap / Asal -->
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Alamat Lengkap / Asal</label>
            <textarea
              v-model="form.address"
              rows="2"
              placeholder="Desa, Kecamatan, Kabupaten asal"
              class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
            ></textarea>
          </div>

          <!-- INPUT FOTO ANGGOTA (Clean White Style) -->
          <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200">
            <label class="block font-semibold text-slate-800 mb-2">Foto Anggota (Input Foto Profil)</label>
            <div class="flex items-center space-x-4">
              <!-- Thumbnail Preview -->
              <div class="w-16 h-16 rounded-2xl bg-white border-2 border-dashed border-slate-300 overflow-hidden flex items-center justify-center text-slate-400 flex-shrink-0 shadow-xs">
                <img
                  v-if="form.photo_path"
                  :src="form.photo_path"
                  alt="Preview Foto"
                  class="w-full h-full object-cover"
                />
                <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              </div>

              <!-- Action buttons for Photo -->
              <div class="space-y-1.5 flex-1">
                <input
                  type="file"
                  ref="photoInputRef"
                  accept="image/*"
                  @change="handlePhotoUpload"
                  class="hidden"
                />
                <button
                  type="button"
                  @click="$refs.photoInputRef.click()"
                  class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-white hover:bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold shadow-xs transition"
                >
                  <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                  <span>{{ form.photo_path ? 'Ganti Foto' : 'Pilih Foto' }}</span>
                </button>
                <button
                  v-if="form.photo_path"
                  type="button"
                  @click="form.photo_path = null"
                  class="inline-flex items-center space-x-1 px-2.5 py-1.5 rounded-xl text-rose-600 hover:text-rose-700 text-xs font-semibold ml-2 transition"
                >
                  Hapus Foto
                </button>
                <p class="text-[10px] text-slate-500">Format JPG, PNG, atau WEBP (Maksimal 3 MB).</p>
              </div>
            </div>
          </div>

          <!-- Section: Buat Akun Login untuk Anggota Ini? -->
          <div class="p-3.5 rounded-2xl bg-emerald-50/50 border border-emerald-200">
            <label class="flex items-center justify-between cursor-pointer">
              <div class="flex items-center space-x-2.5">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <div>
                  <span class="font-bold text-slate-800 text-xs">Buat Akun Login untuk Anggota Ini?</span>
                  <p class="text-[10px] text-slate-500">Jika tidak dicentang, data anggota tetap tersimpan tanpa akses login ke sistem.</p>
                </div>
              </div>
              <input
                v-model="createAccount"
                type="checkbox"
                class="w-4 h-4 rounded text-emerald-600 focus:ring-emerald-500 bg-white border-slate-300"
              />
            </label>

            <!-- Expanded Inputs if Account is Created -->
            <div v-if="createAccount" class="mt-3 pt-3 border-t border-emerald-200 grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div>
                <label class="block font-semibold text-slate-700 mb-1">Email Login *</label>
                <input
                  v-model="form.email"
                  type="email"
                  :required="createAccount"
                  placeholder="anggota@domain.com"
                  class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                />
              </div>
              <div>
                <label class="block font-semibold text-slate-700 mb-1">Password Sementara</label>
                <input
                  v-model="form.password"
                  type="text"
                  placeholder="password123 (opsional)"
                  class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500"
                />
              </div>
            </div>
          </div>

          <!-- Modal Action Buttons -->
          <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
            <button
              type="button"
              @click="showModal = false"
              class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition text-xs"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="saving"
              class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold shadow-md shadow-emerald-500/20 transition disabled:opacity-50 text-xs"
            >
              {{ saving ? 'Menyimpan...' : 'Simpan Data' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- MODAL: Impor Spreadsheet -->
    <div v-if="showImportModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
      <div class="w-full max-w-lg bg-white border border-slate-200 rounded-3xl p-6 shadow-2xl text-slate-800">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
          <div class="flex items-center space-x-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <h3 class="text-base font-bold text-slate-800">Impor Spreadsheet Anggota</h3>
          </div>
          <button @click="showImportModal = false" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <div class="space-y-4 text-xs">
          <p class="text-slate-600">
            Unggah file spreadsheet Excel (<span class="text-slate-800 font-mono font-bold">.xlsx, .xls</span>) atau <span class="text-slate-800 font-mono font-bold">.csv</span> yang berisi data keanggotaan panitia, divisi tugas, seragam, dan kontak.
          </p>

          <!-- Template Download Card -->
          <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-between">
            <div>
              <p class="font-semibold text-slate-800">Belum punya format kolom?</p>
              <p class="text-[11px] text-slate-500">Unduh format template resmi agar data rapi dan terbaca sistem.</p>
            </div>
            <button
              type="button"
              @click="downloadTemplate"
              class="px-3 py-1.5 rounded-xl bg-white hover:bg-slate-50 text-emerald-700 font-semibold text-xs border border-emerald-300 shadow-xs transition flex items-center space-x-1"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
              <span>Unduh Template</span>
            </button>
          </div>

          <div>
            <label class="block font-semibold text-slate-700 mb-1.5">Pilih Berkas Spreadsheet</label>
            <input
              type="file"
              ref="importFileRef"
              accept=".xlsx,.xls,.csv,.txt"
              class="w-full text-xs text-slate-600 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 cursor-pointer bg-white border border-slate-300 rounded-xl p-1.5"
            />
          </div>
        </div>

        <div class="flex items-center justify-end space-x-3 mt-6 pt-4 border-t border-slate-100">
          <button
            @click="showImportModal = false"
            :disabled="importing"
            class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition"
          >
            Batal
          </button>
          <button
            @click="executeImport"
            :disabled="importing"
            class="px-5 py-2 bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold rounded-xl text-xs transition flex items-center space-x-1.5 shadow-md shadow-emerald-500/20 disabled:opacity-50"
          >
            <span v-if="importing" class="inline-block w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
            <span>{{ importing ? 'Memproses Impor...' : 'Mulai Impor Data' }}</span>
          </button>
        </div>
      </div>
    </div>

    <!-- MODAL KONTROL: Aktifkan / Buatkan Akun Login untuk Pendaftar Google Form -->
    <div v-if="showCreateAccountModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
      <div class="w-full max-w-md bg-white border border-slate-200 rounded-3xl p-6 shadow-2xl text-slate-800">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-4">
          <div class="flex items-center space-x-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <h3 class="text-base font-bold text-slate-800">Buatkan Akun Login Portal</h3>
          </div>
          <button @click="showCreateAccountModal = false" class="text-slate-400 hover:text-slate-600 text-lg">✕</button>
        </div>

        <p class="text-xs text-slate-600 mb-4 leading-relaxed">
          Aktifkan akun portal untuk <strong>{{ selectedMemberForAccount?.name }}</strong> agar dapat login, mengajukan peminjaman logistik, dan mengelola tugas kepanitiaan.
        </p>

        <form @submit.prevent="submitCreateAccount" class="space-y-4 text-xs">
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Nama Anggota</label>
            <input
              :value="selectedMemberForAccount?.name"
              disabled
              class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-slate-700 font-semibold"
            />
          </div>

          <div>
            <label class="block font-semibold text-slate-700 mb-1">No. WhatsApp</label>
            <input
              :value="selectedMemberForAccount?.phone_wa || '-'"
              disabled
              class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-slate-700 font-mono"
            />
          </div>

          <div>
            <label class="block font-semibold text-slate-700 mb-1">Email Login *</label>
            <input
              v-model="accountForm.email"
              type="email"
              required
              placeholder="nama@rojabiyyah.id"
              class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
            />
          </div>

          <div>
            <label class="block font-semibold text-slate-700 mb-1">Kata Sandi (Password) *</label>
            <div class="flex space-x-2">
              <input
                v-model="accountForm.password"
                type="text"
                required
                minlength="6"
                placeholder="Kata sandi akun"
                class="flex-1 px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 font-mono placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
              />
              <button
                type="button"
                @click="generateRandomPassword"
                class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-[11px] font-semibold rounded-xl border border-slate-200 transition"
                title="Acak Kata Sandi"
              >
                Acak
              </button>
            </div>
          </div>

          <div>
            <label class="block font-semibold text-slate-700 mb-1">Peran Akses (Role)</label>
            <select
              v-model="accountForm.role"
              class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
            >
              <option value="anggota">Anggota Relawan</option>
              <option value="ketua_divisi">Ketua Divisi</option>
              <option value="pic_posko">PIC Posko Wilayah</option>
              <option value="bendahara">Bendahara</option>
              <option value="admin">Admin Operasional</option>
            </select>
          </div>

          <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-100">
            <button
              type="button"
              @click="showCreateAccountModal = false"
              :disabled="creatingAccount"
              class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="creatingAccount"
              class="px-5 py-2 bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold rounded-xl text-xs transition flex items-center space-x-1.5 shadow-md shadow-emerald-500/20 disabled:opacity-50"
            >
              <span v-if="creatingAccount" class="inline-block w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
              <span>{{ creatingAccount ? 'Mengaktifkan...' : 'Aktifkan Akun Login' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- MODAL NOTIFIKASI: Kredensial Akun Berhasil Dibuat & Kirim WA -->
    <div v-if="showCredentialsModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4">
      <div class="w-full max-w-md bg-white border border-emerald-300 rounded-3xl p-6 shadow-2xl text-slate-800">
        <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center mx-auto mb-3 text-2xl">
          🎉
        </div>
        <h3 class="text-base font-bold text-slate-800 text-center">Akun Login Berhasil Diaktifkan!</h3>
        <p class="text-xs text-slate-600 text-center mt-1">
          Kini <strong>{{ createdCredentials.name }}</strong> dapat masuk ke Portal Sistem Rojabiyyah 2026.
        </p>

        <!-- Kredensial Box -->
        <div class="my-4 p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2 text-xs">
          <div class="flex justify-between items-center py-1 border-b border-slate-200">
            <span class="text-slate-500">Nomor Anggota:</span>
            <span class="font-mono text-emerald-700 font-bold">{{ createdCredentials.member_no }}</span>
          </div>
          <div class="flex justify-between items-center py-1 border-b border-slate-200">
            <span class="text-slate-500">Email Login:</span>
            <span class="font-mono text-slate-800 font-bold">{{ createdCredentials.email }}</span>
          </div>
          <div class="flex justify-between items-center py-1">
            <span class="text-slate-500">Kata Sandi:</span>
            <span class="font-mono text-emerald-700 font-bold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">{{ createdCredentials.password }}</span>
          </div>
        </div>

        <div class="space-y-2 pt-2">
          <!-- Button Kirim WhatsApp -->
          <button
            v-if="selectedMemberForAccount?.phone_wa"
            @click="shareCredentialsWa"
            class="w-full py-2.5 px-4 bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold text-xs rounded-xl shadow-md shadow-emerald-500/20 flex items-center justify-center space-x-2 transition"
          >
            <span>💬</span>
            <span>Kirim Akun ke WhatsApp Anggota</span>
          </button>

          <!-- Button Salin Teks -->
          <button
            @click="copyCredentialsText"
            class="w-full py-2 px-4 bg-slate-100 hover:bg-slate-200 border border-slate-200 text-slate-700 text-xs font-semibold rounded-xl flex items-center justify-center space-x-1.5 transition"
          >
            <span>📋</span>
            <span>{{ copiedCredentials ? 'Teks Berhasil Disalin!' : 'Salin Info Akun Login' }}</span>
          </button>

          <button
            @click="showCredentialsModal = false"
            class="w-full py-2 text-slate-400 hover:text-slate-600 text-xs font-medium transition"
          >
            Tutup Dialog
          </button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import DashboardLayout from '../../layouts/DashboardLayout.vue';
import { useAuthStore } from '../../stores/auth';
import api from '../../services/api';
import { downloadAuthenticatedFile } from '../../utils/fileDownloader';

const authStore = useAuthStore();
const members = ref([]);
const divisions = ref([]);
const regions = ref([]);
const loading = ref(false);
const saving = ref(false);
const importing = ref(false);
const exporting = ref(false);
const showModal = ref(false);
const showImportModal = ref(false);
const showCreateAccountModal = ref(false);
const showCredentialsModal = ref(false);
const creatingAccount = ref(false);
const copiedCredentials = ref(false);
const isEditing = ref(false);
const editingId = ref(null);
const createAccount = ref(false);
const copiedLink = ref(false);
const copiedAccountLink = ref(false);
const photoInputRef = ref(null);
const importFileRef = ref(null);

const activeTab = ref(''); // '' (semua), 'account', 'google_form'
const stats = ref({ total: 0, account: 0, google_form: 0 });

const accountPercentage = computed(() => {
  if (!stats.value.total) return 0;
  return Math.round((stats.value.account / stats.value.total) * 100);
});

const googleFormPercentage = computed(() => {
  if (!stats.value.total) return 0;
  return Math.round((stats.value.google_form / stats.value.total) * 100);
});

const selectedMemberForAccount = ref(null);
const createdCredentials = ref({ name: '', email: '', password: '', member_no: '' });
const accountForm = ref({
  email: '',
  password: '',
  role: 'anggota',
});

const uniformSizes = ['S', 'M', 'L', 'XL', 'XXL', 'XXXL'];
const domicilesList = ['Al-Muhibbin', 'Al-Amanah', 'Pondok Putra', 'Pondok Putri', 'Ndalem', 'Santri Kalong', 'Gresik', 'Surabaya', 'Jombang'];

const filters = ref({
  search: '',
  division_id: '',
  domicile: '',
  is_active: '',
  registration_source: '',
});

const hasActiveFilters = computed(() => {
  return Boolean(
    filters.value.search ||
    filters.value.division_id ||
    filters.value.domicile ||
    filters.value.is_active !== ''
  );
});

const resetFilters = () => {
  filters.value.search = '';
  filters.value.division_id = '';
  filters.value.domicile = '';
  filters.value.is_active = '';
  fetchMembers(1);
};

const pagination = ref({
  current_page: 1,
  last_page: 1,
  total: 0,
  per_page: 15,
});

const form = ref({
  member_no: '',
  name: '',
  division_id: null,
  education: '',
  class: '',
  domicile: '',
  uniform_size: 'L',
  address: '',
  phone_wa: '',
  region_id: null,
  is_active: true,
  photo_path: null,
  email: '',
  password: '',
});

let debounceTimer = null;
const debounceSearch = () => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    fetchMembers(1);
  }, 350);
};

const fetchMembers = async (page = 1) => {
  loading.value = true;
  try {
    const params = {
      page,
      search: filters.value.search || undefined,
      division_id: filters.value.division_id || undefined,
      domicile: filters.value.domicile || undefined,
      is_active: filters.value.is_active !== '' ? filters.value.is_active : undefined,
      registration_source: filters.value.registration_source || undefined,
    };
    const res = await api.get('/members', { params });
    members.value = res.data.data;
    if (res.data.stats) {
      stats.value = res.data.stats;
    }
    pagination.value = {
      current_page: res.data.current_page,
      last_page: res.data.last_page,
      total: res.data.total,
      per_page: res.data.per_page,
    };
  } catch (err) {
    console.error('Gagal mengambil data anggota:', err);
  } finally {
    loading.value = false;
  }
};

const fetchOptions = async () => {
  try {
    const [divRes, regRes] = await Promise.all([
      api.get('/divisions'),
      api.get('/regions'),
    ]);
    divisions.value = divRes.data;
    regions.value = regRes.data;
  } catch (err) {
    console.error('Gagal mengambil opsi:', err);
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

const handlePhotoUpload = async (e) => {
  const file = e.target.files[0];
  if (!file) return;
  try {
    const compressed = await compressImage(file, 800, 0.82);
    form.value.photo_path = compressed;
  } catch (err) {
    console.error('Gagal memproses foto:', err);
    const reader = new FileReader();
    reader.onload = (ev) => {
      form.value.photo_path = ev.target.result;
    };
    reader.readAsDataURL(file);
  }
};

const openAddModal = () => {
  isEditing.value = false;
  editingId.value = null;
  createAccount.value = false;
  form.value = {
    member_no: '',
    name: '',
    division_id: null,
    education: '',
    class: '',
    domicile: '',
    uniform_size: 'L',
    address: '',
    phone_wa: '',
    region_id: null,
    is_active: true,
    photo_path: null,
    email: '',
    password: '',
  };
  showModal.value = true;
};

const openEditModal = (user) => {
  isEditing.value = true;
  editingId.value = user.id;
  createAccount.value = Boolean(user.email);
  form.value = {
    member_no: user.member_no || '',
    name: user.name || '',
    division_id: user.member?.division_id || null,
    education: user.member?.education || '',
    class: user.member?.class || '',
    domicile: user.member?.domicile || '',
    uniform_size: user.member?.uniform_size || 'L',
    address: user.member?.address || '',
    phone_wa: user.phone_wa || '',
    region_id: user.assignments?.[0]?.region_id || null,
    is_active: Boolean(user.is_active),
    photo_path: user.member?.photo_path || null,
    email: user.email || '',
    password: '',
  };
  showModal.value = true;
};

const saveMember = async () => {
  saving.value = true;
  try {
    const payload = { ...form.value };
    if (!createAccount.value) {
      delete payload.email;
      delete payload.password;
    }
    if (isEditing.value) {
      await api.put(`/members/${editingId.value}`, payload);
      alert('Data anggota berhasil diperbarui!');
    } else {
      await api.post('/members', payload);
      alert('Anggota baru berhasil ditambahkan!');
    }
    showModal.value = false;
    fetchMembers(pagination.value.current_page);
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan data anggota.');
  } finally {
    saving.value = false;
  }
};

const deleteMember = async (user) => {
  if (await window.$confirm(`Yakin ingin menghapus data panitia "${user.name}"?`)) {
    try {
      await api.delete(`/members/${user.id}`);
      fetchMembers(pagination.value.current_page);
    } catch (err) {
      alert(err.response?.data?.message || 'Gagal menghapus anggota.');
    }
  }
};

const hasLoginAccount = (user) => {
  if (!user) return false;
  return Boolean(user.email && (user.registration_source === 'account' || user.role !== 'anggota'));
};

const cleanPhone = (phone) => {
  if (!phone) return '';
  let cleaned = String(phone).replace(/\D/g, '');
  if (cleaned.startsWith('0')) {
    cleaned = '62' + cleaned.slice(1);
  }
  return cleaned;
};

const setTab = (source) => {
  activeTab.value = source;
  filters.value.registration_source = source;
  fetchMembers(1);
};

const copyPublicFormLink = () => {
  const url = `${window.location.origin}/register-member`;
  navigator.clipboard.writeText(url);
  copiedLink.value = true;
  setTimeout(() => (copiedLink.value = false), 2500);
};

const copyAccountRegLink = () => {
  const url = `${window.location.origin}/register-account`;
  navigator.clipboard.writeText(url);
  copiedAccountLink.value = true;
  setTimeout(() => (copiedAccountLink.value = false), 2500);
};

const generateRandomPassword = () => {
  const chars = 'ABCDEFGHJKLMNPQRSTUVWXYZabcdefghijkmnpqrstuvwxyz23456789';
  let pwd = '';
  for (let i = 0; i < 8; i++) {
    pwd += chars.charAt(Math.floor(Math.random() * chars.length));
  }
  accountForm.value.password = pwd;
};

const openCreateAccountModal = (user) => {
  selectedMemberForAccount.value = user;
  const cleanName = (user.name || 'anggota')
    .toLowerCase()
    .trim()
    .replace(/[^a-z0-9]/g, '');

  const chars = 'abcdefghijkmnpqrstuvwxyz23456789';
  let randomPwd = '';
  for (let i = 0; i < 8; i++) {
    randomPwd += chars.charAt(Math.floor(Math.random() * chars.length));
  }

  accountForm.value = {
    email: `${cleanName}@rojabiyyah.id`,
    password: randomPwd,
    role: user.role && user.role !== 'anggota' ? user.role : 'anggota',
  };
  showCreateAccountModal.value = true;
};

const submitCreateAccount = async () => {
  if (!selectedMemberForAccount.value) return;
  creatingAccount.value = true;
  try {
    const res = await api.post(`/members/${selectedMemberForAccount.value.id}/create-account`, {
      email: accountForm.value.email,
      password: accountForm.value.password,
      role: accountForm.value.role,
    });

    createdCredentials.value = {
      name: selectedMemberForAccount.value.name,
      email: res.data.user?.email || accountForm.value.email,
      password: accountForm.value.password,
      member_no: res.data.user?.member_no || selectedMemberForAccount.value.member_no || '-',
    };

    showCreateAccountModal.value = false;
    showCredentialsModal.value = true;
    fetchMembers(pagination.value.current_page);
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal membuatkan akun login.');
  } finally {
    creatingAccount.value = false;
  }
};

const revokeAccount = async (user) => {
  if (await window.$confirm(`Yakin ingin mencabut akses login untuk "${user.name}"?\nData profil panitia & penugasan tetap aman, namun akses login portal akan dinonaktifkan.`)) {
    try {
      await api.post(`/members/${user.id}/revoke-account`);
      alert(`Akses login untuk "${user.name}" berhasil dicabut.`);
      fetchMembers(pagination.value.current_page);
    } catch (err) {
      alert(err.response?.data?.message || 'Gagal mencabut akun login.');
    }
  }
};

const copyCredentialsText = () => {
  const loginUrl = `${window.location.origin}/login`;
  const text = `Assalamu'alaikum Wr. Wb.
Halo ${createdCredentials.value.name},
Berikut adalah akun akses Portal Dokumentasi & Panitia Rojabiyyah 2026:

🔑 Email: ${createdCredentials.value.email}
🔒 Password: ${createdCredentials.value.password}
🏷️ ID Anggota: ${createdCredentials.value.member_no}
🌐 Link Login: ${loginUrl}

Simpan informasi ini dengan baik. Terima kasih atas khidmahnya!`;

  navigator.clipboard.writeText(text);
  copiedCredentials.value = true;
  setTimeout(() => (copiedCredentials.value = false), 2500);
};

const shareCredentialsWa = () => {
  const phone = cleanPhone(selectedMemberForAccount.value?.phone_wa);
  if (!phone) return alert('Nomor WhatsApp anggota tidak ditemukan.');

  const loginUrl = `${window.location.origin}/login`;
  const message = `Assalamu'alaikum Wr. Wb.
Halo *${createdCredentials.value.name}*,
Berikut adalah akun akses Portal Dokumentasi & Panitia Rojabiyyah 2026:

*Email Login:* ${createdCredentials.value.email}
*Password:* ${createdCredentials.value.password}
*ID Anggota:* ${createdCredentials.value.member_no}

*Link Login:*
${loginUrl}

Silakan masuk dan simpan informasi akun ini dengan aman. Terima kasih atas khidmahnya! 🙏`;

  const waUrl = `https://wa.me/${phone}?text=${encodeURIComponent(message)}`;
  window.open(waUrl, '_blank');
};

const exportExcel = async () => {
  if (exporting.value) return;
  exporting.value = true;
  try {
    const today = new Date().toISOString().slice(0, 10);
    let prefix = 'Data_Semua_Panitia';
    if (activeTab.value === 'account') {
      prefix = 'Data_Panitia_Akun_Login';
    } else if (activeTab.value === 'google_form') {
      prefix = 'Data_Panitia_Google_Form';
    }
    await downloadAuthenticatedFile(
      '/members/export',
      `${prefix}_Rojabiyyah_${today}.xlsx`,
      filters.value
    );
  } finally {
    exporting.value = false;
  }
};

const downloadTemplate = async () => {
  await downloadAuthenticatedFile(
    '/members/template',
    'Template_Impor_Anggota_Rojabiyyah.xlsx'
  );
};

const executeImport = async () => {
  const file = importFileRef.value?.files[0];
  if (!file) return alert('Silakan pilih berkas Excel (.xlsx) atau CSV terlebih dahulu.');

  importing.value = true;
  const formData = new FormData();
  formData.append('file', file);

  try {
    const res = await api.post('/members/import/preview', formData);
    const validCount = res.data.valid_count ?? res.data.count ?? 0;
    const invalidCount = res.data.invalid_count ?? 0;
    const validRows = res.data.valid_rows ?? res.data.preview ?? [];

    if (validCount === 0) {
      alert('Tidak ditemukan baris data yang valid. Pastikan kolom nama terisi dan format spreadsheet sesuai template.');
      return;
    }

    let msg = `Ditemukan ${validCount} baris data valid.`;
    if (invalidCount > 0) {
      msg += ` (${invalidCount} baris tidak lengkap/kosong akan dilewati).`;
    }
    msg += ` Lanjutkan proses impor data anggota?`;

    if (confirm(msg)) {
      const importRes = await api.post('/members/import', { rows: validRows });
      alert(importRes.data.message || `Berhasil mengimpor ${validCount} anggota.`);
      showImportModal.value = false;
      if (importFileRef.value) importFileRef.value.value = '';
      fetchMembers(1);
    }
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal memproses file impor. Periksa format berkas Anda.');
  } finally {
    importing.value = false;
  }
};

const changePage = (p) => {
  fetchMembers(p);
};

onMounted(() => {
  fetchMembers();
  fetchOptions();
});
</script>
