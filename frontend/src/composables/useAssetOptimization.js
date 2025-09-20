import { ref, onMounted, onUnmounted } from 'vue'

/**
 * Composable for asset optimization and preloading
 */
export function useAssetOptimization() {
  const preloadedAssets = ref(new Set())
  const loadingAssets = ref(new Set())

  /**
   * Preload image
   */
  const preloadImage = (src) => {
    if (preloadedAssets.value.has(src) || loadingAssets.value.has(src)) {
      return Promise.resolve()
    }

    loadingAssets.value.add(src)

    return new Promise((resolve, reject) => {
      const img = new Image()
      img.onload = () => {
        preloadedAssets.value.add(src)
        loadingAssets.value.delete(src)
        resolve()
      }
      img.onerror = () => {
        loadingAssets.value.delete(src)
        reject(new Error(`Failed to preload image: ${src}`))
      }
      img.src = src
    })
  }

  /**
   * Preload multiple images
   */
  const preloadImages = async (sources) => {
    const promises = sources.map(src => preloadImage(src).catch(err => {
      console.warn('Failed to preload image:', err)
    }))
    await Promise.all(promises)
  }

  /**
   * Preload CSS
   */
  const preloadCSS = (href) => {
    if (preloadedAssets.value.has(href)) {
      return Promise.resolve()
    }

    return new Promise((resolve, reject) => {
      const link = document.createElement('link')
      link.rel = 'preload'
      link.as = 'style'
      link.href = href
      link.onload = () => {
        preloadedAssets.value.add(href)
        resolve()
      }
      link.onerror = () => {
        reject(new Error(`Failed to preload CSS: ${href}`))
      }
      document.head.appendChild(link)
    })
  }

  /**
   * Preload JavaScript
   */
  const preloadJS = (src) => {
    if (preloadedAssets.value.has(src)) {
      return Promise.resolve()
    }

    return new Promise((resolve, reject) => {
      const link = document.createElement('link')
      link.rel = 'preload'
      link.as = 'script'
      link.href = src
      link.onload = () => {
        preloadedAssets.value.add(src)
        resolve()
      }
      link.onerror = () => {
        reject(new Error(`Failed to preload JS: ${src}`))
      }
      document.head.appendChild(link)
    })
  }

  /**
   * Preload font
   */
  const preloadFont = (href, type = 'font/woff2') => {
    if (preloadedAssets.value.has(href)) {
      return Promise.resolve()
    }

    return new Promise((resolve, reject) => {
      const link = document.createElement('link')
      link.rel = 'preload'
      link.as = 'font'
      link.type = type
      link.href = href
      link.crossOrigin = 'anonymous'
      link.onload = () => {
        preloadedAssets.value.add(href)
        resolve()
      }
      link.onerror = () => {
        reject(new Error(`Failed to preload font: ${href}`))
      }
      document.head.appendChild(link)
    })
  }

  /**
   * Lazy load image with intersection observer
   */
  const useLazyImage = (src, options = {}) => {
    const {
      placeholder = 'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cmVjdCB3aWR0aD0iMTAwJSIgaGVpZ2h0PSIxMDAlIiBmaWxsPSIjZGRkIi8+PC9zdmc+',
      threshold = 0.1,
      rootMargin = '50px'
    } = options

    const imageRef = ref(null)
    const imageSrc = ref(placeholder)
    const isLoaded = ref(false)
    const isLoading = ref(false)
    const error = ref(null)

    const observer = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting && !isLoaded.value && !isLoading.value) {
          loadImage()
        }
      })
    }, { threshold, rootMargin })

    const loadImage = () => {
      isLoading.value = true
      error.value = null

      const img = new Image()
      img.onload = () => {
        imageSrc.value = src
        isLoaded.value = true
        isLoading.value = false
        observer.disconnect()
      }
      img.onerror = (err) => {
        error.value = err
        isLoading.value = false
      }
      img.src = src
    }

    onMounted(() => {
      if (imageRef.value) {
        observer.observe(imageRef.value)
      }
    })

    onUnmounted(() => {
      observer.disconnect()
    })

    return {
      imageRef,
      imageSrc,
      isLoaded,
      isLoading,
      error
    }
  }

  /**
   * Optimize image with compression
   */
  const optimizeImage = async (file, options = {}) => {
    const {
      maxWidth = 1920,
      maxHeight = 1080,
      quality = 0.8,
      format = 'image/jpeg'
    } = options

    return new Promise((resolve) => {
      const canvas = document.createElement('canvas')
      const ctx = canvas.getContext('2d')
      const img = new Image()

      img.onload = () => {
        // Calculate new dimensions
        let { width, height } = img
        if (width > maxWidth || height > maxHeight) {
          const ratio = Math.min(maxWidth / width, maxHeight / height)
          width *= ratio
          height *= ratio
        }

        canvas.width = width
        canvas.height = height

        // Draw and compress
        ctx.drawImage(img, 0, 0, width, height)
        canvas.toBlob(resolve, format, quality)
      }

      img.src = URL.createObjectURL(file)
    })
  }

  /**
   * Get optimized image URL with WebP support
   */
  const getOptimizedImageUrl = (src, options = {}) => {
    const {
      width,
      height,
      quality = 80,
      format = 'webp'
    } = options

    // If using a CDN or image optimization service
    if (src.includes('cdn') || src.includes('optimize')) {
      const params = new URLSearchParams()
      if (width) params.append('w', width)
      if (height) params.append('h', height)
      if (quality) params.append('q', quality)
      if (format) params.append('f', format)

      return `${src}?${params.toString()}`
    }

    return src
  }

  /**
   * Preload critical assets
   */
  const preloadCriticalAssets = async () => {
    const criticalAssets = [
      // Add critical asset URLs here
      '/images/logo.png',
      '/images/hero-bg.jpg',
      // Add more critical assets
    ]

    try {
      await preloadImages(criticalAssets)
    } catch (error) {
      console.warn('Failed to preload some critical assets:', error)
    }
  }

  /**
   * Preload route-specific assets
   */
  const preloadRouteAssets = async (routeName) => {
    const routeAssets = {
      dashboard: [
        '/images/dashboard-bg.jpg',
        '/images/chart-icons.svg'
      ],
      users: [
        '/images/user-avatar-placeholder.png'
      ],
      siswa: [
        '/images/student-avatar-placeholder.png'
      ],
      // Add more route-specific assets
    }

    const assets = routeAssets[routeName] || []
    if (assets.length > 0) {
      try {
        await preloadImages(assets)
      } catch (error) {
        console.warn(`Failed to preload assets for route ${routeName}:`, error)
      }
    }
  }

  /**
   * Cleanup unused assets
   */
  const cleanupUnusedAssets = () => {
    // Remove unused preloaded assets from memory
    preloadedAssets.value.clear()
    loadingAssets.value.clear()
  }

  return {
    preloadedAssets,
    loadingAssets,
    preloadImage,
    preloadImages,
    preloadCSS,
    preloadJS,
    preloadFont,
    useLazyImage,
    optimizeImage,
    getOptimizedImageUrl,
    preloadCriticalAssets,
    preloadRouteAssets,
    cleanupUnusedAssets
  }
}

/**
 * Composable for bundle optimization
 */
export function useBundleOptimization() {
  const loadedChunks = ref(new Set())
  const loadingChunks = ref(new Set())

  /**
   * Preload chunk
   */
  const preloadChunk = async (chunkName) => {
    if (loadedChunks.value.has(chunkName) || loadingChunks.value.has(chunkName)) {
      return Promise.resolve()
    }

    loadingChunks.value.add(chunkName)

    try {
      // This would typically use webpack's magic comments
      // or a custom chunk loading mechanism
      await import(/* webpackChunkName: "[request]" */ `../views/${chunkName}.vue`)
      loadedChunks.value.add(chunkName)
    } catch (error) {
      console.warn(`Failed to preload chunk ${chunkName}:`, error)
    } finally {
      loadingChunks.value.delete(chunkName)
    }
  }

  /**
   * Preload multiple chunks
   */
  const preloadChunks = async (chunkNames) => {
    const promises = chunkNames.map(name => preloadChunk(name))
    await Promise.all(promises)
  }

  /**
   * Get chunk loading status
   */
  const getChunkStatus = (chunkName) => {
    return {
      loaded: loadedChunks.value.has(chunkName),
      loading: loadingChunks.value.has(chunkName)
    }
  }

  return {
    loadedChunks,
    loadingChunks,
    preloadChunk,
    preloadChunks,
    getChunkStatus
  }
}
