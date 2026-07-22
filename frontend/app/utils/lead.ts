import type { LeadInput } from '@/types/lead'

const fieldMap: Record<keyof LeadInput, string> = {
  name: 'name', phone: 'phone', email: 'email', insuranceType: 'insurance_type', customInsuranceType: 'custom_insurance_type',
  status: 'status', urgency: 'urgency', source: 'source', sourceDetails: 'source_details', generalNotes: 'general_notes',
  birthDate: 'birth_date', city: 'city', state: 'state', occupation: 'occupation', companyName: 'company_name',
  employmentType: 'employment_type', incomeRange: 'income_range', hasCurrentInsurance: 'has_current_insurance',
  currentInsurer: 'current_insurer', currentPolicyExpiresAt: 'current_policy_expires_at', estimatedAssetValue: 'estimated_asset_value',
  availableBudget: 'available_budget', needsDescription: 'needs_description', preferredContactPeriod: 'preferred_contact_period',
  financialCapacityScore: 'financial_capacity_score', salesViabilityScore: 'sales_viability_score', interestLevelScore: 'interest_level_score',
  availabilityScore: 'availability_score', nextContactAt: 'next_contact_at', assignedUserId: 'assigned_user_id',
  lossReason: 'loss_reason', lossReasonDetails: 'loss_reason_details',
}

export function toLeadApiPayload(input: LeadInput): Record<string, unknown> {
  return Object.fromEntries(
    Object.entries(input)
      .filter(([, value]) => value !== undefined)
      .map(([key, value]) => [fieldMap[key as keyof LeadInput], value]),
  )
}

export function normalizePhone(value: string): string {
  return value.replace(/\D/g, '')
}
