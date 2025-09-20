<template>
  <div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
      <!-- Header -->
      <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900 mb-4">
          Text Editor Comparison
        </h1>
        <p class="text-lg text-gray-600">
          Perbandingan antara TinyMCE, Atto Editor, dan RichTextEditor
        </p>
      </div>

      <!-- Comparison Table -->
      <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <h2 class="text-xl font-semibold mb-4">Perbandingan Fitur</h2>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Fitur
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  TinyMCE
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Atto Editor
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  RichTextEditor
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  File Size
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  2-3MB
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  500KB-1MB
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  &lt;100KB
                </td>
              </tr>
              <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  Loading Speed
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  Lambat
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  Cepat
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  Sangat Cepat
                </td>
              </tr>
              <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  Features
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  Sangat Lengkap
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  Lengkap
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  Basic
                </td>
              </tr>
              <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  Image Upload
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  ✅ Built-in
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  ❌ Manual
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  ❌ Manual
                </td>
              </tr>
              <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  Table Support
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  ✅ Advanced
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  ❌ Basic
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  ❌ No
                </td>
              </tr>
              <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                  API Key Required
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  ⚠️ Some Features
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  ❌ No
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                  ❌ No
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Editor Demos -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- TinyMCE Demo -->
        <div class="bg-white rounded-lg shadow-md p-6">
          <h3 class="text-lg font-semibold mb-4 text-blue-600">
            TinyMCE Editor
          </h3>
          <p class="text-sm text-gray-600 mb-4">
            Editor profesional dengan fitur lengkap, cocok untuk konten yang kompleks.
          </p>
          <TinyMCEEditor
            v-model="tinyMCEContent"
            placeholder="Masukkan konten dengan TinyMCE..."
            :height="200"
            @change="handleTinyMCEChange"
          />
          <div class="mt-4 p-3 bg-gray-50 rounded text-xs">
            <strong>Content:</strong>
            <div class="mt-1 text-gray-600" v-html="tinyMCEContent"></div>
          </div>
        </div>

        <!-- Atto Editor Demo -->
        <div class="bg-white rounded-lg shadow-md p-6">
          <h3 class="text-lg font-semibold mb-4 text-green-600">
            Atto Editor
          </h3>
          <p class="text-sm text-gray-600 mb-4">
            Editor ringan dengan fitur essential, cepat dan mudah digunakan.
          </p>
          <AttoEditor
            v-model="attoContent"
            placeholder="Masukkan konten dengan Atto Editor..."
            :height="200"
            @change="handleAttoChange"
          />
          <div class="mt-4 p-3 bg-gray-50 rounded text-xs">
            <strong>Content:</strong>
            <div class="mt-1 text-gray-600" v-html="attoContent"></div>
          </div>
        </div>

        <!-- RichTextEditor Demo -->
        <div class="bg-white rounded-lg shadow-md p-6">
          <h3 class="text-lg font-semibold mb-4 text-purple-600">
            RichTextEditor
          </h3>
          <p class="text-sm text-gray-600 mb-4">
            Editor custom yang sudah ada, ringan dan terintegrasi dengan baik.
          </p>
          <RichTextEditor
            v-model="richTextContent"
            placeholder="Masukkan konten dengan RichTextEditor..."
            :height="200"
            @change="handleRichTextChange"
          />
          <div class="mt-4 p-3 bg-gray-50 rounded text-xs">
            <strong>Content:</strong>
            <div class="mt-1 text-gray-600" v-html="richTextContent"></div>
          </div>
        </div>
      </div>

      <!-- Recommendation -->
      <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-4">
          💡 Rekomendasi untuk Sistem Kesiswaan
        </h3>
        <div class="space-y-3 text-blue-800">
          <p>
            <strong>TinyMCE:</strong> Cocok untuk konten yang kompleks seperti profil sekolah, 
            struktur organisasi, dan dokumentasi lengkap. Memiliki fitur upload gambar, 
            tabel, dan formatting yang advanced.
          </p>
          <p>
            <strong>Atto Editor:</strong> Pilihan yang seimbang antara fitur dan performa. 
            Cocok untuk konten sehari-hari seperti visi-misi, prestasi, dan catatan.
          </p>
          <p>
            <strong>RichTextEditor:</strong> Pilihan terbaik untuk performa dan integrasi. 
            Sudah terintegrasi dengan baik dan tidak memerlukan dependency tambahan.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import TinyMCEEditor from '../components/editor/TinyMCEEditor.vue'
import AttoEditor from '../components/editor/AttoEditor.vue'
import RichTextEditor from '../components/editor/RichTextEditor.vue'

const tinyMCEContent = ref('')
const attoContent = ref('')
const richTextContent = ref('')

const handleTinyMCEChange = (content) => {
  console.log('TinyMCE changed:', content)
}

const handleAttoChange = (content) => {
  console.log('Atto changed:', content)
}

const handleRichTextChange = (content) => {
  console.log('RichText changed:', content)
}
</script>
