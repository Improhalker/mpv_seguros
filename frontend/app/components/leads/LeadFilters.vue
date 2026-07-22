<script setup lang="ts">
import type { LeadFilters } from '@/types/lead'
import { insuranceTypes, leadSources } from '@/constants/lead-options'

defineProps<{ filters: LeadFilters, loading?: boolean }>()
const emit = defineEmits<{ change: [filters: Partial<LeadFilters>] }>()
</script>

<template>
  <Card>
    <CardContent class="grid gap-3 p-4 md:grid-cols-2 xl:grid-cols-6">
      <Input :model-value="filters.search" class="xl:col-span-2" placeholder="Buscar nome, telefone ou e-mail" aria-label="Buscar leads" @update:model-value="emit('change', { search: String($event) })" />
      <select :value="filters.status" class="h-10 rounded-lg border bg-card px-3 text-sm" aria-label="Filtrar por status" @change="emit('change', { status: ($event.target as HTMLSelectElement).value })"><option value="">Todos os status</option><option value="novo">Novo</option><option value="contatado">Contatado</option><option value="em_negociacao">Em negociação</option><option value="aguardando_cliente">Aguardando cliente</option><option value="fechado">Fechado</option><option value="perdido">Perdido</option></select>
      <select :value="filters.insuranceType" class="h-10 rounded-lg border bg-card px-3 text-sm" aria-label="Filtrar por seguro" @change="emit('change', { insuranceType: ($event.target as HTMLSelectElement).value })"><option value="">Todos os seguros</option><option v-for="option in insuranceTypes" :key="option.value" :value="option.value">{{ option.label }}</option></select>
      <select :value="filters.urgency" class="h-10 rounded-lg border bg-card px-3 text-sm" aria-label="Filtrar por urgência" @change="emit('change', { urgency: ($event.target as HTMLSelectElement).value })"><option value="">Todas as urgências</option><option value="alta">Alta</option><option value="media">Média</option><option value="baixa">Baixa</option></select>
      <select :value="filters.contactPeriod" class="h-10 rounded-lg border bg-card px-3 text-sm" aria-label="Filtrar por contato" @change="emit('change', { contactPeriod: ($event.target as HTMLSelectElement).value })"><option value="">Todos os contatos</option><option value="overdue">Atrasados</option><option value="today">Para hoje</option><option value="upcoming">Próximos</option></select>
      <select :value="filters.source" class="h-10 rounded-lg border bg-card px-3 text-sm" aria-label="Filtrar por origem" @change="emit('change', { source: ($event.target as HTMLSelectElement).value })"><option value="">Todas as origens</option><option v-for="option in leadSources" :key="option.value" :value="option.value">{{ option.label }}</option></select>
      <Input :model-value="filters.minScore" type="number" min="0" max="10" step="0.1" inputmode="decimal" placeholder="Nota mín." aria-label="Filtrar por nota mínima" @update:model-value="emit('change', { minScore: String($event) })" />
      <Input :model-value="filters.maxScore" type="number" min="0" max="10" step="0.1" inputmode="decimal" placeholder="Nota máx." aria-label="Filtrar por nota máxima" @update:model-value="emit('change', { maxScore: String($event) })" />
    </CardContent>
  </Card>
</template>
