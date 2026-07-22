# API de leads

Base Laravel: `backend/routes/api.php`. As rotas Nuxt equivalentes estão em `frontend/server/api/leads/` e encaminham a chamada ao Laravel pelo BFF.

> Estado atual: estas rotas de negócio não possuem middleware `auth:sanctum` no arquivo de rotas. Os endpoints abaixo descrevem o comportamento observável, não uma promessa de autorização por organização.

## Leads

| Método e endpoint | Request/serviço | Resultado |
| --- | --- | --- |
| `GET /api/leads` | `ListLeadsRequest`, `LeadService::paginate()` | Lista paginada de `LeadResource`. |
| `POST /api/leads` | `StoreLeadRequest`, `LeadService::create()` | Lead criado, HTTP 201. |
| `GET /api/leads/{lead}` | `LeadController::show()` | Lead com `assignedUser` quando houver. |
| `PUT /api/leads/{lead}` | `UpdateLeadRequest`, `LeadService::update()` | Lead atualizado. |
| `DELETE /api/leads/{lead}` | `LeadController::destroy()` | HTTP 204 e soft delete. |

### Filtros de listagem

`search`, `status`, `insurance_type`, `urgency`, `source`, `assigned_user_id`, `contact_period` (`overdue`, `today`, `upcoming`), `min_score`, `max_score`, `sort_by`, `sort_direction`, `page` e `per_page`. `min_score` e `max_score` são validados de 0 a 10; `max_score` deve ser maior ou igual ao mínimo.

### Payload mínimo de criação

```json
{
  "name": "Nome do cliente",
  "phone": "11999999999",
  "insurance_type": "auto"
}
```

O payload completo usa snake_case na API. O frontend converte `LeadInput` camelCase em `frontend/app/utils/lead.ts`. Erros de validação retornam HTTP 422; registros inexistentes retornam 404; a criação bem-sucedida retorna 201.

## Interações

| Método e endpoint | Request/serviço | Efeito |
| --- | --- | --- |
| `GET /api/leads/{lead}/interactions` | `LeadInteractionController::index()` | Lista paginada, mais recentes primeiro. |
| `POST /api/leads/{lead}/interactions` | `StoreLeadInteractionRequest`, `LeadInteractionService::create()` | Cria interação, HTTP 201 e recalcula `last_contact_at`. |
| `PUT /api/leads/{lead}/interactions/{interaction}` | `UpdateLeadInteractionRequest`, `update()` | Atualiza a interação e recalcula datas. |
| `DELETE /api/leads/{lead}/interactions/{interaction}` | `delete()` | Remove interação e recalcula `last_contact_at`; HTTP 204. |

Payload de criação: `type`, `description`, `occurred_at` são obrigatórios; `next_contact_at` é opcional. Se a interação não pertencer ao lead da URL, o controller retorna 404.

## Autenticação já disponível

`POST /api/register`, `POST /api/login`, `POST /api/logout` e `GET /api/me` existem. Somente logout e me estão protegidos no Laravel atualmente. O BFF possui `server/api/auth/` para login, logout e me; não existe página Nuxt de cadastro no código atual.
