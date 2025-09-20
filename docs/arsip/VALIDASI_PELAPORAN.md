# Alur Pelaporan dan Validasi Sistem Poin

## 🔄 **Alur Pelaporan Pelanggaran/Prestasi**

### **1. Tahap Pelaporan Awal**
```
Civitas Akademika → Sistem Pelaporan → Queue Validasi
     ↓
- Guru mata pelajaran
- Wali kelas  
- Staff kesiswaan
- Konselor BK
- Pembina OSIS
- Pelatih ekstrakurikuler
- Security sekolah
- Siswa (peer reporting)
- Orang tua (laporan dari rumah)
```

### **2. Validasi Bertingkat**
```
Level 1: Validasi Awal (Wali Kelas/Guru Piket)
    ↓
Level 2: Validasi Supervisor (Koordinator BK/Kesiswaan)
    ↓
Level 3: Validasi Final (Kepala Sekolah/Wakasek)
    ↓
Approved → Input ke Sistem Poin
```

## 📋 **Form Pelaporan Terstandar**

### **A. Data Wajib Laporan**
- **Identitas Pelapor**: Nama, jabatan, kredensial
- **Data Siswa**: Nama, kelas, NIS
- **Waktu Kejadian**: Tanggal, jam, durasi
- **Tempat Kejadian**: Lokasi spesifik di sekolah
- **Jenis Pelanggaran/Prestasi**: Pilih dari dropdown
- **Deskripsi Detail**: Narasi lengkap kejadian
- **Saksi**: Minimal 1 saksi (jika ada)
- **Bukti**: Foto, video, dokumen pendukung

### **B. Klasifikasi Urgency Level**
```sql
URGENT (24 jam)     → Kekerasan, narkoba, bullying berat
HIGH (2-3 hari)     → Tidak masuk tanpa izin, merokok, berkelahi
MEDIUM (1 minggu)   → Terlambat berulang, seragam tidak lengkap
LOW (2 minggu)      → Prestasi akademik, kegiatan positif
```

## 🛡️ **Mekanisme Validasi dan Akuntabilitas**

### **1. Sistem Kredibilitas Pelapor**
```sql
-- Skor kredibilitas otomatis
Accuracy Score = (Laporan Valid / Total Laporan) * 100
Trust Level:
- PLATINUM (95-100%): Auto-approve untuk pelanggaran ringan
- GOLD (85-94%): Validasi cepat (24 jam)
- SILVER (75-84%): Validasi normal (2-3 hari)  
- BRONZE (65-74%): Validasi ketat + cross-check
- RESTRICTED (<65%): Semua laporan harus divalidasi tingkat 3
```

### **2. Cross-Validation System**
```
Laporan Masuk → Auto-Check Database
    ↓
- Cek riwayat siswa yang dilaporkan
- Cek pattern pelanggaran serupa
- Cek kredibilitas pelapor
- Cek keberadaan siswa di waktu kejadian (absensi)
- Cross-reference dengan CCTV (jika tersedia)
```

### **3. Evidence Management**
```sql
-- Bukti digital tersimpan aman
bukti_laporan (
    id, laporan_siswa_id, jenis_bukti,
    file_path, hash_file, uploaded_by,
    verified_authentic, chain_of_custody,
    retention_period, encryption_key
)
```

## ⚖️ **Proses Validasi Detail**

### **Level 1: Validasi Awal (24 jam)**
**Validator**: Wali Kelas/Guru Piket/Koordinator
- ✅ Verifikasi identitas siswa dan pelapor
- ✅ Cek kehadiran siswa di waktu kejadian  
- ✅ Konfirmasi lokasi kejadian masuk akal
- ✅ Review bukti awal (foto, saksi)
- ⚠️ **Jika ditolak**: Kembali ke pelapor dengan alasan

### **Level 2: Validasi Supervisor (48-72 jam)**
**Validator**: Koordinator BK/Kesiswaan/Wakasek Kesiswaan
- ✅ Review validasi level 1
- ✅ Cross-check dengan sistem (CCTV, absensi)
- ✅ Wawancara singkat dengan siswa yang dilaporkan
- ✅ Konfirmasi dengan saksi (jika ada)
- ✅ Assess appropriate point value
- ⚠️ **Jika ditolak**: Escalate ke Level 3 untuk review

### **Level 3: Validasi Final (1 minggu)**
**Validator**: Kepala Sekolah/Wakil Kepala Sekolah
- ✅ Final review untuk kasus berat/controversial
- ✅ Keputusan akhir untuk kasus yang ditolak Level 2
- ✅ Approve/reject dengan legal standing
- ✅ Set precedent untuk kasus serupa

## 🔒 **Best Practices & Safeguards**

### **1. Anti-Fraud Measures**
```sql
-- Deteksi pola mencurigakan
TRIGGER fraud_detection:
- Laporan massal dari 1 pelapor dalam 24 jam
- Multiple laporan untuk 1 siswa dalam waktu singkat
- Pattern laporan yang tidak logis (waktu/tempat)
- Laporan dari IP address yang sama berulang
- Revenge reporting (laporan balas dendam)
```

### **2. Appeal Process**
```sql
-- Proses banding untuk siswa/orang tua
appeal_poin (
    id, poin_siswa_id, pengaju_appeal,
    alasan_appeal, bukti_tambahan,
    status_appeal, reviewer_id,
    keputusan_appeal, tanggal_keputusan
)
```

### **3. Transparency & Audit**
- **Digital Signature** pada setiap validasi
- **Timestamp** yang tidak bisa dimanipulasi  
- **Audit Trail** lengkap untuk setiap perubahan
- **Notification** real-time ke orang tua untuk setiap poin
- **Monthly Report** kredibilitas pelapor

### **4. Privacy Protection**
- **Anonymized Reporting** untuk kasus sensitif
- **Data Encryption** untuk semua bukti
- **Access Control** ketat per level user
- **GDPR Compliance** untuk data protection

## 📊 **Dashboard Monitoring**

### **Real-time Alerts**
```
🚨 URGENT: Laporan kekerasan butuh validasi segera
⏰ OVERDUE: 5 laporan melewati deadline validasi
📈 TREND: Peningkatan laporan bullying 40% minggu ini
🎯 ACCURACY: Pelapor X memiliki accuracy 95% (promote ke Gold)
```

### **Analytics untuk Kepala Sekolah**
- **Report Volume**: Tren laporan per bulan
- **Validation Speed**: Average time per validation level  
- **Accuracy Metrics**: Success rate per validator
- **Pattern Analysis**: Hot spots dan peak times
- **Intervention Success**: Follow-up effectiveness

## 🎯 **Key Performance Indicators (KPI)**

| Metrik | Target | Pengukuran |
|--------|---------|------------|
| **Validation Speed** | <48 jam | Average validation time |
| **Report Accuracy** | >90% | Valid reports/Total reports |
| **Appeal Success** | <10% | Successful appeals/Total appeals |
| **False Positive** | <5% | Invalid reports after validation |
| **User Satisfaction** | >85% | Survey kepuasan pelapor |

Sistem ini memastikan **akuntabilitas tinggi** dengan tetap **efisien** dan **fair** untuk semua pihak! 🛡️