<template>
  <DashboardLayout>
    <div class="space-y-6">
      <!-- Title & Actions (Image 4 Match) -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <div class="flex items-center space-x-2.5">
            <div class="w-8 h-8 rounded-xl bg-teal-50 border border-slate-200 flex items-center justify-center text-teal-400">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
            </div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Kelola Foto Slider / Banner Halaman Depan</h2>
          </div>
          <p class="text-xs text-slate-500 mt-1">Unggah dan atur foto-foto dokumentasi Haul Ke-5 &amp; Rojabiyyah 2026 yang tampil berputar otomatis di halaman utama.</p>
        </div>

        <button
          v-if="['super_admin', 'admin'].includes(authStore.user?.role)"
          @click="openAddModal"
          class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white text-xs font-bold shadow-lg shadow-emerald-950/60 transition"
        >
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
          <span>+ Tambah Foto Slider Baru</span>
        </button>
      </div>

      <!-- Top Live Preview Card (Image 4 Match) -->
      <div class="bg-white rounded-3xl border border-slate-200 p-5 shadow-sm relative overflow-hidden">
        <div class="flex items-center justify-between mb-3 text-xs">
          <div class="flex items-center space-x-2 text-teal-400 font-bold uppercase tracking-wider text-[11px]">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            <span>LIVE PREVIEW TAMPILAN HALAMAN DEPAN:</span>
          </div>
          <span class="text-slate-500 font-mono text-[11px]">
            slide {{ currentSlideIndex + 1 }} dari {{ sliders.length }}
          </span>
        </div>

        <!-- Banner Preview Container -->
        <div class="relative w-full h-56 sm:h-72 md:h-80 rounded-2xl overflow-hidden bg-slate-900 border border-slate-200">
          <img
            v-if="currentSlider"
            :src="getSliderImageUrl(currentSlider.image_path)"
            :alt="currentSlider.title"
            @error="handleImageError($event, currentSlider)"
            class="w-full h-full object-cover transition-all duration-500"
          />
          <div class="absolute inset-0 bg-gradient-to-t from-[#070b14]/90 via-[#070b14]/30 to-transparent flex flex-col justify-end p-6">
            <h3 class="text-xl sm:text-2xl font-black text-slate-900 drop-shadow-md">
              {{ currentSlider?.title || 'Media Rojabiyyah 2026' }}
            </h3>
            <p class="text-xs sm:text-sm text-amber-300 font-medium drop-shadow-sm mt-1">
              {{ currentSlider?.description || 'Hormat Haul & Khidmah Bersama' }}
            </p>
          </div>

          <!-- Bottom slider indicators -->
          <div class="absolute bottom-4 right-4 flex items-center space-x-1.5 z-10">
            <button
              v-for="(s, idx) in sliders"
              :key="s.id"
              @click="currentSlideIndex = idx"
              :class="[
                'transition-all duration-300 rounded-full',
                currentSlideIndex === idx ? 'w-5 h-1.5 bg-amber-400' : 'w-1.5 h-1.5 bg-slate-500 hover:bg-slate-300'
              ]"
            ></button>
          </div>
        </div>
      </div>

      <!-- Section: Daftar Foto yang Terpasang (Image 4 Match) -->
      <div>
        <div class="flex items-center space-x-2 text-xs font-bold text-slate-700 mb-4">
          <svg class="w-4 h-4 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          <span>Daftar Foto yang Terpasang ({{ sliders.length }} Foto)</span>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
          <div
            v-for="(slider, index) in sliders"
            :key="slider.id"
            class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm flex flex-col justify-between hover:border-slate-300 transition"
          >
            <!-- Card Thumbnail with Badges -->
            <div class="relative h-44 bg-slate-900 overflow-hidden">
              <img
                :src="getSliderImageUrl(slider.image_path)"
                :alt="slider.title"
                @error="handleImageError($event, slider)"
                class="w-full h-full object-cover"
              />
              <!-- Top Badges -->
              <div class="absolute top-3 left-3">
                <span class="px-2.5 py-0.5 rounded-md bg-amber-500 text-slate-950 font-black text-[10px] tracking-wider shadow">
                  Urutan #{{ slider.sort_order || index + 1 }}
                </span>
              </div>
              <div class="absolute top-3 right-3">
                <span class="px-2.5 py-0.5 rounded-md bg-teal-500/80 text-white font-bold text-[10px] backdrop-blur-sm shadow">
                  Tampil
                </span>
              </div>
            </div>

            <!-- Card Content & Actions -->
            <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
              <div>
                <h4 class="font-bold text-slate-900 text-xs truncate">
                  {{ slider.title || 'Tanpa Judul' }}
                </h4>
                <p class="text-[11px] text-slate-500 truncate mt-0.5">
                  {{ slider.description || 'Tanpa Keterangan' }}
                </p>
                <div class="text-[10px] font-mono text-slate-600 mt-2">
                  slide_{{ slider.id }}
                </div>
              </div>

              <div class="pt-2 border-t border-slate-200 flex items-center justify-end">
                <button
                  @click="deleteSlider(slider)"
                  class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-xl bg-rose-950/40 hover:bg-rose-900/60 border border-rose-800/60 text-rose-300 text-xs font-semibold transition"
                >
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                  <span>Hapus</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- MODAL: Tambah Foto Slider / Banner Baru (Image 5 Match) -->
    <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md p-4 overflow-y-auto">
      <div class="relative w-full max-w-lg bg-white border border-slate-200 rounded-3xl p-6 sm:p-7 shadow-sm text-slate-700 my-8">
        <!-- Header -->
        <div class="flex items-center justify-between pb-3 border-b border-slate-200 mb-5">
          <h3 class="text-base font-bold text-slate-900 tracking-wide">
            Tambah Foto Slider / Banner Baru
          </h3>
          <button @click="showModal = false" class="text-slate-500 hover:text-white transition p-1 rounded-lg hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <!-- Top Toggle Tabs (Image 5 Match) -->
        <div class="grid grid-cols-2 gap-2 p-1 bg-white rounded-2xl border border-slate-200 mb-5 text-xs">
          <button
            type="button"
            @click="uploadMode = 'file'"
            :class="[
              'py-2 px-3 rounded-xl font-bold flex items-center justify-center space-x-1.5 transition',
              uploadMode === 'file'
                ? 'bg-[#0e7490] text-white shadow'
                : 'text-slate-500 hover:text-white'
            ]"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
            <span>Unggah File Foto</span>
          </button>
          <button
            type="button"
            @click="uploadMode = 'url'"
            :class="[
              'py-2 px-3 rounded-xl font-bold flex items-center justify-center space-x-1.5 transition',
              uploadMode === 'url'
                ? 'bg-[#0e7490] text-white shadow'
                : 'text-slate-500 hover:text-white'
            ]"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/></svg>
            <span>Gunakan Link / URL Gambar</span>
          </button>
        </div>

        <!-- Form -->
        <form @submit.prevent="saveSlider" class="space-y-4 text-xs">
          <!-- Field 1: File Upload or URL Input -->
          <div v-if="uploadMode === 'file'">
            <label class="block font-semibold text-slate-700 mb-1.5">
              Pilih File Foto (JPG, PNG, WebP) *
            </label>
            <div class="space-y-2">
              <input
                type="file"
                ref="sliderFileRef"
                accept="image/*"
                @change="handleSliderFileUpload"
                class="hidden"
              />
              <div class="flex items-center space-x-2">
                <button
                  type="button"
                  @click="$refs.sliderFileRef.click()"
                  class="px-3.5 py-2 rounded-xl bg-slate-800 hover:bg-slate-700 text-white font-semibold transition"
                >
                  Choose file
                </button>
                <span class="text-slate-500 text-[11px] truncate max-w-[200px]">
                  {{ sliderFileName || 'No file chosen' }}
                </span>
              </div>
              <div v-if="form.image_path" class="w-full h-28 rounded-xl overflow-hidden border border-slate-300 mt-2">
                <img :src="form.image_path" class="w-full h-full object-cover" />
              </div>
            </div>
          </div>

          <div v-else>
            <label class="block font-semibold text-slate-700 mb-1">URL / Link Gambar *</label>
            <input
              v-model="form.image_path"
              type="url"
              required
              placeholder="https://images.unsplash.com/..."
              class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-rose-500 transition"
            />
          </div>

          <!-- Field 2: Judul Slide -->
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Judul Slide (Opsional)</label>
            <input
              v-model="form.title"
              type="text"
              placeholder="misal: Haul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad"
              class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-rose-500 transition"
            />
          </div>

          <!-- Field 3: Subjudul / Keterangan Singkat -->
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Subjudul / Keterangan Singkat (Opsional)</label>
            <input
              v-model="form.description"
              type="text"
              placeholder="misal: Momen Kebersamaan & Khidmah Panitia Rojabiyyah 2026"
              class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-rose-500 transition"
            />
          </div>

          <!-- Field 4: Nomor Urutan Tampil -->
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Nomor Urutan Tampil</label>
            <input
              v-model.number="form.sort_order"
              type="number"
              min="1"
              required
              class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-rose-500 font-mono transition"
            />
          </div>

          <!-- Bottom Actions (Image 5 Match) -->
          <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200">
            <button
              type="button"
              @click="showModal = false"
              class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 font-semibold transition"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="saving"
              class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold shadow-lg shadow-emerald-950/60 transition disabled:opacity-50"
            >
              {{ saving ? 'Menyimpan...' : 'Simpan Foto Slider' }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import DashboardLayout from '../../layouts/DashboardLayout.vue';
import { useAuthStore } from '../../stores/auth';
import api from '../../services/api';

const authStore = useAuthStore();
const sliders = ref([]);
const showModal = ref(false);
const saving = ref(false);
const uploadMode = ref('file');
const sliderFileRef = ref(null);
const sliderFileName = ref('');
const currentSlideIndex = ref(0);

const getSliderImageUrl = (path) => {
  if (!path) return '/hero-bg.png';
  if (path.startsWith('data:') || path.startsWith('http://') || path.startsWith('https://')) {
    return path;
  }
  const backendBase = import.meta.env.VITE_API_URL?.replace(/\/api\/v1\/?$/, '') || 'http://127.0.0.1:8000';
  return backendBase + (path.startsWith('/') ? path : '/' + path);
};

const currentSlider = computed(() => {
  return sliders.value[currentSlideIndex.value] || sliders.value[0];
});

const form = ref({
  title: '',
  description: '',
  image_path: '',
  sort_order: 1,
  is_active: true,
});

const fetchSliders = async () => {
  try {
    const res = await api.get('/sliders');
    sliders.value = res.data;
    if (sliders.value.length === 0) {
      sliders.value = [
        {
          id: '1',
          title: 'Haul Ke-5 Masyayikh & Rojabiyyah 2026',
          description: 'Hormat Haul Al-Maghfurlah KH. Moch. Djamaluddin Achmad',
          image_path: '/hero-bg.png',
          sort_order: 1,
          is_active: true,
        },
        {
          id: '2',
          title: 'Mau\'idhoh Khasanah Masyayikh',
          description: 'Momen Tausiyah Penuh Berkah Haul Akbar',
          image_path: 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=1200&q=80',
          sort_order: 2,
          is_active: true,
        },
        {
          id: '3',
          title: 'Kebersamaan & Khidmah Panitia',
          description: 'Satu Barisan Menuju Suksesnya Acara Rojabiyyah 2026',
          image_path: 'https://images.unsplash.com/photo-1511578314322-379afb476865?auto=format&fit=crop&w=1200&q=80',
          sort_order: 3,
          is_active: true,
        },
      ];
    }
  } catch (err) {
    console.error('Gagal mengambil slider:', err);
  }
};

const handleSliderFileUpload = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  sliderFileName.value = file.name;
  const reader = new FileReader();
  reader.onload = (event) => {
    const img = new Image();
    img.onload = () => {
      const canvas = document.createElement('canvas');
      const maxDim = 1600;
      let width = img.width;
      let height = img.height;
      if (width > height && width > maxDim) {
        height = Math.round((height * maxDim) / width);
        width = maxDim;
      } else if (height > maxDim) {
        width = Math.round((width * maxDim) / height);
        height = maxDim;
      }
      canvas.width = width;
      canvas.height = height;
      const ctx = canvas.getContext('2d');
      ctx.drawImage(img, 0, 0, width, height);
      form.value.image_path = canvas.toDataURL('image/jpeg', 0.85);
    };
    img.src = event.target.result;
  };
  reader.readAsDataURL(file);
};

const openAddModal = () => {
  uploadMode.value = 'file';
  sliderFileName.value = '';
  form.value = {
    title: '',
    description: '',
    image_path: '',
    sort_order: sliders.value.length + 1,
    is_active: true,
  };
  showModal.value = true;
};

const saveSlider = async () => {
  if (!form.value.image_path) {
    alert('Silakan pilih file foto terlebih dahulu.');
    return;
  }
  saving.value = true;
  try {
    const payload = {
      ...form.value,
      title: form.value.title?.trim() || 'Dokumentasi Haul & Rojabiyyah 2026',
      description: form.value.description?.trim() || 'Momen Kebersamaan & Khidmah Panitia',
    };
    await api.post('/sliders', payload);
    alert('Foto slider baru berhasil disimpan!');
    showModal.value = false;
    fetchSliders();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan foto slider.');
  } finally {
    saving.value = false;
  }
};

const deleteSlider = async (slider) => {
  if (await window.$confirm(`Hapus foto banner "${slider.title || 'Foto ini'}"?`)) {
    try {
      await api.delete(`/sliders/${slider.id}`);
      fetchSliders();
    } catch (err) {
      // If local array
      sliders.value = sliders.value.filter((s) => s.id !== slider.id);
    }
  }
};

const handleImageError = (e, slider) => {
  if (slider?.image_path?.startsWith('/uploads/') && !e.target.src.includes(':8000')) {
    e.target.src = 'http://127.0.0.1:8000' + slider.image_path;
  }
};

onMounted(() => {
  fetchSliders();
  // Auto loop the live preview every 3.5s
  setInterval(() => {
    if (sliders.value.length > 0) {
      currentSlideIndex.value = (currentSlideIndex.value + 1) % sliders.value.length;
    }
  }, 3500);
});
</script>
