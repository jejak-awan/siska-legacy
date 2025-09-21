# 🚀 Quick Start Guide - SISKA

## 📋 Prerequisites

### System Requirements
- **PHP**: 8.3 or higher
- **Node.js**: 18.x or higher
- **MySQL**: 8.0 or higher
- **Composer**: Latest version
- **NPM**: Latest version

### Development Tools
- **IDE**: VS Code (recommended)
- **Browser**: Chrome/Firefox (latest)
- **Git**: Latest version

## ⚡ Quick Setup (5 Minutes)

### 1. Clone & Navigate
```bash
git clone https://github.com/jejak-awan/siska.git
cd siska
```

### 2. Backend Setup
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve --port=8000
```

### 3. Frontend Setup
```bash
cd frontend
npm install
npm run dev
```

### 4. Access Application
- **Frontend**: http://localhost:3000
- **Backend API**: http://localhost:8000
- **API Docs**: http://localhost:8080/api/documentation

## 🔧 Development Commands

### Backend Commands
```bash
# Start Laravel server
php artisan serve --port=8000

# Run migrations
php artisan migrate

# Seed database
php artisan db:seed

# Clear caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# Generate API docs
php artisan l5-swagger:generate
```

### Frontend Commands
```bash
# Development server
npm run dev

# Build for production
npm run build

# Preview production build
npm run preview
```

### Project Scripts
```bash
# Start all servers
./scripts/server-manager.sh start all

# Generate documentation
./scripts/generate-docs.sh --start --port=8080

# Build with Swagger
./scripts/build-with-swagger.sh production true
```

## 🎯 Default Login Credentials

### Admin Account
- **Email**: admin@siska.local
- **Password**: password
- **Role**: Admin

### Teacher Account
- **Email**: guru@siska.local
- **Password**: password
- **Role**: Guru

### Student Account
- **Email**: siswa@siska.local
- **Password**: password
- **Role**: Siswa

## 📁 Project Structure

```
siska/
├── backend/                 # Laravel Backend
│   ├── app/Http/Controllers/Api/  # API Controllers
│   ├── app/Models/               # Eloquent Models
│   ├── database/migrations/      # Database Migrations
│   └── routes/api.php            # API Routes
├── frontend/                # Vue.js Frontend
│   ├── src/components/           # Reusable Components
│   ├── src/views/                # Page Components
│   ├── src/stores/               # Pinia Stores
│   └── src/router/               # Vue Router
└── scripts/                 # Management Scripts
```

## 🔑 Key Features to Test

### 1. **Authentication**
- Login with different roles
- Role-based dashboard access
- Token-based API authentication

### 2. **User Management**
- Create/edit users
- Role assignment
- User profile management

### 3. **Student Management**
- Student registration
- Class assignment
- Academic data management

### 4. **Attendance System**
- Daily attendance tracking
- QR code attendance
- Attendance reports

### 5. **Credit Point System**
- Point assignment
- Category management
- Point history tracking

### 6. **School Profile**
- School information
- Logo upload
- Organizational structure

## 🛠️ Development Workflow

### 1. **Feature Development**
```bash
# Create new feature
php artisan make:controller Api/NewFeatureController
php artisan make:model NewFeature
php artisan make:migration create_new_feature_table
```

### 2. **Frontend Component**
```bash
# Create new component
touch frontend/src/components/NewComponent.vue
touch frontend/src/views/NewView.vue
```

### 3. **API Testing**
```bash
# Test API endpoints
curl -X GET http://localhost:8000/api/users \
  -H "Authorization: Bearer YOUR_TOKEN"
```

## 🐛 Common Issues & Solutions

### Backend Issues
```bash
# Permission denied
sudo chown -R $USER:$USER storage bootstrap/cache
chmod -R 775 storage bootstrap/cache

# Database connection
php artisan config:clear
php artisan cache:clear

# Missing dependencies
composer install --no-dev --optimize-autoloader
```

### Frontend Issues
```bash
# Node modules issues
rm -rf node_modules package-lock.json
npm install

# Build errors
npm run build --verbose

# Port conflicts
lsof -ti:3000 | xargs kill -9
```

### API Issues
```bash
# CORS problems
Check backend/config/cors.php

# Authentication issues
php artisan sanctum:install
php artisan migrate
```

## 📊 Database Schema

### Core Tables
- `users` - User accounts
- `roles` - User roles
- `siswa` - Student data
- `guru` - Teacher data
- `kelas` - Class information
- `kredit_poin` - Credit point system
- `presensi` - Attendance records

### Relationships
- Users have roles (many-to-many)
- Students belong to classes
- Teachers manage classes
- Credit points belong to students

## 🔐 Environment Configuration

### Backend (.env)
```env
APP_NAME=SISKA
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=siska
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=localhost:3000
```

### Frontend (.env)
```env
VITE_APP_NAME=SISKA
VITE_APP_URL=http://localhost:3000
VITE_API_URL=http://localhost:8000
VITE_API_DOCS_URL=http://localhost:8080
```

## 📚 Learning Resources

### Laravel
- [Laravel Documentation](https://laravel.com/docs)
- [Laravel Sanctum](https://laravel.com/docs/sanctum)
- [Laravel Eloquent](https://laravel.com/docs/eloquent)

### Vue.js
- [Vue 3 Guide](https://vuejs.org/guide/)
- [Vue Router](https://router.vuejs.org/)
- [Pinia](https://pinia.vuejs.org/)

### Tailwind CSS
- [Tailwind Documentation](https://tailwindcss.com/docs)
- [Headless UI](https://headlessui.com/)

## 🚀 Deployment

### Production Build
```bash
# Backend
composer install --no-dev --optimize-autoloader
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Frontend
npm run build
```

### Environment Setup
```bash
# Production environment
APP_ENV=production
APP_DEBUG=false
DB_CONNECTION=mysql
# ... other production settings
```

## 📞 Support

### Getting Help
1. Check this Quick Start guide
2. Read the full Agent Instructions
3. Check GitHub Issues
4. Review API Documentation

### Useful Links
- **Repository**: https://github.com/jejak-awan/siska
- **API Docs**: http://localhost:8080/api/documentation
- **Project README**: /opt/kesiswaan/README.md

---

**Happy Coding! 🎉**

*Last Updated: 2024-12-21*