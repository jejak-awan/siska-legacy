<template>
  <div class="p-6">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900 mb-2">Profil Sekolah</h1>
      <p class="text-gray-600">Kelola informasi profil, visi misi, dan struktur organisasi sekolah</p>
    </div>

    <!-- Loading State -->
    <div v-if="loading" class="flex justify-center items-center py-12">
      <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
      <span class="ml-2 text-gray-600">Memuat data sekolah...</span>
    </div>

    <!-- Main Content -->
    <div v-else class="space-y-6">
      <!-- School Profile Overview -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center space-x-6">
            <!-- School Logo -->
            <div class="flex-shrink-0">
            <div 
              class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300 cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-colors"
              @click="triggerLogoUpload"
              @dragover.prevent
              @drop.prevent="handleLogoDrop"
            >
              <div v-if="logoUploading" class="flex flex-col items-center">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
                <span class="text-xs text-gray-500 mt-1">Uploading...</span>
              </div>
              <div v-else-if="!schoolProfile.logo && !logoPreview" class="flex flex-col items-center">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <span class="text-xs text-gray-500 mt-1">Upload Logo</span>
              </div>
              <div v-else class="relative group">
                <img 
                  :src="logoPreview || schoolProfile.logo_url || (schoolProfile.logo ? `/storage/${schoolProfile.logo}` : '')" 
                  :alt="schoolProfile.nama" 
                  class="w-full h-full object-cover rounded-lg"
                >
                <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                  <button 
                    @click.stop="triggerLogoUpload"
                    class="text-white text-xs bg-blue-600 px-2 py-1 rounded hover:bg-blue-700"
                  >
                    Change
                  </button>
                </div>
              </div>
            </div>
            <input 
              ref="logoInput"
              type="file" 
              accept="image/*" 
              @change="handleLogoUpload"
              class="hidden"
            >
            <p class="text-xs text-gray-500 mt-1 text-center">Klik atau seret untuk mengunggah</p>
            <p class="text-xs text-gray-400 mt-1 text-center">Maksimal 2MB • JPG, PNG, GIF</p>
            <p class="text-xs text-gray-400 text-center">Disarankan: 200x200px</p>
            </div>
            
            <!-- Basic Info -->
            <div class="flex-1">
              <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ schoolProfile.nama || 'Nama Sekolah' }}</h2>
              <p v-if="schoolProfile.slogan" class="text-lg text-blue-600 font-medium mb-3 italic">"{{ schoolProfile.slogan }}"</p>
              <div class="space-y-1 text-sm text-gray-600">
                <p><span class="font-medium">NPSN:</span> {{ schoolProfile.npsn || '-' }}</p>
                <p><span class="font-medium">Alamat:</span> {{ schoolProfile.alamat || '-' }}</p>
                <p><span class="font-medium">Telepon:</span> {{ schoolProfile.telepon || '-' }}</p>
                <p><span class="font-medium">Email:</span> {{ schoolProfile.email || '-' }}</p>
                <p><span class="font-medium">Website:</span> {{ schoolProfile.website || '-' }}</p>
                <p><span class="font-medium">Jenjang:</span> {{ schoolProfile.jenjang || '-' }}</p>
                <p><span class="font-medium">Status:</span> {{ schoolProfile.status || '-' }}</p>
                <p><span class="font-medium">Akreditasi:</span> {{ schoolProfile.akreditasi || '-' }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="border-b border-gray-200">
          <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
            <button
              v-for="tab in tabs"
              :key="tab.id"
              @click="activeTab = tab.id"
              :class="[
                activeTab === tab.id
                  ? 'border-blue-500 text-blue-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
              ]"
            >
              {{ tab.name }}
            </button>
          </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
          <!-- Profile Sekolah Tab -->
          <div v-if="activeTab === 'profile'" class="space-y-6">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-semibold text-gray-900">Informasi Dasar Sekolah</h3>
              <button
                @click="updateBasicInfo"
                :disabled="loading"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
              >
                <span v-if="loading">Menyimpan...</span>
                <span v-else>Simpan Perubahan</span>
              </button>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Nama Sekolah *</label>
                  <input 
                    v-model="form.nama"
                    type="text" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    placeholder="Masukkan nama sekolah"
                    required
                  >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Slogan/Jargon</label>
                  <input 
                    v-model="form.slogan"
                    type="text" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    placeholder="Masukkan slogan atau jargon sekolah"
                  >
                </div>
            <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">NPSN</label>
              <input
                v-model="form.npsn"
                type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                placeholder="Masukkan NPSN"
              >
            </div>
            <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
              <textarea
                v-model="form.alamat"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                rows="3"
                    placeholder="Masukkan alamat sekolah"
              ></textarea>
            </div>
              </div>
              <div class="space-y-4">
              <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                <input
                  v-model="form.telepon"
                  type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                  placeholder="Masukkan nomor telepon"
                >
              </div>
              <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input
                  v-model="form.email"
                  type="email"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                  placeholder="Masukkan email sekolah"
                >
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                  <input 
                    v-model="form.website"
                    type="url" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                    placeholder="Masukkan website sekolah"
                  >
                </div>
                <div class="grid grid-cols-2 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenjang *</label>
                    <select 
                      v-model="form.jenjang"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                      required
                    >
                      <option value="">Pilih Jenjang</option>
                      <option value="SD">SD</option>
                      <option value="SMP">SMP</option>
                      <option value="SMA">SMA</option>
                      <option value="SMK">SMK</option>
                    </select>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                    <select 
                      v-model="form.status"
                      class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                      required
                    >
                      <option value="">Pilih Status</option>
                      <option value="Negeri">Negeri</option>
                      <option value="Swasta">Swasta</option>
                    </select>
                  </div>
                </div>
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Akreditasi</label>
                  <select 
                    v-model="form.akreditasi"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                  >
                    <option value="">Pilih Akreditasi</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="C">C</option>
                    <option value="TT">TT</option>
                  </select>
                </div>
              </div>
              </div>
            </div>
            
          <!-- Visi Misi & Program Unggulan Tab -->
          <div v-if="activeTab === 'visi-misi'" class="space-y-6">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-semibold text-gray-900">Visi & Misi</h3>
              <button
                @click="updateSchoolDetails"
                :disabled="loading"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
              >
                <span v-if="loading">Menyimpan...</span>
                <span v-else>Simpan Perubahan</span>
              </button>
            </div>
            
            <div class="space-y-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sejarah Sekolah</label>
                <TinyMCEEditor 
                  v-model="form.sejarah"
                  placeholder="Masukkan sejarah sekolah"
                  :height="180"
                />
        </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Prestasi Sekolah</label>
                <TinyMCEEditor 
                  v-model="form.prestasi"
                  placeholder="Masukkan prestasi sekolah"
                  :height="180"
                />
      </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Visi</label>
                <TinyMCEEditor 
                  v-model="form.visi"
                  placeholder="Masukkan visi sekolah"
                  :height="120"
                />
        </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Misi</label>
                <TinyMCEEditor 
                  v-model="form.misi"
                  placeholder="Masukkan misi sekolah"
                  :height="180"
                />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Tujuan</label>
                <TinyMCEEditor 
                  v-model="form.tujuan"
                  placeholder="Masukkan tujuan sekolah"
                  :height="120"
                />
              </div>
            </div>
            </div>
            
          <!-- Struktur Organisasi Tab -->
          <div v-if="activeTab === 'struktur'" class="space-y-6">
            <!-- Kepala Sekolah Section -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-4">Kepala Sekolah</h3>
              <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kepala Sekolah</label>
              <input
                    v-model="form.kepala_sekolah"
                type="text"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" 
                placeholder="Masukkan nama kepala sekolah"
              >
            </div>
            <div>
                  <label class="block text-sm font-medium text-gray-700 mb-2">Foto Kepala Sekolah</label>
                  <div class="flex items-center space-x-4">
                    <div 
                      class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300 cursor-pointer hover:border-blue-400 hover:bg-blue-50 transition-colors"
                      @click="triggerHeadmasterUpload"
                      @dragover.prevent
                      @drop.prevent="handleHeadmasterDrop"
                    >
                      <div v-if="headmasterUploading" class="flex flex-col items-center">
                        <div class="animate-spin rounded-full h-4 w-4 border-b-2 border-blue-600"></div>
                      </div>
                      <div v-else-if="!schoolProfile.foto_kepala_sekolah && !headmasterPreview" class="flex flex-col items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                      </div>
                      <div v-else class="relative group">
                        <img 
                          :src="headmasterPreview || schoolProfile.foto_kepala_sekolah_url || (schoolProfile.foto_kepala_sekolah ? `/storage/${schoolProfile.foto_kepala_sekolah}` : '')" 
                          :alt="form.kepala_sekolah" 
                          class="w-full h-full object-cover rounded-lg"
                        >
                        <div class="absolute inset-0 bg-black bg-opacity-50 rounded-lg flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                          <button 
                            @click.stop="triggerHeadmasterUpload"
                            class="text-white text-xs bg-blue-600 px-2 py-1 rounded hover:bg-blue-700"
                          >
                            Change
                          </button>
                        </div>
                      </div>
                    </div>
                    <div class="flex-1">
                      <p class="text-sm text-gray-600">Unggah foto kepala sekolah</p>
                      <p class="text-xs text-gray-500">Format: JPG, PNG, GIF (Maksimal 2MB)</p>
                      <p class="text-xs text-gray-400">Disarankan: 150x150px</p>
                    </div>
                  </div>
                  <input 
                    ref="headmasterInput"
                    type="file" 
                    accept="image/*" 
                    @change="handleHeadmasterUpload"
                    class="hidden"
                  >
                </div>
              </div>
            </div>
            
            <!-- Struktur Organisasi Management -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
              <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Struktur Organisasi</h3>
                <button 
                  @click="addStructurePosition"
                  class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors"
                >
                  <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                  Tambah Jabatan
                </button>
              </div>

              <!-- Structure Positions Table -->
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="(position, index) in structurePositions" :key="index">
                      <td class="px-6 py-4 whitespace-nowrap">
                        <input 
                          v-model="position.jabatan"
                          type="text"
                          class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                          placeholder="Nama jabatan"
                        >
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <input 
                          v-model="position.nama"
                          type="text"
                          class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                          placeholder="Nama lengkap"
                        >
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <select 
                          v-model="position.level"
                          class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                        >
                          <option value="1">Level 1 (Pimpinan)</option>
                          <option value="2">Level 2 (Wakil)</option>
                          <option value="3">Level 3 (Kepala Bagian)</option>
                          <option value="4">Level 4 (Staff)</option>
                        </select>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap">
                        <select 
                          v-model="position.status"
                          class="w-full px-2 py-1 border border-gray-300 rounded text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                        >
                          <option value="aktif">Aktif</option>
                          <option value="tidak_aktif">Tidak Aktif</option>
                        </select>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button 
                          @click="removeStructurePosition(index)"
                          class="text-red-600 hover:text-red-900"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                          </svg>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <!-- Auto Design Preview -->
              <div class="mt-6">
                <div class="flex items-center justify-between mb-4">
                  <h4 class="text-md font-medium text-gray-900">Preview Struktur Organisasi</h4>
                  <button 
                    @click="generateStructurePreview"
                    class="px-3 py-1 bg-green-600 text-white rounded text-sm hover:bg-green-700 transition-colors"
                  >
                    Generate Preview
                  </button>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 min-h-[200px]">
                  <div v-if="structurePreview" v-html="structurePreview" class="structure-preview"></div>
                  <div v-else class="text-center text-gray-500 py-8">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                    <p>Klik "Generate Preview" untuk melihat struktur organisasi</p>
                  </div>
                </div>
            </div>
            
              <!-- Save Structure Button -->
              <div class="mt-6 flex justify-end">
              <button
                  @click="saveStructure"
                :disabled="loading"
                  class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span v-if="loading">Menyimpan...</span>
                  <span v-else>Simpan Struktur</span>
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
import { ref, reactive, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import api from '@/services/api'
import TiptapEditor from '@/components/editor/TiptapEditor.vue'

const authStore = useAuthStore()

// Reactive data
const loading = ref(false)
const activeTab = ref('profile')
const schoolProfile = ref({})
const logoUploading = ref(false)
const headmasterUploading = ref(false)
const logoPreview = ref(null)
const headmasterPreview = ref(null)
const logoInput = ref(null)
const headmasterInput = ref(null)
const structurePositions = ref([])
const structurePreview = ref('')

const form = reactive({
  nama: '',
  slogan: '',
  npsn: '',
  alamat: '',
  telepon: '',
  email: '',
  website: '',
  jenjang: '',
  status: '',
  akreditasi: '',
  kepala_sekolah: '',
  visi: '',
  misi: '',
  tujuan: '',
  sejarah: '',
  prestasi: '',
  kontakLain: {},
  sosialMedia: {}
})

const tabs = [
  { id: 'profile', name: 'Profile Sekolah' },
  { id: 'visi-misi', name: 'Visi & Misi' },
  { id: 'struktur', name: 'Struktur Organisasi' }
]

// Methods
const showNotification = (title, message, type = 'info') => {
  // Simple browser notification
  if ('Notification' in window && Notification.permission === 'granted') {
    new Notification(title, {
      body: message,
      icon: '/favicon.ico'
    })
  }
  
  // Console log for debugging
  console.log(`${type.toUpperCase()}: ${title} - ${message}`)
}

const loadSchoolProfile = async () => {
  try {
    loading.value = true
    const response = await api.get('/profile-sekolah')
    schoolProfile.value = response.data.data
    
    // Populate form with existing data
    Object.assign(form, {
      nama: schoolProfile.value.nama || '',
      slogan: schoolProfile.value.slogan || '',
      npsn: schoolProfile.value.npsn || '',
      alamat: schoolProfile.value.alamat || '',
      telepon: schoolProfile.value.telepon || '',
      email: schoolProfile.value.email || '',
      website: schoolProfile.value.website || '',
      jenjang: schoolProfile.value.jenjang || '',
      status: schoolProfile.value.status || '',
      akreditasi: schoolProfile.value.akreditasi || '',
      kepala_sekolah: schoolProfile.value.kepala_sekolah || '',
      visi: schoolProfile.value.visi || '',
      misi: schoolProfile.value.misi || '',
      tujuan: schoolProfile.value.tujuan || '',
      sejarah: schoolProfile.value.sejarah || '',
      prestasi: schoolProfile.value.prestasi || '',
      kontakLain: schoolProfile.value.kontak_lain || {},
      sosialMedia: schoolProfile.value.sosial_media || {}
    })
    
    // Load struktur organisasi if exists
    if (schoolProfile.value.struktur_organisasi) {
      structurePositions.value = schoolProfile.value.struktur_organisasi
    }
    
    // Clear previews when loading existing data
    logoPreview.value = null
    headmasterPreview.value = null
    
  } catch (error) {
    console.error('Error loading school profile:', error)
    showNotification('Error', 'Gagal memuat profil sekolah', 'error')
  } finally {
    loading.value = false
  }
}

const updateBasicInfo = async () => {
  try {
    loading.value = true
    const response = await api.put('/profile-sekolah/basic-info', {
      nama: form.nama,
      slogan: form.slogan,
      npsn: form.npsn,
      alamat: form.alamat,
      telepon: form.telepon,
      email: form.email,
      website: form.website,
      jenjang: form.jenjang,
      status: form.status,
      akreditasi: form.akreditasi
    })
    
    schoolProfile.value = response.data.data
    showNotification('Berhasil', 'Informasi dasar sekolah berhasil diperbarui', 'success')
  } catch (error) {
    console.error('Error updating basic info:', error)
    if (error.response?.status === 422) {
      showNotification('Validasi Error', 'Mohon periksa kembali data yang diisi', 'error')
    } else {
      showNotification('Error', 'Gagal memperbarui informasi dasar sekolah', 'error')
    }
  } finally {
    loading.value = false
  }
}

const updateSchoolDetails = async () => {
  try {
    loading.value = true
    const response = await api.put('/profile-sekolah/school-details', {
      kepala_sekolah: form.kepala_sekolah,
      visi: form.visi,
      misi: form.misi,
      tujuan: form.tujuan,
      sejarah: form.sejarah,
      prestasi: form.prestasi,
      kontak_lain: form.kontakLain,
      sosial_media: form.sosialMedia
    })
    
    schoolProfile.value = response.data.data
    showNotification('Berhasil', 'Detail sekolah berhasil diperbarui', 'success')
  } catch (error) {
    console.error('Error updating school details:', error)
    if (error.response?.status === 422) {
      showNotification('Validasi Error', 'Mohon periksa kembali data yang diisi', 'error')
    } else {
      showNotification('Error', 'Gagal memperbarui detail sekolah', 'error')
    }
  } finally {
    loading.value = false
  }
}

// Logo Upload Methods
const triggerLogoUpload = () => {
  logoInput.value?.click()
}

const handleLogoUpload = async (event) => {
  const file = event.target.files[0]
  if (file) {
    await uploadLogo(file)
  }
}

const handleLogoDrop = async (event) => {
  const file = event.dataTransfer.files[0]
  if (file && file.type.startsWith('image/')) {
    await uploadLogo(file)
  }
}

const uploadLogo = async (file) => {
  // Validate file size (2MB max)
  if (file.size > 2 * 1024 * 1024) {
    showNotification('Error', 'Ukuran file maksimal 2MB', 'error')
    return
  }

  // Validate file type
  if (!file.type.startsWith('image/')) {
    showNotification('Error', 'File harus berupa gambar', 'error')
    return
  }

  try {
    logoUploading.value = true
    
    // Create preview
    const reader = new FileReader()
    reader.onload = (e) => {
      logoPreview.value = e.target.result
    }
    reader.readAsDataURL(file)

    // Upload to server
    const formData = new FormData()
    formData.append('logo', file)
    
    const response = await api.post('/profile-sekolah/upload-logo', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    
    schoolProfile.value = response.data.data
    logoPreview.value = null // Clear preview since we now have the real URL
    showNotification('Berhasil', 'Logo sekolah berhasil diunggah', 'success')
    
  } catch (error) {
    console.error('Error uploading logo:', error)
    showNotification('Error', 'Gagal mengupload logo', 'error')
    logoPreview.value = null
  } finally {
    logoUploading.value = false
  }
}

// Headmaster Photo Upload Methods
const triggerHeadmasterUpload = () => {
  headmasterInput.value?.click()
}

const handleHeadmasterUpload = async (event) => {
  const file = event.target.files[0]
  if (file) {
    await uploadHeadmasterPhoto(file)
  }
}

const handleHeadmasterDrop = async (event) => {
  const file = event.dataTransfer.files[0]
  if (file && file.type.startsWith('image/')) {
    await uploadHeadmasterPhoto(file)
  }
}

const uploadHeadmasterPhoto = async (file) => {
  // Validate file size (2MB max)
  if (file.size > 2 * 1024 * 1024) {
    showNotification('Error', 'Ukuran file maksimal 2MB', 'error')
    return
  }

  // Validate file type
  if (!file.type.startsWith('image/')) {
    showNotification('Error', 'File harus berupa gambar', 'error')
    return
  }

  try {
    headmasterUploading.value = true
    
    // Create preview
    const reader = new FileReader()
    reader.onload = (e) => {
      headmasterPreview.value = e.target.result
    }
    reader.readAsDataURL(file)

    // Upload to server
    const formData = new FormData()
    formData.append('foto_kepala_sekolah', file)
    
    const response = await api.post('/profile-sekolah/upload-headmaster', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })
    
    schoolProfile.value = response.data.data
    headmasterPreview.value = null // Clear preview since we now have the real URL
    showNotification('Berhasil', 'Foto kepala sekolah berhasil diunggah', 'success')
    
  } catch (error) {
    console.error('Error uploading headmaster photo:', error)
    showNotification('Error', 'Gagal mengupload foto kepala sekolah', 'error')
    headmasterPreview.value = null
  } finally {
    headmasterUploading.value = false
  }
}

// Structure Organization Methods
const addStructurePosition = () => {
  structurePositions.value.push({
    jabatan: '',
    nama: '',
    level: '4',
    status: 'aktif'
  })
}

const removeStructurePosition = (index) => {
  structurePositions.value.splice(index, 1)
  generateStructurePreview() // Auto update preview
}

const generateStructurePreview = () => {
  if (structurePositions.value.length === 0) {
    structurePreview.value = ''
    return
  }

  // Group by level
  const groupedByLevel = structurePositions.value
    .filter(pos => pos.jabatan && pos.nama && pos.status === 'aktif')
    .reduce((acc, pos) => {
      if (!acc[pos.level]) acc[pos.level] = []
      acc[pos.level].push(pos)
      return acc
    }, {})

  // Generate HTML structure
  let html = '<div class="structure-org">'
  
  // Level 1 (Pimpinan) - Center top
  if (groupedByLevel['1']) {
    html += '<div class="level-1 flex justify-center mb-8">'
    groupedByLevel['1'].forEach(pos => {
      html += `
        <div class="position-card bg-blue-100 border-2 border-blue-300 rounded-lg p-4 mx-2 text-center min-w-[200px]">
          <div class="font-bold text-blue-800">${pos.jabatan}</div>
          <div class="text-sm text-blue-600 mt-1">${pos.nama}</div>
        </div>
      `
    })
    html += '</div>'
  }

  // Level 2 (Wakil) - Below level 1
  if (groupedByLevel['2']) {
    html += '<div class="level-2 flex justify-center mb-6">'
    groupedByLevel['2'].forEach(pos => {
      html += `
        <div class="position-card bg-green-100 border-2 border-green-300 rounded-lg p-3 mx-2 text-center min-w-[180px]">
          <div class="font-semibold text-green-800">${pos.jabatan}</div>
          <div class="text-sm text-green-600 mt-1">${pos.nama}</div>
        </div>
      `
    })
    html += '</div>'
  }

  // Level 3 (Kepala Bagian) - Below level 2
  if (groupedByLevel['3']) {
    html += '<div class="level-3 flex justify-center mb-4">'
    groupedByLevel['3'].forEach(pos => {
      html += `
        <div class="position-card bg-yellow-100 border-2 border-yellow-300 rounded-lg p-3 mx-2 text-center min-w-[160px]">
          <div class="font-medium text-yellow-800">${pos.jabatan}</div>
          <div class="text-sm text-yellow-600 mt-1">${pos.nama}</div>
        </div>
      `
    })
    html += '</div>'
  }

  // Level 4 (Staff) - Bottom
  if (groupedByLevel['4']) {
    html += '<div class="level-4 flex justify-center flex-wrap">'
    groupedByLevel['4'].forEach(pos => {
      html += `
        <div class="position-card bg-gray-100 border-2 border-gray-300 rounded-lg p-2 mx-1 mb-2 text-center min-w-[140px]">
          <div class="font-medium text-gray-800 text-sm">${pos.jabatan}</div>
          <div class="text-xs text-gray-600 mt-1">${pos.nama}</div>
        </div>
      `
    })
    html += '</div>'
  }

  html += '</div>'
  structurePreview.value = html
}

const saveStructure = async () => {
  try {
    loading.value = true
    
    const response = await api.put('/profile-sekolah/school-details', {
      kepala_sekolah: form.kepala_sekolah,
      visi: form.visi,
      misi: form.misi,
      tujuan: form.tujuan,
      sejarah: form.sejarah,
      prestasi: form.prestasi,
      kontak_lain: form.kontakLain,
      sosial_media: form.sosialMedia,
      struktur_organisasi: structurePositions.value
    })
    
    schoolProfile.value = response.data.data
    showNotification('Berhasil', 'Struktur organisasi berhasil disimpan', 'success')
  } catch (error) {
    console.error('Error saving structure:', error)
    if (error.response?.status === 422) {
      showNotification('Validasi Error', 'Mohon periksa kembali data yang diisi', 'error')
    } else {
      showNotification('Error', 'Gagal menyimpan struktur organisasi', 'error')
    }
  } finally {
    loading.value = false
  }
}

// Lifecycle
onMounted(() => {
  loadSchoolProfile()
})
</script>
