<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard Ekstrakurikuler</h1>
        <p class="mt-1 text-sm text-gray-500">
          Dashboard pembina ekstrakurikuler - kelola kegiatan dan prestasi siswa.
        </p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <StatCard title="Anggota Ekskul" :value="67" icon="user-group" color="blue" />
        <StatCard title="Kegiatan Bulan Ini" :value="12" icon="calendar" color="green" />
        <StatCard title="Prestasi Tahun Ini" :value="8" icon="trophy" color="yellow" />
        <StatCard title="Kompetisi Mendatang" :value="5" icon="flag" color="purple" />
      </div>

      <div class="card p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Fitur Ekstrakurikuler</h3>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
          <button class="btn btn-primary">Kelola Anggota</button>
          <button class="btn btn-secondary">Jadwal Latihan</button>
          <button class="btn btn-secondary">Input Prestasi</button>
          <button class="btn btn-outline">Laporan Ekskul</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import StatCard from '../../components/ui/StatCard.vue'
</script>
