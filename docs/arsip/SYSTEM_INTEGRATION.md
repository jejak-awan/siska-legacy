# Integrasi Sistem Kesiswaan Berdasarkan Standar Nasional Indonesia

## Alur Kerja Sistem Poin dan Tindak Lanjut

### 1. KESISWAAN MODULE (Data Recording & Point System)
**Fungsi Utama:**
- Mencatat pelanggaran dan prestasi siswa
- Menghitung akumulasi poin secara otomatis
- Mengirim notifikasi ke BK jika poin mencapai batas tertentu
- Menyimpan riwayat pelanggaran/prestasi

**Kriteria Rujukan ke BK:**
- Poin pelanggaran ringan: 10-25 poin → Peringatan tertulis
- Poin pelanggaran sedang: 26-50 poin → Rujukan ke BK
- Poin pelanggaran berat: 51-75 poin → Panggilan orang tua + BK
- Poin pelanggaran sangat berat: >75 poin → Skorsing/tindakan khusus

### 2. BK/BP MODULE (Counseling & Follow-up)
**Fungsi Utama:**
- Menerima rujukan otomatis dari sistem poin
- Mengelola sesi konseling individual/kelompok
- Melakukan assessment dan intervensi
- Membuat laporan progress siswa
- Merekomendasikan tindakan selanjutnya

**Workflow BK:**
```
Rujukan dari Kesiswaan → Assessment Awal → Jadwal Konseling 
→ Sesi Konseling → Evaluasi Progress → Laporan ke Kesiswaan/Ortu
```

### 3. OSIS MODULE (Student Leadership)
**Fungsi Utama:**
- Mengelola struktur organisasi siswa
- Tracking leadership development
- Koordinasi kegiatan dan event
- Pemilihan pengurus OSIS
- Monitoring aktivitas kepemimpinan

**Integrasi dengan Kesiswaan:**
- Prestasi leadership → Poin positif di Kesiswaan
- Violation by OSIS members → Sanksi khusus
- OSIS activities attendance → Credit points

### 4. EKSTRAKURIKULER MODULE
**Fungsi Utama:**
- Pendaftaran dan manajemen kegiatan
- Tracking prestasi dan kompetisi
- Absensi kegiatan ekstrakurikuler
- Manajemen pelatih/pembina
- Sertifikat dan penghargaan

**Integrasi dengan Sistem:**
- Prestasi ekstrakurikuler → Poin positif + BK career guidance
- Partisipasi aktif → Leadership development (OSIS)
- Achievement tracking → Portfolio siswa

## Database Relations

### Core Entities:
```sql
students → point_records → bk_referrals → counseling_sessions
students → osis_members → osis_activities
students → extracurricular_participants → achievements
students → attendance_records (daily, activities, counseling)
```

### Integration Points:
1. **Point System Integration:**
   - Kesiswaan records points
   - Auto-trigger BK referral
   - OSIS/Ekskul achievements add positive points

2. **Notification System:**
   - Parent alerts for point thresholds
   - BK appointment reminders
   - OSIS meeting notifications
   - Extracurricular event updates

3. **Reporting Integration:**
   - Comprehensive student profile
   - Multi-module analytics
   - Parent dashboard with all activities
   - Academic + behavioral + leadership report

## User Roles & Permissions

### Admin
- Full system access
- Configure point thresholds
- Manage all modules
- System reports

### Kesiswaan Staff
- Student data management
- Point recording
- Attendance management
- Generate reports

### BK Counselor
- Counseling case management
- Student assessment
- Progress tracking
- Referral system

### OSIS Advisor
- OSIS activity management
- Leadership development
- Event coordination
- Student election

### Ekstrakurikuler Coach
- Activity management
- Achievement recording
- Participant tracking
- Performance evaluation

### Teacher
- Report violations/achievements
- View student profiles
- Submit BK referrals
- Attendance recording

### Student
- View own profile
- Check point status
- Schedule counseling
- OSIS/Ekskul registration

### Parent
- View child's complete profile
- Point status notifications
- Counseling appointments
- Activity participation

## Implementation Strategy

### Phase 1: Core System
1. User management & authentication
2. Student database
3. Basic point recording system
4. Simple BK referral workflow

### Phase 2: Integration
1. Automated BK referrals
2. OSIS management system
3. Ekstrakurikuler tracking
4. Cross-module notifications

### Phase 3: Advanced Features
1. Analytics dashboard
2. Mobile app
3. Advanced reporting
4. AI-powered insights

### Phase 4: Optimization
1. Performance optimization
2. Advanced integrations
3. Custom workflows
4. API for third-party systems