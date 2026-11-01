<template>
  <div>
    <!-- MODAL DIALOG (Modern Alert & Confirm) -->
    <Transition name="fade">
      <div
        v-if="store.dialog.isOpen"
        class="fixed inset-0 z-[9999] flex items-center justify-center p-4 bg-black/80 backdrop-blur-md"
        @click.self="store.dialog.isConfirm ? store.handleCancel() : store.handleConfirm()"
      >
        <Transition name="scale">
          <div
            v-if="store.dialog.isOpen"
            :class="[
              'relative w-full max-w-md rounded-3xl p-6 sm:p-7 shadow-sm border transition-all text-center overflow-hidden',
              'bg-white/95 backdrop-blur-2xl',
              themeClasses.border,
              themeClasses.shadow
            ]"
          >
            <!-- Ambient top glow -->
            <div
              :class="[
                'absolute -top-20 left-1/2 -translate-x-1/2 w-56 h-36 rounded-full blur-3xl pointer-events-none opacity-40',
                themeClasses.glowBg
              ]"
            ></div>

            <!-- Header Badge -->
            <div class="flex items-center justify-center space-x-2 mb-4">
              <span class="w-1.5 h-1.5 rounded-full" :class="themeClasses.dot"></span>
              <span class="text-[10px] uppercase font-bold tracking-widest text-slate-500 font-mono">
                Sistem Rojabiyyah 2026
              </span>
              <span class="w-1.5 h-1.5 rounded-full" :class="themeClasses.dot"></span>
            </div>

            <!-- Animated Icon -->
            <div class="mx-auto mb-4 flex items-center justify-center">
              <div
                :class="[
                  'w-16 h-16 rounded-2xl flex items-center justify-center text-3xl shadow-inner border relative',
                  themeClasses.iconBox
                ]"
              >
                <!-- Pulse ring -->
                <span
                  :class="[
                    'absolute inset-0 rounded-2xl animate-ping opacity-25 pointer-events-none',
                    themeClasses.iconPulse
                  ]"
                ></span>

                <!-- SVGs or Icons based on type -->
                <svg
                  v-if="store.dialog.type === 'success'"
                  class="w-8 h-8 text-emerald-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                </svg>

                <svg
                  v-else-if="store.dialog.type === 'error'"
                  class="w-8 h-8 text-rose-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>

                <svg
                  v-else-if="store.dialog.type === 'warning'"
                  class="w-8 h-8 text-amber-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>

                <svg
                  v-else-if="store.dialog.type === 'confirm'"
                  class="w-8 h-8 text-indigo-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>

                <svg
                  v-else
                  class="w-8 h-8 text-cyan-400"
                  fill="none"
                  stroke="currentColor"
                  viewBox="0 0 24 24"
                >
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
            </div>

            <!-- Title -->
            <h3 class="text-base sm:text-lg font-bold text-slate-900 tracking-wide mb-2">
              {{ store.dialog.title }}
            </h3>

            <!-- Message (formatted text) -->
            <div
              class="text-xs sm:text-sm text-slate-700 leading-relaxed mb-6 whitespace-pre-line px-2 font-normal"
            >
              {{ store.dialog.message }}
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-center space-x-3 pt-2">
              <!-- Cancel Button for Confirm -->
              <button
                v-if="store.dialog.isConfirm"
                type="button"
                @click="store.handleCancel"
                class="px-5 py-2.5 rounded-2xl bg-white hover:bg-slate-100 text-slate-700 font-semibold text-xs transition border border-slate-200 hover:border-slate-600 active:scale-95 flex-1"
              >
                {{ store.dialog.cancelText || 'Batal' }}
              </button>

              <!-- Confirm / OK Button -->
              <button
                type="button"
                ref="confirmBtnRef"
                @click="store.handleConfirm"
                :class="[
                  'px-6 py-2.5 rounded-2xl text-white font-bold text-xs shadow-lg transition-all active:scale-95',
                  store.dialog.isConfirm ? 'flex-1' : 'w-full sm:w-auto min-w-[160px]',
                  themeClasses.btn
                ]"
              >
                {{ store.dialog.confirmText || 'OK, Mengerti' }}
              </button>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>

    <!-- FLOATING TOASTS (Top Right Notifications) -->
    <div
      v-if="store.toasts.length > 0"
      class="fixed top-5 right-5 z-[9998] flex flex-col space-y-3 pointer-events-none max-w-sm w-full"
    >
      <TransitionGroup name="toast">
        <div
          v-for="item in store.toasts"
          :key="item.id"
          :class="[
            'pointer-events-auto p-4 rounded-2xl bg-white/95 backdrop-blur-xl border shadow-sm flex items-start space-x-3 transition-all',
            item.type === 'success'
              ? 'border-emerald-500/40 shadow-emerald-950/40 text-slate-700'
              : item.type === 'error'
              ? 'border-rose-500/40 shadow-rose-950/40 text-slate-700'
              : item.type === 'warning'
              ? 'border-amber-500/40 shadow-amber-950/40 text-slate-700'
              : 'border-cyan-500/40 shadow-cyan-950/40 text-slate-700'
          ]"
        >
          <!-- Toast Icon -->
          <div class="flex-shrink-0 mt-0.5">
            <span
              v-if="item.type === 'success'"
              class="w-6 h-6 rounded-full bg-emerald-100 border border-emerald-300 text-emerald-700 flex items-center justify-center text-xs font-bold"
            >✓</span>
            <span
              v-else-if="item.type === 'error'"
              class="w-6 h-6 rounded-full bg-rose-100 border border-rose-300 text-rose-700 flex items-center justify-center text-xs font-bold"
            >✕</span>
            <span
              v-else-if="item.type === 'warning'"
              class="w-6 h-6 rounded-full bg-amber-100 border border-amber-300 text-amber-700 flex items-center justify-center text-xs font-bold"
            >!</span>
            <span
              v-else
              class="w-6 h-6 rounded-full bg-cyan-100 border border-cyan-300 text-cyan-700 flex items-center justify-center text-xs font-bold"
            >ℹ</span>
          </div>

          <!-- Toast Content -->
          <div class="flex-1 min-w-0">
            <h4 class="text-xs font-bold text-slate-900 tracking-wide">{{ item.title }}</h4>
            <p class="text-[11px] text-slate-700 mt-0.5 leading-snug">{{ item.message }}</p>
          </div>

          <!-- Close Button -->
          <button
            type="button"
            @click="store.removeToast(item.id)"
            class="text-slate-500 hover:text-white text-xs p-1 rounded transition"
          >
            ✕
          </button>
        </div>
      </TransitionGroup>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, watch, onMounted, onUnmounted } from 'vue';
import { useNotificationStore } from '../stores/notification';

const store = useNotificationStore();
const confirmBtnRef = ref(null);

const themeClasses = computed(() => {
  const type = store.dialog.type;

  switch (type) {
    case 'success':
      return {
        border: 'border-emerald-200',
        shadow: 'shadow-lg shadow-emerald-500/10',
        glowBg: 'bg-emerald-400',
        dot: 'bg-emerald-500 animate-pulse',
        iconBox: 'bg-emerald-50 border-emerald-200',
        iconPulse: 'bg-emerald-400',
        btn: 'bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 shadow-md shadow-emerald-500/20',
      };
    case 'error':
      return {
        border: 'border-rose-200',
        shadow: 'shadow-lg shadow-rose-500/10',
        glowBg: 'bg-rose-400',
        dot: 'bg-rose-500 animate-pulse',
        iconBox: 'bg-rose-50 border-rose-200',
        iconPulse: 'bg-rose-400',
        btn: 'bg-gradient-to-r from-rose-600 to-red-600 hover:from-rose-500 hover:to-red-500 shadow-md shadow-rose-500/20',
      };
    case 'warning':
      return {
        border: 'border-amber-200',
        shadow: 'shadow-lg shadow-amber-500/10',
        glowBg: 'bg-amber-400',
        dot: 'bg-amber-500 animate-pulse',
        iconBox: 'bg-amber-50 border-amber-200',
        iconPulse: 'bg-amber-400',
        btn: 'bg-gradient-to-r from-amber-600 to-yellow-600 hover:from-amber-500 hover:to-yellow-500 shadow-md shadow-amber-500/20',
      };
    case 'confirm':
      return {
        border: 'border-indigo-200',
        shadow: 'shadow-lg shadow-indigo-500/10',
        glowBg: 'bg-indigo-400',
        dot: 'bg-indigo-500 animate-pulse',
        iconBox: 'bg-indigo-50 border-indigo-200',
        iconPulse: 'bg-indigo-400',
        btn: 'bg-gradient-to-r from-indigo-600 to-cyan-600 hover:from-indigo-500 hover:to-cyan-500 shadow-md shadow-indigo-500/20',
      };
    default:
      return {
        border: 'border-cyan-200',
        shadow: 'shadow-lg shadow-cyan-500/10',
        glowBg: 'bg-cyan-400',
        dot: 'bg-cyan-500 animate-pulse',
        iconBox: 'bg-cyan-50 border-cyan-200',
        iconPulse: 'bg-cyan-400',
        btn: 'bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 shadow-md shadow-cyan-500/20',
      };
  }
});

// Auto-focus confirm button when modal opens
watch(
  () => store.dialog.isOpen,
  (open) => {
    if (open) {
      setTimeout(() => {
        confirmBtnRef.value?.focus();
      }, 50);
    }
  }
);

// Keyboard controls: Enter -> confirm, Escape -> cancel
const handleKeydown = (e) => {
  if (!store.dialog.isOpen) return;
  if (e.key === 'Enter') {
    e.preventDefault();
    store.handleConfirm();
  } else if (e.key === 'Escape') {
    e.preventDefault();
    store.dialog.isConfirm ? store.handleCancel() : store.handleConfirm();
  }
};

onMounted(() => {
  window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
  window.removeEventListener('keydown', handleKeydown);
});
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

.scale-enter-active,
.scale-leave-active {
  transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.scale-enter-from,
.scale-leave-to {
  opacity: 0;
  transform: scale(0.92) translateY(8px);
}

.toast-enter-active,
.toast-leave-active {
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}
.toast-enter-from {
  opacity: 0;
  transform: translateX(30px);
}
.toast-leave-to {
  opacity: 0;
  transform: translateX(30px) scale(0.95);
}
</style>

