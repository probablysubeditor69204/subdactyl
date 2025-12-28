<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const step = ref(1)
const form = ref({
  app_name: 'Subdactyl',
  pterodactyl_url: '',
  pterodactyl_key: '',
  admin_username: '',
  admin_email: '',
  admin_password: ''
})
const loading = ref(false)
const error = ref('')

const submitSetup = async () => {
  loading.value = true
  error.value = ''
  
  try {
    const res = await fetch('http://localhost:8000/api/setup', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form.value)
    })
    
    if (res.ok) {
        // Auto login or redirect to login
        router.push('/login')
    } else {
        const data = await res.json()
        error.value = data.message || 'Setup failed'
    }
  } catch (e) {
    error.value = 'Connection error'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="setup-container">
    <div class="card">
      <h1>Welcome to Subdactyl</h1>
      <p class="subtitle">Initial Setup Wizard</p>
      
      <div v-if="step === 1">
        <h3>Step 1: Pterodactyl Configuration</h3>
        <div class="form-group">
          <label>Dashboard Name</label>
          <input v-model="form.app_name" placeholder="Subdactyl" />
        </div>
        <div class="form-group">
          <label>Pterodactyl Application URL</label>
          <input v-model="form.pterodactyl_url" placeholder="https://panel.example.com" />
        </div>
        <div class="form-group">
          <label>Application API Key</label>
          <input v-model="form.pterodactyl_key" type="password" placeholder="ptlc_..." />
        </div>
        <button @click="step++" class="btn btn-primary">Next</button>
      </div>

      <div v-else>
        <h3>Step 2: Admin Account</h3>
        <div class="form-group">
          <label>Admin Username</label>
          <input v-model="form.admin_username" />
        </div>
        <div class="form-group">
          <label>Admin Email</label>
          <input v-model="form.admin_email" type="email" />
        </div>
        <div class="form-group">
          <label>Admin Password</label>
          <input v-model="form.admin_password" type="password" />
        </div>
        
        <div class="buttons">
          <button @click="step--" class="btn secondary">Back</button>
          <button @click="submitSetup" :disabled="loading" class="btn btn-primary">
            {{ loading ? 'Installing...' : 'Complete Setup' }}
          </button>
        </div>
        
        <p v-if="error" class="error-msg">{{ error }}</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.setup-container { display: flex; justify-content: center; align-items: center; min-height: 100vh; }
.card { background: var(--color-surface); padding: 2.5rem; border-radius: 12px; width: 100%; max-width: 500px; border: 1px solid #333; }
h1 { text-align: center; margin-bottom: 0.5rem; }
.subtitle { text-align: center; color: var(--color-text-muted); margin-bottom: 2rem; border-bottom: 1px solid #333; padding-bottom: 1rem; }
.form-group { margin-bottom: 1.5rem; }
label { display: block; margin-bottom: 0.5rem; }
input { width: 100%; padding: 0.75rem; background: #0d0d0d; border: 1px solid #333; color: white; border-radius: 6px; box-sizing: border-box; }
.btn { margin-top: 1rem; padding: 0.75rem 1.5rem; }
.buttons { display: flex; gap: 1rem; justify-content: space-between; }
.secondary { background: #333; }
.btn-primary { flex: 1; }
.error-msg { color: #e53e3e; margin-top: 1rem; }
</style>
