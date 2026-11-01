<template>
  <div class="min-h-screen bg-slate-100 flex flex-col items-center justify-center p-4">
    <div class="max-w-xl w-full bg-white rounded-3xl shadow-xl border border-slate-200 overflow-hidden">
      <!-- Header -->
      <div class="bg-gradient-to-r from-emerald-800 to-teal-700 p-6 text-white text-center">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-white/20 mb-3">
          <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <h2 class="text-xl font-bold">Verifikasi Dokumen Resmi</h2>
        <p class="text-xs text-emerald-100 mt-1">Sistem Administrasi Rojabiyyah 2026</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="p-8 text-center text-slate-500">
        <div class="animate-spin inline-block w-8 h-8 border-4 border-emerald-600 border-t-transparent rounded-full mb-2"></div>
        <p class="text-sm">Memeriksa keabsahan surat di server...</p>
      </div>

      <!-- Error State -->
      <div v-else-if="error" class="p-8 text-center">
        <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-rose-100 text-rose-600 mb-3">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 class="text-base font-bold text-slate-800 mb-1">Surat Tidak Ditemukan</h3>
        <p class="text-xs text-slate-500 mb-6">{{ error }}</p>
        <router-link to="/" class="px-5 py-2 bg-slate-900 text-white rounded-xl text-xs font-semibold hover:bg-slate-800 transition">
          Kembali ke Beranda
        </router-link>
      </div>

      <!-- Verified Data -->
      <div v-else-if="loanData" class="p-6 sm:p-8 space-y-6">
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
          <div>
            <span class="text-xs text-slate-400 font-semibold uppercase">Nomor Surat</span>
            <div class="text-base font-bold text-slate-800">{{ loanData.loan_no }}</div>
          </div>
          <span :class="getStatusBadgeClass(loanData.status)" class="px-3 py-1 text-xs font-bold rounded-full">
            {{ loanData.status }}
          </span>
        </div>

        <div class="grid grid-cols-2 gap-4 text-xs">
          <div>
            <span class="text-slate-400 font-medium">Peminjam:</span>
            <div class="font-bold text-slate-800 text-sm mt-0.5">{{ loanData.borrower_name }}</div>
            <div class="text-slate-500">{{ loanData.member_no }} &bull; {{ loanData.division }}</div>
          </div>
          <div>
            <span class="text-slate-400 font-medium">Waktu Pinjam:</span>
            <div class="font-bold text-slate-800 text-sm mt-0.5">{{ loanData.requested_at }}</div>
            <div class="text-slate-500">Rencana Kembali: {{ loanData.due_at }}</div>
          </div>
        </div>

        <div>
          <span class="text-xs text-slate-400 font-medium">Keperluan:</span>
          <div class="text-xs text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100 mt-1">
            {{ loanData.purpose }}
          </div>
        </div>

        <div>
          <span class="text-xs font-bold text-slate-700 mb-2 block">Daftar Barang yang Disetujui:</span>
          <div class="divide-y divide-slate-100 border border-slate-100 rounded-xl overflow-hidden">
            <div v-for="(item, idx) in loanData.items" :key="idx" class="p-3 flex items-center justify-between text-xs bg-white">
              <span class="font-medium text-slate-800">{{ item.item_name }}</span>
              <span class="font-bold text-emerald-700">{{ item.qty_approved }} unit</span>
            </div>
          </div>
        </div>

        <div class="p-4 bg-emerald-50 rounded-2xl border border-emerald-100 flex items-center space-x-3 text-emerald-800 text-xs">
          <svg class="w-5 h-5 flex-shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
          <div>
            <strong>Dokumen ini Sah & Terdaftar Resmi.</strong>
            <p class="text-[11px] text-emerald-700 mt-0.5">Diterbitkan oleh Panitia Administrasi Rojabiyyah 2026.</p>
          </div>
        </div>

        <div class="text-center pt-2">
          <router-link to="/" class="text-xs font-semibold text-slate-500 hover:text-slate-800 transition">
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

