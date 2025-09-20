<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal"></div>

      <!-- Modal panel -->
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <form @submit.prevent="handleSubmit">
          <!-- Header -->
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-medium text-gray-900">Kirim Notifikasi</h3>
              <button
                type="button"
                @click="closeModal"
                class="text-gray-400 hover:text-gray-600 transition-colors"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <!-- Form Fields -->
            <div class="space-y-4">
              <!-- User Selection -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Penerima <span class="text-red-500">*</span>
                </label>
                <select
                  v-model="form.user_ids"
                  multiple
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  required
                >
                  <option v-for="user in users" :key="user.id" :value="user.id">
                    {{ user.username }} ({{ user.email }})
                  </option>
                </select>
                <p v-if="errors.user_ids" class="mt-1 text-sm text-red-600">{{ errors.user_ids[0] }}</p>
                <p class="mt-1 text-sm text-gray-500">Gunakan Ctrl/Cmd untuk memilih multiple user</p>
              </div>

              <!-- Title -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Judul <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.judul"
                  type="text"
                  placeholder="Judul notifikasi"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  required
                />
                <p v-if="errors.judul" class="mt-1 text-sm text-red-600">{{ errors.judul[0] }}</p>
              </div>

              <!-- Message -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Pesan <span class="text-red-500">*</span>
                </label>
                <textarea
                  v-model="form.pesan"
                  rows="3"
                  placeholder="Isi pesan notifikasi..."
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  required
                ></textarea>
                <p v-if="errors.pesan" class="mt-1 text-sm text-red-600">{{ errors.pesan[0] }}</p>
              </div>

              <!-- Type -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Tipe <span class="text-red-500">*</span>
                </label>
                <select
                  v-model="form.tipe"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  required
                >
                  <option value="">Pilih Tipe</option>
                  <option value="info">Informasi</option>
                  <option value="warning">Peringatan</option>
                  <option value="error">Error</option>
                  <option value="success">Berhasil</option>
                </select>
                <p v-if="errors.tipe" class="mt-1 text-sm text-red-600">{{ errors.tipe[0] }}</p>
              </div>

              <!-- Action URL -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">URL Aksi</label>
                <input
                  v-model="form.action_url"
                  type="url"
                  placeholder="https://example.com/action"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <p v-if="errors.action_url" class="mt-1 text-sm text-red-600">{{ errors.action_url[0] }}</p>
              </div>

              <!-- Action Text -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Teks Tombol Aksi</label>
                <input
                  v-model="form.action_text"
                  type="text"
                  placeholder="Lihat Detail"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <p v-if="errors.action_text" class="mt-1 text-sm text-red-600">{{ errors.action_text[0] }}</p>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="submit"
              :disabled="loading"
              class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="loading" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>
              Kirim
            </button>
            <button
              type="button"
              @click="closeModal"
              class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Batal
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import api from '@/services/api'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'saved'])

// Reactive data
const loading = ref(false)
const users = ref([])
const errors = ref({})

// Form data
const form = reactive({
  user_ids: [],
  judul: '',
  pesan: '',
  tipe: '',
  action_url: '',
  action_text: ''
})

// Methods
const loadUsers = async () => {
  try {
    const response = await api.get('/users')
    users.value = response.data.data.data
  } catch (error) {
    console.error('Error loading users:', error)
  }
}

const resetForm = () => {
  Object.assign(form, {
    user_ids: [],
    judul: '',
    pesan: '',
    tipe: '',
    action_url: '',
    action_text: ''
  })
  errors.value = {}
}

const handleSubmit = async () => {
  loading.value = true
  errors.value = {}

  try {
    const data = { ...form }
    
    // Convert empty strings to null for optional fields
    Object.keys(data).forEach(key => {
      if (data[key] === '') {
        data[key] = null
      }
    })

    await api.post('/notifications/send-to-users', data)
    emit('saved')
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors
    } else {
      console.error('Error sending notification:', error)
    }
  } finally {
    loading.value = false
  }
}

const closeModal = () => {
  resetForm()
  emit('close')
}

// Lifecycle
onMounted(() => {
  loadUsers()
})
</script>
