import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const useThemeStore = defineStore('theme', () => {
  const theme = ref('light')
  const systemTheme = ref('light')

  // Computed
  const isDark = computed(() => theme.value === 'dark')
  const isLight = computed(() => theme.value === 'light')
  const isSystem = computed(() => theme.value === 'system')

  // Actions
  const setTheme = (newTheme) => {
    theme.value = newTheme
    applyTheme()
    localStorage.setItem('theme', newTheme)
  }

  const toggleTheme = () => {
    setTheme(theme.value === 'light' ? 'dark' : 'light')
  }

  const applyTheme = () => {
    const root = document.documentElement
    const currentTheme = theme.value === 'system' ? systemTheme.value : theme.value
    
    root.classList.remove('light', 'dark')
    root.classList.add(currentTheme)
    root.setAttribute('data-theme', currentTheme)
  }

  const initTheme = () => {
    // Get saved theme from localStorage
    const savedTheme = localStorage.getItem('theme')
    if (savedTheme && ['light', 'dark', 'system'].includes(savedTheme)) {
      theme.value = savedTheme
    }

    // Detect system theme
    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)')
    systemTheme.value = mediaQuery.matches ? 'dark' : 'light'

    // Listen for system theme changes
    mediaQuery.addEventListener('change', (e) => {
      systemTheme.value = e.matches ? 'dark' : 'light'
      if (theme.value === 'system') {
        applyTheme()
      }
    })

    // Apply initial theme
    applyTheme()
  }

  return {
    theme,
    systemTheme,
    isDark,
    isLight,
    isSystem,
    setTheme,
    toggleTheme,
    applyTheme,
    initTheme
  }
})
