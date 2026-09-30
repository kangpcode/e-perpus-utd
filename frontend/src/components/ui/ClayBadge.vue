<template>
  <span
    :class="[
      'inline-flex items-center font-semibold select-none transition-transform',
      sizeClasses[size],
      variantClasses[variant],
      customClass
    ]"
  >
    <span v-if="dot" :class="['w-1.5 h-1.5 rounded-full mr-1.5 animate-pulse', dotClasses[variant]]"></span>
    <slot name="leading"></slot>
    <slot></slot>
  </span>
</template>

<script setup>
defineProps({
  variant: {
    type: String,
    default: 'blue', // 'red' | 'blue' | 'emerald' | 'amber' | 'slate'
  },
  size: {
    type: String,
    default: 'sm', // 'xs' | 'sm' | 'md'
  },
  dot: {
    type: Boolean,
    default: false,
  },
  customClass: {
    type: String,
    default: '',
  }
})

const sizeClasses = {
  xs: 'px-2 py-0.5 text-[10px] rounded-full',
  sm: 'px-3 py-1 text-xs rounded-full gap-1',
  md: 'px-4 py-1.5 text-sm rounded-full gap-1.5',
}

const variantClasses = {
  red: 'clay-badge-red font-medium',
  blue: 'clay-badge-blue font-medium',
  emerald: 'clay-badge-emerald font-medium',
  amber: 'clay-badge-amber font-medium',
  slate: 'bg-slate-100 text-slate-700 shadow-sm border border-slate-200/60',
}

const dotClasses = {
  red: 'bg-red-500',
  blue: 'bg-blue-600',
  emerald: 'bg-emerald-500',
  amber: 'bg-amber-500',
  slate: 'bg-slate-400',
}
</script>
