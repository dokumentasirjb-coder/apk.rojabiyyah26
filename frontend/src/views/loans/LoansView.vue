<template>
  <DashboardLayout>
    <div class="space-y-6">
      <!-- Title & Actions (Dynamic based on Admin vs Anggota) -->
      <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div>
          <div class="flex items-center space-x-2.5">
            <div class="w-8 h-8 rounded-xl bg-teal-50 border border-slate-200 flex items-center justify-center text-teal-400">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            </div>
            <h2 class="text-xl font-bold text-slate-900 tracking-tight">
              {{ isAdmin ? 'Peminjaman & Sirkulasi Logistik (Admin)' : 'Katalog & Peminjaman Logistik (Portal Anggota)' }}
            </h2>
          </div>
          <p class="text-xs text-slate-500 mt-1">
            {{ isAdmin
              ? 'Kelola persetujuan peminjaman barang oleh relawan anggota, cetak dokumen resmi (Surat Jalan & Stiker Label), dan verifikasi pengembalian.'
              : 'Pilih alat inventaris untuk operasional tugas Anda, ajukan peminjaman, dan konfirmasi pengembalian barang disertai bukti foto.'
            }}
          </p>
          <div class="mt-2 inline-flex items-center space-x-1.5 px-3 py-1 rounded-full bg-rose-50 border border-rose-200 text-[11px] text-rose-700">
            <span>✨</span>
            <span>Sub Tema: Haul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad &amp; Rojabiyyah 2026</span>
          </div>
        </div>

        <!-- Top Action Buttons: Distinct for Admin vs Anggota -->
        <div class="flex flex-wrap items-center gap-2.5">
          <!-- Admin Actions (Admin DOES NOT apply, Admin manages inventory & approvals) -->
          <template v-if="isAdmin">
            <router-link
              to="/inventory"
              class="inline-flex items-center space-x-1.5 px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white text-xs font-bold shadow-lg shadow-emerald-950/60 transition"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
              <span>+ Tambah Barang Inventaris</span>
            </router-link>

            <router-link
              to="/partner-loans"
              class="inline-flex items-center space-x-1.5 px-3.5 py-2.5 rounded-xl bg-cyan-50 hover:bg-cyan-100 border border-cyan-300 text-cyan-800 text-xs font-bold transition shadow-xs"
            >
              <svg class="w-3.5 h-3.5 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
              <span>Pinjaman Lembaga Mitra</span>
            </router-link>
          </template>

          <!-- Anggota Action (Anggota IS the one who applies for loans) -->
          <template v-else>
            <button
              @click="openApplyModal(null)"
              class="inline-flex items-center space-x-1.5 px-4 py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white text-xs font-bold shadow-lg shadow-emerald-950/60 transition"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
              <span>+ Ajukan Peminjaman</span>
            </button>
          </template>
        </div>
      </div>

      <!-- Navigation Tabs: Ordered logically for Admin vs Anggota -->
      <div class="flex items-center space-x-3 border-b border-slate-200 pb-2 text-xs font-bold">
        <!-- Tab: Loans / Riwayat (Default for Admin) -->
        <button
          @click="activeMainTab = 'riwayat'"
          :class="[
            'inline-flex items-center space-x-2 px-4 py-2 rounded-xl transition',
            activeMainTab === 'riwayat'
              ? 'bg-[#059669] text-white shadow-lg shadow-emerald-950/60'
              : 'text-slate-500 hover:text-white'
          ]"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
          <span>{{ isAdmin ? `Daftar Pengajuan & Peminjaman Anggota (${loans.length})` : `Riwayat Peminjaman Saya (${loans.length})` }}</span>
        </button>

        <!-- Tab: Catalog (Default for Anggota) -->
        <button
          @click="activeMainTab = 'katalog'"
          :class="[
            'inline-flex items-center space-x-2 px-4 py-2 rounded-xl transition',
            activeMainTab === 'katalog'
              ? 'bg-[#059669] text-white shadow-lg shadow-emerald-950/60'
              : 'text-slate-500 hover:text-white'
          ]"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          <span>{{ isAdmin ? `Katalog Stok Barang Gudang (${availableItems.length})` : `Katalog Barang Siap Pinjam (${availableItems.length})` }}</span>
        </button>
      </div>

      <!-- ========================================================================= -->
      <!-- TAB 1: DAFTAR PENGAJUAN & PEMINJAMAN (Approval & Circulation)             -->
      <!-- ========================================================================= -->
      <div v-if="activeMainTab === 'riwayat'" class="space-y-4">
        <!-- Status Filter Pills -->
        <div class="flex items-center space-x-2 overflow-x-auto pb-1 text-xs font-semibold">
          <button
            v-for="st in statusFilters"
            :key="st.key"
            @click="selectedStatusFilter = st.key"
            :class="[
              'px-3.5 py-1.5 rounded-full transition whitespace-nowrap',
              selectedStatusFilter === st.key
                ? 'bg-[#059669] text-white font-bold shadow-md shadow-emerald-950/60'
                : 'bg-slate-50 text-slate-500 hover:text-white border border-slate-200'
            ]"
          >
            {{ st.label }}
          </button>
        </div>

        <!-- Empty State -->
        <div v-if="filteredLoans.length === 0" class="bg-white rounded-3xl border border-slate-200 p-12 text-center shadow-sm">
          <div class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-500 mx-auto mb-3">
            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          </div>
          <h4 class="text-sm font-bold text-slate-900 mb-1">
            {{ isAdmin ? 'Tidak Ada Pengajuan Peminjaman' : 'Belum Ada Riwayat Peminjaman' }}
          </h4>
          <p class="text-xs text-slate-500">
            {{ isAdmin
              ? 'Belum ada relawan anggota yang mengajukan peminjaman dengan status ini.'
              : 'Anda belum pernah mengajukan peminjaman barang, atau filter status tidak sesuai.'
            }}
          </p>
        </div>

        <!-- Loans Cards List -->
        <div v-else class="space-y-4">
          <div
            v-for="loan in filteredLoans"
            :key="loan.id"
            class="bg-white border border-slate-200 rounded-3xl p-5 sm:p-6 shadow-sm space-y-5 hover:border-slate-200 transition"
          >
            <!-- Card Header: No Dokumen, Status & Tanggal -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 pb-4 border-b border-slate-200">
              <div class="flex items-center space-x-3">
                <span class="font-mono font-black text-cyan-400 text-sm tracking-wide">{{ loan.loan_no }}</span>
                <span
                  :class="[
                    'px-2.5 py-0.5 rounded-full text-[11px] font-bold border flex items-center space-x-1.5 capitalize',
                    loan.status === 'disetujui' ? 'bg-sky-50 text-sky-700 border-sky-200' :
                    loan.status === 'diserahkan' ? 'bg-indigo-50 text-indigo-700 border-indigo-200' :
                    loan.status === 'dikembalikan' ? 'bg-emerald-50 text-emerald-700 border-emerald-200' :
                    loan.status === 'ditolak' ? 'bg-rose-50 text-rose-700 border-rose-200' :
                    'bg-amber-50 text-amber-700 border-amber-200'
                  ]"
                >
                  <span
                    class="w-1.5 h-1.5 rounded-full"
                    :class="[
                      loan.status === 'dikembalikan' ? 'bg-emerald-400' :
                      loan.status === 'disetujui' ? 'bg-sky-400' :
                      loan.status === 'diserahkan' ? 'bg-indigo-400' :
                      loan.status === 'ditolak' ? 'bg-rose-400' : 'bg-amber-400'
                    ]"
                  ></span>
                  <span>{{ formatStatusLabel(loan.status) }}</span>
                </span>
              </div>

              <!-- Peminjam Info (Prominent for Admin) -->
              <div class="text-[11px] text-slate-700 flex items-center space-x-2">
                <span class="text-slate-500">Peminjam:</span>
                <strong class="text-white">{{ loan.borrower?.name || 'Anggota Relawan' }}</strong>
                <span v-if="loan.borrower?.member_no" class="px-1.5 py-0.2 rounded bg-slate-800 text-slate-500 font-mono text-[10px]">
                  {{ loan.borrower?.member_no }}
                </span>
                <span class="text-slate-500">• {{ formatDate(loan.requested_at) }}</span>
              </div>
            </div>

            <!-- Card Body Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">
              <!-- Left: Items List -->
              <div class="space-y-2.5">
                <span class="text-slate-500 text-[11px] font-semibold block">
                  Daftar Barang yang Dipinjam ({{ loan.loan_items?.length || 1 }} Barang):
                </span>
                <div
                  v-for="li in loan.loan_items"
                  :key="li.id"
                  class="p-3 bg-slate-50 border border-slate-200 rounded-2xl flex items-center justify-between gap-3"
                >
                  <div class="flex items-center space-x-3 min-w-0">
                    <div class="w-12 h-12 rounded-xl bg-slate-900 border border-slate-300 overflow-hidden flex items-center justify-center flex-shrink-0">
                      <img v-if="li.item?.photo_path" :src="li.item.photo_path" class="w-full h-full object-cover" />
                      <svg v-else class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/></svg>
                    </div>
                    <div class="min-w-0">
                      <h5 class="font-extrabold text-slate-900 text-xs truncate">{{ li.item?.name || 'Barang Inventaris' }}</h5>
                      <div class="flex items-center space-x-2 mt-0.5">
                        <span class="text-[10px] font-mono text-cyan-400 font-bold">{{ li.item?.code || 'INV-01' }}</span>
                        <span class="px-1.5 py-0.2 rounded bg-rose-50 text-rose-700 text-[9px] font-semibold border border-rose-200">
                          {{ li.item?.source === 'mitra' ? 'Mitra' : 'Rojabiyyah' }}
                        </span>
                      </div>
                    </div>
                  </div>
                  <div class="px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 font-mono font-bold text-xs flex-shrink-0">
                    {{ li.qty_approved || li.qty_requested }} Unit
                  </div>
                </div>
              </div>

              <!-- Right: Keperluan & Tanggal Pinjam -->
              <div class="space-y-3">
                <div>
                  <span class="text-slate-500 text-[11px] block mb-0.5">Keperluan Peminjaman:</span>
                  <span class="font-bold text-white text-xs uppercase tracking-wide">{{ loan.purpose }}</span>
                </div>
                <div>
                  <span class="text-slate-500 text-[11px] block mb-0.5">Periode Tanggal Pinjam:</span>
                  <div class="flex items-center space-x-1.5 text-slate-700 font-mono text-xs">
                    <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h18"/></svg>
                    <span>{{ formatDate(loan.requested_at) }} s/d {{ formatDate(loan.due_at) }}</span>
                  </div>
                </div>

                <!-- Approval / Handover / Return Status Box -->
                <div v-if="loan.status === 'diajukan'" class="p-2.5 rounded-xl bg-amber-950/40 border border-amber-800/50 text-[11px] space-y-0.5">
                  <span class="text-amber-400 font-bold">Status Persetujuan:</span>
                  <p class="text-amber-200">
                    {{ isAdmin ? 'Menunggu persetujuan Anda sebagai Admin Logistik.' : 'Pengajuan Anda sedang menunggu verifikasi admin.' }}
                  </p>
                </div>

                <div v-else-if="loan.status === 'disetujui'" class="p-2.5 rounded-xl bg-sky-950/40 border border-sky-800/50 text-[11px] space-y-0.5">
                  <span class="text-sky-400 font-bold">Telah Disetujui:</span>
                  <p class="text-sky-200">
                    Disetujui oleh {{ loan.approver?.name || 'Admin Logistik' }}. Stok telah dicadangkan.
                  </p>
                </div>

                <div v-else-if="loan.status === 'diserahkan'" class="p-2.5 rounded-xl bg-indigo-950/40 border border-indigo-800/50 text-[11px] space-y-0.5">
                  <span class="text-indigo-400 font-bold">Sedang Aktif Dipinjam:</span>
                  <p class="text-indigo-200">
                    {{ isAdmin ? 'Barang telah diserahterimakan kepada peminjam.' : 'Barang telah Anda terima. Kembalikan tepat waktu disertai bukti foto.' }}
                  </p>
                </div>

                <div v-else-if="loan.status === 'dikembalikan'" class="p-2.5 rounded-xl bg-emerald-950/40 border border-emerald-800/50 text-[11px] space-y-0.5">
                  <span class="text-emerald-400 font-bold">Selesai Dikembalikan:</span>
                  <p class="text-emerald-200">
                    Barang telah dikembalikan lengkap. Bukti foto serah terima tersimpan.
                  </p>
                </div>

                <div v-else-if="loan.status === 'ditolak'" class="p-2.5 rounded-xl bg-rose-950/40 border border-rose-800/50 text-[11px] space-y-0.5">
                  <span class="text-rose-400 font-bold">Alasan Penolakan:</span>
                  <p class="text-rose-200">{{ loan.reject_reason || 'Tidak memenuhi kriteria / stok bentrok jadwal.' }}</p>
                </div>
              </div>
            </div>

            <!-- Card Footer Actions: Role Separation (Strict) -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-4 border-t border-slate-200">
              <!-- Left Action: Primary Action for Admin vs Anggota -->
              <div class="flex flex-wrap items-center gap-2">
                <!-- ================= ADMIN ACTIONS ================= -->
                <template v-if="isAdmin">
                  <!-- Case 1: Status diajukan -> Admin APPROVE or REJECT -->
                  <template v-if="loan.status === 'diajukan'">
                    <button
                      @click="handleApproveLoan(loan)"
                      class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs shadow-lg shadow-emerald-950/60 transition"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                      <span>✓ Setujui Peminjaman</span>
                    </button>

                    <button
                      @click="openRejectModal(loan)"
                      class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-[#1c121e] hover:bg-rose-950/80 border border-rose-800/80 text-rose-300 font-bold text-xs transition"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                      <span>✕ Tolak</span>
                    </button>
                  </template>

                  <!-- Case 2: Status disetujui -> Admin HANDOVER to Member -->
                  <template v-else-if="loan.status === 'disetujui'">
                    <button
                      @click="handleHandoverLoan(loan)"
                      class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-teal-600 hover:bg-teal-500 text-white font-bold text-xs shadow-lg shadow-teal-950/60 transition"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                      <span>Serahkan Barang Fisik (Handover)</span>
                    </button>
                  </template>

                  <!-- Case 3: Status diserahkan -> Waiting for member return -->
                  <template v-else-if="loan.status === 'diserahkan'">
                    <span class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-indigo-950/70 border border-indigo-800 text-indigo-300 text-xs font-semibold">
                      <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                      <span>Sedang dibawa peminjam (Menunggu pengembalian anggota)</span>
                    </span>
                  </template>

                  <!-- Case 4: Status dikembalikan -> Admin can view photo proof -->
                  <template v-else-if="loan.status === 'dikembalikan'">
                    <button
                      @click="openEvidenceModal(loan)"
                      class="inline-flex items-center space-x-1.5 px-3.5 py-2 rounded-xl bg-[#0e1d2c] hover:bg-[#132a40] border border-cyan-500/60 text-cyan-300 font-bold text-xs transition shadow"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                      <span>Lihat Foto Bukti Pengembalian Anggota</span>
                    </button>
                  </template>
                </template>

                <!-- ================= ANGGOTA ACTIONS ================= -->
                <template v-else>
                  <!-- Case 1: Status diajukan -> Member can cancel -->
                  <template v-if="loan.status === 'diajukan'">
                    <button
                      @click="handleCancelLoan(loan)"
                      class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-[#1c121e] hover:bg-rose-950/80 border border-rose-800 text-rose-300 text-xs font-semibold transition"
                    >
                      <span>Batalkan Pengajuan</span>
                    </button>
                  </template>

                  <!-- Case 2: Status disetujui / diserahkan -> Member RETURNS with PHOTO -->
                  <template v-else-if="['disetujui', 'diserahkan'].includes(loan.status)">
                    <button
                      @click="openReturnModal(loan)"
                      class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-lg shadow-emerald-950/60 transition"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                      <span>Kembalikan Barang (Jepret Foto Kamera / Galeri)</span>
                    </button>
                  </template>

                  <!-- Case 3: Status dikembalikan -> Member views proof -->
                  <template v-else-if="loan.status === 'dikembalikan'">
                    <button
                      @click="openEvidenceModal(loan)"
                      class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-[#0e1d2c] hover:bg-[#132a40] border border-cyan-500/60 text-cyan-300 font-semibold text-xs transition"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                      <span>Lihat Bukti Foto yang Dikirim</span>
                    </button>
                  </template>
                </template>
              </div>

              <!-- Right Actions: Print & Documents (Surat Jalan, Stiker F4, Bukti, PDF) -->
              <div class="flex items-center space-x-2 self-end sm:self-auto flex-wrap gap-y-2">
                <!-- Cetak Surat Jalan -->
                <button
                  v-if="['disetujui', 'diserahkan', 'dikembalikan'].includes(loan.status)"
                  @click="openPrintModalTab(loan, 'surat_jalan')"
                  class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-[#0f2336] hover:bg-[#13324d] border border-teal-500/50 text-teal-300 font-bold text-xs transition shadow"
                  title="Cetak Surat Jalan & Berita Acara F4 Resmi"
                >
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                  <span>Surat Jalan</span>
                </button>

                <!-- Cetak Stiker F4 Multi-Ukuran -->
                <button
                  v-if="['disetujui', 'diserahkan', 'dikembalikan'].includes(loan.status)"
                  @click="openPrintModalTab(loan, 'stiker')"
                  class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-[#2b1f0d] hover:bg-[#3d2b12] border border-amber-500/50 text-amber-300 font-bold text-xs transition shadow"
                  title="Cetak Stiker Label Kertas F4 Multi-Ukuran (Besar, Sedang, Kecil)"
                >
                  <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                  <span>Stiker F4</span>
                </button>

                <!-- Bukti Peminjaman (For Both Admin and Member) -->
                <button
                  @click="openPrintModalTab(loan, 'bukti')"
                  class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-teal-50 border border-slate-300 text-slate-700 font-semibold text-xs transition"
                  title="Lihat Lembar Bukti Tanda Terima Peminjaman"
                >
                  <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                  <span>Bukti</span>
                </button>

                <!-- Unduh / Cetak Dokumen PDF Resmi -->
                <button
                  @click="downloadLoanPdf(loan)"
                  :disabled="downloadingPdfId === loan.id"
                  class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-slate-50 hover:bg-teal-50 border border-slate-300 text-slate-700 font-semibold text-xs transition"
                  title="Unduh / Buka Dokumen PDF Resmi"
                >
                  <svg v-if="downloadingPdfId === loan.id" class="w-3.5 h-3.5 animate-spin text-rose-400" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path></svg>
                  <svg v-else class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                  <span>{{ downloadingPdfId === loan.id ? 'Memuat...' : 'PDF' }}</span>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- ========================================================================= -->
      <!-- TAB 2: KATALOG BARANG SIAP PINJAM                                         -->
      <!-- ========================================================================= -->
      <div v-else class="space-y-6">
        <!-- Filter Bar -->
        <div class="bg-white p-4 rounded-2xl border border-slate-200 flex flex-wrap gap-3 items-center text-xs">
          <div class="flex-1 min-w-[220px]">
            <input
              v-model="filters.search"
              @input="filterCatalog"
              type="text"
              placeholder="Cari nama, kode barang, lokasi simpan..."
              class="w-full px-4 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-emerald-500 transition"
            />
          </div>

          <select
            v-model="filters.category_id"
            @change="filterCatalog"
            class="px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 transition"
          >
            <option value="">Semua Kategori</option>
            <option v-for="c in categories" :key="c.id" :value="c.id">{{ c.name }}</option>
          </select>

          <select
            v-model="filters.condition"
            @change="filterCatalog"
            class="px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 transition"
          >
            <option value="">Semua Kondisi Fisik</option>
            <option value="baik">Kondisi Baik</option>
            <option value="rusak_ringan">Rusak Ringan</option>
          </select>

          <select
            v-model="filters.source"
            @change="filterCatalog"
            class="px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 transition"
          >
            <option value="">Semua Sumber Kepemilikan</option>
            <option value="internal">Milik Internal</option>
            <option value="mitra">Pinjaman Mitra</option>
          </select>
        </div>

        <!-- Catalog Cards Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
          <div
            v-for="item in filteredCatalog"
            :key="item.id"
            class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm flex flex-col justify-between hover:border-slate-300 transition"
          >
            <!-- Card Thumbnail with Badges -->
            <div class="relative h-44 bg-slate-900 overflow-hidden flex items-center justify-center">
              <img
                v-if="item.photo_path"
                :src="item.photo_path"
                :alt="item.name"
                class="w-full h-full object-cover"
              />
              <div v-else class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-600">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </div>

              <!-- Top Badges -->
              <div class="absolute top-3 left-3">
                <span class="px-2.5 py-0.5 rounded-md bg-[#070b14]/80 text-cyan-400 border border-emerald-200 font-mono font-bold text-[10px] backdrop-blur-sm shadow">
                  {{ item.code }}
                </span>
              </div>
              <div class="absolute top-3 right-3">
                <span class="px-2.5 py-0.5 rounded-full bg-emerald-950/80 text-emerald-300 border border-emerald-700/60 font-bold text-[10px] backdrop-blur-sm shadow flex items-center space-x-1">
                  <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                  <span>{{ item.condition === 'baik' ? 'Baik' : item.condition }}</span>
                </span>
              </div>
            </div>

            <!-- Card Content -->
            <div class="p-4 flex-1 flex flex-col justify-between space-y-3">
              <div>
                <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-wider mb-1">
                  <span class="text-rose-400">{{ item.category?.name || 'ALAT UMUM' }}</span>
                  <span class="px-2 py-0.5 rounded bg-teal-50 text-slate-500 border border-slate-200">
                    {{ item.source === 'mitra' ? (item.partner?.name || 'Mitra Luar') : 'Milik Rojabiyyah' }}
                  </span>
                </div>

                <h4 class="font-extrabold text-slate-900 text-sm tracking-wide capitalize">{{ item.name }}</h4>

                <div class="flex items-center space-x-1 text-[11px] text-slate-500 mt-1">
                  <svg class="w-3.5 h-3.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  <span>{{ item.location || 'Gudang Inventaris Utama' }}</span>
                </div>
              </div>

              <!-- Stock metrics -->
              <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200 space-y-1.5 text-xs">
                <div class="flex items-center justify-between">
                  <span class="text-slate-500 text-[11px]">Total Stok Fisik:</span>
                  <span class="font-mono font-bold text-white px-2 py-0.5 rounded bg-slate-800 text-[10px]">{{ item.qty_total }} Unit</span>
                </div>
                <div class="flex items-center justify-between">
                  <span class="text-slate-500 text-[11px]">Stok Siap Pakai:</span>
                  <span class="font-mono font-bold text-emerald-400 px-2 py-0.5 rounded bg-emerald-950/80 border border-emerald-800/50 text-[10px]">{{ item.qty_available }} Unit</span>
                </div>
              </div>

              <!-- Action Buttons: Distinct for Admin vs Anggota -->
              <div class="grid grid-cols-2 gap-2 pt-1">
                <!-- For Anggota: "Rincian" & "Pinjam Ini" -->
                <template v-if="!isAdmin">
                  <button
                    @click="openItemDetail(item)"
                    class="py-2 px-3 rounded-xl bg-slate-50 hover:bg-slate-800 border border-slate-300 text-slate-700 text-xs font-semibold flex items-center justify-center space-x-1 transition"
                  >
                    <span>Rincian</span>
                  </button>

                  <button
                    @click="openApplyModal(item)"
                    :disabled="item.qty_available <= 0"
                    class="py-2 px-3 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white text-xs font-bold flex items-center justify-center space-x-1 shadow-md transition disabled:opacity-40"
                  >
                    <span>Pinjam Ini</span>
                  </button>
                </template>

                <!-- For Admin: "Rincian" & "Kelola di Inventaris" (Admin does NOT borrow) -->
                <template v-else>
                  <button
                    @click="openItemDetail(item)"
                    class="py-2 px-3 rounded-xl bg-slate-50 hover:bg-slate-800 border border-slate-300 text-slate-700 text-xs font-semibold flex items-center justify-center space-x-1 transition"
                  >
                    <span>Rincian</span>
                  </button>

                  <router-link
                    to="/inventory"
                    class="py-2 px-3 rounded-xl bg-[#1c2847] hover:bg-[#25365e] border border-sky-500/50 text-sky-200 text-xs font-bold flex items-center justify-center space-x-1 shadow transition text-center"
                  >
                    <span>Kelola Stok &rarr;</span>
                  </router-link>
                </template>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 1: FORM PENGAJUAN PEMINJAMAN (Khusus Anggota)                       -->
    <!-- ========================================================================= -->
    <div v-if="showApplyModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md p-4 overflow-y-auto">
      <div class="relative w-full max-w-2xl bg-white border border-slate-200 rounded-3xl p-6 sm:p-8 shadow-sm text-slate-700 my-8 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200 mb-5">
          <div>
            <h3 class="text-base font-bold text-slate-900 tracking-wide">Formulir Pengajuan Peminjaman Barang</h3>
            <p class="text-xs text-slate-500 mt-0.5">Pengajuan resmi untuk operasional penugasan relawan anggota.</p>
          </div>
          <button @click="showApplyModal = false" class="text-slate-500 hover:text-white transition p-1 rounded-lg hover:bg-slate-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <form @submit.prevent="submitLoanRequest" class="space-y-4 text-xs">
          <!-- Nama Peminjam & Kontak -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Nama Peminjam *</label>
              <input
                v-model="applyForm.borrower_name"
                type="text"
                required
                readonly
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-700 font-bold cursor-not-allowed"
              />
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Email / No. WhatsApp</label>
              <input
                v-model="applyForm.borrower_contact"
                type="text"
                required
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500"
              />
            </div>
          </div>

          <!-- Banner Sub Tema Acara -->
          <div class="p-3 rounded-2xl bg-[#1a0f1b] border border-rose-900/60 flex items-center justify-between text-xs">
            <span class="text-rose-400 font-bold">Sub Tema Acara:</span>
            <span class="text-white font-semibold">Haul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad &amp; Rojabiyyah 2026</span>
          </div>

          <!-- Section: Daftar Barang yang Dipinjam -->
          <div>
            <div class="flex items-center justify-between mb-2">
              <label class="font-semibold text-slate-700">Daftar Barang yang Dipinjam (Bisa Lebih Dari 1 Barang) *</label>
              <button
                type="button"
                @click="addMoreItemRow"
                class="inline-flex items-center space-x-1 px-3 py-1 rounded-xl bg-slate-50 hover:bg-slate-800 border border-rose-700/60 text-rose-300 text-[11px] font-bold"
              >
                <span>+ Tambah Barang Lain</span>
              </button>
            </div>

            <!-- List of dynamic item rows -->
            <div class="space-y-3">
              <div
                v-for="(row, index) in applyForm.items"
                :key="index"
                class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-2 relative"
              >
                <div class="flex items-center justify-between text-[11px] font-bold text-amber-400">
                  <span>Barang #{{ index + 1 }}</span>
                  <button
                    v-if="applyForm.items.length > 1"
                    type="button"
                    @click="applyForm.items.splice(index, 1)"
                    class="text-rose-400 hover:underline"
                  >
                    Hapus
                  </button>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-4 gap-3">
                  <div class="sm:col-span-3">
                    <label class="block text-slate-500 text-[10px] mb-1">Pilih Alat / Barang *</label>
                    <select
                      v-model="row.item_id"
                      required
                      class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 text-xs focus:outline-none focus:border-emerald-500"
                    >
                      <option :value="null">-- Pilih Barang dari Katalog --</option>
                      <!-- Group 1: Milik Internal Rojabiyyah -->
                      <optgroup label="📦 INVENTARIS INTERNAL ROJABIYYAH">
                        <option
                          v-for="it in internalItems"
                          :key="it.id"
                          :value="it.id"
                          :disabled="it.qty_available <= 0"
                        >
                          {{ it.name }} ({{ it.code }}) • Tersedia: {{ it.qty_available }} Unit [{{ it.category?.name || 'Umum' }}]
                        </option>
                      </optgroup>
                      <!-- Group 2: Pinjaman Antar Lembaga / Mitra -->
                      <optgroup label="🤝 PINJAMAN ANTAR LEMBAGA / MITRA">
                        <option
                          v-for="it in mitraItems"
                          :key="it.id"
                          :value="it.id"
                          :disabled="it.qty_available <= 0"
                        >
                          {{ it.name }} ({{ it.code }}) • Mitra: {{ it.partner?.name || 'Lembaga Mitra' }} (Tersedia: {{ it.qty_available }} Unit)
                        </option>
                      </optgroup>
                    </select>
                  </div>
                  <div>
                    <label class="block text-slate-500 text-[10px] mb-1">Jumlah Unit *</label>
                    <input
                      v-model.number="row.qty"
                      type="number"
                      min="1"
                      :max="getItem(row.item_id)?.qty_available || 99"
                      required
                      class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 text-xs font-mono"
                    />
                  </div>
                </div>

                <!-- Selected Item Summary & Integration Info -->
                <div v-if="getItem(row.item_id)" class="mt-2 pt-2 border-t border-slate-200 flex flex-wrap items-center justify-between gap-2 text-[11px]">
                  <div class="flex items-center space-x-2">
                    <span
                      :class="[
                        'px-2 py-0.5 rounded-md font-bold text-[10px] border',
                        getItem(row.item_id)?.source === 'mitra'
                          ? 'bg-amber-950/70 text-amber-300 border-amber-800'
                          : 'bg-emerald-950/70 text-emerald-300 border-emerald-800'
                      ]"
                    >
                      {{ getItem(row.item_id)?.source === 'mitra' ? ('🤝 Mitra: ' + (getItem(row.item_id)?.partner?.name || 'Antar Lembaga')) : '📦 Milik Internal Rojabiyyah' }}
                    </span>
                    <span class="text-slate-500">📍 {{ getItem(row.item_id)?.location || 'Gudang Utama' }}</span>
                  </div>

                  <div class="flex items-center space-x-2 font-mono">
                    <span class="text-slate-500">Stok Siap:</span>
                    <span class="font-bold text-emerald-400">{{ getItem(row.item_id)?.qty_available }} Unit</span>
                    <span v-if="row.qty > getItem(row.item_id)?.qty_available" class="text-rose-400 font-bold font-sans">
                      ⚠️ Melebihi stok!
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Tanggal Pinjam & Estimasi Tanggal Kembali -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Tanggal Pinjam *</label>
              <input
                v-model="applyForm.requested_at"
                type="date"
                required
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 font-sans focus:outline-none focus:border-emerald-500"
              />
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1">Estimasi Tanggal Kembali *</label>
              <input
                v-model="applyForm.due_at"
                type="date"
                required
                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 font-sans focus:outline-none focus:border-emerald-500"
              />
            </div>
          </div>

          <!-- Keperluan Peminjaman -->
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Keperluan Peminjaman *</label>
            <input
              v-model="applyForm.purpose"
              type="text"
              required
              placeholder="CONTOH: DOKUMENTASI MAULID NABI / LIPUTAN HAUL KE-5"
              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 uppercase placeholder-slate-500 focus:outline-none focus:border-emerald-500"
            />
          </div>

          <!-- Catatan Tambahan -->
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Catatan Tambahan (Opsional)</label>
            <textarea
              v-model="applyForm.notes"
              rows="2"
              placeholder="Catatan aksesoris tambahan yang diperlukan..."
              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-emerald-500"
            ></textarea>
          </div>

          <!-- Bottom Actions -->
          <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-200">
            <button
              type="button"
              @click="showApplyModal = false"
              class="px-4 py-2.5 rounded-xl bg-slate-50 hover:bg-slate-800 text-slate-700 font-semibold"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="submitting"
              class="px-5 py-2.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold shadow-lg shadow-emerald-950/60 transition disabled:opacity-50"
            >
              {{ submitting ? 'Mengirim...' : 'Kirim Pengajuan Peminjaman' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 2: FORM PENGEMBALIAN BARANG DENGAN BUKTI FOTO (Khusus Anggota)       -->
    <!-- ========================================================================= -->
    <div v-if="showReturnModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md p-4 overflow-y-auto">
      <div class="relative w-full max-w-lg bg-white border border-slate-200 rounded-3xl p-6 shadow-sm text-slate-700 my-8">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200 mb-3">
          <div class="flex items-center space-x-2.5">
            <div class="w-8 h-8 rounded-xl bg-teal-950/80 border border-teal-700/80 text-teal-400 flex items-center justify-center">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            </div>
            <h3 class="text-base font-bold text-slate-900 tracking-wide">
              Pengembalian Barang – {{ returnTargetLoan?.loan_no }}
            </h3>
          </div>
          <button @click="showReturnModal = false" class="text-slate-500 hover:text-white transition p-1 rounded-lg hover:bg-slate-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
          </button>
        </div>

        <p class="text-xs text-slate-500 mb-4 leading-relaxed">
          Wajib menyertakan bukti: ambil foto fisik barang saat ini via kamera HP atau unggah file foto dari galeri.
        </p>

        <form @submit.prevent="submitReturnForm" class="space-y-4 text-xs">
          <div>
            <label class="block font-semibold text-slate-700 mb-2">Foto Fisik Barang Bukti Pengembalian *</label>

            <!-- Hidden Inputs for Camera vs Gallery -->
            <input
              ref="cameraInputRef"
              type="file"
              accept="image/*"
              capture="environment"
              class="hidden"
              @change="handleReturnPhoto"
            />
            <input
              ref="galleryInputRef"
              type="file"
              accept="image/*"
              class="hidden"
              @change="handleReturnPhoto"
            />

            <!-- Preview or Selector Box -->
            <div
              v-if="returnForm.photo"
              class="relative rounded-2xl overflow-hidden border border-teal-500/60 bg-slate-50 p-3 text-center"
            >
              <img :src="returnForm.photo" alt="Bukti Foto" class="max-h-56 mx-auto rounded-xl object-contain mb-2 shadow-lg" />
              <button
                type="button"
                @click="returnForm.photo = null"
                class="px-3 py-1 rounded-lg bg-rose-950/80 hover:bg-rose-900 border border-rose-700 text-rose-200 text-xs font-semibold transition"
              >
                Hapus / Ganti Foto
              </button>
            </div>

            <!-- Selector Box with Camera / Gallery Buttons -->
            <div
              v-else
              class="border-2 border-dashed border-slate-200 rounded-2xl p-6 text-center bg-slate-50/60 hover:border-slate-600 transition"
            >
              <div class="w-14 h-14 rounded-full bg-emerald-50 border border-cyan-800/80 flex items-center justify-center text-cyan-400 mx-auto mb-3 shadow-inner">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
              </div>

              <h4 class="font-bold text-slate-900 text-sm mb-0.5">Pilih Metode Pengambilan Foto</h4>
              <p class="text-[11px] text-slate-500 mb-4">Dukung kamera HP langsung atau unggah file foto dari galeri</p>

              <div class="flex items-center justify-center gap-3">
                <button
                  type="button"
                  @click="triggerCameraInput"
                  class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-[#0f2336] hover:bg-[#13324d] border border-teal-500/60 text-teal-300 font-bold transition shadow"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                  <span>Jepret Foto Kamera</span>
                </button>

                <button
                  type="button"
                  @click="triggerGalleryInput"
                  class="inline-flex items-center space-x-1.5 px-4 py-2 rounded-xl bg-teal-50 hover:bg-slate-800 border border-slate-300 text-slate-700 font-bold transition shadow"
                >
                  <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                  <span>Pilih dari Galeri</span>
                </button>
              </div>
            </div>
          </div>

          <div>
            <label class="block font-semibold text-slate-700 mb-1">Catatan Kondisi Barang / Serah Terima (Opsional)</label>
            <textarea
              v-model="returnForm.notes"
              rows="3"
              placeholder="Keterangan kelengkapan kabel, adaptor, kondisi fisik, dll."
              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-emerald-500"
            ></textarea>
          </div>

          <!-- Footer Buttons -->
          <div class="flex items-center justify-end space-x-3 pt-3 border-t border-slate-200">
            <button
              type="button"
              @click="showReturnModal = false"
              class="px-4 py-2 rounded-xl bg-teal-50 hover:bg-slate-800 border border-slate-300 text-slate-700 font-bold transition"
            >
              Batal
            </button>
            <button
              type="submit"
              :disabled="submittingReturn"
              class="inline-flex items-center space-x-1.5 px-5 py-2 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold transition shadow-lg shadow-emerald-950/60 disabled:opacity-50"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
              <span>{{ submittingReturn ? 'Menyimpan...' : 'Konfirmasi & Simpan Pengembalian' }}</span>
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 3: TOLAK PENGAJUAN PEMINJAMAN (Khusus Admin)                        -->
    <!-- ========================================================================= -->
    <div v-if="showRejectModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md p-4">
      <div class="relative w-full max-w-md bg-white border border-slate-200 rounded-3xl p-6 shadow-sm text-slate-700">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200 mb-4">
          <h3 class="text-base font-bold text-slate-900">Tolak Pengajuan Peminjaman</h3>
          <button @click="showRejectModal = false" class="text-slate-500 hover:text-white">✕</button>
        </div>

        <p class="text-xs text-slate-500 mb-3">
          Tolak permohonan <strong class="text-white">{{ rejectTargetLoan?.loan_no }}</strong> atas nama
          <strong class="text-white">{{ rejectTargetLoan?.borrower?.name }}</strong>. Berikan alasan penolakan.
        </p>

        <form @submit.prevent="submitReject" class="space-y-4 text-xs">
          <div>
            <label class="block font-semibold text-slate-700 mb-1">Alasan Penolakan *</label>
            <textarea
              v-model="rejectReason"
              required
              rows="3"
              placeholder="misal: Barang bentrok jadwal dengan live streaming panggung utama / stok tidak cukup..."
              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-800 placeholder-slate-500 focus:outline-none focus:border-emerald-500"
            ></textarea>
          </div>

          <div class="flex items-center justify-end space-x-2 pt-3 border-t border-slate-200">
            <button type="button" @click="showRejectModal = false" class="px-4 py-2 bg-slate-50 rounded-xl text-slate-700">Batal</button>
            <button
              type="submit"
              :disabled="submittingReject"
              class="px-5 py-2 bg-rose-600 hover:bg-rose-500 text-white font-bold rounded-xl shadow"
            >
              {{ submittingReject ? 'Menolak...' : 'Konfirmasi Tolak' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 4: LIHAT BUKTI FOTO PENGEMBALIAN (Admin & Anggota)                   -->
    <!-- ========================================================================= -->
    <div v-if="showEvidenceModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md p-4">
      <div class="relative w-full max-w-lg bg-white border border-slate-200 rounded-3xl p-6 shadow-sm text-slate-700">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200 mb-4">
          <div>
            <h3 class="text-base font-bold text-slate-900">Bukti Foto Serah Terima / Pengembalian</h3>
            <span class="text-xs font-mono text-cyan-400">{{ evidenceTargetLoan?.loan_no }}</span>
          </div>
          <button @click="showEvidenceModal = false" class="text-slate-500 hover:text-white">✕</button>
        </div>

        <div class="space-y-4 text-xs">
          <!-- Photo Box -->
          <div class="rounded-2xl overflow-hidden border border-slate-300 bg-slate-950 p-2 flex items-center justify-center min-h-[220px]">
            <img
              v-if="evidenceTargetLoan?.photo_proof || evidenceTargetLoan?.loan_items?.[0]?.photo_in"
              :src="evidenceTargetLoan?.photo_proof || evidenceTargetLoan?.loan_items?.[0]?.photo_in"
              alt="Bukti Pengembalian"
              class="max-h-72 w-full object-contain rounded-xl"
            />
            <div v-else class="text-center p-8 text-slate-500">
              <svg class="w-12 h-12 mx-auto mb-2 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
              <span>Foto tidak tersedia / belum diunggah.</span>
            </div>
          </div>

          <!-- Details -->
          <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-1.5">
            <div class="flex justify-between">
              <span class="text-slate-500">Peminjam:</span>
              <span class="font-bold text-white">{{ evidenceTargetLoan?.borrower?.name || '-' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-500">Waktu Dikembalikan:</span>
              <span class="font-mono text-slate-700">{{ formatDateTime(evidenceTargetLoan?.returned_at) }}</span>
            </div>
            <div>
              <span class="text-slate-500 block mb-0.5">Catatan Kondisi Barang:</span>
              <p class="text-slate-700 bg-[#0c1324] p-2 rounded-lg border border-slate-200 italic">
                "{{ evidenceTargetLoan?.return_notes || evidenceTargetLoan?.loan_items?.[0]?.condition_in || 'Kondisi barang baik & lengkap' }}"
              </p>
            </div>
          </div>

          <div class="flex justify-end pt-2">
            <button @click="showEvidenceModal = false" class="px-4 py-2 bg-slate-50 hover:bg-slate-800 border border-slate-300 text-slate-700 rounded-xl font-bold">
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- ========================================================================= -->
    <!-- MODAL 5: PREVIEW & CETAK DOKUMEN RESMI (F4 FOLIO / STIKER MULTI-UKURAN)     -->
    <!-- ========================================================================= -->
    <div v-if="showPrintModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md p-2 sm:p-4 overflow-y-auto">
      <div class="relative w-full max-w-5xl bg-white border border-slate-200 rounded-3xl p-4 sm:p-6 shadow-sm text-slate-700 my-4 max-h-[96vh] overflow-y-auto">
        <!-- Top Toolbar -->
        <div class="no-print flex flex-wrap items-center justify-between pb-4 border-b border-slate-200 gap-3">
          <!-- Left: Tab Switcher -->
          <div class="flex flex-wrap items-center gap-2 text-xs">
            <span class="text-slate-500 font-bold mr-1">DOKUMEN:</span>
            <button
              @click="printTab = 'bukti'"
              :class="printTab === 'bukti' ? 'bg-[#0f766e] text-white border-teal-500 shadow-lg shadow-teal-950/60' : 'bg-slate-50 text-slate-500 border-slate-200 hover:text-white'"
              class="px-3.5 py-1.5 rounded-xl font-bold border transition flex items-center space-x-1.5"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              <span>Bukti Peminjaman</span>
            </button>
            <button
              @click="printTab = 'surat_jalan'"
              :class="printTab === 'surat_jalan' ? 'bg-[#0f766e] text-white border-teal-500 shadow-lg shadow-teal-950/60' : 'bg-slate-50 text-slate-500 border-slate-200 hover:text-white'"
              class="px-3.5 py-1.5 rounded-xl font-bold border transition flex items-center space-x-1.5"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
              <span>Surat Jalan</span>
            </button>
            <button
              @click="printTab = 'stiker'"
              :class="printTab === 'stiker' ? 'bg-[#d97706] text-white border-amber-500 shadow-lg shadow-amber-950/60' : 'bg-slate-50 text-slate-500 border-slate-200 hover:text-white'"
              class="px-3.5 py-1.5 rounded-xl font-bold border transition flex items-center space-x-1.5"
            >
              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
              <span>Stiker F4 (Multi-Ukuran)</span>
            </button>
          </div>

          <!-- Right: Print & Close -->
          <div class="flex items-center space-x-2">
            <button
              @click="triggerBrowserPrint"
              class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl bg-[#be123c] hover:bg-[#a11033] text-white text-xs font-bold shadow-lg shadow-emerald-950/60 transition active:scale-95"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
              <span>Cetak Sekarang (Print F4)</span>
            </button>
            <button @click="showPrintModal = false" class="text-slate-500 hover:text-white p-1.5 rounded-lg hover:bg-slate-800 transition">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>
        </div>

        <!-- Banner & Ukuran Selector for Stiker F4 -->
        <div v-if="printTab === 'stiker'" class="no-print my-3 space-y-2">
          <div class="flex flex-wrap items-center justify-between gap-3 p-3.5 bg-emerald-50 border border-emerald-200 rounded-2xl text-xs">
            <div class="flex items-start space-x-3">
              <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-800 border border-emerald-300 flex items-center justify-center flex-shrink-0 mt-0.5">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              </div>
              <div>
                <h4 class="font-bold text-emerald-950 text-sm">Lembar Stiker Penanda Barang Ukuran F4 (Siap Cetak pada Kertas Stiker Label)</h4>
                <p class="text-slate-600 text-[11px] mt-0.5">Dilengkapi Barcode &amp; QR Code cerdas untuk pelacakan barang fisik di lapangan Haul Ke-5 &amp; Rojabiyyah 2026.</p>
              </div>
            </div>
            <div class="flex items-center space-x-2 text-right">
              <span class="px-2.5 py-1 rounded-lg bg-emerald-100 border border-emerald-300 text-emerald-900 font-bold text-xs">
                {{ selectedLoan?.loan_items?.length || 1 }} Jenis Barang (Total {{ expandedLoanItems.length }} Unit)
              </span>
              <span class="px-2.5 py-1 rounded-lg bg-white border border-slate-200 text-slate-700 font-medium text-xs">
                Haul Ke-5 &amp; Rojabiyyah 2026
              </span>
            </div>
          </div>

          <!-- Ukuran Selector Toolbar -->
          <div class="flex flex-wrap items-center justify-between gap-2 p-2 bg-slate-50 border border-slate-200 rounded-xl text-xs">
            <span class="text-slate-700 font-bold px-2">Format Ukuran:</span>
            <div class="flex flex-wrap items-center gap-1.5">
              <button
                @click="stickerSize = 'campur'"
                :class="stickerSize === 'campur' ? 'bg-[#0f766e] text-white font-bold shadow-xs' : 'bg-white text-slate-700 hover:text-emerald-800 border border-slate-300'"
                class="px-3 py-1.5 rounded-lg transition text-xs"
              >
                1 Lembar F4 (Multi-Ukuran: 1 Besar + 2 Sedang + 4 Mini)
              </button>
              <button
                @click="stickerSize = 'besar'"
                :class="stickerSize === 'besar' ? 'bg-[#0f766e] text-white font-bold shadow-xs' : 'bg-white text-slate-700 hover:text-emerald-800 border border-slate-300'"
                class="px-3 py-1.5 rounded-lg transition text-xs"
              >
                Besar (Box)
              </button>
              <button
                @click="stickerSize = 'sedang'"
                :class="stickerSize === 'sedang' ? 'bg-[#0f766e] text-white font-bold shadow-xs' : 'bg-white text-slate-700 hover:text-emerald-800 border border-slate-300'"
                class="px-3 py-1.5 rounded-lg transition text-xs"
              >
                Sedang (Unit)
              </button>
              <button
                @click="stickerSize = 'kecil'"
                :class="stickerSize === 'kecil' ? 'bg-[#0f766e] text-white font-bold shadow-xs' : 'bg-white text-slate-700 hover:text-emerald-800 border border-slate-300'"
                class="px-3 py-1.5 rounded-lg transition text-xs"
              >
                Kecil / Mini
              </button>
            </div>
          </div>

          <!-- Pilihan Barang Cetak Toolbar (Aktif bila lebih dari 1 barang) -->
          <div v-if="expandedLoanItems.length > 1 && stickerSize === 'campur'" class="flex flex-wrap items-center justify-between gap-2 p-2.5 bg-emerald-50/80 border border-emerald-200 rounded-xl text-xs">
            <div class="flex items-center space-x-1.5 px-2">
              <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
              <span class="text-emerald-950 font-black">Mode Cetak Barang:</span>
            </div>
            <div class="flex flex-wrap items-center gap-1.5">
              <button
                @click="selectedStickerItem = 'gabungan'"
                :class="selectedStickerItem === 'gabungan' ? 'bg-[#0f766e] text-white font-bold shadow-xs' : 'bg-white text-slate-700 hover:text-emerald-800 border border-slate-300'"
                class="px-2.5 py-1.5 rounded-lg transition text-xs flex items-center space-x-1"
                title="Semua barang gabung dalam 1 lembar F4 hemat kertas"
              >
                <span>Gabungan 1 Lembar (Semua Barang)</span>
              </button>
              <button
                @click="selectedStickerItem = 'semua_lembar'"
                :class="selectedStickerItem === 'semua_lembar' ? 'bg-[#0f766e] text-white font-bold shadow-xs' : 'bg-white text-slate-700 hover:text-emerald-800 border border-slate-300'"
                class="px-2.5 py-1.5 rounded-lg transition text-xs flex items-center space-x-1"
                title="Cetak stiker lengkap untuk setiap barang, masing-masing 1 lembar F4 penuh"
              >
                <span>Cetak Tiap Barang ({{ expandedLoanItems.length }} Lembar F4 Penuh)</span>
              </button>
              <button
                v-for="(li, idx) in expandedLoanItems"
                :key="'btn-sel-item-' + idx"
                @click="selectedStickerItem = idx"
                :class="selectedStickerItem === idx ? 'bg-emerald-700 text-white font-bold shadow-xs' : 'bg-white text-slate-700 hover:text-emerald-800 border border-slate-300'"
                class="px-2.5 py-1.5 rounded-lg transition text-xs"
                :title="`Cetak hanya untuk ${li.item?.name}`"
              >
                {{ idx + 1 }}. {{ li.item?.name }} ({{ li.item?.code }})
              </button>
            </div>
          </div>
        </div>

        <!-- PRINTABLE SHEET CONTAINER (Clean White F4 Paper) -->
        <div id="printableDocument" class="bg-white text-slate-900 rounded-2xl p-6 sm:p-8 shadow-sm my-2 text-xs font-sans border border-slate-200">
          <!-- ========================================================================= -->
          <!-- 1. BUKTI PEMINJAMAN BARANG (TAB BUKTI)                                    -->
          <!-- ========================================================================= -->
          <div v-if="printTab === 'bukti'" class="space-y-4">
            <!-- Header Bukti -->
            <div class="text-center">
              <h1 class="text-2xl font-black text-[#0f766e] tracking-tight uppercase">
                BUKTI PEMINJAMAN BARANG
              </h1>
              <h2 class="text-sm font-black text-slate-900 tracking-wider uppercase mt-1">
                {{ settingsStore.settings?.kop_name || 'DOKUMENTASI ROJABIYYAH 2026' }}
              </h2>
              <p class="text-xs font-bold text-[#0f766e] mt-0.5">
                {{ settingsStore.settings?.kop_sub_theme || 'Haul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad & Rojabiyyah 2026' }}
              </p>
              <div class="w-full h-1 bg-[#0f766e] mt-4 mb-5"></div>
            </div>

            <!-- Table 2-Col Data -->
            <table class="w-full text-left text-xs divide-y divide-slate-200">
              <tbody class="divide-y divide-slate-200">
                <tr>
                  <td class="py-2.5 w-1/3 font-bold text-slate-900">Nama Peminjam</td>
                  <td class="py-2.5 font-bold text-slate-900">{{ selectedLoan?.borrower?.name }}</td>
                </tr>
                <tr>
                  <td class="py-2.5 font-bold text-slate-900">Email / Kontak</td>
                  <td class="py-2.5 text-slate-800">{{ selectedLoan?.borrower?.email || selectedLoan?.borrower?.phone_wa || '-' }}</td>
                </tr>
                <tr>
                  <td class="py-2.5 font-bold text-slate-900 align-top">Barang</td>
                  <td class="py-2.5 text-slate-900">
                    <div v-for="(li, idx) in selectedLoan?.loan_items" :key="li.id" class="font-medium">
                      {{ idx + 1 }}. {{ li.item?.name }} ({{ li.qty_approved || li.qty_requested }} Unit)
                    </div>
                  </td>
                </tr>
                <tr>
                  <td class="py-2.5 font-bold text-slate-900">Kode Barang</td>
                  <td class="py-2.5 font-mono font-bold text-slate-900">
                    {{ selectedLoan?.loan_items?.map(li => li.item?.code).filter(Boolean).join(', ') || '-' }}
                  </td>
                </tr>
                <tr>
                  <td class="py-2.5 font-bold text-slate-900">Jumlah</td>
                  <td class="py-2.5 font-bold text-slate-900">
                    {{ selectedLoan?.loan_items?.reduce((acc, li) => acc + (li.qty_approved || li.qty_requested || 1), 0) || 1 }} Unit
                  </td>
                </tr>
                <tr>
                  <td class="py-2.5 font-bold text-slate-900">Tanggal Pinjam</td>
                  <td class="py-2.5 text-slate-800">{{ formatDate(selectedLoan?.requested_at) }}</td>
                </tr>
                <tr>
                  <td class="py-2.5 font-bold text-slate-900">Rencana Kembali</td>
                  <td class="py-2.5 text-slate-800">{{ formatDate(selectedLoan?.due_at) }}</td>
                </tr>
                <tr>
                  <td class="py-2.5 font-bold text-slate-900">Tanggal Kembali Realisasi</td>
                  <td class="py-2.5 text-slate-800">{{ selectedLoan?.returned_at ? formatDate(selectedLoan?.returned_at) : '-' }}</td>
                </tr>
                <tr>
                  <td class="py-2.5 font-bold text-slate-900">Keperluan</td>
                  <td class="py-2.5 font-bold text-slate-900 uppercase">{{ selectedLoan?.purpose }}</td>
                </tr>
                <tr>
                  <td class="py-2.5 font-bold text-slate-900">Status</td>
                  <td class="py-2.5">
                    <span class="font-black text-[#0f766e] uppercase">
                      {{ selectedLoan?.status === 'diserahkan' ? 'SEDANG DIPINJAM' : (selectedLoan?.status === 'disetujui' ? 'DISETUJUI' : selectedLoan?.status) }}
                    </span>
                  </td>
                </tr>
                <tr>
                  <td class="py-2.5 font-bold text-slate-900">Catatan Admin</td>
                  <td class="py-2.5 italic text-slate-700">Disetujui oleh Super Admin</td>
                </tr>
              </tbody>
            </table>

            <!-- Signatures Section -->
            <div class="grid grid-cols-2 text-center pt-10 mt-6 text-xs">
              <div>
                <p class="text-slate-600 mb-14 font-medium">Peminjam</p>
                <p class="font-bold text-slate-950 text-sm">{{ selectedLoan?.borrower?.name }}</p>
              </div>
              <div>
                <p class="text-slate-600 mb-14 font-medium">Admin</p>
                <p class="font-bold text-slate-950 text-sm">{{ selectedLoan?.approver?.name || 'admin' }}</p>
              </div>
            </div>

            <!-- Footer Bukti -->
            <div class="text-center text-[10px] text-slate-500 pt-8">
              Kode Referensi: {{ selectedLoan?.loan_no }} &bull; Dicetak pada: {{ formatDate(new Date()) }}
            </div>
          </div>

          <!-- ========================================================================= -->
          <!-- 2. SURAT JALAN PENGELUARAN & TRANSPORTASI BARANG (TAB SURAT JALAN)        -->
          <!-- ========================================================================= -->
          <div v-else-if="printTab === 'surat_jalan'" class="space-y-3.5">
            <!-- Header Surat Jalan -->
            <div class="flex items-center justify-between pb-2 border-b-2 border-slate-900">
              <div class="flex items-center space-x-3">
                <img
                  :src="settingsStore.settings?.kop_logo_url || settingsStore.settings?.logo_url || '/logo.png'"
                  alt="Logo Kop"
                  class="w-14 h-14 object-contain flex-shrink-0"
                />
                <div>
                  <h1 class="text-base font-black uppercase text-slate-950 tracking-tight leading-tight">
                    {{ settingsStore.settings?.kop_name || 'DOKUMENTASI ROJABIYYAH 2026' }}
                  </h1>
                  <h2 class="text-xs font-bold text-emerald-800 tracking-tight mt-0.5">
                    {{ settingsStore.settings?.kop_sub_theme || 'Haul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad & Rojabiyyah 2026' }}
                  </h2>
                  <p class="text-[10px] text-slate-500 mt-0.5">
                    Divisi Transportasi &amp; Pengawalan Logistik Penugasan
                  </p>
                </div>
              </div>
              <div class="text-right flex flex-col items-end">
                <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase bg-emerald-100 text-emerald-800 border border-emerald-300">
                  SURAT JALAN RESMI
                </span>
                <span class="font-mono font-bold text-xs text-slate-900 mt-1">
                  SJ/HAUL5/{{ selectedLoan?.loan_no }}
                </span>
              </div>
            </div>

            <!-- Title & Ref -->
            <div class="text-center pt-1">
              <h2 class="text-sm sm:text-base font-black text-slate-950 uppercase tracking-wide underline">
                SURAT JALAN PENGELUARAN &amp; TRANSPORTASI BARANG
              </h2>
              <p class="text-xs text-slate-600 mt-1">
                Acara: <strong class="text-slate-900 font-bold">Haul Ke-5 &amp; Rojabiyyah 2026</strong> &bull; Ref Peminjaman: <strong class="font-mono text-slate-900 font-bold">{{ selectedLoan?.loan_no }}</strong>
              </p>
            </div>

            <!-- Info Box 2-Columns -->
            <div class="border border-slate-300 rounded-xl p-3 bg-slate-50/60 my-2">
              <div class="grid grid-cols-2 gap-x-6 gap-y-1.5 text-xs">
                <div><span class="text-slate-500 w-32 inline-block">Petugas Pembawa</span> : <strong class="text-slate-900 font-bold">{{ selectedLoan?.borrower?.name }}</strong></div>
                <div><span class="text-slate-500 w-32 inline-block">Tanggal Berangkat</span> : <strong class="text-slate-900 font-bold">{{ formatDate(selectedLoan?.requested_at) }}</strong></div>
                <div><span class="text-slate-500 w-32 inline-block">Kontak / No WA</span> : <strong class="text-slate-900 font-bold">{{ selectedLoan?.borrower?.phone_wa || selectedLoan?.borrower?.email || '-' }}</strong></div>
                <div><span class="text-slate-500 w-32 inline-block">Estimasi Kembali</span> : <strong class="text-slate-900 font-bold">{{ formatDate(selectedLoan?.due_at) }}</strong></div>
                <div><span class="text-slate-500 w-32 inline-block">Tujuan / Lokasi</span> : <strong class="text-slate-900 font-bold uppercase">{{ selectedLoan?.purpose }}</strong></div>
                <div><span class="text-slate-500 w-32 inline-block">Status Izin Jalan</span> : <strong class="text-emerald-700 font-black uppercase">RESMI DIIZINKAN</strong></div>
              </div>
            </div>

            <!-- Table Items -->
            <table class="w-full text-left text-xs border border-slate-300 divide-y divide-slate-300">
              <thead class="bg-slate-100 text-slate-900 font-bold uppercase text-[10px]">
                <tr>
                  <th class="p-2 border-r border-slate-300 text-center w-10">NO</th>
                  <th class="p-2 border-r border-slate-300 w-28">KODE ALAT</th>
                  <th class="p-2 border-r border-slate-300">DESKRIPSI BARANG</th>
                  <th class="p-2 border-r border-slate-300">ASAL KEPEMILIKAN</th>
                  <th class="p-2 border-r border-slate-300 text-center w-14">QTY</th>
                  <th class="p-2 border-r border-slate-300 text-center w-20">CHECK GO</th>
                  <th class="p-2 text-center w-20">CHECK BACK</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-200">
                <tr v-for="(li, idx) in selectedLoan?.loan_items" :key="li.id">
                  <td class="p-2 border-r border-slate-300 text-center font-bold">{{ idx + 1 }}</td>
                  <td class="p-2 border-r border-slate-300 font-mono font-bold text-emerald-800">{{ li.item?.code }}</td>
                  <td class="p-2 border-r border-slate-300 font-bold text-slate-900">{{ li.item?.name }}</td>
                  <td class="p-2 border-r border-slate-300 text-blue-700 font-medium">{{ li.item?.source === 'mitra' ? (li.item?.partner?.name || 'Pinjaman Mitra Luar') : 'Milik Internal Rojabiyyah' }}</td>
                  <td class="p-2 border-r border-slate-300 text-center font-bold text-slate-900">{{ li.qty_approved || li.qty_requested }}</td>
                  <td class="p-2 border-r border-slate-300 text-center font-mono font-bold text-slate-800">[ ✓ ]</td>
                  <td class="p-2 text-center font-mono font-bold text-slate-500">[ &nbsp; ]</td>
                </tr>
              </tbody>
            </table>

            <!-- Footnote Note -->
            <p class="text-[10px] italic text-slate-600 mt-1">
              * Surat jalan ini adalah dokumen sah pengawalan dan izin pengangkutan perlengkapan di jalan raya dan pos pengamanan Haul Ke-5 &amp; Rojabiyyah 2026.
            </p>

            <!-- 3 Signatures -->
            <div class="grid grid-cols-3 text-center pt-8 text-xs gap-4">
              <div>
                <p class="text-slate-600 mb-12 text-[11px]">Pengirim (Gudang/Admin),</p>
                <p class="font-bold text-slate-900 text-xs underline">{{ selectedLoan?.approver?.name || 'admin' }}</p>
                <p class="text-[10px] text-slate-500">Petugas Logistik</p>
              </div>
              <div>
                <p class="text-slate-600 mb-12 text-[11px]">Petugas Pembawa / Driver,</p>
                <p class="font-bold text-slate-900 text-xs underline">{{ selectedLoan?.borrower?.name }}</p>
                <p class="text-[10px] text-slate-500">Peminjam Lapangan</p>
              </div>
              <div>
                <p class="text-slate-600 mb-12 text-[11px]">Penerima Lokasi Tugas,</p>
                <p class="font-bold text-slate-900 text-xs underline">( .......................................... )</p>
                <p class="text-[10px] text-slate-500">Penanggung Jawab Acara</p>
              </div>
            </div>
          </div>

          <!-- ========================================================================= -->
          <!-- 3. STIKER KERTAS F4 MULTI-UKURAN (TAB STIKER)                             -->
          <!-- ========================================================================= -->
          <div v-else-if="printTab === 'stiker'" class="space-y-4">

            <!-- MODE 1: CAMPURAN 1 LEMBAR F4 / MULTI-UKURAN -->
            <div v-if="stickerSize === 'campur'">

              <!-- SUB-MODE A: CETAK TIAP BARANG (SEMUA LEMBAR F4 TERPISAH) -->
              <div v-if="selectedStickerItem === 'semua_lembar'" class="space-y-8">
                <div
                  v-for="(sheetLi, sheetIdx) in expandedLoanItems"
                  :key="'full-sheet-' + sheetIdx"
                  class="page-break space-y-3.5 pb-6"
                  :class="sheetIdx > 0 ? 'pt-8 border-t-2 border-dashed border-slate-300' : ''"
                >
                  <!-- Sheet Header -->
                  <div class="flex items-center justify-between pb-2 border-b-2 border-slate-900">
                    <div class="flex items-center space-x-2.5">
                      <img
                        :src="settingsStore.settings?.kop_logo_url || settingsStore.settings?.logo_url || '/logo.png'"
                        alt="Logo"
                        class="w-10 h-10 object-contain flex-shrink-0"
                      />
                      <div>
                        <h1 class="text-xs font-black uppercase text-slate-950 tracking-wide">
                          {{ settingsStore.settings?.kop_name || 'DOKUMENTASI ROJABIYYAH 2026' }}
                        </h1>
                        <p class="text-[10px] text-emerald-800 font-bold">
                          {{ settingsStore.settings?.kop_sub_theme || 'Haul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad & Rojabiyyah 2026' }}
                        </p>
                      </div>
                    </div>
                    <div class="text-right">
                      <span class="text-xs font-black uppercase tracking-wider text-slate-950 block">LEMBAR STIKER LABEL F4</span>
                      <span class="text-[10px] font-mono text-slate-600">Barang {{ sheetIdx + 1 }} dari {{ expandedLoanItems.length }} &bull; Ref: {{ selectedLoan?.loan_no }}</span>
                    </div>
                  </div>

                  <!-- 1. Stiker Boks / Hardcase (Besar) -->
                  <div class="border-2 border-slate-900 rounded-2xl overflow-hidden bg-white shadow-sm">
                    <div class="bg-slate-900 text-white px-3.5 py-2 flex items-center justify-between">
                      <div class="flex items-center space-x-2">
                        <img
                          :src="settingsStore.settings?.kop_logo_url || settingsStore.settings?.logo_url || '/logo.png'"
                          alt="Logo"
                          class="w-5 h-5 object-contain bg-white rounded p-0.5"
                        />
                        <span class="font-black text-xs uppercase tracking-wider">MEDIA ROJABIYYAH 2026</span>
                      </div>
                      <span class="px-2 py-0.5 rounded bg-emerald-500 text-slate-950 font-black text-[9px] uppercase tracking-wider">
                        STIKER BOKS / HARDCASE
                      </span>
                    </div>

                    <div class="p-3 grid grid-cols-12 gap-3 items-center">
                      <div class="col-span-3 flex flex-col items-center justify-center p-2 border border-slate-300 rounded-xl bg-slate-50 text-center">
                        <img
                          :src="`https://api.qrserver.com/v1/create-qr-code/?size=95x95&data=${encodeURIComponent(getVerifyUrl(selectedLoan?.loan_no))}`"
                          alt="QR"
                          class="w-18 h-18"
                        />
                        <span class="text-[8px] font-black text-slate-700 mt-1 uppercase tracking-tight">SCAN HP UNTUK CEK</span>
                      </div>

                      <div class="col-span-5 space-y-1 text-xs">
                        <div class="text-sm font-black text-slate-950 uppercase leading-snug">
                          {{ sheetLi.item?.name }}
                        </div>
                        <div class="text-[11px] font-mono font-black text-emerald-700">
                          {{ sheetLi.item?.code }}
                        </div>
                        <div class="text-[10px] text-slate-600">
                          Asal: <strong class="text-slate-800">{{ sheetLi.item?.source === 'mitra' ? (sheetLi.item?.partner?.name || 'Pinjaman Mitra Luar') : 'Milik Internal' }}</strong>
                        </div>
                        <div class="text-[10px] text-slate-600">
                          Peminjam: <strong class="text-slate-900 uppercase">{{ selectedLoan?.borrower?.name }}</strong>
                        </div>
                        <div class="text-[10px] text-slate-500 font-mono">
                          No. Pinjam: {{ selectedLoan?.loan_no }}
                        </div>
                        <div class="text-[10px] text-rose-700 font-bold">
                          Tenggat: {{ formatDate(selectedLoan?.due_at) }}
                        </div>
                      </div>

                      <div class="col-span-4 flex flex-col items-center justify-center p-2 border-l border-slate-200">
                        <svg class="w-full h-11" viewBox="0 0 160 40" preserveAspectRatio="none">
                          <rect x="0" y="0" width="3" height="40" fill="#000" />
                          <rect x="5" y="0" width="2" height="40" fill="#000" />
                          <rect x="9" y="0" width="4" height="40" fill="#000" />
                          <rect x="15" y="0" width="2" height="40" fill="#000" />
                          <rect x="19" y="0" width="5" height="40" fill="#000" />
                          <rect x="26" y="0" width="2" height="40" fill="#000" />
                          <rect x="30" y="0" width="3" height="40" fill="#000" />
                          <rect x="35" y="0" width="4" height="40" fill="#000" />
                          <rect x="41" y="0" width="2" height="40" fill="#000" />
                          <rect x="45" y="0" width="5" height="40" fill="#000" />
                          <rect x="52" y="0" width="3" height="40" fill="#000" />
                          <rect x="57" y="0" width="2" height="40" fill="#000" />
                          <rect x="61" y="0" width="4" height="40" fill="#000" />
                          <rect x="67" y="0" width="2" height="40" fill="#000" />
                          <rect x="71" y="0" width="5" height="40" fill="#000" />
                          <rect x="78" y="0" width="3" height="40" fill="#000" />
                          <rect x="83" y="0" width="2" height="40" fill="#000" />
                          <rect x="87" y="0" width="4" height="40" fill="#000" />
                          <rect x="93" y="0" width="2" height="40" fill="#000" />
                          <rect x="97" y="0" width="5" height="40" fill="#000" />
                          <rect x="104" y="0" width="3" height="40" fill="#000" />
                          <rect x="109" y="0" width="2" height="40" fill="#000" />
                          <rect x="113" y="0" width="4" height="40" fill="#000" />
                          <rect x="119" y="0" width="2" height="40" fill="#000" />
                          <rect x="123" y="0" width="5" height="40" fill="#000" />
                          <rect x="130" y="0" width="3" height="40" fill="#000" />
                          <rect x="135" y="0" width="2" height="40" fill="#000" />
                          <rect x="139" y="0" width="4" height="40" fill="#000" />
                          <rect x="145" y="0" width="3" height="40" fill="#000" />
                          <rect x="150" y="0" width="2" height="40" fill="#000" />
                          <rect x="154" y="0" width="4" height="40" fill="#000" />
                        </svg>
                        <span class="font-mono text-[9px] font-bold text-slate-800 mt-1 tracking-wider">
                          {{ sheetLi.item?.code || 'INV-01' }} &bull; HAUL5-RJB
                        </span>
                      </div>
                    </div>
                  </div>

                  <!-- 2. Dua Stiker Sedang -->
                  <div class="grid grid-cols-2 gap-3">
                    <div
                      v-for="sIdx in 2"
                      :key="'sheet-med-' + sheetIdx + '-' + sIdx"
                      class="border-2 border-slate-900 rounded-xl p-3 bg-white flex flex-col justify-between shadow-xs"
                    >
                      <div class="flex items-center justify-between pb-1.5 border-b border-slate-300">
                        <span class="font-mono font-black text-xs text-emerald-800">{{ sheetLi.item?.code }}</span>
                        <span class="px-1.5 py-0.5 rounded bg-slate-900 text-white font-bold text-[8px] uppercase">STIKER UNIT</span>
                      </div>
                      <div class="flex items-center space-x-2.5 py-2">
                        <div class="flex flex-col items-center justify-center flex-shrink-0">
                          <img
                            :src="`https://api.qrserver.com/v1/create-qr-code/?size=65x65&data=${encodeURIComponent(getVerifyUrl(selectedLoan?.loan_no))}`"
                            alt="QR"
                            class="w-13 h-13 border border-slate-300 p-0.5 rounded"
                          />
                          <span class="text-[7px] font-bold text-slate-600 mt-0.5">SCAN HP</span>
                        </div>
                        <div class="flex-1 min-w-0 space-y-0.5 text-xs">
                          <div class="font-black text-slate-950 truncate">{{ sheetLi.item?.name }}</div>
                          <div class="text-[9px] text-slate-600 truncate">Peminjam: <strong class="text-slate-900">{{ selectedLoan?.borrower?.name }}</strong></div>
                          <div class="text-[9px] text-slate-500 truncate">Asal: {{ sheetLi.item?.source === 'mitra' ? 'Mitra' : 'Internal' }}</div>
                          <div class="text-[9px] text-rose-700 font-bold">Kembali: {{ formatDate(selectedLoan?.due_at) }}</div>
                        </div>
                      </div>
                      <div class="text-[8px] text-slate-500 font-mono border-t border-slate-100 pt-1 flex justify-between">
                        <span>{{ selectedLoan?.loan_no }}</span>
                        <span>Haul Ke-5</span>
                      </div>
                    </div>
                  </div>

                  <!-- 3. Empat Stiker Kecil -->
                  <div class="grid grid-cols-4 gap-2">
                    <div
                      v-for="kIdx in 4"
                      :key="'sheet-sml-' + sheetIdx + '-' + kIdx"
                      class="border-2 border-slate-900 rounded-lg p-2 bg-white flex flex-col justify-between shadow-xs"
                    >
                      <div class="flex items-center justify-between text-[8px] border-b border-slate-200 pb-1">
                        <span class="font-black text-slate-900">RJB-26</span>
                        <span class="font-mono font-bold text-emerald-800">{{ sheetLi.item?.code }}</span>
                      </div>
                      <div class="flex items-center space-x-1.5 py-1.5">
                        <img
                          :src="`https://api.qrserver.com/v1/create-qr-code/?size=45x45&data=${encodeURIComponent(getVerifyUrl(selectedLoan?.loan_no))}`"
                          alt="QR"
                          class="w-9 h-9 border border-slate-200 p-0.5 rounded flex-shrink-0"
                        />
                        <div class="flex-1 min-w-0">
                          <div class="font-black text-[9px] text-slate-900 leading-tight truncate">{{ sheetLi.item?.name }}</div>
                          <div class="text-[8px] text-slate-500 truncate">{{ selectedLoan?.borrower?.name }}</div>
                        </div>
                      </div>
                      <div class="text-[7px] text-slate-500 font-mono text-right border-t border-slate-100 pt-0.5">
                        {{ selectedLoan?.loan_no }}
                      </div>
                    </div>
                  </div>

                  <!-- Footer -->
                  <div class="pt-3 border-t-2 border-slate-900 flex items-center justify-between text-[10px] text-slate-600">
                    <span class="font-semibold">Properti Resmi Logistik MEDIA ROJABIYYAH &bull; Haul Ke-5 &amp; Rojabiyyah 2026</span>
                    <span class="font-mono">Kertas Stiker Standar F4 &bull; Barang {{ sheetIdx + 1 }} dari {{ expandedLoanItems.length }}</span>
                  </div>
                </div>
              </div>

              <!-- SUB-MODE B & C: GABUNGAN 1 LEMBAR ATAU PILIH SATU BARANG SPESIFIK -->
              <div v-else class="space-y-3.5">
                <!-- Sheet Header -->
                <div class="flex items-center justify-between pb-2 border-b-2 border-slate-900">
                  <div class="flex items-center space-x-2.5">
                    <img
                      :src="settingsStore.settings?.kop_logo_url || settingsStore.settings?.logo_url || '/logo.png'"
                      alt="Logo"
                      class="w-10 h-10 object-contain flex-shrink-0"
                    />
                    <div>
                      <h1 class="text-xs font-black uppercase text-slate-950 tracking-wide">
                        {{ settingsStore.settings?.kop_name || 'DOKUMENTASI ROJABIYYAH 2026' }}
                      </h1>
                      <p class="text-[10px] text-emerald-800 font-bold">
                        {{ settingsStore.settings?.kop_sub_theme || 'Haul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad & Rojabiyyah 2026' }}
                      </p>
                    </div>
                  </div>
                  <div class="text-right">
                    <span class="text-xs font-black uppercase tracking-wider text-slate-950 block">LEMBAR STIKER LABEL F4</span>
                    <span class="text-[10px] font-mono text-slate-600">
                      {{ typeof selectedStickerItem === 'number' ? `Barang: ${expandedLoanItems[selectedStickerItem]?.item?.name}` : `Gabungan (${expandedLoanItems.length} Barang)` }} &bull; Ref: {{ selectedLoan?.loan_no }}
                    </span>
                  </div>
                </div>

                <!-- 1. Stiker Boks / Hardcase (Besar) -->
                <div
                  v-for="li in [typeof selectedStickerItem === 'number' ? expandedLoanItems[selectedStickerItem] : (expandedLoanItems[selectedBoxItemIndex] || expandedLoanItems[0])]"
                  :key="'box-single-' + (li?.id || 0)"
                  class="border-2 border-slate-900 rounded-2xl overflow-hidden bg-white shadow-sm"
                >
                  <!-- Top Header Bar -->
                  <div class="bg-slate-900 text-white px-3.5 py-2 flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                      <img
                        :src="settingsStore.settings?.kop_logo_url || settingsStore.settings?.logo_url || '/logo.png'"
                        alt="Logo"
                        class="w-5 h-5 object-contain bg-white rounded p-0.5"
                      />
                      <span class="font-black text-xs uppercase tracking-wider">MEDIA ROJABIYYAH 2026</span>
                    </div>
                    <div class="flex items-center space-x-2">
                      <!-- Box Item Switcher (Jika mode gabungan dan lebih dari 1 barang) -->
                      <div v-if="selectedStickerItem === 'gabungan' && expandedLoanItems.length > 1" class="no-print flex items-center space-x-1 bg-slate-800 px-2 py-0.5 rounded-lg border border-slate-700">
                        <span class="text-[9px] text-slate-300 font-bold mr-1">Barang Boks:</span>
                        <button
                          v-for="(it, bIdx) in expandedLoanItems"
                          :key="'box-sel-btn-' + bIdx"
                          @click.stop="selectedBoxItemIndex = bIdx"
                          :class="selectedBoxItemIndex === bIdx ? 'bg-emerald-500 text-slate-950 font-black shadow-xs' : 'bg-slate-700 text-slate-300 hover:text-white'"
                          class="px-1.5 py-0.5 rounded text-[8px] uppercase transition font-mono font-bold"
                          :title="it.item?.name"
                        >
                          {{ it.item?.code }}
                        </button>
                      </div>
                      <span class="px-2 py-0.5 rounded bg-emerald-500 text-slate-950 font-black text-[9px] uppercase tracking-wider">
                        STIKER BOKS / HARDCASE
                      </span>
                    </div>
                  </div>

                  <!-- 3-Column Body -->
                  <div class="p-3 grid grid-cols-12 gap-3 items-center">
                    <!-- QR Code (Left) -->
                    <div class="col-span-3 flex flex-col items-center justify-center p-2 border border-slate-300 rounded-xl bg-slate-50 text-center">
                      <img
                        :src="`https://api.qrserver.com/v1/create-qr-code/?size=95x95&data=${encodeURIComponent(getVerifyUrl(selectedLoan?.loan_no))}`"
                        alt="QR"
                        class="w-18 h-18"
                      />
                      <span class="text-[8px] font-black text-slate-700 mt-1 uppercase tracking-tight">SCAN HP UNTUK CEK</span>
                    </div>

                    <!-- Details (Center) -->
                    <div class="col-span-5 space-y-1 text-xs">
                      <div class="text-sm font-black text-slate-950 uppercase leading-snug">
                        {{ li?.item?.name }}
                      </div>
                      <div class="text-[11px] font-mono font-black text-emerald-700">
                        {{ li?.item?.code }}
                      </div>
                      <div class="text-[10px] text-slate-600">
                        Asal: <strong class="text-slate-800">{{ li?.item?.source === 'mitra' ? (li?.item?.partner?.name || 'Pinjaman Mitra Luar') : 'Milik Internal' }}</strong>
                      </div>
                      <div class="text-[10px] text-slate-600">
                        Peminjam: <strong class="text-slate-900 uppercase">{{ selectedLoan?.borrower?.name }}</strong>
                      </div>
                      <div class="text-[10px] text-slate-500 font-mono">
                        No. Pinjam: {{ selectedLoan?.loan_no }}
                      </div>
                      <div class="text-[10px] text-rose-700 font-bold">
                        Tenggat: {{ formatDate(selectedLoan?.due_at) }}
                      </div>
                    </div>

                    <!-- Barcode SVG (Right) -->
                    <div class="col-span-4 flex flex-col items-center justify-center p-2 border-l border-slate-200">
                      <svg class="w-full h-11" viewBox="0 0 160 40" preserveAspectRatio="none">
                        <rect x="0" y="0" width="3" height="40" fill="#000" />
                        <rect x="5" y="0" width="2" height="40" fill="#000" />
                        <rect x="9" y="0" width="4" height="40" fill="#000" />
                        <rect x="15" y="0" width="2" height="40" fill="#000" />
                        <rect x="19" y="0" width="5" height="40" fill="#000" />
                        <rect x="26" y="0" width="2" height="40" fill="#000" />
                        <rect x="30" y="0" width="3" height="40" fill="#000" />
                        <rect x="35" y="0" width="4" height="40" fill="#000" />
                        <rect x="41" y="0" width="2" height="40" fill="#000" />
                        <rect x="45" y="0" width="5" height="40" fill="#000" />
                        <rect x="52" y="0" width="3" height="40" fill="#000" />
                        <rect x="57" y="0" width="2" height="40" fill="#000" />
                        <rect x="61" y="0" width="4" height="40" fill="#000" />
                        <rect x="67" y="0" width="2" height="40" fill="#000" />
                        <rect x="71" y="0" width="5" height="40" fill="#000" />
                        <rect x="78" y="0" width="3" height="40" fill="#000" />
                        <rect x="83" y="0" width="2" height="40" fill="#000" />
                        <rect x="87" y="0" width="4" height="40" fill="#000" />
                        <rect x="93" y="0" width="2" height="40" fill="#000" />
                        <rect x="97" y="0" width="5" height="40" fill="#000" />
                        <rect x="104" y="0" width="3" height="40" fill="#000" />
                        <rect x="109" y="0" width="2" height="40" fill="#000" />
                        <rect x="113" y="0" width="4" height="40" fill="#000" />
                        <rect x="119" y="0" width="2" height="40" fill="#000" />
                        <rect x="123" y="0" width="5" height="40" fill="#000" />
                        <rect x="130" y="0" width="3" height="40" fill="#000" />
                        <rect x="135" y="0" width="2" height="40" fill="#000" />
                        <rect x="139" y="0" width="4" height="40" fill="#000" />
                        <rect x="145" y="0" width="3" height="40" fill="#000" />
                        <rect x="150" y="0" width="2" height="40" fill="#000" />
                        <rect x="154" y="0" width="4" height="40" fill="#000" />
                      </svg>
                      <span class="font-mono text-[9px] font-bold text-slate-800 mt-1 tracking-wider">
                        {{ li?.item?.code || 'INV-01' }} &bull; HAUL5-RJB
                      </span>
                    </div>
                  </div>
                </div>

                <!-- 2. Stiker Sedang (Unit Kamera / Perangkat) - Tampilkan Semua Barang -->
                <div class="grid grid-cols-2 gap-3">
                  <div
                    v-for="(li, idx) in (typeof selectedStickerItem === 'number' ? [expandedLoanItems[selectedStickerItem], expandedLoanItems[selectedStickerItem]] : getDistributedMediumItems(expandedLoanItems))"
                    :key="'med-' + idx"
                    class="border-2 border-slate-900 rounded-xl p-3 bg-white flex flex-col justify-between shadow-xs"
                  >
                    <div class="flex items-center justify-between pb-1.5 border-b border-slate-300">
                      <span class="font-mono font-black text-xs text-emerald-800">{{ li?.item?.code }}</span>
                      <span class="px-1.5 py-0.5 rounded bg-slate-900 text-white font-bold text-[8px] uppercase">STIKER UNIT</span>
                    </div>
                    <div class="flex items-center space-x-2.5 py-2">
                      <div class="flex flex-col items-center justify-center flex-shrink-0">
                        <img
                          :src="`https://api.qrserver.com/v1/create-qr-code/?size=65x65&data=${encodeURIComponent(getVerifyUrl(selectedLoan?.loan_no))}`"
                          alt="QR"
                          class="w-13 h-13 border border-slate-300 p-0.5 rounded"
                        />
                        <span class="text-[7px] font-bold text-slate-600 mt-0.5">SCAN HP</span>
                      </div>
                      <div class="flex-1 min-w-0 space-y-0.5 text-xs">
                        <div class="font-black text-slate-950 truncate">{{ li?.item?.name }}</div>
                        <div class="text-[9px] text-slate-600 truncate">Peminjam: <strong class="text-slate-900">{{ selectedLoan?.borrower?.name }}</strong></div>
                        <div class="text-[9px] text-slate-500 truncate">Asal: {{ li?.item?.source === 'mitra' ? 'Mitra' : 'Internal' }}</div>
                        <div class="text-[9px] text-rose-700 font-bold">Kembali: {{ formatDate(selectedLoan?.due_at) }}</div>
                      </div>
                    </div>
                    <div class="text-[8px] text-slate-500 font-mono border-t border-slate-100 pt-1 flex justify-between">
                      <span>{{ selectedLoan?.loan_no }}</span>
                      <span>Haul Ke-5</span>
                    </div>
                  </div>
                </div>

                <!-- 3. Stiker Kecil (Kabel, Baterai, Aksesoris) - Distribusikan ke Semua Barang -->
                <div class="grid grid-cols-4 gap-2">
                  <div
                    v-for="(li, idx) in (typeof selectedStickerItem === 'number' ? [expandedLoanItems[selectedStickerItem], expandedLoanItems[selectedStickerItem], expandedLoanItems[selectedStickerItem], expandedLoanItems[selectedStickerItem]] : getDistributedMiniItems(expandedLoanItems, 4))"
                    :key="'sml-' + idx"
                    class="border-2 border-slate-900 rounded-lg p-2 bg-white flex flex-col justify-between shadow-xs"
                  >
                    <div class="flex items-center justify-between text-[8px] border-b border-slate-200 pb-1">
                      <span class="font-black text-slate-900">RJB-26</span>
                      <span class="font-mono font-bold text-emerald-800">{{ li?.item?.code }}</span>
                    </div>
                    <div class="flex items-center space-x-1.5 py-1.5">
                      <img
                        :src="`https://api.qrserver.com/v1/create-qr-code/?size=45x45&data=${encodeURIComponent(getVerifyUrl(selectedLoan?.loan_no))}`"
                        alt="QR"
                        class="w-9 h-9 border border-slate-200 p-0.5 rounded flex-shrink-0"
                      />
                      <div class="flex-1 min-w-0">
                        <div class="font-black text-[9px] text-slate-900 leading-tight truncate">{{ li?.item?.name }}</div>
                        <div class="text-[8px] text-slate-500 truncate">{{ selectedLoan?.borrower?.name }}</div>
                      </div>
                    </div>
                    <div class="text-[7px] text-slate-500 font-mono text-right border-t border-slate-100 pt-0.5">
                      {{ selectedLoan?.loan_no }}
                    </div>
                  </div>
                </div>

                <!-- Bottom Sheet Footer -->
                <div class="pt-3 border-t-2 border-slate-900 flex items-center justify-between text-[10px] text-slate-600">
                  <span class="font-semibold">Properti Resmi Logistik MEDIA ROJABIYYAH &bull; Haul Ke-5 &amp; Rojabiyyah 2026</span>
                  <span class="font-mono">Kertas Stiker Standar F4 (Gunting/Kupas sesuai pola kotak)</span>
                </div>
              </div>
            </div>

            <!-- MODE 2: LAYOUT BESAR ONLY (Semua Box Stiker untuk Barang Peminjaman) -->
            <div v-else-if="stickerSize === 'besar'" class="space-y-4">
              <!-- Sheet Header -->
              <div class="flex items-center justify-between pb-2 border-b-2 border-slate-900">
                <div class="flex items-center space-x-2.5">
                  <img :src="settingsStore.settings?.kop_logo_url || settingsStore.settings?.logo_url || '/logo.png'" alt="Logo" class="w-10 h-10 object-contain flex-shrink-0" />
                  <div>
                    <h1 class="text-xs font-black uppercase text-slate-950 tracking-wide">{{ settingsStore.settings?.kop_name || 'DOKUMENTASI ROJABIYYAH 2026' }}</h1>
                    <p class="text-[10px] text-emerald-800 font-bold">{{ settingsStore.settings?.kop_sub_theme || 'Haul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad & Rojabiyyah 2026' }}</p>
                  </div>
                </div>
                <div class="text-right">
                  <span class="text-xs font-black uppercase tracking-wider text-slate-950 block">LEMBAR STIKER BOKS (BESAR)</span>
                  <span class="text-[10px] font-mono text-slate-600">Ref: {{ selectedLoan?.loan_no }}</span>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-3.5">
                <div
                  v-for="(li, idx) in expandedLoanItems"
                  :key="'lg-' + idx"
                  class="border-2 border-slate-900 rounded-2xl overflow-hidden bg-white shadow-sm flex flex-col justify-between"
                >
                  <div class="bg-slate-900 text-white px-3 py-1.5 flex items-center justify-between">
                    <div class="flex items-center space-x-1.5">
                      <img :src="settingsStore.settings?.kop_logo_url || settingsStore.settings?.logo_url || '/logo.png'" alt="Logo" class="w-4 h-4 object-contain bg-white rounded p-0.5" />
                      <span class="font-black text-[11px] uppercase tracking-wider">MEDIA ROJABIYYAH 2026</span>
                    </div>
                    <span class="px-2 py-0.5 rounded bg-emerald-500 text-slate-950 font-black text-[8px] uppercase">BOX / HARDCASE</span>
                  </div>
                  <div class="p-3 grid grid-cols-3 gap-2 items-center">
                    <div class="flex flex-col items-center justify-center p-1.5 border border-slate-300 rounded-xl bg-slate-50">
                      <img :src="`https://api.qrserver.com/v1/create-qr-code/?size=85x85&data=${encodeURIComponent(getVerifyUrl(selectedLoan?.loan_no))}`" alt="QR" class="w-16 h-16" />
                      <span class="text-[7px] font-black text-slate-700 mt-1">SCAN VERIFIKASI</span>
                    </div>
                    <div class="col-span-2 space-y-1 text-xs">
                      <div class="font-mono font-black text-emerald-800 text-xs">{{ li.item?.code }}</div>
                      <div class="text-xs font-black text-slate-950 uppercase leading-tight">{{ li.item?.name }}</div>
                      <div class="text-[10px] text-slate-600">Peminjam: <strong class="text-slate-900">{{ selectedLoan?.borrower?.name }}</strong></div>
                      <div class="text-[9px] text-rose-700 font-bold">Kembali: {{ formatDate(selectedLoan?.due_at) }}</div>
                    </div>
                  </div>
                  <div class="px-3 py-1 bg-slate-50 border-t border-slate-200 text-[8px] font-mono text-slate-500 flex justify-between">
                    <span>{{ selectedLoan?.loan_no }}</span>
                    <span>Wajib Dijaga Amanah</span>
                  </div>
                </div>
              </div>

              <!-- Bottom Sheet Footer -->
              <div class="pt-3 border-t-2 border-slate-900 flex items-center justify-between text-[10px] text-slate-600">
                <span class="font-semibold">Properti Resmi Logistik MEDIA ROJABIYYAH &bull; Haul Ke-5 &amp; Rojabiyyah 2026</span>
                <span class="font-mono">Kertas Stiker Standar F4</span>
              </div>
            </div>

            <!-- MODE 3: LAYOUT SEDANG ONLY (Semua Stiker Unit untuk Semua Barang) -->
            <div v-else-if="stickerSize === 'sedang'" class="space-y-4">
              <!-- Sheet Header -->
              <div class="flex items-center justify-between pb-2 border-b-2 border-slate-900">
                <div class="flex items-center space-x-2.5">
                  <img :src="settingsStore.settings?.kop_logo_url || settingsStore.settings?.logo_url || '/logo.png'" alt="Logo" class="w-10 h-10 object-contain flex-shrink-0" />
                  <div>
                    <h1 class="text-xs font-black uppercase text-slate-950 tracking-wide">{{ settingsStore.settings?.kop_name || 'DOKUMENTASI ROJABIYYAH 2026' }}</h1>
                    <p class="text-[10px] text-emerald-800 font-bold">{{ settingsStore.settings?.kop_sub_theme || 'Haul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad & Rojabiyyah 2026' }}</p>
                  </div>
                </div>
                <div class="text-right">
                  <span class="text-xs font-black uppercase tracking-wider text-slate-950 block">LEMBAR STIKER UNIT (SEDANG)</span>
                  <span class="text-[10px] font-mono text-slate-600">Ref: {{ selectedLoan?.loan_no }}</span>
                </div>
              </div>

              <div class="grid grid-cols-2 gap-2.5">
                <div
                  v-for="(li, idx) in expandedLoanItems"
                  :key="'md-' + idx"
                  class="border-2 border-slate-900 rounded-xl p-2.5 bg-white flex flex-col justify-between shadow-xs"
                >
                  <div class="flex items-center justify-between pb-1 border-b border-slate-300">
                    <span class="font-mono font-black text-xs text-emerald-800">{{ li.item?.code }}</span>
                    <span class="px-1.5 py-0.5 rounded bg-slate-900 text-white font-bold text-[8px]">LABEL UNIT</span>
                  </div>
                  <div class="flex items-center space-x-2 py-1.5">
                    <img :src="`https://api.qrserver.com/v1/create-qr-code/?size=60x60&data=${encodeURIComponent(getVerifyUrl(selectedLoan?.loan_no))}`" alt="QR" class="w-12 h-12 border border-slate-200 p-0.5 rounded" />
                    <div class="flex-1 min-w-0 space-y-0.5 text-xs">
                      <div class="font-black text-slate-950 text-xs truncate">{{ li.item?.name }}</div>
                      <div class="text-[9px] text-slate-600 truncate">Peminjam: {{ selectedLoan?.borrower?.name }}</div>
                      <div class="text-[9px] text-rose-700 font-bold">Kembali: {{ formatDate(selectedLoan?.due_at) }}</div>
                    </div>
                  </div>
                  <div class="text-[8px] text-slate-500 font-mono text-right border-t border-slate-100 pt-0.5">
                    {{ selectedLoan?.loan_no }}
                  </div>
                </div>
              </div>

              <!-- Bottom Sheet Footer -->
              <div class="pt-3 border-t-2 border-slate-900 flex items-center justify-between text-[10px] text-slate-600">
                <span class="font-semibold">Properti Resmi Logistik MEDIA ROJABIYYAH &bull; Haul Ke-5 &amp; Rojabiyyah 2026</span>
                <span class="font-mono">Kertas Stiker Standar F4</span>
              </div>
            </div>

            <!-- MODE 4: LAYOUT KECIL ONLY (16 Mini Stiker Didistribusikan ke Semua Barang) -->
            <div v-else-if="stickerSize === 'kecil'" class="space-y-4">
              <!-- Sheet Header -->
              <div class="flex items-center justify-between pb-2 border-b-2 border-slate-900">
                <div class="flex items-center space-x-2.5">
                  <img :src="settingsStore.settings?.kop_logo_url || settingsStore.settings?.logo_url || '/logo.png'" alt="Logo" class="w-10 h-10 object-contain flex-shrink-0" />
                  <div>
                    <h1 class="text-xs font-black uppercase text-slate-950 tracking-wide">{{ settingsStore.settings?.kop_name || 'DOKUMENTASI ROJABIYYAH 2026' }}</h1>
                    <p class="text-[10px] text-emerald-800 font-bold">{{ settingsStore.settings?.kop_sub_theme || 'Haul ke-5 Al-Maghfurlah K.H. Moch. Djamaluddin Achmad & Rojabiyyah 2026' }}</p>
                  </div>
                </div>
                <div class="text-right">
                  <span class="text-xs font-black uppercase tracking-wider text-slate-950 block">LEMBAR STIKER MINI (KECIL)</span>
                  <span class="text-[10px] font-mono text-slate-600">Ref: {{ selectedLoan?.loan_no }}</span>
                </div>
              </div>

              <div class="grid grid-cols-4 gap-2">
                <div
                  v-for="(li, idx) in getDistributedMiniItems(expandedLoanItems, 16)"
                  :key="'sm-' + idx"
                  class="border-2 border-slate-900 rounded-lg p-1.5 bg-white flex flex-col justify-between shadow-xs"
                >
                  <div class="flex items-center justify-between text-[8px] border-b border-slate-200 pb-0.5">
                    <span class="font-black text-slate-900">RJB-26</span>
                    <span class="font-mono font-bold text-emerald-800">{{ li.item?.code }}</span>
                  </div>
                  <div class="flex items-center space-x-1 py-1">
                    <img :src="`https://api.qrserver.com/v1/create-qr-code/?size=40x40&data=${encodeURIComponent(getVerifyUrl(selectedLoan?.loan_no))}`" alt="QR" class="w-8 h-8 rounded" />
                    <div class="flex-1 min-w-0">
                      <div class="font-black text-[8px] text-slate-900 leading-tight truncate">{{ li.item?.name }}</div>
                      <div class="text-[7px] text-slate-500 truncate">{{ selectedLoan?.borrower?.name }}</div>
                    </div>
                  </div>
                  <div class="text-[7px] text-slate-500 font-mono text-right border-t border-slate-100 pt-0.5">
                    {{ selectedLoan?.loan_no }}
                  </div>
                </div>
              </div>

              <!-- Bottom Sheet Footer -->
              <div class="pt-3 border-t-2 border-slate-900 flex items-center justify-between text-[10px] text-slate-600">
                <span class="font-semibold">Properti Resmi Logistik MEDIA ROJABIYYAH &bull; Haul Ke-5 &amp; Rojabiyyah 2026</span>
                <span class="font-mono">Kertas Stiker Standar F4</span>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>

    <!-- ========================================================================= -->
    <!-- MODAL 6: RINCIAN PENDATAAN BARANG INVENTARIS                              -->
    <!-- ========================================================================= -->
    <div v-if="showItemDetailModal" class="fixed inset-0 z-50 flex items-center justify-center bg-black/80 backdrop-blur-md p-4">
      <div class="relative w-full max-w-md bg-white border border-slate-200 rounded-3xl p-6 shadow-sm text-slate-700">
        <div class="flex items-center justify-between pb-3 border-b border-slate-200 mb-4">
          <h3 class="text-base font-bold text-slate-900">Rincian Pendataan Inventaris</h3>
          <button @click="showItemDetailModal = false" class="text-slate-500 hover:text-white">✕</button>
        </div>

        <div v-if="selectedDetailItem" class="space-y-4 text-xs">
          <div class="w-full h-44 rounded-2xl bg-slate-50 overflow-hidden border border-slate-300 flex items-center justify-center">
            <img v-if="selectedDetailItem.photo_path" :src="selectedDetailItem.photo_path" class="w-full h-full object-cover" />
            <svg v-else class="w-12 h-12 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
          </div>

          <div class="p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2">
            <div class="flex justify-between"><span class="text-slate-500">Nama Barang:</span><span class="font-bold text-white">{{ selectedDetailItem.name }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Kode Alat:</span><span class="font-mono text-cyan-400 font-bold">{{ selectedDetailItem.code }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Kategori:</span><span>{{ selectedDetailItem.category?.name || '-' }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Lokasi:</span><span>{{ selectedDetailItem.location || 'Gudang Utama' }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Total Stok Fisik:</span><span class="font-mono font-bold">{{ selectedDetailItem.qty_total }} Unit</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Stok Siap Pakai:</span><span class="font-mono font-bold text-emerald-400">{{ selectedDetailItem.qty_available }} Unit</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Kondisi Fisik:</span><span class="capitalize">{{ selectedDetailItem.condition }}</span></div>
            <div class="flex justify-between"><span class="text-slate-500">Kepemilikan:</span><span>{{ selectedDetailItem.source === 'mitra' ? 'Pinjaman Mitra' : 'Milik Internal' }}</span></div>
          </div>

          <div class="flex items-center justify-end space-x-2 pt-2">
            <button
              v-if="!isAdmin && selectedDetailItem.qty_available > 0"
              @click="showItemDetailModal = false; openApplyModal(selectedDetailItem)"
              class="px-4 py-2 bg-gradient-to-r from-[#059669] to-[#10b981] hover:from-[#047857] hover:to-[#059669] text-white font-bold rounded-xl text-xs transition"
            >
              Ajukan Pinjam Barang Ini &rarr;
            </button>
            <button
              v-else-if="isAdmin"
              @click="showItemDetailModal = false"
              class="px-4 py-2 bg-slate-50 hover:bg-slate-800 text-slate-700 rounded-xl text-xs font-semibold"
            >
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';
import { useRoute } from 'vue-router';
import DashboardLayout from '../../layouts/DashboardLayout.vue';
import { useAuthStore } from '../../stores/auth';
import { useSettingsStore } from '../../stores/settings';
import api from '../../services/api';

const route = useRoute();
const authStore = useAuthStore();
const settingsStore = useSettingsStore();

// Roles
const isAdmin = computed(() => ['super_admin', 'admin'].includes(authStore.user?.role));

// Main active tab:
// Admin defaults to 'riwayat' (Daftar Pengajuan Peminjaman Anggota)
// Anggota defaults to 'katalog' (Katalog Barang Siap Pinjam)
const activeMainTab = ref(isAdmin.value ? 'riwayat' : 'katalog');

const availableItems = ref([]);
const categories = ref([]);
const loans = ref([]);
const showApplyModal = ref(false);
const showPrintModal = ref(false);
const submitting = ref(false);
const selectedLoan = ref(null);
const printTab = ref('bukti');
const stickerSize = ref('campur');
const selectedStickerItem = ref('gabungan'); // 'gabungan' | 'semua_lembar' | number (index)
const selectedBoxItemIndex = ref(0);
const downloadingPdfId = ref(null);

const expandedLoanItems = computed(() => {
  if (!selectedLoan.value || !selectedLoan.value.loan_items) return [];
  const list = [];
  selectedLoan.value.loan_items.forEach((li) => {
    const qty = li.qty_approved || li.qty_requested || 1;
    for (let i = 0; i < qty; i++) {
      list.push({
        ...li,
        unit_index: i + 1,
        total_unit: qty,
      });
    }
  });
  return list;
});

const getDistributedMiniItems = (items, minSlots = 4) => {
  if (!items || items.length === 0) return [];
  const count = Math.max(items.length, minSlots);
  const result = [];
  for (let i = 0; i < count; i++) {
    result.push({
      ...items[i % items.length],
      _slotIndex: i + 1,
    });
  }
  return result;
};

const getDistributedMediumItems = (items) => {
  if (!items || items.length === 0) return [];
  if (items.length === 1) {
    return [items[0], items[0]];
  }
  return items;
};

const getVerifyUrl = (loanNo) => {
  if (typeof window !== 'undefined' && window.location && window.location.origin) {
    return `${window.location.origin}/verify/${loanNo || 'PJ-001'}`;
  }
  return `https://rojabiyyah.id/verify/${loanNo || 'PJ-001'}`;
};

// Detail item modal
const showItemDetailModal = ref(false);
const selectedDetailItem = ref(null);

const openItemDetail = (item) => {
  selectedDetailItem.value = item;
  showItemDetailModal.value = true;
};

// Catalogs Grouping
const internalItems = computed(() => {
  return availableItems.value.filter((it) => it.source !== 'mitra' && !it.partner_id);
});
const mitraItems = computed(() => {
  return availableItems.value.filter((it) => it.source === 'mitra' || it.partner_id || it.partner);
});
const getItem = (id) => availableItems.value.find((it) => it.id === id);

// Member Return Modal State
const showReturnModal = ref(false);
const returnTargetLoan = ref(null);
const submittingReturn = ref(false);
const cameraInputRef = ref(null);
const galleryInputRef = ref(null);
const returnForm = ref({
  photo: null,
  notes: '',
});

// Admin Reject Modal State
const showRejectModal = ref(false);
const rejectTargetLoan = ref(null);
const rejectReason = ref('');
const submittingReject = ref(false);

// Evidence Modal State (view return photo)
const showEvidenceModal = ref(false);
const evidenceTargetLoan = ref(null);

// Status Filters
const selectedStatusFilter = ref('all');
const statusFilters = [
  { key: 'all', label: 'Semua Status' },
  { key: 'diajukan', label: 'Menunggu Persetujuan' },
  { key: 'disetujui', label: 'Disetujui' },
  { key: 'diserahkan', label: 'Sedang Dipinjam' },
  { key: 'dikembalikan', label: 'Dikembalikan' },
  { key: 'ditolak', label: 'Ditolak' },
];

const filteredLoans = computed(() => {
  if (selectedStatusFilter.value === 'all') return loans.value;
  return loans.value.filter((l) => l.status === selectedStatusFilter.value);
});

const filters = ref({
  search: '',
  category_id: '',
  condition: '',
  source: '',
});

const applyForm = ref({
  borrower_name: authStore.user?.name || '',
  borrower_contact: authStore.user?.phone_wa || authStore.user?.email || '',
  requested_at: new Date().toISOString().split('T')[0],
  due_at: new Date(Date.now() + 86400000 * 2).toISOString().split('T')[0],
  purpose: 'DOKUMENTASI MAULID NABI / LIPUTAN HAUL KE-5',
  notes: '',
  items: [
    { item_id: null, qty: 1 }
  ]
});

const filteredCatalog = computed(() => {
  return availableItems.value.filter((it) => {
    const matchSearch =
      !filters.value.search ||
      it.name.toLowerCase().includes(filters.value.search.toLowerCase()) ||
      it.code.toLowerCase().includes(filters.value.search.toLowerCase());
    const matchCat = !filters.value.category_id || it.category_id == filters.value.category_id;
    const matchCond = !filters.value.condition || it.condition === filters.value.condition;
    const matchSource = !filters.value.source || it.source === filters.value.source;
    return matchSearch && matchCat && matchCond && matchSource;
  });
});

const filterCatalog = () => {
  // reactivity
};

const formatStatusLabel = (st) => {
  switch (st) {
    case 'diajukan': return 'Menunggu Persetujuan';
    case 'disetujui': return 'Disetujui';
    case 'diserahkan': return 'Sedang Dipinjam';
    case 'dikembalikan': return 'Dikembalikan';
    case 'ditolak': return 'Ditolak';
    case 'dibatalkan': return 'Dibatalkan';
    default: return st;
  }
};

const formatDate = (dateStr) => {
  if (!dateStr) return '-';
  const d = new Date(dateStr);
  return d.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
};

const formatDateTime = (dateStr) => {
  if (!dateStr) return '-';
  const d = new Date(dateStr);
  return d.toLocaleString('id-ID', { day: 'numeric', month: 'short', year: 'numeric', hour: '2-digit', minute: '2-digit' });
};

// =========================================================================
// API ACTIONS
// =========================================================================
const fetchInitial = async () => {
  try {
    settingsStore.fetchSettings();
    const [itemsRes, catRes, loansRes] = await Promise.allSettled([
      api.get('/items', { params: { per_page: 100 } }),
      api.get('/inventory-categories'),
      api.get('/loans'),
    ]);

    if (itemsRes.status === 'fulfilled') {
      const data = itemsRes.value.data?.data || itemsRes.value.data || [];
      if (Array.isArray(data) && data.length > 0) {
        availableItems.value = data;
      }
    }
    if (catRes.status === 'fulfilled') {
      categories.value = catRes.value.data || [];
    }
    if (loansRes.status === 'fulfilled') {
      loans.value = loansRes.value.data?.data || loansRes.value.data || [];
    }

    // Safety fallback items if database items are empty
    if (availableItems.value.length === 0) {
      availableItems.value = [
        {
          id: 1,
          code: 'INV-01',
          name: 'sony 6000',
          category: { id: 7, name: 'ALAT KOMUNIKASI & HT' },
          condition: 'baik',
          source: 'internal',
          location: 'Gudang Inventaris Utama',
          qty_total: 4,
          qty_available: 3,
          photo_path: 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=600&q=80',
        },
        {
          id: 4,
          code: 'BRG-004',
          name: 'Proyektor Epson 5000 Lumens (Mitra TI)',
          category: { id: 4, name: 'Proyektor & Layar LED' },
          condition: 'baik',
          source: 'mitra',
          partner: { id: 1, name: 'SMK TI Annuriyyah' },
          location: 'Lab SMK TI Annuriyyah (Dipinjam)',
          qty_total: 3,
          qty_available: 3,
        }
      ];
    }
  } catch (err) {
    console.error('Gagal mengambil data katalog peminjaman:', err);
  }
};

// Member opens apply modal
const openApplyModal = (preselectedItem = null) => {
  applyForm.value.borrower_name = authStore.user?.name || '';
  applyForm.value.borrower_contact = authStore.user?.phone_wa || authStore.user?.email || '';
  applyForm.value.requested_at = new Date().toISOString().split('T')[0];
  applyForm.value.due_at = new Date(Date.now() + 86400000 * 2).toISOString().split('T')[0];
  applyForm.value.purpose = 'DOKUMENTASI MAULID NABI / LIPUTAN HAUL KE-5';
  applyForm.value.notes = '';

  if (preselectedItem) {
    applyForm.value.items = [{ item_id: preselectedItem.id, qty: 1 }];
  } else {
    applyForm.value.items = [{ item_id: availableItems.value[0]?.id || null, qty: 1 }];
  }
  showApplyModal.value = true;
};

const addMoreItemRow = () => {
  applyForm.value.items.push({ item_id: null, qty: 1 });
};

// Member submits loan request
const submitLoanRequest = async () => {
  const validItems = applyForm.value.items.filter((it) => it.item_id && it.qty > 0);
  if (validItems.length === 0) {
    alert('Pilih minimal 1 barang dari katalog!');
    return;
  }

  submitting.value = true;
  try {
    const payload = {
      purpose: applyForm.value.purpose,
      due_at: applyForm.value.due_at + ' 23:59:59',
      items: validItems.map((r) => ({ item_id: r.item_id, qty: r.qty })),
    };

    await api.post('/loans', payload);
    alert('Permohonan peminjaman berhasil diajukan! Menunggu persetujuan Admin Logistik.');
    showApplyModal.value = false;
    activeMainTab.value = 'riwayat';
    await fetchInitial();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal mengirim pengajuan peminjaman.');
  } finally {
    submitting.value = false;
  }
};

// Admin: Approve Loan
const handleApproveLoan = async (loan) => {
  if (!await window.$confirm(`Setujui peminjaman ${loan.loan_no} atas nama ${loan.borrower?.name}? Stok barang akan otomatis dipesan (reserved).`)) return;
  try {
    await api.post(`/loans/${loan.id}/approve`);
    alert(`Peminjaman ${loan.loan_no} berhasil disetujui!`);
    await fetchInitial();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menyetujui peminjaman.');
  }
};

// Admin: Open Reject Modal
const openRejectModal = (loan) => {
  rejectTargetLoan.value = loan;
  rejectReason.value = '';
  showRejectModal.value = true;
};

// Admin: Submit Reject
const submitReject = async () => {
  if (!rejectReason.value.trim()) {
    alert('Alasan penolakan wajib diisi!');
    return;
  }
  submittingReject.value = true;
  try {
    await api.post(`/loans/${rejectTargetLoan.value.id}/reject`, {
      reject_reason: rejectReason.value,
    });
    alert(`Peminjaman ${rejectTargetLoan.value.loan_no} telah ditolak.`);
    showRejectModal.value = false;
    await fetchInitial();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal menolak peminjaman.');
  } finally {
    submittingReject.value = false;
  }
};

// Admin: Handover Loan (serahkan barang fisik)
const handleHandoverLoan = async (loan) => {
  if (!await window.$confirm(`Konfirmasi penyerahan fisik barang untuk ${loan.loan_no} kepada ${loan.borrower?.name}? Status peminjaman akan menjadi "Sedang Dipinjam".`)) return;
  try {
    await api.post(`/loans/${loan.id}/handover`);
    alert(`Barang ${loan.loan_no} telah resmi diserahkan kepada peminjam!`);
    await fetchInitial();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal memperbarui status serah terima.');
  }
};

// Member: Cancel Pending Loan
const handleCancelLoan = async (loan) => {
  if (!await window.$confirm(`Batalkan pengajuan peminjaman ${loan.loan_no}?`)) return;
  try {
    await api.post(`/loans/${loan.id}/cancel`);
    alert(`Pengajuan ${loan.loan_no} telah dibatalkan.`);
    await fetchInitial();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal membatalkan peminjaman.');
  }
};

// Member: Open Return Modal
const openReturnModal = (loan) => {
  returnTargetLoan.value = loan;
  returnForm.value.photo = null;
  returnForm.value.notes = '';
  showReturnModal.value = true;
};

const triggerCameraInput = () => {
  cameraInputRef.value?.click();
};

const triggerGalleryInput = () => {
  galleryInputRef.value?.click();
};

const handleReturnPhoto = (e) => {
  const file = e.target.files[0];
  if (!file) return;
  const reader = new FileReader();
  reader.onload = (event) => {
    const img = new Image();
    img.onload = () => {
      const canvas = document.createElement('canvas');
      const maxDim = 1200;
      let width = img.width;
      let height = img.height;
      if (width > height && width > maxDim) {
        height = Math.round((height * maxDim) / width);
        width = maxDim;
      } else if (height > maxDim) {
        width = Math.round((width * maxDim) / height);
        height = maxDim;
      }
      canvas.width = width;
      canvas.height = height;
      const ctx = canvas.getContext('2d');
      ctx.drawImage(img, 0, 0, width, height);
      returnForm.value.photo = canvas.toDataURL('image/jpeg', 0.8);
    };
    img.src = event.target.result;
  };
  reader.readAsDataURL(file);
};

// Member: Submit Return
const submitReturnForm = async () => {
  if (!returnForm.value.photo) {
    alert('Wajib mengambil atau mengunggah foto fisik barang bukti pengembalian!');
    return;
  }

  submittingReturn.value = true;
  try {
    await api.post(`/loans/${returnTargetLoan.value.id}/return`, {
      photo_proof: returnForm.value.photo,
      return_evidence_photo: returnForm.value.photo,
      notes: returnForm.value.notes || 'Pengembalian barang lengkap dan terverifikasi.',
    });
    alert('Pengembalian barang berhasil dicatat! Terima kasih.');
    showReturnModal.value = false;
    await fetchInitial();
  } catch (err) {
    alert(err.response?.data?.message || 'Gagal mencatat pengembalian.');
  } finally {
    submittingReturn.value = false;
  }
};

// Open Evidence Modal
const openEvidenceModal = (loan) => {
  evidenceTargetLoan.value = loan;
  showEvidenceModal.value = true;
};

// Print Modal
const openPrintModalTab = (loan, tabType = 'bukti') => {
  selectedLoan.value = loan;
  printTab.value = tabType;
  selectedStickerItem.value = 'gabungan';
  selectedBoxItemIndex.value = 0;
  showPrintModal.value = true;
};

const triggerBrowserPrint = () => {
  const printable = document.getElementById('printableDocument');
  if (!printable) {
    window.print();
    return;
  }

  // Remove any leftover container from previous prints
  const oldContainer = document.getElementById('directPrintContainer');
  if (oldContainer) {
    oldContainer.remove();
  }

  // Create clean print container directly attached to document.body
  const printDiv = document.createElement('div');
  printDiv.id = 'directPrintContainer';
  printDiv.innerHTML = printable.innerHTML;
  document.body.appendChild(printDiv);
  document.body.classList.add('is-printing-document');

  const cleanup = () => {
    document.body.classList.remove('is-printing-document');
    const el = document.getElementById('directPrintContainer');
    if (el && el.parentNode) {
      el.parentNode.removeChild(el);
    }
    window.removeEventListener('afterprint', cleanup);
  };

  window.addEventListener('afterprint', cleanup);

  // Trigger print on next tick
  setTimeout(() => {
    window.print();
    // Safety fallback cleanup in case afterprint does not fire
    setTimeout(cleanup, 2500);
  }, 100);
};

const downloadLoanPdf = async (loan) => {
  downloadingPdfId.value = loan.id;
  try {
    const res = await api.get(`/loans/${loan.id}/pdf`, { responseType: 'blob' });
    const blob = new Blob([res.data], { type: 'application/pdf' });
    const fileUrl = window.URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = fileUrl;
    link.download = `Surat_Peminjaman_${loan.loan_no}.pdf`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    setTimeout(() => window.URL.revokeObjectURL(fileUrl), 15000);
  } catch (err) {
    console.warn('Backend DomPdf direct stream issue, opening printable document view:', err);
    openPrintModalTab(loan, 'surat_jalan');
  } finally {
    downloadingPdfId.value = null;
  }
};

onMounted(() => {
  // If query specifies apply item
  if (route.query.apply_item_id && !isAdmin.value) {
    const preId = Number(route.query.apply_item_id);
    fetchInitial().then(() => {
      const it = availableItems.value.find((i) => i.id === preId);
      if (it) openApplyModal(it);
    });
    return;
  }

  // If query tab specifies 'mitra'
  if (route.query.tab === 'mitra') {
    filters.value.source = 'mitra';
    activeMainTab.value = 'katalog';
  }

  fetchInitial();
});
</script>

<style>
@page {
  size: 215mm 330mm portrait;
  margin: 6mm 8mm;
}

@media print {
  html, body {
    margin: 0 !important;
    padding: 0 !important;
    background: #ffffff !important;
    color: #000000 !important;
    width: 100% !important;
    height: auto !important;
  }

  /* When printing via triggerBrowserPrint: HIDE the whole Vue #app */
  body.is-printing-document > #app {
    display: none !important;
  }

  /* Only directPrintContainer is visible at the very top of the page */
  body.is-printing-document > #directPrintContainer {
    display: block !important;
    position: static !important;
    margin: 0 auto !important;
    padding: 0 !important;
    width: 100% !important;
    max-width: 199mm !important;
    background: #ffffff !important;
    color: #000000 !important;
    border: none !important;
    box-shadow: none !important;
    -webkit-print-color-adjust: exact !important;
    print-color-adjust: exact !important;
  }

  body.is-printing-document > #directPrintContainer * {
    visibility: visible !important;
    box-sizing: border-box !important;
  }

  .page-break {
    page-break-after: always !important;
    break-after: page !important;
  }

  /* Fallback if user presses Ctrl+P while modal is open */
  body:not(.is-printing-document) .no-print {
    display: none !important;
  }
}
</style>
