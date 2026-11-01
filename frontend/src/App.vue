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

onMounted(() => {
  // Silent non-blocking background sync
  settingsStore.fetchSettings();
  if (authStore.token) {
    authStore.fetchUser();
  }
});
</script>
