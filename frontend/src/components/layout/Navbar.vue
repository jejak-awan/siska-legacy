<template>
  <nav class="bg-background shadow-sm border-b border-border sticky top-0 z-40">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center h-16">
        <!-- Left Section - Mobile Menu & Breadcrumb -->
        <div class="flex items-center space-x-4">
          <!-- Mobile sidebar toggle -->
          <button
            @click="$emit('toggle-sidebar')"
            class="lg:hidden p-2 rounded-md text-muted-foreground hover:text-foreground hover:bg-accent focus:outline-none focus:ring-2 focus:ring-ring"
          >
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>

          <!-- Breadcrumb -->
          <nav class="hidden sm:flex" aria-label="Breadcrumb">
            <ol class="flex items-center space-x-2">
              <li>
                <router-link to="/dashboard" class="text-muted-foreground hover:text-foreground">
                  <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                  </svg>
                </router-link>
              </li>
              <li v-for="(crumb, index) in breadcrumbs" :key="index" class="flex items-center">
                <svg class="h-5 w-5 text-muted-foreground mx-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <router-link
                  v-if="crumb.to && index < breadcrumbs.length - 1"
                  :to="crumb.to"
                  class="text-sm font-medium text-muted-foreground hover:text-foreground"
                >
                  {{ crumb.label }}
                </router-link>
                <span
                  v-else
                  class="text-sm font-medium text-foreground"
                >
                  {{ crumb.label }}
                </span>
              </li>
            </ol>
          </nav>

          <!-- Page Title for Mobile -->
          <h1 class="sm:hidden text-lg font-semibold text-foreground">
            {{ currentPageTitle }}
          </h1>
        </div>

        <!-- Right Section - Search, Notifications, Quick Actions, Profile -->
        <div class="flex items-center space-x-4">
          <!-- Global Search -->
          <div class="relative hidden md:block">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
            </div>
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari siswa, guru, atau data..."
              class="block w-64 pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 text-sm"
              @keydown.enter="handleSearch"
              @focus="showSearchResults = true"
              @blur="hideSearchResults"
            >
            
            <!-- Search Results Dropdown -->
            <div
              v-if="showSearchResults && (searchResults.length > 0 || searchQuery.length > 0)"
              class="absolute top-full left-0 right-0 mt-1 bg-white rounded-md shadow-lg border border-gray-200 z-50 max-h-96 overflow-y-auto"
            >
              <div v-if="searchQuery.length === 0" class="p-4 text-sm text-gray-500 text-center">
                Ketik untuk mencari...
              </div>
              <div v-else-if="searchLoading" class="p-4 text-sm text-gray-500 text-center">
                <div class="animate-spin h-4 w-4 border-2 border-blue-500 border-t-transparent rounded-full mx-auto"></div>
              </div>
              <div v-else-if="searchResults.length === 0" class="p-4 text-sm text-gray-500 text-center">
                Tidak ada hasil ditemukan
              </div>
              <div v-else>
                <div v-for="result in searchResults" :key="result.id" class="border-b border-gray-100 last:border-0">
                  <button
                    @click="goToResult(result)"
                    class="w-full px-4 py-3 text-left hover:bg-gray-50 focus:bg-gray-50 focus:outline-none"
                  >
                    <div class="flex items-center space-x-3">
                      <div class="flex-shrink-0">
                        <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                          <svg v-if="result.type === 'siswa'" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                          </svg>
                          <svg v-else-if="result.type === 'guru'" class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222" />
                          </svg>
                          <svg v-else class="h-4 w-4 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                          </svg>
                        </div>
                      </div>
                      <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 truncate">{{ result.name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ result.subtitle }}</p>
                      </div>
                      <div class="flex-shrink-0">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 capitalize">
                          {{ result.type }}
                        </span>
                      </div>
                    </div>
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="relative">
            <button
              @click="showQuickActions = !showQuickActions"
              class="p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
              title="Aksi Cepat"
            >
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
              </svg>
            </button>

            <!-- Quick Actions Dropdown -->
            <transition
              enter-active-class="transition ease-out duration-200"
              enter-from-class="transform opacity-0 scale-95"
              enter-to-class="transform opacity-100 scale-100"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <div
                v-if="showQuickActions"
                class="origin-top-right absolute right-0 mt-2 w-64 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                @click="showQuickActions = false"
              >
                <div class="py-2">
                  <div class="px-4 py-2 text-xs font-semibold text-gray-500 uppercase tracking-wider border-b border-gray-100">
                    Aksi Cepat
                  </div>
                  
                  <!-- Role-based quick actions -->
                  <template v-if="authStore.hasAnyRole(['admin', 'guru', 'wali_kelas'])">
                    <button
                      @click="quickAction('add-siswa')"
                      class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 flex items-center space-x-2"
                    >
                      <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                      </svg>
                      <span>Tambah Siswa</span>
                    </button>
                  </template>

                  <template v-if="authStore.hasAnyRole(['admin', 'guru', 'wali_kelas'])">
                    <button
                      @click="quickAction('input-presensi')"
                      class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 flex items-center space-x-2"
                    >
                      <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                      </svg>
                      <span>Input Presensi</span>
                    </button>
                  </template>

                  <template v-if="authStore.hasAnyRole(['admin', 'wali_kelas', 'bk'])">
                    <button
                      @click="quickAction('kredit-poin')"
                      class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 flex items-center space-x-2"
                    >
                      <svg class="h-4 w-4 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                      </svg>
                      <span>Input Kredit Poin</span>
                    </button>
                  </template>

                  <button
                    @click="quickAction('laporan')"
                    class="w-full px-4 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 flex items-center space-x-2"
                  >
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <span>Lihat Laporan</span>
                  </button>
                </div>
              </div>
            </transition>
          </div>

          <!-- Notifications -->
          <div class="relative">
            <button
              @click="showNotifications = !showNotifications"
              class="p-2 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 relative"
              title="Notifikasi"
            >
              <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zm-5-5l5 5H9v-5zm0 0V7a3 3 0 116 0v5l-6 5z" />
              </svg>
              
              <!-- Notification Badge -->
              <span
                v-if="unreadNotifications > 0"
                class="absolute -top-1 -right-1 h-5 w-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-medium"
              >
                {{ unreadNotifications > 9 ? '9+' : unreadNotifications }}
              </span>
            </button>

            <!-- Notifications Dropdown -->
            <transition
              enter-active-class="transition ease-out duration-200"
              enter-from-class="transform opacity-0 scale-95"
              enter-to-class="transform opacity-100 scale-100"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <div
                v-if="showNotifications"
                class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 max-h-96 overflow-hidden"
              >
                <div class="py-2">
                  <div class="px-4 py-2 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-semibold text-gray-900">Notifikasi</h3>
                    <button
                      v-if="unreadNotifications > 0"
                      @click="markAllAsRead"
                      class="text-xs text-blue-600 hover:text-blue-700 font-medium"
                    >
                      Tandai semua dibaca
                    </button>
                  </div>
                  
                  <div class="max-h-64 overflow-y-auto">
                    <div v-if="notifications.length === 0" class="px-4 py-8 text-center text-sm text-gray-500">
                      Tidak ada notifikasi
                    </div>
                    <div v-else>
                      <div
                        v-for="notification in notifications"
                        :key="notification.id"
                        class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-0"
                        :class="{ 'bg-blue-50': !notification.read }"
                      >
                        <div class="flex space-x-3">
                          <div class="flex-shrink-0">
                            <div
                              class="h-8 w-8 rounded-full flex items-center justify-center"
                              :class="getNotificationIconClass(notification.type)"
                            >
                              <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path v-if="notification.type === 'presensi'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                <path v-else-if="notification.type === 'kredit_poin'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                              </svg>
                            </div>
                          </div>
                          <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">{{ notification.title }}</p>
                            <p class="text-xs text-gray-500 mt-1">{{ notification.message }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ formatNotificationTime(notification.created_at) }}</p>
                          </div>
                          <div v-if="!notification.read" class="flex-shrink-0">
                            <div class="h-2 w-2 bg-blue-500 rounded-full"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  
                  <div class="px-4 py-2 border-t border-gray-100">
                    <router-link
                      to="/dashboard/notifications"
                      @click="showNotifications = false"
                      class="text-sm text-blue-600 hover:text-blue-700 font-medium"
                    >
                      Lihat semua notifikasi
                    </router-link>
                  </div>
                </div>
              </div>
            </transition>
          </div>

          <!-- User Profile -->
          <div class="relative">
            <button
              @click="showProfileMenu = !showProfileMenu"
              class="flex items-center space-x-2 p-1 rounded-md text-gray-400 hover:text-gray-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <div class="h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center">
                <span class="text-sm font-medium text-blue-600">
                  {{ userInitials }}
                </span>
              </div>
              <div class="hidden md:block text-left">
                <p class="text-sm font-medium text-gray-900">{{ authStore.user?.username }}</p>
                <p class="text-xs text-gray-500 capitalize">{{ formatRole(authStore.userRole) }}</p>
              </div>
              <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </button>

            <!-- Profile Menu -->
            <transition
              enter-active-class="transition ease-out duration-200"
              enter-from-class="transform opacity-0 scale-95"
              enter-to-class="transform opacity-100 scale-100"
              leave-active-class="transition ease-in duration-75"
              leave-from-class="transform opacity-100 scale-100"
              leave-to-class="transform opacity-0 scale-95"
            >
              <div
                v-if="showProfileMenu"
                class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50"
                @click="showProfileMenu = false"
              >
                <div class="py-1">
                  <div class="px-4 py-2 text-sm text-gray-700 border-b border-gray-100">
                    <div class="font-medium">{{ authStore.user?.username }}</div>
                    <div class="text-xs text-gray-500 capitalize">{{ formatRole(authStore.userRole) }}</div>
                  </div>
                  
                  <router-link
                    to="/dashboard/profile"
                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center space-x-2"
                  >
                    <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span>Profil Saya</span>
                  </router-link>
                  
                  <button
                    @click="handleLogout"
                    class="w-full text-left px-4 py-2 text-sm text-red-700 hover:bg-red-50 flex items-center space-x-2"
                  >
                    <svg class="h-4 w-4 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    <span>Keluar</span>
                  </button>
                </div>
              </div>
            </transition>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>

<script setup>
import { ref, computed, watch, onMounted, onUnmounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

// Define emits
defineEmits(['toggle-sidebar'])

const route = useRoute()
const router = useRouter()
const authStore = useAuthStore()

// State
const searchQuery = ref('')
const searchResults = ref([])
const searchLoading = ref(false)
const showSearchResults = ref(false)
const showQuickActions = ref(false)
const showNotifications = ref(false)
const showProfileMenu = ref(false)
const unreadNotifications = ref(3)

// Mock notifications
const notifications = ref([
  {
    id: 1,
    type: 'presensi',
    title: 'Presensi Hari Ini',
    message: 'Ada 5 siswa yang belum hadir di kelas X-A',
    created_at: new Date(Date.now() - 1000 * 60 * 30), // 30 minutes ago
    read: false
  },
  {
    id: 2,
    type: 'kredit_poin',
    title: 'Kredit Poin Siswa',
    message: 'Ahmad Rizki mendapat kredit poin +10 untuk prestasi',
    created_at: new Date(Date.now() - 1000 * 60 * 60 * 2), // 2 hours ago
    read: false
  },
  {
    id: 3,
    type: 'info',
    title: 'Pengumuman',
    message: 'Rapat guru dijadwalkan besok pukul 14:00',
    created_at: new Date(Date.now() - 1000 * 60 * 60 * 6), // 6 hours ago
    read: true
  }
])

// Computed
const userInitials = computed(() => {
  const username = authStore.user?.username || 'U'
  return username.charAt(0).toUpperCase()
})

const breadcrumbs = computed(() => {
  const pathArray = route.path.split('/').filter(path => path)
  const crumbs = []
  
  for (let i = 0; i < pathArray.length; i++) {
    const path = pathArray[i]
    const routePath = '/' + pathArray.slice(0, i + 1).join('/')
    
    // Generate breadcrumb labels
    let label = path.charAt(0).toUpperCase() + path.slice(1)
    if (path === 'siswa') label = 'Data Siswa'
    if (path === 'guru') label = 'Data Guru'
    if (path === 'users') label = 'Pengguna'
    if (path === 'profile') label = 'Profil'
    if (path === 'kredit-poin') label = 'Kredit Poin'
    
    crumbs.push({
      label,
      to: routePath
    })
  }
  
  return crumbs
})

const currentPageTitle = computed(() => {
  if (breadcrumbs.value.length > 0) {
    return breadcrumbs.value[breadcrumbs.value.length - 1].label
  }
  return 'Dashboard'
})

// Methods
const handleSearch = async () => {
  if (searchQuery.value.length < 2) {
    searchResults.value = []
    return
  }
  
  searchLoading.value = true
  
  // Mock search - replace with actual API call
  setTimeout(() => {
    searchResults.value = [
      {
        id: 1,
        type: 'siswa',
        name: 'Ahmad Rizki',
        subtitle: 'X-A • NISN: 1234567890',
        route: '/siswa/1'
      },
      {
        id: 2,
        type: 'guru',
        name: 'Dr. Siti Aminah',
        subtitle: 'Guru Matematika • NIP: 196801011990031001',
        route: '/guru/1'
      },
      {
        id: 3,
        type: 'siswa',
        name: 'Sari Dewi',
        subtitle: 'XI IPA 1 • NISN: 0987654321',
        route: '/siswa/2'
      }
    ].filter(item => 
      item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
    
    searchLoading.value = false
  }, 500)
}

const hideSearchResults = () => {
  setTimeout(() => {
    showSearchResults.value = false
  }, 200)
}

const goToResult = (result) => {
  router.push(result.route)
  searchQuery.value = ''
  showSearchResults.value = false
}

const quickAction = (action) => {
  switch (action) {
    case 'add-siswa':
      router.push('/dashboard/siswa?action=add')
      break
    case 'input-presensi':
      router.push('/dashboard/presensi?action=input')
      break
    case 'kredit-poin':
      router.push('/dashboard/kredit-poin?action=input')
      break
    case 'laporan':
      router.push('/dashboard/laporan')
      break
  }
}

const markAllAsRead = () => {
  notifications.value.forEach(notification => {
    notification.read = true
  })
  unreadNotifications.value = 0
}

const getNotificationIconClass = (type) => {
  switch (type) {
    case 'presensi':
      return 'bg-green-100 text-green-600'
    case 'kredit_poin':
      return 'bg-yellow-100 text-yellow-600'
    default:
      return 'bg-blue-100 text-blue-600'
  }
}

const formatNotificationTime = (date) => {
  const now = new Date()
  const diff = now - new Date(date)
  const minutes = Math.floor(diff / (1000 * 60))
  const hours = Math.floor(diff / (1000 * 60 * 60))
  const days = Math.floor(diff / (1000 * 60 * 60 * 24))
  
  if (minutes < 60) {
    return `${minutes} menit yang lalu`
  } else if (hours < 24) {
    return `${hours} jam yang lalu`
  } else {
    return `${days} hari yang lalu`
  }
}

const formatRole = (role) => {
  if (!role) return ''
  return role.replace('_', ' ')
}

const handleLogout = async () => {
  showProfileMenu.value = false
  await authStore.logout()
  router.push('/login')
}

// Close dropdowns when clicking outside
const closeDropdowns = (event) => {
  if (!event.target.closest('.relative')) {
    showQuickActions.value = false
    showNotifications.value = false
    showProfileMenu.value = false
  }
}

// Watch search query for live search
watch(searchQuery, (newQuery) => {
  if (newQuery.length >= 2) {
    handleSearch()
  } else {
    searchResults.value = []
  }
})

// Lifecycle
onMounted(() => {
  document.addEventListener('click', closeDropdowns)
})

onUnmounted(() => {
  document.removeEventListener('click', closeDropdowns)
})
</script>
