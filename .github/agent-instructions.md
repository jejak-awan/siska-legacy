# Agent Instructions - Sistem Manajemen Kesiswaan Terintegrasi (SISKA)

## 🎯 Project Overview

**SISKA** adalah sistem manajemen kesiswaan terintegrasi yang dibangun dengan Laravel (Backend) dan Vue.js (Frontend). Sistem ini dirancang untuk mengelola seluruh aspek kesiswaan sekolah mulai dari manajemen siswa, guru, presensi, penilaian karakter, hingga ekstrakurikuler dan OSIS.

### **Key Information:**
- **Project Path**: `/opt/kesiswaan`
- **Backend**: Laravel 11.35 (PHP 8.3+)
- **Frontend**: Vue.js 3.5.21 dengan Composition API
- **Database**: MySQL 8.0 dengan 20+ migrations
- **Authentication**: Laravel Sanctum
- **API Documentation**: Swagger/OpenAPI
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

### ✅ **COMPLETED PHASES:**
1. **✅ Phase 1: Database & Core Models** - COMPLETE (100%)
2. **✅ Phase 2: API Development & Authentication** - COMPLETE (100%)
3. **✅ Phase 3: Frontend Development & Dashboard** - COMPLETE (100%)
4. **✅ Phase 4: Real-time Features & Notifications** - COMPLETE (100%)
5. **✅ Phase 5: Performance Optimization** - COMPLETE (100%)
6. **✅ Phase 6: Security Enhancement** - COMPLETE (100%)
7. **✅ Phase 7: System Integration** - COMPLETE (100%)
8. **✅ Phase 8: Mobile Optimization** - COMPLETE (100%)
9. **✅ Phase 9: Documentation & Cleanup** - COMPLETE (100%)

### 🟡 **CURRENT FOCUS:**
- **Feature Completion**: Melengkapi fitur yang belum lengkap
- **UI Improvements**: Perbaikan antarmuka pengguna
- **Bug Fixes**: Perbaikan bug yang ditemukan
- **Performance Tuning**: Optimasi performa sistem

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

## 📋 Development Standards & Conventions

### **File Naming Conventions**

#### Backend (Laravel)
- **Controllers**: PascalCase (e.g., `UserController.php`, `StudentController.php`)
- **Models**: PascalCase (e.g., `User.php`, `Student.php`)
- **Migrations**: snake_case with timestamp (e.g., `2024_01_15_000001_create_users_table.php`)
- **Seeders**: PascalCase (e.g., `UserSeeder.php`)
- **Services**: PascalCase (e.g., `UserService.php`)
- **Requests**: PascalCase (e.g., `CreateUserRequest.php`)
- **Resources**: PascalCase (e.g., `UserResource.php`)
- **Middleware**: PascalCase (e.g., `RoleMiddleware.php`)

#### Frontend (Vue.js)
- **Components**: PascalCase (e.g., `UserForm.vue`, `StudentCard.vue`)
- **Views**: PascalCase (e.g., `UsersView.vue`, `StudentView.vue`)
- **Stores**: camelCase (e.g., `userStore.js`, `studentStore.js`)
- **Services**: camelCase (e.g., `userService.js`, `apiService.js`)
- **Utils**: camelCase (e.g., `helpers.js`, `constants.js`)
- **Assets**: kebab-case (e.g., `user-avatar.png`, `school-logo.svg`)

#### Database
- **Tables**: snake_case (e.g., `users`, `user_roles`, `credit_points`)
- **Columns**: snake_case (e.g., `created_at`, `user_id`, `credit_point_id`)
- **Indexes**: snake_case (e.g., `idx_users_email`, `idx_students_class_id`)

#### Routes & URLs
- **API Routes**: kebab-case (e.g., `/api/user-management`, `/api/credit-points`)
- **Frontend Routes**: kebab-case (e.g., `/user-management`, `/credit-points`)
- **Route Names**: kebab-case (e.g., `user-management`, `credit-points`)

### **Language & Localization Standards**

#### Primary Language
- **Default**: Bahasa Indonesia
- **UI Text**: Semua teks antarmuka dalam Bahasa Indonesia
- **Comments**: Kode comments dalam Bahasa Indonesia
- **Documentation**: Dokumentasi dalam Bahasa Indonesia
- **Error Messages**: Pesan error dalam Bahasa Indonesia

#### Code Language
- **Variables**: English (e.g., `userData`, `isLoading`, `handleSubmit`)
- **Functions**: English (e.g., `getUserData()`, `validateForm()`)
- **API Endpoints**: English (e.g., `/api/users`, `/api/students`)
- **Database Fields**: English (e.g., `created_at`, `updated_at`)

#### UI Text Examples
```javascript
// ✅ Correct - Indonesian UI text
const labels = {
  nama: 'Nama Lengkap',
  email: 'Alamat Email',
  simpan: 'Simpan',
  batal: 'Batal',
  hapus: 'Hapus'
}

// ❌ Incorrect - English UI text
const labels = {
  name: 'Full Name',
  email: 'Email Address',
  save: 'Save',
  cancel: 'Cancel',
  delete: 'Delete'
}
```

### **UI/UX Consistency & Theme**

#### Design System
- **Framework**: Tailwind CSS
- **Component Library**: Headless UI + Custom Components
- **Icons**: Heroicons + Lucide Vue
- **Color Scheme**: Consistent with school branding
- **Typography**: Inter font family

#### Theme Configuration
```javascript
// Tailwind Config - Consistent Colors
colors: {
  primary: {
    50: '#eff6ff',
    500: '#3b82f6',
    600: '#2563eb',
    700: '#1d4ed8'
  },
  secondary: {
    50: '#f8fafc',
    500: '#64748b',
    600: '#475569'
  },
  success: '#10b981',
  warning: '#f59e0b',
  error: '#ef4444'
}
```

#### Component Standards
- **Buttons**: Consistent sizing (sm, md, lg)
- **Forms**: Consistent spacing and validation states
- **Cards**: Consistent padding and shadows
- **Modals**: Consistent overlay and positioning
- **Tables**: Consistent headers and row styling

#### Responsive Design
- **Mobile First**: Design for mobile, enhance for desktop
- **Breakpoints**: sm (640px), md (768px), lg (1024px), xl (1280px)
- **Grid System**: CSS Grid and Flexbox
- **Spacing**: Consistent spacing scale (4, 8, 12, 16, 24, 32px)

### **Pre-Creation Checklist**

#### Before Creating New Files/Folders
1. **Check Existing Structure**: 
   ```bash
   # Check if similar file/folder exists
   find . -name "*[similar_name]*" -type f
   find . -name "*[similar_name]*" -type d
   ```

2. **Verify Naming Convention**:
   - Backend: PascalCase for classes
   - Frontend: PascalCase for components
   - Database: snake_case for tables
   - Routes: kebab-case for URLs

3. **Check for Duplicates**:
   - Search existing files with similar functionality
   - Check if feature already exists in different location
   - Verify no duplicate routes or components

4. **Validate Structure**:
   - Follow established folder structure
   - Place files in correct directories
   - Maintain consistent organization

#### File Creation Process
```bash
# 1. Check existing structure
find . -name "*user*" -type f | head -10

# 2. Verify naming convention
# Backend: UserController.php ✅
# Frontend: UserForm.vue ✅
# Database: users table ✅

# 3. Check for duplicates
grep -r "UserController" backend/app/Http/Controllers/

# 4. Create with proper structure
# Backend
touch backend/app/Http/Controllers/Api/UserController.php
touch backend/app/Models/User.php

# Frontend
touch frontend/src/components/forms/UserForm.vue
touch frontend/src/views/users/UsersView.vue
```

### **Progress & TODO Management**

#### TODO Tracking
- **Use TODO Comments**: `// TODO: Implement user validation`
- **Track Progress**: Update completion status
- **Document Changes**: Log what was implemented
- **Update Documentation**: Keep docs current

#### Progress Update Format
```markdown
## Progress Update - [Date]

### Completed
- ✅ Feature A: Description
- ✅ Feature B: Description

### In Progress
- 🟡 Feature C: Description (50% complete)

### Pending
- 🔴 Feature D: Description

### Notes
- Any important notes or decisions
```

#### File Update Checklist
1. **Update Progress**: Mark completed items
2. **Document Changes**: Log what was changed
3. **Update TODO**: Remove completed, add new
4. **Commit Changes**: Clear commit messages
5. **Update Documentation**: Keep docs current

### **Code Quality Standards**

#### Backend (Laravel)
- **PSR-12**: Follow PHP coding standards
- **Eloquent**: Use relationships properly
- **Validation**: Validate all inputs
- **Error Handling**: Proper exception handling
- **Documentation**: PHPDoc comments

#### Frontend (Vue.js)
- **Composition API**: Use `<script setup>` syntax
- **TypeScript**: Consider for complex components
- **Validation**: Use VeeValidate for forms
- **Accessibility**: ARIA attributes and keyboard navigation
- **Performance**: Lazy loading and optimization

#### Database
- **Migrations**: Use for all schema changes
- **Indexes**: Add for frequently queried columns
- **Relationships**: Proper foreign key constraints
- **Naming**: Consistent snake_case naming
- **Data Types**: Appropriate data types

### **Security Guidelines**

#### Authentication
- **Sanctum**: Use for API authentication
- **Tokens**: Implement proper token management
- **Sessions**: Secure session handling
- **Passwords**: Strong password requirements

#### Authorization
- **Roles**: Implement role-based access
- **Permissions**: Fine-grained permissions
- **Middleware**: Use for route protection
- **Validation**: Validate all inputs

#### Data Protection
- **Encryption**: Encrypt sensitive data
- **Sanitization**: Sanitize all inputs
- **CSRF**: Protect against CSRF attacks
- **XSS**: Prevent cross-site scripting

### **Performance Guidelines**

#### Backend Performance
- **Database**: Optimize queries and indexes
- **Caching**: Implement appropriate caching
- **API**: Optimize API responses
- **Memory**: Monitor memory usage

#### Frontend Performance
- **Bundle**: Optimize bundle size
- **Loading**: Implement lazy loading
- **Images**: Optimize image assets
- **Caching**: Use browser caching

### **Documentation Standards**

#### Code Documentation
- **Comments**: Clear and concise comments
- **PHPDoc**: Document all public methods
- **JSDoc**: Document complex functions
- **README**: Keep README updated

#### API Documentation
- **Swagger**: Use OpenAPI annotations
- **Examples**: Provide request/response examples
- **Error Codes**: Document all error responses
- **Authentication**: Document auth requirements

### **Deployment Guidelines**

#### Environment Configuration
- **Development**: Local development setup
- **Staging**: Testing environment
- **Production**: Live environment
- **Secrets**: Secure secret management

#### Build Process
- **Backend**: Composer install and optimize
- **Frontend**: NPM build and optimize
- **Database**: Run migrations and seeders
- **Assets**: Optimize and compress assets

### **Troubleshooting Guidelines**

#### Common Issues
- **Database**: Connection and migration issues
- **API**: Authentication and CORS issues
- **Frontend**: Build and dependency issues
- **Performance**: Slow queries and large bundles

#### Debugging Process
1. **Check Logs**: Laravel and browser logs
2. **Verify Configuration**: Environment and config files
3. **Test Components**: Individual component testing
4. **Check Dependencies**: Package and version issues
5. **Document Solutions**: Record solutions for future reference

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
- ✅ School administration efficiency improved
- ✅ Student behavior tracking effective
- ✅ Parent communication enhanced
- ✅ Academic progress monitoring
- ✅ System adoption rate > 80%

---

*Dokumen ini akan terus diperbarui seiring perkembangan proyek.*