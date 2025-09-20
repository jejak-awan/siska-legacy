import { createRouter, createWebHistory } from 'vue-router'
import { useAuthStore } from '../stores/auth'

// Import critical views (loaded immediately)
import LoginView from '../views/auth/LoginView.vue'
import DashboardView from '../views/dashboard/DashboardView.vue'

// Import dashboard components for different roles (critical for initial load)
import AdminDashboard from '../views/dashboard/AdminDashboard.vue'
import GuruDashboard from '../views/dashboard/GuruDashboard.vue'
import SiswaDashboard from '../views/dashboard/SiswaDashboard.vue'
import WaliKelasDashboard from '../views/dashboard/WaliKelasDashboard.vue'
import BKDashboard from '../views/dashboard/BKDashboard.vue'
import OSISDashboard from '../views/dashboard/OSISDashboard.vue'
import EkstrakurikulerDashboard from '../views/dashboard/EkstrakurikulerDashboard.vue'
import OrangTuaDashboard from '../views/dashboard/OrangTuaDashboard.vue'

// Lazy loading functions with preloading
const lazyLoad = (path) => () => import(/* webpackChunkName: "views" */ `../views/${path}.vue`)

// Preload critical routes after initial load
const preloadCriticalRoutes = () => {
  // Preload commonly used routes after 2 seconds
  setTimeout(() => {
    import('../views/users/UsersView.vue')
    import('../views/siswa/SiswaView.vue')
    import('../views/presensi/PresensiView.vue')
    import('../views/kredit-poin/KreditPoinView.vue')
  }, 2000)
}

// Preload secondary routes after 5 seconds
const preloadSecondaryRoutes = () => {
  setTimeout(() => {
    import('../views/guru/GuruView.vue')
    import('../views/bk/BKView.vue')
    import('../views/osis/OSISView.vue')
    import('../views/ekstrakurikuler/EkstrakurikulerView.vue')
  }, 5000)
}

const routes = [
  {
    path: '/',
    name: 'LandingPage',
    component: () => import('../views/public/LandingPage.vue'),
    meta: { 
      requiresAuth: false,
      title: 'Beranda - SISKA'
    }
  },
  {
    path: '/login',
    name: 'Login',
    component: LoginView,
    meta: { 
      requiresAuth: false,
      title: 'Login - Sistem Kesiswaan'
    }
  },
  {
    path: '/demo/editor',
    name: 'EditorDemo',
    component: () => import('../views/demo/EditorDemo.vue'),
    meta: { 
      requiresAuth: true,
      title: 'Demo Editor - Sistem Kesiswaan'
    }
  },
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: DashboardView,
    meta: { 
      requiresAuth: true,
      title: 'Dashboard - Sistem Kesiswaan'
    },
    children: [
      {
        path: '',
        name: 'DashboardHome',
        component: AdminDashboard
      },
      // All feature pages as children of dashboard
      {
        path: 'users',
        name: 'Users',
        component: lazyLoad('users/UsersView'),
        meta: { 
          requiresAuth: true,
          roles: ['admin'],
          title: 'Manajemen Pengguna - Sistem Kesiswaan'
        }
      },
      {
        path: 'guru',
        name: 'Guru',
        component: () => import('../views/guru/GuruView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin', 'guru', 'wali_kelas', 'bk', 'osis', 'ekstrakurikuler'],
          title: 'Manajemen Guru - Sistem Kesiswaan'
        }
      },
      {
        path: 'siswa',
        name: 'Siswa',
        component: lazyLoad('siswa/SiswaView'),
        meta: { 
          requiresAuth: true,
          roles: ['admin', 'guru', 'wali_kelas', 'bk', 'osis', 'ekstrakurikuler'],
          title: 'Manajemen Siswa - Sistem Kesiswaan'
        }
      },
      {
        path: 'profile',
        name: 'Profile',
        component: () => import('../views/profile/ProfileView.vue'),
        meta: { 
          requiresAuth: true,
          title: 'Profil - Sistem Kesiswaan'
        }
      },
      // Manajemen Profil Sekolah
      {
        path: 'profil-sekolah',
        name: 'ProfilSekolah',
        component: () => import('../views/profil-sekolah/ProfilSekolahView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin'],
          title: 'Profil Sekolah - Sistem Kesiswaan'
        }
      },
      {
        path: 'data-pegawai',
        name: 'DataPegawai',
        component: () => import('../views/data-pegawai/DataPegawaiView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin'],
          title: 'Data Pegawai - Sistem Kesiswaan'
        }
      },
      {
        path: 'data-siswa',
        name: 'DataSiswa',
        component: () => import('../views/siswa/SiswaView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin', 'wali_kelas', 'bk'],
          title: 'Data Siswa - Sistem Kesiswaan'
        }
      },
      {
        path: 'referensi-data',
        name: 'ReferensiData',
        component: () => import('../views/referensi-data/ReferensiDataView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin'],
          title: 'Referensi Data - Sistem Kesiswaan'
        }
      },
      // Manajemen Konten
      {
        path: 'content',
        name: 'ContentManagement',
        component: () => import('../views/content/ContentManagement.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin'],
          title: 'Manajemen Konten - SISKA'
        }
      },
      {
        path: 'gallery',
        name: 'Gallery',
        component: () => import('../views/gallery/GalleryView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin'],
          title: 'Gallery - SISKA'
        }
      },
      {
        path: 'hak-akses',
        name: 'HakAkses',
        component: () => import('../views/hak-akses/HakAksesView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin'],
          title: 'Hak Akses - Sistem Kesiswaan'
        }
      },
      // Akademik & Kurikulum
      {
        path: 'tahun-ajaran',
        name: 'TahunAjaran',
        component: () => import('../views/tahun-ajaran/TahunAjaranView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin'],
          title: 'Tahun Ajaran - Sistem Kesiswaan'
        }
      },
      {
        path: 'mata-pelajaran',
        name: 'MataPelajaran',
        component: () => import('../views/mata-pelajaran/MataPelajaranView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin'],
          title: 'Mata Pelajaran - Sistem Kesiswaan'
        }
      },
      {
        path: 'kelas',
        name: 'Kelas',
        component: () => import('../views/kelas/KelasView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin', 'guru', 'wali_kelas', 'bk', 'osis', 'ekstrakurikuler'],
          title: 'Data Kelas - Sistem Kesiswaan'
        }
      },
      {
        path: 'jadwal',
        name: 'Jadwal',
        component: () => import('../views/jadwal/JadwalView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin', 'guru', 'wali_kelas'],
          title: 'Jadwal Pelajaran - Sistem Kesiswaan'
        }
      },
      // Kesiswaan & Karakter
      {
        path: 'presensi',
        name: 'Presensi',
        component: lazyLoad('presensi/PresensiView'),
        meta: { 
          requiresAuth: true,
          roles: ['admin', 'guru', 'wali_kelas', 'bk'],
          title: 'Manajemen Presensi - Sistem Kesiswaan'
        }
      },
      {
        path: 'kredit-poin',
        name: 'KreditPoin',
        component: lazyLoad('kredit-poin/KreditPoinView'),
        meta: { 
          requiresAuth: true,
          roles: ['admin', 'wali_kelas', 'bk'],
          title: 'Manajemen Kredit Poin - Sistem Kesiswaan'
        }
      },
      {
        path: 'bimbingan-konseling',
        name: 'BimbinganKonseling',
        component: () => import('../views/bk/BKView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin', 'bk', 'wali_kelas'],
          title: 'Bimbingan Konseling - Sistem Kesiswaan'
        }
      },
      {
        path: 'program-kesiswaan',
        name: 'ProgramKesiswaan',
        component: () => import('../views/program-kesiswaan/ProgramKesiswaanView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin'],
          title: 'Program Kesiswaan - Sistem Kesiswaan'
        }
      },
      {
        path: 'osis',
        name: 'OSIS',
        component: () => import('../views/osis/OSISView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin', 'osis', 'guru'],
          title: 'Manajemen OSIS - Sistem Kesiswaan'
        }
      },
      {
        path: 'ekstrakurikuler',
        name: 'Ekstrakurikuler',
        component: () => import('../views/ekstrakurikuler/EkstrakurikulerView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin', 'ekstrakurikuler', 'guru'],
          title: 'Manajemen Ekstrakurikuler - Sistem Kesiswaan'
        }
      },
      {
        path: 'laporan',
        name: 'Laporan',
        component: () => import('../views/laporan/LaporanView.vue'),
        meta: { 
          requiresAuth: true,
          title: 'Laporan - Sistem Kesiswaan'
        }
      },
      {
        path: 'analytics',
        name: 'Analytics',
        component: () => import('../views/analytics/AnalyticsView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin'],
          title: 'Dashboard Analytics - Sistem Kesiswaan'
        }
      },
      {
        path: 'pengaturan',
        name: 'Pengaturan',
        component: () => import('../views/pengaturan/PengaturanView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin'],
          title: 'Pengaturan Sistem - Sistem Kesiswaan'
        }
      },
      {
        path: 'konfigurasi-karakter',
        name: 'KonfigurasiKarakter',
        component: () => import('../views/konfigurasi-karakter/KonfigurasiKarakterView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin'],
          title: 'Konfigurasi Karakter - Sistem Kesiswaan'
        }
      },
      {
        path: 'backup-restore',
        name: 'BackupRestore',
        component: () => import('../views/backup-restore/BackupRestoreView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin'],
          title: 'Backup & Restore - Sistem Kesiswaan'
        }
      },
      {
        path: 'notifications',
        name: 'Notifications',
        component: () => import('../views/notifications/NotificationView.vue'),
        meta: { 
          requiresAuth: true,
          title: 'Notifikasi - Sistem Kesiswaan'
        }
      },
      {
        path: 'home-visit',
        name: 'HomeVisit',
        component: () => import('../views/bk/HomeVisitView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin', 'bk'],
          title: 'Home Visit - Sistem Kesiswaan'
        }
      },
      {
        path: 'osis-anggota',
        name: 'OSISAnggota',
        component: () => import('../views/osis/OSISAnggotaView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin', 'osis'],
          title: 'Anggota OSIS - Sistem Kesiswaan'
        }
      },
      {
        path: 'osis-prestasi',
        name: 'OSISPrestasi',
        component: () => import('../views/osis/OSISPrestasiView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin', 'osis'],
          title: 'Prestasi OSIS - Sistem Kesiswaan'
        }
      },
      {
        path: 'ekstrakurikuler-prestasi',
        name: 'EkstrakurikulerPrestasi',
        component: () => import('../views/ekstrakurikuler/EkstrakurikulerPrestasiView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin', 'ekstrakurikuler'],
          title: 'Prestasi Ekstrakurikuler - Sistem Kesiswaan'
        }
      },
      {
        path: 'ekstrakurikuler-jadwal',
        name: 'EkstrakurikulerJadwal',
        component: () => import('../views/ekstrakurikuler/EkstrakurikulerJadwalView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin', 'ekstrakurikuler'],
          title: 'Jadwal Ekstrakurikuler - Sistem Kesiswaan'
        }
      },
      {
        path: 'laporan-presensi',
        name: 'LaporanPresensi',
        component: () => import('../views/laporan/LaporanPresensiView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin', 'guru', 'wali_kelas'],
          title: 'Laporan Presensi - Sistem Kesiswaan'
        }
      },
      {
        path: 'laporan-konseling',
        name: 'LaporanKonseling',
        component: () => import('../views/laporan/LaporanKonselingView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin', 'bk'],
          title: 'Laporan Konseling - Sistem Kesiswaan'
        }
      },
      {
        path: 'laporan-osis',
        name: 'LaporanOSIS',
        component: () => import('../views/laporan/LaporanOSISView.vue'),
        meta: { 
          requiresAuth: true,
          roles: ['admin', 'osis'],
          title: 'Laporan OSIS - Sistem Kesiswaan'
        }
      }
    ]
  },
  {
    path: '/:pathMatch(.*)*',
    name: 'NotFound',
    component: () => import('../views/error/NotFoundView.vue'),
    meta: { 
      title: '404 - Halaman Tidak Ditemukan'
    }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    }
    return { top: 0 }
  }
})

// Navigation guards
router.beforeEach(async (to, from, next) => {
  const authStore = useAuthStore()
  
  console.log('🛣️ Router guard - navigating to:', to.path)
  console.log('🛣️ Auth status:', authStore.isAuthenticated)
  console.log('🛣️ User role:', authStore.userRole)
  console.log('🛣️ Token exists:', !!authStore.token)
  
  // Set page title
  document.title = to.meta.title || 'Sistem Kesiswaan'
  
  // Handle authentication
  if (to.meta.requiresAuth) {
    console.log('🛣️ Route requires auth')
    if (!authStore.isAuthenticated) {
      console.log('🛣️ Not authenticated, redirecting to login')
      // Not authenticated, redirect to login
      return next({
        path: '/login',
        query: { redirect: to.fullPath }
      })
    }
    
    // Check role-based access
    if (to.meta.roles && to.meta.roles.length > 0) {
      if (!authStore.hasAnyRole(to.meta.roles)) {
        // Access denied
        console.warn(`🛣️ Access denied to ${to.path} for role ${authStore.userRole}`)
        return next('/dashboard')
      }
    }
  } else {
    console.log('🛣️ Route does not require auth')
    // Route doesn't require auth
    if (to.path === '/login' && authStore.isAuthenticated) {
      console.log('🛣️ Already authenticated, redirecting to dashboard')
      // Already authenticated, redirect to dashboard
      return next('/dashboard')
    }
    // Remove automatic redirect from landing page to dashboard
    // Let users stay on landing page even if authenticated
  }
  
  console.log('🛣️ Navigation allowed')
  next()
})

// Initialize preloading after router is created
router.isReady().then(() => {
  preloadCriticalRoutes()
  preloadSecondaryRoutes()
})

export default router
