<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const name = ref('')
const selectedNode = ref(1) // mocked for MVP
const selectedEgg = ref(1)  // mocked for MVP
const loading = ref(false)
const error = ref('')

const createServer = async () => {
  loading.value = true
  error.value = ''
  
  try {
    const token = localStorage.getItem('token')
    const res = await fetch('http://localhost:8000/api/servers', {
      method: 'POST',
      headers: { 
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}` 
      },
      body: JSON.stringify({
        name: name.value,
        node_id: selectedNode.value,
        egg_id: selectedEgg.value
      })
    })
    
    const data = await res.json()
    if (res.ok) {
      router.push('/dashboard')
    } else {
      error.value = data.error || 'Failed to create server'
    }
  } catch (e) {
    error.value = 'Connection error'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="create-container">
    <h1>Create New Server</h1>
    <div class="card">
      <div class="form-group">
        <label>Server Name</label>
        <input v-model="name" placeholder="My Awesome Server" />
      </div>

      <div class="form-group">
        <label>Type</label>
        <select v-model="selectedEgg">
          <option :value="1">Minecraft Paper</option>
          <option :value="2">Vanilla Minecraft</option>
        </select>
      </div>

      <div class="info-box">
        <h4>Free Plan Limits</h4>
        <ul>
          <li>1 GB RAM</li>
          <li>5 GB Disk</li>
          <li>100% CPU</li>
        </ul>
      </div>

      <div v-if="error" class="error-msg">{{ error }}</div>

      <button @click="createServer" :disabled="loading" class="btn btn-primary">
        {{ loading ? 'Deploying...' : 'Deploy Server' }}
      </button>
    </div>
  </div>
</template>

<style scoped>
.create-container { max-width: 600px; margin: 0 auto; }
.card { background: var(--color-surface); padding: 2rem; border-radius: 12px; border: 1px solid #333; }
.form-group { margin-bottom: 1.5rem; }
label { display: block; margin-bottom: 0.5rem; color: var(--color-text-muted); }
input, select { width: 100%; padding: 0.75rem; background: #0d0d0d; border: 1px solid #333; color: white; border-radius: 6px; box-sizing: border-box; }
.info-box { background: rgba(59, 130, 246, 0.1); border: 1px solid rgba(59, 130, 246, 0.3); padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; }
.info-box h4 { margin: 0 0 0.5rem 0; color: #60a5fa; }
.info-box ul { margin: 0; padding-left: 1.2rem; color: #dbeafe; }
.btn-primary { width: 100%; padding: 1rem; font-size: 1rem; }
.error-msg { color: #e53e3e; margin-bottom: 1rem; }
</style>
