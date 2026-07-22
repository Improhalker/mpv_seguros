# Documentação técnica — MPV Seguros

Este é o ponto inicial da documentação do projeto. Ela descreve o estado observado no código em 22 de julho de 2026, separando o que funciona hoje do que ainda precisa de implementação ou correção.

## Comece por aqui

1. [Visão geral](architecture/overview.md) — objetivo, stack, execução local e estado do MVP.
2. [Arquitetura do sistema](architecture/system-architecture.md) — camadas, comunicação e fronteiras atuais.
3. [Módulo de leads](modules/leads/README.md) — cadastro, listagem, detalhe e ciclo comercial.
4. [Dashboard](modules/dashboard/README.md) — endpoint agregado, métricas, gráficos e estados de interface.
5. [Relatório de situação](reports/current-status.md) — pendências, inconsistências, riscos e prioridades.

## Mapa de documentos

| Área | Documento | Finalidade |
| --- | --- | --- |
| Arquitetura | [overview.md](architecture/overview.md) | Contexto, execução e escopo atual. |
| Arquitetura | [system-architecture.md](architecture/system-architecture.md) | Comunicação Nuxt, Laravel e PostgreSQL. |
| Leads | [README.md](modules/leads/README.md) | Fluxos, responsabilidades e tela. |
| Leads | [data-model.md](modules/leads/data-model.md) | Campos, tabelas e relacionamentos. |
| Leads | [API.md](modules/leads/API.md) | Endpoints, payloads, respostas e erros. |
| Leads | [scoring.md](modules/leads/scoring.md) | Nota de qualificação de 0 a 10. |
| Leads | [interactions.md](modules/leads/interactions.md) | Histórico de contatos e datas derivadas. |
| Dashboard | [README.md](modules/dashboard/README.md) | Composição da página e contratos. |
| Dashboard | [metrics.md](modules/dashboard/metrics.md) | Métricas, gráficos e listas. |
| Dashboard | [data-flow.md](modules/dashboard/data-flow.md) | Carregamento, filtros, estados e erros. |
| Auditoria | [current-status.md](reports/current-status.md) | Estado real, pendências e débitos. |

Os arquivos `.mmd` são as fontes editáveis dos diagramas; cada um possui um PNG correspondente na mesma pasta.
