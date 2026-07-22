export interface Organization {
  id: number
  name: string
  timezone: string
}

export interface AuthUser {
  id: number
  name: string
  email: string
  is_active: boolean
  organization: Organization
}

export interface AuthResponse {
  user: AuthUser
}
