<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl">
      <!-- Header -->
      <div class="flex items-center justify-between p-6 border-b">
        <h2 class="text-xl font-semibold text-gray-900">
          Daftar Ekstrakurikuler
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

      <!-- Content -->
      <div class="p-6">
        <!-- Ekstrakurikuler Info -->
        <div v-if="ekstrakurikuler" class="mb-6 p-4 bg-gray-50 rounded-lg">
          <h3 class="text-lg font-semibold text-gray-900 mb-2">
            {{ ekstrakurikuler.nama_ekstrakurikuler }}
          </h3>
          <p class="text-gray-600 mb-2">{{ ekstrakurikuler.deskripsi }}</p>
          <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
              <span class="font-medium">Jadwal:</span>
              <span class="text-gray-600">{{ ekstrakurikuler.jadwal_latihan }}</span>
            </div>
            <div>
              <span class="font-medium">Tempat:</span>
              <span class="text-gray-600">{{ ekstrakurikuler.tempat_latihan }}</span>
            </div>
            <div>
              <span class="font-medium">Kuota:</span>
              <span class="text-gray-600">{{ ekstrakurikuler.kuota_anggota || 'Tidak terbatas' }}</span>
            </div>
            <div>
              <span class="font-medium">Biaya:</span>
              <span class="text-gray-600">
                {{ ekstrakurikuler.biaya_pendaftaran ? `Rp ${ekstrakurikuler.biaya_pendaftaran.toLocaleString()}` : 'Gratis' }}
              </span>
            </div>
          </div>
        </div>

        <!-- Form -->
        <form @submit.prevent="handleSubmit">
          <div class="space-y-4">
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
                <option v-for="siswa in availableStudents" :key="siswa.id" :value="siswa.id">
                  {{ siswa.nama_lengkap }} ({{ siswa.nis }}) - {{ siswa.kelas?.nama_kelas }}
                </option>
              </select>
            </div>

            <!-- Catatan -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Catatan
              </label>
              <textarea
                v-model="form.catatan"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Alasan mendaftar atau catatan khusus"
              ></textarea>
            </div>

            <!-- Persyaratan -->
            <div v-if="ekstrakurikuler?.persyaratan_anggota" class="p-4 bg-blue-50 rounded-lg">
              <h4 class="font-medium text-blue-900 mb-2">Persyaratan Anggota:</h4>
              <p class="text-blue-800 text-sm">{{ ekstrakurikuler.persyaratan_anggota }}</p>
            </div>

            <!-- Fasilitas -->
            <div v-if="ekstrakurikuler?.fasilitas" class="p-4 bg-green-50 rounded-lg">
              <h4 class="font-medium text-green-900 mb-2">Fasilitas:</h4>
              <p class="text-green-800 text-sm">{{ ekstrakurikuler.fasilitas }}</p>
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
              <span v-if="loading">Mendaftar...</span>
              <span v-else>Daftar</span>
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted, computed } from 'vue'
import api from '@/services/api'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  ekstrakurikuler: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'registered'])

const loading = ref(false)
const allStudents = ref([])
const registeredStudents = ref([])

const form = ref({
  siswa_id: '',
  catatan: ''
})

// Computed properties
const availableStudents = computed(() => {
  if (!allStudents.value.length) return []
  
  const registeredIds = registeredStudents.value.map(reg => reg.siswa_id)
  return allStudents.value.filter(siswa => !registeredIds.includes(siswa.id))
})

// Load data
onMounted(async () => {
  await loadStudents()
})

// Watch for ekstrakurikuler changes
watch(() => props.ekstrakurikuler, async (newEkstrakurikuler) => {
  if (newEkstrakurikuler) {
    await loadRegisteredStudents(newEkstrakurikuler.id)
  }
}, { immediate: true })

// Watch for modal open/close
watch(() => props.isOpen, (isOpen) => {
  if (!isOpen) {
    resetForm()
  }
})

const loadStudents = async () => {
  try {
    const response = await api.get('/siswa')
    allStudents.value = response.data.data.data || []
  } catch (error) {
    console.error('Error loading students:', error)
  }
}

const loadRegisteredStudents = async (ekstrakurikulerId) => {
  try {
    const response = await api.get(`/ekstrakurikuler/${ekstrakurikulerId}/students`)
    registeredStudents.value = response.data.data || []
  } catch (error) {
    console.error('Error loading registered students:', error)
  }
}

const resetForm = () => {
  form.value = {
    siswa_id: '',
    catatan: ''
  }
}

const handleSubmit = async () => {
  if (!props.ekstrakurikuler) return
  
  loading.value = true
  
  try {
    await api.post('/ekstrakurikuler/register-student', {
      ekstrakurikuler_id: props.ekstrakurikuler.id,
      siswa_id: form.value.siswa_id,
      catatan: form.value.catatan
    })
    
    emit('registered')
    closeModal()
  } catch (error) {
    console.error('Error registering student:', error)
    alert('Terjadi kesalahan saat mendaftarkan siswa')
  } finally {
    loading.value = false
  }
}

const closeModal = () => {
  emit('close')
}
</script>
