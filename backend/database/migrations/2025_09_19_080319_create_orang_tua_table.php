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
        Schema::create('orang_tua', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id')->comment('Foreign key ke tabel siswa');
            
            // Data Identitas Ayah (sesuai format data)
            $table->string('nama_ayah', 100);
            $table->string('nik_ayah', 16)->unique()->nullable()->comment('NIK ayah (16 digit)');
            $table->string('tempat_lahir_ayah', 50)->nullable();
            $table->date('tanggal_lahir_ayah')->nullable();
            $table->enum('pendidikan_ayah', ['SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3'])->nullable();
            $table->string('pekerjaan_ayah', 100);
            $table->decimal('penghasilan_ayah', 15, 2)->nullable()->comment('Penghasilan per bulan');
            $table->string('nomor_hp_ayah', 15)->nullable();
            $table->string('email_ayah', 100)->nullable();
            
            // Data Identitas Ibu (sesuai format data)
            $table->string('nama_ibu', 100);
            $table->string('nik_ibu', 16)->unique()->nullable()->comment('NIK ibu (16 digit)');
            $table->string('tempat_lahir_ibu', 50)->nullable();
            $table->date('tanggal_lahir_ibu')->nullable();
            $table->enum('pendidikan_ibu', ['SD', 'SMP', 'SMA', 'D1', 'D2', 'D3', 'S1', 'S2', 'S3'])->nullable();
            $table->string('pekerjaan_ibu', 100);
            $table->decimal('penghasilan_ibu', 15, 2)->nullable()->comment('Penghasilan per bulan');
            $table->string('nomor_hp_ibu', 15)->nullable();
            $table->string('email_ibu', 100)->nullable();
            
            // Data Wali (jika berbeda dengan ortu) (sesuai format data)
            $table->string('nama_wali', 100)->nullable();
            $table->string('nik_wali', 16)->nullable();
            $table->string('hubungan_wali', 50)->nullable()->comment('Hubungan dengan siswa');
            $table->string('pekerjaan_wali', 100)->nullable();
            $table->decimal('penghasilan_wali', 15, 2)->nullable();
            $table->string('nomor_hp_wali', 15)->nullable();
            $table->string('email_wali', 100)->nullable();
            
            // Data Ekonomi Keluarga (sesuai format data)
            $table->enum('status_ekonomi', ['Mampu', 'Kurang Mampu', 'Tidak Mampu'])->nullable();
            $table->decimal('penghasilan_total', 15, 2)->nullable()->comment('Total penghasilan keluarga');
            $table->integer('jumlah_tanggungan')->nullable()->comment('Jumlah anggota keluarga');
            $table->enum('kepemilikan_rumah', ['Milik Sendiri', 'Sewa', 'Menumpang'])->nullable();
            $table->boolean('penerima_pkh')->default(false)->comment('Penerima Program Keluarga Harapan');
            
            // Data Kontak Darurat
            $table->string('nomor_hp_darurat', 15)->nullable();
            $table->text('alamat_ortu')->nullable()->comment('Alamat orang tua (jika berbeda dengan siswa)');
            
            // System fields
            $table->timestamps();
            
            // Indexes
            $table->index('siswa_id');
            $table->index('nik_ayah');
            $table->index('nik_ibu');
            $table->index('nomor_hp_ayah');
            $table->index('nomor_hp_ibu');
            $table->index('nomor_hp_wali');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orang_tua');
    }
};