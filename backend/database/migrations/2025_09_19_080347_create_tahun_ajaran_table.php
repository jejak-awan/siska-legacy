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
        Schema::create('tahun_ajaran', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 20)->unique()->comment('Format: 2024/2025');
            $table->integer('tahun_mulai')->comment('Tahun mulai (2024)');
            $table->integer('tahun_selesai')->comment('Tahun selesai (2025)');
            $table->date('tanggal_mulai')->comment('Tanggal mulai tahun ajaran');
            $table->date('tanggal_selesai')->comment('Tanggal selesai tahun ajaran');
            $table->boolean('is_aktif')->default(false)->comment('Tahun ajaran aktif saat ini');
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // Indexes
            $table->index('nama');
            $table->index('is_aktif');
            $table->index('tahun_mulai');
            $table->index('tahun_selesai');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tahun_ajaran');
    }
};