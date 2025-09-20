<template>
  <div class="character-assessment">
    <!-- Header -->
    <div class="mb-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-2">Penilaian Karakter Dinamis</h3>
      <p class="text-sm text-gray-600">Penilaian karakter siswa berdasarkan dimensi yang dapat dikonfigurasi</p>
    </div>

    <!-- Student Info -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
      <div class="flex items-center space-x-4">
        <div class="flex-shrink-0">
          <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
            </svg>
          </div>
        </div>
        <div class="flex-1">
          <h4 class="text-lg font-medium text-gray-900">{{ student.nama }}</h4>
          <p class="text-sm text-gray-500">{{ student.kelas }} - {{ student.nis }}</p>
        </div>
        <div class="text-right">
          <p class="text-sm text-gray-500">Periode</p>
          <p class="text-sm font-medium text-gray-900">{{ assessment.period }}</p>
        </div>
      </div>
    </div>

    <!-- Assessment Form -->
    <form @submit.prevent="submitAssessment" class="space-y-6">
      <!-- Character Dimensions -->
      <div
        v-for="dimension in dimensions"
        :key="dimension.id"
        class="bg-white rounded-lg shadow-sm border border-gray-200 p-6"
      >
        <div class="mb-4">
          <h4 class="text-lg font-semibold text-gray-900 mb-2">{{ dimension.nama }}</h4>
          <p class="text-sm text-gray-600">{{ dimension.deskripsi }}</p>
        </div>

        <!-- Indicators -->
        <div class="space-y-4">
          <div
            v-for="indicator in dimension.indikator"
            :key="indicator.id"
            class="border border-gray-200 rounded-lg p-4"
          >
            <div class="flex items-start justify-between mb-3">
              <div class="flex-1">
                <h5 class="text-sm font-medium text-gray-900 mb-1">{{ indicator.nama }}</h5>
                <p class="text-xs text-gray-500">{{ indicator.deskripsi }}</p>
              </div>
            </div>

            <!-- Rating Scale -->
            <div class="flex items-center space-x-4">
              <span class="text-xs text-gray-500">Nilai:</span>
              <div class="flex space-x-2">
                <label
                  v-for="(scale, index) in ratingScale"
                  :key="index"
                  class="flex items-center space-x-1 cursor-pointer"
                >
                  <input
                    :name="`dimension_${dimension.id}_indicator_${indicator.id}`"
                    :value="scale.value"
                    v-model="assessment.scores[`${dimension.id}_${indicator.id}`]"
                    type="radio"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300"
                  >
                  <span class="text-sm text-gray-700">{{ scale.label }}</span>
                </label>
              </div>
            </div>

            <!-- Evidence/Notes -->
            <div class="mt-3">
              <label class="block text-xs font-medium text-gray-700 mb-1">Bukti/Catatan</label>
              <textarea
                v-model="assessment.evidence[`${dimension.id}_${indicator.id}`]"
                rows="2"
                class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :placeholder="`Masukkan bukti atau catatan untuk ${indicator.nama}`"
              ></textarea>
            </div>
          </div>
        </div>

        <!-- Dimension Summary -->
        <div class="mt-4 p-3 bg-gray-50 rounded-lg">
          <div class="flex items-center justify-between">
            <span class="text-sm font-medium text-gray-700">Rata-rata Dimensi:</span>
            <span class="text-sm font-semibold text-gray-900">
              {{ getDimensionAverage(dimension.id) }}
            </span>
          </div>
        </div>
      </div>

      <!-- Overall Assessment -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h4 class="text-lg font-semibold text-gray-900 mb-4">Penilaian Keseluruhan</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Overall Score -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Keseluruhan</label>
            <div class="flex items-center space-x-2">
              <input
                v-model="assessment.overallScore"
                type="number"
                min="1"
                :max="maxScore"
                step="0.1"
                class="w-20 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
              <span class="text-sm text-gray-500">/ {{ maxScore }}</span>
            </div>
          </div>

          <!-- Assessment Date -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Penilaian</label>
            <input
              v-model="assessment.assessmentDate"
              type="date"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            >
          </div>
        </div>

        <!-- Comments -->
        <div class="mt-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">Komentar Penilaian</label>
          <textarea
            v-model="assessment.comments"
            rows="4"
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Masukkan komentar atau catatan tambahan untuk penilaian ini"
          ></textarea>
        </div>

        <!-- Recommendations -->
        <div class="mt-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">Rekomendasi</label>
          <div class="space-y-2">
            <label
              v-for="recommendation in recommendations"
              :key="recommendation.id"
              class="flex items-center space-x-2"
            >
              <input
                v-model="assessment.recommendations"
                :value="recommendation.id"
                type="checkbox"
                class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
              >
              <span class="text-sm text-gray-700">{{ recommendation.text }}</span>
            </label>
          </div>
        </div>
      </div>

      <!-- Action Buttons -->
      <div class="flex justify-end space-x-4">
        <button
          type="button"
          @click="saveDraft"
          :disabled="loading"
          class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 disabled:opacity-50"
        >
          Simpan Draft
        </button>
        <button
          type="submit"
          :disabled="loading || !isFormValid"
          class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
        >
          <span v-if="loading">Menyimpan...</span>
          <span v-else>Simpan Penilaian</span>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'

// Props
const props = defineProps({
  student: {
    type: Object,
    required: true
  },
  assessmentId: {
    type: [String, Number],
    default: null
  },
  period: {
    type: String,
    default: 'bulanan'
  }
})

// Emits
const emit = defineEmits(['saved', 'draft-saved'])

// Reactive data
const loading = ref(false)
const dimensions = ref([])
const ratingScale = ref([
  { value: 1, label: 'Kurang' },
  { value: 2, label: 'Cukup' },
  { value: 3, label: 'Baik' },
  { value: 4, label: 'Sangat Baik' }
])
const maxScore = ref(4)

const assessment = reactive({
  period: props.period,
  scores: {},
  evidence: {},
  overallScore: 0,
  assessmentDate: new Date().toISOString().split('T')[0],
  comments: '',
  recommendations: []
})

const recommendations = ref([
  { id: 'improve_attendance', text: 'Perbaiki kehadiran' },
  { id: 'enhance_participation', text: 'Tingkatkan partisipasi' },
  { id: 'develop_leadership', text: 'Kembangkan kepemimpinan' },
  { id: 'strengthen_character', text: 'Perkuat karakter' },
  { id: 'academic_support', text: 'Dukungan akademik' }
])

// Computed
const isFormValid = computed(() => {
  // Check if all dimensions have at least one score
  const hasScores = dimensions.value.every(dimension => 
    dimension.indikator.some(indicator => 
      assessment.scores[`${dimension.id}_${indicator.id}`]
    )
  )
  
  return hasScores && assessment.overallScore > 0
})

// Methods
const loadDimensions = async () => {
  try {
    // TODO: Implement API call
    // const response = await api.get('/character-dimensions')
    // dimensions.value = response.data
    
    // Mock data
    dimensions.value = [
      {
        id: 1,
        nama: 'Spiritual & Religius',
        deskripsi: 'Kemampuan menghayati dan mengamalkan ajaran agama yang dianut',
        indikator: [
          {
            id: 1,
            nama: 'Melaksanakan ibadah sesuai agama',
            deskripsi: 'Siswa melaksanakan ibadah sesuai dengan agama yang dianut'
          },
          {
            id: 2,
            nama: 'Menghormati perbedaan agama',
            deskripsi: 'Siswa menghormati dan toleran terhadap perbedaan agama'
          },
          {
            id: 3,
            nama: 'Berperilaku jujur dan amanah',
            deskripsi: 'Siswa menunjukkan kejujuran dan dapat dipercaya'
          }
        ]
      },
      {
        id: 2,
        nama: 'Sosial & Kebangsaan',
        deskripsi: 'Kemampuan berinteraksi dengan lingkungan sosial dan memiliki rasa kebangsaan',
        indikator: [
          {
            id: 4,
            nama: 'Menghormati orang lain',
            deskripsi: 'Siswa menghormati dan menghargai orang lain'
          },
          {
            id: 5,
            nama: 'Mengikuti upacara bendera',
            deskripsi: 'Siswa aktif mengikuti upacara bendera dan kegiatan kebangsaan'
          },
          {
            id: 6,
            nama: 'Menggunakan bahasa Indonesia yang baik',
            deskripsi: 'Siswa menggunakan bahasa Indonesia yang baik dan benar'
          }
        ]
      },
      {
        id: 3,
        nama: 'Gotong Royong',
        deskripsi: 'Kemampuan bekerja sama dalam kelompok dan membantu sesama',
        indikator: [
          {
            id: 7,
            nama: 'Bekerja sama dalam kelompok',
            deskripsi: 'Siswa dapat bekerja sama dengan baik dalam kelompok'
          },
          {
            id: 8,
            nama: 'Membantu teman yang kesulitan',
            deskripsi: 'Siswa membantu teman yang mengalami kesulitan'
          },
          {
            id: 9,
            nama: 'Berpartisipasi dalam kegiatan sekolah',
            deskripsi: 'Siswa aktif berpartisipasi dalam kegiatan sekolah'
          }
        ]
      }
    ]
  } catch (error) {
    console.error('Error loading dimensions:', error)
  }
}

const getDimensionAverage = (dimensionId) => {
  const dimension = dimensions.value.find(d => d.id === dimensionId)
  if (!dimension) return '0.0'
  
  const scores = dimension.indikator
    .map(indicator => assessment.scores[`${dimensionId}_${indicator.id}`])
    .filter(score => score !== undefined && score !== null)
  
  if (scores.length === 0) return '0.0'
  
  const average = scores.reduce((sum, score) => sum + score, 0) / scores.length
  return average.toFixed(1)
}

const calculateOverallScore = () => {
  const allScores = Object.values(assessment.scores).filter(score => score !== undefined && score !== null)
  if (allScores.length === 0) return 0
  
  const average = allScores.reduce((sum, score) => sum + score, 0) / allScores.length
  assessment.overallScore = Math.round(average * 10) / 10
}

const saveDraft = async () => {
  try {
    loading.value = true
    // TODO: Implement API call
    // await api.post('/character-assessments/draft', assessment)
    
    console.log('Saving draft:', assessment)
    emit('draft-saved', assessment)
  } catch (error) {
    console.error('Error saving draft:', error)
  } finally {
    loading.value = false
  }
}

const submitAssessment = async () => {
  try {
    loading.value = true
    
    const assessmentData = {
      ...assessment,
      studentId: props.student.id,
      assessmentId: props.assessmentId,
      dimensionAverages: dimensions.value.map(dimension => ({
        dimensionId: dimension.id,
        average: parseFloat(getDimensionAverage(dimension.id))
      }))
    }
    
    // TODO: Implement API call
    // await api.post('/character-assessments', assessmentData)
    
    console.log('Submitting assessment:', assessmentData)
    emit('saved', assessmentData)
  } catch (error) {
    console.error('Error submitting assessment:', error)
  } finally {
    loading.value = false
  }
}

// Watch for score changes to auto-calculate overall score
watch(() => assessment.scores, () => {
  calculateOverallScore()
}, { deep: true })

// Lifecycle
onMounted(() => {
  loadDimensions()
})
</script>

<style scoped>
.character-assessment {
  @apply max-w-4xl mx-auto;
}
</style>
