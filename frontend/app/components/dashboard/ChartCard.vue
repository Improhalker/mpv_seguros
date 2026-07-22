<script setup lang="ts">
defineProps<{
  title: string
  description: string
}>()
</script>

<template>
  <Card class="h-full">
    <CardHeader>
      <h2 class="text-base font-semibold">{{ title }}</h2>
      <p class="text-sm text-muted-foreground">{{ description }}</p>
    </CardHeader>
    <CardContent>
      <slot />
    </CardContent>
  </Card>
</template>
