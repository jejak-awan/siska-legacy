<template>
  <div class="min-h-screen bg-background">
    <!-- Header -->
    <header class="sticky top-0 z-50 w-full border-b border-gray-200 dark:border-gray-700 bg-white/95 dark:bg-gray-900/95 backdrop-blur supports-[backdrop-filter]:bg-white/60 dark:supports-[backdrop-filter]:bg-gray-900/60">
      <div class="container mx-auto px-4">
        <div class="flex h-16 items-center justify-between">
          <!-- Logo -->
          <div class="flex items-center space-x-2">
            <router-link to="/" class="flex items-center space-x-2">
              <div class="h-8 w-8 rounded-lg bg-gray-900 dark:bg-white flex items-center justify-center">
                <span class="text-white dark:text-gray-900 font-bold text-sm">S</span>
              </div>
              <span class="font-bold text-xl text-gray-900 dark:text-white">SISKA</span>
            </router-link>
          </div>

          <!-- Navigation -->
          <nav class="hidden md:flex items-center space-x-6">
            <router-link 
              to="/" 
              class="text-sm font-medium transition-colors hover:text-gray-600 dark:hover:text-gray-300 text-gray-900 dark:text-white"
            >
              Beranda
            </router-link>
            <a href="#activities" class="text-sm font-medium transition-colors hover:text-gray-600 dark:hover:text-gray-300 text-gray-600 dark:text-gray-300">
              Kegiatan
            </a>
            <a href="#news" class="text-sm font-medium transition-colors hover:text-gray-600 dark:hover:text-gray-300 text-gray-600 dark:text-gray-300">
              Berita
            </a>
            <a href="#programs" class="text-sm font-medium transition-colors hover:text-gray-600 dark:hover:text-gray-300 text-gray-600 dark:text-gray-300">
              Program
            </a>
          </nav>

          <!-- Actions -->
          <div class="flex items-center space-x-4">
            <!-- Theme Toggle -->
            <ThemeToggle />
            
            <!-- Login Button -->
            <Button variant="outline" size="sm" @click="goToLogin" class="border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800">
              Masuk
            </Button>
          </div>
        </div>
      </div>
    </header>

    <!-- Hero Section -->
    <section class="relative py-20 bg-gradient-to-br from-gray-900/50 to-gray-800/50 dark:from-gray-900/80 dark:to-gray-800/80">
      <div class="container mx-auto px-4">
        <div class="text-center space-y-6">
          <h1 class="text-4xl md:text-6xl font-bold tracking-tight text-foreground">
            Selamat Datang di
            <span class="text-white dark:text-gray-100">SISKA</span>
          </h1>
          <p class="text-xl text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
            Sistem Informasi Sekolah - Kesiswaan untuk mengelola data dan kegiatan siswa dengan efisien dan terintegrasi.
          </p>
          <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <Button size="lg" @click="goToActivities">
              Lihat Kegiatan
            </Button>
            <Button variant="outline" size="lg" @click="goToLogin" class="border-gray-300 dark:border-gray-600 text-gray-900 dark:text-white hover:bg-gray-100 dark:hover:bg-gray-800">
              Masuk ke Dashboard
            </Button>
          </div>
        </div>
      </div>
    </section>

    <!-- Featured Content -->
    <section class="py-16">
      <div class="container mx-auto px-4">
        <div class="text-center space-y-4 mb-12">
          <h2 class="text-3xl font-bold text-foreground">Konten Unggulan</h2>
          <p class="text-gray-600 dark:text-gray-300 max-w-2xl mx-auto">
            Temukan kegiatan, berita, dan program terbaru dari sekolah kami.
          </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
          <!-- Featured Posts -->
          <div class="space-y-4">
            <h3 class="text-xl font-semibold text-foreground">Berita Terbaru</h3>
            <div class="space-y-4">
              <Card v-for="post in featuredPosts" :key="post.id" class="cursor-pointer hover:shadow-md transition-shadow">
                <CardHeader>
                  <CardTitle class="text-lg">{{ post.title }}</CardTitle>
                </CardHeader>
                <CardContent>
                  <p class="text-sm text-muted-foreground">{{ post.excerpt }}</p>
                  <div class="flex items-center justify-between mt-4">
                    <span class="text-xs text-muted-foreground">
                      {{ formatDate(post.published_at) }}
                    </span>
                    <span class="text-xs bg-primary/10 text-primary px-2 py-1 rounded">
                      {{ post.category }}
                    </span>
                  </div>
                </CardContent>
              </Card>
            </div>
          </div>

          <!-- Featured Activities -->
          <div class="space-y-4">
            <h3 class="text-xl font-semibold text-foreground">Kegiatan Terbaru</h3>
            <div class="space-y-4">
              <Card v-for="activity in featuredActivities" :key="activity.id" class="cursor-pointer hover:shadow-md transition-shadow">
                <CardHeader>
                  <CardTitle class="text-lg">{{ activity.title }}</CardTitle>
                </CardHeader>
                <CardContent>
                  <p class="text-sm text-muted-foreground">{{ activity.description }}</p>
                  <div class="flex items-center justify-between mt-4">
                    <span class="text-xs text-muted-foreground">
                      {{ formatDate(activity.activity_date) }}
                    </span>
                    <span class="text-xs bg-secondary text-secondary-foreground px-2 py-1 rounded">
                      {{ activity.category }}
                    </span>
                  </div>
                </CardContent>
              </Card>
            </div>
          </div>

          <!-- Featured Programs -->
          <div class="space-y-4">
            <h3 class="text-xl font-semibold text-foreground">Program Unggulan</h3>
            <div class="space-y-4">
              <Card v-for="program in featuredPrograms" :key="program.id" class="cursor-pointer hover:shadow-md transition-shadow">
                <CardHeader>
                  <CardTitle class="text-lg">{{ program.name }}</CardTitle>
                </CardHeader>
                <CardContent>
                  <p class="text-sm text-muted-foreground">{{ program.description }}</p>
                  <div class="flex items-center justify-between mt-4">
                    <div class="flex items-center space-x-2">
                      <div class="w-16 bg-secondary rounded-full h-2">
                        <div 
                          class="bg-primary h-2 rounded-full" 
                          :style="{ width: `${program.completion_percentage}%` }"
                        ></div>
                      </div>
                      <span class="text-xs text-muted-foreground">
                        {{ program.completion_percentage }}%
                      </span>
                    </div>
                    <span class="text-xs bg-accent text-accent-foreground px-2 py-1 rounded">
                      {{ program.category }}
                    </span>
                  </div>
                </CardContent>
              </Card>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-gray-50 dark:bg-gray-900/50">
      <div class="container mx-auto px-4">
        <div class="text-center space-y-4 mb-12">
          <h2 class="text-3xl font-bold text-foreground">Statistik Sekolah</h2>
          <p class="text-gray-600 dark:text-gray-300">
            Data dan pencapaian sekolah kami.
          </p>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
          <div class="text-center space-y-2">
            <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.activities?.total || 0 }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-300">Kegiatan</div>
          </div>
          <div class="text-center space-y-2">
            <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.posts?.total || 0 }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-300">Berita</div>
          </div>
          <div class="text-center space-y-2">
            <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.programs?.total || 0 }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-300">Program</div>
          </div>
          <div class="text-center space-y-2">
            <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ stats.programs?.completed || 0 }}</div>
            <div class="text-sm text-gray-600 dark:text-gray-300">Program Selesai</div>
          </div>
        </div>
      </div>
    </section>

    <!-- Footer -->
    <footer class="border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-900">
      <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <!-- Logo & Description -->
          <div class="space-y-4">
            <div class="flex items-center space-x-2">
              <div class="h-8 w-8 rounded-lg bg-gray-900 dark:bg-white flex items-center justify-center">
                <span class="text-white dark:text-gray-900 font-bold text-sm">S</span>
              </div>
              <span class="font-bold text-xl text-gray-900 dark:text-white">SISKA</span>
            </div>
            <p class="text-sm text-gray-600 dark:text-gray-300">
              Sistem Informasi Sekolah - Kesiswaan untuk mengelola data dan kegiatan siswa dengan efisien.
            </p>
          </div>

          <!-- Quick Links -->
          <div class="space-y-4">
            <h3 class="font-semibold text-foreground">Tautan Cepat</h3>
            <ul class="space-y-2 text-sm">
              <li>
                <a href="#activities" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                  Kegiatan
                </a>
              </li>
              <li>
                <a href="#news" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                  Berita
                </a>
              </li>
              <li>
                <a href="#programs" class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white transition-colors">
                  Program
                </a>
              </li>
            </ul>
          </div>

          <!-- Contact Info -->
          <div class="space-y-4">
            <h3 class="font-semibold text-foreground">Kontak</h3>
            <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-300">
              <li>Email: info@sekolah.edu</li>
              <li>Telepon: (021) 123-4567</li>
              <li>Alamat: Jl. Pendidikan No. 123</li>
            </ul>
          </div>

          <!-- Social Media -->
          <div class="space-y-4">
            <h3 class="font-semibold text-foreground">Media Sosial</h3>
            <div class="flex space-x-4">
              <Button variant="ghost" size="icon">
                <FacebookIcon class="h-4 w-4" />
              </Button>
              <Button variant="ghost" size="icon">
                <TwitterIcon class="h-4 w-4" />
              </Button>
              <Button variant="ghost" size="icon">
                <InstagramIcon class="h-4 w-4" />
              </Button>
              <Button variant="ghost" size="icon">
                <YoutubeIcon class="h-4 w-4" />
              </Button>
            </div>
          </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-200 dark:border-gray-700 mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
          <p class="text-sm text-gray-600 dark:text-gray-300">
            © 2024 SISKA. Semua hak dilindungi.
          </p>
          <div class="flex items-center space-x-2 mt-4 md:mt-0">
            <span class="text-sm text-gray-600 dark:text-gray-300">Dibuat dengan</span>
            <HeartIcon class="h-4 w-4 text-red-500" />
            <span class="text-sm text-gray-600 dark:text-gray-300">oleh Jejakawan | Support By K2NET</span>
          </div>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'
import { usePublicStore } from '../../stores/public'
import Button from '../../components/ui/Button.vue'
import Card from '../../components/ui/Card.vue'
import CardHeader from '../../components/ui/CardHeader.vue'
import CardContent from '../../components/ui/CardContent.vue'
import CardTitle from '../../components/ui/CardTitle.vue'
import ThemeToggle from '../../components/ui/ThemeToggle.vue'
import { 
  FacebookIcon, 
  TwitterIcon, 
  InstagramIcon, 
  YoutubeIcon, 
  HeartIcon 
} from 'lucide-vue-next'

const router = useRouter()
const publicStore = usePublicStore()

const featuredPosts = computed(() => publicStore.featuredPosts)
const featuredActivities = computed(() => publicStore.featuredActivities)
const featuredPrograms = computed(() => publicStore.featuredPrograms)
const stats = computed(() => publicStore.stats)

onMounted(async () => {
  try {
    await Promise.all([
      publicStore.fetchFeaturedContent(),
      publicStore.fetchStats()
    ])
  } catch (error) {
    console.error('Error loading featured content:', error)
  }
})

const goToActivities = () => {
  router.push('/activities')
}

const goToLogin = () => {
  router.push('/login')
}

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}
</script>
