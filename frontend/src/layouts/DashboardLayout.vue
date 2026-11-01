<template>
  <div class="min-h-screen flex bg-[#f8fafc] text-slate-800 font-nunito selection:bg-emerald-500 selection:text-white antialiased">
    <!-- Mobile Sidebar Backdrop -->
    <div
      v-if="mobileMenuOpen"
      @click="mobileMenuOpen = false"
      class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden transition-opacity duration-300"
    ></div>

    <!-- Sidebar (Crisp White & Clean Emerald Accent Theme) -->
    <aside
      :class="[
        'fixed inset-y-0 left-0 z-50 bg-white border-r border-slate-200 flex flex-col transition-all duration-300 ease-in-out lg:static lg:inset-auto shadow-xl lg:shadow-none',
        mobileMenuOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0',
        sidebarCollapsed ? 'lg:w-[74px]' : 'lg:w-64 w-64'
      ]"
    >
      <!-- Brand Header -->
      <div
        :class="[
          'h-16 flex items-center bg-white border-b border-slate-200 flex-shrink-0 transition-all duration-300',
          sidebarCollapsed ? 'justify-center px-0' : 'justify-between px-4'
        ]"
      >
        <router-link
          to="/dashboard"
          :class="[
            'flex items-center group transition-all duration-200',
            sidebarCollapsed ? 'justify-center' : 'space-x-3 overflow-hidden'
          ]"
          :title="sidebarCollapsed ? (settingsStore.settings?.app_name || 'MEDIA ROJABIYYAH') : ''"
        >
          <div
            :class="[
              'flex items-center justify-center flex-shrink-0 group-hover:scale-105 transition-transform duration-200',
              sidebarCollapsed ? 'w-10 h-10' : 'h-10 w-auto'
            ]"
          >
            <img
              v-if="settingsStore.settings?.logo_url && settingsStore.settings?.logo_url !== '/logo.png'"
              :src="settingsStore.settings.logo_url"
              alt="Logo"
              class="h-8 sm:h-9 w-auto max-w-[48px] object-contain"
            />
            <div v-else class="w-9 h-9 rounded-xl bg-gradient-to-br from-[#059669] to-[#10b981] flex items-center justify-center text-white">
              <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
          </div>
          <div v-show="!sidebarCollapsed" class="min-w-0 transition-opacity duration-200">
            <div class="text-xs font-black text-slate-900 tracking-wider uppercase truncate max-w-[145px]">
              {{ settingsStore.settings?.app_name || 'MEDIA ROJABIYYAH' }}
            </div>
            <div class="text-[10px] text-emerald-600 font-extrabold uppercase tracking-widest truncate max-w-[145px] flex items-center space-x-1">
              <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-ping mr-0.5"></span>
              <span>{{ isMemberOnly ? 'PORTAL ANGGOTA' : (authStore.roleLabel || 'SUPER ADMIN') }}</span>
            </div>
          </div>
        </router-link>

        <!-- Close button on Mobile -->
        <button
          @click="mobileMenuOpen = false"
          class="lg:hidden text-slate-400 hover:text-slate-700 p-1 rounded-lg hover:bg-slate-100 transition"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <!-- Desktop collapse toggle in sidebar header (only shown when expanded) -->
        <button
          v-show="!sidebarCollapsed"
          @click="toggleSidebarCollapse"
          class="hidden lg:flex items-center justify-center w-7 h-7 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition"
          title="Ciutkan Sidebar"
        >
          <svg
            class="w-4 h-4"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
          </svg>
        </button>
      </div>

      <!-- Navigation Links -->
      <nav class="flex-1 overflow-y-auto py-3 px-2 space-y-4 scrollbar-thin">
        <!-- =================== MEMBER PORTAL NAVIGATION =================== -->
        <template v-if="isMemberOnly">
          <!-- 1. RINGKASAN -->
          <div>
            <div
              v-show="!sidebarCollapsed"
              class="px-3 pb-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider flex items-center space-x-1.5"
            >
              <span class="w-2 h-0.5 bg-emerald-500 rounded"></span>
              <span>RINGKASAN</span>
            </div>
            <div class="space-y-1 mt-1">
              <router-link
                to="/dashboard"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="$route.path === '/dashboard' ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Beranda Anggota' : ''"
              >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v18M3 9h6"/></svg>
                <span v-show="!sidebarCollapsed" class="truncate">Beranda Anggota</span>
              </router-link>

              <router-link
                to="/reports"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="$route.path.startsWith('/reports') ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Jadwal Penugasan' : ''"
              >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01"/></svg>
                <span v-show="!sidebarCollapsed" class="truncate">Jadwal Penugasan</span>
              </router-link>
            </div>
          </div>

          <!-- 2. LAYANAN ANGGOTA -->
          <div>
            <div
              v-show="!sidebarCollapsed"
              class="px-3 pb-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider flex items-center space-x-1.5"
            >
              <span class="w-2 h-0.5 bg-emerald-500 rounded"></span>
              <span>LAYANAN ANGGOTA</span>
            </div>
            <div class="space-y-1 mt-1">
              <router-link
                to="/inventory"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="$route.path === '/inventory' ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Katalog Inventaris' : ''"
              >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span v-show="!sidebarCollapsed" class="truncate">Katalog Inventaris</span>
              </router-link>

              <router-link
                to="/loans"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="$route.path.startsWith('/loans') ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Peminjaman Saya' : ''"
              >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <span v-show="!sidebarCollapsed" class="truncate">Peminjaman Saya</span>
              </router-link>

              <router-link
                to="/regions"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="$route.path.startsWith('/regions') ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Kontrol Wilayah' : ''"
              >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3" stroke-width="2"/></svg>
                <span v-show="!sidebarCollapsed" class="truncate">Kontrol Wilayah</span>
              </router-link>

              <router-link
                to="/members"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="$route.path.startsWith('/members') ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Direktori Anggota' : ''"
              >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="9" cy="7" r="4" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-2a4 4 0 014-4h4a4 4 0 014 4v2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3.13a4 4 0 010 7.75M21 21v-2a4 4 0 00-3-3.85"/></svg>
                <span v-show="!sidebarCollapsed" class="truncate">Direktori Anggota</span>
              </router-link>
            </div>
          </div>

          <!-- 3. BERKAS & DOKUMEN -->
          <div>
            <div
              v-show="!sidebarCollapsed"
              class="px-3 pb-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider flex items-center space-x-1.5"
            >
              <span class="w-2 h-0.5 bg-emerald-500 rounded"></span>
              <span>BERKAS &amp; DOKUMEN</span>
            </div>
            <div class="space-y-1 mt-1">
              <router-link
                to="/settings?tab=drive"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="$route.path === '/settings' && $route.query.tab === 'drive' ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Google Drive Unduhan' : ''"
              >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h12a4 4 0 002-7.5 5 5 0 00-9.8-1.5A4.5 4.5 0 003 15z"/></svg>
                <span v-show="!sidebarCollapsed" class="truncate">Google Drive Unduhan</span>
              </router-link>
            </div>
          </div>

          <!-- 4. PENGATURAN & AKUN -->
          <div>
            <div
              v-show="!sidebarCollapsed"
              class="px-3 pb-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider flex items-center space-x-1.5"
            >
              <span class="w-2 h-0.5 bg-emerald-500 rounded"></span>
              <span>PENGATURAN &amp; AKUN</span>
            </div>
            <div class="space-y-1 mt-1">
              <router-link
                to="/settings?tab=profile"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="($route.path === '/settings' && ($route.query.tab === 'profile' || !$route.query.tab)) ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Pengaturan Profil' : ''"
              >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 21v-2a4 4 0 014-4h8a4 4 0 014 4v2"/></svg>
                <span v-show="!sidebarCollapsed" class="truncate">Pengaturan Profil</span>
              </router-link>
            </div>
          </div>
        </template>

        <!-- =================== ADMIN / SUPER ADMIN NAVIGATION =================== -->
        <template v-else>
          <!-- 1. RINGKASAN -->
          <div>
            <div
              v-show="!sidebarCollapsed"
              class="px-3 pb-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider flex items-center space-x-1.5"
            >
              <span class="w-2 h-0.5 bg-emerald-500 rounded"></span>
              <span>RINGKASAN</span>
            </div>
            <div class="space-y-1 mt-1">
              <router-link
                to="/dashboard"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="$route.path === '/dashboard' ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Administrasi' : ''"
              >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v18M3 9h6"/></svg>
                <span v-show="!sidebarCollapsed" class="truncate">Administrasi</span>
              </router-link>

              <router-link
                to="/reports"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="$route.path.startsWith('/reports') ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Pembagian Penugasan' : ''"
              >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h18M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01M16 18h.01"/></svg>
                <span v-show="!sidebarCollapsed" class="truncate">Pembagian Penugasan</span>
              </router-link>
            </div>
          </div>

          <!-- 2. LOGISTIK & OPERASIONAL -->
          <div>
            <div
              v-show="!sidebarCollapsed"
              class="px-3 pb-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider flex items-center space-x-1.5"
            >
              <span class="w-2 h-0.5 bg-emerald-500 rounded"></span>
              <span>LOGISTIK &amp; OPERASIONAL</span>
            </div>
            <div class="space-y-1 mt-1">
              <router-link
                to="/inventory"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="$route.path === '/inventory' && !$route.query.tab ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Data Inventaris Internal' : ''"
              >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                <span v-show="!sidebarCollapsed" class="truncate">Data Inventaris Internal</span>
              </router-link>

              <router-link
                to="/loans?tab=mitra"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="$route.path.startsWith('/loans') && $route.query.tab === 'mitra' ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Pinjaman Lembaga Mitra' : ''"
              >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                <span v-show="!sidebarCollapsed" class="truncate">Pinjaman Lembaga Mitra</span>
              </router-link>

              <router-link
                to="/loans"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="$route.path === '/loans' && $route.query.tab !== 'mitra' ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Peminjaman Anggota' : ''"
              >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                <span v-show="!sidebarCollapsed" class="truncate">Peminjaman Anggota</span>
              </router-link>

              <router-link
                to="/members"
                class="flex items-center justify-between px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="$route.path.startsWith('/members') ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Data Anggota' : ''"
              >
                <div class="flex items-center space-x-3 min-w-0">
                  <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="9" cy="7" r="4" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-2a4 4 0 014-4h4a4 4 0 014 4v2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3.13a4 4 0 010 7.75M21 21v-2a4 4 0 00-3-3.85"/></svg>
                  <span v-show="!sidebarCollapsed" class="truncate">Data Anggota</span>
                </div>
                <span
                  v-if="!sidebarCollapsed && realtimeStore.stats.total_members > 0"
                  class="px-2 py-0.5 text-[10px] font-bold bg-emerald-100 text-emerald-800 rounded-full"
                >
                  {{ realtimeStore.stats.total_members }}
                </span>
              </router-link>

              <router-link
                to="/regions"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="$route.path.startsWith('/regions') ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Kontrol Wilayah' : ''"
              >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3" stroke-width="2"/></svg>
                <span v-show="!sidebarCollapsed" class="truncate">Kontrol Wilayah</span>
              </router-link>

              <router-link
                v-if="['super_admin', 'bendahara', 'admin'].includes(authStore.user?.role)"
                to="/cash"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="$route.path.startsWith('/cash') ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Rekap Uang' : ''"
              >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 1v22m5-18H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
                <span v-show="!sidebarCollapsed" class="truncate">Rekap Uang</span>
              </router-link>
            </div>
          </div>

          <!-- 3. PUBLIKASI & HALAMAN UTAMA -->
          <div v-if="['super_admin', 'admin'].includes(authStore.user?.role)">
            <div
              v-show="!sidebarCollapsed"
              class="px-3 pb-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider flex items-center space-x-1.5"
            >
              <span class="w-2 h-0.5 bg-emerald-500 rounded"></span>
              <span>PUBLIKASI &amp; HALAMAN UTAMA</span>
            </div>
            <div class="space-y-1 mt-1">
              <router-link
                to="/landing-manager"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="$route.path.startsWith('/landing-manager') ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Kelola Halaman Utama' : ''"
              >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="2" y="3" width="20" height="14" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 21h8M12 17v4M7 8h.01M10 8h.01M13 8h.01"/></svg>
                <span v-show="!sidebarCollapsed" class="truncate">Kelola Halaman Utama</span>
              </router-link>

              <router-link
                to="/sliders"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="$route.path.startsWith('/sliders') ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Foto Slider Depan' : ''"
              >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="18" height="18" rx="2" stroke-width="2"/><circle cx="8.5" cy="8.5" r="1.5"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15l-5-5L5 21"/></svg>
                <span v-show="!sidebarCollapsed" class="truncate">Foto Slider Depan</span>
              </router-link>
            </div>
          </div>

          <!-- 4. PENGATURAN SISTEM (TERPISAH & DI SINI KELOLA DIVISI TUGAS) -->
          <div v-if="['super_admin', 'admin'].includes(authStore.user?.role)">
            <div
              v-show="!sidebarCollapsed"
              class="px-3 pb-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider flex items-center space-x-1.5"
            >
              <span class="w-2 h-0.5 bg-emerald-500 rounded"></span>
              <span>PENGATURAN SISTEM</span>
            </div>
            <div class="space-y-1 mt-1">
              <router-link
                to="/settings?tab=divisions"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="($route.path === '/settings' && $route.query.tab === 'divisions') || $route.path.startsWith('/divisions') ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Divisi Tugas Panitia' : ''"
              >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                <span v-show="!sidebarCollapsed" class="truncate">Divisi Tugas Panitia</span>
              </router-link>

              <router-link
                to="/settings?tab=drive"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="$route.path.startsWith('/settings') && $route.query.tab === 'drive' ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Google Drive Berkas' : ''"
              >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h12a4 4 0 002-7.5 5 5 0 00-9.8-1.5A4.5 4.5 0 003 15z"/></svg>
                <span v-show="!sidebarCollapsed" class="truncate">Google Drive Berkas</span>
              </router-link>

              <router-link
                to="/settings?tab=admins"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="$route.path.startsWith('/settings') && $route.query.tab === 'admins' ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Kelola Pengelola Admin' : ''"
              >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                <span v-show="!sidebarCollapsed" class="truncate">Kelola Pengelola Admin</span>
              </router-link>

              <router-link
                to="/settings"
                class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold transition group"
                :class="$route.path === '/settings' && !$route.query.tab ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-md shadow-emerald-600/20 font-bold' : 'text-slate-600 hover:bg-emerald-50 hover:text-emerald-700'"
                :title="sidebarCollapsed ? 'Pengaturan Sistem' : ''"
              >
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
                <span v-show="!sidebarCollapsed" class="truncate">Pengaturan Sistem</span>
              </router-link>
            </div>
          </div>

          <!-- Danger Zone Link if Super Admin -->
          <div v-if="authStore.isSuperAdmin" class="pt-2">
            <router-link
              to="/danger-zone"
              class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-xs font-semibold text-orange-600 hover:bg-orange-50 transition group"
              :class="$route.path === '/danger-zone' ? 'bg-orange-100 text-orange-700 border border-orange-200' : ''"
              :title="sidebarCollapsed ? 'Danger Zone' : ''"
            >
              <svg class="w-4 h-4 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
              <span v-show="!sidebarCollapsed" class="truncate">Danger Zone</span>
            </router-link>
          </div>
        </template>
      </nav>

      <!-- Bottom User Profile Card (Clean Light Theme) -->
      <div :class="['bg-slate-50 border-t border-slate-200 flex-shrink-0 transition-all duration-300', sidebarCollapsed ? 'p-2' : 'p-3']">
        <div :class="['flex items-center rounded-xl bg-white border border-slate-200 shadow-sm transition-all', sidebarCollapsed ? 'justify-center p-2' : 'justify-between p-2']">
          <router-link
            to="/settings?tab=profile"
            :class="['flex items-center min-w-0 hover:opacity-90 transition cursor-pointer', sidebarCollapsed ? 'justify-center' : 'space-x-2.5 flex-1']"
            :title="sidebarCollapsed ? (authStore.user?.name || 'Profil Akun') : ''"
          >
            <div
              class="w-8 h-8 rounded-full bg-gradient-to-br from-[#059669] to-[#10b981] text-white font-black flex items-center justify-center text-xs shadow-md shadow-emerald-500/20 flex-shrink-0 overflow-hidden relative"
            >
              <img
                v-if="authStore.user?.member?.photo_path"
                :src="authStore.user.member.photo_path"
                alt="Avatar"
                class="w-full h-full object-cover"
              />
              <span v-else>{{ userInitial }}</span>
              <span class="absolute bottom-0 right-0 w-2 h-2 rounded-full bg-emerald-500 border border-white"></span>
            </div>
            <div v-show="!sidebarCollapsed" class="min-w-0">
              <p class="text-xs font-bold text-slate-900 truncate">{{ authStore.user?.name || authStore.user?.username || 'Anggota' }}</p>
              <p class="text-[10px] text-emerald-600 font-medium truncate">{{ authStore.roleLabel || 'Anggota' }}</p>
            </div>
          </router-link>
          <button
            v-show="!sidebarCollapsed"
            @click="handleLogout"
            title="Keluar"
            class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
          </button>
        </div>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-[#f8fafc]">
      <!-- Top Navbar Header (Clean White & Emerald Accent Style) -->
      <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 z-30 sticky top-0 shadow-sm">
        <div class="flex items-center space-x-3">
          <!-- Mobile Hamburger -->
          <button
            @click="mobileMenuOpen = true"
            class="lg:hidden p-2 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
          </button>

          <!-- Desktop Sidebar Collapse Toggle Button -->
          <button
            @click="toggleSidebarCollapse"
            class="hidden lg:flex p-2 rounded-lg text-slate-500 hover:text-slate-800 hover:bg-slate-100 transition"
            :title="sidebarCollapsed ? 'Buka Sidebar' : 'Tutup Sidebar'"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
            </svg>
          </button>

          <!-- Breadcrumb / Header Title -->
          <div class="flex items-center space-x-2">
            <span class="text-xs text-slate-400 hidden sm:inline">Aplikasi /</span>
            <h1 class="text-sm font-bold text-slate-900 tracking-wide">
              {{ isMemberOnly ? 'Portal Anggota' : `Administrasi ${authStore.roleLabel || 'Super Admin'}` }}
            </h1>
          </div>
        </div>

        <!-- Right Header Items -->
        <div class="flex items-center space-x-2 sm:space-x-4">
          <!-- Quick Link: Halaman Depan Publik -->
          <router-link
            to="/"
            target="_blank"
            class="hidden sm:inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white border border-emerald-200 text-xs font-bold transition shadow-sm"
            title="Buka Halaman Utama Publik di tab baru"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
            <span>Halaman Publik</span>
          </router-link>

          <!-- Notification Bell & Dropdown -->
          <div class="relative">
            <button
              @click="toggleNotifications"
              class="p-2 text-slate-500 hover:text-slate-800 transition relative rounded-lg hover:bg-slate-100"
              title="Notifikasi & Tugas"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
              <span
                v-if="unreadNotifCount > 0"
                class="absolute 0.5 top-0.5 right-0.5 px-1.5 py-0.2 min-w-[17px] h-4 text-[9px] font-black bg-[#f97316] text-white rounded-full flex items-center justify-center animate-pulse shadow-md"
              >
                {{ unreadNotifCount }}
              </span>
              <span v-else-if="realtimeStore.activeAlerts.length > 0" class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-emerald-500 animate-ping"></span>
            </button>

            <!-- Notifications Dropdown Popup -->
            <div
              v-if="showNotifications"
              class="absolute right-0 mt-2 w-80 sm:w-96 bg-white border border-slate-200 rounded-2xl shadow-2xl z-50 overflow-hidden text-xs flex flex-col"
            >
              <!-- Dropdown Header -->
              <div class="p-4 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                <div class="flex items-center space-x-2">
                  <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                  <span class="font-bold text-slate-900 text-xs">Pemberitahuan &amp; Tugas</span>
                  <span v-if="unreadNotifCount > 0" class="px-2 py-0.5 rounded-full bg-orange-100 text-orange-700 border border-orange-200 text-[10px] font-bold">
                    {{ unreadNotifCount }} Baru
                  </span>
                </div>
                <button
                  v-if="unreadNotifCount > 0"
                  @click="markAllNotifsRead"
                  class="text-[11px] text-emerald-600 hover:underline font-semibold transition"
                >
                  Tandai Dibaca
                </button>
              </div>

              <!-- Filter Tabs: Semua / Belum Dibaca -->
              <div class="px-3 pt-2 pb-1 bg-white border-b border-slate-200 flex items-center space-x-2">
                <button
                  @click="notifTab = 'all'"
                  :class="[
                    'px-2.5 py-1 rounded-lg text-[11px] font-bold transition',
                    notifTab === 'all' ? 'bg-[#059669] text-white shadow-sm' : 'text-slate-500 hover:text-slate-900'
                  ]"
                >
                  Semua ({{ notifications.length }})
                </button>
                <button
                  @click="notifTab = 'unread'"
                  :class="[
                    'px-2.5 py-1 rounded-lg text-[11px] font-bold transition',
                    notifTab === 'unread' ? 'bg-[#f97316] text-white shadow-sm' : 'text-slate-500 hover:text-slate-900'
                  ]"
                >
                  Belum Dibaca ({{ unreadNotifCount }})
                </button>
              </div>

              <!-- Notifications List -->
              <div class="max-h-80 overflow-y-auto divide-y divide-slate-100">
                <div v-if="filteredNotifications.length === 0" class="p-7 text-center text-slate-400 text-xs">
                  <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-400 mx-auto mb-2.5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                  </div>
                  <p class="font-bold text-slate-800 text-xs">Tidak Ada Pemberitahuan</p>
                  <p class="text-[11px] text-slate-500 mt-0.5">
                    {{ notifTab === 'unread' ? 'Semua notifikasi telah Anda baca.' : 'Belum ada tugas atau informasi baru saat ini.' }}
                  </p>
                </div>

                <div
                  v-for="n in filteredNotifications"
                  :key="n.id"
                  @click="clickNotification(n)"
                  class="p-3.5 hover:bg-slate-50 transition cursor-pointer flex items-start space-x-3 group"
                  :class="!n.read_at ? 'bg-emerald-50/50' : ''"
                >
                  <div
                    class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 text-base shadow-sm mt-0.5 transition group-hover:scale-105"
                    :class="getNotifBadgeClass(n.type)"
                  >
                    <svg v-if="n.type === 'assignment_priority'" class="w-4 h-4 text-[#f97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><polygon stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
                    <svg v-else-if="n.type === 'loan_pending' || n.type === 'loan_diajukan'" class="w-4 h-4 text-[#0284c7]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="12 6 12 12 16 14"/></svg>
                    <svg v-else-if="n.type === 'loan_disetujui'" class="w-4 h-4 text-[#059669]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <svg v-else-if="n.type === 'loan_diserahkan'" class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    <svg v-else-if="n.type === 'loan_ditolak'" class="w-4 h-4 text-[#ef4444]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9l-6 6m0-6l6 6"/></svg>
                    <svg v-else-if="n.type === 'loan_dikembalikan'" class="w-4 h-4 text-[#059669]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    <svg v-else-if="n.type === 'report_urgent'" class="w-4 h-4 text-[#f97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    <svg v-else class="w-4 h-4 text-[#059669]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                  </div>
                  <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between">
                      <span class="font-bold text-slate-800 text-xs truncate group-hover:text-emerald-700 transition">
                        {{ n.data?.title || 'Notifikasi' }}
                      </span>
                      <span v-if="!n.read_at" class="w-2 h-2 rounded-full bg-[#f97316] animate-pulse flex-shrink-0 ml-1"></span>
                    </div>
                    <p class="text-slate-600 text-[11px] mt-0.5 leading-snug">
                      {{ n.data?.message }}
                    </p>
                    <div class="flex items-center justify-between mt-1 text-[10px] text-slate-400">
                      <span>{{ formatTimeAgo(n.created_at) }}</span>
                      <span v-if="n.data?.url" class="text-emerald-600 font-sans group-hover:underline">Buka detail &rarr;</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Dropdown Footer -->
              <div class="p-2.5 bg-slate-50 border-t border-slate-200 flex items-center justify-between text-[11px]">
                <button
                  type="button"
                  @click="fetchNotifications"
                  class="text-slate-500 hover:text-slate-800 transition flex items-center space-x-1.5"
                >
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                  <span>Segarkan</span>
                </button>
                <router-link
                  :to="isMemberOnly ? '/regions' : '/loans'"
                  @click="showNotifications = false"
                  class="text-emerald-600 hover:underline font-semibold transition"
                >
                  {{ isMemberOnly ? 'Buka Jadwal Posko →' : 'Buka Antrean Logistik →' }}
                </router-link>
              </div>
            </div>
          </div>

          <!-- User Profile Dropdown / Quick Pill -->
          <router-link
            to="/settings?tab=profile"
            class="flex items-center space-x-2 pl-3 border-l border-slate-200 hover:opacity-90 transition cursor-pointer"
            title="Buka Pengaturan Profil"
          >
            <div
              class="w-8 h-8 rounded-full bg-gradient-to-br from-[#059669] to-[#10b981] text-white font-black flex items-center justify-center text-xs overflow-hidden shadow-sm"
            >
              <img
                v-if="authStore.user?.member?.photo_path"
                :src="authStore.user.member.photo_path"
                alt="Avatar"
                class="w-full h-full object-cover"
              />
              <span v-else>{{ userInitial }}</span>
            </div>
            <div class="hidden sm:block text-left">
              <span class="block text-xs font-bold text-slate-800 truncate max-w-[120px]">
                {{ authStore.user?.name || authStore.user?.username || 'Anggota' }}
              </span>
              <span class="block text-[10px] text-emerald-600 font-semibold">
                ● Online
              </span>
            </div>
          </router-link>
        </div>
      </header>

      <!-- Page Content Canvas -->
      <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-7 bg-[#f8fafc]">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { useRouter } from 'vue-router';
import { useAuthStore } from '../stores/auth';
import { useRealtimeStore } from '../stores/realtime';
import { useSettingsStore } from '../stores/settings';
import api from '../services/api';

const router = useRouter();
const authStore = useAuthStore();
const realtimeStore = useRealtimeStore();
const settingsStore = useSettingsStore();

// Sidebar state
const mobileMenuOpen = ref(false);
const sidebarCollapsed = ref(localStorage.getItem('vristo_sidebar_collapsed') === 'true');

const toggleSidebarCollapse = () => {
  sidebarCollapsed.value = !sidebarCollapsed.value;
  localStorage.setItem('vristo_sidebar_collapsed', sidebarCollapsed.value ? 'true' : 'false');
};

// Notifications logic
const showNotifications = ref(false);
const notifications = ref([]);
const unreadNotifCount = ref(0);
const notifTab = ref('all');
let notifPollInterval = null;

const filteredNotifications = computed(() => {
  if (notifTab.value === 'unread') {
    return notifications.value.filter((n) => !n.read_at);
  }
  return notifications.value;
});

const getNotifBadgeClass = (type) => {
  if (type === 'assignment_priority') return 'bg-orange-100 text-[#f97316] border border-orange-200';
  if (type === 'loan_pending') return 'bg-sky-100 text-sky-600 border border-sky-200';
  if (type === 'loan_disetujui') return 'bg-emerald-100 text-emerald-700 border border-emerald-200';
  if (type === 'loan_diserahkan') return 'bg-teal-100 text-teal-700 border border-teal-200';
  if (type === 'loan_ditolak') return 'bg-red-100 text-red-600 border border-red-200';
  if (type === 'report_urgent') return 'bg-orange-100 text-[#f97316] border border-orange-200';
  return 'bg-emerald-100 text-emerald-700 border border-emerald-200';
};

const fetchNotifications = async () => {
  if (!authStore.token) return;
  try {
    const res = await api.get('/notifications');
    notifications.value = res.data.notifications || [];
    unreadNotifCount.value = res.data.unread_count || 0;
  } catch (err) {
    // Silent fail
  }
};

const toggleNotifications = () => {
  showNotifications.value = !showNotifications.value;
  if (showNotifications.value) {
    fetchNotifications();
  }
};

const markAllNotifsRead = async () => {
  try {
    await api.post('/notifications/mark-read');
    unreadNotifCount.value = 0;
    notifications.value.forEach((n) => {
      n.read_at = new Date().toISOString();
    });
  } catch (err) {
    console.error(err);
  }
};

const clickNotification = async (n) => {
  if (!n.read_at) {
    try {
      await api.post(`/notifications/${n.id}/read`);
      n.read_at = new Date().toISOString();
      if (unreadNotifCount.value > 0) unreadNotifCount.value--;
    } catch (e) {}
  }
  showNotifications.value = false;
  if (n.data?.url) {
    router.push(n.data.url);
  } else {
    router.push('/regions');
  }
};

const formatTimeAgo = (dateStr) => {
  if (!dateStr) return '';
  const diffSec = Math.floor((new Date() - new Date(dateStr)) / 1000);
  if (diffSec < 60) return 'Baru saja';
  if (diffSec < 3600) return `${Math.floor(diffSec / 60)} menit lalu`;
  if (diffSec < 86400) return `${Math.floor(diffSec / 3600)} jam lalu`;
  return `${Math.floor(diffSec / 86400)} hari lalu`;
};

const isMemberOnly = computed(() => {
  return authStore.user?.role === 'anggota';
});

const userInitial = computed(() => {
  if (isMemberOnly.value) {
    return authStore.user?.name ? authStore.user.name.charAt(0).toUpperCase() : 'M';
  }
  return 'AD';
});

const handleLogout = async () => {
  if (await window.$confirm('Apakah Anda yakin ingin keluar dari sistem?', 'Konfirmasi Keluar')) {
    realtimeStore.stopLiveSync();
    await authStore.logout();
    router.push('/login');
  }
};

onMounted(() => {
  settingsStore.fetchSettings();
  realtimeStore.startLiveSync(20000);
  fetchNotifications();
  notifPollInterval = setInterval(fetchNotifications, 15000);
});

onUnmounted(() => {
  realtimeStore.stopLiveSync();
  if (notifPollInterval) clearInterval(notifPollInterval);
});
</script>
