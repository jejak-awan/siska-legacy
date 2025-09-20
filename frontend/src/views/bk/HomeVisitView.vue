<template>
  <div class="p-6">
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Home Visit</h1>
      <p class="text-gray-600">Manajemen kunjungan rumah siswa</p>
    </div>

    <!-- Action Buttons -->
    <div class="mb-6 flex justify-between items-center">
      <div class="flex space-x-3">
        <button
          @click="openCreateModal"
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
        >
          <svg class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          Tambah Home Visit
        </button>
      </div>
      
      <div class="flex space-x-2">
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Cari siswa..."
          class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        />
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
          <div class="p-2 bg-blue-100 rounded-lg">
            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Home Visit</p>
            <p class="text-2xl font-semibold text-gray-900">{{ statistics.total }}</p>
          </div>
        </div>
      </div>
      
      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
          <div class="p-2 bg-green-100 rounded-lg">
            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Selesai</p>
            <p class="text-2xl font-semibold text-gray-900">{{ statistics.completed }}</p>
          </div>
        </div>
      </div>
      
      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
          <div class="p-2 bg-yellow-100 rounded-lg">
            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Terjadwal</p>
            <p class="text-2xl font-semibold text-gray-900">{{ statistics.scheduled }}</p>
          </div>
        </div>
      </div>
      
      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
          <div class="p-2 bg-red-100 rounded-lg">
            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Dibatalkan</p>
            <p class="text-2xl font-semibold text-gray-900">{{ statistics.cancelled }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Home Visit Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Siswa
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Tanggal Visit
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Alamat
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Aksi
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="visit in filteredHomeVisits" :key="visit.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-blue-600 font-semibold text-sm">
                      {{ visit.siswa.nama_lengkap.charAt(0).toUpperCase() }}
                    </span>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ visit.siswa.nama_lengkap }}</div>
                    <div class="text-sm text-gray-500">{{ visit.siswa.kelas }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ formatDate(visit.tanggal_visit) }}
              </td>
              <td class="px-6 py-4 text-sm text-gray-900">
                <div class="max-w-xs truncate">{{ visit.alamat_visit }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStatusBadgeClass(visit.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                  {{ visit.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button
                    @click="viewDetails(visit)"
                    class="text-blue-600 hover:text-blue-900"
                  >
                    Lihat
                  </button>
                  <button
                    v-if="visit.status === 'Terjadwal'"
                    @click="editVisit(visit)"
                    class="text-indigo-600 hover:text-indigo-900"
                  >
                    Edit
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">
            {{ isEditing ? 'Edit Home Visit' : 'Tambah Home Visit' }}
          </h3>
          
          <form @submit.prevent="saveVisit" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Siswa</label>
              <select v-model="form.siswa_id" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                <option value="">Pilih Siswa</option>
                <option v-for="siswa in students" :key="siswa.id" :value="siswa.id">
                  {{ siswa.nama_lengkap }} - {{ siswa.kelas }}
                </option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700">Tanggal Visit</label>
              <input
                v-model="form.tanggal_visit"
                type="date"
                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2"
                required
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700">Alamat Visit</label>
              <textarea
                v-model="form.alamat_visit"
                rows="3"
                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2"
                required
              ></textarea>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700">Tujuan Visit</label>
              <textarea
                v-model="form.tujuan_visit"
                rows="3"
                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2"
                required
              ></textarea>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
              <button
                type="button"
                @click="closeModal"
                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
              >
                Batal
              </button>
              <button
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
              >
                {{ isEditing ? 'Update' : 'Simpan' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

// Reactive data
const homeVisits = ref([])
const students = ref([])
const searchQuery = ref('')
const showModal = ref(false)
const isEditing = ref(false)
const form = ref({
  id: null,
  siswa_id: '',
  tanggal_visit: '',
  alamat_visit: '',
  tujuan_visit: '',
  hasil_visit: '',
  tindak_lanjut: '',
  status: 'Terjadwal'
})

// Statistics
const statistics = ref({
  total: 0,
  completed: 0,
  scheduled: 0,
  cancelled: 0
})

// Computed
const filteredHomeVisits = computed(() => {
  if (!searchQuery.value) return homeVisits.value
  
  return homeVisits.value.filter(visit =>
    visit.siswa.nama_lengkap.toLowerCase().includes(searchQuery.value.toLowerCase())
  )
})

// Methods
const loadHomeVisits = async () => {
  try {
    // Mock data - replace with actual API call
    homeVisits.value = [
      {
        id: 1,
        siswa: { nama_lengkap: 'Ahmad Rizki', kelas: 'X-A' },
        tanggal_visit: '2025-09-20',
        alamat_visit: 'Jl. Merdeka No. 123, Bandung',
        tujuan_visit: 'Konseling masalah akademik',
        status: 'Terjadwal'
      },
      {
        id: 2,
        siswa: { nama_lengkap: 'Siti Nurhaliza', kelas: 'XI-B' },
        tanggal_visit: '2025-09-18',
        alamat_visit: 'Jl. Sudirman No. 456, Bandung',
        tujuan_visit: 'Konseling masalah keluarga',
        status: 'Selesai'
      }
    ]
    
    updateStatistics()
  } catch (error) {
    console.error('Error loading home visits:', error)
  }
}

const loadStudents = async () => {
  try {
    // Mock data - replace with actual API call
    students.value = [
      { id: 1, nama_lengkap: 'Ahmad Rizki', kelas: 'X-A' },
      { id: 2, nama_lengkap: 'Siti Nurhaliza', kelas: 'XI-B' }
    ]
  } catch (error) {
    console.error('Error loading students:', error)
  }
}

const updateStatistics = () => {
  statistics.value = {
    total: homeVisits.value.length,
    completed: homeVisits.value.filter(v => v.status === 'Selesai').length,
    scheduled: homeVisits.value.filter(v => v.status === 'Terjadwal').length,
    cancelled: homeVisits.value.filter(v => v.status === 'Dibatalkan').length
  }
}

const openCreateModal = () => {
  isEditing.value = false
  form.value = {
    id: null,
    siswa_id: '',
    tanggal_visit: '',
    alamat_visit: '',
    tujuan_visit: '',
    hasil_visit: '',
    tindak_lanjut: '',
    status: 'Terjadwal'
  }
  showModal.value = true
}

const editVisit = (visit) => {
  isEditing.value = true
  form.value = { ...visit }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  isEditing.value = false
}

const saveVisit = async () => {
  try {
    if (isEditing.value) {
      // Update existing visit
      const index = homeVisits.value.findIndex(v => v.id === form.value.id)
      if (index !== -1) {
        homeVisits.value[index] = { ...form.value }
      }
    } else {
      // Create new visit
      const newVisit = {
        ...form.value,
        id: Date.now(),
        siswa: students.value.find(s => s.id === parseInt(form.value.siswa_id))
      }
      homeVisits.value.unshift(newVisit)
    }
    
    updateStatistics()
    closeModal()
  } catch (error) {
    console.error('Error saving visit:', error)
  }
}

const viewDetails = (visit) => {
  // Implement view details functionality
  console.log('View details:', visit)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID')
}

const getStatusBadgeClass = (status) => {
  const classes = {
    'Terjadwal': 'bg-yellow-100 text-yellow-800',
    'Selesai': 'bg-green-100 text-green-800',
    'Dibatalkan': 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

// Lifecycle
onMounted(() => {
  loadHomeVisits()
  loadStudents()
})
</script>
