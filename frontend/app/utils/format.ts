export function formatDate(value: string): string {
  return new Intl.DateTimeFormat('pt-BR', {
    day: '2-digit',
    month: 'short',
    year: 'numeric',
  }).format(new Date(value))
}

export function formatDateTime(value: string): string {
  return new Intl.DateTimeFormat('pt-BR', {
    day: '2-digit',
    month: 'short',
    hour: '2-digit',
    minute: '2-digit',
  }).format(new Date(value))
}

export function formatPhone(value: string): string {
  const digits = value.replace(/\D/g, '')
  const matches = digits.match(/^(\d{2})(\d{5})(\d{4})$/)

  return matches ? `(${matches[1]}) ${matches[2]}-${matches[3]}` : value
}

export function formatNumber(value: number): string {
  return new Intl.NumberFormat('pt-BR').format(value)
}
