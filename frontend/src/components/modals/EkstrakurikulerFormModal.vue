<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
      <!-- Header -->
      <div class="flex items-center justify-between p-6 border-b">
        <h2 class="text-xl font-semibold text-gray-900">
          {{ isEditing ? 'Edit Ekstrakurikuler' : 'Tambah Ekstrakurikuler' }}
        </h2>
        <button
          @click="closeModal"
          class="text-gray-400 hover:text-gray-600 transition-colors"
        >
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Form -->
      <form @submit.prevent="handleSubmit" class="p-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Nama Ekstrakurikuler -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Nama Ekstrakurikuler <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.nama_ekstrakurikuler"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Nama ekstrakurikuler"
              required
            />
          </div>

          <!-- Deskripsi -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Deskripsi <span class="text-red-500">*</span>
            </label>
            <textarea
              v-model="form.deskripsi"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Deskripsi ekstrakurikuler"
              required
            ></textarea>
          </div>

          <!-- Jenis -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Jenis <span class="text-red-500">*</span>
            </label>
            <select
              v-model="form.jenis"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            >
              <option value="">Pilih Jenis</option>
              <option value="olahraga">Olahraga</option>
              <option value="seni">Seni</option>
              <option value="akademik">Akademik</option>
              <option value="keagamaan">Keagamaan</option>
              <option value="keterampilan">Keterampilan</option>
              <option value="sosial">Sosial</option>
            </select>
          </div>

          <!-- Status -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Status <span class="text-red-500">*</span>
            </label>
            <select
              v-model="form.status"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            >
              <option value="">Pilih Status</option>
              <option value="aktif">Aktif</option>
              <option value="tidak_aktif">Tidak Aktif</option>
              <option value="ditutup">Ditutup</option>
            </select>
          </div>

          <!-- Pembina -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Pembina
            </label>
            <select
              v-model="form.pembina_id"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Pilih Pembina</option>
              <option v-for="guru in guruList" :key="guru.id" :value="guru.id">
                {{ guru.nama_lengkap }}
              </option>
            </select>
          </div>

          <!-- Ketua -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Ketua
            </label>
            <select
              v-model="form.ketua_id"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Pilih Ketua</option>
              <option v-for="siswa in siswaList" :key="siswa.id" :value="siswa.id">
                {{ siswa.nama_lengkap }} ({{ siswa.nis }})
              </option>
            </select>
          </div>

          <!-- Jadwal Latihan -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Jadwal Latihan <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.jadwal_latihan"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Senin dan Kamis, 15:00-17:00"
              required
            />
          </div>

          <!-- Tempat Latihan -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Tempat Latihan <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.tempat_latihan"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Lapangan Sekolah"
              required
            />
          </div>

          <!-- Kuota Anggota -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Kuota Anggota
            </label>
            <input
              v-model.number="form.kuota_anggota"
              type="number"
              min="0"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="20"
            />
          </div>

          <!-- Biaya Pendaftaran -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Biaya Pendaftaran (Rp)
            </label>
            <input
              v-model.number="form.biaya_pendaftaran"
              type="number"
              min="0"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="50000"
            />
          </div>

          <!-- Persyaratan Anggota -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Persyaratan Anggota
            </label>
            <textarea
              v-model="form.persyaratan_anggota"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Siswa kelas X-XII, memiliki minat dan bakat"
            ></textarea>
          </div>

          <!-- Fasilitas -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Fasilitas
            </label>
            <textarea
              v-model="form.fasilitas"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Lapangan, bola, kostum tim"
            ></textarea>
          </div>

          <!-- Prestasi -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Prestasi
            </label>
            <textarea
              v-model="form.prestasi"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Juara 2 Turnamen Futsal Antar Sekolah 2023"
            ></textarea>
          </div>

          <!-- Foto Ekstrakurikuler -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Foto Ekstrakurikuler
            </label>
            <input
              v-model="form.foto_ekstrakurikuler"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Nama file foto"
            />
          </div>

          <!-- Checkbox -->
          <div class="flex items-center">
            <label class="flex items-center">
              <input
                v-model="form.is_aktif"
                type="checkbox"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
              />
              <span class="ml-2 text-sm text-gray-700">Aktif</span>
            </label>
          </div>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3 mt-6 pt-6 border-t">
          <button
            type="button"
            @click="closeModal"
            class="px-4 py-2 text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 transition-colors"
          >
            Batal
          </button>
          <button
            type="submit"
            :disabled="loading"
            class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 disabled:opacity-50 transition-colors"
          >
            <span v-if="loading">Menyimpan...</span>
            <span v-else>{{ isEditing ? 'Update' : 'Simpan' }}</span>
          </button>
        </div>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref, watch, onMounted } from 'vue'
import api from '@/services/api'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  ekstrakurikuler: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'saved'])

const loading = ref(false)
const guruList = ref([])
const siswaList = ref([])

const form = ref({
  nama_ekstrakurikuler: '',
  deskripsi: '',
  jenis: '',
  status: '',
  pembina_id: '',
  ketua_id: '',
  jadwal_latihan: '',
  tempat_latihan: '',
  persyaratan_anggota: '',
  kuota_anggota: null,
  biaya_pendaftaran: null,
  fasilitas: '',
  prestasi: '',
  foto_ekstrakurikuler: '',
  is_aktif: true
})

const isEditing = ref(false)

// Load data
onMounted(async () => {
  await loadGuruList()
  await loadSiswaList()
})

// Watch for ekstrakurikuler changes
watch(() => props.ekstrakurikuler, (newEkstrakurikuler) => {
  if (newEkstrakurikuler) {
    isEditing.value = true
    form.value = { ...newEkstrakurikuler }
  } else {
    isEditing.value = false
    resetForm()
  }
}, { immediate: true })

// Watch for modal open/close
watch(() => props.isOpen, (isOpen) => {
  if (!isOpen) {
    resetForm()
  }
})

const loadGuruList = async () => {
  try {
    const response = await api.get('/guru')
    guruList.value = response.data.data.data || []
  } catch (error) {
    console.error('Error loading guru list:', error)
  }
}

const loadSiswaList = async () => {
  try {
    const response = await api.get('/siswa')
    siswaList.value = response.data.data.data || []
  } catch (error) {
    console.error('Error loading siswa list:', error)
  }
}

const resetForm = () => {
  form.value = {
    nama_ekstrakurikuler: '',
    deskripsi: '',
    jenis: '',
    status: '',
    pembina_id: '',
    ketua_id: '',
    jadwal_latihan: '',
    tempat_latihan: '',
    persyaratan_anggota: '',
    kuota_anggota: null,
    biaya_pendaftaran: null,
    fasilitas: '',
    prestasi: '',
    foto_ekstrakurikuler: '',
    is_aktif: true
  }
  isEditing.value = false
}

const handleSubmit = async () => {
  loading.value = true
  
  try {
    if (isEditing.value) {
      await api.put(`/ekstrakurikuler/${props.ekstrakurikuler.id}`, form.value)
    } else {
      await api.post('/ekstrakurikuler', form.value)
    }
    
    emit('saved')
    closeModal()
  } catch (error) {
    console.error('Error saving ekstrakurikuler:', error)
    alert('Terjadi kesalahan saat menyimpan data')
  } finally {
    loading.value = false
  }
}

const closeModal = () => {
  emit('close')
}
</script>
