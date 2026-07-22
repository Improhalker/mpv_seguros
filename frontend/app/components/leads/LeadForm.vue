<script setup lang="ts">
import type { Lead, LeadInput } from '@/types/lead'
import { contactPeriods, employmentTypes, incomeRanges, insuranceTypes, leadSources, lossReasons } from '@/constants/lead-options'
import { leadStatusConfig } from '@/constants/lead-status'
import { normalizePhone } from '@/utils/lead'

const props = withDefaults(defineProps<{
  initialLead?: Lead | null
  submitting?: boolean
  submitLabel?: string
  apiErrors?: Record<string, string[]>
}>(), {
  initialLead: null,
  submitting: false,
  submitLabel: 'Salvar lead',
  apiErrors: () => ({}),
})

const emit = defineEmits<{ submit: [lead: LeadInput] }>()

const form = reactive<LeadInput>({ name: '', phone: '', insuranceType: 'auto', status: 'novo' })
const errors = ref<Record<string, string>>({})

function applyLead(lead: Lead | null): void {
  Object.assign(form, {
    name: lead?.name ?? '', phone: lead?.phone ?? '', email: lead?.email ?? null, insuranceType: lead?.insuranceType ?? 'auto',
    customInsuranceType: lead?.customInsuranceType ?? null, status: lead?.status ?? 'novo', urgency: lead?.urgency ?? null,
    source: lead?.source ?? null, sourceDetails: lead?.sourceDetails ?? null, generalNotes: lead?.generalNotes ?? null,
    birthDate: lead?.birthDate ?? null, city: lead?.city ?? null, state: lead?.state ?? null, occupation: lead?.occupation ?? null,
    companyName: lead?.companyName ?? null, employmentType: lead?.employmentType ?? null, incomeRange: lead?.incomeRange ?? null,
    hasCurrentInsurance: lead?.hasCurrentInsurance ?? null, currentInsurer: lead?.currentInsurer ?? null,
    currentPolicyExpiresAt: lead?.currentPolicyExpiresAt ?? null, estimatedAssetValue: lead?.estimatedAssetValue ?? null,
    availableBudget: lead?.availableBudget ?? null, needsDescription: lead?.needsDescription ?? null,
    preferredContactPeriod: lead?.preferredContactPeriod ?? null, financialCapacityScore: lead?.financialCapacityScore ?? null,
    salesViabilityScore: lead?.salesViabilityScore ?? null, interestLevelScore: lead?.interestLevelScore ?? null,
    availabilityScore: lead?.availabilityScore ?? null, nextContactAt: toDateTimeLocal(lead?.nextContactAt),
    lossReason: lead?.lossReason ?? null, lossReasonDetails: lead?.lossReasonDetails ?? null,
  })
}

function toDateTimeLocal(value: string | null | undefined): string | null {
  return value ? new Date(value).toISOString().slice(0, 16) : null
}

function localError(field: string): string | undefined {
  return errors.value[field] ?? props.apiErrors[field]?.[0]
}

function handleSubmit(): void {
  errors.value = {}
  form.phone = normalizePhone(form.phone)

  if (!form.name.trim()) errors.value.name = 'Informe o nome do lead.'
  if (form.phone.length < 10) errors.value.phone = 'Informe um telefone válido.'
  if (!form.insuranceType) errors.value.insuranceType = 'Selecione o tipo de seguro.'
  if (form.insuranceType === 'outro' && !form.customInsuranceType?.trim()) errors.value.customInsuranceType = 'Descreva o tipo de seguro.'
  if (form.status === 'perdido' && !form.lossReason) errors.value.lossReason = 'Informe o motivo da perda.'
  if (form.status === 'perdido' && form.lossReason === 'outro' && !form.lossReasonDetails?.trim()) errors.value.lossReasonDetails = 'Detalhe o motivo da perda.'

  if (Object.keys(errors.value).length === 0) emit('submit', { ...form })
}

watch(() => props.initialLead, applyLead, { immediate: true })
</script>

<template>
  <form class="space-y-6" novalidate @submit.prevent="handleSubmit">
    <Card>
      <CardHeader class="flex-row items-start justify-between gap-4">
        <div><h2 class="font-semibold">Cadastro básico</h2><p class="mt-1 text-sm text-muted-foreground">Informações necessárias para criar o lead.</p></div>
        <Badge tone="info">Obrigatória</Badge>
      </CardHeader>
      <CardContent class="grid gap-4 md:grid-cols-2">
        <label class="space-y-1.5 text-sm font-medium">Nome <span class="text-danger" aria-hidden="true">*</span><Input v-model="form.name" autocomplete="name" :aria-invalid="Boolean(localError('name'))" /><span v-if="localError('name')" class="text-xs text-danger">{{ localError('name') }}</span></label>
        <label class="space-y-1.5 text-sm font-medium">Telefone <span class="text-danger" aria-hidden="true">*</span><Input v-model="form.phone" inputmode="tel" autocomplete="tel" placeholder="(11) 99999-9999" :aria-invalid="Boolean(localError('phone'))" /><span v-if="localError('phone')" class="text-xs text-danger">{{ localError('phone') }}</span></label>
        <label class="space-y-1.5 text-sm font-medium">E-mail <Input v-model="form.email" type="email" autocomplete="email" /></label>
        <label class="space-y-1.5 text-sm font-medium">Tipo de seguro <span class="text-danger" aria-hidden="true">*</span><select v-model="form.insuranceType" class="h-10 w-full rounded-lg border bg-card px-3 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"><option v-for="option in insuranceTypes" :key="option.value" :value="option.value">{{ option.label }}</option></select><span v-if="localError('insuranceType')" class="text-xs text-danger">{{ localError('insuranceType') }}</span></label>
        <label v-if="form.insuranceType === 'outro'" class="space-y-1.5 text-sm font-medium md:col-span-2">Qual tipo de seguro? <span class="text-danger" aria-hidden="true">*</span><Input v-model="form.customInsuranceType" /><span v-if="localError('customInsuranceType')" class="text-xs text-danger">{{ localError('customInsuranceType') }}</span></label>
        <label class="space-y-1.5 text-sm font-medium">Origem<select v-model="form.source" class="h-10 w-full rounded-lg border bg-card px-3 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring"><option :value="null">Selecione</option><option v-for="option in leadSources" :key="option.value" :value="option.value">{{ option.label }}</option></select></label>
        <label v-if="form.source === 'outro'" class="space-y-1.5 text-sm font-medium">Detalhe da origem<Input v-model="form.sourceDetails" /></label>
        <label class="space-y-1.5 text-sm font-medium md:col-span-2">Observação geral<textarea v-model="form.generalNotes" rows="3" class="w-full rounded-lg border bg-card px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring" /></label>
      </CardContent>
    </Card>

    <Card>
      <CardHeader class="flex-row items-start justify-between gap-4"><div><h2 class="font-semibold">Perfil e qualificação</h2><p class="mt-1 text-sm text-muted-foreground">Dados úteis para entender melhor a necessidade do cliente.</p></div><Badge>Opcional</Badge></CardHeader>
      <CardContent class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        <label class="space-y-1.5 text-sm font-medium">Nascimento<Input v-model="form.birthDate" type="date" /></label><label class="space-y-1.5 text-sm font-medium">Cidade<Input v-model="form.city" /></label><label class="space-y-1.5 text-sm font-medium">UF<Input v-model="form.state" maxlength="2" /></label>
        <label class="space-y-1.5 text-sm font-medium">Profissão<Input v-model="form.occupation" /></label><label class="space-y-1.5 text-sm font-medium">Empresa<Input v-model="form.companyName" /></label><label class="space-y-1.5 text-sm font-medium">Vínculo<select v-model="form.employmentType" class="h-10 w-full rounded-lg border bg-card px-3 text-sm"><option :value="null">Selecione</option><option v-for="option in employmentTypes" :key="option.value" :value="option.value">{{ option.label }}</option></select></label>
        <label class="space-y-1.5 text-sm font-medium">Faixa de renda<select v-model="form.incomeRange" class="h-10 w-full rounded-lg border bg-card px-3 text-sm"><option :value="null">Selecione</option><option v-for="option in incomeRanges" :key="option.value" :value="option.value">{{ option.label }}</option></select></label><label class="space-y-1.5 text-sm font-medium">Possui seguro atual?<select v-model="form.hasCurrentInsurance" class="h-10 w-full rounded-lg border bg-card px-3 text-sm"><option :value="null">Não informado</option><option :value="true">Sim</option><option :value="false">Não</option></select></label><label class="space-y-1.5 text-sm font-medium">Seguradora atual<Input v-model="form.currentInsurer" /></label>
        <label class="space-y-1.5 text-sm font-medium">Vencimento da apólice<Input v-model="form.currentPolicyExpiresAt" type="date" /></label><label class="space-y-1.5 text-sm font-medium">Valor estimado do bem (R$)<Input v-model.number="form.estimatedAssetValue" type="number" min="0" step="0.01" inputmode="decimal" /></label><label class="space-y-1.5 text-sm font-medium">Orçamento disponível (R$)<Input v-model.number="form.availableBudget" type="number" min="0" step="0.01" inputmode="decimal" /></label>
        <label class="space-y-1.5 text-sm font-medium">Período preferido<select v-model="form.preferredContactPeriod" class="h-10 w-full rounded-lg border bg-card px-3 text-sm"><option :value="null">Selecione</option><option v-for="option in contactPeriods" :key="option.value" :value="option.value">{{ option.label }}</option></select></label><label class="space-y-1.5 text-sm font-medium md:col-span-2">Necessidade do cliente<textarea v-model="form.needsDescription" rows="2" class="w-full rounded-lg border bg-card px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring" /></label>
      </CardContent>
    </Card>

    <Card>
      <CardHeader class="flex-row items-start justify-between gap-4"><div><h2 class="font-semibold">Priorização e acompanhamento</h2><p class="mt-1 text-sm text-muted-foreground">As notas são opcionais; a nota de qualificação de 0 a 10 é calculada automaticamente pelo sistema.</p></div><Badge>Opcional</Badge></CardHeader>
      <CardContent class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
        <label class="space-y-1.5 text-sm font-medium">Status<select v-model="form.status" class="h-10 w-full rounded-lg border bg-card px-3 text-sm"><option v-for="(config, value) in leadStatusConfig" :key="value" :value="value">{{ config.label }}</option></select></label><label class="space-y-1.5 text-sm font-medium">Urgência<select v-model="form.urgency" class="h-10 w-full rounded-lg border bg-card px-3 text-sm"><option :value="null">Não definida</option><option value="baixa">Baixa</option><option value="media">Média</option><option value="alta">Alta</option></select></label><label class="space-y-1.5 text-sm font-medium">Próximo contato<Input v-model="form.nextContactAt" type="datetime-local" /></label>
        <template v-for="field in [{ key: 'financialCapacityScore', label: 'Capacidade financeira' }, { key: 'salesViabilityScore', label: 'Viabilidade comercial' }, { key: 'interestLevelScore', label: 'Nível de interesse' }, { key: 'availabilityScore', label: 'Disponibilidade' }]" :key="field.key"><label class="space-y-1.5 text-sm font-medium">{{ field.label }}<select v-model="form[field.key as keyof LeadInput]" class="h-10 w-full rounded-lg border bg-card px-3 text-sm"><option :value="null">Não avaliado</option><option v-for="score in 5" :key="score" :value="score">{{ score }}</option></select><span class="block text-xs font-normal text-muted-foreground">1 = muito baixo · 3 = médio · 5 = muito alto</span></label></template>
        <template v-if="form.status === 'perdido'"><label class="space-y-1.5 text-sm font-medium">Motivo da perda <span class="text-danger">*</span><select v-model="form.lossReason" class="h-10 w-full rounded-lg border bg-card px-3 text-sm"><option :value="null">Selecione</option><option v-for="reason in lossReasons" :key="reason.value" :value="reason.value">{{ reason.label }}</option></select><span v-if="localError('lossReason')" class="text-xs text-danger">{{ localError('lossReason') }}</span></label><label v-if="form.lossReason === 'outro'" class="space-y-1.5 text-sm font-medium">Detalhe <span class="text-danger">*</span><Input v-model="form.lossReasonDetails" /><span v-if="localError('lossReasonDetails')" class="text-xs text-danger">{{ localError('lossReasonDetails') }}</span></label></template>
      </CardContent>
    </Card>

    <div class="flex justify-end"><Button type="submit" :disabled="submitting">{{ submitting ? 'Salvando...' : submitLabel }}</Button></div>
  </form>
</template>
