# Template Import Data Sistem Kesiswaan

## 🗂️ **Daftar Template Import Yang Tersedia**

### **1. Template Import Data Siswa**
**File**: `template_import_siswa.xlsx`
**Tujuan**: Import data siswa baru atau update data existing

#### **Kolom Wajib:**
| Field | Deskripsi | Contoh | Validasi |
|-------|-----------|---------|----------|
| `nis` | Nomor Induk Siswa | 2024001 | Unique, numerik |
| `nisn` | Nomor Induk Siswa Nasional | 1234567890 | 10 digit, unique |
| `nama_lengkap` | Nama lengkap siswa | Ahmad Budi Santoso | Max 100 karakter |
| `kelas` | Kode kelas | 10-IPA-1 | Harus exist di master kelas |
| `jenis_kelamin` | Jenis kelamin | L/P | L atau P |
| `tempat_lahir` | Tempat lahir | Jakarta | Max 50 karakter |
| `tanggal_lahir` | Tanggal lahir | 2008-01-15 | Format: YYYY-MM-DD |
| `agama` | Agama siswa | Islam | Sesuai pilihan master |
| `alamat_lengkap` | Alamat lengkap | Jl. Merdeka No 123 | Max 255 karakter |
| `nama_ayah` | Nama ayah kandung | Budi Santoso | Max 100 karakter |
| `nama_ibu` | Nama ibu kandung | Siti Aminah | Max 100 karakter |
| `nomor_hp_ortu` | No HP orang tua | 081234567890 | Format: 08xxxxxxxxxx |
| `email_ortu` | Email orang tua | ortu@email.com | Format email valid |

#### **Kolom Opsional:**
| Field | Deskripsi | Contoh |
|-------|-----------|---------|
| `anak_ke` | Anak keberapa | 2 |
| `jumlah_saudara` | Jumlah saudara | 3 |
| `golongan_darah` | Golongan darah | A |
| `tinggi_badan` | Tinggi badan (cm) | 165 |
| `berat_badan` | Berat badan (kg) | 55 |
| `asal_sekolah` | Asal sekolah | SMP Negeri 1 Jakarta |
| `nomor_hp_siswa` | No HP siswa | 081234567891 |
| `hobi` | Hobi siswa | Membaca, Olahraga |

### **2. Template Import Data Orang Tua**
**File**: `template_import_orang_tua.xlsx`
**Tujuan**: Import/update data lengkap orang tua siswa

#### **Kolom Wajib:**
| Field | Deskripsi | Contoh | Validasi |
|-------|-----------|---------|----------|
| `nis_siswa` | NIS siswa (referensi) | 2024001 | Harus exist di tabel siswa |
| `nama_ayah` | Nama ayah lengkap | Budi Santoso, S.E | Max 100 karakter |
| `nik_ayah` | NIK ayah | 3171234567890001 | 16 digit |
| `pekerjaan_ayah` | Pekerjaan ayah | Pegawai Swasta | Max 50 karakter |
| `penghasilan_ayah` | Penghasilan ayah | 5000000 | Numerik |
| `nama_ibu` | Nama ibu lengkap | Siti Aminah, S.Pd | Max 100 karakter |
| `nik_ibu` | NIK ibu | 3171234567890002 | 16 digit |
| `pekerjaan_ibu` | Pekerjaan ibu | Guru | Max 50 karakter |
| `penghasilan_ibu` | Penghasilan ibu | 4000000 | Numerik |

### **3. Template Import Data Pegawai**
**File**: `template_import_pegawai.xlsx`
**Tujuan**: Import data pegawai (guru & staf)

#### **Kolom Wajib:**
| Field | Deskripsi | Contoh | Validasi |
|-------|-----------|---------|----------|
| `nip` | Nomor Induk Pegawai | 196801011990031001 | Unique |
| `nama_lengkap` | Nama lengkap | Dr. Ahmad Hidayat, M.Pd | Max 100 karakter |
| `jenis_ptk` | Jenis PTK | Guru/Tenaga Kependidikan | Pilihan dropdown |
| `status_kepegawaian` | Status kepegawaian | PNS/PPPK/GTT/PTT | Pilihan dropdown |
| `jenis_kelamin` | Jenis kelamin | L/P | L atau P |
| `tempat_lahir` | Tempat lahir | Bandung | Max 50 karakter |
| `tanggal_lahir` | Tanggal lahir | 1968-01-01 | Format: YYYY-MM-DD |
| `pendidikan_terakhir` | Pendidikan terakhir | S2 Pendidikan Matematika | Max 100 karakter |
| `tanggal_masuk` | Tanggal masuk kerja | 1990-03-01 | Format: YYYY-MM-DD |

### **4. Template Import Data Kelas**
**File**: `template_import_kelas.xlsx`
**Tujuan**: Import setup kelas per tahun ajaran

#### **Kolom Wajib:**
| Field | Deskripsi | Contoh | Validasi |
|-------|-----------|---------|----------|
| `nama_kelas` | Nama kelas | 10-IPA-1 | Unique per tahun ajaran |
| `tingkat` | Tingkat kelas | 10 | 10/11/12 |
| `jurusan` | Kode jurusan | IPA | IPA/IPS/BHS |
| `wali_kelas_nip` | NIP wali kelas | 196801011990031001 | Harus exist di pegawai |
| `kapasitas` | Kapasitas siswa | 36 | Max 40 |
| `ruang_kelas` | Ruang kelas | R.10A | Max 20 karakter |

## 📥 **Proses Import Data**

### **1. Download Template**
```
Dashboard → Data Master → Import Data → Download Template
- Pilih jenis template yang dibutuhkan
- Download file Excel dengan format yang sudah ditetapkan
- File berisi header dan contoh data
```

### **2. Isi Data**
```
- Buka file template di Excel/Google Sheets
- Isi data sesuai dengan format yang sudah ditentukan
- Jangan menghapus atau mengubah header kolom
- Pastikan data sesuai dengan validasi yang ditetapkan
- Save file dalam format .xlsx atau .csv
```

### **3. Upload & Validasi**
```
Dashboard → Data Master → Import Data → Upload File
- Pilih file yang sudah diisi
- Sistem akan melakukan validasi otomatis
- Review data yang akan diimport
- Perbaiki error jika ada
```

### **4. Konfirmasi Import**
```
- Review summary import (jumlah data baru, update, error)
- Sistem akan membuat backup data existing
- Konfirmasi untuk melanjutkan import
- Monitor progress import real-time
```

## ⚠️ **Aturan Validasi Import**

### **Validasi Data Siswa:**
- NIS harus unique dalam sistem
- NISN harus 10 digit dan unique
- Kelas harus sudah exist di master kelas tahun ajaran aktif
- Format tanggal harus YYYY-MM-DD
- Email harus format valid
- No HP harus format Indonesia (08xxxxxxxxxx)
- Jika siswa sudah exist, data akan di-update

### **Validasi Data Orang Tua:**
- NIS siswa harus sudah exist di database
- NIK harus 16 digit
- Penghasilan harus berupa angka
- Minimal data ayah dan ibu harus diisi
- Data wali opsional

### **Validasi Data Pegawai:**
- NIP harus unique
- Status kepegawaian harus sesuai pilihan yang tersedia
- Jenis PTK harus valid
- Tanggal lahir tidak boleh lebih dari tanggal masuk kerja
- Untuk guru, mata pelajaran harus diisi

### **Validasi Data Kelas:**
- Nama kelas harus unique per tahun ajaran
- Wali kelas (NIP) harus exist dan berstatus guru
- Satu guru hanya bisa menjadi wali kelas maksimal 1 kelas
- Kapasitas maksimal 40 siswa per kelas

## 🔄 **Fitur Advanced Import**

### **1. Import Bertahap (Batch Processing)**
- File besar akan diproses secara bertahap
- Progress bar real-time
- Notifikasi ketika selesai
- Resume import jika terputus

### **2. Data Mapping & Transformation**
- Mapping kolom custom jika header berbeda
- Auto-transform data (uppercase nama, format tanggal)
- Validasi referensi data (foreign key)
- Duplicate detection & merging

### **3. Rollback & Recovery**
- Backup otomatis sebelum import
- Rollback ke kondisi sebelum import
- Export data yang error untuk perbaikan
- Audit trail lengkap untuk tracking

### **4. Scheduled Import**
- Import terjadwal (harian, mingguan)
- Auto-download dari server/cloud
- Notifikasi hasil import via email
- Integration dengan sistem eksternal (SIAKAD, EMIS)

## 📊 **Monitoring & Reporting**

### **Dashboard Import:**
- Summary import terakhir
- Grafik trend import data
- Alert untuk data yang perlu review
- Quick stats (total siswa, pegawai, dll)

### **Audit Log Import:**
- History semua aktivitas import
- User yang melakukan import
- File yang diupload
- Hasil validasi dan error
- Waktu proses import

### **Quality Score Data:**
- Persentase kelengkapan data siswa
- Data yang perlu di-update
- Rekomendasi perbaikan data
- Benchmark dengan sekolah lain

Sistem import ini memastikan **integritas data tinggi** dengan **proses yang user-friendly** dan **error handling yang robust**! 📈