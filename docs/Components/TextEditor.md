# Rich Text Editor Component

## Overview
Komponen Rich Text Editor yang dibangun dengan contenteditable HTML5 dan fitur-fitur editing yang lengkap.

## Features
- **Text Formatting**: Bold, Italic, Underline
- **Headings**: H1, H2, H3
- **Lists**: Bullet list, Numbered list
- **Links**: Insert dan edit links
- **Images**: Upload dan insert images dengan optimization
- **Undo/Redo**: Keyboard shortcuts (Ctrl+Z, Ctrl+Y)
- **Drag & Drop**: Support drag & drop untuk images
- **Image Compression**: Otomatis compress images sebelum upload

## Usage

### Basic Usage
```vue
<template>
  <RichTextEditor
    v-model="content"
    placeholder="Ketik sesuatu..."
    :min-height="200"
    @change="handleChange"
  />
</template>

<script setup>
import RichTextEditor from '@/components/editor/RichTextEditor.vue'

const content = ref('')
const handleChange = (newContent) => {
  console.log('Content changed:', newContent)
}
</script>
```

### With Validation
```vue
<template>
  <RichTextEditor
    v-model="content"
    :error-message="errors.content"
    :disabled="isSubmitting"
  />
</template>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | String | `''` | HTML content dari editor |
| `placeholder` | String | `'Ketik sesuatu...'` | Placeholder text |
| `minHeight` | Number | `200` | Minimum height dalam pixels |
| `disabled` | Boolean | `false` | Disable editor |
| `errorMessage` | String | `''` | Error message untuk validation |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | String | Emitted ketika content berubah |
| `change` | String | Emitted ketika content berubah |

## Keyboard Shortcuts

| Shortcut | Action |
|----------|--------|
| `Ctrl+B` | Bold |
| `Ctrl+I` | Italic |
| `Ctrl+U` | Underline |
| `Ctrl+Z` | Undo |
| `Ctrl+Y` | Redo |

## Image Upload Features

### Automatic Compression
- Images otomatis di-compress menggunakan `image-compression` library
- Default settings: max 1MB, max 1920px width/height
- Support format: JPG, PNG, GIF, WebP

### Upload Methods
1. **File Upload**: Klik tombol image untuk pilih file
2. **URL Input**: Masukkan URL gambar langsung
3. **Drag & Drop**: Drag gambar ke editor area

## Styling

Komponen menggunakan Tailwind CSS classes dan dapat di-customize dengan CSS variables:

```css
.rich-text-editor {
  --editor-border-color: #d1d5db;
  --editor-focus-color: #3b82f6;
  --toolbar-bg: #f9fafb;
}
```

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## Dependencies

- Vue 3
- Tailwind CSS
- image-compression (untuk image optimization)
