<script setup lang="ts">
import type { DashboardMetric, DashboardMetricDefinition } from '@/types/dashboard'
import { formatNumber } from '@/utils/format'

defineProps<{
  metric: DashboardMetric | DashboardMetricDefinition
  loading?: boolean
  refreshing?: boolean
}>()

const toneClasses = {
  primary: 'bg-primary/15 text-primary',
  success: 'bg-success/15 text-success-foreground',
  warning: 'bg-warning/20 text-warning-foreground',
  danger: 'bg-danger/15 text-danger',
} as const
</script>

<template>
  <Card>
    <CardContent class="p-5">
      <div v-if="loading" class="space-y-3">
        <Skeleton class="h-4 w-28" />
        <Skeleton class="h-8 w-20" />
        <Skeleton class="h-4 w-36" />
      </div>
      <template v-else>
        <div class="flex items-start justify-between gap-4">
          <div class="space-y-1">
            <p class="text-sm font-medium text-muted-foreground">{{ metric.title }}</p>
            <p class="text-3xl font-semibold tracking-tight">{{ formatNumber('value' in metric ? metric.value : 0) }}</p>
          </div>
          <span class="inline-flex size-10 items-center justify-center rounded-lg" :class="toneClasses[metric.tone]">
            <component :is="metric.icon" class="size-5" aria-hidden="true" />
          </span>
        </div>
        <div class="mt-4 flex items-center gap-2 text-xs text-muted-foreground">
          <span v-if="refreshing" class="size-2 animate-pulse rounded-full bg-primary" aria-label="Atualizando dados" />
          <span>{{ metric.description }}</span>
        </div>
      </template>
    </CardContent>
  </Card>
</template>
