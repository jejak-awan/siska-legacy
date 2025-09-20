<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Bimbingan Konseling</h1>
        <p class="mt-1 text-sm text-gray-500">
          Dashboard untuk konselor BK - kelola konseling dan bimbingan siswa.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <StatCard title="Siswa Konseling" :value="25" icon="user-group" color="blue" />
        <StatCard title="Sesi Bulan Ini" :value="48" icon="calendar" color="green" />
        <StatCard title="Kasus Aktif" :value="12" icon="exclamation-triangle" color="red" />
        <StatCard title="Home Visit" :value="8" icon="home" color="purple" />
      </div>

      <div class="card p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Fitur BK</h3>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
          <button class="btn btn-primary">Konseling Individu</button>
          <button class="btn btn-secondary">Konseling Kelompok</button>
          <button class="btn btn-secondary">Home Visit</button>
          <button class="btn btn-outline">Laporan BK</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import StatCard from '../../components/ui/StatCard.vue'
</script>
