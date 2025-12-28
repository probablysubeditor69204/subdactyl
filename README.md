# 🦖 Subdactyl Dashboard

**Subdactyl** is a modern, dark-themed, free server creation dashboard for Pterodactyl. It allows users to register, login, and deploy their own servers within admin-defined limits.

![Subdactyl Dashboard](https://via.placeholder.com/800x400?text=Subdactyl+Dashboard)

## ✨ Features
- **Modern UI**: Built with Vue 3 and a clean, dark aesthetic.
- **Automated Onboarding**: Setup wizard to configure Pterodactyl API keys.
- **User Sync**: Automatically creates Pterodactyl users upon registration.
- **Free Server System**: Users can create servers (limits enforced by admin).
- **Admin Control**: Sync Nodes/Eggs and manage global application settings.

---

## 🚀 Easy Installation (Docker)

The recommended way to install Subdactyl is using Docker Compose.

### Prerequisites
- Docker & Docker Compose installed on your host machine.
- A running Pterodactyl Panel instance.

### Steps
1. **Clone the repository:**
   ```bash
   git clone https://github.com/probablysubeditor69204/subdactyl.git
   cd subdactyl
   ```

2. **Start the application:**
   ```bash
   docker-compose up -d --build
   ```

3. **Access the Dashboard:**
   - Open `http://localhost` (or your server's IP) in your browser.
   - You will be greeted by the **Setup Wizard**.
   - Enter your Pterodactyl URL and Application API Key to finish installation.

---

## 🛠️ Manual Installation

If you prefer to host without Docker, follow these steps.

### Prerequisites
- PHP 8.2+
- Composer
- Node.js 18+ & NPM
- MySQL / MariaDB
- Nginx

### 1. Backend Setup (Laravel)
```bash
cd backend

# Install dependencies
composer install

# Configure environment
cp .env.example .env
nano .env  # Update DB_ settings

# Generate key & migrate
php artisan key:generate
php artisan migrate

# Serve (Dev) or configure PHP-FPM (Prod)
php artisan serve
```

### 2. Frontend Setup (Vue 3)
```bash
cd frontend

# Install dependencies
npm install

# Build for production
npm run build
```
Dist files will be in `frontend/dist`.

### 3. Nginx Configuration
A sample Nginx configuration is provided in `nginx/subdactyl.conf`.
1. Copy it to `/etc/nginx/sites-available/subdactyl`.
2. Update paths (`root`, `alias`) and `server_name` to match your environment.
3. Reload Nginx: `sudo systemctl reload nginx`.

---

## 🤝 Contributing
Contributions are welcome! Please feel free to submit a Pull Request.

## 📄 License
MIT
