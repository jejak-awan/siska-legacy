import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

export const usePublicStore = defineStore('public', () => {
  // State
  const posts = ref([])
  const activities = ref([])
  const programs = ref([])
  const images = ref([])
  const loading = ref(false)
  const error = ref(null)

  // Getters
  const featuredPosts = computed(() => 
    posts.value.filter(post => post.is_featured).slice(0, 3)
  )

  const featuredActivities = computed(() => 
    activities.value.filter(activity => activity.is_featured).slice(0, 3)
  )

  const featuredPrograms = computed(() => 
    programs.value.filter(program => program.is_featured).slice(0, 3)
  )

  const stats = computed(() => ({
    posts: {
      total: posts.value.length,
      published: posts.value.filter(p => p.status === 'published').length,
      draft: posts.value.filter(p => p.status === 'draft').length
    },
    activities: {
      total: activities.value.length,
      upcoming: activities.value.filter(a => new Date(a.date) > new Date()).length,
      completed: activities.value.filter(a => new Date(a.date) < new Date()).length
    },
    programs: {
      total: programs.value.length,
      active: programs.value.filter(p => p.status === 'active').length,
      completed: programs.value.filter(p => p.status === 'completed').length
    }
  }))

  // Actions
  const fetchFeaturedContent = async () => {
    try {
      loading.value = true
      error.value = null

      // TODO: Replace with actual API calls
      await new Promise(resolve => setTimeout(resolve, 1000)) // Mock delay

      // Mock data
      posts.value = [
        {
          id: 1,
          title: 'Pembukaan Tahun Ajaran Baru 2024/2025',
          excerpt: 'Selamat datang di tahun ajaran baru dengan berbagai program unggulan...',
          content: '<p>Konten lengkap...</p>',
          category: 'pengumuman',
          status: 'published',
          featured_image: null,
          is_featured: true,
          author: { username: 'admin' },
          created_at: '2024-01-15T10:00:00Z',
          updated_at: '2024-01-15T10:00:00Z',
          published_at: '2024-01-15T10:00:00Z'
        }
      ]

      activities.value = [
        {
          id: 1,
          title: 'Kegiatan OSIS Bulan Januari',
          description: 'Berbagai kegiatan menarik dari OSIS untuk bulan ini...',
          activity_date: '2024-01-20T09:00:00Z',
          location: 'Aula Sekolah',
          category: 'osis',
          is_featured: true,
          created_at: '2024-01-14T14:30:00Z'
        }
      ]

      programs.value = [
        {
          id: 1,
          name: 'Program Beasiswa Prestasi',
          description: 'Program beasiswa untuk siswa berprestasi di berbagai bidang...',
          status: 'active',
          category: 'beasiswa',
          completion_percentage: 75,
          is_featured: true,
          created_at: '2024-01-13T09:15:00Z'
        }
      ]

    } catch (err) {
      error.value = 'Gagal memuat konten publik'
      console.error('Fetch featured content error:', err)
    } finally {
      loading.value = false
    }
  }

  const fetchStats = async () => {
    try {
      loading.value = true
      error.value = null

      // TODO: Replace with actual API calls
      await new Promise(resolve => setTimeout(resolve, 500)) // Mock delay

      // Mock stats - this will be computed from the data
      console.log('Stats fetched successfully')

    } catch (err) {
      error.value = 'Gagal memuat statistik'
      console.error('Fetch stats error:', err)
    } finally {
      loading.value = false
    }
  }

  const fetchAllPublicContent = async () => {
    try {
      loading.value = true
      error.value = null

      // TODO: Replace with actual API calls
      await new Promise(resolve => setTimeout(resolve, 1000)) // Mock delay

      // Mock data
      posts.value = [
        {
          id: 1,
          title: 'Pembukaan Tahun Ajaran Baru 2024/2025',
          excerpt: 'Selamat datang di tahun ajaran baru dengan berbagai program unggulan...',
          content: '<p>Konten lengkap...</p>',
          category: 'pengumuman',
          status: 'published',
          featured_image: null,
          is_featured: true,
          author: { username: 'admin' },
          created_at: '2024-01-15T10:00:00Z',
          updated_at: '2024-01-15T10:00:00Z'
        }
      ]

      activities.value = [
        {
          id: 1,
          title: 'Kegiatan OSIS Bulan Januari',
          description: 'Berbagai kegiatan menarik dari OSIS untuk bulan ini...',
          date: '2024-01-20T09:00:00Z',
          location: 'Aula Sekolah',
          is_featured: true,
          created_at: '2024-01-14T14:30:00Z'
        }
      ]

      programs.value = [
        {
          id: 1,
          name: 'Program Beasiswa Prestasi',
          description: 'Program beasiswa untuk siswa berprestasi di berbagai bidang...',
          status: 'active',
          is_featured: true,
          created_at: '2024-01-13T09:15:00Z'
        }
      ]

      images.value = [
        {
          id: 1,
          title: 'Upacara Bendera',
          description: 'Upacara bendera setiap hari Senin',
          url: 'https://via.placeholder.com/400x300/3B82F6/FFFFFF?text=Upacara+Bendera',
          category: 'kegiatan',
          file_size: 1024000,
          created_at: '2024-01-15T10:00:00Z'
        }
      ]

    } catch (err) {
      error.value = 'Gagal memuat konten publik'
      console.error('Fetch public content error:', err)
    } finally {
      loading.value = false
    }
  }

  const createPost = async (postData) => {
    try {
      loading.value = true
      
      // TODO: Replace with actual API call
      await new Promise(resolve => setTimeout(resolve, 1000))
      
      const newPost = {
        id: Date.now(),
        ...postData,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      }
      
      posts.value.unshift(newPost)
      return newPost
    } catch (err) {
      error.value = 'Gagal membuat post'
      throw err
    } finally {
      loading.value = false
    }
  }

  const updatePost = async (id, postData) => {
    try {
      loading.value = true
      
      // TODO: Replace with actual API call
      await new Promise(resolve => setTimeout(resolve, 1000))
      
      const index = posts.value.findIndex(post => post.id === id)
      if (index > -1) {
        posts.value[index] = {
          ...posts.value[index],
          ...postData,
          updated_at: new Date().toISOString()
        }
        return posts.value[index]
      }
      throw new Error('Post tidak ditemukan')
    } catch (err) {
      error.value = 'Gagal memperbarui post'
      throw err
    } finally {
      loading.value = false
    }
  }

  const deletePost = async (id) => {
    try {
      loading.value = true
      
      // TODO: Replace with actual API call
      await new Promise(resolve => setTimeout(resolve, 1000))
      
      const index = posts.value.findIndex(post => post.id === id)
      if (index > -1) {
        posts.value.splice(index, 1)
        return true
      }
      throw new Error('Post tidak ditemukan')
    } catch (err) {
      error.value = 'Gagal menghapus post'
      throw err
    } finally {
      loading.value = false
    }
  }

  const uploadImage = async (imageData) => {
    try {
      loading.value = true
      
      // TODO: Replace with actual API call
      await new Promise(resolve => setTimeout(resolve, 2000))
      
      const newImage = {
        id: Date.now(),
        ...imageData,
        created_at: new Date().toISOString(),
        updated_at: new Date().toISOString()
      }
      
      images.value.unshift(newImage)
      return newImage
    } catch (err) {
      error.value = 'Gagal mengupload gambar'
      throw err
    } finally {
      loading.value = false
    }
  }

  const updateImage = async (id, imageData) => {
    try {
      loading.value = true
      
      // TODO: Replace with actual API call
      await new Promise(resolve => setTimeout(resolve, 1000))
      
      const index = images.value.findIndex(image => image.id === id)
      if (index > -1) {
        images.value[index] = {
          ...images.value[index],
          ...imageData,
          updated_at: new Date().toISOString()
        }
        return images.value[index]
      }
      throw new Error('Gambar tidak ditemukan')
    } catch (err) {
      error.value = 'Gagal memperbarui gambar'
      throw err
    } finally {
      loading.value = false
    }
  }

  const deleteImage = async (id) => {
    try {
      loading.value = true
      
      // TODO: Replace with actual API call
      await new Promise(resolve => setTimeout(resolve, 1000))
      
      const index = images.value.findIndex(image => image.id === id)
      if (index > -1) {
        images.value.splice(index, 1)
        return true
      }
      throw new Error('Gambar tidak ditemukan')
    } catch (err) {
      error.value = 'Gagal menghapus gambar'
      throw err
    } finally {
      loading.value = false
    }
  }

  return {
    // State
    posts,
    activities,
    programs,
    images,
    loading,
    error,
    
    // Getters
    featuredPosts,
    featuredActivities,
    featuredPrograms,
    stats,
    
    // Actions
    fetchFeaturedContent,
    fetchStats,
    fetchAllPublicContent,
    createPost,
    updatePost,
    deletePost,
    uploadImage,
    updateImage,
    deleteImage
  }
})