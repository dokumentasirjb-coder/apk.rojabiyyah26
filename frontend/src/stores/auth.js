import { defineStore } from 'pinia';
import api from '../services/api';

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: JSON.parse(localStorage.getItem('user') || 'null'),
    token: localStorage.getItem('token') || null,
    loading: false,
    error: null,
  }),

  getters: {
    isAuthenticated: (state) => !!state.token && !!state.user,
    isSuperAdmin: (state) => state.user?.role === 'super_admin',
    isAdmin: (state) => ['super_admin', 'admin'].includes(state.user?.role),
    isBendahara: (state) => state.user?.role === 'bendahara',
    isKetuaDivisi: (state) => state.user?.role === 'ketua_divisi',
    isPicPosko: (state) => state.user?.role === 'pic_posko',
    isAnggota: (state) => state.user?.role === 'anggota',
    roleLabel: (state) => {
      const map = {
        super_admin: 'Super Admin',
        admin: 'Admin Operasional',
        bendahara: 'Bendahara',
        ketua_divisi: 'Ketua Divisi',
        pic_posko: 'PIC Posko',
        anggota: 'Anggota',
      };
      return map[state.user?.role] || 'Pengguna';
    },
  },

  actions: {
    async login(credentials) {
      this.loading = true;
      this.error = null;
      try {
        const response = await api.post('/login', credentials);
        this.token = response.data.token;
        this.user = response.data.user;

        localStorage.setItem('token', this.token);
        localStorage.setItem('user', JSON.stringify(this.user));

        return response.data;
      } catch (err) {
        this.error = err.response?.data?.message || 'Login gagal, periksa email/ID dan password Anda.';
        throw err;
      } finally {
        this.loading = false;
      }
    },

    async fetchUser() {
      if (!this.token) return null;
      try {
        const response = await api.get('/me');
        this.user = response.data.user;
        localStorage.setItem('user', JSON.stringify(this.user));
        return this.user;
      } catch (err) {
        this.logout();
        return null;
      }
    },

    async logout() {
      try {
        if (this.token) {
          await api.post('/logout');
        }
      } catch (e) {
        // Ignore error
      } finally {
        this.user = null;
        this.token = null;
        localStorage.removeItem('token');
        localStorage.removeItem('user');
      }
    },

    setUser(user) {
      this.user = user;
      localStorage.setItem('user', JSON.stringify(user));
    }
  },
});

