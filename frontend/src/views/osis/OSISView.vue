<template>
  <div class="p-6">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Manajemen OSIS</h1>
      <p class="text-gray-600 mt-1">Sistem manajemen kegiatan dan organisasi OSIS</p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
      <StatCard
        title="Total Anggota"
        :value="stats.totalAnggota"
        icon="users"
        color="blue"
      />
      <StatCard
        title="Kegiatan Aktif"
        :value="stats.kegiatanAktif"
        icon="calendar-days"
        color="green"
      />
      <StatCard
        title="Proposal Pending"
        :value="stats.proposalPending"
        icon="document"
        color="yellow"
      />
      <StatCard
        title="Budget Tersedia"
        :value="stats.budgetTersedia"
        icon="currency-dollar"
        color="purple"
      />
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
      <button
        @click="showTambahKegiatan = true"
        class="bg-blue-600 text-white p-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 flex items-center space-x-3"
      >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        <span class="font-medium">Tambah Kegiatan</span>
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
        @click="showBuatProposal = true"
        class="bg-purple-600 text-white p-4 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 flex items-center space-x-3"
      >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <span class="font-medium">Buat Proposal</span>
      </button>
      
      <button
        @click="showLaporanKegiatan = true"
        class="bg-orange-600 text-white p-4 rounded-lg hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 flex items-center space-x-3"
      >
        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
        </svg>
        <span class="font-medium">Laporan</span>
      </button>
    </div>

    <!-- Tabs -->
    <div class="mb-6">
      <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
          <button
            @click="activeTab = 'kegiatan'"
            :class="[
              'py-2 px-1 border-b-2 font-medium text-sm',
              activeTab === 'kegiatan'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            ]"
          >
            Kegiatan OSIS
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
            Anggota OSIS
          </button>
          <button
            @click="activeTab = 'proposal'"
            :class="[
              'py-2 px-1 border-b-2 font-medium text-sm',
              activeTab === 'proposal'
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
            ]"
          >
            Proposal Kegiatan
          </button>
        </nav>
      </div>
    </div>

    <!-- Tab Content: Kegiatan -->
    <div v-if="activeTab === 'kegiatan'" class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Daftar Kegiatan OSIS</h3>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">PIC</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="kegiatan in daftarKegiatan" :key="kegiatan.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ kegiatan.nama }}</div>
                <div class="text-sm text-gray-500">{{ kegiatan.deskripsi }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ kegiatan.tanggal }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  :class="getStatusClass(kegiatan.status)"
                >
                  {{ kegiatan.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ kegiatan.pic }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ formatCurrency(kegiatan.budget) }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button
                  @click="viewKegiatan(kegiatan)"
                  class="text-blue-600 hover:text-blue-900 mr-3"
                >
                  Detail
                </button>
                <button
                  @click="editKegiatan(kegiatan)"
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

    <!-- Tab Content: Anggota -->
    <div v-if="activeTab === 'anggota'" class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Struktur Organisasi OSIS</h3>
      </div>
      
      <div class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div v-for="anggota in anggotaOSIS" :key="anggota.id" class="border rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="text-center">
              <div class="h-16 w-16 rounded-full bg-blue-100 flex items-center justify-center mx-auto mb-4">
                <span class="text-lg font-medium text-blue-600">{{ anggota.nama.charAt(0) }}</span>
              </div>
              <h4 class="font-medium text-gray-900">{{ anggota.nama }}</h4>
              <p class="text-sm text-gray-500 mb-2">{{ anggota.kelas }}</p>
              <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                {{ anggota.jabatan }}
              </span>
              <div class="mt-4 flex justify-center space-x-2">
                <button class="text-blue-600 hover:text-blue-900 text-sm">
                  Edit
                </button>
                <button class="text-red-600 hover:text-red-900 text-sm">
                  Hapus
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Tab Content: Proposal -->
    <div v-if="activeTab === 'proposal'" class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-medium text-gray-900">Daftar Proposal Kegiatan</h3>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Proposal</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Pengajuan</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Budget</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="proposal in daftarProposal" :key="proposal.id">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ proposal.judul }}</div>
                <div class="text-sm text-gray-500">{{ proposal.deskripsi }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ proposal.tanggalPengajuan }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ formatCurrency(proposal.budget) }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span
                  class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  :class="getProposalStatusClass(proposal.status)"
                >
                  {{ proposal.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button
                  @click="viewProposal(proposal)"
                  class="text-blue-600 hover:text-blue-900 mr-3"
                >
                  Detail
                </button>
                <button
                  @click="downloadProposal(proposal)"
                  class="text-green-600 hover:text-green-900"
                >
                  Download
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modals (placeholder) -->
    <div v-if="showTambahKegiatan" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 max-w-md w-full mx-4">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Kegiatan Baru</h3>
        <p class="text-gray-600 mb-4">Fitur tambah kegiatan akan dikembangkan lebih lanjut.</p>
        <button
          @click="showTambahKegiatan = false"
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
const activeTab = ref('kegiatan')
const showTambahKegiatan = ref(false)
const showKelolaAnggota = ref(false)
const showBuatProposal = ref(false)
const showLaporanKegiatan = ref(false)

// Reactive data
const loading = ref(false)
const stats = ref({
  totalAnggota: 0,
  kegiatanAktif: 0,
  proposalPending: 0,
  budgetTersedia: '0'
})

const daftarKegiatan = ref([
  {
    id: 1,
    nama: 'Penerimaan Siswa Baru',
    deskripsi: 'Kegiatan MPLS untuk siswa baru',
    tanggal: '2024-07-15',
    status: 'Selesai',
    pic: 'Ahmad Rizki',
    budget: 5000000
  },
  {
    id: 2,
    nama: 'HUT RI ke-79',
    deskripsi: 'Perayaan kemerdekaan Indonesia',
    tanggal: '2024-08-17',
    status: 'Berlangsung',
    pic: 'Sari Dewi',
    budget: 3000000
  }
])

const anggotaOSIS = ref([
  {
    id: 1,
    nama: 'Ahmad Rizki',
    kelas: 'XI IPA 1',
    jabatan: 'Ketua OSIS'
  },
  {
    id: 2,
    nama: 'Sari Dewi',
    kelas: 'XI IPA 2',
    jabatan: 'Wakil Ketua'
  },
  {
    id: 3,
    nama: 'Budi Santoso',
    kelas: 'X-A',
    jabatan: 'Sekretaris'
  }
])

const daftarProposal = ref([
  {
    id: 1,
    judul: 'Kegiatan Bakti Sosial',
    deskripsi: 'Baksos untuk masyarakat sekitar',
    tanggalPengajuan: '2024-01-10',
    budget: 2000000,
    status: 'Disetujui'
  },
  {
    id: 2,
    judul: 'Festival Seni Budaya',
    deskripsi: 'Pentas seni siswa-siswi',
    tanggalPengajuan: '2024-01-15',
    budget: 4000000,
    status: 'Pending'
  }
])

// Methods
const getStatusClass = (status) => {
  switch (status) {
    case 'Berlangsung':
      return 'bg-blue-100 text-blue-800'
    case 'Selesai':
      return 'bg-green-100 text-green-800'
    case 'Ditunda':
      return 'bg-yellow-100 text-yellow-800'
    case 'Dibatalkan':
      return 'bg-red-100 text-red-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const getProposalStatusClass = (status) => {
  switch (status) {
    case 'Disetujui':
      return 'bg-green-100 text-green-800'
    case 'Pending':
      return 'bg-yellow-100 text-yellow-800'
    case 'Ditolak':
      return 'bg-red-100 text-red-800'
    default:
      return 'bg-gray-100 text-gray-800'
  }
}

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(amount)
}

const viewKegiatan = (kegiatan) => {
  console.log('View kegiatan:', kegiatan)
}

const editKegiatan = (kegiatan) => {
  console.log('Edit kegiatan:', kegiatan)
}

const viewProposal = (proposal) => {
  console.log('View proposal:', proposal)
}

const downloadProposal = (proposal) => {
  console.log('Download proposal:', proposal)
}

// Load data from API
const loadData = async () => {
  try {
    loading.value = true
    
    // Load statistics
    const statsResponse = await api.get('/osis-statistics')
    stats.value = statsResponse.data.data || statsResponse.data
    
    // Load OSIS activities
    const kegiatanResponse = await api.get('/osis-kegiatan')
    daftarKegiatan.value = kegiatanResponse.data.data || kegiatanResponse.data
    
    // Load OSIS members
    const anggotaResponse = await api.get('/osis-anggota')
    anggotaOSIS.value = anggotaResponse.data.data || anggotaResponse.data
    
    // Fallback to mock data if API fails
    if (!stats.value || Object.keys(stats.value).length === 0) {
      stats.value = {
        totalAnggota: 25,
        kegiatanAktif: 8,
        proposalPending: 3,
        budgetTersedia: '15M'
      }
    }
    
    if (!daftarKegiatan.value || daftarKegiatan.value.length === 0) {
      daftarKegiatan.value = [
        {
          id: 1,
          nama: 'Penerimaan Siswa Baru',
          deskripsi: 'Kegiatan MPLS untuk siswa baru',
          tanggal: '2024-07-15',
          status: 'Selesai',
          pic: 'Ahmad Rizki',
          budget: 5000000
        },
        {
          id: 2,
          nama: 'HUT RI ke-79',
          deskripsi: 'Perayaan kemerdekaan Indonesia',
          tanggal: '2024-08-17',
          status: 'Berlangsung',
          pic: 'Sari Dewi',
          budget: 3000000
        }
      ]
    }
    
    if (!anggotaOSIS.value || anggotaOSIS.value.length === 0) {
      anggotaOSIS.value = [
        {
          id: 1,
          nama: 'Ahmad Rizki',
          kelas: 'XI IPA 1',
          jabatan: 'Ketua OSIS'
        },
        {
          id: 2,
          nama: 'Sari Dewi',
          kelas: 'XI IPA 2',
          jabatan: 'Wakil Ketua'
        },
        {
          id: 3,
          nama: 'Budi Santoso',
          kelas: 'X-A',
          jabatan: 'Sekretaris'
        }
      ]
    }
    
  } catch (error) {
    console.error('Error loading OSIS data:', error)
    // Use mock data as fallback
    stats.value = {
      totalAnggota: 25,
      kegiatanAktif: 8,
      proposalPending: 3,
      budgetTersedia: '15M'
    }
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadData()
})
</script>
