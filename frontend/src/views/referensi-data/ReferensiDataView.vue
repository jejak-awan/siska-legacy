<template>
  <div class="p-6">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900 mb-2">Referensi Data</h1>
      <p class="text-gray-600">Kelola data referensi yang dapat digunakan oleh proses lain dalam sistem</p>
    </div>

    <!-- Action Bar -->
    <div class="mb-6 flex justify-between items-center">
      <div class="flex space-x-4">
        <div class="flex-1 max-w-md">
          <input 
            v-model="searchQuery"
            type="text" 
            placeholder="Cari referensi data..." 
            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
        </div>
        <select v-model="selectedCategory" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
          <option value="">Semua Kategori</option>
          <option v-for="category in categories" :key="category.id" :value="category.id">
            {{ category.name }}
          </option>
        </select>
      </div>
      <div class="flex space-x-3">
        <button 
          @click="showCreateCategoryModal = true"
          class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          + Kategori Baru
        </button>
        <button 
          @click="showCreateDataModal = true"
          class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          + Data Baru
        </button>
      </div>
    </div>

    <!-- Categories Table -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-8">
      <div class="px-6 py-4 border-b border-gray-200">
        <h3 class="text-lg font-semibold text-gray-900">Kategori Referensi Data</h3>
      </div>
      
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kategori</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Data</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir Update</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr 
              v-for="category in filteredCategories" 
              :key="category.id"
              class="hover:bg-gray-50 cursor-pointer"
              @click="selectCategory(category)"
            >
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="text-sm font-medium text-gray-900">{{ category.name }}</div>
                </div>
              </td>
              <td class="px-6 py-4">
                <div class="text-sm text-gray-900 max-w-xs truncate">{{ category.description }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                  {{ category.dataCount }} data
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(category.updatedAt) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <button 
                  @click.stop="toggleCategoryStatus(category)"
                  :class="[
                    'p-1 rounded-full transition-colors',
                    category.isActive 
                      ? 'text-green-600 hover:text-green-800 hover:bg-green-50' 
                      : 'text-gray-400 hover:text-gray-600 hover:bg-gray-50'
                  ]"
                  :title="category.isActive ? 'Klik untuk nonaktifkan' : 'Klik untuk aktifkan'"
                >
                  <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                </button>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button 
                    @click.stop="viewCategoryData(category)"
                    class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50"
                    title="Lihat Data"
                  >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                  </button>
                  <button 
                    @click.stop="editCategory(category)"
                    class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50"
                    title="Edit Kategori"
                  >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </button>
                  <button 
                    @click.stop="deleteCategoryWithCheck(category)"
                    class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50"
                    title="Hapus Kategori"
                  >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Empty State -->
      <div v-if="filteredCategories.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada kategori referensi</h3>
        <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat kategori referensi data baru.</p>
        <div class="mt-6">
          <button 
            @click="showCreateCategoryModal = true"
            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Buat Kategori Baru
          </button>
        </div>
      </div>
    </div>

    <!-- View Data Modal -->
    <div v-if="showViewDataModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-6xl shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">{{ selectedCategoryData?.name }} - Data Referensi</h3>
            <button 
              @click="showViewDataModal = false"
              class="text-gray-400 hover:text-gray-600"
            >
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>
          
          <!-- Action Bar -->
          <div class="mb-4 flex justify-between items-center">
            <div class="flex space-x-4">
              <div class="flex-1 max-w-md">
                <input 
                  v-model="dataSearchQuery"
                  type="text" 
                  placeholder="Cari data..." 
                  class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
              </div>
            </div>
            <button 
              @click="showCreateDataModal = true"
              class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              + Tambah Data
            </button>
          </div>

          <!-- Data Table -->
          <div class="overflow-x-auto max-h-96">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50 sticky top-0">
                <tr>
                  <th v-for="field in selectedCategoryData?.fields" :key="field.id" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    {{ field.label }}
                  </th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="data in filteredData" :key="data.id">
                  <td v-for="field in selectedCategoryData?.fields" :key="field.id" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ data[field.name] }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <div class="flex space-x-2">
                      <button 
                        @click="editData(data)" 
                        class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50"
                        title="Edit Data"
                      >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                      </button>
                      <button 
                        @click="deleteDataWithCheck(data)" 
                        class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50"
                        title="Hapus Data"
                      >
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Empty State for Data -->
          <div v-if="filteredData.length === 0" class="text-center py-8">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data</h3>
            <p class="mt-1 text-sm text-gray-500">Mulai dengan menambahkan data referensi baru.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Create Category Modal -->
    <div v-if="showCreateCategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Buat Kategori Referensi Baru</h3>
          <form @submit.prevent="createCategory">
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
              <input 
                v-model="newCategory.name"
                type="text" 
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan nama kategori"
              >
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
              <textarea 
                v-model="newCategory.description"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                rows="3"
                placeholder="Masukkan deskripsi kategori"
              ></textarea>
            </div>
            <div class="flex justify-end space-x-3">
              <button 
                type="button"
                @click="showCreateCategoryModal = false"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
              >
                Batal
              </button>
              <button 
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
              >
                Buat Kategori
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Create Data Modal -->
    <div v-if="showCreateDataModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Tambah Data Referensi</h3>
          <form @submit.prevent="createData">
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
              <select 
                v-model="newData.categoryId"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="">Pilih Kategori</option>
                <option v-for="category in categories" :key="category.id" :value="category.id">
                  {{ category.name }}
                </option>
              </select>
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Nama Data</label>
              <input 
                v-model="newData.name"
                type="text" 
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan nama data"
              >
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
              <textarea 
                v-model="newData.description"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                rows="3"
                placeholder="Masukkan deskripsi data"
              ></textarea>
            </div>
            <div class="flex justify-end space-x-3">
              <button 
                type="button"
                @click="showCreateDataModal = false"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
              >
                Batal
              </button>
              <button 
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
              >
                Tambah Data
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Edit Data Modal -->
    <div v-if="showEditDataModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Edit Data Referensi</h3>
          <form @submit.prevent="updateData">
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Nama Data</label>
              <input 
                v-model="editDataForm.name"
                type="text" 
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan nama data"
              >
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Kode (Opsional)</label>
              <input 
                v-model="editDataForm.code"
                type="text" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan kode data"
              >
            </div>
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
              <textarea 
                v-model="editDataForm.description"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                rows="3"
                placeholder="Masukkan deskripsi data"
              ></textarea>
            </div>
            <div class="flex justify-end space-x-3">
              <button 
                type="button"
                @click="showEditDataModal = false"
                class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
              >
                Batal
              </button>
              <button 
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
              >
                Simpan Perubahan
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
            <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
          </div>
          <div class="mt-3 text-center">
            <h3 class="text-lg font-medium text-gray-900">Konfirmasi Hapus</h3>
            <div class="mt-2">
              <p class="text-sm text-gray-500">
                Apakah Anda yakin ingin menghapus {{ deleteModalData?.type === 'category' ? 'kategori' : 'data' }} 
                <strong>"{{ deleteModalData?.name }}"</strong>?
              </p>
              
              <!-- Usage Information -->
              <div v-if="deleteModalData?.usage && deleteModalData.usage.length > 0" class="mt-4 p-3 bg-yellow-50 border border-yellow-200 rounded-md">
                <p class="text-sm text-yellow-800 font-medium mb-2">Referensi ini sedang digunakan oleh:</p>
                <ul class="text-sm text-yellow-700 list-disc list-inside">
                  <li v-for="usage in deleteModalData.usage" :key="usage">{{ usage }}</li>
                </ul>
                <p class="text-sm text-yellow-800 mt-2 font-medium">
                  Referensi tidak dapat dihapus karena masih digunakan. Nonaktifkan terlebih dahulu.
                </p>
              </div>
            </div>
          </div>
          <div class="mt-5 flex justify-center space-x-3">
            <button 
              @click="showDeleteModal = false"
              class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50"
            >
              Batal
            </button>
            <button 
              @click="confirmDelete"
              :disabled="deleteModalData?.usage && deleteModalData.usage.length > 0"
              :class="[
                'px-4 py-2 rounded-md text-white',
                deleteModalData?.usage && deleteModalData.usage.length > 0
                  ? 'bg-gray-400 cursor-not-allowed'
                  : 'bg-red-600 hover:bg-red-700'
              ]"
            >
              Hapus
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useValidation, validationSchemas } from '../../composables/useValidation'
import { useApi } from '../../composables/useApi'
import { useToast } from '../../plugins/toast.js'

const searchQuery = ref('')
const selectedCategory = ref('')
const selectedCategoryData = ref(null)
const showCreateCategoryModal = ref(false)
const showCreateDataModal = ref(false)
const showEditDataModal = ref(false)
const showViewDataModal = ref(false)
const showDeleteModal = ref(false)
const dataSearchQuery = ref('')
const deleteModalData = ref(null)

// Setup validation and API
const toast = useToast()
const api = useApi()

// Category form validation
const categoryForm = useValidation(validationSchemas.referenceCategory, {
  name: '',
  description: '',
  fields_config: [
    { name: 'name', label: 'Nama', type: 'text', required: true },
    { name: 'code', label: 'Kode', type: 'text', required: false }
  ]
})

// Data form validation
const dataForm = useValidation(validationSchemas.referenceData, {
  category_id: '',
  code: '',
  name: '',
  description: ''
})

// Edit data form validation
const editDataForm = useValidation(validationSchemas.referenceData, {
  id: null,
  category_id: '',
  code: '',
  name: '',
  description: ''
})

// Sample data - in real app, this would come from API
const categories = ref([
  {
    id: 1,
    name: 'Jenis Kelamin',
    description: 'Referensi data jenis kelamin',
    dataCount: 2,
    isActive: true,
    updatedAt: new Date(),
    fields: [
      { id: 1, name: 'code', label: 'Kode', type: 'text' },
      { id: 2, name: 'name', label: 'Nama', type: 'text' }
    ],
    data: [
      { id: 1, code: 'L', name: 'Laki-laki', isActive: true },
      { id: 2, code: 'P', name: 'Perempuan', isActive: true }
    ]
  },
  {
    id: 2,
    name: 'Agama',
    description: 'Referensi data agama',
    dataCount: 6,
    isActive: true,
    updatedAt: new Date(),
    fields: [
      { id: 1, name: 'code', label: 'Kode', type: 'text' },
      { id: 2, name: 'name', label: 'Nama', type: 'text' }
    ],
    data: [
      { id: 1, code: 'ISL', name: 'Islam', isActive: true },
      { id: 2, code: 'KRI', name: 'Kristen', isActive: true },
      { id: 3, code: 'KAT', name: 'Katolik', isActive: true },
      { id: 4, code: 'HIN', name: 'Hindu', isActive: true },
      { id: 5, code: 'BUD', name: 'Buddha', isActive: true },
      { id: 6, code: 'KON', name: 'Konghucu', isActive: true }
    ]
  },
  {
    id: 3,
    name: 'Status Perkawinan',
    description: 'Referensi data status perkawinan',
    dataCount: 4,
    isActive: true,
    updatedAt: new Date(),
    fields: [
      { id: 1, name: 'code', label: 'Kode', type: 'text' },
      { id: 2, name: 'name', label: 'Nama', type: 'text' }
    ],
    data: [
      { id: 1, code: 'BLM', name: 'Belum Menikah', isActive: true },
      { id: 2, code: 'NIK', name: 'Menikah', isActive: true },
      { id: 3, code: 'CER', name: 'Cerai Hidup', isActive: true },
      { id: 4, code: 'MEN', name: 'Cerai Mati', isActive: true }
    ]
  }
])

const filteredCategories = computed(() => {
  let filtered = categories.value

  if (searchQuery.value) {
    filtered = filtered.filter(category => 
      category.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      category.description.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }

  if (selectedCategory.value) {
    filtered = filtered.filter(category => category.id === selectedCategory.value)
  }

  return filtered
})

const filteredData = computed(() => {
  if (!selectedCategoryData.value) return []
  
  let filtered = selectedCategoryData.value.data

  if (dataSearchQuery.value) {
    filtered = filtered.filter(data => 
      Object.values(data).some(value => 
        String(value).toLowerCase().includes(dataSearchQuery.value.toLowerCase())
      )
    )
  }

  return filtered
})

const selectCategory = (category) => {
  selectedCategoryData.value = category
}

const viewCategoryData = (category) => {
  selectedCategoryData.value = category
  showViewDataModal.value = true
  dataSearchQuery.value = '' // Reset search
}

const toggleCategoryStatus = (category) => {
  category.isActive = !category.isActive
  category.updatedAt = new Date()
}

const editCategory = (category) => {
  // Implementation for editing category
  console.log('Edit category:', category)
}

const deleteCategoryWithCheck = (category) => {
  // Check if category is being used
  const usage = checkCategoryUsage(category)
  
  deleteModalData.value = {
    type: 'category',
    id: category.id,
    name: category.name,
    usage: usage
  }
  
  showDeleteModal.value = true
}

const checkCategoryUsage = (category) => {
  // Sample usage check - in real app, this would check database
  const usage = []
  
  if (category.name === 'Jenis Kelamin') {
    usage.push('Data Siswa (Field: Jenis Kelamin)')
    usage.push('Data Guru (Field: Jenis Kelamin)')
  } else if (category.name === 'Agama') {
    usage.push('Data Siswa (Field: Agama)')
    usage.push('Data Guru (Field: Agama)')
  } else if (category.name === 'Status Perkawinan') {
    usage.push('Data Guru (Field: Status Perkawinan)')
  }
  
  return usage
}

const checkDataUsage = (data, category) => {
  // Sample usage check - in real app, this would check database
  const usage = []
  
  if (data.code === 'L' || data.code === 'P') {
    usage.push('Data Siswa (Field: Jenis Kelamin)')
    usage.push('Data Guru (Field: Jenis Kelamin)')
  } else if (data.code === 'ISL' || data.code === 'KRI' || data.code === 'KAT') {
    usage.push('Data Siswa (Field: Agama)')
    usage.push('Data Guru (Field: Agama)')
  }
  
  return usage
}

const editData = (data) => {
  editDataForm.value = {
    id: data.id,
    name: data.name,
    code: data.code || '',
    description: data.description || ''
  }
  showEditDataModal.value = true
}

const updateData = () => {
  // Find and update the data
  const category = categories.value.find(c => c.id === selectedCategoryData.value.id)
  if (category) {
    const dataIndex = category.data.findIndex(d => d.id === editDataForm.value.id)
    if (dataIndex > -1) {
      category.data[dataIndex] = {
        ...category.data[dataIndex],
        name: editDataForm.value.name,
        code: editDataForm.value.code,
        description: editDataForm.value.description
      }
    }
  }
  
  showEditDataModal.value = false
  editDataForm.value = { id: null, name: '', code: '', description: '' }
}

const deleteDataWithCheck = (data) => {
  // Check if data is being used
  const usage = checkDataUsage(data, selectedCategoryData.value)
  
  deleteModalData.value = {
    type: 'data',
    id: data.id,
    name: data.name,
    usage: usage
  }
  
  showDeleteModal.value = true
}

const confirmDelete = () => {
  if (deleteModalData.value.type === 'category') {
    const index = categories.value.findIndex(c => c.id === deleteModalData.value.id)
    if (index > -1) {
      categories.value.splice(index, 1)
    }
  } else if (deleteModalData.value.type === 'data') {
    const category = categories.value.find(c => c.id === selectedCategoryData.value.id)
    if (category) {
      const dataIndex = category.data.findIndex(d => d.id === deleteModalData.value.id)
      if (dataIndex > -1) {
        category.data.splice(dataIndex, 1)
        category.dataCount = category.data.length
      }
    }
  }
  
  showDeleteModal.value = false
  deleteModalData.value = null
}

const createCategory = () => {
  const newId = Math.max(...categories.value.map(c => c.id)) + 1
  categories.value.push({
    id: newId,
    name: newCategory.value.name,
    description: newCategory.value.description,
    dataCount: 0,
    updatedAt: new Date(),
    fields: [],
    data: []
  })
  
  newCategory.value = { name: '', description: '' }
  showCreateCategoryModal.value = false
}

const createData = () => {
  // Implementation for creating new data
  console.log('Create data:', newData.value)
  newData.value = { categoryId: '', name: '', description: '' }
  showCreateDataModal.value = false
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID')
}
</script>
