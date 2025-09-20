<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Page Header -->
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Siswa</h1>
        <p class="mt-1 text-sm text-gray-500">
          Selamat datang, {{ authStore.userProfile?.nama_lengkap || authStore.user?.username }}. 
          Pantau aktivitas dan perkembangan akademik Anda.
        </p>
      </div>

      <!-- Stats Overview -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <StatCard
          title="Kehadiran Bulan Ini"
          :value="`${stats.kehadiranPercent}%`"
          :loading="statsLoading"
          icon="check-circle"
          color="green"
          :trend="stats.kehadiranTrend"
          subtitle="Dari total hari sekolah"
        />
        <StatCard
          title="Kredit Poin"
          :value="stats.kreditPoin"
          :loading="statsLoading"
          icon="star"
          color="yellow"
          :trend="stats.kreditTrend"
          subtitle="Poin perilaku"
        />
        <StatCard
          title="Tugas Selesai"
          :value="`${stats.tugasPercent}%`"
          :loading="statsLoading"
          icon="document-check"
          color="blue"
          subtitle="Tugas yang dikumpulkan"
        />
        <StatCard
          title="Ranking Kelas"
          :value="`#${stats.rankingKelas}`"
          :loading="statsLoading"
          icon="trophy"
          color="purple"
          subtitle="Dari 32 siswa"
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
              <h3 class="text-lg font-semibold text-gray-900">Penilaian Karakter Saya</h3>
              <p class="text-sm text-gray-500">Lihat perkembangan karakter Anda</p>
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

      <!-- Main Content Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Jadwal dan Aktivitas -->
        <div class="lg:col-span-2 space-y-6">
          <!-- Jadwal Hari Ini -->
          <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-medium text-gray-900">Jadwal Hari Ini</h3>
              <span class="text-sm text-gray-500">{{ getCurrentDate() }}</span>
            </div>
            
            <div v-if="jadwalLoading" class="space-y-4">
              <div v-for="i in 4" :key="i" class="animate-pulse">
                <div class="flex items-center space-x-4 p-3 border rounded-lg">
                  <div class="h-4 w-20 bg-gray-200 rounded"></div>
                  <div class="flex-1 h-4 bg-gray-200 rounded"></div>
                  <div class="h-6 w-16 bg-gray-200 rounded-full"></div>
                </div>
              </div>
            </div>
            
            <div v-else-if="jadwalToday.length === 0" class="text-center py-8">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 012 0v4m0 0v4m0-4h4m-4 0H6m5 10v-1a1 1 0 10-2 0v1m2 0H9m0 0v1a1 1 0 102 0v-1m0 0h2" />
              </svg>
              <p class="mt-2 text-sm text-gray-500">Tidak ada jadwal hari ini</p>
            </div>
            
            <div v-else class="space-y-3">
              <div 
                v-for="jadwal in jadwalToday" 
                :key="jadwal.id"
                class="flex items-center justify-between p-3 border border-gray-200 rounded-lg"
                :class="jadwal.status === 'berlangsung' ? 'bg-blue-50 border-blue-200' : ''"
              >
                <div class="flex items-center space-x-3">
                  <div class="text-sm font-medium text-gray-900 min-w-0 flex-shrink-0">
                    {{ jadwal.waktu }}
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-900">{{ jadwal.mata_pelajaran }}</p>
                    <p class="text-xs text-gray-500">{{ jadwal.guru }} • {{ jadwal.ruang }}</p>
                  </div>
                </div>
                <span 
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="{
                    'bg-green-100 text-green-800': jadwal.status === 'selesai',
                    'bg-blue-100 text-blue-800': jadwal.status === 'berlangsung',
                    'bg-gray-100 text-gray-800': jadwal.status === 'akan datang'
                  }"
                >
                  {{ jadwal.status }}
                </span>
              </div>
            </div>
          </div>

          <!-- Tugas Terbaru -->
          <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-medium text-gray-900">Tugas Terbaru</h3>
              <button class="btn btn-sm btn-outline">Lihat Semua</button>
            </div>
            
            <div v-if="tugasLoading" class="space-y-4">
              <div v-for="i in 3" :key="i" class="animate-pulse">
                <div class="flex items-center space-x-4 p-3 border rounded-lg">
                  <div class="h-10 w-10 bg-gray-200 rounded-lg"></div>
                  <div class="flex-1 space-y-2">
                    <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                    <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                  </div>
                </div>
              </div>
            </div>
            
            <div v-else-if="tugasList.length === 0" class="text-center py-8">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              <p class="mt-2 text-sm text-gray-500">Belum ada tugas terbaru</p>
            </div>
            
            <div v-else class="space-y-4">
              <div 
                v-for="tugas in tugasList" 
                :key="tugas.id"
                class="flex items-center justify-between p-3 border border-gray-200 rounded-lg hover:bg-gray-50"
              >
                <div class="flex items-center space-x-3">
                  <div 
                    class="h-10 w-10 rounded-lg flex items-center justify-center"
                    :class="tugas.status === 'belum' ? 'bg-red-100' : tugas.status === 'terlambat' ? 'bg-yellow-100' : 'bg-green-100'"
                  >
                    <svg 
                      class="h-5 w-5" 
                      :class="tugas.status === 'belum' ? 'text-red-600' : tugas.status === 'terlambat' ? 'text-yellow-600' : 'text-green-600'"
                      fill="none" 
                      viewBox="0 0 24 24" 
                      stroke="currentColor"
                    >
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                  </div>
                  <div>
                    <p class="text-sm font-medium text-gray-900">{{ tugas.judul }}</p>
                    <p class="text-xs text-gray-500">{{ tugas.mata_pelajaran }} • Deadline: {{ formatDate(tugas.deadline) }}</p>
                  </div>
                </div>
                <span 
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="{
                    'bg-red-100 text-red-800': tugas.status === 'belum',
                    'bg-yellow-100 text-yellow-800': tugas.status === 'terlambat',
                    'bg-green-100 text-green-800': tugas.status === 'selesai'
                  }"
                >
                  {{ tugas.status }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <!-- Informasi Pribadi -->
          <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pribadi</h3>
            <div class="space-y-3 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-600">NISN</span>
                <span class="font-medium">{{ authStore.userProfile?.nisn || '-' }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">NIS</span>
                <span class="font-medium">{{ authStore.userProfile?.nis || '-' }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Kelas</span>
                <span class="font-medium">{{ authStore.userProfile?.kelas || '-' }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Angkatan</span>
                <span class="font-medium">{{ authStore.userProfile?.angkatan || '-' }}</span>
              </div>
            </div>
          </div>

          <!-- Prestasi Terbaru -->
          <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Prestasi Terbaru</h3>
            <div v-if="prestasiList.length === 0" class="text-center py-4">
              <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.5 2.5L16 4.5 14.5 3 16 1.5 17.5 3 16 4.5 14.5 6L16 7.5M13 13h8m-8 4h8m-8-8h8m-8-4h8" />
              </svg>
              <p class="mt-2 text-xs text-gray-500">Belum ada prestasi</p>
            </div>
            <div v-else class="space-y-3">
              <div v-for="prestasi in prestasiList" :key="prestasi.id" class="flex items-start space-x-3">
                <div class="h-8 w-8 rounded-full bg-yellow-100 flex items-center justify-center flex-shrink-0">
                  <svg class="h-4 w-4 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                </div>
                <div class="flex-1 min-w-0">
                  <p class="text-sm font-medium text-gray-900">{{ prestasi.nama }}</p>
                  <p class="text-xs text-gray-500">{{ prestasi.tingkat }} • {{ prestasi.tahun }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
              <button class="w-full btn btn-primary text-left">
                <svg class="inline h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                Lihat Semua Tugas
              </button>
              
              <button class="w-full btn btn-secondary text-left">
                <svg class="inline h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Lihat Nilai
              </button>
              
              <button class="w-full btn btn-secondary text-left">
                <svg class="inline h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 012 0v4m0 0v4m0-4h4m-4 0H6m5 10v-1a1 1 0 10-2 0v1m2 0H9m0 0v1a1 1 0 102 0v-1m0 0h2" />
                </svg>
                Jadwal Lengkap
              </button>
              
              <router-link
                to="/profile"
                class="block w-full btn btn-outline text-left"
              >
                <svg class="inline h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                Edit Profil
              </router-link>
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

// State
const stats = ref({
  kehadiranPercent: 0,
  kehadiranTrend: 0,
  kreditPoin: 0,
  kreditTrend: 0,
  tugasPercent: 0,
  rankingKelas: 0
})

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

const jadwalToday = ref([])
const tugasList = ref([])
const prestasiList = ref([])

const statsLoading = ref(false)
const jadwalLoading = ref(false)
const tugasLoading = ref(false)

// Methods
const fetchStats = async () => {
  try {
    statsLoading.value = true
    
    // Mock data
    stats.value = {
      kehadiranPercent: 92,
      kehadiranTrend: 2,
      kreditPoin: 85,
      kreditTrend: 5,
      tugasPercent: 88,
      rankingKelas: 8
    }
    
  } catch (error) {
    console.error('Failed to fetch stats:', error)
  } finally {
    statsLoading.value = false
  }
}

const fetchJadwalToday = async () => {
  try {
    jadwalLoading.value = true
    
    // Mock data
    jadwalToday.value = [
      {
        id: 1,
        waktu: '07:00-08:30',
        mata_pelajaran: 'Matematika',
        guru: 'Pak Ahmad',
        ruang: 'X-A',
        status: 'selesai'
      },
      {
        id: 2,
        waktu: '08:30-10:00',
        mata_pelajaran: 'Bahasa Indonesia',
        guru: 'Bu Sari',
        ruang: 'X-A',
        status: 'berlangsung'
      },
      {
        id: 3,
        waktu: '10:15-11:45',
        mata_pelajaran: 'Fisika',
        guru: 'Pak Budi',
        ruang: 'Lab IPA',
        status: 'akan datang'
      },
      {
        id: 4,
        waktu: '12:30-14:00',
        mata_pelajaran: 'Sejarah',
        guru: 'Bu Maya',
        ruang: 'X-A',
        status: 'akan datang'
      }
    ]
    
  } catch (error) {
    console.error('Failed to fetch jadwal:', error)
  } finally {
    jadwalLoading.value = false
  }
}

const fetchTugasList = async () => {
  try {
    tugasLoading.value = true
    
    // Mock data
    tugasList.value = [
      {
        id: 1,
        judul: 'Latihan Soal Trigonometri',
        mata_pelajaran: 'Matematika',
        deadline: new Date('2025-09-25'),
        status: 'belum'
      },
      {
        id: 2,
        judul: 'Essay Kemerdekaan Indonesia',
        mata_pelajaran: 'Sejarah',
        deadline: new Date('2025-09-22'),
        status: 'selesai'
      },
      {
        id: 3,
        judul: 'Laporan Praktikum Fisika',
        mata_pelajaran: 'Fisika',
        deadline: new Date('2025-09-20'),
        status: 'terlambat'
      }
    ]
    
    // Mock prestasi
    prestasiList.value = [
      {
        id: 1,
        nama: 'Juara 1 Olimpiade Matematika',
        tingkat: 'Kota',
        tahun: 2024
      },
      {
        id: 2,
        nama: 'Best Student of the Month',
        tingkat: 'Sekolah',
        tahun: 2024
      }
    ]
    
  } catch (error) {
    console.error('Failed to fetch tugas:', error)
  } finally {
    tugasLoading.value = false
  }
}

const getCurrentDate = () => {
  return new Intl.DateTimeFormat('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  }).format(new Date())
}

const formatDate = (date) => {
  return new Intl.DateTimeFormat('id-ID', {
    day: 'numeric',
    month: 'short'
  }).format(new Date(date))
}

const getDimensionColor = (score) => {
  if (score >= 3.5) return 'bg-green-500'
  if (score >= 3.0) return 'bg-yellow-500'
  if (score >= 2.5) return 'bg-orange-500'
  return 'bg-red-500'
}

// Lifecycle
onMounted(() => {
  fetchStats()
  fetchJadwalToday()
  fetchTugasList()
})
</script>
