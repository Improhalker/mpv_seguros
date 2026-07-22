import type { Component } from 'vue'
import type { LeadStatus, LeadUrgency } from './lead'

export type MetricTone = 'primary' | 'success' | 'warning' | 'danger'

export interface DashboardSummary {
  total_leads: number
  created_today: number
  closed_leads: number
  lost_leads: number
  contacts_today: number
  overdue_contacts: number
}

export interface DashboardMetricDefinition {
  id: keyof DashboardSummary
  title: string
  description: string
  tone: MetricTone
  icon: Component
}

export interface DashboardMetric extends DashboardMetricDefinition {
  value: number
}

export interface DashboardStatusDistribution {
  status: LeadStatus
  label: string
  count: number
}

export interface DashboardEvolutionPoint {
  date: string
  created: number
  closed: number
  lost: number
}

export interface InsuranceDistribution {
  insurance_type: string
  label: string
  count: number
}

export interface DashboardLead {
  id: string
  name: string
  phone: string
  insurance_type: string
  status: LeadStatus
  urgency: LeadUrgency | null
  qualification_score: number | null
  last_contact_at: string | null
  next_contact_at: string | null
  created_at: string
  assigned_user: { id: number, name: string } | null
}

export interface DashboardMeta {
  period: number | null
  date_from: string
  date_to: string
  generated_at: string
}

export interface DashboardData {
  summary: DashboardSummary
  status_distribution: DashboardStatusDistribution[]
  lead_evolution: DashboardEvolutionPoint[]
  insurance_distribution: InsuranceDistribution[]
  priority_leads: DashboardLead[]
  upcoming_contacts: DashboardLead[]
  meta: DashboardMeta
}

export interface DashboardFilters {
  period: '7' | '30' | '90' | '365'
}
