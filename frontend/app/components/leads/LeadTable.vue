<script setup lang="ts">
import { Eye, MessageSquarePlus, Pencil, Trash2 } from 'lucide-vue-next'
import type { Lead } from '@/types/lead'
import { formatDate, formatPhone } from '@/utils/format'

defineProps<{ leads: readonly Lead[], loading?: boolean }>()
const emit = defineEmits<{ delete: [lead: Lead] }>()

const insuranceLabels: Record<string, string> = { auto: 'Auto', residencial: 'Residencial', vida: 'Vida', saude: 'Saúde', empresarial: 'Empresarial', viagem: 'Viagem', previdencia: 'Previdência', outro: 'Outro' }
const urgencyTone = { baixa: 'neutral', media: 'warning', alta: 'danger' } as const
</script>

<template>
  <Card>
    <CardContent class="p-0">
      <div v-if="loading" class="space-y-3 p-5"><Skeleton v-for="index in 6" :key="index" class="h-12 w-full" /></div>
      <div v-else-if="leads.length === 0" class="p-10 text-center"><p class="font-medium">Nenhum lead encontrado</p><p class="mt-1 text-sm text-muted-foreground">Ajuste os filtros ou cadastre uma nova oportunidade.</p></div>
      <div v-else class="overflow-x-auto"><table class="w-full min-w-[1120px] text-left text-sm"><thead class="border-b bg-muted/35 text-xs uppercase tracking-wide text-muted-foreground"><tr><th class="p-4 font-medium">Lead</th><th class="p-4 font-medium">Seguro</th><th class="p-4 font-medium">Status</th><th class="p-4 font-medium">Urgência</th><th class="p-4 font-medium">Nota de qualificação</th><th class="p-4 font-medium">Próximo contato</th><th class="p-4 font-medium">Corretor</th><th class="p-4 font-medium">Cadastro</th><th class="p-4 text-right font-medium">Ações</th></tr></thead><tbody class="divide-y"><tr v-for="lead in leads" :key="lead.id" class="hover:bg-muted/35"><td class="p-4"><p class="font-medium">{{ lead.name }}</p><p class="mt-0.5 text-xs text-muted-foreground">{{ formatPhone(lead.phone) }}</p></td><td class="p-4 text-muted-foreground">{{ insuranceLabels[lead.insuranceType] ?? lead.insuranceType }}</td><td class="p-4"><LeadStatusBadge :status="lead.status" /></td><td class="p-4"><Badge v-if="lead.urgency" :tone="urgencyTone[lead.urgency]">{{ lead.urgency }}</Badge><span v-else class="text-muted-foreground">—</span></td><td class="p-4"><QualificationBadge :score="lead.qualificationScore" /></td><td class="p-4 text-muted-foreground">{{ lead.nextContactAt ? formatDate(lead.nextContactAt) : '—' }}</td><td class="p-4 text-muted-foreground">{{ lead.assignedUser?.name ?? '—' }}</td><td class="p-4 text-muted-foreground">{{ formatDate(lead.createdAt) }}</td><td class="p-4"><div class="flex justify-end gap-1"><Button as-child variant="ghost" size="icon"><NuxtLink :to="`/leads/${lead.id}`" :aria-label="`Visualizar ${lead.name}`"><Eye class="size-4" /></NuxtLink></Button><Button as-child variant="ghost" size="icon"><NuxtLink :to="`/leads/${lead.id}/editar`" :aria-label="`Editar ${lead.name}`"><Pencil class="size-4" /></NuxtLink></Button><Button as-child variant="ghost" size="icon"><NuxtLink :to="`/leads/${lead.id}#interacoes`" :aria-label="`Registrar interação com ${lead.name}`"><MessageSquarePlus class="size-4" /></NuxtLink></Button><Button variant="ghost" size="icon" :aria-label="`Excluir ${lead.name}`" @click="emit('delete', lead)"><Trash2 class="size-4 text-danger" /></Button></div></td></tr></tbody></table></div>
    </CardContent>
  </Card>
</template>
