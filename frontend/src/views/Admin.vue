<script setup>
import { ref } from 'vue'

const message = ref('')
const loading = ref(false)

const syncResources = async () => {
  loading.value = true
  message.value = ''
  try {
    const token = localStorage.getItem('token')
    const res = await fetch('/api/admin/sync', {
      method: 'POST',
      headers: { 'Authorization': `Bearer ${token}` }
    })
    const data = await res.json()
    message.value = data.message || data.error
  } catch (e) {
    message.value = 'Failed to sync resources'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div>
    <h1>Admin Dashboard</h1>
    
    <div class="grid">
      <div class="card">
        <h3>Resource Management</h3>
        <p>Sync Nodes and Eggs from Pterodactyl</p>
        <button @click="syncResources" :disabled="loading" class="btn">
          {{ loading ? 'Syncing...' : 'Sync Resources' }}
        </button>
        <p v-if="message" class="status-msg">{{ message }}</p>
      </div>

      <div class="card">
        <h3>User Management</h3>
        <p>Manage registered users</p>
        <button class="btn secondary">View Users</button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem; margin-top: 1.5rem; }
.card { background: var(--color-surface); padding: 1.5rem; border-radius: 12px; border: 1px solid #333; }
.card h3 { margin-top: 0; }
.status-msg { margin-top: 1rem; color: #4ade80; }
.secondary { background: #333; }
</style>
