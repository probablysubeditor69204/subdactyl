<script setup>
import { computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import Sidebar from './components/Sidebar.vue'

const route = useRoute()
const router = useRouter()

// Simple check: don't show sidebar on login/register pages
const showSidebar = computed(() => {
  return !['/login', '/register', '/'].includes(route.path)
})

const handleLogout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  router.push('/login')
}
</script>

<template>
  <div class="app-layout">
    <Sidebar v-if="showSidebar" @logout="handleLogout" />
    <main :class="{ 'with-sidebar': showSidebar }">
      <router-view></router-view>
    </main>
  </div>
</template>

<style>
.app-layout {
  display: flex;
  min-height: 100vh;
}

main {
  flex: 1;
  padding: 2rem;
  overflow-y: auto;
}

main.with-sidebar {
  /* Sidebar width 250px */
  /* padding-left handled by flex layout usually, but ensures content doesn't touch sidebar edge */
}
</style>
