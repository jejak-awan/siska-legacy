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
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
            </svg>
          </div>
          <div class="mt-3 text-center sm:mt-5">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
              Pindah Kelas Siswa
            </h3>
            
            <!-- Student Info -->
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
              <div class="flex items-center justify-center">
                <div class="flex-shrink-0 h-12 w-12">
                  <div class="h-12 w-12 rounded-full flex items-center justify-center"
                       :class="siswa?.jenis_kelamin === 'L' ? 'bg-blue-100' : 'bg-pink-100'">
                    <span class="font-semibold text-lg"
                          :class="siswa?.jenis_kelamin === 'L' ? 'text-blue-600' : 'text-pink-600'">
                      {{ siswa?.nama_lengkap?.charAt(0).toUpperCase() }}
                    </span>
                  </div>
                </div>
                <div class="ml-4 text-left">
                  <div class="text-lg font-medium text-gray-900">
                    {{ siswa?.nama_lengkap }}
                  </div>
                  <div class="text-sm text-gray-500">
                    NISN: {{ siswa?.nisn }} | NIS: {{ siswa?.nis }}
                  </div>
                  <div class="text-sm text-gray-500">
                    Kelas Saat Ini: 
                    <span v-if="siswa?.kelas_relation" class="font-medium text-blue-600">
                      {{ siswa.kelas_relation.nama_kelas }}
                    </span>
                    <span v-else class="text-red-500">Belum ada kelas</span>
                  </div>
                </div>
              </div>
            </div>
            
            <form @submit.prevent="submitForm" class="space-y-4">
              <!-- Kelas Selection -->
              <div class="text-left">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Pilih Kelas Baru <span class="text-red-500">*</span>
                </label>
                <select
                  v-model="selectedKelasId"
                  required
                  class="form-select w-full"
                  :class="{ 'border-red-500': errors.kelas_id }"
                >
                  <option value="">Pilih Kelas</option>
                  <optgroup v-for="tingkat in groupedKelas" :key="tingkat.tingkat" :label="`Kelas ${tingkat.tingkat}`">
                    <option v-for="kelas in tingkat.kelas" :key="kelas.id" :value="kelas.id">
                      {{ kelas.nama_kelas }} 
                      <span v-if="kelas.wali_kelas">(Wali: {{ kelas.wali_kelas.nama_lengkap }})</span>
                      - Kapasitas: {{ kelas.current_capacity || 0 }}/{{ kelas.kapasitas }}
                    </option>
                  </optgroup>
                </select>
                <p v-if="errors.kelas_id" class="mt-1 text-sm text-red-600">
                  {{ errors.kelas_id[0] }}
                </p>
              </div>

              <!-- Selected Class Info -->
              <div v-if="selectedKelas" class="p-4 bg-blue-50 rounded-lg text-left">
                <h4 class="text-sm font-medium text-blue-900 mb-2">Informasi Kelas Terpilih</h4>
                <div class="text-sm text-blue-700 space-y-1">
                  <div><strong>Nama Kelas:</strong> {{ selectedKelas.nama_kelas }}</div>
                  <div><strong>Tingkat:</strong> {{ selectedKelas.tingkat }}</div>
                  <div><strong>Jurusan:</strong> {{ selectedKelas.jurusan }}</div>
                  <div v-if="selectedKelas.wali_kelas">
                    <strong>Wali Kelas:</strong> {{ selectedKelas.wali_kelas.nama_lengkap }}
                  </div>
                  <div>
                    <strong>Kapasitas:</strong> 
                    {{ selectedKelas.current_capacity || 0 }}/{{ selectedKelas.kapasitas }}
                    <span v-if="selectedKelas.current_capacity >= selectedKelas.kapasitas" class="text-red-600 font-medium">
                      (Kelas Penuh!)
                    </span>
                  </div>
                </div>
              </div>

              <!-- Warning if class is full -->
              <div v-if="selectedKelas && selectedKelas.current_capacity >= selectedKelas.kapasitas" 
                   class="p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex">
                  <svg class="h-5 w-5 text-red-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
                  </svg>
                  <div class="text-sm text-red-700">
                    <strong>Peringatan:</strong> Kelas ini sudah mencapai kapasitas maksimum. 
                    Memindahkan siswa ke kelas ini akan melebihi kapasitas yang ditentukan.
                  </div>
                </div>
              </div>

              <!-- Alasan Pindah (Optional) -->
              <div class="text-left">
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Alasan Pindah Kelas (Opsional)
                </label>
                <textarea
                  v-model="reason"
                  rows="3"
                  class="form-textarea w-full"
                  placeholder="Masukkan alasan perpindahan kelas..."
                ></textarea>
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
                  :disabled="loading || !selectedKelasId"
                >
                  <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  Pindah Kelas
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
import { ref, computed, watch, onMounted } from 'vue'
import api from '../../services/api'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  siswa: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'saved'])

const loading = ref(false)
const errors = ref({})
const kelasList = ref([])
const selectedKelasId = ref('')
const reason = ref('')

// Computed property to get selected class details
const selectedKelas = computed(() => {
  if (!selectedKelasId.value) return null
  return kelasList.value.find(kelas => kelas.id == selectedKelasId.value)
})

// Group kelas by tingkat for better organization
const groupedKelas = computed(() => {
  const grouped = {}
  
  kelasList.value.forEach(kelas => {
    if (!grouped[kelas.tingkat]) {
      grouped[kelas.tingkat] = {
        tingkat: kelas.tingkat,
        kelas: []
      }
    }
    grouped[kelas.tingkat].kelas.push(kelas)
  })
  
  // Sort by tingkat and return as array
  return Object.values(grouped).sort((a, b) => a.tingkat - b.tingkat)
})

// Load kelas list
const loadKelasList = async () => {
  try {
    const response = await api.get('/kelas')
    kelasList.value = response.data.data || []
    
    // Load current capacity for each class
    for (const kelas of kelasList.value) {
      try {
        const siswaResponse = await api.get(`/siswa/kelas/${kelas.id}`)
        kelas.current_capacity = siswaResponse.data.data.length
      } catch (error) {
        kelas.current_capacity = 0
      }
    }
  } catch (error) {
    console.error('Error loading kelas list:', error)
    kelasList.value = []
  }
}

// Watch for siswa prop changes to reset form
watch(() => props.siswa, (newSiswa) => {
  selectedKelasId.value = ''
  reason.value = ''
  errors.value = {}
}, { immediate: true })

const submitForm = async () => {
  loading.value = true
  errors.value = {}
  
  try {
    await api.post(`/siswa/${props.siswa.id}/assign-class`, {
      kelas_id: selectedKelasId.value,
      reason: reason.value
    })
    
    emit('saved')
    
    const kelasName = selectedKelas.value?.nama_kelas || 'kelas baru'
    alert(`${props.siswa.nama_lengkap} berhasil dipindahkan ke ${kelasName}`)
    
  } catch (error) {
    console.error('Error assigning class:', error)
    
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

onMounted(() => {
  if (props.show) {
    loadKelasList()
  }
})

// Watch for show prop to load data when modal opens
watch(() => props.show, (newShow) => {
  if (newShow) {
    loadKelasList()
  }
})
</script>
