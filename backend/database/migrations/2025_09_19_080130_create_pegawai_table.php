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
        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Data Identitas Utama (sesuai format data)
            $table->string('nip', 18)->unique()->comment('Nomor Induk Pegawai (18 digit)');
            $table->string('nuptk', 16)->unique()->nullable()->comment('Nomor Unik Pendidik dan Tenaga Kependidikan');
            $table->string('nama_lengkap', 100)->comment('Nama lengkap beserta gelar');
            $table->string('nama_panggilan', 50)->nullable()->comment('Nama yang biasa dipanggil');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('tempat_lahir', 50);
            $table->date('tanggal_lahir');
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']);
            $table->enum('status_pernikahan', ['Belum Menikah', 'Menikah', 'Cerai'])->nullable();
            
            // Data Kontak dan Alamat (sesuai format data)
            $table->text('alamat_lengkap');
            $table->string('rt_rw', 10)->nullable()->comment('Format: 001/002');
            $table->string('kelurahan', 50);
            $table->string('kecamatan', 50);
            $table->string('kabupaten_kota', 50);
            $table->string('provinsi', 50);
            $table->string('kode_pos', 5)->nullable();
            $table->string('nomor_hp', 15)->comment('Format: 08xxxxxxxxxx');
            $table->string('email', 100)->nullable();
            $table->string('nomor_hp_darurat', 15)->nullable();
            
            // Data Kepegawaian (sesuai format data)
            $table->enum('jenis_ptk', ['Guru Kelas', 'Guru Mapel', 'Tenaga Kependidikan']);
            $table->enum('status_kepegawaian', ['PNS', 'PPPK', 'GTT', 'PTT', 'Honorer']);
            $table->string('golongan', 10)->nullable()->comment('Golongan kepegawaian (untuk PNS/PPPK)');
            $table->string('jabatan', 100);
            $table->string('unit_kerja', 100);
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar')->nullable();
            $table->integer('masa_kerja')->nullable()->comment('Masa kerja dalam tahun');
            
            // Data Pendidikan dan Kompetensi (sesuai format data)
            $table->enum('pendidikan_terakhir', ['SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3']);
            $table->string('jurusan_pendidikan', 100)->nullable();
            $table->string('nama_universitas', 100)->nullable();
            $table->integer('tahun_lulus')->nullable();
            $table->string('no_ijazah', 50)->nullable();
            $table->string('mata_pelajaran', 100)->nullable()->comment('Mata pelajaran yang diampu');
            $table->json('kelas_yang_diajar')->nullable()->comment('Array kelas yang diajar');
            $table->boolean('is_wali_kelas')->default(false);
            $table->string('kelas_wali', 20)->nullable()->comment('Kelas yang dibimbing (jika wali kelas)');
            
            // Data Sertifikasi (sesuai format data)
            $table->enum('status_sertifikasi', ['Tersertifikasi', 'Belum Tersertifikasi', 'Dalam Proses'])->nullable();
            $table->string('no_sertifikat_pendidik', 50)->nullable();
            $table->date('tanggal_sertifikasi')->nullable();
            $table->string('lembaga_sertifikasi', 100)->nullable();
            $table->json('sertifikat_lainnya')->nullable()->comment('Array sertifikat tambahan');
            
            // Data Tugas Tambahan (sesuai format data)
            $table->boolean('is_konselor_bk')->default(false);
            $table->boolean('is_pembina_osis')->default(false);
            $table->json('ekstrakurikuler')->nullable()->comment('Array ekstrakurikuler yang dibina');
            $table->json('tugas_tambahan')->nullable()->comment('Array tugas tambahan lainnya');
            
            // System fields
            $table->boolean('status_aktif')->default(true);
            $table->timestamps();
            
            // Indexes
            $table->index('nip');
            $table->index('nuptk');
            $table->index('nama_lengkap');
            $table->index('status_aktif');
            $table->index('is_wali_kelas');
            $table->index('is_konselor_bk');
            $table->index('is_pembina_osis');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};