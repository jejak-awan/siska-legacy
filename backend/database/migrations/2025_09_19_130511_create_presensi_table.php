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
        Schema::create('presensi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            $table->time('jam_masuk')->nullable();
            $table->time('jam_keluar')->nullable();
            $table->enum('status', ['hadir', 'terlambat', 'sakit', 'izin', 'alpha'])->default('alpha');
            $table->decimal('lokasi_lat', 10, 8)->nullable();
            $table->decimal('lokasi_lng', 11, 8)->nullable();
            $table->string('qr_code')->nullable();
            $table->string('foto_absen')->nullable();
            $table->text('keterangan')->nullable();
            $table->foreignId('divalidasi_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'tanggal']);
            $table->index('tanggal');
            $table->index('status');
            $table->unique(['user_id', 'tanggal']); // Prevent duplicate attendance per day
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
