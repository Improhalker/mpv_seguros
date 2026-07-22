import type { AuthUser } from '@/types/auth'
import { laravelApiUrl, persistAuthToken } from '../../utils/laravel'

interface LaravelLoginResponse {
  token: string
  user: AuthUser
}

export default defineEventHandler(async (event) => {
  const body = await readBody<{ email?: string, password?: string }>(event)

  if (!body.email || !body.password) {
    throw createError({ statusCode: 422, statusMessage: 'E-mail e senha são obrigatórios.' })
  }

  try {
    const response = await $fetch<LaravelLoginResponse>(laravelApiUrl(event, '/login'), {
      method: 'POST',
      body: { email: body.email, password: body.password },
      headers: { Accept: 'application/json' },
    })

    persistAuthToken(event, response.token)

    return { user: response.user }
  } catch (error) {
    const fetchError = error as { status?: number, statusCode?: number }
    const statusCode = fetchError.statusCode ?? fetchError.status ?? 502

    throw createError({
      statusCode,
      statusMessage: statusCode === 422 ? 'E-mail ou senha inválidos.' : 'Não foi possível acessar o servidor de autenticação.',
    })
  }
})
