# Deployment & Environment Setup Guide

## 1. Persiapan Server
- Pilih server (VPS/cloud/on-premise) sesuai kebutuhan
- Install OS (Linux/Windows), update package
- Install web server (Nginx/Apache)
- Install PHP 8.2+, Composer, Node.js, npm/pnpm
- Install database (MySQL 8.0/PostgreSQL 15)

## 2. Setup Project
- Clone repository ke server
- Jalankan `composer install` untuk backend
- Jalankan `npm install` atau `pnpm install` untuk frontend
- Copy file `.env.example` ke `.env` dan sesuaikan konfigurasi (database, mail, broadcasting, dsb)
- Generate app key: `php artisan key:generate`
- Jalankan migrasi: `php artisan migrate --seed`
- Build frontend: `npm run build` atau `pnpm build`

## 3. Konfigurasi SSL & Domain
- Setup domain dan DNS
- Install SSL (Let's Encrypt/Cloudflare)
- Update konfigurasi web server untuk HTTPS

## 4. Konfigurasi Backup & Monitoring
- Setup backup otomatis database dan file
- Integrasi monitoring (uptime, error log, audit log)
- Setup notifikasi error ke email/WhatsApp

## 5. Konfigurasi Broadcasting & Notifikasi
- Setup Pusher/Redis untuk broadcasting
- Konfigurasi Laravel Notifications (mail, SMS, WhatsApp)
- Uji notifikasi real-time

## 6. Security & Maintenance
- Update permission folder/file
- Setup firewall dan security best practice
- Update aplikasi dan dependency secara berkala

## 7. Dokumentasi & Support
- Update deployment log dan dokumentasi
- Siapkan user manual dan support channel

---
*Panduan ini digunakan untuk memastikan proses deployment berjalan lancar, aman, dan siap digunakan oleh sekolah.*