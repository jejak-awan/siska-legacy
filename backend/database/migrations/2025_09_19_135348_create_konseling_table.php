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
        Schema::create('konseling', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('konselor_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_konseling');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('jenis_konseling', ['individual', 'kelompok', 'keluarga', 'krisis']);
            $table->enum('status', ['terjadwal', 'berlangsung', 'selesai', 'dibatalkan']);
            $table->text('masalah');
            $table->text('tujuan_konseling');
            $table->text('intervensi')->nullable();
            $table->text('hasil_konseling')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->text('catatan_konselor')->nullable();
            $table->json('data_tambahan')->nullable();
            $table->string('tempat_konseling')->default('ruang_bk');
            $table->boolean('is_urgent')->default(false);
            $table->boolean('is_confidential')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            // Indexes
            $table->index(['siswa_id', 'tanggal_konseling']);
            $table->index(['konselor_id', 'tanggal_konseling']);
            $table->index('status');
            $table->index('jenis_konseling');
            $table->index('is_urgent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konseling');
    }
};