<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 transition-opacity" @click="$emit('close')">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
      </div>

      <!-- Modal content -->
      <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
        <div>
          <div class="mt-3 text-center sm:mt-0 sm:text-left">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
              Import Pengguna
            </h3>
            
            <div class="space-y-4">
              <!-- Instructions -->
              <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                  <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <div class="ml-3">
                    <h3 class="text-sm font-medium text-blue-800">
                      Panduan Import
                    </h3>
                    <div class="mt-2 text-sm text-blue-700">
                      <ul class="list-disc list-inside space-y-1">
                        <li>File harus berformat CSV atau Excel (.xlsx, .xls)</li>
                        <li>Maksimal ukuran file 2MB</li>
                        <li>Kolom wajib: username, email, password, role_type</li>
                        <li>Role yang valid: admin, guru, siswa, tendik, bk, wali_kelas, osis, ekstrakurikuler, orang_tua</li>
                      </ul>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Download Template -->
              <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div>
                  <h4 class="text-sm font-medium text-gray-900">Template Import</h4>
                  <p class="text-sm text-gray-500">Download template untuk memudahkan import data</p>
                </div>
                <button 
                  @click="downloadTemplate"
                  class="btn btn-sm btn-outline-primary"
                >
                  <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                  </svg>
                  Download Template
                </button>
              </div>

              <!-- File Upload -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                  Pilih File Import
                </label>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                  <div class="space-y-1 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                      <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    <div class="flex text-sm text-gray-600">
                      <label class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                        <span>Upload file</span>
                        <input 
                          ref="fileInput"
                          type="file" 
                          class="sr-only" 
                          accept=".csv,.xlsx,.xls"
                          @change="handleFileSelect"
                        />
                      </label>
                      <p class="pl-1">atau drag and drop</p>
                    </div>
                    <p class="text-xs text-gray-500">
                      CSV, XLSX, XLS hingga 2MB
                    </p>
                  </div>
                </div>
                
                <!-- Selected File Info -->
                <div v-if="selectedFile" class="mt-3 p-3 bg-green-50 border border-green-200 rounded-md">
                  <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div class="ml-3">
                      <p class="text-sm font-medium text-green-800">
                        {{ selectedFile.name }}
                      </p>
                      <p class="text-sm text-green-600">
                        {{ formatFileSize(selectedFile.size) }}
                      </p>
                    </div>
                    <button 
                      @click="removeFile"
                      class="ml-auto text-green-400 hover:text-green-600"
                    >
                      <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                </div>

                <!-- Error Message -->
                <p v-if="error" class="mt-2 text-sm text-red-600">
                  {{ error }}
                </p>
              </div>

              <!-- Import Options -->
              <div class="space-y-3">
                <h4 class="text-sm font-medium text-gray-900">Opsi Import</h4>
                
                <label class="flex items-center">
                  <input
                    v-model="options.updateExisting"
                    type="checkbox"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                  />
                  <span class="ml-2 text-sm text-gray-700">Update pengguna yang sudah ada</span>
                </label>
                
                <label class="flex items-center">
                  <input
                    v-model="options.skipErrors"
                    type="checkbox"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                  />
                  <span class="ml-2 text-sm text-gray-700">Lewati baris yang error</span>
                </label>
                
                <label class="flex items-center">
                  <input
                    v-model="options.sendNotification"
                    type="checkbox"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                  />
                  <span class="ml-2 text-sm text-gray-700">Kirim notifikasi ke pengguna baru</span>
                </label>
              </div>

              <!-- Progress Bar (when importing) -->
              <div v-if="importing" class="space-y-2">
                <div class="flex items-center justify-between text-sm">
                  <span class="text-gray-600">Mengimport data...</span>
                  <span class="text-gray-900 font-medium">{{ progress }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                  <div 
                    class="bg-blue-600 h-2 rounded-full transition-all duration-300" 
                    :style="{ width: progress + '%' }"
                  ></div>
                </div>
              </div>

              <!-- Form Actions -->
              <div class="mt-6 flex items-center justify-end space-x-3">
                <button
                  type="button"
                  @click="$emit('close')"
                  class="btn btn-secondary"
                  :disabled="importing"
                >
                  Batal
                </button>
                <button
                  @click="importFile"
                  class="btn btn-primary"
                  :disabled="!selectedFile || importing"
                >
                  <svg v-if="importing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ importing ? 'Mengimport...' : 'Import Data' }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import api from '../../services/api'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'imported'])

const selectedFile = ref(null)
const importing = ref(false)
const progress = ref(0)
const error = ref('')
const fileInput = ref(null)

const options = reactive({
  updateExisting: false,
  skipErrors: true,
  sendNotification: false
})

const handleFileSelect = (event) => {
  const file = event.target.files[0]
  if (!file) return
  
  error.value = ''
  
  // Validate file type
  const allowedTypes = ['.csv', '.xlsx', '.xls']
  const fileExtension = '.' + file.name.split('.').pop().toLowerCase()
  
  if (!allowedTypes.includes(fileExtension)) {
    error.value = 'Format file tidak didukung. Gunakan CSV, XLSX, atau XLS.'
    return
  }
  
  // Validate file size (2MB)
  if (file.size > 2 * 1024 * 1024) {
    error.value = 'Ukuran file terlalu besar. Maksimal 2MB.'
    return
  }
  
  selectedFile.value = file
}

const removeFile = () => {
  selectedFile.value = null
  if (fileInput.value) {
    fileInput.value.value = ''
  }
  error.value = ''
}

const downloadTemplate = () => {
  // Create CSV template
  const csvContent = `username,email,password,role_type,status
john_doe,john@example.com,password123,guru,aktif
jane_smith,jane@example.com,password123,siswa,aktif
admin_user,admin@example.com,password123,admin,aktif`
  
  const blob = new Blob([csvContent], { type: 'text/csv' })
  const url = window.URL.createObjectURL(blob)
  const a = document.createElement('a')
  a.href = url
  a.download = 'template_import_users.csv'
  document.body.appendChild(a)
  a.click()
  window.URL.revokeObjectURL(url)
  document.body.removeChild(a)
}

const importFile = async () => {
  if (!selectedFile.value) return
  
  importing.value = true
  progress.value = 0
  error.value = ''
  
  try {
    const formData = new FormData()
    formData.append('file', selectedFile.value)
    formData.append('update_existing', options.updateExisting)
    formData.append('skip_errors', options.skipErrors)
    formData.append('send_notification', options.sendNotification)
    
    // Simulate progress for now (in real implementation, use upload progress)
    const progressInterval = setInterval(() => {
      if (progress.value < 90) {
        progress.value += 10
      }
    }, 200)
    
    const response = await api.post('/users/import', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    
    clearInterval(progressInterval)
    progress.value = 100
    
    // Show success message
    alert(response.data.message || 'Import berhasil dilakukan')
    
    emit('imported')
    
  } catch (error) {
    console.error('Error importing file:', error)
    error.value = error.response?.data?.message || 'Terjadi kesalahan saat import'
  } finally {
    importing.value = false
    progress.value = 0
  }
}

const formatFileSize = (bytes) => {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}
</script>
