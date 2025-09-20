# 🏗️ Arsitektur Sistem

## 📊 Database Architecture

### Main Database (Private)
```
/opt/kesiswaan/backend/database/
├── migrations/
│   ├── users, students, activities (existing)
│   └── private content tables
└── seeders/
    └── private data seeders
```

### Public Database (New)
```
/opt/kesiswaan/backend/database_public/
├── migrations/
│   ├── public_activities
│   ├── public_news
│   ├── public_gallery
│   ├── public_achievements
│   ├── public_pages
│   ├── general_posts
│   ├── programs
│   └── program_components
└── seeders/
    └── public content seeders
```

## 🔒 Security & Privacy

### Data Isolation
- ✅ **Complete separation** - No direct connection between databases
- ✅ **API Gateway** - Controlled data flow
- ✅ **Data Sanitization** - Strip sensitive info before public
- ✅ **Audit Trail** - Log all public content changes

### Privacy Controls
```php
// Data yang AMAN ditampilkan publik
✅ Nama kegiatan, tanggal, lokasi umum
✅ Deskripsi kegiatan, kategori
✅ Foto kegiatan (tanpa wajah siswa)
✅ Prestasi umum, galeri

// Data yang TIDAK boleh publik
❌ NISN, NIK, alamat lengkap siswa
❌ Nomor telepon pribadi, data kesehatan
❌ Nilai akademik, data keluarga
```

## Design System

### Shadcn UI + Theme System
```css
:root {
  /* Light Mode */
  --background: 0 0% 100%;
  --foreground: 222.2 84% 4.9%;
  --card: 0 0% 100%;
  --primary: 221.2 83.2% 53.3%;
  --secondary: 210 40% 96%;
  --muted: 210 40% 96%;
  --accent: 210 40% 96%;
  --destructive: 0 84.2% 60.2%;
  --border: 214.3 31.8% 91.4%;
  --radius: 0.5rem;
}

[data-theme="dark"] {
  /* Dark Mode */
  --background: 222.2 84% 4.9%;
  --foreground: 210 40% 98%;
  --card: 222.2 84% 4.9%;
  --primary: 217.2 91.2% 59.8%;
  --secondary: 217.2 32.6% 17.5%;
  --muted: 217.2 32.6% 17.5%;
  --accent: 217.2 32.6% 17.5%;
  --destructive: 0 62.8% 30.6%;
  --border: 217.2 32.6% 17.5%;
}
```

## 🔗 Dashboard Integration

### Admin Dashboard Enhancement
- ✅ **Content Management** section
- ✅ **Program Overview** dashboard
- ✅ **Analytics** dan reporting
- ✅ **Approval** workflow

### Role-Based Integration
- ✅ **Guru**: Kegiatan & konten management
- ✅ **Wali Kelas**: Class activity management
- ✅ **Siswa OSIS**: OSIS content creation
- ✅ **Siswa Ekskul**: Ekskul content creation

## 📱 Frontend Structure

```
/opt/kesiswaan/frontend/
├── src/
│   ├── views/
│   │   ├── public/ (new - landing pages)
│   │   └── dashboard/ (existing - private)
│   ├── components/
│   │   ├── ui/ (new - Shadcn components)
│   │   ├── public/ (new - public components)
│   │   └── layout/ (existing - private)
│   ├── stores/
│   │   ├── theme.js (new - theme management)
│   │   └── public.js (new - public content)
│   └── router/
│       ├── index.js (existing - private)
│       └── public.js (new - public routes)
```

## 🔧 Backend Structure

```
/opt/kesiswaan/backend/
├── app/
│   ├── Http/Controllers/
│   │   ├── Api/ (existing - private)
│   │   └── Public/ (new - public API)
│   ├── Services/
│   │   ├── ContentSanitizationService.php
│   │   ├── PublicContentService.php
│   │   ├── ProgramCompletionService.php
│   │   └── ReportGenerationService.php
│   └── Models/
│       ├── (existing - private)
│       └── Public/ (new - public models)
├── database/ (existing - private)
├── database_public/ (new - public)
└── routes/
    ├── api.php (existing - private)
    └── public.php (new - public)
```
