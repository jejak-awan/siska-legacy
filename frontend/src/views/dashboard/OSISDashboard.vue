<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard OSIS</h1>
        <p class="mt-1 text-sm text-gray-500">
          Dashboard OSIS - kelola kegiatan dan organisasi siswa.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <StatCard title="Anggota OSIS" :value="45" icon="user-group" color="blue" />
        <StatCard title="Kegiatan Aktif" :value="8" icon="calendar" color="green" />
        <StatCard title="Proposal Pending" :value="3" icon="document" color="yellow" />
        <StatCard title="Budget Tersedia" :value="'15M'" icon="currency-dollar" color="purple" />
      </div>

      <div class="card p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Fitur OSIS</h3>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
          <button class="btn btn-primary">Kelola Kegiatan</button>
          <button class="btn btn-secondary">Manajemen Anggota</button>
          <button class="btn btn-secondary">Proposal Kegiatan</button>
          <button class="btn btn-outline">Laporan OSIS</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import StatCard from '../../components/ui/StatCard.vue'
</script>
