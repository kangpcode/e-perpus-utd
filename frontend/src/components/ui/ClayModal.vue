<template>
  <Teleport to="body">
    <Transition
      enter-active-class="transition duration-300 ease-out"
      enter-from-class="opacity-0"
      enter-to-class="opacity-100"
      leave-active-class="transition duration-200 ease-in"
      leave-from-class="opacity-100"
      leave-to-class="opacity-0"
    >
      <div
        v-if="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-md overflow-y-auto"
        @click.self="$emit('close')"
      >
        <Transition
          enter-active-class="transition duration-300 cubic-bezier(0.16, 1, 0.3, 1)"
          enter-from-class="opacity-0 scale-90 translate-y-4"
          enter-to-class="opacity-100 scale-100 translate-y-0"
          leave-active-class="transition duration-200 ease-in"
          leave-from-class="opacity-100 scale-100 translate-y-0"
          leave-to-class="opacity-0 scale-90 translate-y-4"
        >
          <div
            v-if="isOpen"
            :class="[
              'clay-card w-full max-w-2xl bg-white p-6 md:p-8 relative max-h-[90vh] flex flex-col',
              customClass
            ]"
          >
            <!-- Close Button -->
            <button
              type="button"
              class="absolute top-5 right-5 text-slate-400 hover:text-slate-700 w-9 h-9 flex items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200 active:scale-95 transition-all shadow-inner"
              @click="$emit('close')"
            >
              ✕
            </button>

            <!-- Modal Header -->
            <div v-if="$slots.header" class="mb-4 pr-8">
              <slot name="header"></slot>
            </div>

            <!-- Modal Body (Scrollable) -->
            <div class="overflow-y-auto pr-1 flex-1">
              <slot></slot>
            </div>

            <!-- Modal Footer -->
            <div v-if="$slots.footer" class="mt-6 pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
              <slot name="footer"></slot>
            </div>
          </div>
        </Transition>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup>
defineProps({
  isOpen: {
    type: Boolean,
    default: false,
  },
  customClass: {
    type: String,
    default: '',
  }
})

defineEmits(['close'])
</script>
