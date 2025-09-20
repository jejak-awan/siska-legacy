<template>
  <div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="mb-8">
        <div class="flex items-center justify-between">
          <div>
            <h1 class="text-3xl font-bold text-gray-900">Pengaturan Sistem</h1>
            <p class="mt-2 text-lg text-gray-600">Kelola konfigurasi dan pengaturan sistem kesiswaan</p>
            <p class="text-sm text-gray-500">Konfigurasi lengkap untuk optimalisasi sistem</p>
          </div>
          <div class="flex items-center space-x-3">
            <button
              @click="resetSettings"
              class="flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 transition-colors"
            >
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              Reset
            </button>
            <button
              @click="saveSettings"
              :disabled="isSaving"
              class="flex items-center px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors disabled:opacity-50"
            >
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              <span v-if="isSaving">Menyimpan...</span>
              <span v-else>Simpan Pengaturan</span>
            </button>
          </div>
        </div>
      </div>

      <!-- Tabs -->
      <div class="mb-8">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-1">
          <nav class="flex space-x-1">
            <button
              @click="activeTab = 'umum'"
              :class="[
                'flex items-center px-4 py-2 rounded-lg font-medium text-sm transition-colors',
                activeTab === 'umum'
                  ? 'bg-blue-600 text-white'
                  : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'
              ]"
            >
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
              </svg>
              Pengaturan Umum
            </button>
            <button
              @click="activeTab = 'akademik'"
              :class="[
                'flex items-center px-4 py-2 rounded-lg font-medium text-sm transition-colors',
                activeTab === 'akademik'
                  ? 'bg-blue-600 text-white'
                  : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'
              ]"
            >
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
              </svg>
              Pengaturan Akademik
            </button>
            <button
              @click="activeTab = 'notifikasi'"
              :class="[
                'flex items-center px-4 py-2 rounded-lg font-medium text-sm transition-colors',
                activeTab === 'notifikasi'
                  ? 'bg-blue-600 text-white'
                  : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'
              ]"
            >
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.828 7l2.586 2.586a2 2 0 002.828 0L12.828 7H4.828z" />
              </svg>
              Notifikasi
            </button>
            <button
              @click="activeTab = 'backup'"
              :class="[
                'flex items-center px-4 py-2 rounded-lg font-medium text-sm transition-colors',
                activeTab === 'backup'
                  ? 'bg-blue-600 text-white'
                  : 'text-gray-600 hover:text-gray-900 hover:bg-gray-100'
              ]"
            >
              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
              </svg>
              Backup & Restore
            </button>
          </nav>
        </div>
      </div>

      <!-- Tab Content: Pengaturan Umum -->
      <div v-if="activeTab === 'umum'" class="space-y-6">
        <!-- Informasi Sekolah -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <div class="flex items-center mb-6">
            <div class="p-2 bg-blue-100 rounded-lg mr-3">
              <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Informasi Sekolah</h3>
              <p class="text-sm text-gray-500">Data identitas dan kontak sekolah</p>
            </div>
          </div>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Nama Sekolah</label>
              <input
                v-model="settings.school.name"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan nama sekolah"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">NPSN</label>
              <input
                v-model="settings.school.npsn"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan NPSN"
              >
            </div>
            <div class="md:col-span-2">
              <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
              <textarea
                v-model="settings.school.address"
                rows="3"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan alamat lengkap sekolah"
              ></textarea>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
              <input
                v-model="settings.school.phone"
                type="text"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan nomor telepon"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
              <input
                v-model="settings.school.email"
                type="email"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                placeholder="Masukkan email sekolah"
              >
            </div>
          </div>
        </div>

        <!-- Pengaturan Sistem -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
          <div class="flex items-center mb-6">
            <div class="p-2 bg-green-100 rounded-lg mr-3">
              <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Pengaturan Sistem</h3>
              <p class="text-sm text-gray-500">Konfigurasi sistem dan maintenance</p>
            </div>
          </div>
          
          <div class="space-y-6">
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
              <div>
                <label class="text-sm font-medium text-gray-700">Mode Maintenance</label>
                <p class="text-sm text-gray-500">Aktifkan untuk menonaktifkan akses sistem sementara</p>
              </div>
              <label class="relative inline-flex items-center cursor-pointer">
                <input
                  v-model="settings.system.maintenanceMode"
                  type="checkbox"
                  class="sr-only peer"
                >
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
              </label>
            </div>
            
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
              <div>
                <label class="text-sm font-medium text-gray-700">Auto Backup</label>
                <p class="text-sm text-gray-500">Backup otomatis database setiap hari</p>
              </div>
              <label class="relative inline-flex items-center cursor-pointer">
                <input
                  v-model="settings.system.autoBackup"
                  type="checkbox"
                  class="sr-only peer"
                >
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
              </label>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
              <select
                v-model="settings.system.timezone"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
              >
                <option value="Asia/Jakarta">WIB (GMT+7)</option>
                <option value="Asia/Makassar">WITA (GMT+8)</option>
                <option value="Asia/Jayapura">WIT (GMT+9)</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <!-- Tab Content: Pengaturan Akademik -->
      <div v-if="activeTab === 'akademik'" class="space-y-6">
        <!-- Tahun Ajaran -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Tahun Ajaran</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tahun Ajaran Aktif</label>
            <select
              v-model="settings.academic.activeYear"
              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
              <option value="2023/2024">2023/2024</option>
              <option value="2024/2025">2024/2025</option>
              <option value="2025/2026">2025/2026</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Semester Aktif</label>
            <select
              v-model="settings.academic.activeSemester"
              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
              <option value="1">Semester 1</option>
              <option value="2">Semester 2</option>
            </select>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Minggu Efektif</label>
            <input
              v-model="settings.academic.effectiveWeeks"
              type="number"
              min="1"
              max="20"
              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
          </div>
        </div>
      </div>

      <!-- Pengaturan Presensi -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Pengaturan Presensi</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Waktu Masuk</label>
            <input
              v-model="settings.attendance.startTime"
              type="time"
              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Batas Terlambat</label>
            <input
              v-model="settings.attendance.lateThreshold"
              type="time"
              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Minimal Kehadiran (%)</label>
            <input
              v-model="settings.attendance.minAttendance"
              type="number"
              min="0"
              max="100"
              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
          </div>
          <div class="flex items-center">
            <input
              v-model="settings.attendance.autoAbsent"
              type="checkbox"
              class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
            >
            <label class="ml-2 block text-sm text-gray-700">
              Auto Alpha jika tidak presensi
            </label>
          </div>
        </div>
      </div>

      <!-- Pengaturan Kredit Poin -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Pengaturan Kredit Poin</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Poin Maksimal</label>
            <input
              v-model="settings.creditPoint.maxPoints"
              type="number"
              min="0"
              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Batas Peringatan</label>
            <input
              v-model="settings.creditPoint.warningThreshold"
              type="number"
              min="0"
              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Batas Bahaya</label>
            <input
              v-model="settings.creditPoint.dangerThreshold"
              type="number"
              min="0"
              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
            >
          </div>
        </div>
      </div>
      </div>

      <!-- Tab Content: Notifikasi -->
      <div v-if="activeTab === 'notifikasi'" class="space-y-6">
        <!-- WhatsApp Settings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
          <h3 class="text-lg font-medium text-gray-900 mb-4">Pengaturan WhatsApp</h3>
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <div>
              <label class="text-sm font-medium text-gray-700">Aktifkan Notifikasi WhatsApp</label>
              <p class="text-sm text-gray-500">Kirim notifikasi otomatis via WhatsApp</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
              <input
                v-model="settings.notification.whatsapp.enabled"
                type="checkbox"
                class="sr-only peer"
              >
              <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
            </label>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">API Token</label>
              <input
                v-model="settings.notification.whatsapp.apiToken"
                type="password"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number ID</label>
              <input
                v-model="settings.notification.whatsapp.phoneNumberId"
                type="text"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              >
            </div>
          </div>
        </div>
      </div>

      <!-- Email Settings -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Pengaturan Email</h3>
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <div>
              <label class="text-sm font-medium text-gray-700">Aktifkan Notifikasi Email</label>
              <p class="text-sm text-gray-500">Kirim notifikasi otomatis via Email</p>
            </div>
            <label class="relative inline-flex items-center cursor-pointer">
              <input
                v-model="settings.notification.email.enabled"
                type="checkbox"
                class="sr-only peer"
              >
              <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-blue-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
            </label>
          </div>
          
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Host</label>
              <input
                v-model="settings.notification.email.smtpHost"
                type="text"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">SMTP Port</label>
              <input
                v-model="settings.notification.email.smtpPort"
                type="number"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Username</label>
              <input
                v-model="settings.notification.email.username"
                type="text"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              >
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
              <input
                v-model="settings.notification.email.password"
                type="password"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"
              >
            </div>
          </div>
        </div>
      </div>
      </div>

      <!-- Tab Content: Backup & Restore -->
      <div v-if="activeTab === 'backup'" class="space-y-6">
        <!-- Backup Settings -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Backup Database</h3>
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <div>
              <p class="text-sm text-gray-700">Backup database secara manual atau otomatis</p>
              <p class="text-sm text-gray-500">Backup terakhir: {{ lastBackup }}</p>
            </div>
            <div class="space-x-2">
              <button
                @click="createBackup"
                :disabled="isBackingUp"
                class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:opacity-50"
              >
                <span v-if="isBackingUp">Backing up...</span>
                <span v-else>Backup Sekarang</span>
              </button>
              <button
                @click="downloadBackup"
                class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500"
              >
                Download Backup
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Backup History -->
      <div class="bg-white rounded-lg shadow-sm border border-gray-200">
        <div class="px-6 py-4 border-b border-gray-200">
          <h3 class="text-lg font-medium text-gray-900">Riwayat Backup</h3>
        </div>
        
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ukuran</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="backup in backupHistory" :key="backup.id">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ backup.date }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ backup.size }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                    :class="backup.status === 'Success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'"
                  >
                    {{ backup.status }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <button
                    @click="downloadBackupFile(backup)"
                    class="text-blue-600 hover:text-blue-900 mr-3"
                  >
                    Download
                  </button>
                  <button
                    @click="deleteBackup(backup)"
                    class="text-red-600 hover:text-red-900"
                  >
                    Hapus
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

// State
const activeTab = ref('umum')
const isSaving = ref(false)
const isBackingUp = ref(false)
const lastBackup = ref('2024-01-15 10:30:00')

// Settings data
const settings = ref({
  school: {
    name: 'SMA Negeri 1 Jakarta',
    npsn: '12345678',
    address: 'Jl. Merdeka No. 123, Jakarta Pusat',
    phone: '021-12345678',
    email: 'info@sman1jakarta.sch.id'
  },
  system: {
    maintenanceMode: false,
    autoBackup: true,
    timezone: 'Asia/Jakarta'
  },
  academic: {
    activeYear: '2024/2025',
    activeSemester: '1',
    effectiveWeeks: 18
  },
  attendance: {
    startTime: '07:00',
    lateThreshold: '07:15',
    minAttendance: 75,
    autoAbsent: true
  },
  creditPoint: {
    maxPoints: 100,
    warningThreshold: 40,
    dangerThreshold: 20
  },
  notification: {
    whatsapp: {
      enabled: true,
      apiToken: '',
      phoneNumberId: ''
    },
    email: {
      enabled: false,
      smtpHost: 'smtp.gmail.com',
      smtpPort: 587,
      username: '',
      password: ''
    }
  }
})

const backupHistory = ref([
  {
    id: 1,
    date: '2024-01-15 10:30:00',
    size: '12.5 MB',
    status: 'Success'
  },
  {
    id: 2,
    date: '2024-01-14 10:30:00',
    size: '12.3 MB',
    status: 'Success'
  },
  {
    id: 3,
    date: '2024-01-13 10:30:00',
    size: '12.1 MB',
    status: 'Success'
  }
])

// Methods
const saveSettings = async () => {
  isSaving.value = true
  
  // Simulate API call
  setTimeout(() => {
    isSaving.value = false
    alert('Pengaturan berhasil disimpan!')
  }, 2000)
}

const resetSettings = () => {
  if (confirm('Apakah Anda yakin ingin mereset pengaturan?')) {
    // Reset to default values
    console.log('Reset settings')
  }
}

const createBackup = async () => {
  isBackingUp.value = true
  
  // Simulate backup process
  setTimeout(() => {
    isBackingUp.value = false
    lastBackup.value = new Date().toLocaleString('id-ID')
    alert('Backup berhasil dibuat!')
  }, 3000)
}

const downloadBackup = () => {
  console.log('Download latest backup')
}

const downloadBackupFile = (backup) => {
  console.log('Download backup file:', backup)
}

const deleteBackup = (backup) => {
  if (confirm('Apakah Anda yakin ingin menghapus backup ini?')) {
    console.log('Delete backup:', backup)
  }
}

onMounted(() => {
  console.log('PengaturanView mounted')
})
</script>
