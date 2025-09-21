# 🏗️ Project Architecture - SISKA

## 📋 Architecture Overview

SISKA dibangun dengan arsitektur **Full-Stack Modern** menggunakan Laravel sebagai backend dan Vue.js sebagai frontend, dengan desain yang scalable, maintainable, dan secure.

## 🎯 Architecture Principles

### 1. **Separation of Concerns**
- **Backend**: Business logic, data management, API
- **Frontend**: User interface, user experience, state management
- **Database**: Data persistence dan relationships

### 2. **API-First Design**
- RESTful API dengan Laravel
- Stateless authentication dengan Sanctum
- JSON-based communication
- Swagger/OpenAPI documentation

### 3. **Component-Based Frontend**
- Vue.js 3 dengan Composition API
- Reusable components
- Modular architecture
- State management dengan Pinia

### 4. **Security by Design**
- Role-based access control
- Token-based authentication
- Input validation dan sanitization
- CORS protection

## 🏛️ System Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                    CLIENT LAYER                            │
├─────────────────────────────────────────────────────────────┤
│  Web Browser (Vue.js SPA)                                  │
│  ├── Dashboard Components                                   │
│  ├── User Management                                        │
│  ├── Student Management                                     │
│  ├── Attendance System                                      │
│  ├── Credit Point System                                    │
│  └── Reporting & Analytics                                  │
└─────────────────────────────────────────────────────────────┘
                                │
                                │ HTTP/HTTPS
                                │ JSON API
                                ▼
┌─────────────────────────────────────────────────────────────┐
│                    API LAYER                               │
├─────────────────────────────────────────────────────────────┤
│  Laravel API Gateway                                        │
│  ├── Authentication (Sanctum)                              │
│  ├── Authorization (Middleware)                            │
│  ├── Rate Limiting                                          │
│  ├── CORS Handling                                          │
│  └── Request/Response Processing                           │
└─────────────────────────────────────────────────────────────┘
                                │
                                │
                                ▼
┌─────────────────────────────────────────────────────────────┐
│                  BUSINESS LAYER                            │
├─────────────────────────────────────────────────────────────┤
│  Laravel Controllers & Services                            │
│  ├── UserController                                         │
│  ├── StudentController                                      │
│  ├── AttendanceController                                   │
│  ├── CreditPointController                                  │
│  ├── CharacterAssessmentController                          │
│  ├── ExtracurricularController                              │
│  ├── OSISController                                         │
│  └── SchoolProfileController                                │
└─────────────────────────────────────────────────────────────┘
                                │
                                │
                                ▼
┌─────────────────────────────────────────────────────────────┐
│                   DATA LAYER                               │
├─────────────────────────────────────────────────────────────┤
│  Eloquent ORM & Database                                    │
│  ├── MySQL Database                                         │
│  ├── User Models                                            │
│  ├── Student Models                                         │
│  ├── Attendance Models                                      │
│  ├── Credit Point Models                                    │
│  ├── Character Assessment Models                            │
│  └── Relationship Management                                │
└─────────────────────────────────────────────────────────────┘
```

## 🔧 Technology Stack Architecture

### Backend Architecture (Laravel)

```
backend/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Api/                    # API Controllers
│   │   │       ├── AuthController.php
│   │   │       ├── UserController.php
│   │   │       ├── StudentController.php
│   │   │       ├── AttendanceController.php
│   │   │       ├── CreditPointController.php
│   │   │       ├── CharacterAssessmentController.php
│   │   │       ├── ExtracurricularController.php
│   │   │       ├── OSISController.php
│   │   │       └── SchoolProfileController.php
│   │   ├── Middleware/                 # Custom Middleware
│   │   │   ├── CorsMiddleware.php
│   │   │   ├── RoleMiddleware.php
│   │   │   └── SwaggerConditionalMiddleware.php
│   │   └── Requests/                   # Form Requests
│   ├── Models/                         # Eloquent Models
│   │   ├── User.php
│   │   ├── Student.php
│   │   ├── Teacher.php
│   │   ├── Attendance.php
│   │   ├── CreditPoint.php
│   │   ├── CharacterAssessment.php
│   │   ├── Extracurricular.php
│   │   ├── OSIS.php
│   │   └── SchoolProfile.php
│   ├── Services/                       # Business Logic
│   │   ├── UserService.php
│   │   ├── AttendanceService.php
│   │   ├── CreditPointService.php
│   │   └── NotificationService.php
│   └── Http/Resources/                 # API Resources
│       ├── UserResource.php
│       ├── StudentResource.php
│       └── AttendanceResource.php
├── database/
│   ├── migrations/                     # Database Migrations
│   ├── seeders/                        # Database Seeders
│   └── factories/                      # Model Factories
├── routes/
│   ├── api.php                         # API Routes
│   └── api-dev.php                     # Development Routes
└── config/                             # Configuration
    ├── sanctum.php
    ├── cors.php
    └── l5-swagger.php
```

### Frontend Architecture (Vue.js)

```
frontend/
├── src/
│   ├── components/                     # Reusable Components
│   │   ├── ui/                         # UI Components
│   │   │   ├── Button.vue
│   │   │   ├── Card.vue
│   │   │   ├── Modal.vue
│   │   │   ├── Table.vue
│   │   │   └── StatCard.vue
│   │   ├── forms/                      # Form Components
│   │   │   ├── UserForm.vue
│   │   │   ├── StudentForm.vue
│   │   │   └── AttendanceForm.vue
│   │   ├── modals/                     # Modal Components
│   │   │   ├── UserDetailModal.vue
│   │   │   └── StudentDetailModal.vue
│   │   └── upload/                     # Upload Components
│   │       └── ImageUpload.vue
│   ├── views/                          # Page Components
│   │   ├── dashboard/                  # Dashboard Views
│   │   │   ├── DashboardView.vue
│   │   │   ├── AdminDashboard.vue
│   │   │   ├── TeacherDashboard.vue
│   │   │   └── StudentDashboard.vue
│   │   ├── users/                      # User Management
│   │   │   └── UsersView.vue
│   │   ├── students/                   # Student Management
│   │   │   └── StudentsView.vue
│   │   ├── attendance/                 # Attendance System
│   │   │   └── AttendanceView.vue
│   │   ├── credit-points/              # Credit Point System
│   │   │   └── CreditPointView.vue
│   │   └── [other modules]/            # Other Features
│   ├── stores/                         # Pinia Stores
│   │   ├── auth.js                     # Authentication Store
│   │   ├── user.js                     # User Store
│   │   ├── student.js                  # Student Store
│   │   └── attendance.js               # Attendance Store
│   ├── services/                       # API Services
│   │   ├── api.js                      # API Client
│   │   ├── auth.js                     # Auth Service
│   │   ├── user.js                     # User Service
│   │   └── student.js                  # Student Service
│   ├── utils/                          # Utility Functions
│   │   ├── helpers.js                  # Helper Functions
│   │   ├── validation.js               # Validation Rules
│   │   └── constants.js                # Constants
│   ├── router/                         # Vue Router
│   │   └── index.js                    # Route Configuration
│   └── assets/                         # Static Assets
│       ├── css/                        # Styles
│       └── images/                     # Images
├── public/                             # Public Assets
└── dist/                               # Build Output
```

## 🗄️ Database Architecture

### Core Tables

```sql
-- Users & Authentication
users (id, name, email, password, role_type, status, created_at, updated_at)
roles (id, name, description, permissions, created_at, updated_at)
user_roles (user_id, role_id, created_at, updated_at)
personal_access_tokens (id, tokenable_type, tokenable_id, name, token, abilities, last_used_at, expires_at, created_at, updated_at)

-- Student Management
students (id, user_id, nis, nisn, nama_lengkap, kelas_id, tanggal_lahir, alamat, created_at, updated_at)
teachers (id, user_id, nip, nama_lengkap, mata_pelajaran, created_at, updated_at)
classes (id, nama_kelas, tingkat, wali_kelas_id, created_at, updated_at)
academic_years (id, tahun_ajaran, semester, status, created_at, updated_at)

-- Attendance System
attendances (id, student_id, tanggal, status, keterangan, created_at, updated_at)
attendance_schedules (id, class_id, hari, jam_mulai, jam_selesai, created_at, updated_at)

-- Credit Point System
credit_points (id, student_id, kategori_id, poin, keterangan, created_at, updated_at)
credit_point_categories (id, nama_kategori, poin_maksimal, deskripsi, created_at, updated_at)

-- Character Assessment
character_assessments (id, student_id, dimensi_id, nilai, periode, created_at, updated_at)
character_dimensions (id, nama_dimensi, deskripsi, created_at, updated_at)
character_indicators (id, dimensi_id, nama_indikator, deskripsi, created_at, updated_at)

-- Extracurricular & OSIS
extracurriculars (id, nama, deskripsi, pembina_id, created_at, updated_at)
extracurricular_members (id, extracurricular_id, student_id, posisi, created_at, updated_at)
osis_members (id, student_id, posisi, periode, created_at, updated_at)
osis_activities (id, nama_kegiatan, deskripsi, tanggal, created_at, updated_at)

-- School Profile
school_profiles (id, nama_sekolah, alamat, telepon, email, logo, visi, misi, created_at, updated_at)
organizational_structures (id, jabatan, nama, level, status, created_at, updated_at)

-- Communication
whatsapp_logs (id, recipient, message, status, sent_at, created_at, updated_at)
notifications (id, user_id, title, message, type, read_at, created_at, updated_at)
```

### Relationships

```sql
-- User Relationships
users -> user_roles -> roles (Many-to-Many)
users -> students (One-to-One)
users -> teachers (One-to-One)

-- Student Relationships
students -> classes (Many-to-One)
students -> attendances (One-to-Many)
students -> credit_points (One-to-Many)
students -> character_assessments (One-to-Many)

-- Class Relationships
classes -> students (One-to-Many)
classes -> teachers (One-to-Many, wali_kelas)

-- Credit Point Relationships
credit_points -> credit_point_categories (Many-to-One)
credit_points -> students (Many-to-One)

-- Character Assessment Relationships
character_assessments -> character_dimensions (Many-to-One)
character_dimensions -> character_indicators (One-to-Many)
```

## 🔐 Security Architecture

### Authentication Flow

```
1. User Login Request
   ↓
2. Laravel Sanctum Authentication
   ↓
3. Token Generation & Storage
   ↓
4. Token Validation Middleware
   ↓
5. Role-based Authorization
   ↓
6. API Response
```

### Authorization Levels

```
Admin
├── Full System Access
├── User Management
├── System Configuration
└── Data Management

Teacher
├── Student Management
├── Attendance Tracking
├── Character Assessment
└── Class Management

Student
├── Personal Profile
├── Attendance View
├── Credit Point Tracking
└── Academic Progress

Other Roles
├── Role-specific Access
├── Limited Functionality
└── Data Viewing Only
```

## 📊 API Architecture

### RESTful API Design

```
GET    /api/users              # List users
POST   /api/users              # Create user
GET    /api/users/{id}         # Get user
PUT    /api/users/{id}         # Update user
DELETE /api/users/{id}         # Delete user

GET    /api/students           # List students
POST   /api/students           # Create student
GET    /api/students/{id}      # Get student
PUT    /api/students/{id}      # Update student
DELETE /api/students/{id}      # Delete student

GET    /api/attendances        # List attendances
POST   /api/attendances        # Create attendance
GET    /api/attendances/{id}   # Get attendance
PUT    /api/attendances/{id}   # Update attendance
DELETE /api/attendances/{id}   # Delete attendance
```

### API Response Format

```json
{
  "success": true,
  "message": "Data retrieved successfully",
  "data": {
    "id": 1,
    "name": "John Doe",
    "email": "john@example.com"
  },
  "meta": {
    "pagination": {
      "current_page": 1,
      "per_page": 15,
      "total": 100
    }
  }
}
```

## 🚀 Performance Architecture

### Caching Strategy

```
Application Cache
├── Database Query Cache
├── API Response Cache
├── Static Asset Cache
└── Session Cache

Browser Cache
├── Static Assets
├── API Responses
├── Component Cache
└── Local Storage
```

### Optimization Techniques

```
Database Optimization
├── Indexing Strategy
├── Query Optimization
├── Connection Pooling
└── Read Replicas

Frontend Optimization
├── Code Splitting
├── Lazy Loading
├── Bundle Optimization
└── Image Optimization

API Optimization
├── Response Compression
├── Rate Limiting
├── Request Batching
└── Caching Headers
```

## 🔄 Deployment Architecture

### Development Environment

```
Local Development
├── Laravel Artisan Serve
├── Vite Dev Server
├── MySQL Local
└── Redis (Optional)
```

### Production Environment

```
Production Server
├── Nginx Web Server
├── PHP-FPM
├── MySQL Database
├── Redis Cache
└── SSL Certificate
```

### CI/CD Pipeline

```
GitHub Actions
├── Code Quality Checks
├── Automated Testing
├── Build Process
├── Deployment
└── Health Checks
```

## 📈 Monitoring & Logging

### Application Monitoring

```
Performance Monitoring
├── Response Times
├── Database Queries
├── Memory Usage
└── Error Rates

User Analytics
├── User Behavior
├── Feature Usage
├── Performance Metrics
└── Error Tracking
```

### Logging Strategy

```
Application Logs
├── Error Logs
├── Access Logs
├── Security Logs
└── Performance Logs

Database Logs
├── Query Logs
├── Slow Query Logs
├── Connection Logs
└── Transaction Logs
```

## 🔧 Development Tools

### Backend Tools

```
Development
├── Laravel Artisan
├── Composer
├── PHPUnit
└── Laravel Tinker

Code Quality
├── Laravel Pint
├── PHPStan
├── Psalm
└── CodeSniffer
```

### Frontend Tools

```
Development
├── Vite
├── Vue DevTools
├── ESLint
└── Prettier

Testing
├── Vitest
├── Vue Test Utils
├── Cypress
└── Jest
```

## 📚 Documentation Architecture

### API Documentation

```
Swagger/OpenAPI
├── Endpoint Documentation
├── Request/Response Schemas
├── Authentication Guide
└── Example Requests
```

### Code Documentation

```
Inline Documentation
├── JSDoc Comments
├── PHPDoc Comments
├── README Files
└── Code Comments
```

---

**Architecture Last Updated**: 2024-12-21
**Version**: 1.0.0
**Maintainer**: Development Team
