<template>
  <Button
    variant="ghost"
    size="icon"
    @click="toggleTheme"
    :title="isDark ? 'Switch to light mode' : 'Switch to dark mode'"
    class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white hover:bg-gray-100 dark:hover:bg-gray-800"
  >
    <SunIcon v-if="isDark" class="h-5 w-5" />
    <MoonIcon v-else class="h-5 w-5" />
  </Button>
</template>

<script setup>
import { computed } from 'vue'
import { useThemeStore } from '../../stores/theme'
import Button from './Button.vue'
import { SunIcon, MoonIcon } from 'lucide-vue-next'

const themeStore = useThemeStore()

const isDark = computed(() => themeStore.isDark)

const toggleTheme = () => {
  themeStore.toggleTheme()
}
</script>
