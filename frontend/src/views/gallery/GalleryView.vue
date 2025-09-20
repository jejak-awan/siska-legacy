<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Page Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-foreground">Gallery</h1>
            <p class="mt-2 text-lg text-muted-foreground">
              Kelola dan tampilkan foto-foto kegiatan sekolah
            </p>
          </div>
          <div class="flex items-center space-x-4">
            <Button @click="showUploadModal = true" class="bg-primary text-primary-foreground hover:bg-primary/90">
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              Upload Foto
            </Button>
          </div>
        </div>
      </div>

      <!-- Gallery Filters -->
      <div class="mb-8">
        <div class="flex flex-col sm:flex-row gap-4">
          <div class="flex-1">
            <div class="relative">
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
              </div>
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Cari foto..."
                class="block w-full pl-10 pr-3 py-2 border border-input rounded-md bg-background text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring"
              >
            </div>
          </div>
          <div class="flex gap-2">
            <select
              v-model="categoryFilter"
              class="px-3 py-2 border border-input rounded-md bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring"
            >
              <option value="">Semua Kategori</option>
              <option value="kegiatan">Kegiatan</option>
              <option value="acara">Acara</option>
              <option value="prestasi">Prestasi</option>
              <option value="sekolah">Sekolah</option>
            </select>
            <select
              v-model="sortBy"
              class="px-3 py-2 border border-input rounded-md bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring"
            >
              <option value="newest">Terbaru</option>
              <option value="oldest">Terlama</option>
              <option value="name">Nama A-Z</option>
            </select>
          </div>
        </div>
      </div>

      <!-- View Toggle -->
      <div class="mb-6 flex justify-between items-center">
        <div class="flex items-center space-x-2">
          <button
            @click="viewMode = 'grid'"
            :class="[
              'p-2 rounded-md transition-colors',
              viewMode === 'grid'
                ? 'bg-primary text-primary-foreground'
                : 'text-muted-foreground hover:text-foreground hover:bg-accent'
            ]"
          >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
            </svg>
          </button>
          <button
            @click="viewMode = 'list'"
            :class="[
              'p-2 rounded-md transition-colors',
              viewMode === 'list'
                ? 'bg-primary text-primary-foreground'
                : 'text-muted-foreground hover:text-foreground hover:bg-accent'
            ]"
          >
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
            </svg>
          </button>
        </div>
        <div class="text-sm text-muted-foreground">
          {{ filteredImages.length }} foto
        </div>
      </div>

      <!-- Gallery Grid -->
      <div v-if="viewMode === 'grid'" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
        <div
          v-for="image in filteredImages"
          :key="image.id"
          class="group relative aspect-square bg-muted rounded-lg overflow-hidden cursor-pointer"
          @click="viewImage(image)"
        >
          <img
            :src="image.url"
            :alt="image.title"
            class="w-full h-full object-cover transition-transform group-hover:scale-105"
          >
          <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors">
            <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
              <div class="text-white text-center">
                <svg class="h-8 w-8 mx-auto mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <p class="text-sm font-medium">{{ image.title }}</p>
              </div>
            </div>
          </div>
          <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
            <div class="flex space-x-1">
              <button
                @click.stop="editImage(image)"
                class="p-1 bg-white/90 rounded-full hover:bg-white transition-colors"
              >
                <svg class="h-4 w-4 text-gray-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </button>
              <button
                @click.stop="deleteImage(image)"
                class="p-1 bg-white/90 rounded-full hover:bg-white transition-colors"
              >
                <svg class="h-4 w-4 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Gallery List -->
      <div v-else class="space-y-4">
        <div
          v-for="image in filteredImages"
          :key="image.id"
          class="flex items-center space-x-4 p-4 bg-card rounded-lg border border-border hover:shadow-md transition-shadow cursor-pointer"
          @click="viewImage(image)"
        >
          <div class="flex-shrink-0 w-20 h-20 bg-muted rounded-lg overflow-hidden">
            <img
              :src="image.url"
              :alt="image.title"
              class="w-full h-full object-cover"
            >
          </div>
          <div class="flex-1 min-w-0">
            <h3 class="text-sm font-medium text-foreground truncate">{{ image.title }}</h3>
            <p class="text-sm text-muted-foreground truncate">{{ image.description }}</p>
            <div class="flex items-center space-x-4 mt-1">
              <span class="text-xs text-muted-foreground bg-muted px-2 py-1 rounded">
                {{ getCategoryText(image.category) }}
              </span>
              <span class="text-xs text-muted-foreground">
                {{ formatDate(image.created_at) }}
              </span>
              <span class="text-xs text-muted-foreground">
                {{ formatFileSize(image.file_size) }}
              </span>
            </div>
          </div>
          <div class="flex items-center space-x-2">
            <button
              @click.stop="editImage(image)"
              class="p-2 text-muted-foreground hover:text-foreground hover:bg-accent rounded-md transition-colors"
            >
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
            </button>
            <button
              @click.stop="deleteImage(image)"
              class="p-2 text-destructive hover:text-destructive hover:bg-destructive/10 rounded-md transition-colors"
            >
              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- Empty State -->
      <div v-if="filteredImages.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-foreground">Tidak ada foto</h3>
        <p class="mt-1 text-sm text-muted-foreground">
          {{ searchQuery ? 'Tidak ada foto yang sesuai dengan pencarian.' : 'Mulai dengan mengupload foto pertama Anda.' }}
        </p>
        <div class="mt-6">
          <Button @click="showUploadModal = true" v-if="!searchQuery">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Upload Foto
          </Button>
        </div>
      </div>
    </div>

    <!-- Upload Modal -->
    <GalleryUploadModal
      v-if="showUploadModal"
      @close="showUploadModal = false"
      @uploaded="handleImageUploaded"
    />

    <!-- Edit Modal -->
    <GalleryEditModal
      v-if="showEditModal"
      :image="editingImage"
      @close="showEditModal = false"
      @updated="handleImageUpdated"
    />

    <!-- View Modal -->
    <GalleryViewModal
      v-if="showViewModal"
      :image="viewingImage"
      :images="filteredImages"
      @close="showViewModal = false"
      @edit="editImage"
      @delete="deleteImage"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useToast } from '../../plugins/toast.js'
import Button from '../../components/ui/Button.vue'
import GalleryUploadModal from '../../components/gallery/GalleryUploadModal.vue'
import GalleryEditModal from '../../components/gallery/GalleryEditModal.vue'
import GalleryViewModal from '../../components/gallery/GalleryViewModal.vue'

const toast = useToast()

// State
const searchQuery = ref('')
const categoryFilter = ref('')
const sortBy = ref('newest')
const viewMode = ref('grid')
const showUploadModal = ref(false)
const showEditModal = ref(false)
const showViewModal = ref(false)
const editingImage = ref(null)
const viewingImage = ref(null)

// Mock data - replace with actual API calls
const images = ref([
  {
    id: 1,
    title: 'Upacara Bendera',
    description: 'Upacara bendera setiap hari Senin',
    url: 'https://via.placeholder.com/400x300/3B82F6/FFFFFF?text=Upacara+Bendera',
    category: 'kegiatan',
    file_size: 1024000,
    created_at: '2024-01-15T10:00:00Z',
    updated_at: '2024-01-15T10:00:00Z'
  },
  {
    id: 2,
    title: 'Lomba Cerdas Cermat',
    description: 'Lomba cerdas cermat antar kelas',
    url: 'https://via.placeholder.com/400x300/10B981/FFFFFF?text=Lomba+Cerdas+Cermat',
    category: 'acara',
    file_size: 2048000,
    created_at: '2024-01-14T14:30:00Z',
    updated_at: '2024-01-14T14:30:00Z'
  },
  {
    id: 3,
    title: 'Prestasi Siswa',
    description: 'Penghargaan prestasi siswa berprestasi',
    url: 'https://via.placeholder.com/400x300/F59E0B/FFFFFF?text=Prestasi+Siswa',
    category: 'prestasi',
    file_size: 1536000,
    created_at: '2024-01-13T09:15:00Z',
    updated_at: '2024-01-13T09:15:00Z'
  }
])

// Computed
const filteredImages = computed(() => {
  let filtered = images.value

  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(image =>
      image.title.toLowerCase().includes(query) ||
      image.description.toLowerCase().includes(query)
    )
  }

  // Filter by category
  if (categoryFilter.value) {
    filtered = filtered.filter(image => image.category === categoryFilter.value)
  }

  // Sort
  switch (sortBy.value) {
    case 'newest':
      filtered = filtered.sort((a, b) => new Date(b.created_at) - new Date(a.created_at))
      break
    case 'oldest':
      filtered = filtered.sort((a, b) => new Date(a.created_at) - new Date(b.created_at))
      break
    case 'name':
      filtered = filtered.sort((a, b) => a.title.localeCompare(b.title))
      break
  }

  return filtered
})

// Methods
const getCategoryText = (category) => {
  switch (category) {
    case 'kegiatan':
      return 'Kegiatan'
    case 'acara':
      return 'Acara'
    case 'prestasi':
      return 'Prestasi'
    case 'sekolah':
      return 'Sekolah'
    default:
      return category
  }
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric'
  })
}

const formatFileSize = (bytes) => {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

const viewImage = (image) => {
  viewingImage.value = image
  showViewModal.value = true
}

const editImage = (image) => {
  editingImage.value = image
  showEditModal.value = true
}

const deleteImage = async (image) => {
  if (confirm(`Apakah Anda yakin ingin menghapus "${image.title}"?`)) {
    try {
      // TODO: Implement delete API call
      const index = images.value.findIndex(item => item.id === image.id)
      if (index > -1) {
        images.value.splice(index, 1)
        toast.success('Foto berhasil dihapus')
      }
    } catch (error) {
      toast.error('Gagal menghapus foto')
    }
  }
}

const handleImageUploaded = (newImage) => {
  images.value.unshift(newImage)
  showUploadModal.value = false
  toast.success('Foto berhasil diupload')
}

const handleImageUpdated = (updatedImage) => {
  const index = images.value.findIndex(item => item.id === updatedImage.id)
  if (index > -1) {
    images.value[index] = updatedImage
  }
  showEditModal.value = false
  toast.success('Foto berhasil diperbarui')
}

// Lifecycle
onMounted(() => {
  // Load images from API
})
</script>
