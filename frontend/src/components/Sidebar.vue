<script setup>
import { RouterLink } from 'vue-router';
</script>

<template>
  <aside class="sidebar">
    <div class="logo">
      <h2>Subdactyl</h2>
    </div>
    <nav>
      <RouterLink to="/dashboard" class="nav-item">
        <span class="icon">💻</span> Servers
      </RouterLink>
      <RouterLink to="/create-server" class="nav-item">
        <span class="icon">➕</span> Create New
      </RouterLink>
      <div class="divider"></div>
      <RouterLink to="/admin" class="nav-item admin-link">
        <span class="icon">🛡️</span> Admin
      </RouterLink>
    </nav>
    <div class="user-info">
      <button @click="$emit('logout')" class="btn-logout">Logout</button>
    </div>
  </aside>
</template>

<style scoped>
.sidebar {
  width: 250px;
  background-color: var(--color-surface);
  height: 100vh;
  display: flex;
  flex-direction: column;
  border-right: 1px solid #333;
  padding: 1rem;
}

.logo h2 {
  margin: 0 0 2rem 0;
  color: white;
  text-align: center;
}

.nav-item {
  display: flex;
  align-items: center;
  padding: 0.75rem 1rem;
  color: var(--color-text-muted);
  text-decoration: none;
  border-radius: var(--border-radius);
  margin-bottom: 0.5rem;
  transition: all 0.2s;
}

.nav-item:hover, .nav-item.router-link-active {
  background-color: rgba(255, 255, 255, 0.05);
  color: white;
}

.icon {
  margin-right: 0.75rem;
}

.divider {
  height: 1px;
  background-color: #333;
  margin: 1rem 0;
}

.user-info {
  margin-top: auto;
  padding-top: 1rem;
  border-top: 1px solid #333;
}

.btn-logout {
  width: 100%;
  background: transparent;
  border: 1px solid #e53e3e;
  color: #e53e3e;
}

.btn-logout:hover {
  background: #e53e3e;
  color: white;
}
</style>
