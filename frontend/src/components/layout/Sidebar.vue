<template>
  <div>
    <!-- Sidebar -->
    <div 
      class="fixed inset-y-0 left-0 z-50 bg-background shadow-lg transform transition-all duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0 flex flex-col h-full"
      :class="[
        sidebarOpen ? 'translate-x-0' : '-translate-x-full',
        sidebarCollapsed ? 'w-16' : 'w-64'
      ]"
    >
      <!-- Sidebar Header -->
      <div class="flex items-center justify-between h-16 px-4 border-b border-border">
        <div class="flex items-center space-x-2" :class="{ 'justify-center': sidebarCollapsed }">
          <div class="h-8 w-8 bg-blue-600 rounded-lg flex items-center justify-center">
            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
          </div>
          <div v-show="!sidebarCollapsed" class="hidden sm:block">
            <h1 class="text-lg font-semibold text-foreground">Kesiswaan</h1>
          </div>
        </div>
        
        <!-- Toggle Collapse Button -->
        <div class="flex items-center space-x-1">
          <button 
            @click="toggleCollapse"
            class="hidden lg:block p-1.5 rounded-md text-muted-foreground hover:text-foreground hover:bg-accent transition-colors"
            :title="sidebarCollapsed ? 'Expand sidebar' : 'Collapse sidebar'"
          >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path v-if="!sidebarCollapsed" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7" />
              <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 5l7 7-7 7" />
            </svg>
          </button>
          
          <!-- Close button for mobile -->
          <button 
            @click="toggleSidebar"
            class="lg:hidden p-1.5 rounded-md text-muted-foreground hover:text-foreground hover:bg-accent"
          >
            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
      </div>

      <!-- Sidebar Navigation -->
      <nav class="flex-1 flex flex-col px-2 py-4 min-h-0">
        <!-- Scrollable Content -->
        <div class="flex-1 overflow-y-auto space-y-1">

        <!-- Dashboard -->
        <SidebarItem
          :to="'/dashboard'"
          icon="home"
          label="Dashboard"
          :active="$route.path === '/dashboard'"
          :collapsed="sidebarCollapsed"
        />

        <!-- Admin Sections -->
        <template v-if="authStore.hasRole('admin')">
          <!-- Manajemen Data -->
          <SidebarSection 
            title="Manajemen Data" 
            :collapsed="sidebarCollapsed"
            :defaultOpen="true"
          >
            <SidebarItem
              :to="'/dashboard/data-sekolah'"
              icon="building"
              label="Data Sekolah"
              :active="$route.path === '/dashboard/data-sekolah'"
              :collapsed="sidebarCollapsed"
            />
            <SidebarItem
              :to="'/dashboard/data-pegawai'"
              icon="academic-cap"
              label="Data Pegawai"
              :active="$route.path === '/dashboard/data-pegawai'"
              :collapsed="sidebarCollapsed"
            />
            <SidebarItem
              :to="'/dashboard/data-siswa'"
              icon="user-group"
              label="Data Siswa"
              :active="$route.path === '/dashboard/data-siswa'"
              :collapsed="sidebarCollapsed"
            />
            <SidebarItem
              :to="'/dashboard/referensi-data'"
              icon="document"
              label="Referensi Data"
              :active="$route.path === '/dashboard/referensi-data'"
              :collapsed="sidebarCollapsed"
            />
          </SidebarSection>

          <!-- Manajemen Konten -->
          <SidebarSection 
            title="Manajemen Konten" 
            :collapsed="sidebarCollapsed"
            :defaultOpen="true"
          >
            <SidebarItem
              :to="'/dashboard/content'"
              icon="document-text"
              label="Konten"
              :active="$route.path === '/dashboard/content'"
              :collapsed="sidebarCollapsed"
            />
            <SidebarItem
              :to="'/dashboard/gallery'"
              icon="photograph"
              label="Gallery"
              :active="$route.path === '/dashboard/gallery'"
              :collapsed="sidebarCollapsed"
            />
          </SidebarSection>

          <!-- Manajemen Pengguna -->
          <SidebarSection 
            title="Manajemen Pengguna" 
            :collapsed="sidebarCollapsed"
          >
            <SidebarItem
              :to="'/dashboard/users'"
              icon="users"
              label="Kelola Pengguna"
              :active="$route.path === '/dashboard/users'"
              :collapsed="sidebarCollapsed"
            />
            <SidebarItem
              :to="'/dashboard/hak-akses'"
              icon="cog"
              label="Hak Akses"
              :active="$route.path === '/dashboard/hak-akses'"
              :collapsed="sidebarCollapsed"
            />
          </SidebarSection>

          <!-- Akademik & Kurikulum Merdeka -->
          <SidebarSection 
            title="Akademik & Kurikulum" 
            :collapsed="sidebarCollapsed"
          >
            <SidebarItem
              :to="'/dashboard/tahun-ajaran'"
              icon="calendar"
              label="Tahun Ajaran"
              :active="$route.path === '/dashboard/tahun-ajaran'"
              :collapsed="sidebarCollapsed"
            />
            <SidebarItem
              :to="'/dashboard/mata-pelajaran'"
              icon="document-text"
              label="Mata Pelajaran"
              :active="$route.path === '/dashboard/mata-pelajaran'"
              :collapsed="sidebarCollapsed"
            />
            <SidebarItem
              :to="'/dashboard/kelas'"
              icon="building-office"
              label="Data Kelas"
              :active="$route.path === '/dashboard/kelas'"
              :collapsed="sidebarCollapsed"
            />
            <SidebarItem
              :to="'/dashboard/jadwal'"
              icon="calendar"
              label="Jadwal Pelajaran"
              :active="$route.path === '/dashboard/jadwal'"
              :collapsed="sidebarCollapsed"
            />
          </SidebarSection>

          <!-- Kesiswaan & Karakter -->
          <SidebarSection 
            title="Kesiswaan & Karakter" 
            :collapsed="sidebarCollapsed"
          >
            <SidebarItem
              :to="'/dashboard/presensi'"
              icon="check-circle"
              label="Presensi & Kehadiran"
              :active="$route.path === '/dashboard/presensi'"
              :collapsed="sidebarCollapsed"
            />
            <SidebarItem
              :to="'/dashboard/kredit-poin'"
              icon="star"
              label="Sistem Poin & Karakter"
              :active="$route.path === '/dashboard/kredit-poin'"
              :collapsed="sidebarCollapsed"
            />
            <SidebarItem
              :to="'/dashboard/bimbingan-konseling'"
              icon="heart"
              label="Bimbingan & Konseling"
              :active="$route.path === '/dashboard/bimbingan-konseling'"
              :collapsed="sidebarCollapsed"
            />
            <SidebarItem
              :to="'/dashboard/program-kesiswaan'"
              icon="clipboard-document-list"
              label="Program Kesiswaan"
              :active="$route.path === '/dashboard/program-kesiswaan'"
              :collapsed="sidebarCollapsed"
            />
            <SidebarItem
              :to="'/dashboard/konfigurasi-karakter'"
              icon="star"
              label="Konfigurasi Karakter"
              :active="$route.path === '/dashboard/konfigurasi-karakter'"
              :collapsed="sidebarCollapsed"
            />
          </SidebarSection>

          <!-- Kegiatan Siswa -->
          <SidebarSection 
            title="Kegiatan Siswa" 
            :collapsed="sidebarCollapsed"
          >
            <SidebarItem
              :to="'/dashboard/osis'"
              icon="calendar-days"
              label="OSIS"
              :active="$route.path === '/dashboard/osis'"
              :collapsed="sidebarCollapsed"
            />
            <SidebarItem
              :to="'/dashboard/ekstrakurikuler'"
              icon="users"
              label="Ekstrakurikuler"
              :active="$route.path === '/dashboard/ekstrakurikuler'"
              :collapsed="sidebarCollapsed"
            />
          </SidebarSection>

          <!-- Laporan & Analisis -->
          <SidebarSection 
            title="Laporan & Analisis" 
            :collapsed="sidebarCollapsed"
          >
            <SidebarItem
              :to="'/dashboard/laporan'"
              icon="chart-bar"
              label="Laporan Sistem"
              :active="$route.path === '/dashboard/laporan'"
              :collapsed="sidebarCollapsed"
            />
            <SidebarItem
              :to="'/dashboard/analytics'"
              icon="chart-bar"
              label="Dashboard Analytics"
              :active="$route.path === '/dashboard/analytics'"
              :collapsed="sidebarCollapsed"
            />
          </SidebarSection>

          <!-- Pengaturan Sistem -->
          <SidebarSection 
            title="Pengaturan Sistem" 
            :collapsed="sidebarCollapsed"
          >
            <SidebarItem
              :to="'/dashboard/pengaturan'"
              icon="cog"
              label="Konfigurasi Aplikasi"
              :active="$route.path === '/dashboard/pengaturan'"
              :collapsed="sidebarCollapsed"
            />
            <SidebarItem
              :to="'/dashboard/notifications'"
              icon="bell"
              label="Notifikasi & Alert"
              :active="$route.path === '/dashboard/notifications'"
              :collapsed="sidebarCollapsed"
              :badge="notificationCount"
            />
            <SidebarItem
              :to="'/dashboard/backup-restore'"
              icon="document-arrow-down"
              label="Backup & Restore"
              :active="$route.path === '/dashboard/backup-restore'"
              :collapsed="sidebarCollapsed"
            />
          </SidebarSection>
        </template>

        <!-- Non-Admin Sections -->
        <template v-if="!authStore.hasRole('admin')">
          <!-- Akademik Section untuk Guru & Wali Kelas -->
          <template v-if="authStore.hasAnyRole(['guru', 'wali_kelas'])">
            <SidebarSection 
              title="Akademik" 
              :collapsed="sidebarCollapsed"
            >
              <SidebarItem
                :to="'/dashboard/presensi'"
                icon="check-circle"
                label="Presensi"
                :active="$route.path === '/dashboard/presensi'"
              :collapsed="sidebarCollapsed"
            />
            <SidebarItem
              :to="'/dashboard/jadwal'"
              icon="calendar"
              label="Jadwal Pelajaran"
              :active="$route.path === '/dashboard/jadwal'"
              :collapsed="sidebarCollapsed"
            />
          </SidebarSection>
        </template>

          <!-- Kesiswaan Section untuk Non-Admin -->
        <SidebarSection 
          title="Kesiswaan" 
          :collapsed="sidebarCollapsed"
        >
          <SidebarItem
            :to="'/dashboard/kredit-poin'"
            icon="star"
            label="Kredit Poin"
            :active="$route.path === '/dashboard/kredit-poin'"
            :collapsed="sidebarCollapsed"
          />
          <SidebarItem
              v-if="authStore.hasAnyRole(['bk', 'wali_kelas'])"
            :to="'/dashboard/bimbingan-konseling'"
            icon="heart"
              label="Bimbingan & Konseling"
            :active="$route.path === '/dashboard/bimbingan-konseling'"
            :collapsed="sidebarCollapsed"
          />
          <SidebarItem
              v-if="authStore.hasRole('bk')"
            :to="'/dashboard/home-visit'"
            icon="home"
            label="Home Visit"
            :active="$route.path === '/dashboard/home-visit'"
            :collapsed="sidebarCollapsed"
          />
        </SidebarSection>

          <!-- OSIS Section untuk Non-Admin -->
          <template v-if="authStore.hasRole('osis')">
          <SidebarSection 
            title="OSIS" 
            :collapsed="sidebarCollapsed"
          >
            <SidebarItem
              :to="'/dashboard/osis'"
              icon="calendar-days"
              label="Kegiatan OSIS"
              :active="$route.path === '/dashboard/osis'"
              :collapsed="sidebarCollapsed"
            />
            <SidebarItem
              :to="'/dashboard/osis-anggota'"
              icon="users"
              label="Anggota OSIS"
              :active="$route.path === '/dashboard/osis-anggota'"
              :collapsed="sidebarCollapsed"
            />
            <SidebarItem
              :to="'/dashboard/osis-prestasi'"
              icon="trophy"
              label="Prestasi OSIS"
              :active="$route.path === '/dashboard/osis-prestasi'"
              :collapsed="sidebarCollapsed"
            />
          </SidebarSection>
        </template>

          <!-- Ekstrakurikuler Section untuk Non-Admin -->
          <template v-if="authStore.hasRole('ekstrakurikuler')">
          <SidebarSection 
            title="Ekstrakurikuler" 
            :collapsed="sidebarCollapsed"
          >
            <SidebarItem
              :to="'/dashboard/ekstrakurikuler'"
              icon="users"
                label="Anggota Ekstrakurikuler"
              :active="$route.path === '/dashboard/ekstrakurikuler'"
              :collapsed="sidebarCollapsed"
            />
            <SidebarItem
              :to="'/dashboard/ekstrakurikuler-prestasi'"
              icon="trophy"
                label="Prestasi Ekstrakurikuler"
              :active="$route.path === '/dashboard/ekstrakurikuler-prestasi'"
              :collapsed="sidebarCollapsed"
            />
            <SidebarItem
              :to="'/dashboard/ekstrakurikuler-jadwal'"
              icon="calendar"
                label="Jadwal Ekstrakurikuler"
              :active="$route.path === '/dashboard/ekstrakurikuler-jadwal'"
              :collapsed="sidebarCollapsed"
            />
          </SidebarSection>
        </template>

          <!-- Laporan Section untuk Non-Admin -->
        <SidebarSection 
          title="Laporan" 
          :collapsed="sidebarCollapsed"
        >
          <SidebarItem
            :to="'/dashboard/laporan'"
            icon="chart-bar"
            label="Laporan Umum"
            :active="$route.path === '/dashboard/laporan'"
            :collapsed="sidebarCollapsed"
          />
          <SidebarItem
              v-if="authStore.hasAnyRole(['guru', 'wali_kelas'])"
            :to="'/dashboard/laporan-presensi'"
            icon="check-circle"
            label="Laporan Presensi"
            :active="$route.path === '/dashboard/laporan-presensi'"
            :collapsed="sidebarCollapsed"
          />
          <SidebarItem
              v-if="authStore.hasRole('bk')"
            :to="'/dashboard/laporan-konseling'"
            icon="heart"
            label="Laporan Konseling"
            :active="$route.path === '/dashboard/laporan-konseling'"
            :collapsed="sidebarCollapsed"
          />
          <SidebarItem
              v-if="authStore.hasRole('osis')"
            :to="'/dashboard/laporan-osis'"
            icon="calendar-days"
            label="Laporan OSIS"
            :active="$route.path === '/dashboard/laporan-osis'"
            :collapsed="sidebarCollapsed"
          />
        </SidebarSection>
        </template>
        </div>

        <!-- App Info - Fixed at Bottom -->
        <div class="pt-4 border-t border-gray-200 flex-shrink-0">
          <div v-if="!sidebarCollapsed" class="px-3 py-4 text-center">
            <div class="text-lg font-bold text-gray-900 mb-1">SISKA</div>
            <div class="text-xs text-gray-600 mb-2">Sistem Informasi Sekolah - Kesiswaan</div>
            <div class="flex items-center justify-center text-xs text-gray-500">
              <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
              Jejakawan | Support By K2NET
            </div>
          </div>
          <div v-else class="px-3 py-4 text-center">
            <div class="text-lg font-bold text-gray-900 mb-2">S</div>
            <div class="text-xs text-gray-500">
              <svg class="h-3 w-3 mx-auto mb-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            </div>
          </div>
        </div>
      </nav>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div 
      v-if="sidebarOpen" 
      class="fixed inset-0 z-40 bg-gray-600 bg-opacity-75 lg:hidden" 
      @click="toggleSidebar"
    ></div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../../stores/auth'
import SidebarItem from './SidebarItem.vue'
import SidebarSection from './SidebarSection.vue'

const router = useRouter()
const authStore = useAuthStore()

const sidebarOpen = ref(true)
const sidebarCollapsed = ref(false)

// Notification count (placeholder - should be connected to notification store)
const notificationCount = computed(() => {
  // This should be connected to a notification store in real implementation
  return 0
})

const toggleSidebar = () => {
  sidebarOpen.value = !sidebarOpen.value
}

const toggleCollapse = () => {
  sidebarCollapsed.value = !sidebarCollapsed.value
  // Save preference to localStorage
  localStorage.setItem('sidebar-collapsed', sidebarCollapsed.value.toString())
}


onMounted(() => {
  // Restore sidebar collapse state from localStorage
  const savedState = localStorage.getItem('sidebar-collapsed')
  if (savedState !== null) {
    sidebarCollapsed.value = savedState === 'true'
  }
  
  // Auto-hide sidebar on mobile
  const handleResize = () => {
    if (window.innerWidth < 1024) {
      sidebarOpen.value = false
    } else {
      sidebarOpen.value = true
    }
  }
  
  window.addEventListener('resize', handleResize)
  handleResize() // Initial check
  
  return () => {
    window.removeEventListener('resize', handleResize)
  }
})
</script>