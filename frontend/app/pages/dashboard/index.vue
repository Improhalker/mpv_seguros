<script setup lang="ts">
import { CircleAlert, LoaderCircle, Plus, UsersRound } from 'lucide-vue-next'
import { dashboardCopy, dashboardMetricDefinitions, dashboardPeriods } from '@/constants/dashboard'
import type { DashboardMetric } from '@/types/dashboard'
import { formatDateTime } from '@/utils/format'

definePageMeta({
  layout: 'authenticated',
  middleware: 'auth',
  title: 'Dashboard',
})

const { dashboard, filters, loading, refreshing, error, fetchDashboard } = useDashboard()
const metrics = computed<DashboardMetric[]>(() => {
  const dashboardData = dashboard.value

  return dashboardData
    ? dashboardMetricDefinitions.map((definition) => ({ ...definition, value: dashboardData.summary[definition.id] }))
    : []
})
const hasNoLeads = computed(() => dashboard.value?.summary.total_leads === 0)
</script>

<template>
  <div class="space-y-8">
    <section class="flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
      <div>
        <p class="text-sm font-medium text-primary">Visão geral</p>
        <h2 class="mt-1 text-2xl font-semibold tracking-tight sm:text-3xl">{{ dashboardCopy.title }}</h2>
        <p class="mt-2 max-w-2xl text-sm text-muted-foreground sm:text-base">{{ dashboardCopy.subtitle }}</p>
      </div>
      <div class="flex items-center gap-3">
        <label class="sr-only" for="dashboard-period">Período do dashboard</label>
        <select id="dashboard-period" v-model="filters.period" class="h-10 rounded-md border border-input bg-background px-3 text-sm shadow-sm outline-none focus-visible:ring-2 focus-visible:ring-ring" :disabled="loading" aria-label="Selecionar período">
          <option v-for="period in dashboardPeriods" :key="period.value" :value="period.value">{{ period.label }}</option>
        </select>
        <span v-if="refreshing" class="inline-flex items-center gap-2 text-sm text-muted-foreground" role="status"><LoaderCircle class="size-4 animate-spin" />Atualizando</span>
      </div>
    </section>

    <div v-if="error && !dashboard" role="alert" class="rounded-lg border border-danger/30 bg-danger/10 p-5 text-danger">
      <div class="flex items-start gap-3"><CircleAlert class="mt-0.5 size-5" aria-hidden="true" /><div><p class="font-medium">Não foi possível carregar os dados do dashboard.</p><p class="mt-1 text-sm">Verifique sua conexão e tente novamente.</p><Button class="mt-4" variant="outline" @click="fetchDashboard">Tentar novamente</Button></div></div>
    </div>

    <div v-else>
      <div v-if="error" role="alert" class="mb-5 flex items-center justify-between gap-4 rounded-lg border border-warning/30 bg-warning/10 p-4 text-sm text-warning-foreground"><span>Não foi possível atualizar os dados. Exibindo a última informação disponível.</span><Button size="sm" variant="outline" @click="fetchDashboard">Tentar novamente</Button></div>

      <section v-if="dashboard || loading" class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3" aria-label="Métricas principais">
        <MetricCard v-for="metric in dashboard ? metrics : dashboardMetricDefinitions" :key="metric.id" :metric="metric" :loading="loading" :refreshing="refreshing" />
      </section>

      <EmptyState v-if="dashboard && hasNoLeads" title="Você ainda não possui leads cadastrados." description="Cadastre seu primeiro lead para começar a acompanhar resultados e contatos." :icon="UsersRound">
        <Button as-child class="mt-1"><NuxtLink to="/leads/novo"><Plus class="size-4" />Cadastrar lead</NuxtLink></Button>
      </EmptyState>

      <template v-else-if="dashboard">
        <p class="mt-4 text-xs text-muted-foreground">Dados atualizados em {{ formatDateTime(dashboard.meta.generated_at) }}.</p>

        <section class="mt-6 grid gap-6 xl:grid-cols-2">
          <LeadGrowthChart class="xl:col-span-2" :points="dashboard.lead_evolution" :loading="loading" />
          <LeadStatusChart :distribution="dashboard.status_distribution" :loading="loading" />
          <InsuranceDistributionChart :distribution="dashboard.insurance_distribution" :loading="loading" />
        </section>

        <section class="grid gap-6 xl:grid-cols-2">
          <PriorityLeadsList :leads="dashboard.priority_leads" :loading="loading" />
          <UpcomingContactsList :contacts="dashboard.upcoming_contacts" :loading="loading" />
        </section>
      </template>
    </div>
  </div>
</template>
