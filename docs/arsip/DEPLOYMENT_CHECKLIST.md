# Deployment Checklist

## 1. Server & Environment
- [ ] Pilih server (VPS/cloud/on-premise)
- [ ] Install OS, web server, PHP, Composer, Node.js, npm/pnpm
- [ ] Install database (MySQL/PostgreSQL)
- [ ] Setup firewall & security

## 2. Project Setup
- [ ] Clone repository ke server
- [ ] Jalankan composer install (backend)
- [ ] Jalankan npm/pnpm install (frontend)
- [ ] Copy & konfigurasi .env
- [ ] Generate app key
- [ ] Jalankan migrasi & seeder
- [ ] Build frontend

## 3. Konfigurasi Web Server & Domain
- [ ] Setup domain & DNS
- [ ] Install SSL & konfigurasi HTTPS
- [ ] Update konfigurasi web server

## 4. Backup & Monitoring
- [ ] Setup backup otomatis database & file
- [ ] Integrasi monitoring (uptime, error log, audit log)
- [ ] Setup notifikasi error

## 5. Broadcasting & Notifikasi
- [ ] Setup Pusher/Redis
- [ ] Konfigurasi Laravel Notifications (mail, SMS, WhatsApp)
- [ ] Uji notifikasi real-time

## 6. Finalisasi & Dokumentasi
- [ ] Update deployment log
- [ ] Dokumentasi proses deployment
- [ ] Siapkan user manual & support

---
*Checklist ini digunakan untuk memastikan proses deployment berjalan lancar dan aman.*