# Arquitetura do MVP

## Visão geral

O produto será composto por um frontend Nuxt e uma API REST Laravel. O frontend será hospedado na Vercel e o backend em domínio próprio, VPS ou Render. A API será a única responsável por autenticação, autorização e regras de negócio.

```text
Navegador
  → Nuxt na Vercel
  → rotas de servidor Nuxt (BFF)
  → API REST Laravel
  → PostgreSQL do Supabase
```

O Supabase será usado exclusivamente como PostgreSQL no MVP. Supabase Storage poderá ser incorporado futuramente, quando existir uma necessidade aprovada de arquivos. Supabase Auth não será utilizado.

## Frontend

- Nuxt, Vue 3 e TypeScript;
- Tailwind CSS e shadcn-vue;
- Interface focada em dashboard, lista de leads e detalhe do lead;
- Comunicação com a API por rotas de servidor do Nuxt, evitando expor tokens Bearer ao JavaScript do navegador.

## Backend

- Laravel com API REST;
- Laravel Sanctum para emissão e revogação de tokens Bearer;
- PostgreSQL como banco de dados;
- Controllers finos, services para regras de negócio e models Eloquent;
- Repositories somente se uma necessidade real justificar a camada;
- Validação, autorização e escopo de organização executados no backend.

## Autenticação entre domínios

Como frontend e API estarão em domínios diferentes, a autenticação usará tokens Bearer do Laravel Sanctum.

Estratégia aprovada para o frontend:

1. O navegador envia credenciais somente ao endpoint de login do Nuxt.
2. A camada de servidor do Nuxt chama a API Laravel e recebe o token Sanctum.
3. O Nuxt armazena o token em cookie `HttpOnly`, `Secure` e com política `SameSite` adequada ao domínio do frontend.
4. Nas chamadas posteriores, as rotas de servidor do Nuxt leem o cookie e enviam `Authorization: Bearer <token>` para o Laravel.
5. O código executado no navegador não acessa o token e não o grava em `localStorage`, `sessionStorage`, IndexedDB ou cookies legíveis por JavaScript.
6. No logout, o Laravel revoga o token atual e o Nuxt remove o cookie.

Essa abordagem usa o Nuxt como BFF (Backend for Frontend), preserva o padrão Bearer entre Nuxt e Laravel e mantém uma única fonte de autenticação: Laravel/Sanctum. O frontend não deverá integrar Supabase Auth nem manter um segundo estado de sessão independente.

## Isolamento por organização

- Toda tabela de domínio possui `organization_id`.
- A organização do usuário autenticado é determinada no servidor, nunca confiada ao valor enviado pelo cliente.
- Consultas de domínio devem sempre ser filtradas por `organization_id` do usuário autenticado.
- Ao criar registros, o backend atribui a organização do usuário autenticado.
- Relações entre usuário, lead, observação e interação devem ser validadas para a mesma organização.
- Usuários inativos não podem autenticar nem acessar a API.

## Datas e fuso horário

- Persistir datas e horas em UTC;
- Converter e calcular intervalos de lembrete em `America/Sao_Paulo`;
- Apresentar datas no fuso horário da organização no MVP, inicialmente fixado em `America/Sao_Paulo`.

## Segurança e operação

- Variáveis de ambiente para URLs, credenciais e chaves;
- HTTPS obrigatório em produção;
- CORS limitado às origens do frontend e às rotas necessárias;
- Senhas protegidas pelos mecanismos nativos do Laravel;
- Nunca expor credenciais do Supabase ao navegador para acesso direto ao banco.
