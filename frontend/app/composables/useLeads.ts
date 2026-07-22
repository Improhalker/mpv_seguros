import { useDebounce } from '@vueuse/core'
import type { Lead, LeadFilters, LeadInput, PaginatedResponse, PaginationMeta } from '@/types/lead'
import { toLeadApiPayload } from '@/utils/lead'

const emptyMeta: PaginationMeta = { current_page: 1, last_page: 1, per_page: 20, total: 0 }

export function useLeads() {
  const route = useRoute()
  const router = useRouter()
  const leads = ref<Lead[]>([])
  const meta = ref<PaginationMeta>({ ...emptyMeta })
  const loading = ref(true)
  const refreshing = ref(false)
  const error = ref<string | null>(null)
  const filters = reactive<LeadFilters>({
    search: typeof route.query.search === 'string' ? route.query.search : '',
    status: typeof route.query.status === 'string' ? route.query.status : '',
    insuranceType: typeof route.query.insurance_type === 'string' ? route.query.insurance_type : '',
    urgency: typeof route.query.urgency === 'string' ? route.query.urgency : '',
    source: typeof route.query.source === 'string' ? route.query.source : '',
    contactPeriod: typeof route.query.contact_period === 'string' ? route.query.contact_period : '',
    minScore: typeof route.query.min_score === 'string' ? route.query.min_score : '',
    maxScore: typeof route.query.max_score === 'string' ? route.query.max_score : '',
    page: Number(route.query.page) || 1,
    perPage: Number(route.query.per_page) || 20,
  })
  const debouncedSearch = useDebounce(toRef(filters, 'search'), 350)
  let requestId = 0
  let controller: AbortController | undefined

  async function fetchLeads(): Promise<void> {
    requestId += 1
    const currentRequest = requestId
    controller?.abort()
    controller = new AbortController()
    error.value = null

    if (leads.value.length === 0) {
      loading.value = true
    } else {
      refreshing.value = true
    }

    try {
      const response = await $fetch<PaginatedResponse<Lead>>('/api/leads', {
        query: {
          search: debouncedSearch.value || undefined,
          status: filters.status || undefined,
          insurance_type: filters.insuranceType || undefined,
          urgency: filters.urgency || undefined,
          source: filters.source || undefined,
          contact_period: filters.contactPeriod || undefined,
          min_score: filters.minScore || undefined,
          max_score: filters.maxScore || undefined,
          page: filters.page,
          per_page: filters.perPage,
        },
        signal: controller.signal,
      })

      if (currentRequest === requestId) {
        leads.value = response.data
        meta.value = response.meta
      }
    } catch (fetchError) {
      if (currentRequest === requestId && !(fetchError instanceof DOMException && fetchError.name === 'AbortError')) {
        error.value = 'Não foi possível carregar os leads.'
      }
    } finally {
      if (currentRequest === requestId) {
        loading.value = false
        refreshing.value = false
      }
    }
  }

  function updateFilters(next: Partial<LeadFilters>): void {
    Object.assign(filters, next)

    if (!Object.prototype.hasOwnProperty.call(next, 'page')) {
      filters.page = 1
    }
  }

  function syncUrl(): void {
    router.replace({
      query: {
        search: filters.search || undefined,
        status: filters.status || undefined,
        insurance_type: filters.insuranceType || undefined,
        urgency: filters.urgency || undefined,
        source: filters.source || undefined,
        contact_period: filters.contactPeriod || undefined,
        min_score: filters.minScore || undefined,
        max_score: filters.maxScore || undefined,
        page: filters.page > 1 ? String(filters.page) : undefined,
        per_page: filters.perPage !== 20 ? String(filters.perPage) : undefined,
      },
    })
  }

  async function createLead(input: LeadInput): Promise<Lead> {
    return $fetch<Lead>('/api/leads', { method: 'POST', body: toLeadApiPayload(input) })
  }

  async function updateLead(id: string, input: LeadInput): Promise<Lead> {
    return $fetch<Lead>(`/api/leads/${id}`, { method: 'PUT', body: toLeadApiPayload(input) })
  }

  async function deleteLead(id: string): Promise<void> {
    await $fetch(`/api/leads/${id}`, { method: 'DELETE' })
  }

  watch([debouncedSearch, () => filters.status, () => filters.insuranceType, () => filters.urgency, () => filters.source, () => filters.contactPeriod, () => filters.minScore, () => filters.maxScore, () => filters.page, () => filters.perPage], () => {
    syncUrl()
    void fetchLeads()
  })

  onMounted(() => {
    void fetchLeads()
  })

  return { leads: readonly(leads), meta: readonly(meta), filters, loading: readonly(loading), refreshing: readonly(refreshing), error: readonly(error), fetchLeads, updateFilters, createLead, updateLead, deleteLead }
}
