import { ref } from 'vue'
import { useApi } from './useApi.js'
import { useToast } from '../plugins/toast.js'

/**
 * Composable for image upload functionality
 */
export function useImageUpload(options = {}) {
  const {
    maxFiles = 5,
    maxSizeMB = 5,
    maxWidthOrHeight = 1920,
    quality = 0.8,
    folder = 'uploads',
    autoOptimize = true
  } = options

  const api = useApi()
  const toast = useToast()
  
  const uploading = ref(false)
  const uploadProgress = ref(0)
  const uploadedImages = ref([])

  /**
   * Upload single image
   */
  const uploadSingle = async (file, customOptions = {}) => {
    try {
      uploading.value = true
      uploadProgress.value = 0

      const formData = new FormData()
      formData.append('image', file)
      formData.append('folder', customOptions.folder || folder)
      formData.append('optimize', customOptions.optimize !== undefined ? customOptions.optimize : autoOptimize)
      
      if (customOptions.width || maxWidthOrHeight) {
        formData.append('width', customOptions.width || maxWidthOrHeight)
      }
      
      if (customOptions.height) {
        formData.append('height', customOptions.height)
      }
      
      formData.append('quality', customOptions.quality || Math.round(quality * 100))

      const response = await api.post('/images/upload', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        onUploadProgress: (progressEvent) => {
          uploadProgress.value = Math.round(
            (progressEvent.loaded * 100) / progressEvent.total
          )
        },
        showSuccess: false,
        showError: true
      })

      if (response.success) {
        uploadedImages.value.push(response.data)
        toast.success('Gambar berhasil diupload')
        return response.data
      } else {
        throw new Error(response.message || 'Upload gagal')
      }

    } catch (error) {
      console.error('Upload error:', error)
      toast.error(error.message || 'Gagal mengupload gambar')
      throw error
    } finally {
      uploading.value = false
      uploadProgress.value = 0
    }
  }

  /**
   * Upload multiple images
   */
  const uploadMultiple = async (files, customOptions = {}) => {
    try {
      uploading.value = true
      uploadProgress.value = 0

      const formData = new FormData()
      
      // Add all files
      files.forEach(file => {
        formData.append('images[]', file)
      })
      
      formData.append('folder', customOptions.folder || folder)
      formData.append('optimize', customOptions.optimize !== undefined ? customOptions.optimize : autoOptimize)
      
      if (customOptions.width || maxWidthOrHeight) {
        formData.append('width', customOptions.width || maxWidthOrHeight)
      }
      
      if (customOptions.height) {
        formData.append('height', customOptions.height)
      }
      
      formData.append('quality', customOptions.quality || Math.round(quality * 100))

      const response = await api.post('/images/upload-multiple', formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
        onUploadProgress: (progressEvent) => {
          uploadProgress.value = Math.round(
            (progressEvent.loaded * 100) / progressEvent.total
          )
        },
        showSuccess: false,
        showError: true
      })

      if (response.success) {
        uploadedImages.value.push(...response.data)
        toast.success(`${response.data.length} gambar berhasil diupload`)
        return response.data
      } else {
        throw new Error(response.message || 'Upload gagal')
      }

    } catch (error) {
      console.error('Upload error:', error)
      toast.error(error.message || 'Gagal mengupload gambar')
      throw error
    } finally {
      uploading.value = false
      uploadProgress.value = 0
    }
  }

  /**
   * Resize existing image
   */
  const resizeImage = async (path, width, height, customOptions = {}) => {
    try {
      const response = await api.post('/images/resize', {
        path,
        width,
        height,
        quality: customOptions.quality || Math.round(quality * 100)
      }, {
        showSuccess: false,
        showError: true
      })

      if (response.success) {
        toast.success('Gambar berhasil diresize')
        return response.data
      } else {
        throw new Error(response.message || 'Resize gagal')
      }

    } catch (error) {
      console.error('Resize error:', error)
      toast.error(error.message || 'Gagal meresize gambar')
      throw error
    }
  }

  /**
   * Delete image
   */
  const deleteImage = async (path) => {
    try {
      const response = await api.delete('/images/delete', {
        data: { path }
      }, {
        showSuccess: false,
        showError: true
      })

      if (response.success) {
        // Remove from uploaded images
        uploadedImages.value = uploadedImages.value.filter(img => img.path !== path)
        toast.success('Gambar berhasil dihapus')
        return true
      } else {
        throw new Error(response.message || 'Delete gagal')
      }

    } catch (error) {
      console.error('Delete error:', error)
      toast.error(error.message || 'Gagal menghapus gambar')
      throw error
    }
  }

  /**
   * Get image info
   */
  const getImageInfo = async (path) => {
    try {
      const response = await api.get('/images/info', {
        params: { path }
      }, {
        showError: true
      })

      if (response.success) {
        return response.data
      } else {
        throw new Error(response.message || 'Gagal mendapatkan info gambar')
      }

    } catch (error) {
      console.error('Get info error:', error)
      toast.error(error.message || 'Gagal mendapatkan info gambar')
      throw error
    }
  }

  /**
   * Clear uploaded images
   */
  const clearUploadedImages = () => {
    uploadedImages.value = []
  }

  /**
   * Validate file before upload
   */
  const validateFile = (file) => {
    const errors = []

    // Check file type
    if (!file.type.startsWith('image/')) {
      errors.push('File harus berupa gambar')
    }

    // Check file size
    if (file.size > maxSizeMB * 1024 * 1024) {
      errors.push(`Ukuran file maksimal ${maxSizeMB}MB`)
    }

    // Check max files
    if (uploadedImages.value.length >= maxFiles) {
      errors.push(`Maksimal ${maxFiles} gambar`)
    }

    return {
      isValid: errors.length === 0,
      errors
    }
  }

  /**
   * Validate multiple files
   */
  const validateFiles = (files) => {
    const errors = []
    const validFiles = []

    // Check total files count
    if (uploadedImages.value.length + files.length > maxFiles) {
      errors.push(`Maksimal ${maxFiles} gambar`)
      return { isValid: false, errors, validFiles: [] }
    }

    files.forEach((file, index) => {
      const validation = validateFile(file)
      if (validation.isValid) {
        validFiles.push(file)
      } else {
        errors.push(`File ${index + 1}: ${validation.errors.join(', ')}`)
      }
    })

    return {
      isValid: errors.length === 0,
      errors,
      validFiles
    }
  }

  return {
    // State
    uploading,
    uploadProgress,
    uploadedImages,

    // Methods
    uploadSingle,
    uploadMultiple,
    resizeImage,
    deleteImage,
    getImageInfo,
    clearUploadedImages,
    validateFile,
    validateFiles,

    // Options
    maxFiles,
    maxSizeMB,
    maxWidthOrHeight,
    quality,
    folder
  }
}

export default {
  useImageUpload
}
