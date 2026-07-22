import { fetchLaravel } from '../../../../utils/laravel'

export default defineEventHandler((event) => fetchLaravel(event, `/leads/${getRouterParam(event, 'id')}/interactions`))
