<template>
  <DashboardLayout>
    <div class="space-y-6">
      <!-- Title & Actions -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <div class="flex items-center space-x-2.5">
            <div class="w-8 h-8 rounded-xl bg-teal-50 border border-slate-200 flex items-center justify-center text-teal-400">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
            </div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Rekap Kas Keuangan</h2>
          </div>
          <p class="text-xs text-slate-500 mt-1">Pencatatan kas masuk &amp; keluar, bukti transaksi, transparansi anggaran, dan pembatalan void.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
          <!-- Ekspor Kas -->
          <button
            @click="exportCash"
            :disabled="exporting"
            class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-teal-50 border border-emerald-200 text-emerald-700 text-xs font-semibold transition disabled:opacity-50"
          >
            <span v-if="exporting" class="inline-block w-3.5 h-3.5 border-2 border-emerald-400 border-t-transparent rounded-full animate-spin"></span>
            <svg v-else class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            <span>{{ exporting ? 'Mengunduh...' : 'Ekspor Kas Excel/CSV' }}</span>
          </button>

          <!-- Impor Kas -->
          <button
            v-if="['super_admin', 'bendahara'].includes(authStore.user?.role)"
            @click="showImportModal = true"
            class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-teal-50 border border-indigo-200 text-indigo-700 text-xs font-semibold transition"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
            <span>Impor Kas</span>
          </button>

          <button
            v-if="['super_admin', 'bendahara'].includes(authStore.user?.role)"
            @click="openAddCashModal"
            class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white text-xs font-bold shadow-lg shadow-emerald-950/60 transition"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>+ Catat Transaksi Kas</span>
          </button>
        </div>
      </div>

      <!-- Financial Metrics Summary Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-3xl border border-slate-200 flex items-center justify-between shadow-sm">
          <div>
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Kas Masuk</span>
            <div class="text-xl sm:text-2xl font-black text-emerald-400 mt-1 font-mono">
              Rp {{ formatRupiah(summary.total_in) }}
            </div>
            <span class="text-[10px] text-emerald-500 font-medium">Infaq, Sponsor, Donasi</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-emerald-950/60 border border-emerald-800 text-emerald-400 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"/></svg>
          </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200 flex items-center justify-between shadow-sm">
          <div>
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Total Pengeluaran</span>
            <div class="text-xl sm:text-2xl font-black text-rose-400 mt-1 font-mono">
              Rp {{ formatRupiah(summary.total_out) }}
            </div>
            <span class="text-[10px] text-rose-500 font-medium">Konsumsi, Logistik, Operasional</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-rose-950/60 border border-rose-800 text-rose-400 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"/></svg>
          </div>
        </div>

        <div class="bg-white p-5 rounded-3xl border border-slate-200 flex items-center justify-between shadow-sm">
          <div>
            <span class="text-xs font-bold text-slate-500 uppercase tracking-wider block">Saldo Bersih Tersedia</span>
            <div class="text-xl sm:text-2xl font-black text-white mt-1 font-mono">
              Rp {{ formatRupiah(summary.balance) }}
            </div>
            <span class="text-[10px] text-teal-400 font-medium">Saldo Kas Riil Terverifikasi</span>
          </div>
          <div class="w-12 h-12 rounded-2xl bg-teal-950/60 border border-teal-800 text-teal-400 flex items-center justify-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
        </div>
      </div>

      <!-- Filters -->
      <div class="bg-white p-4 rounded-2xl border border-slate-200 flex flex-wrap gap-3 items-center text-xs">
        <select
          v-model="filters.type"
          @change="fetchTransactions"
          class="px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
        >
          <option value="">Semua Jenis Transaksi</option>
          <option value="in">Pemasukan (+)</option>
          <option value="out">Pengeluaran (-)</option>
        </select>

        <select
          v-model="filters.category_id"
          @change="fetchTransactions"
          class="px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
        >
          <option value="">Semua Kategori Kas</option>
          <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
        </select>

        <input
          v-model="filters.search"
          @input="debounceSearch"
          type="text"
          placeholder="Cari keterangan, bukti, atau uraian belanja..."
          class="flex-1 min-w-[220px] px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-emerald-500"
        />
      </div>

      <!-- Transactions Table -->
      <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead class="bg-[#0e172a] border-b border-slate-200 text-slate-500 uppercase font-bold text-[10px] tracking-wider">
              <tr>
                <th class="py-3.5 px-4">Tanggal</th>
                <th class="py-3.5 px-4">Jenis</th>
                <th class="py-3.5 px-4">Kategori &amp; Uraian</th>
                <th class="py-3.5 px-4 text-right">Nominal (Rp)</th>
                <th class="py-3.5 px-4 text-center">Bukti Nota</th>
                <th class="py-3.5 px-4">Status</th>
                <th v-if="['super_admin', 'bendahara'].includes(authStore.user?.role)" class="py-3.5 px-4 text-center w-20">Aksi</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-if="transactions.length === 0" class="text-center">
                <td colspan="7" class="py-12 text-slate-500">Belum ada catatan transaksi kas.</td>
              </tr>
              <tr
                v-for="trx in transactions"
                :key="trx.id"
                :class="trx.voided_at ? 'bg-rose-950/20 opacity-60' : 'hover:bg-slate-50'"
                class="transition"
              >
                <td class="py-3.5 px-4 text-slate-700 font-mono whitespace-nowrap">
                  {{ formatDate(trx.date) }}
                </td>
                <td class="py-3.5 px-4 whitespace-nowrap">
                  <span
                    :class="trx.type === 'in' ? 'bg-emerald-950 text-emerald-300 border-emerald-800' : 'bg-rose-950 text-rose-300 border-rose-800'"
                    class="px-2.5 py-1 font-bold rounded-lg text-[10px] uppercase border"
                  >
                    {{ trx.type === 'in' ? 'Pemasukan (+)' : 'Pengeluaran (-)' }}
                  </span>
                </td>
                <td class="py-3.5 px-4">
                  <div class="font-bold text-white">{{ trx.category?.name || 'Kas Umum' }}</div>
                  <div class="text-[11px] text-slate-500">{{ trx.description }}</div>
                </td>
                <td class="py-3.5 px-4 text-right font-mono font-bold whitespace-nowrap" :class="trx.type === 'in' ? 'text-emerald-400' : 'text-rose-400'">
                  {{ trx.type === 'in' ? '+' : '-' }} Rp {{ formatRupiah(trx.amount) }}
                </td>
                <td class="py-3.5 px-4 text-center">
                  <div v-if="trx.receipt_path" class="inline-block w-8 h-8 rounded-lg overflow-hidden border border-slate-300">
                    <img :src="trx.receipt_path" alt="Nota" class="w-full h-full object-cover" />
                  </div>
                  <span v-else class="text-slate-500 text-[10px]">-</span>
                </td>
                <td class="py-3.5 px-4">
                  <span v-if="trx.voided_at" class="text-[10px] text-rose-400 font-bold">
                    DIBATALKAN (VOID)
                  </span>
                  <span v-else class="text-[10px] text-emerald-400 font-semibold">
                    Sah
                  </span>
                </td>
                <td v-if="['super_admin', 'bendahara'].includes(authStore.user?.role)" class="py-3.5 px-4 text-center">
                  <button
                    v-if="!trx.voided_at"
                    @click="openVoidModal(trx)"
                    title="Batalkan Transaksi (Void)"
                    class="p-1.5 text-slate-500 hover:text-rose-400 hover:bg-teal-50 rounded-lg transition"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- MODAL: Catat Transaksi Keuangan Baru (Image 3 Match + Upload Foto Bukti) -->
    <div v-if="showAddModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md p-4 overflow-y-auto">
      <div class="relative w-full max-w-lg bg-white border border-slate-200 rounded-3xl p-6 sm:p-7 shadow-sm text-slate-700 my-8">
        <!-- Header -->
        <div class="flex items-center justify-between pb-3 border-b border-slate-200 mb-5">
          <h3 class="text-base font-bold text-slate-900 tracking-wide">Catat Transaksi Keuangan Baru</h3>
          <button @click="showAddModal = false" class="text-slate-500 hover:text-white transition p-1 rounded-lg hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <form @submit.prevent="submitCash" class="space-y-4 text-xs">
          <!-- Row 1: Jenis Transaksi & Kategori Kas -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Jenis Transaksi *</label>
              <select
                v-model="cashForm.type"
                required
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 transition"
              >
                <option value="in">Pemasukan (+)</option>
                <option value="out">Pengeluaran (-)</option>
              </select>
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

          <!-- Row 2: Nominal & Tanggal Transaksi -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Nominal (Rp) *</label>
              <input
                v-model.number="cashForm.amount"
                type="number"
                min="1"
                required
                placeholder="misal: 500000"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-emerald-500 font-mono transition"
              />
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

          <!-- Row 3: Keterangan / Deskripsi Transaksi -->
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Keterangan / Deskripsi Transaksi *</label>
            <textarea
              v-model="cashForm.description"
              required
              rows="3"
              placeholder="Rincian peruntukan kas atau nama donatur..."
              class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-emerald-500 transition"
            ></textarea>
          </div>

          <!-- Row 4: Foto Bukti Nota / Kwitansi (Opsional - Image 3 Match) -->
          <div class="p-3.5 rounded-2xl bg-slate-50 border border-slate-200">
            <label class="block font-semibold text-slate-700 mb-2">Foto Bukti Nota / Kwitansi (Opsional)</label>
            <div class="flex items-center space-x-3.5">
              <div class="w-14 h-14 rounded-xl bg-white border border-slate-300 overflow-hidden flex items-center justify-center text-slate-500 flex-shrink-0">
                <img
                  v-if="cashForm.receipt_path"
                  :src="cashForm.receipt_path"
                  alt="Bukti Nota"
                  class="w-full h-full object-cover"
                />
                <svg v-else class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              </div>

              <div class="flex-1 space-y-1">
                <input
                  type="file"
                  ref="receiptFileRef"
                  accept="image/*"
                  @change="handleReceiptUpload"
                  class="hidden"
                />
                <div class="flex items-center space-x-2">
                  <button
                    type="button"
                    @click="$refs.receiptFileRef.click()"
                    class="px-3 py-1.5 rounded-xl bg-[#4f46e5] hover:bg-[#4338ca] text-white text-xs font-semibold shadow-md transition"
                  >
                    Choose file
                  </button>
                  <span class="text-slate-500 text-[11px] truncate max-w-[150px]">
                    {{ receiptFileName || 'No file chosen' }}
                  </span>
                </div>
                <button
                  v-if="cashForm.receipt_path"
                  type="button"
                  @click="clearReceipt"
                  class="text-[10px] text-rose-400 hover:underline block pt-1"
                >
                  Hapus File
                </button>
              </div>
            </div>
          </div>

          <!-- Bottom Actions (Image 3 Match) -->
          <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200">
            <button
              type="button"
              @click="showAddModal = false"
              class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 font-semibold transition"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="submitting"
              class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold shadow-lg shadow-emerald-950/60 transition disabled:opacity-50"
            >
              {{ submitting ? 'Menyimpan...' : 'Simpan Transaksi' }}
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
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import DashboardLayout from '../../layouts/DashboardLayout.vue';
import { useAuthStore } from '../../stores/auth';
import { useRealtimeStore } from '../../stores/realtime';
import api from '../../services/api';
import { downloadAuthenticatedFile } from '../../utils/fileDownloader';

const authStore = useAuthStore();
const realtimeStore = useRealtimeStore();
const transactions = ref([]);
const categories = ref([]);
const summary = ref({ total_in: 0, total_out: 0, balance: 0 });
const filters = ref({ type: '', category_id: '', search: '' });

const showAddModal = ref(false);
const showImportModal = ref(false);
const submitting = ref(false);
const exporting = ref(false);
const importing = ref(false);
const receiptFileRef = ref(null);
const importFileRef = ref(null);
const receiptFileName = ref('');

const cashForm = ref({
  type: 'in',
  amount: '',
  date: new Date().toISOString().split('T')[0],
  category_id: null,
  description: '',
  receipt_path: '',
});

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
    transactions.value = res.data.data;
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
  clearReceipt();
  cashForm.value = {
    type: 'in',
    amount: '',
    date: new Date().toISOString().split('T')[0],
    category_id: categories.value[0]?.id || null,
    description: '',
    receipt_path: '',
  };
  showAddModal.value = true;
};

const submitCash = async () => {
  submitting.value = true;
  try {
    await api.post('/cash', cashForm.value);
    showAddModal.value = false;
    fetchTransactions();
    fetchSummary();
    realtimeStore.fetchLiveStats();
    alert('Transaksi kas berhasil dicatat!');
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan transaksi kas.');
  } finally {
    submitting.value = false;
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
      `Rekap_Kas_Rojabiyyah_${today}.xlsx`,
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

onMounted(() => {
  fetchTransactions();
  fetchSummary();
  fetchCategories();
});
</script>
