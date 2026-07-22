import { fetchLaravel } from '../../utils/laravel'

export default defineEventHandler(async (event) => fetchLaravel(event, `/leads/${getRouterParam(event, 'id')}`, {
  method: 'PUT',
  body: await readBody(event),
}))
