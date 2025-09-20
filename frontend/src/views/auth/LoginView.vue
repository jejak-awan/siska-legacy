<template>
  <div class="min-h-screen flex items-center justify-center bg-background py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
      <!-- Header -->
      <div>
        <div class="mx-auto h-12 w-12 flex items-center justify-center rounded-full bg-primary/10">
          <svg class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
          </svg>
        </div>
        <h2 class="mt-6 text-center text-3xl font-extrabold text-foreground">
          Sistem Manajemen Kesiswaan
        </h2>
        <p class="mt-2 text-center text-sm text-muted-foreground">
          Masuk ke akun Anda
        </p>
      </div>

      <!-- Login Form -->
      <form class="mt-8 space-y-6" @submit.prevent="handleLogin">
        <div class="rounded-md shadow-sm -space-y-px">
          <div>
            <label for="username" class="sr-only">Username atau Email</label>
            <input
              id="username"
              v-model="form.username"
              name="username"
              type="text"
              autocomplete="username"
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-input placeholder-muted-foreground text-foreground rounded-t-md focus:outline-none focus:ring-ring focus:border-ring focus:z-10 sm:text-sm bg-background"
              :class="{ 'border-red-500': errors.username }"
              placeholder="Username atau Email"
              :disabled="isLoading"
            >
            <p v-if="errors.username" class="mt-1 text-sm text-red-600">
              {{ errors.username }}
            </p>
          </div>
          <div>
            <label for="password" class="sr-only">Password</label>
            <input
              id="password"
              v-model="form.password"
              name="password"
              type="password"
              autocomplete="current-password"
              class="appearance-none rounded-none relative block w-full px-3 py-2 border border-input placeholder-muted-foreground text-foreground rounded-b-md focus:outline-none focus:ring-ring focus:border-ring focus:z-10 sm:text-sm bg-background"
              :class="{ 'border-red-500': errors.password }"
              placeholder="Password"
              :disabled="isLoading"
            >
            <p v-if="errors.password" class="mt-1 text-sm text-red-600">
              {{ errors.password }}
            </p>
          </div>
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between">
          <div class="flex items-center">
            <input
              id="remember-me"
              v-model="form.remember"
              name="remember-me"
              type="checkbox"
              class="h-4 w-4 text-primary focus:ring-primary border-input rounded"
            >
            <label for="remember-me" class="ml-2 block text-sm text-foreground">
              Ingat saya
            </label>
          </div>
        </div>

        <!-- Submit Button -->
        <div>
          <button
            type="submit"
            :disabled="isLoading"
            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-primary-foreground bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
          >
            <span class="absolute left-0 inset-y-0 flex items-center pl-3">
              <svg v-if="isLoading" class="animate-spin h-5 w-5 text-primary-foreground/70" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <svg v-else class="h-5 w-5 text-primary-foreground/70 group-hover:text-primary-foreground" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
              </svg>
            </span>
            {{ isLoading ? 'Masuk...' : 'Masuk' }}
          </button>
        </div>

        <!-- Back to Landing Page -->
        <div class="text-center">
          <router-link 
            to="/" 
            class="text-sm text-muted-foreground hover:text-foreground transition-colors"
          >
            ← Kembali ke Beranda
          </router-link>
        </div>

      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import { useToast } from '../../plugins/toast.js'

const router = useRouter()
const route = useRoute()
const authStore = useAuthStore()
const toast = useToast()

// Form state
const form = reactive({
  username: '',
  password: '',
  remember: false
})

const isLoading = ref(false)
const errors = ref({})

const handleLogin = async () => {
  try {
    isLoading.value = true
    errors.value = {}

    // Basic validation
    if (!form.username) {
      errors.value.username = 'Username atau email wajib diisi'
      isLoading.value = false
      return
    }
    if (!form.password) {
      errors.value.password = 'Password wajib diisi'
      isLoading.value = false
      return
    }

    const result = await authStore.login({
      username: form.username,
      password: form.password
    })

    console.log('Login result:', result)

    if (result.success) {
      // Show success message
      console.log('Login berhasil, showing toast...')
      try {
        toast.success('Login berhasil!')
      } catch (toastErr) {
        alert('Login berhasil!')
      }
      
      // Redirect to intended page or dashboard
      const redirectPath = route.query.redirect || '/dashboard'
      await router.push(redirectPath)
    } else {
      // Show error message
      console.log('Login gagal, showing error toast...', result.error)
      try {
        toast.error(result.error || 'Username atau password salah')
      } catch (toastErr) {
        alert(result.error || 'Username atau password salah')
      }
    }
  } catch (err) {
    // Show error message
    try {
      toast.error('Terjadi kesalahan saat login. Silakan coba lagi.')
    } catch (toastErr) {
      alert('Terjadi kesalahan saat login. Silakan coba lagi.')
    }
    console.error('Login error:', err)
  } finally {
    isLoading.value = false
  }
}
</script>
