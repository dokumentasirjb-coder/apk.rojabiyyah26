import { defineStore } from 'pinia';
import api from '../services/api';

const defaultSettings = {
  app_name: 'Administrasi Rojabiyyah 2026',
  sub_theme: "Peringatan Haul & Isro' Mi'roj 1447 H / 2026 M",
  letterhead_text: 'PANITIA PERINGATAN ROJABIYYAH 2026',
  gdrive_folder_url: 'https://drive.google.com',
};

export const useSettingsStore = defineStore('settings', {
  state: () => {
    let saved = null;
    try {
      saved = JSON.parse(localStorage.getItem('app_settings'));
    } catch (e) {}

    return {
      settings: saved || defaultSettings,
      loaded: !!saved,
    };
  },

  actions: {
    async fetchSettings() {
      try {
        const response = await api.get('/settings/public');
        this.settings = { ...this.settings, ...response.data };
        this.loaded = true;
        localStorage.setItem('app_settings', JSON.stringify(this.settings));
      } catch (e) {
        // Fallback default
      }
    },
  },
});
