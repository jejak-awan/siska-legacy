<template>
  <div class="py-6">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Page Header -->
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Profil Saya</h1>
        <p class="mt-1 text-sm text-gray-500">
          Kelola informasi profil dan pengaturan akun Anda
        </p>
      </div>

      <!-- Profile Content -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Profile Info -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Basic Info Card -->
          <div class="card p-6">
            <div class="flex items-center justify-between mb-6">
              <h3 class="text-lg font-medium text-gray-900">Informasi Dasar</h3>
              <button class="btn btn-outline btn-sm">Edit</button>
            </div>
            
            <div class="space-y-4">
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="form-label">Username</label>
                  <input 
                    type="text" 
                    :value="authStore.user?.username" 
                    class="form-input" 
                    disabled
                  >
                </div>
                <div>
                  <label class="form-label">Email</label>
                  <input 
                    type="email" 
                    :value="authStore.user?.email" 
                    class="form-input" 
                    disabled
                  >
                </div>
              </div>
              
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="form-label">Role</label>
                  <input 
                    type="text" 
                    :value="authStore.userRole" 
                    class="form-input capitalize" 
                    disabled
                  >
                </div>
                <div>
                  <label class="form-label">Status</label>
                  <input 
                    type="text" 
                    :value="authStore.user?.status" 
                    class="form-input capitalize" 
                    disabled
                  >
                </div>
              </div>
            </div>
          </div>

          <!-- Change Password Card -->
          <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-6">Ubah Password</h3>
            
            <form @submit.prevent="handleChangePassword" class="space-y-4">
              <div>
                <label class="form-label">Password Saat Ini</label>
                <input 
                  v-model="passwordForm.current_password"
                  type="password" 
                  class="form-input"
                  required
                >
              </div>
              
              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                  <label class="form-label">Password Baru</label>
                  <input 
                    v-model="passwordForm.new_password"
                    type="password" 
                    class="form-input"
                    required
                  >
                </div>
                <div>
                  <label class="form-label">Konfirmasi Password</label>
                  <input 
                    v-model="passwordForm.new_password_confirmation"
                    type="password" 
                    class="form-input"
                    required
                  >
                </div>
              </div>
              
              <div v-if="passwordError" class="form-error">
                {{ passwordError }}
              </div>
              
              <div class="flex justify-end">
                <button 
                  type="submit" 
                  class="btn btn-primary"
                  :disabled="passwordLoading"
                >
                  <svg v-if="passwordLoading" class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ passwordLoading ? 'Menyimpan...' : 'Ubah Password' }}
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <!-- Profile Picture -->
          <div class="card p-6 text-center">
            <div class="mx-auto h-24 w-24 rounded-full bg-gray-200 flex items-center justify-center mb-4">
              <span class="text-2xl font-bold text-gray-600">
                {{ (authStore.user?.username || 'U').charAt(0).toUpperCase() }}
              </span>
            </div>
            <h3 class="text-lg font-medium text-gray-900">{{ authStore.user?.username }}</h3>
            <p class="text-sm text-gray-500 capitalize">{{ authStore.userRole?.replace('_', ' ') }}</p>
            <button class="btn btn-outline btn-sm mt-4">Ubah Foto</button>
          </div>

          <!-- Quick Stats -->
          <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Statistik</h3>
            <div class="space-y-3">
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Bergabung</span>
                <span class="font-medium">{{ formatDate(authStore.user?.created_at) }}</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Login Terakhir</span>
                <span class="font-medium">Hari ini</span>
              </div>
              <div class="flex justify-between text-sm">
                <span class="text-gray-600">Status</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                  Online
                </span>
              </div>
            </div>
          </div>

          <!-- Account Actions -->
          <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Akun</h3>
            <div class="space-y-3">
              <button class="w-full btn btn-outline text-left">
                <svg class="inline h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Keamanan Akun
              </button>
              
              <button class="w-full btn btn-outline text-left">
                <svg class="inline h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zm-5-5l5 5H9v-5zm0 0V7a3 3 0 116 0v5l-6 5z" />
                </svg>
                Notifikasi
              </button>
              
              <button class="w-full btn btn-outline text-left">
                <svg class="inline h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Download Data
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useAuthStore } from '../../stores/auth'

const authStore = useAuthStore()

// Password change form
const passwordForm = reactive({
  current_password: '',
  new_password: '',
  new_password_confirmation: ''
})

const passwordLoading = ref(false)
const passwordError = ref('')

const handleChangePassword = async () => {
  try {
    passwordLoading.value = true
    passwordError.value = ''

    if (passwordForm.new_password !== passwordForm.new_password_confirmation) {
      passwordError.value = 'Konfirmasi password tidak cocok'
      return
    }

    const result = await authStore.changePassword({
      current_password: passwordForm.current_password,
      new_password: passwordForm.new_password,
      new_password_confirmation: passwordForm.new_password_confirmation
    })

    if (result.success) {
      // Reset form
      Object.keys(passwordForm).forEach(key => {
        passwordForm[key] = ''
      })
      
      alert('Password berhasil diubah!')
    } else {
      passwordError.value = result.error
    }
  } catch (error) {
    passwordError.value = 'Terjadi kesalahan saat mengubah password'
    console.error('Change password error:', error)
  } finally {
    passwordLoading.value = false
  }
}

const formatDate = (date) => {
  if (!date) return '-'
  return new Intl.DateTimeFormat('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(new Date(date))
}
</script>
