<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const form = ref({
  username: '',
  email: '',
  first_name: '',
  last_name: '',
  password: ''
})
const error = ref('')

const register = async () => {
  try {
    const response = await fetch('/api/register', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(form.value)
    })

    const data = await response.json()

    if (response.ok) {
      localStorage.setItem('token', data.token)
      localStorage.setItem('user', JSON.stringify(data.user))
      router.push('/dashboard')
    } else {
      error.value = data.error || data.message || 'Registration failed'
    }
  } catch (e) {
    error.value = 'An error occurred.'
  }
}
</script>

<template>
  <div class="auth-container">
    <div class="auth-card">
      <h1>Register</h1>
      <p class="subtitle">Join Subdactyl</p>
      
      <form @submit.prevent="register">
        <div class="row">
           <div class="form-group half">
            <label>First Name</label>
            <input v-model="form.first_name" required />
          </div>
          <div class="form-group half">
            <label>Last Name</label>
            <input v-model="form.last_name" required />
          </div>
        </div>

        <div class="form-group">
          <label>Username</label>
          <input v-model="form.username" required />
        </div>

        <div class="form-group">
          <label>Email</label>
          <input type="email" v-model="form.email" required />
        </div>
        
        <div class="form-group">
          <label>Password</label>
          <input type="password" v-model="form.password" required minlength="8" />
        </div>

        <div v-if="error" class="error-msg">{{ error }}</div>

        <button type="submit" class="btn btn-full">Create Account</button>
      </form>
      
      <div class="links">
        <router-link to="/login">Already have an account?</router-link>
      </div>
    </div>
  </div>
</template>

<style scoped>
.auth-container {
  display: flex;
  justify-content: center;
  align-items: center;
  height: 100vh;
}

.auth-card {
  background: var(--color-surface);
  padding: 2.5rem;
  border-radius: 12px;
  width: 100%;
  max-width: 450px;
  border: 1px solid #333;
}

h1, .subtitle { text-align: center; margin-bottom: 0.5rem; }
.subtitle { color: var(--color-text-muted); margin-bottom: 2rem; }

.form-group { margin-bottom: 1rem; }
.row { display: flex; gap: 1rem; }
.half { width: 50%; }

label { display: block; margin-bottom: 0.5rem; color: var(--color-text-muted); font-size: 0.85rem; }
input { width: 100%; padding: 0.75rem; background: #0d0d0d; border: 1px solid #333; color: white; border-radius: 6px; box-sizing: border-box; }
input:focus { border-color: var(--color-primary); outline: none; }

.btn-full { width: 100%; margin-top: 1rem; }
.error-msg { color: #e53e3e; background: rgba(229,62,62,0.1); padding: 0.75rem; margin-bottom: 1rem; border-radius: 6px; }
.links { margin-top: 1.5rem; text-align: center; }
.links a { color: var(--color-primary); text-decoration: none; }
</style>
