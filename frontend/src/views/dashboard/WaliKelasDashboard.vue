<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Page Header -->
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Wali Kelas</h1>
        <p class="mt-1 text-sm text-gray-500">
          Selamat datang, {{ authStore.userProfile?.nama_lengkap || authStore.user?.username }}. 
          Kelola kelas {{ authStore.userProfile?.kelas_wali || 'Anda' }} dengan baik.
        </p>
      </div>

      <!-- Stats Overview -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <StatCard
          title="Total Siswa Kelas"
          :value="stats.totalSiswa"
          :loading="statsLoading"
          icon="user-group"
          color="blue"
        />
        <StatCard
          title="Hadir Hari Ini"
          :value="stats.hadirToday"
          :loading="statsLoading"
          icon="check-circle"
          color="green"
        />
        <StatCard
          title="Kredit Poin Rata-rata"
          :value="stats.avgKreditPoin"
          :loading="statsLoading"
          icon="star"
          color="yellow"
        />
        <StatCard
          title="Perlu Perhatian"
          :value="stats.needAttention"
          :loading="statsLoading"
          icon="exclamation-triangle"
          color="red"
        />
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
              <h3 class="text-lg font-semibold text-gray-900">Penilaian Karakter Kelas</h3>
              <p class="text-sm text-gray-500">Monitor perkembangan karakter siswa kelas {{ authStore.userProfile?.kelas_wali || 'Anda' }}</p>
            </div>
          </div>
          <router-link to="/dashboard/kredit-poin" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            Kelola Penilaian
          </router-link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
          <div class="bg-purple-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
              <h4 class="text-sm font-medium text-purple-900">Penilaian Bulan Ini</h4>
              <span class="text-lg font-bold text-purple-600">{{ characterStats.monthlyAssessments || 0 }}</span>
            </div>
            <p class="text-xs text-purple-700">Penilaian yang sudah dilakukan</p>
          </div>
          
          <div class="bg-green-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
              <h4 class="text-sm font-medium text-green-900">Siswa Berprestasi</h4>
              <span class="text-lg font-bold text-green-600">{{ characterStats.topPerformers || 0 }}</span>
            </div>
            <p class="text-xs text-green-700">Siswa dengan skor tinggi</p>
          </div>
          
          <div class="bg-yellow-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
              <h4 class="text-sm font-medium text-yellow-900">Perlu Bimbingan</h4>
              <span class="text-lg font-bold text-yellow-600">{{ characterStats.needsGuidance || 0 }}</span>
            </div>
            <p class="text-xs text-yellow-700">Siswa yang perlu perhatian</p>
          </div>
          
          <div class="bg-blue-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
              <h4 class="text-sm font-medium text-blue-900">Rata-rata Kelas</h4>
              <span class="text-lg font-bold text-blue-600">{{ characterStats.classAverage || 0 }}</span>
            </div>
            <p class="text-xs text-blue-700">Skor rata-rata kelas</p>
          </div>
        </div>

        <!-- Character Dimensions Overview -->
        <div class="mt-6">
          <h4 class="text-sm font-medium text-gray-900 mb-3">Performa Dimensi Karakter Kelas</h4>
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
                    :class="getDimensionColor(dimension.average)"
                    class="h-2 rounded-full"
                    :style="{ width: (dimension.average / 4) * 100 + '%' }"
                  ></div>
                </div>
                <span class="text-sm font-medium text-gray-900 w-8">{{ dimension.average }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Actions -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="card p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Cepat Wali Kelas</h3>
          <div class="grid grid-cols-2 gap-3">
            <button class="btn btn-primary">Input Presensi</button>
            <button class="btn btn-secondary">Lihat Nilai</button>
            <button class="btn btn-secondary">Konseling Siswa</button>
            <button class="btn btn-outline">Laporan Kelas</button>
          </div>
        </div>

        <div class="card p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Kelas</h3>
          <div class="space-y-3 text-sm">
            <div class="flex justify-between">
              <span class="text-gray-600">Kelas</span>
              <span class="font-medium">{{ authStore.userProfile?.kelas_wali || '-' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">Jumlah Siswa</span>
              <span class="font-medium">{{ stats.totalSiswa }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">Tingkat Kehadiran</span>
              <span class="font-medium">{{ Math.round((stats.hadirToday / stats.totalSiswa) * 100) }}%</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '../../stores/auth'
import StatCard from '../../components/ui/StatCard.vue'

const authStore = useAuthStore()

const stats = ref({
  totalSiswa: 32,
  hadirToday: 30,
  avgKreditPoin: 78,
  needAttention: 3
})

const characterStats = ref({
  monthlyAssessments: 15,
  topPerformers: 8,
  needsGuidance: 3,
  classAverage: 3.6
})

const characterDimensions = ref([
  { id: 1, nama: 'Spiritual & Religius', average: 3.8 },
  { id: 2, nama: 'Sosial & Kebangsaan', average: 3.5 },
  { id: 3, nama: 'Gotong Royong', average: 3.9 },
  { id: 4, nama: 'Mandiri', average: 3.2 },
  { id: 5, nama: 'Bernalar Kritis', average: 3.7 },
  { id: 6, nama: 'Kreatif', average: 3.4 }
])

const statsLoading = ref(false)

const getDimensionColor = (score) => {
  if (score >= 3.5) return 'bg-green-500'
  if (score >= 3.0) return 'bg-yellow-500'
  if (score >= 2.5) return 'bg-orange-500'
  return 'bg-red-500'
}

onMounted(() => {
  // Fetch stats
})
</script>
