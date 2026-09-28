<script setup lang="ts">
import { SUPPORT_EMAIL } from '~/constants/site'

const route = useRoute()
const sidebarOpen = ref(false)
const { user, logout } = useAuth()

const title = computed(() => (route.meta.title as string | undefined) ?? 'Dashboard')

async function handleLogout(): Promise<void> {
  await logout()
}
</script>

<template>
  <div class="min-h-screen bg-background lg:flex">
    <AppSidebar :mobile-open="sidebarOpen" @close-mobile="sidebarOpen = false" />
    <div class="min-w-0 flex-1">
      <AppHeader :title="title" :user="user" @open-sidebar="sidebarOpen = true" @logout="handleLogout" />
      <main class="mx-auto w-full max-w-7xl p-4 sm:p-6 lg:p-8">
        <slot />
      </main>
      <footer class="px-4 pb-6 text-center text-xs text-muted-foreground sm:px-6 lg:px-8">
        MVP Seguros · Gestão simples para corretores ·
        <a class="underline-offset-4 transition-colors hover:text-foreground hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring" :href="`mailto:${SUPPORT_EMAIL}`">
          {{ SUPPORT_EMAIL }}
        </a>
      </footer>
    </div>
  </div>
</template>
