# Modul Pengaturan Aplikasi

## Tujuan
Modul ini digunakan untuk mengelola semua pengaturan sistem, baik umum maupun khusus, agar aplikasi dapat dikonfigurasi sesuai kebutuhan sekolah.

## Struktur Tabel
- `pengaturans`: Menyimpan semua setting aplikasi secara dinamis
  - `kategori`: Kategori pengaturan (general, tahun_ajaran, backup, dsb)
  - `nama`: Nama setting
  - `deskripsi`: Penjelasan setting
  - `tipe`: Jenis data (string, integer, boolean, json)
  - `nilai`: Value setting
  - `is_active`: Status aktif

## Contoh Kategori Pengaturan
- **General**: Nama sekolah, alamat, logo, kontak, kepala sekolah, timezone, bahasa
- **Tahun Ajaran/Semester**: Tahun ajaran aktif, semester aktif, tanggal mulai/akhir
- **Profile Pengguna**: Default foto, password policy
- **Backup/Restore**: Jadwal backup otomatis, lokasi file backup
- **Notifikasi**: SMS gateway, email server, WhatsApp API
- **Digital Signature**: Path file, status aktif
- **Absensi**: Mode absensi (QR/GPS/manual), jam masuk/keluar
- **Poin**: Batas poin, auto reward
- **Surat Digital**: Template surat, workflow approval

## Best Practice
- Semua pengaturan dapat diubah tanpa edit kode
- Validasi tipe data dan value
- Audit log perubahan pengaturan
- Integrasi dengan UI admin

## Saran Pengembangan
- Tambahkan controller dan UI untuk CRUD pengaturan
- Integrasi backup/restore dengan Laravel command
- Dokumentasi update setiap penambahan setting baru
