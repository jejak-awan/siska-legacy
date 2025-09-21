# Agent Instructions - Sistem Manajemen Kesiswaan Terintegrasi (SISKA)

## 🎯 Project Overview

**SISKA** adalah sistem manajemen kesiswaan terintegrasi yang dibangun dengan Laravel (Backend) dan Vue.js (Frontend). Sistem ini dirancang untuk mengelola seluruh aspek kesiswaan sekolah mulai dari manajemen siswa, guru, presensi, penilaian karakter, hingga ekstrakurikuler dan OSIS.

### **Key Information:**
- **Project Path**: `/opt/kesiswaan`
- **Backend**: Laravel 11.35 (PHP 8.3+)
- **Frontend**: Vue.js 3.5.21 dengan Composition API
- **Database**: MySQL dengan 20+ migrations
- **Authentication**: Laravel Sanctum
- **API Documentation**: Swagger/OpenAPI
- **Database**: MySQL 8.0 dengan skema sesuai format data Indonesia
- **Status**: READY FOR IMPLEMENTATION
- **Timeline**: 10 weeks implementation

## 👥 User Roles & Permissions

### 1. **Admin**
- Full system access
- User management
- System configuration
- School profile management

### 2. **Guru**
- Student management
- Attendance tracking
- Character assessment
- Class management

### 3. **Siswa**
- Personal profile
- Attendance view
- Credit point tracking
- Academic progress

### 4. **Wali Kelas**
- Class student management
- Attendance monitoring
- Parent communication

### 5. **BK (Bimbingan Konseling)**
- Student counseling
- Character assessment
- Home visit management

### 6. **OSIS**
- OSIS member management
- Activity organization
- Achievement tracking

### 7. **Ekstrakurikuler**
- Extracurricular management
- Schedule management
- Member management

### 8. **Orang Tua**
- Child's academic progress
- Attendance monitoring
- Communication with school

## 🎯 Core Features

### 1. **User Management System**
- Multi-role authentication
- User profile management
- Role-based access control

### 2. **Student Management**
- Student registration and profiles
- Class assignment
- Academic year management

### 3. **Attendance System**
- Daily attendance tracking
- QR code attendance
- Attendance reports

### 4. **Credit Point System**
- Point-based behavior tracking
- Category management
- Point history

### 5. **Character Assessment**
- Multi-dimensional character evaluation
- Assessment indicators
- Progress tracking

### 6. **Academic Management**
- Class scheduling
- Subject management
- Academic year configuration

### 7. **Extracurricular Management**
- Activity organization
- Member management
- Schedule coordination

### 8. **OSIS Management**
- Member management
- Activity planning
- Leadership tracking

### 9. **School Profile**
- School information management
- Logo and branding
- Organizational structure

### 10. **Content Management**
- Gallery management
- Content publishing
- Media upload

### 11. **Communication**
- WhatsApp integration
- Notification system
- Parent communication

### 12. **Reporting & Analytics**
- Dashboard analytics
- Role-based reports
- Data visualization

## 📋 Current Project Status

### ✅ **COMPLETED PHASES (Partial):**
1. **✅ Phase 1: Database & Core Models** - PARTIAL (40% - Basic tables only)
2. **✅ Phase 2: API Development & Authentication** - PARTIAL (30% - Basic auth only)  
3. **✅ Phase 3: Frontend Development & Dashboard** - PARTIAL (30% - Basic structure only)
4. **📋 Documentation** - Comprehensive guides, progress reports, agent instructions COMPLETE

### 🔄 **CURRENT STATUS:**
- **Overall Progress**: 25% COMPLETE (Foundation Only)
- **Current Phase**: Phase 4 - Core Modules Implementation
- **Frontend**: Basic structure with navigation (missing core views)
- **Backend**: Basic auth working (missing core controllers)
- **Database**: 12/26+ tables implemented (missing critical tables)

### ⚠️ **REALITY CHECK:**
- ✅ **Foundation**: Database basic, Auth, Frontend structure
- ❌ **Core Modules**: Presensi, Kredit Poin, BK, OSIS, Ekstrakurikuler (0% complete)
- ❌ **Business Logic**: Point system, notifications, reports (90% missing)
- ❌ **Integrations**: WhatsApp, file upload, advanced features (0% complete)

### 🚀 **IMMEDIATE PRIORITIES:**
- **Database**: Create 14+ missing tables (presensi, kredit_poin, notifikasi, dll)
- **Controllers**: Create 10+ missing controllers (PresensiController, KreditPoinController, dll)
- **Frontend**: Create 8+ missing views (PresensiView, KreditPoinView, dll)
- **Business Logic**: Implement point system, notifications, WhatsApp integration

## 🏗️ Project Structure

```
kesiswaan/
├── 📂 backend/                 # Laravel 11 API
│   ├── 📂 app/
│   │   ├── 📂 Http/Controllers/
│   │   │   ├── 📂 Api/         # API Controllers
│   │   │   ├── 📂 Kesiswaan/   # Program, Agenda, Laporan
│   │   │   ├── 📂 BK/          # Bimbingan konseling  
│   │   │   ├── 📂 WaliKelas/   # Manajemen wali kelas
│   │   │   ├── 📂 OSIS/        # Organisasi siswa
│   │   │   ├── 📂 Ekstrakurikuler/ # Ekstrakurikuler
│   │   │   ├── 📂 Piket/       # Piket guru & kebersihan
│   │   │   └── 📂 Surat/       # Administrasi surat
│   │   ├── 📂 Models/          # Sesuai format data Indonesia
│   │   ├── 📂 Services/        # Business logic
│   │   └── 📂 Middleware/      # Role & permission middleware
│   └── 📂 database/migrations/ # 15+ migrations sesuai format data
├── 📂 frontend/                # Vue.js 3 SPA
│   └── 📂 src/
│       ├── 📂 components/      # Role-based components
│       ├── 📂 views/           # Role-based dashboards
│       ├── 📂 stores/          # Pinia stores
│       └── 📂 services/        # API services
├── 📂 docs/                    # Comprehensive documentation
│   ├── 📄 RENCANA_IMPLEMENTASI_LENGKAP.md
│   ├── 📄 PROJECT_TIMELINE_IMPLEMENTASI.md
│   ├── 📄 REKOMENDASI_TAMBAHAN_BEST_PRACTICES.md
│   ├── 📄 SKEMA_DATABASE_SESUAI_FORMAT_DATA.md
│   └── 📂 data format/         # Excel templates
└── 📂 docker/                  # Docker configuration
```

## 🎯 Core Modules

### **1. Dashboard System**
- **Type**: Role-based unified dashboard
- **Roles**: Admin, Guru, Siswa, Wali Kelas, BK, OSIS, Ekstrakurikuler, Orang Tua
- **Implementation**: Vue.js components dengan role-specific content

### **2. User Management**
- **Models**: User, Guru, Siswa, Tendik, OrangTua
- **Authentication**: Laravel Sanctum dengan JWT tokens
- **Permissions**: Role-based access control

### **3. Presensi System**
- **Technology**: QR code + GPS validation
- **Flow**: Siswa Scan QR → Validasi GPS → Presensi Tercatat → Auto Notifikasi
- **Integration**: Auto-trigger kredit poin

### **4. Kredit Poin System**
- **Purpose**: Reward & punishment terintegrasi
- **Thresholds**: 10-25 (Peringatan), 26-50 (BK), 51-75 (Orang Tua), >75 (Skorsing)
- **Integration**: Auto-referral ke BK

### **5. Bimbingan Konseling**
- **Features**: Konseling, home visit, tindak lanjut otomatis
- **Integration**: Auto-referral dari kredit poin system

### **6. OSIS Management**
- **Features**: Struktur organisasi, kegiatan, anggota, laporan
- **Integration**: Leadership points ke kredit poin

### **7. Ekstrakurikuler**
- **Features**: Daftar ekskul, anggota, jadwal, prestasi
- **Integration**: Achievement points ke kredit poin

### **8. Piket System**
- **Features**: Piket guru dan kebersihan siswa
- **Integration**: Reporting system

## 📊 Database Schema

### **Core Tables:**
- `users` - Core user authentication
- `roles` - User roles (Admin, Guru, Siswa, dll)
- `user_roles` - Many-to-many relationship
- `pegawai` - Data pegawai lengkap (NIP, NUPTK, dll)
- `siswa` - Data siswa lengkap (NISN, NIK, dll)
- `orang_tua` - Data orang tua lengkap
- `kelas` - Master kelas
- `tahun_ajaran` - Master tahun ajaran

### **Module Tables:**
- `presensi` - Sistem presensi
- `kredit_poin` - Sistem kredit poin
- `konseling` - Bimbingan konseling
- `piket_guru` - Piket guru
- `struktur_osis` - Struktur OSIS
- `ekstrakurikuler` - Ekstrakurikuler
- `pendaftaran_ekskul` - Pendaftaran ekstrakurikuler

### **Key Features:**
- **Format Data Indonesia**: Sesuai dengan NIP, NISN, NIK, dll
- **JSON Fields**: Untuk data yang fleksibel
- **Indexing**: Optimized untuk performance
- **Constraints**: Data integrity dan validation

## 🛠️ Technology Stack

### **Backend (Laravel 11 LTS):**
- **PHP**: 8.3+ (Laravel 11 requirement)
- **Database**: MySQL 8.0 dengan skema sesuai format data Indonesia
- **Authentication**: Laravel Sanctum dengan JWT tokens
- **Real-time**: Laravel Broadcasting + Pusher/Redis
- **QR Code**: SimpleSoftwareIO/simple-qrcode
- **Notifications**: WhatsApp Business API + Laravel Notifications
- **Excel**: PhpSpreadsheet untuk import/export

### **Frontend (Vue.js 3 LTS):**
- **Framework**: Vue.js 3 LTS + Composition API
- **Build Tool**: Vite
- **UI Framework**: Tailwind CSS + Shadcn/ui
- **State Management**: Pinia
- **Routing**: Vue Router
- **HTTP Client**: Axios

### **Infrastructure:**
- **Containerization**: Docker
- **Caching**: Redis
- **Real-time**: WebSocket + Pusher
- **Notifications**: WhatsApp Business API

## 📖 Critical Documentation

### **MUST READ Documents:**
1. **`docs/RENCANA_IMPLEMENTASI_LENGKAP.md`** - Comprehensive implementation plan
2. **`docs/PROJECT_TIMELINE_IMPLEMENTASI.md`** - 10-week timeline dengan checklist
3. **`docs/SKEMA_DATABASE_SESUAI_FORMAT_DATA.md`** - Database schema yang sudah disesuaikan
4. **`docs/REKOMENDASI_TAMBAHAN_BEST_PRACTICES.md`** - Best practices dan rekomendasi

### **Data Format References:**
- **`docs/data format/Data Guru.xlsx`** - Template data guru
- **`docs/data format/Data Siswa.xlsx`** - Template data siswa
- **`docs/arsip/DATA_FORMAT_REFERENCE.md`** - Format data reference

## 🔄 Business Logic Flows

### **1. Presensi Flow:**
```
Siswa Scan QR → Validasi GPS → Presensi Tercatat → Auto Notifikasi
```

### **2. Kredit Poin Flow:**
```
Guru Input Poin → Validasi → Update Total → Cek Threshold → Auto Action
```

### **3. BK Referral Flow:**
```
Kredit Poin Threshold → Auto Trigger → BK Notification → Counseling Session
```

### **4. Notification Flow:**
```
System Event → WhatsApp Service → Parent Notification → Confirmation
```

## 🎯 Implementation Priorities

### **⚡ CURRENT ACHIEVEMENTS:**

✅ **Phase 1-3 COMPLETED** - Database, API, Frontend Dashboard fully functional
✅ **Database Fully Implemented** - All 15+ tables, models, relationships working
✅ **API System Complete** - Authentication, CRUD endpoints, middleware functional  
✅ **Frontend Dashboard Ready** - Vue.js 3 with responsive navigation, 8 role dashboards
✅ **Navigation System Optimal** - Collapsible sidebar + global navbar with dropdown
✅ **Authentication Working** - Login/logout, role-based access, session management

### **🚀 NEXT PHASE: Core Modules (Phase 4 - Weeks 7-10)**

#### **Priority 1: Complete CRUD Operations**
1. **Users Management**: Advanced filtering, bulk operations, import/export
2. **Guru Management**: Teacher profiles, subject assignment, schedule management  
3. **Siswa Management**: Student profiles, class assignment, parent linking
4. **File Uploads**: Profile photos, document management

#### **Priority 2: Attendance System**
1. **Presensi Module**: Daily attendance marking, QR code scanning
2. **Attendance Reports**: Late/absence tracking, parent notifications
3. **Real-time Updates**: Live attendance dashboard

#### **Priority 3: Credit Point System** 
1. **Kredit Poin Engine**: Point calculation, violation recording
2. **Achievement Tracking**: Positive points, ranking system
3. **Behavioral Reports**: Point history, trend analysis

#### **Priority 4: Specialized Modules**
1. **BK Module**: Counseling sessions, case tracking, home visits
2. **OSIS Module**: Activity planning, member management, proposals
3. **Ekstrakurikuler**: Registration, schedules, achievements

#### **Priority 5: Reports & System Config**
1. **Laporan System**: Comprehensive reporting with PDF/Excel export
2. **Pengaturan**: School settings, notifications, backup/restore

### **Phase 2: API Development (Weeks 3-4)**
1. **Controllers**: API controllers untuk semua modules
2. **Services**: Business logic services
3. **Middleware**: Role & permission middleware
4. **Validation**: Request validation rules

### **Phase 3: Frontend Core (Weeks 5-6)**
1. **Authentication**: Login/logout system
2. **Dashboard**: Role-based dashboard components
3. **Routing**: Vue Router setup
4. **State Management**: Pinia stores

### **Phase 4: Module Development (Weeks 7-8)**
1. **Presensi**: QR code + GPS validation
2. **Kredit Poin**: Input dan threshold system
3. **BK**: Counseling management
4. **OSIS**: Organization management

### **Phase 5: Integration & Testing (Weeks 9-10)**
1. **Integration**: Module integration testing
2. **WhatsApp**: Notification system
3. **Performance**: Optimization
4. **Deployment**: Production deployment

## 🚨 Important Notes

### **Database Schema:**
- **CRITICAL**: Gunakan skema yang sudah didesain di `docs/SKEMA_DATABASE_SESUAI_FORMAT_DATA.md`
- **Format Data**: Sesuai dengan format data Indonesia (NIP, NISN, NIK, dll)
- **Migrations**: 15+ migrations sudah didesain, implement sesuai urutan

### **Authentication:**
- **Laravel Sanctum**: Untuk API authentication
- **JWT Tokens**: Untuk stateless authentication
- **Role-based**: 8 role types dengan permissions yang jelas

### **Frontend Architecture:**
- **Vue.js 3**: Composition API (bukan Options API)
- **Role-based**: Dashboard berbeda per role
- **Responsive**: Mobile-first design
- **Real-time**: WebSocket integration

### **Integration Points:**
- **Presensi → Kredit Poin**: Auto-trigger system
- **Kredit Poin → BK**: Threshold-based referral
- **WhatsApp**: Business API untuk notifications
- **Excel**: Import/export dengan templates

## 🔧 Development Guidelines

### **Code Standards:**
- **Laravel**: PSR-12 coding standards
- **Vue.js**: Composition API dengan TypeScript (optional)
- **Database**: Eloquent ORM dengan relationships
- **API**: RESTful API design

### **Testing:**
- **Backend**: PHPUnit tests
- **Frontend**: Vue Test Utils
- **Integration**: API testing
- **E2E**: Cypress atau Playwright

### **Performance:**
- **Caching**: Redis untuk caching
- **Database**: Proper indexing
- **Frontend**: Lazy loading dan code splitting
- **API**: Pagination dan filtering

## 📞 Support & Resources

### **Documentation:**
- **Laravel 11**: https://laravel.com/docs/11.x
- **Vue.js 3**: https://vuejs.org/guide/
- **Tailwind CSS**: https://tailwindcss.com/docs
- **Pinia**: https://pinia.vuejs.org/

### **Project Contacts:**
- **Email**: support@kesiswaan.id
- **WhatsApp**: +62-xxx-xxxx-xxxx
- **Telegram**: @kesiswaan_support

## 🎯 Success Criteria

### **Technical Success:**
- ✅ Database schema implemented sesuai design
- ✅ All 8 user roles working dengan permissions
- ✅ Presensi system dengan QR code + GPS
- ✅ Kredit poin system dengan auto-trigger
- ✅ WhatsApp notifications working
- ✅ Role-based dashboards functional

### **Business Success:**
- ✅ Siswa dapat presensi dengan QR code
- ✅ Guru dapat input kredit poin
- ✅ BK dapat manage konseling
- ✅ Orang tua dapat monitor anak
- ✅ Admin dapat manage semua data
- ✅ System dapat handle 1000+ users

## 🚀 Next Steps for New Agent

### **Immediate Actions:**
1. **Read Documentation**: Baca semua dokumen di `docs/` folder
2. **Understand Schema**: Pelajari database schema di `SKEMA_DATABASE_SESUAI_FORMAT_DATA.md`
3. **Setup Environment**: Setup Laravel 11 + Vue.js 3 development environment
4. **Start Phase 1**: Implement database migrations dan core models

### **Development Workflow:**
1. **Database First**: Implement migrations sesuai skema
2. **Models Second**: Create Eloquent models dengan relationships
3. **API Third**: Build API controllers dan services
4. **Frontend Fourth**: Build Vue.js components dan views
5. **Integration Last**: Integrate semua modules

### **Quality Assurance:**
- **Code Review**: Review semua code sebelum merge
- **Testing**: Write tests untuk semua features
- **Documentation**: Update documentation saat ada perubahan
- **Performance**: Monitor performance dan optimize

---

**IMPORTANT**: Project ini sudah siap untuk implementasi dengan dokumentasi lengkap. Fokus pada implementasi sesuai timeline dan prioritas yang sudah ditentukan. Semua aspek teknis dan bisnis sudah didesain dengan baik.

**Good luck dengan implementasi! 🚀**
