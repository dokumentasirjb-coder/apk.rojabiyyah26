<template>
  <DashboardLayout>
    <!-- ================================================================= -->
    <!-- TAMPILAN 1: PUSAT KENDALI SUPER ADMIN & ADMIN (Executive Center) -->
    <!-- ================================================================= -->
    <div v-if="isAdminOrSuperAdmin" class="space-y-6">
      <!-- Executive Command Hero Banner -->
      <div class="relative rounded-3xl p-6 sm:p-8 bg-gradient-to-br from-white via-emerald-50/40 to-slate-50 border border-slate-200 shadow-sm overflow-hidden">
        <!-- Ambient radial glow effects -->
        <div class="absolute -top-24 -right-24 w-80 h-80 rounded-full bg-emerald-400/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-80 h-80 rounded-full bg-teal-400/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
          <div class="space-y-2 max-w-2xl">
            <!-- Badges Bar -->
            <div class="flex items-center space-x-2 flex-wrap gap-y-1">
              <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                <span>Pusat Kendali Eksekutif</span>
              </span>
              <span class="px-2.5 py-0.5 rounded-full text-[11px] font-mono text-cyan-800 bg-cyan-100 border border-cyan-300">
                {{ authStore.roleLabel }}
              </span>
              <span class="px-2.5 py-0.5 rounded-full text-[11px] font-mono text-slate-700 bg-slate-100 border border-slate-300">
                ID: {{ authStore.user?.member_no || 'RJB-ADM-01' }}
              </span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">
              Ahlan wa Sahlan, {{ authStore.user?.name }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed">
              Monitoring operasional terpadu Haul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad & Rojabiyyah 2026. Pantau pergerakan logistik, sebaran posko relawan, dan neraca kas secara realtime.
            </p>
          </div>

          <!-- Quick Actions in Header -->
          <div class="flex flex-wrap sm:flex-nowrap items-center gap-2.5 self-start md:self-auto flex-shrink-0">
            <router-link
              to="/loans"
              class="px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-md shadow-emerald-500/20 transition flex items-center space-x-2 active:scale-95"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
              <span>+ Peminjaman</span>
            </router-link>

            <router-link
              to="/members"
              class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 font-bold text-xs transition flex items-center space-x-2 active:scale-95 shadow-sm"
            >
              <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
              <span>+ Anggota</span>
            </router-link>
          </div>
        </div>

        <!-- System Status Bar Indicator -->
        <div class="mt-6 pt-4 border-t border-slate-200 flex flex-wrap items-center justify-between text-[11px] text-slate-500 gap-2">
          <div class="flex items-center space-x-4">
            <span class="flex items-center space-x-1.5">
              <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
              <span class="text-slate-700 font-medium">Live Polling Aktif</span>
            </span>
            <span class="flex items-center space-x-1.5">
              <span class="w-2 h-2 rounded-full bg-cyan-500"></span>
              <span class="text-slate-700 font-medium">Database MySQL Terkoneksi</span>
            </span>
          </div>
          <div class="font-mono text-slate-500">
            Waktu Server: <span class="text-slate-900 font-bold">{{ currentTime }}</span>
          </div>
        </div>
      </div>

      <!-- KPI Executive Grid (6 Bright White Cards) -->
      <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3.5">
        <!-- 1. Total Anggota -->
        <router-link
          to="/members"
          class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-emerald-400 shadow-sm hover:shadow-md transition group flex flex-col justify-between"
        >
          <div class="flex items-center justify-between">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Total Anggota</span>
            <div class="w-7 h-7 rounded-lg bg-indigo-50 border border-indigo-200 flex items-center justify-center text-indigo-600">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="9" cy="7" r="4" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-2a4 4 0 014-4h4a4 4 0 014 4v2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3.13a4 4 0 010 7.75M21 21v-2a4 4 0 00-3-3.85"/></svg>
            </div>
          </div>
          <div class="my-2">
            <div class="text-2xl font-black text-slate-900 font-mono group-hover:text-indigo-600 transition">
              {{ stats.total_members || memberStats.total || 0 }}
            </div>
            <div class="text-[10px] text-slate-500 truncate mt-0.5">
              <span class="text-emerald-600 font-semibold">{{ memberStats.account }} Akun</span> &bull;
              <span class="text-indigo-600 font-semibold">{{ memberStats.google_form }} Form</span>
            </div>
          </div>
          <div class="text-[9px] text-indigo-600 font-medium">Buka Manajemen &rarr;</div>
        </router-link>

        <!-- 2. Inventaris Unit -->
        <router-link
          to="/inventory"
          class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-emerald-400 shadow-sm hover:shadow-md transition group flex flex-col justify-between"
        >
          <div class="flex items-center justify-between">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Inventaris</span>
            <div class="w-7 h-7 rounded-lg bg-cyan-50 border border-cyan-200 flex items-center justify-center text-cyan-600">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            </div>
          </div>
          <div class="my-2">
            <div class="text-2xl font-black text-slate-900 font-mono group-hover:text-cyan-600 transition">
              {{ stats.total_items || 0 }}
            </div>
            <div class="text-[10px] text-cyan-600 font-medium mt-0.5">Unit Aset Logistik</div>
          </div>
          <div class="text-[9px] text-cyan-600 font-medium">Katalog Inventaris &rarr;</div>
        </router-link>

        <!-- 3. Dipinjam (Aktif) -->
        <router-link
          to="/loans"
          class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-emerald-400 shadow-sm hover:shadow-md transition group flex flex-col justify-between"
        >
          <div class="flex items-center justify-between">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Dipinjam</span>
            <div class="w-7 h-7 rounded-lg bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
          </div>
          <div class="my-2">
            <div class="text-2xl font-black text-emerald-600 font-mono">
              {{ stats.active_loans || 0 }}
            </div>
            <div class="text-[10px] text-slate-500 mt-0.5">Unit di Luar Posko</div>
          </div>
          <div class="text-[9px] text-emerald-600 font-medium">Cek Logistik &rarr;</div>
        </router-link>

        <!-- 4. Antrean Izin (Pending ACC) -->
        <router-link
          to="/loans"
          :class="[
            'p-4 rounded-2xl border shadow-sm hover:shadow-md transition group flex flex-col justify-between',
            (stats.pending_loans || 0) > 0
              ? 'bg-amber-50 border-amber-300 ring-1 ring-amber-400/40'
              : 'bg-white border-slate-200 hover:border-amber-400'
          ]"
        >
          <div class="flex items-center justify-between">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Antrean ACC</span>
            <div class="w-7 h-7 rounded-lg bg-amber-100 border border-amber-200 flex items-center justify-center text-amber-600">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="12 6 12 12 16 14"/></svg>
            </div>
          </div>
          <div class="my-2">
            <div class="text-2xl font-black text-amber-600 font-mono flex items-center space-x-1.5">
              <span>{{ stats.pending_loans || 0 }}</span>
              <span v-if="(stats.pending_loans || 0) > 0" class="w-2 h-2 rounded-full bg-amber-500 animate-ping"></span>
            </div>
            <div class="text-[10px] text-amber-700 font-medium mt-0.5">Menunggu Verifikasi</div>
          </div>
          <div class="text-[9px] text-amber-700 font-bold">Respon Sekarang &rarr;</div>
        </router-link>

        <!-- 5. Posko Wilayah Aktif -->
        <router-link
          to="/regions"
          class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-emerald-400 shadow-sm hover:shadow-md transition group flex flex-col justify-between"
        >
          <div class="flex items-center justify-between">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Titik Posko</span>
            <div class="w-7 h-7 rounded-lg bg-purple-50 border border-purple-200 flex items-center justify-center text-purple-600">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3" stroke-width="2"/></svg>
            </div>
          </div>
          <div class="my-2">
            <div class="text-2xl font-black text-slate-900 font-mono group-hover:text-purple-600 transition">
              {{ stats.total_regions || 0 }}
            </div>
            <div class="text-[10px] text-slate-500 mt-0.5">Titik Pantau Lapangan</div>
          </div>
          <div class="text-[9px] text-purple-600 font-medium">Buka Wilayah &rarr;</div>
        </router-link>

        <!-- 6. Saldo Kas Riil -->
        <router-link
          to="/cash"
          class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-emerald-400 shadow-sm hover:shadow-md transition group flex flex-col justify-between"
        >
          <div class="flex items-center justify-between">
            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Saldo Kas</span>
            <div class="w-7 h-7 rounded-lg bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-600">
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V6m0 12v-2m0 0c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
          </div>
          <div class="my-2">
            <div class="text-sm sm:text-base font-black text-emerald-700 font-mono truncate">
              Rp {{ formatRupiah(stats.cash_balance) }}
            </div>
            <div class="text-[10px] text-emerald-600 font-medium mt-0.5">Kas Bersih Riil</div>
          </div>
          <div class="text-[9px] text-emerald-600 font-medium">Buku Kas &rarr;</div>
        </router-link>
      </div>

      <!-- Quick Command Hub (4 Cards) -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <router-link
          to="/members"
          class="p-5 rounded-2xl bg-white border border-slate-200 hover:border-emerald-400 shadow-sm hover:shadow-md transition group flex items-start space-x-3.5"
        >
          <div class="w-11 h-11 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 group-hover:scale-105 transition flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="9" cy="7" r="4" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-2a4 4 0 014-4h4a4 4 0 014 4v2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 3.13a4 4 0 010 7.75M21 21v-2a4 4 0 00-3-3.85"/></svg>
          </div>
          <div>
            <h4 class="font-bold text-slate-800 text-xs sm:text-sm group-hover:text-emerald-700 transition">Kontrol Akun &amp; Anggota</h4>
            <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">Kelola akun portal, pisahkan data Google Form &amp; export/import.</p>
          </div>
        </router-link>

        <router-link
          to="/loans"
          class="p-5 rounded-2xl bg-white border border-slate-200 hover:border-cyan-400 shadow-sm hover:shadow-md transition group flex items-start space-x-3.5"
        >
          <div class="w-11 h-11 rounded-xl bg-cyan-50 border border-cyan-200 flex items-center justify-center text-cyan-600 group-hover:scale-105 transition flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
          </div>
          <div>
            <h4 class="font-bold text-slate-800 text-xs sm:text-sm group-hover:text-cyan-700 transition">Persetujuan &amp; Surat Jalan</h4>
            <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">ACC permohonan logistik, serah terima &amp; cetak lembar stiker F4.</p>
          </div>
        </router-link>

        <router-link
          to="/regions"
          class="p-5 rounded-2xl bg-white border border-slate-200 hover:border-purple-400 shadow-sm hover:shadow-md transition group flex items-start space-x-3.5"
        >
          <div class="w-11 h-11 rounded-xl bg-purple-50 border border-purple-200 flex items-center justify-center text-purple-600 group-hover:scale-105 transition flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3" stroke-width="2"/></svg>
          </div>
          <div>
            <h4 class="font-bold text-slate-800 text-xs sm:text-sm group-hover:text-purple-700 transition">Posko &amp; Penugasan</h4>
            <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">Plotting anggota ke posko, koordinasi wilayah &amp; pantau presensi.</p>
          </div>
        </router-link>

        <router-link
          to="/settings"
          class="p-5 rounded-2xl bg-white border border-slate-200 hover:border-rose-400 shadow-sm hover:shadow-md transition group flex items-start space-x-3.5"
        >
          <div class="w-11 h-11 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-600 group-hover:scale-105 transition flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/></svg>
          </div>
          <div>
            <h4 class="font-bold text-slate-800 text-xs sm:text-sm group-hover:text-rose-700 transition">Pengaturan Sistem</h4>
            <p class="text-[11px] text-slate-500 mt-0.5 leading-snug">Identitas acara, lembaga mitra, admin pengelola &amp; snapshot data.</p>
          </div>
        </router-link>
      </div>

      <!-- Live Activities Dual Monitor -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Panel 1: Peminjaman Logistik Terkini -->
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm flex flex-col">
          <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
              <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
              <h3 class="font-bold text-slate-800 text-sm">Peminjaman Logistik Terkini</h3>
            </div>
            <router-link to="/loans" class="text-xs font-semibold text-emerald-600 hover:text-emerald-700 transition flex items-center space-x-1">
              <span>Buka Semua</span>
              <span>&rarr;</span>
            </router-link>
          </div>

          <div class="divide-y divide-slate-100 flex-1">
            <div
              v-for="loan in stats.recent_loans"
              :key="loan.id"
              class="p-4 flex items-center justify-between hover:bg-slate-50 transition text-xs"
            >
              <div class="space-y-1">
                <div class="flex items-center space-x-2">
                  <span class="font-mono font-bold text-emerald-700">{{ loan.loan_no }}</span>
                  <span class="text-slate-800 font-semibold">{{ loan.borrower?.name }}</span>
                </div>
                <div class="text-[11px] text-slate-500">
                  Keperluan: <span class="text-slate-700">{{ loan.purpose }}</span>
                </div>
              </div>
              <span
                :class="getStatusBadgeClass(loan.status)"
                class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border font-mono"
              >
                {{ loan.status }}
              </span>
            </div>

            <div v-if="!stats.recent_loans?.length" class="p-8 text-center text-xs text-slate-400">
              Belum ada riwayat permohonan logistik.
            </div>
          </div>
        </div>

        <!-- Panel 2: Laporan Kendala & Status Posko -->
        <div class="bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm flex flex-col">
          <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
            <div class="flex items-center space-x-2.5">
              <span class="w-2.5 h-2.5 rounded-full bg-rose-500 animate-pulse"></span>
              <h3 class="font-bold text-slate-800 text-sm">Laporan Situasi Posko Realtime</h3>
            </div>
            <router-link to="/reports" class="text-xs font-semibold text-rose-600 hover:text-rose-700 transition flex items-center space-x-1">
              <span>Buka Laporan</span>
              <span>&rarr;</span>
            </router-link>
          </div>

          <div class="divide-y divide-slate-100 flex-1">
            <div
              v-for="rep in stats.recent_reports"
              :key="rep.id"
              class="p-4 flex items-center justify-between hover:bg-slate-50 transition text-xs"
            >
              <div class="space-y-1 max-w-[70%]">
                <div class="font-bold text-slate-800 flex items-center space-x-1.5">
                  <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3" stroke-width="2"/></svg>
                  <span>{{ rep.region?.name || 'Posko Umum' }}</span>
                </div>
                <p class="text-[11px] text-slate-500 line-clamp-1">{{ rep.description }}</p>
              </div>
              <span
                :class="getReportStatusClass(rep.status)"
                class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider border font-mono whitespace-nowrap"
              >
                {{ rep.status ? rep.status.replace('_', ' ') : 'NORMAL' }}
              </span>
            </div>

            <div v-if="!stats.recent_reports?.length" class="p-8 text-center text-xs text-slate-400">
              Belum ada laporan kendala posko yang masuk.
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ================================================================= -->
    <!-- TAMPILAN 2: PORTAL EKSKLUSIF USER / ANGGOTA (Member Hub & E-KTA) -->
    <!-- ================================================================= -->
    <div v-else class="space-y-6">
      <!-- Member Welcome Banner -->
      <div class="relative rounded-3xl p-6 sm:p-8 bg-gradient-to-br from-white via-emerald-50/40 to-slate-50 border border-slate-200 shadow-sm overflow-hidden">
        <div class="absolute -top-20 -right-20 w-64 h-64 rounded-full bg-emerald-400/10 blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
          <div class="space-y-2">
            <div class="flex items-center space-x-2">
              <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-300">
                <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                <span>Portal Resmi Panitia &amp; Relawan</span>
              </span>
              <span class="px-2.5 py-0.5 rounded-full text-[11px] font-mono text-cyan-800 bg-cyan-100 border border-cyan-300">
                ID: {{ authStore.user?.member_no || '-' }}
              </span>
            </div>

            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
              Ahlan wa Sahlan, {{ authStore.user?.name }}
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 leading-relaxed max-w-2xl">
              Terima kasih atas khidmah dan dedikasi Anda di kepanitiaan Rojabiyyah 2026. Di portal ini Anda dapat memeriksa kartu identitas kepanitiaan, jadwal titik posko, dan mengajukan peminjaman alat operasional.
            </p>
          </div>

          <div class="flex items-center space-x-3 self-start md:self-auto flex-shrink-0">
            <router-link
              to="/loans"
              class="px-5 py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-md shadow-emerald-500/20 transition flex items-center space-x-2 active:scale-95"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
              <span>Ajukan Peminjaman Barang</span>
            </router-link>
          </div>
        </div>
      </div>

      <!-- Grid: E-KTA Holographic Card + Detail Penugasan -->
      <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <!-- Kolom Kiri: E-KTA Panitia (Digital ID Card Holographic Style) -->
        <div class="lg:col-span-5 flex flex-col">
          <div class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2.5 flex items-center space-x-1.5">
            <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
            <span>Kartu Tanda Anggota Digital (E-KTA)</span>
          </div>

          <div class="relative w-full rounded-3xl p-6 bg-gradient-to-br from-emerald-800 via-teal-900 to-slate-900 border-2 border-emerald-400/50 shadow-xl text-white overflow-hidden flex flex-col justify-between min-h-[290px] group">
            <!-- Hologram shimmer overlay -->
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent -translate-x-full group-hover:translate-x-full transition-transform duration-1000 pointer-events-none"></div>

            <!-- Header E-KTA -->
            <div class="flex items-start justify-between border-b border-white/20 pb-3">
              <div class="flex items-center space-x-2.5">
                <div class="w-8 h-8 rounded-xl bg-white/10 border border-white/30 flex items-center justify-center text-emerald-300 font-black text-sm">
                  R
                </div>
                <div>
                  <div class="text-[10px] font-mono text-emerald-300 uppercase tracking-widest font-bold">Kepanitiaan Resmi</div>
                  <div class="text-xs font-black text-white">ROJABIYYAH 2026</div>
                </div>
              </div>
              <div class="text-right">
                <span class="px-2 py-0.5 rounded-full text-[9px] font-mono font-bold bg-emerald-500/30 text-emerald-200 border border-emerald-400/40">
                  STATUS AKTIF
                </span>
              </div>
            </div>

            <!-- Body E-KTA -->
            <div class="my-4 flex items-center space-x-4">
              <!-- Avatar or Photo -->
              <div class="w-16 h-16 rounded-2xl bg-white/10 border-2 border-emerald-300/50 overflow-hidden flex items-center justify-center flex-shrink-0 text-xl font-bold shadow-md">
                <img
                  v-if="authStore.user?.member?.photo_path"
                  :src="authStore.user.member.photo_path"
                  :alt="authStore.user.name"
                  class="w-full h-full object-cover"
                />
                <span v-else class="text-emerald-200">{{ authStore.user?.name ? authStore.user.name.charAt(0) : 'A' }}</span>
              </div>

              <!-- Name & Divisi -->
              <div class="min-w-0 flex-1">
                <h3 class="text-base font-black text-white truncate">{{ authStore.user?.name }}</h3>
                <div class="text-xs font-mono text-cyan-200 font-bold mt-0.5">
                  ID: {{ authStore.user?.member_no || 'RJB-ANGGOTA' }}
                </div>
                <div class="text-[11px] text-slate-200 mt-1 flex items-center space-x-1.5">
                  <span class="px-2 py-0.5 rounded bg-black/30 text-teal-200 font-semibold text-[10px]">
                    {{ authStore.user?.member?.division?.name || 'Panitia Lapangan' }}
                  </span>
                  <span v-if="authStore.user?.member?.uniform_size" class="px-1.5 py-0.5 rounded bg-black/30 text-cyan-200 font-mono text-[10px] font-bold">
                    {{ authStore.user?.member?.uniform_size }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Footer E-KTA -->
            <div class="pt-3 border-t border-white/20 flex items-center justify-between text-[10px] text-emerald-200 font-mono">
              <div>
                <span>Domisili: </span>
                <strong class="text-white">{{ authStore.user?.member?.domicile || 'Jombang' }}</strong>
              </div>
              <div>
                <span>No. WA: </span>
                <strong class="text-white">{{ authStore.user?.phone_wa || '-' }}</strong>
              </div>
            </div>
          </div>
        </div>

        <!-- Kolom Kanan: Status Penugasan Posko & Peminjaman Saya -->
        <div class="lg:col-span-7 space-y-4 flex flex-col justify-between">
          <!-- Card 1: Seluruh Penugasan Saya (Posko, Proyektor, Liputan, Multicam) -->
          <div class="p-5 rounded-3xl bg-white border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-3 pb-3 border-b border-slate-100">
              <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">
                  Penugasan Resmi Anda ({{ allUserDuties.length }} Tugas)
                </h3>
              </div>
              <div class="flex items-center space-x-2">
                <router-link to="/jadwal-kerja" class="text-xs text-emerald-600 hover:text-emerald-700 font-semibold hidden sm:inline">
                  Jadwal Kerja &rarr;
                </router-link>
                <router-link to="/pembagian-proyektor" class="text-xs text-amber-600 hover:text-amber-700 font-semibold hidden sm:inline">
                  Proyektor &rarr;
                </router-link>
              </div>
            </div>

            <div v-if="allUserDuties.length > 0" class="space-y-3">
              <div
                v-for="duty in allUserDuties"
                :key="duty.id"
                class="p-4 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col sm:flex-row sm:items-start justify-between gap-3 hover:border-emerald-300 transition"
              >
                <div class="space-y-1.5 flex-1 min-w-0">
                  <div class="flex items-center space-x-2 flex-wrap gap-y-1">
                    <span
                      :class="duty.badgeClass"
                      class="px-2 py-0.5 rounded-md text-[10px] font-bold uppercase tracking-wider border shadow-xs"
                    >
                      {{ duty.badge }}
                    </span>
                    <span v-if="duty.titik" class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                      {{ duty.titik }}
                    </span>
                  </div>

                  <h4 class="font-extrabold text-slate-900 text-sm tracking-tight">{{ duty.title }}</h4>
                  <p class="text-xs text-slate-600 leading-relaxed">{{ duty.detail }}</p>

                  <div v-if="duty.picName" class="text-[11px] text-slate-500 flex items-center space-x-1">
                    <span>Koordinator / PIC:</span>
                    <strong class="text-slate-700">{{ duty.picName }}</strong>
                  </div>
                </div>

                <!-- Action Buttons: Kirim ke WA Saya & Hubungi PIC -->
                <div class="flex items-center space-x-2 flex-shrink-0 self-start sm:self-center">
                  <button
                    type="button"
                    @click="sendDutyToMyWhatsApp(duty)"
                    class="px-3 py-1.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold flex items-center space-x-1.5 transition shadow-sm active:scale-95"
                    title="Simpan / Kirim rincian tugas ke nomor WhatsApp Anda"
                  >
                    <svg class="w-3.5 h-3.5" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.652c1.746.951 3.71 1.452 5.711 1.453h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    <span>Kirim ke WA Saya</span>
                  </button>

                  <a
                    v-if="duty.picPhone"
                    :href="'https://wa.me/' + cleanPhone(duty.picPhone)"
                    target="_blank"
                    class="px-2.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold flex items-center space-x-1 transition"
                    title="Hubungi PIC / Koordinator via WhatsApp"
                  >
                    <span>PIC</span>
                  </a>
                </div>
              </div>
            </div>

            <div v-else class="p-6 text-center text-xs text-slate-500 bg-slate-50 rounded-2xl border border-dashed border-slate-200">
              <svg class="w-8 h-8 mx-auto mb-2 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="12 6 12 12 16 14"/></svg>
              Anda belum memiliki plot penugasan di Posko, Proyektor, Liputan, atau Shooting Multicam. Hubungi admin/koordinator divisi untuk konfirmasi tugas.
            </div>
          </div>

          <!-- Card 2: Logistik yang Sedang Saya Pinjam -->
          <div class="p-5 rounded-3xl bg-white border border-slate-200 shadow-sm">
            <div class="flex items-center justify-between mb-3 pb-3 border-b border-slate-100">
              <div class="flex items-center space-x-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500"></span>
                <h3 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Logistik Dipinjam Saya</h3>
              </div>
              <router-link to="/loans" class="text-xs text-emerald-600 hover:text-emerald-700 font-semibold">
                Riwayat Peminjaman &rarr;
              </router-link>
            </div>

            <div v-if="myLoans.length > 0" class="divide-y divide-slate-100">
              <div
                v-for="l in myLoans.slice(0, 3)"
                :key="l.id"
                class="py-3 flex items-center justify-between text-xs"
              >
                <div>
                  <div class="font-bold text-slate-900 flex items-center space-x-2">
                    <span class="font-mono text-emerald-700">{{ l.loan_no }}</span>
                    <span>&bull;</span>
                    <span>{{ l.purpose }}</span>
                  </div>
                  <div class="text-[11px] text-slate-500 mt-0.5">
                    Batas Kembali: <strong class="text-amber-700 font-mono">{{ formatDate(l.due_at) }}</strong>
                  </div>
                </div>
                <span
                  :class="getStatusBadgeClass(l.status)"
                  class="px-2.5 py-0.5 rounded-full text-[10px] font-bold font-mono uppercase border"
                >
                  {{ l.status }}
                </span>
              </div>
            </div>

            <div v-else class="p-4 text-center text-xs text-slate-500">
              Anda tidak sedang memegang pinjaman barang logistik saat ini.
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Action Buttons for Anggota -->
      <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <router-link
          to="/loans"
          class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-emerald-400 transition flex items-center space-x-3.5 shadow-sm hover:shadow-md group"
        >
          <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 group-hover:scale-105 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          </div>
          <div>
            <h4 class="font-bold text-slate-800 text-xs group-hover:text-emerald-700 transition">Pinjam Alat Logistik</h4>
            <p class="text-[10px] text-slate-500">Kamera, HT, kabel, sound, proyektor</p>
          </div>
        </router-link>

        <router-link
          to="/reports"
          class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-rose-400 transition flex items-center space-x-3.5 shadow-sm hover:shadow-md group"
        >
          <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-center text-rose-600 group-hover:scale-105 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
          </div>
          <div>
            <h4 class="font-bold text-slate-800 text-xs group-hover:text-rose-700 transition">Lapor Kendala Posko</h4>
            <p class="text-[10px] text-slate-500">Laporkan kendala darurat di lapangan</p>
          </div>
        </router-link>

        <router-link
          to="/regions"
          class="p-4 rounded-2xl bg-white border border-slate-200 hover:border-cyan-400 transition flex items-center space-x-3.5 shadow-sm hover:shadow-md group"
        >
          <div class="w-10 h-10 rounded-xl bg-cyan-50 border border-cyan-200 flex items-center justify-center text-cyan-600 group-hover:scale-105 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
          </div>
          <div>
            <h4 class="font-bold text-slate-800 text-xs group-hover:text-cyan-700 transition">Peta &amp; Posko Lapangan</h4>
            <p class="text-[10px] text-slate-500">Cek lokasi titik posko dan kontak PIC</p>
          </div>
        </router-link>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import DashboardLayout from '../../layouts/DashboardLayout.vue';
import { useAuthStore } from '../../stores/auth';
import { useRealtimeStore } from '../../stores/realtime';
import api from '../../services/api';

const authStore = useAuthStore();
const realtimeStore = useRealtimeStore();

const myAssignments = ref([]);
const myLoans = ref([]);
const operationalDuties = ref([]);
const memberStats = ref({ total: 0, account: 0, google_form: 0 });
const currentTime = ref('');

const isAdminOrSuperAdmin = computed(() => {
  const r = authStore.user?.role;
  return ['super_admin', 'admin', 'bendahara'].includes(r);
});

const isUserMatch = (text) => {
  if (!text) return false;
  const raw = String(text).toLowerCase();
  const name = (authStore.user?.name || '').trim().toLowerCase();
  const username = (authStore.user?.username || '').trim().toLowerCase();
  if (name && (raw === name || raw.includes(name) || name.includes(raw))) return true;
  if (username && (raw === username || raw.includes(username) || username.includes(raw))) return true;
  return false;
};

const allUserDuties = computed(() => {
  const list = [];

  // 1. Posko penugasan lapangan
  myAssignments.value.forEach((asg) => {
    list.push({
      id: 'posko_' + asg.id,
      module: 'posko',
      badge: 'Posko Wilayah',
      badgeClass: 'bg-amber-100 text-amber-800 border-amber-300',
      title: asg.region?.name || 'Posko Wilayah',
      role: asg.role_in_post || 'Petugas Jaga',
      detail: `Wilayah: ${asg.region?.name || '-'} • Harap standby dan koordinasi dengan posko`,
      picName: asg.region?.coordinator_name || 'Koordinator Posko',
      picPhone: asg.region?.coordinator_phone || '',
    });
  });

  // 2. Proyektor, Liputan, Multicam duties
  operationalDuties.value.forEach((d) => {
    list.push(d);
  });

  return list;
});

const fetchOperationalDuties = async () => {
  try {
    const res = await api.get('/public/overview');
    const data = res.data;
    const duties = [];

    // 1. Proyektor Teams
    if (Array.isArray(data.proyektor_teams)) {
      data.proyektor_teams.forEach((team) => {
        const titik = team.titik || 'Titik Proyektor';
        if (team.korwil && isUserMatch(team.korwil)) {
          duties.push({
            id: 'proyektor_' + titik + '_korwil',
            module: 'proyektor',
            badge: 'Korwil Proyektor',
            badgeClass: 'bg-amber-100 text-amber-800 border-amber-300',
            title: `Koordinator Wilayah (Korwil) ${titik}`,
            role: 'Koordinator Wilayah (Korwil)',
            detail: `Memimpin display layar, kabel, dan koordinasi operasional ${titik}`,
            titik,
            picName: team.korwil,
          });
        }
        if (team.kabel && isUserMatch(team.kabel)) {
          duties.push({
            id: 'proyektor_' + titik + '_kabel',
            module: 'proyektor',
            badge: 'Tim Kabel',
            badgeClass: 'bg-blue-100 text-blue-800 border-blue-300',
            title: `Tim Kabel & Transmisi ${titik}`,
            role: 'Tim Kabel & FO',
            detail: `Instalasi kabel display, jalur splitter, dan standby transmisi ${titik}`,
            titik,
            picName: team.korwil,
          });
        }
        if (Array.isArray(team.items)) {
          team.items.forEach((item) => {
            if (item.petugas && isUserMatch(item.petugas)) {
              duties.push({
                id: 'proyektor_' + titik + '_' + (item.kode || 'screen'),
                module: 'proyektor',
                badge: 'Petugas Layar',
                badgeClass: 'bg-emerald-100 text-emerald-800 border-emerald-300',
                title: `${item.kode || 'Perangkat Display'} (${titik})`,
                role: 'Petugas Monitor / Display',
                detail: `Lokasi: ${item.wilayah || 'Area Pos'} • Standby di lokasi monitor`,
                titik,
                lokasi: item.wilayah,
                kode: item.kode,
                picName: team.korwil,
              });
            }
          });
        }
      });
    }

    // 2. Jadwal Tim Liputan (Fotografer & Videografer)
    if (Array.isArray(data.jadwal_tim)) {
      data.jadwal_tim.forEach((row, idx) => {
        if (row.fotografer && isUserMatch(row.fotografer)) {
          duties.push({
            id: 'liputan_foto_' + idx,
            module: 'liputan',
            badge: 'Fotografer',
            badgeClass: 'bg-emerald-100 text-emerald-800 border-emerald-300',
            title: `Fotografer: ${row.momen || 'Liputan'}`,
            role: 'Fotografer Dokumentasi',
            detail: `Hari ${row.hari || '-'} (${row.waktu || '-'}) • Lokasi: ${row.wilayah || '-'}`,
            hari: row.hari,
            waktu: row.waktu,
            momen: row.momen,
            lokasi: row.wilayah,
          });
        }
        if (row.videografer && isUserMatch(row.videografer)) {
          duties.push({
            id: 'liputan_video_' + idx,
            module: 'liputan',
            badge: 'Videografer',
            badgeClass: 'bg-teal-100 text-teal-800 border-teal-300',
            title: `Videografer: ${row.momen || 'Liputan'}`,
            role: 'Videografer Dokumentasi',
            detail: `Hari ${row.hari || '-'} (${row.waktu || '-'}) • Lokasi: ${row.wilayah || '-'}`,
            hari: row.hari,
            waktu: row.waktu,
            momen: row.momen,
            lokasi: row.wilayah,
          });
        }
      });
    }

    // 3. Jadwal Shooting Multicam (Kameramen)
    if (Array.isArray(data.jadwal_shooting)) {
      data.jadwal_shooting.forEach((sRow, idx) => {
        if (sRow.kameramen && isUserMatch(sRow.kameramen)) {
          duties.push({
            id: 'shooting_' + idx,
            module: 'shooting',
            badge: 'Kameramen Multicam',
            badgeClass: 'bg-purple-100 text-purple-800 border-purple-300',
            title: `Kameramen: ${sRow.shooting || 'Kamera Multicam'}`,
            role: 'Kameramen Multicam',
            detail: `Hari ${sRow.hari || '-'} (${sRow.waktu || '-'}) • Acara: ${sRow.momen || '-'} • Lokasi: ${sRow.wilayah || '-'}`,
            hari: sRow.hari,
            waktu: sRow.waktu,
            momen: sRow.momen,
            lokasi: sRow.wilayah,
            shooting: sRow.shooting,
          });
        }
      });
    }

    operationalDuties.value = duties;
  } catch (err) {
    // Silent fail
  }
};

const sendDutyToMyWhatsApp = (duty) => {
  const userPhone = authStore.user?.phone_wa || authStore.user?.member?.phone_wa || '';
  const clean = cleanPhone(userPhone);

  let msg = `*PENGINGAT PENUGASAN SAYA*\n`;
  msg += `*PANITIA ROJABIYYAH & HAUL 2026*\n`;
  msg += `_Pondok Pesantren Al-Muhibbin Tambakberas_\n\n`;
  msg += `Nama: *${authStore.user?.name || 'Panitia'}*\n`;
  msg += `📋 *Tugas*: ${duty.title}\n`;
  msg += `🎖️ *Peran*: ${duty.role}\n`;
  if (duty.detail) msg += `📍 *Detail*: ${duty.detail}\n`;
  if (duty.titik) msg += `📍 *Wilayah/Titik*: ${duty.titik}\n`;
  if (duty.lokasi) msg += `📌 *Lokasi*: ${duty.lokasi}\n`;
  if (duty.hari) msg += `📅 *Hari*: ${duty.hari}\n`;
  if (duty.waktu) msg += `⏰ *Waktu*: ${duty.waktu}\n`;
  if (duty.momen) msg += `🎯 *Momen*: ${duty.momen}\n`;
  if (duty.shooting) msg += `🎥 *Kamera*: ${duty.shooting}\n`;
  msg += `\nStandby di lokasi 30 menit sebelum jadwal dimulai.\nPortal Panitia: ${window.location.origin}/dashboard\n\nSemangat berkhidmah!`;

  if (clean) {
    window.open(`https://wa.me/${clean}?text=${encodeURIComponent(msg)}`, '_blank');
  } else {
    window.open(`https://wa.me/?text=${encodeURIComponent(msg)}`, '_blank');
  }
};

const updateClock = () => {
  const now = new Date();
  currentTime.value = now.toLocaleTimeString('id-ID', {
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
  });
};

const fetchMyAssignments = async () => {
  if (!authStore.user?.id) return;
  try {
    const res = await api.get('/assignments', { params: { user_id: authStore.user.id } });
    myAssignments.value = res.data || [];
  } catch (err) {
    // Silent fail
  }
};

const fetchMyLoans = async () => {
  try {
    const res = await api.get('/loans');
    myLoans.value = res.data.data || res.data || [];
  } catch (err) {
    // Silent fail
  }
};

const fetchMemberStats = async () => {
  if (!isAdminOrSuperAdmin.value) return;
  try {
    const res = await api.get('/members', { params: { per_page: 1 } });
    if (res.data.stats) {
      memberStats.value = res.data.stats;
    }
  } catch (err) {
    // Silent fail
  }
};

onMounted(() => {
  updateClock();
  setInterval(updateClock, 1000);

  fetchMyAssignments();
  fetchMyLoans();
  fetchMemberStats();
  fetchOperationalDuties();
});

const stats = computed(() => realtimeStore.stats);

const formatRupiah = (val) => {
  return new Intl.NumberFormat('id-ID').format(val || 0);
};

const formatDate = (val) => {
  if (!val) return '-';
  try {
    const d = new Date(val);
    return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
  } catch (e) {
    return val;
  }
};

const cleanPhone = (phone) => {
  if (!phone) return '';
  let cleaned = String(phone).replace(/\D/g, '');
  if (cleaned.startsWith('0')) {
    cleaned = '62' + cleaned.slice(1);
  }
  return cleaned;
};

const getStatusBadgeClass = (status) => {
  const map = {
    diajukan: 'bg-amber-50 text-amber-700 border-amber-200',
    disetujui: 'bg-blue-50 text-blue-700 border-blue-200',
    diserahkan: 'bg-emerald-50 text-emerald-700 border-emerald-200',
    dikembalikan: 'bg-slate-100 text-slate-700 border-slate-200',
    ditolak: 'bg-rose-50 text-rose-700 border-rose-200',
  };
  return map[status] || 'bg-slate-100 text-slate-700 border-slate-200';
};

const getReportStatusClass = (status) => {
  const map = {
    normal: 'bg-emerald-50 text-emerald-700 border-emerald-200',
    perlu_perhatian: 'bg-amber-50 text-amber-700 border-amber-200',
    rusak_kendala: 'bg-rose-50 text-rose-700 border-rose-200',
  };
  return map[status] || 'bg-slate-100 text-slate-700 border-slate-200';
};
</script>
