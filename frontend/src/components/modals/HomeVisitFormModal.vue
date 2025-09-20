<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
      <!-- Header -->
      <div class="flex items-center justify-between p-6 border-b">
        <h2 class="text-xl font-semibold text-gray-900">
          {{ isEditing ? 'Edit Home Visit' : 'Tambah Home Visit' }}
        </h2>
        <button
          @click="closeModal"
          class="text-gray-400 hover:text-gray-600 transition-colors"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Form -->
      <form @submit.prevent="handleSubmit" class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Siswa -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Siswa <span class="text-red-500">*</span>
            </label>
            <select
              v-model="form.siswa_id"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            >
              <option value="">Pilih Siswa</option>
              <option v-for="siswa in siswaList" :key="siswa.id" :value="siswa.id">
                {{ siswa.nama_lengkap }} ({{ siswa.nis }})
              </option>
            </select>
          </div>

          <!-- Konselor -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Konselor <span class="text-red-500">*</span>
            </label>
            <select
              v-model="form.konselor_id"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            >
              <option value="">Pilih Konselor</option>
              <option v-for="konselor in konselorList" :key="konselor.id" :value="konselor.id">
                {{ konselor.name }}
              </option>
            </select>
          </div>

          <!-- Tanggal Kunjungan -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Tanggal Kunjungan <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.tanggal_kunjungan"
              type="date"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>

          <!-- Jam Mulai -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Jam Mulai <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.jam_mulai"
              type="time"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>

          <!-- Jam Selesai -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Jam Selesai <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.jam_selesai"
              type="time"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>

          <!-- Status -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Status <span class="text-red-500">*</span>
            </label>
            <select
              v-model="form.status"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            >
              <option value="">Pilih Status</option>
              <option value="terjadwal">Terjadwal</option>
              <option value="berlangsung">Berlangsung</option>
              <option value="selesai">Selesai</option>
              <option value="dibatalkan">Dibatalkan</option>
            </select>
          </div>

          <!-- Alamat Kunjungan -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Alamat Kunjungan <span class="text-red-500">*</span>
            </label>
            <textarea
              v-model="form.alamat_kunjungan"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Alamat lengkap tempat kunjungan"
              required
            ></textarea>
          </div>

          <!-- Koordinat -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Latitude
            </label>
            <input
              v-model="form.koordinat_lat"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="-6.9175"
            />
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Longitude
            </label>
            <input
              v-model="form.koordinat_lng"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="107.6191"
            />
          </div>

          <!-- Tujuan Kunjungan -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Tujuan Kunjungan <span class="text-red-500">*</span>
            </label>
            <textarea
              v-model="form.tujuan_kunjungan"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Tujuan dari kunjungan ke rumah siswa"
              required
            ></textarea>
          </div>

          <!-- Hasil Kunjungan -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Hasil Kunjungan
            </label>
            <textarea
              v-model="form.hasil_kunjungan"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Hasil yang dicapai dari kunjungan"
            ></textarea>
          </div>

          <!-- Catatan Kunjungan -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Catatan Kunjungan
            </label>
            <textarea
              v-model="form.catatan_kunjungan"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Catatan tambahan dari kunjungan"
            ></textarea>
          </div>

          <!-- Tindak Lanjut -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Tindak Lanjut
            </label>
            <textarea
              v-model="form.tindak_lanjut"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Rencana tindak lanjut setelah kunjungan"
            ></textarea>
          </div>

          <!-- Foto Kunjungan -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Foto Kunjungan
            </label>
            <input
              v-model="form.foto_kunjungan"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Nama file foto"
            />
          </div>

          <!-- Checkboxes -->
          <div class="flex gap-6">
            <label class="flex items-center">
              <input
                v-model="form.is_urgent"
                type="checkbox"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
              />
              <span class="ml-2 text-sm text-gray-700">Urgent</span>
            </label>
            <label class="flex items-center">
              <input
                v-model="form.is_confidential"
                type="checkbox"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
              />
              <span class="ml-2 text-sm text-gray-700">Confidential</span>
            </label>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3 mt-6 pt-6 border-t">
          <button
            type="button"
            @click="closeModal"
            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors"
          >
            Batal
          </button>
          <button
            type="submit"
            :disabled="loading"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 transition-colors"
          >
            <span v-if="loading">Menyimpan...</span>
            <span v-else>{{ isEditing ? 'Update' : 'Simpan' }}</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import api from '@/services/api'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  homeVisit: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'saved'])

const loading = ref(false)
const siswaList = ref([])
const konselorList = ref([])

const form = ref({
  siswa_id: '',
  konselor_id: '',
  tanggal_kunjungan: '',
  jam_mulai: '',
  jam_selesai: '',
  status: '',
  alamat_kunjungan: '',
  koordinat_lat: '',
  koordinat_lng: '',
  tujuan_kunjungan: '',
  hasil_kunjungan: '',
  catatan_kunjungan: '',
  tindak_lanjut: '',
  foto_kunjungan: '',
  is_urgent: false,
  is_confidential: false
})

const isEditing = ref(false)

// Load data
onMounted(async () => {
  await loadSiswaList()
  await loadKonselorList()
})

// Watch for homeVisit changes
watch(() => props.homeVisit, (newHomeVisit) => {
  if (newHomeVisit) {
    isEditing.value = true
    form.value = { ...newHomeVisit }
  } else {
    isEditing.value = false
    resetForm()
  }
}, { immediate: true })

// Watch for modal open/close
watch(() => props.isOpen, (isOpen) => {
  if (!isOpen) {
    resetForm()
  }
})

const loadSiswaList = async () => {
  try {
    const response = await api.get('/siswa')
    siswaList.value = response.data.data.data || []
  } catch (error) {
    console.error('Error loading siswa list:', error)
  }
}

const loadKonselorList = async () => {
  try {
    const response = await api.get('/users/role/bk')
    konselorList.value = response.data.data || []
  } catch (error) {
    console.error('Error loading konselor list:', error)
  }
}

const resetForm = () => {
  form.value = {
    siswa_id: '',
    konselor_id: '',
    tanggal_kunjungan: '',
    jam_mulai: '',
    jam_selesai: '',
    status: '',
    alamat_kunjungan: '',
    koordinat_lat: '',
    koordinat_lng: '',
    tujuan_kunjungan: '',
    hasil_kunjungan: '',
    catatan_kunjungan: '',
    tindak_lanjut: '',
    foto_kunjungan: '',
    is_urgent: false,
    is_confidential: false
  }
  isEditing.value = false
}

const handleSubmit = async () => {
  loading.value = true
  
  try {
    if (isEditing.value) {
      await api.put(`/home-visit/${props.homeVisit.id}`, form.value)
    } else {
      await api.post('/home-visit', form.value)
    }
    
    emit('saved')
    closeModal()
  } catch (error) {
    console.error('Error saving home visit:', error)
    alert('Terjadi kesalahan saat menyimpan data')
  } finally {
    loading.value = false
  }
}

const closeModal = () => {
  emit('close')
}
</script>
