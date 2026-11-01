<template>
  <DashboardLayout>
    <div class="space-y-6">
      <!-- 1. Page Header & Actions -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <div class="flex items-center space-x-2.5">
            <div class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
              </svg>
            </div>
            <h2 class="text-xl font-bold text-slate-800 tracking-tight">Kelola Divisi Tugas Kepanitiaan</h2>
          </div>
          <p class="text-xs text-slate-500 mt-1">
            Atur dan kelola daftar divisi kepanitiaan Haul &amp; Rojabiyyah 2026. Perubahan nama divisi otomatis sinkron ke formulir anggota dan filter sistem.
          </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
          <!-- Back to Settings -->
          <router-link
            to="/settings"
            class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold shadow-sm transition"
          >
            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Ke Pengaturan Sistem</span>
          </router-link>

          <!-- Back to Members -->
          <router-link
            to="/members"
            class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold shadow-sm transition"
          >
            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <circle cx="9" cy="7" r="4" stroke-width="2"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-2a4 4 0 014-4h4a4 4 0 014 4v2"/>
            </svg>
            <span>Data Anggota</span>
          </router-link>

          <!-- Add Division Button -->
          <button
            @click="openAddModal"
            class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white text-xs font-bold shadow-md shadow-emerald-500/20 transition active:scale-95"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>+ Tambah Divisi Baru</span>
          </button>
        </div>
      </div>

      <!-- 2. KPI Cards -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
        <!-- Card 1: Total Divisi -->
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-sm flex items-center justify-between">
          <div>
            <span class="text-[10px] font-mono text-slate-500 uppercase tracking-wider block font-semibold">Total Divisi Terdaftar</span>
            <div class="text-2xl font-black text-slate-900 mt-1 font-mono">{{ divisions.length }}</div>
            <span class="text-[11px] text-slate-400">Struktur panitia haul</span>
          </div>
          <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
            </svg>
          </div>
        </div>

        <!-- Card 2: Total Anggota Terplotting -->
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-sm flex items-center justify-between">
          <div>
            <span class="text-[10px] font-mono text-slate-500 uppercase tracking-wider block font-semibold">Anggota Terplotting</span>
            <div class="text-2xl font-black text-emerald-600 mt-1 font-mono">{{ totalMembersInDivisions }}</div>
            <span class="text-[11px] text-slate-400">Dari total anggota aktif</span>
          </div>
          <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <circle cx="9" cy="7" r="4" stroke-width="2"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-2a4 4 0 014-4h4a4 4 0 014 4v2"/>
            </svg>
          </div>
        </div>

        <!-- Card 3: Rata-rata Anggota -->
        <div class="p-4 rounded-2xl bg-white border border-slate-200 shadow-sm flex items-center justify-between">
          <div>
            <span class="text-[10px] font-mono text-slate-500 uppercase tracking-wider block font-semibold">Rata-Rata Personil</span>
            <div class="text-2xl font-black text-indigo-600 mt-1 font-mono">{{ averageMembersPerDiv }}</div>
            <span class="text-[11px] text-slate-400">Anggota per divisi</span>
          </div>
          <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-200 text-indigo-600 flex items-center justify-center">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
          </div>
        </div>
      </div>

      <!-- 3. Division Table Card -->
      <div class="bg-white rounded-2xl sm:rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
        <!-- Search & Control Header -->
        <div class="p-4 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/50">
          <div class="relative w-full sm:w-72">
            <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <circle cx="11" cy="11" r="7" stroke-width="2"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35"/>
            </svg>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari nama divisi..."
              class="w-full pl-9 pr-3.5 py-2 bg-white border border-slate-200 rounded-xl text-slate-800 placeholder-slate-400 text-xs focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
            />
          </div>

          <div class="flex items-center space-x-2 text-xs text-slate-500">
            <span>Menampilkan <strong>{{ filteredDivisions.length }}</strong> divisi</span>
            <button
              @click="fetchDivisions"
              class="p-1.5 rounded-lg bg-white border border-slate-200 hover:bg-slate-100 text-slate-600 transition shadow-sm"
              title="Segarkan data"
            >
              <svg class="w-3.5 h-3.5" :class="{ 'animate-spin': loading }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
              </svg>
            </button>
          </div>
        </div>

        <!-- Table Content -->
        <div class="overflow-x-auto">
          <table class="w-full text-left text-xs border-collapse">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-600 uppercase font-mono text-[10px] tracking-wider font-bold">
              <tr>
                <th class="py-3 px-4 w-12 text-center">#</th>
                <th class="py-3 px-4 min-w-[200px]">NAMA DIVISI TUGAS</th>
                <th class="py-3 px-4 min-w-[280px]">DESKRIPSI / RINCIAN TANGGUNG JAWAB</th>
                <th class="py-3 px-4 w-32 text-center">JUMLAH ANGGOTA</th>
                <th class="py-3 px-4 w-28 text-right">AKSI</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
              <!-- Loading State -->
              <tr v-if="loading">
                <td colspan="5" class="py-12 text-center text-slate-400">
                  <div class="inline-block w-6 h-6 border-2 border-emerald-600 border-t-transparent rounded-full animate-spin mb-2"></div>
                  <p class="text-xs">Memuat daftar divisi...</p>
                </td>
              </tr>

              <!-- Empty State -->
              <tr v-else-if="filteredDivisions.length === 0">
                <td colspan="5" class="py-12 text-center text-slate-400">
                  <svg class="w-10 h-10 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                  </svg>
                  <p class="font-semibold text-slate-700">Belum Ada Divisi Ditemukan</p>
                  <p class="text-[11px] text-slate-400 mt-0.5">
                    {{ searchQuery ? 'Tidak ada hasil untuk "' + searchQuery + '"' : 'Klik tombol "+ Tambah Divisi Baru" untuk membuat divisi pertama.' }}
                  </p>
                </td>
              </tr>

              <!-- Row Items -->
              <tr
                v-for="(div, index) in filteredDivisions"
                :key="div.id"
                class="hover:bg-slate-50/80 transition group"
              >
                <!-- Number -->
                <td class="py-3.5 px-4 text-center font-mono text-slate-400 font-bold text-[11px]">
                  {{ index + 1 }}
                </td>

                <!-- Division Name -->
                <td class="py-3.5 px-4">
                  <div class="flex items-center space-x-2.5">
                    <div class="w-7 h-7 rounded-lg bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                      {{ div.name.charAt(0) }}
                    </div>
                    <div>
                      <span class="font-bold text-slate-800 text-xs block group-hover:text-emerald-700 transition">
                        {{ div.name }}
                      </span>
                      <span class="text-[10px] font-mono text-slate-400">ID: #{{ div.id }}</span>
                    </div>
                  </div>
                </td>

                <!-- Description -->
                <td class="py-3.5 px-4 text-slate-600">
                  <p v-if="div.description" class="line-clamp-2 leading-relaxed text-slate-600">
                    {{ div.description }}
                  </p>
                  <span v-else class="text-slate-400 italic text-[11px]">- Tidak ada deskripsi khusus -</span>
                </td>

                <!-- Members Count -->
                <td class="py-3.5 px-4 text-center">
                  <router-link
                    :to="'/members?division_id=' + div.id"
                    class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-700 hover:bg-emerald-100 transition font-mono font-bold text-[11px]"
                    title="Lihat anggota di divisi ini"
                  >
                    <span>{{ div.members_count || 0 }}</span>
                    <span class="text-[10px] text-emerald-600/80 font-sans">Orang</span>
                  </router-link>
                </td>

                <!-- Actions -->
                <td class="py-3.5 px-4 text-right">
                  <div class="inline-flex items-center space-x-1.5">
                    <!-- Edit Button -->
                    <button
                      @click="openEditModal(div)"
                      class="p-1.5 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-600 hover:text-slate-900 border border-slate-200 transition shadow-xs"
                      title="Edit Nama / Deskripsi Divisi"
                    >
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                      </svg>
                    </button>

                    <!-- Delete Button -->
                    <button
                      @click="confirmDelete(div)"
                      class="p-1.5 rounded-lg bg-rose-50 hover:bg-rose-100 text-rose-600 border border-rose-200 transition shadow-xs"
                      title="Hapus Divisi"
                    >
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- 4. MODAL: Tambah / Edit Divisi -->
      <div
        v-if="showModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4 animate-in fade-in"
      >
        <div class="w-full max-w-lg bg-white border border-slate-200 rounded-3xl p-6 shadow-2xl text-slate-800">
          <!-- Modal Header -->
          <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
            <div class="flex items-center space-x-2.5">
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
              <h3 class="text-base font-bold text-slate-800">
                {{ isEditing ? 'Edit Divisi Tugas' : 'Tambah Divisi Tugas Baru' }}
              </h3>
            </div>
            <button @click="showModal = false" class="text-slate-400 hover:text-slate-600 transition p-1">
              ✕
            </button>
          </div>

          <!-- Modal Form -->
          <form @submit.prevent="saveDivision" class="space-y-4 text-xs">
            <div>
              <label class="block font-semibold text-slate-700 mb-1.5">
                Nama Divisi Panitia *
              </label>
              <input
                v-model="form.name"
                type="text"
                required
                placeholder="misal: Divisi Dokumentasi & Multimedia"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
              />
              <p class="text-[10px] text-slate-400 mt-1">
                Nama ini akan muncul di dropdown pilihan anggota dan formulir registrasi panitia.
              </p>
            </div>

            <div>
              <label class="block font-semibold text-slate-700 mb-1.5">
                Deskripsi / Lingkup Tanggung Jawab <span class="text-slate-400 font-normal">(Opsional)</span>
              </label>
              <textarea
                v-model="form.description"
                rows="3"
                placeholder="misal: Bertanggung jawab atas peliputan momen haul, multicam shooting, live streaming, dan dokumentasi foto/video..."
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition leading-relaxed"
              ></textarea>
            </div>

            <!-- Error Banner -->
            <div v-if="errorMessage" class="p-3 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 text-xs">
              {{ errorMessage }}
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
              <button
                type="button"
                @click="showModal = false"
                :disabled="saving"
                class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition"
              >
                Batal
              </button>
              <button
                type="submit"
                :disabled="saving"
                class="px-5 py-2 bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold rounded-xl text-xs transition flex items-center space-x-1.5 shadow-md shadow-emerald-500/20 disabled:opacity-50"
              >
                <span v-if="saving" class="inline-block w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
                <span>{{ saving ? 'Menyimpan...' : (isEditing ? 'Simpan Perubahan' : 'Tambahkan Divisi') }}</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- 5. MODAL: Konfirmasi Hapus Divisi -->
      <div
        v-if="showDeleteModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/40 backdrop-blur-sm p-4 animate-in fade-in"
      >
        <div class="w-full max-w-md bg-white border border-slate-200 rounded-3xl p-6 shadow-2xl text-slate-800">
          <div class="w-12 h-12 rounded-2xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center mx-auto mb-3 text-2xl">
            ⚠️
          </div>
          <h3 class="text-base font-bold text-slate-800 text-center">Hapus Divisi Ini?</h3>
          <p class="text-xs text-slate-600 text-center mt-2 leading-relaxed">
            Anda akan menghapus divisi <strong class="text-slate-900 font-bold">"{{ selectedDivision?.name }}"</strong>.
          </p>

          <div v-if="selectedDivision?.members_count > 0" class="mt-3 p-3 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs">
            <strong>Perhatian:</strong> Terdapat <strong>{{ selectedDivision.members_count }} anggota</strong> yang terdaftar di divisi ini. Jika dihapus, status divisi anggota tersebut akan dikosongkan (belum terplotting) tanpa menghapus data atau akun mereka.
          </div>

          <div class="flex items-center justify-center space-x-3 mt-6 pt-4 border-t border-slate-100">
            <button
              @click="showDeleteModal = false"
              :disabled="deleting"
              class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl text-xs font-semibold transition"
            >
              Batal
            </button>
            <button
              @click="executeDelete"
              :disabled="deleting"
              class="px-5 py-2 bg-rose-600 hover:bg-rose-700 text-white font-bold rounded-xl text-xs transition flex items-center space-x-1.5 shadow-md shadow-rose-600/20 disabled:opacity-50"
            >
              <span v-if="deleting" class="inline-block w-3.5 h-3.5 border-2 border-white border-t-transparent rounded-full animate-spin"></span>
              <span>{{ deleting ? 'Menghapus...' : 'Ya, Hapus Divisi' }}</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import DashboardLayout from '../../layouts/DashboardLayout.vue';
import api from '../../services/api';

const divisions = ref([]);
const loading = ref(false);
const saving = ref(false);
const deleting = ref(false);
const searchQuery = ref('');

const showModal = ref(false);
const isEditing = ref(false);
const editingId = ref(null);
const errorMessage = ref('');

const showDeleteModal = ref(false);
const selectedDivision = ref(null);

const form = ref({
  name: '',
  description: '',
});

// KPI Computations
const totalMembersInDivisions = computed(() => {
  return divisions.value.reduce((acc, curr) => acc + (curr.members_count || 0), 0);
});

const averageMembersPerDiv = computed(() => {
  if (divisions.value.length === 0) return 0;
  return Math.round(totalMembersInDivisions.value / divisions.value.length);
});

const filteredDivisions = computed(() => {
  const q = (searchQuery.value || '').toLowerCase().trim();
  if (!q) return divisions.value;
  return divisions.value.filter(
    (d) =>
      d.name.toLowerCase().includes(q) ||
      (d.description && d.description.toLowerCase().includes(q))
  );
});

const fetchDivisions = async () => {
  loading.value = true;
  try {
    const res = await api.get('/divisions');
    divisions.value = res.data;
  } catch (err) {
    console.error('Gagal memuat divisi:', err);
  } finally {
    loading.value = false;
  }
};

const openAddModal = () => {
  isEditing.value = false;
  editingId.value = null;
  errorMessage.value = '';
  form.value = {
    name: '',
    description: '',
  };
  showModal.value = true;
};

const openEditModal = (div) => {
  isEditing.value = true;
  editingId.value = div.id;
  errorMessage.value = '';
  form.value = {
    name: div.name,
    description: div.description || '',
  };
  showModal.value = true;
};

const saveDivision = async () => {
  saving.value = true;
  errorMessage.value = '';
  try {
    if (isEditing.value) {
      await api.put(`/divisions/${editingId.value}`, form.value);
    } else {
      await api.post('/divisions', form.value);
    }
    showModal.value = false;
    await fetchDivisions();
  } catch (err) {
    if (err.response?.data?.message) {
      errorMessage.value = err.response.data.message;
    } else {
      errorMessage.value = 'Gagal menyimpan divisi. Pastikan nama tidak duplikat.';
    }
  } finally {
    saving.value = false;
  }
};

const confirmDelete = (div) => {
  selectedDivision.value = div;
  showDeleteModal.value = true;
};

const executeDelete = async () => {
  if (!selectedDivision.value) return;
  deleting.value = true;
  try {
    await api.delete(`/divisions/${selectedDivision.value.id}`);
    showDeleteModal.value = false;
    selectedDivision.value = null;
    await fetchDivisions();
  } catch (err) {
    console.error('Gagal menghapus divisi:', err);
    alert('Gagal menghapus divisi: ' + (err.response?.data?.message || err.message));
  } finally {
    deleting.value = false;
  }
};

onMounted(() => {
  fetchDivisions();
});
</script>

