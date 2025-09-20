<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 transition-opacity" @click="$emit('close')">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
      </div>

      <!-- Modal content -->
      <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
        <div>
          <div class="mt-3 text-center sm:mt-0 sm:text-left">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
              {{ user ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}
            </h3>
            
            <form @submit.prevent="submitForm" class="space-y-4">
              <!-- Username -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Username <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.username"
                  type="text"
                  required
                  class="form-input"
                  :class="{ 'border-red-500': errors.username }"
                  placeholder="Masukkan username"
                />
                <p v-if="errors.username" class="mt-1 text-sm text-red-600">
                  {{ errors.username[0] }}
                </p>
              </div>

              <!-- Email -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Email <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.email"
                  type="email"
                  required
                  class="form-input"
                  :class="{ 'border-red-500': errors.email }"
                  placeholder="Masukkan email"
                />
                <p v-if="errors.email" class="mt-1 text-sm text-red-600">
                  {{ errors.email[0] }}
                </p>
              </div>

              <!-- Password -->
              <div v-if="!user">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Password <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.password"
                  type="password"
                  required
                  class="form-input"
                  :class="{ 'border-red-500': errors.password }"
                  placeholder="Masukkan password"
                />
                <p v-if="errors.password" class="mt-1 text-sm text-red-600">
                  {{ errors.password[0] }}
                </p>
              </div>

              <!-- Change Password (for edit) -->
              <div v-if="user">
                <label class="flex items-center">
                  <input
                    v-model="changePassword"
                    type="checkbox"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                  />
                  <span class="ml-2 text-sm text-gray-700">Ubah password</span>
                </label>
                
                <div v-if="changePassword" class="mt-2">
                  <input
                    v-model="form.password"
                    type="password"
                    class="form-input"
                    :class="{ 'border-red-500': errors.password }"
                    placeholder="Masukkan password baru"
                  />
                  <p v-if="errors.password" class="mt-1 text-sm text-red-600">
                    {{ errors.password[0] }}
                  </p>
                </div>
              </div>

              <!-- Role Type -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Role <span class="text-red-500">*</span>
                </label>
                <select
                  v-model="form.role_type"
                  required
                  class="form-select"
                  :class="{ 'border-red-500': errors.role_type }"
                >
                  <option value="">Pilih Role</option>
                  <option value="admin">Admin</option>
                  <option value="guru">Guru</option>
                  <option value="siswa">Siswa</option>
                  <option value="tendik">Tenaga Kependidikan</option>
                  <option value="bk">BK</option>
                  <option value="wali_kelas">Wali Kelas</option>
                  <option value="osis">OSIS</option>
                  <option value="ekstrakurikuler">Ekstrakurikuler</option>
                  <option value="orang_tua">Orang Tua</option>
                </select>
                <p v-if="errors.role_type" class="mt-1 text-sm text-red-600">
                  {{ errors.role_type[0] }}
                </p>
              </div>

              <!-- Status -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                  Status
                </label>
                <select
                  v-model="form.status"
                  class="form-select"
                  :class="{ 'border-red-500': errors.status }"
                >
                  <option value="aktif">Aktif</option>
                  <option value="nonaktif">Non Aktif</option>
                  <option value="suspended">Suspended</option>
                </select>
                <p v-if="errors.status" class="mt-1 text-sm text-red-600">
                  {{ errors.status[0] }}
                </p>
              </div>

              <!-- Two Factor -->
              <div>
                <label class="flex items-center">
                  <input
                    v-model="form.two_factor_enabled"
                    type="checkbox"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                  />
                  <span class="ml-2 text-sm text-gray-700">Aktifkan Two Factor Authentication</span>
                </label>
              </div>

              <!-- Form Actions -->
              <div class="mt-6 flex items-center justify-end space-x-3">
                <button
                  type="button"
                  @click="$emit('close')"
                  class="btn btn-secondary"
                  :disabled="loading"
                >
                  Batal
                </button>
                <button
                  type="submit"
                  class="btn btn-primary"
                  :disabled="loading"
                >
                  <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ user ? 'Update' : 'Simpan' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, watch } from 'vue'
import api from '../../services/api'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  user: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'saved'])

const loading = ref(false)
const changePassword = ref(false)
const errors = ref({})

const form = reactive({
  username: '',
  email: '',
  password: '',
  role_type: '',
  status: 'aktif',
  two_factor_enabled: false
})

// Watch for user prop changes
watch(() => props.user, (newUser) => {
  if (newUser) {
    form.username = newUser.username || ''
    form.email = newUser.email || ''
    form.password = ''
    form.role_type = newUser.role_type || ''
    form.status = newUser.status || 'aktif'
    form.two_factor_enabled = newUser.two_factor_enabled || false
  } else {
    // Reset form for new user
    form.username = ''
    form.email = ''
    form.password = ''
    form.role_type = ''
    form.status = 'aktif'
    form.two_factor_enabled = false
  }
  
  changePassword.value = false
  errors.value = {}
}, { immediate: true })

const submitForm = async () => {
  loading.value = true
  errors.value = {}
  
  try {
    const payload = { ...form }
    
    // Remove password if not changing (for edit)
    if (props.user && !changePassword.value) {
      delete payload.password
    }
    
    if (props.user) {
      // Update existing user
      await api.put(`/users/${props.user.id}`, payload)
    } else {
      // Create new user
      await api.post('/users', payload)
    }
    
    emit('saved')
    
    // Show success message
    const action = props.user ? 'diupdate' : 'dibuat'
    alert(`Pengguna berhasil ${action}`)
    
  } catch (error) {
    console.error('Error saving user:', error)
    
    if (error.response?.status === 422) {
      // Validation errors
      errors.value = error.response.data.errors || {}
    } else {
      alert(`Error: ${error.response?.data?.message || 'Terjadi kesalahan'}`)
    }
  } finally {
    loading.value = false
  }
}
</script>
