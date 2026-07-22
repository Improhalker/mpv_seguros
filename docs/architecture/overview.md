# Visão geral do projeto

## Propósito

MPV Seguros é um SaaS para corretores autônomos e pequenas corretoras organizarem leads, registrarem contatos e acompanharem follow-ups. O fluxo central é identificar contatos pendentes, abrir o lead, registrar a interação e definir o próximo contato.

## Estado atual

### Implementado

- API Laravel para cadastro, login, logout e consulta do usuário atual com Sanctum.
- BFF no Nuxt que mantém o token em cookie `HttpOnly` e o encaminha como Bearer para o Laravel.
- CRUD de leads, filtros, paginação, exclusão lógica, detalhe e edição.
- Registro, edição e exclusão de interações; sincronização de `last_contact_at` e, quando informado, `next_contact_at`.
- Nota de qualificação calculada no backend e apresentada de 0 a 10.
- Dashboard alimentado por `GET /api/dashboard`, com métricas, listas e gráficos reais.
- Estados de carregamento, atualização, vazio e erro nas telas de leads e dashboard.

### Parcialmente implementado

- A estrutura de organizações existe e o cadastro cria organização e usuário; entretanto as consultas de leads, interações e dashboard não estão protegidas nem filtradas por organização. Consulte o [relatório](../reports/current-status.md).
- O frontend possui login e middleware de rota, mas não possui página de cadastro nem recuperação de senha.
- O dashboard valida filtros de data, seguro e responsável no backend; a interface atual expõe somente o período.

### Planejado

- Isolamento efetivo por `organization_id`, autorização e associação automática de autor/responsável.
- Observações separadas de interações, recuperação de senha, agenda dedicada, relatórios e personalizações.

### Fora do escopo atual

- Integração com WhatsApp, automações, IA, comissões, financeiro, Kanban, notificações e aplicativo móvel.

## Stack observada

- **Frontend:** Nuxt 4, Vue 3, TypeScript, Tailwind CSS 4, shadcn-nuxt, Lucide, Chart.js e vue-chartjs.
- **Backend:** Laravel 13, PHP 8.5, Laravel Sanctum, Eloquent e Form Requests.
- **Dados:** PostgreSQL no Supabase; o Supabase não é usado para autenticação pelo código atual.
- **Comunicação:** navegador → rotas de servidor Nuxt (BFF) → API REST Laravel → PostgreSQL.

## Estrutura de código

```text
frontend/
  app/pages/                 páginas Nuxt
  app/components/            componentes de interface
  app/composables/           estado e chamadas do cliente
  app/types/                 contratos TypeScript
  server/api/                BFF e proxy para Laravel
  server/utils/laravel.ts    cliente HTTP do BFF

backend/
  app/Http/Controllers/Api/  endpoints REST
  app/Http/Requests/         validações HTTP
  app/Http/Resources/        transformação das respostas
  app/Models/                modelos Eloquent
  app/Services/              regras de negócio e agregações
  database/migrations/       esquema PostgreSQL
  tests/Feature/             testes HTTP
```

## Execução local

Frontend, em `frontend/`:

```powershell
npm run dev
npm run lint
npm run typecheck
npm run build
```

Backend, em `backend/`:

```powershell
php artisan migrate
php artisan test --compact
php vendor/bin/pint --dirty --format agent
```

O frontend usa `LARAVEL_API_BASE` em `frontend/nuxt.config.ts`; o valor padrão do código é `http://localhost:8000/api`. O backend requer as variáveis Laravel usuais (`APP_*`, `DB_CONNECTION`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`) e, em produção, configuração de CORS/Sanctum apropriada. Não existe `frontend/.env.example` no repositório no momento da revisão.

## Estados de interface

- **Loading inicial:** `Skeleton` é apresentado antes de a primeira resposta real chegar.
- **Atualização:** `useLeads()` e `useDashboard()` preservam a resposta anterior, abortam a requisição anterior e mostram estado discreto de atualização.
- **Vazio:** listas, gráficos e dashboard possuem mensagens específicas; valor numérico zero permanece um dado válido.
- **Erro:** leads e dashboard mostram alerta e ação de nova tentativa; o BFF transforma falhas da API em erros HTTP sem fallback fictício.

Não há arquivos ou referências ativas a mocks de dashboard ou leads em `frontend/app` e `frontend/server` na revisão realizada.
