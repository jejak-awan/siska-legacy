<template>
  <div class="hak-akses-view">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Hak Akses</h1>
      <p class="text-gray-600">Kelola hak akses dan permission untuk setiap role</p>
    </div>

    <!-- Role Tabs -->
    <div class="mb-6">
      <nav class="flex space-x-8" aria-label="Tabs">
        <button
          v-for="role in roles"
          :key="role.id"
          @click="activeRole = role.id"
          :class="[
            'whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm',
            activeRole === role.id
              ? 'border-blue-500 text-blue-600'
              : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
          ]"
        >
          {{ role.name }}
        </button>
      </nav>
    </div>

    <!-- Permission Matrix -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
          <h3 class="text-lg font-semibold text-gray-900">
            Matriks Hak Akses - {{ getRoleName(activeRole) }}
          </h3>
          <button
            @click="savePermissions"
            :disabled="loading"
            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
          >
            <span v-if="loading">Menyimpan...</span>
            <span v-else>Simpan Perubahan</span>
          </button>
        </div>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Modul</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Lihat</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Tambah</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Edit</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Hapus</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Export</th>
              <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Import</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="module in modules" :key="module.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                      <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="module.icon"></path>
                      </svg>
                    </div>
                  </div>
                  <div class="ml-3">
                    <div class="text-sm font-medium text-gray-900">{{ module.name }}</div>
                    <div class="text-sm text-gray-500">{{ module.description }}</div>
                  </div>
                </div>
              </td>
              
              <!-- Permission checkboxes for each action -->
              <td v-for="action in actions" :key="action" class="px-6 py-4 whitespace-nowrap text-center">
                <input
                  :id="`${module.id}-${action}`"
                  v-model="permissions[activeRole][module.id][action]"
                  type="checkbox"
                  class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                >
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Role Management -->
    <div class="mt-8 bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <div class="flex justify-between items-center">
          <h3 class="text-lg font-semibold text-gray-900">Manajemen Role</h3>
          <button
            @click="showAddRoleModal = true"
            class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
          >
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Role
          </button>
        </div>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah User</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="role in roles" :key="role.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="flex-shrink-0">
                    <div :class="getRoleBadgeClass(role.id)" class="w-8 h-8 rounded-lg flex items-center justify-center">
                      <span class="text-xs font-bold text-white">{{ role.name.charAt(0) }}</span>
                    </div>
                  </div>
                  <div class="ml-3">
                    <div class="text-sm font-medium text-gray-900">{{ role.name }}</div>
                    <div class="text-sm text-gray-500">{{ role.code }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ role.description }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ role.userCount || 0 }} user
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="role.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                  {{ role.status ? 'Aktif' : 'Nonaktif' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button
                  @click="editRole(role)"
                  class="text-blue-600 hover:text-blue-900 mr-3"
                >
                  Edit
                </button>
                <button
                  @click="deleteRole(role.id)"
                  :disabled="role.code === 'admin'"
                  class="text-red-600 hover:text-red-900 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  Hapus
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Add/Edit Role Modal -->
    <div v-if="showAddRoleModal || showEditRoleModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">
            {{ showEditRoleModal ? 'Edit Role' : 'Tambah Role' }}
          </h3>
          
          <form @submit.prevent="saveRole" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama Role</label>
              <input
                v-model="roleForm.name"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan nama role"
              >
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Kode Role</label>
              <input
                v-model="roleForm.code"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan kode role"
              >
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
              <textarea
                v-model="roleForm.description"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan deskripsi role"
              ></textarea>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select
                v-model="roleForm.status"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option :value="true">Aktif</option>
                <option :value="false">Nonaktif</option>
              </select>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
              <button
                type="button"
                @click="closeRoleModal"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-gray-500"
              >
                Batal
              </button>
              <button
                type="submit"
                :disabled="loading"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
              >
                {{ loading ? 'Menyimpan...' : 'Simpan' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import api from '@/services/api'

// Reactive data
const loading = ref(false)
const activeRole = ref('admin')
const showAddRoleModal = ref(false)
const showEditRoleModal = ref(false)
const editingRole = ref(null)

const roles = ref([])
const modules = ref([])
const actions = ['view', 'create', 'edit', 'delete', 'export', 'import']

const permissions = ref({})

const roleForm = reactive({
  name: '',
  code: '',
  description: '',
  status: true
})

// Methods
const loadHakAkses = async () => {
  try {
    loading.value = true
    // Load hak akses data from API
    const response = await api.get('/hak-akses')
    
    roles.value = response.data.data.roles
    modules.value = Object.keys(response.data.data.modules).map(moduleId => ({
      id: moduleId,
      name: moduleId.charAt(0).toUpperCase() + moduleId.slice(1).replace('-', ' '),
      description: `Module ${moduleId}`
    }))
    
    // Mock data
    roles.value = [
      {
        id: 'admin',
        name: 'Administrator',
        code: 'admin',
        description: 'Akses penuh ke semua fitur sistem',
        userCount: 2,
        status: true
      },
      {
        id: 'guru',
        name: 'Guru',
        code: 'guru',
        description: 'Akses untuk mengelola kelas dan siswa',
        userCount: 25,
        status: true
      },
      {
        id: 'wali_kelas',
        name: 'Wali Kelas',
        code: 'wali_kelas',
        description: 'Akses untuk mengelola kelas yang diampu',
        userCount: 12,
        status: true
      },
      {
        id: 'siswa',
        name: 'Siswa',
        code: 'siswa',
        description: 'Akses terbatas untuk melihat data pribadi',
        userCount: 1250,
        status: true
      }
    ]
    
    modules.value = [
      {
        id: 'dashboard',
        name: 'Dashboard',
        description: 'Halaman utama sistem',
        icon: 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z'
      },
      {
        id: 'users',
        name: 'Manajemen User',
        description: 'Kelola pengguna sistem',
        icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z'
      },
      {
        id: 'siswa',
        name: 'Data Siswa',
        description: 'Kelola data siswa',
        icon: 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z'
      },
      {
        id: 'guru',
        name: 'Data Guru',
        description: 'Kelola data guru',
        icon: 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'
      },
      {
        id: 'presensi',
        name: 'Presensi',
        description: 'Kelola presensi siswa',
        icon: 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'
      },
      {
        id: 'kredit-poin',
        name: 'Kredit Poin',
        description: 'Kelola sistem poin karakter',
        icon: 'M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z'
      }
    ]
    
    // Initialize permissions matrix
    initializePermissions()
  } catch (error) {
    console.error('Error loading hak akses:', error)
  } finally {
    loading.value = false
  }
}

const initializePermissions = () => {
  roles.value.forEach(role => {
    permissions.value[role.id] = {}
    modules.value.forEach(module => {
      permissions.value[role.id][module.id] = {}
      actions.forEach(action => {
        // Set default permissions based on role
        permissions.value[role.id][module.id][action] = getDefaultPermission(role.id, module.id, action)
      })
    })
  })
}

const getDefaultPermission = (roleId, moduleId, action) => {
  // Admin has all permissions
  if (roleId === 'admin') return true
  
  // Student has limited permissions
  if (roleId === 'siswa') {
    return moduleId === 'dashboard' && action === 'view'
  }
  
  // Teacher and wali kelas have moderate permissions
  if (roleId === 'guru' || roleId === 'wali_kelas') {
    const allowedModules = ['dashboard', 'siswa', 'presensi', 'kredit-poin']
    const allowedActions = ['view', 'create', 'edit']
    return allowedModules.includes(moduleId) && allowedActions.includes(action)
  }
  
  return false
}

const getRoleName = (roleId) => {
  const role = roles.value.find(r => r.id === roleId)
  return role ? role.name : roleId
}

const getRoleBadgeClass = (roleId) => {
  const classes = {
    admin: 'bg-red-500',
    guru: 'bg-blue-500',
    wali_kelas: 'bg-green-500',
    siswa: 'bg-yellow-500'
  }
  return classes[roleId] || 'bg-gray-500'
}

const savePermissions = async () => {
  try {
    loading.value = true
    // Update permissions via API
    const currentRole = roles.value.find(r => r.id === activeRole.value)
    if (currentRole) {
      const permissionUpdates = []
      Object.keys(permissions.value[activeRole.value]).forEach(moduleId => {
        Object.keys(permissions.value[activeRole.value][moduleId]).forEach(action => {
          permissionUpdates.push({
            permission_id: permissions.value[activeRole.value][moduleId][action].permission_id,
            granted: permissions.value[activeRole.value][moduleId][action]
          })
        })
      })
      
      await api.put('/hak-akses/permissions', {
        role_id: currentRole.id,
        permissions: permissionUpdates
      })
    }
    
    console.log('Permissions saved:', permissions.value)
    // Show success message
  } catch (error) {
    console.error('Error saving permissions:', error)
    // Show error message
  } finally {
    loading.value = false
  }
}

const editRole = (role) => {
  editingRole.value = role
  Object.assign(roleForm, role)
  showEditRoleModal.value = true
}

const deleteRole = async (roleId) => {
  if (confirm('Apakah Anda yakin ingin menghapus role ini?')) {
    try {
      // TODO: Implement API call
      // await api.delete(`/roles/${roleId}`)
      
      roles.value = roles.value.filter(r => r.id !== roleId)
    } catch (error) {
      console.error('Error deleting role:', error)
    }
  }
}

const saveRole = async () => {
  try {
    loading.value = true
    
    if (showEditRoleModal.value) {
      // TODO: Implement API call for update
      // await api.put(`/roles/${editingRole.value.id}`, roleForm)
      
      const index = roles.value.findIndex(r => r.id === editingRole.value.id)
      if (index !== -1) {
        roles.value[index] = { ...editingRole.value, ...roleForm }
      }
    } else {
      // TODO: Implement API call for create
      // const response = await api.post('/roles', roleForm)
      
      const newRole = {
        id: roleForm.code,
        userCount: 0,
        ...roleForm
      }
      roles.value.push(newRole)
      
      // Initialize permissions for new role
      permissions.value[newRole.id] = {}
      modules.value.forEach(module => {
        permissions.value[newRole.id][module.id] = {}
        actions.forEach(action => {
          permissions.value[newRole.id][module.id][action] = false
        })
      })
    }
    
    closeRoleModal()
  } catch (error) {
    console.error('Error saving role:', error)
  } finally {
    loading.value = false
  }
}

const closeRoleModal = () => {
  showAddRoleModal.value = false
  showEditRoleModal.value = false
  editingRole.value = null
  Object.assign(roleForm, {
    name: '',
    code: '',
    description: '',
    status: true
  })
}

// Lifecycle
onMounted(() => {
  loadHakAkses()
})
</script>

<style scoped>
.hak-akses-view {
  @apply p-6;
}
</style>
