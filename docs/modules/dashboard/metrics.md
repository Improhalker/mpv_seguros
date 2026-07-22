# Métricas, gráficos e listas

## Indicadores

`DashboardService::summary()` executa uma agregação SQL com `COUNT` e `SUM(CASE ...)` sobre a query base, evitando carregar leads em memória.

| Indicador | Regra atual | Período/filtros |
| --- | --- | --- |
| Total de leads | `count(*)` de leads não excluídos (Eloquent aplica soft delete) | filtros de seguro/responsável; não limita por período. |
| Cadastrados hoje | `created_at` entre início e fim do dia em São Paulo | seguro/responsável; não usa intervalo selecionado. |
| Fechados | `status = fechado` e `closed_at` no intervalo | seguro/responsável e período/data selecionada. |
| Perdidos | `status = perdido` e `lost_at` no intervalo | seguro/responsável e período/data selecionada. |
| Contatos para hoje | `next_contact_at` hoje e status não terminal | seguro/responsável. |
| Contatos atrasados | `next_contact_at < agora` e status não terminal | seguro/responsável. |

Zero é exibido como valor válido. Os cards são definidos em `frontend/app/constants/dashboard.ts` e renderizados por `MetricCard.vue`; não há comparações percentuais.

## Gráficos

| Gráfico | Dados da API | Implementação | Sem dados |
| --- | --- | --- | --- |
| Evolução | `lead_evolution[]` (`date`, `created`, `closed`, `lost`) | `LeadGrowthChart.vue` | Série preenche datas ausentes com zero; se todas forem zero, mostra empty state. |
| Por status | `status_distribution[]` | `LeadStatusChart.vue` | Categorias de contagem zero são omitidas da rosca; sem categorias positivas, empty state. |
| Por seguro | `insurance_distribution[]` | `InsuranceDistributionChart.vue` | Só tipos presentes no período são retornados; array vazio mostra empty state. |

`DashboardService::countByDate()` usa agrupamento por data no banco e `leadEvolution()` completa apenas as datas ausentes dentro do período com zero, sem inventar leads. No PostgreSQL a data é convertida em `America/Sao_Paulo` antes do agrupamento.

## Listas

### Leads prioritários

`priority_leads` é limitado a 8 e carrega `assignedUser:id,name` para evitar N+1. A ordenação é: contato atrasado, contato de hoje, urgência alta, maior `qualification_score`, lead novo sem contato e data de criação decrescente. Leads `fechado` e `perdido` são excluídos.

`PriorityLeadsList.vue` apresenta status, telefone, próximo contato e `QualificationBadge`. Score nulo é mostrado como Não avaliada pelo componente compartilhado.

### Próximos contatos

`upcoming_contacts` é limitado a 8, exclui status terminais, considera `next_contact_at > agora` e ordena por data crescente. `UpcomingContactsList.vue` abre o detalhe do lead.

## Testes do módulo

`backend/tests/Feature/DashboardTest.php` cobre contrato vazio, resumo, exclusão de contatos terminais, distribuições, evolução, filtro de seguro, ordenação de listas, score e validação de filtros. A cobertura não mede percentual de linhas; ela é composta por testes de fluxo HTTP.
