<template>
  <div class="p-6">
    <!-- Header -->
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900 mb-2">Data Sekolah</h1>
      <p class="text-gray-600">Kelola informasi profil, visi misi, dan struktur organisasi sekolah</p>
    </div>

    <!-- Tabs -->
    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
      <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
          <button
            v-for="tab in tabs"
            :key="tab.id"
            @click="activeTab = tab.id"
            :class="[
              activeTab === tab.id
                ? 'border-blue-500 text-blue-600'
                : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
              'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm'
            ]"
          >
            {{ tab.name }}
          </button>
        </nav>
      </div>

      <!-- Tab Content -->
      <div class="p-6">
        <!-- Profile Sekolah Tab -->
        <div v-if="activeTab === 'profile'" class="space-y-6">
          <h3 class="text-lg font-semibold text-gray-900">Profil Sekolah</h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Sekolah</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan nama sekolah">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">NPSN</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan NPSN">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="3" placeholder="Masukkan alamat sekolah"></textarea>
              </div>
            </div>
            <div class="space-y-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                <input type="text" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan nomor telepon">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input type="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan email sekolah">
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Website</label>
                <input type="url" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Masukkan website sekolah">
              </div>
            </div>
          </div>
        </div>

        <!-- Visi Misi & Program Unggulan Tab -->
        <div v-if="activeTab === 'visi-misi'" class="space-y-6">
          <h3 class="text-lg font-semibold text-gray-900">Visi, Misi & Program Unggulan</h3>
          <div class="space-y-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Visi</label>
              <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4" placeholder="Masukkan visi sekolah"></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Misi</label>
              <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="6" placeholder="Masukkan misi sekolah"></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Program Unggulan</label>
              <textarea class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500" rows="4" placeholder="Masukkan program unggulan sekolah"></textarea>
            </div>
          </div>
        </div>

        <!-- Struktur Organisasi Tab -->
        <div v-if="activeTab === 'struktur'" class="space-y-6">
          <h3 class="text-lg font-semibold text-gray-900">Struktur Organisasi</h3>
          <div class="bg-gray-50 p-4 rounded-lg">
            <p class="text-gray-600">Fitur struktur organisasi akan segera tersedia. Anda dapat mengelola hierarki organisasi sekolah di sini.</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Action Buttons -->
    <div class="mt-6 flex justify-end space-x-3">
      <button class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
        Batal
      </button>
      <button class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
        Simpan Perubahan
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const activeTab = ref('profile')

const tabs = [
  { id: 'profile', name: 'Profile Sekolah' },
  { id: 'visi-misi', name: 'Visi & Misi + Program Unggulan' },
  { id: 'struktur', name: 'Struktur Organisasi' }
]
</script>
