import { fetchLaravel } from '../../utils/laravel'

export default defineEventHandler(async (event) => fetchLaravel(event, '/leads', {
  method: 'POST',
  body: await readBody(event),
}))
