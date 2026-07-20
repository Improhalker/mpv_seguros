# Especificação — Usuários e Autenticação

## Usuários

- Nome, e-mail, senha, organização e situação ativa são os dados mínimos.
- Usuários inativos não podem iniciar ou manter acesso à API.
- Todos os usuários ativos podem visualizar e editar os leads da própria organização.

## Autenticação

- Laravel é a única fonte de autenticação.
- Laravel Sanctum emite tokens Bearer.
- Supabase Auth não é utilizado.
- O Nuxt mantém o token somente em cookie HttpOnly no servidor/BFF e o encaminha ao Laravel nas chamadas autenticadas.
- O navegador não armazena o token em mecanismos acessíveis por JavaScript.
- Login, cadastro, logout e recuperação de senha pertencem ao escopo do MVP.
