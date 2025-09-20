# Image Upload Component

## Overview
Komponen Image Upload dengan fitur drag & drop, image compression, dan preview yang lengkap.

## Features
- **Drag & Drop**: Support drag & drop multiple images
- **Image Compression**: Otomatis compress images untuk optimasi
- **Preview**: Preview images dengan overlay controls
- **Multiple Upload**: Support upload multiple images sekaligus
- **Validation**: File type, size, dan count validation
- **Image Viewer**: Modal untuk melihat gambar dalam ukuran penuh
- **Progress Tracking**: Upload progress indicator
- **Responsive**: Grid layout yang responsive

## Usage

### Basic Usage
```vue
<template>
  <ImageUpload
    v-model="images"
    :max-files="5"
    :max-size-m-b="5"
    @change="handleImageChange"
    @upload="handleImageUpload"
  />
</template>

<script setup>
import ImageUpload from '@/components/upload/ImageUpload.vue'

const images = ref([])

const handleImageChange = (newImages) => {
  console.log('Images changed:', newImages)
}

const handleImageUpload = (image) => {
  console.log('Image uploaded:', image)
}
</script>
```

### With Custom Settings
```vue
<template>
  <ImageUpload
    v-model="images"
    :max-files="3"
    :max-size-m-b="2"
    :max-width-or-height="1200"
    :quality="0.9"
    :disabled="isSubmitting"
    :error-message="errors.images"
  />
</template>
```

## Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `modelValue` | Array | `[]` | Array of image objects |
| `maxFiles` | Number | `5` | Maximum number of files |
| `maxSizeMB` | Number | `5` | Maximum file size in MB |
| `maxWidthOrHeight` | Number | `1920` | Maximum width or height for compression |
| `quality` | Number | `0.8` | Compression quality (0-1) |
| `disabled` | Boolean | `false` | Disable upload |
| `errorMessage` | String | `''` | Error message untuk validation |

## Events

| Event | Payload | Description |
|-------|---------|-------------|
| `update:modelValue` | Array | Emitted ketika images berubah |
| `change` | Array | Emitted ketika images berubah |
| `upload` | Object | Emitted ketika image berhasil diupload |

## Image Object Structure

```javascript
{
  id: 'unique-id',
  file: File, // Compressed file object
  name: 'image.jpg',
  size: 1024000, // Size in bytes
  preview: 'data:image/jpeg;base64...', // Base64 preview
  dimensions: {
    width: 1920,
    height: 1080
  },
  compression: true, // Whether image was compressed
  originalSize: 2048000 // Original size before compression
}
```

## Image Compression

### Automatic Compression
- Images otomatis di-compress menggunakan `image-compression` library
- Compression settings dapat di-customize via props
- Support format: JPG, PNG, GIF, WebP

### Compression Settings
```javascript
const compressionOptions = {
  maxSizeMB: 1, // Maximum file size in MB
  maxWidthOrHeight: 1920, // Maximum width or height
  useWebWorker: true, // Use web worker for better performance
  initialQuality: 0.8 // Initial compression quality
}
```

## Validation

### File Type Validation
- Hanya accept image files: `image/*`
- Support format: JPEG, PNG, GIF, WebP

### File Size Validation
- Default max size: 5MB
- Dapat di-customize via `maxSizeMB` prop

### File Count Validation
- Default max files: 5
- Dapat di-customize via `maxFiles` prop

## UI Features

### Upload Area
- Drag & drop zone dengan visual feedback
- Click to browse files
- Responsive design

### Image Grid
- Responsive grid layout (2-4 columns)
- Hover effects dengan overlay controls
- Image info display (name, size, compression status)

### Image Controls
- **Remove**: Hapus image dari list
- **View**: Buka image viewer modal
- **Compression Badge**: Indikator jika image di-compress

### Image Viewer Modal
- Full-size image preview
- Image details (size, dimensions)
- Close button

## Styling

Komponen menggunakan Tailwind CSS classes:

```css
.image-upload {
  --upload-border-color: #d1d5db;
  --upload-hover-color: #3b82f6;
  --upload-error-color: #ef4444;
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

## Backend Integration

Komponen ini dirancang untuk bekerja dengan backend API:

### Upload Endpoint
```
POST /api/images/upload
POST /api/images/upload-multiple
```

### Request Format
```javascript
const formData = new FormData()
formData.append('image', file)
formData.append('folder', 'uploads')
formData.append('optimize', true)
formData.append('width', 1920)
formData.append('quality', 85)
```

### Response Format
```javascript
{
  "success": true,
  "message": "Gambar berhasil diupload",
  "data": {
    "filename": "uuid.jpg",
    "path": "uploads/uuid.jpg",
    "url": "http://localhost:8000/storage/uploads/uuid.jpg",
    "size": 1024000,
    "mime_type": "image/jpeg",
    "original_name": "image.jpg",
    "optimized": true
  }
}
```
