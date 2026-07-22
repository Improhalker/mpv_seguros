<script setup lang="ts">
defineOptions({ inheritAttrs: false })

const modelValue = defineModel<string | number | null>({ default: '' })
</script>

<template>
  <input
    v-bind="$attrs"
    :value="modelValue ?? ''"
    class="flex h-10 w-full rounded-lg border bg-card px-3 py-2 text-sm outline-none placeholder:text-muted-foreground focus-visible:ring-2 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50"
    @input="modelValue = ($event.target as HTMLInputElement).value"
  >
</template>
