<template>
  <div class="min-h-screen bg-slate-50 text-slate-900 font-sans antialiased">
    <router-view />
    <ForceChangePasswordModal />
    <ModernDialogModal />
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import ForceChangePasswordModal from './components/ForceChangePasswordModal.vue';
import ModernDialogModal from './components/ModernDialogModal.vue';
import { useSettingsStore } from './stores/settings';
import { useAuthStore } from './stores/auth';

const settingsStore = useSettingsStore();
const authStore = useAuthStore();

const checkAuthOnBrowserNavigation = () => {
  const token = localStorage.getItem('token');
  const user = localStorage.getItem('user');
  const currentPath = window.location.pathname;

  const publicRoutes = ['/', '/login', '/register-member', '/register', '/register-account', '/jadwal-kerja', '/pembagian-proyektor'];
  const isPublic = publicRoutes.includes(currentPath) || currentPath.startsWith('/verify');

  if (!isPublic && (!token || !user)) {
    authStore.token = null;
    authStore.user = null;
    localStorage.removeItem('token');
    localStorage.removeItem('user');
    window.location.replace('/login');
  }
};

onMounted(() => {
  // Silent non-blocking background sync
  settingsStore.fetchSettings();
  if (authStore.token) {
    authStore.fetchUser();
  }

  // Lindungi tombol panah kembali (Back/Forward) dan browser cache (bfcache)
  window.addEventListener('pageshow', () => {
    checkAuthOnBrowserNavigation();
  });

  window.addEventListener('popstate', () => {
    checkAuthOnBrowserNavigation();
  });
});
</script>
