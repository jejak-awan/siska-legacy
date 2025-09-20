<template>
  <div class="min-h-screen bg-background">
    <PublicHeader />
    <main class="flex-1">
      <slot />
    </main>
    <PublicFooter />
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useThemeStore } from '../../stores/theme'
import PublicHeader from './PublicHeader.vue'
import PublicFooter from './PublicFooter.vue'

const themeStore = useThemeStore()

onMounted(() => {
  themeStore.initTheme()
})
</script>
