<template>
  <div class="min-h-screen bg-slate-50 flex flex-col items-center justify-center p-4 selection:bg-emerald-500 selection:text-white">
    <div class="max-w-xl w-full bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
      <!-- Header -->
      <div class="bg-gradient-to-r from-emerald-700 via-teal-700 to-emerald-800 p-6 text-white text-center">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-white/15 backdrop-blur border border-white/20 mb-3 shadow-sm">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h2 class="text-xl font-bold tracking-tight">Verifikasi Keabsahan &amp; Kepemilikan Barang</h2>
        <p class="text-xs text-emerald-100 mt-1">Sistem Administrasi Haul Ke-5 &amp; Rojabiyyah 2026</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="p-8 text-center text-slate-500">
        <div class="animate-spin inline-block w-8 h-8 border-4 border-emerald-600 border-t-transparent rounded-full mb-2"></div>
        <p class="text-sm font-medium">Memeriksa keabsahan surat &amp; kepemilikan barang di server...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="p-8 text-center">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-rose-50 text-rose-600 border border-rose-200 mb-3">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 class="text-base font-bold text-slate-800 mb-1">Dokumen Tidak Ditemukan</h3>
        <p class="text-xs text-slate-500 mb-6">{{ error }}</p>
        <router-link to="/" class="px-5 py-2.5 bg-slate-900 text-white rounded-xl text-xs font-semibold hover:bg-slate-800 transition">
          Kembali ke Beranda
        </router-link>
      </div>

      <!-- Verified Data -->
      <div v-else-if="loanData" class="p-6 sm:p-8 space-y-6">
        <!-- Surat & Status Bar -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
          <div>
            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-wider">Nomor Surat Jalan</span>
            <div class="text-base font-mono font-black text-emerald-700">{{ loanData.loan_no }}</div>
          </div>
          <span :class="getStatusBadgeClass(loanData.status)" class="px-3 py-1 text-xs font-mono font-bold rounded-full border">
            {{ loanData.status }}
          </span>
        </div>

        <!-- Peminjam & Tanggal -->
        <div class="grid grid-cols-2 gap-4 text-xs">
          <div>
            <span class="text-slate-500 font-medium">Peminjam / Penanggung Jawab:</span>
            <div class="font-bold text-slate-900 text-sm mt-0.5">{{ loanData.borrower_name }}</div>
            <div class="text-slate-500 font-mono text-[11px]">{{ loanData.member_no }} &bull; {{ loanData.division }}</div>
          </div>
          <div>
            <span class="text-slate-500 font-medium">Periode Peminjaman:</span>
            <div class="font-bold text-slate-900 text-sm mt-0.5 font-mono">{{ loanData.requested_at }}</div>
            <div class="text-amber-700 font-mono text-[11px] font-semibold">Tenggat Kembali: {{ loanData.due_at }}</div>
          </div>
        </div>

        <div>
          <span class="text-xs text-slate-500 font-medium">Keperluan Operasional:</span>
          <div class="text-xs text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-200 mt-1">
            {{ loanData.purpose }}
          </div>
        </div>

        <!-- Detail Kepemilikan Barang Inventaris -->
        <div>
          <div class="flex items-center justify-between mb-2">
            <span class="text-xs font-bold text-slate-800 uppercase tracking-wider flex items-center space-x-1.5">
              <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
              <span>Detail Barang &amp; Kepemilikan ({{ loanData.items?.length || 0 }} Unit):</span>
            </span>
          </div>

          <div class="space-y-2.5">
            <div
              v-for="(item, idx) in loanData.items"
              :key="idx"
              class="p-3.5 rounded-2xl border border-slate-200 bg-white shadow-sm space-y-2"
            >
              <div class="flex items-start justify-between gap-2">
                <div>
                  <div class="font-bold text-slate-900 text-sm">{{ item.item_name }}</div>
                  <div class="text-[11px] font-mono text-emerald-700 font-semibold mt-0.5">
                    Kode Unit: {{ item.item_code }}
                  </div>
                </div>
                <span class="px-2.5 py-1 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-bold font-mono text-xs flex-shrink-0">
                  {{ item.qty_approved }} Unit
                </span>
              </div>

              <!-- Ownership Pill / Status Kepemilikan -->
              <div class="pt-2 border-t border-slate-100 flex flex-wrap items-center justify-between gap-1 text-[11px]">
                <div class="flex items-center space-x-1.5">
                  <span class="text-slate-500">Asal Kepemilikan:</span>
                  <span
                    :class="item.source === 'mitra' ? 'bg-sky-50 text-sky-800 border-sky-200' : 'bg-emerald-50 text-emerald-800 border-emerald-200'"
                    class="px-2 py-0.5 rounded-full font-bold border text-[10px]"
                  >
                    {{ item.source_label }}
                  </span>
                </div>
                <div class="text-slate-600">
                  Pemilik: <strong class="text-slate-900">{{ item.owner_name }}</strong>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Official Seal Banner -->
        <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-200 flex items-center space-x-3 text-emerald-900 text-xs">
          <svg class="w-5 h-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          <div>
            <strong>Barang &amp; Surat Terverifikasi Sah.</strong>
            <p class="text-[11px] text-emerald-700 mt-0.5">Tercatat resmi dalam database logistik Haul &amp; Rojabiyyah 2026.</p>
          </div>
        </div>

        <div class="text-center pt-2">
          <router-link to="/" class="text-xs font-semibold text-slate-500 hover:text-emerald-700 transition">
            &larr; Kembali ke Beranda Utama
          </router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import api from '../../services/api';

const route = useRoute();
const loading = ref(true);
const error = ref('');
const loanData = ref(null);

const fetchVerification = async () => {
  loading.value = true;
  error.value = '';
  try {
    const res = await api.get(`/public/loans/verify/${route.params.code}`);
    loanData.value = res.data;
  } catch (err) {
    error.value = err.response?.data?.message || 'Kode verifikasi tidak valid atau dokumen tidak ditemukan.';
  } finally {
    loading.value = false;
  }
};

const getStatusBadgeClass = (status) => {
  const map = {
    'DISETUJUI': 'bg-blue-100 text-blue-800',
    'DISERAHKAN': 'bg-emerald-100 text-emerald-800',
    'DIKEMBALIKAN': 'bg-slate-100 text-slate-800',
    'DITOLAK': 'bg-rose-100 text-rose-800',
  };
  return map[status] || 'bg-amber-100 text-amber-800';
};

onMounted(() => {
  fetchVerification();
});
</script>

