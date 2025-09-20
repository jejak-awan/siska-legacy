<template>
  <div class="p-6">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Laporan Sistem</h1>
      <p class="text-gray-600 mt-1">Generate dan kelola berbagai laporan sistem kesiswaan</p>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
      <h3 class="text-lg font-medium text-gray-900 mb-4">Filter Laporan</h3>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Date Range -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Dari Tanggal</label>
          <input
            v-model="filters.startDate"
            type="date"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
          >
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Sampai Tanggal</label>
          <input
            v-model="filters.endDate"
            type="date"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
          >
        </div>
        
        <!-- Class Filter -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
          <select
            v-model="filters.kelas"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
          >
            <option value="">Semua Kelas</option>
            <option v-for="kelas in classList" :key="kelas.id" :value="kelas.id">
              {{ kelas.nama }}
            </option>
          </select>
        </div>
        
        <!-- Report Type -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Laporan</label>
          <select
            v-model="filters.type"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
          >
            <option value="">Semua Laporan</option>
            <option value="presensi">Presensi</option>
            <option value="kredit_poin">Kredit Poin</option>
            <option value="akademik">Akademik</option>
            <option value="bk">Bimbingan Konseling</option>
          </select>
        </div>
      </div>
      
      <div class="mt-4 flex justify-end">
        <button
          @click="applyFilters"
          class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          Terapkan Filter
        </button>
      </div>
    </div>

    <!-- Report Categories -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
      <!-- Presensi Reports -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-4">
          <div class="h-12 w-12 rounded-lg bg-green-100 flex items-center justify-center mr-4">
            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900">Laporan Presensi</h3>
        </div>
        
        <div class="space-y-2">
          <button
            @click="generateReport('presensi_harian')"
            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md"
          >
            Presensi Harian
          </button>
          <button
            @click="generateReport('presensi_bulanan')"
            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md"
          >
            Presensi Bulanan
          </button>
          <button
            @click="generateReport('rekap_ketidakhadiran')"
            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md"
          >
            Rekap Ketidakhadiran
          </button>
        </div>
      </div>

      <!-- Academic Reports -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-4">
          <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center mr-4">
            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900">Laporan Akademik</h3>
        </div>
        
        <div class="space-y-2">
          <button
            @click="generateReport('nilai_semester')"
            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md"
          >
            Nilai Semester
          </button>
          <button
            @click="generateReport('ranking_kelas')"
            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md"
          >
            Ranking Kelas
          </button>
          <button
            @click="generateReport('statistik_nilai')"
            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md"
          >
            Statistik Nilai
          </button>
        </div>
      </div>

      <!-- Kredit Poin Reports -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-4">
          <div class="h-12 w-12 rounded-lg bg-yellow-100 flex items-center justify-center mr-4">
            <svg class="h-6 w-6 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900">Laporan Kredit Poin</h3>
        </div>
        
        <div class="space-y-2">
          <button
            @click="generateReport('ranking_poin')"
            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md"
          >
            Ranking Kredit Poin
          </button>
          <button
            @click="generateReport('riwayat_poin')"
            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md"
          >
            Riwayat Pemberian Poin
          </button>
          <button
            @click="generateReport('siswa_bermasalah')"
            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md"
          >
            Siswa Bermasalah
          </button>
        </div>
      </div>

      <!-- BK Reports -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-4">
          <div class="h-12 w-12 rounded-lg bg-purple-100 flex items-center justify-center mr-4">
            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900">Laporan BK</h3>
        </div>
        
        <div class="space-y-2">
          <button
            @click="generateReport('kasus_konseling')"
            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md"
          >
            Kasus Konseling
          </button>
          <button
            @click="generateReport('monitoring_siswa')"
            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md"
          >
            Monitoring Siswa
          </button>
          <button
            @click="generateReport('home_visit')"
            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md"
          >
            Laporan Home Visit
          </button>
        </div>
      </div>

      <!-- OSIS Reports -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-4">
          <div class="h-12 w-12 rounded-lg bg-indigo-100 flex items-center justify-center mr-4">
            <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900">Laporan OSIS</h3>
        </div>
        
        <div class="space-y-2">
          <button
            @click="generateReport('kegiatan_osis')"
            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md"
          >
            Kegiatan OSIS
          </button>
          <button
            @click="generateReport('anggota_osis')"
            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md"
          >
            Data Anggota OSIS
          </button>
          <button
            @click="generateReport('proposal_kegiatan')"
            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md"
          >
            Proposal Kegiatan
          </button>
        </div>
      </div>

      <!-- Ekstrakurikuler Reports -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center mb-4">
          <div class="h-12 w-12 rounded-lg bg-orange-100 flex items-center justify-center mr-4">
            <svg class="h-6 w-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900">Laporan Ekstrakurikuler</h3>
        </div>
        
        <div class="space-y-2">
          <button
            @click="generateReport('anggota_ekskul')"
            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md"
          >
            Data Anggota Ekskul
          </button>
          <button
            @click="generateReport('prestasi_ekskul')"
            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md"
          >
            Prestasi Ekstrakurikuler
          </button>
          <button
            @click="generateReport('jadwal_kegiatan')"
            class="w-full text-left px-3 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-md"
          >
            Jadwal Kegiatan
          </button>
        </div>
      </div>
    </div>

    <!-- Recent Reports -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Laporan Terbaru</h3>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Laporan</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="report in recentReports" :key="report.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ report.nama }}</div>
                <div class="text-sm text-gray-500">{{ report.deskripsi }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ report.jenis }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ report.tanggal }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  :class="getStatusClass(report.status)"
                >
                  {{ report.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button
                  @click="downloadReport(report)"
                  class="text-blue-600 hover:text-blue-900 mr-3"
                  :disabled="report.status === 'Processing'"
                >
                  Download
                </button>
                <button
                  @click="viewReport(report)"
                  class="text-green-600 hover:text-green-900"
                >
                  Lihat
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Loading Modal -->
    <div v-if="isGenerating" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <div class="text-center">
          <div class="animate-spin h-12 w-12 border-4 border-blue-500 border-t-transparent rounded-full mx-auto mb-4"></div>
          <h3 class="text-lg font-medium text-gray-900 mb-2">Generating Laporan</h3>
          <p class="text-gray-600">Mohon tunggu, laporan sedang diproses...</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

// State
const isGenerating = ref(false)
const filters = ref({
  startDate: '',
  endDate: '',
  kelas: '',
  type: ''
})

// Mock data
const classList = ref([
  { id: 1, nama: 'X-A' },
  { id: 2, nama: 'X-B' },
  { id: 3, nama: 'XI IPA 1' },
  { id: 4, nama: 'XI IPA 2' },
  { id: 5, nama: 'XII IPA 1' }
])

const recentReports = ref([
  {
    id: 1,
    nama: 'Laporan Presensi Januari 2024',
    deskripsi: 'Rekap presensi siswa bulan Januari',
    jenis: 'Presensi',
    tanggal: '2024-01-31',
    status: 'Selesai'
  },
  {
    id: 2,
    nama: 'Ranking Kredit Poin Semester 1',
    deskripsi: 'Ranking siswa berdasarkan kredit poin',
    jenis: 'Kredit Poin',
    tanggal: '2024-01-30',
    status: 'Selesai'
  },
  {
    id: 3,
    nama: 'Laporan Kasus Konseling',
    deskripsi: 'Laporan kasus bimbingan konseling',
    jenis: 'BK',
    tanggal: '2024-01-29',
    status: 'Processing'
  }
])

// Methods
const getStatusClass = (status) => {
  switch (status) {
    case 'Selesai':
      return 'bg-green-100 text-green-800'
    case 'Processing':
      return 'bg-yellow-100 text-yellow-800'
    case 'Error':
      return 'bg-red-100 text-red-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const applyFilters = () => {
  console.log('Apply filters:', filters.value)
  // Implement filter logic
}

const generateReport = async (reportType) => {
  console.log('Generate report:', reportType)
  isGenerating.value = true
  
  // Simulate report generation
  setTimeout(() => {
    isGenerating.value = false
    alert(`Laporan ${reportType} berhasil dibuat!`)
  }, 3000)
}

const downloadReport = (report) => {
  console.log('Download report:', report)
  // Implement download logic
}

const viewReport = (report) => {
  console.log('View report:', report)
  // Implement view logic
}

onMounted(() => {
  // Set default date range (last 30 days)
  const today = new Date()
  const lastMonth = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000)
  
  filters.value.endDate = today.toISOString().split('T')[0]
  filters.value.startDate = lastMonth.toISOString().split('T')[0]
  
  console.log('LaporanView mounted')
})
</script>
