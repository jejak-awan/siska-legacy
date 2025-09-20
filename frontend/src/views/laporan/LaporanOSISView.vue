<template>
  <div class="p-6">
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">{{ pageTitle }}</h1>
      <p class="text-gray-600">{{ pageDescription }}</p>
    </div>

    <!-- Content Placeholder -->
    <div class="bg-white rounded-lg shadow p-6">
      <div class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">{{ pageTitle }}</h3>
        <p class="mt-1 text-sm text-gray-500">Fitur ini sedang dalam pengembangan.</p>
        <div class="mt-6">
          <button
            type="button"
            class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
          >
            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            Mulai Pengembangan
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

// Get page info from route name
const routeName = computed(() => {
  // This would be connected to the actual route in real implementation
  return 'Default Page'
})

const pageTitle = computed(() => {
  const titles = {
    'EkstrakurikulerPrestasi': 'Prestasi Ekstrakurikuler',
    'EkstrakurikulerJadwal': 'Jadwal Ekstrakurikuler',
    'LaporanPresensi': 'Laporan Presensi',
    'LaporanKonseling': 'Laporan Konseling',
    'LaporanOSIS': 'Laporan OSIS'
  }
  return titles[routeName.value] || 'Halaman'
})

const pageDescription = computed(() => {
  const descriptions = {
    'EkstrakurikulerPrestasi': 'Manajemen prestasi dan pencapaian ekstrakurikuler',
    'EkstrakurikulerJadwal': 'Jadwal kegiatan ekstrakurikuler',
    'LaporanPresensi': 'Laporan kehadiran siswa',
    'LaporanKonseling': 'Laporan bimbingan konseling',
    'LaporanOSIS': 'Laporan kegiatan OSIS'
  }
  return descriptions[routeName.value] || 'Deskripsi halaman'
})
</script>
