import tailwindcss from '@tailwindcss/vite'

export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  modules: ['@nuxt/eslint', 'shadcn-nuxt'],
  css: ['~/assets/css/main.css'],
  components: [
    {
      path: '~/components',
      pathPrefix: false,
    },
  ],
  vite: {
    plugins: [tailwindcss()],
  },
  runtimeConfig: {
    laravelApiBase: import.meta.env.LARAVEL_API_BASE ?? 'http://localhost:8000/api',
  },
  shadcn: {
    prefix: '',
    componentDir: './app/components/ui',
  },
})
