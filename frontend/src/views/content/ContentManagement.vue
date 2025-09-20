<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Page Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-foreground">Manajemen Konten</h1>
            <p class="mt-2 text-lg text-muted-foreground">
              Kelola berita, kegiatan, dan program sekolah
            </p>
          </div>
          <div class="flex items-center space-x-4">
            <Button @click="showCreateModal = true" class="bg-primary text-primary-foreground hover:bg-primary/90">
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              Buat Konten
            </Button>
          </div>
        </div>
      </div>

      <!-- Content Tabs -->
      <div class="mb-8">
        <div class="border-b border-border">
          <nav class="-mb-px flex space-x-8">
            <button
              v-for="tab in tabs"
              :key="tab.id"
              @click="activeTab = tab.id"
              :class="[
                'py-2 px-1 border-b-2 font-medium text-sm transition-colors',
                activeTab === tab.id
                  ? 'border-primary text-primary'
                  : 'border-transparent text-muted-foreground hover:text-foreground hover:border-border'
              ]"
            >
              {{ tab.name }}
              <span class="ml-2 bg-muted text-muted-foreground px-2 py-1 rounded-full text-xs">
                {{ getContentCount(tab.id) }}
              </span>
            </button>
          </nav>
        </div>
      </div>

      <!-- Content List -->
      <div class="space-y-6">
        <!-- Search and Filters -->
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
                placeholder="Cari konten..."
                class="block w-full pl-10 pr-3 py-2 border border-input rounded-md bg-background text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring"
              >
            </div>
          </div>
          <div class="flex gap-2">
            <select
              v-model="statusFilter"
              class="px-3 py-2 border border-input rounded-md bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring"
            >
              <option value="">Semua Status</option>
              <option value="draft">Draft</option>
              <option value="published">Published</option>
              <option value="archived">Archived</option>
            </select>
            <select
              v-model="categoryFilter"
              class="px-3 py-2 border border-input rounded-md bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring"
            >
              <option value="">Semua Kategori</option>
              <option value="berita">Berita</option>
              <option value="kegiatan">Kegiatan</option>
              <option value="program">Program</option>
              <option value="pengumuman">Pengumuman</option>
            </select>
          </div>
        </div>

        <!-- Content Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <Card
            v-for="content in filteredContent"
            :key="content.id"
            class="cursor-pointer hover:shadow-lg transition-shadow"
            @click="viewContent(content)"
          >
            <div class="relative">
              <img
                v-if="content.featured_image"
                :src="content.featured_image"
                :alt="content.title"
                class="w-full h-48 object-cover rounded-t-lg"
              >
              <div
                v-else
                class="w-full h-48 bg-muted flex items-center justify-center rounded-t-lg"
              >
                <svg class="h-12 w-12 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
              </div>
              <div class="absolute top-2 right-2">
                <span
                  :class="[
                    'px-2 py-1 rounded-full text-xs font-medium',
                    getStatusClass(content.status)
                  ]"
                >
                  {{ getStatusText(content.status) }}
                </span>
              </div>
            </div>
            <CardContent class="p-4">
              <div class="flex items-center justify-between mb-2">
                <span class="text-xs text-muted-foreground bg-muted px-2 py-1 rounded">
                  {{ getCategoryText(content.category) }}
                </span>
                <span class="text-xs text-muted-foreground">
                  {{ formatDate(content.created_at) }}
                </span>
              </div>
              <h3 class="font-semibold text-foreground mb-2 line-clamp-2">
                {{ content.title }}
              </h3>
              <p class="text-sm text-muted-foreground line-clamp-3 mb-4">
                {{ content.excerpt }}
              </p>
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                  <div class="h-6 w-6 rounded-full bg-primary/10 flex items-center justify-center">
                    <span class="text-xs font-medium text-primary">
                      {{ content.author?.username?.charAt(0).toUpperCase() }}
                    </span>
                  </div>
                  <span class="text-xs text-muted-foreground">
                    {{ content.author?.username }}
                  </span>
                </div>
                <div class="flex items-center space-x-1">
                  <Button
                    variant="ghost"
                    size="sm"
                    @click.stop="editContent(content)"
                    class="h-8 w-8 p-0"
                  >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                  </Button>
                  <Button
                    variant="ghost"
                    size="sm"
                    @click.stop="deleteContent(content)"
                    class="h-8 w-8 p-0 text-destructive hover:text-destructive"
                  >
                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                  </Button>
                </div>
              </div>
            </CardContent>
          </Card>
        </div>

        <!-- Empty State -->
        <div v-if="filteredContent.length === 0" class="text-center py-12">
          <svg class="mx-auto h-12 w-12 text-muted-foreground" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          <h3 class="mt-2 text-sm font-medium text-foreground">Tidak ada konten</h3>
          <p class="mt-1 text-sm text-muted-foreground">
            {{ searchQuery ? 'Tidak ada konten yang sesuai dengan pencarian.' : 'Mulai dengan membuat konten pertama Anda.' }}
          </p>
          <div class="mt-6">
            <Button @click="showCreateModal = true" v-if="!searchQuery">
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
              Buat Konten
            </Button>
          </div>
        </div>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <ContentModal
      v-if="showCreateModal || showEditModal"
      :content="editingContent"
      :is-edit="showEditModal"
      @close="closeModal"
      @saved="handleContentSaved"
    />

    <!-- View Modal -->
    <ContentViewModal
      v-if="showViewModal"
      :content="viewingContent"
      @close="showViewModal = false"
      @edit="editContent"
      @delete="deleteContent"
    />
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { usePublicStore } from '../../stores/public'
import { useToast } from '../../plugins/toast.js'
import Button from '../../components/ui/Button.vue'
import Card from '../../components/ui/Card.vue'
import CardContent from '../../components/ui/CardContent.vue'
import ContentModal from '../../components/content/ContentModal.vue'
import ContentViewModal from '../../components/content/ContentViewModal.vue'

const publicStore = usePublicStore()
const toast = useToast()

// State
const activeTab = ref('all')
const searchQuery = ref('')
const statusFilter = ref('')
const categoryFilter = ref('')
const showCreateModal = ref(false)
const showEditModal = ref(false)
const showViewModal = ref(false)
const editingContent = ref(null)
const viewingContent = ref(null)

// Mock data - replace with actual API calls
const content = ref([
  {
    id: 1,
    title: 'Pembukaan Tahun Ajaran Baru 2024/2025',
    excerpt: 'Selamat datang di tahun ajaran baru dengan berbagai program unggulan...',
    content: 'Konten lengkap...',
    category: 'pengumuman',
    status: 'published',
    featured_image: null,
    author: { username: 'admin' },
    created_at: '2024-01-15T10:00:00Z',
    updated_at: '2024-01-15T10:00:00Z'
  },
  {
    id: 2,
    title: 'Kegiatan OSIS Bulan Januari',
    excerpt: 'Berbagai kegiatan menarik dari OSIS untuk bulan ini...',
    content: 'Konten lengkap...',
    category: 'kegiatan',
    status: 'published',
    featured_image: null,
    author: { username: 'admin' },
    created_at: '2024-01-14T14:30:00Z',
    updated_at: '2024-01-14T14:30:00Z'
  },
  {
    id: 3,
    title: 'Program Beasiswa Prestasi',
    excerpt: 'Program beasiswa untuk siswa berprestasi di berbagai bidang...',
    content: 'Konten lengkap...',
    category: 'program',
    status: 'draft',
    featured_image: null,
    author: { username: 'admin' },
    created_at: '2024-01-13T09:15:00Z',
    updated_at: '2024-01-13T09:15:00Z'
  }
])

// Tabs
const tabs = [
  { id: 'all', name: 'Semua' },
  { id: 'berita', name: 'Berita' },
  { id: 'kegiatan', name: 'Kegiatan' },
  { id: 'program', name: 'Program' },
  { id: 'pengumuman', name: 'Pengumuman' }
]

// Computed
const filteredContent = computed(() => {
  let filtered = content.value

  // Filter by tab
  if (activeTab.value !== 'all') {
    filtered = filtered.filter(item => item.category === activeTab.value)
  }

  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(item =>
      item.title.toLowerCase().includes(query) ||
      item.excerpt.toLowerCase().includes(query)
    )
  }

  // Filter by status
  if (statusFilter.value) {
    filtered = filtered.filter(item => item.status === statusFilter.value)
  }

  // Filter by category
  if (categoryFilter.value) {
    filtered = filtered.filter(item => item.category === categoryFilter.value)
  }

  return filtered
})

// Methods
const getContentCount = (tabId) => {
  if (tabId === 'all') return content.value.length
  return content.value.filter(item => item.category === tabId).length
}

const getStatusClass = (status) => {
  switch (status) {
    case 'published':
      return 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200'
    case 'draft':
      return 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200'
    case 'archived':
      return 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
    default:
      return 'bg-muted text-muted-foreground'
  }
}

const getStatusText = (status) => {
  switch (status) {
    case 'published':
      return 'Published'
    case 'draft':
      return 'Draft'
    case 'archived':
      return 'Archived'
    default:
      return status
  }
}

const getCategoryText = (category) => {
  switch (category) {
    case 'berita':
      return 'Berita'
    case 'kegiatan':
      return 'Kegiatan'
    case 'program':
      return 'Program'
    case 'pengumuman':
      return 'Pengumuman'
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

const viewContent = (contentItem) => {
  viewingContent.value = contentItem
  showViewModal.value = true
}

const editContent = (contentItem) => {
  editingContent.value = contentItem
  showEditModal.value = true
}

const deleteContent = async (contentItem) => {
  if (confirm(`Apakah Anda yakin ingin menghapus "${contentItem.title}"?`)) {
    try {
      // TODO: Implement delete API call
      const index = content.value.findIndex(item => item.id === contentItem.id)
      if (index > -1) {
        content.value.splice(index, 1)
        toast.success('Konten berhasil dihapus')
      }
    } catch (error) {
      toast.error('Gagal menghapus konten')
    }
  }
}

const closeModal = () => {
  showCreateModal.value = false
  showEditModal.value = false
  editingContent.value = null
}

const handleContentSaved = (savedContent) => {
  if (editingContent.value) {
    // Update existing content
    const index = content.value.findIndex(item => item.id === savedContent.id)
    if (index > -1) {
      content.value[index] = savedContent
    }
  } else {
    // Add new content
    content.value.unshift(savedContent)
  }
  closeModal()
  toast.success('Konten berhasil disimpan')
}

// Lifecycle
onMounted(() => {
  // Load content from API
  // publicStore.fetchAllPublicContent()
})
</script>
