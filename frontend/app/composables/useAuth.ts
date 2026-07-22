import type { AuthResponse, AuthUser } from '@/types/auth'

interface LoginPayload {
  email: string
  password: string
}

export function useAuth() {
  const user = useState<AuthUser | null>('auth-user', () => null)
  const loading = useState<boolean>('auth-loading', () => false)

  async function fetchCurrentUser(): Promise<AuthUser | null> {
    loading.value = true

    try {
      const response = await $fetch<AuthResponse>('/api/auth/me')
      user.value = response.user
      return user.value
    } catch {
      user.value = null
      return null
    } finally {
      loading.value = false
    }
  }

  async function login(payload: LoginPayload): Promise<AuthUser> {
    loading.value = true

    try {
      const response = await $fetch<AuthResponse>('/api/auth/login', {
        method: 'POST',
        body: payload,
      })
      user.value = response.user
      return user.value
    } finally {
      loading.value = false
    }
  }

  async function logout(): Promise<void> {
    try {
      await $fetch('/api/auth/logout', { method: 'POST' })
    } finally {
      user.value = null
      await navigateTo('/login')
    }
  }

  return {
    user: readonly(user),
    loading: readonly(loading),
    isAuthenticated: computed(() => user.value !== null),
    fetchCurrentUser,
    login,
    logout,
  }
}
