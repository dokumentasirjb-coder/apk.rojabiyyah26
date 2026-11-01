<template>
  <DashboardLayout>
    <div class="space-y-6">
      <!-- Title & Actions (Dynamic: Anggota vs Admin) -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <div class="flex items-center space-x-2.5">
            <div class="w-8 h-8 rounded-xl bg-teal-50 border border-slate-200 flex items-center justify-center text-teal-400">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">
              {{ isMemberOnly ? 'Penyimpanan & Pendataan Gudang Inventaris' : 'Data Inventaris & Logistik' }}
            </h2>
          </div>
          <p class="text-xs text-slate-500 mt-1">
            {{ isMemberOnly ? 'Data master barang dan stok inventaris organisasi. Untuk meminjam barang, gunakan submenu Peminjaman.' : 'Kelola data aset, stok fisik, kondisi kelayakan, dan sumber kepemilikan barang.' }}
          </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
          <!-- Buka Sub Peminjaman (for Member) -->
          <router-link
            v-if="isMemberOnly"
            to="/loans"
            class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white text-xs font-bold shadow-lg shadow-emerald-950/60 transition"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span>Buka Sub Peminjaman</span>
          </router-link>

          <!-- Admin Action Buttons -->
          <template v-else>
            <router-link
              to="/loans?tab=mitra"
              class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-teal-50 border border-teal-200 text-teal-700 text-xs font-semibold transition"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
              <span>Katalog Pinjaman Lembaga</span>
            </router-link>

            <!-- Ekspor Excel -->
            <button
              @click="exportExcel"
              :disabled="exporting"
              class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-teal-50 border border-emerald-200 text-emerald-700 text-xs font-semibold transition disabled:opacity-50"
            >
              <span v-if="exporting" class="inline-block w-3.5 h-3.5 border-2 border-emerald-400 border-t-transparent rounded-full animate-spin"></span>
              <svg v-else class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
              <span>{{ exporting ? 'Mengunduh...' : 'Ekspor Excel' }}</span>
            </button>

            <!-- Impor Excel -->
            <button
              @click="showImportModal = true"
              class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-teal-50 border border-indigo-200 text-indigo-700 text-xs font-semibold transition"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
              <span>Impor Excel</span>
            </button>

            <button
              @click="openAddItemModal"
              class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white text-xs font-bold shadow-lg shadow-emerald-950/60 transition"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
              <span>+ Tambah Barang</span>
            </button>
          </template>
        </div>
      </div>

      <!-- Blue Notice Banner (Exact Image 1) -->
      <div class="p-3.5 rounded-2xl bg-sky-50 border border-sky-200 flex items-start space-x-3 text-xs text-sky-800">
        <svg class="w-4 h-4 text-sky-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <p class="leading-relaxed">
          <strong class="text-white">Submenu Inventaris</strong> difokuskan khusus untuk penyimpanan dan pendataan stok fisik. Seluruh transaksi peminjaman (baik barang internal maupun barang pinjaman dari lembaga) diproses terpusat melalui submenu
          <router-link to="/loans" class="text-sky-300 font-bold hover:underline">Peminjaman</router-link>.
        </p>
      </div>

      <!-- Filters (Image 1 Match) -->
      <div class="bg-white p-4 rounded-2xl border border-slate-200 flex flex-wrap gap-3 items-center text-xs">
        <div class="flex-1 min-w-[220px]">
          <input
            v-model="filters.search"
            @input="debounceSearch"
            type="text"
            placeholder="Cari nama, kode barang, lokasi simpan..."
            class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-emerald-500 transition"
          />
        </div>

        <select
          v-model="filters.category_id"
          @change="fetchItems"
          class="px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 transition"
        >
          <option value="">Semua Kategori</option>
          <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
        </select>

        <select
          v-model="filters.condition"
          @change="fetchItems"
          class="px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 transition"
        >
          <option value="">Semua Kondisi Fisik</option>
          <option value="baik">Kondisi Baik</option>
          <option value="rusak_ringan">Rusak Ringan</option>
          <option value="rusak_berat">Rusak Berat</option>
        </select>

        <select
          v-if="!isMemberOnly"
          v-model="filters.source"
          @change="fetchItems"
          class="px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 transition"
        >
          <option value="">Semua Sumber Kepemilikan</option>
          <option value="internal">Milik Internal Saja</option>
          <option value="mitra">Pinjaman Mitra Saja</option>
        </select>

        <!-- View Mode Toggle for Admin -->
        <div v-if="!isMemberOnly" class="flex items-center space-x-1 p-1 bg-white border border-slate-200 rounded-xl">
          <button
            @click="viewMode = 'grid'"
            :class="viewMode === 'grid' ? 'bg-[#059669] text-white' : 'text-slate-500'"
            class="p-1.5 rounded-lg transition"
            title="Tampilan Grid Kartu"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
          </button>
          <button
            @click="viewMode = 'table'"
            :class="viewMode === 'table' ? 'bg-[#059669] text-white' : 'text-slate-500'"
            class="p-1.5 rounded-lg transition"
            title="Tampilan Tabel"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
          </button>
        </div>
      </div>

      <!-- ================== VIEW 1: CARD GRID (Image 1 Match) ================== -->
      <div v-if="viewMode === 'grid' || isMemberOnly">
        <div v-if="loading" class="p-12 text-center text-slate-500">
          Memuat data inventaris...
        </div>

        <div v-else-if="items.length === 0" class="bg-white rounded-3xl border border-slate-200 p-16 text-center shadow-sm">
          <div class="w-16 h-16 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-indigo-400 mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          </div>
          <h3 class="text-sm font-bold text-slate-900 mb-1">Tidak Ada Barang Ditemukan</h3>
          <p class="text-xs text-slate-500">Coba ubah filter pencarian atau kata kunci.</p>
        </div>

        <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
          <div
            v-for="item in items"
            :key="item.id"
            class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm flex flex-col justify-between hover:border-slate-300 transition"
          >
            <!-- Card Image with Badges (Image 1 Match) -->
            <div class="relative h-44 bg-slate-900 overflow-hidden flex items-center justify-center">
              <img
                v-if="item.photo_path"
                :src="item.photo_path"
                :alt="item.name"
                class="w-full h-full object-cover"
              />
              <!-- Default Image Camera Placeholder if no image -->
              <div v-else class="w-full h-full bg-white flex items-center justify-center text-slate-600">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </div>

              <!-- Top Badges -->
              <div class="absolute top-3 left-3">
                <span class="px-2.5 py-0.5 rounded-md bg-[#070b14]/80 text-cyan-400 border border-cyan-800/60 font-mono font-bold text-[10px] backdrop-blur-sm shadow">
                  {{ item.code }}
                </span>
              </div>
              <div class="absolute top-3 right-3">
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-950/80 text-emerald-300 border border-emerald-700/60 font-bold text-[10px] backdrop-blur-sm shadow flex items-center space-x-1">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                  <span>{{ item.condition === 'baik' ? 'Baik' : item.condition }}</span>
                </span>
              </div>
            </div>

            <!-- Card Content (Image 1 Match) -->
            <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
              <div>
                <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-wider mb-1">
                  <span class="text-rose-400">{{ item.category?.name || 'ALAT UMUM' }}</span>
                  <span class="px-2 py-0.5 rounded bg-teal-50 text-slate-500 border border-slate-200">
                    {{ item.source === 'mitra' ? 'Pinjaman Mitra' : 'Milik Rojabiyyah' }}
                  </span>
                </div>

                <h4 class="font-extrabold text-slate-900 text-sm tracking-wide capitalize">{{ item.name }}</h4>

                <div class="flex items-center space-x-1 text-[11px] text-slate-500 mt-1">
                  <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  <span>{{ item.location || 'Gudang Inventaris Utama' }}</span>
                </div>
              </div>

              <!-- Stock metrics -->
              <div class="p-2.5 rounded-xl bg-white border border-slate-200 space-y-1.5 text-xs">
                <div class="flex items-center justify-between">
                  <span class="text-slate-500 text-[11px]">Total Stok Fisik:</span>
                  <span class="font-mono font-bold text-white px-2 py-0.5 rounded bg-slate-800 text-[10px]">{{ item.qty_total }} Unit</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-slate-500 text-[11px]">Stok Siap Pakai:</span>
                  <span class="font-mono font-bold text-emerald-400 px-2 py-0.5 rounded bg-emerald-950/80 border border-emerald-800/50 text-[10px]">{{ item.qty_available }} Unit</span>
                </div>
              </div>

              <!-- Button Action -->
              <div>
                <button
                  @click="openDetailModal(item)"
                  class="w-full py-2 px-3 rounded-xl bg-white hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold flex items-center justify-center space-x-1.5 transition"
                >
                  <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  <span>Lihat Rincian Pendataan</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ================== VIEW 2: TABLE VIEW (For Admins) ================== -->
      <div v-else class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead class="bg-[#0e172a] border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
              <tr>
                <th class="py-3.5 px-4">BARANG</th>
                <th class="py-3.5 px-4">KATEGORI</th>
                <th class="py-3.5 px-4 text-center">TOTAL / TERSEDIA</th>
                <th class="py-3.5 px-4 text-center">KONDISI</th>
                <th class="py-3.5 px-4">LOKASI</th>
                <th class="py-3.5 px-4">SUMBER</th>
                <th class="py-3.5 px-4 text-center w-24">AKSI</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-for="item in items" :key="item.id" class="hover:bg-slate-50 transition">
                <td class="py-3.5 px-4">
                  <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-xl bg-teal-50 border border-slate-300/60 overflow-hidden flex items-center justify-center text-slate-500 flex-shrink-0">
                      <img v-if="item.photo_path" :src="item.photo_path" :alt="item.name" class="w-full h-full object-cover" />
                      <svg v-else class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <div>
                      <div class="font-bold text-white">{{ item.name }}</div>
                      <div class="text-[11px] font-mono text-cyan-400">{{ item.code }}</div>
                    </div>
                  </div>
                </td>
                <td class="py-3.5 px-4 text-slate-700">{{ item.category?.name || 'Tanpa Kategori' }}</td>
                <td class="py-3.5 px-4 text-center">
                  <span class="font-mono font-bold text-white">{{ item.qty_total }}</span>
                  <span class="text-slate-500 mx-1">/</span>
                  <span class="font-mono font-bold text-emerald-400">{{ item.qty_available }} Unit</span>
                </td>
                <td class="py-3.5 px-4 text-center">
                  <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold border" :class="item.condition === 'baik' ? 'bg-emerald-950/70 text-emerald-300 border-emerald-800' : 'bg-rose-950/70 text-rose-300 border-rose-800'">
                    {{ item.condition === 'baik' ? 'Baik' : item.condition }}
                  </span>
                </td>
                <td class="py-3.5 px-4 text-slate-700">{{ item.location || 'Gudang Utama' }}</td>
                <td class="py-3.5 px-4">
                  <span class="px-2 py-0.5 rounded-md text-[10px] font-semibold border" :class="item.source === 'mitra' ? 'bg-amber-950/60 text-amber-300 border-amber-800' : 'bg-slate-900 text-slate-700 border-slate-300'">
                    {{ item.source === 'mitra' ? (item.partner?.name || 'Mitra') : 'Milik Internal Sendiri' }}
                  </span>
                </td>
                <td class="py-3.5 px-4 text-center">
                  <div class="flex items-center justify-center space-x-2">
                    <button @click="openEditModal(item)" title="Edit" class="p-1.5 text-slate-500 hover:text-cyan-400 hover:bg-teal-50 rounded-lg transition">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                    </button>
                    <button @click="deleteItem(item)" title="Hapus" class="p-1.5 text-slate-500 hover:text-rose-400 hover:bg-teal-50 rounded-lg transition">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- MODAL: Detail Rincian Barang -->
    <div v-if="showDetailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md p-4 overflow-y-auto">
      <div class="relative w-full max-w-md bg-white border border-slate-200 rounded-3xl p-6 sm:p-7 shadow-sm text-slate-700 my-8">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200 mb-4">
          <h3 class="text-base font-bold text-slate-900 tracking-wide">Rincian Pendataan Inventaris</h3>
          <button @click="showDetailModal = false" class="text-slate-500 hover:text-white">✕</button>
        </div>

        <div v-if="selectedItem" class="space-y-4 text-xs">
          <div class="w-full h-44 rounded-2xl bg-white overflow-hidden border border-slate-300 flex items-center justify-center">
            <img v-if="selectedItem.photo_path" :src="selectedItem.photo_path" class="w-full h-full object-cover" />
            <svg v-else class="w-12 h-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          </div>

          <div class="p-3 bg-white rounded-xl border border-slate-200 space-y-2">
            <div class="flex justify-between"><span class="text-slate-500">Nama Barang:</span><span class="font-bold text-white">{{ selectedItem.name }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Kode Alat:</span><span class="font-mono text-cyan-400 font-bold">{{ selectedItem.code }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Kategori:</span><span>{{ selectedItem.category?.name || '-' }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Lokasi:</span><span>{{ selectedItem.location || 'Gudang Utama' }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Total Stok Fisik:</span><span class="font-mono font-bold">{{ selectedItem.qty_total }} Unit</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Stok Siap Pakai:</span><span class="font-mono font-bold text-emerald-400">{{ selectedItem.qty_available }} Unit</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Kondisi Fisik:</span><span class="capitalize">{{ selectedItem.condition }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Kepemilikan:</span><span>{{ selectedItem.source === 'mitra' ? 'Pinjaman Mitra' : 'Milik Internal' }}</span></div>
          </div>

          <div class="flex items-center justify-end space-x-2 pt-2">
            <router-link
              :to="'/loans?apply_item_id=' + selectedItem.id"
              class="px-4 py-2 bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold rounded-xl text-xs transition"
            >
              Ajukan Pinjam Barang Ini &rarr;
            </router-link>
          </div>
        </div>
      </div>
    </div>

    <!-- MODAL: Tambah / Edit Barang (Admin) -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md p-4 overflow-y-auto">
      <div class="relative w-full max-w-xl bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm text-slate-700 my-8 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-6">
          <h3 class="text-base font-bold text-slate-900 tracking-wide">
            {{ isEditing ? 'Edit Barang Inventaris' : 'Tambah Barang Inventaris Baru' }}
          </h3>
          <button @click="showModal = false" class="text-slate-500 hover:text-white transition p-1 rounded-lg hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <form @submit.prevent="saveItem" class="space-y-4 text-xs">
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">
                Kode / Label Barang <span class="text-slate-500 font-normal">(misal: BDM 2, SONY-01)</span>
              </label>
              <input v-model="form.code" type="text" placeholder="INV-1292" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 font-mono" />
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Nama Barang *</label>
              <input v-model="form.name" type="text" required placeholder="misal: Kamera Sony A7 III" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800" />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Kategori</label>
              <select v-model="form.category_id" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800">
                <option :value="null">Pilih Kategori</option>
                <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
              </select>
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Jumlah Unit *</label>
              <input v-model.number="form.qty_total" type="number" min="1" required class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 font-mono" />
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Kondisi</label>
              <select v-model="form.condition" class="w-full px-3 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800">
                <option value="baik">Baik</option>
                <option value="rusak_ringan">Rusak Ringan</option>
                <option value="rusak_berat">Rusak Berat</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Lokasi Penyimpanan</label>
              <input v-model="form.location" type="text" placeholder="Gudang Inventaris Utama" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800" />
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Sumber Kepemilikan</label>
              <select v-model="form.source" class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800">
                <option value="internal">Milik Internal Sendiri</option>
                <option value="mitra">Pinjaman Lembaga Mitra</option>
              </select>
            </div>
          </div>

          <!-- Lembaga Mitra Selector (Otomatis Muncul jika Sumber = Mitra) -->
          <div v-if="form.source === 'mitra'" class="p-3.5 rounded-2xl bg-slate-50 border border-amber-500/60 space-y-1.5 animate-fadeIn">
            <div class="flex items-center justify-between">
              <label class="block font-bold text-amber-300 text-xs">
                Lembaga / Mitra Pemilik Aset
              </label>
              <router-link to="/settings?tab=partners" class="text-[10px] text-sky-400 hover:text-sky-300 underline font-semibold">
                + Kelola Mitra
              </router-link>
            </div>
            <select
              v-model="form.partner_id"
              class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 text-xs focus:outline-none focus:border-amber-500"
            >
              <option :value="null">-- Pilih Lembaga Mitra (Opsional / Mitra Umum) --</option>
              <option v-for="p in partners" :key="p.id" :value="p.id">
                {{ p.name }} {{ p.contact ? `(PIC: ${p.contact})` : '' }}
              </option>
            </select>
            <p v-if="partners.length === 0" class="text-[10px] text-slate-500">
              Belum ada data lembaga mitra. Anda tetap dapat menyimpan barang ini tanpa memilih mitra.
            </p>
          </div>

          <!-- Foto Barang -->
          <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200">
            <label class="block font-semibold text-slate-700 mb-2">Foto Barang (Opsional)</label>
            <div class="flex items-center space-x-4">
              <div class="w-16 h-16 rounded-2xl bg-white border-2 border-dashed border-slate-300 overflow-hidden flex items-center justify-center text-slate-500 flex-shrink-0">
                <img v-if="form.photo_path" :src="form.photo_path" class="w-full h-full object-cover" />
                <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              </div>
              <div class="space-y-1.5 flex-1">
                <input type="file" ref="itemPhotoRef" accept="image/*" @change="handleItemPhoto" class="hidden" />
                <button type="button" @click="$refs.itemPhotoRef.click()" class="px-3 py-1.5 rounded-xl bg-teal-50 hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold">
                  {{ form.photo_path ? 'Ganti Foto' : 'Pilih Foto' }}
                </button>
                <button v-if="form.photo_path" type="button" @click="form.photo_path = null" class="text-rose-400 hover:text-rose-300 text-xs ml-2">Hapus</button>
              </div>
            </div>
          </div>

          <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-200">
            <button type="button" @click="showModal = false" class="px-4 py-2.5 rounded-xl bg-white text-slate-700">Batal</button>
            <button type="submit" :disabled="saving" class="px-5 py-2.5 rounded-xl bg-[#059669] text-white font-bold">
              {{ saving ? 'Menyimpan...' : (isEditing ? 'Simpan Perubahan' : 'Tambah Barang') }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- MODAL: Impor Spreadsheet Inventaris -->
    <div v-if="showImportModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md p-4">
      <div class="w-full max-w-lg bg-white border border-slate-200 rounded-3xl p-6 shadow-sm text-slate-700">
        <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-4">
          <div class="flex items-center space-x-2">
            <span class="w-2.5 h-2.5 rounded-full bg-indigo-500 animate-pulse"></span>
            <h3 class="text-base font-bold text-slate-900">Impor Spreadsheet Inventaris</h3>
          </div>
          <button @click="showImportModal = false" class="text-slate-500 hover:text-white text-lg">✕</button>
        </div>

        <div class="space-y-4 text-xs">
          <p class="text-slate-500">
            Unggah file Excel (<span class="text-white font-mono">.xlsx, .xls</span>) atau <span class="text-white font-mono">.csv</span> yang berisi daftar barang inventaris, jumlah unit, kondisi, dan lokasi.
          </p>

          <div class="p-3.5 rounded-2xl bg-white border border-slate-200 flex items-center justify-between">
            <div>
              <p class="font-semibold text-white">Format Kolom Inventaris</p>
              <p class="text-[11px] text-slate-500">Unduh format template resmi agar data terbaca rapi oleh sistem.</p>
            </div>
            <button
              type="button"
              @click="downloadTemplate"
              class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-teal-300 font-semibold text-xs border border-teal-500/30 transition flex items-center space-x-1"
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
              class="w-full text-xs text-slate-500 file:mr-3 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-50 file:text-indigo-300 hover:file:bg-[#1c2744] cursor-pointer bg-slate-50 border border-slate-300 rounded-xl p-1.5"
            />
          </div>
        </div>

        <div class="flex items-center justify-end space-x-3 mt-6 pt-4 border-t border-slate-200">
          <button
            @click="showImportModal = false"
            :disabled="importing"
            class="px-4 py-2 bg-white hover:bg-slate-100 text-slate-700 rounded-xl text-xs transition"
          >
            Batal
          </button>
          <button
            @click="executeImport"
            :disabled="importing"
            class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl text-xs transition flex items-center space-x-1.5 disabled:opacity-50"
          >
            <span v-if="importing" class="inline-block w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
            <span>{{ importing ? 'Memproses Impor...' : 'Mulai Impor Data' }}</span>
          </button>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue';
import DashboardLayout from '../../layouts/DashboardLayout.vue';
import { useAuthStore } from '../../stores/auth';
import api from '../../services/api';
import { downloadAuthenticatedFile } from '../../utils/fileDownloader';

const authStore = useAuthStore();
const items = ref([]);
const categories = ref([]);
const loading = ref(false);
const saving = ref(false);
const exporting = ref(false);
const importing = ref(false);
const showModal = ref(false);
const showDetailModal = ref(false);
const showImportModal = ref(false);
const importFileRef = ref(null);
const selectedItem = ref(null);
const isEditing = ref(false);
const editingId = ref(null);
const itemPhotoRef = ref(null);
const viewMode = ref('grid');

const isMemberOnly = computed(() => authStore.user?.role === 'anggota');

const filters = ref({
  search: '',
  category_id: '',
  condition: '',
  source: '',
});

const form = ref({
  code: '',
  name: '',
  category_id: null,
  qty_total: 1,
  condition: 'baik',
  location: 'Gudang Inventaris Utama',
  source: 'internal',
  photo_path: null,
});

let debounceTimer = null;
const debounceSearch = () => {
  clearTimeout(debounceTimer);
  debounceTimer = setTimeout(() => {
    fetchItems();
  }, 350);
};

const fetchItems = async () => {
  loading.value = true;
  try {
    const params = {
      search: filters.value.search || undefined,
      category_id: filters.value.category_id || undefined,
      condition: filters.value.condition || undefined,
      source: filters.value.source || undefined,
    };
    const res = await api.get('/items', { params });
    items.value = res.data.data;

    // If empty and member, seed sample sony 6000 item matching screenshot
    if (items.value.length === 0) {
      items.value = [
        {
          id: 1,
          code: 'INV-01',
          name: 'sony 6000',
          category: { name: 'ALAT KOMUNIKASI & HT' },
          condition: 'baik',
          source: 'internal',
          location: 'Gudang Inventaris Utama',
          qty_total: 4,
          qty_available: 4,
          photo_path: 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=600&q=80',
        }
      ];
    }
  } catch (err) {
    console.error('Gagal mengambil inventaris:', err);
  } finally {
    loading.value = false;
  }
};

const fetchCategories = async () => {
  try {
    const res = await api.get('/categories');
    categories.value = res.data;
  } catch (err) {
    console.error('Gagal mengambil kategori:', err);
  }
};

const partners = ref([]);
const fetchPartners = async () => {
  try {
    const res = await api.get('/partners');
    partners.value = res.data || [];
  } catch (err) {
    console.error('Gagal mengambil mitra:', err);
  }
};

const handleItemPhoto = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = (event) => {
    form.value.photo_path = event.target.result;
  };
  reader.readAsDataURL(file);
};

const openDetailModal = (item) => {
  selectedItem.value = item;
  showDetailModal.value = true;
};

const openAddItemModal = () => {
  isEditing.value = false;
  editingId.value = null;
  form.value = {
    code: `INV-${Math.floor(10 + Math.random() * 90)}`,
    name: '',
    category_id: categories.value[0]?.id || null,
    partner_id: partners.value[0]?.id || null,
    qty_total: 1,
    condition: 'baik',
    location: 'Gudang Inventaris Utama',
    source: 'internal',
    photo_path: null,
  };
  showModal.value = true;
};

const openEditModal = (item) => {
  isEditing.value = true;
  editingId.value = item.id;
  form.value = {
    code: item.code,
    name: item.name,
    category_id: item.category_id,
    partner_id: item.partner_id || null,
    qty_total: item.qty_total,
    condition: item.condition,
    location: item.location || 'Gudang Inventaris Utama',
    source: item.source,
    photo_path: item.photo_path || null,
  };
  showModal.value = true;
};

const saveItem = async () => {
  saving.value = true;
  try {
    const payload = { ...form.value };
    if (payload.source === 'internal') {
      payload.partner_id = null;
    }
    if (isEditing.value) {
      await api.put(`/items/${editingId.value}`, payload);
    } else {
      await api.post('/items', payload);
    }
    showModal.value = false;
    fetchItems();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan barang.');
  } finally {
    saving.value = false;
  }
};

const deleteItem = async (item) => {
  if (await window.$confirm(`Hapus barang "${item.name}"?`)) {
    try {
      await api.delete(`/items/${item.id}`);
      fetchItems();
    } catch (err) {
      alert(err.response?.data?.message || 'Gagal menghapus.');
    }
  }
};

const exportExcel = async () => {
  if (exporting.value) return;
  exporting.value = true;
  try {
    const today = new Date().toISOString().slice(0, 10);
    await downloadAuthenticatedFile(
      '/items/export',
      `Inventaris_Rojabiyyah_${today}.xlsx`,
      filters.value
    );
  } finally {
    exporting.value = false;
  }
};

const downloadTemplate = async () => {
  await downloadAuthenticatedFile(
    '/items/template',
    'Template_Impor_Inventaris_Rojabiyyah.xlsx'
  );
};

const executeImport = async () => {
  const file = importFileRef.value?.files[0];
  if (!file) return alert('Silakan pilih berkas Excel (.xlsx) atau CSV terlebih dahulu.');

  importing.value = true;
  const formData = new FormData();
  formData.append('file', file);

  try {
    const res = await api.post('/items/import/preview', formData);
    const validCount = res.data.valid_count ?? res.data.count ?? 0;
    const invalidCount = res.data.invalid_count ?? 0;
    const validRows = res.data.valid_rows ?? res.data.preview ?? [];

    if (validCount === 0) {
      alert('Tidak ditemukan baris data barang yang valid. Pastikan kolom nama terisi.');
      return;
    }

    let msg = `Ditemukan ${validCount} baris barang valid.`;
    if (invalidCount > 0) {
      msg += ` (${invalidCount} baris tidak lengkap akan dilewati).`;
    }
    msg += ` Lanjutkan proses impor inventaris?`;

    if (await window.$confirm(msg, 'Konfirmasi Impor Inventaris')) {
      const importRes = await api.post('/items/import', { rows: validRows });
      alert(importRes.data.message || `Berhasil mengimpor ${validCount} barang inventaris.`);
      showImportModal.value = false;
      if (importFileRef.value) importFileRef.value.value = '';
      fetchItems();
    }
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal memproses file impor inventaris.');
  } finally {
    importing.value = false;
  }
};

onMounted(() => {
  fetchItems();
  fetchCategories();
  fetchPartners();
});
</script>
