<template>
  <div class="konfigurasi-karakter-view">
    <!-- Header -->
    <div class="mb-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-2">Konfigurasi Karakter</h1>
      <p class="text-gray-600">Kelola dimensi karakter dan sistem penilaian karakter dinamis</p>
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

    <!-- Dimensi Karakter Tab -->
    <div v-if="activeTab === 'dimensi'" class="space-y-6">
      <!-- Action Bar -->
      <div class="flex justify-between items-center">
        <div class="flex space-x-4">
          <button
            @click="showAddDimensiModal = true"
            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
          >
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Dimensi
          </button>
        </div>
      </div>

      <!-- Dimensi List -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Dimensi Karakter</h3>
        </div>
        
        <div class="p-6">
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <div
              v-for="dimensi in dimensiList"
              :key="dimensi.id"
              class="bg-gray-50 rounded-lg p-4 border border-gray-200"
            >
              <div class="flex items-center justify-between mb-3">
                <h4 class="text-lg font-semibold text-gray-900">{{ dimensi.nama }}</h4>
                <div class="flex space-x-2">
                  <button
                    @click="editDimensi(dimensi)"
                    class="text-blue-600 hover:text-blue-900"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                  </button>
                  <button
                    @click="deleteDimensi(dimensi.id)"
                    class="text-red-600 hover:text-red-900"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                  </button>
                </div>
              </div>
              
              <p class="text-sm text-gray-600 mb-3">{{ dimensi.deskripsi }}</p>
              
              <div class="space-y-2">
                <div class="text-xs text-gray-500">Indikator:</div>
                <div class="space-y-1">
                  <div
                    v-for="indikator in dimensi.indikator"
                    :key="indikator.id"
                    class="text-xs bg-white px-2 py-1 rounded border"
                  >
                    {{ indikator.nama }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Template Penilaian Tab -->
    <div v-if="activeTab === 'template'" class="space-y-6">
      <!-- Action Bar -->
      <div class="flex justify-between items-center">
        <div class="flex space-x-4">
          <button
            @click="showAddTemplateModal = true"
            class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
          >
            <svg class="w-5 h-5 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Tambah Template
          </button>
        </div>
      </div>

      <!-- Template List -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Template Penilaian</h3>
        </div>
        
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Template</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dimensi</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="template in templateList" :key="template.id" class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-medium text-gray-900">{{ template.nama }}</div>
                  <div class="text-sm text-gray-500">{{ template.deskripsi }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="getJenisBadgeClass(template.jenis)" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                    {{ getJenisText(template.jenis) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ template.dimensi_count }} dimensi
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span :class="template.status ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full">
                    {{ template.status ? 'Aktif' : 'Nonaktif' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    @click="editTemplate(template)"
                    class="text-blue-600 hover:text-blue-900 mr-3"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteTemplate(template.id)"
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
    </div>

    <!-- Pengaturan Sistem Tab -->
    <div v-if="activeTab === 'pengaturan'" class="space-y-6">
      <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-semibold text-gray-900">Pengaturan Sistem Karakter</h3>
        </div>
        
        <div class="p-6">
          <form @submit.prevent="savePengaturan" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Skala Penilaian</label>
                <select
                  v-model="pengaturan.skala_penilaian"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                  <option value="1-4">1-4 (Sangat Baik, Baik, Cukup, Kurang)</option>
                  <option value="1-5">1-5 (Sangat Baik, Baik, Cukup, Kurang, Sangat Kurang)</option>
                  <option value="A-D">A-D (A, B, C, D)</option>
                </select>
              </div>
              
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Frekuensi Penilaian</label>
                <select
                  v-model="pengaturan.frekuensi_penilaian"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                >
                  <option value="harian">Harian</option>
                  <option value="mingguan">Mingguan</option>
                  <option value="bulanan">Bulanan</option>
                  <option value="semester">Semester</option>
                </select>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Metode Penilaian</label>
              <div class="space-y-2">
                <label class="flex items-center">
                  <input
                    v-model="pengaturan.metode_observasi"
                    type="checkbox"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                  >
                  <span class="ml-2 text-sm text-gray-700">Observasi</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="pengaturan.metode_self_assessment"
                    type="checkbox"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                  >
                  <span class="ml-2 text-sm text-gray-700">Self Assessment</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="pengaturan.metode_peer_assessment"
                    type="checkbox"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                  >
                  <span class="ml-2 text-sm text-gray-700">Peer Assessment</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="pengaturan.metode_portfolio"
                    type="checkbox"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                  >
                  <span class="ml-2 text-sm text-gray-700">Portfolio</span>
                </label>
              </div>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Notifikasi</label>
              <div class="space-y-2">
                <label class="flex items-center">
                  <input
                    v-model="pengaturan.notifikasi_guru"
                    type="checkbox"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                  >
                  <span class="ml-2 text-sm text-gray-700">Notifikasi ke Guru</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="pengaturan.notifikasi_ortu"
                    type="checkbox"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                  >
                  <span class="ml-2 text-sm text-gray-700">Notifikasi ke Orang Tua</span>
                </label>
                <label class="flex items-center">
                  <input
                    v-model="pengaturan.notifikasi_siswa"
                    type="checkbox"
                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                  >
                  <span class="ml-2 text-sm text-gray-700">Notifikasi ke Siswa</span>
                </label>
              </div>
            </div>
            
            <div class="pt-4">
              <button
                type="submit"
                :disabled="loading"
                class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50"
              >
                <span v-if="loading">Menyimpan...</span>
                <span v-else>Simpan Pengaturan</span>
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Add/Edit Dimensi Modal -->
    <div v-if="showAddDimensiModal || showEditDimensiModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">
            {{ showEditDimensiModal ? 'Edit Dimensi' : 'Tambah Dimensi' }}
          </h3>
          
          <form @submit.prevent="saveDimensi" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama Dimensi</label>
              <input
                v-model="dimensiForm.nama"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan nama dimensi"
              >
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
              <textarea
                v-model="dimensiForm.deskripsi"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan deskripsi dimensi"
              ></textarea>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Indikator</label>
              <div class="space-y-2">
                <div
                  v-for="(indikator, index) in dimensiForm.indikator"
                  :key="index"
                  class="flex items-center space-x-2"
                >
                  <input
                    v-model="indikator.nama"
                    type="text"
                    class="flex-1 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    placeholder="Masukkan indikator"
                  >
                  <button
                    type="button"
                    @click="removeIndikator(index)"
                    class="text-red-600 hover:text-red-900"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                  </button>
                </div>
                <button
                  type="button"
                  @click="addIndikator"
                  class="text-blue-600 hover:text-blue-900 text-sm"
                >
                  + Tambah Indikator
                </button>
              </div>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
              <button
                type="button"
                @click="closeDimensiModal"
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
const activeTab = ref('dimensi')
const showAddDimensiModal = ref(false)
const showEditDimensiModal = ref(false)
const showAddTemplateModal = ref(false)
const showEditTemplateModal = ref(false)
const editingDimensi = ref(null)
const editingTemplate = ref(null)

const tabs = [
  { id: 'dimensi', name: 'Dimensi Karakter' },
  { id: 'template', name: 'Template Penilaian' },
  { id: 'pengaturan', name: 'Pengaturan Sistem' }
]

const dimensiList = ref([])
const templateList = ref([])

const dimensiForm = reactive({
  nama: '',
  deskripsi: '',
  indikator: [{ nama: '' }]
})

const templateForm = reactive({
  nama: '',
  deskripsi: '',
  jenis: '',
  dimensi: [],
  status: true
})

const pengaturan = reactive({
  skala_penilaian: '1-4',
  frekuensi_penilaian: 'bulanan',
  metode_observasi: true,
  metode_self_assessment: false,
  metode_peer_assessment: false,
  metode_portfolio: false,
  notifikasi_guru: true,
  notifikasi_ortu: true,
  notifikasi_siswa: false
})

// Methods
const loadKonfigurasiKarakter = async () => {
  try {
    loading.value = true
    // TODO: Implement API calls
    // const [dimensiRes, templateRes, pengaturanRes] = await Promise.all([
    //   api.get('/konfigurasi-karakter/dimensi'),
    //   api.get('/konfigurasi-karakter/template'),
    //   api.get('/konfigurasi-karakter/pengaturan')
    // ])
    
    // Mock data
    dimensiList.value = [
      {
        id: 1,
        nama: 'Spiritual & Religius',
        deskripsi: 'Kemampuan menghayati dan mengamalkan ajaran agama yang dianut',
        indikator: [
          { id: 1, nama: 'Melaksanakan ibadah sesuai agama' },
          { id: 2, nama: 'Menghormati perbedaan agama' },
          { id: 3, nama: 'Berperilaku jujur dan amanah' }
        ]
      },
      {
        id: 2,
        nama: 'Sosial & Kebangsaan',
        deskripsi: 'Kemampuan berinteraksi dengan lingkungan sosial dan memiliki rasa kebangsaan',
        indikator: [
          { id: 4, nama: 'Menghormati orang lain' },
          { id: 5, nama: 'Mengikuti upacara bendera' },
          { id: 6, nama: 'Menggunakan bahasa Indonesia yang baik' }
        ]
      },
      {
        id: 3,
        nama: 'Gotong Royong',
        deskripsi: 'Kemampuan bekerja sama dalam kelompok dan membantu sesama',
        indikator: [
          { id: 7, nama: 'Bekerja sama dalam kelompok' },
          { id: 8, nama: 'Membantu teman yang kesulitan' },
          { id: 9, nama: 'Berpartisipasi dalam kegiatan sekolah' }
        ]
      }
    ]
    
    templateList.value = [
      {
        id: 1,
        nama: 'Template Penilaian Harian',
        deskripsi: 'Template untuk penilaian karakter harian',
        jenis: 'harian',
        dimensi_count: 6,
        status: true
      },
      {
        id: 2,
        nama: 'Template Penilaian Bulanan',
        deskripsi: 'Template untuk penilaian karakter bulanan',
        jenis: 'bulanan',
        dimensi_count: 6,
        status: true
      }
    ]
  } catch (error) {
    console.error('Error loading konfigurasi karakter:', error)
  } finally {
    loading.value = false
  }
}

const getJenisBadgeClass = (jenis) => {
  const classes = {
    harian: 'bg-green-100 text-green-800',
    mingguan: 'bg-blue-100 text-blue-800',
    bulanan: 'bg-yellow-100 text-yellow-800',
    semester: 'bg-purple-100 text-purple-800'
  }
  return classes[jenis] || 'bg-gray-100 text-gray-800'
}

const getJenisText = (jenis) => {
  const texts = {
    harian: 'Harian',
    mingguan: 'Mingguan',
    bulanan: 'Bulanan',
    semester: 'Semester'
  }
  return texts[jenis] || jenis
}

const editDimensi = (dimensi) => {
  editingDimensi.value = dimensi
  Object.assign(dimensiForm, {
    nama: dimensi.nama,
    deskripsi: dimensi.deskripsi,
    indikator: dimensi.indikator.map(i => ({ nama: i.nama }))
  })
  showEditDimensiModal.value = true
}

const deleteDimensi = async (id) => {
  if (confirm('Apakah Anda yakin ingin menghapus dimensi ini?')) {
    try {
      // TODO: Implement API call
      // await api.delete(`/konfigurasi-karakter/dimensi/${id}`)
      
      dimensiList.value = dimensiList.value.filter(d => d.id !== id)
    } catch (error) {
      console.error('Error deleting dimensi:', error)
    }
  }
}

const addIndikator = () => {
  dimensiForm.indikator.push({ nama: '' })
}

const removeIndikator = (index) => {
  dimensiForm.indikator.splice(index, 1)
}

const saveDimensi = async () => {
  try {
    loading.value = true
    
    if (showEditDimensiModal.value) {
      // TODO: Implement API call for update
      // await api.put(`/konfigurasi-karakter/dimensi/${editingDimensi.value.id}`, dimensiForm)
      
      const index = dimensiList.value.findIndex(d => d.id === editingDimensi.value.id)
      if (index !== -1) {
        dimensiList.value[index] = {
          ...editingDimensi.value,
          ...dimensiForm,
          indikator: dimensiForm.indikator.map((i, idx) => ({ id: idx + 1, nama: i.nama }))
        }
      }
    } else {
      // TODO: Implement API call for create
      // const response = await api.post('/konfigurasi-karakter/dimensi', dimensiForm)
      
      const newDimensi = {
        id: Date.now(),
        ...dimensiForm,
        indikator: dimensiForm.indikator.map((i, idx) => ({ id: idx + 1, nama: i.nama }))
      }
      dimensiList.value.push(newDimensi)
    }
    
    closeDimensiModal()
  } catch (error) {
    console.error('Error saving dimensi:', error)
  } finally {
    loading.value = false
  }
}

const closeDimensiModal = () => {
  showAddDimensiModal.value = false
  showEditDimensiModal.value = false
  editingDimensi.value = null
  Object.assign(dimensiForm, {
    nama: '',
    deskripsi: '',
    indikator: [{ nama: '' }]
  })
}

const editTemplate = (template) => {
  editingTemplate.value = template
  Object.assign(templateForm, template)
  showEditTemplateModal.value = true
}

const deleteTemplate = async (id) => {
  if (confirm('Apakah Anda yakin ingin menghapus template ini?')) {
    try {
      // TODO: Implement API call
      // await api.delete(`/konfigurasi-karakter/template/${id}`)
      
      templateList.value = templateList.value.filter(t => t.id !== id)
    } catch (error) {
      console.error('Error deleting template:', error)
    }
  }
}

const saveTemplate = async () => {
  try {
    loading.value = true
    
    if (showEditTemplateModal.value) {
      // TODO: Implement API call for update
      // await api.put(`/konfigurasi-karakter/template/${editingTemplate.value.id}`, templateForm)
      
      const index = templateList.value.findIndex(t => t.id === editingTemplate.value.id)
      if (index !== -1) {
        templateList.value[index] = { ...editingTemplate.value, ...templateForm }
      }
    } else {
      // TODO: Implement API call for create
      // const response = await api.post('/konfigurasi-karakter/template', templateForm)
      
      const newTemplate = {
        id: Date.now(),
        dimensi_count: templateForm.dimensi.length,
        ...templateForm
      }
      templateList.value.push(newTemplate)
    }
    
    closeTemplateModal()
  } catch (error) {
    console.error('Error saving template:', error)
  } finally {
    loading.value = false
  }
}

const closeTemplateModal = () => {
  showAddTemplateModal.value = false
  showEditTemplateModal.value = false
  editingTemplate.value = null
  Object.assign(templateForm, {
    nama: '',
    deskripsi: '',
    jenis: '',
    dimensi: [],
    status: true
  })
}

const savePengaturan = async () => {
  try {
    loading.value = true
    // TODO: Implement API call
    // await api.put('/konfigurasi-karakter/pengaturan', pengaturan)
    
    console.log('Pengaturan saved:', pengaturan)
    // Show success message
  } catch (error) {
    console.error('Error saving pengaturan:', error)
    // Show error message
  } finally {
    loading.value = false
  }
}

// Lifecycle
onMounted(() => {
  loadKonfigurasiKarakter()
})
</script>

<style scoped>
.konfigurasi-karakter-view {
  @apply p-6;
}
</style>
