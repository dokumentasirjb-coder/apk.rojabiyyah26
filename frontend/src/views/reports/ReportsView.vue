<template>
  <DashboardLayout>
    <div class="space-y-6">
      <!-- Title & Actions -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <h2 class="text-xl font-bold text-slate-900">Pelaporan Lapangan & Posko</h2>
          <p class="text-xs text-slate-500">Monitoring kondisi operasional posko, respon cepat kendala, dan dokumentasi tindak lanjut.</p>
        </div>

        <button
          @click="openReportModal"
          class="inline-flex items-center space-x-1.5 px-4 py-2 bg-emerald-700 hover:bg-emerald-800 text-white font-semibold text-xs rounded-xl shadow-sm transition"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
          <span>Buat Laporan Baru</span>
        </button>
      </div>

      <!-- Filters -->
      <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-200 flex flex-wrap gap-3 items-center text-xs">
        <select v-model="filters.region_id" @change="fetchReports" class="px-3 py-2 border border-slate-300 rounded-xl">
          <option value="">Semua Posko</option>
          <option v-for="r in regions" :key="r.id" :value="r.id">{{ r.name }}</option>
        </select>

        <select v-model="filters.status" @change="fetchReports" class="px-3 py-2 border border-slate-300 rounded-xl">
          <option value="">Semua Kondisi</option>
          <option value="normal">Normal</option>
          <option value="perlu_perhatian">Perlu Perhatian</option>
          <option value="rusak_kendala">Kendala / Rusak</option>
        </select>

        <select v-model="filters.followup_status" @change="fetchReports" class="px-3 py-2 border border-slate-300 rounded-xl">
          <option value="">Semua Status Respon</option>
          <option value="open">Open (Menunggu Respon)</option>
          <option value="in_progress">In Progress (Ditindaklanjuti)</option>
          <option value="resolved">Resolved (Selesai)</option>
        </select>
      </div>

      <!-- Reports Timeline / Cards List -->
      <div class="space-y-4">
        <div
          v-for="rep in reports"
          :key="rep.id"
          class="bg-white rounded-3xl p-5 shadow-sm border border-slate-200 hover:border-emerald-300 transition"
        >
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-3 border-b border-slate-100 gap-2">
            <div class="flex items-center space-x-3">
              <span :class="getStatusBadgeClass(rep.status)" class="px-3 py-1 font-bold rounded-full text-[10px] uppercase">
                {{ rep.status.replace('_', ' ') }}
              </span>
              <h3 class="font-bold text-slate-800 text-sm">{{ rep.region?.name }}</h3>
            </div>
            <div class="flex items-center space-x-2 text-xs text-slate-400">
              <span>Oleh: <strong>{{ rep.reporter?.name }}</strong></span>
              <span>&bull;</span>
              <span>{{ formatDate(rep.created_at) }}</span>
            </div>
          </div>

          <div class="py-3 text-xs text-slate-700 leading-relaxed whitespace-pre-line">
            {{ rep.description }}
          </div>

          <!-- Media Proof -->
          <div v-if="rep.media?.length" class="flex flex-wrap gap-2 py-2">
            <div v-for="m in rep.media" :key="m.id" class="relative group">
              <img :src="m.path" class="w-20 h-20 object-cover rounded-xl border border-slate-200" />
            </div>
          </div>

          <!-- Followups Section -->
          <div v-if="rep.followups?.length" class="mt-3 pt-3 border-t border-slate-100 space-y-2">
            <div class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tindak Lanjut & Solusi:</div>
            <div v-for="f in rep.followups" :key="f.id" class="p-3 bg-slate-50 rounded-xl border border-slate-200 text-xs">
              <div class="flex items-center justify-between font-semibold text-slate-800 mb-1">
                <span>{{ f.user?.name }} ({{ f.status.toUpperCase() }})</span>
                <span class="text-[10px] text-slate-400 font-normal">{{ formatDate(f.created_at) }}</span>
              </div>
              <p class="text-slate-600">{{ f.solution }}</p>
            </div>
          </div>

          <!-- Actions: Add Followup -->
          <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between text-xs">
            <div class="flex items-center space-x-2">
              <span class="text-slate-400">Status Respon:</span>
              <span :class="getFollowupBadgeClass(rep.followup_status)" class="px-2.5 py-0.5 rounded-full font-bold text-[10px] uppercase">
                {{ rep.followup_status }}
              </span>
            </div>

            <button
              v-if="['super_admin', 'admin', 'pic_posko'].includes(authStore.user?.role)"
              @click="openFollowupModal(rep)"
              class="px-3 py-1.5 bg-slate-100 hover:bg-emerald-50 hover:text-emerald-700 text-slate-700 rounded-xl font-semibold transition"
            >
              + Tambah Tindak Lanjut
            </button>
          </div>
        </div>

        <div v-if="reports.length === 0" class="bg-white rounded-3xl p-10 text-center text-xs text-slate-400 border border-slate-200">
          Belum ada laporan lapangan yang sesuai dengan filter.
        </div>
      </div>

      <!-- Create Report Modal -->
      <div v-if="showReportModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-100 text-xs">
          <h3 class="text-base font-bold text-slate-800 mb-4">Buat Laporan Lapangan Baru</h3>
          <form @submit.prevent="submitReport" class="space-y-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Pilih Posko / Wilayah *</label>
              <select v-model="reportForm.region_id" required class="w-full px-3 py-2 border border-slate-300 rounded-xl">
                <option :value="null">Pilih Posko</option>
                <option v-for="r in regions" :key="r.id" :value="r.id">{{ r.name }}</option>
              </select>
            </div>

            <div>
              <label class="block font-semibold text-slate-700 mb-1">Status Kondisi *</label>
              <select v-model="reportForm.status" required class="w-full px-3 py-2 border border-slate-300 rounded-xl">
                <option value="normal">Normal (Kondisi Terkendali & Lancar)</option>
                <option value="perlu_perhatian">Perlu Perhatian (Hampir Habis / Peringatan)</option>
                <option value="rusak_kendala">Kendala / Rusak (Membutuhkan Bantuan Segera)</option>
              </select>
            </div>

            <div>
              <label class="block font-semibold text-slate-700 mb-1">Uraian Detail Laporan *</label>
              <textarea v-model="reportForm.description" required rows="3" placeholder="Jelaskan kondisi di lapangan secara jelas dan ringkas..." class="w-full px-3 py-2 border border-slate-300 rounded-xl"></textarea>
            </div>

            <div>
              <label class="block font-semibold text-slate-700 mb-1">Foto Bukti Lapangan / Kondisi</label>
              <input
                type="file"
                ref="fileInputRef"
                accept="image/*"
                capture="environment"
                @change="handlePhotoUpload"
                class="hidden"
              />

              <div v-if="reportPhotoPreview" class="relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-50 mb-2">
                <img :src="reportPhotoPreview" alt="Bukti Foto" class="w-full max-h-48 object-contain" />
                <button
                  type="button"
                  @click="reportPhotoPreview = ''"
                  class="absolute top-2 right-2 px-2.5 py-1 rounded-lg bg-rose-600 text-white font-bold text-[10px] hover:bg-rose-700 transition"
                >
                  Hapus Foto
                </button>
              </div>

              <button
                v-else
                type="button"
                @click="$refs.fileInputRef?.click()"
                class="w-full py-3.5 border-2 border-dashed border-slate-300 hover:border-emerald-500 rounded-2xl bg-slate-50 hover:bg-emerald-50/40 transition flex flex-col items-center justify-center space-y-1 text-slate-600"
              >
                <div class="text-xl">📷</div>
                <span class="font-bold text-xs text-emerald-800">Ambil Foto Kamera / Pilih dari Galeri</span>
                <span class="text-[10px] text-slate-400">Format JPG, PNG, WEBP (maks 10MB)</span>
              </button>
            </div>

            <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100">
              <button type="button" @click="showReportModal = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl font-semibold">Batal</button>
              <button type="submit" class="px-5 py-2 bg-emerald-700 text-white rounded-xl font-semibold">Kirim Laporan</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Followup Modal -->
      <div v-if="showFollowupModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4">
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-100 text-xs">
          <h3 class="text-base font-bold text-slate-800 mb-4">Tindak Lanjut & Respon Laporan</h3>
          <form @submit.prevent="submitFollowup" class="space-y-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Perbarui Status Penanganan *</label>
              <select v-model="followupForm.status" required class="w-full px-3 py-2 border border-slate-300 rounded-xl">
                <option value="in_progress">In Progress (Sedang Ditangani)</option>
                <option value="resolved">Resolved (Kendala Teratasi / Selesai)</option>
                <option value="open">Open (Tetap Buka)</option>
              </select>
            </div>

            <div>
              <label class="block font-semibold text-slate-700 mb-1">Solusi / Catatan Penanganan *</label>
              <textarea v-model="followupForm.solution" required rows="3" placeholder="Jelaskan tindakan yang telah diambil atau personil cadangan yang dikirim..." class="w-full px-3 py-2 border border-slate-300 rounded-xl"></textarea>
            </div>

            <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100">
              <button type="button" @click="showFollowupModal = false" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl font-semibold">Batal</button>
              <button type="submit" class="px-5 py-2 bg-emerald-700 text-white rounded-xl font-semibold">Simpan Respon</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import DashboardLayout from '../../layouts/DashboardLayout.vue';
import { useAuthStore } from '../../stores/auth';
import { useRealtimeStore } from '../../stores/realtime';
import api from '../../services/api';

const authStore = useAuthStore();
const realtimeStore = useRealtimeStore();
const reports = ref([]);
const regions = ref([]);
const filters = ref({ region_id: '', status: '', followup_status: '' });
let reportPollTimer = null;

// Modals
const showReportModal = ref(false);
const reportForm = ref({ region_id: null, status: 'normal', description: '' });
const reportPhotoPreview = ref('');
const fileInputRef = ref(null);

const handlePhotoUpload = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  if (file.size > 10 * 1024 * 1024) {
    alert('Ukuran foto terlalu besar. Maksimal 10MB.');
    return;
  }
  const reader = new FileReader();
  reader.onload = (event) => {
    reportPhotoPreview.value = event.target.result;
  };
  reader.readAsDataURL(file);
};

const showFollowupModal = ref(false);
const followupForm = ref({ status: 'resolved', solution: '' });
const activeReport = ref(null);

const fetchReports = async () => {
  try {
    const res = await api.get('/reports', { params: filters.value });
    reports.value = res.data.data;
  } catch (e) {
    //
  }
};

const fetchRegions = async () => {
  try {
    const res = await api.get('/regions');
    regions.value = res.data;
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
    hour: '2-digit',
    minute: '2-digit',
  });
};

const getStatusBadgeClass = (status) => {
  const map = {
    'normal': 'bg-emerald-100 text-emerald-800',
    'perlu_perhatian': 'bg-amber-100 text-amber-800',
    'rusak_kendala': 'bg-rose-100 text-rose-800',
  };
  return map[status] || 'bg-slate-100 text-slate-800';
};

const getFollowupBadgeClass = (status) => {
  const map = {
    'open': 'bg-rose-100 text-rose-800',
    'in_progress': 'bg-blue-100 text-blue-800',
    'resolved': 'bg-emerald-100 text-emerald-800',
  };
  return map[status] || 'bg-slate-100 text-slate-800';
};

const openReportModal = () => {
  reportForm.value = { region_id: null, status: 'normal', description: '' };
  reportPhotoPreview.value = '';
  showReportModal.value = true;
};

const submitReport = async () => {
  try {
    const payload = { ...reportForm.value };
    if (reportPhotoPreview.value) {
      payload.media = [{ path: reportPhotoPreview.value, type: 'photo' }];
    }
    await api.post('/reports', payload);
    showReportModal.value = false;
    alert('Laporan kondisi lapangan dan bukti foto berhasil dikirim!');
    fetchReports();
    realtimeStore.fetchLiveStats();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal mengirim laporan.');
  }
};

const openFollowupModal = (rep) => {
  activeReport.value = rep;
  followupForm.value = { status: 'resolved', solution: '' };
  showFollowupModal.value = true;
};

const submitFollowup = async () => {
  try {
    await api.post(`/reports/${activeReport.value.id}/followups`, followupForm.value);
    showFollowupModal.value = false;
    fetchReports();
    realtimeStore.fetchLiveStats();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan respon.');
  }
};

onMounted(() => {
  fetchReports();
  fetchRegions();
});
</script>

