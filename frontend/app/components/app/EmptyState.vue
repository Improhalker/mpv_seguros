<script setup lang="ts">
import type { Component } from 'vue'

withDefaults(defineProps<{
  title: string
  description: string
  icon?: Component
}>(), {
  icon: undefined,
})
</script>

<template>
  <div class="flex min-h-52 flex-col items-center justify-center gap-3 px-4 text-center">
    <component :is="icon" v-if="icon" class="size-8 text-muted-foreground" aria-hidden="true" />
    <div>
      <p class="font-medium">{{ title }}</p>
      <p class="mt-1 max-w-sm text-sm text-muted-foreground">{{ description }}</p>
    </div>
    <slot />
  </div>
</template>
