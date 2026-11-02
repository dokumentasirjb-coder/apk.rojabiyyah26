<template>
  <DashboardLayout>
    <div class="space-y-6">
      <!-- Title & Actions -->
      <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
          <div class="flex items-center space-x-2.5">
            <div class="w-9 h-9 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-center text-emerald-600 shadow-xs">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <div>
              <h2 class="text-xl font-black text-slate-900 tracking-tight">
                Pelaporan Lapangan &amp; Kendala Posko
              </h2>
            </div>
          </div>
          <p class="text-xs text-slate-500 mt-1">
            Monitoring kondisi operasional posko dan titik tugas, penerimaan laporan kendala teknis dari anggota di lapangan, serta dokumentasi solusi penanganan.
          </p>
        </div>

        <div class="flex flex-wrap items-center gap-2.5">
          <!-- Refresh Button -->
          <button
            @click="fetchReports"
            :disabled="loading"
            class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 text-xs font-semibold transition shadow-xs disabled:opacity-50"
            title="Muat ulang laporan terbaru"
          >
            <svg :class="loading ? 'animate-spin' : ''" class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            <span>Segarkan</span>
          </button>

          <!-- Buka Titik Tugas -->
          <router-link
            to="/regions"
            class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-slate-50 hover:bg-slate-100 border border-slate-200 text-slate-700 text-xs font-semibold transition shadow-xs"
          >
            <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3" stroke-width="2"/></svg>
            <span>Daftar Titik Tugas &amp; Pos →</span>
          </router-link>

          <!-- Buat Laporan Baru -->
          <button
            @click="openReportModal"
            class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:brightness-105 text-white text-xs font-bold shadow-md shadow-emerald-600/20 transition active:scale-95"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            <span>+ Buat Laporan Lapangan</span>
          </button>
        </div>
      </div>

      <!-- Quick Summary Cards -->
      <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
          <div>
            <span class="text-[10px] sm:text-xs font-extrabold text-slate-500 uppercase tracking-wider block">Total Laporan</span>
            <div class="text-xl sm:text-2xl font-black text-slate-900 mt-1 font-mono">
              {{ reports.length }}
            </div>
            <span class="text-[10px] text-slate-500 font-semibold">Semua Riwayat Laporan</span>
          </div>
          <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-200 text-slate-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
          <div>
            <span class="text-[10px] sm:text-xs font-extrabold text-rose-600 uppercase tracking-wider block">Kendala / Butuh Bantuan</span>
            <div class="text-xl sm:text-2xl font-black text-rose-600 mt-1 font-mono">
              {{ countKendala }}
            </div>
            <span class="text-[10px] text-rose-600 font-semibold">Perlu Tindakan Cepat</span>
          </div>
          <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
          </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
          <div>
            <span class="text-[10px] sm:text-xs font-extrabold text-amber-600 uppercase tracking-wider block">Perlu Perhatian</span>
            <div class="text-xl sm:text-2xl font-black text-amber-600 mt-1 font-mono">
              {{ countPerhatian }}
            </div>
            <span class="text-[10px] text-amber-700 font-semibold">Antisipasi Posko</span>
          </div>
          <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200 text-amber-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-xs flex items-center justify-between">
          <div>
            <span class="text-[10px] sm:text-xs font-extrabold text-emerald-600 uppercase tracking-wider block">Kondisi Normal / Aman</span>
            <div class="text-xl sm:text-2xl font-black text-emerald-600 mt-1 font-mono">
              {{ countNormal }}
            </div>
            <span class="text-[10px] text-emerald-700 font-semibold">Operasional Lancar</span>
          </div>
          <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
        </div>
      </div>

      <!-- Filters Bar -->
      <div class="bg-white p-3.5 sm:p-4 rounded-2xl shadow-xs border border-slate-200 flex flex-wrap gap-2.5 items-center text-xs">
        <div class="flex-1 min-w-[200px]">
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari uraian laporan, nama pelapor, atau posko..."
            class="w-full px-3.5 py-2 rounded-xl bg-white border border-slate-300 text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500"
          />
        </div>

        <select
          v-model="filters.region_id"
          @change="fetchReports"
          class="px-3 py-2 border border-slate-300 rounded-xl bg-white text-slate-800 focus:outline-none focus:border-emerald-500"
        >
          <option value="">Semua Posko / Wilayah</option>
          <option v-for="r in regions" :key="r.id" :value="r.id">{{ r.name }}</option>
        </select>

        <select
          v-model="filters.status"
          @change="fetchReports"
          class="px-3 py-2 border border-slate-300 rounded-xl bg-white text-slate-800 focus:outline-none focus:border-emerald-500"
        >
          <option value="">Semua Kondisi</option>
          <option value="normal">Normal (Lancar)</option>
          <option value="perlu_perhatian">Perlu Perhatian</option>
          <option value="rusak_kendala">Kendala / Rusak</option>
        </select>

        <select
          v-model="filters.followup_status"
          @change="fetchReports"
          class="px-3 py-2 border border-slate-300 rounded-xl bg-white text-slate-800 focus:outline-none focus:border-emerald-500"
        >
          <option value="">Semua Status Respon</option>
          <option value="open">Open (Menunggu Respon)</option>
          <option value="in_progress">In Progress (Ditindaklanjuti)</option>
          <option value="resolved">Resolved (Selesai)</option>
        </select>
      </div>

      <!-- Reports List -->
      <div class="space-y-4">
        <!-- Empty State -->
        <div v-if="filteredReports.length === 0" class="bg-white rounded-3xl p-12 text-center text-slate-400 border border-slate-200">
          <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center mx-auto mb-3 text-emerald-600">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          </div>
          <p class="text-sm font-bold text-slate-700">Belum Ada Laporan Lapangan</p>
          <p class="text-xs text-slate-500 mt-1">Laporan kondisi posko dan kendala teknis dari anggota di lapangan akan tampil di sini.</p>
          <button
            @click="openReportModal"
            class="mt-4 px-4 py-2 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] text-white text-xs font-bold shadow-md shadow-emerald-600/20"
          >
            + Buat Laporan Lapangan Sekarang
          </button>
        </div>

        <!-- Report Card Items -->
        <div
          v-for="rep in filteredReports"
          :key="rep.id"
          class="bg-white rounded-2xl sm:rounded-3xl p-4 sm:p-6 shadow-xs border border-slate-200 hover:border-emerald-300 transition space-y-3.5"
        >
          <!-- Top Row: Posko & Status Badge & Reporter -->
          <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-3 border-b border-slate-100 gap-2">
            <div class="flex flex-wrap items-center gap-2">
              <span
                :class="getStatusBadgeClass(rep.status)"
                class="px-2.5 py-1 font-black rounded-full text-[10px] uppercase tracking-wider shadow-xs"
              >
                {{ formatStatusLabel(rep.status) }}
              </span>
              <h3 class="font-black text-slate-900 text-sm sm:text-base flex items-center space-x-1.5">
                <span>📍</span>
                <span>{{ rep.region?.name || 'Posko Lapangan' }}</span>
              </h3>
            </div>

            <div class="flex items-center space-x-2 text-xs text-slate-500">
              <span class="flex items-center space-x-1">
                <span>👤</span>
                <strong class="text-slate-800">{{ rep.reporter?.name || 'Anggota' }}</strong>
                <span v-if="rep.reporter?.member?.division?.name" class="text-[10px] text-emerald-700 font-bold bg-emerald-50 px-1.5 py-0.2 rounded">
                  {{ rep.reporter.member.division.name }}
                </span>
              </span>
              <span>&bull;</span>
              <span class="font-mono text-slate-400">{{ formatDate(rep.created_at) }}</span>
            </div>
          </div>

          <!-- Description Body -->
          <div class="text-xs text-slate-800 leading-relaxed whitespace-pre-line bg-slate-50/70 p-3.5 rounded-xl border border-slate-200">
            {{ rep.description }}
          </div>

          <!-- Photo Media Proof (if any) -->
          <div v-if="rep.media?.length" class="space-y-1.5">
            <div class="text-[11px] font-bold text-slate-600 flex items-center space-x-1">
              <span>📷</span>
              <span>Foto Bukti Kondisi Lapangan:</span>
            </div>
            <div class="flex flex-wrap gap-2.5">
              <div
                v-for="m in rep.media"
                :key="m.id"
                class="relative group cursor-pointer"
                @click="lightboxPhoto = m.path"
              >
                <img
                  :src="m.path"
                  class="w-24 h-24 sm:w-28 sm:h-28 object-cover rounded-xl border border-slate-200 group-hover:scale-105 transition shadow-xs"
                />
                <div class="absolute inset-0 bg-black/20 group-hover:bg-black/0 rounded-xl transition"></div>
                <span class="absolute bottom-1 right-1 px-1.5 py-0.5 rounded bg-black/60 text-white text-[8px] font-bold">
                  Perbesar
                </span>
              </div>
            </div>
          </div>

          <!-- Followups & Solutions Section -->
          <div v-if="rep.followups?.length" class="pt-2 border-t border-slate-100 space-y-2">
            <div class="text-[11px] font-bold text-emerald-800 uppercase tracking-wider flex items-center space-x-1.5">
              <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
              <span>Tindak Lanjut &amp; Solusi Penanganan:</span>
            </div>
            <div
              v-for="f in rep.followups"
              :key="f.id"
              class="p-3 bg-emerald-50/50 rounded-xl border border-emerald-200 text-xs space-y-1"
            >
              <div class="flex items-center justify-between font-bold text-slate-900">
                <span class="text-emerald-900 font-bold flex items-center space-x-1">
                  <span>🛠️</span>
                  <span>{{ f.user?.name || 'Petugas Penanganan' }}</span>
                  <span class="text-[10px] font-mono px-2 py-0.2 rounded bg-emerald-200 text-emerald-900 uppercase">
                    {{ f.status }}
                  </span>
                </span>
                <span class="text-[10px] text-slate-500 font-mono">{{ formatDate(f.created_at) }}</span>
              </div>
              <p class="text-slate-700 leading-relaxed pl-5">{{ f.solution }}</p>
            </div>
          </div>

          <!-- Card Bottom Action Row -->
          <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 text-xs">
            <div class="flex items-center space-x-2">
              <span class="text-slate-500 font-medium">Status Penanganan:</span>
              <span :class="getFollowupBadgeClass(rep.followup_status)" class="px-2.5 py-0.5 rounded-full font-bold text-[10px] uppercase shadow-xs">
                {{ formatFollowupLabel(rep.followup_status) }}
              </span>
            </div>

            <div class="flex items-center space-x-2 self-end sm:self-auto">
              <!-- Admin Followup Action -->
              <button
                v-if="['super_admin', 'admin'].includes(authStore.user?.role)"
                @click="openFollowupModal(rep)"
                class="px-3.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 rounded-xl font-bold transition flex items-center space-x-1.5 shadow-xs"
              >
                <svg class="w-3.5 h-3.5 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>+ Tanggapi / Beri Solusi</span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- MODAL 1: BUAT LAPORAN LAPANGAN BARU                                      -->
      <!-- ========================================================================= -->
      <div
        v-if="showReportModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 animate-in fade-in"
        @click.self="showReportModal = false"
      >
        <div class="bg-white rounded-3xl max-w-lg w-full p-6 shadow-2xl border border-slate-200 text-xs relative max-h-[90vh] overflow-y-auto">
          <button
            type="button"
            @click="showReportModal = false"
            class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 p-1 rounded-lg hover:bg-slate-100 transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>

          <div class="flex items-center space-x-2 mb-1">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
            <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Laporan Petugas Lapangan</span>
          </div>

          <h3 class="text-base font-black text-slate-900 mb-4">Buat Laporan Lapangan Baru</h3>

          <form @submit.prevent="submitReport" class="space-y-4">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Pilih Posko / Titik Wilayah *</label>
              <select
                v-model="reportForm.region_id"
                required
                class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl bg-white text-slate-900 focus:outline-none focus:border-emerald-500"
              >
                <option :value="null" disabled>-- Pilih Posko / Titik Tugas --</option>
                <option v-for="r in regions" :key="r.id" :value="r.id">{{ r.name }}</option>
              </select>
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Status Kondisi Posko *</label>
              <select
                v-model="reportForm.status"
                required
                class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl bg-white text-slate-900 focus:outline-none focus:border-emerald-500 font-semibold"
              >
                <option value="normal">🟢 Normal (Operasional Aman &amp; Lancar)</option>
                <option value="perlu_perhatian">🟡 Perlu Perhatian (Kabel Goyang / Butuh Cadangan)</option>
                <option value="rusak_kendala">🔴 Kendala / Rusak (Darurat - Perlu Bantuan Segera)</option>
              </select>
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Uraian / Deskripsi Kondisi Lapangan *</label>
              <textarea
                v-model="reportForm.description"
                required
                rows="3"
                placeholder="Jelaskan kondisi alat, perkabelan, sinyal video, atau kendala yang dihadapi secara ringkas..."
                class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl bg-white text-slate-900 focus:outline-none focus:border-emerald-500"
              ></textarea>
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Foto Bukti Lapangan (Kamera / Galeri)</label>
              <input
                type="file"
                ref="fileInputRef"
                accept="image/*"
                capture="environment"
                @change="handlePhotoUpload"
                class="hidden"
              />

              <div v-if="reportPhotoPreview" class="relative rounded-2xl overflow-hidden border border-slate-200 bg-slate-50 mb-2">
                <img :src="reportPhotoPreview" alt="Bukti Foto" class="w-full max-h-56 object-contain" />
                <button
                  type="button"
                  @click="reportPhotoPreview = ''"
                  class="absolute top-2 right-2 px-2.5 py-1 rounded-lg bg-rose-600 text-white font-bold text-[10px] hover:bg-rose-700 transition"
                >
                  Hapus Foto
                </button>
              </div>

              <button
                v-else
                type="button"
                @click="$refs.fileInputRef?.click()"
                class="w-full py-4 border-2 border-dashed border-slate-300 hover:border-emerald-500 rounded-2xl bg-slate-50 hover:bg-emerald-50/40 transition flex flex-col items-center justify-center space-y-1 text-slate-600 active:scale-98"
              >
                <div class="text-2xl">📸</div>
                <span class="font-bold text-xs text-emerald-800">Buka Kamera / Unggah Foto Bukti</span>
                <span class="text-[10px] text-slate-400">Format JPG, PNG, WEBP otomatis dikompres</span>
              </button>
            </div>

            <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100">
              <button
                type="button"
                @click="showReportModal = false"
                class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl font-bold transition hover:bg-slate-100"
              >
                Batal
              </button>
              <button
                type="submit"
                :disabled="submitting"
                class="px-5 py-2 bg-gradient-to-r from-[#059669] to-[#10b981] hover:brightness-105 text-white font-bold rounded-xl shadow-md shadow-emerald-600/20 transition disabled:opacity-50"
              >
                {{ submitting ? 'Mengirim...' : 'Kirim Laporan Lapangan' }}
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- MODAL 2: TINDAK LANJUT / RESPON ADMIN                                    -->
      <!-- ========================================================================= -->
      <div
        v-if="showFollowupModal"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm p-4 animate-in fade-in"
        @click.self="showFollowupModal = false"
      >
        <div class="bg-white rounded-3xl max-w-md w-full p-6 shadow-2xl border border-slate-200 text-xs relative">
          <button
            type="button"
            @click="showFollowupModal = false"
            class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 p-1 rounded-lg hover:bg-slate-100 transition"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>

          <h3 class="text-base font-black text-slate-900 mb-1">Tindak Lanjut &amp; Solusi Laporan</h3>
          <p class="text-xs text-slate-500 mb-4">Posko: <strong>{{ activeReport?.region?.name }}</strong> &bull; Pelapor: {{ activeReport?.reporter?.name }}</p>

          <form @submit.prevent="submitFollowup" class="space-y-4">
            <div>
              <label class="block font-bold text-slate-700 mb-1">Perbarui Status Penanganan *</label>
              <select
                v-model="followupForm.status"
                required
                class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl bg-white text-slate-900 focus:outline-none focus:border-emerald-500 font-semibold"
              >
                <option value="resolved">🟢 Resolved (Kendala Teratasi / Selesai)</option>
                <option value="in_progress">🔵 In Progress (Sedang Ditangani / Tim OTW)</option>
                <option value="open">🔴 Open (Masih Terbuka / Perlu Bantuan Lanjutan)</option>
              </select>
            </div>

            <div>
              <label class="block font-bold text-slate-700 mb-1">Catatan Solusi / Petugas yang Ditugaskan *</label>
              <textarea
                v-model="followupForm.solution"
                required
                rows="3"
                placeholder="misal: Kabel HDMI cadangan telah diantar oleh tim logistik dan layar kembali normal..."
                class="w-full px-3.5 py-2.5 border border-slate-300 rounded-xl bg-white text-slate-900 focus:outline-none focus:border-emerald-500"
              ></textarea>
            </div>

            <div class="flex justify-end space-x-2 pt-3 border-t border-slate-100">
              <button
                type="button"
                @click="showFollowupModal = false"
                class="px-4 py-2 border border-slate-200 text-slate-600 rounded-xl font-bold hover:bg-slate-100 transition"
              >
                Batal
              </button>
              <button
                type="submit"
                :disabled="submittingFollowup"
                class="px-5 py-2 bg-gradient-to-r from-[#059669] to-[#10b981] hover:brightness-105 text-white font-bold rounded-xl shadow-md shadow-emerald-600/20 transition disabled:opacity-50"
              >
                {{ submittingFollowup ? 'Menyimpan...' : 'Simpan Respon' }}
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- MODAL 3: LIGHTBOX PERBESAR FOTO BUKTI LAPANGAN                           -->
      <!-- ========================================================================= -->
      <div
        v-if="lightboxPhoto"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 p-4 animate-in fade-in"
        @click.self="lightboxPhoto = null"
      >
        <div class="relative max-w-4xl w-full flex flex-col items-center">
          <button
            @click="lightboxPhoto = null"
            class="absolute -top-10 right-0 text-white font-bold text-sm bg-white/20 hover:bg-white/40 px-3 py-1 rounded-xl transition"
          >
            ✕ Tutup
          </button>
          <img :src="lightboxPhoto" alt="Foto Lapangan" class="max-h-[85vh] w-auto rounded-2xl object-contain shadow-2xl" />
        </div>
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

const reports = ref([]);
const regions = ref([]);
const loading = ref(false);
const submitting = ref(false);
const submittingFollowup = ref(false);
const searchQuery = ref('');

const filters = ref({
  region_id: '',
  status: '',
  followup_status: '',
});

// Photo Lightbox
const lightboxPhoto = ref(null);

// Modals State
const showReportModal = ref(false);
const reportForm = ref({
  region_id: null,
  status: 'normal',
  description: '',
});
const reportPhotoPreview = ref('');
const fileInputRef = ref(null);

const showFollowupModal = ref(false);
const followupForm = ref({
  status: 'resolved',
  solution: '',
});
const activeReport = ref(null);

// Image compressor helper
const compressImage = (file, maxWidth = 1200, quality = 0.8) => {
  return new Promise((resolve, reject) => {
    const reader = new FileReader();
    reader.readAsDataURL(file);
    reader.onload = (event) => {
      const img = new Image();
      img.src = event.target.result;
      img.onload = () => {
        const elem = document.createElement('canvas');
        let width = img.width;
        let height = img.height;
        if (width > maxWidth) {
          height = Math.round((height * maxWidth) / width);
          width = maxWidth;
        }
        elem.width = width;
        elem.height = height;
        const ctx = elem.getContext('2d');
        ctx.drawImage(img, 0, 0, width, height);
        resolve(elem.toDataURL('image/jpeg', quality));
      };
      img.onerror = (err) => reject(err);
    };
    reader.onerror = (err) => reject(err);
  });
};

const handlePhotoUpload = async (e) => {
  const file = e.target.files[0];
  if (!file) return;
  try {
    const base64 = await compressImage(file, 1200, 0.82);
    reportPhotoPreview.value = base64;
  } catch (err) {
    alert('Gagal memproses foto. Silakan coba lagi.');
  }
};

// Filtered Reports
const filteredReports = computed(() => {
  let list = reports.value || [];
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase();
    list = list.filter((r) => {
      const desc = (r.description || '').toLowerCase().includes(q);
      const reg = (r.region?.name || '').toLowerCase().includes(q);
      const rep = (r.reporter?.name || '').toLowerCase().includes(q);
      return desc || reg || rep;
    });
  }
  return list;
});

// Metrics
const countKendala = computed(() => {
  return reports.value.filter((r) => r.status === 'rusak_kendala').length;
});

const countPerhatian = computed(() => {
  return reports.value.filter((r) => r.status === 'perlu_perhatian').length;
});

const countNormal = computed(() => {
  return reports.value.filter((r) => r.status === 'normal').length;
});

const fetchReports = async () => {
  loading.value = true;
  try {
    const res = await api.get('/reports', { params: { ...filters.value, per_page: 100 } });
    reports.value = res.data?.data || (Array.isArray(res.data) ? res.data : []);
  } catch (e) {
    console.error('Gagal mengambil data laporan:', e);
  } finally {
    loading.value = false;
  }
};

const fetchRegions = async () => {
  try {
    const res = await api.get('/regions');
    regions.value = res.data || [];
  } catch (e) {
    console.error('Gagal mengambil data posko:', e);
  }
};

const formatDate = (dateStr) => {
  if (!dateStr) return '-';
  const d = new Date(dateStr);
  return d.toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'short',
    hour: '2-digit',
    minute: '2-digit',
  });
};

const formatStatusLabel = (status) => {
  const map = {
    'normal': 'Normal & Lancar',
    'perlu_perhatian': 'Perlu Perhatian',
    'rusak_kendala': 'Kendala Lapangan',
  };
  return map[status] || status;
};

const formatFollowupLabel = (status) => {
  const map = {
    'open': 'Menunggu Tindak Lanjut',
    'in_progress': 'Sedang Ditangani',
    'resolved': 'Selesai / Teratasi',
  };
  return map[status] || status;
};

const getStatusBadgeClass = (status) => {
  const map = {
    'normal': 'bg-emerald-100 text-emerald-800 border border-emerald-200',
    'perlu_perhatian': 'bg-amber-100 text-amber-800 border border-amber-200',
    'rusak_kendala': 'bg-rose-100 text-rose-800 border border-rose-200',
  };
  return map[status] || 'bg-slate-100 text-slate-800 border border-slate-200';
};

const getFollowupBadgeClass = (status) => {
  const map = {
    'open': 'bg-rose-100 text-rose-800 border border-rose-200',
    'in_progress': 'bg-sky-100 text-sky-800 border border-sky-200',
    'resolved': 'bg-emerald-100 text-emerald-800 border border-emerald-200',
  };
  return map[status] || 'bg-slate-100 text-slate-800 border border-slate-200';
};

const openReportModal = () => {
  reportForm.value = {
    region_id: regions.value[0]?.id || null,
    status: 'normal',
    description: '',
  };
  reportPhotoPreview.value = '';
  showReportModal.value = true;
};

const submitReport = async () => {
  submitting.value = true;
  try {
    const payload = { ...reportForm.value };
    if (reportPhotoPreview.value) {
      payload.media = [{ path: reportPhotoPreview.value, type: 'photo' }];
    }
    await api.post('/reports', payload);
    showReportModal.value = false;
    alert('Laporan kondisi lapangan dan bukti foto berhasil dikirim!');
    await fetchReports();
    realtimeStore.fetchLiveStats();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal mengirim laporan lapangan.');
  } finally {
    submitting.value = false;
  }
};

const openFollowupModal = (rep) => {
  activeReport.value = rep;
  followupForm.value = { status: 'resolved', solution: '' };
  showFollowupModal.value = true;
};

const submitFollowup = async () => {
  submittingFollowup.value = true;
  try {
    await api.post(`/reports/${activeReport.value.id}/followups`, followupForm.value);
    showFollowupModal.value = false;
    alert('Tindak lanjut solusi laporan berhasil disimpan!');
    await fetchReports();
    realtimeStore.fetchLiveStats();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyimpan respon.');
  } finally {
    submittingFollowup.value = false;
  }
};

onMounted(() => {
  fetchReports();
  fetchRegions();
});
</script>