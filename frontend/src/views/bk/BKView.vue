<template>
  <div class="p-6">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Bimbingan Konseling</h1>
      <p class="text-gray-600 mt-1">Sistem manajemen bimbingan konseling dan monitoring siswa</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
      <StatCard
        title="Siswa Konseling"
        :value="stats.siswaKonseling"
        icon="users"
        color="blue"
      />
      <StatCard
        title="Kasus Aktif"
        :value="stats.kasusAktif"
        icon="exclamation-triangle"
        color="red"
      />
      <StatCard
        title="Kasus Selesai"
        :value="stats.kasusSelesai"
        icon="check-circle"
        color="green"
      />
      <StatCard
        title="Home Visit"
        :value="stats.homeVisit"
        icon="home"
        color="purple"
      />
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <button
        @click="showTambahKasus = true"
        class="bg-blue-600 text-white p-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center space-x-3"
      >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span class="font-medium">Tambah Kasus</span>
      </button>
      
      <button
        @click="showJadwalKonseling = true"
        class="bg-green-600 text-white p-4 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 flex items-center space-x-3"
      >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <span class="font-medium">Jadwal Konseling</span>
      </button>
      
      <button
        @click="showHomeVisit = true"
        class="bg-purple-600 text-white p-4 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 flex items-center space-x-3"
      >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
        </svg>
        <span class="font-medium">Home Visit</span>
      </button>
      
      <button
        @click="showLaporan = true"
        class="bg-orange-600 text-white p-4 rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 flex items-center space-x-3"
      >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
        <span class="font-medium">Laporan BK</span>
      </button>
    </div>

    <!-- Tabs -->
    <div class="mb-6">
      <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
          <button
            @click="activeTab = 'kasus'"
            :class="[
              'py-2 px-1 border-b-2 font-medium text-sm',
              activeTab === 'kasus'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            ]"
          >
            Daftar Kasus
          </button>
          <button
            @click="activeTab = 'konseling'"
            :class="[
              'py-2 px-1 border-b-2 font-medium text-sm',
              activeTab === 'konseling'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            ]"
          >
            Jadwal Konseling
          </button>
          <button
            @click="activeTab = 'monitoring'"
            :class="[
              'py-2 px-1 border-b-2 font-medium text-sm',
              activeTab === 'monitoring'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            ]"
          >
            Monitoring Siswa
          </button>
        </nav>
      </div>
    </div>

    <!-- Tab Content: Daftar Kasus -->
    <div v-if="activeTab === 'kasus'" class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Daftar Kasus Bimbingan Konseling</h3>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kasus</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prioritas</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="kasus in daftarKasus" :key="kasus.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <span class="text-sm font-medium text-blue-600">{{ kasus.siswa.nama.charAt(0) }}</span>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ kasus.siswa.nama }}</div>
                    <div class="text-sm text-gray-500">{{ kasus.siswa.kelas }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ kasus.jenisKasus }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ kasus.tanggal }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  :class="getStatusClass(kasus.status)"
                >
                  {{ kasus.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  :class="getPriorityClass(kasus.prioritas)"
                >
                  {{ kasus.prioritas }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button
                  @click="viewKasus(kasus)"
                  class="text-blue-600 hover:text-blue-900 mr-3"
                >
                  Detail
                </button>
                <button
                  @click="editKasus(kasus)"
                  class="text-green-600 hover:text-green-900"
                >
                  Edit
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Tab Content: Jadwal Konseling -->
    <div v-if="activeTab === 'konseling'" class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Jadwal Konseling Hari Ini</h3>
      </div>
      
      <div class="p-6">
        <div class="space-y-4">
          <div v-for="jadwal in jadwalKonseling" :key="jadwal.id" class="border rounded-lg p-4 hover:bg-gray-50">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-4">
                <div class="text-lg font-semibold text-blue-600">{{ jadwal.waktu }}</div>
                <div>
                  <div class="font-medium text-gray-900">{{ jadwal.siswa.nama }}</div>
                  <div class="text-sm text-gray-500">{{ jadwal.siswa.kelas }} • {{ jadwal.topik }}</div>
                </div>
              </div>
              <div class="flex space-x-2">
                <button class="bg-green-100 text-green-800 px-3 py-1 rounded-md text-sm font-medium hover:bg-green-200">
                  Selesai
                </button>
                <button class="bg-blue-100 text-blue-800 px-3 py-1 rounded-md text-sm font-medium hover:bg-blue-200">
                  Reschedule
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tab Content: Monitoring -->
    <div v-if="activeTab === 'monitoring'" class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Monitoring Siswa Bermasalah</h3>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Masalah Utama</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skor Risiko</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Konseling</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="monitoring in dataMonitoring" :key="monitoring.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <span class="text-sm font-medium text-blue-600">{{ monitoring.siswa.nama.charAt(0) }}</span>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ monitoring.siswa.nama }}</div>
                    <div class="text-sm text-gray-500">{{ monitoring.siswa.kelas }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ monitoring.masalahUtama }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  :class="getRiskClass(monitoring.skorRisiko)"
                >
                  {{ monitoring.skorRisiko }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ monitoring.terakhirKonseling }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button
                  @click="jadwalkanKonseling(monitoring.siswa)"
                  class="text-blue-600 hover:text-blue-900"
                >
                  Jadwalkan
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modals (placeholder) -->
    <div v-if="showTambahKasus" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Kasus Baru</h3>
        <p class="text-gray-600 mb-4">Fitur tambah kasus akan dikembangkan lebih lanjut.</p>
        <button
          @click="showTambahKasus = false"
          class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700"
        >
          Tutup
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import StatCard from '../../components/ui/StatCard.vue'

// State
const activeTab = ref('kasus')
const showTambahKasus = ref(false)
const showJadwalKonseling = ref(false)
const showHomeVisit = ref(false)
const showLaporan = ref(false)

// Mock data
const stats = ref({
  siswaKonseling: 45,
  kasusAktif: 12,
  kasusSelesai: 33,
  homeVisit: 8
})

const daftarKasus = ref([
  {
    id: 1,
    siswa: { nama: 'Ahmad Rizki', kelas: 'X-A' },
    jenisKasus: 'Masalah Akademik',
    tanggal: '2024-01-15',
    status: 'Aktif',
    prioritas: 'Tinggi'
  },
  {
    id: 2,
    siswa: { nama: 'Sari Dewi', kelas: 'XI IPA 1' },
    jenisKasus: 'Masalah Sosial',
    tanggal: '2024-01-10',
    status: 'Dalam Proses',
    prioritas: 'Sedang'
  }
])

const jadwalKonseling = ref([
  {
    id: 1,
    waktu: '08:00',
    siswa: { nama: 'Ahmad Rizki', kelas: 'X-A' },
    topik: 'Masalah Akademik'
  },
  {
    id: 2,
    waktu: '10:00',
    siswa: { nama: 'Budi Santoso', kelas: 'X-B' },
    topik: 'Konseling Karir'
  }
])

const dataMonitoring = ref([
  {
    id: 1,
    siswa: { nama: 'Ahmad Rizki', kelas: 'X-A' },
    masalahUtama: 'Prestasi Menurun',
    skorRisiko: 'Tinggi',
    terakhirKonseling: '2024-01-10'
  },
  {
    id: 2,
    siswa: { nama: 'Dewi Sari', kelas: 'XI IPA 2' },
    masalahUtama: 'Sering Terlambat',
    skorRisiko: 'Sedang',
    terakhirKonseling: '2024-01-08'
  }
])

// Methods
const getStatusClass = (status) => {
  switch (status) {
    case 'Aktif':
      return 'bg-red-100 text-red-800'
    case 'Dalam Proses':
      return 'bg-yellow-100 text-yellow-800'
    case 'Selesai':
      return 'bg-green-100 text-green-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const getPriorityClass = (priority) => {
  switch (priority) {
    case 'Tinggi':
      return 'bg-red-100 text-red-800'
    case 'Sedang':
      return 'bg-yellow-100 text-yellow-800'
    case 'Rendah':
      return 'bg-green-100 text-green-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const getRiskClass = (risk) => {
  switch (risk) {
    case 'Tinggi':
      return 'bg-red-100 text-red-800'
    case 'Sedang':
      return 'bg-yellow-100 text-yellow-800'
    case 'Rendah':
      return 'bg-green-100 text-green-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const viewKasus = (kasus) => {
  console.log('View kasus:', kasus)
}

const editKasus = (kasus) => {
  console.log('Edit kasus:', kasus)
}

const jadwalkanKonseling = (siswa) => {
  console.log('Jadwalkan konseling untuk:', siswa)
}

onMounted(() => {
  console.log('BKView mounted')
})
</script>
