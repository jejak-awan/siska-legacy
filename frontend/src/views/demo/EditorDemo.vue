<template>
  <div class="editor-demo">
    <div class="container mx-auto px-4 py-8">
      <h1 class="text-3xl font-bold text-gray-900 mb-8">Demo Text Editor & Image Upload</h1>
      
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Rich Text Editor Demo -->
        <div class="space-y-6">
          <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Rich Text Editor</h2>
            
            <RichTextEditor
              v-model="editorContent"
              :placeholder="'Ketik konten Anda di sini...'"
              :min-height="300"
              @change="handleEditorChange"
            />
            
            <div class="mt-4">
              <h3 class="text-sm font-medium text-gray-700 mb-2">HTML Output:</h3>
              <pre class="bg-gray-100 p-3 rounded text-xs overflow-auto max-h-32">{{ editorContent }}</pre>
            </div>
          </div>
        </div>

        <!-- Image Upload Demo -->
        <div class="space-y-6">
          <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Image Upload</h2>
            
            <ImageUpload
              v-model="uploadedImages"
              :max-files="5"
              :max-size-m-b="5"
              :max-width-or-height="1920"
              :quality="0.8"
              @change="handleImageChange"
              @upload="handleImageUpload"
            />
            
            <div v-if="uploadedImages.length" class="mt-4">
              <h3 class="text-sm font-medium text-gray-700 mb-2">Uploaded Images:</h3>
              <div class="space-y-2">
                <div 
                  v-for="(image, index) in uploadedImages" 
                  :key="image.id"
                  class="flex items-center justify-between p-2 bg-gray-50 rounded"
                >
                  <div class="flex items-center space-x-3">
                    <img :src="image.preview" :alt="image.name" class="w-8 h-8 object-cover rounded">
                    <div>
                      <p class="text-sm font-medium text-gray-900">{{ image.name }}</p>
                      <p class="text-xs text-gray-500">{{ formatFileSize(image.size) }}</p>
                    </div>
                  </div>
                  <button 
                    @click="removeImage(index)"
                    class="text-red-600 hover:text-red-800"
                  >
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Combined Form Demo -->
      <div class="mt-8">
        <div class="bg-white rounded-lg shadow-md p-6">
          <h2 class="text-xl font-semibold text-gray-900 mb-4">Form dengan Editor & Upload</h2>
          
          <form @submit.prevent="handleSubmit" class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Judul Artikel
              </label>
              <input 
                v-model="form.title"
                type="text" 
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan judul artikel"
              >
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Konten Artikel
              </label>
              <RichTextEditor
                v-model="form.content"
                :placeholder="'Tulis konten artikel Anda di sini...'"
                :min-height="400"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Gambar Artikel
              </label>
              <ImageUpload
                v-model="form.images"
                :max-files="3"
                :max-size-m-b="2"
                :max-width-or-height="1200"
                :quality="0.9"
              />
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">
                Kategori
              </label>
              <select 
                v-model="form.category"
                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
              >
                <option value="">Pilih kategori</option>
                <option value="berita">Berita</option>
                <option value="pengumuman">Pengumuman</option>
                <option value="artikel">Artikel</option>
              </select>
            </div>

            <div class="flex justify-end space-x-4">
              <button 
                type="button"
                @click="resetForm"
                class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300 transition-colors"
              >
                Reset
              </button>
              <button 
                type="submit"
                :disabled="isSubmitting"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-colors disabled:opacity-50"
              >
                {{ isSubmitting ? 'Menyimpan...' : 'Simpan Artikel' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useToast } from '../../plugins/toast.js'
import RichTextEditor from '../../components/editor/RichTextEditor.vue'
import ImageUpload from '../../components/upload/ImageUpload.vue'

const toast = useToast()

// Editor state
const editorContent = ref('')
const uploadedImages = ref([])

// Form state
const form = reactive({
  title: '',
  content: '',
  images: [],
  category: ''
})

const isSubmitting = ref(false)

// Event handlers
const handleEditorChange = (content) => {
  console.log('Editor content changed:', content)
}

const handleImageChange = (images) => {
  console.log('Images changed:', images)
}

const handleImageUpload = (image) => {
  console.log('Image uploaded:', image)
  toast.success(`Gambar ${image.name} berhasil diupload`)
}

const removeImage = (index) => {
  uploadedImages.value.splice(index, 1)
}

const handleSubmit = async () => {
  try {
    isSubmitting.value = true

    // Validate form
    if (!form.title.trim()) {
      toast.error('Judul artikel wajib diisi')
      return
    }

    if (!form.content.trim()) {
      toast.error('Konten artikel wajib diisi')
      return
    }

    if (!form.category) {
      toast.error('Kategori wajib dipilih')
      return
    }

    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 2000))

    toast.success('Artikel berhasil disimpan!')
    console.log('Form submitted:', form)

  } catch (error) {
    toast.error('Gagal menyimpan artikel')
    console.error('Submit error:', error)
  } finally {
    isSubmitting.value = false
  }
}

const resetForm = () => {
  form.title = ''
  form.content = ''
  form.images = []
  form.category = ''
  editorContent.value = ''
  uploadedImages.value = []
}

const formatFileSize = (bytes) => {
  if (!bytes) return '0 B'
  
  const k = 1024
  const sizes = ['B', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}
</script>

<style scoped>
.editor-demo {
  @apply min-h-screen bg-gray-50;
}

.container {
  max-width: 1200px;
}
</style>
