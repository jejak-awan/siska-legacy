<template>
  <div class="simple-text-editor">
    <div class="editor-toolbar border border-gray-300 border-b-0 rounded-t-lg bg-gray-50 p-2">
      <div class="flex flex-wrap gap-1">
        <button
          type="button"
          @click="formatText('bold')"
          class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200"
          :class="{ 'bg-blue-100 border-blue-300': isActive('bold') }"
          title="Bold"
        >
          <strong>B</strong>
        </button>
        <button
          type="button"
          @click="formatText('italic')"
          class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200"
          :class="{ 'bg-blue-100 border-blue-300': isActive('italic') }"
          title="Italic"
        >
          <em>I</em>
        </button>
        <button
          type="button"
          @click="formatText('underline')"
          class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200"
          :class="{ 'bg-blue-100 border-blue-300': isActive('underline') }"
          title="Underline"
        >
          <u>U</u>
        </button>
        <div class="w-px h-6 bg-gray-300 mx-1"></div>
        <button
          type="button"
          @click="formatText('insertUnorderedList')"
          class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200"
          title="Bullet List"
        >
          • List
        </button>
        <button
          type="button"
          @click="formatText('insertOrderedList')"
          class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200"
          title="Numbered List"
        >
          1. List
        </button>
        <div class="w-px h-6 bg-gray-300 mx-1"></div>
        <button
          type="button"
          @click="formatText('justifyLeft')"
          class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200"
          title="Align Left"
        >
          ←
        </button>
        <button
          type="button"
          @click="formatText('justifyCenter')"
          class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200"
          title="Align Center"
        >
          ↔
        </button>
        <button
          type="button"
          @click="formatText('justifyRight')"
          class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200"
          title="Align Right"
        >
          →
        </button>
        <div class="w-px h-6 bg-gray-300 mx-1"></div>
        <button
          type="button"
          @click="clearFormatting"
          class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 text-red-600"
          title="Clear Formatting"
        >
          Clear
        </button>
      </div>
    </div>
    
    <div
      ref="editor"
      :style="{ height: height + 'px' }"
      class="editor-content border border-gray-300 rounded-b-lg p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent overflow-y-auto"
      :contenteditable="!disabled"
      @input="handleInput"
      @paste="handlePaste"
      @keydown="handleKeydown"
      v-html="content"
    ></div>
    
    <div class="mt-1 text-xs text-gray-500">
      {{ content.length }} karakter
    </div>
  </div>
</template>

<script setup>
import { ref, watch, nextTick, onMounted } from 'vue'

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
  }
})

const emit = defineEmits(['update:modelValue', 'change'])

const editor = ref(null)
const content = ref(props.modelValue)

// Watch for external changes
watch(() => props.modelValue, (newValue) => {
  if (newValue !== content.value) {
    content.value = newValue
    if (editor.value) {
      editor.value.innerHTML = newValue
    }
  }
})

// Watch for internal changes
watch(content, (newValue) => {
  emit('update:modelValue', newValue)
  emit('change', newValue)
})

const handleInput = () => {
  if (editor.value) {
    content.value = editor.value.innerHTML
  }
}

const handlePaste = (e) => {
  e.preventDefault()
  const text = e.clipboardData.getData('text/plain')
  document.execCommand('insertText', false, text)
}

const handleKeydown = (e) => {
  // Allow basic formatting shortcuts
  if (e.ctrlKey || e.metaKey) {
    switch (e.key) {
      case 'b':
        e.preventDefault()
        formatText('bold')
        break
      case 'i':
        e.preventDefault()
        formatText('italic')
        break
      case 'u':
        e.preventDefault()
        formatText('underline')
        break
    }
  }
}

const formatText = (command, value = null) => {
  if (editor.value) {
    editor.value.focus()
    document.execCommand(command, false, value)
    handleInput()
  }
}

const clearFormatting = () => {
  if (editor.value) {
    editor.value.focus()
    document.execCommand('removeFormat', false, null)
    handleInput()
  }
}

const isActive = (command) => {
  if (editor.value) {
    editor.value.focus()
    return document.queryCommandState(command)
  }
  return false
}

onMounted(() => {
  nextTick(() => {
    if (editor.value) {
      editor.value.innerHTML = content.value
      
      // Add placeholder if empty
      if (!content.value) {
        editor.value.innerHTML = `<div class="text-gray-400">${props.placeholder}</div>`
      }
      
      // Handle focus/blur for placeholder
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
.simple-text-editor {
  @apply w-full;
}

.editor-content {
  @apply bg-white;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  font-size: 14px;
  line-height: 1.6;
  color: #374151;
}

.editor-content:empty:before {
  content: attr(data-placeholder);
  color: #9ca3af;
  pointer-events: none;
}

.editor-content p {
  margin: 0 0 8px 0;
}

.editor-content p:last-child {
  margin-bottom: 0;
}

.editor-content ul, .editor-content ol {
  margin: 8px 0;
  padding-left: 24px;
}

.editor-content li {
  margin: 4px 0;
}

.editor-content strong {
  font-weight: 600;
}

.editor-content em {
  font-style: italic;
}

.editor-content u {
  text-decoration: underline;
}

.editor-toolbar button {
  transition: all 0.2s ease;
}

.editor-toolbar button:hover {
  background-color: #f3f4f6;
}

.editor-toolbar button:active {
  transform: translateY(1px);
}
</style>
