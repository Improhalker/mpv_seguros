import { BarChart3, CalendarDays, FileText, LayoutDashboard, LogOut, Settings, Users } from 'lucide-vue-next'
import type { Component } from 'vue'

export interface NavigationItem {
  label: string
  to: string
  icon: Component
  isAvailable: boolean
}

export const primaryNavigation: NavigationItem[] = [
  { label: 'Dashboard', to: '/dashboard', icon: LayoutDashboard, isAvailable: true },
  { label: 'Leads', to: '/leads', icon: Users, isAvailable: true },
  { label: 'Agenda', to: '/agenda', icon: CalendarDays, isAvailable: false },
  { label: 'Relatórios', to: '/relatorios', icon: BarChart3, isAvailable: false },
  { label: 'Configurações', to: '/configuracoes', icon: Settings, isAvailable: false },
]

export const logoutNavigationItem = {
  label: 'Sair',
  icon: LogOut,
}

export const documentNavigationIcon = FileText
