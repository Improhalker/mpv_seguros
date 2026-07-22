<script setup lang="ts">
import { CalendarClock, ChevronRight } from 'lucide-vue-next'
import type { DashboardLead } from '@/types/dashboard'
import { insuranceTypes } from '@/constants/lead-options'
import { formatDateTime, formatPhone } from '@/utils/format'

defineProps<{
  contacts: readonly DashboardLead[]
  loading?: boolean
}>()

const insuranceLabels = Object.fromEntries(insuranceTypes.map((item) => [item.value, item.label]))
</script>

<template>
  <Card>
    <CardHeader>
      <div class="flex items-center justify-between gap-4">
        <div><h2 class="text-base font-semibold">Próximos contatos</h2><p class="text-sm text-muted-foreground">Follow-ups futuros já agendados.</p></div>
        <CalendarClock class="size-5 text-muted-foreground" aria-hidden="true" />
      </div>
    </CardHeader>
    <CardContent>
      <div v-if="loading" class="space-y-3" aria-busy="true"><Skeleton v-for="index in 4" :key="index" class="h-14 w-full" /></div>
      <EmptyState v-else-if="contacts.length === 0" title="Nenhum contato agendado." description="Defina o próximo contato de um lead para vê-lo aqui." :icon="CalendarClock" />
      <ul v-else class="divide-y">
        <li v-for="contact in contacts" :key="contact.id" class="flex items-center gap-3 py-3 first:pt-0 last:pb-0">
          <Avatar :name="contact.name" />
          <div class="min-w-0 flex-1"><p class="truncate text-sm font-medium">{{ contact.name }}</p><p class="mt-0.5 truncate text-xs text-muted-foreground">{{ insuranceLabels[contact.insurance_type] ?? contact.insurance_type }} · {{ formatPhone(contact.phone) }}</p></div>
          <div class="text-right"><p class="text-sm font-medium">{{ contact.next_contact_at ? formatDateTime(contact.next_contact_at) : '—' }}</p><p class="text-xs text-muted-foreground">Próximo contato</p></div>
          <Button as-child variant="ghost" size="icon"><NuxtLink :to="`/leads/${contact.id}`" :aria-label="`Abrir lead ${contact.name}`"><ChevronRight class="size-4" /></NuxtLink></Button>
        </li>
      </ul>
    </CardContent>
  </Card>
</template>
