# Dokumentasi API Sistem Kesiswaan

## Autentikasi
- POST /api/login
- POST /api/logout
- GET /api/profile

## Kesiswaan
- GET /api/siswa
- GET /api/siswa/{id}
- POST /api/siswa
- PUT /api/siswa/{id}
- DELETE /api/siswa/{id}

## Guru
- GET /api/guru
- GET /api/guru/{id}
- POST /api/guru
- PUT /api/guru/{id}
- DELETE /api/guru/{id}

## Wali Kelas
- GET /api/walikelas
- ...

## BK/Konseling
- GET /api/konseling
- ...

## OSIS
- GET /api/osis
- ...

## Ekstrakurikuler
- GET /api/ekstrakurikuler
- ...

## Program Kesiswaan
- GET /api/program
- ...

## Surat
- GET /api/surat
- ...

## Notifikasi
- GET /api/notifikasi
- ...

## Pengaturan
- GET /api/pengaturan
- ...

## Format Response
- Semua response dalam format JSON
- Error: { errors: {...} }
- Sukses: { data: {...} }

## Autentikasi & Role
- Semua endpoint dilindungi oleh Laravel Sanctum
- Role-based access: admin, guru, wali kelas, siswa, orang tua

---
*Dokumentasi ini akan terus diperbarui sesuai pengembangan API.*