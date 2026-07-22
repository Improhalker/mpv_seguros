import { fetchLaravel } from '../utils/laravel'

export default defineEventHandler((event) => fetchLaravel(event, '/dashboard', {
  query: getQuery(event) as Record<string, string | number | undefined>,
}))
