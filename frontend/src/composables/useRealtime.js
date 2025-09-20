import { ref, onMounted, onUnmounted } from 'vue'
import { useAuthStore } from '../stores/auth'
import echo from '../services/echo'

export function useRealtime() {
  const authStore = useAuthStore()
  const isConnected = ref(false)
  const connectionError = ref(null)

  const connect = () => {
    try {
      // Listen to public channels
      echo.channel('kredit-poin')
        .listen('.kredit-poin.created', (data) => {
          console.log('📊 Kredit Poin Created:', data)
          // Emit custom event for components to listen
          window.dispatchEvent(new CustomEvent('kredit-poin-created', { detail: data }))
        })

      echo.channel('presensi')
        .listen('.presensi.created', (data) => {
          console.log('📅 Presensi Created:', data)
          window.dispatchEvent(new CustomEvent('presensi-created', { detail: data }))
        })

      echo.channel('dashboard-stats')
        .listen('.dashboard.stats.updated', (data) => {
          console.log('📈 Dashboard Stats Updated:', data)
          window.dispatchEvent(new CustomEvent('dashboard-stats-updated', { detail: data }))
        })

      // Listen to private channels based on user role
      if (authStore.isAuthenticated) {
        const role = authStore.userRole
        
        // Admin dashboard
        if (role === 'admin') {
          echo.private('admin-dashboard')
            .listen('.kredit-poin.created', (data) => {
              console.log('🔔 Admin: Kredit Poin Created:', data)
              window.dispatchEvent(new CustomEvent('admin-kredit-poin-created', { detail: data }))
            })
            .listen('.presensi.created', (data) => {
              console.log('🔔 Admin: Presensi Created:', data)
              window.dispatchEvent(new CustomEvent('admin-presensi-created', { detail: data }))
            })
            .listen('.dashboard.stats.updated', (data) => {
              console.log('🔔 Admin: Dashboard Stats Updated:', data)
              window.dispatchEvent(new CustomEvent('admin-dashboard-stats-updated', { detail: data }))
            })
        }

        // Guru dashboard
        if (role === 'guru' || role === 'wali_kelas') {
          echo.private('guru-dashboard')
            .listen('.presensi.created', (data) => {
              console.log('🔔 Guru: Presensi Created:', data)
              window.dispatchEvent(new CustomEvent('guru-presensi-created', { detail: data }))
            })
            .listen('.dashboard.stats.updated', (data) => {
              console.log('🔔 Guru: Dashboard Stats Updated:', data)
              window.dispatchEvent(new CustomEvent('guru-dashboard-stats-updated', { detail: data }))
            })
        }

        // Siswa dashboard
        if (role === 'siswa') {
          echo.private('siswa-dashboard')
            .listen('.dashboard.stats.updated', (data) => {
              console.log('🔔 Siswa: Dashboard Stats Updated:', data)
              window.dispatchEvent(new CustomEvent('siswa-dashboard-stats-updated', { detail: data }))
            })
        }
      }

      isConnected.value = true
      connectionError.value = null
      console.log('✅ Real-time connection established')

    } catch (error) {
      console.error('❌ Real-time connection failed:', error)
      connectionError.value = error.message
      isConnected.value = false
    }
  }

  const disconnect = () => {
    try {
      echo.disconnect()
      isConnected.value = false
      console.log('🔌 Real-time connection disconnected')
    } catch (error) {
      console.error('❌ Error disconnecting:', error)
    }
  }

  const reconnect = () => {
    disconnect()
    setTimeout(() => {
      connect()
    }, 1000)
  }

  onMounted(() => {
    if (authStore.isAuthenticated) {
      connect()
    }
  })

  onUnmounted(() => {
    disconnect()
  })

  return {
    isConnected,
    connectionError,
    connect,
    disconnect,
    reconnect
  }
}
