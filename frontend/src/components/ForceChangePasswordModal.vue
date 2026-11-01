<template>
  <div v-if="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/80 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl max-w-md w-full p-6 shadow-2xl border border-slate-100">
      <div class="flex items-center space-x-3 mb-4">
        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-bold">
          !
        </div>
        <div>
          <h3 class="text-lg font-bold text-slate-800">Wajib Ganti Kata Sandi</h3>
          <p class="text-xs text-slate-500">Ini adalah login pertama Anda dengan kredensial sementara.</p>
        </div>
      </div>

      <div v-if="error" class="mb-4 p-3 bg-red-50 border border-red-200 text-red-700 text-xs rounded-lg">
        {{ error }}
      </div>

      <form @submit.prevent="handleSubmit" class="space-y-4">
        <div>
          <label class="block text-xs font-semibold text-slate-600 mb-1">Kata Sandi Sementara / Saat Ini</label>
          <input
            v-model="form.current_password"
            type="password"
            required
            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none"
            placeholder="Masukkan kata sandi awal"
          />
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-600 mb-1">Kata Sandi Baru (Minimal 8 Karakter)</label>
          <input
            v-model="form.new_password"
            type="password"
            required
            minlength="8"
            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none"
            placeholder="Kombinasi huruf & angka aman"
          />
        </div>

        <div>
          <label class="block text-xs font-semibold text-slate-600 mb-1">Konfirmasi Kata Sandi Baru</label>
          <input
            v-model="form.new_password_confirmation"
            type="password"
            required
            class="w-full px-3 py-2 text-sm border border-slate-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:outline-none"
            placeholder="Ulangi kata sandi baru"
          />
        </div>

        <button
          type="submit"
          :disabled="loading"
          class="w-full py-2.5 px-4 bg-primary-600 hover:bg-primary-700 text-white font-medium text-sm rounded-lg shadow-md transition disabled:opacity-50"
        >
          {{ loading ? 'Menyimpan...' : 'Perbarui Kata Sandi & Lanjutkan' }}
        </button>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { useAuthStore } from '../stores/auth';
import api from '../services/api';

const authStore = useAuthStore();
const showModal = computed(() => authStore.isAuthenticated && authStore.user?.must_change_password);

const form = ref({
  current_password: '',
  new_password: '',
  new_password_confirmation: '',
});

const loading = ref(false);
const error = ref('');

const handleSubmit = async () => {
  error.value = '';
  if (form.value.new_password !== form.value.new_password_confirmation) {
    error.value = 'Konfirmasi kata sandi baru tidak cocok.';
    return;
  }

  loading.value = true;
  try {
    const res = await api.post('/password/change', form.value);
    authStore.setUser(res.data.user);
    alert('Kata sandi berhasil diperbarui. Selamat datang di Portal Rojabiyyah 2026!');
  } catch (err) {
    error.value = err.response?.data?.message || 'Gagal memperbarui kata sandi.';
  } finally {
    loading.value = false;
  }
};
</script>

