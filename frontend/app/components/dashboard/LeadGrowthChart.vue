<script setup lang="ts">
import { CategoryScale, Chart as ChartJS, Legend, LineElement, LinearScale, PointElement, Tooltip, type ChartData, type ChartOptions } from 'chart.js'
import { ChartNoAxesCombined } from 'lucide-vue-next'
import { Line } from 'vue-chartjs'
import type { DashboardEvolutionPoint } from '@/types/dashboard'
import { formatDate } from '@/utils/format'

ChartJS.register(CategoryScale, Legend, LineElement, LinearScale, PointElement, Tooltip)

const props = defineProps<{
  points: readonly DashboardEvolutionPoint[]
  loading?: boolean
}>()

const { colors, isReady } = useChartPalette()
const hasData = computed(() => props.points.some((point) => point.created > 0 || point.closed > 0 || point.lost > 0))
const chartData = computed<ChartData<'line'>>(() => ({
  labels: props.points.map((point) => formatDate(`${point.date}T12:00:00-03:00`)),
  datasets: [
    { label: 'Cadastrados', data: props.points.map((point) => point.created), borderColor: colors.value[0], backgroundColor: colors.value[0], borderWidth: 2, pointRadius: 2, tension: 0.3 },
    { label: 'Fechados', data: props.points.map((point) => point.closed), borderColor: colors.value[1], backgroundColor: colors.value[1], borderWidth: 2, pointRadius: 2, tension: 0.3 },
    { label: 'Perdidos', data: props.points.map((point) => point.lost), borderColor: colors.value[2], backgroundColor: colors.value[2], borderWidth: 2, pointRadius: 2, tension: 0.3 },
  ],
}))
const chartOptions: ChartOptions<'line'> = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, boxHeight: 10, color: '#64748b', padding: 14, usePointStyle: true } } },
  scales: { x: { grid: { display: false }, ticks: { color: '#64748b', maxRotation: 0 } }, y: { beginAtZero: true, border: { display: false }, ticks: { color: '#64748b', precision: 0 } } },
}
</script>

<template>
  <ChartCard title="Evolução dos leads" description="Cadastros, fechamentos e perdas no período selecionado.">
    <div class="h-72" :aria-busy="loading">
      <Skeleton v-if="loading" class="h-full w-full" />
      <EmptyState v-else-if="!hasData" title="Nenhum dado disponível neste período." description="Cadastre novos leads ou selecione outro período para visualizar este gráfico." :icon="ChartNoAxesCombined" />
      <ClientOnly v-else>
        <Line v-if="isReady" :data="chartData" :options="chartOptions" aria-label="Gráfico de evolução dos leads" />
        <template #fallback><Skeleton class="h-full w-full" /></template>
      </ClientOnly>
    </div>
  </ChartCard>
</template>
