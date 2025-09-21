# README - Sistem Manajemen Kesiswaan Terintegrasi

## 🎯 Gambaran Umum
Sistem manajemen kesiswaan yang komprehensif dan terintegrasi, dirancang sesuai standar nasional Indonesia dengan struktur modular yang terorganisir.

### **📊 PROJECT STATUS:**
- **Current Phase**: Active Development - Core Features Implemented
- **Overall Progress**: 75% COMPLETE (IN DEVELOPMENT)
- **Last Updated: 2025-09-20 15:06:01

### **✅ IMPLEMENTED FEATURES:**
- ✅ **Backend Foundation**: Laravel 11.35 with 20+ Models implemented
- ✅ **Frontend Structure**: Vue.js 3.5.21 with comprehensive component library
- ✅ **Database Models**: User, Guru, Siswa, Presensi, KreditPoin, Konseling, dll
- ✅ **Authentication**: Laravel Sanctum integration
- ✅ **UI Components**: Tailwind CSS + Headless UI components
- ✅ **Form Validation**: VeeValidate + Yup integration
- ✅ **Charts & Analytics**: Chart.js integration
- ✅ **File Management**: Image compression, PDF generation
- ✅ **Real-time Features**: Laravel Echo + Pusher setup
- ✅ **QR Code System**: HTML5-QRCode scanner implementation
- ✅ **Rich Text Editor**: Quill editor integration
- ✅ **Docker Support**: Complete Docker configuration

### **📋 Modul Utama:**
- **Dashboard**: Role-based dashboard untuk semua pengguna
- **Manajemen Pengguna**: Pengguna, Guru, Siswa, Tendik, Role
- **Kesiswaan**: Program, Agenda, Laporan
- **Manajemen Surat**: Sistem surat menyurat digital
- **Presensi**: Sistem presensi real-time dengan QR code
- **Kredit Poin Siswa**: Sistem reward & punishment terintegrasi
- **Bimbingan Konseling**: Konseling, home visit, tindak lanjut otomatis
- **Wali Kelas**: Manajemen siswa per kelas, koordinasi dengan BK
- **OSIS**: Manajemen organisasi siswa, tracking kepemimpinan
- **Ekstrakurikuler**: Kegiatan ekstrakurikuler, pencapaian, sertifikasi
- **Piket**: Sistem piket guru dan kebersihan siswa
- **Data Referensi**: Data sekolah, kelas, mata pelajaran, ekstrakurikuler, humas
- **Pengaturan**: Konfigurasi sistem

## 🔄 Alur Kerja Sistem

### **Sistem Kredit Poin Terintegrasi**
```
Presensi → Kredit Poin → Auto Trigger → BK (Counseling) 
    ↓           ↓              ↓
Dashboard Analytics → WhatsApp Notifications → Parent Alerts
    ↓
OSIS/Ekskul (Positive Points) → Leadership Tracking
```

### **Kriteria Rujukan Otomatis:**
- 🟡 **10-25 poin**: Peringatan tertulis
- 🟠 **26-50 poin**: Rujukan ke BK
- 🔴 **51-75 poin**: Panggilan orang tua + BK intensif  
- ⚫ **>75 poin**: Tindakan khusus/skorsing

### **Flow Presensi:**
```
Siswa Scan QR → Validasi GPS → Presensi Tercatat → Auto Notifikasi
```

### **Flow Kredit Poin:**
```
Guru Input Poin → Validasi → Update Total → Cek Threshold → Auto Action
```

## 🛠️ Technology Stack

### Backend (Laravel 11 LTS)
- **Framework**: Laravel 11.35 (PHP 8.3+)
- **Database**: MySQL 8.0 LTS dengan skema sesuai format data Indonesia
- **Authentication**: Laravel Sanctum 4.1 dengan JWT tokens
- **Real-time**: Laravel Broadcasting + Pusher/Redis
- **QR Code**: SimpleSoftwareIO/simple-qrcode 4.2 untuk presensi
- **Notifications**: WhatsApp Business API + Laravel Notifications
- **Excel Import/Export**: PhpSpreadsheet 2.4 untuk data management
- **Image Processing**: Intervention Image 3.11
- **HTTP Client**: Guzzle HTTP 7.9
- **Testing**: PHPUnit 11.5 + Laravel Testing

### Frontend (Vue.js 3 LTS)
- **Framework**: Vue.js 3.5.21 + Composition API
- **Build Tool**: Vite 5.4.20 (Modern & Fast)
- **UI Framework**: Tailwind CSS 3.4.17 + Headless UI 1.7.23
- **State Management**: Pinia 3.0.3
- **Routing**: Vue Router 4.5.1
- **Form Validation**: VeeValidate 4.15.1 + Yup 1.7.0
- **Charts**: Chart.js 4.5.0 + Vue-ChartJS 5.3.2
- **QR Code Scanner**: HTML5-QRCode 2.3.8
- **Rich Text Editor**: Quill 2.0.3 + Vue-Quill-Editor 3.0.6
- **PDF Generation**: jsPDF 3.0.3 + jsPDF-AutoTable 5.0.2
- **Image Compression**: Image-Compression 0.0.6
- **Real-time**: Laravel Echo 2.2.0 + Pusher-JS 8.4.0
- **Icons**: Lucide Vue Next 0.544.0 + Heroicons 2.2.0
- **Notifications**: Vue Toast Notification 3.1.3
- **Date Picker**: Vue Datepicker 11.0.2
- **Utilities**: VueUse 13.9.0 + Class Variance Authority 0.7.1
- **Package Manager**: npm

## 📁 Project Structure

```
kesiswaan/
├── 📂 app/                     # Laravel Models (Root Level)
│   ├── 📂 Http/Controllers/    # API Controllers
│   └── 📂 Models/              # Core Models
│       ├── CharacterAssessment.php
│       ├── CharacterAssessmentHistory.php
│       ├── CharacterDimension.php
│       └── CharacterIndicator.php
├── 📂 backend/                 # Laravel 11 API Backend
│   ├── 📂 app/
│   │   ├── 📂 Http/Controllers/
│   │   │   ├── 📂 Api/         # API Controllers
│   │   │   └── 📂 Public/      # Public Controllers
│   │   ├── 📂 Models/          # Database Models
│   │   │   ├── User.php        # Core user model
│   │   │   ├── Guru.php        # Data guru lengkap
│   │   │   ├── Siswa.php       # Data siswa lengkap
│   │   │   ├── OrangTua.php    # Data orang tua
│   │   │   ├── Presensi.php    # Sistem presensi
│   │   │   ├── KreditPoin.php  # Sistem kredit poin
│   │   │   ├── Konseling.php   # Bimbingan konseling
│   │   │   ├── HomeVisit.php   # Home visit BK
│   │   │   ├── Ekstrakurikuler.php # Ekstrakurikuler
│   │   │   ├── OSISKegiatan.php # Kegiatan OSIS
│   │   │   ├── Kelas.php       # Master kelas
│   │   │   ├── TahunAjaran.php # Master tahun ajaran
│   │   │   ├── Role.php        # Role management
│   │   │   ├── Notifikasi.php  # Notification system
│   │   │   ├── WhatsAppLog.php # WhatsApp integration
│   │   │   └── 📂 Public/      # Public models
│   │   ├── 📂 Services/        # Business logic services
│   │   ├── 📂 Notifications/   # Notification classes
│   │   └── 📂 Rules/           # Validation rules
│   ├── 📂 database/            # Database migrations & seeders
│   ├── 📂 routes/              # API routes
│   ├── 📂 config/              # Configuration files
│   ├── 📂 public/              # Public assets
│   ├── 📂 resources/           # Views & assets
│   ├── 📂 storage/             # File storage
│   ├── 📂 tests/               # Test files
│   ├── 📂 vendor/              # Composer dependencies
│   ├── composer.json           # PHP dependencies
│   └── artisan                 # Laravel CLI
├── 📂 frontend/                # Vue.js 3 SPA
│   ├── 📂 src/
│   │   ├── 📂 components/      # Vue components
│   │   │   ├── 📂 character/   # Character assessment components
│   │   │   ├── 📂 content/     # Content management
│   │   │   ├── 📂 editor/      # Rich text editor
│   │   │   ├── 📂 gallery/     # Image gallery
│   │   │   ├── 📂 layout/      # Layout components
│   │   │   ├── 📂 modals/      # Modal dialogs
│   │   │   ├── 📂 public/      # Public components
│   │   │   ├── 📂 ui/          # UI components
│   │   │   └── 📂 upload/      # File upload components
│   │   ├── 📂 views/           # Page views
│   │   │   ├── 📂 analytics/   # Analytics dashboard
│   │   │   ├── 📂 auth/        # Authentication pages
│   │   │   ├── 📂 backup-restore/ # Backup & restore
│   │   │   ├── 📂 bk/          # Bimbingan konseling
│   │   │   ├── 📂 content/     # Content management
│   │   │   ├── 📂 dashboard/   # Main dashboard
│   │   │   ├── 📂 data-pegawai/ # Employee data
│   │   │   ├── 📂 data-referensi/ # Reference data
│   │   │   ├── 📂 data-sekolah/ # School data
│   │   │   ├── 📂 ekstrakurikuler/ # Extracurricular
│   │   │   ├── 📂 guru/        # Teacher management
│   │   │   ├── 📂 hak-akses/   # Access rights
│   │   │   ├── 📂 jadwal/      # Schedule management
│   │   │   ├── 📂 kelas/       # Class management
│   │   │   ├── 📂 konfigurasi-karakter/ # Character config
│   │   │   ├── 📂 kredit-poin/ # Credit points
│   │   │   ├── 📂 laporan/     # Reports
│   │   │   ├── 📂 mata-pelajaran/ # Subjects
│   │   │   ├── 📂 notifications/ # Notifications
│   │   │   ├── 📂 osis/        # OSIS management
│   │   │   ├── 📂 pengaturan/  # Settings
│   │   │   ├── 📂 presensi/    # Attendance
│   │   │   ├── 📂 profil-sekolah/ # School profile
│   │   │   ├── 📂 profile/     # User profile
│   │   │   ├── 📂 program-kesiswaan/ # Student programs
│   │   │   ├── 📂 public/      # Public pages
│   │   │   ├── 📂 referensi-data/ # Reference data
│   │   │   ├── 📂 siswa/       # Student management
│   │   │   ├── 📂 struktur-organisasi/ # Organization structure
│   │   │   ├── 📂 tahun-ajaran/ # Academic year
│   │   │   └── 📂 users/       # User management
│   │   ├── 📂 stores/          # Pinia state management
│   │   ├── 📂 services/        # API services
│   │   ├── 📂 router/          # Vue Router
│   │   ├── 📂 composables/     # Vue composables
│   │   ├── 📂 utils/           # Utility functions
│   │   ├── 📂 lib/             # Library configurations
│   │   ├── 📂 plugins/         # Vue plugins
│   │   ├── 📂 assets/          # Static assets
│   │   ├── App.vue             # Main app component
│   │   └── main.js             # App entry point
│   ├── 📂 dist/                # Built assets
│   ├── 📂 node_modules/        # NPM dependencies
│   ├── package.json            # NPM dependencies
│   ├── vite.config.js          # Vite configuration
│   ├── tailwind.config.js      # Tailwind CSS config
│   └── postcss.config.js       # PostCSS config
├── 📂 docs/                    # Documentation
│   ├── 📂 arsip/               # Archived documentation
│   ├── 📂 Components/          # Component documentation
│   ├── 📂 Dashboard/           # Dashboard documentation
│   ├── 📂 data format/         # Excel templates
│   └── 📂 konten/              # Content documentation
├── 📂 docker/                  # Docker configuration
│   ├── docker-compose.yml      # Docker services
│   └── README.md               # Docker documentation
├── 📂 backups/                 # Backup files
├── 📂 ssl/                     # SSL certificates
├── 📂 tests/                   # Test files
├── 📂 .github/                 # GitHub workflows & docs
│   ├── QUICK_START.md          # Quick start guide
│   └── agent-instructions.md   # Agent instructions
└── 📄 README.md                # This file
```

## 🚀 Quick Start

### Prerequisites
- **PHP 8.3+** (Laravel 11 requirement)
- **Composer** (PHP dependency manager)
- **Node.js 18+** (Vue.js 3 requirement)
- **MySQL 8.0** (Database engine)
- **Redis** (Caching & real-time features)
- **Git** (Version control)

### Installation

1. **Clone Repository**
```bash
git clone https://github.com/jejak-awan/siska.git
cd siska
```

2. **Setup Backend (Laravel)**
```bash
cd backend
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
```

3. **Setup Frontend (Vue.js)**
```bash
cd frontend
npm install
image.pngnpm run dev  # Runs on http://localhost:3000
```

4. **Development Servers**
```bash
# Backend API (Laravel)
cd backend && php artisan serve --port=8000
# Access: http://localhost:8000/api/

# Alternative Swagger Documentation
cd backend && php artisan serve --port=8080
# Access: http://localhost:8080/api/documentation

# Frontend Component Documentation
cd frontend && npm run dev
# Access: http://localhost:6006
```

5. **Production Build (Nginx Native)**
```bash
# Build frontend for production
cd frontend
npm run build

# Nginx configuration already set up at /etc/nginx/sites-available/siska
# Frontend: https://siska.local (serves static files)
# Backend API: https://siska.local/api/ (proxy to Laravel port 8000)
# Swagger UI: https://siska.local/api/documentation
```

5. **Configure Environment**
```env
# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=kesiswaan
DB_USERNAME=root
DB_PASSWORD=

# Redis Configuration
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# WhatsApp Configuration
WHATSAPP_API_URL=your_whatsapp_api_url
WHATSAPP_API_TOKEN=your_whatsapp_token

# Pusher Configuration (for real-time features)
PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_app_key
PUSHER_APP_SECRET=your_pusher_app_secret
```

## 👥 User Roles & Permissions

### **Dashboard Khusus per Role:**

| Role | Dashboard Features | Permissions | Access Level |
|------|-------------------|-------------|--------------|
| **Admin** | System overview, user management, analytics | Full access to all modules | ✅ Complete |
| **Guru** | Kelas yang diampu, presensi siswa, input kredit poin | Manage class, input points, view schedule | 📝 Manage |
| **Siswa** | Presensi pribadi, kredit poin, jadwal, notifikasi | View own data, view schedule | 👁️ View |
| **Wali Kelas** | Kelas yang diampu, presensi kelas, koordinasi BK | Manage class, coordinate with BK | 📝 Manage |
| **BK** | Siswa rujukan, jadwal konseling, home visit | Manage counseling, home visit | 📝 Manage |
| **OSIS** | Struktur OSIS, kegiatan, anggota, laporan | Manage OSIS activities | 📝 Manage |
| **Ekstrakurikuler** | Daftar ekskul, anggota, jadwal, prestasi | Manage extracurricular | 📝 Manage |
| **Orang Tua** | Data anak, presensi anak, kredit poin anak | View child's data | 👁️ View |

### **Permission Matrix:**

| Module | Admin | Guru | Siswa | Wali Kelas | BK | OSIS | Ekstrakurikuler | Orang Tua |
|--------|-------|------|-------|------------|----|----- |----------------|-----------|
| **Dashboard** | ✅ Full | ✅ Role-based | ✅ Personal | ✅ Class | ✅ Counseling | ✅ OSIS | ✅ Extracurricular | ✅ Child |
| **Presensi** | ✅ Manage | 📝 Input | 👁️ View | 📝 Manage Class | 👁️ View | 👁️ View | 👁️ View | 👁️ Child |
| **Kredit Poin** | ✅ Manage | 📝 Input | 👁️ View | 📝 Manage Class | 📝 Refer | 📝 Input | 📝 Input | 👁️ Child |
| **Konseling** | ✅ Manage | 📝 Refer | 📅 Schedule | 👁️ Coordinate | ✅ Manage | 👁️ View | 👁️ View | 📅 Appointment |
| **OSIS** | ✅ Manage | 👁️ View | 🗳️ Participate | 👁️ View | 👁️ View | ✅ Manage | 👁️ View | 👁️ View |
| **Ekstrakurikuler** | ✅ Manage | 👁️ View | 📝 Register | 👁️ View | 👁️ View | 👁️ View | ✅ Manage | 👁️ Child |
| **Piket** | ✅ Manage | 📝 Report | 📝 Clean | 📝 Manage | 👁️ View | 👁️ View | 👁️ View | 👁️ View |
| **User Management** | ✅ Manage | 👁️ View | 👁️ Profile | 👁️ View | 👁️ View | 👁️ View | 👁️ View | 👁️ Profile |

## 🎯 Key Features

### ✅ **Core Features (Ready for Implementation)**
- [x] **Database Schema**: 15+ tables sesuai format data Indonesia
- [x] **Multi-role Authentication**: 8 role types dengan permissions
- [x] **Role-based Dashboard**: Unified dashboard dengan role-specific content
- [x] **Presensi System**: QR code + GPS validation
- [x] **Kredit Poin System**: Automated threshold-based actions
- [x] **WhatsApp Notifications**: Business API integration
- [x] **Excel Import/Export**: Template-based data management
- [x] **Real-time Updates**: WebSocket + Redis caching

### 🔄 **Integration Features**
- [x] **Presensi → Kredit Poin**: Auto-trigger based on attendance
- [x] **Kredit Poin → BK**: Auto-referral based on thresholds
- [x] **OSIS → Kredit Poin**: Leadership points integration
- [x] **Ekstrakurikuler → Kredit Poin**: Achievement points
- [x] **Cross-Module**: Unified student profile across all modules
- [x] **Notifications**: Multi-channel alerts (WhatsApp, Email, Push)

### 🚫 **Excluded Features**
- ❌ Payment/billing system
- ❌ Financial reporting
- ❌ Fee collection
- ❌ Payment gateways

## 📊 Dashboard Features

### **Admin Dashboard**
- System overview & statistics
- Multi-module analytics
- User management
- System configuration

### **Guru Dashboard**
- Kelas yang diampu
- Presensi siswa
- Input kredit poin
- Jadwal mengajar

### **Siswa Dashboard**
- Presensi pribadi
- Kredit poin terkini
- Jadwal kegiatan
- Notifikasi

### **Wali Kelas Dashboard**
- Kelas yang diampu
- Presensi kelas
- Kredit poin kelas
- Koordinasi dengan BK

### **BK Dashboard**
- Siswa rujukan
- Jadwal konseling
- Home visit
- Laporan perkembangan

### **OSIS Dashboard**
- Struktur organisasi
- Kegiatan OSIS
- Anggota OSIS
- Laporan kegiatan

### **Ekstrakurikuler Dashboard**
- Daftar ekstrakurikuler
- Anggota per ekstrakurikuler
- Jadwal kegiatan
- Prestasi

## 🔧 Development

### Run Development Server
```bash
# Backend
cd backend && php artisan serve

# Frontend  
cd frontend && npm run dev
```

### Run Tests
```bash
# Backend tests
cd backend && php artisan test

# Frontend tests
cd frontend && npm run test
```

### Docker Development
```bash
# Start all services
docker-compose up -d

# View logs
docker-compose logs -f

# Stop services
docker-compose down
```

## 📖 Documentation

### **🔧 Automated Documentation Tools**

#### **API Documentation (Swagger/OpenAPI)**
- **Swagger UI**: `http://localhost:8080/api/documentation` (Primary documentation port)
- **Swagger UI (Alt)**: `http://localhost:8000/api/documentation` (Backend integrated)
- **OpenAPI Spec**: `http://localhost:8000/api/docs` (JSON format)
- **Features**: Interactive API testing, Request/Response examples, Authentication testing

#### **Frontend Component Documentation**
- **Component Documentation**: Removed Storybook, using inline component documentation
- **Features**: Inline component documentation, JSDoc comments, Component examples

#### **Quick Start Documentation**
```bash
# Development Environment
# API Documentation (Primary)
cd backend && php artisan serve --port=8000
# Visit: http://localhost:8000/api/documentation

# API Documentation (Alternative)
cd backend && php artisan serve --port=8080
# Visit: http://localhost:8080/api/documentation

# Component Documentation
cd frontend && npm run dev
# Visit: http://localhost:6006

# Production Environment
# All documentation available at: https://siska.local/api/documentation
```

### **📋 Manual Documentation**
- [Rencana Implementasi Lengkap](docs/RENCANA_IMPLEMENTASI_LENGKAP.md)
- [Project Timeline & Checklist](docs/PROJECT_TIMELINE_IMPLEMENTASI.md)
- [Rekomendasi Tambahan & Best Practices](docs/REKOMENDASI_TAMBAHAN_BEST_PRACTICES.md)
- [Skema Database Sesuai Format Data](docs/SKEMA_DATABASE_SESUAI_FORMAT_DATA.md)

### **📊 Data Format Templates**
- [Data Guru Template](docs/data%20format/Data%20Guru.xlsx)
- [Data Siswa Template](docs/data%20format/Data%20Siswa.xlsx)
- [Formulir Guru](docs/data%20format/Formulir%20Guru.xlsx)
- [Formulir Siswa](docs/data%20format/Formulir%20Siswa.xlsx)

### **📁 Archived Documentation**
- [System Integration Guide](docs/arsip/SYSTEM_INTEGRATION.md)
- [API Documentation](docs/arsip/API_DOCUMENTATION.md)
- [Data Format Reference](docs/arsip/DATA_FORMAT_REFERENCE.md)

## 🚀 Project Status

### **Current Status: ACTIVE DEVELOPMENT** 🚧

**Completed:**
- ✅ **Database Schema Design** (20+ models implemented)
- ✅ **Project Architecture** (Laravel 11.35 + Vue.js 3.5.21)
- ✅ **Backend Foundation** (Models, Controllers, Services)
- ✅ **Frontend Structure** (Components, Views, Router, Stores)
- ✅ **Technology Stack** (Modern, scalable, production-ready)
- ✅ **Docker Configuration** (Complete containerization)
- ✅ **UI/UX Components** (Tailwind CSS + Headless UI)

**In Progress:**
- 🔄 **API Integration** (Backend-Frontend connectivity)
- 🔄 **Business Logic** (Kredit Poin, Presensi, BK workflows)
- 🔄 **Real-time Features** (Notifications, Live updates)
- 🔄 **Testing & Quality Assurance**

**Next Steps:**
- 🔄 **Phase 1**: Complete API endpoints & authentication
- 🔄 **Phase 2**: Implement business workflows
- 🔄 **Phase 3**: Integration testing & optimization
- 🔄 **Phase 4**: Production deployment & monitoring

**Timeline**: 4-6 weeks to completion
**Team Size**: 2-3 developers recommended

## 🤝 Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🆘 Support

For support and questions:
- 📧 Email: jejakawan007@gmail.com
- 📱 GitHub: [jejak-awan/siska](https://github.com/jejak-awan/siska)
- 💬 Issues: [GitHub Issues](https://github.com/jejak-awan/siska/issues)

---

**Sistem Manajemen Kesiswaan Terintegrasi** - Mengelola siswa dengan standar nasional Indonesia 🇮🇩

*Ready for implementation with comprehensive documentation and modern technology stack.*
