<script setup lang="ts">
import { Bell, LogOut, Menu, Search } from 'lucide-vue-next'
import type { AuthUser } from '@/types/auth'

defineProps<{
  title: string
  user: AuthUser | null
}>()

defineEmits<{
  openSidebar: []
  logout: []
}>()
</script>

<template>
  <header class="sticky top-0 z-10 flex min-h-16 items-center gap-3 border-b bg-background/95 px-4 backdrop-blur lg:px-8">
    <Button variant="ghost" size="icon" class="lg:hidden" aria-label="Abrir menu" @click="$emit('openSidebar')">
      <Menu class="size-5" aria-hidden="true" />
    </Button>
    <div class="min-w-0 flex-1">
      <h1 class="truncate text-lg font-semibold tracking-tight">{{ title }}</h1>
    </div>
    <label class="relative hidden w-full max-w-sm md:block">
      <span class="sr-only">Buscar no sistema</span>
      <Search class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-muted-foreground" aria-hidden="true" />
      <Input placeholder="Buscar leads..." class="pl-9" disabled />
    </label>
    <Button variant="ghost" size="icon" aria-label="Notificações (em breve)" disabled>
      <Bell class="size-5" aria-hidden="true" />
    </Button>
    <details class="relative">
      <summary class="list-none rounded-full focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
        <Avatar :name="user?.name ?? 'Usuário'" />
        <span class="sr-only">Abrir menu de usuário</span>
      </summary>
      <div class="absolute right-0 top-11 z-30 w-56 rounded-lg border bg-popover p-1 text-popover-foreground shadow-lg">
        <div class="border-b px-3 py-2">
          <p class="truncate text-sm font-medium">{{ user?.name }}</p>
          <p class="truncate text-xs text-muted-foreground">{{ user?.organization.name }}</p>
        </div>
        <button type="button" class="mt-1 flex w-full items-center gap-2 rounded-md px-3 py-2 text-left text-sm text-danger hover:bg-danger/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring" @click="$emit('logout')">
          <LogOut class="size-4" aria-hidden="true" />
          Sair
        </button>
      </div>
    </details>
  </header>
</template>
