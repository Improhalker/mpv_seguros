export type LeadStatus = 'novo' | 'contatado' | 'em_negociacao' | 'aguardando_cliente' | 'fechado' | 'perdido'
export type LeadUrgency = 'baixa' | 'media' | 'alta'

export interface Lead {
  id: string
  organizationId: number | null
  assignedUserId: number | null
  createdBy: number | null
  name: string
  phone: string
  email: string | null
  insuranceType: string
  customInsuranceType: string | null
  status: LeadStatus
  urgency: LeadUrgency | null
  source: string | null
  sourceDetails: string | null
  generalNotes: string | null
  birthDate: string | null
  city: string | null
  state: string | null
  occupation: string | null
  companyName: string | null
  employmentType: string | null
  incomeRange: string | null
  hasCurrentInsurance: boolean | null
  currentInsurer: string | null
  currentPolicyExpiresAt: string | null
  estimatedAssetValue: number | null
  availableBudget: number | null
  needsDescription: string | null
  preferredContactPeriod: string | null
  financialCapacityScore: number | null
  salesViabilityScore: number | null
  interestLevelScore: number | null
  availabilityScore: number | null
  qualificationScore: number | null
  lastContactAt: string | null
  nextContactAt: string | null
  closedAt: string | null
  lostAt: string | null
  lossReason: string | null
  lossReasonDetails: string | null
  assignedUser?: { id: number, name: string } | null
  createdAt: string
  updatedAt: string
}

export interface LeadInput {
  name: string
  phone: string
  email?: string | null
  insuranceType: string
  customInsuranceType?: string | null
  status?: LeadStatus
  urgency?: LeadUrgency | null
  source?: string | null
  sourceDetails?: string | null
  generalNotes?: string | null
  birthDate?: string | null
  city?: string | null
  state?: string | null
  occupation?: string | null
  companyName?: string | null
  employmentType?: string | null
  incomeRange?: string | null
  hasCurrentInsurance?: boolean | null
  currentInsurer?: string | null
  currentPolicyExpiresAt?: string | null
  estimatedAssetValue?: number | null
  availableBudget?: number | null
  needsDescription?: string | null
  preferredContactPeriod?: string | null
  financialCapacityScore?: number | null
  salesViabilityScore?: number | null
  interestLevelScore?: number | null
  availabilityScore?: number | null
  nextContactAt?: string | null
  assignedUserId?: number | null
  lossReason?: string | null
  lossReasonDetails?: string | null
}

export interface LeadInteraction {
  id: string
  leadId: string
  userId: number | null
  type: string
  description: string
  occurredAt: string
  nextContactAt: string | null
  createdAt: string
  updatedAt: string
}

export interface LeadInteractionInput {
  type: string
  description: string
  occurredAt: string
  nextContactAt?: string | null
}

export interface LeadFilters {
  search: string
  status: string
  insuranceType: string
  urgency: string
  source: string
  contactPeriod: string
  minScore: string
  maxScore: string
  page: number
  perPage: number
}

export interface PaginationMeta {
  current_page: number
  last_page: number
  per_page: number
  total: number
}

export interface PaginatedResponse<T> {
  data: T[]
  meta: PaginationMeta
}
