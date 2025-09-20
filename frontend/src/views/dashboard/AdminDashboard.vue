<template>
  <div>
    <!-- School Header -->
    <SchoolHeader />
    
    <div class="py-6">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-8">
          <div class="flex items-center justify-between">
            <div>
              <h1 class="text-3xl font-bold text-foreground">Dashboard Admin</h1>
              <p class="mt-2 text-lg text-muted-foreground">
                Selamat datang, <span class="font-semibold text-primary">{{ authStore.user?.username }}</span>
              </p>
              <p class="text-sm text-muted-foreground">Kelola sistem kesiswaan dan penilaian karakter dinamis</p>
            </div>
          <div class="flex items-center space-x-4">
            <!-- Real-time Connection Status -->
            <div class="flex items-center space-x-2">
              <div class="flex items-center space-x-1">
                <div 
                  class="w-2 h-2 rounded-full"
                  :class="isConnected ? 'bg-green-500' : 'bg-red-500'"
                ></div>
                <span class="text-xs text-muted-foreground">
                  {{ isConnected ? 'Live' : 'Offline' }}
                </span>
              </div>
            </div>
            <div class="text-right">
              <p class="text-sm text-muted-foreground">Terakhir Update</p>
              <p class="text-sm font-medium text-foreground">{{ formatTime(new Date()) }}</p>
            </div>
            <button
              @click="refreshStats"
              class="p-2 bg-primary text-primary-foreground rounded-lg hover:bg-primary/90 transition-colors"
              :disabled="statsLoading"
              :class="{ 'opacity-50 cursor-not-allowed': statsLoading }"
            >
              <svg class="h-5 w-5" :class="{ 'animate-spin': statsLoading }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
            </button>
          </div>
        </div>
      </div>

      <!-- System Status Overview -->
      <div class="mb-8">
        <div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-xl p-6 text-white">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-xl font-semibold">Status Sistem</h2>
              <p class="text-green-100 mt-1">Semua layanan berjalan normal</p>
            </div>
            <div class="flex items-center space-x-6">
              <div class="text-center">
                <div class="text-2xl font-bold">{{ stats.totalUsers || 0 }}</div>
                <div class="text-sm text-green-100">Total Pengguna</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold">{{ stats.totalSiswa || 0 }}</div>
                <div class="text-sm text-green-100">Total Siswa</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold">{{ stats.presensiHariIni || 0 }}</div>
                <div class="text-sm text-green-100">Presensi Hari Ini</div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Main Dashboard Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Left Column - Statistics -->
        <div class="lg:col-span-2 space-y-8">
          
          <!-- User Management Section -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-lg font-semibold text-gray-900">Manajemen Pengguna</h3>
                    <p class="text-sm text-gray-500">Data pengguna sistem</p>
                  </div>
                </div>
                <router-link to="/dashboard/users" class="text-blue-600 hover:text-blue-700 text-sm font-medium">
                  Kelola →
                </router-link>
              </div>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                  <div class="text-2xl font-bold text-gray-900">{{ stats.totalUsers || 0 }}</div>
                  <div class="text-sm text-gray-500">Total Pengguna</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-green-600">{{ stats.totalGuru || 0 }}</div>
                  <div class="text-sm text-gray-500">Guru</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-purple-600">{{ stats.totalSiswa || 0 }}</div>
                  <div class="text-sm text-gray-500">Siswa</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-emerald-600">{{ stats.siswaAktif || 0 }}</div>
                  <div class="text-sm text-gray-500">Siswa Aktif</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Academic Section -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-lg font-semibold text-gray-900">Akademik & Presensi</h3>
                    <p class="text-sm text-gray-500">Data akademik dan kehadiran</p>
                  </div>
                </div>
                <router-link to="/dashboard/presensi" class="text-green-600 hover:text-green-700 text-sm font-medium">
                  Kelola →
                </router-link>
              </div>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                  <div class="text-2xl font-bold text-blue-600">{{ stats.totalPresensi || 0 }}</div>
                  <div class="text-sm text-gray-500">Total Presensi</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-green-600">{{ stats.presensiHariIni || 0 }}</div>
                  <div class="text-sm text-gray-500">Hari Ini</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-yellow-600">{{ stats.totalKreditPoin || 0 }}</div>
                  <div class="text-sm text-gray-500">Kredit Poin</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-orange-600">{{ stats.kreditPoinPending || 0 }}</div>
                  <div class="text-sm text-gray-500">Pending</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Character Assessment Section -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-lg font-semibold text-gray-900">Penilaian Karakter Dinamis</h3>
                    <p class="text-sm text-gray-500">Sistem penilaian karakter universal</p>
                  </div>
                </div>
                <router-link to="/dashboard/konfigurasi-karakter" class="text-purple-600 hover:text-purple-700 text-sm font-medium">
                  Konfigurasi →
                </router-link>
              </div>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                  <div class="text-2xl font-bold text-purple-600">{{ stats.totalAssessments || 0 }}</div>
                  <div class="text-sm text-gray-500">Total Penilaian</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-green-600">{{ stats.avgCharacterScore || 0 }}</div>
                  <div class="text-sm text-gray-500">Rata-rata Karakter</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-blue-600">{{ stats.topPerformers || 0 }}</div>
                  <div class="text-sm text-gray-500">Siswa Berprestasi</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-yellow-600">{{ stats.pendingAssessments || 0 }}</div>
                  <div class="text-sm text-gray-500">Pending</div>
                </div>
              </div>
              
              <!-- Character Dimensions Overview -->
              <div class="mt-6">
                <h4 class="text-sm font-medium text-gray-900 mb-3">Performa Dimensi Karakter</h4>
                <div class="space-y-3">
                  <div
                    v-for="dimension in characterDimensions"
                    :key="dimension.id"
                    class="flex items-center justify-between"
                  >
                    <span class="text-sm text-gray-700">{{ dimension.nama }}</span>
                    <div class="flex items-center space-x-2">
                      <div class="w-20 bg-gray-200 rounded-full h-2">
                        <div
                          :class="getDimensionColor(dimension.average)"
                          class="h-2 rounded-full"
                          :style="{ width: (dimension.average / 4) * 100 + '%' }"
                        ></div>
                      </div>
                      <span class="text-sm font-medium text-gray-900 w-8">{{ dimension.average }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Kesiswaan Section -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-lg font-semibold text-gray-900">Bimbingan Konseling</h3>
                    <p class="text-sm text-gray-500">Konseling dan home visit</p>
                  </div>
                </div>
                <router-link to="/dashboard/bimbingan-konseling" class="text-purple-600 hover:text-purple-700 text-sm font-medium">
                  Kelola →
                </router-link>
              </div>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                  <div class="text-2xl font-bold text-pink-600">{{ stats.totalKonseling || 0 }}</div>
                  <div class="text-sm text-gray-500">Total Konseling</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-purple-600">{{ stats.konselingAktif || 0 }}</div>
                  <div class="text-sm text-gray-500">Konseling Aktif</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-indigo-600">{{ stats.totalHomeVisit || 0 }}</div>
                  <div class="text-sm text-gray-500">Home Visit</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-blue-600">{{ stats.homeVisitAktif || 0 }}</div>
                  <div class="text-sm text-gray-500">Visit Aktif</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Activities Section -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
              <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                  <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                  </div>
                  <div>
                    <h3 class="text-lg font-semibold text-gray-900">Kegiatan & Organisasi</h3>
                    <p class="text-sm text-gray-500">OSIS dan ekstrakurikuler</p>
                  </div>
                </div>
                <router-link to="/dashboard/osis" class="text-red-600 hover:text-red-700 text-sm font-medium">
                  Kelola →
                </router-link>
              </div>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="text-center">
                  <div class="text-2xl font-bold text-red-600">{{ stats.totalOSISKegiatan || 0 }}</div>
                  <div class="text-sm text-gray-500">Kegiatan OSIS</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-green-600">{{ stats.osisKegiatanAktif || 0 }}</div>
                  <div class="text-sm text-gray-500">OSIS Aktif</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-yellow-600">{{ stats.totalEkstrakurikuler || 0 }}</div>
                  <div class="text-sm text-gray-500">Ekstrakurikuler</div>
                </div>
                <div class="text-center">
                  <div class="text-2xl font-bold text-purple-600">{{ stats.ekstrakurikulerAktif || 0 }}</div>
                  <div class="text-sm text-gray-500">Ekskul Aktif</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Charts Section -->
          <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <ChartCard
              title="Presensi 7 Hari Terakhir"
              subtitle="Data kehadiran harian"
              :data="presensiChartData"
              :trend="5.2"
              color-scheme="green"
            />
            <ChartCard
              title="Kredit Poin per Kategori"
              subtitle="Distribusi kredit poin"
              :data="kreditPoinChartData"
              :trend="-2.1"
              color-scheme="yellow"
              :show-legend="true"
            />
          </div>
        </div>

        <!-- Right Column - Activities & Quick Actions -->
        <div class="space-y-6">
          
          <!-- Recent Activities -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
              <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
            </div>
            <div class="p-6">
              <div v-if="activitiesLoading" class="space-y-4">
                <div v-for="i in 5" :key="i" class="animate-pulse">
                  <div class="flex space-x-3">
                    <div class="h-10 w-10 bg-gray-200 rounded-full"></div>
                    <div class="flex-1 space-y-2">
                      <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                      <div class="h-3 bg-gray-200 rounded w-1/2"></div>
                    </div>
                  </div>
                </div>
              </div>
              <div v-else-if="activities.length === 0" class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
                <p class="mt-2 text-sm text-gray-500">Belum ada aktivitas</p>
              </div>
              <div v-else class="space-y-4">
                <div v-for="activity in activities" :key="activity.id" class="flex space-x-3">
                  <div class="flex-shrink-0">
                    <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                      <svg class="h-5 w-5 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                      </svg>
                    </div>
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">{{ activity.title }}</p>
                    <p class="text-sm text-gray-500">{{ activity.description }}</p>
                    <p class="text-xs text-gray-400 mt-1">{{ formatDate(activity.created_at) }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
              <h3 class="text-lg font-semibold text-gray-900">Aksi Cepat</h3>
            </div>
            <div class="p-6">
              <div class="grid grid-cols-2 gap-3">
                <router-link
                  to="/dashboard/users"
                  class="flex items-center justify-center p-3 bg-blue-50 hover:bg-blue-100 rounded-lg transition-colors group"
                >
                  <svg class="h-5 w-5 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                  </svg>
                  <span class="text-sm font-medium text-blue-700 group-hover:text-blue-800">Users</span>
                </router-link>
                
                <router-link
                  to="/dashboard/presensi"
                  class="flex items-center justify-center p-3 bg-green-50 hover:bg-green-100 rounded-lg transition-colors group"
                >
                  <svg class="h-5 w-5 text-green-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                  </svg>
                  <span class="text-sm font-medium text-green-700 group-hover:text-green-800">Presensi</span>
                </router-link>
                
                <router-link
                  to="/dashboard/kredit-poin"
                  class="flex items-center justify-center p-3 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition-colors group"
                >
                  <svg class="h-5 w-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                  <span class="text-sm font-medium text-yellow-700 group-hover:text-yellow-800">Kredit</span>
                </router-link>
                
                <router-link
                  to="/dashboard/bimbingan-konseling"
                  class="flex items-center justify-center p-3 bg-purple-50 hover:bg-purple-100 rounded-lg transition-colors group"
                >
                  <svg class="h-5 w-5 text-purple-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                  </svg>
                  <span class="text-sm font-medium text-purple-700 group-hover:text-purple-800">BK</span>
                </router-link>
                
                <router-link
                  to="/dashboard/osis"
                  class="flex items-center justify-center p-3 bg-red-50 hover:bg-red-100 rounded-lg transition-colors group"
                >
                  <svg class="h-5 w-5 text-red-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                  <span class="text-sm font-medium text-red-700 group-hover:text-red-800">OSIS</span>
                </router-link>
                
                <router-link
                  to="/dashboard/laporan"
                  class="flex items-center justify-center p-3 bg-indigo-50 hover:bg-indigo-100 rounded-lg transition-colors group"
                >
                  <svg class="h-5 w-5 text-indigo-600 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                  </svg>
                  <span class="text-sm font-medium text-indigo-700 group-hover:text-indigo-800">Laporan</span>
                </router-link>
              </div>
            </div>
          </div>

          <!-- System Status -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="p-6 border-b border-gray-200">
              <h3 class="text-lg font-semibold text-gray-900">Status Sistem</h3>
            </div>
            <div class="p-6">
              <div class="space-y-4">
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-3">
                    <div class="h-2 w-2 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">API Status</span>
                  </div>
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Online
                  </span>
                </div>
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-3">
                    <div class="h-2 w-2 bg-green-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">Database</span>
                  </div>
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    Connected
                  </span>
                </div>
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-3">
                    <div class="h-2 w-2 bg-blue-500 rounded-full"></div>
                    <span class="text-sm text-gray-600">Notifikasi</span>
                  </div>
                  <span class="text-sm font-medium text-gray-900">{{ stats.notifikasiUnread || 0 }} unread</span>
                </div>
                <div class="pt-2 border-t border-gray-200">
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Last Update</span>
                    <span class="text-sm text-gray-500">{{ formatTime(new Date()) }}</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useAuthStore } from '../../stores/auth'
import { useRealtime } from '../../composables/useRealtime'
import StatCard from '../../components/ui/StatCard.vue'
import ChartCard from '../../components/ui/ChartCard.vue'
import SchoolHeader from '../../components/layout/SchoolHeader.vue'
import api from '../../services/api'

const authStore = useAuthStore()

// Real-time functionality
const { isConnected, connectionError } = useRealtime()

// State
const stats = ref({
  totalUsers: 0,
  totalGuru: 0,
  totalSiswa: 0,
  siswaAktif: 0,
  totalPresensi: 0,
  presensiHariIni: 0,
  totalKreditPoin: 0,
  kreditPoinPending: 0,
  totalKonseling: 0,
  konselingAktif: 0,
  totalHomeVisit: 0,
  homeVisitAktif: 0,
  totalOSISKegiatan: 0,
  osisKegiatanAktif: 0,
  totalEkstrakurikuler: 0,
  ekstrakurikulerAktif: 0,
  totalNotifikasi: 0,
  notifikasiUnread: 0,
  // Character Assessment Stats
  totalAssessments: 0,
  avgCharacterScore: 0,
  topPerformers: 0,
  pendingAssessments: 0
})

const activities = ref([])
const statsLoading = ref(false)
const activitiesLoading = ref(false)

// Character dimensions data
const characterDimensions = ref([
  { id: 1, nama: 'Spiritual & Religius', average: 3.8 },
  { id: 2, nama: 'Sosial & Kebangsaan', average: 3.6 },
  { id: 3, nama: 'Gotong Royong', average: 3.9 },
  { id: 4, nama: 'Mandiri', average: 3.4 },
  { id: 5, nama: 'Bernalar Kritis', average: 3.7 },
  { id: 6, nama: 'Kreatif', average: 3.5 }
])

// Chart data
const presensiChartData = ref([
  { label: 'Sen', value: 85 },
  { label: 'Sel', value: 92 },
  { label: 'Rab', value: 78 },
  { label: 'Kam', value: 88 },
  { label: 'Jum', value: 95 },
  { label: 'Sab', value: 82 },
  { label: 'Min', value: 0 }
])

const kreditPoinChartData = ref([
  { label: 'Prestasi', value: 45 },
  { label: 'Pelanggaran', value: 12 },
  { label: 'Kegiatan', value: 28 },
  { label: 'Lainnya', value: 15 }
])

// Methods
const fetchStats = async () => {
  try {
    statsLoading.value = true
    
    // Fetch real statistics from all modules
    const [
      usersResponse, 
      guruResponse, 
      siswaResponse,
      presensiResponse,
      kreditPoinResponse,
      bkResponse,
      osisResponse,
      ekstrakurikulerResponse,
      notificationsResponse,
      characterResponse
    ] = await Promise.all([
      api.get('/users-statistics'),
      api.get('/guru-statistics'),
      api.get('/siswa/statistics/overview'),
      api.get('/presensi-statistics'),
      api.get('/kredit-poin-statistics'),
      api.get('/bk-statistics'),
      api.get('/osis-statistics'),
      api.get('/ekstrakurikuler-statistics'),
      api.get('/notifications/stats'),
      api.get('/character-assessment/statistics')
    ])
    
    stats.value = {
      // User stats
      totalUsers: usersResponse.data.data.totalUsers,
      totalGuru: guruResponse.data.data.totalGuru,
      totalSiswa: siswaResponse.data.data.total,
      siswaAktif: siswaResponse.data.data.active,
      
      // Presensi stats
      totalPresensi: presensiResponse.data.data.totalPresensi,
      presensiHariIni: presensiResponse.data.data.presensiHariIni,
      
      // Kredit Poin stats
      totalKreditPoin: kreditPoinResponse.data.data.totalKreditPoin,
      kreditPoinPending: kreditPoinResponse.data.data.pending,
      
      // BK stats
      totalKonseling: bkResponse.data.data.total_konseling,
      konselingAktif: bkResponse.data.data.konseling_urgent,
      totalHomeVisit: bkResponse.data.data.total_home_visit,
      homeVisitAktif: bkResponse.data.data.home_visit_urgent,
      
      // OSIS stats
      totalOSISKegiatan: osisResponse.data.data.total_kegiatan,
      osisKegiatanAktif: osisResponse.data.data.kegiatan_aktif,
      
      // Ekstrakurikuler stats
      totalEkstrakurikuler: ekstrakurikulerResponse.data.data.total_ekstrakurikuler,
      ekstrakurikulerAktif: ekstrakurikulerResponse.data.data.ekstrakurikuler_aktif,
      
      // Notification stats
      totalNotifikasi: notificationsResponse.data.data.total,
      notifikasiUnread: notificationsResponse.data.data.unread
    }
    
  } catch (error) {
    console.error('Failed to fetch stats:', error)
    // Fallback to mock data if API fails
    stats.value = {
      totalUsers: 0,
      totalGuru: 0,
      totalSiswa: 0,
      siswaAktif: 0,
      totalPresensi: 0,
      presensiHariIni: 0,
      totalKreditPoin: 0,
      kreditPoinPending: 0,
      totalKonseling: 0,
      konselingAktif: 0,
      totalHomeVisit: 0,
      homeVisitAktif: 0,
      totalOSISKegiatan: 0,
      osisKegiatanAktif: 0,
      totalEkstrakurikuler: 0,
      ekstrakurikulerAktif: 0,
      totalNotifikasi: 0,
      notifikasiUnread: 0
    }
  } finally {
    statsLoading.value = false
  }
}

const fetchActivities = async () => {
  try {
    activitiesLoading.value = true
    
    // Mock data for now
    activities.value = [
      {
        id: 1,
        title: 'User baru terdaftar',
        description: 'Guru baru dengan username guru002 telah ditambahkan',
        created_at: new Date(Date.now() - 1000 * 60 * 30) // 30 minutes ago
      },
      {
        id: 2,
        title: 'Data siswa diperbarui',
        description: '25 data siswa telah diperbarui untuk tahun ajaran 2024/2025',
        created_at: new Date(Date.now() - 1000 * 60 * 60 * 2) // 2 hours ago
      },
      {
        id: 3,
        title: 'Backup database',
        description: 'Backup database berhasil dilakukan',
        created_at: new Date(Date.now() - 1000 * 60 * 60 * 6) // 6 hours ago
      }
    ]
    
  } catch (error) {
    console.error('Failed to fetch activities:', error)
  } finally {
    activitiesLoading.value = false
  }
}

const refreshStats = async () => {
  await fetchStats()
  await fetchActivities()
}

const getDimensionColor = (score) => {
  if (score >= 3.5) return 'bg-green-500'
  if (score >= 3.0) return 'bg-yellow-500'
  if (score >= 2.5) return 'bg-orange-500'
  return 'bg-red-500'
}

const formatDate = (date) => {
  return new Intl.DateTimeFormat('id-ID', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  }).format(new Date(date))
}

const formatTime = (date) => {
  return new Intl.DateTimeFormat('id-ID', {
    hour: '2-digit',
    minute: '2-digit'
  }).format(new Date(date))
}

// Real-time event handlers
const handleKreditPoinCreated = (event) => {
  console.log('📊 Admin Dashboard: Kredit Poin Created', event.detail)
  // Refresh stats when new kredit poin is created
  fetchStats()
  // Show notification
  showNotification('Kredit Poin Baru', 'Kredit poin baru telah ditambahkan dan menunggu persetujuan')
}

const handlePresensiCreated = (event) => {
  console.log('📅 Admin Dashboard: Presensi Created', event.detail)
  // Refresh stats when new presensi is created
  fetchStats()
  // Show notification
  showNotification('Presensi Baru', 'Data presensi baru telah ditambahkan')
}

const handleDashboardStatsUpdated = (event) => {
  console.log('📈 Admin Dashboard: Stats Updated', event.detail)
  // Update stats with real-time data
  if (event.detail.stats) {
    stats.value = { ...stats.value, ...event.detail.stats }
  }
}

const showNotification = (title, message) => {
  // Simple browser notification
  if ('Notification' in window && Notification.permission === 'granted') {
    new Notification(title, {
      body: message,
      icon: '/favicon.ico'
    })
  }
}

// Lifecycle
onMounted(() => {
  fetchStats()
  fetchActivities()
  
  // Request notification permission
  if ('Notification' in window && Notification.permission === 'default') {
    Notification.requestPermission()
  }
  
  // Add real-time event listeners
  window.addEventListener('admin-kredit-poin-created', handleKreditPoinCreated)
  window.addEventListener('admin-presensi-created', handlePresensiCreated)
  window.addEventListener('admin-dashboard-stats-updated', handleDashboardStatsUpdated)
})

onUnmounted(() => {
  // Remove event listeners
  window.removeEventListener('admin-kredit-poin-created', handleKreditPoinCreated)
  window.removeEventListener('admin-presensi-created', handlePresensiCreated)
  window.removeEventListener('admin-dashboard-stats-updated', handleDashboardStatsUpdated)
})
</script>
