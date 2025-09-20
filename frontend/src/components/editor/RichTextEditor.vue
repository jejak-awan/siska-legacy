<template>
  <div class="rich-text-editor">
    <div class="border border-input rounded-md bg-background">
      <!-- Toolbar -->
      <div class="flex items-center space-x-1 p-2 border-b border-border">
        <button
          type="button"
          @click="formatText('bold')"
          class="p-2 text-muted-foreground hover:text-foreground hover:bg-accent rounded"
          :class="{ 'bg-accent text-foreground': formats.bold }"
        >
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z" />
          </svg>
        </button>
        <button
          type="button"
          @click="formatText('italic')"
          class="p-2 text-muted-foreground hover:text-foreground hover:bg-accent rounded"
          :class="{ 'bg-accent text-foreground': formats.italic }"
        >
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 4h4M8 12h8M10 20h4" />
          </svg>
        </button>
        <button
          type="button"
          @click="formatText('underline')"
          class="p-2 text-muted-foreground hover:text-foreground hover:bg-accent rounded"
          :class="{ 'bg-accent text-foreground': formats.underline }"
        >
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 4h12M6 20h12" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4v12a4 4 0 008 0V4" />
          </svg>
        </button>
        <div class="w-px h-6 bg-border mx-1"></div>
        <button
          type="button"
          @click="insertList('bullet')"
          class="p-2 text-muted-foreground hover:text-foreground hover:bg-accent rounded"
        >
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
          </svg>
        </button>
        <button
          type="button"
          @click="insertList('ordered')"
          class="p-2 text-muted-foreground hover:text-foreground hover:bg-accent rounded"
        >
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
          </svg>
        </button>
      </div>
      
      <!-- Editor -->
      <div
        ref="editorRef"
        class="min-h-[200px] p-4 text-foreground focus:outline-none"
        :contenteditable="true"
        @input="handleInput"
        @keydown="handleKeydown"
        v-html="modelValue"
      ></div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, watch, nextTick } from 'vue'

const props = defineProps({
  modelValue: {
    type: String,
    default: ''
  },
  placeholder: {
    type: String,
    default: 'Masukkan konten...'
  }
})

const emit = defineEmits(['update:modelValue'])

const editorRef = ref(null)
const formats = reactive({
  bold: false,
  italic: false,
  underline: false
})

// Methods
const formatText = (format) => {
  document.execCommand(format, false, null)
  updateFormats()
}

const insertList = (type) => {
  if (type === 'bullet') {
    document.execCommand('insertUnorderedList', false, null)
  } else {
    document.execCommand('insertOrderedList', false, null)
  }
}

const updateFormats = () => {
  formats.bold = document.queryCommandState('bold')
  formats.italic = document.queryCommandState('italic')
  formats.underline = document.queryCommandState('underline')
}

const handleInput = (event) => {
  const content = event.target.innerHTML
  emit('update:modelValue', content)
}

const handleKeydown = (event) => {
  // Handle keyboard shortcuts
  if (event.ctrlKey || event.metaKey) {
    switch (event.key) {
      case 'b':
        event.preventDefault()
        formatText('bold')
        break
      case 'i':
        event.preventDefault()
        formatText('italic')
        break
      case 'u':
        event.preventDefault()
        formatText('underline')
        break
    }
  }
}

// Watch for external changes
watch(() => props.modelValue, (newValue) => {
  if (editorRef.value && editorRef.value.innerHTML !== newValue) {
    editorRef.value.innerHTML = newValue
  }
})

// Initialize editor
nextTick(() => {
  if (editorRef.value && !props.modelValue) {
    editorRef.value.innerHTML = `<p>${props.placeholder}</p>`
  }
})
</script>

<style scoped>
.rich-text-editor :deep(p) {
  margin: 0 0 1rem 0;
}

.rich-text-editor :deep(ul),
.rich-text-editor :deep(ol) {
  margin: 0 0 1rem 0;
  padding-left: 2rem;
}

.rich-text-editor :deep(li) {
  margin: 0.25rem 0;
}

.rich-text-editor :deep(strong) {
  font-weight: bold;
}

.rich-text-editor :deep(em) {
  font-style: italic;
}

.rich-text-editor :deep(u) {
  text-decoration: underline;
}
</style>