<template>
  <div class="tinymce-editor">
    <div ref="editorElement" class="tinymce-container"></div>
    
    <!-- Character Counter -->
    <div class="mt-2 text-xs text-gray-500 text-right">
      {{ characterCount }} karakter
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted, nextTick } from 'vue'
import tinymce from 'tinymce/tinymce'

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
    default: 'undo redo | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | removeformat | help'
  },
  plugins: {
    type: String,
    default: 'lists link paste wordcount'
  }
})

const emit = defineEmits(['update:modelValue', 'change'])

const editorElement = ref(null)
const editorInstance = ref(null)
const content = ref(props.modelValue)

// Character count (excluding HTML tags)
const characterCount = computed(() => {
  if (!content.value) return 0
  const textContent = content.value.replace(/<[^>]*>/g, '')
  return textContent.length
})

// Initialize TinyMCE
const initEditor = () => {
  if (!editorElement.value) return

  tinymce.init({
    target: editorElement.value,
    height: props.height,
    menubar: false,
    toolbar: 'undo redo | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | removeformat',
    plugins: 'lists link paste wordcount',
    placeholder: props.placeholder,
    branding: false,
    promotion: false,
    statusbar: false,
    resize: false,
    // Disable all cloud features
    cloud_channel: false,
    automatic_uploads: false,
    images_upload_url: false,
    images_upload_handler: false,
    // Disable external plugins
    external_plugins: {},
    // Local content styling
    content_style: `
      body { 
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; 
        font-size: 14px; 
        line-height: 1.6;
        margin: 8px;
        color: #374151;
      }
      p { margin: 0 0 8px 0; }
      h1, h2, h3, h4, h5, h6 { 
        margin: 16px 0 8px 0; 
        font-weight: bold;
        color: #1f2937;
      }
      h1 { font-size: 1.5rem; }
      h2 { font-size: 1.25rem; }
      h3 { font-size: 1.125rem; }
      ul, ol { margin: 8px 0; padding-left: 20px; }
      li { margin: 4px 0; }
      a { color: #3b82f6; text-decoration: underline; }
      a:hover { color: #1d4ed8; }
      blockquote { 
        border-left: 4px solid #e5e7eb; 
        padding-left: 16px; 
        margin: 16px 0; 
        font-style: italic; 
        color: #6b7280;
      }
      strong { font-weight: bold; }
      em { font-style: italic; }
      u { text-decoration: underline; }
      s { text-decoration: line-through; }
    `,
    setup: (editor) => {
      editorInstance.value = editor
      editor.on('init', () => {
        console.log('TinyMCE Editor initialized (Self-Hosted Free Mode)')
        if (props.modelValue) {
          editor.setContent(props.modelValue)
        }
      })
      editor.on('input change keyup', () => {
        const newContent = editor.getContent()
        content.value = newContent
        emit('update:modelValue', newContent)
        emit('change', newContent)
      })
    },
    // Paste configuration - local only
    paste_as_text: false,
    paste_auto_cleanup_on_paste: true,
    paste_remove_styles_if_webkit: true,
    paste_remove_empty_paragraphs: true,
    paste_merge_formats: true,
    // Word count - local regex
    wordcount_countregex: /[\w\u2019\'-]+/g,
    // Accessibility
    accessibility_focus: true,
    // Custom styles - local only
    style_formats: [
      { title: 'Heading 1', format: 'h1' },
      { title: 'Heading 2', format: 'h2' },
      { title: 'Heading 3', format: 'h3' },
      { title: 'Paragraph', format: 'p' },
      { title: 'Blockquote', format: 'blockquote' },
      { title: 'Bold', format: 'bold' },
      { title: 'Italic', format: 'italic' },
      { title: 'Underline', format: 'underline' },
      { title: 'Strikethrough', format: 'strikethrough' }
    ],
    // Link configuration - local only
    link_context_toolbar: true,
    link_title: false,
    // Force local mode
    forced_root_block: 'p',
    force_br_newlines: false,
    force_p_newlines: true,
    // Local language
    language: 'en'
  })
}

// Watch for external changes
watch(() => props.modelValue, (newValue) => {
  if (newValue !== content.value && editorInstance.value) {
    editorInstance.value.setContent(newValue || '')
    content.value = newValue
  }
})

// Watch for height changes
watch(() => props.height, (newHeight) => {
  if (editorInstance.value) {
    editorInstance.value.theme.resizeTo(null, newHeight)
  }
})

onMounted(() => {
  nextTick(() => {
    initEditor()
  })
})

onUnmounted(() => {
  if (editorInstance.value) {
    editorInstance.value.destroy()
    editorInstance.value = null
  }
})
</script>

<style scoped>
.tinymce-editor {
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  overflow: hidden;
}

.tinymce-container {
  min-height: 200px;
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
