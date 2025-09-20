import { ref } from 'vue'
import api from '../services/api'
import { useToast } from '../plugins/toast.js'

/**
 * Composable for API calls with error handling and loading states
 */
export function useApi() {
  const loading = ref(false)
  const error = ref(null)
  const toast = useToast()

  /**
   * Generic API call wrapper
   * @param {Function} apiCall - API function to call
   * @param {Object} options - Options for the API call
   */
  const call = async (apiCall, options = {}) => {
    const {
      showLoading = true,
      showError = true,
      showSuccess = false,
      successMessage = 'Operasi berhasil',
      errorMessage = 'Terjadi kesalahan',
      onSuccess = null,
      onError = null,
    } = options

    try {
      if (showLoading) {
        loading.value = true
      }
      
      error.value = null
      
      const response = await apiCall()
      
      if (showSuccess && successMessage) {
        toast.success(successMessage)
      }
      
      if (onSuccess) {
        onSuccess(response)
      }
      
      return { success: true, data: response.data, response }
      
    } catch (err) {
      console.error('API call error:', err)
      
      const errorMsg = err.response?.data?.message || errorMessage
      error.value = errorMsg
      
      if (showError) {
        toast.error(errorMsg)
      }
      
      if (onError) {
        onError(err)
      }
      
      return { 
        success: false, 
        error: err, 
        message: errorMsg,
        validationErrors: err.response?.data?.errors || {}
      }
      
    } finally {
      if (showLoading) {
        loading.value = false
      }
    }
  }

  /**
   * GET request
   */
  const get = async (url, options = {}) => {
    return call(() => api.get(url), options)
  }

  /**
   * POST request
   */
  const post = async (url, data, options = {}) => {
    return call(() => api.post(url, data), options)
  }

  /**
   * PUT request
   */
  const put = async (url, data, options = {}) => {
    return call(() => api.put(url, data), options)
  }

  /**
   * PATCH request
   */
  const patch = async (url, data, options = {}) => {
    return call(() => api.patch(url, data), options)
  }

  /**
   * DELETE request
   */
  const del = async (url, options = {}) => {
    return call(() => api.delete(url), options)
  }

  /**
   * Handle form submission with validation
   */
  const submitForm = async (url, data, method = 'POST', options = {}) => {
    const apiCall = () => {
      switch (method.toUpperCase()) {
        case 'PUT':
          return api.put(url, data)
        case 'PATCH':
          return api.patch(url, data)
        case 'DELETE':
          return api.delete(url)
        default:
          return api.post(url, data)
      }
    }

    return call(apiCall, {
      showSuccess: true,
      successMessage: options.successMessage || 'Data berhasil disimpan',
      ...options
    })
  }

  /**
   * Handle file upload
   */
  const uploadFile = async (url, file, options = {}) => {
    const formData = new FormData()
    formData.append('file', file)

    return call(() => api.post(url, formData, {
      headers: {
        'Content-Type': 'multipart/form-data',
      },
    }), {
      showSuccess: true,
      successMessage: options.successMessage || 'File berhasil diupload',
      ...options
    })
  }

  /**
   * Clear error state
   */
  const clearError = () => {
    error.value = null
  }

  return {
    loading,
    error,
    call,
    get,
    post,
    put,
    patch,
    delete: del,
    submitForm,
    uploadFile,
    clearError,
  }
}

/**
 * Composable for handling form errors from API
 */
export function useFormErrors() {
  const errors = ref({})

  /**
   * Set form errors from API response
   */
  const setErrors = (apiErrors) => {
    errors.value = apiErrors || {}
  }

  /**
   * Get error message for specific field
   */
  const getFieldError = (field) => {
    return errors.value[field]?.[0] || null
  }

  /**
   * Check if field has error
   */
  const hasFieldError = (field) => {
    return !!errors.value[field]
  }

  /**
   * Clear all errors
   */
  const clearErrors = () => {
    errors.value = {}
  }

  /**
   * Clear specific field error
   */
  const clearFieldError = (field) => {
    if (errors.value[field]) {
      delete errors.value[field]
    }
  }

  return {
    errors,
    setErrors,
    getFieldError,
    hasFieldError,
    clearErrors,
    clearFieldError,
  }
}

export default {
  useApi,
  useFormErrors,
}
