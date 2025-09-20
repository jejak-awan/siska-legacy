<template>
  <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
      <!-- Background overlay -->
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal"></div>

      <!-- Modal panel -->
      <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
        <div>
          <!-- Header -->
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-lg font-medium text-gray-900">Detail Notifikasi</h3>
              <button
                type="button"
                @click="closeModal"
                class="text-gray-400 hover:text-gray-600 transition-colors"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>

            <!-- Notification Details -->
            <div v-if="notification" class="space-y-4">
              <!-- Status and Type -->
              <div class="flex space-x-4">
                <div>
                  <span class="text-sm font-medium text-gray-700">Status:</span>
                  <span
                    :class="getStatusBadgeClass(notification.status)"
                    class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  >
                    {{ getStatusLabel(notification.status) }}
                  </span>
                </div>
                <div>
                  <span class="text-sm font-medium text-gray-700">Tipe:</span>
                  <span
                    :class="getTypeBadgeClass(notification.tipe)"
                    class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full"
                  >
                    {{ getTypeLabel(notification.tipe) }}
                  </span>
                </div>
              </div>

              <!-- Title -->
              <div>
                <span class="text-sm font-medium text-gray-700">Judul:</span>
                <p class="mt-1 text-sm text-gray-900">{{ notification.judul }}</p>
              </div>

              <!-- Message -->
              <div>
                <span class="text-sm font-medium text-gray-700">Pesan:</span>
                <p class="mt-1 text-sm text-gray-900 whitespace-pre-wrap">{{ notification.pesan }}</p>
              </div>

              <!-- Action Button (if exists) -->
              <div v-if="notification.action_url && notification.action_text">
                <span class="text-sm font-medium text-gray-700">Aksi:</span>
                <div class="mt-1">
                  <a
                    :href="notification.action_url"
                    target="_blank"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                  >
                    {{ notification.action_text }}
                    <svg class="ml-2 -mr-0.5 h-4 w-4" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" />
                    </svg>
                  </a>
                </div>
              </div>

              <!-- Timestamps -->
              <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                  <span class="font-medium text-gray-700">Dibuat:</span>
                  <p class="text-gray-900">{{ formatDateTime(notification.created_at) }}</p>
                </div>
                <div v-if="notification.read_at">
                  <span class="font-medium text-gray-700">Dibaca:</span>
                  <p class="text-gray-900">{{ formatDateTime(notification.read_at) }}</p>
                </div>
              </div>

              <!-- Additional Data (if exists) -->
              <div v-if="notification.data" class="bg-gray-50 rounded-lg p-4">
                <span class="text-sm font-medium text-gray-700">Data Tambahan:</span>
                <pre class="mt-1 text-sm text-gray-900 overflow-x-auto">{{ JSON.stringify(notification.data, null, 2) }}</pre>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
            <button
              v-if="notification?.status === 'unread'"
              @click="markAsRead"
              :disabled="loading"
              class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-green-600 text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="loading" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>
              Tandai Dibaca
            </button>
            <button
              v-if="notification?.status === 'read'"
              @click="markAsArchived"
              :disabled="loading"
              class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-gray-600 text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="loading" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>
              Arsipkan
            </button>
            <button
              @click="deleteNotification"
              :disabled="loading"
              class="w-full inline-flex justify-center rounded-lg border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50 disabled:cursor-not-allowed"
            >
              <span v-if="loading" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white mr-2"></span>
              Hapus
            </button>
            <button
              type="button"
              @click="closeModal"
              class="mt-3 w-full inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
            >
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import api from '@/services/api'

const props = defineProps({
  show: {
    type: Boolean,
    default: false
  },
  notification: {
    type: Object,
    default: null
  }
})

const emit = defineEmits(['close', 'updated'])

// Reactive data
const loading = ref(false)

// Methods
const markAsRead = async () => {
  if (!props.notification) return
  
  loading.value = true
  try {
    await api.post(`/notifications/${props.notification.id}/mark-read`)
    emit('updated')
  } catch (error) {
    console.error('Error marking notification as read:', error)
  } finally {
    loading.value = false
  }
}

const markAsArchived = async () => {
  if (!props.notification) return
  
  loading.value = true
  try {
    await api.post(`/notifications/${props.notification.id}/mark-archived`)
    emit('updated')
  } catch (error) {
    console.error('Error marking notification as archived:', error)
  } finally {
    loading.value = false
  }
}

const deleteNotification = async () => {
  if (!props.notification) return
  
  if (confirm('Apakah Anda yakin ingin menghapus notifikasi ini?')) {
    loading.value = true
    try {
      await api.delete(`/notifications/${props.notification.id}`)
      emit('updated')
    } catch (error) {
      console.error('Error deleting notification:', error)
    } finally {
      loading.value = false
    }
  }
}

const closeModal = () => {
  emit('close')
}

// Utility functions
const formatDateTime = (date) => {
  return new Date(date).toLocaleString('id-ID')
}

const getStatusLabel = (status) => {
  const labels = {
    unread: 'Belum Dibaca',
    read: 'Sudah Dibaca',
    archived: 'Diarsipkan'
  }
  return labels[status] || status
}

const getStatusBadgeClass = (status) => {
  const classes = {
    unread: 'bg-yellow-100 text-yellow-800',
    read: 'bg-green-100 text-green-800',
    archived: 'bg-gray-100 text-gray-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

const getTypeLabel = (tipe) => {
  const labels = {
    info: 'Informasi',
    warning: 'Peringatan',
    error: 'Error',
    success: 'Berhasil'
  }
  return labels[tipe] || tipe
}

const getTypeBadgeClass = (tipe) => {
  const classes = {
    info: 'bg-blue-100 text-blue-800',
    warning: 'bg-yellow-100 text-yellow-800',
    error: 'bg-red-100 text-red-800',
    success: 'bg-green-100 text-green-800'
  }
  return classes[tipe] || 'bg-gray-100 text-gray-800'
}
</script>
