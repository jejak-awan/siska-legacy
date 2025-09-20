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
        Schema::create('jadwal_presensi', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jadwal', 100);
            $table->enum('jenis', ['masuk', 'pulang', 'istirahat', 'kegiatan'])->default('masuk');
            $table->time('jam_mulai');
            $table->time('jam_selesai')->nullable();
            $table->json('hari_aktif')->nullable(); // ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu']
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('cascade');
            $table->foreignId('tahun_ajaran_id')->constrained('tahun_ajaran')->onDelete('cascade');
            $table->boolean('is_aktif')->default(true);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['kelas_id', 'tahun_ajaran_id']);
            $table->index('jenis');
            $table->index('is_aktif');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_presensi');
    }
};
