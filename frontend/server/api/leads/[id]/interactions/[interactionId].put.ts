import { fetchLaravel } from '../../../../utils/laravel'

export default defineEventHandler(async (event) => fetchLaravel(event, `/leads/${getRouterParam(event, 'id')}/interactions/${getRouterParam(event, 'interactionId')}`, {
  method: 'PUT',
  body: await readBody(event),
}))
