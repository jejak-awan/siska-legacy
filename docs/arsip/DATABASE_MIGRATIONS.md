# Database Migrations - Sistem Manajemen Kesiswaan

## Overview
Dokumentasi ini menjelaskan struktur database migrations untuk Sistem Manajemen Kesiswaan yang telah dibuat. Sistem ini menggunakan Laravel migrations untuk memastikan konsistensi database across environments.

## Migration Files Created

### 1. Core System Tables

#### **2024_01_01_000001_create_users_table.php**
- **Tabel**: `users`
- **Fungsi**: Tabel utama untuk semua pengguna sistem
- **Kolom Utama**:
  - `username`: Username unik untuk login
  - `email`: Email (optional untuk siswa)
  - `nama`: Nama lengkap pengguna
  - `nomor_hp`: Nomor handphone
  - `alamat`: Alamat lengkap
  - `jenis_kelamin`: L/P
  - `tanggal_lahir`, `tempat_lahir`: Data kelahiran
  - `agama`: Agama pengguna
  - `foto`: Path file foto
  - `is_active`: Status aktif
  - `last_login_at`: Timestamp login terakhir

#### **2024_01_01_000002_create_tahun_ajarans_table.php**
- **Tabel**: `tahun_ajarans`
- **Fungsi**: Manajemen tahun ajaran sekolah
- **Kolom Utama**:
  - `nama`: Nama tahun ajaran (2024/2025)
  - `tahun_mulai`, `tahun_akhir`: Range tahun
  - `tanggal_mulai`, `tanggal_akhir`: Periode aktif
  - `is_active`: Status tahun ajaran aktif

#### **2024_01_01_000003_create_semesters_table.php**
- **Tabel**: `semesters`
- **Fungsi**: Manajemen semester dalam tahun ajaran
- **Relasi**: Belongs to `tahun_ajarans`
- **Kolom Utama**:
  - `tahun_ajaran_id`: Foreign key
  - `nama`: Ganjil/Genap
  - `semester`: 1 atau 2
  - `tanggal_mulai`, `tanggal_akhir`: Periode semester
  - `is_active`: Status semester aktif

#### **2024_01_01_000004_create_struktur_organisasis_table.php**
- **Tabel**: `struktur_organisasis`
- **Fungsi**: Data organisasi/identitas sekolah
- **Kolom Utama**:
  - `nama_sekolah`: Nama resmi sekolah
  - `npsn`: Nomor Pokok Sekolah Nasional
  - `alamat_sekolah`: Alamat lengkap sekolah
  - `kepala_sekolah_id`, `wakil_kepala_*_id`: Struktur kepemimpinan
  - `visi`, `misi`, `tujuan`: Visi misi sekolah
  - `jenjang`: SD/SMP/SMA/SMK
  - `status`: Negeri/Swasta
  - `akreditasi`: Status akreditasi

### 2. Personnel Management Tables

#### **2024_01_01_000005_create_pegawais_table.php**
- **Tabel**: `pegawais`
- **Fungsi**: Data pegawai/staff sekolah
- **Relasi**: Belongs to `users`
- **Kolom Utama**:
  - `user_id`: Foreign key
  - `nip`: Nomor Induk Pegawai
  - `nuptk`: Nomor Unik Pendidik dan Tenaga Kependidikan
  - `jenis_pegawai`: ASN/Non-ASN/Honorer/Kontrak
  - `status_kepegawaian`: Aktif/Cuti/Tugas Belajar/Pensiun/Resign
  - `golongan`: Golongan kepegawaian (untuk ASN)
  - `jabatan`: Jabatan/posisi
  - `unit_kerja`: Unit kerja
  - `tanggal_mulai_kerja`: Tanggal mulai bekerja
  - `pendidikan_terakhir`: Pendidikan terakhir
  - `sertifikat`: Array sertifikat (JSON)
  - `gaji_pokok`: Gaji pokok
  - `tunjangan`: Array tunjangan (JSON)

#### **2024_01_01_000006_create_gurus_table.php**
- **Tabel**: `gurus`
- **Fungsi**: Data spesifik guru
- **Relasi**: Belongs to `pegawais`
- **Kolom Utama**:
  - `pegawai_id`: Foreign key
  - `mata_pelajaran`: Mata pelajaran yang diampu
  - `kelas_yang_diajar`: Array kelas (JSON)
  - `is_wali_kelas`: Boolean wali kelas
  - `kelas_wali`: Kelas yang dibimbing
  - `status_sertifikasi`: Status sertifikasi guru
  - `is_pembina_osis`: Boolean pembina OSIS
  - `is_konselor_bk`: Boolean konselor BK
  - `jam_mengajar_per_minggu`: Jam mengajar
  - `kompetensi_khusus`: Array kompetensi (JSON)

#### **2024_01_01_000007_create_orang_tuas_table.php**
- **Tabel**: `orang_tuas`
- **Fungsi**: Data orang tua/wali siswa
- **Relasi**: Belongs to `users`
- **Kolom Utama**:
  - `user_id`: Foreign key
  - `nik`: Nomor Induk Kependudukan
  - `hubungan_keluarga`: Ayah/Ibu/Wali/dll
  - `is_kontak_utama`: Boolean kontak utama
  - `pekerjaan`: Pekerjaan
  - `penghasilan_bulanan`: Penghasilan per bulan
  - `pendidikan_terakhir`: Pendidikan terakhir
  - `status_ekonomi`: Mampu/Kurang Mampu/Tidak Mampu
  - `penerima_kip`: Boolean penerima KIP
  - `penerima_pkh`: Boolean penerima PKH
  - `alamat_domisili`: Alamat tinggal
  - `provinsi`, `kabupaten_kota`: Wilayah

#### **2024_01_01_000008_create_siswas_table.php**
- **Tabel**: `siswas`
- **Fungsi**: Data lengkap siswa
- **Relasi**: Belongs to `users`, `tahun_ajarans`
- **Kolom Utama**:
  - `user_id`, `tahun_ajaran_id`: Foreign keys
  - `nisn`: Nomor Induk Siswa Nasional
  - `nis`: Nomor Induk Siswa sekolah
  - `kelas`: Kelas saat ini
  - `jurusan`: Jurusan (untuk SMA/SMK)
  - `angkatan`: Tahun masuk
  - `status_siswa`: Aktif/Pindah/Lulus/DO
  - `tanggal_masuk`: Tanggal masuk sekolah
  - `asal_sekolah`: Sekolah asal
  - `nik`: NIK siswa
  - `no_kk`: Nomor Kartu Keluarga
  - `anak_ke`: Anak keberapa
  - `riwayat_penyakit`: Array riwayat medis (JSON)
  - `alamat_lengkap`: Alamat domisili
  - `transportasi`: Moda transportasi ke sekolah
  - `penerima_beasiswa`: Boolean penerima beasiswa
  - `penerima_kip`: Boolean penerima KIP
  - `prestasi`: Array prestasi (JSON)
  - `total_poin`: Total poin kedisiplinan

#### **2024_01_01_000009_create_siswa_orang_tuas_table.php**
- **Tabel**: `siswa_orang_tuas`
- **Fungsi**: Relasi many-to-many siswa dan orang tua
- **Relasi**: Pivot table `siswas` dan `orang_tuas`
- **Kolom Utama**:
  - `siswa_id`, `orang_tua_id`: Foreign keys
  - `is_kontak_darurat`: Boolean kontak darurat
  - `prioritas`: Prioritas kontak (1=utama, 2=sekunder)

### 3. Academic & Class Management Tables

#### **2024_01_01_000013_create_kelas_table.php**
- **Tabel**: `kelas`
- **Fungsi**: Manajemen kelas
- **Relasi**: Belongs to `gurus` (wali_kelas), `tahun_ajarans`
- **Kolom Utama**:
  - `nama_kelas`: Nama kelas (X IPA 1, dll)
  - `tingkat`: Tingkat kelas (X, XI, XII)
  - `jurusan`: Jurusan kelas
  - `kapasitas_maksimal`: Kapasitas siswa
  - `jumlah_siswa_aktif`: Jumlah siswa saat ini
  - `wali_kelas_id`: Foreign key ke guru
  - `tahun_ajaran_id`: Foreign key
  - `ruang_kelas`: Lokasi ruang kelas

### 4. Attendance Management Tables

#### **2024_01_01_000010_create_absensis_table.php**
- **Tabel**: `absensis`
- **Fungsi**: Sistem absensi siswa
- **Relasi**: Belongs to `siswas`, `tahun_ajarans`, `semesters`, `users`
- **Kolom Utama**:
  - `siswa_id`: Foreign key siswa
  - `tahun_ajaran_id`, `semester_id`: Foreign keys
  - `tanggal`: Tanggal absensi
  - `jenis_absensi`: Masuk/Pulang/Istirahat/Kegiatan
  - `status`: Hadir/Terlambat/Izin/Sakit/Alpha
  - `jam_masuk`, `jam_keluar`: Waktu absensi
  - `metode_absen`: QR Code/Manual/Fingerprint
  - `qr_code_id`: ID QR code yang dipindai
  - `latitude`, `longitude`: Koordinat GPS
  - `lokasi_nama`: Nama lokasi absensi
  - `foto_absen`: Path foto absensi
  - `petugas_input_id`: Petugas yang input manual
  - `is_valid`: Status validitas absensi

### 5. Points System Tables

#### **2024_01_01_000011_create_kategori_poins_table.php**
- **Tabel**: `kategori_poins`
- **Fungsi**: Kategori sistem poin
- **Kolom Utama**:
  - `nama_kategori`: Nama kategori poin
  - `jenis`: Positif/Negatif
  - `deskripsi`: Deskripsi kategori
  - `bobot_minimum`, `bobot_maksimum`: Range bobot poin
  - `aturan_khusus`: Aturan khusus (JSON)

#### **2024_01_01_000012_create_sistem_poins_table.php**
- **Tabel**: `sistem_poins`
- **Fungsi**: Pencatatan poin siswa
- **Relasi**: Belongs to `siswas`, `kategori_poins`, `tahun_ajarans`, `semesters`, `users`
- **Kolom Utama**:
  - `siswa_id`: Foreign key siswa
  - `kategori_poin_id`: Foreign key kategori
  - `judul_kejadian`: Judul kejadian
  - `deskripsi_kejadian`: Deskripsi lengkap
  - `poin`: Nilai poin (bisa negatif)
  - `tanggal_kejadian`: Tanggal kejadian
  - `pelapor_id`: Yang melaporkan
  - `validator_id`: Yang memvalidasi
  - `status_validasi`: Pending/Disetujui/Ditolak/Butuh Review
  - `bukti`: Array bukti (JSON)
  - `saksi`: Array saksi (JSON)
  - `butuh_tindak_lanjut`: Boolean butuh tindak lanjut
  - `tingkat_urgensi`: Rendah/Sedang/Tinggi/Kritis
  - `tindak_lanjut`: Catatan tindak lanjut
  - `penanggungjawab_tindaklanjut_id`: PIC tindak lanjut

### 6. Counseling & Home Visit Tables

#### **2024_01_01_000014_create_konselings_table.php**
- **Tabel**: `konselings`
- **Fungsi**: Manajemen konseling BK
- **Relasi**: Belongs to `siswas`, `gurus` (konselor), `tahun_ajarans`
- **Kolom Utama**:
  - `siswa_id`: Foreign key siswa
  - `konselor_id`: Foreign key guru BK
  - `jenis_konseling`: Individual/Kelompok/Krisis
  - `kategori_masalah`: Akademik/Sosial/Pribadi/Karir
  - `deskripsi_masalah`: Deskripsi masalah
  - `tingkat_urgensi`: Level urgensi
  - `tanggal_konseling`: Tanggal sesi
  - `jam_mulai`, `jam_selesai`: Waktu sesi
  - `tempat_konseling`: Lokasi
  - `proses_konseling`: Catatan proses
  - `hasil_konseling`: Hasil sesi
  - `rekomendasi`: Rekomendasi
  - `tindak_lanjut`: Tindak lanjut
  - `status`: Dijadwalkan/Berlangsung/Selesai/Dibatalkan
  - `butuh_home_visit`: Boolean butuh kunjungan rumah
  - `butuh_rujukan`: Boolean butuh rujukan
  - `rujukan_ke`: Tujuan rujukan
  - `metode_yang_digunakan`: Array metode (JSON)
  - `progress_score`: Skor progress (0.0-10.0)
  - `melibatkan_orang_tua`: Boolean melibatkan ortu

#### **2024_01_01_000015_create_home_visits_table.php**
- **Tabel**: `home_visits`
- **Fungsi**: Manajemen kunjungan rumah
- **Relasi**: Belongs to `siswas`, `gurus` (konselor), `konselings`
- **Kolom Utama**:
  - `siswa_id`: Foreign key siswa
  - `konselor_id`: Foreign key konselor
  - `konseling_id`: Foreign key konseling (optional)
  - `alasan_home_visit`: Alasan kunjungan
  - `latar_belakang`: Latar belakang
  - `tanggal_rencana`: Tanggal rencana
  - `tanggal_pelaksanaan`: Tanggal pelaksanaan
  - `alamat_kunjungan`: Alamat tujuan
  - `orang_yang_ditemui`: Array orang yang ditemui (JSON)
  - `kondisi_rumah`: Kondisi rumah
  - `kondisi_keluarga`: Kondisi keluarga
  - `permasalahan_yang_ditemukan`: Masalah yang ditemukan
  - `solusi_yang_diberikan`: Solusi yang diberikan
  - `rekomendasi`: Rekomendasi
  - `status`: Direncanakan/Dalam Perjalanan/Selesai/Dibatalkan
  - `foto_dokumentasi`: Array foto (JSON)
  - `butuh_kunjungan_ulang`: Boolean butuh kunjungan ulang

### 7. Extracurricular & OSIS Tables

#### **2024_01_01_000016_create_ekstrakurikulers_table.php**
- **Tabel**: `ekstrakurikulers`
- **Fungsi**: Manajemen ekstrakurikuler
- **Relasi**: Belongs to `gurus` (pembina), `tahun_ajarans`
- **Kolom Utama**:
  - `nama_ekstrakurikuler`: Nama kegiatan
  - `deskripsi`: Deskripsi kegiatan
  - `pembina_id`: Foreign key guru pembina
  - `kategori`: Olahraga/Seni/Sains/Rohani/Bahasa/Kepemimpinan/Lainnya
  - `kapasitas_maksimal`: Kapasitas maksimal
  - `jumlah_anggota`: Jumlah anggota saat ini
  - `jadwal_kegiatan`: Array jadwal (JSON)
  - `tempat_kegiatan`: Lokasi kegiatan
  - `peralatan_yang_diperlukan`: Array peralatan (JSON)
  - `syarat_pendaftaran`: Syarat pendaftaran
  - `biaya_pendaftaran`: Biaya pendaftaran
  - `biaya_bulanan`: Biaya bulanan
  - `is_wajib`: Boolean ekstrakurikuler wajib
  - `tanggal_mulai_pendaftaran`: Tanggal buka pendaftaran
  - `tanggal_akhir_pendaftaran`: Tanggal tutup pendaftaran
  - `prestasi`: Array prestasi (JSON)

#### **2024_01_01_000017_create_osis_struktur_table.php**
- **Tabel**: `osis_struktur`
- **Fungsi**: Struktur organisasi OSIS
- **Relasi**: Belongs to `tahun_ajarans`, `siswas`, `gurus` (pembina)
- **Kolom Utama**:
  - `tahun_ajaran_id`: Foreign key
  - `nama_jabatan`: Nama jabatan OSIS
  - `siswa_id`: Foreign key siswa yang menjabat
  - `pembina_id`: Foreign key guru pembina
  - `deskripsi_jabatan`: Deskripsi jabatan
  - `tugas_dan_tanggung_jawab`: Array tugas (JSON)
  - `hierarki_level`: Level hierarki (0=Ketua)
  - `tanggal_mulai_jabatan`: Tanggal mulai menjabat
  - `tanggal_akhir_jabatan`: Tanggal akhir menjabat
  - `status`: Aktif/Tidak Aktif/Demisioner
  - `poin_kepemimpinan`: Poin kepemimpinan
  - `program_kerja`: Array program kerja (JSON)
  - `catatan_kinerja`: Catatan kinerja

### 8. Program & Activities Tables

#### **2024_01_01_000018_create_program_kesiswaans_table.php**
- **Tabel**: `program_kesiswaans`
- **Fungsi**: Program dan kegiatan kesiswaan
- **Relasi**: Belongs to `tahun_ajarans`, `semesters`, `users`
- **Kolom Utama**:
  - `nama_program`: Nama program/kegiatan
  - `deskripsi`: Deskripsi lengkap
  - `tahun_ajaran_id`, `semester_id`: Foreign keys
  - `kategori`: Upacara/Kegiatan Sekolah/Lomba/Pelatihan/Seminar/Lainnya
  - `tingkat`: Kelas/Sekolah/Kecamatan/Kabupaten/Provinsi/Nasional
  - `tanggal_mulai`, `tanggal_selesai`: Periode kegiatan
  - `tempat_pelaksanaan`: Lokasi
  - `penanggung_jawab_id`: Foreign key penanggung jawab
  - `panitia`: Array panitia (JSON)
  - `target_peserta`: Array target peserta (JSON)
  - `jumlah_peserta`: Jumlah peserta aktual
  - `anggaran`: Anggaran kegiatan
  - `sponsor`: Array sponsor (JSON)
  - `status`: Direncanakan/Persiapan/Berlangsung/Selesai/Dibatalkan
  - `laporan_kegiatan`: Laporan hasil
  - `dokumentasi`: Array dokumentasi (JSON)
  - `hasil_evaluasi`: Array evaluasi (JSON)
  - `rating_kepuasan`: Rating kepuasan (0.0-10.0)

### 9. User Management & Letters Tables

#### **2024_01_01_000019_create_roles_table.php**
- **Tabel**: `roles`
- **Fungsi**: Manajemen role/peran pengguna
- **Kolom Utama**:
  - `name`: Nama role (unique)
  - `display_name`: Nama tampilan
  - `description`: Deskripsi role
  - `permissions`: Array permissions (JSON)
  - `hierarchy_level`: Level hierarki (0=tertinggi)

#### **2024_01_01_000020_create_user_roles_table.php**
- **Tabel**: `user_roles`
- **Fungsi**: Relasi many-to-many user dan roles
- **Relasi**: Pivot table `users` dan `roles`
- **Kolom Utama**:
  - `user_id`, `role_id`: Foreign keys
  - `assigned_at`: Tanggal pemberian role
  - `expires_at`: Tanggal kadaluarsa (optional)

#### **2024_01_01_000021_create_surats_table.php**
- **Tabel**: `surats`
- **Fungsi**: Sistem administrasi surat
- **Relasi**: Belongs to `users`, `siswas`
- **Kolom Utama**:
  - `nomor_surat`: Nomor surat (unique)
  - `jenis_surat`: Jenis surat (Izin/Keterangan/Rekomendasi)
  - `template_id`: ID template surat
  - `perihal`: Perihal surat
  - `pemohon_id`: Foreign key pemohon
  - `siswa_id`: Foreign key siswa terkait
  - `isi_surat`: Isi lengkap surat
  - `data_variabel`: Data variabel untuk template (JSON)
  - `tanggal_surat`: Tanggal surat
  - `tanggal_berlaku`, `tanggal_berakhir`: Periode berlaku
  - `status`: Draft/Diajukan/Direview/Disetujui/Ditolak/Selesai
  - `reviewer_id`: Foreign key reviewer
  - `reviewed_at`: Tanggal review
  - `catatan_review`: Catatan review
  - `approver_id`: Foreign key approver
  - `approved_at`: Tanggal approve
  - `catatan_approval`: Catatan approval
  - `file_surat`: Path file PDF surat
  - `tanda_tangan`: Array tanda tangan digital (JSON)
  - `butuh_stempel`: Boolean butuh stempel
  - `sudah_distempel`: Boolean sudah distempel
  - `jumlah_copy`: Jumlah copy

### 10. New Feature Tables (Piket Guru & Organisasi Kelas)

#### **2024_01_01_000022_create_piket_gurus_table.php**
- **Tabel**: `piket_gurus`
- **Fungsi**: Manajemen jadwal piket harian guru
- **Relasi**: Belongs to `tahun_ajarans`, `semesters`, `gurus` (koordinator)
- **Kolom Utama**:
  - `tahun_ajaran_id`, `semester_id`: Foreign keys
  - `hari`: Hari piket (Senin-Sabtu)
  - `jam_mulai`, `jam_selesai`: Waktu piket
  - `guru_piket`: Array ID guru yang bertugas (JSON)
  - `tugas_piket`: Array tugas piket (JSON)
  - `koordinator_piket_id`: Guru koordinator piket
  - `area_tanggung_jawab`: Area tanggung jawab (JSON)

#### **2024_01_01_000023_create_laporan_piket_gurus_table.php**
- **Tabel**: `laporan_piket_gurus`
- **Fungsi**: Laporan harian kegiatan piket guru
- **Relasi**: Belongs to `piket_gurus`, `gurus`
- **Kolom Utama**:
  - `piket_guru_id`: Foreign key jadwal piket
  - `guru_id`: Guru yang melaporkan
  - `tanggal_piket`: Tanggal piket
  - `kegiatan_piket`: Kegiatan yang dilakukan (JSON)
  - `kejadian_khusus`: Kejadian khusus (JSON)
  - `jumlah_siswa_terlambat`: Jumlah siswa terlambat
  - `siswa_bermasalah`: Siswa bermasalah (JSON)
  - `status_piket`: Hadir/Tidak Hadir/Izin/Diganti

#### **2024_01_01_000024_create_struktur_organisasi_kelas_table.php**
- **Tabel**: `struktur_organisasi_kelas`
- **Fungsi**: Struktur organisasi dan pengurus kelas
- **Relasi**: Belongs to `kelas`, `siswas`
- **Kolom Utama**:
  - `kelas_id`: Foreign key kelas
  - `nama_jabatan`: Nama jabatan (Ketua Kelas, Wakil, dll)
  - `siswa_id`: Siswa yang menjabat
  - `tugas_dan_tanggung_jawab`: Array tugas (JSON)
  - `hierarki_level`: Level hierarki jabatan
  - `tanggal_mulai_jabatan`, `tanggal_akhir_jabatan`: Periode jabatan
  - `status`: Aktif/Tidak Aktif/Diganti
  - `poin_kepemimpinan`: Poin kepemimpinan yang diperoleh

#### **2024_01_01_000025_create_jadwal_piket_kebersihanans_table.php**
- **Tabel**: `jadwal_piket_kebersihanans`
- **Fungsi**: Jadwal piket kebersihan kelas
- **Relasi**: Belongs to `kelas`, `tahun_ajarans`, `semesters`, `siswas` (koordinator)
- **Kolom Utama**:
  - `kelas_id`: Foreign key kelas
  - `hari`: Hari piket
  - `jenis_piket`: Harian/Mingguan/Bulanan/Insidentil
  - `siswa_piket`: Array ID siswa piket (JSON)
  - `area_piket`: Area yang dibersihkan (JSON)
  - `tugas_kebersihan`: Tugas spesifik (JSON)
  - `koordinator_piket_id`: Siswa koordinator piket
  - `waktu_mulai`, `waktu_selesai`: Waktu piket

#### **2024_01_01_000026_create_laporan_piket_kebersihanans_table.php**
- **Tabel**: `laporan_piket_kebersihanans`
- **Fungsi**: Laporan piket kebersihan siswa
- **Relasi**: Belongs to `jadwal_piket_kebersihanans`, `siswas`, `gurus` (validator)
- **Kolom Utama**:
  - `jadwal_piket_id`: Foreign key jadwal piket
  - `siswa_id`: Siswa yang melaporkan
  - `tanggal_piket`: Tanggal pelaksanaan
  - `tugas_yang_dikerjakan`: Tugas yang dikerjakan (JSON)
  - `status_kebersihan`: Bersih/Kurang Bersih/Kotor/Tidak Dibersihkan
  - `siswa_hadir`, `siswa_tidak_hadir`: Kehadiran siswa (JSON)
  - `foto_sebelum`, `foto_sesudah`: Dokumentasi foto (JSON)
  - `rating_kebersihan`: Rating 0.0-10.0
  - `validator_id`: Guru yang memvalidasi
  - `status_validasi`: Pending/Disetujuan/Ditolak

## Indexes dan Performance

### Primary Indexes
- Semua tabel memiliki primary key `id` dengan auto-increment
- Foreign key constraints untuk menjaga referential integrity

### Secondary Indexes
- **Users**: `username`, `email`, `is_active`
- **Absensi**: `siswa_id + tanggal`, `tanggal + jenis_absensi`, `status + is_valid`
- **Sistem Poin**: `siswa_id + tanggal_kejadian`, `status_validasi + butuh_tindak_lanjut`
- **Konseling**: `siswa_id + tanggal_konseling`, `konselor_id + status`
- **OSIS**: `tahun_ajaran_id + status`, `siswa_id + hierarki_level`

### Unique Constraints
- **Users**: `username`, `email`
- **Siswa**: `nisn`, `nis`, `nik`
- **Pegawai**: `nip`, `nuptk`
- **Orang Tua**: `nik`
- **Struktur Organisasi**: `npsn`
- **Surat**: `nomor_surat`

## Data Types Considerations

### JSON Fields
Menggunakan JSON untuk data yang kompleks dan fleksibel:
- `aturan_khusus` di kategori_poins
- `bukti`, `saksi` di sistem_poins
- `permissions` di roles
- `prestasi`, `hobi` di siswas
- `sertifikat`, `pelatihan` di pegawais

### Decimal Fields
Menggunakan decimal untuk data finansial dan poin:
- `penghasilan_bulanan` DECIMAL(15,2)
- `poin` DECIMAL(5,2)
- `progress_score` DECIMAL(3,1)
- `anggaran` DECIMAL(15,2)

### Enum Fields
Menggunakan enum untuk data dengan pilihan terbatas:
- `jenis_kelamin`: L/P
- `status_siswa`: Aktif/Pindah/Lulus/DO/Mengundurkan Diri
- `status_validasi`: Pending/Disetujui/Ditolak/Butuh Review
- `tingkat_urgensi`: Rendah/Sedang/Tinggi/Kritis/Darurat

## Running Migrations

### Development Environment
```bash
php artisan migrate
```

### Production Environment
```bash
php artisan migrate --force
```

### Rollback Migrations
```bash
php artisan migrate:rollback
php artisan migrate:rollback --step=5
```

### Reset and Migrate
```bash
php artisan migrate:fresh
php artisan migrate:fresh --seed
```

## Seeders

### RoleSeeder
Membuat 9 role utama:
1. **kepala_sekolah** (Level 0) - Akses penuh
2. **wakil_kepala_kesiswaan** (Level 1) - Manajemen kesiswaan
3. **staff_kesiswaan** (Level 2) - Administrasi kesiswaan
4. **konselor_bk** (Level 2) - BK dan konseling
5. **wali_kelas** (Level 3) - Manajemen kelas
6. **pembina_osis** (Level 3) - Pembinaan OSIS
7. **guru** (Level 4) - Pengajaran
8. **siswa** (Level 5) - Akses siswa
9. **orang_tua** (Level 6) - Akses orang tua

### KategoriPoinSeeder
Membuat 8 kategori poin:

**Poin Positif:**
1. **Prestasi Akademik** (5-50 poin)
2. **Prestasi Non-Akademik** (5-50 poin)
3. **Kedisiplinan Positif** (1-10 poin)
4. **Kepemimpinan** (5-25 poin)

**Poin Negatif:**
1. **Pelanggaran Ringan** (-1 sampai -10 poin)
2. **Pelanggaran Sedang** (-10 sampai -25 poin)
3. **Pelanggaran Berat** (-25 sampai -50 poin)
4. **Pelanggaran Sangat Berat** (-50 sampai -100 poin)

## Best Practices

### 1. Migration Naming
- Gunakan timestamp yang konsisten
- Nama file descriptive dan terurut
- Gunakan convention Laravel

### 2. Foreign Keys
- Selalu definisikan onDelete actions
- Gunakan cascade untuk relasi dependent
- Gunakan restrict untuk relasi critical

### 3. Indexes
- Index pada kolom yang sering di-query
- Composite index untuk query multi-kolom
- Unique index untuk data yang harus unik

### 4. Data Integrity
- Gunakan constraints untuk validasi database level
- Enum untuk data dengan pilihan terbatas
- Not null untuk kolom mandatory

### 5. Performance
- Gunakan appropriate data types
- Index foreign keys
- Limit text field lengths
- Use JSON sparingly dan dengan index yang tepat

## Troubleshooting

### Common Issues

1. **Foreign Key Constraint Error**
   - Pastikan parent table sudah ada
   - Pastikan data type dan size sama
   - Run migrations dalam urutan yang benar

2. **Duplicate Key Error**
   - Check untuk duplicate data
   - Pastikan unique constraints sesuai bisnis logic

3. **Column Not Found Error**
   - Pastikan migration sudah dijalankan
   - Check spelling kolom names
   - Refresh migration jika perlu

### Migration Order Dependencies
1. users (base table)
2. tahun_ajarans, semesters
3. struktur_organisasis
4. pegawais, gurus
5. orang_tuas
6. siswas
7. siswa_orang_tuas (pivot)
8. kelas
9. kategori_poins
10. sistem_poins, absensis
11. konselings, home_visits
12. ekstrakurikulers, osis_struktur
13. program_kesiswaans
14. roles, user_roles
15. surats
16. **piket_gurus** (new)
17. **laporan_piket_gurus** (new)
18. **struktur_organisasi_kelas** (new)
19. **jadwal_piket_kebersihanans** (new)
20. **laporan_piket_kebersihanans** (new)

Dokumentasi ini akan terus diperbarui seiring dengan pengembangan sistem.