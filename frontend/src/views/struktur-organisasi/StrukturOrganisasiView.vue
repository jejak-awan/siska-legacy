<template>
  <div class="struktur-organisasi-view">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Struktur Organisasi</h1>
      <p class="text-gray-600">Kelola struktur organisasi dan jabatan di sekolah</p>
    </div>

    <!-- Action Bar -->
    <div class="flex justify-between items-center mb-6">
      <div class="flex space-x-4">
        <button
          @click="showAddModal = true"
          class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
        >
          <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
          </svg>
          Tambah Jabatan
        </button>
        
        <button
          @click="loadStrukturOrganisasi"
          class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
        >
          <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
          </svg>
          Refresh
        </button>
      </div>
      
      <div class="flex items-center space-x-2">
        <label class="text-sm font-medium text-gray-700">Filter:</label>
        <select
          v-model="filterLevel"
          @change="filterStruktur"
          class="px-3 py-1 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <option value="">Semua Level</option>
          <option value="pimpinan">Pimpinan</option>
          <option value="manajemen">Manajemen</option>
          <option value="staf">Staf</option>
        </select>
      </div>
    </div>

    <!-- Organizational Chart -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
      <h3 class="text-lg font-semibold text-gray-900 mb-4">Diagram Struktur Organisasi</h3>
      
      <div class="overflow-x-auto">
        <div class="min-w-full">
          <!-- Top Level - Kepala Sekolah -->
          <div class="flex justify-center mb-8">
            <div class="bg-blue-100 border-2 border-blue-300 rounded-lg p-4 text-center min-w-[200px]">
              <div class="text-sm font-medium text-blue-800">Kepala Sekolah</div>
              <div class="text-xs text-blue-600 mt-1">{{ getKepalaSekolah() }}</div>
            </div>
          </div>
          
          <!-- Second Level - Wakil Kepala -->
          <div class="flex justify-center space-x-8 mb-6">
            <div
              v-for="wakil in getWakilKepala()"
              :key="wakil.id"
              class="bg-green-100 border-2 border-green-300 rounded-lg p-3 text-center min-w-[150px]"
            >
              <div class="text-sm font-medium text-green-800">{{ wakil.jabatan }}</div>
              <div class="text-xs text-green-600 mt-1">{{ wakil.nama }}</div>
            </div>
          </div>
          
          <!-- Third Level - Koordinator & Kepala -->
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div
              v-for="koordinator in getKoordinator()"
              :key="koordinator.id"
              class="bg-yellow-100 border-2 border-yellow-300 rounded-lg p-3 text-center"
            >
              <div class="text-sm font-medium text-yellow-800">{{ koordinator.jabatan }}</div>
              <div class="text-xs text-yellow-600 mt-1">{{ koordinator.nama }}</div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Jabatan List -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Daftar Jabatan</h3>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jabatan</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Atasan</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penanggung Jawab</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="jabatan in filteredJabatan" :key="jabatan.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ jabatan.nama }}</div>
                <div class="text-sm text-gray-500">{{ jabatan.deskripsi }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getLevelBadgeClass(jabatan.level)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                  {{ jabatan.level }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ jabatan.atasan || '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ jabatan.penanggungJawab || '-' }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="jabatan.status === 'aktif' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                  {{ jabatan.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <button
                  @click="editJabatan(jabatan)"
                  class="text-blue-600 hover:text-blue-900 mr-3"
                >
                  Edit
                </button>
                <button
                  @click="deleteJabatan(jabatan.id)"
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

    <!-- Add/Edit Modal -->
    <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">
            {{ showEditModal ? 'Edit Jabatan' : 'Tambah Jabatan' }}
          </h3>
          
          <form @submit.prevent="saveJabatan" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama Jabatan</label>
              <input
                v-model="form.nama"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan nama jabatan"
              >
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
              <textarea
                v-model="form.deskripsi"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan deskripsi jabatan"
              ></textarea>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Level</label>
              <select
                v-model="form.level"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="">Pilih level</option>
                <option value="pimpinan">Pimpinan</option>
                <option value="manajemen">Manajemen</option>
                <option value="staf">Staf</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Atasan</label>
              <select
                v-model="form.atasan"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="">Pilih atasan</option>
                <option v-for="jabatan in jabatanList" :key="jabatan.id" :value="jabatan.nama">
                  {{ jabatan.nama }}
                </option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Penanggung Jawab</label>
              <input
                v-model="form.penanggungJawab"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan nama penanggung jawab"
              >
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select
                v-model="form.status"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
              </select>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
              <button
                type="button"
                @click="closeModal"
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
import { ref, reactive, computed, onMounted } from 'vue'

// Reactive data
const loading = ref(false)
const showAddModal = ref(false)
const showEditModal = ref(false)
const filterLevel = ref('')
const jabatanList = ref([])
const editingJabatan = ref(null)

const form = reactive({
  nama: '',
  deskripsi: '',
  level: '',
  atasan: '',
  penanggungJawab: '',
  status: 'aktif'
})

// Computed
const filteredJabatan = computed(() => {
  if (!filterLevel.value) return jabatanList.value
  return jabatanList.value.filter(jabatan => jabatan.level === filterLevel.value)
})

// Methods
const loadStrukturOrganisasi = async () => {
  try {
    loading.value = true
    // TODO: Implement API call
    // const response = await api.get('/struktur-organisasi')
    // jabatanList.value = response.data
    
    // Mock data
    jabatanList.value = [
      {
        id: 1,
        nama: 'Kepala Sekolah',
        deskripsi: 'Pimpinan tertinggi di sekolah',
        level: 'pimpinan',
        atasan: null,
        penanggungJawab: 'Dr. John Doe, M.Pd',
        status: 'aktif'
      },
      {
        id: 2,
        nama: 'Wakil Kepala Sekolah Bidang Kurikulum',
        deskripsi: 'Mengkoordinasikan kegiatan kurikulum',
        level: 'manajemen',
        atasan: 'Kepala Sekolah',
        penanggungJawab: 'Siti Aminah, S.Pd',
        status: 'aktif'
      },
      {
        id: 3,
        nama: 'Wakil Kepala Sekolah Bidang Kesiswaan',
        deskripsi: 'Mengkoordinasikan kegiatan kesiswaan',
        level: 'manajemen',
        atasan: 'Kepala Sekolah',
        penanggungJawab: 'Ahmad Fauzi, S.Pd',
        status: 'aktif'
      },
      {
        id: 4,
        nama: 'Koordinator BK',
        deskripsi: 'Mengkoordinasikan bimbingan dan konseling',
        level: 'staf',
        atasan: 'Wakil Kepala Sekolah Bidang Kesiswaan',
        penanggungJawab: 'Rina Sari, S.Pd',
        status: 'aktif'
      }
    ]
  } catch (error) {
    console.error('Error loading struktur organisasi:', error)
  } finally {
    loading.value = false
  }
}

const getKepalaSekolah = () => {
  const kepala = jabatanList.value.find(j => j.nama === 'Kepala Sekolah')
  return kepala ? kepala.penanggungJawab : 'Belum ditentukan'
}

const getWakilKepala = () => {
  return jabatanList.value.filter(j => j.nama.includes('Wakil Kepala Sekolah'))
}

const getKoordinator = () => {
  return jabatanList.value.filter(j => j.nama.includes('Koordinator'))
}

const getLevelBadgeClass = (level) => {
  const classes = {
    pimpinan: 'bg-blue-100 text-blue-800',
    manajemen: 'bg-green-100 text-green-800',
    staf: 'bg-yellow-100 text-yellow-800'
  }
  return classes[level] || 'bg-gray-100 text-gray-800'
}

const filterStruktur = () => {
  // Filter logic is handled by computed property
}

const editJabatan = (jabatan) => {
  editingJabatan.value = jabatan
  Object.assign(form, jabatan)
  showEditModal.value = true
}

const deleteJabatan = async (id) => {
  if (confirm('Apakah Anda yakin ingin menghapus jabatan ini?')) {
    try {
      // TODO: Implement API call
      // await api.delete(`/struktur-organisasi/${id}`)
      
      jabatanList.value = jabatanList.value.filter(j => j.id !== id)
    } catch (error) {
      console.error('Error deleting jabatan:', error)
    }
  }
}

const saveJabatan = async () => {
  try {
    loading.value = true
    
    if (showEditModal.value) {
      // TODO: Implement API call for update
      // await api.put(`/struktur-organisasi/${editingJabatan.value.id}`, form)
      
      const index = jabatanList.value.findIndex(j => j.id === editingJabatan.value.id)
      if (index !== -1) {
        jabatanList.value[index] = { ...editingJabatan.value, ...form }
      }
    } else {
      // TODO: Implement API call for create
      // const response = await api.post('/struktur-organisasi', form)
      
      const newJabatan = {
        id: Date.now(),
        ...form
      }
      jabatanList.value.push(newJabatan)
    }
    
    closeModal()
  } catch (error) {
    console.error('Error saving jabatan:', error)
  } finally {
    loading.value = false
  }
}

const closeModal = () => {
  showAddModal.value = false
  showEditModal.value = false
  editingJabatan.value = null
  Object.assign(form, {
    nama: '',
    deskripsi: '',
    level: '',
    atasan: '',
    penanggungJawab: '',
    status: 'aktif'
  })
}

// Lifecycle
onMounted(() => {
  loadStrukturOrganisasi()
})
</script>

<style scoped>
.struktur-organisasi-view {
  @apply p-6;
}
</style>
