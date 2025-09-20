<template>
  <div v-if="isOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
      <!-- Header -->
      <div class="flex items-center justify-between p-6 border-b">
        <h2 class="text-xl font-semibold text-gray-900">
          {{ isEditing ? 'Edit Kegiatan OSIS' : 'Tambah Kegiatan OSIS' }}
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
          <!-- Nama Kegiatan -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Nama Kegiatan <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.nama_kegiatan"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Nama kegiatan OSIS"
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
              placeholder="Deskripsi kegiatan"
              required
            ></textarea>
          </div>

          <!-- Tanggal Mulai -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Tanggal Mulai <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.tanggal_mulai"
              type="date"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>

          <!-- Tanggal Selesai -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Tanggal Selesai <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.tanggal_selesai"
              type="date"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>

          <!-- Jam Mulai -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Jam Mulai <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.jam_mulai"
              type="time"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>

          <!-- Jam Selesai -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Jam Selesai <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.jam_selesai"
              type="time"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            />
          </div>

          <!-- Jenis Kegiatan -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Jenis Kegiatan <span class="text-red-500">*</span>
            </label>
            <select
              v-model="form.jenis_kegiatan"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              required
            >
              <option value="">Pilih Jenis</option>
              <option value="akademik">Akademik</option>
              <option value="non_akademik">Non-Akademik</option>
              <option value="sosial">Sosial</option>
              <option value="olahraga">Olahraga</option>
              <option value="seni">Seni</option>
              <option value="keagamaan">Keagamaan</option>
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
              <option value="perencanaan">Perencanaan</option>
              <option value="persiapan">Persiapan</option>
              <option value="berlangsung">Berlangsung</option>
              <option value="selesai">Selesai</option>
              <option value="dibatalkan">Dibatalkan</option>
            </select>
          </div>

          <!-- Tempat Kegiatan -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Tempat Kegiatan <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.tempat_kegiatan"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Lapangan Sekolah"
              required
            />
          </div>

          <!-- Penanggung Jawab -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Penanggung Jawab <span class="text-red-500">*</span>
            </label>
            <input
              v-model="form.penanggung_jawab"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Nama penanggung jawab"
              required
            />
          </div>

          <!-- Anggaran -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Anggaran (Rp)
            </label>
            <input
              v-model.number="form.anggaran"
              type="number"
              min="0"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="0"
            />
          </div>

          <!-- Sumber Dana -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Sumber Dana
            </label>
            <input
              v-model="form.sumber_dana"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Dana OSIS"
            />
          </div>

          <!-- Tujuan Kegiatan -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Tujuan Kegiatan <span class="text-red-500">*</span>
            </label>
            <textarea
              v-model="form.tujuan_kegiatan"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Tujuan yang ingin dicapai dari kegiatan"
              required
            ></textarea>
          </div>

          <!-- Sasaran Kegiatan -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Sasaran Kegiatan <span class="text-red-500">*</span>
            </label>
            <textarea
              v-model="form.sasaran_kegiatan"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Siapa yang menjadi sasaran kegiatan"
              required
            ></textarea>
          </div>

          <!-- Keterangan -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Keterangan
            </label>
            <textarea
              v-model="form.keterangan"
              rows="3"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Keterangan tambahan"
            ></textarea>
          </div>

          <!-- Foto Kegiatan -->
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
              Foto Kegiatan
            </label>
            <input
              v-model="form.foto_kegiatan"
              type="text"
              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              placeholder="Nama file foto"
            />
          </div>

          <!-- Checkboxes -->
          <div class="flex gap-6">
            <label class="flex items-center">
              <input
                v-model="form.is_aktif"
                type="checkbox"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
              />
              <span class="ml-2 text-sm text-gray-700">Aktif</span>
            </label>
            <label class="flex items-center">
              <input
                v-model="form.is_urgent"
                type="checkbox"
                class="rounded border-gray-300 text-blue-600 focus:ring-blue-500"
              />
              <span class="ml-2 text-sm text-gray-700">Urgent</span>
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
import { ref, watch } from 'vue'
import api from '@/services/api'

const props = defineProps({
  isOpen: {
    type: Boolean,
    default: false
  },
  kegiatan: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'saved'])

const loading = ref(false)

const form = ref({
  nama_kegiatan: '',
  deskripsi: '',
  tanggal_mulai: '',
  tanggal_selesai: '',
  jam_mulai: '',
  jam_selesai: '',
  jenis_kegiatan: '',
  status: '',
  tempat_kegiatan: '',
  tujuan_kegiatan: '',
  sasaran_kegiatan: '',
  anggaran: null,
  sumber_dana: '',
  penanggung_jawab: '',
  peserta: {},
  panitia: {},
  keterangan: '',
  foto_kegiatan: '',
  is_aktif: true,
  is_urgent: false
})

const isEditing = ref(false)

// Watch for kegiatan changes
watch(() => props.kegiatan, (newKegiatan) => {
  if (newKegiatan) {
    isEditing.value = true
    form.value = { ...newKegiatan }
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

const resetForm = () => {
  form.value = {
    nama_kegiatan: '',
    deskripsi: '',
    tanggal_mulai: '',
    tanggal_selesai: '',
    jam_mulai: '',
    jam_selesai: '',
    jenis_kegiatan: '',
    status: '',
    tempat_kegiatan: '',
    tujuan_kegiatan: '',
    sasaran_kegiatan: '',
    anggaran: null,
    sumber_dana: '',
    penanggung_jawab: '',
    peserta: {},
    panitia: {},
    keterangan: '',
    foto_kegiatan: '',
    is_aktif: true,
    is_urgent: false
  }
  isEditing.value = false
}

const handleSubmit = async () => {
  loading.value = true
  
  try {
    if (isEditing.value) {
      await api.put(`/osis-kegiatan/${props.kegiatan.id}`, form.value)
    } else {
      await api.post('/osis-kegiatan', form.value)
    }
    
    emit('saved')
    closeModal()
  } catch (error) {
    console.error('Error saving OSIS kegiatan:', error)
    alert('Terjadi kesalahan saat menyimpan data')
  } finally {
    loading.value = false
  }
}

const closeModal = () => {
  emit('close')
}
</script>
