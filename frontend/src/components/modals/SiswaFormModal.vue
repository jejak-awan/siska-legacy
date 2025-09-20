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
          <div class="mt-3 text-center sm:mt-0 sm:text-left">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
              {{ siswa ? 'Edit Siswa' : 'Tambah Siswa Baru' }}
            </h3>
            
            <form @submit.prevent="submitForm" class="space-y-6">
              <!-- Account Info Section -->
              <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="text-md font-medium text-gray-900 mb-3">Informasi Akun</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <!-- Username -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Username <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="form.username"
                      type="text"
                      required
                      class="form-input"
                      :class="{ 'border-red-500': errors.username }"
                      placeholder="Username siswa"
                      :disabled="!!siswa"
                    />
                    <p v-if="errors.username" class="mt-1 text-sm text-red-600">
                      {{ errors.username[0] }}
                    </p>
                  </div>

                  <!-- Email -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Email <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="form.email"
                      type="email"
                      required
                      class="form-input"
                      :class="{ 'border-red-500': errors.email }"
                      placeholder="Email siswa"
                      :disabled="!!siswa"
                    />
                    <p v-if="errors.email" class="mt-1 text-sm text-red-600">
                      {{ errors.email[0] }}
                    </p>
                  </div>

                  <!-- Password (for new siswa) -->
                  <div v-if="!siswa">
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Password <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="form.password"
                      type="password"
                      required
                      class="form-input"
                      :class="{ 'border-red-500': errors.password }"
                      placeholder="Password siswa"
                    />
                    <p v-if="errors.password" class="mt-1 text-sm text-red-600">
                      {{ errors.password[0] }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Student Identity Section -->
              <div class="bg-blue-50 p-4 rounded-lg">
                <h4 class="text-md font-medium text-gray-900 mb-3">Identitas Siswa</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <!-- NISN -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      NISN <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="form.nisn"
                      type="text"
                      required
                      maxlength="10"
                      class="form-input"
                      :class="{ 'border-red-500': errors.nisn }"
                      placeholder="10 digit NISN"
                    />
                    <p v-if="errors.nisn" class="mt-1 text-sm text-red-600">
                      {{ errors.nisn[0] }}
                    </p>
                  </div>

                  <!-- NIS -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      NIS <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="form.nis"
                      type="text"
                      required
                      class="form-input"
                      :class="{ 'border-red-500': errors.nis }"
                      placeholder="Nomor Induk Siswa"
                    />
                    <p v-if="errors.nis" class="mt-1 text-sm text-red-600">
                      {{ errors.nis[0] }}
                    </p>
                  </div>

                  <!-- NIK -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      NIK <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="form.nik"
                      type="text"
                      required
                      maxlength="16"
                      class="form-input"
                      :class="{ 'border-red-500': errors.nik }"
                      placeholder="16 digit NIK"
                    />
                    <p v-if="errors.nik" class="mt-1 text-sm text-red-600">
                      {{ errors.nik[0] }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Personal Info Section -->
              <div class="bg-green-50 p-4 rounded-lg">
                <h4 class="text-md font-medium text-gray-900 mb-3">Data Pribadi</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <!-- Nama Lengkap -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="form.nama_lengkap"
                      type="text"
                      required
                      class="form-input"
                      :class="{ 'border-red-500': errors.nama_lengkap }"
                      placeholder="Nama lengkap siswa"
                    />
                    <p v-if="errors.nama_lengkap" class="mt-1 text-sm text-red-600">
                      {{ errors.nama_lengkap[0] }}
                    </p>
                  </div>

                  <!-- Nama Panggilan -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Nama Panggilan
                    </label>
                    <input
                      v-model="form.nama_panggilan"
                      type="text"
                      class="form-input"
                      placeholder="Nama panggilan"
                    />
                  </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                  <!-- Jenis Kelamin -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Jenis Kelamin <span class="text-red-500">*</span>
                    </label>
                    <select
                      v-model="form.jenis_kelamin"
                      required
                      class="form-select"
                      :class="{ 'border-red-500': errors.jenis_kelamin }"
                    >
                      <option value="">Pilih Jenis Kelamin</option>
                      <option value="L">Laki-laki</option>
                      <option value="P">Perempuan</option>
                    </select>
                    <p v-if="errors.jenis_kelamin" class="mt-1 text-sm text-red-600">
                      {{ errors.jenis_kelamin[0] }}
                    </p>
                  </div>

                  <!-- Tempat Lahir -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Tempat Lahir <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="form.tempat_lahir"
                      type="text"
                      required
                      class="form-input"
                      :class="{ 'border-red-500': errors.tempat_lahir }"
                      placeholder="Tempat lahir"
                    />
                    <p v-if="errors.tempat_lahir" class="mt-1 text-sm text-red-600">
                      {{ errors.tempat_lahir[0] }}
                    </p>
                  </div>

                  <!-- Tanggal Lahir -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Tanggal Lahir <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="form.tanggal_lahir"
                      type="date"
                      required
                      class="form-input"
                      :class="{ 'border-red-500': errors.tanggal_lahir }"
                    />
                    <p v-if="errors.tanggal_lahir" class="mt-1 text-sm text-red-600">
                      {{ errors.tanggal_lahir[0] }}
                    </p>
                  </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                  <!-- Agama -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Agama <span class="text-red-500">*</span>
                    </label>
                    <select
                      v-model="form.agama"
                      required
                      class="form-select"
                      :class="{ 'border-red-500': errors.agama }"
                    >
                      <option value="">Pilih Agama</option>
                      <option value="Islam">Islam</option>
                      <option value="Kristen">Kristen</option>
                      <option value="Katolik">Katolik</option>
                      <option value="Hindu">Hindu</option>
                      <option value="Buddha">Buddha</option>
                      <option value="Konghucu">Konghucu</option>
                    </select>
                    <p v-if="errors.agama" class="mt-1 text-sm text-red-600">
                      {{ errors.agama[0] }}
                    </p>
                  </div>

                  <!-- Kewarganegaraan -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Kewarganegaraan <span class="text-red-500">*</span>
                    </label>
                    <select
                      v-model="form.kewarganegaraan"
                      required
                      class="form-select"
                      :class="{ 'border-red-500': errors.kewarganegaraan }"
                    >
                      <option value="">Pilih Kewarganegaraan</option>
                      <option value="WNI">WNI</option>
                      <option value="WNA">WNA</option>
                    </select>
                    <p v-if="errors.kewarganegaraan" class="mt-1 text-sm text-red-600">
                      {{ errors.kewarganegaraan[0] }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Academic Info Section -->
              <div class="bg-purple-50 p-4 rounded-lg">
                <h4 class="text-md font-medium text-gray-900 mb-3">Informasi Akademik</h4>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <!-- Kelas -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Kelas
                    </label>
                    <select
                      v-model="form.kelas_id"
                      class="form-select"
                      :class="{ 'border-red-500': errors.kelas_id }"
                    >
                      <option value="">Pilih Kelas</option>
                      <option v-for="kelas in kelasList" :key="kelas.id" :value="kelas.id">
                        {{ kelas.nama_kelas }}
                      </option>
                    </select>
                    <p v-if="errors.kelas_id" class="mt-1 text-sm text-red-600">
                      {{ errors.kelas_id[0] }}
                    </p>
                  </div>

                  <!-- Angkatan -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Angkatan <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="form.angkatan"
                      type="text"
                      required
                      class="form-input"
                      :class="{ 'border-red-500': errors.angkatan }"
                      placeholder="Tahun angkatan (misal: 2024)"
                    />
                    <p v-if="errors.angkatan" class="mt-1 text-sm text-red-600">
                      {{ errors.angkatan[0] }}
                    </p>
                  </div>

                  <!-- Status Siswa -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Status Siswa <span class="text-red-500">*</span>
                    </label>
                    <select
                      v-model="form.status_siswa"
                      required
                      class="form-select"
                      :class="{ 'border-red-500': errors.status_siswa }"
                    >
                      <option value="">Pilih Status</option>
                      <option value="Aktif">Aktif</option>
                      <option value="Lulus">Lulus</option>
                      <option value="Pindah">Pindah</option>
                      <option value="Keluar">Keluar</option>
                      <option value="Mengundurkan Diri">Mengundurkan Diri</option>
                    </select>
                    <p v-if="errors.status_siswa" class="mt-1 text-sm text-red-600">
                      {{ errors.status_siswa[0] }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Contact Info Section -->
              <div class="bg-yellow-50 p-4 rounded-lg">
                <h4 class="text-md font-medium text-gray-900 mb-3">Informasi Kontak</h4>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                  <!-- Nomor HP -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Nomor HP
                    </label>
                    <input
                      v-model="form.nomor_hp"
                      type="tel"
                      class="form-input"
                      placeholder="08xxxxxxxxxx"
                    />
                  </div>

                  <!-- Email Pribadi -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Email Pribadi
                    </label>
                    <input
                      v-model="form.email_pribadi"
                      type="email"
                      class="form-input"
                      placeholder="Email pribadi (opsional)"
                    />
                  </div>
                </div>

                <!-- Alamat -->
                <div class="mt-4">
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Alamat Lengkap <span class="text-red-500">*</span>
                  </label>
                  <textarea
                    v-model="form.alamat_lengkap"
                    required
                    rows="3"
                    class="form-textarea"
                    :class="{ 'border-red-500': errors.alamat_lengkap }"
                    placeholder="Alamat lengkap siswa"
                  ></textarea>
                  <p v-if="errors.alamat_lengkap" class="mt-1 text-sm text-red-600">
                    {{ errors.alamat_lengkap[0] }}
                  </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
                  <!-- Kelurahan -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Kelurahan <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="form.kelurahan"
                      type="text"
                      required
                      class="form-input"
                      :class="{ 'border-red-500': errors.kelurahan }"
                      placeholder="Kelurahan"
                    />
                    <p v-if="errors.kelurahan" class="mt-1 text-sm text-red-600">
                      {{ errors.kelurahan[0] }}
                    </p>
                  </div>

                  <!-- Kecamatan -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Kecamatan <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="form.kecamatan"
                      type="text"
                      required
                      class="form-input"
                      :class="{ 'border-red-500': errors.kecamatan }"
                      placeholder="Kecamatan"
                    />
                    <p v-if="errors.kecamatan" class="mt-1 text-sm text-red-600">
                      {{ errors.kecamatan[0] }}
                    </p>
                  </div>

                  <!-- Kabupaten/Kota -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Kabupaten/Kota <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="form.kabupaten_kota"
                      type="text"
                      required
                      class="form-input"
                      :class="{ 'border-red-500': errors.kabupaten_kota }"
                      placeholder="Kabupaten/Kota"
                    />
                    <p v-if="errors.kabupaten_kota" class="mt-1 text-sm text-red-600">
                      {{ errors.kabupaten_kota[0] }}
                    </p>
                  </div>

                  <!-- Provinsi -->
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                      Provinsi <span class="text-red-500">*</span>
                    </label>
                    <input
                      v-model="form.provinsi"
                      type="text"
                      required
                      class="form-input"
                      :class="{ 'border-red-500': errors.provinsi }"
                      placeholder="Provinsi"
                    />
                    <p v-if="errors.provinsi" class="mt-1 text-sm text-red-600">
                      {{ errors.provinsi[0] }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Form Actions -->
              <div class="mt-6 flex items-center justify-end space-x-3">
                <button
                  type="button"
                  @click="$emit('close')"
                  class="btn btn-secondary"
                  :disabled="loading"
                >
                  Batal
                </button>
                <button
                  type="submit"
                  class="btn btn-primary"
                  :disabled="loading"
                >
                  <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  {{ siswa ? 'Update' : 'Simpan' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, watch, onMounted } from 'vue'
import api from '../../services/api'

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

const emit = defineEmits(['close', 'saved'])

const loading = ref(false)
const errors = ref({})
const kelasList = ref([])

const form = reactive({
  // Account info
  username: '',
  email: '',
  password: '',
  
  // Student identity
  nisn: '',
  nis: '',
  nik: '',
  
  // Personal info
  nama_lengkap: '',
  nama_panggilan: '',
  jenis_kelamin: '',
  tempat_lahir: '',
  tanggal_lahir: '',
  agama: '',
  kewarganegaraan: '',
  
  // Academic info
  kelas_id: '',
  angkatan: '',
  status_siswa: 'Aktif',
  
  // Contact info
  nomor_hp: '',
  email_pribadi: '',
  alamat_lengkap: '',
  kelurahan: '',
  kecamatan: '',
  kabupaten_kota: '',
  provinsi: ''
})

// Load kelas list
const loadKelasList = async () => {
  try {
    // Assuming we have a kelas endpoint
    const response = await api.get('/kelas')
    kelasList.value = response.data.data || []
  } catch (error) {
    console.error('Error loading kelas list:', error)
    kelasList.value = []
  }
}

// Watch for siswa prop changes
watch(() => props.siswa, (newSiswa) => {
  if (newSiswa) {
    // Editing existing siswa
    form.username = newSiswa.user?.username || ''
    form.email = newSiswa.user?.email || ''
    form.password = ''
    form.nisn = newSiswa.nisn || ''
    form.nis = newSiswa.nis || ''
    form.nik = newSiswa.nik || ''
    form.nama_lengkap = newSiswa.nama_lengkap || ''
    form.nama_panggilan = newSiswa.nama_panggilan || ''
    form.jenis_kelamin = newSiswa.jenis_kelamin || ''
    form.tempat_lahir = newSiswa.tempat_lahir || ''
    form.tanggal_lahir = newSiswa.tanggal_lahir || ''
    form.agama = newSiswa.agama || ''
    form.kewarganegaraan = newSiswa.kewarganegaraan || ''
    form.kelas_id = newSiswa.kelas_id || ''
    form.angkatan = newSiswa.angkatan || ''
    form.status_siswa = newSiswa.status_siswa || 'Aktif'
    form.nomor_hp = newSiswa.nomor_hp || ''
    form.email_pribadi = newSiswa.email_pribadi || ''
    form.alamat_lengkap = newSiswa.alamat_lengkap || ''
    form.kelurahan = newSiswa.kelurahan || ''
    form.kecamatan = newSiswa.kecamatan || ''
    form.kabupaten_kota = newSiswa.kabupaten_kota || ''
    form.provinsi = newSiswa.provinsi || ''
  } else {
    // Reset form for new siswa
    Object.keys(form).forEach(key => {
      if (key === 'status_siswa') {
        form[key] = 'Aktif'
      } else {
        form[key] = ''
      }
    })
  }
  
  errors.value = {}
}, { immediate: true })

const submitForm = async () => {
  loading.value = true
  errors.value = {}
  
  try {
    const payload = { ...form }
    
    // Remove password if editing and not provided
    if (props.siswa && !payload.password) {
      delete payload.password
    }
    
    if (props.siswa) {
      // Update existing siswa
      await api.put(`/siswa/${props.siswa.id}`, payload)
    } else {
      // Create new siswa
      await api.post('/siswa', payload)
    }
    
    emit('saved')
    
    // Show success message
    const action = props.siswa ? 'diupdate' : 'dibuat'
    alert(`Siswa berhasil ${action}`)
    
  } catch (error) {
    console.error('Error saving siswa:', error)
    
    if (error.response?.status === 422) {
      // Validation errors
      errors.value = error.response.data.errors || {}
    } else {
      alert(`Error: ${error.response?.data?.message || 'Terjadi kesalahan'}`)
    }
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadKelasList()
})
</script>
