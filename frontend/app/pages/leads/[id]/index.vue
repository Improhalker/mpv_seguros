<script setup lang="ts">
import { Pencil } from 'lucide-vue-next'
import type { LeadInteraction, LeadInteractionInput } from '@/types/lead'
import { formatDate, formatDateTime, formatPhone } from '@/utils/format'
import { formatQualificationScore, qualificationClassification } from '@/utils/qualification'

definePageMeta({ layout: 'authenticated', middleware: 'auth', title: 'Lead' })

const route = useRoute()
const id = computed(() => String(route.params.id))
const { lead, interactions, loading, interactionsLoading, fetchLead, fetchInteractions, saveInteraction, deleteInteraction } = useLead()
const interactionSaving = ref(false)

onMounted(() => { void fetchLead(id.value); void fetchInteractions(id.value) })

async function save(input: LeadInteractionInput, interactionId?: string): Promise<void> {
  interactionSaving.value = true
  try { await saveInteraction(id.value, input, interactionId); await fetchLead(id.value) } finally { interactionSaving.value = false }
}

async function remove(interaction: LeadInteraction): Promise<void> {
  if (!window.confirm('Excluir esta interação?')) return
  await deleteInteraction(id.value, interaction.id)
  await fetchLead(id.value)
}
</script>

<template><div class="space-y-6"><div v-if="loading" class="space-y-4"><Skeleton class="h-44 w-full" /><Skeleton class="h-80 w-full" /></div><template v-else-if="lead"><div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start"><div><NuxtLink to="/leads" class="text-sm text-primary hover:underline">← Voltar para leads</NuxtLink><h1 class="mt-3 text-2xl font-semibold tracking-tight">{{ lead.name }}</h1><p class="mt-1 text-sm text-muted-foreground">{{ formatPhone(lead.phone) }} <span v-if="lead.email">· {{ lead.email }}</span></p></div><Button as-child variant="outline"><NuxtLink :to="`/leads/${lead.id}/editar`"><Pencil class="size-4" />Editar</NuxtLink></Button></div><Card><CardContent class="grid gap-5 p-5 sm:grid-cols-2 lg:grid-cols-4"><div><p class="text-xs uppercase text-muted-foreground">Seguro</p><p class="mt-1 font-medium">{{ lead.insuranceType }}</p></div><div><p class="text-xs uppercase text-muted-foreground">Status</p><div class="mt-1"><LeadStatusBadge :status="lead.status" /></div></div><div><p class="text-xs uppercase text-muted-foreground">Nota de qualificação</p><div v-if="lead.qualificationScore !== null" class="mt-1"><p class="font-medium tabular-nums">{{ formatQualificationScore(lead.qualificationScore) }}</p><Badge :tone="qualificationClassification(lead.qualificationScore).tone">{{ qualificationClassification(lead.qualificationScore).label }}</Badge></div><p v-else class="mt-1 font-medium">Não avaliada</p></div><div><p class="text-xs uppercase text-muted-foreground">Próximo contato</p><p class="mt-1 font-medium">{{ lead.nextContactAt ? formatDateTime(lead.nextContactAt) : 'Não agendado' }}</p></div><div><p class="text-xs uppercase text-muted-foreground">Origem</p><p class="mt-1 font-medium">{{ lead.source ?? 'Não informada' }}</p></div><div><p class="text-xs uppercase text-muted-foreground">Cadastro</p><p class="mt-1 font-medium">{{ formatDate(lead.createdAt) }}</p></div><div class="sm:col-span-2"><p class="text-xs uppercase text-muted-foreground">Observações</p><p class="mt-1 whitespace-pre-wrap text-sm">{{ lead.generalNotes ?? 'Sem observações.' }}</p></div></CardContent></Card><LeadInteractions :interactions="interactions" :loading="interactionsLoading" :saving="interactionSaving" @save="save" @delete="remove" /></template><div v-else class="rounded-lg border border-danger/30 bg-danger/10 p-4 text-sm text-danger">Não foi possível encontrar este lead.</div></div></template>
