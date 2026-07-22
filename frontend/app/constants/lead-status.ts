import type { LeadStatus } from '@/types/lead'

export type LeadStatusTone = 'info' | 'warning' | 'primary' | 'success' | 'danger'

export interface LeadStatusConfig {
  label: string
  tone: LeadStatusTone
}

export const leadStatusConfig: Record<LeadStatus, LeadStatusConfig> = {
  novo: { label: 'Novo', tone: 'info' },
  contatado: { label: 'Contatado', tone: 'primary' },
  em_negociacao: { label: 'Em negociação', tone: 'warning' },
  aguardando_cliente: { label: 'Aguardando cliente', tone: 'warning' },
  fechado: { label: 'Fechado', tone: 'success' },
  perdido: { label: 'Perdido', tone: 'danger' },
}
