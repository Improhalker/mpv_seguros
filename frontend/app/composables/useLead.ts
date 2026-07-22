import type { Lead, LeadInput, LeadInteraction, LeadInteractionInput, PaginatedResponse } from '@/types/lead'
import { toLeadApiPayload } from '@/utils/lead'

export function useLead() {
  const lead = ref<Lead | null>(null)
  const interactions = ref<LeadInteraction[]>([])
  const loading = ref(false)
  const interactionsLoading = ref(false)

  async function fetchLead(id: string): Promise<Lead> {
    loading.value = true

    try {
      lead.value = await $fetch<Lead>(`/api/leads/${id}`)
      return lead.value
    } finally {
      loading.value = false
    }
  }

  async function saveLead(id: string, input: LeadInput): Promise<Lead> {
    lead.value = await $fetch<Lead>(`/api/leads/${id}`, { method: 'PUT', body: toLeadApiPayload(input) })
    return lead.value
  }

  async function fetchInteractions(id: string): Promise<void> {
    interactionsLoading.value = true

    try {
      const response = await $fetch<PaginatedResponse<LeadInteraction>>(`/api/leads/${id}/interactions`)
      interactions.value = response.data
    } finally {
      interactionsLoading.value = false
    }
  }

  async function saveInteraction(id: string, input: LeadInteractionInput, interactionId?: string): Promise<LeadInteraction> {
    const body = {
      type: input.type,
      description: input.description,
      occurred_at: input.occurredAt,
      next_contact_at: input.nextContactAt,
    }
    const path = interactionId ? `/api/leads/${id}/interactions/${interactionId}` : `/api/leads/${id}/interactions`
    const interaction = await $fetch<LeadInteraction>(path, { method: interactionId ? 'PUT' : 'POST', body })
    await fetchInteractions(id)
    return interaction
  }

  async function deleteInteraction(id: string, interactionId: string): Promise<void> {
    await $fetch(`/api/leads/${id}/interactions/${interactionId}`, { method: 'DELETE' })
    await fetchInteractions(id)
  }

  return { lead: readonly(lead), interactions: readonly(interactions), loading: readonly(loading), interactionsLoading: readonly(interactionsLoading), fetchLead, saveLead, fetchInteractions, saveInteraction, deleteInteraction }
}
