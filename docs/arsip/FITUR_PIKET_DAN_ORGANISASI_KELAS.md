# Fitur Piket Guru dan Struktur Organisasi Kelas

## 📋 **Gambaran Umum Fitur Baru**

Dokumentasi ini menjelaskan fitur-fitur baru yang telah ditambahkan ke dalam Sistem Manajemen Kesiswaan:

1. **Piket Guru** - Sistem manajemen piket harian guru
2. **Struktur Organisasi Kelas** - Manajemen pengurus dan jabatan dalam kelas  
3. **Jadwal Piket Kebersihan Siswa** - Sistem piket kebersihan kelas yang terorganisir
4. **Laporan Piket Kebersihan** - Pelaporan dan monitoring kebersihan kelas

---

## 🏫 **FITUR PIKET GURU**

### **Tabel Database: `piket_gurus`**

#### **Struktur Data:**
| Field | Tipe | Keterangan |
|-------|------|------------|
| `tahun_ajaran_id` | Foreign Key | Referensi tahun ajaran |
| `semester_id` | Foreign Key | Referensi semester |
| `hari` | Enum | Senin/Selasa/Rabu/Kamis/Jumat/Sabtu |
| `jam_mulai` | Time | Jam mulai piket (default: 07:00) |
| `jam_selesai` | Time | Jam selesai piket (default: 15:00) |
| `guru_piket` | JSON Array | ID guru yang bertugas piket |
| `tugas_piket` | JSON Array | Daftar tugas piket |
| `koordinator_piket_id` | Foreign Key | Guru koordinator piket hari tersebut |
| `area_tanggung_jawab` | JSON Array | Area yang menjadi tanggung jawab |

#### **Tugas Default Piket Guru:**
```json
[
    "Menyambut siswa di gerbang sekolah",
    "Mengecek kebersihan lingkungan sekolah", 
    "Memantau kedisiplinan siswa",
    "Menangani siswa terlambat",
    "Melayani tamu sekolah",
    "Mengatur parkir kendaraan",
    "Memantau kantin dan lingkungan sekolah",
    "Membuat laporan harian piket",
    "Koordinasi dengan guru BK untuk siswa bermasalah",
    "Menjaga keamanan dan ketertiban sekolah"
]
```

#### **Area Tanggung Jawab Default:**
```json
[
    "Gerbang utama sekolah",
    "Halaman depan", 
    "Koridor lantai 1",
    "Koridor lantai 2",
    "Toilet siswa",
    "Kantin sekolah",
    "Tempat parkir",
    "Lapangan olahraga",
    "Perpustakaan",
    "Ruang guru"
]
```

### **Tabel Database: `laporan_piket_gurus`**

#### **Fungsi:** Pelaporan harian kegiatan piket guru
| Field | Tipe | Keterangan |
|-------|------|------------|
| `piket_guru_id` | Foreign Key | Referensi jadwal piket |
| `guru_id` | Foreign Key | Guru yang melaporkan |
| `tanggal_piket` | Date | Tanggal piket |
| `jam_masuk` | Time | Jam masuk piket |
| `jam_keluar` | Time | Jam keluar piket |
| `kegiatan_piket` | JSON Array | Kegiatan yang dilakukan |
| `kejadian_khusus` | JSON Array | Kejadian khusus saat piket |
| `jumlah_siswa_terlambat` | Integer | Jumlah siswa terlambat |
| `jumlah_tamu` | Integer | Jumlah tamu yang dilayani |
| `siswa_bermasalah` | JSON Array | Siswa yang bermasalah |
| `status_piket` | Enum | Hadir/Tidak Hadir/Izin/Diganti |

---

## 👥 **FITUR STRUKTUR ORGANISASI KELAS**

### **Tabel Database: `struktur_organisasi_kelas`**

#### **Struktur Default Organisasi Kelas:**

| Jabatan | Level | Tugas Utama |
|---------|--------|-------------|
| **Ketua Kelas** | 0 | Memimpin dan mengkoordinasi kelas |
| **Wakil Ketua** | 1 | Membantu ketua kelas |
| **Sekretaris** | 2 | Administrasi dan dokumentasi |
| **Bendahara** | 3 | Mengelola keuangan kelas |
| **Koordinator Kebersihan** | 4 | Mengatur piket kebersihan |
| **Koordinator Keamanan** | 5 | Menjaga ketertiban kelas |

#### **Detail Tugas dan Tanggung Jawab:**

**1. Ketua Kelas:**
```json
{
    "tugas_dan_tanggung_jawab": [
        "Memimpin dan mengkoordinasi seluruh kegiatan kelas",
        "Menjadi penghubung antara siswa dengan wali kelas", 
        "Bertanggung jawab atas kondisi dan suasana kelas",
        "Memimpin rapat kelas dan pengambilan keputusan",
        "Menjaga kedisiplinan dan ketertiban kelas"
    ]
}
```

**2. Wakil Ketua Kelas:**
```json
{
    "tugas_dan_tanggung_jawab": [
        "Membantu ketua kelas dalam menjalankan tugasnya",
        "Menggantikan ketua kelas jika berhalangan",
        "Mengkoordinasi program kerja kelas", 
        "Membantu menjaga kedisiplinan kelas"
    ]
}
```

**3. Sekretaris:**
```json
{
    "tugas_dan_tanggung_jawab": [
        "Mencatat dan mendokumentasikan setiap kegiatan kelas",
        "Membuat notulen rapat kelas",
        "Mengelola administrasi kelas",
        "Membuat surat-menyurat kelas"
    ]
}
```

**4. Bendahara:**
```json
{
    "tugas_dan_tanggung_jawab": [
        "Mengelola keuangan kelas",
        "Mencatat pemasukan dan pengeluaran kas kelas",
        "Mengumpulkan iuran kelas", 
        "Membuat laporan keuangan kelas"
    ]
}
```

**5. Koordinator Kebersihan:**
```json
{
    "tugas_dan_tanggung_jawab": [
        "Mengatur jadwal piket kebersihan kelas",
        "Memantau kebersihan kelas setiap hari",
        "Mengkoordinasi kerja bakti kelas",
        "Mengingatkan siswa tentang tugas piket"
    ]
}
```

**6. Koordinator Keamanan:**
```json
{
    "tugas_dan_tanggung_jawab": [
        "Menjaga keamanan dan ketertiban kelas",
        "Mengatur pergantian jam pelajaran",
        "Membantu guru dalam menjaga kondusifitas kelas", 
        "Melaporkan hal-hal yang mengganggu keamanan"
    ]
}
```

### **Sistem Poin Kepemimpinan:**
- **Ketua Kelas:** 10 poin base + bonus durasi
- **Wakil Ketua:** 8 poin base + bonus durasi  
- **Sekretaris:** 6 poin base + bonus durasi
- **Bendahara:** 6 poin base + bonus durasi
- **Koordinator:** 4 poin base + bonus durasi
- **Bonus Durasi:** 0.5 poin per bulan (maksimal 5 poin)

---

## 🧹 **FITUR JADWAL PIKET KEBERSIHAN SISWA**

### **Tabel Database: `jadwal_piket_kebersihanans`**

#### **Struktur Data:**
| Field | Tipe | Keterangan |
|-------|------|------------|
| `kelas_id` | Foreign Key | Referensi kelas |
| `hari` | Enum | Hari piket (Senin-Sabtu) |
| `jenis_piket` | Enum | Harian/Mingguan/Bulanan/Insidentil |
| `siswa_piket` | JSON Array | ID siswa yang bertugas |
| `area_piket` | JSON Array | Area yang dibersihkan |
| `waktu_mulai` | Time | Waktu mulai piket (default: 06:30) |
| `waktu_selesai` | Time | Waktu selesai piket (default: 07:00) |
| `tugas_kebersihan` | JSON Array | Daftar tugas spesifik |
| `koordinator_piket_id` | Foreign Key | Siswa koordinator piket |

#### **Area Piket Default:**
```json
{
    "Dalam Kelas": [
        "Menyapu lantai kelas",
        "Mengepel lantai kelas", 
        "Mengelap papan tulis",
        "Merapikan meja dan kursi",
        "Mengelap jendela dan kaca",
        "Membersihkan tempat sampah"
    ],
    "Koridor Depan Kelas": [
        "Menyapu koridor",
        "Mengepel koridor", 
        "Membersihkan area di depan kelas",
        "Merapikan sepatu/sandal"
    ],
    "Toilet Terdekat": [
        "Membersihkan toilet",
        "Mengisi air di bak mandi",
        "Mengelap cermin", 
        "Memastikan kebersihan wastafel"
    ],
    "Halaman Kelas": [
        "Menyapu halaman",
        "Membersihkan taman kecil",
        "Merapikan area parkir sepeda"
    ]
}
```

#### **Tugas Kebersihan Berdasarkan Periode:**

**Tugas Harian:**
```json
[
    "Menyapu dan mengepel kelas",
    "Mengelap papan tulis setelah pelajaran",
    "Membuang sampah ke tempat pembuangan", 
    "Merapikan meja dan kursi setelah pulang",
    "Mematikan lampu dan kipas angin"
]
```

**Tugas Mingguan:**
```json
[
    "Membersihkan jendela dan ventilasi",
    "Mengelap lemari dan rak buku",
    "Membersihkan area display kelas",
    "Menyapu dan mengepel area sekitar kelas"
]
```

**Tugas Bulanan:**
```json
[
    "Kerja bakti besar-besaran",
    "Membersihkan langit-langit dari sarang laba-laba",
    "Mencuci gorden (jika ada)",
    "Perawatan tanaman hias kelas"
]
```

### **Algoritma Pembagian Piket Otomatis:**
1. **Input:** Jumlah siswa dalam kelas
2. **Pembagian:** Siswa dibagi ke 5 hari sekolah (Senin-Jumat)
3. **Formula:** `siswa_per_hari = ceil(jumlah_siswa / 5)`
4. **Koordinator:** Siswa pertama di setiap hari menjadi koordinator
5. **Rotasi:** Sistem rotasi otomatis setiap semester

---

## 📊 **FITUR LAPORAN PIKET KEBERSIHAN**

### **Tabel Database: `laporan_piket_kebersihanans`**

#### **Struktur Laporan:**
| Field | Tipe | Keterangan |
|-------|------|------------|
| `jadwal_piket_id` | Foreign Key | Referensi jadwal piket |
| `siswa_id` | Foreign Key | Siswa yang melaporkan |
| `tanggal_piket` | Date | Tanggal pelaksanaan piket |
| `tugas_yang_dikerjakan` | JSON Array | Tugas yang telah dikerjakan |
| `status_kebersihan` | Enum | Bersih/Kurang Bersih/Kotor/Tidak Dibersihkan |
| `kondisi_area` | JSON Array | Kondisi setiap area |
| `siswa_hadir` | JSON Array | Siswa yang hadir piket |
| `siswa_tidak_hadir` | JSON Array | Siswa yang tidak hadir |
| `kendala` | Text | Kendala yang dihadapi |
| `foto_sebelum` | JSON Array | Foto sebelum dibersihkan |
| `foto_sesudah` | JSON Array | Foto setelah dibersihkan |
| `rating_kebersihan` | Decimal | Rating 0.0-10.0 |

#### **Sistem Validasi:**
- **Status:** Pending → Disetujui/Ditolak
- **Validator:** Guru wali kelas atau guru piket
- **Kriteria Rating:**
  - **9.0-10.0:** Sangat Bersih
  - **8.0-8.9:** Bersih  
  - **7.0-7.9:** Cukup Bersih
  - **6.0-6.9:** Kurang Bersih
  - **< 6.0:** Kotor

---

## 🎯 **FITUR WALI KELAS YANG DIPERKAYA**

### **Penambahan pada Model Kelas:**

#### **Tata Tertib Kelas Default:**
```json
{
    "Kedisiplinan": [
        "Datang tepat waktu sebelum bel masuk",
        "Berpakaian rapi dan sesuai aturan sekolah",
        "Mengikuti upacara bendera dengan hikmat", 
        "Tidak meninggalkan kelas saat jam pelajaran"
    ],
    "Kebersihan": [
        "Melaksanakan piket kebersihan sesuai jadwal",
        "Membuang sampah pada tempatnya",
        "Menjaga kebersihan meja dan kursi",
        "Tidak mencoret-coret dinding atau fasilitas kelas"
    ],
    "Ketertiban": [
        "Tidak membuat keributan saat pelajaran", 
        "Menghormati guru dan teman sekelas",
        "Tidak membawa barang berbahaya ke kelas",
        "Menggunakan HP sesuai aturan sekolah"
    ],
    "Akademik": [
        "Mengerjakan tugas tepat waktu",
        "Berpartisipasi aktif dalam pembelajaran",
        "Tidak mencontek saat ulangan",
        "Menjaga buku dan alat tulis dengan baik"
    ]
}
```

#### **Program Kerja Kelas Default:**
```json
{
    "Program Harian": [
        "Melaksanakan piket kebersihan",
        "Absensi siswa", 
        "Menjaga ketertiban kelas"
    ],
    "Program Mingguan": [
        "Rapat kelas setiap hari Jumat",
        "Evaluasi kebersihan kelas",
        "Pengecekan kelengkapan fasilitas kelas"
    ],
    "Program Bulanan": [
        "Kerja bakti kelas",
        "Laporan keuangan kas kelas",
        "Evaluasi struktur organisasi kelas"
    ],
    "Program Semester": [
        "Pemilihan pengurus kelas baru",
        "Study tour atau kegiatan kelas", 
        "Laporan pertanggungjawaban pengurus kelas"
    ]
}
```

### **Dashboard Wali Kelas:**
1. **Monitoring Struktur Organisasi**
   - Daftar pengurus kelas aktif
   - Performa setiap pengurus
   - Poin kepemimpinan yang diperoleh

2. **Manajemen Piket Kebersihan**
   - Jadwal piket mingguan
   - Laporan kebersihan harian
   - Rating kebersihan rata-rata kelas

3. **Statistik Kelas**
   - Skor performa kelas (akademik, kedisiplinan, kebersihan)
   - Grafik trend kebersihan
   - Laporan kehadiran piket

---

## 🔧 **INTEGRASI DENGAN FITUR LAIN**

### **Integrasi dengan Sistem Poin:**
- **Poin Positif:** Melaksanakan piket dengan baik (+2 poin)
- **Poin Negatif:** Tidak melaksanakan piket (-5 poin)
- **Poin Kepemimpinan:** Berdasarkan jabatan di struktur organisasi

### **Integrasi dengan Absensi:**
- Absensi piket pagi dicatat terpisah
- Keterlambatan piket mempengaruhi poin kedisiplinan
- Notifikasi otomatis untuk siswa piket

### **Integrasi dengan Notifikasi:**
- Pengingat piket H-1 melalui WhatsApp/SMS
- Notifikasi laporan piket ke wali kelas
- Alert jika rating kebersihan turun drastis

### **Integrasi dengan Dashboard:**
- Grafik performa piket per kelas
- Ranking kebersihan antar kelas
- Laporan bulanan ke kepala sekolah

---

## 📱 **PENGGUNAAN FITUR DALAM APLIKASI**

### **Untuk Wali Kelas:**
1. **Setup Struktur Organisasi**
   ```
   Dashboard → Kelas Saya → Struktur Organisasi
   - Assign siswa ke jabatan
   - Monitor performa pengurus
   - Update tugas dan tanggung jawab
   ```

2. **Manajemen Piket Kebersihan**
   ```
   Dashboard → Kelas Saya → Jadwal Piket
   - Generate jadwal otomatis
   - Edit pembagian siswa
   - Monitor laporan harian
   ```

### **Untuk Siswa:**
1. **Cek Jadwal Piket**
   ```
   Dashboard → Piket Saya
   - Lihat jadwal piket pribadi
   - Reminder tugas piket
   - Submit laporan piket
   ```

2. **Laporan Piket**
   ```
   Dashboard → Piket Saya → Buat Laporan
   - Upload foto sebelum/sesudah
   - Checklist tugas yang dikerjakan
   - Beri rating kebersihan
   ```

### **Untuk Guru Piket:**
1. **Jadwal Piket Guru**
   ```
   Dashboard → Piket Guru
   - Lihat jadwal piket harian
   - Input laporan piket
   - Monitor siswa bermasalah
   ```

---

## 📈 **METRICS DAN ANALITIK**

### **KPI Piket Guru:**
- Tingkat kehadiran piket guru (target: >95%)
- Jumlah siswa terlambat yang ditangani
- Response time terhadap kejadian khusus
- Rating kepuasan layanan piket

### **KPI Kebersihan Kelas:**
- Rating kebersihan rata-rata (target: >8.0)
- Persentase kehadiran piket siswa (target: >90%)  
- Jumlah laporan piket tepat waktu
- Tren kebersihan bulanan

### **KPI Organisasi Kelas:**
- Efektivitas pengurus kelas (berdasarkan poin kepemimpinan)
- Tingkat partisipasi siswa dalam kegiatan kelas
- Frekuensi rapat kelas
- Achievement program kerja kelas

---

*Dokumentasi ini akan terus diperbarui seiring dengan pengembangan dan feedback pengguna sistem.*