import { authHeaders, clearAuthToken, laravelApiUrl } from '../../utils/laravel'

export default defineEventHandler(async (event) => {
  try {
    await $fetch(laravelApiUrl(event, '/logout'), {
      method: 'POST',
      headers: {
        Accept: 'application/json',
        ...authHeaders(event),
      },
    })
  } finally {
    clearAuthToken(event)
  }

  setResponseStatus(event, 204)
})
