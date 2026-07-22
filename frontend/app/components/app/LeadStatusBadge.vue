<script setup lang="ts">
import type { LeadStatus } from '@/types/lead'
import { leadStatusConfig } from '@/constants/lead-status'

const props = defineProps<{
  status: LeadStatus
}>()

const status = computed(() => leadStatusConfig[props.status])
</script>

<template>
  <Badge :tone="status.tone">{{ status.label }}</Badge>
</template>
