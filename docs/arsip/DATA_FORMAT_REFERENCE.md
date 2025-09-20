# Format Data Referensi Sistem Kesiswaan

## 📋 **Gambaran Umum**

Dokumentasi ini berisi format data referensi yang digunakan dalam Sistem Manajemen Kesiswaan, berdasarkan file Excel template yang tersedia di folder `docs/data format/`. Format ini mengikuti standar pendidikan Indonesia dan best practices untuk sistem informasi sekolah.

## 📊 **File Referensi yang Tersedia**

### 1. **Data Guru.xlsx**
Template dan format data untuk informasi lengkap guru/pegawai

### 2. **Data Siswa.xlsx** 
Template dan format data untuk informasi lengkap siswa

### 3. **Formulir Guru.xlsx**
Form input dan validasi data guru baru

### 4. **Formulir Siswa.xlsx**
Form input dan validasi data siswa baru

---

## 👨‍🏫 **FORMAT DATA GURU/PEGAWAI**

### **Data Identitas Utama**
| Field | Tipe Data | Panjang | Required | Keterangan |
|-------|-----------|---------|----------|------------|
| `nip` | STRING | 18 | YES | Nomor Induk Pegawai (unique) |
| `nuptk` | STRING | 16 | NO | Nomor Unik Pendidik dan Tenaga Kependidikan |
| `nama_lengkap` | STRING | 100 | YES | Nama lengkap beserta gelar |
| `nama_panggilan` | STRING | 50 | NO | Nama yang biasa dipanggil |
| `jenis_kelamin` | ENUM | - | YES | L/P |
| `tempat_lahir` | STRING | 50 | YES | Kota/kabupaten kelahiran |
| `tanggal_lahir` | DATE | - | YES | Format: YYYY-MM-DD |
| `agama` | ENUM | - | YES | Islam/Kristen/Katolik/Hindu/Buddha/Konghucu |
| `status_pernikahan` | ENUM | - | NO | Belum Menikah/Menikah/Cerai |

### **Data Kontak dan Alamat**
| Field | Tipe Data | Panjang | Required | Keterangan |
|-------|-----------|---------|----------|------------|
| `alamat_lengkap` | TEXT | 255 | YES | Alamat domisili lengkap |
| `rt_rw` | STRING | 10 | NO | RT/RW (contoh: 001/002) |
| `kelurahan` | STRING | 50 | YES | Nama kelurahan |
| `kecamatan` | STRING | 50 | YES | Nama kecamatan |
| `kabupaten_kota` | STRING | 50 | YES | Nama kabupaten/kota |
| `provinsi` | STRING | 50 | YES | Nama provinsi |
| `kode_pos` | STRING | 5 | NO | Kode pos wilayah |
| `nomor_hp` | STRING | 15 | YES | Format: 08xxxxxxxxxx |
| `email` | STRING | 100 | NO | Email aktif |
| `nomor_hp_darurat` | STRING | 15 | NO | Kontak darurat |

### **Data Kepegawaian**
| Field | Tipe Data | Panjang | Required | Keterangan |
|-------|-----------|---------|----------|------------|
| `jenis_ptk` | ENUM | - | YES | Guru Kelas/Guru Mapel/Tenaga Kependidikan |
| `status_kepegawaian` | ENUM | - | YES | PNS/PPPK/GTT/PTT/Honorer |
| `golongan` | STRING | 10 | NO | Golongan kepegawaian (untuk PNS/PPPK) |
| `jabatan` | STRING | 100 | YES | Jabatan/posisi |
| `unit_kerja` | STRING | 100 | YES | Unit kerja/bagian |
| `tanggal_masuk` | DATE | - | YES | Tanggal mulai bekerja |
| `tanggal_keluar` | DATE | - | NO | Tanggal berhenti (jika ada) |
| `masa_kerja` | INTEGER | - | NO | Masa kerja dalam tahun |

### **Data Pendidikan dan Kompetensi**
| Field | Tipe Data | Panjang | Required | Keterangan |
|-------|-----------|---------|----------|------------|
| `pendidikan_terakhir` | ENUM | - | YES | SD/SMP/SMA/D1/D2/D3/S1/S2/S3 |
| `jurusan_pendidikan` | STRING | 100 | NO | Jurusan/program studi |
| `nama_universitas` | STRING | 100 | NO | Nama perguruan tinggi |
| `tahun_lulus` | INTEGER | 4 | NO | Tahun kelulusan |
| `no_ijazah` | STRING | 50 | NO | Nomor ijazah |
| `mata_pelajaran` | STRING | 100 | NO | Mata pelajaran yang diampu |
| `kelas_yang_diajar` | JSON | - | NO | Array kelas yang diajar |
| `is_wali_kelas` | BOOLEAN | - | NO | Status wali kelas (true/false) |
| `kelas_wali` | STRING | 20 | NO | Kelas yang dibimbing (jika wali kelas) |

### **Data Sertifikasi**
| Field | Tipe Data | Panjang | Required | Keterangan |
|-------|-----------|---------|----------|------------|
| `status_sertifikasi` | ENUM | - | NO | Tersertifikasi/Belum Tersertifikasi/Dalam Proses |
| `no_sertifikat_pendidik` | STRING | 50 | NO | Nomor sertifikat pendidik |
| `tanggal_sertifikasi` | DATE | - | NO | Tanggal lulus sertifikasi |
| `lembaga_sertifikasi` | STRING | 100 | NO | Lembaga penerbit sertifikat |
| `sertifikat_lainnya` | JSON | - | NO | Array sertifikat tambahan |

### **Data Tugas Tambahan**
| Field | Tipe Data | Panjang | Required | Keterangan |
|-------|-----------|---------|----------|------------|
| `is_konselor_bk` | BOOLEAN | - | NO | Status konselor BK (true/false) |
| `is_pembina_osis` | BOOLEAN | - | NO | Status pembina OSIS (true/false) |
| `ekstrakurikuler` | JSON | - | NO | Array ekstrakurikuler yang dibina |
| `tugas_tambahan` | JSON | - | NO | Array tugas tambahan lainnya |

---

## 👨‍🎓 **FORMAT DATA SISWA**

### **Data Identitas Utama**
| Field | Tipe Data | Panjang | Required | Keterangan |
|-------|-----------|---------|----------|------------|
| `nisn` | STRING | 10 | YES | Nomor Induk Siswa Nasional (unique) |
| `nis` | STRING | 10 | YES | Nomor Induk Siswa sekolah (unique) |
| `nama_lengkap` | STRING | 100 | YES | Nama lengkap sesuai akta kelahiran |
| `nama_panggilan` | STRING | 50 | NO | Nama yang biasa dipanggil |
| `jenis_kelamin` | ENUM | - | YES | L/P |
| `tempat_lahir` | STRING | 50 | YES | Kota/kabupaten kelahiran |
| `tanggal_lahir` | DATE | - | YES | Format: YYYY-MM-DD |
| `agama` | ENUM | - | YES | Islam/Kristen/Katolik/Hindu/Buddha/Konghucu |
| `kewarganegaraan` | STRING | 20 | NO | Default: Indonesia |

### **Data Keluarga**
| Field | Tipe Data | Panjang | Required | Keterangan |
|-------|-----------|---------|----------|------------|
| `nik` | STRING | 16 | YES | NIK siswa (unique) |
| `no_kk` | STRING | 16 | YES | Nomor Kartu Keluarga |
| `no_akta_kelahiran` | STRING | 50 | NO | Nomor akta kelahiran |
| `anak_ke` | INTEGER | - | NO | Anak keberapa dalam keluarga |
| `jumlah_saudara` | INTEGER | - | NO | Jumlah saudara kandung |
| `bahasa_sehari_hari` | STRING | 50 | NO | Bahasa yang digunakan sehari-hari |

### **Data Fisik dan Kesehatan**
| Field | Tipe Data | Panjang | Required | Keterangan |
|-------|-----------|---------|----------|------------|
| `tinggi_badan` | DECIMAL | (5,2) | NO | Tinggi badan dalam cm |
| `berat_badan` | DECIMAL | (5,2) | NO | Berat badan dalam kg |
| `golongan_darah` | ENUM | - | NO | A/B/AB/O |
| `riwayat_penyakit` | JSON | - | NO | Array riwayat penyakit |
| `kelainan_fisik` | TEXT | 255 | NO | Keterangan kelainan fisik (jika ada) |

### **Data Kontak dan Alamat**
| Field | Tipe Data | Panjang | Required | Keterangan |
|-------|-----------|---------|----------|------------|
| `alamat_lengkap` | TEXT | 255 | YES | Alamat domisili lengkap |
| `rt_rw` | STRING | 10 | NO | RT/RW (contoh: 001/002) |
| `kelurahan` | STRING | 50 | YES | Nama kelurahan |
| `kecamatan` | STRING | 50 | YES | Nama kecamatan |
| `kabupaten_kota` | STRING | 50 | YES | Nama kabupaten/kota |
| `provinsi` | STRING | 50 | YES | Nama provinsi |
| `kode_pos` | STRING | 5 | NO | Kode pos wilayah |
| `nomor_hp_siswa` | STRING | 15 | NO | HP siswa (jika ada) |
| `email_siswa` | STRING | 100 | NO | Email siswa (jika ada) |

### **Data Akademik**
| Field | Tipe Data | Panjang | Required | Keterangan |
|-------|-----------|---------|----------|------------|
| `kelas` | STRING | 20 | YES | Kelas saat ini |
| `jurusan` | STRING | 50 | NO | Jurusan (untuk SMA/SMK) |
| `angkatan` | INTEGER | 4 | YES | Tahun masuk sekolah |
| `tahun_ajaran_id` | INTEGER | - | YES | Foreign key tahun ajaran |
| `asal_sekolah` | STRING | 100 | NO | Nama sekolah asal |
| `tanggal_masuk` | DATE | - | YES | Tanggal masuk sekolah |
| `status_siswa` | ENUM | - | YES | Aktif/Pindah/Lulus/DO/Mengundurkan Diri |

### **Data Transportasi**
| Field | Tipe Data | Panjang | Required | Keterangan |
|-------|-----------|---------|----------|------------|
| `jarak_ke_sekolah` | DECIMAL | (8,2) | NO | Jarak rumah ke sekolah (km) |
| `transportasi` | ENUM | - | NO | Jalan Kaki/Sepeda/Motor/Mobil/Angkutan Umum |
| `waktu_tempuh` | INTEGER | - | NO | Waktu tempuh dalam menit |

### **Data Beasiswa dan Bantuan**
| Field | Tipe Data | Panjang | Required | Keterangan |
|-------|-----------|---------|----------|------------|
| `penerima_beasiswa` | BOOLEAN | - | NO | Status penerima beasiswa |
| `jenis_beasiswa` | STRING | 100 | NO | Nama/jenis beasiswa |
| `penerima_kip` | BOOLEAN | - | NO | Penerima Kartu Indonesia Pintar |
| `no_kip` | STRING | 20 | NO | Nomor KIP |
| `penerima_pkh` | BOOLEAN | - | NO | Keluarga penerima PKH |

### **Data Minat dan Bakat**
| Field | Tipe Data | Panjang | Required | Keterangan |
|-------|-----------|---------|----------|------------|
| `hobi` | JSON | - | NO | Array hobi siswa |
| `cita_cita` | JSON | - | NO | Array cita-cita |
| `prestasi` | JSON | - | NO | Array prestasi yang diraih |
| `ekstrakurikuler` | JSON | - | NO | Array ekstrakurikuler yang diikuti |

---

## 👪 **FORMAT DATA ORANG TUA/WALI**

### **Data Identitas Ayah**
| Field | Tipe Data | Panjang | Required | Keterangan |
|-------|-----------|---------|----------|------------|
| `nama_ayah` | STRING | 100 | YES | Nama lengkap ayah |
| `nik_ayah` | STRING | 16 | NO | NIK ayah (unique) |
| `tempat_lahir_ayah` | STRING | 50 | NO | Tempat lahir ayah |
| `tanggal_lahir_ayah` | DATE | - | NO | Tanggal lahir ayah |
| `pendidikan_ayah` | ENUM | - | NO | Tingkat pendidikan terakhir |
| `pekerjaan_ayah` | STRING | 100 | YES | Pekerjaan ayah |
| `penghasilan_ayah` | DECIMAL | (15,2) | NO | Penghasilan per bulan |
| `nomor_hp_ayah` | STRING | 15 | NO | HP ayah |

### **Data Identitas Ibu**
| Field | Tipe Data | Panjang | Required | Keterangan |
|-------|-----------|---------|----------|------------|
| `nama_ibu` | STRING | 100 | YES | Nama lengkap ibu |
| `nik_ibu` | STRING | 16 | NO | NIK ibu (unique) |
| `tempat_lahir_ibu` | STRING | 50 | NO | Tempat lahir ibu |
| `tanggal_lahir_ibu` | DATE | - | NO | Tanggal lahir ibu |
| `pendidikan_ibu` | ENUM | - | NO | Tingkat pendidikan terakhir |
| `pekerjaan_ibu` | STRING | 100 | YES | Pekerjaan ibu |
| `penghasilan_ibu` | DECIMAL | (15,2) | NO | Penghasilan per bulan |
| `nomor_hp_ibu` | STRING | 15 | NO | HP ibu |

### **Data Wali (jika berbeda dengan ortu)**
| Field | Tipe Data | Panjang | Required | Keterangan |
|-------|-----------|---------|----------|------------|
| `nama_wali` | STRING | 100 | NO | Nama lengkap wali |
| `nik_wali` | STRING | 16 | NO | NIK wali |
| `hubungan_wali` | STRING | 50 | NO | Hubungan dengan siswa |
| `pekerjaan_wali` | STRING | 100 | NO | Pekerjaan wali |
| `penghasilan_wali` | DECIMAL | (15,2) | NO | Penghasilan wali |
| `nomor_hp_wali` | STRING | 15 | NO | HP wali |

### **Data Ekonomi Keluarga**
| Field | Tipe Data | Panjang | Required | Keterangan |
|-------|-----------|---------|----------|------------|
| `status_ekonomi` | ENUM | - | NO | Mampu/Kurang Mampu/Tidak Mampu |
| `penghasilan_total` | DECIMAL | (15,2) | NO | Total penghasilan keluarga |
| `jumlah_tanggungan` | INTEGER | - | NO | Jumlah anggota keluarga |
| `kepemilikan_rumah` | ENUM | - | NO | Milik Sendiri/Sewa/Menumpang |
| `penerima_pkh` | BOOLEAN | - | NO | Penerima Program Keluarga Harapan |

---

## 📝 **ATURAN VALIDASI DATA**

### **Validasi Umum**
- **NIK**: Harus 16 digit numerik, unique dalam sistem
- **Email**: Format email yang valid
- **Nomor HP**: Dimulai dengan 08, panjang 10-15 digit
- **Tanggal**: Format YYYY-MM-DD, tidak boleh di masa depan
- **Enum Fields**: Harus sesuai dengan pilihan yang tersedia

### **Validasi Khusus Guru**
- **NIP**: Unique, format sesuai standar Kemendikbud
- **NUPTK**: 16 digit, unique (jika ada)
- **Mata Pelajaran**: Harus sesuai dengan kurikulum yang berlaku
- **Wali Kelas**: Satu guru hanya boleh menjadi wali satu kelas

### **Validasi Khusus Siswa**
- **NISN**: 10 digit numerik, unique secara nasional
- **NIS**: Unique dalam sekolah
- **Kelas**: Harus exist dalam master kelas aktif
- **Status**: Siswa aktif tidak boleh duplikat dalam satu kelas

### **Validasi Relasi Data**
- **Siswa-Orang Tua**: Satu siswa bisa memiliki multiple orang tua/wali
- **Guru-Kelas**: Wali kelas harus guru yang aktif
- **Tahun Ajaran**: Data harus sesuai tahun ajaran aktif

---

## 🔄 **PROSES IMPORT DAN EXPORT**

### **Import Data**
1. **Download Template**: Unduh template Excel dari sistem
2. **Isi Data**: Lengkapi data sesuai format yang ditetapkan
3. **Validasi**: Sistem akan memvalidasi data sebelum import
4. **Preview**: Review data yang akan diimport
5. **Konfirmasi**: Konfirmasi untuk menyimpan data

### **Export Data**
1. **Filter Data**: Pilih kriteria data yang akan di-export
2. **Format Export**: Pilih format (Excel, CSV, PDF)
3. **Download**: Unduh file hasil export

### **Error Handling**
- **Format Error**: Error akan ditampilkan dengan baris dan kolom yang bermasalah
- **Duplicate Data**: Sistem akan mendeteksi dan menampilkan data duplicate
- **Missing Data**: Field wajib yang kosong akan dilaporkan
- **Invalid Reference**: Foreign key yang tidak valid akan ditolak

---

## 📊 **CONTOH DATA VALID**

### **Contoh Data Guru**
```
NIP: 196801011990031001
Nama: Dr. Ahmad Hidayat, M.Pd
Jenis Kelamin: L
Tempat Lahir: Bandung
Tanggal Lahir: 1968-01-01
Mata Pelajaran: Matematika
Kelas: ["10-IPA-1", "10-IPA-2", "11-IPA-1"]
Is Wali Kelas: true
Kelas Wali: 10-IPA-1
```

### **Contoh Data Siswa**
```
NISN: 1234567890
NIS: 2024001
Nama: Ahmad Budi Santoso
Jenis Kelamin: L
Kelas: 10-IPA-1
Nama Ayah: Budi Santoso
Nama Ibu: Siti Aminah
HP Orang Tua: 081234567890
```

### **Contoh Data Orang Tua**
```
Nama Ayah: Budi Santoso, S.E
NIK Ayah: 3171234567890001
Pekerjaan Ayah: Pegawai Swasta
Penghasilan Ayah: 5000000
Status Ekonomi: Mampu
```

---

*Dokumentasi ini akan terus diperbarui seiring dengan pengembangan sistem dan kebutuhan sekolah.*