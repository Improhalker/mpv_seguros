# Especificação — Leads

## Campos

| Campo | Obrigatório | Regra |
| --- | --- | --- |
| Nome | Sim | Texto identificador do lead. |
| Telefone | Sim | Dado de contato principal. |
| E-mail | Não | Dado de contato opcional. |
| Tipo de seguro | Sim | Valor pré-definido. |
| Descrição de outros | Condicional | Usada quando o tipo for `outros`. |
| Status | Sim | Padrão `novo`. |
| Próximo contato | Não | Base dos lembretes. |
| Origem | Não | Origem declarada do lead. |
| Observação geral | Não | Contexto geral do lead. |
| Organização | Sim | Definida no servidor a partir do usuário. |

## Tipos de seguro

`auto`, `residencial`, `vida`, `empresarial`, `saude`, `viagem` e `outros`.

O modelo usa um valor estável para permitir tipos personalizáveis no futuro, mas a administração desses tipos não será criada no MVP.

## Status e encerramento

Os valores permitidos são `novo`, `contatado`, `em_negociacao`, `aguardando_cliente`, `fechado` e `perdido`.

- O usuário pode alterar o status manualmente.
- Ao definir `fechado`, registrar `closed_at`.
- Ao definir `perdido`, registrar `lost_at`.
- `lost_reason` é opcional.
- Leads em `fechado` ou `perdido` não aparecem como contatos atrasados ou próximos.

## Exclusão

A exclusão será lógica. Leads excluídos ficam fora das consultas usuais, métricas e lembretes.
