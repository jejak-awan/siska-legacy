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
                {{ isEdit ? 'Edit Presensi' : 'Tambah Presensi' }}
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

            <!-- Form Fields -->
            <div class="space-y-4">
              <!-- User Selection -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  User <span class="text-red-500">*</span>
                </label>
                <select
                  v-model="form.user_id"
                  :disabled="isEdit"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  required
                >
                  <option value="">Pilih User</option>
                  <option v-for="user in users" :key="user.id" :value="user.id">
                    {{ user.username }} ({{ user.email }})
                  </option>
                </select>
                <p v-if="errors.user_id" class="mt-1 text-sm text-red-600">{{ errors.user_id[0] }}</p>
              </div>

              <!-- Date -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Tanggal <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.tanggal"
                  type="date"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  required
                />
                <p v-if="errors.tanggal" class="mt-1 text-sm text-red-600">{{ errors.tanggal[0] }}</p>
              </div>

              <!-- Time Fields -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Jam Masuk</label>
                  <input
                    v-model="form.jam_masuk"
                    type="time"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  />
                  <p v-if="errors.jam_masuk" class="mt-1 text-sm text-red-600">{{ errors.jam_masuk[0] }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Jam Keluar</label>
                  <input
                    v-model="form.jam_keluar"
                    type="time"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  />
                  <p v-if="errors.jam_keluar" class="mt-1 text-sm text-red-600">{{ errors.jam_keluar[0] }}</p>
                </div>
              </div>

              <!-- Status -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Status <span class="text-red-500">*</span>
                </label>
                <select
                  v-model="form.status"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  required
                >
                  <option value="">Pilih Status</option>
                  <option value="hadir">Hadir</option>
                  <option value="terlambat">Terlambat</option>
                  <option value="sakit">Sakit</option>
                  <option value="izin">Izin</option>
                  <option value="alpha">Alpha</option>
                </select>
                <p v-if="errors.status" class="mt-1 text-sm text-red-600">{{ errors.status[0] }}</p>
              </div>

              <!-- Location Fields -->
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Latitude</label>
                  <input
                    v-model="form.lokasi_lat"
                    type="number"
                    step="any"
                    placeholder="Contoh: -6.2088"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  />
                  <p v-if="errors.lokasi_lat" class="mt-1 text-sm text-red-600">{{ errors.lokasi_lat[0] }}</p>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Longitude</label>
                  <input
                    v-model="form.lokasi_lng"
                    type="number"
                    step="any"
                    placeholder="Contoh: 106.8456"
                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  />
                  <p v-if="errors.lokasi_lng" class="mt-1 text-sm text-red-600">{{ errors.lokasi_lng[0] }}</p>
                </div>
              </div>

              <!-- QR Code -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">QR Code</label>
                <input
                  v-model="form.qr_code"
                  type="text"
                  placeholder="Kode QR presensi"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <p v-if="errors.qr_code" class="mt-1 text-sm text-red-600">{{ errors.qr_code[0] }}</p>
              </div>

              <!-- Photo -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Foto Absen</label>
                <input
                  v-model="form.foto_absen"
                  type="text"
                  placeholder="Path foto absen"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                />
                <p v-if="errors.foto_absen" class="mt-1 text-sm text-red-600">{{ errors.foto_absen[0] }}</p>
              </div>

              <!-- Validator -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Validator</label>
                <select
                  v-model="form.divalidasi_oleh"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                  <option value="">Pilih Validator</option>
                  <option v-for="user in users" :key="user.id" :value="user.id">
                    {{ user.username }} ({{ user.email }})
                  </option>
                </select>
                <p v-if="errors.divalidasi_oleh" class="mt-1 text-sm text-red-600">{{ errors.divalidasi_oleh[0] }}</p>
              </div>

              <!-- Description -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                <textarea
                  v-model="form.keterangan"
                  rows="3"
                  placeholder="Keterangan tambahan..."
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                ></textarea>
                <p v-if="errors.keterangan" class="mt-1 text-sm text-red-600">{{ errors.keterangan[0] }}</p>
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
              {{ isEdit ? 'Update' : 'Simpan' }}
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
import { ref, reactive, computed, onMounted, watch } from 'vue'
import api from '@/services/api'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  presensi: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'saved'])

// Reactive data
const loading = ref(false)
const users = ref([])
const errors = ref({})

// Form data
const form = reactive({
  user_id: '',
  tanggal: '',
  jam_masuk: '',
  jam_keluar: '',
  status: '',
  lokasi_lat: '',
  lokasi_lng: '',
  qr_code: '',
  foto_absen: '',
  divalidasi_oleh: '',
  keterangan: ''
})

// Computed
const isEdit = computed(() => !!props.presensi)

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
    user_id: '',
    tanggal: '',
    jam_masuk: '',
    jam_keluar: '',
    status: '',
    lokasi_lat: '',
    lokasi_lng: '',
    qr_code: '',
    foto_absen: '',
    divalidasi_oleh: '',
    keterangan: ''
  })
  errors.value = {}
}

const populateForm = () => {
  if (props.presensi) {
    Object.assign(form, {
      user_id: props.presensi.user_id || '',
      tanggal: props.presensi.tanggal || '',
      jam_masuk: props.presensi.jam_masuk || '',
      jam_keluar: props.presensi.jam_keluar || '',
      status: props.presensi.status || '',
      lokasi_lat: props.presensi.lokasi_lat || '',
      lokasi_lng: props.presensi.lokasi_lng || '',
      qr_code: props.presensi.qr_code || '',
      foto_absen: props.presensi.foto_absen || '',
      divalidasi_oleh: props.presensi.divalidasi_oleh || '',
      keterangan: props.presensi.keterangan || ''
    })
  }
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

    if (isEdit.value) {
      await api.put(`/presensi/${props.presensi.id}`, data)
    } else {
      await api.post('/presensi', data)
    }

    emit('saved')
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors
    } else {
      console.error('Error saving presensi:', error)
    }
  } finally {
    loading.value = false
  }
}

const closeModal = () => {
  resetForm()
  emit('close')
}

// Watchers
watch(() => props.show, (newVal) => {
  if (newVal) {
    populateForm()
  }
})

watch(() => props.presensi, () => {
  if (props.show) {
    populateForm()
  }
})

// Lifecycle
onMounted(() => {
  loadUsers()
})
</script>
