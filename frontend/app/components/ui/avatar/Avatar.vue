<script setup lang="ts">
defineProps<{
  name: string
}>()

const initials = (name: string) => name
  .split(' ')
  .filter(Boolean)
  .slice(0, 2)
  .map((part) => part[0])
  .join('')
  .toUpperCase()
</script>

<template>
  <span class="inline-flex size-9 shrink-0 items-center justify-center rounded-full bg-primary/15 text-xs font-semibold text-primary">
    {{ initials(name) }}
  </span>
</template>
