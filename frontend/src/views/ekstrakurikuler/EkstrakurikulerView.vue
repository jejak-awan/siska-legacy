<template>
  <div class="p-6">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Manajemen Ekstrakurikuler</h1>
      <p class="text-gray-600 mt-1">Kelola kegiatan ekstrakurikuler dan prestasi siswa</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
      <StatCard
        title="Total Ekstrakurikuler"
        :value="stats.totalEkskul"
        icon="star"
        color="blue"
      />
      <StatCard
        title="Total Anggota"
        :value="stats.totalAnggota"
        icon="users"
        color="green"
      />
      <StatCard
        title="Prestasi Bulan Ini"
        :value="stats.prestasiBulanIni"
        icon="trophy"
        color="yellow"
      />
      <StatCard
        title="Kegiatan Aktif"
        :value="stats.kegiatanAktif"
        icon="calendar-days"
        color="purple"
      />
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <button
        @click="showTambahEkskul = true"
        class="bg-blue-600 text-white p-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center space-x-3"
      >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span class="font-medium">Tambah Ekskul</span>
      </button>
      
      <button
        @click="showKelolaAnggota = true"
        class="bg-green-600 text-white p-4 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 flex items-center space-x-3"
      >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
        </svg>
        <span class="font-medium">Kelola Anggota</span>
      </button>
      
      <button
        @click="showJadwalLatihan = true"
        class="bg-purple-600 text-white p-4 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 flex items-center space-x-3"
      >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <span class="font-medium">Jadwal Latihan</span>
      </button>
      
      <button
        @click="showInputPrestasi = true"
        class="bg-orange-600 text-white p-4 rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 flex items-center space-x-3"
      >
        <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 20 20">
          <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
        </svg>
        <span class="font-medium">Input Prestasi</span>
      </button>
    </div>

    <!-- Tabs -->
    <div class="mb-6">
      <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
          <button
            @click="activeTab = 'ekskul'"
            :class="[
              'py-2 px-1 border-b-2 font-medium text-sm',
              activeTab === 'ekskul'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            ]"
          >
            Daftar Ekstrakurikuler
          </button>
          <button
            @click="activeTab = 'anggota'"
            :class="[
              'py-2 px-1 border-b-2 font-medium text-sm',
              activeTab === 'anggota'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            ]"
          >
            Anggota Aktif
          </button>
          <button
            @click="activeTab = 'prestasi'"
            :class="[
              'py-2 px-1 border-b-2 font-medium text-sm',
              activeTab === 'prestasi'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            ]"
          >
            Prestasi & Penghargaan
          </button>
          <button
            @click="activeTab = 'jadwal'"
            :class="[
              'py-2 px-1 border-b-2 font-medium text-sm',
              activeTab === 'jadwal'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            ]"
          >
            Jadwal Kegiatan
          </button>
        </nav>
      </div>
    </div>

    <!-- Tab Content: Ekstrakurikuler -->
    <div v-if="activeTab === 'ekskul'" class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Daftar Ekstrakurikuler</h3>
      </div>
      
      <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="ekskul in daftarEkskul" :key="ekskul.id" class="border rounded-lg p-6 hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-4">
              <div class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center">
                <svg class="h-6 w-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
              </div>
              <span
                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                :class="getStatusClass(ekskul.status)"
              >
                {{ ekskul.status }}
              </span>
            </div>
            
            <h4 class="font-semibold text-gray-900 mb-2">{{ ekskul.nama }}</h4>
            <p class="text-sm text-gray-600 mb-4">{{ ekskul.deskripsi }}</p>
            
            <div class="space-y-2 text-sm text-gray-500">
              <div class="flex items-center">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>{{ ekskul.pembina }}</span>
              </div>
              <div class="flex items-center">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span>{{ ekskul.jumlahAnggota }} anggota</span>
              </div>
              <div class="flex items-center">
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <span>{{ ekskul.jadwal }}</span>
              </div>
            </div>
            
            <div class="mt-4 flex space-x-2">
              <button
                @click="viewEkskul(ekskul)"
                class="flex-1 bg-blue-600 text-white py-2 px-3 rounded-md text-sm font-medium hover:bg-blue-700"
              >
                Detail
              </button>
              <button
                @click="editEkskul(ekskul)"
                class="flex-1 bg-gray-100 text-gray-700 py-2 px-3 rounded-md text-sm font-medium hover:bg-gray-200"
              >
                Edit
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tab Content: Anggota -->
    <div v-if="activeTab === 'anggota'" class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Anggota Ekstrakurikuler Aktif</h3>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ekstrakurikuler</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posisi</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bergabung</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="anggota in anggotaEkskul" :key="anggota.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                    <span class="text-sm font-medium text-blue-600">{{ anggota.siswa.nama.charAt(0) }}</span>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ anggota.siswa.nama }}</div>
                    <div class="text-sm text-gray-500">{{ anggota.siswa.kelas }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ anggota.ekstrakurikuler }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ anggota.posisi }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ anggota.tanggalBergabung }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  :class="getAnggotaStatusClass(anggota.status)"
                >
                  {{ anggota.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button
                  @click="viewAnggota(anggota)"
                  class="text-blue-600 hover:text-blue-900 mr-3"
                >
                  Detail
                </button>
                <button
                  @click="editAnggota(anggota)"
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

    <!-- Tab Content: Prestasi -->
    <div v-if="activeTab === 'prestasi'" class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Prestasi & Penghargaan</h3>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prestasi</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Siswa</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ekstrakurikuler</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="prestasi in daftarPrestasi" :key="prestasi.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ prestasi.nama }}</div>
                <div class="text-sm text-gray-500">{{ prestasi.penyelenggara }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ prestasi.siswa.nama }}</div>
                <div class="text-sm text-gray-500">{{ prestasi.siswa.kelas }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ prestasi.ekstrakurikuler }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  :class="getTingkatClass(prestasi.tingkat)"
                >
                  {{ prestasi.tingkat }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ prestasi.tanggal }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button
                  @click="viewPrestasi(prestasi)"
                  class="text-blue-600 hover:text-blue-900 mr-3"
                >
                  Detail
                </button>
                <button
                  @click="editPrestasi(prestasi)"
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

    <!-- Tab Content: Jadwal -->
    <div v-if="activeTab === 'jadwal'" class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Jadwal Kegiatan Ekstrakurikuler</h3>
      </div>
      
      <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
          <div v-for="jadwal in jadwalKegiatan" :key="jadwal.id" class="border rounded-lg p-4 hover:bg-gray-50">
            <div class="flex items-center justify-between mb-3">
              <span class="text-lg font-semibold text-blue-600">{{ jadwal.hari }}</span>
              <span class="text-sm text-gray-500">{{ jadwal.waktu }}</span>
            </div>
            <h4 class="font-medium text-gray-900 mb-2">{{ jadwal.ekstrakurikuler }}</h4>
            <p class="text-sm text-gray-600 mb-2">{{ jadwal.kegiatan }}</p>
            <div class="flex items-center text-sm text-gray-500">
              <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              <span>{{ jadwal.tempat }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modals (placeholder) -->
    <div v-if="showTambahEkskul" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Ekstrakurikuler Baru</h3>
        <p class="text-gray-600 mb-4">Fitur tambah ekstrakurikuler akan dikembangkan lebih lanjut.</p>
        <button
          @click="showTambahEkskul = false"
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
import api from '@/services/api'

// State
const activeTab = ref('ekskul')
const showTambahEkskul = ref(false)
const showKelolaAnggota = ref(false)
const showJadwalLatihan = ref(false)
const showInputPrestasi = ref(false)

// Reactive data
const loading = ref(false)
const stats = ref({
  totalEkskul: 0,
  totalAnggota: 0,
  prestasiBulanIni: 0,
  kegiatanAktif: 0
})

const daftarEkskul = ref([
  {
    id: 1,
    nama: 'Pramuka',
    deskripsi: 'Kegiatan kepanduan untuk membangun karakter',
    pembina: 'Pak Ahmad',
    jumlahAnggota: 45,
    jadwal: 'Sabtu 14:00-16:00',
    status: 'Aktif'
  },
  {
    id: 2,
    nama: 'Basket',
    deskripsi: 'Olahraga basket untuk prestasi sekolah',
    pembina: 'Bu Sari',
    jumlahAnggota: 20,
    jadwal: 'Selasa & Jumat 15:30-17:00',
    status: 'Aktif'
  }
])

const anggotaEkskul = ref([
  {
    id: 1,
    siswa: { nama: 'Ahmad Rizki', kelas: 'XI IPA 1' },
    ekstrakurikuler: 'Pramuka',
    posisi: 'Ketua Regu',
    tanggalBergabung: '2023-08-01',
    status: 'Aktif'
  },
  {
    id: 2,
    siswa: { nama: 'Sari Dewi', kelas: 'X-A' },
    ekstrakurikuler: 'Basket',
    posisi: 'Kapten Tim',
    tanggalBergabung: '2023-09-15',
    status: 'Aktif'
  }
])

const daftarPrestasi = ref([
  {
    id: 1,
    nama: 'Juara 1 Lomba Basket Antar SMA',
    penyelenggara: 'Dinas Pendidikan Kota',
    siswa: { nama: 'Tim Basket Putra', kelas: 'XI' },
    ekstrakurikuler: 'Basket',
    tingkat: 'Kota',
    tanggal: '2024-01-15'
  }
])

const jadwalKegiatan = ref([
  {
    id: 1,
    hari: 'Senin',
    waktu: '15:30-17:00',
    ekstrakurikuler: 'Karate',
    kegiatan: 'Latihan Rutin',
    tempat: 'Aula Sekolah'
  },
  {
    id: 2,
    hari: 'Selasa',
    waktu: '15:30-17:00',
    ekstrakurikuler: 'Basket',
    kegiatan: 'Latihan Rutin',
    tempat: 'Lapangan Basket'
  }
])

// Methods
const getStatusClass = (status) => {
  switch (status) {
    case 'Aktif':
      return 'bg-green-100 text-green-800'
    case 'Tidak Aktif':
      return 'bg-red-100 text-red-800'
    case 'Libur':
      return 'bg-yellow-100 text-yellow-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const getAnggotaStatusClass = (status) => {
  switch (status) {
    case 'Aktif':
      return 'bg-green-100 text-green-800'
    case 'Cuti':
      return 'bg-yellow-100 text-yellow-800'
    case 'Alumni':
      return 'bg-blue-100 text-blue-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const getTingkatClass = (tingkat) => {
  switch (tingkat) {
    case 'Internasional':
      return 'bg-purple-100 text-purple-800'
    case 'Nasional':
      return 'bg-red-100 text-red-800'
    case 'Provinsi':
      return 'bg-blue-100 text-blue-800'
    case 'Kota':
      return 'bg-green-100 text-green-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const viewEkskul = (ekskul) => {
  console.log('View ekstrakurikuler:', ekskul)
}

const editEkskul = (ekskul) => {
  console.log('Edit ekstrakurikuler:', ekskul)
}

const viewAnggota = (anggota) => {
  console.log('View anggota:', anggota)
}

const editAnggota = (anggota) => {
  console.log('Edit anggota:', anggota)
}

const viewPrestasi = (prestasi) => {
  console.log('View prestasi:', prestasi)
}

const editPrestasi = (prestasi) => {
  console.log('Edit prestasi:', prestasi)
}

// Load data from API
const loadData = async () => {
  try {
    loading.value = true
    
    // Load statistics
    const statsResponse = await api.get('/ekstrakurikuler-statistics')
    stats.value = statsResponse.data.data || statsResponse.data
    
    // Load ekstrakurikuler list
    const ekskulResponse = await api.get('/ekstrakurikuler')
    daftarEkskul.value = ekskulResponse.data.data || ekskulResponse.data
    
    // Load anggota data
    const anggotaResponse = await api.get('/ekstrakurikuler-siswa')
    anggotaEkskul.value = anggotaResponse.data.data || anggotaResponse.data
    
    // Fallback to mock data if API fails
    if (!stats.value || Object.keys(stats.value).length === 0) {
      stats.value = {
        totalEkskul: 15,
        totalAnggota: 245,
        prestasiBulanIni: 8,
        kegiatanAktif: 12
      }
    }
    
    if (!daftarEkskul.value || daftarEkskul.value.length === 0) {
      daftarEkskul.value = [
        {
          id: 1,
          nama: 'Pramuka',
          deskripsi: 'Kegiatan kepanduan untuk membangun karakter',
          pembina: 'Pak Ahmad',
          jumlahAnggota: 45,
          jadwal: 'Sabtu 14:00-16:00',
          status: 'Aktif'
        },
        {
          id: 2,
          nama: 'Basket',
          deskripsi: 'Olahraga basket untuk prestasi sekolah',
          pembina: 'Bu Sari',
          jumlahAnggota: 20,
          jadwal: 'Selasa & Jumat 15:30-17:00',
          status: 'Aktif'
        }
      ]
    }
    
    if (!anggotaEkskul.value || anggotaEkskul.value.length === 0) {
      anggotaEkskul.value = [
        {
          id: 1,
          siswa: { nama: 'Ahmad Rizki', kelas: 'XI IPA 1' },
          ekstrakurikuler: 'Pramuka',
          posisi: 'Ketua Regu',
          tanggalBergabung: '2023-08-01',
          status: 'Aktif'
        },
        {
          id: 2,
          siswa: { nama: 'Sari Dewi', kelas: 'X-A' },
          ekstrakurikuler: 'Basket',
          posisi: 'Kapten Tim',
          tanggalBergabung: '2023-09-15',
          status: 'Aktif'
        }
      ]
    }
    
  } catch (error) {
    console.error('Error loading ekstrakurikuler data:', error)
    // Use mock data as fallback
    stats.value = {
      totalEkskul: 15,
      totalAnggota: 245,
      prestasiBulanIni: 8,
      kegiatanAktif: 12
    }
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadData()
})
</script>
