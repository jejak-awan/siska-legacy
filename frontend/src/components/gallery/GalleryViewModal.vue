<template>
  <div class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 bg-background/80 backdrop-blur-sm transition-opacity" @click="$emit('close')"></div>

      <!-- Modal panel -->
      <div class="inline-block align-bottom bg-background rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
        <!-- Header -->
        <div class="bg-background px-6 py-4 border-b border-border">
          <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
              <span class="text-xs text-muted-foreground bg-muted px-2 py-1 rounded">
                {{ getCategoryText(image.category) }}
              </span>
              <div v-if="image.is_featured" class="flex items-center space-x-1 text-primary">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="text-xs font-medium">Unggulan</span>
              </div>
            </div>
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
        <div class="bg-background">
          <!-- Image -->
          <div class="relative">
            <img
              :src="image.url"
              :alt="image.title"
              class="w-full h-96 object-cover"
            >
            <!-- Navigation arrows -->
            <button
              v-if="currentIndex > 0"
              @click="previousImage"
              class="absolute left-4 top-1/2 transform -translate-y-1/2 p-2 bg-black/50 text-white rounded-full hover:bg-black/70 transition-colors"
            >
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
              </svg>
            </button>
            <button
              v-if="currentIndex < images.length - 1"
              @click="nextImage"
              class="absolute right-4 top-1/2 transform -translate-y-1/2 p-2 bg-black/50 text-white rounded-full hover:bg-black/70 transition-colors"
            >
              <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
              </svg>
            </button>
            <!-- Image counter -->
            <div class="absolute bottom-4 right-4 bg-black/50 text-white px-3 py-1 rounded-full text-sm">
              {{ currentIndex + 1 }} / {{ images.length }}
            </div>
          </div>

          <!-- Image Info -->
          <div class="px-6 py-6">
            <!-- Title -->
            <h1 class="text-2xl font-bold text-foreground mb-4">
              {{ image.title }}
            </h1>

            <!-- Meta Info -->
            <div class="flex items-center justify-between mb-6 text-sm text-muted-foreground">
              <div class="flex items-center space-x-4">
                <span>{{ formatDate(image.created_at) }}</span>
                <span>•</span>
                <span>{{ formatFileSize(image.file_size) }}</span>
                <span v-if="image.updated_at !== image.created_at">•</span>
                <span v-if="image.updated_at !== image.created_at">
                  Diperbarui {{ formatDate(image.updated_at) }}
                </span>
              </div>
            </div>

            <!-- Description -->
            <div v-if="image.description" class="mb-6 p-4 bg-muted/50 rounded-lg">
              <p class="text-muted-foreground">{{ image.description }}</p>
            </div>

            <!-- Tags -->
            <div v-if="image.tags && image.tags.length > 0" class="mb-6">
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="tag in image.tags"
                  :key="tag"
                  class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary"
                >
                  #{{ tag }}
                </span>
              </div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="bg-background px-6 py-4 border-t border-border flex justify-between">
          <div class="flex space-x-2">
            <Button
              variant="outline"
              size="sm"
              @click="$emit('edit', image)"
            >
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Edit
            </Button>
            <Button
              variant="outline"
              size="sm"
              @click="$emit('delete', image)"
              class="text-destructive hover:text-destructive"
            >
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
              </svg>
              Hapus
            </Button>
          </div>
          <Button
            variant="outline"
            @click="$emit('close')"
          >
            Tutup
          </Button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import Button from '../ui/Button.vue'

const props = defineProps({
  image: {
    type: Object,
    required: true
  },
  images: {
    type: Array,
    default: () => []
  }
})

const emit = defineEmits(['close', 'edit', 'delete'])

// Computed
const currentIndex = computed(() => {
  return props.images.findIndex(img => img.id === props.image.id)
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
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

const formatFileSize = (bytes) => {
  if (bytes === 0) return '0 Bytes'
  const k = 1024
  const sizes = ['Bytes', 'KB', 'MB', 'GB']
  const i = Math.floor(Math.log(bytes) / Math.log(k))
  return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i]
}

const previousImage = () => {
  if (currentIndex.value > 0) {
    const prevImage = props.images[currentIndex.value - 1]
    // Emit event to parent to change current image
    // This would need to be handled by the parent component
  }
}

const nextImage = () => {
  if (currentIndex.value < props.images.length - 1) {
    const nextImage = props.images[currentIndex.value + 1]
    // Emit event to parent to change current image
    // This would need to be handled by the parent component
  }
}
</script>
