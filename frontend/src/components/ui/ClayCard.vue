<template>
  <div
    :class="[
      'relative overflow-hidden',
      variant === 'inset' ? 'clay-inset' : 'clay-card',
      hoverable ? 'clay-card-hover cursor-pointer' : '',
      paddingClasses[padding],
      customClass
    ]"
    @click="$emit('click', $event)"
  >
    <slot></slot>
  </div>
</template>

<script setup>
defineProps({
  variant: {
    type: String,
    default: 'default', // 'default' | 'inset'
  },
  hoverable: {
    type: Boolean,
    default: false,
  },
  padding: {
    type: String,
    default: 'md', // 'none' | 'sm' | 'md' | 'lg'
  },
  customClass: {
    type: String,
    default: '',
  }
})

defineEmits(['click'])

const paddingClasses = {
  none: 'p-0',
  sm: 'p-3 md:p-4',
  md: 'p-5 md:p-6',
  lg: 'p-6 md:p-8',
}
</script>
