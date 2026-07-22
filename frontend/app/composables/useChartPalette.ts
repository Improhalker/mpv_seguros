export function useChartPalette() {
  const colors = ref<string[]>([])

  onMounted(() => {
    const styles = getComputedStyle(document.documentElement)
    colors.value = ['--chart-1', '--chart-2', '--chart-3', '--chart-4', '--chart-5'].map((token) => styles.getPropertyValue(token).trim())
  })

  return {
    colors: readonly(colors),
    isReady: computed(() => colors.value.length > 0),
  }
}
