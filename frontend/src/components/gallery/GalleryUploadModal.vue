<template>
  <div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 bg-background/80 backdrop-blur-sm transition-opacity" @click="$emit('close')"></div>

      <!-- Modal panel -->
      <div class="inline-block align-bottom bg-background rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
        <form @submit.prevent="handleSubmit">
          <!-- Header -->
          <div class="bg-background px-6 py-4 border-b border-border">
            <div class="flex items-center justify-between">
              <h3 class="text-lg font-medium text-foreground">
                Upload Foto
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
          <div class="bg-background px-6 py-4">
            <div class="space-y-6">
              <!-- Image Upload -->
              <div>
                <label class="block text-sm font-medium text-foreground mb-2">
                  Foto *
                </label>
                <ImageUpload
                  v-model="form.image"
                  :max-size="10"
                  :max-width="2048"
                  :max-height="2048"
                  accept="image/*"
                  multiple
                />
                <p class="mt-1 text-xs text-muted-foreground">
                  Format: JPG, PNG, GIF. Maksimal 10MB per foto.
                </p>
              </div>

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
                  placeholder="Masukkan judul foto"
                >
              </div>

              <!-- Description -->
              <div>
                <label for="description" class="block text-sm font-medium text-foreground mb-2">
                  Deskripsi
                </label>
                <textarea
                  id="description"
                  v-model="form.description"
                  rows="3"
                  class="w-full px-3 py-2 border border-input rounded-md bg-background text-foreground placeholder-muted-foreground focus:outline-none focus:ring-2 focus:ring-ring focus:border-ring"
                  placeholder="Masukkan deskripsi foto"
                ></textarea>
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
                  <option value="kegiatan">Kegiatan</option>
                  <option value="acara">Acara</option>
                  <option value="prestasi">Prestasi</option>
                  <option value="sekolah">Sekolah</option>
                </select>
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
                  Jadikan foto unggulan
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
              :disabled="isLoading || !form.image"
              class="bg-primary text-primary-foreground hover:bg-primary/90"
            >
              <svg v-if="isLoading" class="animate-spin h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              {{ isLoading ? 'Mengupload...' : 'Upload' }}
            </Button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useToast } from '../../plugins/toast.js'
import Button from '../ui/Button.vue'
import ImageUpload from '../upload/ImageUpload.vue'

const emit = defineEmits(['close', 'uploaded'])

const toast = useToast()

// Form state
const form = reactive({
  image: null,
  title: '',
  description: '',
  category: '',
  tags: [],
  is_featured: false
})

const tagsInput = ref('')
const isLoading = ref(false)

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
    if (!form.image) {
      toast.error('Foto wajib diupload')
      return
    }
    if (!form.title.trim()) {
      toast.error('Judul wajib diisi')
      return
    }
    if (!form.category) {
      toast.error('Kategori wajib dipilih')
      return
    }

    // Prepare data
    const imageData = {
      id: Date.now(), // Mock ID
      title: form.title,
      description: form.description,
      category: form.category,
      tags: form.tags,
      is_featured: form.is_featured,
      url: form.image, // In real app, this would be the uploaded image URL
      file_size: 1024000, // Mock file size
      created_at: new Date().toISOString(),
      updated_at: new Date().toISOString()
    }

    // Upload to API
    const formData = new FormData()
    formData.append('title', form.title)
    formData.append('description', form.description)
    formData.append('category', form.category)
    formData.append('subcategory', form.subcategory)
    formData.append('image', selectedFile.value)
    formData.append('tags', JSON.stringify(form.tags))
    formData.append('is_featured', form.is_featured)
    formData.append('is_public', form.is_public)
    formData.append('status', 'published')

    const response = await api.post('/galleries', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    emit('uploaded', response.data.data)
  } catch (error) {
    toast.error('Gagal mengupload foto')
    console.error('Upload error:', error)
  } finally {
    isLoading.value = false
  }
}
</script>
