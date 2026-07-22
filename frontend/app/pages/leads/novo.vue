<script setup lang="ts">
import type { LeadInput } from '@/types/lead'

definePageMeta({ layout: 'authenticated', middleware: 'auth', title: 'Novo lead' })

const { createLead } = useLeads()
const submitting = ref(false)
const apiErrors = ref<Record<string, string[]>>({})
const message = ref<string | null>(null)

async function saveLead(input: LeadInput): Promise<void> {
  submitting.value = true
  apiErrors.value = {}
  message.value = null

  try {
    const lead = await createLead(input)
    await navigateTo(`/leads/${lead.id}`)
  } catch (error) {
    const data = error as { data?: { errors?: Record<string, string[]> } }
    apiErrors.value = data.data?.errors ?? {}
    message.value = 'Não foi possível salvar o lead. Revise os campos destacados.'
  } finally {
    submitting.value = false
  }
}
</script>

<template><div class="space-y-6"><div><NuxtLink to="/leads" class="text-sm text-primary hover:underline">← Voltar para leads</NuxtLink><h1 class="mt-3 text-2xl font-semibold tracking-tight">Novo lead</h1><p class="mt-1 text-sm text-muted-foreground">Comece com o básico e complemente os dados quando necessário.</p></div><p v-if="message" class="rounded-lg border border-danger/30 bg-danger/10 p-4 text-sm text-danger" role="alert">{{ message }}</p><LeadForm :submitting="submitting" :api-errors="apiErrors" submit-label="Cadastrar lead" @submit="saveLead" /></div></template>
