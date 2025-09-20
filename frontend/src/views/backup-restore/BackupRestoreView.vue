<template>
  <div class="backup-restore-view">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Backup & Restore</h1>
      <p class="text-gray-600">Kelola backup dan restore data sistem</p>
    </div>

    <!-- Tabs -->
    <div class="mb-6">
      <nav class="flex space-x-8" aria-label="Tabs">
        <button
          v-for="tab in tabs"
          :key="tab.id"
          @click="activeTab = tab.id"
          :class="[
            'whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm',
            activeTab === tab.id
              ? 'border-blue-500 text-blue-600'
              : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
          ]"
        >
          {{ tab.name }}
        </button>
      </nav>
    </div>

    <!-- Backup Tab -->
    <div v-if="activeTab === 'backup'" class="space-y-6">
      <!-- Backup Actions -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Buat Backup</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Full Backup -->
          <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex items-center mb-3">
              <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path>
                </svg>
              </div>
              <h4 class="text-lg font-semibold text-gray-900">Full Backup</h4>
            </div>
            <p class="text-sm text-gray-600 mb-4">Backup semua data sistem termasuk database, file, dan konfigurasi</p>
            <button
              @click="createFullBackup"
              :disabled="loading"
              class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
            >
              <span v-if="loading && backupType === 'full'">Membuat Backup...</span>
              <span v-else>Buat Full Backup</span>
            </button>
          </div>

          <!-- Database Only -->
          <div class="border border-gray-200 rounded-lg p-4">
            <div class="flex items-center mb-3">
              <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                </svg>
              </div>
              <h4 class="text-lg font-semibold text-gray-900">Database Only</h4>
            </div>
            <p class="text-sm text-gray-600 mb-4">Backup hanya database tanpa file dan konfigurasi</p>
            <button
              @click="createDatabaseBackup"
              :disabled="loading"
              class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50"
            >
              <span v-if="loading && backupType === 'database'">Membuat Backup...</span>
              <span v-else>Buat Database Backup</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Backup History -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Riwayat Backup</h3>
        </div>
        
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama File</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ukuran</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="backup in backupList" :key="backup.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ backup.nama_file }}</div>
                  <div class="text-sm text-gray-500">{{ backup.deskripsi }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getJenisBadgeClass(backup.jenis)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                    {{ getJenisText(backup.jenis) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatFileSize(backup.ukuran) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatDate(backup.tanggal) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getStatusBadgeClass(backup.status)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                    {{ getStatusText(backup.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    @click="downloadBackup(backup)"
                    class="text-blue-600 hover:text-blue-900 mr-3"
                  >
                    Download
                  </button>
                  <button
                    @click="deleteBackup(backup.id)"
                    class="text-red-600 hover:text-red-900"
                  >
                    Hapus
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Restore Tab -->
    <div v-if="activeTab === 'restore'" class="space-y-6">
      <!-- Upload Backup -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Restore dari File</h3>
        
        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6">
          <div class="text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
              <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="mt-4">
              <label for="file-upload" class="cursor-pointer">
                <span class="mt-2 block text-sm font-medium text-gray-900">
                  Upload file backup
                </span>
                <span class="mt-1 block text-sm text-gray-500">
                  atau drag and drop file backup di sini
                </span>
                <input
                  id="file-upload"
                  ref="fileInput"
                  type="file"
                  accept=".sql,.zip,.tar.gz"
                  @change="handleFileUpload"
                  class="sr-only"
                >
              </label>
            </div>
            <p class="mt-2 text-xs text-gray-500">
              File yang didukung: .sql, .zip, .tar.gz (maksimal 100MB)
            </p>
          </div>
        </div>
        
        <div v-if="selectedFile" class="mt-4 p-4 bg-gray-50 rounded-lg">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm font-medium text-gray-900">{{ selectedFile.name }}</p>
              <p class="text-sm text-gray-500">{{ formatFileSize(selectedFile.size) }}</p>
            </div>
            <button
              @click="removeSelectedFile"
              class="text-red-600 hover:text-red-900"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
        </div>
        
        <div class="mt-6">
          <button
            @click="restoreFromFile"
            :disabled="!selectedFile || loading"
            class="w-full bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 disabled:opacity-50"
          >
            <span v-if="loading && restoreType === 'file'">Memulihkan...</span>
            <span v-else>Restore dari File</span>
          </button>
        </div>
      </div>

      <!-- Restore from Backup List -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Restore dari Riwayat Backup</h3>
        </div>
        
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama File</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="backup in backupList" :key="backup.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ backup.nama_file }}</div>
                  <div class="text-sm text-gray-500">{{ backup.deskripsi }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getJenisBadgeClass(backup.jenis)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                    {{ getJenisText(backup.jenis) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatDate(backup.tanggal) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    @click="restoreFromBackup(backup)"
                    :disabled="loading"
                    class="text-green-600 hover:text-green-900 disabled:opacity-50"
                  >
                    <span v-if="loading && restoreType === 'backup'">Memulihkan...</span>
                    <span v-else>Restore</span>
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Settings Tab -->
    <div v-if="activeTab === 'settings'" class="space-y-6">
      <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Pengaturan Backup</h3>
        </div>
        
        <div class="p-6">
          <form @submit.prevent="saveSettings" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Auto Backup</label>
                <div class="space-y-2">
                  <label class="flex items-center">
                    <input
                      v-model="settings.auto_backup_enabled"
                      type="checkbox"
                      class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                    >
                    <span class="ml-2 text-sm text-gray-700">Aktifkan auto backup</span>
                  </label>
                </div>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Frekuensi Backup</label>
                <select
                  v-model="settings.backup_frequency"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                  <option value="daily">Harian</option>
                  <option value="weekly">Mingguan</option>
                  <option value="monthly">Bulanan</option>
                </select>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Retensi Backup</label>
              <input
                v-model="settings.backup_retention"
                type="number"
                min="1"
                max="365"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Jumlah hari backup disimpan"
              >
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Backup</label>
              <input
                v-model="settings.backup_location"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Path lokasi backup"
              >
            </div>
            
            <div class="pt-4">
              <button
                type="submit"
                :disabled="loading"
                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
              >
                <span v-if="loading">Menyimpan...</span>
                <span v-else>Simpan Pengaturan</span>
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

// Reactive data
const loading = ref(false)
const activeTab = ref('backup')
const backupType = ref('')
const restoreType = ref('')
const selectedFile = ref(null)
const fileInput = ref(null)

const tabs = [
  { id: 'backup', name: 'Backup' },
  { id: 'restore', name: 'Restore' },
  { id: 'settings', name: 'Pengaturan' }
]

const backupList = ref([])

const settings = reactive({
  auto_backup_enabled: false,
  backup_frequency: 'weekly',
  backup_retention: 30,
  backup_location: '/var/backups/kesiswaan'
})

// Methods
const loadBackupData = async () => {
  try {
    loading.value = true
    // TODO: Implement API call
    // const response = await api.get('/backup-restore')
    // backupList.value = response.data
    
    // Mock data
    backupList.value = [
      {
        id: 1,
        nama_file: 'backup_full_20241201_143022.zip',
        deskripsi: 'Full backup sistem',
        jenis: 'full',
        ukuran: 52428800, // 50MB
        tanggal: '2024-12-01T14:30:22Z',
        status: 'completed'
      },
      {
        id: 2,
        nama_file: 'backup_db_20241130_090000.sql',
        deskripsi: 'Database backup',
        jenis: 'database',
        ukuran: 10485760, // 10MB
        tanggal: '2024-11-30T09:00:00Z',
        status: 'completed'
      },
      {
        id: 3,
        nama_file: 'backup_full_20241129_180000.zip',
        deskripsi: 'Full backup sistem',
        jenis: 'full',
        ukuran: 52428800, // 50MB
        tanggal: '2024-11-29T18:00:00Z',
        status: 'completed'
      }
    ]
  } catch (error) {
    console.error('Error loading backup data:', error)
  } finally {
    loading.value = false
  }
}

const createFullBackup = async () => {
  try {
    loading.value = true
    backupType.value = 'full'
    // TODO: Implement API call
    // await api.post('/backup-restore/backup', { type: 'full' })
    
    console.log('Creating full backup...')
    // Show success message
    await loadBackupData()
  } catch (error) {
    console.error('Error creating full backup:', error)
    // Show error message
  } finally {
    loading.value = false
    backupType.value = ''
  }
}

const createDatabaseBackup = async () => {
  try {
    loading.value = true
    backupType.value = 'database'
    // TODO: Implement API call
    // await api.post('/backup-restore/backup', { type: 'database' })
    
    console.log('Creating database backup...')
    // Show success message
    await loadBackupData()
  } catch (error) {
    console.error('Error creating database backup:', error)
    // Show error message
  } finally {
    loading.value = false
    backupType.value = ''
  }
}

const downloadBackup = (backup) => {
  // TODO: Implement download functionality
  console.log('Downloading backup:', backup)
}

const deleteBackup = async (id) => {
  if (confirm('Apakah Anda yakin ingin menghapus backup ini?')) {
    try {
      // TODO: Implement API call
      // await api.delete(`/backup-restore/${id}`)
      
      backupList.value = backupList.value.filter(b => b.id !== id)
    } catch (error) {
      console.error('Error deleting backup:', error)
    }
  }
}

const handleFileUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    selectedFile.value = file
  }
}

const removeSelectedFile = () => {
  selectedFile.value = null
  if (fileInput.value) {
    fileInput.value.value = ''
  }
}

const restoreFromFile = async () => {
  if (!selectedFile.value) return
  
  if (confirm('Apakah Anda yakin ingin melakukan restore? Ini akan mengganti semua data yang ada.')) {
    try {
      loading.value = true
      restoreType.value = 'file'
      
      const formData = new FormData()
      formData.append('file', selectedFile.value)
      
      // TODO: Implement API call
      // await api.post('/backup-restore/restore', formData, {
      //   headers: { 'Content-Type': 'multipart/form-data' }
      // })
      
      console.log('Restoring from file...')
      // Show success message
      removeSelectedFile()
    } catch (error) {
      console.error('Error restoring from file:', error)
      // Show error message
    } finally {
      loading.value = false
      restoreType.value = ''
    }
  }
}

const restoreFromBackup = async (backup) => {
  if (confirm('Apakah Anda yakin ingin melakukan restore? Ini akan mengganti semua data yang ada.')) {
    try {
      loading.value = true
      restoreType.value = 'backup'
      
      // TODO: Implement API call
      // await api.post(`/backup-restore/restore/${backup.id}`)
      
      console.log('Restoring from backup:', backup)
      // Show success message
    } catch (error) {
      console.error('Error restoring from backup:', error)
      // Show error message
    } finally {
      loading.value = false
      restoreType.value = ''
    }
  }
}

const saveSettings = async () => {
  try {
    loading.value = true
    // TODO: Implement API call
    // await api.put('/backup-restore/settings', settings)
    
    console.log('Settings saved:', settings)
    // Show success message
  } catch (error) {
    console.error('Error saving settings:', error)
    // Show error message
  } finally {
    loading.value = false
  }
}

const getJenisBadgeClass = (jenis) => {
  const classes = {
    full: 'bg-blue-100 text-blue-800',
    database: 'bg-green-100 text-green-800'
  }
  return classes[jenis] || 'bg-gray-100 text-gray-800'
}

const getJenisText = (jenis) => {
  const texts = {
    full: 'Full Backup',
    database: 'Database Only'
  }
  return texts[jenis] || jenis
}

const getStatusBadgeClass = (status) => {
  const classes = {
    completed: 'bg-green-100 text-green-800',
    processing: 'bg-yellow-100 text-yellow-800',
    failed: 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const getStatusText = (status) => {
  const texts = {
    completed: 'Selesai',
    processing: 'Proses',
    failed: 'Gagal'
  }
  return texts[status] || status
}

const formatFileSize = (bytes) => {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

const formatDate = (dateString) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// Lifecycle
onMounted(() => {
  loadBackupData()
})
</script>

<style scoped>
.backup-restore-view {
  @apply p-6;
}
</style>
