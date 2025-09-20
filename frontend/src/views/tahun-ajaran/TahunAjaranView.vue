<template>
  <div class="tahun-ajaran-view">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Tahun Ajaran</h1>
      <p class="text-gray-600">Kelola tahun ajaran dan periode akademik</p>
    </div>

    <!-- Action Bar -->
    <div class="flex justify-between items-center mb-6">
      <div class="flex space-x-4">
        <button
          @click="showAddModal = true"
          class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
          <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          Tambah Tahun Ajaran
        </button>
        
        <button
          @click="loadTahunAjaran"
          class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
        >
          <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
          </svg>
          Refresh
        </button>
      </div>
      
      <div class="flex items-center space-x-2">
        <label class="text-sm font-medium text-gray-700">Filter:</label>
        <select
          v-model="filterStatus"
          @change="filterTahunAjaran"
          class="px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option value="">Semua Status</option>
          <option value="aktif">Aktif</option>
          <option value="tidak-aktif">Tidak Aktif</option>
          <option value="selesai">Selesai</option>
        </select>
      </div>
    </div>

    <!-- Current Academic Year Card -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg shadow-lg p-6 mb-6 text-white">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-lg font-semibold mb-2">Tahun Ajaran Aktif</h3>
          <p class="text-2xl font-bold">{{ getCurrentTahunAjaran() }}</p>
          <p class="text-blue-100 mt-1">Semester {{ getCurrentSemester() }}</p>
        </div>
        <div class="text-right">
          <div class="text-sm text-blue-100">Sisa Hari</div>
          <div class="text-3xl font-bold">{{ getRemainingDays() }}</div>
        </div>
      </div>
    </div>

    <!-- Academic Year List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Daftar Tahun Ajaran</h3>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Ajaran</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Semester</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Kelas</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="tahun in filteredTahunAjaran" :key="tahun.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ tahun.tahun_ajaran }}</div>
                <div class="text-sm text-gray-500">{{ tahun.keterangan }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ formatDate(tahun.tanggal_mulai) }} - {{ formatDate(tahun.tanggal_selesai) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                  {{ tahun.semester }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStatusBadgeClass(tahun.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                  {{ getStatusText(tahun.status) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ tahun.jumlah_kelas || 0 }} kelas
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button
                  @click="editTahunAjaran(tahun)"
                  class="text-blue-600 hover:text-blue-900 mr-3"
                >
                  Edit
                </button>
                <button
                  @click="setActiveTahunAjaran(tahun.id)"
                  v-if="tahun.status !== 'aktif'"
                  class="text-green-600 hover:text-green-900 mr-3"
                >
                  Set Aktif
                </button>
                <button
                  @click="deleteTahunAjaran(tahun.id)"
                  v-if="tahun.status !== 'aktif'"
                  class="text-red-600 hover:text-red-900"
                >
                  Hapus
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Add/Edit Modal -->
    <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">
            {{ showEditModal ? 'Edit Tahun Ajaran' : 'Tambah Tahun Ajaran' }}
          </h3>
          
          <form @submit.prevent="saveTahunAjaran" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran</label>
              <input
                v-model="form.tahun_ajaran"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Contoh: 2024/2025"
              >
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
              <select
                v-model="form.semester"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="">Pilih semester</option>
                <option value="Ganjil">Ganjil</option>
                <option value="Genap">Genap</option>
              </select>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Mulai</label>
                <input
                  v-model="form.tanggal_mulai"
                  type="date"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Selesai</label>
                <input
                  v-model="form.tanggal_selesai"
                  type="date"
                  required
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan</label>
              <textarea
                v-model="form.keterangan"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan keterangan tambahan"
              ></textarea>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select
                v-model="form.status"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="tidak-aktif">Tidak Aktif</option>
                <option value="aktif">Aktif</option>
                <option value="selesai">Selesai</option>
              </select>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
              <button
                type="button"
                @click="closeModal"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500"
              >
                Batal
              </button>
              <button
                type="submit"
                :disabled="loading"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
              >
                {{ loading ? 'Menyimpan...' : 'Simpan' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue'

// Reactive data
const loading = ref(false)
const showAddModal = ref(false)
const showEditModal = ref(false)
const filterStatus = ref('')
const tahunAjaranList = ref([])
const editingTahunAjaran = ref(null)

const form = reactive({
  tahun_ajaran: '',
  semester: '',
  tanggal_mulai: '',
  tanggal_selesai: '',
  keterangan: '',
  status: 'tidak-aktif'
})

// Computed
const filteredTahunAjaran = computed(() => {
  if (!filterStatus.value) return tahunAjaranList.value
  return tahunAjaranList.value.filter(tahun => tahun.status === filterStatus.value)
})

// Methods
const loadTahunAjaran = async () => {
  try {
    loading.value = true
    // TODO: Implement API call
    // const response = await api.get('/tahun-ajaran')
    // tahunAjaranList.value = response.data
    
    // Mock data
    tahunAjaranList.value = [
      {
        id: 1,
        tahun_ajaran: '2024/2025',
        semester: 'Ganjil',
        tanggal_mulai: '2024-07-15',
        tanggal_selesai: '2024-12-20',
        keterangan: 'Tahun ajaran baru dengan kurikulum merdeka',
        status: 'aktif',
        jumlah_kelas: 42
      },
      {
        id: 2,
        tahun_ajaran: '2024/2025',
        semester: 'Genap',
        tanggal_mulai: '2025-01-06',
        tanggal_selesai: '2025-06-20',
        keterangan: 'Semester genap tahun ajaran 2024/2025',
        status: 'tidak-aktif',
        jumlah_kelas: 42
      },
      {
        id: 3,
        tahun_ajaran: '2023/2024',
        semester: 'Genap',
        tanggal_mulai: '2024-01-08',
        tanggal_selesai: '2024-06-21',
        keterangan: 'Tahun ajaran sebelumnya',
        status: 'selesai',
        jumlah_kelas: 40
      }
    ]
  } catch (error) {
    console.error('Error loading tahun ajaran:', error)
  } finally {
    loading.value = false
  }
}

const getCurrentTahunAjaran = () => {
  const current = tahunAjaranList.value.find(t => t.status === 'aktif')
  return current ? current.tahun_ajaran : 'Belum ditentukan'
}

const getCurrentSemester = () => {
  const current = tahunAjaranList.value.find(t => t.status === 'aktif')
  return current ? current.semester : '-'
}

const getRemainingDays = () => {
  const current = tahunAjaranList.value.find(t => t.status === 'aktif')
  if (!current) return 0
  
  const endDate = new Date(current.tanggal_selesai)
  const today = new Date()
  const diffTime = endDate - today
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24))
  
  return diffDays > 0 ? diffDays : 0
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

const getStatusBadgeClass = (status) => {
  const classes = {
    aktif: 'bg-green-100 text-green-800',
    'tidak-aktif': 'bg-gray-100 text-gray-800',
    selesai: 'bg-blue-100 text-blue-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const getStatusText = (status) => {
  const texts = {
    aktif: 'Aktif',
    'tidak-aktif': 'Tidak Aktif',
    selesai: 'Selesai'
  }
  return texts[status] || status
}

const filterTahunAjaran = () => {
  // Filter logic is handled by computed property
}

const editTahunAjaran = (tahun) => {
  editingTahunAjaran.value = tahun
  Object.assign(form, tahun)
  showEditModal.value = true
}

const setActiveTahunAjaran = async (id) => {
  if (confirm('Apakah Anda yakin ingin mengaktifkan tahun ajaran ini?')) {
    try {
      // TODO: Implement API call
      // await api.put(`/tahun-ajaran/${id}/activate`)
      
      // Set all to tidak-aktif first
      tahunAjaranList.value.forEach(tahun => {
        if (tahun.status === 'aktif') {
          tahun.status = 'tidak-aktif'
        }
      })
      
      // Set selected to aktif
      const selected = tahunAjaranList.value.find(t => t.id === id)
      if (selected) {
        selected.status = 'aktif'
      }
    } catch (error) {
      console.error('Error setting active tahun ajaran:', error)
    }
  }
}

const deleteTahunAjaran = async (id) => {
  if (confirm('Apakah Anda yakin ingin menghapus tahun ajaran ini?')) {
    try {
      // TODO: Implement API call
      // await api.delete(`/tahun-ajaran/${id}`)
      
      tahunAjaranList.value = tahunAjaranList.value.filter(t => t.id !== id)
    } catch (error) {
      console.error('Error deleting tahun ajaran:', error)
    }
  }
}

const saveTahunAjaran = async () => {
  try {
    loading.value = true
    
    if (showEditModal.value) {
      // TODO: Implement API call for update
      // await api.put(`/tahun-ajaran/${editingTahunAjaran.value.id}`, form)
      
      const index = tahunAjaranList.value.findIndex(t => t.id === editingTahunAjaran.value.id)
      if (index !== -1) {
        tahunAjaranList.value[index] = { ...editingTahunAjaran.value, ...form }
      }
    } else {
      // TODO: Implement API call for create
      // const response = await api.post('/tahun-ajaran', form)
      
      const newTahunAjaran = {
        id: Date.now(),
        jumlah_kelas: 0,
        ...form
      }
      tahunAjaranList.value.push(newTahunAjaran)
    }
    
    closeModal()
  } catch (error) {
    console.error('Error saving tahun ajaran:', error)
  } finally {
    loading.value = false
  }
}

const closeModal = () => {
  showAddModal.value = false
  showEditModal.value = false
  editingTahunAjaran.value = null
  Object.assign(form, {
    tahun_ajaran: '',
    semester: '',
    tanggal_mulai: '',
    tanggal_selesai: '',
    keterangan: '',
    status: 'tidak-aktif'
  })
}

// Lifecycle
onMounted(() => {
  loadTahunAjaran()
})
</script>

<style scoped>
.tahun-ajaran-view {
  @apply p-6;
}
</style>
