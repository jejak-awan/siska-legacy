<template>
  <div class="p-6">
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Prestasi OSIS</h1>
      <p class="text-gray-600">Manajemen prestasi dan pencapaian OSIS</p>
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
          Tambah Prestasi
        </button>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
          <div class="p-2 bg-yellow-100 rounded-lg">
            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Prestasi</p>
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
            <p class="text-sm font-medium text-gray-600">Tahun Ini</p>
            <p class="text-2xl font-semibold text-gray-900">{{ statistics.thisYear }}</p>
          </div>
        </div>
      </div>
      
      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
          <div class="p-2 bg-blue-100 rounded-lg">
            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Tingkat Nasional</p>
            <p class="text-2xl font-semibold text-gray-900">{{ statistics.national }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Prestasi Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Nama Prestasi
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Tingkat
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Tanggal
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Pencapaian
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Aksi
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="prestasi in prestasiList" :key="prestasi.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ prestasi.nama }}</div>
                <div class="text-sm text-gray-500">{{ prestasi.deskripsi }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getTingkatBadgeClass(prestasi.tingkat)" class="px-2 py-1 text-xs font-semibold rounded-full">
                  {{ prestasi.tingkat }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ formatDate(prestasi.tanggal) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ prestasi.pencapaian }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button
                    @click="viewDetails(prestasi)"
                    class="text-blue-600 hover:text-blue-900"
                  >
                    Lihat
                  </button>
                  <button
                    @click="editPrestasi(prestasi)"
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
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

// Reactive data
const prestasiList = ref([])

// Statistics
const statistics = ref({
  total: 0,
  thisYear: 0,
  national: 0
})

// Methods
const loadPrestasi = async () => {
  try {
    // Mock data - replace with actual API call
    prestasiList.value = [
      {
        id: 1,
        nama: 'Juara 1 Lomba Debat Bahasa Inggris',
        deskripsi: 'Kompetisi debat tingkat provinsi',
        tingkat: 'Provinsi',
        tanggal: '2024-08-15',
        pencapaian: 'Juara 1'
      },
      {
        id: 2,
        nama: 'Peserta Terbaik Pramuka',
        deskripsi: 'Jambore Nasional Pramuka',
        tingkat: 'Nasional',
        tanggal: '2024-07-20',
        pencapaian: 'Peserta Terbaik'
      }
    ]
    
    updateStatistics()
  } catch (error) {
    console.error('Error loading prestasi:', error)
  }
}

const updateStatistics = () => {
  const currentYear = new Date().getFullYear()
  statistics.value = {
    total: prestasiList.value.length,
    thisYear: prestasiList.value.filter(p => new Date(p.tanggal).getFullYear() === currentYear).length,
    national: prestasiList.value.filter(p => p.tingkat === 'Nasional').length
  }
}

const openCreateModal = () => {
  // Implement create modal
  console.log('Open create modal')
}

const viewDetails = (prestasi) => {
  console.log('View details:', prestasi)
}

const editPrestasi = (prestasi) => {
  console.log('Edit prestasi:', prestasi)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID')
}

const getTingkatBadgeClass = (tingkat) => {
  const classes = {
    'Sekolah': 'bg-gray-100 text-gray-800',
    'Kota': 'bg-blue-100 text-blue-800',
    'Provinsi': 'bg-green-100 text-green-800',
    'Nasional': 'bg-yellow-100 text-yellow-800',
    'Internasional': 'bg-red-100 text-red-800'
  }
  return classes[tingkat] || 'bg-gray-100 text-gray-800'
}

// Lifecycle
onMounted(() => {
  loadPrestasi()
})
</script>
