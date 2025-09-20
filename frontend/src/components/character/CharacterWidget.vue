<template>
  <div class="character-widget">
    <!-- Widget Header -->
    <div class="flex items-center justify-between mb-4">
      <h3 class="text-lg font-semibold text-gray-900">Penilaian Karakter</h3>
      <div class="flex items-center space-x-2">
        <select
          v-model="selectedPeriod"
          @change="loadCharacterData"
          class="text-sm border border-gray-300 rounded-md px-2 py-1 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option value="bulanan">Bulanan</option>
          <option value="semester">Semester</option>
          <option value="tahunan">Tahunan</option>
        </select>
      </div>
    </div>

    <!-- Character Dimensions Overview -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
      <div
        v-for="dimension in characterData.dimensions"
        :key="dimension.id"
        class="bg-white rounded-lg border border-gray-200 p-4 hover:shadow-md transition-shadow"
      >
        <div class="flex items-center justify-between mb-2">
          <h4 class="text-sm font-medium text-gray-900 truncate">{{ dimension.nama }}</h4>
          <span class="text-xs text-gray-500">{{ dimension.average }}/4</span>
        </div>
        
        <!-- Progress Bar -->
        <div class="w-full bg-gray-200 rounded-full h-2 mb-2">
          <div
            :class="getProgressBarClass(dimension.average)"
            class="h-2 rounded-full transition-all duration-300"
            :style="{ width: (dimension.average / 4) * 100 + '%' }"
          ></div>
        </div>
        
        <!-- Trend Indicator -->
        <div class="flex items-center justify-between">
          <span class="text-xs text-gray-500">{{ dimension.trend }}%</span>
          <div class="flex items-center">
            <svg
              v-if="dimension.trend > 0"
              class="w-3 h-3 text-green-500"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
            </svg>
            <svg
              v-else-if="dimension.trend < 0"
              class="w-3 h-3 text-red-500"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7l-9.2 9.2M7 7v10h10"></path>
            </svg>
            <svg
              v-else
              class="w-3 h-3 text-gray-500"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
            </svg>
          </div>
        </div>
      </div>
    </div>

    <!-- Overall Character Score -->
    <div class="bg-gradient-to-r from-blue-500 to-blue-600 rounded-lg p-6 text-white mb-6">
      <div class="flex items-center justify-between">
        <div>
          <h4 class="text-lg font-semibold mb-2">Nilai Karakter Keseluruhan</h4>
          <p class="text-blue-100">Periode {{ selectedPeriod }}</p>
        </div>
        <div class="text-right">
          <div class="text-3xl font-bold">{{ characterData.overallScore }}</div>
          <div class="text-blue-100 text-sm">dari 4.0</div>
        </div>
      </div>
      
      <!-- Overall Progress -->
      <div class="mt-4">
        <div class="w-full bg-blue-400 rounded-full h-3">
          <div
            class="bg-white h-3 rounded-full transition-all duration-500"
            :style="{ width: (characterData.overallScore / 4) * 100 + '%' }"
          ></div>
        </div>
      </div>
    </div>

    <!-- Top Performers -->
    <div class="bg-white rounded-lg border border-gray-200 p-4 mb-6">
      <h4 class="text-lg font-semibold text-gray-900 mb-4">Siswa Berprestasi Karakter</h4>
      
      <div class="space-y-3">
        <div
          v-for="(student, index) in characterData.topPerformers"
          :key="student.id"
          class="flex items-center space-x-3"
        >
          <div class="flex-shrink-0">
            <div :class="getRankBadgeClass(index + 1)" class="w-8 h-8 rounded-full flex items-center justify-center">
              <span class="text-xs font-bold text-white">{{ index + 1 }}</span>
            </div>
          </div>
          <div class="flex-1 min-w-0">
            <p class="text-sm font-medium text-gray-900 truncate">{{ student.nama }}</p>
            <p class="text-xs text-gray-500">{{ student.kelas }}</p>
          </div>
          <div class="flex-shrink-0">
            <span class="text-sm font-semibold text-gray-900">{{ student.score }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Character Insights -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
      <!-- Strengths -->
      <div class="bg-white rounded-lg border border-gray-200 p-4">
        <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
          <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
          Kekuatan
        </h4>
        <div class="space-y-2">
          <div
            v-for="strength in characterData.strengths"
            :key="strength.id"
            class="flex items-center justify-between p-2 bg-green-50 rounded-lg"
          >
            <span class="text-sm text-gray-900">{{ strength.nama }}</span>
            <span class="text-sm font-semibold text-green-600">{{ strength.score }}</span>
          </div>
        </div>
      </div>

      <!-- Areas for Improvement -->
      <div class="bg-white rounded-lg border border-gray-200 p-4">
        <h4 class="text-lg font-semibold text-gray-900 mb-3 flex items-center">
          <svg class="w-5 h-5 text-yellow-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
          </svg>
          Perlu Perbaikan
        </h4>
        <div class="space-y-2">
          <div
            v-for="improvement in characterData.improvements"
            :key="improvement.id"
            class="flex items-center justify-between p-2 bg-yellow-50 rounded-lg"
          >
            <span class="text-sm text-gray-900">{{ improvement.nama }}</span>
            <span class="text-sm font-semibold text-yellow-600">{{ improvement.score }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 flex flex-wrap gap-3">
      <button
        @click="viewDetailedReport"
        class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 text-sm"
      >
        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
        </svg>
        Laporan Detail
      </button>
      
      <button
        @click="exportData"
        class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 text-sm"
      >
        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
        </svg>
        Export Data
      </button>
      
      <button
        @click="configureDimensions"
        class="bg-purple-600 text-white px-4 py-2 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 text-sm"
      >
        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
        </svg>
        Konfigurasi
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'

// Props
const props = defineProps({
  studentId: {
    type: [String, Number],
    default: null
  },
  classId: {
    type: [String, Number],
    default: null
  },
  period: {
    type: String,
    default: 'bulanan'
  }
})

// Emits
const emit = defineEmits(['view-detail', 'export-data', 'configure'])

// Router
const router = useRouter()

// Reactive data
const selectedPeriod = ref(props.period)
const characterData = reactive({
  dimensions: [],
  overallScore: 0,
  topPerformers: [],
  strengths: [],
  improvements: []
})

// Methods
const loadCharacterData = async () => {
  try {
    // TODO: Implement API call
    // const response = await api.get('/character-data', {
    //   params: {
    //     studentId: props.studentId,
    //     classId: props.classId,
    //     period: selectedPeriod.value
    //   }
    // })
    // Object.assign(characterData, response.data)
    
    // Mock data
    characterData.dimensions = [
      {
        id: 1,
        nama: 'Spiritual & Religius',
        average: 3.8,
        trend: 5.2
      },
      {
        id: 2,
        nama: 'Sosial & Kebangsaan',
        average: 3.6,
        trend: 2.1
      },
      {
        id: 3,
        nama: 'Gotong Royong',
        average: 3.9,
        trend: 8.3
      },
      {
        id: 4,
        nama: 'Mandiri',
        average: 3.4,
        trend: -1.2
      },
      {
        id: 5,
        nama: 'Bernalar Kritis',
        average: 3.7,
        trend: 3.5
      },
      {
        id: 6,
        nama: 'Kreatif',
        average: 3.5,
        trend: 4.1
      }
    ]
    
    characterData.overallScore = 3.7
    
    characterData.topPerformers = [
      { id: 1, nama: 'Ahmad Fauzi', kelas: 'XII IPA 1', score: 3.9 },
      { id: 2, nama: 'Siti Aminah', kelas: 'XII IPS 1', score: 3.8 },
      { id: 3, nama: 'Budi Santoso', kelas: 'XI IPA 2', score: 3.7 }
    ]
    
    characterData.strengths = [
      { id: 1, nama: 'Gotong Royong', score: 3.9 },
      { id: 2, nama: 'Spiritual & Religius', score: 3.8 }
    ]
    
    characterData.improvements = [
      { id: 1, nama: 'Mandiri', score: 3.4 },
      { id: 2, nama: 'Kreatif', score: 3.5 }
    ]
  } catch (error) {
    console.error('Error loading character data:', error)
  }
}

const getProgressBarClass = (score) => {
  if (score >= 3.5) return 'bg-green-500'
  if (score >= 3.0) return 'bg-yellow-500'
  if (score >= 2.5) return 'bg-orange-500'
  return 'bg-red-500'
}

const getRankBadgeClass = (rank) => {
  if (rank === 1) return 'bg-yellow-500'
  if (rank === 2) return 'bg-gray-400'
  if (rank === 3) return 'bg-orange-500'
  return 'bg-blue-500'
}

const viewDetailedReport = () => {
  emit('view-detail', {
    studentId: props.studentId,
    classId: props.classId,
    period: selectedPeriod.value
  })
  
  // Navigate to detailed report
  router.push({
    name: 'CharacterReport',
    query: {
      studentId: props.studentId,
      classId: props.classId,
      period: selectedPeriod.value
    }
  })
}

const exportData = () => {
  emit('export-data', {
    studentId: props.studentId,
    classId: props.classId,
    period: selectedPeriod.value,
    data: characterData
  })
}

const configureDimensions = () => {
  emit('configure')
  router.push({ name: 'KonfigurasiKarakter' })
}

// Watch for period changes
watch(selectedPeriod, () => {
  loadCharacterData()
})

// Lifecycle
onMounted(() => {
  loadCharacterData()
})
</script>

<style scoped>
.character-widget {
  @apply p-6;
}
</style>
