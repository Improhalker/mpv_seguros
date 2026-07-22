import type { LeadStatusTone } from '@/constants/lead-status'

export interface QualificationClassification {
  label: string
  tone: LeadStatusTone
}

export function formatQualificationScore(score: number): string {
  return `${new Intl.NumberFormat('pt-BR', { minimumFractionDigits: 1, maximumFractionDigits: 1 }).format(score)} / 10`
}

export function qualificationClassification(score: number): QualificationClassification {
  if (score < 4) return { label: 'Baixa qualificação', tone: 'danger' }
  if (score < 7) return { label: 'Qualificação média', tone: 'warning' }
  if (score < 8.5) return { label: 'Boa qualificação', tone: 'info' }

  return { label: 'Alta qualificação', tone: 'success' }
}
