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
                {{ isEdit ? 'Edit Kredit Poin' : 'Tambah Kredit Poin' }}
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
              <!-- Siswa Selection -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Siswa <span class="text-red-500">*</span>
                </label>
                <select
                  v-model="form.siswa_id"
                  :disabled="isEdit"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  required
                >
                  <option value="">Pilih Siswa</option>
                  <option v-for="siswa in siswaList" :key="siswa.id" :value="siswa.id">
                    {{ siswa.nama_lengkap }} ({{ siswa.nis }})
                  </option>
                </select>
                <p v-if="errors.siswa_id" class="mt-1 text-sm text-red-600">{{ errors.siswa_id[0] }}</p>
              </div>

              <!-- Kategori Selection -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Kategori <span class="text-red-500">*</span>
                </label>
                <select
                  v-model="form.kategori_id"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  required
                  @change="onKategoriChange"
                >
                  <option value="">Pilih Kategori</option>
                  <option v-for="kategori in kategoriList" :key="kategori.id" :value="kategori.id">
                    {{ kategori.nama }} ({{ kategori.jenis === 'positif' ? '+' : '-' }}{{ kategori.nilai_default }})
                  </option>
                </select>
                <p v-if="errors.kategori_id" class="mt-1 text-sm text-red-600">{{ errors.kategori_id[0] }}</p>
              </div>

              <!-- Nilai -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Nilai <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.nilai"
                  type="number"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  required
                />
                <p v-if="errors.nilai" class="mt-1 text-sm text-red-600">{{ errors.nilai[0] }}</p>
                <p class="mt-1 text-sm text-gray-500">
                  Nilai default: {{ selectedKategori?.nilai_default || 0 }}
                </p>
              </div>

              <!-- Deskripsi -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Deskripsi <span class="text-red-500">*</span>
                </label>
                <textarea
                  v-model="form.deskripsi"
                  rows="3"
                  placeholder="Deskripsikan alasan pemberian poin..."
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  required
                ></textarea>
                <p v-if="errors.deskripsi" class="mt-1 text-sm text-red-600">{{ errors.deskripsi[0] }}</p>
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

              <!-- Status (only for edit) -->
              <div v-if="isEdit">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select
                  v-model="form.status"
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                  <option value="pending">Menunggu Persetujuan</option>
                  <option value="approved">Disetujui</option>
                  <option value="rejected">Ditolak</option>
                </select>
                <p v-if="errors.status" class="mt-1 text-sm text-red-600">{{ errors.status[0] }}</p>
              </div>

              <!-- Admin Notes (only for edit) -->
              <div v-if="isEdit">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Admin</label>
                <textarea
                  v-model="form.catatan_admin"
                  rows="2"
                  placeholder="Catatan admin..."
                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
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
  kreditPoin: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'saved'])

// Reactive data
const loading = ref(false)
const siswaList = ref([])
const kategoriList = ref([])
const errors = ref({})

// Form data
const form = reactive({
  siswa_id: '',
  kategori_id: '',
  nilai: '',
  deskripsi: '',
  tanggal: '',
  status: 'pending',
  catatan_admin: ''
})

// Computed
const isEdit = computed(() => !!props.kreditPoin)

const selectedKategori = computed(() => {
  return kategoriList.value.find(k => k.id == form.kategori_id)
})

// Methods
const loadSiswa = async () => {
  try {
    const response = await api.get('/siswa')
    siswaList.value = response.data.data.data
  } catch (error) {
    console.error('Error loading siswa data:', error)
  }
}

const loadKategori = async () => {
  try {
    const response = await api.get('/kategori-kredit-poin')
    kategoriList.value = response.data.data
  } catch (error) {
    console.error('Error loading kategori data:', error)
  }
}

const onKategoriChange = () => {
  if (selectedKategori.value) {
    form.nilai = selectedKategori.value.nilai_default
  }
}

const resetForm = () => {
  Object.assign(form, {
    siswa_id: '',
    kategori_id: '',
    nilai: '',
    deskripsi: '',
    tanggal: '',
    status: 'pending',
    catatan_admin: ''
  })
  errors.value = {}
}

const populateForm = () => {
  if (props.kreditPoin) {
    Object.assign(form, {
      siswa_id: props.kreditPoin.siswa_id || '',
      kategori_id: props.kreditPoin.kategori_id || '',
      nilai: props.kreditPoin.nilai || '',
      deskripsi: props.kreditPoin.deskripsi || '',
      tanggal: props.kreditPoin.tanggal || '',
      status: props.kreditPoin.status || 'pending',
      catatan_admin: props.kreditPoin.catatan_admin || ''
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
      await api.put(`/kredit-poin/${props.kreditPoin.id}`, data)
    } else {
      await api.post('/kredit-poin', data)
    }

    emit('saved')
  } catch (error) {
    if (error.response?.status === 422) {
      errors.value = error.response.data.errors
    } else {
      console.error('Error saving kredit poin:', error)
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

watch(() => props.kreditPoin, () => {
  if (props.show) {
    populateForm()
  }
})

// Lifecycle
onMounted(() => {
  loadSiswa()
  loadKategori()
})
</script>
