<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Page Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Guru</h1>
            <p class="mt-1 text-sm text-gray-500">
              Kelola data guru dan tenaga pendidik
            </p>
          </div>
          <button class="btn btn-primary">
            <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Tambah Guru
          </button>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <StatCard
          title="Total Guru"
          :value="stats.totalGuru"
          :loading="statsLoading"
          icon="academic-cap"
          color="blue"
        />
        <StatCard
          title="Wali Kelas"
          :value="stats.waliKelasCount"
          :loading="statsLoading"
          icon="user-group"
          color="green"
        />
        <StatCard
          title="Konselor BK"
          :value="stats.konselorBKCount"
          :loading="statsLoading"
          icon="heart"
          color="red"
        />
        <StatCard
          title="Pembina OSIS"
          :value="stats.pembinaOSISCount"
          :loading="statsLoading"
          icon="flag"
          color="purple"
        />
      </div>

      <!-- Content Card -->
      <div class="card p-8 text-center">
        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
        </svg>
        <h3 class="text-lg font-medium text-gray-900 mb-2">Manajemen Guru</h3>
        <p class="text-gray-500 mb-6">
          Fitur ini sedang dalam pengembangan. Anda dapat melihat statistik guru di atas.
        </p>
        <div class="flex justify-center space-x-4">
          <button class="btn btn-primary">Lihat Daftar Guru</button>
          <button class="btn btn-outline">Import Data Guru</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import StatCard from '../../components/ui/StatCard.vue'

const stats = ref({
  totalGuru: 45,
  waliKelasCount: 12,
  konselorBKCount: 3,
  pembinaOSISCount: 2
})

const statsLoading = ref(false)

onMounted(() => {
  // Fetch guru stats
})
</script>
