<template>
  <div class="school-header bg-white border-b border-gray-200 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between py-4">
        <!-- School Info -->
        <div class="flex items-center space-x-4">
          <!-- School Logo -->
          <div class="flex-shrink-0">
            <div 
              v-if="schoolProfile?.logo_url || schoolProfile?.logo"
              class="h-12 w-12 rounded-lg overflow-hidden bg-gray-100 flex items-center justify-center"
            >
              <img 
                :src="schoolProfile.logo_url || (schoolProfile.logo ? `/storage/${schoolProfile.logo}` : '')" 
                :alt="schoolProfile?.nama || 'Logo Sekolah'"
                class="h-full w-full object-cover"
              >
            </div>
            <div 
              v-else
              class="h-12 w-12 rounded-lg bg-blue-100 flex items-center justify-center"
            >
              <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
              </svg>
            </div>
          </div>
          
          <!-- School Details -->
          <div class="min-w-0 flex-1">
            <h1 class="text-xl font-bold text-gray-900 truncate">
              {{ schoolProfile?.nama || 'Nama Sekolah' }}
            </h1>
            <p 
              v-if="schoolProfile?.slogan" 
              class="text-sm text-blue-600 font-medium italic truncate"
            >
              "{{ schoolProfile.slogan }}"
            </p>
            <div class="flex items-center space-x-4 text-xs text-gray-500 mt-1">
              <span v-if="schoolProfile?.npsn">NPSN: {{ schoolProfile.npsn }}</span>
              <span v-if="schoolProfile?.jenjang">{{ schoolProfile.jenjang }}</span>
              <span v-if="schoolProfile?.status">{{ schoolProfile.status }}</span>
              <span v-if="schoolProfile?.akreditasi">Akreditasi: {{ schoolProfile.akreditasi }}</span>
            </div>
          </div>
        </div>
        
        <!-- Current Page Info -->
        <div class="hidden md:flex items-center space-x-4">
          <div class="text-right">
            <p class="text-sm font-medium text-gray-900">{{ currentPageTitle }}</p>
            <p class="text-xs text-gray-500">{{ currentDate }}</p>
          </div>
          
          <!-- User Info -->
          <div class="flex items-center space-x-2">
            <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
              <svg class="h-4 w-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
              </svg>
            </div>
            <div class="text-sm">
              <p class="font-medium text-gray-900">{{ authStore.user?.username || 'User' }}</p>
              <p class="text-xs text-gray-500 capitalize">{{ authStore.user?.role_type || 'Role' }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import api from '@/services/api'

const route = useRoute()
const authStore = useAuthStore()

const schoolProfile = ref(null)
const loading = ref(false)

// Computed properties
const currentPageTitle = computed(() => {
  return route.meta?.title || route.name || 'Dashboard'
})

const currentDate = computed(() => {
  return new Date().toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
})

// Methods
const loadSchoolProfile = async () => {
  try {
    loading.value = true
    const response = await api.get('/profile-sekolah')
    schoolProfile.value = response.data.data
  } catch (error) {
    console.error('Error loading school profile:', error)
    // Set default values if API fails
    schoolProfile.value = {
      nama: 'Nama Sekolah',
      slogan: 'Slogan Sekolah',
      npsn: '',
      jenjang: '',
      status: '',
      akreditasi: '',
      logo: null,
      logo_url: null
    }
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  loadSchoolProfile()
})
</script>

<style scoped>
.school-header {
  @apply sticky top-0 z-40;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .school-header .max-w-7xl {
    @apply px-3;
  }
  
  .school-header h1 {
    @apply text-lg;
  }
  
  .school-header p {
    @apply text-xs;
  }
}
</style>
