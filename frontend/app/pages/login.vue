<script setup lang="ts">
import { LoaderCircle, LockKeyhole } from 'lucide-vue-next'

definePageMeta({
  layout: false,
})

const { login, loading, isAuthenticated } = useAuth()
const form = reactive({ email: '', password: '' })
const errorMessage = ref<string | null>(null)

if (isAuthenticated.value) {
  await navigateTo('/dashboard')
}

async function handleSubmit(): Promise<void> {
  errorMessage.value = null

  try {
    await login(form)
    await navigateTo('/dashboard')
  } catch {
    errorMessage.value = 'Não foi possível entrar. Confira seu e-mail e sua senha.'
  }
}
</script>

<template>
  <main class="grid min-h-screen place-items-center bg-background p-4">
    <Card class="w-full max-w-md">
      <CardContent class="p-7 sm:p-8">
        <div class="mb-7 text-center">
          <span class="mx-auto inline-flex size-11 items-center justify-center rounded-xl bg-primary text-lg font-bold text-primary-foreground">M</span>
          <h1 class="mt-4 text-2xl font-semibold tracking-tight">Bem-vindo à MVP Seguros</h1>
          <p class="mt-2 text-sm text-muted-foreground">Entre para acompanhar os seus leads.</p>
        </div>
        <form class="space-y-4" @submit.prevent="handleSubmit">
          <label class="block space-y-1.5 text-sm font-medium">
            <span>E-mail</span>
            <Input v-model="form.email" type="email" autocomplete="email" placeholder="voce@corretora.com" required />
          </label>
          <label class="block space-y-1.5 text-sm font-medium">
            <span>Senha</span>
            <Input v-model="form.password" type="password" autocomplete="current-password" placeholder="Sua senha" required />
          </label>
          <p v-if="errorMessage" role="alert" class="text-sm text-danger">{{ errorMessage }}</p>
          <Button type="submit" class="w-full" :disabled="loading">
            <LoaderCircle v-if="loading" class="size-4 animate-spin" aria-hidden="true" />
            <LockKeyhole v-else class="size-4" aria-hidden="true" />
            Entrar
          </Button>
        </form>
      </CardContent>
    </Card>
  </main>
</template>
