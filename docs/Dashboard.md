# Especificação resumida do Dashboard

O dashboard é a tela inicial do corretor. Seu objetivo é mostrar o volume básico de leads e, principalmente, indicar quem precisa de contato.

Todos os números e listas são restritos à organização do usuário autenticado. O fuso horário de referência é `America/Sao_Paulo`.

## Métricas

- **Total de leads:** todos os leads não excluídos da organização.
- **Cadastrados hoje:** leads criados no dia atual.
- **Negócios fechados:** leads com status `fechado`.
- **Negócios perdidos:** leads com status `perdido`.
- **Contatos para hoje:** leads cujo `next_contact_at` esteja no dia atual.
- **Contatos atrasados:** leads cujo `next_contact_at` já tenha passado e cujo status não seja `fechado` nem `perdido`.

## Lembretes

Os lembretes são consultas exibidas na aplicação, derivadas exclusivamente de `next_contact_at`. Não serão enviados e-mails, mensagens de WhatsApp, push notifications ou executadas tarefas em segundo plano no MVP.

Além dos contatos para hoje e atrasados, a aplicação deverá oferecer uma lista de próximos contatos, ordenada por `next_contact_at` crescente, para leads não encerrados.
