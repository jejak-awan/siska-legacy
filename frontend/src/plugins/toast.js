import Toast from 'vue-toast-notification'
import 'vue-toast-notification/dist/theme-sugar.css'

// Toast configuration
const toastOptions = {
  position: 'top-right',
  duration: 4000,
  dismissible: true,
  pauseOnHover: true,
  useDefaultCss: true,
  toastClassName: 'custom-toast',
  bodyClassName: 'custom-toast-body',
  hideProgressBar: false,
  closeButton: true,
  closeOnClick: true,
  maxToasts: 5,
  newestOnTop: true,
}

// Toast utility functions
export const useToast = () => {
  return {
    success: (message, options = {}) => {
      return Toast.open({
        message,
        type: 'success',
        duration: 3000,
        ...toastOptions,
        ...options
      })
    },
    error: (message, options = {}) => {
      return Toast.open({
        message,
        type: 'error',
        duration: 5000,
        ...toastOptions,
        ...options
      })
    },
    warning: (message, options = {}) => {
      return Toast.open({
        message,
        type: 'warning',
        duration: 4000,
        ...toastOptions,
        ...options
      })
    },
    info: (message, options = {}) => {
      return Toast.open({
        message,
        type: 'info',
        duration: 3000,
        ...toastOptions,
        ...options
      })
    },
    clear: () => {
      return Toast.clear()
    }
  }
}

export default {
  install(app) {
    app.use(Toast, toastOptions)
  }
}
