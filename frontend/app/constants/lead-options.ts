export const insuranceTypes = [
  { value: 'auto', label: 'Auto' },
  { value: 'residencial', label: 'Residencial' },
  { value: 'vida', label: 'Vida' },
  { value: 'saude', label: 'Saúde' },
  { value: 'empresarial', label: 'Empresarial' },
  { value: 'viagem', label: 'Viagem' },
  { value: 'previdencia', label: 'Previdência' },
  { value: 'outro', label: 'Outro' },
] as const

export const leadSources = [
  { value: 'indicacao', label: 'Indicação' },
  { value: 'whatsapp', label: 'WhatsApp' },
  { value: 'instagram', label: 'Instagram' },
  { value: 'site', label: 'Site' },
  { value: 'ligacao', label: 'Ligação' },
  { value: 'cliente_existente', label: 'Cliente existente' },
  { value: 'anuncio', label: 'Anúncio' },
  { value: 'prospeccao', label: 'Prospecção' },
  { value: 'outro', label: 'Outro' },
] as const

export const employmentTypes = [
  { value: 'clt', label: 'CLT' },
  { value: 'autonomo', label: 'Autônomo' },
  { value: 'empresario', label: 'Empresário' },
  { value: 'servidor_publico', label: 'Servidor público' },
  { value: 'aposentado', label: 'Aposentado' },
  { value: 'desempregado', label: 'Desempregado' },
  { value: 'outro', label: 'Outro' },
] as const

export const incomeRanges = [
  { value: 'ate_2000', label: 'Até R$ 2.000' },
  { value: '2001_5000', label: 'R$ 2.001 a R$ 5.000' },
  { value: '5001_10000', label: 'R$ 5.001 a R$ 10.000' },
  { value: '10001_20000', label: 'R$ 10.001 a R$ 20.000' },
  { value: 'acima_20000', label: 'Acima de R$ 20.000' },
  { value: 'nao_informado', label: 'Não informado' },
] as const

export const contactPeriods = [
  { value: 'manha', label: 'Manhã' },
  { value: 'tarde', label: 'Tarde' },
  { value: 'noite', label: 'Noite' },
  { value: 'indiferente', label: 'Indiferente' },
] as const

export const lossReasons = [
  { value: 'sem_interesse', label: 'Sem interesse' },
  { value: 'preco', label: 'Preço' },
  { value: 'sem_capacidade_financeira', label: 'Sem capacidade financeira' },
  { value: 'fechou_com_concorrente', label: 'Fechou com concorrente' },
  { value: 'nao_respondeu', label: 'Não respondeu' },
  { value: 'dados_invalidos', label: 'Dados inválidos' },
  { value: 'fora_do_perfil', label: 'Fora do perfil' },
  { value: 'outro', label: 'Outro' },
] as const

export const interactionTypes = [
  { value: 'ligacao', label: 'Ligação' },
  { value: 'whatsapp', label: 'WhatsApp' },
  { value: 'email', label: 'E-mail' },
  { value: 'reuniao', label: 'Reunião' },
  { value: 'observacao', label: 'Observação' },
  { value: 'proposta_enviada', label: 'Proposta enviada' },
  { value: 'documento_recebido', label: 'Documento recebido' },
  { value: 'mudanca_status', label: 'Mudança de status' },
] as const
