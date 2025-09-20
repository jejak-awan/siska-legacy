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
        Schema::create('ekstrakurikuler_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ekstrakurikuler_id')->constrained('ekstrakurikuler')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->date('tanggal_daftar');
            $table->enum('status', ['aktif', 'tidak_aktif', 'keluar']);
            $table->text('alasan_keluar')->nullable();
            $table->text('catatan')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            // Indexes
            $table->index(['ekstrakurikuler_id', 'siswa_id']);
            $table->index('status');
            $table->index('is_aktif');
            $table->index('tanggal_daftar');
            
            // Unique constraint
            $table->unique(['ekstrakurikuler_id', 'siswa_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ekstrakurikuler_siswa');
    }
};