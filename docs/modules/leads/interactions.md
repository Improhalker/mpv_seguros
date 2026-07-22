# Histórico de interações

## O que é registrado

Uma interação é um evento de contato ou acompanhamento ligado a um lead. A entidade é `LeadInteraction`, mapeada em `backend/app/Models/LeadInteraction.php` e persistida em `lead_interactions`.

Tipos aceitos pelo backend atual: `ligacao`, `whatsapp`, `email`, `reuniao`, `observacao`, `proposta_enviada`, `documento_recebido` e `mudanca_status`. A descrição é obrigatória, assim como a data/hora em `occurred_at`; `next_contact_at` é opcional.

> O tipo `observacao` existe como interação no código atual. Não há tabela ou módulo separado de observações, embora a visão de produto anterior diferencie os dois conceitos.

## Regras de sincronização

`LeadInteractionService` executa criação, edição e remoção dentro de transação:

1. cria, atualiza ou remove a interação;
2. calcula `last_contact_at` como o maior `occurred_at` restante do lead;
3. atualiza `next_contact_at` somente quando o atributo está presente no payload recebido.

Ao remover a última interação, `last_contact_at` torna-se `null`. A exclusão lógica de um lead não remove fisicamente suas interações, pois a cascata de FK só ocorre em exclusão física.

## Frontend

`frontend/app/components/leads/LeadInteractions.vue` exibe o histórico, skeleton, estado vazio e formulário de interação. `useLead().saveInteraction()` e `deleteInteraction()` chamam as rotas BFF e atualizam a lista com nova busca. A transcrição de voz usa `useSpeechRecognition` do VueUse no navegador; apenas texto é enviado, não há upload de áudio.

## Endpoints e respostas

Os endpoints estão detalhados em [API.md](API.md). `LeadInteractionResource` retorna chaves camelCase: `id`, `leadId`, `userId`, `type`, `description`, `occurredAt`, `nextContactAt`, `createdAt` e `updatedAt`.

## Pontos para continuidade

- `user_id` está na tabela, mas não é atribuído pelo serviço atual.
- Não há `organization_id` em `lead_interactions`.
- Não há autorização por organização nas rotas atuais.
- Edição de interação não recalcula uma data anterior de `next_contact_at` automaticamente quando o campo é omitido; ela preserva o valor já existente no lead.
