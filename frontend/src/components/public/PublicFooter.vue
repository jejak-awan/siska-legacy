<template>
  <footer class="border-t bg-background">
    <div class="container mx-auto px-4 py-12">
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
        <!-- Logo & Description -->
        <div class="space-y-4">
          <div class="flex items-center space-x-2">
            <div class="h-8 w-8 rounded-lg bg-primary flex items-center justify-center">
              <span class="text-primary-foreground font-bold text-sm">S</span>
            </div>
            <span class="font-bold text-xl">SISKA</span>
          </div>
          <p class="text-sm text-muted-foreground">
            Sistem Informasi Sekolah - Kesiswaan untuk mengelola data dan kegiatan siswa dengan efisien.
          </p>
        </div>

        <!-- Quick Links -->
        <div class="space-y-4">
          <h3 class="font-semibold">Tautan Cepat</h3>
          <ul class="space-y-2 text-sm">
            <li>
              <router-link to="/" class="text-muted-foreground hover:text-primary transition-colors">
                Beranda
              </router-link>
            </li>
            <li>
              <router-link to="/activities" class="text-muted-foreground hover:text-primary transition-colors">
                Kegiatan
              </router-link>
            </li>
            <li>
              <router-link to="/news" class="text-muted-foreground hover:text-primary transition-colors">
                Berita
              </router-link>
            </li>
            <li>
              <router-link to="/programs" class="text-muted-foreground hover:text-primary transition-colors">
                Program
              </router-link>
            </li>
          </ul>
        </div>

        <!-- Contact Info -->
        <div class="space-y-4">
          <h3 class="font-semibold">Kontak</h3>
          <ul class="space-y-2 text-sm text-muted-foreground">
            <li>Email: info@sekolah.edu</li>
            <li>Telepon: (021) 123-4567</li>
            <li>Alamat: Jl. Pendidikan No. 123</li>
          </ul>
        </div>

        <!-- Social Media -->
        <div class="space-y-4">
          <h3 class="font-semibold">Media Sosial</h3>
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
      <div class="border-t mt-8 pt-8 flex flex-col md:flex-row justify-between items-center">
        <p class="text-sm text-muted-foreground">
          © 2024 SISKA. Semua hak dilindungi.
        </p>
        <div class="flex items-center space-x-2 mt-4 md:mt-0">
          <span class="text-sm text-muted-foreground">Dibuat dengan</span>
          <HeartIcon class="h-4 w-4 text-red-500" />
          <span class="text-sm text-muted-foreground">oleh Jejakawan | Support By K2NET</span>
        </div>
      </div>
    </div>
  </footer>
</template>

<script setup>
import Button from '../ui/Button.vue'
import { 
  FacebookIcon, 
  TwitterIcon, 
  InstagramIcon, 
  YoutubeIcon, 
  HeartIcon 
} from 'lucide-vue-next'
</script>
