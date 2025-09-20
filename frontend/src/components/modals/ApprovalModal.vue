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
              <h3 class="text-lg font-medium text-gray-900">
                {{ action === 'approve' ? 'Setujui Kredit Poin' : 'Tolak Kredit Poin' }}
              </h3>
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

            <!-- Kredit Poin Info -->
            <div v-if="kreditPoin" class="bg-gray-50 rounded-lg p-4 mb-4">
              <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                  <span class="font-medium text-gray-700">Siswa:</span>
                  <p class="text-gray-900">{{ kreditPoin.siswa?.nama_lengkap }}</p>
                </div>
                <div>
                  <span class="font-medium text-gray-700">NIS:</span>
                  <p class="text-gray-900">{{ kreditPoin.siswa?.nis }}</p>
                </div>
                <div>
                  <span class="font-medium text-gray-700">Kategori:</span>
                  <p class="text-gray-900">{{ kreditPoin.kategori?.nama }}</p>
                </div>
                <div>
                  <span class="font-medium text-gray-700">Nilai:</span>
                  <p class="text-gray-900">
                    <span :class="getNilaiClass(kreditPoin.kategori?.jenis)">
                      {{ getNilaiFormatted(kreditPoin.nilai, kreditPoin.kategori?.jenis) }}
                    </span>
                  </p>
                </div>
                <div class="col-span-2">
                  <span class="font-medium text-gray-700">Deskripsi:</span>
                  <p class="text-gray-900">{{ kreditPoin.deskripsi }}</p>
                </div>
                <div>
                  <span class="font-medium text-gray-700">Tanggal:</span>
                  <p class="text-gray-900">{{ formatDate(kreditPoin.tanggal) }}</p>
                </div>
                <div>
                  <span class="font-medium text-gray-700">Pelapor:</span>
                  <p class="text-gray-900">{{ kreditPoin.pelapor?.username }}</p>
                </div>
              </div>
            </div>

            <!-- Form Fields -->
            <div class="space-y-4">
              <!-- Admin Notes -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Catatan Admin
                  <span v-if="action === 'reject'" class="text-red-500">*</span>
                </label>
                <textarea
                  v-model="form.catatan_admin"
                  rows="3"
                  :placeholder="action === 'approve' ? 'Catatan admin (opsional)...' : 'Alasan penolakan (wajib)...'"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  :required="action === 'reject'"
                ></textarea>
                <p v-if="errors.catatan_admin" class="mt-1 text-sm text-red-600">{{ errors.catatan_admin[0] }}</p>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              type="submit"
              :disabled="loading"
              :class="[
                'w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 text-base font-medium text-white focus:outline-none focus:ring-2 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed',
                action === 'approve' 
                  ? 'bg-green-600 hover:bg-green-700 focus:ring-green-500'
                  : 'bg-red-600 hover:bg-red-700 focus:ring-red-500'
              ]"
            >
              <span v-if="loading" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>
              {{ action === 'approve' ? 'Setujui' : 'Tolak' }}
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
import { ref, reactive } from 'vue'
import api from '@/services/api'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  kreditPoin: {
    type: Object,
    default: null
  },
  action: {
    type: String,
    default: 'approve',
    validator: (value) => ['approve', 'reject'].includes(value)
  }
})

const emit = defineEmits(['close', 'approved'])

// Reactive data
const loading = ref(false)
const errors = ref({})

// Form data
const form = reactive({
  catatan_admin: ''
})

// Methods
const handleSubmit = async () => {
  loading.value = true
  errors.value = {}

  try {
    const endpoint = props.action === 'approve' ? 'approve' : 'reject'
    await api.post(`/kredit-poin/${props.kreditPoin.id}/${endpoint}`, form)

    emit('approved')
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors
    } else {
      console.error(`Error ${props.action}ing kredit poin:`, error)
    }
  } finally {
    loading.value = false
  }
}

const closeModal = () => {
  form.catatan_admin = ''
  errors.value = {}
  emit('close')
}

// Utility functions
const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID')
}

const getNilaiFormatted = (nilai, jenis) => {
  const prefix = jenis === 'positif' ? '+' : '-'
  return prefix + Math.abs(nilai)
}

const getNilaiClass = (jenis) => {
  return jenis === 'positif' ? 'text-green-600 font-medium' : 'text-red-600 font-medium'
}
</script>
