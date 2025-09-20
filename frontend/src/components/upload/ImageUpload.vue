<template>
  <div class="image-upload">
    <!-- Upload Area -->
    <div
      v-if="!modelValue"
      @click="triggerFileInput"
      @dragover.prevent
      @drop.prevent="handleDrop"
      class="border-2 border-dashed border-input rounded-lg p-6 text-center cursor-pointer hover:border-primary transition-colors"
    >
      <svg class="mx-auto h-12 w-12 text-muted-foreground mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
      </svg>
      <p class="text-sm text-muted-foreground mb-2">
        Klik untuk upload atau drag & drop
      </p>
      <p class="text-xs text-muted-foreground">
        {{ accept }} (maks. {{ maxSize }}MB)
      </p>
      <input
        ref="fileInput"
        type="file"
        :accept="accept"
        :multiple="multiple"
        @change="handleFileSelect"
        class="hidden"
      >
    </div>

    <!-- Preview -->
    <div v-else class="space-y-4">
      <div v-if="!multiple" class="relative">
        <img
          :src="modelValue"
          :alt="'Preview'"
          class="w-full h-48 object-cover rounded-lg border border-border"
        >
        <button
          @click="removeImage"
          class="absolute top-2 right-2 p-1 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors"
        >
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <div v-else class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div
          v-for="(image, index) in modelValue"
          :key="index"
          class="relative"
        >
          <img
            :src="image"
            :alt="`Preview ${index + 1}`"
            class="w-full h-32 object-cover rounded-lg border border-border"
          >
          <button
            @click="removeImage(index)"
            class="absolute top-1 right-1 p-1 bg-red-500 text-white rounded-full hover:bg-red-600 transition-colors"
          >
            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Add More Button for Multiple -->
      <div v-if="multiple" class="text-center">
        <button
          @click="triggerFileInput"
          class="px-4 py-2 text-sm text-primary border border-primary rounded-md hover:bg-primary hover:text-primary-foreground transition-colors"
        >
          Tambah Gambar
        </button>
      </div>
    </div>

    <!-- Progress -->
    <div v-if="isUploading" class="mt-4">
      <div class="flex items-center space-x-2">
        <div class="flex-1 bg-muted rounded-full h-2">
          <div
            class="bg-primary h-2 rounded-full transition-all duration-300"
            :style="{ width: `${uploadProgress}%` }"
          ></div>
        </div>
        <span class="text-sm text-muted-foreground">{{ uploadProgress }}%</span>
      </div>
    </div>

    <!-- Error Message -->
    <div v-if="error" class="mt-2 text-sm text-red-600">
      {{ error }}
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue'

const props = defineProps({
  modelValue: {
    type: [String, Array],
    default: null
  },
  multiple: {
    type: Boolean,
    default: false
  },
  accept: {
    type: String,
    default: 'image/*'
  },
  maxSize: {
    type: Number,
    default: 5
  },
  maxWidth: {
    type: Number,
    default: 1920
  },
  maxHeight: {
    type: Number,
    default: 1080
  }
})

const emit = defineEmits(['update:modelValue'])

const fileInput = ref(null)
const isUploading = ref(false)
const uploadProgress = ref(0)
const error = ref('')

// Methods
const triggerFileInput = () => {
  fileInput.value?.click()
}

const handleFileSelect = (event) => {
  const files = Array.from(event.target.files)
  if (files.length > 0) {
    processFiles(files)
  }
}

const handleDrop = (event) => {
  const files = Array.from(event.dataTransfer.files)
  if (files.length > 0) {
    processFiles(files)
  }
}

const processFiles = async (files) => {
  error.value = ''
  
  for (const file of files) {
    // Validate file type
    if (!file.type.startsWith('image/')) {
      error.value = 'Hanya file gambar yang diperbolehkan'
      continue
    }

    // Validate file size
    if (file.size > props.maxSize * 1024 * 1024) {
      error.value = `Ukuran file tidak boleh lebih dari ${props.maxSize}MB`
      continue
    }

    // Process image
    try {
      isUploading.value = true
      uploadProgress.value = 0

      const processedImage = await processImage(file)
      
      if (props.multiple) {
        const currentImages = Array.isArray(props.modelValue) ? props.modelValue : []
        emit('update:modelValue', [...currentImages, processedImage])
      } else {
        emit('update:modelValue', processedImage)
      }

      uploadProgress.value = 100
    } catch (err) {
      error.value = 'Gagal memproses gambar'
      console.error('Image processing error:', err)
    } finally {
      isUploading.value = false
      uploadProgress.value = 0
    }
  }
}

const processImage = (file) => {
  return new Promise((resolve, reject) => {
    const reader = new FileReader()
    
    reader.onload = (e) => {
      const img = new Image()
      img.onload = () => {
        // Create canvas for resizing
        const canvas = document.createElement('canvas')
        const ctx = canvas.getContext('2d')
        
        // Calculate new dimensions
        let { width, height } = img
        if (width > props.maxWidth || height > props.maxHeight) {
          const ratio = Math.min(props.maxWidth / width, props.maxHeight / height)
          width *= ratio
          height *= ratio
        }
        
        canvas.width = width
        canvas.height = height
        
        // Draw and compress
        ctx.drawImage(img, 0, 0, width, height)
        const compressedDataUrl = canvas.toDataURL('image/jpeg', 0.8)
        
        resolve(compressedDataUrl)
      }
      img.onerror = reject
      img.src = e.target.result
    }
    
    reader.onerror = reject
    reader.readAsDataURL(file)
  })
}

const removeImage = (index = null) => {
  if (props.multiple && Array.isArray(props.modelValue)) {
    if (index !== null) {
      const newImages = [...props.modelValue]
      newImages.splice(index, 1)
      emit('update:modelValue', newImages)
    } else {
      emit('update:modelValue', [])
    }
  } else {
    emit('update:modelValue', null)
  }
}

// Watch for external changes
watch(() => props.modelValue, (newValue) => {
  if (!newValue) {
    error.value = ''
  }
})
</script>