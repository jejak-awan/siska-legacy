<template>
  <div class="flex h-screen bg-background">
    <Sidebar />
    <div class="flex-1 flex flex-col overflow-hidden">
      <!-- Navbar -->
      <Navbar />
      
      <!-- Page Content -->
      <main class="flex-1 overflow-y-auto bg-background">
        <router-view />
      </main>
    </div>
  </div>
</template>

<script setup>
import Sidebar from '../../components/layout/Sidebar.vue'
import Navbar from '../../components/layout/Navbar.vue'
</script>
