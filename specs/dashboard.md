# Especificação — Dashboard e Lembretes

## Contexto

As métricas e lembretes são calculados somente sobre os dados da organização do usuário autenticado, considerando o fuso `America/Sao_Paulo`.

## Métricas

- Total de leads: leads não excluídos.
- Cadastrados hoje: leads criados no dia atual.
- Negócios fechados: leads em status `fechado`.
- Negócios perdidos: leads em status `perdido`.
- Contatos para hoje: leads com `next_contact_at` no dia atual.
- Contatos atrasados: leads com `next_contact_at` já passado e status diferente de `fechado` e `perdido`.

## Listas de lembrete

- **Para hoje:** contatos previstos para o dia atual;
- **Atrasados:** contatos pendentes cuja data/hora programada já passou;
- **Próximos:** contatos futuros de leads ainda não encerrados, em ordem crescente de `next_contact_at`.

Os lembretes não disparam notificações nem dependem de jobs em segundo plano no MVP.
