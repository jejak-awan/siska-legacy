import { ref, computed, watch } from 'vue'
import api from '../services/api'
import { useLazyLoading } from './useLazyLoading'

/**
 * Composable for API pagination and filtering
 */
export function useApiPagination(endpoint, options = {}) {
  const {
    pageSize = 20,
    initialPage = 1,
    initialFilters = {},
    autoLoad = true
  } = options

  // State
  const data = ref([])
  const loading = ref(false)
  const error = ref(null)
  const currentPage = ref(initialPage)
  const totalPages = ref(0)
  const totalItems = ref(0)
  const hasNextPage = ref(false)
  const hasPrevPage = ref(false)
  const filters = ref({ ...initialFilters })
  const searchQuery = ref('')
  const sortBy = ref('')
  const sortOrder = ref('asc')

  // Computed
  const paginationInfo = computed(() => ({
    currentPage: currentPage.value,
    totalPages: totalPages.value,
    totalItems: totalItems.value,
    pageSize,
    hasNextPage: hasNextPage.value,
    hasPrevPage: hasPrevPage.value,
    startItem: (currentPage.value - 1) * pageSize + 1,
    endItem: Math.min(currentPage.value * pageSize, totalItems.value)
  }))

  const queryParams = computed(() => {
    const params = {
      page: currentPage.value,
      per_page: pageSize,
      ...filters.value
    }

    if (searchQuery.value) {
      params.search = searchQuery.value
    }

    if (sortBy.value) {
      params.sort_by = sortBy.value
      params.sort_order = sortOrder.value
    }

    return params
  })

  // Methods
  const fetchData = async (page = currentPage.value) => {
    loading.value = true
    error.value = null

    try {
      const response = await api.get(endpoint, {
        params: {
          ...queryParams.value,
          page
        }
      })

      const responseData = response.data

      // Handle different response formats
      if (responseData.data) {
        // Laravel pagination format
        data.value = responseData.data
        totalPages.value = responseData.last_page || 1
        totalItems.value = responseData.total || 0
        currentPage.value = responseData.current_page || 1
      } else if (Array.isArray(responseData)) {
        // Simple array format
        data.value = responseData
        totalPages.value = 1
        totalItems.value = responseData.length
        currentPage.value = 1
      } else {
        // Custom format
        data.value = responseData.items || responseData.results || []
        totalPages.value = responseData.totalPages || 1
        totalItems.value = responseData.total || 0
        currentPage.value = responseData.currentPage || 1
      }

      hasNextPage.value = currentPage.value < totalPages.value
      hasPrevPage.value = currentPage.value > 1

    } catch (err) {
      error.value = err
      console.error('Failed to fetch data:', err)
    } finally {
      loading.value = false
    }
  }

  const nextPage = async () => {
    if (hasNextPage.value && !loading.value) {
      await fetchData(currentPage.value + 1)
    }
  }

  const prevPage = async () => {
    if (hasPrevPage.value && !loading.value) {
      await fetchData(currentPage.value - 1)
    }
  }

  const goToPage = async (page) => {
    if (page >= 1 && page <= totalPages.value && page !== currentPage.value) {
      await fetchData(page)
    }
  }

  const refresh = async () => {
    await fetchData(currentPage.value)
  }

  const reset = async () => {
    currentPage.value = initialPage
    filters.value = { ...initialFilters }
    searchQuery.value = ''
    sortBy.value = ''
    sortOrder.value = 'asc'
    await fetchData()
  }

  const setFilters = async (newFilters) => {
    filters.value = { ...filters.value, ...newFilters }
    currentPage.value = 1
    await fetchData()
  }

  const setSearch = async (query) => {
    searchQuery.value = query
    currentPage.value = 1
    await fetchData()
  }

  const setSorting = async (field, order = 'asc') => {
    sortBy.value = field
    sortOrder.value = order
    currentPage.value = 1
    await fetchData()
  }

  // Watchers
  watch([filters, searchQuery, sortBy, sortOrder], () => {
    if (autoLoad) {
      currentPage.value = 1
      fetchData()
    }
  }, { deep: true })

  // Initialize
  if (autoLoad) {
    fetchData()
  }

  return {
    // State
    data,
    loading,
    error,
    currentPage,
    totalPages,
    totalItems,
    hasNextPage,
    hasPrevPage,
    filters,
    searchQuery,
    sortBy,
    sortOrder,

    // Computed
    paginationInfo,
    queryParams,

    // Methods
    fetchData,
    nextPage,
    prevPage,
    goToPage,
    refresh,
    reset,
    setFilters,
    setSearch,
    setSorting
  }
}

/**
 * Composable for infinite scroll pagination
 */
export function useInfiniteScroll(endpoint, options = {}) {
  const {
    pageSize = 20,
    initialFilters = {},
    threshold = 100
  } = options

  const { isVisible, setupObserver } = useLazyLoading()

  const data = ref([])
  const loading = ref(false)
  const error = ref(null)
  const hasMore = ref(true)
  const currentPage = ref(1)
  const filters = ref({ ...initialFilters })
  const searchQuery = ref('')
  const loadMoreRef = ref(null)

  const loadMore = async () => {
    if (!hasMore.value || loading.value) return

    loading.value = true
    error.value = null

    try {
      const params = {
        page: currentPage.value,
        per_page: pageSize,
        ...filters.value
      }

      if (searchQuery.value) {
        params.search = searchQuery.value
      }

      const response = await api.get(endpoint, { params })
      const responseData = response.data

      let newItems = []
      let totalPages = 1

      if (responseData.data) {
        newItems = responseData.data
        totalPages = responseData.last_page || 1
      } else if (Array.isArray(responseData)) {
        newItems = responseData
      } else {
        newItems = responseData.items || responseData.results || []
        totalPages = responseData.totalPages || 1
      }

      data.value.push(...newItems)
      hasMore.value = currentPage.value < totalPages
      currentPage.value++

    } catch (err) {
      error.value = err
      console.error('Failed to load more data:', err)
    } finally {
      loading.value = false
    }
  }

  const reset = async () => {
    data.value = []
    currentPage.value = 1
    hasMore.value = true
    filters.value = { ...initialFilters }
    searchQuery.value = ''
    await loadMore()
  }

  const setFilters = async (newFilters) => {
    filters.value = { ...filters.value, ...newFilters }
    await reset()
  }

  const setSearch = async (query) => {
    searchQuery.value = query
    await reset()
  }

  // Setup intersection observer for infinite scroll
  const setupInfiniteScroll = (element) => {
    if (element) {
      setupObserver(element, {
        rootMargin: `${threshold}px`
      })
    }
  }

  // Watch for visibility to trigger load more
  watch(isVisible, (visible) => {
    if (visible && hasMore.value && !loading.value) {
      loadMore()
    }
  })

  return {
    data,
    loading,
    error,
    hasMore,
    filters,
    searchQuery,
    loadMoreRef,
    loadMore,
    reset,
    setFilters,
    setSearch,
    setupInfiniteScroll
  }
}

/**
 * Composable for search with debouncing
 */
export function useSearch(apiCall, options = {}) {
  const {
    debounceMs = 300,
    minLength = 2,
    initialQuery = ''
  } = options

  const query = ref(initialQuery)
  const results = ref([])
  const loading = ref(false)
  const error = ref(null)
  const debounceTimer = ref(null)

  const search = async (searchQuery = query.value) => {
    if (searchQuery.length < minLength) {
      results.value = []
      return
    }

    loading.value = true
    error.value = null

    try {
      const response = await apiCall(searchQuery)
      results.value = response.data || response
    } catch (err) {
      error.value = err
      console.error('Search failed:', err)
    } finally {
      loading.value = false
    }
  }

  const debouncedSearch = (searchQuery = query.value) => {
    if (debounceTimer.value) {
      clearTimeout(debounceTimer.value)
    }

    debounceTimer.value = setTimeout(() => {
      search(searchQuery)
    }, debounceMs)
  }

  const clearResults = () => {
    results.value = []
    query.value = ''
  }

  return {
    query,
    results,
    loading,
    error,
    search,
    debouncedSearch,
    clearResults
  }
}
