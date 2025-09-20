<template>
  <div class="character-dashboard">
    <!-- Header -->
    <div class="mb-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-2">Dashboard Karakter Siswa</h3>
      <p class="text-sm text-gray-600">Monitor perkembangan karakter siswa secara real-time</p>
    </div>

    <!-- Student Selector -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
      <div class="flex items-center space-x-4">
        <div class="flex-1">
          <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Siswa</label>
          <select v-model="selectedStudentId" @change="loadStudentData" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            <option value="">Pilih siswa...</option>
            <option v-for="student in students" :key="student.id" :value="student.id">
              {{ student.nama }} - {{ student.kelas }} ({{ student.nis }})
            </option>
          </select>
        </div>
        <div class="flex items-end">
          <button
            @click="refreshData"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm transition-colors"
          >
            Refresh
          </button>
        </div>
      </div>
    </div>

    <!-- Student Info -->
    <div v-if="selectedStudent" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
      <div class="flex items-center space-x-4">
        <div class="flex-shrink-0">
          <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
            <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
          </div>
        </div>
        <div class="flex-1">
          <h4 class="text-xl font-semibold text-gray-900">{{ selectedStudent.nama }}</h4>
          <p class="text-gray-600">{{ selectedStudent.kelas }} • NIS: {{ selectedStudent.nis }}</p>
          <p class="text-sm text-gray-500">{{ selectedStudent.jenis_kelamin }} • {{ selectedStudent.agama }}</p>
        </div>
        <div class="text-right">
          <div class="text-3xl font-bold text-blue-600">{{ characterStats.overallScore }}</div>
          <div class="text-sm text-gray-500">Skor Keseluruhan</div>
        </div>
      </div>
    </div>

    <!-- Character Stats Grid -->
    <div v-if="selectedStudent" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
      <!-- Overall Performance -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
          <h5 class="text-lg font-semibold text-gray-900">Performa Keseluruhan</h5>
          <div class="p-2 bg-blue-100 rounded-lg">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
          </div>
        </div>
        <div class="space-y-3">
          <div class="flex justify-between">
            <span class="text-sm text-gray-600">Skor Rata-rata</span>
            <span class="text-sm font-semibold">{{ characterStats.overallScore }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600">Total Penilaian</span>
            <span class="text-sm font-semibold">{{ characterStats.totalAssessments }}</span>
          </div>
          <div class="flex justify-between">
            <span class="text-sm text-gray-600">Tren</span>
            <span class="text-sm font-semibold" :class="getTrendColor(characterStats.trend)">
              {{ characterStats.trend > 0 ? '↗' : characterStats.trend < 0 ? '↘' : '→' }} {{ Math.abs(characterStats.trend) }}
            </span>
          </div>
        </div>
      </div>

      <!-- Top Dimension -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
          <h5 class="text-lg font-semibold text-gray-900">Dimensi Terbaik</h5>
          <div class="p-2 bg-green-100 rounded-lg">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-green-600 mb-2">{{ characterStats.topDimension.score }}</div>
          <div class="text-sm text-gray-600 mb-1">{{ characterStats.topDimension.nama }}</div>
          <div class="text-xs text-gray-500">{{ characterStats.topDimension.description }}</div>
        </div>
      </div>

      <!-- Needs Improvement -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <div class="flex items-center justify-between mb-4">
          <h5 class="text-lg font-semibold text-gray-900">Perlu Peningkatan</h5>
          <div class="p-2 bg-yellow-100 rounded-lg">
            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
          </div>
        </div>
        <div class="text-center">
          <div class="text-2xl font-bold text-yellow-600 mb-2">{{ characterStats.needsImprovement.score }}</div>
          <div class="text-sm text-gray-600 mb-1">{{ characterStats.needsImprovement.nama }}</div>
          <div class="text-xs text-gray-500">{{ characterStats.needsImprovement.description }}</div>
        </div>
      </div>
    </div>

    <!-- Character Dimensions Chart -->
    <div v-if="selectedStudent" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
      <h5 class="text-lg font-semibold text-gray-900 mb-4">Performa Dimensi Karakter</h5>
      <div class="space-y-4">
        <div
          v-for="dimension in characterDimensions"
          :key="dimension.id"
          class="flex items-center space-x-4"
        >
          <div class="w-32 text-sm text-gray-700">{{ dimension.nama }}</div>
          <div class="flex-1 bg-gray-200 rounded-full h-3">
            <div
              :class="getScoreColor(dimension.score)"
              class="h-3 rounded-full transition-all duration-500"
              :style="{ width: (dimension.score / 4) * 100 + '%' }"
            ></div>
          </div>
          <div class="w-12 text-sm font-semibold text-center">{{ dimension.score }}</div>
          <div class="w-16 text-xs text-gray-500 text-center">{{ getScoreLabel(dimension.score) }}</div>
        </div>
      </div>
    </div>

    <!-- Recent Assessments -->
    <div v-if="selectedStudent" class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
      <div class="flex items-center justify-between mb-4">
        <h5 class="text-lg font-semibold text-gray-900">Penilaian Terbaru</h5>
        <button
          @click="viewAllAssessments"
          class="text-blue-600 hover:text-blue-700 text-sm font-medium"
        >
          Lihat Semua
        </button>
      </div>
      <div class="space-y-3">
        <div
          v-for="assessment in recentAssessments"
          :key="assessment.id"
          class="flex items-center justify-between p-3 bg-gray-50 rounded-lg"
        >
          <div>
            <div class="text-sm font-medium text-gray-900">{{ assessment.period }}</div>
            <div class="text-xs text-gray-500">{{ assessment.assessor_name }} • {{ formatDate(assessment.created_at) }}</div>
          </div>
          <div class="text-right">
            <div class="text-lg font-bold text-blue-600">{{ assessment.total_score }}</div>
            <div class="text-xs text-gray-500">Total Skor</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="!selectedStudent" class="text-center py-12">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900">Pilih siswa untuk melihat dashboard</h3>
      <p class="mt-1 text-sm text-gray-500">Pilih siswa dari dropdown di atas untuk melihat dashboard karakter mereka.</p>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'

const authStore = useAuthStore()

// Reactive data
const loading = ref(false)
const students = ref([])
const selectedStudentId = ref('')
const selectedStudent = ref(null)
const characterStats = ref({})
const characterDimensions = ref([])
const recentAssessments = ref([])

// Methods
const loadStudents = async () => {
  try {
    // Mock data - replace with actual API call
    students.value = [
      { id: 1, nama: 'Ahmad Rizki', kelas: 'X IPA 1', nis: '2024001', jenis_kelamin: 'Laki-laki', agama: 'Islam' },
      { id: 2, nama: 'Siti Nurhaliza', kelas: 'X IPA 1', nis: '2024002', jenis_kelamin: 'Perempuan', agama: 'Islam' },
      { id: 3, nama: 'Budi Santoso', kelas: 'X IPA 2', nis: '2024003', jenis_kelamin: 'Laki-laki', agama: 'Kristen' },
      { id: 4, nama: 'Maya Sari', kelas: 'X IPA 2', nis: '2024004', jenis_kelamin: 'Perempuan', agama: 'Hindu' }
    ]
  } catch (error) {
    console.error('Failed to load students:', error)
  }
}

const loadStudentData = async () => {
  if (!selectedStudentId.value) {
    selectedStudent.value = null
    return
  }

  try {
    loading.value = true
    
    // Find selected student
    selectedStudent.value = students.value.find(s => s.id == selectedStudentId.value)
    
    if (selectedStudent.value) {
      // Load character data
      await loadCharacterStats()
      await loadCharacterDimensions()
      await loadRecentAssessments()
    }
  } catch (error) {
    console.error('Failed to load student data:', error)
  } finally {
    loading.value = false
  }
}

const loadCharacterStats = async () => {
  // Mock data - replace with actual API call
  characterStats.value = {
    overallScore: 3.7,
    totalAssessments: 8,
    trend: 0.2,
    topDimension: {
      nama: 'Gotong Royong',
      score: 4.0,
      description: 'Sangat baik dalam kerja sama'
    },
    needsImprovement: {
      nama: 'Kreatif',
      score: 3.0,
      description: 'Perlu lebih banyak latihan'
    }
  }
}

const loadCharacterDimensions = async () => {
  // Mock data - replace with actual API call
  characterDimensions.value = [
    { id: 1, nama: 'Spiritual & Religius', score: 4.0 },
    { id: 2, nama: 'Sosial & Kebangsaan', score: 3.5 },
    { id: 3, nama: 'Gotong Royong', score: 4.0 },
    { id: 4, nama: 'Mandiri', score: 3.0 },
    { id: 5, nama: 'Bernalar Kritis', score: 3.5 },
    { id: 6, nama: 'Kreatif', score: 3.0 }
  ]
}

const loadRecentAssessments = async () => {
  // Mock data - replace with actual API call
  recentAssessments.value = [
    {
      id: 1,
      period: 'Semester 1 - 2024/2025',
      assessor_name: 'Budi Santoso, S.Pd',
      created_at: '2024-01-15',
      total_score: 3.7
    },
    {
      id: 2,
      period: 'Semester 2 - 2023/2024',
      assessor_name: 'Siti Aminah, S.Pd',
      created_at: '2024-06-20',
      total_score: 3.5
    }
  ]
}

const getScoreColor = (score) => {
  if (score >= 3.5) return 'bg-green-500'
  if (score >= 3.0) return 'bg-yellow-500'
  if (score >= 2.5) return 'bg-orange-500'
  return 'bg-red-500'
}

const getScoreLabel = (score) => {
  if (score >= 3.5) return 'Sangat Baik'
  if (score >= 3.0) return 'Baik'
  if (score >= 2.5) return 'Cukup'
  return 'Perlu Bimbingan'
}

const getTrendColor = (trend) => {
  if (trend > 0) return 'text-green-600'
  if (trend < 0) return 'text-red-600'
  return 'text-gray-600'
}

const formatDate = (date) => {
  return new Intl.DateTimeFormat('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  }).format(new Date(date))
}

const refreshData = () => {
  if (selectedStudentId.value) {
    loadStudentData()
  }
}

const viewAllAssessments = () => {
  // Emit event or navigate to full assessment history
  console.log('View all assessments for student:', selectedStudentId.value)
}

// Lifecycle
onMounted(() => {
  loadStudents()
})
</script>

<style scoped>
.character-dashboard {
  max-width: 100%;
}
</style>
