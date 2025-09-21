<template>
  <div class="mata-pelajaran-view">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Mata Pelajaran</h1>
      <p class="text-gray-600">Kelola mata pelajaran dan kurikulum merdeka</p>
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
          Tambah Mata Pelajaran
        </button>
        
        <button
          @click="loadMataPelajaran"
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
          v-model="filterKelompok"
          @change="filterMataPelajaran"
          class="px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option value="">Semua Kelompok</option>
          <option value="A">Kelompok A (Umum)</option>
          <option value="B">Kelompok B (Umum)</option>
          <option value="C">Kelompok C (Peminatan)</option>
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
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
              </svg>
            </div>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-500">Total Mata Pelajaran</p>
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
            <p class="text-sm font-medium text-gray-500">Kelompok A</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.kelompokA }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
              </svg>
            </div>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-500">Kelompok B</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.kelompokB }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
              </svg>
            </div>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-500">Kelompok C</p>
            <p class="text-2xl font-bold text-gray-900">{{ stats.kelompokC }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Mata Pelajaran List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Daftar Mata Pelajaran</h3>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mata Pelajaran</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelompok</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">JP/Minggu</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Guru</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="mapel in filteredMataPelajaran" :key="mapel.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                  {{ mapel.kode }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ mapel.nama }}</div>
                <div class="text-sm text-gray-500">{{ mapel.deskripsi }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getKelompokBadgeClass(mapel.kelompok)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                  Kelompok {{ mapel.kelompok }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ mapel.jam_per_minggu }} JP
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ mapel.guru || 'Belum ditentukan' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="mapel.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                  {{ mapel.status ? 'Aktif' : 'Nonaktif' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button
                  @click="editMataPelajaran(mapel)"
                  class="text-blue-600 hover:text-blue-900 mr-3"
                >
                  Edit
                </button>
                <button
                  @click="deleteMataPelajaran(mapel.id)"
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
            {{ showEditModal ? 'Edit Mata Pelajaran' : 'Tambah Mata Pelajaran' }}
          </h3>
          
          <form @submit.prevent="saveMataPelajaran" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Kode Mata Pelajaran</label>
              <input
                v-model="form.kode"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Contoh: MAT-001"
              >
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama Mata Pelajaran</label>
              <input
                v-model="form.nama"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan nama mata pelajaran"
              >
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
              <textarea
                v-model="form.deskripsi"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan deskripsi mata pelajaran"
              ></textarea>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Kelompok</label>
              <select
                v-model="form.kelompok"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="">Pilih kelompok</option>
                <option value="A">Kelompok A (Umum)</option>
                <option value="B">Kelompok B (Umum)</option>
                <option value="C">Kelompok C (Peminatan)</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Jam Per Minggu</label>
              <input
                v-model="form.jam_per_minggu"
                type="number"
                min="1"
                max="10"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan jam per minggu"
              >
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Guru Pengampu</label>
              <select
                v-model="form.guru"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="">Pilih guru</option>
                <option v-for="guru in guruList" :key="guru.id" :value="guru.nama">
                  {{ guru.nama }}
                </option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select
                v-model="form.status"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option :value="true">Aktif</option>
                <option :value="false">Nonaktif</option>
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
import api from '@/services/api'

// Reactive data
const loading = ref(false)
const showAddModal = ref(false)
const showEditModal = ref(false)
const filterKelompok = ref('')
const mataPelajaranList = ref([])
const guruList = ref([])
const editingMataPelajaran = ref(null)

const stats = ref({
  total: 0,
  kelompokA: 0,
  kelompokB: 0,
  kelompokC: 0
})

const form = reactive({
  kode: '',
  nama: '',
  deskripsi: '',
  kelompok: '',
  jam_per_minggu: '',
  guru: '',
  status: true
})

// Computed
const filteredMataPelajaran = computed(() => {
  if (!filterKelompok.value) return mataPelajaranList.value
  return mataPelajaranList.value.filter(mapel => mapel.kelompok === filterKelompok.value)
})

// Methods
const loadMataPelajaran = async () => {
  try {
    loading.value = true
    
    // Load mata pelajaran from API
    const [mapelRes, guruRes] = await Promise.all([
      api.get('/mata-pelajaran', {
        params: {
          per_page: 100,
          kelompok: filterKelompok.value
        }
      }),
      api.get('/guru')
    ])
    
    mataPelajaranList.value = mapelRes.data.data.data || mapelRes.data.data
    guruList.value = guruRes.data.data || guruRes.data
    
    calculateStats()
  } catch (error) {
    console.error('Error loading mata pelajaran:', error)
    // Fallback to mock data if API fails
    mataPelajaranList.value = [
      {
        id: 1,
        kode: 'MAT-001',
        nama: 'Matematika',
        deskripsi: 'Mata pelajaran matematika untuk semua tingkat',
        kelompok: 'A',
        jam_per_minggu: 4,
        guru: 'Dr. Ahmad Fauzi, S.Pd',
        status_aktif: true
      },
      {
        id: 2,
        kode: 'BIN-001',
        nama: 'Bahasa Indonesia',
        deskripsi: 'Mata pelajaran bahasa Indonesia',
        kelompok: 'A',
        jam_per_minggu: 4,
        guru: 'Siti Aminah, S.Pd',
        status_aktif: true
      }
    ]
    
    guruList.value = [
      { id: 1, nama: 'Dr. Ahmad Fauzi, S.Pd' },
      { id: 2, nama: 'Siti Aminah, S.Pd' }
    ]
    
    calculateStats()
  } finally {
    loading.value = false
  }
}

const calculateStats = () => {
  stats.value.total = mataPelajaranList.value.length
  stats.value.kelompokA = mataPelajaranList.value.filter(m => m.kelompok === 'A').length
  stats.value.kelompokB = mataPelajaranList.value.filter(m => m.kelompok === 'B').length
  stats.value.kelompokC = mataPelajaranList.value.filter(m => m.kelompok === 'C').length
}

const getKelompokBadgeClass = (kelompok) => {
  const classes = {
    A: 'bg-green-100 text-green-800',
    B: 'bg-yellow-100 text-yellow-800',
    C: 'bg-purple-100 text-purple-800'
  }
  return classes[kelompok] || 'bg-gray-100 text-gray-800'
}

const filterMataPelajaran = () => {
  // Filter logic is handled by computed property
}

const editMataPelajaran = (mapel) => {
  editingMataPelajaran.value = mapel
  Object.assign(form, mapel)
  showEditModal.value = true
}

const deleteMataPelajaran = async (id) => {
  if (confirm('Apakah Anda yakin ingin menghapus mata pelajaran ini?')) {
    try {
      await api.delete(`/mata-pelajaran/${id}`)
      
      mataPelajaranList.value = mataPelajaranList.value.filter(m => m.id !== id)
      calculateStats()
    } catch (error) {
      console.error('Error deleting mata pelajaran:', error)
    }
  }
}

const saveMataPelajaran = async () => {
  try {
    loading.value = true
    
    if (showEditModal.value) {
      // Update existing mata pelajaran
      const response = await api.put(`/mata-pelajaran/${editingMataPelajaran.value.id}`, form)
      const index = mataPelajaranList.value.findIndex(m => m.id === editingMataPelajaran.value.id)
      if (index !== -1) {
        mataPelajaranList.value[index] = response.data.data
      }
    } else {
      // Create new mata pelajaran
      const response = await api.post('/mata-pelajaran', form)
      mataPelajaranList.value.push(response.data.data)
    }
    
    calculateStats()
    closeModal()
  } catch (error) {
    console.error('Error saving mata pelajaran:', error)
  } finally {
    loading.value = false
  }
}

const closeModal = () => {
  showAddModal.value = false
  showEditModal.value = false
  editingMataPelajaran.value = null
  Object.assign(form, {
    kode: '',
    nama: '',
    deskripsi: '',
    kelompok: '',
    jam_per_minggu: '',
    guru: '',
    status: true
  })
}

// Lifecycle
onMounted(() => {
  loadMataPelajaran()
})
</script>

<style scoped>
.mata-pelajaran-view {
  @apply p-6;
}
</style>
