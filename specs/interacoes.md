# Especificação — Observações e Interações

## Observações

Uma observação é uma anotação livre sobre o lead, contendo autor, data e texto. Não representa necessariamente uma tentativa ou realização de contato.

Criar, editar ou consultar uma observação não altera `last_contact_at`.

## Interações

Uma interação representa contato real com o cliente. Os tipos permitidos são:

- `ligacao`;
- `whatsapp`;
- `email`;
- `reuniao`;
- `outro`.

Toda interação registra autor, lead, organização, tipo, data/hora da ocorrência e uma descrição opcional.

Ao registrar uma interação, o backend atualiza automaticamente `lead.last_contact_at` com a data/hora da interação. Essa regra deve estar centralizada no backend, e não no frontend.
