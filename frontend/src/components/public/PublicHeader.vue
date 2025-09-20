<template>
  <header class="sticky top-0 z-50 w-full border-b border-gray-200 dark:border-gray-700 bg-white/95 dark:bg-gray-900/95 backdrop-blur supports-[backdrop-filter]:bg-white/60 dark:supports-[backdrop-filter]:bg-gray-900/60">
    <div class="container mx-auto px-4">
      <div class="flex h-16 items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center space-x-2">
          <router-link to="/" class="flex items-center space-x-2">
            <div class="h-8 w-8 rounded-lg bg-gray-900 dark:bg-white flex items-center justify-center">
              <span class="text-white dark:text-gray-900 font-bold text-sm">S</span>
            </div>
            <span class="font-bold text-xl text-gray-900 dark:text-white">SISKA</span>
          </router-link>
        </div>

        <!-- Navigation -->
        <nav class="hidden md:flex items-center space-x-6">
          <router-link 
            to="/" 
            class="text-sm font-medium transition-colors hover:text-gray-600 dark:hover:text-gray-300"
            :class="{ 'text-gray-900 dark:text-white': $route.path === '/', 'text-gray-600 dark:text-gray-300': $route.path !== '/' }"
          >
            Beranda
          </router-link>
          <router-link 
            to="/activities" 
            class="text-sm font-medium transition-colors hover:text-gray-600 dark:hover:text-gray-300"
            :class="{ 'text-gray-900 dark:text-white': $route.path === '/activities', 'text-gray-600 dark:text-gray-300': $route.path !== '/activities' }"
          >
            Kegiatan
          </router-link>
          <router-link 
            to="/news" 
            class="text-sm font-medium transition-colors hover:text-gray-600 dark:hover:text-gray-300"
            :class="{ 'text-gray-900 dark:text-white': $route.path === '/news', 'text-gray-600 dark:text-gray-300': $route.path !== '/news' }"
          >
            Berita
          </router-link>
          <router-link 
            to="/programs" 
            class="text-sm font-medium transition-colors hover:text-gray-600 dark:hover:text-gray-300"
            :class="{ 'text-gray-900 dark:text-white': $route.path === '/programs', 'text-gray-600 dark:text-gray-300': $route.path !== '/programs' }"
          >
            Program
          </router-link>
        </nav>

        <!-- Actions -->
        <div class="flex items-center space-x-4">
          <!-- Search -->
          <Button variant="ghost" size="icon" @click="toggleSearch" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800">
            <SearchIcon class="h-4 w-4" />
          </Button>
          
          <!-- Theme Toggle -->
          <ThemeToggle />
          
          <!-- Login Button -->
          <Button variant="outline" size="sm" @click="goToLogin" class="border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800">
            Masuk
          </Button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { useRouter } from 'vue-router'
import Button from '../ui/Button.vue'
import ThemeToggle from '../ui/ThemeToggle.vue'
import { SearchIcon } from 'lucide-vue-next'

const router = useRouter()

const toggleSearch = () => {
  // TODO: Implement search functionality
  console.log('Search clicked')
}

const goToLogin = () => {
  router.push('/login')
}
</script>
