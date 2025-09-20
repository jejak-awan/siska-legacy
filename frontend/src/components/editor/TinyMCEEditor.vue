<template>
  <div class="tinymce-editor">
    <Editor
      v-model="content"
      :init="editorConfig"
      :disabled="disabled"
      @update:modelValue="handleUpdate"
      @change="handleChange"
    />
    
    <!-- Character Counter -->
    <div class="mt-2 text-xs text-gray-500 text-right">
      {{ characterCount }} karakter
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import Editor from '@tinymce/tinymce-vue'

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  placeholder: {
    type: String,
    default: ''
  },
  height: {
    type: Number,
    default: 300
  },
  disabled: {
    type: Boolean,
    default: false
  },
  toolbar: {
    type: String,
    default: 'undo redo | bold italic underline | alignleft aligncenter alignright | bullist numlist outdent indent | link image | removeformat'
  },
  plugins: {
    type: String,
    default: 'lists link image wordcount'
  }
})

const emit = defineEmits(['update:modelValue', 'change'])

const content = ref(props.modelValue)

// Character count (excluding HTML tags)
const characterCount = computed(() => {
  if (!content.value) return 0
  const textContent = content.value.replace(/<[^>]*>/g, '')
  return textContent.length
})

// TinyMCE Configuration
const editorConfig = {
  height: props.height,
  menubar: false,
  toolbar: props.toolbar,
  plugins: props.plugins,
  placeholder: props.placeholder,
  branding: false,
  promotion: false,
  statusbar: false,
  resize: false,
  content_style: `
    body { 
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
      font-size: 14px; 
      line-height: 1.6;
      margin: 8px;
    }
    p { margin: 0 0 8px 0; }
    h1, h2, h3, h4, h5, h6 { margin: 16px 0 8px 0; }
    ul, ol { margin: 8px 0; padding-left: 20px; }
    li { margin: 4px 0; }
    a { color: #3b82f6; text-decoration: underline; }
    img { max-width: 100%; height: auto; }
  `,
  setup: (editor) => {
    // Custom setup if needed
    editor.on('init', () => {
      console.log('TinyMCE Editor initialized')
    })
  },
  // Image upload configuration
  images_upload_handler: async (blobInfo, success, failure) => {
    try {
      // For now, we'll use a simple base64 approach
      // In production, you might want to upload to your server
      const base64 = await new Promise((resolve) => {
        const reader = new FileReader()
        reader.onload = () => resolve(reader.result)
        reader.readAsDataURL(blobInfo.blob())
      })
      success(base64)
    } catch (error) {
      failure('Image upload failed: ' + error.message)
    }
  },
  // Paste configuration
  paste_data_images: true,
  paste_as_text: false,
  paste_auto_cleanup_on_paste: true,
  paste_remove_styles_if_webkit: true,
  paste_remove_empty_paragraphs: true,
  // Word count
  wordcount_countregex: /[\w\u2019\'-]+/g,
  // Accessibility
  accessibility_focus: true,
  // Indonesian language (if available)
  language: 'id',
  // Custom styles
  style_formats: [
    { title: 'Heading 1', format: 'h1' },
    { title: 'Heading 2', format: 'h2' },
    { title: 'Heading 3', format: 'h3' },
    { title: 'Paragraph', format: 'p' },
    { title: 'Blockquote', format: 'blockquote' }
  ]
}

// Watch for external changes
watch(() => props.modelValue, (newValue) => {
  if (newValue !== content.value) {
    content.value = newValue
  }
})

// Handle content updates
const handleUpdate = (value) => {
  content.value = value
  emit('update:modelValue', value)
}

const handleChange = (value) => {
  emit('change', value)
}

onMounted(() => {
  // Initialize content
  content.value = props.modelValue
})
</script>

<style scoped>
.tinymce-editor {
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  overflow: hidden;
}

/* Custom TinyMCE styles */
:deep(.tox-tinymce) {
  border: none !important;
}

:deep(.tox-editor-header) {
  border-bottom: 1px solid #e5e7eb !important;
}

:deep(.tox-toolbar) {
  background: #f9fafb !important;
}

:deep(.tox-tbtn) {
  color: #374151 !important;
}

:deep(.tox-tbtn:hover) {
  background: #e5e7eb !important;
}

:deep(.tox-tbtn--enabled) {
  background: #dbeafe !important;
  color: #1d4ed8 !important;
}

:deep(.tox-edit-area) {
  background: white !important;
}

:deep(.tox-edit-area__iframe) {
  background: white !important;
}
</style>
