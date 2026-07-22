import type { H3Event } from 'h3'

export const authCookieName = 'mvp_session'

export function laravelApiUrl(event: H3Event, path: string): string {
  const { laravelApiBase } = useRuntimeConfig(event)

  return `${laravelApiBase.replace(/\/$/, '')}/${path.replace(/^\//, '')}`
}

export function authHeaders(event: H3Event): HeadersInit {
  const token = getCookie(event, authCookieName)

  return token ? { Authorization: `Bearer ${token}` } : {}
}

export function persistAuthToken(event: H3Event, token: string): void {
  setCookie(event, authCookieName, token, {
    httpOnly: true,
    sameSite: 'lax',
    secure: !import.meta.dev,
    path: '/',
    maxAge: 60 * 60 * 24 * 30,
  })
}

export function clearAuthToken(event: H3Event): void {
  deleteCookie(event, authCookieName, { path: '/' })
}

interface LaravelRequestOptions {
  method?: 'DELETE' | 'GET' | 'POST' | 'PUT'
  body?: unknown
  query?: Record<string, string | number | undefined>
}

interface LaravelFetchError {
  statusCode?: number
  statusMessage?: string
  data?: unknown
}

export async function fetchLaravel<T>(event: H3Event, path: string, options: LaravelRequestOptions = {}): Promise<T> {
  try {
    const laravelFetch = $fetch as unknown as <Response>(url: string, request: LaravelRequestOptions & { headers: HeadersInit }) => Promise<Response>

    return await laravelFetch<T>(laravelApiUrl(event, path), {
      ...options,
      headers: {
        Accept: 'application/json',
        ...authHeaders(event),
      },
    })
  } catch (fetchError) {
    const error = fetchError as LaravelFetchError

    throw createError({
      statusCode: error.statusCode ?? 500,
      statusMessage: error.statusMessage ?? 'Não foi possível concluir a solicitação.',
      data: error.data,
    })
  }
}
