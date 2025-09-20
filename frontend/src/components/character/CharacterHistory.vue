<template>
  <div class="character-history">
    <!-- Header -->
    <div class="mb-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-2">Riwayat Penilaian Karakter</h3>
      <p class="text-sm text-gray-600">Lihat perkembangan karakter siswa dari waktu ke waktu</p>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">Periode</label>
          <select v-model="filters.period" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            <option value="">Semua Periode</option>
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
          <label class="block text-sm font-medium text-gray-700 mb-1">Dimensi</label>
          <select v-model="filters.dimension" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm">
            <option value="">Semua Dimensi</option>
            <option v-for="dimension in dimensions" :key="dimension.id" :value="dimension.id">
              {{ dimension.nama }}
            </option>
          </select>
        </div>
        <div class="flex items-end">
          <button
            @click="loadHistory"
            class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm transition-colors"
          >
            Filter
          </button>
        </div>
      </div>
    </div>

    <!-- History Timeline -->
    <div class="space-y-4">
      <div
        v-for="assessment in historyData"
        :key="assessment.id"
        class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
      >
        <div class="flex items-start justify-between mb-4">
          <div>
            <h4 class="text-lg font-medium text-gray-900">{{ assessment.period }}</h4>
            <p class="text-sm text-gray-500">{{ assessment.assessor_name }} • {{ formatDate(assessment.created_at) }}</p>
          </div>
          <div class="text-right">
            <div class="text-2xl font-bold text-blue-600">{{ assessment.total_score }}</div>
            <div class="text-sm text-gray-500">Total Skor</div>
          </div>
        </div>

        <!-- Dimension Scores -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div
            v-for="dimension in assessment.dimensions"
            :key="dimension.id"
            class="bg-gray-50 rounded-lg p-4"
          >
            <div class="flex items-center justify-between mb-2">
              <h5 class="text-sm font-medium text-gray-900">{{ dimension.nama }}</h5>
              <span class="text-sm font-bold" :class="getScoreColor(dimension.score)">
                {{ dimension.score }}
              </span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2">
              <div
                :class="getScoreColor(dimension.score)"
                class="h-2 rounded-full"
                :style="{ width: (dimension.score / 4) * 100 + '%' }"
              ></div>
            </div>
            <p class="text-xs text-gray-500 mt-1">{{ dimension.notes || 'Tidak ada catatan' }}</p>
          </div>
        </div>

        <!-- Overall Comments -->
        <div v-if="assessment.comments" class="mt-4 p-4 bg-blue-50 rounded-lg">
          <h6 class="text-sm font-medium text-blue-900 mb-1">Komentar Penilai</h6>
          <p class="text-sm text-blue-800">{{ assessment.comments }}</p>
        </div>

        <!-- Actions -->
        <div class="flex justify-end space-x-2 mt-4">
          <button
            @click="viewDetails(assessment)"
            class="text-blue-600 hover:text-blue-700 text-sm font-medium"
          >
            Lihat Detail
          </button>
          <button
            v-if="canEdit(assessment)"
            @click="editAssessment(assessment)"
            class="text-green-600 hover:text-green-700 text-sm font-medium"
          >
            Edit
          </button>
        </div>
      </div>
    </div>

    <!-- Empty State -->
    <div v-if="historyData.length === 0 && !loading" class="text-center py-12">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
      </svg>
      <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada riwayat penilaian</h3>
      <p class="mt-1 text-sm text-gray-500">Riwayat penilaian karakter akan muncul di sini.</p>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="text-center py-12">
      <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      <p class="mt-2 text-sm text-gray-500">Memuat riwayat...</p>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'

const props = defineProps({
  studentId: {
    type: [String, Number],
    required: true
  }
})

const emit = defineEmits(['view-details', 'edit-assessment'])

const authStore = useAuthStore()

// Reactive data
const loading = ref(false)
const historyData = ref([])

// Filters
const filters = reactive({
  period: '',
  semester: '',
  dimension: ''
})

// Character dimensions
const dimensions = ref([
  { id: 1, nama: 'Spiritual & Religius' },
  { id: 2, nama: 'Sosial & Kebangsaan' },
  { id: 3, nama: 'Gotong Royong' },
  { id: 4, nama: 'Mandiri' },
  { id: 5, nama: 'Bernalar Kritis' },
  { id: 6, nama: 'Kreatif' }
])

// Methods
const loadHistory = async () => {
  try {
    loading.value = true
    
    // Mock data - replace with actual API call
    const mockData = [
      {
        id: 1,
        period: 'Semester 1 - 2024/2025',
        assessor_name: 'Budi Santoso, S.Pd',
        created_at: '2024-01-15',
        total_score: 3.7,
        comments: 'Siswa menunjukkan perkembangan yang baik dalam dimensi karakter. Perlu lebih banyak latihan dalam dimensi kreatif.',
        dimensions: [
          { id: 1, nama: 'Spiritual & Religius', score: 4.0, notes: 'Sangat baik' },
          { id: 2, nama: 'Sosial & Kebangsaan', score: 3.5, notes: 'Baik' },
          { id: 3, nama: 'Gotong Royong', score: 4.0, notes: 'Sangat baik' },
          { id: 4, nama: 'Mandiri', score: 3.0, notes: 'Cukup' },
          { id: 5, nama: 'Bernalar Kritis', score: 3.5, notes: 'Baik' },
          { id: 6, nama: 'Kreatif', score: 3.0, notes: 'Perlu ditingkatkan' }
        ]
      },
      {
        id: 2,
        period: 'Semester 2 - 2023/2024',
        assessor_name: 'Siti Aminah, S.Pd',
        created_at: '2024-06-20',
        total_score: 3.5,
        comments: 'Perkembangan karakter siswa sudah menunjukkan peningkatan yang signifikan.',
        dimensions: [
          { id: 1, nama: 'Spiritual & Religius', score: 3.5, notes: 'Baik' },
          { id: 2, nama: 'Sosial & Kebangsaan', score: 3.0, notes: 'Cukup' },
          { id: 3, nama: 'Gotong Royong', score: 3.5, notes: 'Baik' },
          { id: 4, nama: 'Mandiri', score: 3.0, notes: 'Cukup' },
          { id: 5, nama: 'Bernalar Kritis', score: 3.5, notes: 'Baik' },
          { id: 6, nama: 'Kreatif', score: 3.5, notes: 'Baik' }
        ]
      }
    ]
    
    historyData.value = mockData
    
  } catch (error) {
    console.error('Failed to load character history:', error)
  } finally {
    loading.value = false
  }
}

const getScoreColor = (score) => {
  if (score >= 3.5) return 'text-green-600 bg-green-500'
  if (score >= 3.0) return 'text-yellow-600 bg-yellow-500'
  if (score >= 2.5) return 'text-orange-600 bg-orange-500'
  return 'text-red-600 bg-red-500'
}

const formatDate = (date) => {
  return new Intl.DateTimeFormat('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(new Date(date))
}

const canEdit = (assessment) => {
  // Only allow edit if current user is the assessor or admin
  return authStore.hasRole('admin') || assessment.assessor_id === authStore.user?.id
}

const viewDetails = (assessment) => {
  emit('view-details', assessment)
}

const editAssessment = (assessment) => {
  emit('edit-assessment', assessment)
}

// Lifecycle
onMounted(() => {
  loadHistory()
})
</script>

<style scoped>
.character-history {
  max-width: 100%;
}
</style>
