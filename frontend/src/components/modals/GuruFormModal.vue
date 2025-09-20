<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 transition-opacity" @click="$emit('close')">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
      </div>

      <!-- Modal content -->
      <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full sm:p-6">
        <div>
          <div class="mt-3 text-center sm:mt-0 sm:text-left">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
              {{ guru ? 'Edit Guru' : 'Tambah Guru Baru' }}
            </h3>
            
            <form @submit.prevent="submitForm" class="space-y-4">
              <!-- Basic Info Section -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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
                    placeholder="Masukkan username"
                    :disabled="!!guru"
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
                    placeholder="Masukkan email"
                    :disabled="!!guru"
                  />
                  <p v-if="errors.email" class="mt-1 text-sm text-red-600">
                    {{ errors.email[0] }}
                  </p>
                </div>
              </div>

              <!-- Password (for new guru) -->
              <div v-if="!guru" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Password <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="form.password"
                    type="password"
                    required
                    class="form-input"
                    :class="{ 'border-red-500': errors.password }"
                    placeholder="Masukkan password"
                  />
                  <p v-if="errors.password" class="mt-1 text-sm text-red-600">
                    {{ errors.password[0] }}
                  </p>
                </div>
                <div></div> <!-- Empty div for grid alignment -->
              </div>

              <!-- Personal Info -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- NIP -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    NIP <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="form.nip"
                    type="text"
                    required
                    maxlength="18"
                    class="form-input"
                    :class="{ 'border-red-500': errors.nip }"
                    placeholder="18 digit NIP"
                  />
                  <p v-if="errors.nip" class="mt-1 text-sm text-red-600">
                    {{ errors.nip[0] }}
                  </p>
                </div>

                <!-- NUPTK -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    NUPTK
                  </label>
                  <input
                    v-model="form.nuptk"
                    type="text"
                    maxlength="16"
                    class="form-input"
                    :class="{ 'border-red-500': errors.nuptk }"
                    placeholder="16 digit NUPTK"
                  />
                  <p v-if="errors.nuptk" class="mt-1 text-sm text-red-600">
                    {{ errors.nuptk[0] }}
                  </p>
                </div>
              </div>

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
                    placeholder="Nama lengkap guru"
                  />
                  <p v-if="errors.nama_lengkap" class="mt-1 text-sm text-red-600">
                    {{ errors.nama_lengkap[0] }}
                  </p>
                </div>

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
              </div>

              <!-- Birth Info -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

              <!-- Professional Info -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Jenis PTK -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Jenis PTK <span class="text-red-500">*</span>
                  </label>
                  <select
                    v-model="form.jenis_ptk"
                    required
                    class="form-select"
                    :class="{ 'border-red-500': errors.jenis_ptk }"
                  >
                    <option value="">Pilih Jenis PTK</option>
                    <option value="Guru Kelas">Guru Kelas</option>
                    <option value="Guru Mapel">Guru Mapel</option>
                    <option value="Tenaga Kependidikan">Tenaga Kependidikan</option>
                  </select>
                  <p v-if="errors.jenis_ptk" class="mt-1 text-sm text-red-600">
                    {{ errors.jenis_ptk[0] }}
                  </p>
                </div>

                <!-- Status Kepegawaian -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Status Kepegawaian <span class="text-red-500">*</span>
                  </label>
                  <select
                    v-model="form.status_kepegawaian"
                    required
                    class="form-select"
                    :class="{ 'border-red-500': errors.status_kepegawaian }"
                  >
                    <option value="">Pilih Status</option>
                    <option value="PNS">PNS</option>
                    <option value="PPPK">PPPK</option>
                    <option value="GTT">GTT</option>
                    <option value="PTT">PTT</option>
                    <option value="Honorer">Honorer</option>
                  </select>
                  <p v-if="errors.status_kepegawaian" class="mt-1 text-sm text-red-600">
                    {{ errors.status_kepegawaian[0] }}
                  </p>
                </div>
              </div>

              <!-- Contact Info -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Nomor HP -->
                <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">
                    Nomor HP <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="form.nomor_hp"
                    type="tel"
                    required
                    class="form-input"
                    :class="{ 'border-red-500': errors.nomor_hp }"
                    placeholder="08xxxxxxxxxx"
                  />
                  <p v-if="errors.nomor_hp" class="mt-1 text-sm text-red-600">
                    {{ errors.nomor_hp[0] }}
                  </p>
                </div>

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
              </div>

              <!-- Special Roles -->
              <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                  <label class="flex items-center">
                    <input
                      v-model="form.is_wali_kelas"
                      type="checkbox"
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <span class="ml-2 text-sm text-gray-700">Wali Kelas</span>
                  </label>
                </div>
                <div>
                  <label class="flex items-center">
                    <input
                      v-model="form.is_konselor_bk"
                      type="checkbox"
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <span class="ml-2 text-sm text-gray-700">Konselor BK</span>
                  </label>
                </div>
                <div>
                  <label class="flex items-center">
                    <input
                      v-model="form.is_pembina_osis"
                      type="checkbox"
                      class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                    />
                    <span class="ml-2 text-sm text-gray-700">Pembina OSIS</span>
                  </label>
                </div>
              </div>

              <!-- Status -->
              <div>
                <label class="flex items-center">
                  <input
                    v-model="form.status_aktif"
                    type="checkbox"
                    class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                  />
                  <span class="ml-2 text-sm text-gray-700">Status Aktif</span>
                </label>
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
                  {{ guru ? 'Update' : 'Simpan' }}
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
import { ref, reactive, watch } from 'vue'
import api from '../../services/api'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  guru: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'saved'])

const loading = ref(false)
const errors = ref({})

const form = reactive({
  username: '',
  email: '',
  password: '',
  nip: '',
  nuptk: '',
  nama_lengkap: '',
  jenis_kelamin: '',
  tempat_lahir: '',
  tanggal_lahir: '',
  agama: '',
  nomor_hp: '',
  jenis_ptk: '',
  status_kepegawaian: '',
  is_wali_kelas: false,
  is_konselor_bk: false,
  is_pembina_osis: false,
  status_aktif: true
})

// Watch for guru prop changes
watch(() => props.guru, (newGuru) => {
  if (newGuru) {
    // Editing existing guru
    form.username = newGuru.user?.username || ''
    form.email = newGuru.user?.email || ''
    form.password = ''
    form.nip = newGuru.nip || ''
    form.nuptk = newGuru.nuptk || ''
    form.nama_lengkap = newGuru.nama_lengkap || ''
    form.jenis_kelamin = newGuru.jenis_kelamin || ''
    form.tempat_lahir = newGuru.tempat_lahir || ''
    form.tanggal_lahir = newGuru.tanggal_lahir || ''
    form.agama = newGuru.agama || ''
    form.nomor_hp = newGuru.nomor_hp || ''
    form.jenis_ptk = newGuru.jenis_ptk || ''
    form.status_kepegawaian = newGuru.status_kepegawaian || ''
    form.is_wali_kelas = newGuru.is_wali_kelas || false
    form.is_konselor_bk = newGuru.is_konselor_bk || false
    form.is_pembina_osis = newGuru.is_pembina_osis || false
    form.status_aktif = newGuru.status_aktif !== false
  } else {
    // Reset form for new guru
    Object.keys(form).forEach(key => {
      if (typeof form[key] === 'boolean') {
        form[key] = key === 'status_aktif'
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
    if (props.guru && !payload.password) {
      delete payload.password
    }
    
    if (props.guru) {
      // Update existing guru
      await api.put(`/guru/${props.guru.id}`, payload)
    } else {
      // Create new guru
      await api.post('/guru', payload)
    }
    
    emit('saved')
    
    // Show success message
    const action = props.guru ? 'diupdate' : 'dibuat'
    alert(`Guru berhasil ${action}`)
    
  } catch (error) {
    console.error('Error saving guru:', error)
    
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
</script>
