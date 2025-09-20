<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Page Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Manajemen Pengguna</h1>
            <p class="mt-2 text-lg text-gray-600">
              Kelola semua pengguna sistem kesiswaan dengan akses penuh
            </p>
            <p class="text-sm text-gray-500">
              Total {{ stats.totalUsers || 0 }} pengguna terdaftar
            </p>
          </div>
          <div class="flex items-center space-x-3">
            <!-- Import/Export Buttons -->
            <div class="flex items-center space-x-2">
              <button 
                @click="showImportModal = true"
                class="flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors"
              >
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M9 19l3 3m0 0l3-3m-3 3V10" />
                </svg>
                Import
              </button>
              <button 
                @click="exportUsers"
                class="flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors"
              >
                <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                </svg>
                Export
              </button>
            </div>
            <!-- Add User Button -->
            <button 
              @click="showCreateModal = true"
              class="flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors"
            >
              <svg class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              Tambah Pengguna
            </button>
          </div>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <div class="flex items-center">
            <div class="p-3 bg-blue-100 rounded-lg">
              <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Total Pengguna</p>
              <p class="text-2xl font-semibold text-gray-900">{{ stats.totalUsers || 0 }}</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <div class="flex items-center">
            <div class="p-3 bg-red-100 rounded-lg">
              <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Admin</p>
              <p class="text-2xl font-semibold text-gray-900">{{ stats.adminCount || 0 }}</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <div class="flex items-center">
            <div class="p-3 bg-green-100 rounded-lg">
              <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Guru & Staff</p>
              <p class="text-2xl font-semibold text-gray-900">{{ stats.staffCount || 0 }}</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <div class="flex items-center">
            <div class="p-3 bg-purple-100 rounded-lg">
              <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
              </svg>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Siswa & Orang Tua</p>
              <p class="text-2xl font-semibold text-gray-900">{{ stats.studentParentCount || 0 }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Filters & Search -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="p-6">
          <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-900">Filter & Pencarian</h3>
            <button 
              @click="resetFilters"
              class="text-sm text-blue-600 hover:text-blue-700 font-medium"
            >
              Reset Filter
            </button>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Pencarian
              </label>
              <div class="relative">
                <input
                  v-model="filters.search"
                  type="text"
                  placeholder="Cari username atau email..."
                  class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  @input="debouncedSearch"
                />
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
            </div>
            
            <!-- Role Filter -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Role
              </label>
              <select 
                v-model="filters.role_type" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                @change="loadUsers"
              >
                <option value="">Semua Role</option>
                <option value="admin">Admin</option>
                <option value="guru">Guru</option>
                <option value="siswa">Siswa</option>
                <option value="tendik">Tenaga Kependidikan</option>
                <option value="bk">BK</option>
                <option value="wali_kelas">Wali Kelas</option>
                <option value="osis">OSIS</option>
                <option value="ekstrakurikuler">Ekstrakurikuler</option>
                <option value="orang_tua">Orang Tua</option>
              </select>
            </div>
            
            <!-- Status Filter -->
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Status
              </label>
              <select 
                v-model="filters.status" 
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" 
                @change="loadUsers"
              >
                <option value="">Semua Status</option>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Non Aktif</option>
                <option value="suspended">Suspended</option>
              </select>
            </div>
          </div>

          <!-- Bulk Actions -->
          <div v-if="selectedUsers.length > 0" class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
            <div class="flex items-center justify-between">
              <div class="flex items-center space-x-3">
                <div class="flex items-center space-x-2">
                  <svg class="h-5 w-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="text-sm font-medium text-blue-900">
                    {{ selectedUsers.length }} pengguna dipilih
                  </span>
                </div>
                <button 
                  @click="clearSelection"
                  class="text-sm text-blue-600 hover:text-blue-700 font-medium"
                >
                  Batal Pilih
                </button>
              </div>
              <div class="flex items-center space-x-2">
                <button 
                  @click="bulkAction('activate')"
                  class="px-3 py-1 bg-green-600 text-white text-sm rounded-lg hover:bg-green-700 transition-colors"
                >
                  Aktifkan
                </button>
                <button 
                  @click="bulkAction('deactivate')"
                  class="px-3 py-1 bg-yellow-600 text-white text-sm rounded-lg hover:bg-yellow-700 transition-colors"
                >
                  Non-aktifkan
                </button>
                <button 
                  @click="bulkAction('suspend')"
                  class="px-3 py-1 bg-orange-600 text-white text-sm rounded-lg hover:bg-orange-700 transition-colors"
                >
                  Suspend
                </button>
                <button 
                  @click="bulkAction('delete')"
                  class="px-3 py-1 bg-red-600 text-white text-sm rounded-lg hover:bg-red-700 transition-colors"
                >
                  Hapus
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Users Table -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  <input 
                    type="checkbox" 
                    :checked="allSelected"
                    @change="toggleSelectAll"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                  />
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Pengguna
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Role
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Status
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Bergabung
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Aksi
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="loading" v-for="n in 5" :key="n">
                <td colspan="6" class="px-6 py-4">
                  <div class="animate-pulse flex space-x-4">
                    <div class="rounded-full bg-gray-200 h-10 w-10"></div>
                    <div class="flex-1 space-y-2 py-1">
                      <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                      <div class="h-4 bg-gray-200 rounded w-1/2"></div>
                    </div>
                  </div>
                </td>
              </tr>
              
              <tr v-else-if="users.length === 0">
                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                  Tidak ada pengguna ditemukan
                </td>
              </tr>
              
              <tr v-else v-for="user in users" :key="user.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <input 
                    type="checkbox" 
                    :value="user.id"
                    v-model="selectedUsers"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                  />
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <span class="text-blue-600 font-semibold text-sm">
                          {{ user.username.charAt(0).toUpperCase() }}
                        </span>
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">
                        {{ user.username }}
                      </div>
                      <div class="text-sm text-gray-500">
                        {{ user.email }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" 
                        :class="getRoleBadgeClass(user.role_type)">
                    {{ getRoleLabel(user.role_type) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                        :class="getStatusBadgeClass(user.status)">
                    {{ getStatusLabel(user.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDate(user.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <div class="flex items-center space-x-2">
                    <button 
                      @click="editUser(user)"
                      class="text-blue-600 hover:text-blue-900"
                      title="Edit"
                    >
                      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>
                    <button 
                      v-if="user.status === 'aktif'"
                      @click="toggleUserStatus(user, 'deactivate')"
                      class="text-orange-600 hover:text-orange-900"
                      title="Non-aktifkan"
                    >
                      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728L5.636 5.636m12.728 12.728L18.364 5.636M5.636 18.364l12.728-12.728" />
                      </svg>
                    </button>
                    <button 
                      v-else
                      @click="toggleUserStatus(user, 'activate')"
                      class="text-green-600 hover:text-green-900"
                      title="Aktifkan"
                    >
                      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                    </button>
                    <button 
                      v-if="user.role_type !== 'admin'"
                      @click="deleteUser(user)"
                      class="text-red-600 hover:text-red-900"
                      title="Hapus"
                    >
                      <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="pagination.total > 0" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
          <div class="flex-1 flex justify-between sm:hidden">
            <button 
              @click="loadUsers(pagination.current_page - 1)"
              :disabled="pagination.current_page <= 1"
              class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
            >
              Previous
            </button>
            <button 
              @click="loadUsers(pagination.current_page + 1)"
              :disabled="pagination.current_page >= pagination.last_page"
              class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50"
            >
              Next
            </button>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Showing <span class="font-medium">{{ pagination.from }}</span> to <span class="font-medium">{{ pagination.to }}</span> of <span class="font-medium">{{ pagination.total }}</span> results
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <button 
                  @click="loadUsers(pagination.current_page - 1)"
                  :disabled="pagination.current_page <= 1"
                  class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
                >
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                  </svg>
                </button>
                
                <template v-for="page in visiblePages" :key="page">
                  <button 
                    @click="loadUsers(page)"
                    :class="[
                      'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                      page === pagination.current_page 
                        ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' 
                        : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                    ]"
                  >
                    {{ page }}
                  </button>
                </template>
                
                <button 
                  @click="loadUsers(pagination.current_page + 1)"
                  :disabled="pagination.current_page >= pagination.last_page"
                  class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50"
                >
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                  </svg>
                </button>
              </nav>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit User Modal -->
    <UserFormModal
      v-if="showCreateModal || showEditModal"
      :user="editingUser"
      :show="showCreateModal || showEditModal"
      @close="closeModal"
      @saved="handleUserSaved"
    />

    <!-- Import Modal -->
    <ImportModal
      v-if="showImportModal"
      :show="showImportModal"
      @close="showImportModal = false"
      @imported="handleImported"
    />
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import api from '../../services/api'
import StatCard from '../../components/ui/StatCard.vue'
import UserFormModal from '../../components/modals/UserFormModal.vue'
import ImportModal from '../../components/modals/ImportModal.vue'

const router = useRouter()

// Reactive data
const users = ref([])
const stats = ref({
  totalUsers: 0,
  adminCount: 0,
  staffCount: 0,
  studentParentCount: 0
})

const loading = ref(false)
const statsLoading = ref(false)
const selectedUsers = ref([])
const showCreateModal = ref(false)
const showEditModal = ref(false)
const showImportModal = ref(false)
const editingUser = ref(null)

const filters = reactive({
  search: '',
  role_type: '',
  status: ''
})

const pagination = reactive({
  current_page: 1,
  last_page: 1,
  from: 0,
  to: 0,
  total: 0,
  per_page: 15
})

// Computed properties
const allSelected = computed(() => {
  return users.value.length > 0 && selectedUsers.value.length === users.value.length
})

const visiblePages = computed(() => {
  const pages = []
  const start = Math.max(1, pagination.current_page - 2)
  const end = Math.min(pagination.last_page, pagination.current_page + 2)
  
  for (let i = start; i <= end; i++) {
    pages.push(i)
  }
  
  return pages
})

// Methods
const loadUsers = async (page = 1) => {
  loading.value = true
  try {
    const params = {
      page,
      per_page: pagination.per_page,
      ...filters
    }
    
    // Remove empty filters
    Object.keys(params).forEach(key => {
      if (params[key] === '' || params[key] === null) {
        delete params[key]
      }
    })
    
    const response = await api.get('/users', { params })
    
    users.value = response.data.data.data
    Object.assign(pagination, {
      current_page: response.data.data.current_page,
      last_page: response.data.data.last_page,
      from: response.data.data.from,
      to: response.data.data.to,
      total: response.data.data.total,
      per_page: response.data.data.per_page
    })
    
    selectedUsers.value = []
  } catch (error) {
    console.error('Error loading users:', error)
    // Handle error (show toast, etc.)
  } finally {
    loading.value = false
  }
}

const loadStats = async () => {
  statsLoading.value = true
  try {
    const response = await api.get('/users-statistics')
    stats.value = response.data.data
  } catch (error) {
    console.error('Error loading stats:', error)
  } finally {
    statsLoading.value = false
  }
}

const debouncedSearch = (() => {
  let timeout
  return () => {
    clearTimeout(timeout)
    timeout = setTimeout(() => {
      loadUsers(1)
    }, 500)
  }
})()

const toggleSelectAll = () => {
  if (allSelected.value) {
    selectedUsers.value = []
  } else {
    selectedUsers.value = users.value.map(user => user.id)
  }
}

const clearSelection = () => {
  selectedUsers.value = []
}

const resetFilters = () => {
  filters.search = ''
  filters.role_type = ''
  filters.status = ''
  loadUsers(1)
}

const bulkAction = async (action) => {
  if (selectedUsers.value.length === 0) return
  
  const confirmMessage = {
    activate: 'Aktifkan pengguna yang dipilih?',
    deactivate: 'Non-aktifkan pengguna yang dipilih?',
    suspend: 'Suspend pengguna yang dipilih?',
    delete: 'Hapus pengguna yang dipilih? Tindakan ini tidak dapat dibatalkan.'
  }
  
  if (!confirm(confirmMessage[action])) return
  
  try {
    await api.post('/users/bulk-action', {
      action,
      user_ids: selectedUsers.value
    })
    
    selectedUsers.value = []
    await loadUsers(pagination.current_page)
    await loadStats()
    
    // Show success message
    alert(`Bulk ${action} berhasil dilakukan`)
  } catch (error) {
    console.error(`Error performing bulk ${action}:`, error)
    alert(`Error: ${error.response?.data?.message || 'Terjadi kesalahan'}`)
  }
}

const editUser = (user) => {
  editingUser.value = { ...user }
  showEditModal.value = true
}

const deleteUser = async (user) => {
  if (!confirm(`Hapus pengguna ${user.username}? Tindakan ini tidak dapat dibatalkan.`)) return
  
  try {
    await api.delete(`/users/${user.id}`)
    await loadUsers(pagination.current_page)
    await loadStats()
    alert('Pengguna berhasil dihapus')
  } catch (error) {
    console.error('Error deleting user:', error)
    alert(`Error: ${error.response?.data?.message || 'Terjadi kesalahan'}`)
  }
}

const toggleUserStatus = async (user, action) => {
  try {
    await api.post(`/users/${user.id}/${action}`)
    await loadUsers(pagination.current_page)
    await loadStats()
    
    const actionLabel = action === 'activate' ? 'diaktifkan' : 'dinonaktifkan'
    alert(`Pengguna ${user.username} berhasil ${actionLabel}`)
  } catch (error) {
    console.error(`Error ${action} user:`, error)
    alert(`Error: ${error.response?.data?.message || 'Terjadi kesalahan'}`)
  }
}

const exportUsers = async () => {
  try {
    const params = {
      format: 'xlsx',
      ...filters
    }
    
    const response = await api.post('/users/export', params)
    alert(response.data.message)
  } catch (error) {
    console.error('Error exporting users:', error)
    alert(`Error: ${error.response?.data?.message || 'Terjadi kesalahan'}`)
  }
}

const closeModal = () => {
  showCreateModal.value = false
  showEditModal.value = false
  editingUser.value = null
}

const handleUserSaved = () => {
  closeModal()
  loadUsers(pagination.current_page)
  loadStats()
}

const handleImported = () => {
  showImportModal.value = false
  loadUsers(1)
  loadStats()
}

// Utility functions
const getRoleLabel = (role) => {
  const labels = {
    admin: 'Admin',
    guru: 'Guru',
    siswa: 'Siswa',
    tendik: 'Tendik',
    bk: 'BK',
    wali_kelas: 'Wali Kelas',
    osis: 'OSIS',
    ekstrakurikuler: 'Ekstrakurikuler',
    orang_tua: 'Orang Tua'
  }
  return labels[role] || role
}

const getRoleBadgeClass = (role) => {
  const classes = {
    admin: 'bg-red-100 text-red-800',
    guru: 'bg-blue-100 text-blue-800',
    siswa: 'bg-green-100 text-green-800',
    tendik: 'bg-yellow-100 text-yellow-800',
    bk: 'bg-purple-100 text-purple-800',
    wali_kelas: 'bg-indigo-100 text-indigo-800',
    osis: 'bg-pink-100 text-pink-800',
    ekstrakurikuler: 'bg-orange-100 text-orange-800',
    orang_tua: 'bg-gray-100 text-gray-800'
  }
  return classes[role] || 'bg-gray-100 text-gray-800'
}

const getStatusLabel = (status) => {
  const labels = {
    aktif: 'Aktif',
    nonaktif: 'Non Aktif',
    suspended: 'Suspended'
  }
  return labels[status] || status
}

const getStatusBadgeClass = (status) => {
  const classes = {
    aktif: 'bg-green-100 text-green-800',
    nonaktif: 'bg-gray-100 text-gray-800',
    suspended: 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

// Lifecycle
onMounted(() => {
  loadUsers()
  loadStats()
})

// Watch for route query changes (if needed)
watch(() => router.currentRoute.value.query, (newQuery) => {
  if (newQuery.action === 'add') {
    showCreateModal.value = true
  }
}, { immediate: true })
</script>