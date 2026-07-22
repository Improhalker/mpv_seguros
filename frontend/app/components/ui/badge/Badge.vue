<script setup lang="ts">
withDefaults(defineProps<{
  tone?: 'neutral' | 'info' | 'primary' | 'success' | 'warning' | 'danger'
}>(), {
  tone: 'neutral',
})
</script>

<template>
  <span
    class="inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium"
    :class="{
      neutral: 'bg-muted text-muted-foreground',
      info: 'bg-info/15 text-info-foreground',
      primary: 'bg-primary/15 text-primary',
      success: 'bg-success/15 text-success-foreground',
      warning: 'bg-warning/20 text-warning-foreground',
      danger: 'bg-danger/15 text-danger',
    }[tone]"
  >
    <slot />
  </span>
</template>
