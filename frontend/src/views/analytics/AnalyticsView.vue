<template>
  <div class="analytics-view">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Dashboard Analytics</h1>
      <p class="text-gray-600">Analisis dan laporan data sistem kesiswaan</p>
    </div>

    <!-- Filter Controls -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
          <select
            v-model="filter.periode"
            @change="loadAnalytics"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
            <option value="hari">Hari Ini</option>
            <option value="minggu">Minggu Ini</option>
            <option value="bulan">Bulan Ini</option>
            <option value="semester">Semester Ini</option>
            <option value="tahun">Tahun Ini</option>
          </select>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran</label>
          <select
            v-model="filter.tahun_ajaran"
            @change="loadAnalytics"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
            <option value="2024/2025">2024/2025</option>
            <option value="2023/2024">2023/2024</option>
          </select>
        </div>
        
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
          <select
            v-model="filter.kelas"
            @change="loadAnalytics"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
          >
            <option value="">Semua Kelas</option>
            <option value="X">Kelas X</option>
            <option value="XI">Kelas XI</option>
            <option value="XII">Kelas XII</option>
          </select>
        </div>
        
        <div class="flex items-end">
          <button
            @click="loadAnalytics"
            class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
          >
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
            Refresh
          </button>
        </div>
      </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
              </svg>
            </div>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-500">Total Siswa</p>
            <p class="text-2xl font-bold text-gray-900">{{ metrics.totalSiswa }}</p>
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
            <p class="text-sm font-medium text-gray-500">Kehadiran Rata-rata</p>
            <p class="text-2xl font-bold text-gray-900">{{ metrics.kehadiranRata }}%</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
              </svg>
            </div>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-500">Nilai Karakter Rata-rata</p>
            <p class="text-2xl font-bold text-gray-900">{{ metrics.nilaiKarakterRata }}</p>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center">
          <div class="flex-shrink-0">
            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
              <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
              </svg>
            </div>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-500">Tren Positif</p>
            <p class="text-2xl font-bold text-gray-900">{{ metrics.trenPositif }}%</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
      <!-- Kehadiran Chart -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Tren Kehadiran</h3>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
          <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            <p class="mt-2 text-sm text-gray-500">Chart kehadiran akan ditampilkan di sini</p>
          </div>
        </div>
      </div>

      <!-- Karakter Chart -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribusi Nilai Karakter</h3>
        <div class="h-64 flex items-center justify-center bg-gray-50 rounded-lg">
          <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
            </svg>
            <p class="mt-2 text-sm text-gray-500">Chart distribusi karakter akan ditampilkan di sini</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Detailed Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
      <!-- Top Performers -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Siswa Berprestasi</h3>
        <div class="space-y-3">
          <div
            v-for="(siswa, index) in topPerformers"
            :key="siswa.id"
            class="flex items-center space-x-3"
          >
            <div class="flex-shrink-0">
              <div :class="getRankBadgeClass(index + 1)" class="w-8 h-8 rounded-full flex items-center justify-center">
                <span class="text-xs font-bold text-white">{{ index + 1 }}</span>
              </div>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm font-medium text-gray-900 truncate">{{ siswa.nama }}</p>
              <p class="text-sm text-gray-500">{{ siswa.kelas }}</p>
            </div>
            <div class="flex-shrink-0">
              <span class="text-sm font-semibold text-gray-900">{{ siswa.nilai }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Dimensi Karakter -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Performa Dimensi Karakter</h3>
        <div class="space-y-4">
          <div
            v-for="dimensi in dimensiPerformance"
            :key="dimensi.id"
            class="space-y-2"
          >
            <div class="flex justify-between items-center">
              <span class="text-sm font-medium text-gray-900">{{ dimensi.nama }}</span>
              <span class="text-sm text-gray-500">{{ dimensi.nilai }}%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div
                :class="getProgressBarClass(dimensi.nilai)"
                class="h-2 rounded-full"
                :style="{ width: dimensi.nilai + '%' }"
              ></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activities -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Aktivitas Terbaru</h3>
        <div class="space-y-3">
          <div
            v-for="activity in recentActivities"
            :key="activity.id"
            class="flex items-start space-x-3"
          >
            <div class="flex-shrink-0">
              <div :class="getActivityIconClass(activity.type)" class="w-8 h-8 rounded-full flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="getActivityIcon(activity.type)"></path>
                </svg>
              </div>
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-sm text-gray-900">{{ activity.description }}</p>
              <p class="text-xs text-gray-500">{{ formatTime(activity.timestamp) }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Export Options -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Export Laporan</h3>
      <div class="flex flex-wrap gap-4">
        <button
          @click="exportReport('pdf')"
          class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2"
        >
          <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          Export PDF
        </button>
        
        <button
          @click="exportReport('excel')"
          class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2"
        >
          <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          Export Excel
        </button>
        
        <button
          @click="exportReport('csv')"
          class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
          <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
          Export CSV
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'

// Reactive data
const loading = ref(false)

const filter = reactive({
  periode: 'bulan',
  tahun_ajaran: '2024/2025',
  kelas: ''
})

const metrics = ref({
  totalSiswa: 0,
  kehadiranRata: 0,
  nilaiKarakterRata: 0,
  trenPositif: 0
})

const topPerformers = ref([])
const dimensiPerformance = ref([])
const recentActivities = ref([])

// Methods
const loadAnalytics = async () => {
  try {
    loading.value = true
    // TODO: Implement API call
    // const response = await api.get('/analytics', { params: filter })
    // const data = response.data
    
    // Mock data
    metrics.value = {
      totalSiswa: 1250,
      kehadiranRata: 94.5,
      nilaiKarakterRata: 3.8,
      trenPositif: 87.2
    }
    
    topPerformers.value = [
      { id: 1, nama: 'Ahmad Fauzi', kelas: 'XII IPA 1', nilai: 4.0 },
      { id: 2, nama: 'Siti Aminah', kelas: 'XII IPS 1', nilai: 3.9 },
      { id: 3, nama: 'Budi Santoso', kelas: 'XI IPA 2', nilai: 3.8 },
      { id: 4, nama: 'Rina Sari', kelas: 'X IPA 1', nilai: 3.7 },
      { id: 5, nama: 'John Doe', kelas: 'XII IPA 2', nilai: 3.6 }
    ]
    
    dimensiPerformance.value = [
      { id: 1, nama: 'Spiritual & Religius', nilai: 92 },
      { id: 2, nama: 'Sosial & Kebangsaan', nilai: 88 },
      { id: 3, nama: 'Gotong Royong', nilai: 85 },
      { id: 4, nama: 'Mandiri', nilai: 90 },
      { id: 5, nama: 'Bernalar Kritis', nilai: 87 },
      { id: 6, nama: 'Kreatif', nilai: 83 }
    ]
    
    recentActivities.value = [
      {
        id: 1,
        type: 'penilaian',
        description: 'Penilaian karakter bulanan selesai',
        timestamp: new Date(Date.now() - 2 * 60 * 60 * 1000) // 2 hours ago
      },
      {
        id: 2,
        type: 'presensi',
        description: 'Laporan presensi mingguan diupdate',
        timestamp: new Date(Date.now() - 4 * 60 * 60 * 1000) // 4 hours ago
      },
      {
        id: 3,
        type: 'program',
        description: 'Program anti-bullying dimulai',
        timestamp: new Date(Date.now() - 6 * 60 * 60 * 1000) // 6 hours ago
      },
      {
        id: 4,
        type: 'backup',
        description: 'Backup otomatis berhasil',
        timestamp: new Date(Date.now() - 8 * 60 * 60 * 1000) // 8 hours ago
      }
    ]
  } catch (error) {
    console.error('Error loading analytics:', error)
  } finally {
    loading.value = false
  }
}

const getRankBadgeClass = (rank) => {
  if (rank === 1) return 'bg-yellow-500'
  if (rank === 2) return 'bg-gray-400'
  if (rank === 3) return 'bg-orange-500'
  return 'bg-blue-500'
}

const getProgressBarClass = (value) => {
  if (value >= 90) return 'bg-green-500'
  if (value >= 80) return 'bg-yellow-500'
  if (value >= 70) return 'bg-orange-500'
  return 'bg-red-500'
}

const getActivityIconClass = (type) => {
  const classes = {
    penilaian: 'bg-blue-500',
    presensi: 'bg-green-500',
    program: 'bg-purple-500',
    backup: 'bg-gray-500'
  }
  return classes[type] || 'bg-gray-500'
}

const getActivityIcon = (type) => {
  const icons = {
    penilaian: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
    presensi: 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',
    program: 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
    backup: 'M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12'
  }
  return icons[type] || 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'
}

const formatTime = (timestamp) => {
  const now = new Date()
  const diff = now - timestamp
  const hours = Math.floor(diff / (1000 * 60 * 60))
  const minutes = Math.floor(diff / (1000 * 60))
  
  if (hours > 0) {
    return `${hours} jam yang lalu`
  } else if (minutes > 0) {
    return `${minutes} menit yang lalu`
  } else {
    return 'Baru saja'
  }
}

const exportReport = async (format) => {
  try {
    loading.value = true
    // TODO: Implement API call
    // const response = await api.get(`/analytics/export/${format}`, { params: filter })
    
    console.log(`Exporting report as ${format}...`)
    // Handle file download
  } catch (error) {
    console.error(`Error exporting ${format}:`, error)
  } finally {
    loading.value = false
  }
}

// Lifecycle
onMounted(() => {
  loadAnalytics()
})
</script>

<style scoped>
.analytics-view {
  @apply p-6;
}
</style>
