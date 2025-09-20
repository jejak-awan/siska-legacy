import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '../services/api'

export const useAuthStore = defineStore('auth', () => {
  // State
  const user = ref(null)
  const token = ref(localStorage.getItem('auth_token') || null)
  const isLoading = ref(false)
  const error = ref(null)

  // Getters
  const isAuthenticated = computed(() => !!token.value && !!user.value)
  const userRole = computed(() => user.value?.role_type || null)
  const userProfile = computed(() => user.value?.profile || null)

  // Actions
  const login = async (credentials) => {
    try {
      isLoading.value = true
      error.value = null

      const response = await api.post('/login', credentials)
      const { user: userData, token: authToken } = response.data

      // Store token and user data
      token.value = authToken
      user.value = userData
      localStorage.setItem('auth_token', authToken)
      localStorage.setItem('user_data', JSON.stringify(userData))

      // Set default authorization header
      api.defaults.headers.common['Authorization'] = `Bearer ${authToken}`

      return { success: true, user: userData }
    } catch (err) {
      error.value = err.response?.data?.message || 'Login failed'
      return { success: false, error: error.value }
    } finally {
      isLoading.value = false
    }
  }

  const logout = async () => {
    try {
      if (token.value) {
        await api.post('/auth/logout')
      }
    } catch (err) {
      console.error('Logout error:', err)
    } finally {
      // Clear local state regardless of API call success
      token.value = null
      user.value = null
      localStorage.removeItem('auth_token')
      localStorage.removeItem('user_data')
      delete api.defaults.headers.common['Authorization']
      
      // Redirect to login if not already there
      if (window.location.pathname !== '/login') {
        window.location.href = '/login'
      }
    }
  }

  const fetchUser = async () => {
    try {
      if (!token.value) return false

      const response = await api.get('/auth/me')
      user.value = response.data.user
      localStorage.setItem('user_data', JSON.stringify(response.data.user))
      return true
    } catch (err) {
      console.error('Fetch user error:', err)
      // If token is invalid, logout
      await logout()
      return false
    }
  }

  const refreshToken = async () => {
    try {
      const response = await api.post('/auth/refresh')
      const { token: newToken } = response.data

      token.value = newToken
      localStorage.setItem('auth_token', newToken)
      api.defaults.headers.common['Authorization'] = `Bearer ${newToken}`

      return true
    } catch (err) {
      console.error('Token refresh error:', err)
      await logout()
      return false
    }
  }

  const changePassword = async (passwords) => {
    try {
      isLoading.value = true
      error.value = null

      await api.post('/auth/change-password', passwords)
      return { success: true }
    } catch (err) {
      error.value = err.response?.data?.message || 'Password change failed'
      return { success: false, error: error.value }
    } finally {
      isLoading.value = false
    }
  }

  const initialize = async () => {
    if (token.value) {
      // Set authorization header
      api.defaults.headers.common['Authorization'] = `Bearer ${token.value}`
      
      // Try to restore user data from localStorage first
      const savedUserData = localStorage.getItem('user_data')
      if (savedUserData) {
        try {
          user.value = JSON.parse(savedUserData)
        } catch (error) {
          console.error('Error parsing saved user data:', error)
        }
      }
      
      // Verify token and fetch fresh user data
      const success = await fetchUser()
      if (!success) {
        // Token is invalid, clear everything
        await logout()
      } else {
        // Start automatic token refresh
        startTokenRefresh()
      }
    }
  }

  const startTokenRefresh = () => {
    // Refresh token every 23 hours (before 24h expiration)
    const refreshInterval = 23 * 60 * 60 * 1000 // 23 hours in milliseconds
    
    setInterval(async () => {
      if (token.value && user.value) {
        try {
          await refreshToken()
          console.log('Token refreshed automatically')
        } catch (error) {
          console.error('Auto token refresh failed:', error)
          await logout()
        }
      }
    }, refreshInterval)
  }

  const clearError = () => {
    error.value = null
  }

  // Role-based helpers
  const hasRole = (role) => {
    if (Array.isArray(role)) {
      return role.includes(userRole.value)
    }
    return userRole.value === role
  }

  const hasAnyRole = (roles) => {
    return roles.some(role => userRole.value === role)
  }

  const canAccess = (permission) => {
    // Basic role-based access control
    const rolePermissions = {
      admin: ['*'], // Admin has all permissions
      guru: ['siswa.read', 'presensi.create', 'kredit_poin.create', 'dashboard.guru'],
      siswa: ['profile.read', 'presensi.read', 'kredit_poin.read', 'dashboard.siswa'],
      wali_kelas: ['siswa.read', 'siswa.update', 'kelas.read', 'presensi.create', 'kredit_poin.create', 'konseling.refer', 'dashboard.wali_kelas'],
      bk: ['siswa.read', 'konseling.create', 'konseling.read', 'konseling.update', 'home_visit.create', 'kredit_poin.read', 'dashboard.bk'],
      osis: ['siswa.read', 'osis.create', 'osis.read', 'osis.update', 'kegiatan.create', 'kredit_poin.create', 'dashboard.osis'],
      ekstrakurikuler: ['siswa.read', 'ekstrakurikuler.create', 'ekstrakurikuler.read', 'ekstrakurikuler.update', 'prestasi.create', 'kredit_poin.create', 'dashboard.ekstrakurikuler'],
      orang_tua: ['anak.read', 'presensi.read', 'kredit_poin.read', 'konseling.schedule', 'dashboard.orang_tua']
    }

    const userPermissions = rolePermissions[userRole.value] || []
    return userPermissions.includes('*') || userPermissions.includes(permission)
  }

  return {
    // State
    user,
    token,
    isLoading,
    error,
    
    // Getters
    isAuthenticated,
    userRole,
    userProfile,
    
    // Actions
    login,
    logout,
    fetchUser,
    refreshToken,
    changePassword,
    initialize,
    clearError,
    
    // Role helpers
    hasRole,
    hasAnyRole,
    canAccess
  }
})
