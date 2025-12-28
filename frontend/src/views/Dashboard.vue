<script setup>
import { ref, onMounted } from 'vue'

const servers = ref([])
const loading = ref(true)

const fetchServers = async () => {
  try {
    const token = localStorage.getItem('token')
    const res = await fetch('http://localhost:8000/api/servers', {
      headers: { 'Authorization': `Bearer ${token}` }
    })
    if (res.ok) {
      servers.value = await res.json()
    }
  } catch (e) {
    console.error(e)
  } finally {
    loading.value = false
  }
}

onMounted(fetchServers)
</script>

<template>
  <div class="dashboard">
    <div class="header">
      <h1>My Servers</h1>
      <router-link to="/create-server" class="btn">Create New</router-link>
    </div>

    <div v-if="loading" class="loading">Loading...</div>
    
    <div v-else-if="servers.length === 0" class="empty-state">
      <p>You don't have any servers yet.</p>
    </div>

    <div v-else class="server-grid">
      <div v-for="server in servers" :key="server.id" class="server-card">
        <div class="card-header">
          <h3>{{ server.name }}</h3>
          <span :class="['status-badge', server.status]">{{ server.status }}</span>
        </div>
        <div class="card-body">
          <p><strong>ID:</strong> {{ server.identifier }}</p>
          <div class="resources">
            <div class="res-item">
              <span>RAM</span>
              <div class="bar"><div class="fill" style="width: 50%"></div></div>
            </div>
          </div>
          <a :href="`https://panel.pterodactyl.io/server/${server.identifier}`" target="_blank" class="btn-console">Open in Pterodactyl ↗</a>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.server-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 1.5rem;
}

.server-card {
  background: var(--color-surface);
  border: 1px solid #333;
  border-radius: 12px;
  overflow: hidden;
  transition: transform 0.2s;
}

.server-card:hover { transform: translateY(-2px); border-color: var(--color-primary); }

.card-header {
  padding: 1rem 1.5rem;
  background: rgba(255,255,255,0.03);
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #333;
}

.card-header h3 { margin: 0; font-size: 1.1rem; }

.status-badge {
  font-size: 0.75rem;
  padding: 0.25rem 0.5rem;
  border-radius: 100px;
  background: #333;
  text-transform: uppercase;
  font-weight: bold;
}

.status-badge.installing { background: #d69e2e; color: #000; }
.status-badge.running { background: #38a169; color: white; }

.card-body { padding: 1.5rem; }

.btn-console {
  display: block;
  text-align: center;
  margin-top: 1rem;
  background: #2d3748;
  color: white;
  text-decoration: none;
  padding: 0.75rem;
  border-radius: 6px;
  font-size: 0.9rem;
}
.btn-console:hover { background: #4a5568; }

.loading, .empty-state { text-align: center; color: var(--color-text-muted); margin-top: 4rem; }
</style>
