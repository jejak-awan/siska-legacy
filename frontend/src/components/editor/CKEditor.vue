<template>
  <div class="ckeditor-wrapper">
    <ckeditor
      v-model="content"
      :editor="editor"
      :config="editorConfig"
      @ready="onReady"
      @focus="onFocus"
      @blur="onBlur"
      @input="onInput"
    />
    
    <!-- Character Counter -->
    <div class="mt-2 text-xs text-gray-500 text-right">
      {{ characterCount }} karakter
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { component as ckeditor } from '@ckeditor/ckeditor5-vue'
import ClassicEditor from '@ckeditor/ckeditor5-build-classic'

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
    default: 200
  },
  disabled: {
    type: Boolean,
    default: false
  }
})

const emit = defineEmits(['update:modelValue', 'change'])

const editor = ClassicEditor
const content = ref(props.modelValue)

// Character count (excluding HTML tags)
const characterCount = computed(() => {
  if (!content.value) return 0
  const textContent = content.value.replace(/<[^>]*>/g, '')
  return textContent.length
})

// CKEditor Configuration
const editorConfig = {
  placeholder: props.placeholder,
  toolbar: [
    'heading',
    '|',
    'bold',
    'italic',
    'underline',
    'strikethrough',
    '|',
    'bulletedList',
    'numberedList',
    '|',
    'outdent',
    'indent',
    '|',
    'blockQuote',
    'link',
    '|',
    'undo',
    'redo'
  ],
  heading: {
    options: [
      { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
      { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
      { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
      { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' }
    ]
  },
  link: {
    addTargetToExternalLinks: true,
    defaultProtocol: 'https://'
  },
  language: 'en'
}

// Event handlers
const onReady = (editor) => {
  console.log('CKEditor is ready to use!', editor)
  
  // Set initial content
  if (props.modelValue) {
    editor.setData(props.modelValue)
  }
  
  // Set height
  if (props.height) {
    editor.editing.view.change(writer => {
      writer.setStyle('min-height', `${props.height}px`, editor.editing.view.document.getRoot())
    })
  }
}

const onFocus = (event, editor) => {
  console.log('CKEditor focused', editor)
}

const onBlur = (event, editor) => {
  console.log('CKEditor blurred', editor)
}

const onInput = (data, event, editor) => {
  console.log('CKEditor input', data)
  emit('update:modelValue', data)
  emit('change', data)
}

// Watch for external changes
watch(() => props.modelValue, (newValue) => {
  if (newValue !== content.value) {
    content.value = newValue
  }
})

onMounted(() => {
  // Set initial content
  content.value = props.modelValue
})
</script>

<style scoped>
.ckeditor-wrapper {
  border: 1px solid #e5e7eb;
  border-radius: 0.375rem;
  overflow: hidden;
}

/* Custom CKEditor styles */
:deep(.ck-editor__editable) {
  min-height: 150px;
  border: none;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  font-size: 14px;
  line-height: 1.6;
  color: #374151;
}

:deep(.ck-editor__editable:focus) {
  border: none;
  box-shadow: none;
}

:deep(.ck-toolbar) {
  border: none;
  border-bottom: 1px solid #e5e7eb;
  background: #f9fafb;
}

:deep(.ck-toolbar__separator) {
  background: #e5e7eb;
}

:deep(.ck-button) {
  color: #374151;
}

:deep(.ck-button:hover) {
  background: #e5e7eb;
}

:deep(.ck-button.ck-on) {
  background: #dbeafe;
  color: #1d4ed8;
}

:deep(.ck-button.ck-on:hover) {
  background: #bfdbfe;
}

/* Content styling */
:deep(.ck-editor__editable h1) {
  font-size: 1.5rem;
  font-weight: bold;
  margin: 16px 0 8px 0;
  color: #1f2937;
}

:deep(.ck-editor__editable h2) {
  font-size: 1.25rem;
  font-weight: bold;
  margin: 16px 0 8px 0;
  color: #1f2937;
}

:deep(.ck-editor__editable h3) {
  font-size: 1.125rem;
  font-weight: bold;
  margin: 16px 0 8px 0;
  color: #1f2937;
}

:deep(.ck-editor__editable p) {
  margin: 0 0 8px 0;
}

:deep(.ck-editor__editable ul, .ck-editor__editable ol) {
  margin: 8px 0;
  padding-left: 20px;
}

:deep(.ck-editor__editable li) {
  margin: 4px 0;
}

:deep(.ck-editor__editable a) {
  color: #3b82f6;
  text-decoration: underline;
}

:deep(.ck-editor__editable a:hover) {
  color: #1d4ed8;
}

:deep(.ck-editor__editable blockquote) {
  border-left: 4px solid #e5e7eb;
  padding-left: 16px;
  margin: 16px 0;
  font-style: italic;
  color: #6b7280;
}
</style>
