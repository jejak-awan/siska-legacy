<template>
  <div class="atto-editor">
    <!-- Toolbar -->
    <div class="toolbar border border-gray-300 border-b-0 rounded-t-md bg-gray-50 p-2 flex flex-wrap gap-1">
      <!-- Bold -->
      <button
        @click="execCommand('bold')"
        :class="{ 'bg-blue-500 text-white': isActive('bold') }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Bold (Ctrl+B)"
      >
        <strong>B</strong>
      </button>
      
      <!-- Italic -->
      <button
        @click="execCommand('italic')"
        :class="{ 'bg-blue-500 text-white': isActive('italic') }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Italic (Ctrl+I)"
      >
        <em>I</em>
      </button>
      
      <!-- Underline -->
      <button
        @click="execCommand('underline')"
        :class="{ 'bg-blue-500 text-white': isActive('underline') }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Underline (Ctrl+U)"
      >
        <u>U</u>
      </button>
      
      <!-- Separator -->
      <div class="w-px h-6 bg-gray-300 mx-1"></div>
      
      <!-- Align Left -->
      <button
        @click="execCommand('justifyLeft')"
        :class="{ 'bg-blue-500 text-white': isActive('justifyLeft') }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Align Left"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 8a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 12a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1z"/>
        </svg>
      </button>
      
      <!-- Align Center -->
      <button
        @click="execCommand('justifyCenter')"
        :class="{ 'bg-blue-500 text-white': isActive('justifyCenter') }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Align Center"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path d="M4 4a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM4 8a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM4 12a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM4 16a1 1 0 011-1h6a1 1 0 110 2H5a1 1 0 01-1-1z"/>
        </svg>
      </button>
      
      <!-- Align Right -->
      <button
        @click="execCommand('justifyRight')"
        :class="{ 'bg-blue-500 text-white': isActive('justifyRight') }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Align Right"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 8a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 12a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1z"/>
        </svg>
      </button>
      
      <!-- Separator -->
      <div class="w-px h-6 bg-gray-300 mx-1"></div>
      
      <!-- Bullet List -->
      <button
        @click="execCommand('insertUnorderedList')"
        :class="{ 'bg-blue-500 text-white': isActive('insertUnorderedList') }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Bullet List"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 8a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 12a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1z"/>
        </svg>
      </button>
      
      <!-- Numbered List -->
      <button
        @click="execCommand('insertOrderedList')"
        :class="{ 'bg-blue-500 text-white': isActive('insertOrderedList') }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Numbered List"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 8a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 12a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1z"/>
        </svg>
      </button>
      
      <!-- Separator -->
      <div class="w-px h-6 bg-gray-300 mx-1"></div>
      
      <!-- Link -->
      <button
        @click="insertLink"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Insert Link"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"/>
        </svg>
      </button>
      
      <!-- Remove Format -->
      <button
        @click="execCommand('removeFormat')"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Remove Format"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 8a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 12a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1z"/>
        </svg>
      </button>
    </div>
    
    <!-- Editor Content -->
    <div
      ref="editor"
      :style="{ height: height + 'px' }"
      class="editor-content border border-gray-300 rounded-b-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 overflow-y-auto"
      contenteditable="true"
      @input="updateContent"
      @blur="updateContent"
      @paste="handlePaste"
      @keydown="handleKeydown"
      v-html="modelValue"
    ></div>
    
    <!-- Character Counter -->
    <div class="mt-2 text-xs text-gray-500 text-right">
      {{ characterCount }} karakter
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, nextTick } from 'vue'

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
  }
})

const emit = defineEmits(['update:modelValue', 'change'])

const editor = ref(null)

// Character count (excluding HTML tags)
const characterCount = computed(() => {
  if (!props.modelValue) return 0
  const textContent = props.modelValue.replace(/<[^>]*>/g, '')
  return textContent.length
})

// Execute command
const execCommand = (command, value = null) => {
  if (editor.value) {
    document.execCommand(command, false, value)
    editor.value.focus()
    updateContent()
  }
}

// Check if command is active
const isActive = (command) => {
  if (editor.value) {
    return document.queryCommandState(command)
  }
  return false
}

// Insert link
const insertLink = () => {
  const url = prompt('Masukkan URL:')
  if (url) {
    execCommand('createLink', url)
  }
}

// Update content
const updateContent = () => {
  if (editor.value) {
    const content = editor.value.innerHTML
    emit('update:modelValue', content)
    emit('change', content)
  }
}

// Handle paste
const handlePaste = (e) => {
  e.preventDefault()
  const text = e.clipboardData.getData('text/plain')
  document.execCommand('insertText', false, text)
}

// Handle keydown
const handleKeydown = (e) => {
  if (e.ctrlKey || e.metaKey) {
    switch (e.key) {
      case 'b':
        e.preventDefault()
        execCommand('bold')
        break
      case 'i':
        e.preventDefault()
        execCommand('italic')
        break
      case 'u':
        e.preventDefault()
        execCommand('underline')
        break
    }
  }
}

onMounted(() => {
  nextTick(() => {
    if (editor.value) {
      if (props.modelValue) {
        editor.value.innerHTML = props.modelValue
      } else if (props.placeholder) {
        editor.value.innerHTML = `<div class="text-gray-400">${props.placeholder}</div>`
      }
      
      editor.value.addEventListener('focus', () => {
        if (editor.value.innerHTML === `<div class="text-gray-400">${props.placeholder}</div>`) {
          editor.value.innerHTML = ''
        }
      })
      
      editor.value.addEventListener('blur', () => {
        if (!editor.value.innerHTML.trim()) {
          editor.value.innerHTML = `<div class="text-gray-400">${props.placeholder}</div>`
        }
      })
    }
  })
})
</script>

<style scoped>
.atto-editor {
  position: relative;
  display: flex;
  flex-direction: column;
}

.toolbar {
  position: relative;
  z-index: 10;
  background: #f9fafb;
  border-bottom: 1px solid #e5e7eb;
}

.editor-content {
  min-height: 100px;
  line-height: 1.5;
  position: relative;
  z-index: 0;
  background: white;
}

.editor-content:focus {
  outline: none;
}

.editor-content h1 {
  font-size: 1.5rem;
  font-weight: bold;
  margin: 0.5rem 0;
  padding-top: 0.25rem;
}

.editor-content h2 {
  font-size: 1.25rem;
  font-weight: bold;
  margin: 0.5rem 0;
  padding-top: 0.25rem;
}

.editor-content h3 {
  font-size: 1.125rem;
  font-weight: bold;
  margin: 0.5rem 0;
  padding-top: 0.25rem;
}

.editor-content p {
  margin: 0.5rem 0;
  padding-top: 0.25rem;
}

.editor-content ul, .editor-content ol {
  margin: 0.5rem 0;
  padding-left: 1.5rem;
  padding-top: 0.25rem;
}

.editor-content li {
  margin: 0.25rem 0;
}

.editor-content a {
  color: #3b82f6;
  text-decoration: underline;
}

.editor-content a:hover {
  color: #1d4ed8;
}
</style>
