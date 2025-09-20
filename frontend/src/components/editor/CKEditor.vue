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
    'fontSize',
    'fontFamily',
    'fontColor',
    'fontBackgroundColor',
    '|',
    'alignment',
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
    'insertTable',
    '|',
    'horizontalLine',
    '|',
    'sourceEditing',
    '|',
    'undo',
    'redo'
  ],
  heading: {
    options: [
      { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
      { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
      { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
      { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
      { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' }
    ]
  },
  fontSize: {
    options: [
      9, 11, 13, 'default', 17, 19, 21
    ],
    supportAllValues: true
  },
  fontFamily: {
    options: [
      'default',
      'Arial, Helvetica, sans-serif',
      'Courier New, Courier, monospace',
      'Georgia, serif',
      'Lucida Sans Unicode, Lucida Grande, sans-serif',
      'Tahoma, Geneva, sans-serif',
      'Times New Roman, Times, serif',
      'Trebuchet MS, Helvetica, sans-serif',
      'Verdana, Geneva, sans-serif'
    ],
    supportAllValues: true
  },
  fontColor: {
    colors: [
      { color: 'hsl(0, 0%, 0%)', label: 'Black' },
      { color: 'hsl(0, 0%, 30%)', label: 'Dim grey' },
      { color: 'hsl(0, 0%, 60%)', label: 'Grey' },
      { color: 'hsl(0, 0%, 90%)', label: 'Light grey' },
      { color: 'hsl(0, 0%, 100%)', label: 'White', hasBorder: true },
      { color: 'hsl(0, 75%, 60%)', label: 'Red' },
      { color: 'hsl(30, 75%, 60%)', label: 'Orange' },
      { color: 'hsl(60, 75%, 60%)', label: 'Yellow' },
      { color: 'hsl(90, 75%, 60%)', label: 'Light green' },
      { color: 'hsl(120, 75%, 60%)', label: 'Green' },
      { color: 'hsl(150, 75%, 60%)', label: 'Aquamarine' },
      { color: 'hsl(180, 75%, 60%)', label: 'Turquoise' },
      { color: 'hsl(210, 75%, 60%)', label: 'Light blue' },
      { color: 'hsl(240, 75%, 60%)', label: 'Blue' },
      { color: 'hsl(270, 75%, 60%)', label: 'Purple' }
    ]
  },
  fontBackgroundColor: {
    colors: [
      { color: 'hsl(0, 75%, 60%)', label: 'Red' },
      { color: 'hsl(30, 75%, 60%)', label: 'Orange' },
      { color: 'hsl(60, 75%, 60%)', label: 'Yellow' },
      { color: 'hsl(90, 75%, 60%)', label: 'Light green' },
      { color: 'hsl(120, 75%, 60%)', label: 'Green' },
      { color: 'hsl(150, 75%, 60%)', label: 'Aquamarine' },
      { color: 'hsl(180, 75%, 60%)', label: 'Turquoise' },
      { color: 'hsl(210, 75%, 60%)', label: 'Light blue' },
      { color: 'hsl(240, 75%, 60%)', label: 'Blue' },
      { color: 'hsl(270, 75%, 60%)', label: 'Purple' },
      { color: 'hsl(0, 0%, 0%)', label: 'Black' },
      { color: 'hsl(0, 0%, 30%)', label: 'Dim grey' },
      { color: 'hsl(0, 0%, 60%)', label: 'Grey' },
      { color: 'hsl(0, 0%, 90%)', label: 'Light grey' },
      { color: 'hsl(0, 0%, 100%)', label: 'White', hasBorder: true }
    ]
  },
  alignment: {
    options: ['left', 'center', 'right', 'justify']
  },
  link: {
    addTargetToExternalLinks: true,
    defaultProtocol: 'https://',
    decorators: {
      openInNewTab: {
        mode: 'manual',
        label: 'Open in a new tab',
        attributes: {
          target: '_blank',
          rel: 'noopener noreferrer'
        }
      }
    }
  },
  table: {
    contentToolbar: [
      'tableColumn',
      'tableRow',
      'mergeTableCells',
      'tableProperties',
      'tableCellProperties'
    ]
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

:deep(.ck-editor__editable h4) {
  font-size: 1rem;
  font-weight: bold;
  margin: 12px 0 6px 0;
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

/* Text alignment */
:deep(.ck-editor__editable .text-left) {
  text-align: left;
}

:deep(.ck-editor__editable .text-center) {
  text-align: center;
}

:deep(.ck-editor__editable .text-right) {
  text-align: right;
}

:deep(.ck-editor__editable .text-justify) {
  text-align: justify;
}

/* Table styling */
:deep(.ck-editor__editable table) {
  border-collapse: collapse;
  margin: 16px 0;
  width: 100%;
  border: 1px solid #e5e7eb;
}

:deep(.ck-editor__editable table td, .ck-editor__editable table th) {
  border: 1px solid #e5e7eb;
  padding: 8px 12px;
  text-align: left;
}

:deep(.ck-editor__editable table th) {
  background-color: #f9fafb;
  font-weight: bold;
}

/* Horizontal line */
:deep(.ck-editor__editable hr) {
  border: none;
  border-top: 2px solid #e5e7eb;
  margin: 16px 0;
}

/* Font size and family styling */
:deep(.ck-editor__editable) {
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Source editing modal styling */
:deep(.ck-source-editing-area) {
  font-family: 'Courier New', Courier, monospace;
  font-size: 13px;
  line-height: 1.4;
}

:deep(.ck-source-editing-area textarea) {
  background: #1f2937;
  color: #f9fafb;
  border: 1px solid #374151;
  border-radius: 4px;
  padding: 12px;
  font-family: 'Courier New', Courier, monospace;
  font-size: 13px;
  line-height: 1.4;
  resize: vertical;
  min-height: 200px;
}

/* Toolbar dropdown styling */
:deep(.ck-dropdown__panel) {
  border: 1px solid #e5e7eb;
  border-radius: 6px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

:deep(.ck-list__item) {
  padding: 8px 12px;
  color: #374151;
}

:deep(.ck-list__item:hover) {
  background-color: #f3f4f6;
}

:deep(.ck-list__item.ck-on) {
  background-color: #dbeafe;
  color: #1d4ed8;
}

/* Color picker styling */
:deep(.ck-color-grid__tile) {
  border-radius: 4px;
  margin: 2px;
}

:deep(.ck-color-grid__tile:hover) {
  transform: scale(1.1);
  transition: transform 0.1s ease;
}
</style>
