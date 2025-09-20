<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Data Identitas Utama (sesuai format data)
            $table->string('nisn', 10)->unique()->comment('Nomor Induk Siswa Nasional (10 digit)');
            $table->string('nis', 10)->unique()->comment('Nomor Induk Siswa sekolah (10 digit)');
            $table->string('nama_lengkap', 100)->comment('Nama lengkap sesuai akta kelahiran');
            $table->string('nama_panggilan', 50)->nullable()->comment('Nama yang biasa dipanggil');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir');
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']);
            $table->string('kewarganegaraan', 20)->default('Indonesia');
            
            // Data Keluarga (sesuai format data)
            $table->string('nik', 16)->unique()->comment('NIK siswa (16 digit)');
            $table->string('no_kk', 16)->comment('Nomor Kartu Keluarga');
            $table->string('no_akta_kelahiran', 50)->nullable();
            $table->integer('anak_ke')->nullable()->comment('Anak keberapa dalam keluarga');
            $table->integer('jumlah_saudara')->nullable()->comment('Jumlah saudara kandung');
            $table->string('bahasa_sehari_hari', 50)->nullable();
            
            // Data Fisik dan Kesehatan (sesuai format data)
            $table->decimal('tinggi_badan', 5, 2)->nullable()->comment('Tinggi badan dalam cm');
            $table->decimal('berat_badan', 5, 2)->nullable()->comment('Berat badan dalam kg');
            $table->enum('golongan_darah', ['A', 'B', 'AB', 'O'])->nullable();
            $table->json('riwayat_penyakit')->nullable()->comment('Array riwayat penyakit');
            $table->text('kelainan_fisik')->nullable()->comment('Keterangan kelainan fisik (jika ada)');
            
            // Data Kontak dan Alamat (sesuai format data)
            $table->text('alamat_lengkap');
            $table->string('rt_rw', 10)->nullable()->comment('Format: 001/002');
            $table->string('kelurahan', 50);
            $table->string('kecamatan', 50);
            $table->string('kabupaten_kota', 50);
            $table->string('provinsi', 50);
            $table->string('kode_pos', 5)->nullable();
            $table->string('nomor_hp_siswa', 15)->nullable();
            $table->string('email_siswa', 100)->nullable();
            
            // Data Akademik (sesuai format data) - akan dihubungkan ke tabel kelas dan tahun_ajaran nanti
            $table->unsignedBigInteger('kelas_id')->nullable()->comment('Foreign key ke tabel kelas');
            $table->string('kelas', 20)->comment('Kelas saat ini (untuk compatibility)');
            $table->string('jurusan', 50)->nullable()->comment('Jurusan (untuk SMA/SMK)');
            $table->integer('angkatan')->comment('Tahun masuk sekolah');
            $table->unsignedBigInteger('tahun_ajaran_id')->comment('Foreign key ke tabel tahun_ajaran');
            $table->string('asal_sekolah', 100)->nullable();
            $table->date('tanggal_masuk');
            $table->enum('status_siswa', ['Aktif', 'Pindah', 'Lulus', 'DO', 'Mengundurkan Diri']);
            
            // Data Transportasi (sesuai format data)
            $table->decimal('jarak_ke_sekolah', 8, 2)->nullable()->comment('Jarak rumah ke sekolah (km)');
            $table->enum('transportasi', ['Jalan Kaki', 'Sepeda', 'Motor', 'Mobil', 'Angkutan Umum'])->nullable();
            $table->integer('waktu_tempuh')->nullable()->comment('Waktu tempuh dalam menit');
            
            // Data Beasiswa dan Bantuan (sesuai format data)
            $table->boolean('penerima_beasiswa')->default(false);
            $table->string('jenis_beasiswa', 100)->nullable();
            $table->boolean('penerima_kip')->default(false);
            $table->string('no_kip', 20)->nullable();
            $table->boolean('penerima_pkh')->default(false);
            
            // Data Minat dan Bakat (sesuai format data)
            $table->json('hobi')->nullable()->comment('Array hobi siswa');
            $table->json('cita_cita')->nullable()->comment('Array cita-cita');
            $table->json('prestasi')->nullable()->comment('Array prestasi yang diraih');
            $table->json('ekstrakurikuler')->nullable()->comment('Array ekstrakurikuler yang diikuti');
            
            // System fields
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index('nisn');
            $table->index('nis');
            $table->index('nik');
            $table->index('nama_lengkap');
            $table->index('kelas');
            $table->index('angkatan');
            $table->index('status_siswa');
            $table->index('status_aktif');
            $table->index('kelas_id');
            $table->index('tahun_ajaran_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};