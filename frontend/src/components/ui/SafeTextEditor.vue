<template>
  <div class="safe-text-editor">
    <div v-if="loading" class="flex items-center justify-center p-4 bg-gray-50 rounded-lg">
      <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-blue-600"></div>
      <span class="ml-2 text-sm text-gray-600">Memuat editor...</span>
    </div>
    <div v-else>
      <Editor
        v-model="content"
        :init="editorConfig"
        :disabled="disabled"
        @input="handleInput"
        @change="handleChange"
        class="safe-editor"
      />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, nextTick } from 'vue'
// import Editor from '@tinymce/tinymce-vue'

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  placeholder: {
    type: String,
    default: 'Masukkan teks...'
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
    default: 'undo redo | formatselect | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | removeformat | help'
  },
  plugins: {
    type: String,
    default: 'lists link wordcount help'
  }
})

const emit = defineEmits(['update:modelValue', 'change'])

const loading = ref(true)
const content = ref(props.modelValue)

// TinyMCE Configuration - Security Focused
const editorConfig = ref({
  height: props.height,
  menubar: false,
  toolbar: props.toolbar,
  plugins: props.plugins,
  placeholder: props.placeholder,
  branding: false,
  promotion: false,
  
  // Security settings
  valid_elements: 'p,br,strong,b,em,i,u,s,strike,ul,ol,li,h1,h2,h3,h4,h5,h6,blockquote,pre,a[href|title|target],span[style],div[style|class]',
  valid_attributes: {
    'a': 'href,title,target',
    'span': 'style',
    'div': 'style,class'
  },
  
  // Content filtering
  verify_html: true,
  cleanup: true,
  cleanup_on_startup: true,
  
  // XSS Protection
  entity_encoding: 'raw',
  encoding: 'xml',
  
  // Content style
  content_style: `
    body { 
      font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
      font-size: 14px; 
      line-height: 1.6; 
      color: #374151;
      margin: 8px;
    }
    p { margin: 0 0 8px 0; }
    h1, h2, h3, h4, h5, h6 { margin: 16px 0 8px 0; font-weight: 600; }
    ul, ol { margin: 8px 0; padding-left: 24px; }
    li { margin: 4px 0; }
    blockquote { 
      margin: 16px 0; 
      padding: 8px 16px; 
      border-left: 4px solid #e5e7eb; 
      background: #f9fafb; 
    }
    a { color: #2563eb; text-decoration: underline; }
    a:hover { color: #1d4ed8; }
  `,
  
  // Setup callback
  setup: (editor) => {
    editor.on('init', () => {
      loading.value = false
    })
    
    // Additional security: Remove dangerous attributes
    editor.on('BeforeSetContent', (e) => {
      // Remove script tags and dangerous attributes
      e.content = e.content
        .replace(/<script[^>]*>.*?<\/script>/gi, '')
        .replace(/on\w+="[^"]*"/gi, '')
        .replace(/javascript:/gi, '')
    })
  },
  
  // Auto-resize
  autoresize_min_height: props.height,
  autoresize_max_height: props.height * 2,
  autoresize_bottom_margin: 16,
  
  // Accessibility
  accessibility_focus: true,
  tab_focus: ':prev,:next',
  
  // Language
  language: 'id',
  language_url: 'https://cdn.tiny.cloud/1/no-api-key/tinymce/6/langs/id.js'
})

// Watch for external changes
watch(() => props.modelValue, (newValue) => {
  if (newValue !== content.value) {
    content.value = newValue
  }
})

// Watch for internal changes
watch(content, (newValue) => {
  emit('update:modelValue', newValue)
})

const handleInput = (value) => {
  content.value = value
}

const handleChange = (value) => {
  emit('change', value)
}

onMounted(() => {
  // Load TinyMCE from CDN
  if (!window.tinymce) {
    const script = document.createElement('script')
    script.src = 'https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js'
    script.onload = () => {
      nextTick(() => {
        loading.value = false
      })
    }
    document.head.appendChild(script)
  } else {
    loading.value = false
  }
})
</script>

<style scoped>
.safe-text-editor {
  @apply w-full;
}

.safe-editor {
  @apply border border-gray-300 rounded-lg;
}

.safe-editor :deep(.tox-tinymce) {
  @apply border-gray-300 rounded-lg;
}

.safe-editor :deep(.tox-editor-header) {
  @apply border-b border-gray-200;
}

.safe-editor :deep(.tox-toolbar) {
  @apply bg-gray-50;
}

.safe-editor :deep(.tox-edit-area) {
  @apply bg-white;
}

.safe-editor :deep(.tox-edit-area__iframe) {
  @apply bg-white;
}
</style>
