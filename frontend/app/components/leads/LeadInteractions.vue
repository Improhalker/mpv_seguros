<script setup lang="ts">
import { Mic, MicOff, Pencil, Trash2 } from 'lucide-vue-next'
import { useSpeechRecognition } from '@vueuse/core'
import type { LeadInteraction, LeadInteractionInput } from '@/types/lead'
import { interactionTypes } from '@/constants/lead-options'
import { formatDateTime } from '@/utils/format'

defineProps<{ interactions: readonly LeadInteraction[], loading?: boolean, saving?: boolean }>()
const emit = defineEmits<{ save: [input: LeadInteractionInput, interactionId?: string], delete: [interaction: LeadInteraction] }>()

const editing = ref<LeadInteraction | null>(null)
const form = reactive<LeadInteractionInput>({ type: 'ligacao', description: '', occurredAt: new Date().toISOString().slice(0, 16), nextContactAt: null })
const { isListening, isSupported, result, start, stop } = useSpeechRecognition({ lang: 'pt-BR', continuous: true, interimResults: true })

watch(result, (value) => {
  if (isListening.value) form.description = value
})

function edit(interaction: LeadInteraction): void {
  editing.value = interaction
  Object.assign(form, { type: interaction.type, description: interaction.description, occurredAt: new Date(interaction.occurredAt).toISOString().slice(0, 16), nextContactAt: interaction.nextContactAt ? new Date(interaction.nextContactAt).toISOString().slice(0, 16) : null })
}

function reset(): void {
  stop()
  editing.value = null
  Object.assign(form, { type: 'ligacao', description: '', occurredAt: new Date().toISOString().slice(0, 16), nextContactAt: null })
}

function submit(): void {
  if (!form.description.trim()) return
  emit('save', { ...form }, editing.value?.id)
  reset()
}
</script>

<template>
  <Card id="interacoes">
    <CardHeader><h2 class="font-semibold">Histórico de interações</h2><p class="mt-1 text-sm text-muted-foreground">Registre contatos e próximos passos do lead.</p></CardHeader>
    <CardContent class="space-y-6">
      <form class="grid gap-4 md:grid-cols-2" @submit.prevent="submit"><label class="space-y-1.5 text-sm font-medium">Tipo<select v-model="form.type" class="h-10 w-full rounded-lg border bg-card px-3 text-sm"><option v-for="type in interactionTypes" :key="type.value" :value="type.value">{{ type.label }}</option></select></label><label class="space-y-1.5 text-sm font-medium">Quando ocorreu<Input v-model="form.occurredAt" type="datetime-local" required /></label><label class="space-y-1.5 text-sm font-medium md:col-span-2">Descrição<textarea v-model="form.description" rows="4" required class="w-full rounded-lg border bg-card px-3 py-2 text-sm outline-none focus-visible:ring-2 focus-visible:ring-ring" /></label><label class="space-y-1.5 text-sm font-medium">Próximo contato<Input v-model="form.nextContactAt" type="datetime-local" /></label><div class="flex items-end gap-2"><Button v-if="isSupported" type="button" variant="outline" :disabled="isListening" aria-label="Iniciar transcrição por voz" @click="start"><Mic class="size-4" />Falar</Button><Button v-if="isSupported" type="button" variant="outline" :disabled="!isListening" aria-label="Parar transcrição por voz" @click="stop"><MicOff class="size-4" />Parar</Button><Button type="submit" :disabled="saving || !form.description.trim()">{{ saving ? 'Salvando...' : editing ? 'Atualizar interação' : 'Registrar interação' }}</Button><Button v-if="editing" type="button" variant="ghost" @click="reset">Cancelar</Button></div><p v-if="!isSupported" class="text-xs text-muted-foreground md:col-span-2">A transcrição por voz não é compatível com este navegador.</p></form>

      <div v-if="loading" class="space-y-3"><Skeleton v-for="index in 3" :key="index" class="h-20 w-full" /></div><div v-else-if="interactions.length === 0" class="rounded-lg border border-dashed p-6 text-center text-sm text-muted-foreground">Nenhuma interação registrada.</div><ol v-else class="space-y-3"><li v-for="interaction in interactions" :key="interaction.id" class="rounded-lg border p-4"><div class="flex items-start justify-between gap-3"><div><p class="font-medium">{{ interactionTypes.find((item) => item.value === interaction.type)?.label ?? interaction.type }}</p><p class="mt-1 whitespace-pre-wrap text-sm text-muted-foreground">{{ interaction.description }}</p><p class="mt-3 text-xs text-muted-foreground">{{ formatDateTime(interaction.occurredAt) }}</p></div><div class="flex gap-1"><Button variant="ghost" size="icon" :aria-label="`Editar interação`" @click="edit(interaction)"><Pencil class="size-4" /></Button><Button variant="ghost" size="icon" :aria-label="`Excluir interação`" @click="emit('delete', interaction)"><Trash2 class="size-4 text-danger" /></Button></div></div></li></ol>
    </CardContent>
  </Card>
</template>
