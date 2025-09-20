<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Orang Tua</h1>
        <p class="mt-1 text-sm text-gray-500">
          Pantau perkembangan akademik dan perilaku anak Anda.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <StatCard title="Kehadiran Bulan Ini" :value="'95%'" icon="check-circle" color="green" />
        <StatCard title="Kredit Poin" :value="85" icon="star" color="yellow" />
        <StatCard title="Tugas Selesai" :value="'88%'" icon="document-check" color="blue" />
        <StatCard title="Ranking Kelas" :value="'#8'" icon="trophy" color="purple" />
      </div>

      <!-- Character Assessment Section -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
          <div class="flex items-center space-x-3">
            <div class="p-2 bg-purple-100 rounded-lg">
              <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Perkembangan Karakter Anak</h3>
              <p class="text-sm text-gray-500">Pantau perkembangan karakter {{ authStore.userProfile?.siswa?.nama_lengkap || 'anak Anda' }}</p>
            </div>
          </div>
          <div class="text-right">
            <div class="text-2xl font-bold text-purple-600">{{ characterStats.overallScore }}</div>
            <div class="text-sm text-gray-500">Skor Keseluruhan</div>
          </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-green-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
              <h4 class="text-sm font-medium text-green-900">Dimensi Terbaik</h4>
              <span class="text-lg font-bold text-green-600">{{ characterStats.bestDimension.score }}</span>
            </div>
            <p class="text-xs text-green-700">{{ characterStats.bestDimension.nama }}</p>
          </div>
          
          <div class="bg-yellow-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
              <h4 class="text-sm font-medium text-yellow-900">Perlu Peningkatan</h4>
              <span class="text-lg font-bold text-yellow-600">{{ characterStats.needsImprovement.score }}</span>
            </div>
            <p class="text-xs text-yellow-700">{{ characterStats.needsImprovement.nama }}</p>
          </div>
          
          <div class="bg-blue-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
              <h4 class="text-sm font-medium text-blue-900">Penilaian Terbaru</h4>
              <span class="text-lg font-bold text-blue-600">{{ characterStats.lastAssessment }}</span>
            </div>
            <p class="text-xs text-blue-700">{{ characterStats.lastAssessmentDate }}</p>
          </div>
        </div>

        <!-- Character Dimensions Overview -->
        <div class="mt-6">
          <h4 class="text-sm font-medium text-gray-900 mb-3">Performa Dimensi Karakter</h4>
          <div class="space-y-3">
            <div
              v-for="dimension in characterDimensions"
              :key="dimension.id"
              class="flex items-center justify-between"
            >
              <span class="text-sm text-gray-700">{{ dimension.nama }}</span>
              <div class="flex items-center space-x-2">
                <div class="w-20 bg-gray-200 rounded-full h-2">
                  <div
                    :class="getDimensionColor(dimension.score)"
                    class="h-2 rounded-full"
                    :style="{ width: (dimension.score / 4) * 100 + '%' }"
                  ></div>
                </div>
                <span class="text-sm font-medium text-gray-900 w-8">{{ dimension.score }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Anak</h3>
          <div class="space-y-3 text-sm">
            <div class="flex justify-between">
              <span class="text-gray-600">Nama</span>
              <span class="font-medium">{{ authStore.userProfile?.siswa?.nama_lengkap || '-' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">Kelas</span>
              <span class="font-medium">{{ authStore.userProfile?.siswa?.kelas || '-' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">NISN</span>
              <span class="font-medium">{{ authStore.userProfile?.siswa?.nisn || '-' }}</span>
            </div>
          </div>
        </div>

        <div class="card p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Cepat</h3>
          <div class="space-y-3">
            <button class="w-full btn btn-primary text-left">Lihat Nilai Anak</button>
            <button class="w-full btn btn-secondary text-left">Riwayat Presensi</button>
            <button class="w-full btn btn-secondary text-left">Jadwalkan Konseling</button>
            <button class="w-full btn btn-outline text-left">Hubungi Wali Kelas</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useAuthStore } from '../../stores/auth'
import StatCard from '../../components/ui/StatCard.vue'

const authStore = useAuthStore()

const characterStats = ref({
  overallScore: 3.7,
  bestDimension: { nama: 'Gotong Royong', score: 4.0 },
  needsImprovement: { nama: 'Kreatif', score: 3.0 },
  lastAssessment: 'Semester 1',
  lastAssessmentDate: '15 Jan 2024'
})

const characterDimensions = ref([
  { id: 1, nama: 'Spiritual & Religius', score: 4.0 },
  { id: 2, nama: 'Sosial & Kebangsaan', score: 3.5 },
  { id: 3, nama: 'Gotong Royong', score: 4.0 },
  { id: 4, nama: 'Mandiri', score: 3.0 },
  { id: 5, nama: 'Bernalar Kritis', score: 3.5 },
  { id: 6, nama: 'Kreatif', score: 3.0 }
])

const getDimensionColor = (score) => {
  if (score >= 3.5) return 'bg-green-500'
  if (score >= 3.0) return 'bg-yellow-500'
  if (score >= 2.5) return 'bg-orange-500'
  return 'bg-red-500'
}
</script>
