# SKEMA DATABASE SESUAI FORMAT DATA REFERENSI

## 📋 **OVERVIEW**

Dokumen ini berisi skema database yang telah disesuaikan dengan format data referensi yang ada di `docs/data format/`. Skema ini mengikuti standar pendidikan Indonesia dan format data yang sudah ditetapkan.

---

## 🗄️ **1. CORE SYSTEM TABLES**

### **1.1 Users & Authentication**
```sql
-- Users table (tetap sama)
CREATE TABLE users (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role_type ENUM('admin', 'guru', 'siswa', 'tendik', 'bk', 'wali_kelas', 'osis', 'ekstrakurikuler', 'orang_tua') NOT NULL,
    status ENUM('aktif', '非aktif', 'suspended') DEFAULT 'aktif',
    email_verified_at TIMESTAMP NULL,
    two_factor_enabled BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Roles table (tetap sama)
CREATE TABLE roles (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    permissions JSON,
    level INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- User roles table (tetap sama)
CREATE TABLE user_roles (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    role_id BIGINT NOT NULL,
    assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    assigned_by BIGINT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
    FOREIGN KEY (assigned_by) REFERENCES users(id) ON DELETE CASCADE
);
```

---

## 👨‍🏫 **2. GURU/PEGAWAI TABLES (SESUAI FORMAT DATA)**

### **2.1 Guru Table (Lengkap sesuai format data)**
```sql
CREATE TABLE guru (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    
    -- Data Identitas Utama (sesuai format data)
    nip VARCHAR(18) UNIQUE NOT NULL COMMENT 'Nomor Induk Pegawai (18 digit)',
    nuptk VARCHAR(16) UNIQUE NULL COMMENT 'Nomor Unik Pendidik dan Tenaga Kependidikan',
    nama_lengkap VARCHAR(100) NOT NULL COMMENT 'Nama lengkap beserta gelar',
    nama_panggilan VARCHAR(50) NULL COMMENT 'Nama yang biasa dipanggil',
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    tempat_lahir VARCHAR(50) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    agama ENUM('Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu') NOT NULL,
    status_pernikahan ENUM('Belum Menikah', 'Menikah', 'Cerai') NULL,
    
    -- Data Kontak dan Alamat (sesuai format data)
    alamat_lengkap TEXT NOT NULL,
    rt_rw VARCHAR(10) NULL COMMENT 'Format: 001/002',
    kelurahan VARCHAR(50) NOT NULL,
    kecamatan VARCHAR(50) NOT NULL,
    kabupaten_kota VARCHAR(50) NOT NULL,
    provinsi VARCHAR(50) NOT NULL,
    kode_pos VARCHAR(5) NULL,
    nomor_hp VARCHAR(15) NOT NULL COMMENT 'Format: 08xxxxxxxxxx',
    email VARCHAR(100) NULL,
    nomor_hp_darurat VARCHAR(15) NULL,
    
    -- Data Kepegawaian (sesuai format data)
    jenis_ptk ENUM('Guru Kelas', 'Guru Mapel', 'Tenaga Kependidikan') NOT NULL,
    status_kepegawaian ENUM('PNS', 'PPPK', 'GTT', 'PTT', 'Honorer') NOT NULL,
    golongan VARCHAR(10) NULL COMMENT 'Golongan kepegawaian (untuk PNS/PPPK)',
    jabatan VARCHAR(100) NOT NULL,
    unit_kerja VARCHAR(100) NOT NULL,
    tanggal_masuk DATE NOT NULL,
    tanggal_keluar DATE NULL,
    masa_kerja INT NULL COMMENT 'Masa kerja dalam tahun',
    
    -- Data Pendidikan dan Kompetensi (sesuai format data)
    pendidikan_terakhir ENUM('SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3') NOT NULL,
    jurusan_pendidikan VARCHAR(100) NULL,
    nama_universitas VARCHAR(100) NULL,
    tahun_lulus INT(4) NULL,
    no_ijazah VARCHAR(50) NULL,
    mata_pelajaran VARCHAR(100) NULL COMMENT 'Mata pelajaran yang diampu',
    kelas_yang_diajar JSON NULL COMMENT 'Array kelas yang diajar',
    is_wali_kelas BOOLEAN DEFAULT FALSE,
    kelas_wali VARCHAR(20) NULL COMMENT 'Kelas yang dibimbing (jika wali kelas)',
    
    -- Data Sertifikasi (sesuai format data)
    status_sertifikasi ENUM('Tersertifikasi', 'Belum Tersertifikasi', 'Dalam Proses') NULL,
    no_sertifikat_pendidik VARCHAR(50) NULL,
    tanggal_sertifikasi DATE NULL,
    lembaga_sertifikasi VARCHAR(100) NULL,
    sertifikat_lainnya JSON NULL COMMENT 'Array sertifikat tambahan',
    
    -- Data Tugas Tambahan (sesuai format data)
    is_konselor_bk BOOLEAN DEFAULT FALSE,
    is_pembina_osis BOOLEAN DEFAULT FALSE,
    ekstrakurikuler JSON NULL COMMENT 'Array ekstrakurikuler yang dibina',
    tugas_tambahan JSON NULL COMMENT 'Array tugas tambahan lainnya',
    
    -- System fields
    status_aktif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_nip (nip),
    INDEX idx_nuptk (nuptk),
    INDEX idx_nama (nama_lengkap),
    INDEX idx_status_aktif (status_aktif)
);
```

---

## 👨‍🎓 **3. SISWA TABLES (SESUAI FORMAT DATA)**

### **3.1 Siswa Table (Lengkap sesuai format data)**
```sql
CREATE TABLE siswa (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    
    -- Data Identitas Utama (sesuai format data)
    nisn VARCHAR(10) UNIQUE NOT NULL COMMENT 'Nomor Induk Siswa Nasional (10 digit)',
    nis VARCHAR(10) UNIQUE NOT NULL COMMENT 'Nomor Induk Siswa sekolah (10 digit)',
    nama_lengkap VARCHAR(100) NOT NULL COMMENT 'Nama lengkap sesuai akta kelahiran',
    nama_panggilan VARCHAR(50) NULL COMMENT 'Nama yang biasa dipanggil',
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    tempat_lahir VARCHAR(50) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    agama ENUM('Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu') NOT NULL,
    kewarganegaraan VARCHAR(20) DEFAULT 'Indonesia',
    
    -- Data Keluarga (sesuai format data)
    nik VARCHAR(16) UNIQUE NOT NULL COMMENT 'NIK siswa (16 digit)',
    no_kk VARCHAR(16) NOT NULL COMMENT 'Nomor Kartu Keluarga',
    no_akta_kelahiran VARCHAR(50) NULL,
    anak_ke INT NULL COMMENT 'Anak keberapa dalam keluarga',
    jumlah_saudara INT NULL COMMENT 'Jumlah saudara kandung',
    bahasa_sehari_hari VARCHAR(50) NULL,
    
    -- Data Fisik dan Kesehatan (sesuai format data)
    tinggi_badan DECIMAL(5,2) NULL COMMENT 'Tinggi badan dalam cm',
    berat_badan DECIMAL(5,2) NULL COMMENT 'Berat badan dalam kg',
    golongan_darah ENUM('A', 'B', 'AB', 'O') NULL,
    riwayat_penyakit JSON NULL COMMENT 'Array riwayat penyakit',
    kelainan_fisik TEXT NULL COMMENT 'Keterangan kelainan fisik (jika ada)',
    
    -- Data Kontak dan Alamat (sesuai format data)
    alamat_lengkap TEXT NOT NULL,
    rt_rw VARCHAR(10) NULL COMMENT 'Format: 001/002',
    kelurahan VARCHAR(50) NOT NULL,
    kecamatan VARCHAR(50) NOT NULL,
    kabupaten_kota VARCHAR(50) NOT NULL,
    provinsi VARCHAR(50) NOT NULL,
    kode_pos VARCHAR(5) NULL,
    nomor_hp_siswa VARCHAR(15) NULL,
    email_siswa VARCHAR(100) NULL,
    
    -- Data Akademik (sesuai format data)
    kelas_id BIGINT NULL COMMENT 'Foreign key ke tabel kelas',
    kelas VARCHAR(20) NOT NULL COMMENT 'Kelas saat ini (untuk compatibility)',
    jurusan VARCHAR(50) NULL COMMENT 'Jurusan (untuk SMA/SMK)',
    angkatan INT(4) NOT NULL COMMENT 'Tahun masuk sekolah',
    tahun_ajaran_id BIGINT NOT NULL,
    asal_sekolah VARCHAR(100) NULL,
    tanggal_masuk DATE NOT NULL,
    status_siswa ENUM('Aktif', 'Pindah', 'Lulus', 'DO', 'Mengundurkan Diri') NOT NULL,
    
    -- Data Transportasi (sesuai format data)
    jarak_ke_sekolah DECIMAL(8,2) NULL COMMENT 'Jarak rumah ke sekolah (km)',
    transportasi ENUM('Jalan Kaki', 'Sepeda', 'Motor', 'Mobil', 'Angkutan Umum') NULL,
    waktu_tempuh INT NULL COMMENT 'Waktu tempuh dalam menit',
    
    -- Data Beasiswa dan Bantuan (sesuai format data)
    penerima_beasiswa BOOLEAN DEFAULT FALSE,
    jenis_beasiswa VARCHAR(100) NULL,
    penerima_kip BOOLEAN DEFAULT FALSE,
    no_kip VARCHAR(20) NULL,
    penerima_pkh BOOLEAN DEFAULT FALSE,
    
    -- Data Minat dan Bakat (sesuai format data)
    hobi JSON NULL COMMENT 'Array hobi siswa',
    cita_cita JSON NULL COMMENT 'Array cita-cita',
    prestasi JSON NULL COMMENT 'Array prestasi yang diraih',
    ekstrakurikuler JSON NULL COMMENT 'Array ekstrakurikuler yang diikuti',
    
    -- System fields
    status_aktif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (kelas_id) REFERENCES kelas(id) ON DELETE SET NULL,
    FOREIGN KEY (tahun_ajaran_id) REFERENCES tahun_ajaran(id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_nisn (nisn),
    INDEX idx_nis (nis),
    INDEX idx_nik (nik),
    INDEX idx_nama (nama_lengkap),
    INDEX idx_kelas (kelas_id),
    INDEX idx_status_aktif (status_aktif)
);
```

---

## 👪 **4. ORANG TUA/WALI TABLES (SESUAI FORMAT DATA)**

### **4.1 Orang Tua Table (Lengkap sesuai format data)**
```sql
CREATE TABLE orang_tua (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    siswa_id BIGINT NOT NULL,
    
    -- Data Identitas Ayah (sesuai format data)
    nama_ayah VARCHAR(100) NOT NULL,
    nik_ayah VARCHAR(16) UNIQUE NULL COMMENT 'NIK ayah (16 digit)',
    tempat_lahir_ayah VARCHAR(50) NULL,
    tanggal_lahir_ayah DATE NULL,
    pendidikan_ayah ENUM('SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3') NULL,
    pekerjaan_ayah VARCHAR(100) NOT NULL,
    penghasilan_ayah DECIMAL(15,2) NULL COMMENT 'Penghasilan per bulan',
    nomor_hp_ayah VARCHAR(15) NULL,
    email_ayah VARCHAR(100) NULL,
    
    -- Data Identitas Ibu (sesuai format data)
    nama_ibu VARCHAR(100) NOT NULL,
    nik_ibu VARCHAR(16) UNIQUE NULL COMMENT 'NIK ibu (16 digit)',
    tempat_lahir_ibu VARCHAR(50) NULL,
    tanggal_lahir_ibu DATE NULL,
    pendidikan_ibu ENUM('SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3') NULL,
    pekerjaan_ibu VARCHAR(100) NOT NULL,
    penghasilan_ibu DECIMAL(15,2) NULL COMMENT 'Penghasilan per bulan',
    nomor_hp_ibu VARCHAR(15) NULL,
    email_ibu VARCHAR(100) NULL,
    
    -- Data Wali (jika berbeda dengan ortu) (sesuai format data)
    nama_wali VARCHAR(100) NULL,
    nik_wali VARCHAR(16) NULL,
    hubungan_wali VARCHAR(50) NULL COMMENT 'Hubungan dengan siswa',
    pekerjaan_wali VARCHAR(100) NULL,
    penghasilan_wali DECIMAL(15,2) NULL,
    nomor_hp_wali VARCHAR(15) NULL,
    email_wali VARCHAR(100) NULL,
    
    -- Data Ekonomi Keluarga (sesuai format data)
    status_ekonomi ENUM('Mampu', 'Kurang Mampu', 'Tidak Mampu') NULL,
    penghasilan_total DECIMAL(15,2) NULL COMMENT 'Total penghasilan keluarga',
    jumlah_tanggungan INT NULL COMMENT 'Jumlah anggota keluarga',
    kepemilikan_rumah ENUM('Milik Sendiri', 'Sewa', 'Menumpang') NULL,
    penerima_pkh BOOLEAN DEFAULT FALSE COMMENT 'Penerima Program Keluarga Harapan',
    
    -- Data Kontak Darurat
    nomor_hp_darurat VARCHAR(15) NULL,
    alamat_ortu TEXT NULL COMMENT 'Alamat orang tua (jika berbeda dengan siswa)',
    
    -- System fields
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_siswa (siswa_id),
    INDEX idx_nik_ayah (nik_ayah),
    INDEX idx_nik_ibu (nik_ibu)
);
```

---

## 🏫 **5. TENDIK TABLES (SESUAI FORMAT DATA)**

### **5.1 Tendik Table (Lengkap sesuai format data)**
```sql
CREATE TABLE tendik (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    
    -- Data Identitas Utama (sama dengan guru)
    nip VARCHAR(18) UNIQUE NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    nama_panggilan VARCHAR(50) NULL,
    jenis_kelamin ENUM('L', 'P') NOT NULL,
    tempat_lahir VARCHAR(50) NOT NULL,
    tanggal_lahir DATE NOT NULL,
    agama ENUM('Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu') NOT NULL,
    status_pernikahan ENUM('Belum Menikah', 'Menikah', 'Cerai') NULL,
    
    -- Data Kontak dan Alamat (sama dengan guru)
    alamat_lengkap TEXT NOT NULL,
    rt_rw VARCHAR(10) NULL,
    kelurahan VARCHAR(50) NOT NULL,
    kecamatan VARCHAR(50) NOT NULL,
    kabupaten_kota VARCHAR(50) NOT NULL,
    provinsi VARCHAR(50) NOT NULL,
    kode_pos VARCHAR(5) NULL,
    nomor_hp VARCHAR(15) NOT NULL,
    email VARCHAR(100) NULL,
    nomor_hp_darurat VARCHAR(15) NULL,
    
    -- Data Kepegawaian (sama dengan guru)
    jenis_ptk ENUM('Guru Kelas', 'Guru Mapel', 'Tenaga Kependidikan') NOT NULL,
    status_kepegawaian ENUM('PNS', 'PPPK', 'GTT', 'PTT', 'Honorer') NOT NULL,
    golongan VARCHAR(10) NULL,
    jabatan VARCHAR(100) NOT NULL,
    unit_kerja VARCHAR(100) NOT NULL,
    tanggal_masuk DATE NOT NULL,
    tanggal_keluar DATE NULL,
    masa_kerja INT NULL,
    
    -- Data Pendidikan (sama dengan guru)
    pendidikan_terakhir ENUM('SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3') NOT NULL,
    jurusan_pendidikan VARCHAR(100) NULL,
    nama_universitas VARCHAR(100) NULL,
    tahun_lulus INT(4) NULL,
    no_ijazah VARCHAR(50) NULL,
    
    -- Tugas Khusus Tendik
    spesialisasi_kerja VARCHAR(100) NULL COMMENT 'Spesialisasi kerja tendik',
    tugas_tambahan JSON NULL COMMENT 'Array tugas tambahan lainnya',
    
    -- System fields
    status_aktif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    
    -- Indexes
    INDEX idx_nip (nip),
    INDEX idx_nama (nama_lengkap),
    INDEX idx_status_aktif (status_aktif)
);
```

---

## 📚 **6. MASTER DATA TABLES**

### **6.1 Kelas Table**
```sql
CREATE TABLE kelas (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(20) NOT NULL COMMENT 'Contoh: 10-IPA-1, 11-IPS-2',
    tingkat ENUM('10', '11', '12') NOT NULL,
    jurusan VARCHAR(50) NULL COMMENT 'IPA, IPS, Bahasa, dll',
    wali_kelas_id BIGINT NULL,
    kapasitas INT DEFAULT 36,
    ruang_kelas VARCHAR(20) NULL,
    tahun_ajaran_id BIGINT NOT NULL,
    status_aktif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (wali_kelas_id) REFERENCES guru(id) ON DELETE SET NULL,
    FOREIGN KEY (tahun_ajaran_id) REFERENCES tahun_ajaran(id) ON DELETE CASCADE,
    
    INDEX idx_nama (nama),
    INDEX idx_tingkat (tingkat),
    INDEX idx_wali_kelas (wali_kelas_id)
);
```

### **6.2 Tahun Ajaran Table**
```sql
CREATE TABLE tahun_ajaran (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(20) NOT NULL COMMENT 'Contoh: 2024/2025',
    kode VARCHAR(10) NOT NULL COMMENT 'Contoh: 2024-2025',
    tanggal_mulai DATE NOT NULL,
    tanggal_selesai DATE NOT NULL,
    is_aktif BOOLEAN DEFAULT FALSE,
    is_current BOOLEAN DEFAULT FALSE,
    status ENUM('draft', 'aktif', 'selesai') DEFAULT 'draft',
    created_by BIGINT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    
    INDEX idx_kode (kode),
    INDEX idx_aktif (is_aktif)
);
```

### **6.3 Mata Pelajaran Table**
```sql
CREATE TABLE mata_pelajaran (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    kode VARCHAR(10) NOT NULL,
    tingkat ENUM('10', '11', '12') NOT NULL,
    jurusan VARCHAR(50) NULL COMMENT 'IPA, IPS, Bahasa, atau NULL untuk semua',
    jam_per_minggu INT DEFAULT 2,
    status_aktif BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    INDEX idx_kode (kode),
    INDEX idx_tingkat (tingkat),
    INDEX idx_jurusan (jurusan)
);
```

---

## 📊 **7. TABLES LAINNYA (TETAP SAMA)**

### **7.1 Presensi System**
```sql
-- Tetap sama dengan skema sebelumnya
CREATE TABLE presensi (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    tanggal DATE NOT NULL,
    jam_masuk TIME,
    jam_keluar TIME,
    status ENUM('hadir', 'terlambat', 'sakit', 'izin', 'alpha') DEFAULT 'alpha',
    lokasi_lat DECIMAL(10, 8),
    lokasi_lng DECIMAL(11, 8),
    qr_code VARCHAR(255),
    foto_absen VARCHAR(255),
    keterangan TEXT,
    divalidasi_oleh BIGINT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (divalidasi_oleh) REFERENCES users(id) ON DELETE SET NULL
);
```

### **7.2 Kredit Poin System**
```sql
-- Tetap sama dengan skema sebelumnya
CREATE TABLE kategori_kredit_poin (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    jenis ENUM('positif', 'negatif') NOT NULL,
    nilai_default INT NOT NULL,
    deskripsi TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE kredit_poin (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    siswa_id BIGINT NOT NULL,
    kategori_id BIGINT NOT NULL,
    nilai INT NOT NULL,
    deskripsi TEXT NOT NULL,
    tanggal DATE NOT NULL,
    pelapor_id BIGINT NOT NULL,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (siswa_id) REFERENCES siswa(id) ON DELETE CASCADE,
    FOREIGN KEY (kategori_id) REFERENCES kategori_kredit_poin(id) ON DELETE CASCADE,
    FOREIGN KEY (pelapor_id) REFERENCES users(id) ON DELETE CASCADE
);
```

### **7.3 Notification System**
```sql
-- Tetap sama dengan skema sebelumnya
CREATE TABLE notifikasi (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    jenis ENUM('info', 'warning', 'success', 'error') NOT NULL,
    judul VARCHAR(255) NOT NULL,
    pesan TEXT NOT NULL,
    status_baca BOOLEAN DEFAULT FALSE,
    tanggal_kirim TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    data_terkait JSON,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE whatsapp_logs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NOT NULL,
    nomor_wa VARCHAR(20) NOT NULL,
    pesan TEXT NOT NULL,
    template VARCHAR(100),
    status ENUM('pending', 'sent', 'delivered', 'failed') DEFAULT 'pending',
    tanggal_kirim TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    response_data JSON,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);
```

---

## 🔍 **8. VALIDASI SESUAI FORMAT DATA**

### **8.1 Validasi Guru**
```sql
-- Constraints untuk validasi guru
ALTER TABLE guru ADD CONSTRAINT chk_nip_format CHECK (LENGTH(nip) = 18 AND nip REGEXP '^[0-9]+$');
ALTER TABLE guru ADD CONSTRAINT chk_nuptk_format CHECK (nuptk IS NULL OR (LENGTH(nuptk) = 16 AND nuptk REGEXP '^[0-9]+$'));
ALTER TABLE guru ADD CONSTRAINT chk_hp_format CHECK (nomor_hp REGEXP '^08[0-9]{8,13}$');
ALTER TABLE guru ADD CONSTRAINT chk_email_format CHECK (email IS NULL OR email REGEXP '^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$');
```

### **8.2 Validasi Siswa**
```sql
-- Constraints untuk validasi siswa
ALTER TABLE siswa ADD CONSTRAINT chk_nisn_format CHECK (LENGTH(nisn) = 10 AND nisn REGEXP '^[0-9]+$');
ALTER TABLE siswa ADD CONSTRAINT chk_nis_format CHECK (LENGTH(nis) = 10 AND nis REGEXP '^[0-9]+$');
ALTER TABLE siswa ADD CONSTRAINT chk_nik_format CHECK (LENGTH(nik) = 16 AND nik REGEXP '^[0-9]+$');
ALTER TABLE siswa ADD CONSTRAINT chk_no_kk_format CHECK (LENGTH(no_kk) = 16 AND no_kk REGEXP '^[0-9]+$');
```

### **8.3 Validasi Orang Tua**
```sql
-- Constraints untuk validasi orang tua
ALTER TABLE orang_tua ADD CONSTRAINT chk_nik_ayah_format CHECK (nik_ayah IS NULL OR (LENGTH(nik_ayah) = 16 AND nik_ayah REGEXP '^[0-9]+$'));
ALTER TABLE orang_tua ADD CONSTRAINT chk_nik_ibu_format CHECK (nik_ibu IS NULL OR (LENGTH(nik_ibu) = 16 AND nik_ibu REGEXP '^[0-9]+$'));
ALTER TABLE orang_tua ADD CONSTRAINT chk_hp_ayah_format CHECK (nomor_hp_ayah IS NULL OR nomor_hp_ayah REGEXP '^08[0-9]{8,13}$');
ALTER TABLE orang_tua ADD CONSTRAINT chk_hp_ibu_format CHECK (nomor_hp_ibu IS NULL OR nomor_hp_ibu REGEXP '^08[0-9]{8,13}$');
```

---

## 📈 **9. MIGRATION STRATEGY**

### **9.1 Migration Files Order**
```sql
-- 1. Core system
2024_01_01_000001_create_users_table.php
2024_01_01_000002_create_roles_table.php
2024_01_01_000003_create_user_roles_table.php

-- 2. Master data
2024_01_01_000004_create_tahun_ajaran_table.php
2024_01_01_000005_create_mata_pelajaran_table.php
2024_01_01_000006_create_kelas_table.php

-- 3. User management (sesuai format data)
2024_01_01_000007_create_guru_table.php
2024_01_01_000008_create_siswa_table.php
2024_01_01_000009_create_tendik_table.php
2024_01_01_000010_create_orang_tua_table.php

-- 4. System modules
2024_01_01_000011_create_presensi_table.php
2024_01_01_000012_create_kategori_kredit_poin_table.php
2024_01_01_000013_create_kredit_poin_table.php
2024_01_01_000014_create_notifikasi_table.php
2024_01_01_000015_create_whatsapp_logs_table.php
```

### **9.2 Seeder Strategy**
```php
// Seeder untuk data master
class TahunAjaranSeeder extends Seeder
{
    public function run()
    {
        TahunAjaran::create([
            'nama' => '2024/2025',
            'kode' => '2024-2025',
            'tanggal_mulai' => '2024-07-15',
            'tanggal_selesai' => '2025-06-14',
            'is_aktif' => true,
            'is_current' => true,
            'status' => 'aktif'
        ]);
    }
}

// Seeder untuk mata pelajaran
class MataPelajaranSeeder extends Seeder
{
    public function run()
    {
        $mataPelajaran = [
            ['nama' => 'Matematika', 'kode' => 'MAT', 'tingkat' => '10'],
            ['nama' => 'Bahasa Indonesia', 'kode' => 'BIN', 'tingkat' => '10'],
            ['nama' => 'Bahasa Inggris', 'kode' => 'BIG', 'tingkat' => '10'],
            ['nama' => 'Fisika', 'kode' => 'FIS', 'tingkat' => '10', 'jurusan' => 'IPA'],
            ['nama' => 'Kimia', 'kode' => 'KIM', 'tingkat' => '10', 'jurusan' => 'IPA'],
            ['nama' => 'Biologi', 'kode' => 'BIO', 'tingkat' => '10', 'jurusan' => 'IPA'],
            ['nama' => 'Ekonomi', 'kode' => 'EKO', 'tingkat' => '10', 'jurusan' => 'IPS'],
            ['nama' => 'Geografi', 'kode' => 'GEO', 'tingkat' => '10', 'jurusan' => 'IPS'],
            ['nama' => 'Sosiologi', 'kode' => 'SOS', 'tingkat' => '10', 'jurusan' => 'IPS'],
        ];
        
        foreach ($mataPelajaran as $mp) {
            MataPelajaran::create($mp);
        }
    }
}
```

---

## ✅ **KESIMPULAN**

Skema database yang telah diperbaiki ini:

1. **✅ Sesuai dengan format data** yang ada di dokumentasi
2. **✅ Mengikuti standar pendidikan Indonesia**
3. **✅ Memiliki validasi yang ketat** sesuai format data
4. **✅ Mendukung import/export** dari Excel template
5. **✅ Memiliki indexing yang optimal** untuk performance
6. **✅ Mendukung semua fitur** yang direncanakan

**Total Tables**: 15+ tables dengan struktur yang lengkap dan sesuai format data referensi.

---

*Skema ini siap untuk diimplementasikan dan akan mendukung semua fitur sistem manajemen kesiswaan sesuai dengan format data yang telah ditetapkan.*
