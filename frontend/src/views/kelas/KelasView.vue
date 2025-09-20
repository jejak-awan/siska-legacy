<template>
  <div class="kelas-view">
    <!-- Header Section -->
    <div class="mb-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Data Kelas</h1>
          <p class="text-gray-600 mt-1">Kelola data kelas dan struktur pembelajaran</p>
        </div>
        <div class="flex space-x-3">
          <button
            @click="showCreateModal = true"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Tambah Kelas</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="p-2 bg-blue-100 rounded-lg">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Kelas</p>
            <p class="text-2xl font-semibold text-gray-900">{{ statistics.totalKelas || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="p-2 bg-green-100 rounded-lg">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Siswa</p>
            <p class="text-2xl font-semibold text-gray-900">{{ statistics.totalSiswa || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="p-2 bg-yellow-100 rounded-lg">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Rata-rata Siswa/Kelas</p>
            <p class="text-2xl font-semibold text-gray-900">{{ statistics.avgSiswaPerKelas || 0 }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="p-2 bg-purple-100 rounded-lg">
            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Wali Kelas</p>
            <p class="text-2xl font-semibold text-gray-900">{{ statistics.totalWaliKelas || 0 }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Tingkat</label>
          <select v-model="filters.tingkat" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            <option value="">Semua Tingkat</option>
            <option value="X">Kelas X</option>
            <option value="XI">Kelas XI</option>
            <option value="XII">Kelas XII</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Jurusan</label>
          <select v-model="filters.jurusan" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            <option value="">Semua Jurusan</option>
            <option value="IPA">IPA</option>
            <option value="IPS">IPS</option>
            <option value="Bahasa">Bahasa</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran</label>
          <select v-model="filters.tahun_ajaran" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            <option value="">Semua Tahun</option>
            <option value="2024-2025">2024-2025</option>
            <option value="2023-2024">2023-2024</option>
          </select>
        </div>
        <div class="flex items-end">
          <button
            @click="loadData"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm transition-colors"
          >
            Filter
          </button>
        </div>
      </div>
    </div>

    <!-- Classes Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Daftar Kelas</h3>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kelas</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jurusan</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Siswa</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Wali Kelas</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun Ajaran</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="kelas in kelasData" :key="kelas.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ kelas.nama_kelas }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                  {{ kelas.tingkat }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                  {{ kelas.jurusan }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ kelas.jumlah_siswa }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ kelas.wali_kelas || 'Belum ditentukan' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ kelas.tahun_ajaran }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button
                    @click="viewKelas(kelas)"
                    class="text-blue-600 hover:text-blue-900"
                  >
                    Lihat
                  </button>
                  <button
                    @click="editKelas(kelas)"
                    class="text-green-600 hover:text-green-900"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteKelas(kelas)"
                    class="text-red-600 hover:text-red-900"
                  >
                    Hapus
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Empty State -->
      <div v-if="kelasData.length === 0 && !loading" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada data kelas</h3>
        <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan kelas pertama.</p>
      </div>

      <!-- Loading State -->
      <div v-if="loading" class="text-center py-12">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        <p class="mt-2 text-sm text-gray-500">Memuat data...</p>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()

// Reactive data
const loading = ref(false)
const kelasData = ref([])
const statistics = ref({})
const showCreateModal = ref(false)

// Filters
const filters = reactive({
  tingkat: '',
  jurusan: '',
  tahun_ajaran: ''
})

// Methods
const loadData = async () => {
  try {
    loading.value = true
    
    // Mock data - replace with actual API call
    const mockData = [
      {
        id: 1,
        nama_kelas: 'X IPA 1',
        tingkat: 'X',
        jurusan: 'IPA',
        jumlah_siswa: 32,
        wali_kelas: 'Budi Santoso, S.Pd',
        tahun_ajaran: '2024-2025'
      },
      {
        id: 2,
        nama_kelas: 'X IPA 2',
        tingkat: 'X',
        jurusan: 'IPA',
        jumlah_siswa: 30,
        wali_kelas: 'Siti Aminah, S.Pd',
        tahun_ajaran: '2024-2025'
      },
      {
        id: 3,
        nama_kelas: 'XI IPA 1',
        tingkat: 'XI',
        jurusan: 'IPA',
        jumlah_siswa: 28,
        wali_kelas: 'Ahmad Rizki, S.Pd',
        tahun_ajaran: '2024-2025'
      },
      {
        id: 4,
        nama_kelas: 'XII IPA 1',
        tingkat: 'XII',
        jurusan: 'IPA',
        jumlah_siswa: 25,
        wali_kelas: 'Maya Sari, S.Pd',
        tahun_ajaran: '2024-2025'
      }
    ]
    
    kelasData.value = mockData
    
    // Load statistics
    statistics.value = {
      totalKelas: mockData.length,
      totalSiswa: mockData.reduce((sum, kelas) => sum + kelas.jumlah_siswa, 0),
      avgSiswaPerKelas: Math.round(mockData.reduce((sum, kelas) => sum + kelas.jumlah_siswa, 0) / mockData.length),
      totalWaliKelas: mockData.filter(kelas => kelas.wali_kelas).length
    }
    
  } catch (error) {
    console.error('Failed to load kelas data:', error)
  } finally {
    loading.value = false
  }
}

const viewKelas = (kelas) => {
  console.log('View kelas:', kelas)
}

const editKelas = (kelas) => {
  console.log('Edit kelas:', kelas)
}

const deleteKelas = (kelas) => {
  if (confirm(`Apakah Anda yakin ingin menghapus kelas ${kelas.nama_kelas}?`)) {
    console.log('Delete kelas:', kelas)
  }
}

// Lifecycle
onMounted(() => {
  loadData()
})
</script>

<style scoped>
.kelas-view {
  padding: 1.5rem;
}
</style>
