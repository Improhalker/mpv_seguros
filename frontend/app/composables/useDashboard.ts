import type { DashboardData, DashboardFilters } from '@/types/dashboard'

export function useDashboard() {
  const route = useRoute()
  const router = useRouter()
  const dashboard = ref<DashboardData | null>(null)
  const loading = ref(true)
  const refreshing = ref(false)
  const error = ref<string | null>(null)
  const filters = reactive<DashboardFilters>({
    period: isDashboardPeriod(route.query.period) ? route.query.period : '30',
  })
  let requestId = 0
  let controller: AbortController | undefined

  async function fetchDashboard(): Promise<void> {
    requestId += 1
    const currentRequest = requestId
    controller?.abort()
    controller = new AbortController()
    error.value = null

    if (dashboard.value === null) {
      loading.value = true
    } else {
      refreshing.value = true
    }

    try {
      const response = await $fetch<DashboardData>('/api/dashboard', {
        query: { period: filters.period },
        signal: controller.signal,
      })

      if (currentRequest === requestId) {
        dashboard.value = response
      }
    } catch (fetchError) {
      if (currentRequest === requestId && !(fetchError instanceof DOMException && fetchError.name === 'AbortError')) {
        error.value = 'Não foi possível carregar os dados do dashboard.'
      }
    } finally {
      if (currentRequest === requestId) {
        loading.value = false
        refreshing.value = false
      }
    }
  }

  function updatePeriod(period: DashboardFilters['period']): void {
    filters.period = period
  }

  watch(() => filters.period, () => {
    router.replace({ query: { ...route.query, period: filters.period === '30' ? undefined : filters.period } })
    void fetchDashboard()
  })

  onMounted(() => {
    void fetchDashboard()
  })

  return { dashboard: readonly(dashboard), filters, loading: readonly(loading), refreshing: readonly(refreshing), error: readonly(error), fetchDashboard, updatePeriod }
}

function isDashboardPeriod(value: unknown): value is DashboardFilters['period'] {
  return typeof value === 'string' && ['7', '30', '90', '365'].includes(value)
}
