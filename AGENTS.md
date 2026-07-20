# CRM para Corretores de Seguros (MVP)

## Visão do Projeto

Este projeto tem como objetivo criar um SaaS simples e intuitivo para corretores de seguros.

A proposta NÃO é competir com CRMs gigantes como HubSpot, Pipedrive ou RD Station.

Nosso objetivo é resolver um problema específico:

> Ajudar corretores a organizar seus leads, acompanhar contatos e aumentar suas vendas de forma simples.

Sempre que houver dúvida sobre adicionar uma funcionalidade, faça a seguinte pergunta:

> Essa funcionalidade ajuda o corretor a vender mais seguros com menos esforço?

Se a resposta for "não", ela provavelmente não pertence ao MVP.

---

# Objetivo do MVP

O MVP deve ser pequeno, rápido e utilizável.

A primeira versão será utilizada por um corretor de seguros real (irmão do fundador), que fornecerá feedback constante durante o desenvolvimento.

Nosso objetivo é colocar um sistema funcionando rapidamente para validar a ideia no mercado.

Não buscamos perfeição.

Buscamos validação.

---

# Público-Alvo

Este sistema foi pensado para:

- Corretores de seguros autônomos
- Pequenas corretoras
- Equipes de até 10 usuários

Hoje esses profissionais normalmente utilizam:

- Planilhas Excel
- WhatsApp
- Agenda
- Bloco de notas
- Google Agenda

O sistema deverá substituir essas ferramentas por uma única plataforma simples.

---

# Filosofia de Desenvolvimento

Priorizar sempre:

- Simplicidade
- Facilidade de uso
- Rapidez
- Organização
- Código limpo
- Evolução constante

Evitar complexidade desnecessária.

Não implementar funcionalidades "porque podem ser úteis no futuro".

Tudo deve resolver um problema real.

---

# Funcionalidades do MVP

## Autenticação

- Login
- Cadastro
- Recuperação de senha

---

## Dashboard

Exibir rapidamente informações importantes.

Exemplos:

- Total de Leads
- Leads cadastrados hoje
- Negócios fechados
- Negócios perdidos
- Contatos pendentes para hoje

---

## Cadastro de Leads

Cada lead deve possuir:

- Nome
- Telefone
- E-mail
- Tipo de seguro
- Status
- Observações
- Próxima data de contato
- Último contato
- Data de criação

---

## Status do Lead

Inicialmente utilizar:

- Novo
- Primeiro Contato
- Em Negociação
- Aguardando Cliente
- Venda Fechada
- Perdido

No futuro esses status poderão ser personalizados.

---

## Histórico de Observações

Cada lead poderá possuir diversas observações.

Cada observação deve registrar:

- Autor
- Data
- Texto

Não é necessário histórico de edição no MVP.

---

## Lembretes

O sistema deve informar diariamente quais clientes precisam receber contato.

Esse é um dos principais diferenciais do produto.

---

# Funcionalidades Fora do Escopo

Estas funcionalidades NÃO fazem parte do MVP.

Somente implementar caso sejam solicitadas futuramente.

- Integração com WhatsApp
- Inteligência Artificial
- Automações
- Kanban
- Funil avançado
- Disparo de e-mails
- Gestão financeira
- Controle de comissões
- Relatórios avançados
- Aplicativo Mobile
- Multiempresa
- Controle complexo de permissões
- Dashboard analítico avançado
- Integrações externas

O foco é validar o produto.

---

# Stack Tecnológica

## Front-end

- Nuxt
- Vue 3
- TypeScript
- TailwindCSS
- shadcn-vue

## Back-end

- Laravel
- API REST

## Banco de Dados

- PostgreSQL (Supabase)

## Autenticação

- Laravel Sanctum

## Armazenamento

- Supabase Storage

## Deploy

Frontend:

- Vercel

Backend:

- VPS própria ou Render

---

# Arquitetura

Sempre priorizar organização.

Fluxo preferencial:

Controller

↓

Service

↓

Model

Criar Repository apenas quando houver necessidade real.

Evitar abstrações desnecessárias.

---

# Padrões de Código

Escrever código simples.

Priorizar legibilidade.

Nomes de classes, métodos e variáveis devem ser claros.

Evitar comentários desnecessários.

Aplicar boas práticas do Laravel.

Seguir SOLID quando fizer sentido.

Evitar dependências externas sem necessidade.

---

# Interface

A interface deve ser limpa.

Poucos botões.

Poucos cliques.

O fluxo ideal do corretor deve ser:

Entrar no sistema

↓

Visualizar quem precisa ser contatado hoje

↓

Abrir o Lead

↓

Registrar a ligação

↓

Adicionar observação

↓

Agendar próximo contato

Tudo isso em menos de um minuto.

---

# Processo de Desenvolvimento

O primeiro usuário será um corretor de seguros real.

Toda funcionalidade deve seguir este fluxo:

Desenvolver

↓

Disponibilizar para uso

↓

Receber feedback

↓

Ajustar

↓

Somente então criar novas funcionalidades.

Nunca desenvolver baseado apenas em suposições.

---

# Visão de Futuro

Após validar o MVP poderão ser desenvolvidos módulos como:

- WhatsApp Business
- Renovação automática de seguros
- Gestão de comissões
- Dashboard avançado
- Aplicativo Mobile
- IA para sugestões de follow-up
- Equipes e permissões
- Integrações com seguradoras
- Relatórios
- Agenda integrada

Esses recursos NÃO são prioridade neste momento.

---

# Regra Mais Importante

Sempre que existir mais de uma forma de implementar uma funcionalidade, escolha aquela que:

- seja mais simples;
- seja mais fácil de manter;
- gere menos código;
- entregue valor ao usuário mais rapidamente.

Nosso objetivo é validar o negócio o quanto antes.

Feito é melhor que perfeito.

# Mentalidade do Projeto

Este projeto não está sendo desenvolvido apenas para estudo.

O objetivo é construir um produto comercial.

Toda decisão técnica deve considerar:

- Facilidade de manutenção;
- Escalabilidade futura;
- Boa experiência do usuário;
- Desenvolvimento rápido do MVP;
- Possibilidade de monetização.

O código deve ser profissional, mas sem excesso de engenharia.

Sempre que possível, priorize soluções nativas do Laravel e do Vue antes de adicionar novas bibliotecas.