<template>
  <div class="character-report">
    <!-- Header -->
    <div class="mb-6">
      <div class="flex items-center justify-between">
        <div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Laporan Karakter Siswa</h3>
          <p class="text-sm text-gray-600">Generate dan export laporan penilaian karakter</p>
        </div>
        <div class="flex space-x-3">
          <button
            @click="generateReport"
            :disabled="loading"
            class="bg-blue-600 hover:bg-blue-700 disabled:bg-gray-400 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors"
          >
            <svg v-if="loading" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>{{ loading ? 'Generating...' : 'Generate Report' }}</span>
          </button>
          <button
            v-if="reportData"
            @click="exportToPDF"
            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Export PDF</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Report Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
      <h4 class="text-md font-semibold text-gray-900 mb-4">Filter Laporan</h4>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
          <select v-model="filters.period" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            <option value="">Pilih Periode</option>
            <option value="2024-2025">2024-2025</option>
            <option value="2023-2024">2023-2024</option>
            <option value="2022-2023">2022-2023</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Semester</label>
          <select v-model="filters.semester" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            <option value="">Semua Semester</option>
            <option value="1">Semester 1</option>
            <option value="2">Semester 2</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
          <select v-model="filters.kelas" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            <option value="">Semua Kelas</option>
            <option value="X IPA 1">X IPA 1</option>
            <option value="X IPA 2">X IPA 2</option>
            <option value="XI IPA 1">XI IPA 1</option>
            <option value="XI IPA 2">XI IPA 2</option>
            <option value="XII IPA 1">XII IPA 1</option>
            <option value="XII IPA 2">XII IPA 2</option>
          </select>
        </div>
      </div>
    </div>

    <!-- Report Preview -->
    <div v-if="reportData" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
      <div class="mb-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-2">Preview Laporan</h4>
        <p class="text-sm text-gray-600">Laporan akan di-generate berdasarkan filter yang dipilih</p>
      </div>

      <!-- Report Header -->
      <div class="text-center mb-8 border-b border-gray-200 pb-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Laporan Penilaian Karakter</h1>
        <h2 class="text-lg text-gray-700 mb-1">{{ reportData.school.name }}</h2>
        <p class="text-sm text-gray-500">{{ reportData.school.address }}</p>
        <p class="text-sm text-gray-500 mt-2">
          Periode: {{ reportData.period }} | 
          Kelas: {{ reportData.class || 'Semua Kelas' }} |
          Generated: {{ formatDate(new Date()) }}
        </p>
      </div>

      <!-- Summary Statistics -->
      <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan Statistik</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div class="bg-blue-50 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-blue-600">{{ reportData.summary.totalStudents }}</div>
            <div class="text-sm text-blue-800">Total Siswa</div>
          </div>
          <div class="bg-green-50 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-green-600">{{ reportData.summary.avgScore }}</div>
            <div class="text-sm text-green-800">Rata-rata Skor</div>
          </div>
          <div class="bg-yellow-50 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-yellow-600">{{ reportData.summary.topPerformers }}</div>
            <div class="text-sm text-yellow-800">Siswa Berprestasi</div>
          </div>
          <div class="bg-red-50 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-red-600">{{ reportData.summary.needsAttention }}</div>
            <div class="text-sm text-red-800">Perlu Perhatian</div>
          </div>
        </div>
      </div>

      <!-- Dimension Performance -->
      <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Performa Dimensi Karakter</h3>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dimensi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rata-rata</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa Terbaik</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Perlu Bimbingan</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="dimension in reportData.dimensions" :key="dimension.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  {{ dimension.nama }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  <div class="flex items-center">
                    <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                      <div
                        :class="getScoreColor(dimension.average)"
                        class="h-2 rounded-full"
                        :style="{ width: (dimension.average / 4) * 100 + '%' }"
                      ></div>
                    </div>
                    {{ dimension.average }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ dimension.topStudent }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ dimension.needsAttention }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Top Performers -->
      <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Siswa Berprestasi</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div
            v-for="student in reportData.topPerformers"
            :key="student.id"
            class="bg-green-50 rounded-lg p-4"
          >
            <div class="flex items-center justify-between mb-2">
              <h4 class="text-sm font-medium text-green-900">{{ student.nama }}</h4>
              <span class="text-lg font-bold text-green-600">{{ student.score }}</span>
            </div>
            <p class="text-xs text-green-700">{{ student.kelas }} • {{ student.nis }}</p>
            <p class="text-xs text-green-600 mt-1">{{ student.strength }}</p>
          </div>
        </div>
      </div>

      <!-- Recommendations -->
      <div class="mb-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Rekomendasi</h3>
        <div class="bg-yellow-50 rounded-lg p-4">
          <ul class="space-y-2 text-sm text-yellow-800">
            <li v-for="recommendation in reportData.recommendations" :key="recommendation" class="flex items-start">
              <svg class="w-4 h-4 text-yellow-600 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              {{ recommendation }}
            </li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="!reportData && !loading" class="text-center py-12">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada laporan</h3>
      <p class="mt-1 text-sm text-gray-500">Pilih filter dan klik "Generate Report" untuk membuat laporan.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'

// Reactive data
const loading = ref(false)
const reportData = ref(null)

// Filters
const filters = reactive({
  period: '',
  semester: '',
  kelas: ''
})

// Methods
const generateReport = async () => {
  try {
    loading.value = true
    
    // Mock data - replace with actual API call
    const mockReportData = {
      school: {
        name: 'SMA Negeri 1 Jakarta',
        address: 'Jl. Pendidikan No. 1, Jakarta Pusat'
      },
      period: filters.period || '2024-2025',
      class: filters.kelas || 'Semua Kelas',
      summary: {
        totalStudents: 120,
        avgScore: 3.6,
        topPerformers: 15,
        needsAttention: 8
      },
      dimensions: [
        {
          id: 1,
          nama: 'Spiritual & Religius',
          average: 3.8,
          topStudent: 'Ahmad Rizki (4.0)',
          needsAttention: 3
        },
        {
          id: 2,
          nama: 'Sosial & Kebangsaan',
          average: 3.6,
          topStudent: 'Siti Nurhaliza (4.0)',
          needsAttention: 5
        },
        {
          id: 3,
          nama: 'Gotong Royong',
          average: 3.9,
          topStudent: 'Budi Santoso (4.0)',
          needsAttention: 2
        },
        {
          id: 4,
          nama: 'Mandiri',
          average: 3.4,
          topStudent: 'Maya Sari (3.8)',
          needsAttention: 8
        },
        {
          id: 5,
          nama: 'Bernalar Kritis',
          average: 3.7,
          topStudent: 'Ahmad Rizki (4.0)',
          needsAttention: 4
        },
        {
          id: 6,
          nama: 'Kreatif',
          average: 3.5,
          topStudent: 'Siti Nurhaliza (3.8)',
          needsAttention: 6
        }
      ],
      topPerformers: [
        { id: 1, nama: 'Ahmad Rizki', kelas: 'X IPA 1', nis: '2024001', score: 3.9, strength: 'Spiritual & Religius' },
        { id: 2, nama: 'Siti Nurhaliza', kelas: 'X IPA 1', nis: '2024002', score: 3.8, strength: 'Sosial & Kebangsaan' },
        { id: 3, nama: 'Budi Santoso', kelas: 'X IPA 2', nis: '2024003', score: 3.8, strength: 'Gotong Royong' },
        { id: 4, nama: 'Maya Sari', kelas: 'X IPA 2', nis: '2024004', score: 3.7, strength: 'Bernalar Kritis' },
        { id: 5, nama: 'Rizki Pratama', kelas: 'XI IPA 1', nis: '2023001', score: 3.7, strength: 'Kreatif' },
        { id: 6, nama: 'Dewi Lestari', kelas: 'XI IPA 1', nis: '2023002', score: 3.6, strength: 'Mandiri' }
      ],
      recommendations: [
        'Meningkatkan program pengembangan karakter untuk dimensi Mandiri dan Kreatif',
        'Memberikan bimbingan khusus untuk 8 siswa yang memerlukan perhatian',
        'Mengadakan workshop gotong royong untuk meningkatkan dimensi sosial',
        'Mengembangkan program mentoring untuk siswa dengan skor rendah',
        'Membuat sistem reward untuk siswa berprestasi dalam karakter'
      ]
    }
    
    // Simulate API delay
    await new Promise(resolve => setTimeout(resolve, 2000))
    
    reportData.value = mockReportData
    
  } catch (error) {
    console.error('Failed to generate report:', error)
  } finally {
    loading.value = false
  }
}

const exportToPDF = () => {
  // Mock PDF export - replace with actual PDF generation
  console.log('Exporting to PDF...', reportData.value)
  
  // In real implementation, you would use a library like jsPDF or html2pdf
  // For now, we'll just show an alert
  alert('Fitur export PDF akan segera tersedia!')
}

const getScoreColor = (score) => {
  if (score >= 3.5) return 'bg-green-500'
  if (score >= 3.0) return 'bg-yellow-500'
  if (score >= 2.5) return 'bg-orange-500'
  return 'bg-red-500'
}

const formatDate = (date) => {
  return new Intl.DateTimeFormat('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(date)
}
</script>

<style scoped>
.character-report {
  max-width: 100%;
}

/* Print styles for PDF export */
@media print {
  .character-report {
    max-width: none;
  }
  
  .bg-white {
    background: white !important;
  }
  
  .shadow-sm {
    box-shadow: none !important;
  }
  
  .border {
    border: 1px solid #000 !important;
  }
}
</style>
