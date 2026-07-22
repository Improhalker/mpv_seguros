<script setup lang="ts">
import type { LeadInput } from '@/types/lead'

definePageMeta({ layout: 'authenticated', middleware: 'auth', title: 'Editar lead' })

const route = useRoute()
const id = computed(() => String(route.params.id))
const { lead, loading, fetchLead, saveLead } = useLead()
const submitting = ref(false)
const apiErrors = ref<Record<string, string[]>>({})
const message = ref<string | null>(null)

onMounted(() => { void fetchLead(id.value) })

async function save(input: LeadInput): Promise<void> {
  submitting.value = true
  apiErrors.value = {}
  message.value = null
  try { await saveLead(id.value, input); await navigateTo(`/leads/${id.value}`) } catch (error) { const data = error as { data?: { errors?: Record<string, string[]> } }; apiErrors.value = data.data?.errors ?? {}; message.value = 'Não foi possível atualizar o lead.' } finally { submitting.value = false }
}
</script>

<template><div class="space-y-6"><div><NuxtLink :to="`/leads/${id}`" class="text-sm text-primary hover:underline">← Voltar ao lead</NuxtLink><h1 class="mt-3 text-2xl font-semibold tracking-tight">Editar lead</h1></div><div v-if="loading" class="space-y-4"><Skeleton class="h-44 w-full" /><Skeleton class="h-64 w-full" /><Skeleton class="h-52 w-full" /></div><p v-else-if="message" class="rounded-lg border border-danger/30 bg-danger/10 p-4 text-sm text-danger" role="alert">{{ message }}</p><LeadForm v-else-if="lead" :initial-lead="lead" :submitting="submitting" :api-errors="apiErrors" submit-label="Salvar alterações" @submit="save" /></div></template>
