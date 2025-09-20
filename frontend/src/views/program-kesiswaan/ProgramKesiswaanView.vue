<template>
  <div class="program-kesiswaan-view">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Program Kesiswaan</h1>
      <p class="text-gray-600">Kelola program dan kegiatan kesiswaan sekolah</p>
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
          Tambah Program
        </button>
        
        <button
          @click="loadProgramKesiswaan"
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
          v-model="filterKategori"
          @change="filterProgram"
          class="px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option value="">Semua Kategori</option>
          <option value="akademik">Akademik</option>
          <option value="non-akademik">Non-Akademik</option>
          <option value="karakter">Karakter</option>
          <option value="kreativitas">Kreativitas</option>
        </select>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
              </svg>
            </div>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-500">Total Program</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.total }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-500">Aktif</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.aktif }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
            </div>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-500">Berjalan</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.berjalan }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
              </svg>
            </div>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-500">Peserta</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.totalPeserta }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Program List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Daftar Program Kesiswaan</h3>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Program</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Periode</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Peserta</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="program in filteredProgram" :key="program.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ program.nama }}</div>
                <div class="text-sm text-gray-500">{{ program.deskripsi }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getKategoriBadgeClass(program.kategori)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                  {{ getKategoriText(program.kategori) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ formatDate(program.tanggal_mulai) }} - {{ formatDate(program.tanggal_selesai) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ program.jumlah_peserta || 0 }} siswa
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStatusBadgeClass(program.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                  {{ getStatusText(program.status) }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button
                  @click="editProgram(program)"
                  class="text-blue-600 hover:text-blue-900 mr-3"
                >
                  Edit
                </button>
                <button
                  @click="viewDetail(program)"
                  class="text-green-600 hover:text-green-900 mr-3"
                >
                  Detail
                </button>
                <button
                  @click="deleteProgram(program.id)"
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
            {{ showEditModal ? 'Edit Program' : 'Tambah Program' }}
          </h3>
          
          <form @submit.prevent="saveProgram" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama Program</label>
              <input
                v-model="form.nama"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan nama program"
              >
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
              <textarea
                v-model="form.deskripsi"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan deskripsi program"
              ></textarea>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Kategori</label>
              <select
                v-model="form.kategori"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="">Pilih kategori</option>
                <option value="akademik">Akademik</option>
                <option value="non-akademik">Non-Akademik</option>
                <option value="karakter">Karakter</option>
                <option value="kreativitas">Kreativitas</option>
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
              <label class="block text-sm font-medium text-gray-700 mb-1">Target Peserta</label>
              <input
                v-model="form.target_peserta"
                type="number"
                min="1"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan target peserta"
              >
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Penanggung Jawab</label>
              <input
                v-model="form.penanggung_jawab"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan nama penanggung jawab"
              >
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select
                v-model="form.status"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="draft">Draft</option>
                <option value="aktif">Aktif</option>
                <option value="berjalan">Berjalan</option>
                <option value="selesai">Selesai</option>
                <option value="dibatalkan">Dibatalkan</option>
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
const filterKategori = ref('')
const programList = ref([])
const editingProgram = ref(null)

const stats = ref({
  total: 0,
  aktif: 0,
  berjalan: 0,
  totalPeserta: 0
})

const form = reactive({
  nama: '',
  deskripsi: '',
  kategori: '',
  tanggal_mulai: '',
  tanggal_selesai: '',
  target_peserta: '',
  penanggung_jawab: '',
  status: 'draft'
})

// Computed
const filteredProgram = computed(() => {
  if (!filterKategori.value) return programList.value
  return programList.value.filter(program => program.kategori === filterKategori.value)
})

// Methods
const loadProgramKesiswaan = async () => {
  try {
    loading.value = true
    // TODO: Implement API call
    // const response = await api.get('/program-kesiswaan')
    // programList.value = response.data
    
    // Mock data
    programList.value = [
      {
        id: 1,
        nama: 'Program Literasi Digital',
        deskripsi: 'Program pengembangan literasi digital untuk siswa',
        kategori: 'akademik',
        tanggal_mulai: '2024-08-01',
        tanggal_selesai: '2024-12-31',
        target_peserta: 100,
        jumlah_peserta: 85,
        penanggung_jawab: 'Dr. Ahmad Fauzi, S.Pd',
        status: 'berjalan'
      },
      {
        id: 2,
        nama: 'Ekstrakurikuler Pramuka',
        deskripsi: 'Kegiatan ekstrakurikuler pramuka untuk pembentukan karakter',
        kategori: 'karakter',
        tanggal_mulai: '2024-07-15',
        tanggal_selesai: '2025-06-30',
        target_peserta: 50,
        jumlah_peserta: 45,
        penanggung_jawab: 'Siti Aminah, S.Pd',
        status: 'aktif'
      },
      {
        id: 3,
        nama: 'Festival Seni dan Budaya',
        deskripsi: 'Festival tahunan untuk menampilkan bakat seni siswa',
        kategori: 'kreativitas',
        tanggal_mulai: '2024-10-01',
        tanggal_selesai: '2024-10-31',
        target_peserta: 200,
        jumlah_peserta: 180,
        penanggung_jawab: 'Rina Sari, S.Pd',
        status: 'selesai'
      },
      {
        id: 4,
        nama: 'Program Anti-Bullying',
        deskripsi: 'Program pencegahan dan penanganan bullying di sekolah',
        kategori: 'karakter',
        tanggal_mulai: '2024-09-01',
        tanggal_selesai: '2024-11-30',
        target_peserta: 300,
        jumlah_peserta: 0,
        penanggung_jawab: 'Budi Santoso, S.Pd',
        status: 'draft'
      }
    ]
    
    calculateStats()
  } catch (error) {
    console.error('Error loading program kesiswaan:', error)
  } finally {
    loading.value = false
  }
}

const calculateStats = () => {
  stats.value.total = programList.value.length
  stats.value.aktif = programList.value.filter(p => p.status === 'aktif').length
  stats.value.berjalan = programList.value.filter(p => p.status === 'berjalan').length
  stats.value.totalPeserta = programList.value.reduce((sum, p) => sum + (p.jumlah_peserta || 0), 0)
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric'
  })
}

const getKategoriBadgeClass = (kategori) => {
  const classes = {
    akademik: 'bg-blue-100 text-blue-800',
    'non-akademik': 'bg-green-100 text-green-800',
    karakter: 'bg-purple-100 text-purple-800',
    kreativitas: 'bg-yellow-100 text-yellow-800'
  }
  return classes[kategori] || 'bg-gray-100 text-gray-800'
}

const getKategoriText = (kategori) => {
  const texts = {
    akademik: 'Akademik',
    'non-akademik': 'Non-Akademik',
    karakter: 'Karakter',
    kreativitas: 'Kreativitas'
  }
  return texts[kategori] || kategori
}

const getStatusBadgeClass = (status) => {
  const classes = {
    draft: 'bg-gray-100 text-gray-800',
    aktif: 'bg-green-100 text-green-800',
    berjalan: 'bg-blue-100 text-blue-800',
    selesai: 'bg-purple-100 text-purple-800',
    dibatalkan: 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const getStatusText = (status) => {
  const texts = {
    draft: 'Draft',
    aktif: 'Aktif',
    berjalan: 'Berjalan',
    selesai: 'Selesai',
    dibatalkan: 'Dibatalkan'
  }
  return texts[status] || status
}

const filterProgram = () => {
  // Filter logic is handled by computed property
}

const editProgram = (program) => {
  editingProgram.value = program
  Object.assign(form, program)
  showEditModal.value = true
}

const viewDetail = (program) => {
  // TODO: Implement detail view
  console.log('View detail:', program)
}

const deleteProgram = async (id) => {
  if (confirm('Apakah Anda yakin ingin menghapus program ini?')) {
    try {
      // TODO: Implement API call
      // await api.delete(`/program-kesiswaan/${id}`)
      
      programList.value = programList.value.filter(p => p.id !== id)
      calculateStats()
    } catch (error) {
      console.error('Error deleting program:', error)
    }
  }
}

const saveProgram = async () => {
  try {
    loading.value = true
    
    if (showEditModal.value) {
      // TODO: Implement API call for update
      // await api.put(`/program-kesiswaan/${editingProgram.value.id}`, form)
      
      const index = programList.value.findIndex(p => p.id === editingProgram.value.id)
      if (index !== -1) {
        programList.value[index] = { ...editingProgram.value, ...form }
      }
    } else {
      // TODO: Implement API call for create
      // const response = await api.post('/program-kesiswaan', form)
      
      const newProgram = {
        id: Date.now(),
        jumlah_peserta: 0,
        ...form
      }
      programList.value.push(newProgram)
    }
    
    calculateStats()
    closeModal()
  } catch (error) {
    console.error('Error saving program:', error)
  } finally {
    loading.value = false
  }
}

const closeModal = () => {
  showAddModal.value = false
  showEditModal.value = false
  editingProgram.value = null
  Object.assign(form, {
    nama: '',
    deskripsi: '',
    kategori: '',
    tanggal_mulai: '',
    tanggal_selesai: '',
    target_peserta: '',
    penanggung_jawab: '',
    status: 'draft'
  })
}

// Lifecycle
onMounted(() => {
  loadProgramKesiswaan()
})
</script>

<style scoped>
.program-kesiswaan-view {
  @apply p-6;
}
</style>
