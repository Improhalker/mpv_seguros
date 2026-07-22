# Relatório de situação atual

## Resumo

O projeto possui frontend Nuxt funcional, API Laravel, PostgreSQL configurado externamente, autenticação básica, módulo de leads, interações, qualificação e dashboard real. Não foram encontrados mocks ativos no dashboard ou em leads.

## Funcional

- Registro e login Laravel com token Sanctum, logout e consulta de sessão via BFF.
- CRUD, filtros, paginação e soft delete de leads.
- Qualificação de 0 a 10 calculada no backend.
- Histórico de interações com sincronização de datas do lead.
- Dashboard agregado com cards, gráficos e listas reais.
- Lint, typecheck, build Nuxt e testes Laravel estavam disponíveis e foram executados nesta revisão.

## Parcial

- Middleware Nuxt exige sessão antes de páginas internas, mas endpoints de leads, interações e dashboard não exigem Sanctum.
- Organização é criada no registro, mas dados de domínio não são atribuídos nem filtrados por organização.
- Backend do dashboard aceita mais filtros que a UI oferece.
- A autenticação não possui interface de cadastro ou recuperação de senha no Nuxt.

## Pendências e inconsistências encontradas

| Impacto | Achado | Evidência |
| --- | --- | --- |
| Crítico | Rotas de leads, interações e dashboard são públicas; não há escopo por organização. | `backend/routes/api.php`, `LeadService::baseQuery()`, `DashboardService::baseQuery()`. |
| Crítico | `organization_id` em leads é anulável e não é preenchido pelo serviço; interações não têm essa coluna. | migrations de leads/interações, `LeadService::create()`, `LeadInteractionService::create()`. |
| Alto | `created_by`, `assigned_user_id` e `lead_interactions.user_id` são opcionais e não são preenchidos pelo usuário autenticado. | migrations e services. |
| Alto | O frontend possui login, mas não cadastro nem recuperação de senha, embora o backend tenha registro. | `frontend/app/pages/login.vue`, `server/api/auth/`. |
| Médio | A validação de `custom_insurance_type` em `LeadRules` condiciona a obrigatoriedade a `status === outro`, e não a `insurance_type === outro`; a UI compensa parcialmente com validação local. | `LeadRules::rules()`, `LeadForm.vue`. |
| Médio | O produto planeja observações separadas, mas o código usa `observacao` como tipo de interação e não possui tabela de observações. | `StoreLeadInteractionRequest`, migrations. |
| Médio | A exclusão lógica do lead mantém interações no banco; a cascata não é ativada por soft delete. | `Lead` usa `SoftDeletes`; FK de `lead_interactions`. |
| Baixo | Não existe arquivo `frontend/.env.example`. | estrutura do repositório. |
| Baixo | A estrutura de testes tem `tests/Feature/Feature/LeadManagementTest.php`, um nível de pasta redundante. | árvore de testes. |

## Riscos e débitos

O maior risco é exposição e mistura de dados quando mais de uma organização usar o sistema. Também há risco de divergência entre a especificação anterior e a implementação de interações/observações. O uso de RLS no PostgreSQL é ativado nas migrations de leads e interações, mas não há políticas RLS criadas pelo código, portanto não substitui a autorização da aplicação.

## Cobertura observada

- `AuthenticationTest.php`: cadastro, login, logout e me.
- `LeadManagementTest.php`: CRUD, busca, paginação, qualificação, filtros e interações.
- `DashboardTest.php`: contrato, agregações, filtros e listas.

Não há testes de frontend automatizados no repositório. Não foi medida cobertura percentual.

## Próximos passos por prioridade

1. **Crítico:** aplicar middleware Sanctum e escopo obrigatório de organização a toda rota e consulta de domínio.
2. **Alto:** preencher organização, criador, responsável e autor a partir do usuário autenticado; validar relações cruzadas.
3. **Alto:** alinhar cadastro/recuperação de senha do frontend ao backend.
4. **Médio:** decidir e implementar separação real entre observações e interações, ou ajustar a especificação.
5. **Médio:** corrigir a validação de tipo de seguro complementar e revisar o comportamento de exclusão lógica/interações.
6. **Baixo:** fornecer exemplo de ambiente do frontend e organizar a localização do teste de leads.

Nenhuma dessas pendências foi corrigida nesta etapa; este relatório apenas registra o estado real.
