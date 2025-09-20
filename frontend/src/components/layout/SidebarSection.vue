<template>
  <div class="mb-2">
    <!-- Section Header with Dropdown Toggle -->
    <button
      v-if="!collapsed"
      @click="toggleSection"
      class="w-full flex items-center justify-between px-3 py-2 text-gray-500 tracking-wider hover:text-gray-700 hover:bg-gray-50 rounded-lg transition-colors sidebar-section-header"
    >
      <span>{{ title }}</span>
      <svg 
        class="h-4 w-4 transform transition-transform duration-200" 
        :class="{ 'rotate-180': isOpen }"
        fill="none" 
        viewBox="0 0 24 24" 
        stroke="currentColor"
      >
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
      </svg>
    </button>
    
    <!-- Collapsed Section (just a divider) -->
    <div v-else class="border-t border-gray-200 mx-3 my-2"></div>
    
    <!-- Section Content with Slide Animation -->
    <div 
      v-if="!collapsed"
      class="overflow-hidden transition-all duration-300 ease-in-out"
      :class="{ 'max-h-0': !isOpen, 'max-h-96': isOpen }"
    >
      <div class="space-y-1 pb-2">
        <slot />
      </div>
    </div>
    
    <!-- Collapsed Section Content (always visible when sidebar is collapsed) -->
    <div v-else class="space-y-1">
      <slot />
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  collapsed: {
    type: Boolean,
    default: false
  },
  defaultOpen: {
    type: Boolean,
    default: false
  }
})

const isOpen = ref(props.defaultOpen)

const toggleSection = () => {
  isOpen.value = !isOpen.value
  // Save state to localStorage
  localStorage.setItem(`sidebar-section-${props.title.toLowerCase()}`, isOpen.value.toString())
}

onMounted(() => {
  // Restore section state from localStorage
  const savedState = localStorage.getItem(`sidebar-section-${props.title.toLowerCase()}`)
  if (savedState !== null) {
    isOpen.value = savedState === 'true'
  }
})
</script>

<style scoped>
.sidebar-section-header {
  font-size: 14px !important;
  font-weight: 500 !important;
  line-height: 1.3 !important;
}
</style>