<template>
  <div class="data-referensi-view">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Data Referensi</h1>
      <p class="text-gray-600">Kelola data referensi dan master data sistem</p>
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

    <!-- Content based on active tab -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
      <!-- Agama Tab -->
      <div v-if="activeTab === 'agama'" class="p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Data Agama</h3>
          <button
            @click="showAddModal = true; currentData = 'agama'"
            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Agama
          </button>
        </div>
        
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Agama</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(agama, index) in agamaList" :key="agama.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ index + 1 }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ agama.nama }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="agama.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                    {{ agama.status ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button @click="editItem(agama, 'agama')" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                  <button @click="deleteItem(agama.id, 'agama')" class="text-red-600 hover:text-red-900">Hapus</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Jenis Kelamin Tab -->
      <div v-if="activeTab === 'jenis-kelamin'" class="p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Data Jenis Kelamin</h3>
          <button
            @click="showAddModal = true; currentData = 'jenis-kelamin'"
            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Jenis Kelamin
          </button>
        </div>
        
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kelamin</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(jk, index) in jenisKelaminList" :key="jk.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ index + 1 }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ jk.nama }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ jk.kode }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="jk.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                    {{ jk.status ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button @click="editItem(jk, 'jenis-kelamin')" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                  <button @click="deleteItem(jk.id, 'jenis-kelamin')" class="text-red-600 hover:text-red-900">Hapus</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Status Keluarga Tab -->
      <div v-if="activeTab === 'status-keluarga'" class="p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Data Status Keluarga</h3>
          <button
            @click="showAddModal = true; currentData = 'status-keluarga'"
            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Status Keluarga
          </button>
        </div>
        
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status Keluarga</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(status, index) in statusKeluargaList" :key="status.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ index + 1 }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ status.nama }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ status.deskripsi }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="status.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                    {{ status.status ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button @click="editItem(status, 'status-keluarga')" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                  <button @click="deleteItem(status.id, 'status-keluarga')" class="text-red-600 hover:text-red-900">Hapus</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Tingkat Kelas Tab -->
      <div v-if="activeTab === 'tingkat-kelas'" class="p-6">
        <div class="flex justify-between items-center mb-4">
          <h3 class="text-lg font-semibold text-gray-900">Data Tingkat Kelas</h3>
          <button
            @click="showAddModal = true; currentData = 'tingkat-kelas'"
            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Tingkat Kelas
          </button>
        </div>
        
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tingkat Kelas</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kode</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Urutan</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(tingkat, index) in tingkatKelasList" :key="tingkat.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ index + 1 }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ tingkat.nama }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ tingkat.kode }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ tingkat.urutan }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="tingkat.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                    {{ tingkat.status ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button @click="editItem(tingkat, 'tingkat-kelas')" class="text-blue-600 hover:text-blue-900 mr-3">Edit</button>
                  <button @click="deleteItem(tingkat.id, 'tingkat-kelas')" class="text-red-600 hover:text-red-900">Hapus</button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Add/Edit Modal -->
    <div v-if="showAddModal || showEditModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">
            {{ showEditModal ? 'Edit Data' : 'Tambah Data' }}
          </h3>
          
          <form @submit.prevent="saveData" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
              <input
                v-model="form.nama"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                :placeholder="getPlaceholder()"
              >
            </div>
            
            <div v-if="currentData === 'jenis-kelamin' || currentData === 'tingkat-kelas'">
              <label class="block text-sm font-medium text-gray-700 mb-1">Kode</label>
              <input
                v-model="form.kode"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan kode"
              >
            </div>
            
            <div v-if="currentData === 'status-keluarga'">
              <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
              <textarea
                v-model="form.deskripsi"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan deskripsi"
              ></textarea>
            </div>
            
            <div v-if="currentData === 'tingkat-kelas'">
              <label class="block text-sm font-medium text-gray-700 mb-1">Urutan</label>
              <input
                v-model="form.urutan"
                type="number"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan urutan"
              >
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
              <select
                v-model="form.status"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option :value="true">Aktif</option>
                <option :value="false">Nonaktif</option>
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
import { ref, reactive, onMounted } from 'vue'

// Reactive data
const loading = ref(false)
const activeTab = ref('agama')
const showAddModal = ref(false)
const showEditModal = ref(false)
const currentData = ref('')
const editingItem = ref(null)

const tabs = [
  { id: 'agama', name: 'Agama' },
  { id: 'jenis-kelamin', name: 'Jenis Kelamin' },
  { id: 'status-keluarga', name: 'Status Keluarga' },
  { id: 'tingkat-kelas', name: 'Tingkat Kelas' }
]

const agamaList = ref([])
const jenisKelaminList = ref([])
const statusKeluargaList = ref([])
const tingkatKelasList = ref([])

const form = reactive({
  nama: '',
  kode: '',
  deskripsi: '',
  urutan: '',
  status: true
})

// Methods
const loadDataReferensi = async () => {
  try {
    loading.value = true
    // TODO: Implement API calls
    // const [agamaRes, jkRes, statusRes, tingkatRes] = await Promise.all([
    //   api.get('/referensi/agama'),
    //   api.get('/referensi/jenis-kelamin'),
    //   api.get('/referensi/status-keluarga'),
    //   api.get('/referensi/tingkat-kelas')
    // ])
    
    // Mock data
    agamaList.value = [
      { id: 1, nama: 'Islam', status: true },
      { id: 2, nama: 'Kristen', status: true },
      { id: 3, nama: 'Katolik', status: true },
      { id: 4, nama: 'Hindu', status: true },
      { id: 5, nama: 'Buddha', status: true },
      { id: 6, nama: 'Konghucu', status: true }
    ]
    
    jenisKelaminList.value = [
      { id: 1, nama: 'Laki-laki', kode: 'L', status: true },
      { id: 2, nama: 'Perempuan', kode: 'P', status: true }
    ]
    
    statusKeluargaList.value = [
      { id: 1, nama: 'Anak Kandung', deskripsi: 'Anak kandung dari orang tua', status: true },
      { id: 2, nama: 'Anak Angkat', deskripsi: 'Anak angkat dari orang tua', status: true },
      { id: 3, nama: 'Anak Tiri', deskripsi: 'Anak tiri dari orang tua', status: true }
    ]
    
    tingkatKelasList.value = [
      { id: 1, nama: 'X (Sepuluh)', kode: 'X', urutan: 1, status: true },
      { id: 2, nama: 'XI (Sebelas)', kode: 'XI', urutan: 2, status: true },
      { id: 3, nama: 'XII (Dua Belas)', kode: 'XII', urutan: 3, status: true }
    ]
  } catch (error) {
    console.error('Error loading data referensi:', error)
  } finally {
    loading.value = false
  }
}

const getPlaceholder = () => {
  const placeholders = {
    'agama': 'Masukkan nama agama',
    'jenis-kelamin': 'Masukkan jenis kelamin',
    'status-keluarga': 'Masukkan status keluarga',
    'tingkat-kelas': 'Masukkan tingkat kelas'
  }
  return placeholders[currentData.value] || 'Masukkan nama'
}

const editItem = (item, type) => {
  editingItem.value = item
  currentData.value = type
  Object.assign(form, item)
  showEditModal.value = true
}

const deleteItem = async (id, type) => {
  if (confirm('Apakah Anda yakin ingin menghapus data ini?')) {
    try {
      // TODO: Implement API call
      // await api.delete(`/referensi/${type}/${id}`)
      
      const lists = {
        'agama': agamaList,
        'jenis-kelamin': jenisKelaminList,
        'status-keluarga': statusKeluargaList,
        'tingkat-kelas': tingkatKelasList
      }
      
      const list = lists[type]
      if (list) {
        list.value = list.value.filter(item => item.id !== id)
      }
    } catch (error) {
      console.error('Error deleting item:', error)
    }
  }
}

const saveData = async () => {
  try {
    loading.value = true
    
    const lists = {
      'agama': agamaList,
      'jenis-kelamin': jenisKelaminList,
      'status-keluarga': statusKeluargaList,
      'tingkat-kelas': tingkatKelasList
    }
    
    const list = lists[currentData.value]
    
    if (showEditModal.value) {
      // TODO: Implement API call for update
      // await api.put(`/referensi/${currentData.value}/${editingItem.value.id}`, form)
      
      const index = list.value.findIndex(item => item.id === editingItem.value.id)
      if (index !== -1) {
        list.value[index] = { ...editingItem.value, ...form }
      }
    } else {
      // TODO: Implement API call for create
      // const response = await api.post(`/referensi/${currentData.value}`, form)
      
      const newItem = {
        id: Date.now(),
        ...form
      }
      list.value.push(newItem)
    }
    
    closeModal()
  } catch (error) {
    console.error('Error saving data:', error)
  } finally {
    loading.value = false
  }
}

const closeModal = () => {
  showAddModal.value = false
  showEditModal.value = false
  editingItem.value = null
  currentData.value = ''
  Object.assign(form, {
    nama: '',
    kode: '',
    deskripsi: '',
    urutan: '',
    status: true
  })
}

// Lifecycle
onMounted(() => {
  loadDataReferensi()
})
</script>

<style scoped>
.data-referensi-view {
  @apply p-6;
}
</style>
