<script setup lang="ts">
import { ArcElement, Chart as ChartJS, Legend, Tooltip, type ChartData, type ChartOptions } from 'chart.js'
import { ChartPie } from 'lucide-vue-next'
import { Doughnut } from 'vue-chartjs'
import type { InsuranceDistribution } from '@/types/dashboard'

ChartJS.register(ArcElement, Legend, Tooltip)

const props = defineProps<{
  distribution: readonly InsuranceDistribution[]
  loading?: boolean
}>()

const { colors, isReady } = useChartPalette()
const chartData = computed<ChartData<'doughnut'>>(() => ({
  labels: props.distribution.map((item) => item.label),
  datasets: [{ data: props.distribution.map((item) => item.count), backgroundColor: props.distribution.map((_, index) => colors.value[index % colors.value.length]), borderColor: 'transparent', borderWidth: 5, hoverOffset: 5 }],
}))
const chartOptions: ChartOptions<'doughnut'> = {
  responsive: true,
  maintainAspectRatio: false,
  cutout: '68%',
  plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, boxHeight: 10, color: '#64748b', padding: 14, usePointStyle: true } } },
}
</script>

<template>
  <ChartCard title="Leads por tipo de seguro" description="Tipos de seguro dos leads criados no período.">
    <div class="h-72" :aria-busy="loading">
      <Skeleton v-if="loading" class="h-full w-full" />
      <EmptyState v-else-if="distribution.length === 0" title="Nenhum dado disponível neste período." description="Cadastre novos leads ou selecione outro período para visualizar este gráfico." :icon="ChartPie" />
      <ClientOnly v-else>
        <Doughnut v-if="isReady" :data="chartData" :options="chartOptions" aria-label="Gráfico de leads por tipo de seguro" />
        <template #fallback><Skeleton class="h-full w-full" /></template>
      </ClientOnly>
    </div>
  </ChartCard>
</template>
