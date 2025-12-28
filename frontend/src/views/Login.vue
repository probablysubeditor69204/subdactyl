<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const email = ref('')
const password = ref('')
const error = ref('')

const login = async () => {
  try {
    const response = await fetch('/api/login', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify({ email: email.value, password: password.value })
    })

    const data = await response.json()

    if (response.ok) {
      localStorage.setItem('token', data.token)
      localStorage.setItem('user', JSON.stringify(data.user))
      router.push('/dashboard')
    } else {
      error.value = data.message || 'Login failed'
    }
  } catch (e) {
    error.value = 'An error occurred. Check connection.'
  }
}
</script>

<template>
  <div class="auth-container">
    <div class="auth-card">
      <h1>Login</h1>
      <p class="subtitle">Access your dashboard</p>
      
      <form @submit.prevent="login">
        <div class="form-group">
          <label>Email</label>
          <input type="email" v-model="email" required placeholder="user@example.com" />
        </div>
        
        <div class="form-group">
          <label>Password</label>
          <input type="password" v-model="password" required />
        </div>

        <div v-if="error" class="error-msg">{{ error }}</div>

        <button type="submit" class="btn btn-full">Login</button>
      </form>
      
      <div class="links">
        <router-link to="/register">Create an account</router-link>
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
  max-width: 400px;
  border: 1px solid #333;
}

h1 {
  margin: 0;
  text-align: center;
}

.subtitle {
  text-align: center;
  color: var(--color-text-muted);
  margin-bottom: 2rem;
}

.form-group {
  margin-bottom: 1.5rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
  color: var(--color-text-muted);
}

input {
  width: 100%;
  padding: 0.75rem;
  background: #0d0d0d;
  border: 1px solid #333;
  color: white;
  border-radius: var(--border-radius);
  box-sizing: border-box;
}

input:focus {
  outline: none;
  border-color: var(--color-primary);
}

.btn-full {
  width: 100%;
  padding: 0.75rem;
  font-weight: 600;
  margin-top: 1rem;
}

.error-msg {
  color: #e53e3e;
  background: rgba(229, 62, 62, 0.1);
  padding: 0.75rem;
  border-radius: var(--border-radius);
  margin-bottom: 1rem;
  font-size: 0.9rem;
}

.links {
  margin-top: 1.5rem;
  text-align: center;
}

.links a {
  color: var(--color-primary);
  text-decoration: none;
  font-size: 0.9rem;
}
</style>
