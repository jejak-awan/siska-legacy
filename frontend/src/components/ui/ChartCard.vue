<template>
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div class="flex items-center justify-between mb-4">
      <div>
        <h3 class="text-lg font-semibold text-gray-900">{{ title }}</h3>
        <p class="text-sm text-gray-500">{{ subtitle }}</p>
      </div>
      <div v-if="trend !== null" class="flex items-center">
        <svg 
          v-if="trend > 0" 
          class="h-4 w-4 text-green-500 mr-1" 
          fill="none" 
          viewBox="0 0 24 24" 
          stroke="currentColor"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l10-10M17 7v10M17 7H7" />
        </svg>
        <svg 
          v-else-if="trend < 0" 
          class="h-4 w-4 text-red-500 mr-1" 
          fill="none" 
          viewBox="0 0 24 24" 
          stroke="currentColor"
        >
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17l-10-10M7 7v10M7 7h10" />
        </svg>
        <span 
          class="text-sm font-medium"
          :class="trend > 0 ? 'text-green-600' : 'text-red-600'"
        >
          {{ Math.abs(trend) }}%
        </span>
      </div>
    </div>
    
    <!-- Chart Area -->
    <div class="h-48 flex items-end justify-between space-x-2">
      <div 
        v-for="(item, index) in data" 
        :key="index"
        class="flex-1 flex flex-col items-center"
      >
        <div 
          class="w-full rounded-t transition-all duration-500 hover:opacity-80"
          :class="getBarColor(index)"
          :style="{ height: `${(item.value / maxValue) * 100}%` }"
        ></div>
        <div class="mt-2 text-xs text-gray-500 text-center">
          <div class="font-medium">{{ item.value }}</div>
          <div class="truncate max-w-12">{{ item.label }}</div>
        </div>
      </div>
    </div>
    
    <!-- Legend -->
    <div v-if="showLegend" class="mt-4 flex flex-wrap gap-2">
      <div 
        v-for="(item, index) in data" 
        :key="index"
        class="flex items-center space-x-2"
      >
        <div 
          class="w-3 h-3 rounded-full"
          :class="getBarColor(index)"
        ></div>
        <span class="text-xs text-gray-600">{{ item.label }}</span>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  subtitle: {
    type: String,
    default: ''
  },
  data: {
    type: Array,
    required: true,
    validator: (data) => {
      return data.every(item => 
        typeof item.value === 'number' && 
        typeof item.label === 'string'
      )
    }
  },
  trend: {
    type: Number,
    default: null
  },
  showLegend: {
    type: Boolean,
    default: false
  },
  colorScheme: {
    type: String,
    default: 'blue',
    validator: (value) => ['blue', 'green', 'purple', 'red', 'yellow', 'indigo', 'pink'].includes(value)
  }
})

const maxValue = computed(() => {
  return Math.max(...props.data.map(item => item.value))
})

const getBarColor = (index) => {
  const colors = {
    blue: ['bg-blue-500', 'bg-blue-400', 'bg-blue-300', 'bg-blue-200'],
    green: ['bg-green-500', 'bg-green-400', 'bg-green-300', 'bg-green-200'],
    purple: ['bg-purple-500', 'bg-purple-400', 'bg-purple-300', 'bg-purple-200'],
    red: ['bg-red-500', 'bg-red-400', 'bg-red-300', 'bg-red-200'],
    yellow: ['bg-yellow-500', 'bg-yellow-400', 'bg-yellow-300', 'bg-yellow-200'],
    indigo: ['bg-indigo-500', 'bg-indigo-400', 'bg-indigo-300', 'bg-indigo-200'],
    pink: ['bg-pink-500', 'bg-pink-400', 'bg-pink-300', 'bg-pink-200']
  }
  
  const scheme = colors[props.colorScheme] || colors.blue
  return scheme[index % scheme.length]
}
</script>
