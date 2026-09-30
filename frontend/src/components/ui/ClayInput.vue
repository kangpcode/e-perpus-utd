<template>
  <div class="relative w-full">
    <div
      :class="[
        'clay-inset flex items-center px-4 py-3 transition-all duration-200 border-2',
        isFocused ? 'border-blue-500/60 ring-4 ring-blue-100' : 'border-transparent',
        customClass
      ]"
    >
      <div v-if="$slots.leading" class="text-slate-400 mr-3 flex items-center shrink-0">
        <slot name="leading"></slot>
      </div>

      <input
        :type="type"
        :value="modelValue"
        :placeholder="placeholder"
        :disabled="disabled"
        class="w-full bg-transparent text-slate-800 placeholder-slate-400 text-sm md:text-base font-medium focus:outline-none"
        @input="$emit('update:modelValue', $event.target.value)"
        @focus="isFocused = true"
        @blur="isFocused = false"
        @keyup.enter="$emit('enter')"
      />

      <button
        v-if="modelValue && clearable"
        type="button"
        class="text-slate-400 hover:text-slate-600 p-1 ml-2 transition-colors rounded-full hover:bg-slate-200/50"
        @click="$emit('update:modelValue', '')"
      >
        <span class="text-xs font-bold">✕</span>
      </button>

      <div v-if="$slots.trailing" class="ml-2 flex items-center shrink-0">
        <slot name="trailing"></slot>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const isFocused = ref(false)

defineProps({
  modelValue: {
    type: [String, Number],
    default: '',
  },
  type: {
    type: String,
    default: 'text',
  },
  placeholder: {
    type: String,
    default: '',
  },
  clearable: {
    type: Boolean,
    default: true,
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  customClass: {
    type: String,
    default: '',
  }
})

defineEmits(['update:modelValue', 'enter'])
</script>
