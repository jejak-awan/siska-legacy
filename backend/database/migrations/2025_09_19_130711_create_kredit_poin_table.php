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
        Schema::create('kredit_poin', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('kategori_kredit_poin')->onDelete('cascade');
            $table->integer('nilai');
            $table->text('deskripsi');
            $table->date('tanggal');
            $table->foreignId('pelapor_id')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['siswa_id', 'tanggal']);
            $table->index('status');
            $table->index('tanggal');
            $table->index('kategori_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kredit_poin');
    }
};
