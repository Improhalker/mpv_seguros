# Especificação — Organizações

## Finalidade

Uma organização representa a unidade isolada de dados do produto. Pode corresponder a um corretor individual ou pequena corretora.

## Regras

- Todo usuário pertence a uma organização.
- Todo dado de negócio possui `organization_id`.
- Usuários ativos da mesma organização compartilham leads, observações e interações.
- Não existe acesso entre organizações.
- A organização é obtida a partir do usuário autenticado; a API não aceita uma organização escolhida pelo cliente.
- O MVP não possui troca de organização, multiempresa ou permissões complexas.
