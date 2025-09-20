<template>
  <div class="rich-text-editor">
    <div class="toolbar border border-gray-300 border-b-0 rounded-t-md bg-gray-50 p-2 flex flex-wrap gap-1">
      <!-- Bold -->
      <button
        @click="execCommand('bold')"
        :class="{ 'bg-blue-500 text-white': isActive('bold') }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Bold"
      >
        <strong>B</strong>
      </button>
      
      <!-- Italic -->
      <button
        @click="execCommand('italic')"
        :class="{ 'bg-blue-500 text-white': isActive('italic') }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Italic"
      >
        <em>I</em>
      </button>
      
      <!-- Underline -->
      <button
        @click="execCommand('underline')"
        :class="{ 'bg-blue-500 text-white': isActive('underline') }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Underline"
      >
        <u>U</u>
      </button>
      
      <!-- Separator -->
      <div class="w-px h-6 bg-gray-300 mx-1"></div>
      
      <!-- Heading -->
      <select
        @change="execCommand('formatBlock', $event.target.value)"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Heading"
      >
        <option value="div">Normal</option>
        <option value="h1">Heading 1</option>
        <option value="h2">Heading 2</option>
        <option value="h3">Heading 3</option>
      </select>
      
      <!-- Separator -->
      <div class="w-px h-6 bg-gray-300 mx-1"></div>
      
      <!-- List -->
      <button
        @click="execCommand('insertUnorderedList')"
        :class="{ 'bg-blue-500 text-white': isActive('insertUnorderedList') }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Bullet List"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
        </svg>
      </button>
      
      <button
        @click="execCommand('insertOrderedList')"
        :class="{ 'bg-blue-500 text-white': isActive('insertOrderedList') }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Numbered List"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
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
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
        </svg>
      </button>
      
      <!-- Separator -->
      <div class="w-px h-6 bg-gray-300 mx-1"></div>
      
      <!-- Clear Format -->
      <button
        @click="clearFormat"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Clear Format"
      >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
        </svg>
      </button>
    </div>
    
    <div
      ref="editor"
      :style="{ height: height + 'px' }"
      class="editor-content border border-gray-300 rounded-b-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 overflow-y-auto"
      contenteditable="true"
      @input="updateContent"
      @blur="updateContent"
      v-html="modelValue"
    ></div>
    
    <div v-if="placeholder && !modelValue" class="placeholder absolute top-3 left-3 text-gray-400 pointer-events-none">
      {{ placeholder }}
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, nextTick } from 'vue'

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
    default: 120
  }
})

const emit = defineEmits(['update:modelValue'])

const editor = ref(null)

const execCommand = (command, value = null) => {
  document.execCommand(command, false, value)
  editor.value.focus()
  updateContent()
}

const isActive = (command) => {
  return document.queryCommandState(command)
}

const insertLink = () => {
  const url = prompt('Enter URL:')
  if (url) {
    execCommand('createLink', url)
  }
}

const clearFormat = () => {
  execCommand('removeFormat')
}

const updateContent = () => {
  if (editor.value) {
    const content = editor.value.innerHTML
    emit('update:modelValue', content)
  }
}

onMounted(() => {
  nextTick(() => {
    if (editor.value && props.modelValue) {
      editor.value.innerHTML = props.modelValue
    }
  })
})
</script>

<style scoped>
.rich-text-editor {
  position: relative;
}

.editor-content {
  min-height: 100px;
  line-height: 1.5;
}

.editor-content:focus {
  outline: none;
}

.editor-content h1 {
  font-size: 1.5rem;
  font-weight: bold;
  margin: 0.5rem 0;
}

.editor-content h2 {
  font-size: 1.25rem;
  font-weight: bold;
  margin: 0.5rem 0;
}

.editor-content h3 {
  font-size: 1.125rem;
  font-weight: bold;
  margin: 0.5rem 0;
}

.editor-content ul, .editor-content ol {
  margin: 0.5rem 0;
  padding-left: 1.5rem;
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

.placeholder {
  color: #9ca3af;
  pointer-events: none;
}
</style>
