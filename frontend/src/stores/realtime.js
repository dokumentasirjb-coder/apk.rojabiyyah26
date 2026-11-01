import { defineStore } from 'pinia';
import api from '../services/api';
import { useAuthStore } from './auth';

export const useRealtimeStore = defineStore('realtime', {
  state: () => ({
    stats: {
      total_members: 0,
      total_items: 0,
      active_loans: 0,
      pending_loans: 0,
      total_regions: 0,
      pending_reports: 0,
      cash_balance: null,
      recent_loans: [],
      recent_reports: [],
    },
    isLive: false,
    lastSyncTime: null,
    syncInterval: null,
    activeAlerts: [],
  }),

  actions: {
    async fetchLiveStats() {
      const auth = useAuthStore();
      if (!auth.token) return;

      try {
        const res = await api.get('/dashboard/stats');
        this.stats = res.data;
        this.lastSyncTime = new Date();
        this.isLive = true;

        // Check if there are urgent alerts (rusak_kendala reports)
        const urgentReports = (res.data.recent_reports || []).filter(
          (r) => r.status === 'rusak_kendala' && r.followup_status !== 'resolved'
        );
        this.activeAlerts = urgentReports;
      } catch (err) {
        this.isLive = false;
      }
    },

    startLiveSync(intervalMs = 20000) {
      if (this.syncInterval) clearInterval(this.syncInterval);
      this.fetchLiveStats();
      this.syncInterval = setInterval(() => {
        if (document.visibilityState === 'visible') {
          this.fetchLiveStats();
        }
      }, intervalMs);
    },

    stopLiveSync() {
      if (this.syncInterval) {
        clearInterval(this.syncInterval);
        this.syncInterval = null;
      }
      this.isLive = false;
    },
  },
});

