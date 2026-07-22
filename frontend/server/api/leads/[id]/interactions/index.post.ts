import { fetchLaravel } from '../../../../utils/laravel'

export default defineEventHandler(async (event) => fetchLaravel(event, `/leads/${getRouterParam(event, 'id')}/interactions`, {
  method: 'POST',
  body: await readBody(event),
}))
