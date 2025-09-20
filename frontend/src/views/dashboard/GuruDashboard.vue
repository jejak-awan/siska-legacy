<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Page Header -->
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Guru</h1>
        <p class="mt-1 text-sm text-gray-500">
          Selamat datang, {{ authStore.userProfile?.nama_lengkap || authStore.user?.username }}. 
          Kelola pembelajaran dan data siswa Anda.
        </p>
      </div>

      <!-- Stats Overview -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <StatCard
          title="Kelas Diajar"
          :value="stats.kelasCount"
          :loading="statsLoading"
          icon="academic-cap"
          color="blue"
          subtitle="Kelas yang Anda ajar"
        />
        <StatCard
          title="Total Siswa"
          :value="stats.siswaCount"
          :loading="statsLoading"
          icon="user-group"
          color="green"
          subtitle="Siswa di semua kelas"
        />
        <StatCard
          title="Presensi Hari Ini"
          :value="stats.presensiToday"
          :loading="statsLoading"
          icon="check-circle"
          color="emerald"
          subtitle="Siswa hadir hari ini"
        />
        <StatCard
          title="Tugas Pending"
          :value="stats.tugasPending"
          :loading="statsLoading"
          icon="clock"
          color="yellow"
          subtitle="Tugas belum dinilai"
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
              <h3 class="text-lg font-semibold text-gray-900">Penilaian Karakter Dinamis</h3>
              <p class="text-sm text-gray-500">Kelola penilaian karakter siswa</p>
            </div>
          </div>
          <router-link to="/dashboard/kredit-poin" class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
            Kelola Penilaian
          </router-link>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div class="bg-purple-50 rounded-lg p-4">
            <div class="flex items-center justify-between mb-2">
              <h4 class="text-sm font-medium text-purple-900">Penilaian Hari Ini</h4>
              <span class="text-lg font-bold text-purple-600">{{ characterStats.todayAssessments || 0 }}</span>
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
              <h4 class="text-sm font-medium text-yellow-900">Perlu Perhatian</h4>
              <span class="text-lg font-bold text-yellow-600">{{ characterStats.needsAttention || 0 }}</span>
            </div>
            <p class="text-xs text-yellow-700">Siswa yang perlu bimbingan</p>
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

      <!-- Main Content Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kelas Yang Diajar -->
        <div class="lg:col-span-2">
          <div class="card p-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-medium text-gray-900">Kelas Yang Diajar</h3>
              <button class="btn btn-sm btn-outline">
                Lihat Semua
              </button>
            </div>
            
            <div v-if="kelasLoading" class="space-y-4">
              <div v-for="i in 3" :key="i" class="animate-pulse">
                <div class="flex items-center space-x-4 p-4 border rounded-lg">
                  <div class="h-12 w-12 bg-gray-200 rounded-lg"></div>
                  <div class="flex-1 space-y-2">
                    <div class="h-4 bg-gray-200 rounded w-1/3"></div>
                    <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                  </div>
                </div>
              </div>
            </div>
            
            <div v-else-if="kelasList.length === 0" class="text-center py-8">
              <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
              <p class="mt-2 text-sm text-gray-500">Belum ada kelas yang diajar</p>
            </div>
            
            <div v-else class="space-y-4">
              <div 
                v-for="kelas in kelasList" 
                :key="kelas.id"
                class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors"
              >
                <div class="flex items-center space-x-4">
                  <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                  </div>
                  <div>
                    <h4 class="font-medium text-gray-900">{{ kelas.nama }}</h4>
                    <p class="text-sm text-gray-500">{{ kelas.jumlah_siswa }} siswa</p>
                  </div>
                </div>
                <div class="flex space-x-2">
                  <button class="btn btn-sm btn-outline">Presensi</button>
                  <button class="btn btn-sm btn-primary">Lihat Detail</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Quick Actions & Info -->
        <div class="space-y-6">
          <!-- Quick Actions -->
          <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi Cepat</h3>
            <div class="space-y-3">
              <button class="w-full btn btn-primary text-left">
                <svg class="inline h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Input Presensi
              </button>
              
              <button class="w-full btn btn-secondary text-left">
                <svg class="inline h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Input Kredit Poin
              </button>
              
              <button class="w-full btn btn-secondary text-left">
                <svg class="inline h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                </svg>
                Lihat Laporan
              </button>
              
              <router-link
                to="/siswa"
                class="block w-full btn btn-outline text-left"
              >
                <svg class="inline h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                Kelola Siswa
              </router-link>
            </div>
          </div>

          <!-- Jadwal Hari Ini -->
          <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Jadwal Hari Ini</h3>
            <div v-if="jadwalLoading" class="space-y-3">
              <div v-for="i in 3" :key="i" class="animate-pulse">
                <div class="flex space-x-3">
                  <div class="h-4 w-16 bg-gray-200 rounded"></div>
                  <div class="h-4 bg-gray-200 rounded flex-1"></div>
                </div>
              </div>
            </div>
            <div v-else-if="jadwalToday.length === 0" class="text-center py-4">
              <p class="text-sm text-gray-500">Tidak ada jadwal hari ini</p>
            </div>
            <div v-else class="space-y-3">
              <div 
                v-for="jadwal in jadwalToday" 
                :key="jadwal.id"
                class="flex items-center justify-between text-sm"
              >
                <div>
                  <span class="font-medium text-gray-900">{{ jadwal.waktu }}</span>
                  <span class="text-gray-500 ml-2">{{ jadwal.kelas }}</span>
                </div>
                <span 
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="jadwal.status === 'selesai' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800'"
                >
                  {{ jadwal.status }}
                </span>
              </div>
            </div>
          </div>

          <!-- Informasi Profil -->
          <div class="card p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Profil</h3>
            <div class="space-y-3 text-sm">
              <div class="flex justify-between">
                <span class="text-gray-600">NIP</span>
                <span class="font-medium">{{ authStore.userProfile?.nip || '-' }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Mata Pelajaran</span>
                <span class="font-medium">{{ authStore.userProfile?.mata_pelajaran || '-' }}</span>
              </div>
              <div class="flex justify-between">
                <span class="text-gray-600">Status</span>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                  Aktif
                </span>
              </div>
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
  kelasCount: 0,
  siswaCount: 0,
  presensiToday: 0,
  tugasPending: 0
})

const characterStats = ref({
  todayAssessments: 0,
  topPerformers: 0,
  needsAttention: 0
})

const characterDimensions = ref([
  { id: 1, nama: 'Spiritual & Religius', average: 3.8 },
  { id: 2, nama: 'Sosial & Kebangsaan', average: 3.6 },
  { id: 3, nama: 'Gotong Royong', average: 3.9 },
  { id: 4, nama: 'Mandiri', average: 3.4 },
  { id: 5, nama: 'Bernalar Kritis', average: 3.7 },
  { id: 6, nama: 'Kreatif', average: 3.5 }
])

const kelasList = ref([])
const jadwalToday = ref([])

const statsLoading = ref(false)
const kelasLoading = ref(false)
const jadwalLoading = ref(false)

// Methods
const fetchStats = async () => {
  try {
    statsLoading.value = true
    
    // Mock data - replace with actual API calls
    stats.value = {
      kelasCount: 3,
      siswaCount: 89,
      presensiToday: 82,
      tugasPending: 12
    }
    
  } catch (error) {
    console.error('Failed to fetch stats:', error)
  } finally {
    statsLoading.value = false
  }
}

const fetchKelasList = async () => {
  try {
    kelasLoading.value = true
    
    // Mock data
    kelasList.value = [
      {
        id: 1,
        nama: 'X-A',
        jumlah_siswa: 32,
        mata_pelajaran: 'Matematika'
      },
      {
        id: 2,
        nama: 'X-B',
        jumlah_siswa: 30,
        mata_pelajaran: 'Matematika'
      },
      {
        id: 3,
        nama: 'XI IPA 1',
        jumlah_siswa: 27,
        mata_pelajaran: 'Matematika'
      }
    ]
    
  } catch (error) {
    console.error('Failed to fetch kelas:', error)
  } finally {
    kelasLoading.value = false
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
        kelas: 'X-A',
        status: 'selesai'
      },
      {
        id: 2,
        waktu: '08:30-10:00',
        kelas: 'X-B',
        status: 'berlangsung'
      },
      {
        id: 3,
        waktu: '10:15-11:45',
        kelas: 'XI IPA 1',
        status: 'akan datang'
      }
    ]
    
  } catch (error) {
    console.error('Failed to fetch jadwal:', error)
  } finally {
    jadwalLoading.value = false
  }
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
  fetchKelasList()
  fetchJadwalToday()
})
</script>
