import type { AuthUser } from '@/types/auth'
import { authCookieName, authHeaders, clearAuthToken, laravelApiUrl } from '../../utils/laravel'

interface LaravelMeResponse {
  user: AuthUser
}

export default defineEventHandler(async (event) => {
  if (!getCookie(event, authCookieName)) {
    throw createError({ statusCode: 401, statusMessage: 'Não autenticado.' })
  }

  try {
    return await $fetch<LaravelMeResponse>(laravelApiUrl(event, '/me'), {
      headers: {
        Accept: 'application/json',
        ...authHeaders(event),
      },
    })
  } catch {
    clearAuthToken(event)
    throw createError({ statusCode: 401, statusMessage: 'Sessão inválida.' })
  }
})
