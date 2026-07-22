import { fetchLaravel } from '../../utils/laravel'

export default defineEventHandler((event) => fetchLaravel(event, '/leads', {
  query: getQuery(event) as Record<string, string | number | undefined>,
}))
