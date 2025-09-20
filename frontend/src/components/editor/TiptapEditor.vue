<template>
  <div class="tiptap-editor">
    <!-- Toolbar -->
    <div class="toolbar border border-gray-300 border-b-0 rounded-t-md bg-gray-50 p-2 flex flex-wrap gap-1">
      <!-- Bold -->
      <button
        @click="editor.chain().focus().toggleBold().run()"
        :class="{ 'bg-blue-500 text-white': editor?.isActive('bold') }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Bold (Ctrl+B)"
      >
        <strong>B</strong>
      </button>
      
      <!-- Italic -->
      <button
        @click="editor.chain().focus().toggleItalic().run()"
        :class="{ 'bg-blue-500 text-white': editor?.isActive('italic') }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Italic (Ctrl+I)"
      >
        <em>I</em>
      </button>
      
      <!-- Underline -->
      <button
        @click="editor.chain().focus().toggleUnderline().run()"
        :class="{ 'bg-blue-500 text-white': editor?.isActive('underline') }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Underline (Ctrl+U)"
      >
        <u>U</u>
      </button>
      
      <!-- Strikethrough -->
      <button
        @click="editor.chain().focus().toggleStrike().run()"
        :class="{ 'bg-blue-500 text-white': editor?.isActive('strike') }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Strikethrough"
      >
        <s>S</s>
      </button>
      
      <!-- Separator -->
      <div class="w-px h-6 bg-gray-300 mx-1"></div>
      
      <!-- Heading 1 -->
      <button
        @click="editor.chain().focus().toggleHeading({ level: 1 }).run()"
        :class="{ 'bg-blue-500 text-white': editor?.isActive('heading', { level: 1 }) }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Heading 1"
      >
        H1
      </button>
      
      <!-- Heading 2 -->
      <button
        @click="editor.chain().focus().toggleHeading({ level: 2 }).run()"
        :class="{ 'bg-blue-500 text-white': editor?.isActive('heading', { level: 2 }) }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Heading 2"
      >
        H2
      </button>
      
      <!-- Heading 3 -->
      <button
        @click="editor.chain().focus().toggleHeading({ level: 3 }).run()"
        :class="{ 'bg-blue-500 text-white': editor?.isActive('heading', { level: 3 }) }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Heading 3"
      >
        H3
      </button>
      
      <!-- Separator -->
      <div class="w-px h-6 bg-gray-300 mx-1"></div>
      
      <!-- Align Left -->
      <button
        @click="editor.chain().focus().setTextAlign('left').run()"
        :class="{ 'bg-blue-500 text-white': editor?.isActive({ textAlign: 'left' }) }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Align Left"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 8a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 12a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1z"/>
        </svg>
      </button>
      
      <!-- Align Center -->
      <button
        @click="editor.chain().focus().setTextAlign('center').run()"
        :class="{ 'bg-blue-500 text-white': editor?.isActive({ textAlign: 'center' }) }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Align Center"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path d="M4 4a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM4 8a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM4 12a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1zM4 16a1 1 0 011-1h6a1 1 0 110 2H5a1 1 0 01-1-1z"/>
        </svg>
      </button>
      
      <!-- Align Right -->
      <button
        @click="editor.chain().focus().setTextAlign('right').run()"
        :class="{ 'bg-blue-500 text-white': editor?.isActive({ textAlign: 'right' }) }"
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
        @click="editor.chain().focus().toggleBulletList().run()"
        :class="{ 'bg-blue-500 text-white': editor?.isActive('bulletList') }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Bullet List"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 8a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 12a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1z"/>
        </svg>
      </button>
      
      <!-- Numbered List -->
      <button
        @click="editor.chain().focus().toggleOrderedList().run()"
        :class="{ 'bg-blue-500 text-white': editor?.isActive('orderedList') }"
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
        @click="setLink"
        :class="{ 'bg-blue-500 text-white': editor?.isActive('link') }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Insert Link"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M12.586 4.586a2 2 0 112.828 2.828l-3 3a2 2 0 01-2.828 0 1 1 0 00-1.414 1.414 4 4 0 005.656 0l3-3a4 4 0 00-5.656-5.656l-1.5 1.5a1 1 0 101.414 1.414l1.5-1.5zm-5 5a2 2 0 012.828 0 1 1 0 101.414-1.414 4 4 0 00-5.656 0l-3 3a4 4 0 105.656 5.656l1.5-1.5a1 1 0 10-1.414-1.414l-1.5 1.5a2 2 0 11-2.828-2.828l3-3z" clip-rule="evenodd"/>
        </svg>
      </button>
      
      <!-- Blockquote -->
      <button
        @click="editor.chain().focus().toggleBlockquote().run()"
        :class="{ 'bg-blue-500 text-white': editor?.isActive('blockquote') }"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors"
        title="Blockquote"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 8a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 12a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zM3 16a1 1 0 011-1h6a1 1 0 110 2H4a1 1 0 01-1-1z"/>
        </svg>
      </button>
      
      <!-- Separator -->
      <div class="w-px h-6 bg-gray-300 mx-1"></div>
      
      <!-- Undo -->
      <button
        @click="editor.chain().focus().undo().run()"
        :disabled="!editor?.can().undo()"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
        title="Undo (Ctrl+Z)"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M9.707 16.707a1 1 0 01-1.414 0l-6-6a1 1 0 010-1.414l6-6a1 1 0 011.414 1.414L5.414 9H17a1 1 0 110 2H5.414l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd"/>
        </svg>
      </button>
      
      <!-- Redo -->
      <button
        @click="editor.chain().focus().redo().run()"
        :disabled="!editor?.can().redo()"
        class="px-2 py-1 text-sm border border-gray-300 rounded hover:bg-gray-200 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
        title="Redo (Ctrl+Y)"
      >
        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
          <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
        </svg>
      </button>
    </div>
    
    <!-- Editor Content -->
    <editor-content
      :editor="editor"
      :style="{ height: height + 'px' }"
      class="editor-content border border-gray-300 rounded-b-md p-3 focus:outline-none focus:ring-2 focus:ring-blue-500 overflow-y-auto"
    />
    
    <!-- Character Counter -->
    <div class="mt-2 text-xs text-gray-500 text-right">
      {{ characterCount }} karakter
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { useEditor, EditorContent } from '@tiptap/vue-3'
import StarterKit from '@tiptap/starter-kit'
import Link from '@tiptap/extension-link'
import TextAlign from '@tiptap/extension-text-align'
import Underline from '@tiptap/extension-underline'

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

// Character count (excluding HTML tags)
const characterCount = computed(() => {
  if (!editor.value) return 0
  const textContent = editor.value.getText()
  return textContent.length
})

// Initialize Tiptap editor
const editor = useEditor({
  content: props.modelValue,
  extensions: [
    StarterKit,
    Link.configure({
      openOnClick: false,
      HTMLAttributes: {
        class: 'text-blue-600 underline hover:text-blue-800',
      },
    }),
    TextAlign.configure({
      types: ['heading', 'paragraph'],
    }),
    Underline,
  ],
  editorProps: {
    attributes: {
      class: 'prose prose-sm sm:prose lg:prose-lg xl:prose-2xl mx-auto focus:outline-none',
      placeholder: props.placeholder,
    },
  },
  onUpdate: ({ editor }) => {
    const html = editor.getHTML()
    emit('update:modelValue', html)
    emit('change', html)
  },
  editable: !props.disabled,
})

// Set link function
const setLink = () => {
  const previousUrl = editor.value.getAttributes('link').href
  const url = window.prompt('URL', previousUrl)

  // cancelled
  if (url === null) {
    return
  }

  // empty
  if (url === '') {
    editor.value.chain().focus().extendMarkRange('link').unsetLink().run()
    return
  }

  // update link
  editor.value.chain().focus().extendMarkRange('link').setLink({ href: url }).run()
}

// Watch for external changes
watch(() => props.modelValue, (newValue) => {
  if (newValue !== editor.value?.getHTML()) {
    editor.value?.commands.setContent(newValue || '')
  }
})

// Watch for disabled state
watch(() => props.disabled, (newDisabled) => {
  editor.value?.setEditable(!newDisabled)
})

onMounted(() => {
  // Set initial content
  if (props.modelValue) {
    editor.value?.commands.setContent(props.modelValue)
  }
})

onUnmounted(() => {
  editor.value?.destroy()
})
</script>

<style scoped>
.tiptap-editor {
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
  line-height: 1.6;
  position: relative;
  z-index: 0;
  background: white;
}

.editor-content:focus {
  outline: none;
}

/* Tiptap content styling */
:deep(.ProseMirror) {
  outline: none;
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  font-size: 14px;
  line-height: 1.6;
  color: #374151;
}

:deep(.ProseMirror h1) {
  font-size: 1.5rem;
  font-weight: bold;
  margin: 16px 0 8px 0;
  color: #1f2937;
}

:deep(.ProseMirror h2) {
  font-size: 1.25rem;
  font-weight: bold;
  margin: 16px 0 8px 0;
  color: #1f2937;
}

:deep(.ProseMirror h3) {
  font-size: 1.125rem;
  font-weight: bold;
  margin: 16px 0 8px 0;
  color: #1f2937;
}

:deep(.ProseMirror p) {
  margin: 0 0 8px 0;
}

:deep(.ProseMirror ul, .ProseMirror ol) {
  margin: 8px 0;
  padding-left: 20px;
}

:deep(.ProseMirror li) {
  margin: 4px 0;
}

:deep(.ProseMirror a) {
  color: #3b82f6;
  text-decoration: underline;
}

:deep(.ProseMirror a:hover) {
  color: #1d4ed8;
}

:deep(.ProseMirror blockquote) {
  border-left: 4px solid #e5e7eb;
  padding-left: 16px;
  margin: 16px 0;
  font-style: italic;
  color: #6b7280;
}

:deep(.ProseMirror strong) {
  font-weight: bold;
}

:deep(.ProseMirror em) {
  font-style: italic;
}

:deep(.ProseMirror u) {
  text-decoration: underline;
}

:deep(.ProseMirror s) {
  text-decoration: line-through;
}

:deep(.ProseMirror p.is-editor-empty:first-child::before) {
  color: #9ca3af;
  content: attr(data-placeholder);
  float: left;
  height: 0;
  pointer-events: none;
}
</style>
