<script setup lang="ts">
import { Plus, RefreshCw } from 'lucide-vue-next'
import type { Lead } from '@/types/lead'

definePageMeta({ layout: 'authenticated', middleware: 'auth', title: 'Leads' })

const { leads, meta, filters, loading, refreshing, error, fetchLeads, updateFilters, deleteLead } = useLeads()
const deleting = ref<string | null>(null)

async function confirmDelete(lead: Lead): Promise<void> {
  if (!window.confirm(`Excluir o lead ${lead.name}? Esta ação remove o lead das listagens.`)) return

  deleting.value = lead.id
  try {
    await deleteLead(lead.id)
    await fetchLeads()
  } finally {
    deleting.value = null
  }
}
</script>

<template>
  <div class="space-y-6">
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end"><div><p class="text-sm font-medium text-primary">Relacionamento comercial</p><h1 class="mt-1 text-2xl font-semibold tracking-tight sm:text-3xl">Leads</h1><p class="mt-2 text-sm text-muted-foreground">Organize oportunidades e priorize os próximos contatos.</p></div><Button as-child><NuxtLink to="/leads/novo"><Plus class="size-4" />Novo lead</NuxtLink></Button></div>
    <LeadFilters :filters="filters" :loading="refreshing" @change="updateFilters" />
    <div v-if="error" class="flex items-center justify-between rounded-lg border border-danger/30 bg-danger/10 p-4 text-sm text-danger" role="alert"><span>{{ error }}</span><Button variant="outline" size="sm" @click="fetchLeads"><RefreshCw class="size-4" />Tentar novamente</Button></div>
    <LeadTable :leads="leads" :loading="loading" @delete="confirmDelete" />
    <div v-if="meta.total > 0" class="flex flex-col items-center justify-between gap-3 text-sm text-muted-foreground sm:flex-row"><span>{{ meta.total }} lead{{ meta.total === 1 ? '' : 's' }} encontrado{{ meta.total === 1 ? '' : 's' }}</span><div class="flex items-center gap-2"><Button variant="outline" size="sm" :disabled="meta.current_page <= 1 || deleting !== null" @click="updateFilters({ page: meta.current_page - 1 })">Anterior</Button><span>Página {{ meta.current_page }} de {{ meta.last_page }}</span><Button variant="outline" size="sm" :disabled="meta.current_page >= meta.last_page || deleting !== null" @click="updateFilters({ page: meta.current_page + 1 })">Próxima</Button></div></div>
  </div>
</template>
