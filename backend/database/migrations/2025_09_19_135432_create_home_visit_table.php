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
        Schema::create('home_visit', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('konselor_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal_kunjungan');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('status', ['terjadwal', 'berlangsung', 'selesai', 'dibatalkan']);
            $table->text('tujuan_kunjungan');
            $table->text('hasil_kunjungan')->nullable();
            $table->text('catatan_kunjungan')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->json('data_keluarga')->nullable();
            $table->json('data_lingkungan')->nullable();
            $table->string('alamat_kunjungan');
            $table->string('koordinat_lat')->nullable();
            $table->string('koordinat_lng')->nullable();
            $table->string('foto_kunjungan')->nullable();
            $table->boolean('is_urgent')->default(false);
            $table->boolean('is_confidential')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            // Indexes
            $table->index(['siswa_id', 'tanggal_kunjungan']);
            $table->index(['konselor_id', 'tanggal_kunjungan']);
            $table->index('status');
            $table->index('is_urgent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_visit');
    }
};