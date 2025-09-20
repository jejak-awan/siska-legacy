import { createRouter, createWebHistory } from 'vue-router'
import PublicLayout from '../components/public/PublicLayout.vue'
import LandingPage from '../views/public/LandingPage.vue'

const publicRoutes = [
  {
    path: '/',
    component: PublicLayout,
    children: [
      {
        path: '',
        name: 'LandingPage',
        component: LandingPage,
        meta: {
          title: 'Beranda - SISKA'
        }
      },
      {
        path: 'activities',
        name: 'PublicActivities',
        component: () => import('../views/public/ActivitiesPage.vue'),
        meta: {
          title: 'Kegiatan - SISKA'
        }
      },
      {
        path: 'news',
        name: 'PublicNews',
        component: () => import('../views/public/NewsPage.vue'),
        meta: {
          title: 'Berita - SISKA'
        }
      },
      {
        path: 'programs',
        name: 'PublicPrograms',
        component: () => import('../views/public/ProgramsPage.vue'),
        meta: {
          title: 'Program - SISKA'
        }
      }
    ]
  }
]

const publicRouter = createRouter({
  history: createWebHistory(),
  routes: publicRoutes
})

// Set page title
publicRouter.beforeEach((to, from, next) => {
  if (to.meta.title) {
    document.title = to.meta.title
  }
  next()
})

export default publicRouter
