<template>
  <DashboardLayout>
    <!-- ================================================================== -->
    <!-- PASSWORD GATE MODAL — tampil dulu sebelum konten kas               -->
    <!-- ================================================================== -->
    <Transition name="fade">
      <div
        v-if="!cashPasswordVerified"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4"
        @click.self="goBack"
      >
        <div class="relative bg-white rounded-3xl shadow-2xl w-full max-w-md overflow-hidden border border-slate-200">
          <!-- Tombol Silang (X) Kembali -->
          <button
            type="button"
            @click="goBack"
            class="absolute top-4 right-4 z-10 w-9 h-9 rounded-full bg-white/20 hover:bg-white/30 text-white flex items-center justify-center transition"
            title="Batal & Kembali"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>

          <!-- Header hijau -->
          <div class="bg-gradient-to-br from-[#059669] to-[#047857] px-7 py-7 text-white text-center relative">
            <div class="w-16 h-16 rounded-2xl bg-white/20 border border-white/30 flex items-center justify-center mx-auto mb-4">
              <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
              </svg>
            </div>
            <h2 class="text-xl font-black tracking-tight">Verifikasi Akses Kas</h2>
            <p class="text-sm text-emerald-100 mt-1 font-medium">
              Menu Rekap Uang dilindungi. Masukkan kata sandi untuk melanjutkan.
            </p>
          </div>

          <!-- Body -->
          <div class="px-7 py-6 space-y-4">
            <!-- Error alert -->
            <div
              v-if="cashPasswordError"
              class="flex items-center space-x-2 bg-rose-50 border border-rose-200 rounded-xl px-4 py-3 text-rose-700 text-sm"
            >
              <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span>{{ cashPasswordError }}</span>
            </div>

            <!-- Input kata sandi -->
            <div class="space-y-1.5">
              <label class="block text-xs font-semibold text-slate-700">Kata Sandi Akses Kas</label>
              <div class="relative">
                <input
                  :type="showCashPwd ? 'text' : 'password'"
                  v-model="cashPasswordInput"
                  @keyup.enter="verifyCashPassword"
                  @keyup.esc="goBack"
                  placeholder="Masukkan kata sandi..."
                  class="w-full px-4 py-3 pr-10 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 outline-none transition"
                  :disabled="verifyingPassword"
                  autofocus
                />
                <button
                  type="button"
                  @click="showCashPwd = !showCashPwd"
                  class="absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-700 transition"
                  tabindex="-1"
                >
                  <svg v-if="!showCashPwd" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                  <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Submit button -->
            <button
              @click="verifyCashPassword"
              :disabled="verifyingPassword || !cashPasswordInput"
              class="w-full py-3 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold text-sm shadow-lg shadow-emerald-900/30 transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center space-x-2"
            >
              <span v-if="verifyingPassword" class="inline-block w-4 h-4 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
              <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M8 11V7a4 4 0 118 0m-4 8v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2z" />
              </svg>
              <span>{{ verifyingPassword ? 'Memverifikasi...' : 'Buka Rekap Uang' }}</span>
            </button>

            <!-- Tombol Batal & Kembali -->
            <button
              type="button"
              @click="goBack"
              class="w-full py-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 font-semibold text-xs transition flex items-center justify-center space-x-2"
            >
              <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
              </svg>
              <span>Batal &amp; Kembali</span>
            </button>

            <p class="text-center text-[11px] text-slate-400 pt-1">
              Hanya Super Admin &amp; Bendahara yang memiliki akses ke menu ini.
            </p>
          </div>
        </div>
      </div>
    </Transition>

    <!-- Konten halaman kas (tampil setelah password benar) -->
    <div class="space-y-6" v-if="cashPasswordVerified">
      <!-- Title & Actions -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <div class="flex items-center space-x-2.5">
            <div class="w-8 h-8 rounded-xl bg-teal-50 border border-slate-200 flex items-center justify-center text-teal-600">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Laporan Detail Pengeluaran (LPJ Kas)</h2>
          </div>
          <p class="text-xs text-slate-500 mt-1">Laporan pertanggungjawaban kas, rincian belanja operasional, debet, kredit, saldo berjalan, dan bukti nota.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
          <!-- Ekspor LPJ Excel -->
          <button
            @click="exportCash"
            :disabled="exporting"
            class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-emerald-50 hover:bg-emerald-100 border border-emerald-300 text-emerald-800 text-xs font-semibold transition disabled:opacity-50 shadow-sm"
          >
            <span v-if="exporting" class="inline-block w-3.5 h-3.5 border-2 border-emerald-600 border-t-transparent rounded-full animate-spin"></span>
            <svg v-else class="w-3.5 h-3.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            <span>{{ exporting ? 'Mengunduh...' : 'Unduh LPJ Excel (.xlsx)' }}</span>
          </button>

          <!-- Impor Kas -->
          <button
            v-if="['super_admin', 'bendahara'].includes(authStore.user?.role)"
            @click="showImportModal = true"
            class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-300 text-slate-700 text-xs font-semibold transition"
          >
            <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
            <span>Impor Data LPJ</span>
          </button>

          <!-- Tambah Transaksi -->
          <button
            v-if="['super_admin', 'bendahara'].includes(authStore.user?.role)"
            @click="openAddCashModal"
            class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white text-xs font-bold shadow-lg shadow-emerald-950/60 transition"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>+ Catat Transaksi LPJ</span>
          </button>
        </div>
      </div>

      <!-- Financial Metrics Summary Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-3xl border border-slate-200 flex items-center justify-between shadow-sm">
          <div>
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Debet (Kas Masuk)</span>
            <div class="text-xl sm:text-2xl font-black text-emerald-600 mt-1 font-mono">
              Rp {{ formatRupiah(summary.total_in || summaryLPJ.total_debet) }}
            </div>
            <span class="text-[10px] text-emerald-600 font-medium">Infaq, Subsidi, Sponsor</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
          </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200 flex items-center justify-between shadow-sm">
          <div>
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Kredit (Pengeluaran)</span>
            <div class="text-xl sm:text-2xl font-black text-rose-600 mt-1 font-mono">
              Rp {{ formatRupiah(summary.total_out || summaryLPJ.total_kredit) }}
            </div>
            <span class="text-[10px] text-rose-600 font-medium">Belanja Alat, Konsumsi, Operasional</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
          </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200 flex items-center justify-between shadow-sm">
          <div>
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Saldo Akhir Tersedia</span>
            <div class="text-xl sm:text-2xl font-black text-slate-900 mt-1 font-mono" :class="(summary.balance || summaryLPJ.saldo_akhir) < 0 ? 'text-rose-600' : 'text-slate-900'">
              Rp {{ formatRupiah(summary.balance || summaryLPJ.saldo_akhir) }}
            </div>
            <span class="text-[10px] text-teal-600 font-medium">Saldo Kas Riil Terverifikasi</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-teal-50 border border-teal-200 text-teal-600 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
        </div>
      </div>

      <!-- Filters & Search -->
      <div class="bg-white p-4 rounded-2xl border border-slate-200 flex flex-wrap gap-3 items-center text-xs shadow-sm">
        <select
          v-model="filters.type"
          @change="fetchTransactions"
          class="px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
        >
          <option value="">Semua Jenis (Debet &amp; Kredit)</option>
          <option value="in">Debet / Kas Masuk (+)</option>
          <option value="out">Kredit / Pengeluaran (-)</option>
        </select>

        <select
          v-model="filters.category_id"
          @change="fetchTransactions"
          class="px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
        >
          <option value="">Semua Kategori</option>
          <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
        </select>

        <input
          v-model="filters.search"
          @input="debounceSearch"
          type="text"
          placeholder="Cari uraian belanja, nama pelaksana, atau keterangan..."
          class="flex-1 min-w-[220px] px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500"
        />
      </div>

      <!-- LPJ Transactions Table (Persis Format Excel) -->
      <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <!-- Mobile Swipe Helper Indicator -->
        <div class="sm:hidden px-3.5 py-2 bg-emerald-50/70 border-b border-emerald-100 flex items-center justify-between text-[11px] text-emerald-800 font-semibold">
          <span class="flex items-center space-x-1.5">
            <svg class="w-3.5 h-3.5 text-emerald-600 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
            <span>Geser tabel ke samping untuk rincian LPJ</span>
          </span>
          <span class="text-[10px] text-emerald-600 font-mono font-bold">{{ transactions.length }} data</span>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs border-collapse">
            <thead class="bg-[#064e3b] text-white uppercase font-bold text-[10px] tracking-wider">
              <tr>
                <th class="py-3 px-3 text-center w-12 border-b border-emerald-900">No.</th>
                <th class="py-3 px-3 text-center whitespace-nowrap border-b border-emerald-900">Tanggal</th>
                <th class="py-3 px-3 whitespace-nowrap border-b border-emerald-900">Pelaksana</th>
                <th class="py-3 px-4 min-w-[220px] border-b border-emerald-900">Uraian</th>
                <th class="py-3 px-2 text-center w-14 border-b border-emerald-900">Jumlah</th>
                <th class="py-3 px-2 text-center w-16 border-b border-emerald-900">Satuan</th>
                <th class="py-3 px-4 text-right text-emerald-200 whitespace-nowrap border-b border-emerald-900">Debet</th>
                <th class="py-3 px-4 text-right text-rose-200 whitespace-nowrap border-b border-emerald-900">Kredit</th>
                <th class="py-3 px-4 text-right text-emerald-100 whitespace-nowrap border-b border-emerald-900">Saldo</th>
                <th class="py-3 px-3 min-w-[140px] border-b border-emerald-900">Keterangan</th>
                <th class="py-3 px-3 text-center w-20 border-b border-emerald-900">Nota</th>
                <th v-if="['super_admin', 'bendahara'].includes(authStore.user?.role)" class="py-3 px-3 text-center w-24 border-b border-emerald-900">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
              <tr v-if="transactions.length === 0" class="text-center">
                <td colspan="12" class="py-12 text-slate-500">Belum ada catatan transaksi kas LPJ.</td>
              </tr>
              <tr
                v-for="(trx, idx) in transactions"
                :key="trx.id"
                :class="trx.voided_at ? 'bg-rose-50/50 opacity-60' : (idx % 2 === 0 ? 'bg-white' : 'bg-slate-50/60')"
                class="hover:bg-emerald-50/40 transition"
              >
                <!-- No. -->
                <td class="py-3 px-3 text-center font-bold text-slate-500 text-[11px]">{{ idx + 1 }}</td>

                <!-- Tanggal -->
                <td class="py-3 px-3 text-center text-slate-700 whitespace-nowrap font-mono text-[11px]">
                  {{ formatDate(trx.date) }}
                </td>

                <!-- Pelaksana -->
                <td class="py-3 px-3 whitespace-nowrap font-semibold text-slate-900 text-[11px]">
                  {{ trx.pelaksana || trx.creator?.name || '-' }}
                </td>

                <!-- Uraian -->
                <td class="py-3 px-4 text-slate-900 font-medium">
                  <div>{{ trx.description }}</div>
                  <div v-if="trx.category?.name" class="text-[10px] text-slate-600 font-normal mt-0.5">
                    {{ trx.category.name }}
                  </div>
                </td>

                <!-- Jumlah / Qty -->
                <td class="py-3 px-2 text-center text-slate-700 font-mono text-[11px]">
                  {{ trx.qty || '-' }}
                </td>

                <!-- Satuan / Harga Satuan -->
                <td class="py-3 px-2 text-center text-slate-700 text-[11px] whitespace-nowrap">
                  <span v-if="trx.unit_price" class="font-mono text-slate-800 font-semibold">{{ formatRupiah(trx.unit_price) }}</span>
                  <span v-else-if="trx.unit">{{ trx.unit }}</span>
                  <span v-else class="text-slate-400">-</span>
                </td>

                <!-- Debet (Kas Masuk) -->
                <td class="py-3 px-4 text-right font-mono font-bold whitespace-nowrap text-emerald-700 text-[11px]">
                  <span v-if="trx.type === 'in' && !trx.voided_at">{{ formatRupiah(trx.amount) }}</span>
                  <span v-else class="text-slate-400">0</span>
                </td>

                <!-- Kredit (Pengeluaran) -->
                <td class="py-3 px-4 text-right font-mono font-bold whitespace-nowrap text-rose-700 text-[11px]">
                  <span v-if="trx.type === 'out' && !trx.voided_at">{{ formatRupiah(trx.amount) }}</span>
                  <span v-else class="text-slate-400">0</span>
                </td>

                <!-- Saldo Berjalan -->
                <td
                  class="py-3 px-4 text-right font-mono font-black whitespace-nowrap text-[11px]"
                  :class="(trx.saldo ?? 0) < 0 ? 'text-rose-600' : 'text-slate-900'"
                >
                  {{ formatRupiah(trx.saldo ?? 0) }}
                </td>

                <!-- Keterangan / Anggaran -->
                <td class="py-3 px-3 text-slate-600 text-[11px]">
                  {{ trx.notes || '-' }}
                </td>

                <!-- Nota -->
                <td class="py-3 px-3 text-center whitespace-nowrap">
                  <div v-if="trx.receipt_path">
                    <a
                      v-if="trx.receipt_path.startsWith('http')"
                      :href="trx.receipt_path"
                      target="_blank"
                      rel="noopener noreferrer"
                      class="inline-flex items-center space-x-1 px-2 py-1 rounded-md bg-blue-50 text-blue-700 hover:bg-blue-100 text-[10px] font-semibold border border-blue-200"
                      title="Buka Link Nota"
                    >
                      <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                      <span>Nota</span>
                    </a>
                    <a
                      v-else
                      :href="trx.receipt_path"
                      target="_blank"
                      class="inline-block w-7 h-7 rounded border border-slate-300 overflow-hidden hover:scale-110 transition"
                      title="Lihat Foto Bukti"
                    >
                      <img :src="trx.receipt_path" alt="Nota" class="w-full h-full object-cover" />
                    </a>
                  </div>
                  <span v-else class="text-slate-400 text-[10px]">-</span>
                </td>

                <!-- Aksi -->
                <td v-if="['super_admin', 'bendahara'].includes(authStore.user?.role)" class="py-3 px-3 text-center whitespace-nowrap">
                  <div class="flex items-center justify-center space-x-1">
                    <button
                      v-if="!trx.voided_at"
                      @click="openEditCashModal(trx)"
                      title="Edit Data LPJ"
                      class="p-1 text-slate-600 hover:text-emerald-600 hover:bg-emerald-50 rounded-lg transition"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button
                      v-if="!trx.voided_at"
                      @click="openVoidModal(trx)"
                      title="Batalkan Transaksi (Void)"
                      class="p-1 text-slate-600 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                      </svg>
                    </button>
                    <button
                      v-if="authStore.user?.role === 'super_admin'"
                      @click="deleteCash(trx)"
                      title="Hapus Transaksi"
                      class="p-1 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>

            <!-- TOTAL >>> ROW -->
            <tfoot v-if="transactions.length > 0" class="bg-emerald-50 border-t-2 border-emerald-600 font-bold text-xs">
              <tr>
                <td colspan="6" class="py-3.5 px-4 text-right uppercase tracking-wider text-emerald-950 font-black">
                  TOTAL >>>
                </td>
                <td class="py-3.5 px-4 text-right font-mono font-black text-emerald-700 whitespace-nowrap">
                  Rp {{ formatRupiah(summaryLPJ.total_debet) }}
                </td>
                <td class="py-3.5 px-4 text-right font-mono font-black text-rose-700 whitespace-nowrap">
                  Rp {{ formatRupiah(summaryLPJ.total_kredit) }}
                </td>
                <td class="py-3.5 px-4 text-right font-mono font-black whitespace-nowrap" :class="summaryLPJ.saldo_akhir < 0 ? 'text-rose-700' : 'text-emerald-950'">
                  Rp {{ formatRupiah(summaryLPJ.saldo_akhir) }}
                </td>
                <td colspan="3" class="py-3.5 px-4 text-slate-400"></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>

    <!-- MODAL: Catat / Edit Transaksi Kas LPJ -->
    <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md p-4 overflow-y-auto">
      <div class="relative w-full max-w-lg bg-white border border-slate-200 rounded-3xl p-6 sm:p-7 shadow-sm text-slate-700 my-8">
        <!-- Header -->
        <div class="flex items-center justify-between pb-3 border-b border-slate-200 mb-5">
          <div class="flex items-center space-x-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-600"></span>
            <h3 class="text-base font-bold text-slate-900 tracking-wide">
              {{ editingTrxId ? 'Edit Catatan Transaksi LPJ' : 'Catat Transaksi Kas LPJ Baru' }}
            </h3>
          </div>
          <button @click="showAddModal = false" class="text-slate-400 hover:text-slate-700 transition p-1 rounded-lg hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <form @submit.prevent="submitCash" class="space-y-4 text-xs">
          <!-- Row 1: Jenis Transaksi & Tanggal -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Jenis Transaksi *</label>
              <select
                v-model="cashForm.type"
                required
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 transition font-medium"
              >
                <option value="out">Kredit / Pengeluaran (-)</option>
                <option value="in">Debet / Kas Masuk / Subsidi (+)</option>
              </select>
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Tanggal Transaksi *</label>
              <input
                v-model="cashForm.date"
                type="date"
                required
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 font-sans transition"
              />
            </div>
          </div>

          <!-- Row 2: Pelaksana & Kategori Kas -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Pelaksana (PIC) *</label>
              <input
                v-model="cashForm.pelaksana"
                type="text"
                required
                placeholder="misal: MUIZ, Pak Haqi..."
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition"
              />
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Kategori Kas *</label>
              <select
                v-model="cashForm.category_id"
                required
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 transition uppercase"
              >
                <option :value="null">PILIH KATEGORI</option>
                <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
              </select>
            </div>
          </div>

          <!-- Row 3: Uraian Belanja / Transaksi -->
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Uraian Belanja / Subsidi *</label>
            <input
              v-model="cashForm.description"
              type="text"
              required
              placeholder="misal: Subsidi, AMD Rayzen 3 1200, DP VEDIOTRON..."
              class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition"
            />
          </div>

          <!-- Row 4: Jumlah (Qty), Harga Satuan (Rp), Satuan & Total Nominal -->
          <div class="p-3.5 rounded-2xl bg-emerald-50/50 border border-emerald-200/80 space-y-3">
            <div class="flex items-center justify-between">
              <span class="font-bold text-emerald-950 text-xs flex items-center space-x-1.5">
                <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                <span>Perhitungan Kuantitas &amp; Nominal Otomatis</span>
              </span>
              <span class="text-[10px] text-emerald-800 font-bold bg-emerald-100 border border-emerald-300 px-2 py-0.5 rounded-full">
                Auto Hitung
              </span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
              <div>
                <label class="block font-semibold text-slate-700 mb-1">Jumlah (Qty)</label>
                <input
                  v-model.number="cashForm.qty"
                  @input="onQtyOrPriceChange"
                  type="number"
                  step="any"
                  min="0"
                  placeholder="misal: 9"
                  class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:border-emerald-500 text-center font-mono font-bold text-sm"
                />
              </div>
              <div>
                <label class="block font-semibold text-slate-700 mb-1">Harga Satuan (Rp)</label>
                <input
                  v-model.number="cashForm.unit_price"
                  @input="onQtyOrPriceChange"
                  type="number"
                  min="0"
                  placeholder="misal: 250000"
                  class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:border-emerald-500 font-mono text-right text-sm"
                />
                <span v-if="cashForm.unit_price" class="text-[10px] text-emerald-700 font-bold block text-right mt-0.5 font-mono">
                  Rp {{ formatRupiah(cashForm.unit_price) }}
                </span>
              </div>
              <div>
                <label class="block font-semibold text-slate-700 mb-1">Satuan (Opsional)</label>
                <input
                  v-model="cashForm.unit"
                  @input="onUnitInputChange"
                  type="text"
                  placeholder="Pcs, Unit, Bulan..."
                  class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 text-center text-xs"
                />
              </div>
            </div>

            <!-- Total Nominal (Hasil Otomatis) -->
            <div class="pt-2 border-t border-emerald-200">
              <div class="flex items-center justify-between mb-1">
                <label class="font-black text-slate-900 text-xs flex items-center space-x-1">
                  <span>Hasil Total Nominal (Rp) *</span>
                </label>
                <span class="text-xs font-mono font-black" :class="cashForm.type === 'in' ? 'text-emerald-700' : 'text-rose-700'">
                  Rp {{ formatRupiah(cashForm.amount) }}
                </span>
              </div>
              <input
                v-model.number="cashForm.amount"
                @input="onAmountChange"
                type="number"
                min="1"
                required
                placeholder="misal: 2250000"
                class="w-full px-3.5 py-2.5 bg-white border-2 border-emerald-600 rounded-xl text-slate-950 font-mono font-black text-sm focus:outline-none focus:ring-2 focus:ring-emerald-400"
              />
              <p class="text-[10px] text-slate-500 mt-1">
                💡 <em>Hasil nominal otomatis dihitung dari <strong>Jumlah (Qty) × Harga Satuan</strong> (misal: 9 × 250.000 = 2.250.000). Berlaku untuk Pemasukan maupun Pengeluaran.</em>
              </p>
            </div>
          </div>

          <!-- Row 5: Keterangan / Anggaran -->
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Keterangan / Alokasi Anggaran (Opsional)</label>
            <input
              v-model="cashForm.notes"
              type="text"
              placeholder="misal: Anggaran Bulan September, Donasi Hamba Allah..."
              class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 transition"
            />
          </div>

          <!-- Row 6: Bukti Nota (Bisa Link Google Drive atau Upload Gambar) -->
          <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200 space-y-3">
            <label class="block font-semibold text-slate-700">Bukti Nota / Dokumen Pendukung (Opsional)</label>

            <!-- Link Google Drive / Web Nota -->
            <div>
              <input
                v-model="cashForm.receipt_path"
                type="url"
                placeholder="Tempelkan Link Google Drive Nota / URL (misal: https://drive.google.com/...)"
                class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 text-xs"
              />
            </div>

            <!-- Upload File Nota -->
            <div class="flex items-center space-x-3 pt-1">
              <input
                type="file"
                ref="receiptFileRef"
                accept="image/*"
                @change="handleReceiptUpload"
                class="hidden"
              />
              <button
                type="button"
                @click="$refs.receiptFileRef.click()"
                class="px-3 py-1.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-800 text-xs font-semibold transition flex items-center space-x-1"
              >
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                <span>Atau Unggah Berkas Gambar</span>
              </button>
              <span class="text-slate-500 text-[11px] truncate max-w-[200px]">
                {{ receiptFileName || (cashForm.receipt_path?.startsWith('data:') ? 'Foto terunggah' : '') }}
              </span>
              <button
                v-if="cashForm.receipt_path"
                type="button"
                @click="clearReceipt"
                class="text-[11px] text-rose-500 hover:underline"
              >
                Hapus
              </button>
            </div>
          </div>

          <!-- Bottom Actions -->
          <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200">
            <button
              type="button"
              @click="showAddModal = false"
              class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold transition"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="submitting"
              class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold shadow-lg shadow-emerald-950/60 transition disabled:opacity-50"
            >
              {{ submitting ? 'Menyimpan...' : (editingTrxId ? 'Simpan Perubahan LPJ' : 'Simpan Transaksi LPJ') }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- MODAL: Impor Transaksi Kas -->
    <div v-if="showImportModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md p-4">
      <div class="w-full max-w-lg bg-white border border-slate-200 rounded-3xl p-6 shadow-sm text-slate-700">
        <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-4">
          <div class="flex items-center space-x-2">
            <span class="w-2.5 h-2.5 rounded-full bg-indigo-500 animate-pulse"></span>
            <h3 class="text-base font-bold text-slate-900">Impor Spreadsheet Transaksi Kas</h3>
          </div>
          <button @click="showImportModal = false" class="text-slate-500 hover:text-white text-lg">✕</button>
        </div>

        <div class="space-y-4 text-xs">
          <p class="text-slate-500">
            Unggah file Excel (<span class="text-white font-mono">.xlsx, .xls</span>) atau <span class="text-white font-mono">.csv</span> yang berisi rekapitulasi kas masuk / keluar kegiatan.
          </p>

          <div class="p-3.5 rounded-2xl bg-white border border-slate-200 flex items-center justify-between">
            <div>
              <p class="font-semibold text-white">Format Kolom Transaksi Kas</p>
              <p class="text-[11px] text-slate-500">Gunakan format template resmi agar data tanggal &amp; nominal tepat.</p>
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
            <span>{{ importing ? 'Memproses Impor...' : 'Mulai Impor Kas' }}</span>
          </button>
        </div>
      </div>
    </div><!-- end v-if="cashPasswordVerified" -->
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import DashboardLayout from '../../layouts/DashboardLayout.vue';
import { useAuthStore } from '../../stores/auth';
import { useRealtimeStore } from '../../stores/realtime';
import api from '../../services/api';
import { downloadAuthenticatedFile } from '../../utils/fileDownloader';

const router = useRouter();
const authStore = useAuthStore();
const realtimeStore = useRealtimeStore();

// ============================================================
// PASSWORD GATE — Verifikasi akses sebelum melihat rekap uang
// ============================================================
const cashPasswordVerified = ref(false);
const cashPasswordInput = ref('');
const cashPasswordError = ref('');
const verifyingPassword = ref(false);
const showCashPwd = ref(false);

const goBack = () => {
  if (window.history.length > 1) {
    router.back();
  } else if (['super_admin', 'admin'].includes(authStore.user?.role)) {
    router.push('/dashboard');
  } else {
    router.push('/member/dashboard');
  }
};

const verifyCashPassword = async () => {
  if (!cashPasswordInput.value) return;
  verifyingPassword.value = true;
  cashPasswordError.value = '';
  try {
    const res = await api.post('/cash/verify-password', { password: cashPasswordInput.value });
    if (res.data.valid) {
      cashPasswordVerified.value = true;
      cashPasswordInput.value = '';
      // Load data kas setelah akses dibuka
      fetchTransactions();
      fetchSummary();
      fetchCategories();
    } else {
      cashPasswordError.value = res.data.message || 'Kata sandi salah.';
    }
  } catch (err) {
    cashPasswordError.value = err.response?.data?.message || 'Kata sandi salah. Silakan coba lagi.';
  } finally {
    verifyingPassword.value = false;
  }
};
// ============================================================
const transactions = ref([]);
const categories = ref([]);
const summary = ref({ total_in: 0, total_out: 0, balance: 0 });
const summaryLPJ = ref({ total_debet: 0, total_kredit: 0, saldo_akhir: 0 });
const filters = ref({ type: '', category_id: '', search: '' });

const showAddModal = ref(false);
const showImportModal = ref(false);
const editingTrxId = ref(null);
const submitting = ref(false);
const exporting = ref(false);
const importing = ref(false);
const receiptFileRef = ref(null);
const importFileRef = ref(null);
const receiptFileName = ref('');

const cashForm = ref({
  type: 'out',
  amount: '',
  qty: 1,
  unit_price: '',
  unit: '',
  date: new Date().toISOString().split('T')[0],
  category_id: null,
  description: '',
  pelaksana: '',
  notes: '',
  receipt_path: '',
});

const onQtyOrPriceChange = () => {
  const q = parseFloat(cashForm.value.qty);
  const p = parseFloat(cashForm.value.unit_price);
  if (!isNaN(q) && q > 0 && !isNaN(p) && p >= 0) {
    cashForm.value.amount = Math.round(q * p);
  } else if (!isNaN(p) && p > 0 && (isNaN(q) || q === 0)) {
    cashForm.value.amount = Math.round(p);
  }
};

const onAmountChange = () => {
  const a = parseFloat(cashForm.value.amount);
  const q = parseFloat(cashForm.value.qty);
  if (!isNaN(a) && a > 0 && !isNaN(q) && q > 0) {
    cashForm.value.unit_price = Math.round(a / q);
  }
};

const onUnitInputChange = () => {
  const raw = String(cashForm.value.unit || '').trim();
  // Jika user menulis nominal pada kolom satuan (misal: 250000)
  if (/^\d+$/.test(raw) && Number(raw) > 0) {
    cashForm.value.unit_price = Number(raw);
    onQtyOrPriceChange();
  }
};

let searchTimeout = null;
const debounceSearch = () => {
  clearTimeout(searchTimeout);
  searchTimeout = setTimeout(() => {
    fetchTransactions();
  }, 400);
};

const fetchTransactions = async () => {
  try {
    const res = await api.get('/cash', { params: filters.value });
    transactions.value = res.data.data || [];
    if (res.data.summary) {
      summaryLPJ.value = res.data.summary;
    }
  } catch (e) {
    //
  }
};

const fetchSummary = async () => {
  try {
    const res = await api.get('/cash/summary');
    summary.value = res.data;
  } catch (e) {
    //
  }
};

const fetchCategories = async () => {
  try {
    const res = await api.get('/cash/categories');
    categories.value = res.data;
  } catch (e) {
    //
  }
};

const handleReceiptUpload = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  receiptFileName.value = file.name;
  const reader = new FileReader();
  reader.onload = (event) => {
    cashForm.value.receipt_path = event.target.result;
  };
  reader.readAsDataURL(file);
};

const clearReceipt = () => {
  cashForm.value.receipt_path = '';
  receiptFileName.value = '';
  if (receiptFileRef.value) receiptFileRef.value.value = '';
};

const formatRupiah = (val) => {
  return new Intl.NumberFormat('id-ID').format(val || 0);
};

const formatDate = (dateStr) => {
  if (!dateStr) return '-';
  const d = new Date(dateStr);
  return d.toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
};

const openAddCashModal = () => {
  editingTrxId.value = null;
  clearReceipt();
  cashForm.value = {
    type: 'out',
    amount: '',
    qty: 1,
    unit_price: '',
    unit: '',
    date: new Date().toISOString().split('T')[0],
    category_id: categories.value[0]?.id || null,
    description: '',
    pelaksana: authStore.user?.name || '',
    notes: '',
    receipt_path: '',
  };
  showAddModal.value = true;
};

const openEditCashModal = (trx) => {
  editingTrxId.value = trx.id;
  clearReceipt();
  cashForm.value = {
    type: trx.type,
    amount: trx.amount,
    qty: trx.qty || '',
    unit_price: trx.unit_price || (trx.qty && trx.amount ? Math.round(trx.amount / trx.qty) : ''),
    unit: trx.unit || '',
    date: trx.date ? String(trx.date).substring(0, 10) : new Date().toISOString().split('T')[0],
    category_id: trx.category_id || categories.value[0]?.id || null,
    description: trx.description || '',
    pelaksana: trx.pelaksana || trx.creator?.name || '',
    notes: trx.notes || '',
    receipt_path: trx.receipt_path || '',
  };
  showAddModal.value = true;
};

const submitCash = async () => {
  submitting.value = true;
  try {
    if (editingTrxId.value) {
      await api.put(`/cash/${editingTrxId.value}`, cashForm.value);
      alert('Transaksi kas LPJ berhasil diperbarui!');
    } else {
      await api.post('/cash', cashForm.value);
      alert('Transaksi kas LPJ berhasil dicatat!');
    }
    showAddModal.value = false;
    fetchTransactions();
    fetchSummary();
    realtimeStore.fetchLiveStats();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan transaksi kas.');
  } finally {
    submitting.value = false;
  }
};

const deleteCash = async (trx) => {
  if (confirm(`Yakin ingin menghapus transaksi "${trx.description}"? Tindakan ini tidak dapat dibatalkan.`)) {
    try {
      await api.delete(`/cash/${trx.id}`);
      fetchTransactions();
      fetchSummary();
      realtimeStore.fetchLiveStats();
      alert('Transaksi berhasil dihapus.');
    } catch (err) {
      alert(err.response?.data?.message || 'Gagal menghapus transaksi.');
    }
  }
};

const openVoidModal = async (trx) => {
  const reason = prompt(`Masukkan alasan pembatalan (void) untuk transaksi "${trx.description}" sebesar Rp ${formatRupiah(trx.amount)}:`);
  if (reason) {
    try {
      await api.post(`/cash/${trx.id}/void`, { void_reason: reason });
      fetchTransactions();
      fetchSummary();
      realtimeStore.fetchLiveStats();
      alert('Transaksi berhasil dibatalkan (void).');
    } catch (err) {
      alert(err.response?.data?.message || 'Gagal membatalkan transaksi.');
    }
  }
};

const exportCash = async () => {
  if (exporting.value) return;
  exporting.value = true;
  try {
    const today = new Date().toISOString().slice(0, 10);
    await downloadAuthenticatedFile(
      '/cash/export',
      `LPJ_Pengeluaran_Kas_${today}.xlsx`,
      filters.value
    );
  } finally {
    exporting.value = false;
  }
};

const downloadTemplate = async () => {
  await downloadAuthenticatedFile(
    '/cash/template',
    'Template_Impor_Kas_Rojabiyyah.xlsx'
  );
};

const executeImport = async () => {
  const file = importFileRef.value?.files[0];
  if (!file) return alert('Silakan pilih berkas Excel (.xlsx) atau CSV terlebih dahulu.');

  importing.value = true;
  const formData = new FormData();
  formData.append('file', file);

  try {
    const res = await api.post('/cash/import/preview', formData);
    const validCount = res.data.valid_count ?? res.data.count ?? 0;
    const invalidCount = res.data.invalid_count ?? 0;
    const validRows = res.data.valid_rows ?? res.data.preview ?? [];

    if (validCount === 0) {
      alert('Tidak ditemukan baris transaksi kas yang valid. Pastikan kolom jumlah (nominal) dan uraian terisi.');
      return;
    }

    let msg = `Ditemukan ${validCount} baris transaksi kas valid.`;
    if (invalidCount > 0) {
      msg += ` (${invalidCount} baris tidak lengkap akan dilewati).`;
    }
    msg += ` Lanjutkan proses impor data kas?`;

    if (await window.$confirm(msg, 'Konfirmasi Impor Kas')) {
      const importRes = await api.post('/cash/import', { rows: validRows });
      alert(importRes.data.message || `Berhasil mengimpor ${validCount} transaksi kas.`);
      showImportModal.value = false;
      if (importFileRef.value) importFileRef.value.value = '';
      fetchTransactions();
      fetchSummary();
      realtimeStore.fetchLiveStats();
    }
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal memproses file impor transaksi kas.');
  } finally {
    importing.value = false;
  }
};

const handleEscKey = (e) => {
  if (e.key === 'Escape' && !cashPasswordVerified.value) {
    goBack();
  }
};

onMounted(() => {
  // Reset verifikasi setiap kali halaman dimuat ulang
  cashPasswordVerified.value = false;
  cashPasswordInput.value = '';
  cashPasswordError.value = '';
  window.addEventListener('keydown', handleEscKey);
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleEscKey);
});
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.25s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
