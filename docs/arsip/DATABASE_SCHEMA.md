# Skema Database Sistem Kesiswaan Terintegrasi

## Entitas Utama dan Relasi

### 1. USERS & AUTHENTICATION
```sql
-- Tabel pengguna utama
users (
    id, username, email, password, role_id, 
    aktif, email_verified_at, two_factor_enabled,
    created_at, updated_at
)

-- Peran dan izin
roles (id, nama, deskripsi, level_akses)
permissions (id, nama, deskripsi, modul)
role_permissions (role_id, permission_id)

-- Profil pengguna per peran
profiles (
    id, user_id, nama_lengkap, gelar_depan, gelar_belakang,
    foto, telepon, alamat_lengkap, jenis_kelamin, 
    tanggal_lahir, tempat_lahir, agama, status_pernikahan
)

-- Data pegawai (guru, staf, kepala sekolah)
pegawai (
    id, user_id, nip, nuptk, nama_lengkap,
    tempat_lahir, tanggal_lahir, jenis_kelamin,
    agama, status_kepegawaian, jenis_ptk,
    mata_pelajaran_id, pendidikan_terakhir,
    tanggal_masuk, status_aktif,
    alamat, telepon, email_alternatif
)

-- Data guru khusus
guru (
    id, pegawai_id, bidang_keahlian,
    sertifikat_pendidik, no_sertifikat,
    tahun_sertifikasi, status_sertifikasi,
    mengajar_kelas, jam_mengajar_perminggu
)

-- Data staf non-guru
staf (
    id, pegawai_id, unit_kerja, 
    jabatan_fungsional, tugas_tambahan,
    spesialisasi_kerja
)
```

### 2. MASTER DATA SEKOLAH
```sql
-- Data sekolah
sekolah (
    id, nama_sekolah, alamat, telepon, email, website,
    npsn, status_sekolah, akreditasi, 
    kepala_sekolah_id, wakil_kepala_sekolah_id,
    logo, created_at, updated_at
)

-- Struktur organisasi sekolah
jabatan_struktural (
    id, nama_jabatan, level_jabatan, 
    deskripsi, status_aktif
)

-- Pejabat sekolah
pejabat_sekolah (
    id, pegawai_id, jabatan_struktural_id,
    tanggal_mulai, tanggal_selesai, 
    sk_pengangkatan, status_aktif
)

-- Tahun ajaran dan semester dengan sistem aktif
tahun_ajaran (
    id, nama, kode, tanggal_mulai, tanggal_selesai, 
    is_aktif, is_current, status, 
    created_by, created_at, updated_at
)

semester (
    id, tahun_ajaran_id, nama, kode,
    tanggal_mulai, tanggal_selesai,
    is_aktif, is_current, urutan,
    target_hari_efektif, created_at, updated_at
)

-- Konfigurasi sistem per tahun ajaran
konfigurasi_tahun_ajaran (
    id, tahun_ajaran_id,
    batas_maksimal_poin_pelanggaran,
    batas_minimal_kehadiran_persen,
    sistem_penilaian, kurikulum_aktif,
    tanggal_penilaian_tengah_semester,
    tanggal_penilaian_akhir_semester,
    created_at, updated_at
)

-- Struktur kelas
tingkat_kelas (id, nama) -- 10, 11, 12
jurusan (id, nama, kode) -- IPA, IPS, BHS
kelas (
    id, tingkat_kelas_id, jurusan_id, 
    nama, wali_kelas_id, kapasitas,
    ruang_kelas, tahun_ajaran_id
)
```

### 3. DATA SISWA & ORANG TUA
```sql
-- Siswa dan data pribadi lengkap
siswa (
    id, user_id, nis, nisn, nama_lengkap,
    kelas_id, tahun_masuk, tahun_lulus, status_aktif,
    
    -- Data pribadi
    tempat_lahir, tanggal_lahir, jenis_kelamin, agama,
    kewarganegaraan, anak_ke, jumlah_saudara,
    bahasa_sehari_hari, tinggi_badan, berat_badan,
    golongan_darah, riwayat_penyakit,
    
    -- Alamat siswa
    alamat_lengkap, rt, rw, kelurahan, kecamatan,
    kabupaten_kota, provinsi, kode_pos,
    jarak_rumah_ke_sekolah, waktu_tempuh_ke_sekolah,
    
    -- Transportasi
    moda_transportasi, nomor_kks, nomor_pkh,
    nomor_kip, alat_transportasi,
    
    -- Kontak darurat
    telepon_rumah, nomor_hp_siswa, email_siswa,
    
    -- Data akademik
    asal_sekolah, nomor_sttb, tahun_sttb,
    nomor_un, nilai_un, 
    
    -- Data tambahan
    hobi, prestasi_yang_diperoleh, beasiswa,
    created_at, updated_at, imported_at
)

-- Data orang tua lengkap
orang_tua (
    id, siswa_id, 
    
    -- Data Ayah
    nama_ayah, nik_ayah, tempat_lahir_ayah, tanggal_lahir_ayah,
    pendidikan_ayah, pekerjaan_ayah, penghasilan_ayah,
    nomor_hp_ayah, email_ayah,
    
    -- Data Ibu  
    nama_ibu, nik_ibu, tempat_lahir_ibu, tanggal_lahir_ibu,
    pendidikan_ibu, pekerjaan_ibu, penghasilan_ibu,
    nomor_hp_ibu, email_ibu,
    
    -- Data Wali (jika ada)
    nama_wali, nik_wali, tempat_lahir_wali, tanggal_lahir_wali,
    pendidikan_wali, pekerjaan_wali, penghasilan_wali,
    nomor_hp_wali, email_wali, hubungan_dengan_siswa,
    
    -- Alamat orang tua (jika berbeda)
    alamat_ortu, telepon_rumah_ortu,
    
    -- Status keluarga
    status_pernikahan_ortu, status_ekonomi_keluarga,
    jumlah_tanggungan, kepemilikan_rumah,
    
    created_at, updated_at
)

-- Riwayat kelas siswa per tahun ajaran
riwayat_kelas (
    id, siswa_id, kelas_id, tahun_ajaran_id,
    tanggal_masuk, tanggal_keluar, alasan_pindah,
    status_naik_kelas
)

-- Data kesehatan siswa
kesehatan_siswa (
    id, siswa_id, tahun_ajaran_id,
    tinggi_badan, berat_badan, golongan_darah,
    riwayat_penyakit, alergi, obat_yang_diminum,
    nomor_bpjs, faskes_tingkat_1,
    created_at, updated_at
)
```

### 4. WALI KELAS
```sql
-- Wali kelas dan tugasnya
wali_kelas (
    id, guru_id, kelas_id, tahun_ajaran_id,
    tanggal_mulai, tanggal_selesai, status_aktif
)

-- Catatan wali kelas untuk siswa
catatan_wali_kelas (
    id, wali_kelas_id, siswa_id, 
    tanggal, jenis_catatan, isi_catatan,
    tindak_lanjut, status, tingkat_kepentingan
)

-- Koordinasi wali kelas dengan BK
koordinasi_walikelas_bk (
    id, wali_kelas_id, siswa_id, konselor_bk_id,
    jenis_koordinasi, deskripsi_masalah,
    rekomendasi_walikelas, tanggal_koordinasi,
    status_tindak_lanjut, hasil_koordinasi
)
```

### 5. SISTEM ABSENSI
```sql
-- Master absensi
jenis_absensi (id, nama, kode) -- Hadir, Sakit, Izin, Alpha

-- Absensi harian
absensi (
    id, siswa_id, tanggal, jam_masuk, jam_keluar,
    jenis_absensi_id, keterangan, lokasi_lat, lokasi_lng,
    qr_code, foto_absensi, divalidasi_oleh
)

-- Absensi kegiatan
absensi_kegiatan (
    id, siswa_id, kegiatan_id, 
    tanggal, status_kehadiran, keterangan
)
```

### 6. SISTEM POIN KEDISIPLINAN & VALIDASI LAPORAN
```sql
-- Master pelanggaran dan prestasi
kategori_poin (id, nama, jenis, level_urgensi) -- pelanggaran, prestasi
jenis_poin (
    id, kategori_poin_id, nama, 
    nilai_poin, tingkat_sanksi, deskripsi,
    requires_validation, auto_escalate
)

-- Laporan pelanggaran/prestasi (dari semua civitas)
laporan_siswa (
    id, siswa_id, jenis_poin_id, pelapor_id, pelapor_type,
    tanggal_kejadian, tempat_kejadian, 
    deskripsi_kejadian, saksi, bukti_foto,
    status_laporan, urgensi_level,
    created_at, updated_at
)

-- Validasi laporan bertingkat
validasi_laporan (
    id, laporan_siswa_id, validator_id, 
    level_validasi, status_validasi,
    catatan_validasi, tanggal_validasi,
    bukti_tambahan, rekomendasi_tindakan
)

-- Workflow validasi
workflow_validasi (
    id, jenis_poin_id, urutan, 
    validator_role_id, required_evidence,
    timeout_hours, escalation_level
)

-- Pencatatan poin siswa (setelah divalidasi)
poin_siswa (
    id, laporan_siswa_id, siswa_id, jenis_poin_id, 
    tanggal_kejadian, nilai_poin, keterangan,
    dicatat_oleh, divalidasi_oleh, 
    final_validator_id, chain_of_validation,
    status_tindak_lanjut, credibility_score
)

-- Kredibilitas pelapor (untuk mencegah laporan palsu)
kredibilitas_pelapor (
    id, pelapor_id, pelapor_type,
    total_laporan, laporan_valid, laporan_ditolak,
    accuracy_score, trust_level, 
    last_false_report, penalty_until
)

-- Akumulasi poin per periode
akumulasi_poin (
    id, siswa_id, tahun_ajaran_id, semester_id,
    total_poin_negatif, total_poin_positif,
    status_kedisiplinan, trend_behavior
)

-- Log audit untuk transparansi
audit_poin (
    id, poin_siswa_id, action_type, 
    old_value, new_value, changed_by,
    reason, ip_address, user_agent,
    created_at
)
```

### 7. BK/BP DAN HOME VISIT
```sql
-- Kasus konseling
kasus_konseling (
    id, siswa_id, konselor_id, 
    tanggal_rujukan, sumber_rujukan,
    jenis_masalah, deskripsi_masalah,
    status_kasus, prioritas
)

-- Sesi konseling
sesi_konseling (
    id, kasus_konseling_id, 
    tanggal_sesi, durasi, jenis_konseling,
    catatan_konseling, hasil_konseling,
    rekomendasi, status_sesi
)

-- Home visit
home_visit (
    id, siswa_id, kasus_konseling_id,
    tanggal_kunjungan, alamat_kunjungan,
    tujuan_kunjungan, peserta_kunjungan,
    hasil_kunjungan, foto_dokumentasi,
    rekomendasi_tindak_lanjut
)

-- Assessment psikologis
assessment (
    id, siswa_id, konselor_id,
    jenis_assessment, tanggal_assessment,
    hasil_assessment, interpretasi,
    rekomendasi
)
```

### 8. OSIS
```sql
-- Struktur organisasi OSIS
struktur_osis (
    id, siswa_id, jabatan_id, periode_id,
    tanggal_mulai, tanggal_selesai,
    status_aktif
)

-- Kegiatan OSIS
kegiatan_osis (
    id, nama_kegiatan, deskripsi,
    tanggal_mulai, tanggal_selesai,
    penanggung_jawab_id, anggaran,
    status_kegiatan
)

-- Partisipasi dalam kegiatan OSIS
partisipasi_osis (
    id, kegiatan_osis_id, siswa_id,
    peran, kontribusi, penilaian
)
```

### 9. EKSTRAKURIKULER
```sql
-- Master ekstrakurikuler
ekstrakurikuler (
    id, nama, deskripsi, pembina_id,
    hari_kegiatan, jam_mulai, jam_selesai,
    tempat, kapasitas, status_aktif
)

-- Pendaftaran ekstrakurikuler
pendaftaran_eskul (
    id, siswa_id, ekstrakurikuler_id,
    tanggal_daftar, status_pendaftaran,
    tanggal_keluar, alasan_keluar
)

-- Prestasi ekstrakurikuler
prestasi_eskul (
    id, siswa_id, ekstrakurikuler_id,
    nama_kompetisi, tingkat, peringkat,
    tanggal_kompetisi, penghargaan
)
```

### 10. PROGRAM KESISWAAN
```sql
-- Program dan kegiatan sekolah
program_kesiswaan (
    id, nama_program, deskripsi,
    tanggal_mulai, tanggal_selesai,
    penanggung_jawab_id, target_peserta,
    anggaran, status_program
)

-- Partisipasi dalam program
partisipasi_program (
    id, program_kesiswaan_id, siswa_id,
    tanggal_daftar, status_partisipasi,
    hasil_evaluasi, sertifikat
)
```

### 11. ADMINISTRASI SURAT
```sql
-- Template surat
template_surat (
    id, nama_template, jenis_surat,
    konten_template, variabel_template,
    status_aktif
)

-- Pengajuan surat
pengajuan_surat (
    id, siswa_id, template_surat_id,
    tanggal_pengajuan, keperluan,
    data_variabel, status_pengajuan,
    catatan_pengajuan
)

-- Workflow persetujuan
approval_workflow (
    id, pengajuan_surat_id, urutan,
    pejabat_id, tanggal_approval,
    status_approval, catatan_approval
)

-- Surat yang telah disetujui
surat_jadi (
    id, pengajuan_surat_id,
    nomor_surat, tanggal_surat,
    konten_surat, file_surat,
    qr_code_verifikasi
)
```

### 12. SISTEM IMPORT & TEMPLATE DATA
```sql
-- Template import data
import_templates (
    id, nama_template, jenis_data, 
    file_path, kolom_mapping, validasi_rules,
    is_aktif, created_by, created_at, updated_at
)

-- Riwayat import data
import_logs (
    id, template_id, user_id, nama_file,
    total_records, success_count, failed_count,
    error_details, file_path, 
    status_import, tanggal_import
)

-- Data import yang gagal untuk review
import_errors (
    id, import_log_id, row_number,
    data_row, error_message, is_resolved,
    resolved_by, resolved_at
)

-- Backup data sebelum import
data_backups (
    id, table_name, backup_data, 
    import_log_id, created_at
)

### 13. NOTIFIKASI DAN KOMUNIKASI
```sql
-- Notifikasi sistem
notifikasi (
    id, user_id, judul, pesan,
    jenis_notifikasi, tanggal_kirim,
    status_baca, data_terkait, tahun_ajaran_id
)

-- Log komunikasi dengan orang tua
komunikasi_ortu (
    id, siswa_id, jenis_komunikasi,
    pengirim_id, penerima, subjek,
    pesan, tanggal_kirim, status_terkirim,
    tahun_ajaran_id
)

-- Settings notifikasi per user
notifikasi_settings (
    id, user_id, jenis_notifikasi,
    email_enabled, sms_enabled, push_enabled,
    whatsapp_enabled, jadwal_pengiriman
)
```

## Relasi Kunci

### One-to-Many
- users → siswa (1 user bisa punya 1 siswa)
- kelas → siswa (1 kelas banyak siswa)
- siswa → poin_siswa (1 siswa banyak poin)
- siswa → kasus_konseling (1 siswa banyak kasus)
- kasus_konseling → sesi_konseling (1 kasus banyak sesi)

### Many-to-Many
- siswa ↔ ekstrakurikuler (through pendaftaran_eskul)
- siswa ↔ program_kesiswaan (through partisipasi_program)
- roles ↔ permissions (through role_permissions)

### Trigger dan Otomasi
```sql
-- Trigger otomatis rujukan ke BK
TRIGGER auto_bk_referral 
AFTER INSERT ON poin_siswa
WHEN total_poin >= threshold
CREATE kasus_konseling

-- Trigger notifikasi orang tua
TRIGGER parent_notification
AFTER INSERT ON absensi, poin_siswa
SEND notification to parents

-- Update akumulasi poin
TRIGGER update_akumulasi_poin
AFTER INSERT/UPDATE ON poin_siswa
UPDATE akumulasi_poin
```