import { createApp } from 'vue'
import { createPinia } from 'pinia'
import router from './router'
import App from './App.vue'
import './assets/main.css'

// Import plugins
import './plugins/validation.js'
import toastPlugin from './plugins/toast.js'

// Create Vue app
const app = createApp(App)

// Use Pinia for state management
const pinia = createPinia()
app.use(pinia)

// Use Vue Router
app.use(router)

// Use toast plugin
app.use(toastPlugin)

// Initialize theme after Pinia is set up
import { useThemeStore } from './stores/theme'
const themeStore = useThemeStore()
themeStore.initTheme()

// Mount the app
app.mount('#app')
