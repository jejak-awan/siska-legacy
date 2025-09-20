<template>
  <div class="presensi-view">
    <!-- Header Section -->
    <div class="mb-6">
      <div class="flex items-center justify-between">
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Manajemen Presensi</h1>
          <p class="text-gray-600 mt-1">Kelola data kehadiran siswa dan guru</p>
        </div>
        <div class="flex space-x-3">
          <button
            @click="showCreateModal = true"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center space-x-2 transition-colors"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            <span>Tambah Presensi</span>
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
        title="Total Presensi Hari Ini"
        :value="statistics.today || 0"
        icon="calendar-days"
        color="blue"
      />
      <StatCard
        title="Hadir"
        :value="statistics.hadir_today || 0"
        icon="check-circle"
        color="green"
      />
      <StatCard
        title="Terlambat"
        :value="statistics.terlambat_today || 0"
        icon="clock"
        color="yellow"
      />
      <StatCard
        title="Alpha"
        :value="statistics.alpha_today || 0"
        icon="x-circle"
        color="red"
      />
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Search -->
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
          <input
            v-model="filters.search"
            type="text"
            placeholder="Cari nama user..."
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            @input="debouncedSearch"
          />
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

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Selesai</label>
          <input
            v-model="filters.end_date"
            type="date"
            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            @change="loadData"
          />
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
            <option value="hadir">Hadir</option>
            <option value="terlambat">Terlambat</option>
            <option value="sakit">Sakit</option>
            <option value="izin">Izin</option>
            <option value="alpha">Alpha</option>
          </select>
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
          <h3 class="text-lg font-semibold text-gray-900">Data Presensi</h3>
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
                  User
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Tanggal
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Jam Masuk
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Jam Keluar
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Validator
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Aksi
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="presensi in presensiData" :key="presensi.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-sm font-medium text-gray-700">
                          {{ presensi.user?.username?.charAt(0).toUpperCase() }}
                        </span>
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">
                        {{ presensi.user?.username }}
                      </div>
                      <div class="text-sm text-gray-500">
                        {{ presensi.user?.email }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatDate(presensi.tanggal) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ presensi.jam_masuk || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ presensi.jam_keluar || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="getStatusBadgeClass(presensi.status)"
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  >
                    {{ getStatusLabel(presensi.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ presensi.validator?.username || '-' }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex space-x-2">
                    <button
                      @click="viewPresensi(presensi)"
                      class="text-blue-600 hover:text-blue-900 transition-colors"
                    >
                      Lihat
                    </button>
                    <button
                      @click="editPresensi(presensi)"
                      class="text-green-600 hover:text-green-900 transition-colors"
                    >
                      Edit
                    </button>
                    <button
                      @click="deletePresensi(presensi.id)"
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
          <div v-if="presensiData.length === 0" class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data presensi</h3>
            <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan data presensi baru.</p>
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
    <PresensiFormModal
      v-if="showCreateModal || showEditModal"
      :show="showCreateModal || showEditModal"
      :presensi="selectedPresensi"
      @close="closeModal"
      @saved="handlePresensiSaved"
    />
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import StatCard from '@/components/ui/StatCard.vue'
import PresensiFormModal from '@/components/modals/PresensiFormModal.vue'
import api from '@/services/api'

const router = useRouter()

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
const presensiData = ref([])
const statistics = ref({})
const showCreateModal = ref(false)
const showEditModal = ref(false)
const selectedPresensi = ref(null)

// Filters
const filters = reactive({
  search: '',
  start_date: '',
  end_date: '',
  status: ''
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
    
    const response = await api.get('/presensi', { params })
    presensiData.value = response.data.data.data
    Object.assign(pagination, response.data.data)
  } catch (error) {
    console.error('Error loading presensi data:', error)
  } finally {
    loading.value = false
  }
}

const loadStatistics = async () => {
  try {
    const response = await api.get('/presensi-statistics')
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
    start_date: '',
    end_date: '',
    status: ''
  })
  loadData(1)
}

const viewPresensi = (presensi) => {
  selectedPresensi.value = presensi
  showEditModal.value = true
}

const editPresensi = (presensi) => {
  selectedPresensi.value = presensi
  showEditModal.value = true
}

const deletePresensi = async (id) => {
  if (confirm('Apakah Anda yakin ingin menghapus data presensi ini?')) {
    try {
      await api.delete(`/presensi/${id}`)
      await loadData(pagination.current_page)
      await loadStatistics()
    } catch (error) {
      console.error('Error deleting presensi:', error)
    }
  }
}

const exportData = async () => {
  try {
    const params = { ...filters }
    const response = await api.post('/presensi/export', params, {
      responseType: 'blob'
    })
    
    const url = window.URL.createObjectURL(new Blob([response.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', `presensi-${new Date().toISOString().split('T')[0]}.xlsx`)
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
  selectedPresensi.value = null
}

const handlePresensiSaved = () => {
  closeModal()
  loadData(pagination.current_page)
  loadStatistics()
}

// Utility functions
const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID')
}

const getStatusLabel = (status) => {
  const labels = {
    hadir: 'Hadir',
    terlambat: 'Terlambat',
    sakit: 'Sakit',
    izin: 'Izin',
    alpha: 'Alpha'
  }
  return labels[status] || status
}

const getStatusBadgeClass = (status) => {
  const classes = {
    hadir: 'bg-green-100 text-green-800',
    terlambat: 'bg-yellow-100 text-yellow-800',
    sakit: 'bg-blue-100 text-blue-800',
    izin: 'bg-orange-100 text-orange-800',
    alpha: 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}


// Lifecycle
onMounted(() => {
  loadData()
  loadStatistics()
})
</script>

<style scoped>
.presensi-view {
  padding: 1.5rem;
}
</style>