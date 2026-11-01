import { defineStore } from 'pinia';
import { ref } from 'vue';

export const useNotificationStore = defineStore('notification', () => {
  // Modal Dialog State (Modern Alert & Confirm replacement)
  const dialog = ref({
    isOpen: false,
    type: 'info', // 'success' | 'error' | 'warning' | 'info' | 'confirm'
    title: '',
    message: '',
    confirmText: 'OK, Mengerti',
    cancelText: 'Batal',
    resolve: null,
  });

  const dialogQueue = ref([]);

  // Floating Toasts State
  const toasts = ref([]);
  let toastIdCounter = 0;

  const detectTypeAndTitle = (text, customType, customTitle) => {
    if (customType) {
      return {
        type: customType,
        title: customTitle || (customType === 'success' ? 'Berhasil!' : customType === 'error' ? 'Terjadi Kesalahan' : customType === 'warning' ? 'Perhatian' : 'Informasi Sistem'),
      };
    }

    const lower = String(text || '').toLowerCase();
    if (
      lower.includes('berhasil') ||
      lower.includes('sukses') ||
      lower.includes('selesai') ||
      lower.includes('disetujui') ||
      lower.includes('diperbarui') ||
      lower.includes('terkirim') ||
      lower.includes('disimpan') ||
      lower.includes('diaktifkan')
    ) {
      return { type: 'success', title: customTitle || 'Aksi Berhasil!' };
    }

    if (
      lower.includes('gagal') ||
      lower.includes('error') ||
      lower.includes('salah') ||
      lower.includes('tidak valid') ||
      lower.includes('dilarang') ||
      lower.includes('terjadi kendala') ||
      lower.includes('dilewati')
    ) {
      return { type: 'error', title: customTitle || 'Terjadi Kendala' };
    }

    if (
      lower.includes('perhatian') ||
      lower.includes('peringatan') ||
      lower.includes('minimal') ||
      lower.includes('wajib') ||
      lower.includes('belum') ||
      lower.includes('ditolak') ||
      lower.includes('dibatalkan')
    ) {
      return { type: 'warning', title: customTitle || 'Peringatan Sistem' };
    }

    return { type: 'info', title: customTitle || 'Pemberitahuan Sistem' };
  };

  const processQueue = () => {
    if (dialog.value.isOpen || dialogQueue.value.length === 0) return;
    const next = dialogQueue.value.shift();
    dialog.value = {
      isOpen: true,
      ...next,
    };
  };

  /**
   * Modern replacement for window.alert()
   * Can accept a string or an options object: { message, title, type, confirmText }
   */
  const alert = (messageOrOpts, customTitle, customType) => {
    return new Promise((resolve) => {
      let message = '';
      let title = customTitle || '';
      let type = customType || '';
      let confirmText = 'OK, Mengerti';

      if (typeof messageOrOpts === 'object' && messageOrOpts !== null) {
        message = messageOrOpts.message || messageOrOpts.text || '';
        title = messageOrOpts.title || '';
        type = messageOrOpts.type || '';
        confirmText = messageOrOpts.confirmText || 'OK, Mengerti';
      } else {
        message = String(messageOrOpts ?? '');
      }

      const detected = detectTypeAndTitle(message, type, title);

      const dialogItem = {
        type: detected.type,
        title: detected.title,
        message,
        confirmText,
        cancelText: '',
        isConfirm: false,
        resolve,
      };

      dialogQueue.value.push(dialogItem);
      processQueue();
    });
  };

  /**
   * Modern replacement for window.confirm()
   * Returns a Promise resolving to true or false.
   */
  const confirm = (messageOrOpts, customTitle) => {
    return new Promise((resolve) => {
      let message = '';
      let title = customTitle || 'Konfirmasi Tindakan';
      let confirmText = 'Ya, Lanjutkan';
      let cancelText = 'Batal';
      let type = 'confirm';

      if (typeof messageOrOpts === 'object' && messageOrOpts !== null) {
        message = messageOrOpts.message || messageOrOpts.text || '';
        title = messageOrOpts.title || title;
        confirmText = messageOrOpts.confirmText || confirmText;
        cancelText = messageOrOpts.cancelText || cancelText;
        type = messageOrOpts.type || 'confirm';
      } else {
        message = String(messageOrOpts ?? '');
      }

      const dialogItem = {
        type,
        title,
        message,
        confirmText,
        cancelText,
        isConfirm: true,
        resolve,
      };

      dialogQueue.value.push(dialogItem);
      processQueue();
    });
  };

  const handleConfirm = () => {
    if (dialog.value.resolve) {
      dialog.value.resolve(true);
    }
    closeDialog();
  };

  const handleCancel = () => {
    if (dialog.value.resolve) {
      dialog.value.resolve(false);
    }
    closeDialog();
  };

  const closeDialog = () => {
    dialog.value.isOpen = false;
    dialog.value.resolve = null;
    setTimeout(() => {
      processQueue();
    }, 180);
  };

  /**
   * Floating Toast Notification
   */
  const toast = (message, type = 'info', duration = 3500) => {
    const id = ++toastIdCounter;
    const detected = detectTypeAndTitle(message, type);
    const toastItem = {
      id,
      message,
      type: detected.type,
      title: detected.title,
    };
    toasts.value.push(toastItem);

    if (duration > 0) {
      setTimeout(() => {
        removeToast(id);
      }, duration);
    }
    return id;
  };

  const removeToast = (id) => {
    toasts.value = toasts.value.filter((t) => t.id !== id);
  };

  const success = (message, title = 'Berhasil!') => alert({ message, title, type: 'success' });
  const error = (message, title = 'Terjadi Kesalahan') => alert({ message, title, type: 'error' });
  const warning = (message, title = 'Peringatan') => alert({ message, title, type: 'warning' });
  const info = (message, title = 'Informasi') => alert({ message, title, type: 'info' });

  return {
    dialog,
    toasts,
    alert,
    confirm,
    handleConfirm,
    handleCancel,
    closeDialog,
    toast,
    removeToast,
    success,
    error,
    warning,
    info,
  };
});

