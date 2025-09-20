# 🔒 Keamanan dan Privacy

## 🛡️ Security Overview

Sistem ini dirancang dengan keamanan sebagai prioritas utama, menggunakan database terpisah dan kontrol akses yang ketat.

## 🗄️ Database Security

### Data Isolation
- ✅ **Complete separation** - No direct connection between databases
- ✅ **API Gateway** - Controlled data flow
- ✅ **Data Sanitization** - Strip sensitive info before public
- ✅ **Audit Trail** - Log all public content changes

### Database Connections
```php
// Main Database (Private)
'mysql' => [
    'driver' => 'mysql',
    'host' => env('DB_HOST', '127.0.0.1'),
    'port' => env('DB_PORT', '3306'),
    'database' => env('DB_DATABASE', 'kesiswaan_private'),
    'username' => env('DB_USERNAME', 'root'),
    'password' => env('DB_PASSWORD', ''),
],

// Public Database (Public)
'public' => [
    'driver' => 'mysql',
    'host' => env('PUBLIC_DB_HOST', '127.0.0.1'),
    'port' => env('PUBLIC_DB_PORT', '3306'),
    'database' => env('PUBLIC_DB_DATABASE', 'kesiswaan_public'),
    'username' => env('PUBLIC_DB_USERNAME', 'root'),
    'password' => env('PUBLIC_DB_PASSWORD', ''),
],
```

## 🔐 Privacy Controls

### Data Classification

#### Public Data (Safe to Display)
```php
✅ Nama kegiatan, tanggal, lokasi umum
✅ Deskripsi kegiatan, kategori
✅ Foto kegiatan (tanpa wajah siswa)
✅ Prestasi umum, galeri
✅ Pengumuman publik
✅ Berita sekolah
```

#### Private Data (Never Public)
```php
❌ NISN, NIK, alamat lengkap siswa
❌ Nomor telepon pribadi, data kesehatan
❌ Nilai akademik, data keluarga
❌ Data pribadi guru/staff
❌ Informasi internal sekolah
❌ Data keuangan
```

### Data Sanitization Service
```php
class ContentSanitizationService {
    public function sanitizeActivity($activity) {
        return [
            'id' => $activity->public_id, // Different ID
            'title' => $activity->title,
            'description' => $activity->public_description,
            'date' => $activity->date,
            'location' => $activity->public_location, // No specific address
            'category' => $activity->category,
            'status' => $activity->status,
            // NO: student_ids, personal_data, internal_notes
        ];
    }
    
    public function sanitizeStudentData($student) {
        return [
            'name' => $this->maskName($student->name),
            'class' => $student->class,
            'achievement' => $student->achievement,
            // NO: nisn, nik, address, phone, family_data
        ];
    }
}
```

## 🔑 Authentication & Authorization

### Role-Based Access Control
```php
const contentPermissions = {
    admin: {
        create: ['all'],
        edit: ['all'],
        delete: ['all'],
        approve: ['all'],
        publish: ['all']
    },
    staff: {
        create: ['all'],
        edit: ['all'],
        delete: ['own'],
        approve: ['all'],
        publish: ['all']
    },
    guru: {
        create: ['academic', 'class_activity'],
        edit: ['own'],
        delete: ['own'],
        approve: ['class_activity'],
        publish: ['class_activity']
    },
    wali_kelas: {
        create: ['class_activity', 'student_achievement'],
        edit: ['own'],
        delete: ['own'],
        approve: ['class_activity'],
        publish: ['class_activity']
    },
    siswa_osis: {
        create: ['osis_activity', 'osis_news'],
        edit: ['own'],
        delete: ['own'],
        approve: [],
        publish: []
    },
    siswa_ekskul: {
        create: ['ekskul_activity', 'ekskul_documentation'],
        edit: ['own'],
        delete: ['own'],
        approve: [],
        publish: []
    }
};
```

## 🛡️ Content Security

### File Upload Security
```php
class FileUploadService {
    public function validateFile($file) {
        // File type validation
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx'];
        $extension = $file->getClientOriginalExtension();
        
        if (!in_array($extension, $allowedTypes)) {
            throw new InvalidFileTypeException('File type not allowed');
        }
        
        // File size validation
        if ($file->getSize() > 10 * 1024 * 1024) { // 10MB
            throw new FileTooLargeException('File too large');
        }
        
        // Virus scanning (if available)
        if ($this->hasVirus($file)) {
            throw new VirusDetectedException('Virus detected');
        }
        
        return true;
    }
}
```

## 🔍 Audit & Monitoring

### Content Audit Trail
```php
class ContentAuditService {
    public function logContentChange($content, $action, $user) {
        ContentAudit::create([
            'content_id' => $content->id,
            'content_type' => get_class($content),
            'action' => $action, // create, update, delete, publish
            'user_id' => $user->id,
            'user_role' => $user->role,
            'changes' => $this->getChanges($content),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'created_at' => now()
        ]);
    }
}
```

## Incident Response

### Security Incident Response Plan
1. **Detection** - Automated monitoring alerts
2. **Assessment** - Evaluate severity and impact
3. **Containment** - Isolate affected systems
4. **Investigation** - Analyze logs and evidence
5. **Recovery** - Restore normal operations
6. **Documentation** - Record incident details
7. **Prevention** - Update security measures

## 📋 Security Checklist

### Pre-Deployment
- [ ] Database security configuration
- [ ] API authentication setup
- [ ] File upload validation
- [ ] Image optimization
- [ ] Content sanitization
- [ ] Role-based permissions
- [ ] Audit logging
- [ ] Security monitoring

### Post-Deployment
- [ ] Regular security audits
- [ ] Performance monitoring
- [ ] User access reviews
- [ ] Content moderation
- [ ] Backup verification
- [ ] Incident response testing
- [ ] Security training
- [ ] Compliance checks
