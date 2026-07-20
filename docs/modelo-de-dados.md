# Modelo de Dados do MVP

## Organização

Representa uma corretora ou a área de trabalho de um corretor individual.

- `id`
- `name`
- `timezone` — inicialmente `America/Sao_Paulo`
- `created_at`, `updated_at`

## Usuário

Pertence a uma organização. Usuários ativos da mesma organização compartilham todos os leads.

- `id`
- `organization_id`
- `name`
- `email`
- `password`
- `is_active`
- `created_at`, `updated_at`

O e-mail será único no sistema. Não há papéis ou permissões complexos no MVP.

## Lead

Representa uma oportunidade comercial. Pertence obrigatoriamente a uma organização.

- `id`
- `organization_id`
- `name` — obrigatório
- `phone` — obrigatório
- `email` — opcional
- `insurance_type` — obrigatório
- `insurance_type_other_description` — opcional; usado apenas para o tipo `outros`
- `status` — obrigatório, padrão `novo`
- `next_contact_at` — opcional
- `last_contact_at` — opcional; atualizado apenas ao registrar uma interação
- `source` — opcional
- `general_notes` — opcional
- `closed_at` — opcional; registrado ao alterar o status para `fechado`
- `lost_at` — opcional; registrado ao alterar o status para `perdido`
- `lost_reason` — opcional
- `created_at`, `updated_at`, `deleted_at`

O uso de `deleted_at` permite exclusão lógica. Leads excluídos não entram nas métricas ou lembretes.

Valores iniciais de `insurance_type`: `auto`, `residencial`, `vida`, `empresarial`, `saude`, `viagem` e `outros`.

Valores de `status`: `novo`, `contatado`, `em_negociacao`, `aguardando_cliente`, `fechado` e `perdido`.

## Observação

Anotação livre que não representa contato real.

- `id`
- `organization_id`
- `lead_id`
- `author_user_id`
- `content`
- `created_at`, `updated_at`

Criar ou editar uma observação não altera `lead.last_contact_at`.

## Interação

Registro de contato real com o lead.

- `id`
- `organization_id`
- `lead_id`
- `author_user_id`
- `type` — `ligacao`, `whatsapp`, `email`, `reuniao` ou `outro`
- `occurred_at`
- `description` — opcional
- `created_at`, `updated_at`

Ao criar uma interação, o backend atualiza automaticamente `lead.last_contact_at` com `occurred_at`.

## Relações

```text
Organization 1 ── N User
Organization 1 ── N Lead
Organization 1 ── N Observation
Organization 1 ── N Interaction

Lead 1 ── N Observation
Lead 1 ── N Interaction

User 1 ── N Observation (autor)
User 1 ── N Interaction (autor)
```

As relações de observação e interação devem apontar para lead, autor e organização compatíveis. O backend deve rejeitar qualquer combinação pertencente a organizações distintas.
