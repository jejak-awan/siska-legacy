# 🎓 SISKA - Sistem Manajemen Kesiswaan Terintegrasi

[![Laravel](https://img.shields.io/badge/Laravel-11.35-red.svg)](https://laravel.com)
[![Vue.js](https://img.shields.io/badge/Vue.js-3.5.21-green.svg)](https://vuejs.org)
[![PHP](https://img.shields.io/badge/PHP-8.3+-blue.svg)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://mysql.com)

**SISKA** adalah sistem manajemen kesiswaan terintegrasi yang dirancang untuk mengelola seluruh aspek kesiswaan sekolah dengan standar nasional Indonesia. Sistem ini menyediakan solusi komprehensif untuk manajemen siswa, guru, presensi, penilaian karakter, ekstrakurikuler, dan OSIS.

## 🌟 Fitur Utama

### 👥 **Manajemen Pengguna Multi-Role**
- **Admin**: Kontrol penuh sistem dan konfigurasi
- **Guru**: Manajemen siswa dan penilaian akademik
- **Siswa**: Akses profil dan kemajuan akademik
- **Wali Kelas**: Monitoring kelas dan komunikasi orang tua
- **BK**: Bimbingan konseling dan penilaian karakter
- **OSIS**: Manajemen organisasi dan kegiatan
- **Ekstrakurikuler**: Koordinasi kegiatan ekstrakurikuler
- **Orang Tua**: Monitoring kemajuan anak

### 📚 **Manajemen Akademik**
- **Data Siswa**: Registrasi, profil, dan data akademik
- **Data Guru**: Manajemen tenaga pendidik
- **Kelas & Jadwal**: Pengaturan kelas dan jadwal pelajaran
- **Tahun Ajaran**: Manajemen periode akademik
- **Mata Pelajaran**: Konfigurasi kurikulum

### 📊 **Sistem Presensi**
- **Presensi Harian**: Pencatatan kehadiran siswa
- **QR Code**: Presensi dengan teknologi QR
- **Laporan Presensi**: Analisis kehadiran dan ketidakhadiran
- **Notifikasi**: Pemberitahuan ke orang tua

### ⭐ **Sistem Kredit Poin**
- **Penilaian Perilaku**: Sistem poin untuk perilaku siswa
- **Kategori Poin**: Berbagai kategori penilaian
- **Riwayat Poin**: Tracking perubahan poin
- **Pencapaian**: Sistem reward dan recognition

### 🎯 **Penilaian Karakter**
- **Asesmen Multidimensi**: Penilaian karakter komprehensif
- **Indikator Karakter**: Berbagai aspek penilaian
- **Progress Tracking**: Monitoring perkembangan karakter
- **Laporan Karakter**: Analisis perkembangan siswa

### 🏃 **Ekstrakurikuler & OSIS**
- **Manajemen Kegiatan**: Organisasi kegiatan ekstrakurikuler
- **Keanggotaan**: Manajemen anggota OSIS dan ekstrakurikuler
- **Jadwal Kegiatan**: Koordinasi jadwal dan tempat
- **Pencapaian**: Tracking prestasi dan pencapaian

### 🏫 **Profil Sekolah**
- **Informasi Sekolah**: Data lengkap sekolah
- **Logo & Branding**: Manajemen identitas sekolah
- **Struktur Organisasi**: Hierarki dan jabatan
- **Sejarah & Visi Misi**: Dokumentasi sekolah

### 📱 **Komunikasi & Notifikasi**
- **WhatsApp Integration**: Notifikasi via WhatsApp
- **Sistem Notifikasi**: Pemberitahuan real-time
- **Komunikasi Orang Tua**: Kanal komunikasi dengan wali
- **Pengumuman**: Sistem broadcast pesan

### 📈 **Dashboard & Laporan**
- **Dashboard Role-based**: Tampilan sesuai peran pengguna
- **Analytics**: Statistik dan analisis data
- **Laporan Komprehensif**: Berbagai jenis laporan
- **Export Data**: Ekspor data dalam berbagai format

## 🚀 Quick Start

### Prerequisites
- PHP 8.3+
- Node.js 18+
- MySQL 8.0+
- Composer
- NPM

### Installation
```bash
# Clone repository
git clone https://github.com/jejak-awan/siska.git
cd siska

# Backend setup
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed

# Frontend setup
cd ../frontend
npm install
npm run dev

# Start servers
cd ..
./scripts/server-manager.sh start all
```

### Access Application
- **Frontend**: http://localhost:3000
- **Backend API**: http://localhost:8000
- **API Documentation**: http://localhost:8080/api/documentation

### Default Login
- **Admin**: admin@siska.local / password
- **Guru**: guru@siska.local / password
- **Siswa**: siswa@siska.local / password

## 🛠️ Technology Stack

### Backend
- **Laravel 11.35** - PHP Framework
- **Laravel Sanctum** - API Authentication
- **MySQL 8.0** - Database
- **Swagger/OpenAPI** - API Documentation
- **Intervention Image** - Image Processing
- **PhpSpreadsheet** - Excel Processing

### Frontend
- **Vue.js 3.5.21** - JavaScript Framework
- **Vite** - Build Tool
- **Tailwind CSS** - Styling Framework
- **Pinia** - State Management
- **Axios** - HTTP Client
- **CKEditor 5** - Rich Text Editor
- **Chart.js** - Data Visualization

## 📱 User Interface

### Dashboard
- **Role-based Dashboard**: Tampilan khusus sesuai peran pengguna
- **Real-time Statistics**: Statistik terkini dan analisis
- **Quick Actions**: Akses cepat ke fitur utama
- **Notification Center**: Pusat notifikasi dan pengumuman

### Responsive Design
- **Mobile-First**: Optimized untuk perangkat mobile
- **Tablet Support**: Tampilan optimal untuk tablet
- **Desktop Experience**: Pengalaman lengkap di desktop
- **Cross-browser**: Kompatibel dengan browser modern

## 🔐 Security Features

### Authentication & Authorization
- **Multi-role Authentication**: Sistem login berdasarkan peran
- **Token-based Security**: Keamanan berbasis token
- **Role-based Access Control**: Kontrol akses berdasarkan peran
- **Session Management**: Manajemen sesi yang aman

### Data Protection
- **Input Validation**: Validasi input yang ketat
- **SQL Injection Prevention**: Perlindungan dari SQL injection
- **XSS Protection**: Perlindungan dari cross-site scripting
- **CSRF Protection**: Perlindungan dari CSRF attacks

## 📊 Data Management

### Import/Export
- **Excel Import**: Import data dari file Excel
- **Data Export**: Ekspor data dalam berbagai format
- **Template Download**: Template untuk import data
- **Data Validation**: Validasi data sebelum import

### Backup & Recovery
- **Database Backup**: Backup otomatis database
- **File Backup**: Backup file dan media
- **Recovery System**: Sistem pemulihan data
- **Version Control**: Kontrol versi data

## 🌐 Integration

### WhatsApp Integration
- **Notification Service**: Notifikasi via WhatsApp
- **Bulk Messaging**: Pengiriman pesan massal
- **Template Messages**: Template pesan yang dapat dikustomisasi
- **Delivery Status**: Status pengiriman pesan

### API Integration
- **RESTful API**: API yang mengikuti standar REST
- **API Documentation**: Dokumentasi API yang lengkap
- **Rate Limiting**: Pembatasan rate API
- **API Versioning**: Versioning untuk kompatibilitas

## 📈 Performance

### Optimization
- **Database Indexing**: Optimasi database dengan indexing
- **Caching System**: Sistem cache untuk performa
- **Lazy Loading**: Loading komponen yang efisien
- **Bundle Optimization**: Optimasi bundle frontend

### Monitoring
- **Performance Metrics**: Metrik performa aplikasi
- **Error Tracking**: Tracking error dan debugging
- **Usage Analytics**: Analisis penggunaan sistem
- **Health Checks**: Monitoring kesehatan sistem

## 🎯 Target Users

### Sekolah
- **SD/MI**: Sekolah dasar dan madrasah ibtidaiyah
- **SMP/MTs**: Sekolah menengah pertama dan madrasah tsanawiyah
- **SMA/MA**: Sekolah menengah atas dan madrasah aliyah
- **SMK**: Sekolah menengah kejuruan

### Stakeholders
- **Kepala Sekolah**: Monitoring dan evaluasi sekolah
- **Guru**: Manajemen pembelajaran dan siswa
- **Siswa**: Akses informasi akademik dan non-akademik
- **Orang Tua**: Monitoring kemajuan anak
- **Admin**: Manajemen sistem dan data

## 📞 Support & Documentation

### Documentation
- **User Manual**: Panduan penggunaan untuk setiap role
- **API Documentation**: Dokumentasi API yang lengkap
- **Developer Guide**: Panduan untuk developer
- **FAQ**: Frequently Asked Questions

### Support
- **GitHub Issues**: Laporan bug dan feature request
- **Email Support**: Dukungan via email
- **Community Forum**: Forum komunitas pengguna
- **Training Materials**: Materi pelatihan dan tutorial

## 🤝 Contributing

Kami menyambut kontribusi dari komunitas! Silakan lihat [Contributing Guidelines](.github/CONTRIBUTING.md) untuk informasi lebih lanjut.

### Development
- Fork repository
- Create feature branch
- Make changes
- Submit pull request

### Reporting Issues
- Gunakan GitHub Issues
- Berikan detail yang jelas
- Sertakan screenshot jika perlu
- Jelaskan langkah reproduksi

## 📄 License

Proyek ini dilisensikan di bawah [MIT License](LICENSE).

## 🙏 Acknowledgments

- **Laravel Community** - Framework dan ekosistem yang luar biasa
- **Vue.js Community** - Framework frontend yang powerful
- **Tailwind CSS** - Utility-first CSS framework
- **Contributors** - Semua kontributor yang telah membantu

---

**SISKA** - Membangun masa depan pendidikan Indonesia yang lebih baik! 🎓✨

*Dibuat dengan ❤️ untuk pendidikan Indonesia*
