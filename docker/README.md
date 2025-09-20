# Panduan Deploy dengan Docker

## 1. Prasyarat
- Docker & Docker Compose sudah terinstall

## 2. Struktur Docker
- `Dockerfile`: Build image Laravel + Vue.js
- `docker-compose.yml`: Orkestrasi service app & database
- `.env.example`: Contoh environment untuk backend

## 3. Langkah Deploy
1. Copy `.env.example` ke `backend/.env` dan sesuaikan konfigurasi
2. Jalankan perintah berikut di root project:
   ```
   docker-compose -f docker/docker-compose.yml up --build -d
   ```
3. Akses aplikasi di `http://localhost:8000` (Laravel) dan `http://localhost:5173` (Vite)
4. Untuk migrasi database:
   ```
   docker exec -it kesiswaan-app php artisan migrate --seed
   ```
5. Untuk log dan troubleshooting:
   ```
   docker-compose logs app
   docker-compose logs db
   ```

## 4. Tips
- Untuk update image, jalankan ulang dengan `--build`
- Untuk backup database, akses volume `db_data`
- Untuk development, gunakan mode debug di `.env`

---
*Panduan ini digunakan untuk memudahkan deployment dan maintenance aplikasi menggunakan Docker.*