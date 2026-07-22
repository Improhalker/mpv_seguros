/**
 * Adiciona opacidade a uma cor oklch definida pelos tokens do tema.
 */
export function withOpacity(color: string, opacity: number): string {
  const normalizedOpacity = Math.min(1, Math.max(0, opacity))

  if (color.startsWith('oklch(') && color.endsWith(')') && !color.includes('/')) {
    return `${color.slice(0, -1)} / ${normalizedOpacity})`
  }

  return color
}
