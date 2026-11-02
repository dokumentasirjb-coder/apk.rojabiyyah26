<template>
  <DashboardLayout>
    <div class="space-y-6">
      <!-- Header (Image 1 Match) -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <div class="flex items-center space-x-2.5">
            <div class="w-8 h-8 rounded-xl bg-teal-50 border border-slate-200 flex items-center justify-center text-teal-400">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            </div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">Tugas Anggota &amp; Titik Tugas</h2>
          </div>
          <p class="text-xs text-slate-500 mt-1">Penetapan pos teknis tugas anggota (Proyektor, Fotografer, Videografer, Perkabelan), jadwal tanggal &amp; jam, penugasan PIC, dan tindak lanjut laporan lapangan.</p>
        </div>

        <button
          v-if="['super_admin', 'admin'].includes(authStore.user?.role)"
          @click="openAddDutyModal"
          class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white text-xs font-bold shadow-md shadow-emerald-950/60 transition"
        >
          <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
          <span>Tambah Posko</span>
        </button>
      </div>

      <!-- Navigation Tabs: Sederhana & Kompak -->
      <div class="flex items-center space-x-2 border-b border-slate-200 pb-2">
        <button
          @click="activeTab = 'titik_tugas'"
          :class="[
            'inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition',
            activeTab === 'titik_tugas'
              ? 'bg-[#0f1f2e] text-teal-300 border border-teal-600/50 shadow-md'
              : 'text-slate-600 hover:text-slate-900 bg-white border border-slate-200'
          ]"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
          <span>Titik Tugas ({{ duties.length }})</span>
        </button>

        <router-link
          to="/reports"
          class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-bold text-slate-600 hover:text-slate-900 bg-white border border-slate-200 transition"
        >
          <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          <span>Laporan Lapangan ({{ reportsCount }})</span>
        </router-link>
      </div>

      <!-- Banner Prioritas Tugas Saya (Otomatis Muncul jika Akun Anda Ditugaskan) -->
      <div
        v-if="myActiveDuties.length > 0"
        class="p-4 sm:p-5 rounded-3xl bg-gradient-to-r from-amber-50 via-orange-50 to-amber-100/60 border-2 border-amber-300 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 animate-fadeIn"
      >
        <div class="flex items-start space-x-3.5">
          <div class="w-11 h-11 rounded-2xl bg-amber-500/20 border border-amber-500/60 flex items-center justify-center text-amber-400 flex-shrink-0 text-xl shadow-inner">
            ⭐
          </div>
          <div>
            <div class="flex items-center space-x-2">
              <span class="text-[10px] font-black tracking-wider uppercase px-2.5 py-0.5 rounded-full bg-amber-500 text-slate-950 shadow-md">
                Prioritas Tugas Anda
              </span>
              <span class="text-xs text-amber-300 font-bold">
                {{ myActiveDuties.length }} Posko Ditetapkan
              </span>
            </div>
            <p class="text-xs text-slate-700 mt-1">
              Akun Anda (<strong class="text-white">{{ authStore.user?.name }}</strong>) telah resmi ditugaskan pada pos:
              <span class="text-amber-300 font-semibold">
                {{ myActiveDuties.map(d => `${d.name} (${d.region?.name || 'Posko'})`).join(', ') }}
              </span>.
              Kartu tugas Anda diprioritaskan di posisi teratas dengan bingkai emas.
            </p>
          </div>
        </div>

        <button
          @click="openReportDutyModal(myActiveDuties[0])"
          class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black text-xs shadow-lg transition self-start sm:self-auto flex-shrink-0"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          <span>Lapor Kondisi Pos Saya</span>
        </button>
      </div>

      <!-- Filters (Image 1 Match) -->
      <div class="bg-white p-4 rounded-2xl border border-slate-200 flex flex-wrap gap-3 items-center text-xs">
        <div class="flex-1 min-w-[240px]">
          <input
            v-model="filters.search"
            @input="filterDuties"
            type="text"
            placeholder="Cari titik tugas, kategori, PIC anggota, lokasi..."
            class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-rose-500 transition"
          />
        </div>

        <select
          v-model="filters.region_id"
          @change="filterDuties"
          class="px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-rose-500 transition"
        >
          <option value="">Semua Wilayah</option>
          <option v-for="r in regions" :key="r.id" :value="r.id">{{ r.name }}</option>
        </select>

        <select
          v-model="filters.category"
          @change="filterDuties"
          class="px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-rose-500 transition"
        >
          <option value="">Semua Kategori Pos</option>
          <option value="Proyektor">📹 Proyektor</option>
          <option value="Fotografer">📷 Fotografer</option>
          <option value="Videografer">🎥 Videografer</option>
          <option value="Perkabelan">🔌 Perkabelan</option>
          <option value="Sound System">🔊 Sound System</option>
          <option value="Medis">🩺 Medis</option>
          <option value="Keamanan">🛡️ Keamanan</option>
        </select>
      </div>

      <!-- Table / Cards or Empty State (Image 1 Match) -->
      <div v-if="filteredDuties.length === 0" class="bg-white rounded-3xl border border-slate-200 p-16 text-center shadow-sm">
        <div class="w-16 h-16 rounded-2xl bg-white border border-slate-200 flex items-center justify-center text-indigo-400 mx-auto mb-4 shadow-inner">
          <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </div>
        <h3 class="text-sm font-bold text-slate-900 mb-1">Belum Ada Titik Tugas</h3>
        <p class="text-xs text-slate-500 max-w-md mx-auto">
          Belum ada titik tugas yang sesuai dengan kriteria filter saat ini. Klik tombol <strong class="text-white">Tambah Titik Tugas Baru</strong> di atas.
        </p>
      </div>

      <!-- Titik Tugas Cards List -->
      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        <div
          v-for="d in filteredDuties"
          :key="d.id"
          :class="[
            'border rounded-3xl p-5 shadow-sm flex flex-col justify-between transition relative overflow-hidden',
            d.pic_user?.id === authStore.user?.id
              ? 'bg-gradient-to-b from-amber-50/80 via-white to-amber-50/40 border-amber-400 ring-2 ring-amber-300 shadow-sm'
              : 'bg-white border-slate-200 hover:border-teal-500/50'
          ]"
        >
          <!-- Priority Duty Header Badge if assigned to current user -->
          <div v-if="d.pic_user?.id === authStore.user?.id" class="mb-3 flex items-center justify-between pb-2.5 border-b border-amber-500/30">
            <span class="inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-gradient-to-r from-amber-500 to-rose-600 text-white font-black text-[10px] tracking-wider uppercase shadow-md animate-pulse">
              <span>⭐ PRIORITAS TUGAS ANDA</span>
            </span>
            <span class="text-[10px] text-amber-300 font-bold flex items-center space-x-1">
              <span>🎯 Akun Anda Ditugaskan</span>
            </span>
          </div>

          <div>
            <div class="flex items-start justify-between mb-3">
              <span
                :class="d.pic_user?.id === authStore.user?.id ? 'bg-amber-950/80 border-amber-500/70 text-amber-300' : 'bg-teal-950/70 border-teal-600/60 text-teal-300'"
                class="px-2.5 py-1 rounded-full border font-bold text-[10px]"
              >
                {{ d.category || 'Pos Tugas' }}
              </span>
              <span class="text-[10px] text-slate-500 font-mono">
                {{ d.execution_time || '08:00 - Selesai' }}
              </span>
            </div>

            <h3 class="text-sm font-bold text-slate-900 mb-1">{{ d.name }}</h3>
            <p class="text-xs text-slate-500 mb-3">{{ d.description || 'Tidak ada SOP khusus' }}</p>

            <div
              :class="d.pic_user?.id === authStore.user?.id ? 'bg-[#090d19] border-amber-500/40' : 'bg-white border-slate-200'"
              class="p-3 rounded-2xl border space-y-1 text-xs"
            >
              <div class="text-[10px] text-slate-500 uppercase font-bold">Wilayah &amp; Lokasi:</div>
              <div class="text-white font-semibold">{{ d.region?.name || 'Wilayah Umum' }}</div>
              <div class="text-slate-500 text-[11px]">{{ d.specific_location || '-' }}</div>

              <div class="pt-2 border-t border-slate-200 text-[10px] text-slate-500 uppercase font-bold">PIC Bertugas:</div>
              <div class="flex items-center justify-between">
                <span :class="d.pic_user?.id === authStore.user?.id ? 'text-amber-300 font-black' : 'text-amber-400 font-semibold'">
                  {{ d.pic_user?.name || '-' }} ({{ d.pic_user?.member_no || 'RJB' }})
                </span>
                <span v-if="d.pic_user?.id === authStore.user?.id" class="px-2 py-0.5 rounded text-[9px] font-bold bg-amber-500/20 text-amber-300 border border-amber-500/50">
                  Akun Anda
                </span>
              </div>
            </div>
          </div>

          <!-- Card Action Buttons -->
          <div class="pt-4 mt-4 border-t border-slate-200 flex items-center justify-between gap-2 text-xs">
            <button
              @click="openReportDutyModal(d)"
              class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition shadow-sm"
              :class="d.pic_user?.id === authStore.user?.id
                ? 'bg-amber-500 hover:bg-amber-400 text-slate-950 font-black ring-1 ring-amber-400'
                : 'bg-emerald-950/70 hover:bg-emerald-900 border border-emerald-600/60 text-emerald-300'"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              <span>Lapor Kondisi Pos</span>
            </button>

            <button
              v-if="['super_admin', 'admin'].includes(authStore.user?.role)"
              @click="deleteDuty(d.id)"
              class="text-rose-400 hover:text-rose-300 p-1.5 rounded-lg hover:bg-rose-950/40 text-[11px]"
              title="Hapus Penugasan"
            >
              Hapus
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- MODAL: Penetapan Titik Tugas Baru (Image 2 Match) -->
    <div v-if="showDutyModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md p-4 overflow-y-auto">
      <div class="relative w-full max-w-2xl bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm text-slate-700 my-8 max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-5">
          <h3 class="text-base font-bold text-slate-900 tracking-wide">
            Penetapan Titik Tugas Baru (Proyektor, Foto, Video, Kabel)
          </h3>
          <button @click="showDutyModal = false" class="text-slate-500 hover:text-white transition p-1 rounded-lg hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <!-- Cyan Top Banner (Image 2 Match) -->
        <div class="p-3 rounded-2xl bg-[#0d1d29] border border-teal-500/50 flex items-center space-x-2.5 text-xs text-teal-200 mb-5">
          <span class="text-teal-400">✨</span>
          <span>Tentukan wilayah titik tugas, pos kategori, tanggal, jam, PIC anggota, dan rincian instruksi tugas.</span>
        </div>

        <!-- Form -->
        <form @submit.prevent="saveDuty" class="space-y-4 text-xs">
          <!-- Field 1: Nama Titik Tugas -->
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Nama Titik Tugas *</label>
            <input
              v-model="dutyForm.name"
              type="text"
              required
              placeholder="misal: Titik Layar Proyektor 1 (Panggung Utama) / Pos Kamera Kanan"
              class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-rose-500 transition"
            />
          </div>

          <!-- Row 2: Wilayah Penugasan & Kategori Pos Tugas -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Wilayah Penugasan *</label>
              <select
                v-model="dutyForm.region_id"
                required
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-rose-500 transition"
              >
                <option :value="null">Pilih Wilayah Penugasan</option>
                <option v-for="r in regions" :key="r.id" :value="r.id">{{ r.name }}</option>
              </select>
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Kategori Pos Tugas *</label>
              <select
                v-model="dutyForm.category"
                required
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-rose-500 transition"
              >
                <option value="Proyektor">📹 Proyektor</option>
                <option value="Fotografer">📷 Fotografer</option>
                <option value="Videografer">🎥 Videografer</option>
                <option value="Perkabelan">🔌 Perkabelan</option>
                <option value="Sound System">🔊 Sound System</option>
                <option value="Medis">🩺 Medis</option>
                <option value="Keamanan">🛡️ Keamanan</option>
              </select>
            </div>
          </div>

          <!-- Row 3: Tanggal Pelaksanaan & Jam Pelaksanaan -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Tanggal Pelaksanaan Tugas *</label>
              <input
                v-model="dutyForm.execution_date"
                type="date"
                required
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-rose-500 transition font-sans"
              />
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Jam Pelaksanaan Tugas *</label>
              <input
                v-model="dutyForm.execution_time"
                type="text"
                required
                placeholder="08:00 - Selesai WIB"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-rose-500 transition"
              />
            </div>
          </div>

          <!-- Row 4: PIC Anggota & Alamat Spesifik -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">PIC Anggota (Penanggung Jawab) *</label>
              <select
                v-model="dutyForm.user_id"
                required
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-rose-500 transition"
              >
                <option :value="null">Pilih Anggota PIC</option>
                <option v-for="u in members" :key="u.id" :value="u.id">
                  {{ u.name }} ({{ u.member_no || 'RJB' }})
                </option>
              </select>
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Alamat / Lokasi Spesifik</label>
              <input
                v-model="dutyForm.specific_location"
                type="text"
                placeholder="misal: Sayap Kanan Panggung Utama / Pintu Masuk"
                class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-rose-500 transition"
              />
            </div>
          </div>

          <!-- Row 5: Deskripsi Tugas & Instruksi Lapangan -->
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Deskripsi Tugas &amp; Instruksi Lapangan</label>
            <textarea
              v-model="dutyForm.description"
              rows="3"
              placeholder="Tuliskan arahan SOP: alat yang harus disiapkan, jalur kabel yang perlu diawasi, sudut pandang kamera, nomor kontak darurat..."
              class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-rose-500 transition"
            ></textarea>
          </div>

          <!-- Bottom Actions -->
          <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200">
            <button
              type="button"
              @click="showDutyModal = false"
              class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 font-semibold transition"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="saving"
              class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold shadow-lg shadow-emerald-950/60 transition disabled:opacity-50"
            >
              {{ saving ? 'Menyimpan...' : 'Simpan & Tetapkan Titik Tugas' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- MODAL: Lapor Kondisi Posko & Alat Lapangan (Mulai dari Foto sampai Deskripsi) -->
    <div v-if="showReportModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md p-4 overflow-y-auto">
      <div class="relative w-full max-w-lg bg-white border border-slate-200 rounded-3xl p-6 sm:p-7 shadow-sm text-slate-700 my-8 max-h-[90vh] overflow-y-auto">
        <!-- Header -->
        <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-4">
          <div class="flex items-center space-x-2.5">
            <div class="w-9 h-9 rounded-xl bg-amber-500/20 text-amber-400 flex items-center justify-center text-lg border border-amber-500/40">
              📸
            </div>
            <div>
              <h3 class="text-base font-bold text-slate-900 tracking-wide">
                Lapor Kondisi Posko &amp; Alat
              </h3>
              <p class="text-[11px] text-slate-500">Laporkan status posko, ambil bukti foto kamera/galeri, &amp; isi deskripsi.</p>
            </div>
          </div>
          <button @click="showReportModal = false" class="text-slate-500 hover:text-white transition p-1 rounded-lg hover:bg-slate-100">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <!-- Target Info Box -->
        <div class="p-3.5 rounded-2xl bg-white border border-slate-200 space-y-1 mb-4 text-xs">
          <div class="text-[10px] text-slate-500 uppercase font-bold">Titik Tugas Yang Dilaporkan:</div>
          <div class="text-white font-bold text-sm">{{ targetDutyForReport?.name }}</div>
          <div class="text-slate-500 text-[11px]">
            Wilayah: <strong class="text-slate-700">{{ targetDutyForReport?.region?.name }}</strong> &bull; Kategori: <strong class="text-amber-300">{{ targetDutyForReport?.category }}</strong>
          </div>
          <div class="text-[10px] text-slate-500 pt-1">
            Pelapor: <strong class="text-white">{{ authStore.user?.name }} ({{ authStore.user?.member_no || 'RJB' }})</strong>
          </div>
        </div>

        <form @submit.prevent="submitDutyReport" class="space-y-4 text-xs">
          <!-- 1. Status Kondisi -->
          <div>
            <label class="block font-semibold text-slate-700 mb-1.5">Status Kondisi Saat Ini *</label>
            <div class="grid grid-cols-3 gap-2">
              <label
                class="p-2.5 rounded-xl border flex flex-col items-center justify-center text-center cursor-pointer transition select-none"
                :class="reportForm.status === 'normal' ? 'bg-emerald-950/60 border-emerald-500 text-emerald-300 shadow-md' : 'bg-white border-slate-300 text-slate-500 hover:text-white'"
              >
                <input type="radio" value="normal" v-model="reportForm.status" class="hidden" />
                <span class="text-base mb-0.5">🟢</span>
                <span class="font-bold text-[11px]">Normal</span>
                <span class="text-[9px] opacity-75">Alat Lancar</span>
              </label>

              <label
                class="p-2.5 rounded-xl border flex flex-col items-center justify-center text-center cursor-pointer transition select-none"
                :class="reportForm.status === 'perlu_perhatian' ? 'bg-amber-950/60 border-amber-500 text-amber-300 shadow-md' : 'bg-white border-slate-300 text-slate-500 hover:text-white'"
              >
                <input type="radio" value="perlu_perhatian" v-model="reportForm.status" class="hidden" />
                <span class="text-base mb-0.5">🟡</span>
                <span class="font-bold text-[11px]">Perhatian</span>
                <span class="text-[9px] opacity-75">Warning / Minim</span>
              </label>

              <label
                class="p-2.5 rounded-xl border flex flex-col items-center justify-center text-center cursor-pointer transition select-none"
                :class="reportForm.status === 'rusak_kendala' ? 'bg-rose-950/60 border-rose-500 text-rose-300 shadow-md' : 'bg-white border-slate-300 text-slate-500 hover:text-white'"
              >
                <input type="radio" value="rusak_kendala" v-model="reportForm.status" class="hidden" />
                <span class="text-base mb-0.5">🔴</span>
                <span class="font-bold text-[11px]">Kendala</span>
                <span class="text-[9px] opacity-75">Butuh Bantuan</span>
              </label>
            </div>
          </div>

          <!-- 2. Upload / Ambil Foto Bukti Kondisi Lapangan -->
          <div>
            <label class="block font-semibold text-slate-700 mb-1.5">Foto Bukti Lapangan / Kondisi Alat</label>
            <input
              type="file"
              ref="reportPhotoInputRef"
              accept="image/*"
              capture="environment"
              @change="handleReportPhotoUpload"
              class="hidden"
            />

            <!-- Preview Image if uploaded -->
            <div v-if="reportPhotoPreview" class="relative rounded-2xl overflow-hidden border border-slate-300 bg-black/50 mb-2">
              <img :src="reportPhotoPreview" alt="Foto Lapangan" class="w-full max-h-52 object-contain" />
              <button
                type="button"
                @click="clearReportPhoto"
                class="absolute top-2 right-2 px-2.5 py-1 rounded-lg bg-rose-600/90 hover:bg-rose-700 text-white font-bold text-[10px] transition shadow"
              >
                Hapus / Ganti Foto
              </button>
            </div>

            <!-- Upload / Kamera Button -->
            <button
              v-else
              type="button"
              @click="$refs.reportPhotoInputRef?.click()"
              class="w-full py-4 border-2 border-dashed border-slate-300 hover:border-amber-500/70 rounded-2xl bg-white hover:bg-[#15203a] transition flex flex-col items-center justify-center space-y-1 text-slate-700 group"
            >
              <div class="w-10 h-10 rounded-full bg-amber-500/20 text-amber-400 flex items-center justify-center text-xl group-hover:scale-110 transition">
                📷
              </div>
              <span class="font-bold text-xs text-amber-300">Ambil Foto Kamera / Unggah dari Galeri</span>
              <span class="text-[10px] text-slate-500">Mendukung format JPG, PNG, WEBP (maks 10MB)</span>
            </button>
          </div>

          <!-- 3. Deskripsi Kondisi Lapangan -->
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Deskripsi &amp; Detail Kondisi *</label>
            <textarea
              v-model="reportForm.description"
              required
              rows="3"
              placeholder="Contoh: Proyektor 1 menyala lancar, tampilan jernih, kabel HDMI aman, dan audio tersambung ke sound system..."
              class="w-full px-3.5 py-2.5 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-amber-500 transition"
            ></textarea>
          </div>

          <!-- Bottom Actions -->
          <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200">
            <button
              type="button"
              @click="showReportModal = false"
              class="px-4 py-2.5 rounded-xl bg-white hover:bg-slate-100 text-slate-700 font-semibold transition"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="submittingReport"
              class="px-5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-400 text-slate-950 font-black shadow-lg shadow-amber-950/60 transition disabled:opacity-50 flex items-center space-x-1.5"
            >
              <svg v-if="submittingReport" class="animate-spin -ml-1 mr-2 h-4 w-4 text-slate-950" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
              <span>{{ submittingReport ? 'Mengirim Laporan...' : 'Kirim Laporan Kondisi' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import DashboardLayout from '../../layouts/DashboardLayout.vue';
import { useAuthStore } from '../../stores/auth';
import api from '../../services/api';

const authStore = useAuthStore();
const regions = ref([]);
const members = ref([]);
const duties = ref([]);
const filteredDuties = ref([]);
const reportsCount = ref(0);
const activeTab = ref('titik_tugas');
const showDutyModal = ref(false);
const saving = ref(false);

const myActiveDuties = computed(() => {
  const uid = authStore.user?.id;
  if (!uid) return [];
  return duties.value.filter((d) => d.pic_user?.id === uid);
});

const filters = ref({
  search: '',
  region_id: '',
  category: '',
});

const dutyForm = ref({
  name: '',
  region_id: null,
  category: 'Proyektor',
  execution_date: new Date().toISOString().split('T')[0],
  execution_time: '08:00 - Selesai WIB',
  user_id: null,
  specific_location: '',
  description: '',
});

const fetchInitialData = async () => {
  try {
    const [regRes, memRes, repRes] = await Promise.all([
      api.get('/regions'),
      api.get('/members', { params: { per_page: 100 } }),
      api.get('/reports').catch(() => ({ data: { total: 0 } })),
    ]);
    regions.value = regRes.data;
    members.value = memRes.data.data;
    reportsCount.value = repRes.data?.total ?? repRes.data?.data?.length ?? (Array.isArray(repRes.data) ? repRes.data.length : 0);

    // Load duties from regions assignments
    const list = [];
    regions.value.forEach((r) => {
      if (r.assignments) {
        r.assignments.forEach((a) => {
          list.push({
            id: a.id,
            name: a.role_in_post || `Pos di ${r.name}`,
            category: a.role_in_post?.includes('Proyektor') ? 'Proyektor' :
                      (a.role_in_post?.includes('Foto') || a.role_in_post?.includes('Fotografer')) ? 'Fotografer' :
                      (a.role_in_post?.includes('Video') || a.role_in_post?.includes('Videografer')) ? 'Videografer' :
                      (a.role_in_post?.includes('Kabel') || a.role_in_post?.includes('Perkabelan')) ? 'Perkabelan' : 'Proyektor',
            region: r,
            execution_time: '08:00 - Selesai WIB',
            pic_user: a.user,
            specific_location: r.description,
            description: 'Melaksanakan SOP posko dan koordinasi penanggung jawab.',
          });
        });
      }
    });
    duties.value = list;
    filterDuties();
  } catch (err) {
    console.error('Gagal mengambil data wilayah:', err);
  }
};

const filterDuties = () => {
  const currentUserId = authStore.user?.id;
  const list = duties.value.filter((d) => {
    const matchSearch =
      !filters.value.search ||
      d.name.toLowerCase().includes(filters.value.search.toLowerCase()) ||
      d.pic_user?.name?.toLowerCase().includes(filters.value.search.toLowerCase());
    const matchRegion = !filters.value.region_id || d.region?.id == filters.value.region_id;
    const matchCategory = !filters.value.category || d.category === filters.value.category;
    return matchSearch && matchRegion && matchCategory;
  });

  // Prioritaskan tugas akun yang sedang login di urutan paling atas
  list.sort((a, b) => {
    const isAMine = a.pic_user?.id === currentUserId ? 1 : 0;
    const isBMine = b.pic_user?.id === currentUserId ? 1 : 0;
    return isBMine - isAMine;
  });

  filteredDuties.value = list;
};

const openAddDutyModal = () => {
  dutyForm.value = {
    name: '',
    region_id: regions.value[0]?.id || null,
    category: 'Proyektor',
    execution_date: new Date().toISOString().split('T')[0],
    execution_time: '08:00 - Selesai WIB',
    user_id: members.value[0]?.id || null,
    specific_location: '',
    description: '',
  };
  showDutyModal.value = true;
};

const saveDuty = async () => {
  if (!dutyForm.value.region_id) {
    alert('Silakan pilih Wilayah Penugasan.');
    return;
  }
  if (!dutyForm.value.user_id) {
    alert('Silakan pilih PIC Anggota.');
    return;
  }
  if (!dutyForm.value.name) {
    alert('Silakan masukkan Nama Titik Tugas.');
    return;
  }

  saving.value = true;
  try {
    const roleText = `${dutyForm.value.name} (${dutyForm.value.category})`;
    await api.post(`/regions/${dutyForm.value.region_id}/assignments`, {
      user_id: dutyForm.value.user_id,
      role_in_post: roleText,
      start_at: dutyForm.value.execution_date ? `${dutyForm.value.execution_date} 08:00:00` : null,
      end_at: dutyForm.value.execution_date ? `${dutyForm.value.execution_date} 23:59:59` : null,
    });
    alert('Titik tugas baru berhasil ditetapkan!');
    showDutyModal.value = false;
    await fetchInitialData();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menetapkan titik tugas.');
  } finally {
    saving.value = false;
  }
};

const deleteDuty = async (id) => {
  if (await window.$confirm('Hapus penugasan titik posko ini?', 'Konfirmasi Hapus Penugasan')) {
    try {
      await api.delete(`/assignments/${id}`);
      fetchInitialData();
    } catch (err) {
      alert(err.response?.data?.message || 'Gagal menghapus penugasan.');
    }
  }
};

// =========================================================================
// LAPOR KONDISI POSKO (FOTO & DESKRIPSI)
// =========================================================================
const showReportModal = ref(false);
const submittingReport = ref(false);
const targetDutyForReport = ref(null);
const reportPhotoPreview = ref('');
const reportPhotoInputRef = ref(null);

const reportForm = ref({
  status: 'normal',
  description: '',
});

const openReportDutyModal = (duty) => {
  targetDutyForReport.value = duty;
  reportForm.value = {
    status: 'normal',
    description: '',
  };
  reportPhotoPreview.value = '';
  showReportModal.value = true;
};

const handleReportPhotoUpload = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  if (file.size > 10 * 1024 * 1024) {
    alert('Ukuran foto terlalu besar. Maksimal 10MB.');
    return;
  }
  const reader = new FileReader();
  reader.onload = (event) => {
    reportPhotoPreview.value = event.target.result;
  };
  reader.readAsDataURL(file);
};

const clearReportPhoto = () => {
  reportPhotoPreview.value = '';
  if (reportPhotoInputRef.value) {
    reportPhotoInputRef.value.value = '';
  }
};

const submitDutyReport = async () => {
  if (!targetDutyForReport.value?.region?.id) {
    alert('Wilayah posko tidak valid.');
    return;
  }
  if (!reportForm.value.description.trim()) {
    alert('Mohon isi deskripsi kondisi.');
    return;
  }

  submittingReport.value = true;
  try {
    const payload = {
      region_id: targetDutyForReport.value.region.id,
      status: reportForm.value.status,
      description: `[Titik Tugas: ${targetDutyForReport.value.name} (${targetDutyForReport.value.category})]\n${reportForm.value.description.trim()}`,
      media: reportPhotoPreview.value ? [{ path: reportPhotoPreview.value, type: 'photo' }] : [],
    };

    await api.post('/reports', payload);
    alert('Laporan kondisi lapangan dan bukti foto berhasil dikirim!');
    showReportModal.value = false;
    reportsCount.value++;
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal mengirim laporan lapangan.');
  } finally {
    submittingReport.value = false;
  }
};

onMounted(() => {
  fetchInitialData();
});
</script>
