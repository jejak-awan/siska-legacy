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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas', 20)->comment('Format: X-A, XI IPA 1, XII IPS 2');
            $table->integer('tingkat')->comment('Tingkat kelas: 10, 11, 12 atau 7, 8, 9');
            $table->string('jurusan', 50)->nullable()->comment('IPA, IPS, Bahasa, dll');
            $table->string('rombel', 10)->comment('Rombongan belajar: A, B, C, 1, 2, 3');
            $table->integer('kapasitas')->default(32)->comment('Kapasitas maksimal siswa');
            $table->integer('jumlah_siswa')->default(0)->comment('Jumlah siswa saat ini');
            $table->foreignId('wali_kelas_id')->nullable()->constrained('guru')->onDelete('set null');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Unique constraint untuk kombinasi nama kelas dan tahun ajaran
            $table->unique(['nama_kelas', 'tahun_ajaran_id']);

            // Indexes
            $table->index('nama_kelas');
            $table->index('tingkat');
            $table->index('jurusan');
            $table->index('wali_kelas_id');
            $table->index('tahun_ajaran_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};