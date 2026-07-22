<script setup lang="ts">
import { ChevronRight, PanelLeftClose, X } from 'lucide-vue-next'
import { primaryNavigation } from '@/constants/navigation'

const props = defineProps<{
  mobileOpen: boolean
}>()

const emit = defineEmits<{
  closeMobile: []
}>()

const route = useRoute()
const collapsed = ref(false)

const isActive = (path: string) => route.path === path
</script>

<template>
  <Teleport to="body">
    <div v-if="props.mobileOpen" class="fixed inset-0 z-40 bg-foreground/30 lg:hidden" aria-hidden="true" @click="emit('closeMobile')" />
  </Teleport>

  <aside
    class="fixed inset-y-0 left-0 z-50 flex border-r bg-card transition-transform lg:sticky lg:top-0 lg:z-20 lg:h-screen lg:translate-x-0"
    :class="[collapsed ? 'lg:w-20' : 'lg:w-64', props.mobileOpen ? 'translate-x-0' : '-translate-x-full']"
    aria-label="Navegação principal"
  >
    <div class="flex w-72 flex-col p-3 lg:w-full">
      <div class="flex h-11 items-center justify-between gap-2 px-2">
        <NuxtLink to="/dashboard" class="flex min-w-0 items-center gap-2 rounded-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring">
          <span class="inline-flex size-8 items-center justify-center rounded-lg bg-primary text-sm font-bold text-primary-foreground">M</span>
          <span v-show="!collapsed" class="truncate font-semibold tracking-tight">MVP Seguros</span>
        </NuxtLink>
        <Button variant="ghost" size="icon" class="lg:hidden" aria-label="Fechar menu" @click="emit('closeMobile')">
          <X class="size-5" aria-hidden="true" />
        </Button>
      </div>

      <nav class="mt-8 flex-1 space-y-1" aria-label="Páginas do sistema">
        <template v-for="item in primaryNavigation" :key="item.to">
          <NuxtLink
            v-if="item.isAvailable"
            :to="item.to"
            class="flex h-10 items-center gap-3 rounded-lg px-3 text-sm font-medium outline-none transition-colors focus-visible:ring-2 focus-visible:ring-ring"
            :class="isActive(item.to) ? 'bg-primary text-primary-foreground' : 'text-muted-foreground hover:bg-accent hover:text-accent-foreground'"
            :aria-current="isActive(item.to) ? 'page' : undefined"
          >
            <component :is="item.icon" class="size-5 shrink-0" aria-hidden="true" />
            <span v-show="!collapsed">{{ item.label }}</span>
          </NuxtLink>
          <button
            v-else
            type="button"
            class="flex h-10 w-full items-center gap-3 rounded-lg px-3 text-left text-sm font-medium text-muted-foreground/70 outline-none transition-colors hover:bg-muted focus-visible:ring-2 focus-visible:ring-ring"
            :aria-label="`${item.label} estará disponível em breve`"
            title="Em breve"
          >
            <component :is="item.icon" class="size-5 shrink-0" aria-hidden="true" />
            <span v-show="!collapsed">{{ item.label }}</span>
          </button>
        </template>
      </nav>

      <button
        type="button"
        class="mt-4 hidden h-10 items-center gap-3 rounded-lg px-3 text-sm font-medium text-muted-foreground hover:bg-accent hover:text-accent-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring lg:flex"
        :aria-label="collapsed ? 'Expandir menu lateral' : 'Recolher menu lateral'"
        @click="collapsed = !collapsed"
      >
        <component :is="collapsed ? ChevronRight : PanelLeftClose" class="size-5 shrink-0" aria-hidden="true" />
        <span v-show="!collapsed">Recolher menu</span>
      </button>
    </div>
  </aside>
</template>
