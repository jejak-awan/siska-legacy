<template>
  <div class="profil-sekolah-view">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Profil Sekolah</h1>
      <p class="text-gray-600">Kelola informasi dan identitas sekolah</p>
    </div>

    <!-- Profile Cards -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
      <!-- School Logo & Basic Info -->
      <div class="lg:col-span-2">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <div class="flex items-center space-x-6">
            <!-- School Logo -->
            <div class="flex-shrink-0">
              <div class="w-24 h-24 bg-gray-100 rounded-lg flex items-center justify-center border-2 border-dashed border-gray-300">
                <svg v-if="!schoolProfile.logo" class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <img v-else :src="schoolProfile.logo" :alt="schoolProfile.nama" class="w-full h-full object-cover rounded-lg">
              </div>
            </div>
            
            <!-- Basic Info -->
            <div class="flex-1">
              <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ schoolProfile.nama || 'Nama Sekolah' }}</h2>
              <p class="text-gray-600 mb-1">{{ schoolProfile.npsn || 'NPSN: -' }}</p>
              <p class="text-gray-600 mb-1">{{ schoolProfile.alamat || 'Alamat belum diisi' }}</p>
              <p class="text-gray-600">{{ schoolProfile.telepon || 'Telepon: -' }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Quick Stats -->
      <div class="space-y-4">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                </svg>
              </div>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-gray-500">Total Siswa</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.totalSiswa || 0 }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
              </div>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-gray-500">Total Kelas</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.totalKelas || 0 }}</p>
            </div>
          </div>
        </div>

        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
              </div>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-gray-500">Total Guru</p>
              <p class="text-2xl font-bold text-gray-900">{{ stats.totalGuru || 0 }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Form Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
      <!-- Basic Information -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Informasi Dasar</h3>
        </div>
        <div class="p-6">
          <form @submit.prevent="updateBasicInfo" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama Sekolah</label>
              <input
                v-model="form.nama"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan nama sekolah"
              >
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">NPSN</label>
              <input
                v-model="form.npsn"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan NPSN"
              >
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
              <textarea
                v-model="form.alamat"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan alamat lengkap"
              ></textarea>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Telepon</label>
                <input
                  v-model="form.telepon"
                  type="text"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Masukkan nomor telepon"
                >
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input
                  v-model="form.email"
                  type="email"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                  placeholder="Masukkan email sekolah"
                >
              </div>
            </div>
            
            <div class="pt-4">
              <button
                type="submit"
                :disabled="loading"
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span v-if="loading">Menyimpan...</span>
                <span v-else>Simpan Perubahan</span>
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- School Details -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Detail Sekolah</h3>
        </div>
        <div class="p-6">
          <form @submit.prevent="updateSchoolDetails" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Jenjang Pendidikan</label>
              <select
                v-model="form.jenjang"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="">Pilih jenjang</option>
                <option value="SD">SD (Sekolah Dasar)</option>
                <option value="SMP">SMP (Sekolah Menengah Pertama)</option>
                <option value="SMA">SMA (Sekolah Menengah Atas)</option>
                <option value="SMK">SMK (Sekolah Menengah Kejuruan)</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status Sekolah</label>
              <select
                v-model="form.status"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="">Pilih status</option>
                <option value="Negeri">Negeri</option>
                <option value="Swasta">Swasta</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Akreditasi</label>
              <select
                v-model="form.akreditasi"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="">Pilih akreditasi</option>
                <option value="A">A (Sangat Baik)</option>
                <option value="B">B (Baik)</option>
                <option value="C">C (Cukup)</option>
                <option value="TT">TT (Tidak Terakreditasi)</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Kepala Sekolah</label>
              <input
                v-model="form.kepalaSekolah"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan nama kepala sekolah"
              >
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Visi Sekolah</label>
              <textarea
                v-model="form.visi"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan visi sekolah"
              ></textarea>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Misi Sekolah</label>
              <textarea
                v-model="form.misi"
                rows="4"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan misi sekolah"
              ></textarea>
            </div>
            
            <div class="pt-4">
              <button
                type="submit"
                :disabled="loading"
                class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span v-if="loading">Menyimpan...</span>
                <span v-else>Simpan Detail</span>
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
import { useAuthStore } from '@/stores/auth'
import api from '@/services/api'

const authStore = useAuthStore()

// Reactive data
const loading = ref(false)
const schoolProfile = ref({})
const stats = ref({
  totalSiswa: 0,
  totalKelas: 0,
  totalGuru: 0
})

const form = reactive({
  nama: '',
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
    
    // Populate form
    Object.assign(form, schoolProfile.value)
    
  } catch (error) {
    console.error('Error loading school profile:', error)
    // Show error message
    showNotification('Error', 'Gagal memuat profil sekolah', 'error')
  } finally {
    loading.value = false
  }
}

const loadStats = async () => {
  try {
    // TODO: Implement API call to load stats
    // const response = await api.get('/school/stats')
    // stats.value = response.data
    
    // Mock data for now
    stats.value = {
      totalSiswa: 1250,
      totalKelas: 42,
      totalGuru: 85
    }
  } catch (error) {
    console.error('Error loading stats:', error)
  }
}

const updateBasicInfo = async () => {
  try {
    loading.value = true
    const response = await api.put('/profile-sekolah/basic-info', {
      nama: form.nama,
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

// Lifecycle
onMounted(() => {
  loadSchoolProfile()
  loadStats()
})
</script>

<style scoped>
.profil-sekolah-view {
  @apply p-6;
}
</style>
