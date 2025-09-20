<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header Section -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Notifikasi</h1>
            <p class="mt-2 text-lg text-gray-600">Kelola notifikasi sistem kesiswaan</p>
            <p class="text-sm text-gray-500">Monitor dan kelola semua notifikasi pengguna</p>
          </div>
          <div class="flex space-x-3">
            <button
              @click="markAllAsRead"
              :disabled="loading"
              class="flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors disabled:opacity-50"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              Tandai Semua Dibaca
            </button>
            <button
              v-if="canCreate"
              @click="showCreateModal = true"
              class="flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Kirim Notifikasi
            </button>
          </div>
        </div>
      </div>

      <!-- Statistics Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg">
              <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12.828 7H4.828z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Notifikasi</p>
              <p class="text-2xl font-semibold text-gray-900">{{ statistics.total || 0 }}</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <div class="flex items-center">
            <div class="p-3 bg-yellow-100 rounded-lg">
              <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Belum Dibaca</p>
              <p class="text-2xl font-semibold text-gray-900">{{ statistics.unread || 0 }}</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-lg">
              <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Sudah Dibaca</p>
              <p class="text-2xl font-semibold text-gray-900">{{ statistics.read || 0 }}</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <div class="flex items-center">
            <div class="p-3 bg-gray-100 rounded-lg">
              <svg class="h-6 w-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8l6 6m0 0l6-6m-6 6V4" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Diarsipkan</p>
              <p class="text-2xl font-semibold text-gray-900">{{ statistics.archived || 0 }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters Section -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Filter & Pencarian</h3>
          <button
            @click="resetFilters"
            class="text-sm text-blue-600 hover:text-blue-700 font-medium"
          >
            Reset Filter
          </button>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
          <!-- Search -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Cari</label>
            <div class="relative">
              <input
                v-model="filters.search"
                type="text"
                placeholder="Cari judul atau pesan..."
                class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                @input="debouncedSearch"
              />
              <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
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
              <option value="unread">Belum Dibaca</option>
              <option value="read">Sudah Dibaca</option>
              <option value="archived">Diarsipkan</option>
            </select>
          </div>

          <!-- Type Filter -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Tipe</label>
            <select
              v-model="filters.tipe"
              class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              @change="loadData"
            >
              <option value="">Semua Tipe</option>
              <option value="info">Informasi</option>
              <option value="warning">Peringatan</option>
              <option value="error">Error</option>
              <option value="success">Berhasil</option>
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
      </div>

      <!-- Data Table -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
          <div class="flex items-center justify-between mb-6">
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Data Notifikasi</h3>
              <p class="text-sm text-gray-500">Total {{ pagination.total }} notifikasi</p>
            </div>
            <div class="flex items-center space-x-3">
              <span class="text-sm text-gray-500">Per halaman:</span>
              <select
                v-model="pagination.per_page"
                @change="loadData"
                class="px-3 py-1 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
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
                  Status
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Tipe
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Judul
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Pesan
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Tanggal
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Aksi
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="notification in notificationData" :key="notification.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="getStatusBadgeClass(notification.status)"
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  >
                    {{ getStatusLabel(notification.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="getTypeBadgeClass(notification.tipe)"
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  >
                    {{ getTypeLabel(notification.tipe) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">
                    {{ notification.judul }}
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 max-w-xs truncate">
                    {{ notification.pesan }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatDate(notification.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <div class="flex space-x-2">
                    <button
                      @click="viewNotification(notification)"
                      class="text-blue-600 hover:text-blue-900 transition-colors"
                    >
                      Lihat
                    </button>
                    <button
                      v-if="notification.status === 'unread'"
                      @click="markAsRead(notification)"
                      class="text-green-600 hover:text-green-900 transition-colors"
                    >
                      Tandai Dibaca
                    </button>
                    <button
                      v-if="notification.status === 'read'"
                      @click="markAsArchived(notification)"
                      class="text-gray-600 hover:text-gray-900 transition-colors"
                    >
                      Arsipkan
                    </button>
                    <button
                      @click="deleteNotification(notification.id)"
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
          <div v-if="notificationData.length === 0" class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12.828 7H4.828z"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada notifikasi</h3>
            <p class="mt-1 text-sm text-gray-500">Belum ada notifikasi yang tersedia.</p>
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

    <!-- Create Modal -->
    <NotificationFormModal
      v-if="showCreateModal"
      :show="showCreateModal"
      @close="closeModal"
      @saved="handleNotificationSaved"
    />

    <!-- View Modal -->
    <NotificationDetailModal
      v-if="showViewModal"
      :show="showViewModal"
      :notification="selectedNotification"
      @close="closeViewModal"
      @updated="handleNotificationUpdated"
    />
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import StatCard from '@/components/ui/StatCard.vue'
import NotificationFormModal from '@/components/modals/NotificationFormModal.vue'
import NotificationDetailModal from '@/components/modals/NotificationDetailModal.vue'
import api from '@/services/api'

const router = useRouter()
const authStore = useAuthStore()

// Utility functions
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
const notificationData = ref([])
const statistics = ref({})
const showCreateModal = ref(false)
const showViewModal = ref(false)
const selectedNotification = ref(null)

// Filters
const filters = reactive({
  search: '',
  status: '',
  tipe: '',
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
const canCreate = computed(() => {
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
    
    const response = await api.get('/notifications', { params })
    notificationData.value = response.data.data.data
    Object.assign(pagination, response.data.data)
  } catch (error) {
    console.error('Error loading notification data:', error)
  } finally {
    loading.value = false
  }
}

const loadStatistics = async () => {
  try {
    const response = await api.get('/notifications/stats')
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
    status: '',
    tipe: '',
    start_date: '',
    end_date: ''
  })
  loadData(1)
}

const viewNotification = (notification) => {
  selectedNotification.value = notification
  showViewModal.value = true
}

const markAsRead = async (notification) => {
  try {
    await api.post(`/notifications/${notification.id}/mark-read`)
    await loadData(pagination.current_page)
    await loadStatistics()
  } catch (error) {
    console.error('Error marking notification as read:', error)
  }
}

const markAsArchived = async (notification) => {
  try {
    await api.post(`/notifications/${notification.id}/mark-archived`)
    await loadData(pagination.current_page)
    await loadStatistics()
  } catch (error) {
    console.error('Error marking notification as archived:', error)
  }
}

const markAllAsRead = async () => {
  try {
    await api.post('/notifications/mark-all-read')
    await loadData(pagination.current_page)
    await loadStatistics()
  } catch (error) {
    console.error('Error marking all notifications as read:', error)
  }
}

const deleteNotification = async (id) => {
  if (confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
    try {
      await api.delete(`/notifications/${id}`)
      await loadData(pagination.current_page)
      await loadStatistics()
    } catch (error) {
      console.error('Error deleting notification:', error)
    }
  }
}

const closeModal = () => {
  showCreateModal.value = false
}

const closeViewModal = () => {
  showViewModal.value = false
  selectedNotification.value = null
}

const handleNotificationSaved = () => {
  closeModal()
  loadData(pagination.current_page)
  loadStatistics()
}

const handleNotificationUpdated = () => {
  closeViewModal()
  loadData(pagination.current_page)
  loadStatistics()
}

// Utility functions
const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID')
}

const getStatusLabel = (status) => {
  const labels = {
    unread: 'Belum Dibaca',
    read: 'Sudah Dibaca',
    archived: 'Diarsipkan'
  }
  return labels[status] || status
}

const getStatusBadgeClass = (status) => {
  const classes = {
    unread: 'bg-yellow-100 text-yellow-800',
    read: 'bg-green-100 text-green-800',
    archived: 'bg-gray-100 text-gray-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const getTypeLabel = (tipe) => {
  const labels = {
    info: 'Informasi',
    warning: 'Peringatan',
    error: 'Error',
    success: 'Berhasil'
  }
  return labels[tipe] || tipe
}

const getTypeBadgeClass = (tipe) => {
  const classes = {
    info: 'bg-blue-100 text-blue-800',
    warning: 'bg-yellow-100 text-yellow-800',
    error: 'bg-red-100 text-red-800',
    success: 'bg-green-100 text-green-800'
  }
  return classes[tipe] || 'bg-gray-100 text-gray-800'
}

// Lifecycle
onMounted(() => {
  loadData()
  loadStatistics()
})
</script>
