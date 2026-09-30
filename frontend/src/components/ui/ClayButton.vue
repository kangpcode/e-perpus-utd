<template>
  <button
    :type="type"
    :disabled="disabled"
    :class="[
      'inline-flex items-center justify-center font-semibold transition-all select-none focus:outline-none focus:ring-2 focus:ring-offset-2',
      sizeClasses[size],
      variantClasses[variant],
      block ? 'w-full' : '',
      disabled ? 'opacity-50 cursor-not-allowed transform-none shadow-none' : 'cursor-pointer',
      customClass
    ]"
    @click="$emit('click', $event)"
  >
    <slot name="leading"></slot>
    <span :class="{'mx-1.5': hasLeadingOrTrailing}"><slot></slot></span>
    <slot name="trailing"></slot>
  </button>
</template>

<script setup>
import { computed, useSlots } from 'vue'

const slots = useSlots()
const hasLeadingOrTrailing = computed(() => !!slots.leading || !!slots.trailing)

const props = defineProps({
  variant: {
    type: String,
    default: 'red', // 'red' | 'blue' | 'white' | 'ghost' | 'emerald'
  },
  size: {
    type: String,
    default: 'md', // 'sm' | 'md' | 'lg'
  },
  type: {
    type: String,
    default: 'button',
  },
  disabled: {
    type: Boolean,
    default: false,
  },
  block: {
    type: Boolean,
    default: false,
  },
  customClass: {
    type: String,
    default: '',
  }
})

defineEmits(['click'])

const sizeClasses = {
  sm: 'px-3.5 py-1.5 text-xs rounded-xl gap-1.5',
  md: 'px-5 py-2.5 text-sm rounded-2xl gap-2',
  lg: 'px-7 py-3.5 text-base rounded-[22px] gap-2.5',
}

const variantClasses = {
  red: 'clay-btn-red focus:ring-red-400',
  blue: 'clay-btn-blue focus:ring-blue-400',
  white: 'clay-btn-white text-slate-800 hover:text-slate-900 focus:ring-slate-300',
  emerald: 'bg-emerald-600 text-white rounded-2xl shadow-[0_10px_20px_rgba(16,185,129,0.35)] active:scale-95 transition-all',
  ghost: 'bg-transparent text-slate-600 hover:bg-slate-200/50 active:bg-slate-200 rounded-2xl shadow-none',
}
</script>
