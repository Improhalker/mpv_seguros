# Roadmap de Implementação do MVP

## Etapa 1 — Fundação técnica

- Criar os projetos Laravel e Nuxt;
- Configurar variáveis de ambiente e conexão com PostgreSQL do Supabase;
- Definir o fluxo Nuxt BFF → Laravel com Sanctum Bearer;
- Criar migrations iniciais e a base de isolamento por organização.

**Resultado esperado:** aplicações executando localmente, banco conectado e estrutura de autenticação definida, sem telas de negócio completas.

## Etapa 2 — Organizações e autenticação

- Cadastro de organização e primeiro usuário;
- Login, logout e recuperação de senha;
- Usuário ativo/inativo;
- Emissão, armazenamento seguro e revogação do token;
- Proteção das rotas autenticadas.

**Resultado esperado:** um usuário ativo autentica e acessa somente a própria organização.

## Etapa 3 — Gestão de leads

- Modelagem, migrations e API de leads;
- Cadastro, edição, exclusão lógica e listagem;
- Status, tipos de seguro e validações;
- Interface de lista e formulário de lead.

**Resultado esperado:** equipe consegue centralizar e acompanhar seus leads.

## Etapa 4 — Observações e interações

- Observações livres;
- Registro de interações por tipo;
- Atualização automática de `last_contact_at` somente por interações;
- Tela de detalhe do lead com histórico.

**Resultado esperado:** o corretor registra contexto e contatos reais sem confundir os dois conceitos.

## Etapa 5 — Follow-ups e dashboard

- Edição de `next_contact_at`;
- Consultas de contatos para hoje, atrasados e próximos;
- Métricas aprovadas do dashboard;
- Navegação rápida do dashboard para o lead.

**Resultado esperado:** a jornada prioritária de acompanhar e agendar contatos funciona de ponta a ponta.

## Etapa 6 — Validação e publicação

- Testes de isolamento entre organizações e fluxos críticos;
- Revisão da experiência com corretor piloto;
- Ajustes orientados por feedback;
- Deploy do frontend e backend.

**Resultado esperado:** versão utilizável por uma equipe piloto para validação comercial.
