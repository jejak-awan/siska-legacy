import { ref, onMounted, onUnmounted, watch } from 'vue'

/**
 * Composable for lazy loading components and data
 */
export function useLazyLoading() {
  const isVisible = ref(false)
  const isLoaded = ref(false)
  const isLoading = ref(false)
  const error = ref(null)

  /**
   * Intersection Observer for lazy loading
   */
  const observer = ref(null)

  /**
   * Setup intersection observer
   */
  const setupObserver = (element, options = {}) => {
    if (!element) return

    const defaultOptions = {
      root: null,
      rootMargin: '50px',
      threshold: 0.1,
      ...options
    }

    observer.value = new IntersectionObserver((entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          isVisible.value = true
          observer.value?.unobserve(entry.target)
        }
      })
    }, defaultOptions)

    observer.value.observe(element)
  }

  /**
   * Lazy load data with loading states
   */
  const lazyLoadData = async (loader, options = {}) => {
    if (isLoaded.value || isLoading.value) return

    isLoading.value = true
    error.value = null

    try {
      const result = await loader()
      isLoaded.value = true
      return result
    } catch (err) {
      error.value = err
      throw err
    } finally {
      isLoading.value = false
    }
  }

  /**
   * Lazy load component
   */
  const lazyLoadComponent = async (componentLoader) => {
    if (isLoaded.value || isLoading.value) return null

    isLoading.value = true
    error.value = null

    try {
      const component = await componentLoader()
      isLoaded.value = true
      return component
    } catch (err) {
      error.value = err
      throw err
    } finally {
      isLoading.value = false
    }
  }

  /**
   * Preload component
   */
  const preloadComponent = (componentLoader) => {
    return componentLoader().catch((err) => {
      console.warn('Preload failed:', err)
    })
  }

  /**
   * Cleanup observer
   */
  const cleanup = () => {
    if (observer.value) {
      observer.value.disconnect()
      observer.value = null
    }
  }

  onUnmounted(() => {
    cleanup()
  })

  return {
    isVisible,
    isLoaded,
    isLoading,
    error,
    setupObserver,
    lazyLoadData,
    lazyLoadComponent,
    preloadComponent,
    cleanup
  }
}

/**
 * Composable for image lazy loading
 */
export function useLazyImage(src, options = {}) {
  const { isVisible, setupObserver, error } = useLazyLoading()
  const imageSrc = ref('')
  const imageRef = ref(null)

  const loadImage = () => {
    if (!src || !isVisible.value) return

    const img = new Image()
    img.onload = () => {
      imageSrc.value = src
    }
    img.onerror = (err) => {
      error.value = err
    }
    img.src = src
  }

  onMounted(() => {
    if (imageRef.value) {
      setupObserver(imageRef.value, options)
    }
  })

  watch(isVisible, (visible) => {
    if (visible) {
      loadImage()
    }
  })

  return {
    imageSrc,
    imageRef,
    isVisible,
    error
  }
}

/**
 * Composable for lazy loading lists with pagination
 */
export function useLazyList(loader, options = {}) {
  const { lazyLoadData, isLoading, error } = useLazyLoading()
  const items = ref([])
  const hasMore = ref(true)
  const page = ref(1)
  const pageSize = options.pageSize || 20

  const loadMore = async () => {
    if (!hasMore.value || isLoading.value) return

    try {
      const result = await lazyLoadData(() => loader(page.value, pageSize))
      
      if (result.data) {
        items.value.push(...result.data)
        hasMore.value = result.hasMore || result.data.length === pageSize
        page.value++
      }
    } catch (err) {
      console.error('Failed to load more items:', err)
    }
  }

  const reset = () => {
    items.value = []
    hasMore.value = true
    page.value = 1
  }

  const refresh = async () => {
    reset()
    await loadMore()
  }

  return {
    items,
    hasMore,
    isLoading,
    error,
    loadMore,
    reset,
    refresh
  }
}

/**
 * Composable for lazy loading with virtual scrolling
 */
export function useVirtualList(items, options = {}) {
  const containerRef = ref(null)
  const visibleItems = ref([])
  const startIndex = ref(0)
  const endIndex = ref(0)
  const itemHeight = options.itemHeight || 50
  const containerHeight = ref(0)
  const totalHeight = ref(0)

  const updateVisibleItems = () => {
    if (!containerRef.value || !items.value.length) return

    const scrollTop = containerRef.value.scrollTop
    const visibleCount = Math.ceil(containerHeight.value / itemHeight)
    
    startIndex.value = Math.floor(scrollTop / itemHeight)
    endIndex.value = Math.min(startIndex.value + visibleCount + 1, items.value.length)
    
    visibleItems.value = items.value.slice(startIndex.value, endIndex.value)
  }

  const onScroll = () => {
    updateVisibleItems()
  }

  onMounted(() => {
    if (containerRef.value) {
      containerHeight.value = containerRef.value.clientHeight
      totalHeight.value = items.value.length * itemHeight
      updateVisibleItems()
    }
  })

  watch(items, () => {
    totalHeight.value = items.value.length * itemHeight
    updateVisibleItems()
  })

  return {
    containerRef,
    visibleItems,
    startIndex,
    endIndex,
    totalHeight,
    onScroll
  }
}
