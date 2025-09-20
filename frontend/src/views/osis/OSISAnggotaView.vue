<template>
  <div class="p-6">
    <div class="mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Anggota OSIS</h1>
      <p class="text-gray-600">Manajemen anggota organisasi siswa</p>
    </div>

    <!-- Action Buttons -->
    <div class="mb-6 flex justify-between items-center">
      <div class="flex space-x-3">
        <button
          @click="openCreateModal"
          class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors"
        >
          <svg class="h-5 w-5 inline mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          Tambah Anggota
        </button>
      </div>
      
      <div class="flex space-x-2">
        <select
          v-model="selectedPosition"
          class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
        >
          <option value="">Semua Posisi</option>
          <option value="Ketua">Ketua</option>
          <option value="Wakil Ketua">Wakil Ketua</option>
          <option value="Sekretaris">Sekretaris</option>
          <option value="Bendahara">Bendahara</option>
          <option value="Anggota">Anggota</option>
        </select>
        <input
          v-model="searchQuery"
          type="text"
          placeholder="Cari anggota..."
          class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
        />
      </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
          <div class="p-2 bg-blue-100 rounded-lg">
            <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Total Anggota</p>
            <p class="text-2xl font-semibold text-gray-900">{{ statistics.total }}</p>
          </div>
        </div>
      </div>
      
      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
          <div class="p-2 bg-green-100 rounded-lg">
            <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Aktif</p>
            <p class="text-2xl font-semibold text-gray-900">{{ statistics.active }}</p>
          </div>
        </div>
      </div>
      
      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
          <div class="p-2 bg-yellow-100 rounded-lg">
            <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Pengurus</p>
            <p class="text-2xl font-semibold text-gray-900">{{ statistics.officers }}</p>
          </div>
        </div>
      </div>
      
      <div class="bg-white p-6 rounded-lg shadow">
        <div class="flex items-center">
          <div class="p-2 bg-purple-100 rounded-lg">
            <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
            </svg>
          </div>
          <div class="ml-4">
            <p class="text-sm font-medium text-gray-600">Anggota Biasa</p>
            <p class="text-2xl font-semibold text-gray-900">{{ statistics.members }}</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Members Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Anggota
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Posisi
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Kelas
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Tanggal Bergabung
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Status
              </th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                Aksi
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="member in filteredMembers" :key="member.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div class="h-10 w-10 bg-blue-100 rounded-full flex items-center justify-center">
                    <span class="text-blue-600 font-semibold text-sm">
                      {{ member.siswa.nama_lengkap.charAt(0).toUpperCase() }}
                    </span>
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900">{{ member.siswa.nama_lengkap }}</div>
                    <div class="text-sm text-gray-500">{{ member.siswa.nis }}</div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getPositionBadgeClass(member.posisi)" class="px-2 py-1 text-xs font-semibold rounded-full">
                  {{ member.posisi }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ member.siswa.kelas }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                {{ formatDate(member.tanggal_bergabung) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStatusBadgeClass(member.status)" class="px-2 py-1 text-xs font-semibold rounded-full">
                  {{ member.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                  <button
                    @click="viewDetails(member)"
                    class="text-blue-600 hover:text-blue-900"
                  >
                    Lihat
                  </button>
                  <button
                    @click="editMember(member)"
                    class="text-indigo-600 hover:text-indigo-900"
                  >
                    Edit
                  </button>
                  <button
                    v-if="member.status === 'Aktif'"
                    @click="deactivateMember(member)"
                    class="text-red-600 hover:text-red-900"
                  >
                    Nonaktifkan
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Create/Edit Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
      <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
          <h3 class="text-lg font-medium text-gray-900 mb-4">
            {{ isEditing ? 'Edit Anggota OSIS' : 'Tambah Anggota OSIS' }}
          </h3>
          
          <form @submit.prevent="saveMember" class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700">Siswa</label>
              <select v-model="form.siswa_id" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                <option value="">Pilih Siswa</option>
                <option v-for="siswa in students" :key="siswa.id" :value="siswa.id">
                  {{ siswa.nama_lengkap }} - {{ siswa.kelas }}
                </option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700">Posisi</label>
              <select v-model="form.posisi" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                <option value="">Pilih Posisi</option>
                <option value="Ketua">Ketua</option>
                <option value="Wakil Ketua">Wakil Ketua</option>
                <option value="Sekretaris">Sekretaris</option>
                <option value="Bendahara">Bendahara</option>
                <option value="Anggota">Anggota</option>
              </select>
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700">Tanggal Bergabung</label>
              <input
                v-model="form.tanggal_bergabung"
                type="date"
                class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2"
                required
              />
            </div>
            
            <div>
              <label class="block text-sm font-medium text-gray-700">Status</label>
              <select v-model="form.status" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                <option value="Aktif">Aktif</option>
                <option value="Tidak Aktif">Tidak Aktif</option>
              </select>
            </div>
            
            <div class="flex justify-end space-x-3 pt-4">
              <button
                type="button"
                @click="closeModal"
                class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50"
              >
                Batal
              </button>
              <button
                type="submit"
                class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700"
              >
                {{ isEditing ? 'Update' : 'Simpan' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'

// Reactive data
const members = ref([])
const students = ref([])
const searchQuery = ref('')
const selectedPosition = ref('')
const showModal = ref(false)
const isEditing = ref(false)
const form = ref({
  id: null,
  siswa_id: '',
  posisi: '',
  tanggal_bergabung: '',
  status: 'Aktif'
})

// Statistics
const statistics = ref({
  total: 0,
  active: 0,
  officers: 0,
  members: 0
})

// Computed
const filteredMembers = computed(() => {
  let filtered = members.value
  
  if (searchQuery.value) {
    filtered = filtered.filter(member =>
      member.siswa.nama_lengkap.toLowerCase().includes(searchQuery.value.toLowerCase())
    )
  }
  
  if (selectedPosition.value) {
    filtered = filtered.filter(member => member.posisi === selectedPosition.value)
  }
  
  return filtered
})

// Methods
const loadMembers = async () => {
  try {
    // Mock data - replace with actual API call
    members.value = [
      {
        id: 1,
        siswa: { nama_lengkap: 'Ahmad Rizki', kelas: 'X-A', nis: '2024001001' },
        posisi: 'Ketua',
        tanggal_bergabung: '2024-07-15',
        status: 'Aktif'
      },
      {
        id: 2,
        siswa: { nama_lengkap: 'Siti Nurhaliza', kelas: 'XI-B', nis: '2024001002' },
        posisi: 'Sekretaris',
        tanggal_bergabung: '2024-07-15',
        status: 'Aktif'
      },
      {
        id: 3,
        siswa: { nama_lengkap: 'Budi Santoso', kelas: 'X-C', nis: '2024001003' },
        posisi: 'Anggota',
        tanggal_bergabung: '2024-07-20',
        status: 'Aktif'
      }
    ]
    
    updateStatistics()
  } catch (error) {
    console.error('Error loading members:', error)
  }
}

const loadStudents = async () => {
  try {
    // Mock data - replace with actual API call
    students.value = [
      { id: 1, nama_lengkap: 'Ahmad Rizki', kelas: 'X-A' },
      { id: 2, nama_lengkap: 'Siti Nurhaliza', kelas: 'XI-B' },
      { id: 3, nama_lengkap: 'Budi Santoso', kelas: 'X-C' }
    ]
  } catch (error) {
    console.error('Error loading students:', error)
  }
}

const updateStatistics = () => {
  statistics.value = {
    total: members.value.length,
    active: members.value.filter(m => m.status === 'Aktif').length,
    officers: members.value.filter(m => ['Ketua', 'Wakil Ketua', 'Sekretaris', 'Bendahara'].includes(m.posisi)).length,
    members: members.value.filter(m => m.posisi === 'Anggota').length
  }
}

const openCreateModal = () => {
  isEditing.value = false
  form.value = {
    id: null,
    siswa_id: '',
    posisi: '',
    tanggal_bergabung: '',
    status: 'Aktif'
  }
  showModal.value = true
}

const editMember = (member) => {
  isEditing.value = true
  form.value = { ...member }
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  isEditing.value = false
}

const saveMember = async () => {
  try {
    if (isEditing.value) {
      // Update existing member
      const index = members.value.findIndex(m => m.id === form.value.id)
      if (index !== -1) {
        members.value[index] = { ...form.value }
      }
    } else {
      // Create new member
      const newMember = {
        ...form.value,
        id: Date.now(),
        siswa: students.value.find(s => s.id === parseInt(form.value.siswa_id))
      }
      members.value.unshift(newMember)
    }
    
    updateStatistics()
    closeModal()
  } catch (error) {
    console.error('Error saving member:', error)
  }
}

const viewDetails = (member) => {
  // Implement view details functionality
  console.log('View details:', member)
}

const deactivateMember = (member) => {
  if (confirm('Apakah Anda yakin ingin menonaktifkan anggota ini?')) {
    member.status = 'Tidak Aktif'
    updateStatistics()
  }
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID')
}

const getPositionBadgeClass = (position) => {
  const classes = {
    'Ketua': 'bg-red-100 text-red-800',
    'Wakil Ketua': 'bg-orange-100 text-orange-800',
    'Sekretaris': 'bg-blue-100 text-blue-800',
    'Bendahara': 'bg-green-100 text-green-800',
    'Anggota': 'bg-gray-100 text-gray-800'
  }
  return classes[position] || 'bg-gray-100 text-gray-800'
}

const getStatusBadgeClass = (status) => {
  const classes = {
    'Aktif': 'bg-green-100 text-green-800',
    'Tidak Aktif': 'bg-red-100 text-red-800'
  }
  return classes[status] || 'bg-gray-100 text-gray-800'
}

// Lifecycle
onMounted(() => {
  loadMembers()
  loadStudents()
})
</script>
