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
        Schema::create('ekstrakurikuler', function (Blueprint $table) {
            $table->id();
            $table->string('nama_ekstrakurikuler');
            $table->text('deskripsi');
            $table->enum('jenis', ['olahraga', 'seni', 'akademik', 'keagamaan', 'keterampilan', 'sosial']);
            $table->enum('status', ['aktif', 'tidak_aktif', 'ditutup']);
            $table->string('pembina_id')->nullable();
            $table->string('ketua_id')->nullable();
            $table->text('jadwal_latihan');
            $table->string('tempat_latihan');
            $table->text('persyaratan_anggota')->nullable();
            $table->integer('kuota_anggota')->nullable();
            $table->decimal('biaya_pendaftaran', 10, 2)->default(0);
            $table->text('fasilitas')->nullable();
            $table->text('prestasi')->nullable();
            $table->string('foto_ekstrakurikuler')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            // Indexes
            $table->index('jenis');
            $table->index('status');
            $table->index('is_aktif');
            $table->index('pembina_id');
            $table->index('ketua_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ekstrakurikuler');
    }
};