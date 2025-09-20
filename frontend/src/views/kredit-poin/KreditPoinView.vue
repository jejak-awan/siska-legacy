<template>
  <div class="kredit-poin-view">
    <!-- Header Section -->
    <div class="mb-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Sistem Poin & Penilaian Karakter</h1>
          <p class="text-gray-600 mt-1">Kelola sistem poin dan penilaian karakter dinamis siswa</p>
        </div>
        <div class="flex space-x-3">
          <button
            @click="showCreateModal = true"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Tambah Poin</span>
          </button>
          <button
            @click="showCharacterAssessment = true"
            class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>Penilaian Karakter</span>
          </button>
          <button
            @click="exportData"
            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span>Export</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
      <StatCard
        title="Total Kredit Poin"
        :value="statistics.total_kredit_poin || 0"
        icon="star"
        color="blue"
      />
      <StatCard
        title="Menunggu Persetujuan"
        :value="statistics.pending || 0"
        icon="clock"
        color="yellow"
      />
      <StatCard
        title="Poin Positif"
        :value="statistics.total_poin_positif || 0"
        icon="check-circle"
        color="green"
      />
      <StatCard
        title="Poin Negatif"
        :value="statistics.total_poin_negatif || 0"
        icon="x-circle"
        color="red"
      />
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- Search -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
          <input
            v-model="filters.search"
            type="text"
            placeholder="Cari nama siswa..."
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            @input="debouncedSearch"
          />
        </div>

        <!-- Siswa Filter -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Siswa</label>
          <select
            v-model="filters.siswa_id"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            @change="loadData"
          >
            <option value="">Semua Siswa</option>
            <option v-for="siswa in siswaList" :key="siswa.id" :value="siswa.id">
              {{ siswa.nama_lengkap }} ({{ siswa.nis }})
            </option>
          </select>
        </div>

        <!-- Status Filter -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
          <select
            v-model="filters.status"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            @change="loadData"
          >
            <option value="">Semua Status</option>
            <option value="pending">Menunggu Persetujuan</option>
            <option value="approved">Disetujui</option>
            <option value="rejected">Ditolak</option>
          </select>
        </div>

        <!-- Jenis Filter -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Jenis</label>
          <select
            v-model="filters.jenis"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            @change="loadData"
          >
            <option value="">Semua Jenis</option>
            <option value="positif">Poin Positif</option>
            <option value="negatif">Poin Negatif</option>
          </select>
        </div>

        <!-- Date Range -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Mulai</label>
          <input
            v-model="filters.start_date"
            type="date"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            @change="loadData"
          />
        </div>
      </div>

      <div class="flex justify-end mt-4">
        <button
          @click="resetFilters"
          class="px-4 py-2 text-gray-600 hover:text-gray-800 transition-colors"
        >
          Reset Filter
        </button>
      </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="p-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Data Kredit Poin</h3>
          <div class="flex items-center space-x-2">
            <span class="text-sm text-gray-500">Total: {{ pagination.total }}</span>
            <select
              v-model="pagination.per_page"
              @change="loadData"
              class="px-3 py-1 border border-gray-300 rounded text-sm"
            >
              <option value="10">10 per halaman</option>
              <option value="25">25 per halaman</option>
              <option value="50">50 per halaman</option>
            </select>
          </div>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="flex justify-center py-8">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
        </div>

        <!-- Table -->
        <div v-else class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Siswa
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Kategori
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Nilai
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Deskripsi
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Tanggal
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Pelapor
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Aksi
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="kreditPoin in kreditPoinData" :key="kreditPoin.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-sm font-medium text-gray-700">
                          {{ kreditPoin.siswa?.nama_lengkap?.charAt(0).toUpperCase() }}
                        </span>
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">
                        {{ kreditPoin.siswa?.nama_lengkap }}
                      </div>
                      <div class="text-sm text-gray-500">
                        NIS: {{ kreditPoin.siswa?.nis }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ kreditPoin.kategori?.nama }}</div>
                  <div class="text-sm text-gray-500">
                    <span :class="getJenisBadgeClass(kreditPoin.kategori?.jenis)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                      {{ getJenisLabel(kreditPoin.kategori?.jenis) }}
                    </span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getNilaiClass(kreditPoin.kategori?.jenis)" class="text-sm font-medium">
                    {{ getNilaiFormatted(kreditPoin.nilai, kreditPoin.kategori?.jenis) }}
                  </span>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 max-w-xs truncate">
                    {{ kreditPoin.deskripsi }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatDate(kreditPoin.tanggal) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ kreditPoin.pelapor?.username }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="getStatusBadgeClass(kreditPoin.status)"
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  >
                    {{ getStatusLabel(kreditPoin.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex space-x-2">
                    <button
                      @click="viewKreditPoin(kreditPoin)"
                      class="text-blue-600 hover:text-blue-900 transition-colors"
                    >
                      Lihat
                    </button>
                    <button
                      v-if="kreditPoin.status === 'pending' && canApprove"
                      @click="approveKreditPoin(kreditPoin)"
                      class="text-green-600 hover:text-green-900 transition-colors"
                    >
                      Setujui
                    </button>
                    <button
                      v-if="kreditPoin.status === 'pending' && canApprove"
                      @click="rejectKreditPoin(kreditPoin)"
                      class="text-red-600 hover:text-red-900 transition-colors"
                    >
                      Tolak
                    </button>
                    <button
                      v-if="!canApprove"
                      @click="editKreditPoin(kreditPoin)"
                      class="text-green-600 hover:text-green-900 transition-colors"
                    >
                      Edit
                    </button>
                    <button
                      @click="deleteKreditPoin(kreditPoin.id)"
                      class="text-red-600 hover:text-red-900 transition-colors"
                    >
                      Hapus
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Empty State -->
          <div v-if="kreditPoinData.length === 0" class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data kredit poin</h3>
            <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan data kredit poin baru.</p>
          </div>
        </div>

        <!-- Pagination -->
        <div v-if="pagination.last_page > 1" class="mt-6 flex items-center justify-between">
          <div class="text-sm text-gray-700">
            Menampilkan {{ pagination.from }} sampai {{ pagination.to }} dari {{ pagination.total }} data
          </div>
          <div class="flex space-x-2">
            <button
              @click="loadData(pagination.current_page - 1)"
              :disabled="pagination.current_page === 1"
              class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
            >
              Sebelumnya
            </button>
            <button
              v-for="page in visiblePages"
              :key="page"
              @click="loadData(page)"
              :class="[
                'px-3 py-1 border rounded text-sm',
                page === pagination.current_page
                  ? 'bg-blue-600 text-white border-blue-600'
                  : 'border-gray-300 hover:bg-gray-50'
              ]"
            >
              {{ page }}
            </button>
            <button
              @click="loadData(pagination.current_page + 1)"
              :disabled="pagination.current_page === pagination.last_page"
              class="px-3 py-1 border border-gray-300 rounded text-sm disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50"
            >
              Selanjutnya
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <KreditPoinFormModal
      v-if="showCreateModal || showEditModal"
      :show="showCreateModal || showEditModal"
      :kredit-poin="selectedKreditPoin"
      @close="closeModal"
      @saved="handleKreditPoinSaved"
    />

    <!-- Approval Modal -->
    <ApprovalModal
      v-if="showApprovalModal"
      :show="showApprovalModal"
      :kredit-poin="selectedKreditPoin"
      :action="approvalAction"
      @close="closeApprovalModal"
      @approved="handleApproval"
    />

    <!-- Character Assessment Modal -->
    <div v-if="showCharacterAssessment" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-full max-w-6xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Penilaian Karakter Dinamis</h3>
            <button
              @click="closeCharacterAssessment"
              class="text-gray-400 hover:text-gray-600"
            >
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
          
          <CharacterAssessment
            v-if="selectedStudent"
            :student="selectedStudent"
            :period="'bulanan'"
            @saved="handleCharacterAssessmentSaved"
            @draft-saved="handleCharacterAssessmentDraftSaved"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import StatCard from '@/components/ui/StatCard.vue'
import KreditPoinFormModal from '@/components/modals/KreditPoinFormModal.vue'
import ApprovalModal from '@/components/modals/ApprovalModal.vue'
import CharacterAssessment from '@/components/character/CharacterAssessment.vue'
import api from '@/services/api'

const router = useRouter()
const authStore = useAuthStore()

// Debounce function
const debounce = (func, wait) => {
  let timeout
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout)
      func(...args)
    }
    clearTimeout(timeout)
    timeout = setTimeout(later, wait)
  }
}

// Reactive data
const loading = ref(false)
const kreditPoinData = ref([])
const siswaList = ref([])
const statistics = ref({})
const showCreateModal = ref(false)
const showEditModal = ref(false)
const showApprovalModal = ref(false)
const showCharacterAssessment = ref(false)
const selectedKreditPoin = ref(null)
const selectedStudent = ref(null)
const approvalAction = ref('')

// Filters
const filters = reactive({
  search: '',
  siswa_id: '',
  status: '',
  jenis: '',
  start_date: '',
  end_date: ''
})

// Pagination
const pagination = reactive({
  current_page: 1,
  last_page: 1,
  per_page: 15,
  total: 0,
  from: 0,
  to: 0
})

// Computed
const canApprove = computed(() => {
  return authStore.user?.role_type === 'admin'
})

const visiblePages = computed(() => {
  const pages = []
  const current = pagination.current_page
  const last = pagination.last_page
  
  if (last <= 7) {
    for (let i = 1; i <= last; i++) {
      pages.push(i)
    }
  } else {
    if (current <= 4) {
      for (let i = 1; i <= 5; i++) {
        pages.push(i)
      }
      pages.push('...')
      pages.push(last)
    } else if (current >= last - 3) {
      pages.push(1)
      pages.push('...')
      for (let i = last - 4; i <= last; i++) {
        pages.push(i)
      }
    } else {
      pages.push(1)
      pages.push('...')
      for (let i = current - 1; i <= current + 1; i++) {
        pages.push(i)
      }
      pages.push('...')
      pages.push(last)
    }
  }
  
  return pages
})

// Methods
const loadData = async (page = 1) => {
  loading.value = true
  try {
    const params = {
      page,
      per_page: pagination.per_page,
      ...filters
    }
    
    const response = await api.get('/kredit-poin', { params })
    kreditPoinData.value = response.data.data.data
    Object.assign(pagination, response.data.data)
  } catch (error) {
    console.error('Error loading kredit poin data:', error)
  } finally {
    loading.value = false
  }
}

const loadSiswa = async () => {
  try {
    const response = await api.get('/siswa')
    siswaList.value = response.data.data.data
  } catch (error) {
    console.error('Error loading siswa data:', error)
  }
}

const loadStatistics = async () => {
  try {
    const response = await api.get('/kredit-poin-statistics')
    statistics.value = response.data.data
  } catch (error) {
    console.error('Error loading statistics:', error)
  }
}

const debouncedSearch = debounce(() => {
  loadData(1)
}, 500)

const resetFilters = () => {
  Object.assign(filters, {
    search: '',
    siswa_id: '',
    status: '',
    jenis: '',
    start_date: '',
    end_date: ''
  })
  loadData(1)
}

const viewKreditPoin = (kreditPoin) => {
  selectedKreditPoin.value = kreditPoin
  showEditModal.value = true
}

const editKreditPoin = (kreditPoin) => {
  selectedKreditPoin.value = kreditPoin
  showEditModal.value = true
}

const approveKreditPoin = (kreditPoin) => {
  selectedKreditPoin.value = kreditPoin
  approvalAction.value = 'approve'
  showApprovalModal.value = true
}

const rejectKreditPoin = (kreditPoin) => {
  selectedKreditPoin.value = kreditPoin
  approvalAction.value = 'reject'
  showApprovalModal.value = true
}

const deleteKreditPoin = async (id) => {
  if (confirm('Apakah Anda yakin ingin menghapus data kredit poin ini?')) {
    try {
      await api.delete(`/kredit-poin/${id}`)
      await loadData(pagination.current_page)
      await loadStatistics()
    } catch (error) {
      console.error('Error deleting kredit poin:', error)
    }
  }
}

const exportData = async () => {
  try {
    const params = { ...filters }
    const response = await api.post('/kredit-poin/export', params, {
      responseType: 'blob'
    })
    
    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', `kredit-poin-${new Date().toISOString().split('T')[0]}.xlsx`)
    document.body.appendChild(link)
    link.click()
    link.remove()
  } catch (error) {
    console.error('Error exporting data:', error)
  }
}

const closeModal = () => {
  showCreateModal.value = false
  showEditModal.value = false
  selectedKreditPoin.value = null
}

const closeApprovalModal = () => {
  showApprovalModal.value = false
  selectedKreditPoin.value = null
  approvalAction.value = ''
}

const handleKreditPoinSaved = () => {
  closeModal()
  loadData(pagination.current_page)
  loadStatistics()
}

const handleApproval = () => {
  closeApprovalModal()
  loadData(pagination.current_page)
  loadStatistics()
}

// Utility functions
const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID')
}

const getStatusLabel = (status) => {
  const labels = {
    pending: 'Menunggu Persetujuan',
    approved: 'Disetujui',
    rejected: 'Ditolak'
  }
  return labels[status] || status
}

const getStatusBadgeClass = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800',
    approved: 'bg-green-100 text-green-800',
    rejected: 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const getJenisLabel = (jenis) => {
  const labels = {
    positif: 'Poin Positif',
    negatif: 'Poin Negatif'
  }
  return labels[jenis] || jenis
}

const getJenisBadgeClass = (jenis) => {
  const classes = {
    positif: 'bg-green-100 text-green-800',
    negatif: 'bg-red-100 text-red-800'
  }
  return classes[jenis] || 'bg-gray-100 text-gray-800'
}

const getNilaiFormatted = (nilai, jenis) => {
  const prefix = jenis === 'positif' ? '+' : '-'
  return prefix + Math.abs(nilai)
}

const getNilaiClass = (jenis) => {
  return jenis === 'positif' ? 'text-green-600' : 'text-red-600'
}


// Character Assessment Methods
const closeCharacterAssessment = () => {
  showCharacterAssessment.value = false
  selectedStudent.value = null
}

const handleCharacterAssessmentSaved = (assessmentData) => {
  console.log('Character assessment saved:', assessmentData)
  closeCharacterAssessment()
  // Show success message
  // Reload data if needed
}

const handleCharacterAssessmentDraftSaved = (assessmentData) => {
  console.log('Character assessment draft saved:', assessmentData)
  // Show draft saved message
}

// Lifecycle
onMounted(() => {
  loadData()
  loadSiswa()
  loadStatistics()
})
</script>

<style scoped>
.kredit-poin-view {
  padding: 1.5rem;
}
</style>