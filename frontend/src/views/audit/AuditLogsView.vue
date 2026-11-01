<template>
  <DashboardLayout>
    <div class="space-y-6">
      <!-- Title -->
      <div>
        <h2 class="text-xl font-bold text-slate-900">Jejak Audit Sistem (Audit Log)</h2>
        <p class="text-xs text-slate-500">Rekaman seluruh aktivitas penting pengguna, perubahan data, persetujuan logistik, dan eksekusi sistem.</p>
      </div>

      <!-- Filters -->
      <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-wrap gap-3 items-center text-xs">
        <select v-model="filters.action" @change="fetchLogs" class="px-3 py-2 border border-slate-300 rounded-xl">
          <option value="">Semua Aksi</option>
          <option value="login">Login</option>
          <option value="logout">Logout</option>
          <option value="create">Tambah Data</option>
          <option value="update">Ubah Data</option>
          <option value="delete">Hapus Data</option>
          <option value="approve_loan">Persetujuan Peminjaman</option>
          <option value="handover_loan">Serah Terima</option>
          <option value="return_loan">Pengembalian</option>
          <option value="void">Pembatalan Kas (Void)</option>
          <option value="import">Impor Spreadsheet</option>
          <option value="danger_zone_reset">Reset Danger Zone</option>
        </select>

        <select v-model="filters.entity" @change="fetchLogs" class="px-3 py-2 border border-slate-300 rounded-xl">
          <option value="">Semua Entitas</option>
          <option value="User">User / Akun</option>
          <option value="Member">Anggota</option>
          <option value="Item">Barang Inventaris</option>
          <option value="Loan">Peminjaman</option>
          <option value="CashTransaction">Kas Keuangan</option>
          <option value="FieldReport">Laporan Lapangan</option>
          <option value="System">Sistem</option>
        </select>
      </div>

      <!-- Logs Table -->
      <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase font-semibold">
              <tr>
                <th class="py-3.5 px-4">Waktu</th>
                <th class="py-3.5 px-4">Pengguna</th>
                <th class="py-3.5 px-4">Aksi</th>
                <th class="py-3.5 px-4">Entitas & ID</th>
                <th class="py-3.5 px-4">Alamat IP</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <tr v-for="log in logs" :key="log.id" class="hover:bg-slate-50 transition">
                <td class="py-3.5 px-4 text-slate-500 whitespace-nowrap">
                  {{ formatDate(log.created_at) }}
                </td>
                <td class="py-3.5 px-4">
                  <div class="font-bold text-slate-800">{{ log.user?.name || 'Sistem' }}</div>
                  <div class="text-[10px] text-slate-400">{{ log.user?.member_no || log.user?.email || '-' }}</div>
                </td>
                <td class="py-3.5 px-4">
                  <span :class="getActionBadgeClass(log.action)" class="px-2 py-0.5 rounded font-bold text-[10px] uppercase">
                    {{ log.action }}
                  </span>
                </td>
                <td class="py-3.5 px-4 text-slate-700">
                  <span class="font-semibold">{{ log.entity }}</span>
                  <span v-if="log.entity_id" class="text-slate-400 text-[11px]"> #{{ log.entity_id }}</span>
                </td>
                <td class="py-3.5 px-4 font-mono text-slate-500 text-[11px]">
                  {{ log.ip || '-' }}
                </td>
              </tr>
              <tr v-if="logs.length === 0">
                <td colspan="5" class="py-8 text-center text-slate-400">
                  Belum ada log aktivitas yang tercatat.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import DashboardLayout from '../../layouts/DashboardLayout.vue';
import api from '../../services/api';

const logs = ref([]);
const filters = ref({ action: '', entity: '' });

const fetchLogs = async () => {
  try {
    const res = await api.get('/audit-logs', { params: filters.value });
    logs.value = res.data.data;
  } catch (e) {
    //
  }
};

const formatDate = (dateStr) => {
  if (!dateStr) return '-';
  const d = new Date(dateStr);
  return d.toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
  });
};

const getActionBadgeClass = (action) => {
  if (['create', 'import'].includes(action)) return 'bg-emerald-100 text-emerald-800';
  if (['update', 'change_password'].includes(action)) return 'bg-blue-100 text-blue-800';
  if (['delete', 'void', 'danger_zone_reset'].includes(action)) return 'bg-rose-100 text-rose-800';
  if (['approve_loan', 'handover_loan'].includes(action)) return 'bg-teal-100 text-teal-800';
  return 'bg-slate-100 text-slate-700';
};

onMounted(() => {
  fetchLogs();
});
</script>

