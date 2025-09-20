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
              <span
                :class="[
                  'px-2 py-1 rounded-full text-xs font-medium',
                  getStatusClass(content.status)
                ]"
              >
                {{ getStatusText(content.status) }}
              </span>
              <span class="text-xs text-muted-foreground bg-muted px-2 py-1 rounded">
                {{ getCategoryText(content.category) }}
              </span>
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
        <div class="bg-background max-h-96 overflow-y-auto">
          <!-- Featured Image -->
          <div v-if="content.featured_image" class="w-full h-64 bg-muted">
            <img
              :src="content.featured_image"
              :alt="content.title"
              class="w-full h-full object-cover"
            >
          </div>

          <!-- Content Body -->
          <div class="px-6 py-6">
            <!-- Title -->
            <h1 class="text-2xl font-bold text-foreground mb-4">
              {{ content.title }}
            </h1>

            <!-- Meta Info -->
            <div class="flex items-center justify-between mb-6 text-sm text-muted-foreground">
              <div class="flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                  <div class="h-6 w-6 rounded-full bg-primary/10 flex items-center justify-center">
                    <span class="text-xs font-medium text-primary">
                      {{ content.author?.username?.charAt(0).toUpperCase() }}
                    </span>
                  </div>
                  <span>{{ content.author?.username }}</span>
                </div>
                <span>•</span>
                <span>{{ formatDate(content.created_at) }}</span>
                <span v-if="content.updated_at !== content.created_at">•</span>
                <span v-if="content.updated_at !== content.created_at">
                  Diperbarui {{ formatDate(content.updated_at) }}
                </span>
              </div>
              <div v-if="content.is_featured" class="flex items-center space-x-1 text-primary">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
                <span class="text-xs font-medium">Unggulan</span>
              </div>
            </div>

            <!-- Excerpt -->
            <div v-if="content.excerpt" class="mb-6 p-4 bg-muted/50 rounded-lg">
              <p class="text-muted-foreground italic">{{ content.excerpt }}</p>
            </div>

            <!-- Tags -->
            <div v-if="content.tags && content.tags.length > 0" class="mb-6">
              <div class="flex flex-wrap gap-2">
                <span
                  v-for="tag in content.tags"
                  :key="tag"
                  class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-primary/10 text-primary"
                >
                  #{{ tag }}
                </span>
              </div>
            </div>

            <!-- Content -->
            <div class="prose prose-sm max-w-none dark:prose-invert">
              <div v-html="content.content"></div>
            </div>
          </div>
        </div>

        <!-- Footer -->
        <div class="bg-background px-6 py-4 border-t border-border flex justify-between">
          <div class="flex space-x-2">
            <Button
              variant="outline"
              size="sm"
              @click="$emit('edit', content)"
            >
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
              </svg>
              Edit
            </Button>
            <Button
              variant="outline"
              size="sm"
              @click="$emit('delete', content)"
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
import { computed } from 'vue'
import Button from '../ui/Button.vue'

const props = defineProps({
  content: {
    type: Object,
    required: true
  }
})

const emit = defineEmits(['close', 'edit', 'delete'])

// Methods
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
    month: 'long',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>
