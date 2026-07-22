<script setup lang="ts">
import { ArrowUpRight, ListChecks } from 'lucide-vue-next'
import type { DashboardLead } from '@/types/dashboard'
import { insuranceTypes } from '@/constants/lead-options'
import { formatDateTime, formatPhone } from '@/utils/format'

defineProps<{
  leads: readonly DashboardLead[]
  loading?: boolean
}>()

const insuranceLabels = Object.fromEntries(insuranceTypes.map((item) => [item.value, item.label]))
</script>

<template>
  <Card>
    <CardHeader>
      <div class="flex items-center justify-between gap-4">
        <div>
          <h2 class="text-base font-semibold">Leads prioritários</h2>
          <p class="text-sm text-muted-foreground">Acompanhe primeiro quem precisa de atenção.</p>
        </div>
        <ListChecks class="size-5 text-muted-foreground" aria-hidden="true" />
      </div>
    </CardHeader>
    <CardContent>
      <div v-if="loading" class="space-y-3" aria-busy="true"><Skeleton v-for="index in 4" :key="index" class="h-14 w-full" /></div>
      <EmptyState v-else-if="leads.length === 0" title="Nenhum lead prioritário no momento." description="Os leads que precisarem de atenção aparecerão aqui." :icon="ListChecks" />
      <ul v-else class="divide-y">
        <li v-for="lead in leads" :key="lead.id" class="flex items-center gap-3 py-3 first:pt-0 last:pb-0">
          <Avatar :name="lead.name" />
          <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-center gap-2"><p class="truncate text-sm font-medium">{{ lead.name }}</p><LeadStatusBadge :status="lead.status" /></div>
            <p class="mt-0.5 truncate text-xs text-muted-foreground">{{ insuranceLabels[lead.insurance_type] ?? lead.insurance_type }} · {{ formatPhone(lead.phone) }}</p>
            <p v-if="lead.next_contact_at" class="mt-0.5 text-xs text-muted-foreground">Contato: {{ formatDateTime(lead.next_contact_at) }}</p>
          </div>
          <QualificationBadge :score="lead.qualification_score" />
          <Button as-child variant="ghost" size="icon"><NuxtLink :to="`/leads/${lead.id}`" :aria-label="`Abrir lead ${lead.name}`"><ArrowUpRight class="size-4" /></NuxtLink></Button>
        </li>
      </ul>
    </CardContent>
  </Card>
</template>
