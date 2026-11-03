<template>
  <!-- Modal Backdrop & Dialog -->
  <div
    v-if="show"
    class="fixed inset-0 z-50 flex items-end sm:items-center justify-end sm:justify-end sm:p-6 p-0 bg-slate-900/50 backdrop-blur-xs transition-all duration-300"
    @click.self="$emit('close')"
  >
    <!-- Chat Container -->
    <div
      class="w-full sm:w-[440px] h-[92vh] sm:h-[620px] max-h-[660px] bg-white sm:rounded-3xl rounded-t-3xl shadow-2xl border border-slate-200/90 flex flex-col overflow-hidden animate-in fade-in slide-in-from-bottom-5 duration-200"
    >
      <!-- ========================================== -->
      <!-- 1. Header (Emerald Gradient Branding)     -->
      <!-- ========================================== -->
      <div class="bg-gradient-to-r from-[#064e3b] via-[#047857] to-[#059669] p-4 text-white flex-shrink-0 relative">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-3">
            <div class="relative w-10 h-10 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 p-1 flex items-center justify-center flex-shrink-0">
              <img
                :src="logoUrl"
                alt="Logo RJB"
                class="w-full h-full object-contain"
                @error="onLogoError"
              />
              <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full bg-emerald-400 border-2 border-[#064e3b]"></span>
            </div>
            <div>
              <div class="flex items-center space-x-1.5">
                <h3 class="font-bold text-sm text-white tracking-wide">Asisten AI Rojabiyyah</h3>
                <span class="px-1.5 py-0.2 text-[9px] font-bold bg-white/20 text-white rounded border border-white/30 flex items-center space-x-1">
                  <span>✨</span>
                  <span>Gemini AI</span>
                </span>
              </div>
              <p class="text-[11px] text-emerald-100 flex items-center space-x-1">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-300 animate-pulse"></span>
                <span>Tanya Umum, Proyektor &amp; Dokumentasi</span>
              </p>
            </div>
          </div>

          <div class="flex items-center space-x-1">
            <button
              @click="$emit('close')"
              class="w-8 h-8 rounded-xl bg-white/10 hover:bg-white/20 flex items-center justify-center text-white/80 hover:text-white transition cursor-pointer"
              title="Tutup Chatbot"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
          </div>
        </div>

        <!-- Mode Navigation Tabs -->
        <div class="flex items-center space-x-1.5 mt-3 pt-2.5 border-t border-white/15 text-xs font-semibold">
          <button
            @click="activeTab = 'chat'"
            :class="[
              'flex-1 py-1.5 px-2 rounded-xl flex items-center justify-center space-x-1.5 transition cursor-pointer',
              activeTab === 'chat'
                ? 'bg-white text-emerald-900 shadow-sm font-bold'
                : 'text-white/80 hover:bg-white/10 hover:text-white'
            ]"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
            <span>Tanya AI</span>
          </button>

          <button
            @click="activeTab = 'faq'"
            :class="[
              'flex-1 py-1.5 px-2 rounded-xl flex items-center justify-center space-x-1.5 transition cursor-pointer',
              activeTab === 'faq'
                ? 'bg-white text-emerald-900 shadow-sm font-bold'
                : 'text-white/80 hover:bg-white/10 hover:text-white'
            ]"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span>FAQ &amp; SOP</span>
          </button>

          <button
            @click="activeTab = 'feedback'"
            :class="[
              'flex-1 py-1.5 px-2 rounded-xl flex items-center justify-center space-x-1.5 transition cursor-pointer',
              activeTab === 'feedback'
                ? 'bg-white text-emerald-900 shadow-sm font-bold'
                : 'text-white/80 hover:bg-white/10 hover:text-white'
            ]"
          >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            <span>Kritik &amp; Saran</span>
          </button>
        </div>
      </div>

      <!-- ========================================== -->
      <!-- TAB 1: TANYA AI (CHAT CONVERSATION)        -->
      <!-- ========================================== -->
      <div v-show="activeTab === 'chat'" class="flex-1 flex flex-col min-h-0 bg-[#f8fafc]">
        <!-- Messages Scroll Area -->
        <div ref="chatContainerRef" class="flex-1 overflow-y-auto p-4 space-y-3.5 text-xs">
          <!-- Intro Notification Banner -->
          <div class="p-3 bg-emerald-50 border border-emerald-200/80 rounded-2xl text-[11px] text-emerald-800 space-y-1">
            <div class="font-bold flex items-center space-x-1.5 text-emerald-900">
              <span>💡</span>
              <span>Pusat Kendali Informasi Haul &amp; Rojabiyyah 2026</span>
            </div>
            <p class="text-slate-600 leading-relaxed">
              Tanyakan cara kerja proyektor, titik pos A-F, tim kabel, tugas liputan &amp; shooting, atau unduh berkas di Google Drive.
            </p>
          </div>

          <!-- Message Bubbles -->
          <div
            v-for="(msg, idx) in messages"
            :key="idx"
            :class="[
              'flex w-full',
              msg.role === 'user' ? 'justify-end' : 'justify-start'
            ]"
          >
            <!-- Bot Avatar -->
            <div
              v-if="msg.role === 'bot'"
              class="w-7 h-7 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 text-white flex items-center justify-center flex-shrink-0 mr-2 mt-0.5 shadow-xs"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>

            <!-- Bubble Content -->
            <div
              :class="[
                'max-w-[85%] rounded-2xl p-3 shadow-xs leading-relaxed space-y-1.5',
                msg.role === 'user'
                  ? 'bg-emerald-600 text-white rounded-br-xs'
                  : 'bg-white border border-slate-200/90 text-slate-800 rounded-bl-xs'
              ]"
            >
              <div class="whitespace-pre-line text-xs font-normal" v-html="formatMessage(msg.text)"></div>
              
              <!-- Action Button inside Bot Message (if provided) -->
              <div v-if="msg.action" class="pt-1 flex flex-wrap gap-1.5">
                <button
                  v-if="msg.action.type === 'drive'"
                  @click="openDriveModal"
                  class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-lg bg-emerald-50 hover:bg-emerald-100 text-emerald-800 border border-emerald-300 font-bold text-[11px] transition cursor-pointer"
                >
                  <span>📁</span>
                  <span>Buka Google Drive Berkas</span>
                </button>
                <button
                  v-if="msg.action.type === 'faq'"
                  @click="activeTab = 'faq'"
                  class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-lg bg-teal-50 hover:bg-teal-100 text-teal-800 border border-teal-300 font-bold text-[11px] transition cursor-pointer"
                >
                  <span>❓</span>
                  <span>Lihat FAQ Lengkap</span>
                </button>
                <button
                  v-if="msg.action.type === 'feedback'"
                  @click="activeTab = 'feedback'"
                  class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-300 font-bold text-[11px] transition cursor-pointer"
                >
                  <span>✍️</span>
                  <span>Tulis Kritik &amp; Saran</span>
                </button>
              </div>

              <div
                :class="[
                  'text-[9px] pt-1 flex items-center justify-between',
                  msg.role === 'user' ? 'text-emerald-100 justify-end' : 'text-slate-400'
                ]"
              >
                <span v-if="msg.role === 'bot' && msg.provider === 'gemini'" class="inline-flex items-center space-x-1 px-1.5 py-0.2 rounded bg-emerald-50 text-emerald-700 font-bold border border-emerald-200 text-[9px]">
                  <span>✨</span>
                  <span>Google Gemini AI</span>
                </span>
                <span v-else-if="msg.role === 'bot'"></span>
                <span>{{ msg.time }}</span>
              </div>
            </div>
          </div>

          <!-- Typing Indicator -->
          <div v-if="isTyping" class="flex items-center space-x-2 text-slate-400">
            <div class="w-7 h-7 rounded-xl bg-slate-100 border border-slate-200 flex items-center justify-center">
              <svg class="w-3.5 h-3.5 text-emerald-600 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
            </div>
            <div class="bg-white border border-slate-200 rounded-2xl px-3 py-2 flex items-center space-x-1">
              <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-bounce"></span>
              <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-bounce [animation-delay:0.2s]"></span>
              <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-bounce [animation-delay:0.4s]"></span>
            </div>
          </div>
        </div>

        <!-- Quick Question Chips -->
        <div class="px-3 pt-2 pb-1 border-t border-slate-200/80 bg-white flex-shrink-0">
          <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-1.5 flex items-center justify-between">
            <span>Rekomendasi Pertanyaan:</span>
            <button @click="clearChat" class="text-slate-400 hover:text-rose-500 underline text-[10px] cursor-pointer">Reset Chat</button>
          </div>
          <div class="flex items-center space-x-1.5 overflow-x-auto pb-1 text-[11px] scrollbar-none">
            <button
              v-for="(chip, cIdx) in quickChips"
              :key="cIdx"
              @click="askQuick(chip.query)"
              class="whitespace-nowrap px-2.5 py-1 rounded-xl bg-slate-100 hover:bg-emerald-50 border border-slate-200 hover:border-emerald-300 text-slate-700 hover:text-emerald-800 transition active:scale-95 cursor-pointer font-medium"
            >
              {{ chip.label }}
            </button>
          </div>
        </div>

        <!-- Input Box -->
        <form @submit.prevent="sendMessage" class="p-3 bg-white border-t border-slate-100 flex items-center space-x-2 flex-shrink-0">
          <input
            v-model="inputQuery"
            type="text"
            placeholder="Tanyakan proyektor, titik pos, tugas dokumentasi..."
            class="flex-1 px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200 transition"
          />
          <button
            type="submit"
            :disabled="!inputQuery.trim() || isTyping"
            class="w-10 h-10 rounded-xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:brightness-105 disabled:opacity-50 text-white flex items-center justify-center shadow-md shadow-emerald-700/20 transition cursor-pointer flex-shrink-0"
            title="Kirim Pertanyaan"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
          </button>
        </form>
      </div>

      <!-- ========================================== -->
      <!-- TAB 2: FAQ & SOP ACARA                     -->
      <!-- ========================================== -->
      <div v-show="activeTab === 'faq'" class="flex-1 overflow-y-auto p-4 bg-[#f8fafc] space-y-4 text-xs">
        <!-- FAQ Search -->
        <div class="relative">
          <input
            v-model="faqSearch"
            type="text"
            placeholder="Cari pertanyaan seputar proyektor / tugas..."
            class="w-full pl-9 pr-4 py-2 bg-white border border-slate-200 rounded-xl text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition"
          />
          <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </div>

        <!-- FAQ Categories List -->
        <div class="space-y-3">
          <div
            v-for="(item, fIdx) in filteredFaqs"
            :key="fIdx"
            class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden transition"
          >
            <button
              @click="toggleFaq(fIdx)"
              class="w-full p-3.5 text-left flex items-center justify-between font-bold text-slate-800 hover:text-emerald-700 transition cursor-pointer text-xs"
            >
              <div class="flex items-center space-x-2 pr-2">
                <span class="text-base">{{ item.icon }}</span>
                <span>{{ item.q }}</span>
              </div>
              <svg
                :class="['w-4 h-4 text-slate-400 flex-shrink-0 transition-transform duration-200', expandedFaqs.includes(fIdx) ? 'rotate-180 text-emerald-600' : '']"
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
              >
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
              </svg>
            </button>
            <div
              v-show="expandedFaqs.includes(fIdx)"
              class="px-3.5 pb-3.5 text-slate-600 leading-relaxed text-[11px] border-t border-slate-100 pt-2.5 bg-slate-50/50"
            >
              <div class="whitespace-pre-line" v-html="formatMessage(item.a)"></div>
            </div>
          </div>

          <div v-if="filteredFaqs.length === 0" class="text-center py-8 text-slate-400">
            Tidak ada FAQ yang cocok dengan pencarian "{{ faqSearch }}".
          </div>
        </div>
      </div>

      <!-- ========================================== -->
      <!-- TAB 3: FORM KRITIK & SARAN                 -->
      <!-- ========================================== -->
      <div v-show="activeTab === 'feedback'" class="flex-1 overflow-y-auto p-4 sm:p-5 bg-[#f8fafc]">
        <!-- Success State -->
        <div v-if="feedbackSuccess" class="p-6 bg-white border border-emerald-300 rounded-3xl text-center space-y-3 shadow-md">
          <div class="w-12 h-12 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center mx-auto">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
          </div>
          <h4 class="font-bold text-sm text-slate-900">Alhamdulillah, Masukan Terkirim!</h4>
          <p class="text-xs text-slate-600 leading-relaxed">
            Terima kasih banyak atas kritik dan saran yang Anda sampaikan. Masukan Anda langsung tercatat di sistem Super Admin untuk evaluasi perbaikan acara.
          </p>
          <button
            @click="resetFeedbackForm"
            class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl font-bold text-xs shadow-sm transition cursor-pointer"
          >
            Kirim Masukan Lainnya
          </button>
        </div>

        <!-- Form Input -->
        <form v-else @submit.prevent="submitFeedback" class="bg-white border border-slate-200/90 rounded-2xl p-4 sm:p-5 shadow-xs space-y-3.5 text-xs">
          <div>
            <div class="font-bold text-slate-900 text-sm flex items-center space-x-1.5">
              <span>✍️</span>
              <span>Kritik, Saran &amp; Evaluasi</span>
            </div>
            <p class="text-[11px] text-slate-500 mt-0.5">
              Sampaikan kendala di lapangan, saran teknis proyektor, liputan dokumentasi, maupun masukan umum.
            </p>
          </div>

          <div v-if="feedbackError" class="p-2.5 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl text-[11px]">
            {{ feedbackError }}
          </div>

          <!-- Kategori -->
          <div>
            <label class="block font-semibold text-slate-700 mb-1 text-[11px]">Kategori Masukan *</label>
            <select
              v-model="feedbackForm.category"
              class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition text-xs"
            >
              <option value="Proyektor &amp; Kelistrikan">📽️ Proyektor, TV &amp; Jalur Kabel</option>
              <option value="Dokumentasi &amp; Shooting">📸 Dokumentasi, Foto &amp; Video</option>
              <option value="Pelayanan &amp; Acara">🕌 Ketertiban &amp; Acara Haul</option>
              <option value="Aplikasi &amp; Sistem">💻 Sistem Aplikasi &amp; Website</option>
              <option value="Umum">💬 Saran &amp; Kritik Umum</option>
            </select>
          </div>

          <!-- Rating Kepuasan -->
          <div>
            <label class="block font-semibold text-slate-700 mb-1 text-[11px]">Tingkat Kepuasan Layanan</label>
            <div class="flex items-center space-x-2">
              <button
                v-for="star in 5"
                :key="star"
                type="button"
                @click="feedbackForm.rating = star"
                class="text-xl transition cursor-pointer hover:scale-125"
                :title="`Beri ${star} Bintang`"
              >
                <span :class="feedbackForm.rating >= star ? 'text-amber-400' : 'text-slate-200'">★</span>
              </button>
              <span class="text-[11px] font-semibold text-slate-500 ml-2">
                {{ feedbackRatingLabel }}
              </span>
            </div>
          </div>

          <!-- Nama & Kontak (Opsional) -->
          <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
            <div>
              <label class="block font-semibold text-slate-700 mb-1 text-[11px]">Nama Anda (Opsional)</label>
              <input
                v-model="feedbackForm.name"
                type="text"
                placeholder="Anonim / Nama Anda"
                class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition text-xs"
              />
            </div>
            <div>
              <label class="block font-semibold text-slate-700 mb-1 text-[11px]">No. WA / HP (Opsional)</label>
              <input
                v-model="feedbackForm.contact"
                type="text"
                placeholder="0812xxxxxxxx"
                class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition text-xs"
              />
            </div>
          </div>

          <!-- Isi Kritik & Saran -->
          <div>
            <label class="block font-semibold text-slate-700 mb-1 text-[11px]">Pesan Kritik &amp; Saran *</label>
            <textarea
              v-model="feedbackForm.message"
              required
              rows="4"
              placeholder="Tuliskan masukan, kendala yang Anda temui, atau ide perbaikan untuk panitia..."
              class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-emerald-500 focus:ring-1 focus:ring-emerald-500 transition text-xs"
            ></textarea>
          </div>

          <!-- Submit Button -->
          <button
            type="submit"
            :disabled="submittingFeedback || !feedbackForm.message.trim()"
            class="w-full py-2.5 px-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold rounded-xl shadow-md shadow-emerald-700/20 transition disabled:opacity-50 cursor-pointer flex items-center justify-center space-x-1.5"
          >
            <span>{{ submittingFeedback ? 'Mengirim Masukan...' : 'Kirim Kritik & Saran' }}</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
          </button>
        </form>
      </div>

    </div>
  </div>
</template>

<script setup>
import { ref, computed, nextTick, onMounted } from 'vue';
import api from '../services/api';
import { useSettingsStore } from '../stores/settings';

const props = defineProps({
  show: {
    type: Boolean,
    default: false,
  },
  proyektorTeams: {
    type: Array,
    default: () => [],
  },
});

const emit = defineEmits(['close', 'open-drive']);

const settingsStore = useSettingsStore();
const activeTab = ref('chat'); // 'chat' | 'faq' | 'feedback'
const chatContainerRef = ref(null);
const inputQuery = ref('');
const isTyping = ref(false);

const logoUrl = computed(() => {
  return settingsStore.settings?.logo_url || '/logo.png';
});

const onLogoError = (e) => {
  e.target.src = '/logo.png';
};

// ----------------------------------------------------
// Chatbot Knowledge & Messages
// ----------------------------------------------------
const messages = ref([
  {
    role: 'bot',
    text: `Assalamu'alaikum Warahmatullahi Wabarakatuh! 🌿\n\nSaya **Asisten AI Media Rojabiyyah**.\nSaya siap membantu Anda seputar:\n• **SOP & Pemasangan Proyektor**\n• **Daftar Titik Pos (Titik A s/d F)** & Tim Kabel\n• **Tugas Tim Dokumentasi** (Foto & Video)\n• **Akses Google Drive Berkas Resmi**\n\nAda yang bisa saya bantu hari ini?`,
    time: 'Sekarang',
  },
]);

const quickChips = [
  { label: '📽️ Cara Pasang Proyektor', query: 'cara pasang proyektor' },
  { label: '⚠️ Proyektor No Signal', query: 'proyektor no signal kendala' },
  { label: '📍 Titik Pos & Korwil', query: 'daftar titik pos proyektor dan korwil' },
  { label: '📸 Tugas Tim Liputan', query: 'tugas dokumentasi fotografer videografer' },
  { label: '📁 Google Drive Berkas', query: 'buka berkas google drive' },
  { label: '✍️ Kirim Kritik & Saran', query: 'saya ingin kritik dan saran' },
];

const scrollToBottom = async () => {
  await nextTick();
  if (chatContainerRef.value) {
    chatContainerRef.value.scrollTop = chatContainerRef.value.scrollHeight;
  }
};

const formatMessage = (text) => {
  if (!text) return '';
  // Convert **bold** to <strong>
  let html = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
  // Convert bullet points • to neat styled bullets
  html = html.replace(/^• (.*)$/gm, '<span class="inline-block w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 align-middle"></span>$1');
  return html;
};

// Natural Knowledge Matching Engine
const generateBotAnswer = (query) => {
  const q = query.toLowerCase().trim();

  // 1. Kritik & Saran
  if (q.includes('kritik') || q.includes('saran') || q.includes('masukan') || q.includes('evaluasi')) {
    return {
      text: `Masukan dari Anda sangat kami hargai untuk kesempurnaan acara Haul Ke-5 & Rojabiyyah 2026.\n\nSilakan klik tombol di bawah untuk langsung membuka formulir **Kritik & Saran**:`,
      action: { type: 'feedback' },
    };
  }

  // 2. Google Drive / Berkas / Logo
  if (q.includes('drive') || q.includes('berkas') || q.includes('download') || q.includes('unduh') || q.includes('logo') || q.includes('skema')) {
    return {
      text: `Seluruh berkas resmi Haul Ke-5 (Logo RJB resolusi tinggi, skema jalur FO/LAN, juknis rundown, & poster kegiatan) tersimpan di Google Drive Resmi.\n\nKlik tombol di bawah untuk membuka dan mengunduh berkas langsung:`,
      action: { type: 'drive' },
    };
  }

  // 3. Proyektor: Pemasangan / SOP
  if (q.includes('pasang') || q.includes('instalasi') || q.includes('tata cara') || (q.includes('proyektor') && q.includes('cara'))) {
    return {
      text: `**SOP Pemasangan Proyektor & Layar:**\n\n1. **Penempatan Pos**: Letakkan proyektor di tempat yang datar, kokoh, dan sirkulasi udara tidak tertutup.\n2. **Kabel Power**: Sambungkan kabel power listrik ke colokan/stopkontak yang stabil.\n3. **Kabel Sinyal**: Pasang kabel HDMI ke unit proyektor dan hubungkan receiver Extender Fiber Optic (FO) / LAN dari jalur posko.\n4. **Nyalakan**: Tekan tombol Power pada proyektor, tunggu lampu indikator berubah dari merah ke hijau/biru.\n5. **Pilih Source**: Tekan tombol *Source / Input* pada remote/bodi proyektor, pilih **HDMI 1** atau **HDMI 2**.\n6. **Fokus Lensa**: Putar ring fokus pada lensa hingga tampilan tulisan tajam dan presisi.`,
    };
  }

  // 4. Proyektor: Troubleshooting / No Signal / Mati / Berkedip
  if (q.includes('no signal') || q.includes('tidak tampil') || q.includes('mati') || q.includes('rusak') || q.includes('kedip') || q.includes('sinyal')) {
    return {
      text: `**Panduan Cepat Mengatasi Kendala Proyektor:**\n\n• **Jika 'No Signal':**\n  1. Cek port input pada proyektor, pastikan source terpilih sesuai colokan kabel HDMI (HDMI 1 / HDMI 2).\n  2. Periksa lampu indikator *Link / Data* pada alat Extender FO/LAN. Jika mati, cek adaptor listrik extender.\n  3. Pastikan kabel FO tidak terlipat tajam atau terinjak jamaah.\n\n• **Jika Proyektor Mati Mendadak:**\n  1. Jangan langsung dicabut listriknya, biarkan kipas pendingin berputar.\n  2. Cek filter udara belakang apakah tersumbat debu (overheat).\n  3. Segera laporkan ke Tim Kabel posko Anda atau hubungi Korwil Titik.`,
      action: { type: 'faq' },
    };
  }

  // 5. Titik Proyektor / Korwil / Kabel
  if (q.includes('titik') || q.includes('pos') || q.includes('korwil') || q.includes('kabel') || q.includes('posko')) {
    let titikText = `**Pembagian Titik Pos Proyektor (Titik A s/d F):**\n\n`;
    if (props.proyektorTeams && props.proyektorTeams.length > 0) {
      props.proyektorTeams.forEach((t) => {
        titikText += `• **${t.titik}**:\n  - Korwil: **${t.korwil || '-'}**\n  - Tim Kabel: **${t.kabel || '-'}**\n  - Posisi: ${t.items?.length || 0} Perangkat LCD\n`;
      });
    } else {
      titikText += `• **Titik A**: Korwil **Alfani**, Kabel **Habib**\n• **Titik B**: KJA Center & Area Depan\n• **Titik C, D, E, F**: Sesuai pembagian zona sayap kanan, kiri, dan halaman luar majelis.\n`;
    }
    titikText += `\nSeluruh perangkat diatur secara terpusat dari server live streaming utama.`;
    return { text: titikText };
  }

  // 6. Dokumentasi & Liputan (Fotografer / Videografer)
  if (q.includes('dokumentasi') || q.includes('foto') || q.includes('video') || q.includes('shooting') || q.includes('liputan') || q.includes('tugas')) {
    return {
      text: `**SOP Tim Dokumentasi & Shooting Haul 2026:**\n\n• **Fotografer:**\n  - Mengabadikan momen khidmat (Habaib, Masyayikh, Pembacaan Ratib & Maulid, serta suasana jamaah).\n  - Standby di area panggung utama dan sudut strategis.\n\n• **Videografer Liputan:**\n  - Pengambilan footage cinematic 4K/60fps untuk teaser, reel Instagram & TikTok.\n  - Wawancara singkat tokoh/panitia jika diperlukan.\n\n• **Kameramen Multicam Live:**\n  - Bertanggung jawab pada kamera relay untuk tayangan proyektor dan siaran langsung.\n  - Menjaga komposisi stabil dan mematuhi instruksi switcher utama.`,
      action: { type: 'faq' },
    };
  }

  // 7. Salam / Greeting
  if (q.includes('halo') || q.includes('assalam') || q.includes('hai') || q.includes('pagi') || q.includes('siang') || q.includes('malam')) {
    return {
      text: `Wa'alaikumussalam Warahmatullahi Wabarakatuh! 🌿\n\nAda hal teknis mengenai proyektor, pos penugasan, jadwal kerja tim liputan, atau materi yang ingin Anda tanyakan? Silakan pilih tombol cepat di bawah atau ketik langsung.`,
    };
  }

  // 8. Pendaftaran Anggota / Relawan
  if (q.includes('daftar') || q.includes('relawan') || q.includes('seragam') || q.includes('anggota')) {
    return {
      text: `Pendaftaran panitia dan relawan Haul Ke-5 dibuka untuk umum melalui halaman **Formulir Pendaftaran**.\n\nAnda dapat mengisi data nama, ukuran seragam, divisi, dan domisili tanpa wajib memiliki akun login.`,
    };
  }

  // 9. Terima kasih
  if (q.includes('makasih') || q.includes('terima kasih') || q.includes('syukron') || q.includes('siap')) {
    return {
      text: `Afwan! Sama-sama. Semoga tugas dan khidmah Anda di Haul Ke-5 & Rojabiyyah 2026 berjalan lancar dan penuh berkah. Jangan ragu bertanya jika ada kendala di lapangan! 🙏✨`,
    };
  }

  // Default Fallback
  return {
    text: `Terima kasih pertanyaannya. Terkait **"${query}"**, sistem Media Rojabiyyah telah menyiapkan panduan teknis lengkap di tab **FAQ & SOP** atau Anda dapat menyampaikan masukan di tab **Kritik & Saran** agar ditindaklanjuti oleh koordinator lapangan.`,
    action: { type: 'faq' },
  };
};

const askQuick = (query) => {
  inputQuery.value = query;
  sendMessage();
};

const sendMessage = async () => {
  const query = inputQuery.value.trim();
  if (!query || isTyping.value) return;

  const now = new Date();
  const timeStr = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;

  // Push user message
  messages.value.push({
    role: 'user',
    text: query,
    time: timeStr,
  });

  inputQuery.value = '';
  isTyping.value = true;
  await scrollToBottom();

  try {
    // Call backend API (Gemini AI + live context)
    const res = await api.post('/public/ai-chat', {
      message: query,
      history: messages.value.slice(-6).map((m) => ({
        role: m.role === 'user' ? 'user' : 'model',
        text: m.text,
      })),
    });

    isTyping.value = false;
    messages.value.push({
      role: 'bot',
      text: res.data.reply,
      provider: res.data.provider || 'gemini',
      action: res.data.action,
      time: timeStr,
    });
    await scrollToBottom();
  } catch (err) {
    // If backend or network is slow/offline, seamlessly fall back to local AI engine
    const answer = generateBotAnswer(query);
    isTyping.value = false;
    messages.value.push({
      role: 'bot',
      text: answer.text,
      provider: 'local_ai',
      action: answer.action,
      time: timeStr,
    });
    await scrollToBottom();
  }
};

const clearChat = () => {
  messages.value = [
    {
      role: 'bot',
      text: `Obrolan telah dibersihkan. Silakan tanyakan hal seputar Proyektor, Dokumentasi, atau Berkas Haul 2026.`,
      time: 'Sekarang',
    },
  ];
};

const openDriveModal = () => {
  emit('open-drive');
};

// ----------------------------------------------------
// FAQ Section Data & Methods
// ----------------------------------------------------
const faqSearch = ref('');
const expandedFaqs = ref([0, 1]);

const faqs = ref([
  {
    icon: '📽️',
    q: 'Bagaimana cara mengatasi proyektor yang bertuliskan No Signal?',
    a: '1. Pastikan receiver HDMI Extender FO di pos Anda dalam kondisi menyala.\n2. Cek apakah kabel HDMI tercolok kencang di port yang sesuai (HDMI 1 / HDMI 2).\n3. Ganti source input pada proyektor ke port HDMI yang aktif.\n4. Jika lampu fiber optik mati, segera laporkan ke Tim Kabel atau Korwil Pos.',
  },
  {
    icon: '⚡',
    q: 'Apa yang harus dilakukan jika proyektor mendadak mati / lampu merah menyala?',
    a: 'Jangan mencabut kabel colokan listrik! Proyektor memerlukan daya untuk memutar kipas pendingin lampu. Tunggu 5-10 menit hingga dingin, periksa apakah lubang ventilasi tertutup, lalu nyalakan kembali.',
  },
  {
    icon: '📍',
    q: 'Di mana saja pembagian Titik Pos Proyektor A sampai F?',
    a: 'Titik Pos ditempatkan di:\n• Titik A: Area Utama & Panggung Depan (Korwil: Alfani, Kabel: Habib)\n• Titik B: Sayap Kanan & KJA Center\n• Titik C & D: Sayap Kiri dan Koridor Penghubung\n• Titik E & F: Halaman Luar Jamaah dan Area Parkir Utama.',
  },
  {
    icon: '📸',
    q: 'Kapan jam kerja dan shift Tim Dokumentasi (Foto & Video)?',
    a: 'Tim Dokumentasi dibagi menjadi dua agenda utama:\n• Hari Rabu: Shift Pagi/Sore (Persiapan & Penataan) dan Shift Malam (Tawasul & Ratib).\n• Hari Kamis: Shift Pagi hingga Selesai (Puncak Acara Haul Akbar).\nSetiap pergantian sesi, memory card diserahkan ke Posko Media Center untuk backup.',
  },
  {
    icon: '📁',
    q: 'Di mana saya bisa melihat dan mengunduh berkas resmi Google Drive?',
    a: 'Anda dapat mengklik ikon Google Drive di sudut kanan bawah beranda untuk melihat folder resmi yang berisi logo resolusi tinggi, rundown, skema jalur kabel, dan denah posko.',
  },
  {
    icon: '👕',
    q: 'Apakah pendaftaran anggota relawan mendapatkan seragam?',
    a: 'Ya, formulir pendaftaran anggota di halaman /register-member mencakup pendataan ukuran baju/seragam (S, M, L, XL, XXL, XXXL) untuk keperluan logistik panitia.',
  },
]);

const filteredFaqs = computed(() => {
  if (!faqSearch.value.trim()) return faqs.value;
  const s = faqSearch.value.toLowerCase();
  return faqs.value.filter((f) => f.q.toLowerCase().includes(s) || f.a.toLowerCase().includes(s));
});

const toggleFaq = (idx) => {
  const pos = expandedFaqs.value.indexOf(idx);
  if (pos > -1) {
    expandedFaqs.value.splice(pos, 1);
  } else {
    expandedFaqs.value.push(idx);
  }
};

// ----------------------------------------------------
// Feedback Form Section
// ----------------------------------------------------
const submittingFeedback = ref(false);
const feedbackSuccess = ref(false);
const feedbackError = ref('');

const feedbackForm = ref({
  category: 'Proyektor & Kelistrikan',
  rating: 5,
  name: '',
  contact: '',
  message: '',
});

const feedbackRatingLabel = computed(() => {
  switch (feedbackForm.value.rating) {
    case 5: return 'Sangat Puas ⭐⭐⭐⭐⭐';
    case 4: return 'Puas ⭐⭐⭐⭐';
    case 3: return 'Cukup ⭐⭐⭐';
    case 2: return 'Kurang Puas ⭐⭐';
    case 1: return 'Perlu Evaluasi Besar ⭐';
    default: return '';
  }
});

const submitFeedback = async () => {
  if (!feedbackForm.value.message.trim()) return;
  submittingFeedback.value = true;
  feedbackError.value = '';

  try {
    await api.post('/public/feedback', feedbackForm.value);
    feedbackSuccess.value = true;
  } catch (err) {
    feedbackError.value = err.response?.data?.message || 'Gagal mengirim kritik & saran. Silakan coba beberapa saat lagi.';
  } finally {
    submittingFeedback.value = false;
  }
};

const resetFeedbackForm = () => {
  feedbackSuccess.value = false;
  feedbackForm.value = {
    category: 'Proyektor & Kelistrikan',
    rating: 5,
    name: '',
    contact: '',
    message: '',
  };
};

onMounted(() => {
  if (!settingsStore.settings?.app_name) {
    settingsStore.fetchSettings();
  }
});
</script>

