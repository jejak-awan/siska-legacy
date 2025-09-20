<template>
  <div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 bg-background/80 backdrop-blur-sm transition-opacity" @click="$emit('close')"></div>

      <!-- Modal panel -->
      <div class="inline-block align-bottom bg-background rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
        <form @submit.prevent="handleSubmit">
          <!-- Header -->
          <div class="bg-background px-6 py-4 border-b border-border">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-medium text-foreground">
                {{ isEdit ? 'Edit Konten' : 'Buat Konten Baru' }}
              </h3>
              <button
                type="button"
                @click="$emit('close')"
                class="text-muted-foreground hover:text-foreground"
              >
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
              </button>
            </div>
          </div>

          <!-- Content -->
          <div class="bg-background px-6 py-4 max-h-96 overflow-y-auto">
            <div class="space-y-6">
              <!-- Title -->
              <div>
                <label for="title" class="block text-sm font-medium text-foreground mb-2">
                  Judul *
                </label>
                <input
                  id="title"
                  v-model="form.title"
                  type="text"
                  required
                  class="w-full px-3 py-2 border border-input rounded-md bg-background text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring"
                  placeholder="Masukkan judul konten"
                >
              </div>

              <!-- Category -->
              <div>
                <label for="category" class="block text-sm font-medium text-foreground mb-2">
                  Kategori *
                </label>
                <select
                  id="category"
                  v-model="form.category"
                  required
                  class="w-full px-3 py-2 border border-input rounded-md bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring"
                >
                  <option value="">Pilih kategori</option>
                  <option value="berita">Berita</option>
                  <option value="kegiatan">Kegiatan</option>
                  <option value="program">Program</option>
                  <option value="pengumuman">Pengumuman</option>
                </select>
              </div>

              <!-- Status -->
              <div>
                <label for="status" class="block text-sm font-medium text-foreground mb-2">
                  Status
                </label>
                <select
                  id="status"
                  v-model="form.status"
                  class="w-full px-3 py-2 border border-input rounded-md bg-background text-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring"
                >
                  <option value="draft">Draft</option>
                  <option value="published">Published</option>
                  <option value="archived">Archived</option>
                </select>
              </div>

              <!-- Featured Image -->
              <div>
                <label class="block text-sm font-medium text-foreground mb-2">
                  Gambar Utama
                </label>
                <ImageUpload
                  v-model="form.featured_image"
                  :max-size="5"
                  :max-width="1200"
                  :max-height="800"
                  accept="image/*"
                />
              </div>

              <!-- Excerpt -->
              <div>
                <label for="excerpt" class="block text-sm font-medium text-foreground mb-2">
                  Ringkasan
                </label>
                <textarea
                  id="excerpt"
                  v-model="form.excerpt"
                  rows="3"
                  class="w-full px-3 py-2 border border-input rounded-md bg-background text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring"
                  placeholder="Masukkan ringkasan konten"
                ></textarea>
              </div>

              <!-- Content -->
              <div>
                <label class="block text-sm font-medium text-foreground mb-2">
                  Konten *
                </label>
                <RichTextEditor
                  v-model="form.content"
                  placeholder="Masukkan konten lengkap..."
                />
              </div>

              <!-- Tags -->
              <div>
                <label for="tags" class="block text-sm font-medium text-foreground mb-2">
                  Tags
                </label>
                <input
                  id="tags"
                  v-model="tagsInput"
                  type="text"
                  class="w-full px-3 py-2 border border-input rounded-md bg-background text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring"
                  placeholder="Masukkan tags (pisahkan dengan koma)"
                  @blur="updateTags"
                >
                <div v-if="form.tags.length > 0" class="mt-2 flex flex-wrap gap-2">
                  <span
                    v-for="tag in form.tags"
                    :key="tag"
                    class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary"
                  >
                    {{ tag }}
                    <button
                      type="button"
                      @click="removeTag(tag)"
                      class="ml-1 text-primary hover:text-primary/80"
                    >
                      <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </span>
                </div>
              </div>

              <!-- Featured -->
              <div class="flex items-center">
                <input
                  id="featured"
                  v-model="form.is_featured"
                  type="checkbox"
                  class="h-4 w-4 text-primary focus:ring-ring border-input rounded"
                >
                <label for="featured" class="ml-2 block text-sm text-foreground">
                  Jadikan konten unggulan
                </label>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="bg-background px-6 py-4 border-t border-border flex justify-end space-x-3">
            <Button
              type="button"
              variant="outline"
              @click="$emit('close')"
            >
              Batal
            </Button>
            <Button
              type="submit"
              :disabled="isLoading"
              class="bg-primary text-primary-foreground hover:bg-primary/90"
            >
              <svg v-if="isLoading" class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ isLoading ? 'Menyimpan...' : (isEdit ? 'Update' : 'Simpan') }}
            </Button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, watch, onMounted } from 'vue'
import { useToast } from '../../plugins/toast.js'
import Button from '../ui/Button.vue'
import RichTextEditor from '../editor/RichTextEditor.vue'
import ImageUpload from '../upload/ImageUpload.vue'

const props = defineProps({
  content: {
    type: Object,
    default: null
  },
  isEdit: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['close', 'saved'])

const toast = useToast()

// Form state
const form = reactive({
  title: '',
  category: '',
  status: 'draft',
  featured_image: null,
  excerpt: '',
  content: '',
  tags: [],
  is_featured: false
})

const tagsInput = ref('')
const isLoading = ref(false)

// Watch for content changes
watch(() => props.content, (newContent) => {
  if (newContent) {
    form.title = newContent.title || ''
    form.category = newContent.category || ''
    form.status = newContent.status || 'draft'
    form.featured_image = newContent.featured_image || null
    form.excerpt = newContent.excerpt || ''
    form.content = newContent.content || ''
    form.tags = newContent.tags || []
    form.is_featured = newContent.is_featured || false
    tagsInput.value = form.tags.join(', ')
  }
}, { immediate: true })

// Methods
const updateTags = () => {
  if (tagsInput.value.trim()) {
    form.tags = tagsInput.value
      .split(',')
      .map(tag => tag.trim())
      .filter(tag => tag.length > 0)
  } else {
    form.tags = []
  }
}

const removeTag = (tagToRemove) => {
  form.tags = form.tags.filter(tag => tag !== tagToRemove)
  tagsInput.value = form.tags.join(', ')
}

const handleSubmit = async () => {
  try {
    isLoading.value = true

    // Validate required fields
    if (!form.title.trim()) {
      toast.error('Judul wajib diisi')
      return
    }
    if (!form.category) {
      toast.error('Kategori wajib dipilih')
      return
    }
    if (!form.content.trim()) {
      toast.error('Konten wajib diisi')
      return
    }

    // Prepare data
    const contentData = {
      ...form,
      tags: form.tags,
      updated_at: new Date().toISOString()
    }

    if (props.isEdit && props.content) {
      contentData.id = props.content.id
      contentData.created_at = props.content.created_at
    } else {
      contentData.id = Date.now() // Mock ID
      contentData.created_at = new Date().toISOString()
      contentData.author = { username: 'admin' } // Mock author
    }

    // TODO: Replace with actual API call
    await new Promise(resolve => setTimeout(resolve, 1000)) // Mock delay

    emit('saved', contentData)
  } catch (error) {
    toast.error('Gagal menyimpan konten')
    console.error('Save content error:', error)
  } finally {
    isLoading.value = false
  }
}

// Initialize form
onMounted(() => {
  if (!props.content) {
    // Reset form for new content
    Object.assign(form, {
      title: '',
      category: '',
      status: 'draft',
      featured_image: null,
      excerpt: '',
      content: '',
      tags: [],
      is_featured: false
    })
    tagsInput.value = ''
  }
})
</script>
