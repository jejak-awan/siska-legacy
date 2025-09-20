<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 transition-opacity" @click="$emit('close')">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
      </div>

      <!-- Modal content -->
      <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full sm:p-6">
        <div>
          <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              Detail Siswa
            </h3>
            <button 
              @click="$emit('close')"
              class="text-gray-400 hover:text-gray-600"
            >
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <div v-if="siswa" class="space-y-6">
            <!-- Student Header -->
            <div class="bg-gradient-to-r from-blue-50 to-indigo-50 p-6 rounded-lg">
              <div class="flex items-center">
                <div class="flex-shrink-0 h-20 w-20">
                  <div class="h-20 w-20 rounded-full flex items-center justify-center"
                       :class="siswa.jenis_kelamin === 'L' ? 'bg-blue-100' : 'bg-pink-100'">
                    <span class="font-bold text-2xl"
                          :class="siswa.jenis_kelamin === 'L' ? 'text-blue-600' : 'text-pink-600'">
                      {{ siswa.nama_lengkap?.charAt(0).toUpperCase() }}
                    </span>
                  </div>
                </div>
                <div class="ml-6">
                  <h4 class="text-2xl font-bold text-gray-900">{{ siswa.nama_lengkap }}</h4>
                  <p class="text-lg text-gray-600">{{ siswa.nama_panggilan ? `"${siswa.nama_panggilan}"` : '' }}</p>
                  <div class="flex items-center space-x-4 mt-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                          :class="siswa.jenis_kelamin === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800'">
                      {{ siswa.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                      Angkatan {{ siswa.angkatan }}
                    </span>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium"
                          :class="siswa.user?.status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                      {{ siswa.user?.status === 'aktif' ? 'Aktif' : 'Non Aktif' }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tabs -->
            <div class="border-b border-gray-200">
              <nav class="-mb-px flex space-x-8">
                <button 
                  v-for="tab in tabs" 
                  :key="tab.id"
                  @click="activeTab = tab.id"
                  :class="[
                    'py-2 px-1 border-b-2 font-medium text-sm',
                    activeTab === tab.id 
                      ? 'border-blue-500 text-blue-600' 
                      : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                  ]"
                >
                  {{ tab.name }}
                </button>
              </nav>
            </div>

            <!-- Tab Content -->
            <div class="mt-6">
              <!-- Identitas Tab -->
              <div v-if="activeTab === 'identitas'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                  <div>
                    <label class="text-sm font-medium text-gray-500">NISN</label>
                    <p class="mt-1 text-sm text-gray-900">{{ siswa.nisn || '-' }}</p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">NIS</label>
                    <p class="mt-1 text-sm text-gray-900">{{ siswa.nis || '-' }}</p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">NIK</label>
                    <p class="mt-1 text-sm text-gray-900">{{ siswa.nik || '-' }}</p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">Nomor KK</label>
                    <p class="mt-1 text-sm text-gray-900">{{ siswa.nomor_kk || '-' }}</p>
                  </div>
                </div>
                <div class="space-y-4">
                  <div>
                    <label class="text-sm font-medium text-gray-500">Tempat, Tanggal Lahir</label>
                    <p class="mt-1 text-sm text-gray-900">
                      {{ siswa.tempat_lahir }}, {{ formatDate(siswa.tanggal_lahir) }}
                    </p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">Agama</label>
                    <p class="mt-1 text-sm text-gray-900">{{ siswa.agama || '-' }}</p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">Kewarganegaraan</label>
                    <p class="mt-1 text-sm text-gray-900">{{ siswa.kewarganegaraan || '-' }}</p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">Status Siswa</label>
                    <p class="mt-1 text-sm text-gray-900">
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ siswa.status_siswa }}
                      </span>
                    </p>
                  </div>
                </div>
              </div>

              <!-- Akademik Tab -->
              <div v-if="activeTab === 'akademik'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                  <div>
                    <label class="text-sm font-medium text-gray-500">Kelas</label>
                    <p class="mt-1 text-sm text-gray-900">
                      <span v-if="siswa.kelas_relation" class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        {{ siswa.kelas_relation.nama_kelas }}
                      </span>
                      <span v-else class="text-gray-400">Belum ada kelas</span>
                    </p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">Tingkat</label>
                    <p class="mt-1 text-sm text-gray-900">
                      {{ siswa.kelas_relation?.tingkat ? `Kelas ${siswa.kelas_relation.tingkat}` : '-' }}
                    </p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">Jurusan</label>
                    <p class="mt-1 text-sm text-gray-900">{{ siswa.kelas_relation?.jurusan || '-' }}</p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">Wali Kelas</label>
                    <p class="mt-1 text-sm text-gray-900">
                      {{ siswa.kelas_relation?.wali_kelas?.nama_lengkap || '-' }}
                    </p>
                  </div>
                </div>
                <div class="space-y-4">
                  <div>
                    <label class="text-sm font-medium text-gray-500">Angkatan</label>
                    <p class="mt-1 text-sm text-gray-900">{{ siswa.angkatan }}</p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">Tahun Ajaran</label>
                    <p class="mt-1 text-sm text-gray-900">
                      {{ siswa.tahun_ajaran?.nama || '-' }}
                    </p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">Tanggal Masuk</label>
                    <p class="mt-1 text-sm text-gray-900">
                      {{ formatDate(siswa.tanggal_masuk) }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Kontak Tab -->
              <div v-if="activeTab === 'kontak'" class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                  <div>
                    <label class="text-sm font-medium text-gray-500">Email Sekolah</label>
                    <p class="mt-1 text-sm text-gray-900">{{ siswa.user?.email || '-' }}</p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">Email Pribadi</label>
                    <p class="mt-1 text-sm text-gray-900">{{ siswa.email_pribadi || '-' }}</p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">Nomor HP</label>
                    <p class="mt-1 text-sm text-gray-900">{{ siswa.nomor_hp || '-' }}</p>
                  </div>
                </div>
                <div class="space-y-4">
                  <div>
                    <label class="text-sm font-medium text-gray-500">Alamat Lengkap</label>
                    <p class="mt-1 text-sm text-gray-900">{{ siswa.alamat_lengkap || '-' }}</p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">Kelurahan</label>
                    <p class="mt-1 text-sm text-gray-900">{{ siswa.kelurahan || '-' }}</p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">Kecamatan</label>
                    <p class="mt-1 text-sm text-gray-900">{{ siswa.kecamatan || '-' }}</p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">Kabupaten/Kota</label>
                    <p class="mt-1 text-sm text-gray-900">{{ siswa.kabupaten_kota || '-' }}</p>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-500">Provinsi</label>
                    <p class="mt-1 text-sm text-gray-900">{{ siswa.provinsi || '-' }}</p>
                  </div>
                </div>
              </div>

              <!-- Orang Tua Tab -->
              <div v-if="activeTab === 'orangtua'" class="space-y-6">
                <div v-if="siswa.orang_tua">
                  <!-- Ayah -->
                  <div class="bg-blue-50 p-4 rounded-lg">
                    <h5 class="text-lg font-medium text-blue-900 mb-3">Data Ayah</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <div>
                        <label class="text-sm font-medium text-gray-500">Nama</label>
                        <p class="mt-1 text-sm text-gray-900">{{ siswa.orang_tua.nama_ayah || '-' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-500">NIK</label>
                        <p class="mt-1 text-sm text-gray-900">{{ siswa.orang_tua.nik_ayah || '-' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-500">Pendidikan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ siswa.orang_tua.pendidikan_ayah || '-' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-500">Pekerjaan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ siswa.orang_tua.pekerjaan_ayah || '-' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-500">Penghasilan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(siswa.orang_tua.penghasilan_ayah) }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-500">No. HP</label>
                        <p class="mt-1 text-sm text-gray-900">{{ siswa.orang_tua.hp_ayah || '-' }}</p>
                      </div>
                    </div>
                  </div>

                  <!-- Ibu -->
                  <div class="bg-pink-50 p-4 rounded-lg">
                    <h5 class="text-lg font-medium text-pink-900 mb-3">Data Ibu</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <div>
                        <label class="text-sm font-medium text-gray-500">Nama</label>
                        <p class="mt-1 text-sm text-gray-900">{{ siswa.orang_tua.nama_ibu || '-' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-500">NIK</label>
                        <p class="mt-1 text-sm text-gray-900">{{ siswa.orang_tua.nik_ibu || '-' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-500">Pendidikan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ siswa.orang_tua.pendidikan_ibu || '-' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-500">Pekerjaan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ siswa.orang_tua.pekerjaan_ibu || '-' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-500">Penghasilan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ formatCurrency(siswa.orang_tua.penghasilan_ibu) }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-500">No. HP</label>
                        <p class="mt-1 text-sm text-gray-900">{{ siswa.orang_tua.hp_ibu || '-' }}</p>
                      </div>
                    </div>
                  </div>

                  <!-- Wali (if exists) -->
                  <div v-if="siswa.orang_tua.nama_wali" class="bg-green-50 p-4 rounded-lg">
                    <h5 class="text-lg font-medium text-green-900 mb-3">Data Wali</h5>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                      <div>
                        <label class="text-sm font-medium text-gray-500">Nama</label>
                        <p class="mt-1 text-sm text-gray-900">{{ siswa.orang_tua.nama_wali || '-' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-500">NIK</label>
                        <p class="mt-1 text-sm text-gray-900">{{ siswa.orang_tua.nik_wali || '-' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-500">Hubungan</label>
                        <p class="mt-1 text-sm text-gray-900">{{ siswa.orang_tua.hubungan_wali || '-' }}</p>
                      </div>
                      <div>
                        <label class="text-sm font-medium text-gray-500">No. HP</label>
                        <p class="mt-1 text-sm text-gray-900">{{ siswa.orang_tua.hp_wali || '-' }}</p>
                      </div>
                    </div>
                  </div>
                </div>
                <div v-else class="text-center py-8">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                  </svg>
                  <p class="mt-2 text-sm text-gray-500">Data orang tua belum tersedia</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Footer Actions -->
          <div class="mt-8 flex items-center justify-end space-x-3">
            <button
              @click="$emit('close')"
              class="btn btn-secondary"
            >
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  siswa: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close'])

const activeTab = ref('identitas')

const tabs = [
  { id: 'identitas', name: 'Identitas' },
  { id: 'akademik', name: 'Akademik' },
  { id: 'kontak', name: 'Kontak' },
  { id: 'orangtua', name: 'Orang Tua' }
]

// Utility functions
const formatDate = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}

const formatCurrency = (amount) => {
  if (!amount) return '-'
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    minimumFractionDigits: 0
  }).format(amount)
}
</script>
