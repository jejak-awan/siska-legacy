<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Page Header -->
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Jadwal Pelajaran</h1>
        <p class="mt-1 text-sm text-gray-500">
          Kelola jadwal pelajaran dan jadwal mengajar
        </p>
      </div>

      <!-- Content Placeholder -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-8">
        <div class="text-center">
          <div class="mx-auto h-12 w-12 text-gray-400">
            <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a1 1 0 012 0v4m0 0v4m0-4h4m-4 0H6m5 10v-1a1 1 0 10-2 0v1m2 0H9m0 0v1a1 1 0 102 0v-1m0 0h2" />
            </svg>
          </div>
          <h3 class="mt-2 text-sm font-medium text-gray-900">Jadwal Pelajaran</h3>
          <p class="mt-1 text-sm text-gray-500">
            Fitur jadwal pelajaran akan segera tersedia
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

// State
const jadwalData = ref([])
const loading = ref(false)

// Methods
const fetchJadwal = async () => {
  try {
    loading.value = true
    // TODO: Implement API call
  } catch (error) {
    console.error('Failed to fetch jadwal:', error)
  } finally {
    loading.value = false
  }
}

// Lifecycle
onMounted(() => {
  fetchJadwal()
})
</script>
