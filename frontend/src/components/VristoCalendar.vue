<template>
  <div class="panel border border-slate-200 bg-white rounded-2xl shadow-md overflow-hidden text-slate-800">
    <!-- Top Action Toolbar (Clean Light Mode) -->
    <div class="p-3 sm:p-6 border-b border-slate-200 flex flex-col lg:flex-row lg:items-center justify-between gap-3 sm:gap-4 bg-slate-50/80">
      <!-- Top / Left: Navigation (Prev, Next, Today) & Month Title -->
      <div class="flex items-center justify-between sm:justify-start space-x-2 sm:space-x-3 w-full lg:w-auto">
        <h3 class="text-base sm:text-xl font-black text-slate-900 capitalize tracking-tight sm:tracking-wide">
          {{ monthNames[currentMonth] }} {{ currentYear }}
        </h3>

        <div class="flex items-center space-x-1.5 sm:space-x-2">
          <div class="flex items-center space-x-0.5 bg-white p-0.5 sm:p-1 rounded-xl border border-slate-200 shadow-xs">
            <button
              type="button"
              @click="prevMonth"
              class="p-1.5 sm:p-2 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition"
              title="Bulan Sebelumnya"
            >
              <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button
              type="button"
              @click="nextMonth"
              class="p-1.5 sm:p-2 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition"
              title="Bulan Berikutnya"
            >
              <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/></svg>
            </button>
          </div>

          <button
            type="button"
            @click="goToToday"
            class="px-2.5 sm:px-3.5 py-1 sm:py-1.5 rounded-xl text-[11px] sm:text-xs font-bold bg-white hover:bg-emerald-50 text-slate-700 hover:text-emerald-700 border border-slate-200 transition shadow-xs whitespace-nowrap"
          >
            Hari Ini
          </button>
        </div>
      </div>

      <!-- Bottom / Right: Category Filter Chips & View Mode Switcher -->
      <div class="flex flex-col sm:flex-row items-stretch sm:items-center justify-between lg:justify-end gap-2 w-full lg:w-auto">
        <!-- View Toggle (Grid vs List) - Full width on mobile -->
        <div class="grid grid-cols-2 sm:flex items-center bg-white p-1 rounded-xl border border-slate-200 text-xs font-bold shadow-xs">
          <button
            type="button"
            @click="viewMode = 'grid'"
            :class="[
              'px-3 py-1.5 rounded-lg transition flex items-center justify-center space-x-1.5',
              viewMode === 'grid' ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'
            ]"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="3" width="7" height="7" stroke-width="2" rx="1"/><rect x="14" y="3" width="7" height="7" stroke-width="2" rx="1"/><rect x="14" y="14" width="7" height="7" stroke-width="2" rx="1"/><rect x="3" y="14" width="7" height="7" stroke-width="2" rx="1"/></svg>
            <span>Kalender</span>
          </button>
          <button
            type="button"
            @click="viewMode = 'list'"
            :class="[
              'px-3 py-1.5 rounded-lg transition flex items-center justify-center space-x-1.5',
              viewMode === 'list' ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-xs' : 'text-slate-600 hover:text-slate-900'
            ]"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            <span>Daftar Agenda ({{ filteredEvents.length }})</span>
          </button>
        </div>

        <!-- Admin Add Button -->
        <button
          v-if="editable"
          type="button"
          @click="openAddModal"
          class="w-full sm:w-auto justify-center px-4 py-2 sm:py-1.5 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:brightness-105 text-white text-xs font-bold shadow-md shadow-emerald-600/20 flex items-center space-x-1.5 transition active:scale-95"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
          <span>+ Tambah Agenda</span>
        </button>
      </div>
    </div>

    <!-- Category Filter Tags Bar -->
    <div class="px-3 sm:px-6 py-2 sm:py-2.5 bg-white border-b border-slate-200 flex items-center space-x-1.5 sm:space-x-2 overflow-x-auto no-scrollbar">
      <span class="text-[10px] sm:text-[11px] font-bold text-slate-500 uppercase tracking-wider flex-shrink-0 mr-1">
        Kategori:
      </span>
      <button
        type="button"
        @click="selectedCategory = 'all'"
        :class="[
          'px-2.5 sm:px-3 py-1 rounded-lg text-[10px] sm:text-[11px] font-bold transition flex items-center space-x-1 whitespace-nowrap',
          selectedCategory === 'all'
            ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white shadow-xs'
            : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'
        ]"
      >
        <span>Semua ({{ events.length }})</span>
      </button>

      <button
        v-for="(cat, key) in categories"
        :key="key"
        type="button"
        @click="selectedCategory = key"
        :class="[
          'px-2 sm:px-2.5 py-1 rounded-lg text-[10px] sm:text-[11px] font-bold transition flex items-center space-x-1.5 whitespace-nowrap',
          selectedCategory === key
            ? 'ring-2 ring-emerald-500 ' + cat.badge
            : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100'
        ]"
      >
        <span class="w-2 h-2 rounded-full" :class="cat.dot"></span>
        <span>{{ cat.label }} ({{ countCategory(key) }})</span>
      </button>
    </div>

    <!-- =================================================================== -->
    <!-- VIEW 1: MONTH CALENDAR GRID                                         -->
    <!-- =================================================================== -->
    <div v-if="viewMode === 'grid'" class="p-2 sm:p-5 bg-slate-50/50">
      <!-- Weekday Headers -->
      <div class="grid grid-cols-7 gap-1 sm:gap-2 mb-1 sm:mb-2 text-center">
        <div
          v-for="(dayName, dIdx) in dayNames"
          :key="dIdx"
          class="py-1 sm:py-2 text-[10px] sm:text-xs font-black uppercase tracking-wider text-slate-600"
          :class="dIdx === 0 || dIdx === 5 ? 'text-[#f97316]' : ''"
        >
          {{ dayName }}
        </div>
      </div>

      <!-- Calendar Days Grid (6 Weeks x 7 Days) -->
      <div class="grid grid-cols-7 gap-1 sm:gap-2">
        <div
          v-for="(cell, cIdx) in calendarGrid"
          :key="cIdx"
          @click="handleDayCellClick(cell)"
          :class="[
            'min-h-[48px] sm:min-h-[115px] p-1 sm:p-2 rounded-xl border flex flex-col justify-between sm:justify-start transition-all duration-150',
            cell.isCurrentMonth
              ? 'bg-white border-slate-200 hover:border-emerald-400 shadow-xs'
              : 'bg-slate-100/50 border-slate-100 opacity-40',
            cell.isToday ? 'ring-2 ring-[#059669] shadow-sm bg-emerald-50/40' : '',
            'cursor-pointer hover:bg-slate-50'
          ]"
        >
          <!-- Cell Top: Day Number & Today Pill -->
          <div class="flex items-center justify-center sm:justify-between mb-0.5 sm:mb-1">
            <span
              :class="[
                'text-[11px] sm:text-xs font-bold w-5 h-5 sm:w-6 sm:h-6 rounded-full flex items-center justify-center',
                cell.isToday
                  ? 'bg-gradient-to-r from-[#059669] to-[#10b981] text-white font-black shadow-xs'
                  : cell.isCurrentMonth ? 'text-slate-800' : 'text-slate-400'
              ]"
            >
              {{ cell.dayNumber }}
            </span>
            <span
              v-if="cell.isToday"
              class="hidden sm:inline-block text-[9px] font-extrabold uppercase px-1.5 py-0.2 rounded bg-emerald-100 text-emerald-800 border border-emerald-200"
            >
              Hari Ini
            </span>
          </div>

          <!-- Desktop View: Full Event Pills (hidden on mobile) -->
          <div class="hidden sm:block flex-1 space-y-1 overflow-hidden">
            <div
              v-for="evt in getEventsForCell(cell.dateStr).slice(0, 3)"
              :key="evt.id"
              @click.stop="openDetailModal(evt)"
              class="px-1.5 py-0.5 rounded text-[10px] sm:text-[11px] font-bold text-white truncate shadow-xs transition hover:scale-[1.02] cursor-pointer flex items-center space-x-1"
              :class="getCategoryStyle(evt.type).bg"
              :title="`${evt.time_start || ''} ${evt.title}`"
            >
              <span class="w-1.5 h-1.5 rounded-full bg-white flex-shrink-0"></span>
              <span class="truncate">{{ evt.title }}</span>
            </div>

            <!-- More items badge if > 3 -->
            <div
              v-if="getEventsForCell(cell.dateStr).length > 3"
              @click.stop="viewMode = 'list'"
              class="text-[9px] sm:text-[10px] font-bold text-emerald-700 hover:underline text-center cursor-pointer pt-0.5"
            >
              +{{ getEventsForCell(cell.dateStr).length - 3 }} lainnya
            </div>
          </div>

          <!-- Mobile View: Clean Event Indicator Dots (visible on mobile only) -->
          <div v-if="getEventsForCell(cell.dateStr).length > 0" class="sm:hidden flex items-center justify-center space-x-1 mt-0.5 pb-0.5">
            <span
              v-for="(evt, eIdx) in getEventsForCell(cell.dateStr).slice(0, 3)"
              :key="eIdx"
              class="w-1.5 h-1.5 rounded-full shadow-xs"
              :class="getCategoryStyle(evt.type).dot"
            ></span>
            <span v-if="getEventsForCell(cell.dateStr).length > 3" class="text-[8px] font-black text-emerald-700 leading-none">
              +
            </span>
          </div>
        </div>
      </div>
    </div>

    <!-- =================================================================== -->
    <!-- VIEW 2: AGENDA LIST VIEW (RESPONSIVE & DETAILED)                   -->
    <!-- =================================================================== -->
    <div v-else class="p-4 sm:p-6 divide-y divide-slate-100 bg-white">
      <div v-if="filteredEvents.length === 0" class="py-12 text-center text-slate-400">
        <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center mx-auto mb-3 text-emerald-600">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h18"/></svg>
        </div>
        <p class="text-sm font-bold text-slate-800">Tidak Ada Agenda pada Bulan / Kategori Ini</p>
        <p class="text-xs text-slate-500 mt-1">Gunakan tombol panah bulan atau ubah filter kategori di atas.</p>
      </div>

      <div
        v-for="evt in sortedFilteredEvents"
        :key="evt.id"
        class="py-4 first:pt-0 last:pb-0 flex flex-col md:flex-row md:items-center justify-between gap-4 group"
      >
        <div class="flex items-start space-x-3.5 min-w-0">
          <!-- Date Badge Box -->
          <div class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-200 flex flex-col items-center justify-center flex-shrink-0 text-center shadow-xs group-hover:border-emerald-500 transition">
            <span class="text-[10px] font-extrabold uppercase text-emerald-700 leading-none">
              {{ formatEventMonth(evt.date) }}
            </span>
            <span class="text-lg font-black text-slate-900 leading-none mt-1">
              {{ formatEventDay(evt.date) }}
            </span>
          </div>

          <!-- Event Details -->
          <div class="min-w-0">
            <div class="flex flex-wrap items-center gap-2">
              <span
                class="px-2.5 py-0.5 rounded-full text-[10px] font-black uppercase tracking-wider"
                :class="getCategoryStyle(evt.type).badge"
              >
                {{ getCategoryStyle(evt.type).label }}
              </span>
              <span class="text-xs font-semibold text-slate-500 flex items-center space-x-1">
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="12 6 12 12 16 14"/></svg>
                <span>{{ evt.time_start || '00:00' }} - {{ evt.time_end || 'Selesai' }} WIB</span>
              </span>
            </div>

            <h4
              @click="openDetailModal(evt)"
              class="text-sm sm:text-base font-bold text-slate-900 mt-1 hover:text-emerald-700 cursor-pointer transition truncate"
            >
              {{ evt.title }}
            </h4>

            <div class="flex flex-wrap items-center gap-3 text-xs text-slate-600 mt-1">
              <span v-if="evt.location" class="flex items-center space-x-1 text-slate-600">
                <svg class="w-3.5 h-3.5 text-[#f97316]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3" stroke-width="2"/></svg>
                <span>{{ evt.location }}</span>
              </span>
              <span v-if="evt.pic" class="flex items-center space-x-1 text-emerald-700">
                <svg class="w-3.5 h-3.5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 21v-2a4 4 0 014-4h8a4 4 0 014 4v2"/></svg>
                <span>PIC: {{ evt.pic }}</span>
              </span>
            </div>

            <p v-if="evt.description" class="text-xs text-slate-600 mt-1.5 line-clamp-2">
              {{ evt.description }}
            </p>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center space-x-2 self-end md:self-center flex-shrink-0">
          <button
            type="button"
            @click="openDetailModal(evt)"
            class="px-3.5 py-1.5 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-700 hover:text-slate-900 border border-slate-200 text-xs font-semibold transition"
          >
            Rincian
          </button>
          <template v-if="editable">
            <button
              type="button"
              @click="openEditModal(evt)"
              class="p-1.5 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white transition"
              title="Edit Agenda"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            </button>
            <button
              type="button"
              @click="deleteEvent(evt.id)"
              class="p-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white transition"
              title="Hapus Agenda"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </button>
          </template>
        </div>
      </div>
    </div>

    <!-- =================================================================== -->
    <!-- MODAL 1: EVENT DETAIL MODAL (CLEAN LIGHT THEME)                     -->
    <!-- =================================================================== -->
    <div
      v-if="showDetailModal && selectedEvent"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm animate-in fade-in"
      @click.self="showDetailModal = false"
    >
      <div class="w-full max-w-lg bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden p-6 text-left relative">
        <button
          type="button"
          @click="showDetailModal = false"
          class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 p-1 rounded-lg hover:bg-slate-100 transition"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div class="flex items-center space-x-2 mb-3">
          <span
            class="px-2.5 py-1 rounded-full text-[11px] font-black uppercase tracking-wider"
            :class="getCategoryStyle(selectedEvent.type).badge"
          >
            {{ getCategoryStyle(selectedEvent.type).label }}
          </span>
        </div>

        <h3 class="text-lg font-black text-slate-900 leading-snug">
          {{ selectedEvent.title }}
        </h3>

        <div class="mt-4 p-3.5 rounded-xl bg-slate-50 border border-slate-200 space-y-2.5 text-xs text-slate-800">
          <div class="flex items-center space-x-2">
            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 2v4M8 2v4M3 10h18"/></svg>
            <span class="font-bold text-emerald-700">Tanggal:</span>
            <span>{{ formatEventFullDate(selectedEvent.date) }}</span>
          </div>

          <div class="flex items-center space-x-2">
            <svg class="w-4 h-4 text-emerald-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" stroke-width="2"/><polyline stroke-linecap="round" stroke-linejoin="round" stroke-width="2" points="12 6 12 12 16 14"/></svg>
            <span class="font-bold text-emerald-700">Waktu:</span>
            <span>{{ selectedEvent.time_start || '00:00' }} - {{ selectedEvent.time_end || 'Selesai' }} WIB</span>
          </div>

          <div v-if="selectedEvent.location" class="flex items-center space-x-2">
            <svg class="w-4 h-4 text-[#f97316] flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3" stroke-width="2"/></svg>
            <span class="font-bold text-[#f97316]">Lokasi:</span>
            <span>{{ selectedEvent.location }}</span>
          </div>

          <div v-if="selectedEvent.pic" class="flex items-center space-x-2">
            <svg class="w-4 h-4 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="7" r="4" stroke-width="2"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 21v-2a4 4 0 014-4h8a4 4 0 014 4v2"/></svg>
            <span class="font-bold text-amber-700">Penanggung Jawab (PIC):</span>
            <span>{{ selectedEvent.pic }}</span>
          </div>
        </div>

        <div v-if="selectedEvent.description" class="mt-4">
          <p class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Keterangan / Rincian Acara:</p>
          <p class="text-xs text-slate-600 leading-relaxed bg-slate-50 p-3 rounded-xl border border-slate-200">
            {{ selectedEvent.description }}
          </p>
        </div>

        <div class="mt-6 flex items-center justify-end space-x-2">
          <button
            type="button"
            @click="showDetailModal = false"
            class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition"
          >
            Tutup
          </button>
        </div>
      </div>
    </div>

    <!-- =================================================================== -->
    <!-- MODAL 2: ADD / EDIT EVENT FORM (ADMIN ONLY)                        -->
    <!-- =================================================================== -->
    <div
      v-if="editable && showEditModal"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm animate-in fade-in"
      @click.self="showEditModal = false"
    >
      <div class="w-full max-w-lg bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden p-6 text-left relative">
        <button
          type="button"
          @click="showEditModal = false"
          class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 p-1 rounded-lg hover:bg-slate-100 transition"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <h3 class="text-base font-black text-slate-900 mb-4">
          {{ isEditMode ? 'Edit Agenda Acara' : 'Tambah Agenda Acara Baru' }}
        </h3>

        <form @submit.prevent="submitEventForm" class="space-y-4 text-xs">
          <div>
            <label class="block text-slate-700 font-bold mb-1">Judul Agenda / Momen Kegiatan *</label>
            <input
              v-model="eventForm.title"
              type="text"
              required
              placeholder="misal: Puncak Peringatan Haul Akbar & Pengajian"
              class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-500"
            />
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-slate-700 font-bold mb-1">Tanggal Kegiatan *</label>
              <input
                v-model="eventForm.date"
                type="date"
                required
                class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-500"
              />
            </div>
            <div>
              <label class="block text-slate-700 font-bold mb-1">Kategori / Badge Warna</label>
              <select
                v-model="eventForm.type"
                class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-500"
              >
                <option value="primary">Acara Utama (Hijau Zamrud)</option>
                <option value="success">Rapat &amp; Briefing (Hijau Muda)</option>
                <option value="warning">Instalasi &amp; Logistik (Oranye Flyer)</option>
                <option value="info">Dokumentasi &amp; Multicam (Cyan)</option>
                <option value="danger">Gladi Resik &amp; Haul (Merah)</option>
              </select>
            </div>
          </div>

          <div class="grid grid-cols-2 gap-3">
            <div>
              <label class="block text-slate-700 font-bold mb-1">Waktu Mulai</label>
              <input
                v-model="eventForm.time_start"
                type="text"
                placeholder="19:30"
                class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-500"
              />
            </div>
            <div>
              <label class="block text-slate-700 font-bold mb-1">Waktu Selesai</label>
              <input
                v-model="eventForm.time_end"
                type="text"
                placeholder="23:00"
                class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-500"
              />
            </div>
          </div>

          <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div>
              <label class="block text-slate-700 font-bold mb-1">Lokasi / Wilayah</label>
              <input
                v-model="eventForm.location"
                type="text"
                placeholder="misal: Panggung Utama / Ribath Al-Fattah"
                class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-500"
              />
            </div>
            <div>
              <label class="block text-slate-700 font-bold mb-1">Penanggung Jawab (PIC)</label>
              <input
                v-model="eventForm.pic"
                type="text"
                placeholder="misal: Koordinator Acara"
                class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-500"
              />
            </div>
          </div>

          <div>
            <label class="block text-slate-700 font-bold mb-1">Keterangan / Rincian Acara</label>
            <textarea
              v-model="eventForm.description"
              rows="3"
              placeholder="Rincian agenda atau instruksi untuk petugas panitia..."
              class="w-full px-3 py-2 rounded-xl bg-white border border-slate-300 text-slate-900 focus:outline-none focus:border-emerald-500"
            ></textarea>
          </div>

          <div class="pt-2 flex items-center justify-end space-x-2">
            <button
              type="button"
              @click="showEditModal = false"
              class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition"
            >
              Batal
            </button>
            <button
              type="submit"
              class="px-5 py-2 rounded-xl bg-gradient-to-r from-[#059669] to-[#10b981] hover:brightness-105 text-white font-bold shadow-md shadow-emerald-600/20 transition"
            >
              {{ isEditMode ? 'Simpan Perubahan' : 'Tambahkan Agenda' }}
            </button>
          </div>
        </form>
      </div>
    </div>

    <!-- =================================================================== -->
    <!-- MODAL 3: DAY EVENTS SUMMARY MODAL (FOR MOBILE MULTI-EVENTS)         -->
    <!-- =================================================================== -->
    <div
      v-if="showDayModal && activeDayCell"
      class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/50 backdrop-blur-sm animate-in fade-in"
      @click.self="showDayModal = false"
    >
      <div class="w-full max-w-md bg-white border border-slate-200 rounded-2xl shadow-2xl overflow-hidden p-5 text-left relative max-h-[85vh] flex flex-col">
        <button
          type="button"
          @click="showDayModal = false"
          class="absolute top-4 right-4 text-slate-400 hover:text-slate-700 p-1 rounded-lg hover:bg-slate-100 transition"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div class="flex items-center space-x-2 mb-1.5">
          <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
          <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Agenda Kegiatan</span>
        </div>

        <h3 class="text-base font-black text-slate-900 mb-3">
          {{ formatEventFullDate(activeDayCell.dateStr) }}
        </h3>

        <div class="overflow-y-auto space-y-2.5 flex-1 pr-1">
          <div
            v-for="evt in getEventsForCell(activeDayCell.dateStr)"
            :key="evt.id"
            @click="openDetailModalFromDay(evt)"
            class="p-3 rounded-xl bg-slate-50 hover:bg-emerald-50/50 border border-slate-200 hover:border-emerald-300 transition cursor-pointer flex items-start justify-between gap-2 group"
          >
            <div class="min-w-0">
              <div class="flex items-center space-x-1.5 mb-1">
                <span class="px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider" :class="getCategoryStyle(evt.type).badge">
                  {{ getCategoryStyle(evt.type).label }}
                </span>
                <span class="text-[11px] font-semibold text-slate-500 font-mono">
                  {{ evt.time_start || '00:00' }} - {{ evt.time_end || 'Selesai' }}
                </span>
              </div>
              <h4 class="text-xs font-bold text-slate-900 group-hover:text-emerald-700 transition">
                {{ evt.title }}
              </h4>
              <p v-if="evt.location" class="text-[11px] text-slate-500 mt-0.5 flex items-center space-x-1">
                <span>📍 {{ evt.location }}</span>
              </p>
            </div>
            <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-600 transition flex-shrink-0 self-center" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
          </div>
        </div>

        <div class="mt-4 pt-3 border-t border-slate-200 flex items-center justify-end">
          <button
            type="button"
            @click="showDayModal = false"
            class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition"
          >
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';

const props = defineProps({
  events: {
    type: Array,
    default: () => []
  },
  editable: {
    type: Boolean,
    default: false
  },
  initialMonth: {
    type: [Date, String],
    default: null
  }
});

const emit = defineEmits(['save', 'delete']);

// Month and Day Names in Indonesian
const monthNames = [
  'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
];

const dayNames = ['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'];

// Current View Date
const today = new Date();
const currentYear = ref(today.getFullYear());
const currentMonth = ref(today.getMonth());

// Set initial month if provided
if (props.initialMonth) {
  const d = new Date(props.initialMonth);
  if (!isNaN(d.getTime())) {
    currentYear.value = d.getFullYear();
    currentMonth.value = d.getMonth();
  }
}

// Categories Configuration matching Fresh Green & Orange Palette
const categories = {
  primary: {
    label: 'Acara Utama',
    bg: 'bg-emerald-600',
    badge: 'bg-emerald-50 text-emerald-800 border border-emerald-200',
    dot: 'bg-emerald-600'
  },
  success: {
    label: 'Rapat & Briefing',
    bg: 'bg-teal-600',
    badge: 'bg-teal-50 text-teal-800 border border-teal-200',
    dot: 'bg-teal-600'
  },
  warning: {
    label: 'Instalasi & Logistik',
    bg: 'bg-[#f97316]',
    badge: 'bg-orange-50 text-orange-800 border border-orange-200',
    dot: 'bg-[#f97316]'
  },
  info: {
    label: 'Dokumentasi & Multicam',
    bg: 'bg-sky-600',
    badge: 'bg-sky-50 text-sky-800 border border-sky-200',
    dot: 'bg-sky-600'
  },
  danger: {
    label: 'Gladi Resik & Haul',
    bg: 'bg-rose-600',
    badge: 'bg-rose-50 text-rose-800 border border-rose-200',
    dot: 'bg-rose-600'
  }
};

const getCategoryStyle = (type) => {
  return categories[type] || categories.primary;
};

// UI State
const viewMode = ref('grid');
const selectedCategory = ref('all');
const showDetailModal = ref(false);
const selectedEvent = ref(null);
const showEditModal = ref(false);
const isEditMode = ref(false);
const showDayModal = ref(false);
const activeDayCell = ref(null);

const eventForm = ref({
  id: '',
  title: '',
  date: '',
  time_start: '',
  time_end: '',
  type: 'primary',
  location: '',
  pic: '',
  description: ''
});

// Navigation handlers
const prevMonth = () => {
  if (currentMonth.value === 0) {
    currentMonth.value = 11;
    currentYear.value -= 1;
  } else {
    currentMonth.value -= 1;
  }
};

const nextMonth = () => {
  if (currentMonth.value === 11) {
    currentMonth.value = 0;
    currentYear.value += 1;
  } else {
    currentMonth.value += 1;
  }
};

const goToToday = () => {
  currentMonth.value = today.getMonth();
  currentYear.value = today.getFullYear();
};

// Calendar Grid Builder (6 weeks x 7 days)
const calendarGrid = computed(() => {
  const year = currentYear.value;
  const month = currentMonth.value;

  const firstDayOfMonth = new Date(year, month, 1);
  const startDayOfWeek = firstDayOfMonth.getDay(); // 0 = Sunday

  const daysInMonth = new Date(year, month + 1, 0).getDate();
  const daysInPrevMonth = new Date(year, month, 0).getDate();

  const grid = [];

  // Previous month trailing days
  for (let i = startDayOfWeek - 1; i >= 0; i--) {
    const day = daysInPrevMonth - i;
    const d = new Date(year, month - 1, day);
    const dateStr = formatDateString(d);
    grid.push({
      dateStr,
      dayNumber: day,
      isCurrentMonth: false,
      isToday: isSameDay(d, today)
    });
  }

  // Current month days
  for (let day = 1; day <= daysInMonth; day++) {
    const d = new Date(year, month, day);
    const dateStr = formatDateString(d);
    grid.push({
      dateStr,
      dayNumber: day,
      isCurrentMonth: true,
      isToday: isSameDay(d, today)
    });
  }

  // Next month leading days to complete 42 cells (6 rows)
  const remaining = 42 - grid.length;
  for (let day = 1; day <= remaining; day++) {
    const d = new Date(year, month + 1, day);
    const dateStr = formatDateString(d);
    grid.push({
      dateStr,
      dayNumber: day,
      isCurrentMonth: false,
      isToday: isSameDay(d, today)
    });
  }

  return grid;
});

// Helper: Format Date YYYY-MM-DD (local time)
const formatDateString = (d) => {
  const y = d.getFullYear();
  const m = String(d.getMonth() + 1).padStart(2, '0');
  const day = String(d.getDate()).padStart(2, '0');
  return `${y}-${m}-${day}`;
};

const isSameDay = (d1, d2) => {
  return (
    d1.getFullYear() === d2.getFullYear() &&
    d1.getMonth() === d2.getMonth() &&
    d1.getDate() === d2.getDate()
  );
};

// Events Filtering
const filteredEvents = computed(() => {
  let list = props.events || [];
  if (selectedCategory.value !== 'all') {
    list = list.filter((e) => (e.type || 'primary') === selectedCategory.value);
  }
  return list;
});

// Filtered and Sorted for List View
const sortedFilteredEvents = computed(() => {
  const list = [...filteredEvents.value];
  return list.sort((a, b) => {
    const dateComp = (a.date || '').localeCompare(b.date || '');
    if (dateComp !== 0) return dateComp;
    return (a.time_start || '').localeCompare(b.time_start || '');
  });
});

const getEventsForCell = (dateStr) => {
  return filteredEvents.value.filter((e) => e.date === dateStr);
};

const countCategory = (key) => {
  return (props.events || []).filter((e) => (e.type || 'primary') === key).length;
};

// Formatting helpers
const formatEventDay = (dateStr) => {
  if (!dateStr) return '00';
  const parts = dateStr.split('-');
  return parts[2] || '00';
};

const formatEventMonth = (dateStr) => {
  if (!dateStr) return '';
  const parts = dateStr.split('-');
  const mIdx = parseInt(parts[1], 10) - 1;
  return (monthNames[mIdx] || '').substring(0, 3);
};

const formatEventFullDate = (dateStr) => {
  if (!dateStr) return '-';
  const parts = dateStr.split('-');
  if (parts.length < 3) return dateStr;
  const d = parseInt(parts[2], 10);
  const mIdx = parseInt(parts[1], 10) - 1;
  const y = parts[0];
  return `${d} ${monthNames[mIdx] || ''} ${y}`;
};

// Modal Actions
const openDetailModal = (evt) => {
  selectedEvent.value = evt;
  showDetailModal.value = true;
};

const handleDayCellClick = (cell) => {
  if (props.editable) {
    openAddModalForDate(cell.dateStr);
    return;
  }
  const events = getEventsForCell(cell.dateStr);
  if (events.length === 1) {
    openDetailModal(events[0]);
  } else if (events.length > 1) {
    activeDayCell.value = cell;
    showDayModal.value = true;
  }
};

const openDetailModalFromDay = (evt) => {
  showDayModal.value = false;
  openDetailModal(evt);
};

const openAddModal = () => {
  isEditMode.value = false;
  eventForm.value = {
    id: 'evt_' + Date.now(),
    title: '',
    date: formatDateString(new Date(currentYear.value, currentMonth.value, today.getDate() || 1)),
    time_start: '19:30',
    time_end: '23:00',
    type: 'primary',
    location: '',
    pic: '',
    description: ''
  };
  showEditModal.value = true;
};

const openAddModalForDate = (dateStr) => {
  isEditMode.value = false;
  eventForm.value = {
    id: 'evt_' + Date.now(),
    title: '',
    date: dateStr,
    time_start: '19:30',
    time_end: '23:00',
    type: 'primary',
    location: '',
    pic: '',
    description: ''
  };
  showEditModal.value = true;
};

const openEditModal = (evt) => {
  isEditMode.value = true;
  eventForm.value = { ...evt };
  showEditModal.value = true;
};

const submitEventForm = () => {
  emit('save', { ...eventForm.value });
  showEditModal.value = false;
};

const deleteEvent = (id) => {
  if (confirm('Apakah Anda yakin ingin menghapus agenda acara ini?')) {
    emit('delete', id);
    if (showDetailModal.value && selectedEvent.value?.id === id) {
      showDetailModal.value = false;
    }
  }
};
</script>
