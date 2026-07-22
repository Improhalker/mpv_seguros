import { BadgeDollarSign, CircleAlert, CircleCheckBig, ContactRound, Users, UserRoundX } from 'lucide-vue-next'
import type { DashboardMetricDefinition } from '@/types/dashboard'

export const dashboardCopy = {
  title: 'Dashboard',
  subtitle: 'Acompanhe suas oportunidades e priorize seus próximos contatos.',
} as const

export const dashboardPeriods = [
  { value: '7', label: 'Últimos 7 dias' },
  { value: '30', label: 'Últimos 30 dias' },
  { value: '90', label: 'Últimos 90 dias' },
  { value: '365', label: 'Últimos 12 meses' },
] as const

export const dashboardMetricDefinitions: DashboardMetricDefinition[] = [
  { id: 'total_leads', title: 'Total de leads', description: 'Todos os leads cadastrados', tone: 'primary', icon: Users },
  { id: 'created_today', title: 'Cadastrados hoje', description: 'Cadastros realizados hoje', tone: 'success', icon: ContactRound },
  { id: 'closed_leads', title: 'Fechados', description: 'Negócios fechados no período', tone: 'success', icon: CircleCheckBig },
  { id: 'lost_leads', title: 'Perdidos', description: 'Negócios perdidos no período', tone: 'danger', icon: UserRoundX },
  { id: 'contacts_today', title: 'Contatos para hoje', description: 'Follow-ups previstos para hoje', tone: 'primary', icon: BadgeDollarSign },
  { id: 'overdue_contacts', title: 'Contatos atrasados', description: 'Precisam da sua atenção', tone: 'warning', icon: CircleAlert },
]
