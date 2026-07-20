# CRM para Corretores de Seguros — MVP

## Objetivo

Construir um SaaS simples para corretores de seguros e pequenas equipes organizarem leads, registrarem contatos e acompanharem follow-ups. O produto deve ajudar o corretor a vender mais seguros com menos esforço, sem tentar reproduzir CRMs generalistas.

O MVP será validado por usuários reais e prioriza rapidez de entrega, simplicidade de uso e manutenção previsível.

## Público inicial

- Corretores autônomos;
- Pequenas corretoras;
- Equipes de até 10 usuários.

## Escopo aprovado

- Organizações com múltiplos usuários ativos;
- Autenticação, cadastro e recuperação de senha gerenciados pelo Laravel;
- Leads compartilhados entre os usuários da mesma organização;
- Cadastro, listagem, edição e alteração manual de status de leads;
- Observações livres e interações de contato separadas;
- Dashboard com métricas básicas;
- Lembretes apresentados como listas de contatos para hoje, atrasados e próximos.

Cada dado de negócio pertence a uma organização. Um usuário só pode consultar ou alterar dados de sua própria organização. Não haverá permissões complexas no MVP: todo usuário ativo pode visualizar e editar os leads da organização.

## Fora do escopo

- WhatsApp, e-mail, push e outras notificações automáticas;
- Integrações externas;
- IA, automações e tarefas em segundo plano;
- Kanban e funil avançado;
- Gestão financeira, comissões e relatórios avançados;
- Aplicativo mobile;
- Multiempresa para o mesmo usuário;
- Papéis e permissões complexos;
- Tipos de seguro personalizáveis pela interface.

## Diretrizes de produto

- A jornada prioritária é: abrir o sistema, identificar quem deve ser contatado, registrar a interação e agendar o próximo contato.
- Preferir soluções nativas do Laravel e Nuxt antes de bibliotecas adicionais.
- Evitar abstrações e funcionalidades antecipadas.
- Tratar o MVP como produto comercial, com isolamento de dados, rastreabilidade básica e boa experiência de uso.
