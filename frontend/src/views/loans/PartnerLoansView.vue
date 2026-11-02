<template>
  <DashboardLayout>
    <div class="space-y-6">
      <!-- Title & Actions (Dynamic: Admin vs Anggota) -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <div class="flex items-center space-x-2.5">
            <div class="w-9 h-9 rounded-xl bg-cyan-50 border border-cyan-200 flex items-center justify-center text-cyan-600 shadow-xs">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            </div>
            <div>
              <h2 class="text-xl font-black text-slate-900 tracking-tight">
                {{ isMemberOnly ? 'Katalog Pinjaman Mitra' : 'Pinjaman Lembaga Mitra' }}
              </h2>
            </div>
          </div>
          <p class="text-xs text-slate-500 mt-1">
            {{ isMemberOnly
              ? 'Daftar alat pinjaman dari mitra luar yang dapat diajukan untuk operasional.'
              : 'Kelola aset pinjaman mitra, narahubung PIC, batas kembali, dan stok.'
            }}
          </p>
        </div>

        <!-- Action Tools -->
        <div class="flex flex-wrap items-center gap-2">
          <!-- Buka Sirkulasi Peminjaman -->
          <router-link
            to="/loans"
            class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold transition shadow-xs"
          >
            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>{{ isMemberOnly ? 'Peminjaman' : 'Sirkulasi Pinjam →' }}</span>
          </router-link>

          <!-- Ekspor Excel (Admin Only) -->
          <template v-if="!isMemberOnly">
            <button
              @click="exportPartnerItems"
              :disabled="exporting"
              class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-semibold transition disabled:opacity-50 shadow-xs"
            >
              <span v-if="exporting" class="inline-block w-3.5 h-3.5 border-2 border-emerald-600 border-t-transparent rounded-full animate-spin"></span>
              <svg v-else class="w-3.5 h-3.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
              <span>{{ exporting ? 'Mengunduh...' : 'Ekspor Excel' }}</span>
            </button>

            <!-- Tambah Mitra Baru -->
            <button
              @click="openAddPartnerModal"
              class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-cyan-50 hover:bg-cyan-100 border border-cyan-300 text-cyan-800 text-xs font-bold transition shadow-xs"
            >
              <svg class="w-3.5 h-3.5 text-cyan-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
              <span>Mitra Baru</span>
            </button>

            <!-- Tambah Barang Pinjaman -->
            <button
              @click="openAddItemModal"
              class="inline-flex items-center space-x-1.5 px-3.5 py-1.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:brightness-105 text-white text-xs font-bold shadow-md shadow-emerald-600/20 transition active:scale-95"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
              <span>Tambah Barang</span>
            </button>
          </template>
        </div>
      </div>

      <!-- Quick Summary Stat Cards -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
          <div>
            <span class="text-[10px] sm:text-xs font-extrabold text-slate-500 uppercase tracking-wider block">Total Unit Pinjaman</span>
            <div class="text-xl sm:text-2xl font-black text-slate-900 mt-1 font-mono">
              {{ totalUnitsCount }}
            </div>
            <span class="text-[10px] text-cyan-700 font-semibold">{{ items.length }} Item Terdata</span>
          </div>
          <div class="w-10 h-10 rounded-xl bg-cyan-50 border border-cyan-200 text-cyan-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
          <div>
            <span class="text-[10px] sm:text-xs font-extrabold text-slate-500 uppercase tracking-wider block">Tersedia Siap Pakai</span>
            <div class="text-xl sm:text-2xl font-black text-emerald-600 mt-1 font-mono">
              {{ availableUnitsCount }}
            </div>
            <span class="text-[10px] text-emerald-700 font-semibold">Siap Dipinjam Anggota</span>
          </div>
          <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
          <div>
            <span class="text-[10px] sm:text-xs font-extrabold text-slate-500 uppercase tracking-wider block">Sedang Dipakai</span>
            <div class="text-xl sm:text-2xl font-black text-amber-600 mt-1 font-mono">
              {{ reservedUnitsCount }}
            </div>
            <span class="text-[10px] text-amber-700 font-semibold">Dipakai Panitia Lapangan</span>
          </div>
          <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
          <div>
            <span class="text-[10px] sm:text-xs font-extrabold text-slate-500 uppercase tracking-wider block">Lembaga Mitra</span>
            <div class="text-xl sm:text-2xl font-black text-cyan-700 mt-1 font-mono">
              {{ partners.length }}
            </div>
            <span class="text-[10px] text-cyan-700 font-semibold">Instansi / Pondok Bekerjasama</span>
          </div>
          <div class="w-10 h-10 rounded-xl bg-cyan-50 border border-cyan-200 text-cyan-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
          </div>
        </div>
      </div>

      <!-- Navigation Tabs: Barang Pinjaman vs Direktori Lembaga Mitra -->
      <div class="flex items-center justify-between border-b border-slate-200 pb-2 text-xs font-bold">
        <div class="flex items-center space-x-2">
          <button
            @click="activeTab = 'items'"
            :class="[
              'inline-flex items-center space-x-2 px-3.5 py-2 rounded-xl transition',
              activeTab === 'items'
                ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-xs'
                : 'bg-white hover:bg-slate-100 text-slate-600 border border-slate-200'
            ]"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            <span>Barang Mitra ({{ items.length }})</span>
          </button>

          <button
            v-if="!isMemberOnly"
            @click="activeTab = 'partners'"
            :class="[
              'inline-flex items-center space-x-2 px-3 py-1.5 rounded-xl transition',
              activeTab === 'partners'
                ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-xs'
                : 'bg-white hover:bg-slate-100 text-slate-600 border border-slate-200'
            ]"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
            <span>Data Mitra ({{ partners.length }})</span>
          </button>
        </div>

        <div class="hidden sm:flex items-center space-x-2 text-[11px] text-slate-500">
          <span>Khusus Logistik Luar / Mitra</span>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- TAB 1: DAFTAR BARANG PINJAMAN MITRA                                       -->
      <!-- ========================================================================= -->
      <div v-if="activeTab === 'items'" class="space-y-4">
        <!-- Filter Bar -->
        <div class="bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200 flex flex-wrap gap-2.5 items-center text-xs shadow-xs">
          <div class="flex-1 min-w-[200px]">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari nama barang, kode, atau lembaga mitra..."
              class="w-full px-3.5 py-2 rounded-xl bg-white border border-slate-300 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500"
            />
          </div>

          <select
            v-model="selectedPartnerId"
            class="px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
          >
            <option value="">Semua Lembaga Mitra</option>
            <option v-for="p in partners" :key="p.id" :value="p.id">{{ p.name }}</option>
          </select>

          <select
            v-model="selectedCategory"
            class="px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
          >
            <option value="">Semua Kategori</option>
            <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
          </select>

          <!-- View Mode Toggle -->
          <div class="flex items-center space-x-1 p-0.5 bg-slate-100 rounded-xl border border-slate-200">
            <button
              @click="viewMode = 'grid'"
              :class="viewMode === 'grid' ? 'bg-white text-emerald-700 shadow-xs' : 'text-slate-500'"
              class="p-1.5 rounded-lg transition"
              title="Tampilan Grid Kartu"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            </button>
            <button
              @click="viewMode = 'table'"
              :class="viewMode === 'table' ? 'bg-white text-emerald-700 shadow-xs' : 'text-slate-500'"
              class="p-1.5 rounded-lg transition"
              title="Tampilan Tabel"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
            </button>
          </div>
        </div>

        <!-- Empty State -->
        <div v-if="filteredItems.length === 0" class="bg-white rounded-2xl border border-slate-200 p-12 text-center text-slate-400">
          <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center mx-auto mb-3 text-cyan-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
          </div>
          <p class="text-sm font-bold text-slate-700">Belum Ada Barang Pinjaman Mitra</p>
          <p class="text-xs text-slate-500 mt-1">Data barang pinjaman dari lembaga mitra akan muncul di sini.</p>
          <button
            v-if="!isMemberOnly"
            @click="openAddItemModal"
            class="mt-4 px-4 py-2 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] text-white text-xs font-bold shadow-md shadow-emerald-600/20"
          >
            + Tambah Barang Pinjaman Sekarang
          </button>
        </div>

        <!-- VIEW MODE 1: GRID KARTU -->
        <div v-else-if="viewMode === 'grid'" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <div
            v-for="item in filteredItems"
            :key="item.id"
            class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs hover:border-cyan-400 transition flex flex-col justify-between group"
          >
            <div class="p-4 sm:p-5 space-y-3">
              <!-- Top: Category Badge & Partner Source Badge -->
              <div class="flex items-center justify-between gap-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider bg-slate-100 text-slate-700">
                  {{ item.category?.name || 'Logistik' }}
                </span>
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-cyan-50 border border-cyan-200 text-cyan-800 flex items-center space-x-1">
                  <span>🏢</span>
                  <span class="truncate max-w-[130px]">{{ item.partner?.name || 'Mitra Luar' }}</span>
                </span>
              </div>

              <!-- Item Image / Placeholder -->
              <div class="h-36 rounded-xl bg-slate-50 border border-slate-100 overflow-hidden flex items-center justify-center relative">
                <img
                  v-if="item.photo_path"
                  :src="item.photo_path"
                  :alt="item.name"
                  class="w-full h-full object-cover"
                />
                <div v-else class="text-slate-300 flex flex-col items-center">
                  <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                  <span class="text-[10px] text-slate-400 mt-1 font-mono font-semibold">{{ item.code }}</span>
                </div>

                <!-- Status Stock Pill -->
                <div class="absolute bottom-2 right-2">
                  <span
                    :class="[
                      'px-2 py-0.5 rounded-lg text-[10px] font-mono font-bold shadow-xs',
                      item.qty_available > 0 ? 'bg-emerald-600 text-white' : 'bg-rose-600 text-white'
                    ]"
                  >
                    {{ item.qty_available > 0 ? `${item.qty_available} Unit Tersedia` : 'Stok Habis' }}
                  </span>
                </div>
              </div>

              <!-- Item Details -->
              <div>
                <div class="text-[10px] font-mono font-bold text-slate-400 uppercase tracking-wider">{{ item.code }}</div>
                <h3 class="text-sm font-black text-slate-900 group-hover:text-cyan-700 transition line-clamp-1 mt-0.5">
                  {{ item.name }}
                </h3>
              </div>

              <!-- Information Grid -->
              <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5 text-xs text-slate-700">
                <div class="flex items-center justify-between">
                  <span class="text-slate-500 text-[11px]">Lembaga Asal:</span>
                  <span class="font-bold text-cyan-800 text-[11px] truncate max-w-[150px]">{{ item.partner?.name || '-' }}</span>
                </div>
                <div v-if="item.partner?.return_due_date" class="flex items-center justify-between">
                  <span class="text-slate-500 text-[11px]">Tenggat Kembali:</span>
                  <span class="font-mono font-bold text-amber-700 text-[11px]">
                    {{ formatDate(item.partner.return_due_date) }}
                  </span>
                </div>
                <div v-if="item.location" class="flex items-center justify-between">
                  <span class="text-slate-500 text-[11px]">Lokasi Simpan:</span>
                  <span class="font-semibold text-slate-800 text-[11px]">{{ item.location }}</span>
                </div>
                <div class="flex items-center justify-between pt-1 border-t border-slate-200">
                  <span class="text-slate-500 text-[11px]">Status Stok:</span>
                  <span class="text-[11px] font-mono">
                    <strong class="text-slate-900">{{ item.qty_total }}</strong> total •
                    <strong class="text-emerald-700">{{ item.qty_available }}</strong> siap •
                    <strong class="text-amber-700">{{ item.qty_reserved || 0 }}</strong> dipinjam
                  </span>
                </div>
              </div>
            </div>

            <!-- Card Bottom Actions -->
            <div class="p-3 bg-slate-50 border-t border-slate-200 flex items-center justify-between gap-2">
              <template v-if="isMemberOnly">
                <router-link
                  :to="`/loans?apply_item_id=${item.id}`"
                  class="w-full inline-flex items-center justify-center space-x-1.5 px-3 py-2 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] text-white text-xs font-bold shadow-xs hover:brightness-105 transition"
                >
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                  <span>Ajukan Pinjam Barang Ini</span>
                </router-link>
              </template>
              <template v-else>
                <div class="flex items-center space-x-1">
                  <button
                    @click="openEditItemModal(item)"
                    class="px-2.5 py-1.5 rounded-lg bg-white hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-semibold transition"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteItem(item.id)"
                    class="p-1.5 rounded-lg text-rose-500 hover:text-white hover:bg-rose-600 transition"
                    title="Hapus Barang"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  </button>
                </div>
                <router-link
                  :to="`/loans`"
                  class="text-[11px] font-bold text-cyan-700 hover:underline flex items-center space-x-1"
                >
                  <span>Lihat Sirkulasi</span>
                  <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </router-link>
              </template>
            </div>
          </div>
        </div>

        <!-- VIEW MODE 2: TABEL -->
        <div v-else class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-xs">
          <!-- Mobile Swipe Helper Indicator -->
          <div class="sm:hidden px-3.5 py-2 bg-cyan-50 border-b border-cyan-100 flex items-center justify-between text-[11px] text-cyan-900 font-semibold">
            <span class="flex items-center space-x-1.5">
              <svg class="w-3.5 h-3.5 text-cyan-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
              <span>Geser tabel ke samping untuk melihat detail mitra &amp; stok</span>
            </span>
            <span class="text-[10px] text-cyan-700 font-mono font-bold">{{ filteredItems.length }} data</span>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full text-left text-xs border-collapse">
              <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[10px] tracking-wider border-b border-slate-200 font-mono">
                <tr>
                  <th class="py-3 px-3 text-center w-12">No.</th>
                  <th class="py-3 px-3">Kode</th>
                  <th class="py-3 px-4 min-w-[200px]">Nama Barang Pinjaman</th>
                  <th class="py-3 px-3 min-w-[150px]">Lembaga Mitra Asal</th>
                  <th class="py-3 px-3 whitespace-nowrap">Tenggat Kembali</th>
                  <th class="py-3 px-2 text-center">Total</th>
                  <th class="py-3 px-2 text-center text-emerald-700">Tersedia</th>
                  <th class="py-3 px-2 text-center text-amber-700">Dipinjam</th>
                  <th class="py-3 px-3">Lokasi</th>
                  <th class="py-3 px-3 text-center w-28">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100 bg-white">
                <tr
                  v-for="(item, idx) in filteredItems"
                  :key="item.id"
                  class="hover:bg-cyan-50/30 transition"
                >
                  <td class="py-3 px-3 text-center font-bold text-slate-500 font-mono">{{ idx + 1 }}</td>
                  <td class="py-3 px-3 font-mono font-bold text-slate-700 whitespace-nowrap">{{ item.code }}</td>
                  <td class="py-3 px-4 font-bold text-slate-900">
                    <div>{{ item.name }}</div>
                    <span class="text-[10px] text-slate-500 font-normal">{{ item.category?.name || 'Logistik' }}</span>
                  </td>
                  <td class="py-3 px-3 font-semibold text-cyan-800 whitespace-nowrap">
                    🏢 {{ item.partner?.name || '-' }}
                  </td>
                  <td class="py-3 px-3 font-mono text-[11px] whitespace-nowrap">
                    {{ item.partner?.return_due_date ? formatDate(item.partner.return_due_date) : '-' }}
                  </td>
                  <td class="py-3 px-2 text-center font-mono font-bold text-slate-900">{{ item.qty_total }}</td>
                  <td class="py-3 px-2 text-center font-mono font-bold text-emerald-600 bg-emerald-50/40">{{ item.qty_available }}</td>
                  <td class="py-3 px-2 text-center font-mono font-bold text-amber-600">{{ item.qty_reserved || 0 }}</td>
                  <td class="py-3 px-3 text-slate-600 whitespace-nowrap">{{ item.location || '-' }}</td>
                  <td class="py-3 px-3 text-center whitespace-nowrap">
                    <template v-if="isMemberOnly">
                      <router-link
                        :to="`/loans?apply_item_id=${item.id}`"
                        class="px-2.5 py-1 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold"
                      >
                        Pinjam
                      </router-link>
                    </template>
                    <template v-else>
                      <button
                        @click="openEditItemModal(item)"
                        class="p-1 text-slate-600 hover:text-cyan-700 rounded transition mr-1"
                        title="Edit Barang"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                      </button>
                      <button
                        @click="deleteItem(item.id)"
                        class="p-1 text-rose-500 hover:text-rose-700 rounded transition"
                        title="Hapus Barang"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                      </button>
                    </template>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- TAB 2: DIREKTORI LEMBAGA MITRA (ADMIN ONLY)                               -->
      <!-- ========================================================================= -->
      <div v-else-if="activeTab === 'partners' && !isMemberOnly" class="space-y-4">
        <!-- Top Action & Info -->
        <div class="bg-cyan-50 border border-cyan-200 p-4 rounded-2xl flex flex-col sm:flex-row sm:items-center justify-between gap-3 text-xs text-cyan-900">
          <div class="flex items-center space-x-2">
            <span class="w-2.5 h-2.5 rounded-full bg-cyan-500 animate-pulse"></span>
            <span class="font-bold">Direktori Lembaga / Mitra Kerja Sama Logistik</span>
          </div>
          <button
            @click="openAddPartnerModal"
            class="px-3.5 py-1.5 rounded-xl bg-cyan-700 hover:bg-cyan-800 text-white font-bold text-xs shadow-xs transition"
          >
            + Tambah Lembaga Mitra Baru
          </button>
        </div>

        <!-- Partners Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
          <div
            v-for="partner in partners"
            :key="partner.id"
            class="bg-white rounded-2xl border border-slate-200 p-5 shadow-xs hover:border-cyan-400 transition flex flex-col justify-between space-y-4"
          >
            <div class="space-y-3">
              <div class="flex items-start justify-between gap-2">
                <div>
                  <h3 class="text-base font-black text-slate-900 leading-snug">
                    {{ partner.name }}
                  </h3>
                  <p class="text-xs text-slate-500 mt-0.5">{{ partner.description || 'Lembaga / Pondok Mitra' }}</p>
                </div>
                <span class="w-8 h-8 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center flex-shrink-0 font-bold text-xs">
                  🏢
                </span>
              </div>

              <div class="space-y-2 pt-2 border-t border-slate-100 text-xs">
                <div v-if="partner.contact" class="flex items-center space-x-2 text-slate-700">
                  <span class="text-slate-400">👤 PIC:</span>
                  <span class="font-bold">{{ partner.contact }}</span>
                </div>

                <div v-if="partner.phone" class="flex items-center justify-between">
                  <span class="text-slate-400">📞 No. HP / WA:</span>
                  <a
                    :href="formatWhatsAppUrl(partner.phone)"
                    target="_blank"
                    class="font-mono font-bold text-emerald-700 hover:underline flex items-center space-x-1"
                  >
                    <span>{{ partner.phone }}</span>
                    <span>↗</span>
                  </a>
                </div>

                <div v-if="partner.return_due_date" class="flex items-center justify-between">
                  <span class="text-slate-400">⏳ Batas Pengembalian:</span>
                  <span class="font-mono font-bold text-amber-700">{{ formatDate(partner.return_due_date) }}</span>
                </div>

                <div class="flex items-center justify-between pt-1">
                  <span class="text-slate-400">📦 Barang Dipinjam:</span>
                  <span class="px-2 py-0.5 rounded-full bg-slate-100 text-slate-800 font-mono font-bold text-[11px]">
                    {{ partner.items_count || 0 }} Jenis Barang
                  </span>
                </div>
              </div>
            </div>

            <!-- Card Actions -->
            <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-100">
              <button
                @click="openEditPartnerModal(partner)"
                class="px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-slate-100 text-slate-700 border border-slate-200 text-xs font-semibold transition"
              >
                Edit Mitra
              </button>
              <button
                @click="deletePartner(partner.id)"
                class="p-1.5 rounded-xl text-rose-500 hover:text-white hover:bg-rose-600 transition"
                title="Hapus Mitra"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- MODAL 1: FORM TAMBAH / EDIT BARANG PINJAMAN MITRA                         -->
      <!-- ========================================================================= -->
      <div
        v-if="showItemModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm animate-in fade-in"
        @click.self="showItemModal = false"
      >
        <div class="w-full max-w-lg bg-white border border-slate-200 rounded-3xl shadow-2xl overflow-hidden p-6 text-left relative max-h-[90vh] overflow-y-auto">
          <button
            type="button"
            @click="showItemModal = false"
            class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 p-1 rounded-lg hover:bg-slate-100 transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>

          <div class="flex items-center space-x-2 mb-1">
            <span class="w-2.5 h-2.5 rounded-full bg-cyan-500 animate-pulse"></span>
            <span class="text-xs font-bold text-cyan-700 uppercase tracking-wider">Logistik Mitra</span>
          </div>

          <h3 class="text-lg font-black text-slate-900 mb-4">
            {{ isEditingItem ? 'Edit Barang Pinjaman Mitra' : 'Tambah Barang Pinjaman dari Mitra' }}
          </h3>

          <form @submit.prevent="saveItem" class="space-y-4 text-xs">
            <div>
              <label class="block text-slate-700 font-bold mb-1">Nama Barang / Perangkat *</label>
              <input
                v-model="itemForm.name"
                type="text"
                required
                placeholder="misal: Handy Talky Motorola CP1660 / Proyektor Epson 5000 Lumens"
                class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 focus:outline-none focus:border-cyan-500"
              />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
              <div>
                <label class="block text-slate-700 font-bold mb-1">Lembaga Mitra Pemilik *</label>
                <select
                  v-model="itemForm.partner_id"
                  required
                  class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 focus:outline-none focus:border-cyan-500"
                >
                  <option value="" disabled>-- Pilih Lembaga Mitra --</option>
                  <option v-for="p in partners" :key="p.id" :value="p.id">{{ p.name }}</option>
                </select>
              </div>

              <div>
                <label class="block text-slate-700 font-bold mb-1">Kategori Perangkat</label>
                <select
                  v-model="itemForm.category_id"
                  class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 focus:outline-none focus:border-cyan-500"
                >
                  <option value="">-- Pilih Kategori --</option>
                  <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
                </select>
              </div>
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-slate-700 font-bold mb-1">Jumlah Unit Dipinjam *</label>
                <input
                  v-model.number="itemForm.qty_total"
                  type="number"
                  min="1"
                  required
                  class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 font-mono focus:outline-none focus:border-cyan-500"
                />
              </div>

              <div>
                <label class="block text-slate-700 font-bold mb-1">Kondisi Awal Barang</label>
                <select
                  v-model="itemForm.condition"
                  class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 focus:outline-none focus:border-cyan-500"
                >
                  <option value="baik">Kondisi Baik</option>
                  <option value="rusak_ringan">Rusak Ringan</option>
                  <option value="rusak_berat">Rusak Berat</option>
                </select>
              </div>
            </div>

            <div>
              <label class="block text-slate-700 font-bold mb-1">Lokasi Penyimpanan / Posko</label>
              <input
                v-model="itemForm.location"
                type="text"
                placeholder="misal: Gudang Utama / Posko Multicam"
                class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 focus:outline-none focus:border-cyan-500"
              />
            </div>

            <div class="pt-3 border-t border-slate-200 flex items-center justify-end space-x-2">
              <button
                type="button"
                @click="showItemModal = false"
                class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition"
              >
                Batal
              </button>
              <button
                type="submit"
                :disabled="saving"
                class="px-5 py-2 rounded-xl bg-gradient-to-r from-cyan-600 to-teal-600 hover:brightness-105 text-white font-bold shadow-md shadow-cyan-600/20 transition disabled:opacity-50"
              >
                {{ saving ? 'Menyimpan...' : (isEditingItem ? 'Simpan Perubahan' : 'Tambahkan Barang') }}
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- MODAL 2: FORM TAMBAH / EDIT LEMBAGA MITRA                                 -->
      <!-- ========================================================================= -->
      <div
        v-if="showPartnerModal"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm animate-in fade-in"
        @click.self="showPartnerModal = false"
      >
        <div class="w-full max-w-md bg-white border border-slate-200 rounded-3xl shadow-2xl overflow-hidden p-6 text-left relative">
          <button
            type="button"
            @click="showPartnerModal = false"
            class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 p-1 rounded-lg hover:bg-slate-100 transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>

          <h3 class="text-base font-black text-slate-900 mb-4">
            {{ isEditingPartner ? 'Edit Lembaga / Mitra' : 'Tambah Lembaga Mitra Baru' }}
          </h3>

          <form @submit.prevent="savePartner" class="space-y-4 text-xs">
            <div>
              <label class="block text-slate-700 font-bold mb-1">Nama Lembaga / Instansi / Pondok *</label>
              <input
                v-model="partnerForm.name"
                type="text"
                required
                placeholder="misal: Ponpes Bahrul Ulum / Multimedia Induk"
                class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 focus:outline-none focus:border-cyan-500"
              />
            </div>

            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-slate-700 font-bold mb-1">Narahubung (PIC)</label>
                <input
                  v-model="partnerForm.contact"
                  type="text"
                  placeholder="Ust. Ahmad"
                  class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 focus:outline-none focus:border-cyan-500"
                />
              </div>

              <div>
                <label class="block text-slate-700 font-bold mb-1">No. WhatsApp / HP</label>
                <input
                  v-model="partnerForm.phone"
                  type="text"
                  placeholder="081234567890"
                  class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 focus:outline-none focus:border-cyan-500"
                />
              </div>
            </div>

            <div>
              <label class="block text-slate-700 font-bold mb-1">Batas Waktu Pengembalian (Due Date)</label>
              <input
                v-model="partnerForm.return_due_date"
                type="date"
                class="w-full px-3.5 py-2.5 rounded-xl bg-white border border-slate-300 text-slate-900 focus:outline-none focus:border-cyan-500"
              />
            </div>

            <div>
              <label class="block text-slate-700 font-bold mb-1">Catatan / Alamat Mitra</label>
              <textarea
                v-model="partnerForm.description"
                rows="2"
                placeholder="Perjanjian peminjaman atau kontak tambahan..."
                class="w-full px-3.5 py-2 rounded-xl bg-white border border-slate-300 text-slate-900 focus:outline-none focus:border-cyan-500"
              ></textarea>
            </div>

            <div class="pt-3 border-t border-slate-200 flex items-center justify-end space-x-2">
              <button
                type="button"
                @click="showPartnerModal = false"
                class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition"
              >
                Batal
              </button>
              <button
                type="submit"
                :disabled="savingPartner"
                class="px-5 py-2 rounded-xl bg-cyan-700 hover:bg-cyan-800 text-white font-bold shadow-md shadow-cyan-700/20 transition disabled:opacity-50"
              >
                {{ savingPartner ? 'Menyimpan...' : (isEditingPartner ? 'Simpan' : 'Tambahkan Mitra') }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import DashboardLayout from '../../layouts/DashboardLayout.vue';
import api from '../../services/api';
import { useAuthStore } from '../../stores/auth';

const authStore = useAuthStore();
const isMemberOnly = computed(() => authStore.user?.role === 'anggota');

const activeTab = ref('items');
const viewMode = ref('grid');
const loading = ref(false);
const exporting = ref(false);
const saving = ref(false);
const savingPartner = ref(false);

const items = ref([]);
const partners = ref([]);
const categories = ref([]);

// Filters
const searchQuery = ref('');
const selectedPartnerId = ref('');
const selectedCategory = ref('');

// Item Form
const showItemModal = ref(false);
const isEditingItem = ref(false);
const itemForm = ref({
  id: null,
  name: '',
  source: 'mitra',
  partner_id: '',
  category_id: '',
  qty_total: 1,
  condition: 'baik',
  location: '',
  photo_path: '',
});

// Partner Form
const showPartnerModal = ref(false);
const isEditingPartner = ref(false);
const partnerForm = ref({
  id: null,
  name: '',
  contact: '',
  phone: '',
  description: '',
  return_due_date: '',
});

// Computed Metrics
const totalUnitsCount = computed(() => {
  return items.value.reduce((acc, it) => acc + (parseInt(it.qty_total, 10) || 0), 0);
});

const availableUnitsCount = computed(() => {
  return items.value.reduce((acc, it) => acc + (parseInt(it.qty_available, 10) || 0), 0);
});

const reservedUnitsCount = computed(() => {
  return items.value.reduce((acc, it) => acc + (parseInt(it.qty_reserved, 10) || 0), 0);
});

// Filtered Items
const filteredItems = computed(() => {
  let list = items.value || [];

  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    list = list.filter((it) => {
      const nameMatch = (it.name || '').toLowerCase().includes(q);
      const codeMatch = (it.code || '').toLowerCase().includes(q);
      const partnerMatch = (it.partner?.name || '').toLowerCase().includes(q);
      const locationMatch = (it.location || '').toLowerCase().includes(q);
      return nameMatch || codeMatch || partnerMatch || locationMatch;
    });
  }

  if (selectedPartnerId.value) {
    list = list.filter((it) => String(it.partner_id) === String(selectedPartnerId.value));
  }

  if (selectedCategory.value) {
    list = list.filter((it) => String(it.category_id) === String(selectedCategory.value));
  }

  return list;
});

const fetchData = async () => {
  loading.value = true;
  try {
    const [resItems, resPartners, resCats] = await Promise.all([
      api.get('/items', { params: { source: 'mitra', per_page: 500 } }),
      api.get('/partners'),
      api.get('/inventory-categories'),
    ]);

    items.value = resItems.data?.data || resItems.data || [];
    partners.value = resPartners.data || [];
    categories.value = resCats.data || [];
  } catch (err) {
    console.error('Gagal mengambil data logistik mitra:', err);
  } finally {
    loading.value = false;
  }
};

// Item Actions
const openAddItemModal = () => {
  isEditingItem.value = false;
  itemForm.value = {
    id: null,
    name: '',
    source: 'mitra',
    partner_id: partners.value[0]?.id || '',
    category_id: '',
    qty_total: 1,
    condition: 'baik',
    location: '',
    photo_path: '',
  };
  showItemModal.value = true;
};

const openEditItemModal = (item) => {
  isEditingItem.value = true;
  itemForm.value = {
    id: item.id,
    name: item.name,
    source: 'mitra',
    partner_id: item.partner_id || '',
    category_id: item.category_id || '',
    qty_total: item.qty_total || 1,
    condition: item.condition || 'baik',
    location: item.location || '',
    photo_path: item.photo_path || '',
  };
  showItemModal.value = true;
};

const saveItem = async () => {
  saving.value = true;
  try {
    const payload = {
      ...itemForm.value,
      source: 'mitra',
    };

    if (isEditingItem.value && itemForm.value.id) {
      await api.put(`/items/${itemForm.value.id}`, payload);
      alert('Data barang pinjaman mitra berhasil diperbarui.');
    } else {
      await api.post('/items', payload);
      alert('Barang pinjaman mitra berhasil ditambahkan.');
    }

    showItemModal.value = false;
    await fetchData();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan data barang.');
  } finally {
    saving.value = false;
  }
};

const deleteItem = async (id) => {
  if (confirm('Yakin ingin menghapus barang pinjaman mitra ini?')) {
    try {
      await api.delete(`/items/${id}`);
      await fetchData();
    } catch (err) {
      alert(err.response?.data?.message || 'Gagal menghapus barang.');
    }
  }
};

// Partner Actions
const openAddPartnerModal = () => {
  isEditingPartner.value = false;
  partnerForm.value = {
    id: null,
    name: '',
    contact: '',
    phone: '',
    description: '',
    return_due_date: '',
  };
  showPartnerModal.value = true;
};

const openEditPartnerModal = (partner) => {
  isEditingPartner.value = true;
  partnerForm.value = {
    id: partner.id,
    name: partner.name,
    contact: partner.contact || '',
    phone: partner.phone || '',
    description: partner.description || '',
    return_due_date: partner.return_due_date ? partner.return_due_date.substring(0, 10) : '',
  };
  showPartnerModal.value = true;
};

const savePartner = async () => {
  savingPartner.value = true;
  try {
    if (isEditingPartner.value && partnerForm.value.id) {
      await api.put(`/partners/${partnerForm.value.id}`, partnerForm.value);
      alert('Data mitra berhasil diperbarui.');
    } else {
      await api.post('/partners', partnerForm.value);
      alert('Lembaga mitra baru berhasil ditambahkan.');
    }

    showPartnerModal.value = false;
    await fetchData();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan lembaga mitra.');
  } finally {
    savingPartner.value = false;
  }
};

const deletePartner = async (id) => {
  if (confirm('Yakin ingin menghapus lembaga mitra ini?')) {
    try {
      await api.delete(`/partners/${id}`);
      await fetchData();
    } catch (err) {
      alert(err.response?.data?.message || 'Gagal menghapus lembaga mitra.');
    }
  }
};

const exportPartnerItems = async () => {
  exporting.value = true;
  try {
    const res = await api.get('/items/export', {
      params: { source: 'mitra' },
      responseType: 'blob',
    });
    const url = window.URL.createObjectURL(new Blob([res.data]));
    const link = document.createElement('a');
    link.href = url;
    link.setAttribute('download', `Logistik_Pinjaman_Mitra_${new Date().toISOString().slice(0, 10)}.xlsx`);
    document.body.appendChild(link);
    link.click();
    link.remove();
  } catch (err) {
    alert('Gagal mengekspor data pinjaman mitra.');
  } finally {
    exporting.value = false;
  }
};

const formatDate = (dateStr) => {
  if (!dateStr) return '-';
  const d = new Date(dateStr);
  if (isNaN(d.getTime())) return dateStr;
  return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
};

const formatWhatsAppUrl = (phone) => {
  if (!phone) return '#';
  let clean = phone.replace(/[^0-9]/g, '');
  if (clean.startsWith('0')) clean = '62' + clean.slice(1);
  return `https://wa.me/${clean}`;
};

onMounted(() => {
  fetchData();
});
</script>
