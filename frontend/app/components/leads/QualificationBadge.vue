<script setup lang="ts">
import { formatQualificationScore, qualificationClassification } from '@/utils/qualification'

const props = defineProps<{ score: number | null }>()
const classification = computed(() => props.score === null ? null : qualificationClassification(props.score))
</script>

<template>
  <span v-if="score === null" class="text-muted-foreground">Não avaliada</span>
  <div v-else class="space-y-1">
    <p class="font-medium tabular-nums">{{ formatQualificationScore(score) }}</p>
    <Badge :tone="classification?.tone">{{ classification?.label }}</Badge>
  </div>
</template>
