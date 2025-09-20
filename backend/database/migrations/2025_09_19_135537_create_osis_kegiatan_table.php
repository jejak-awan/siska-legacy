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
        Schema::create('osis_kegiatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kegiatan');
            $table->text('deskripsi');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->time('jam_mulai');
            $table->time('jam_selesai');
            $table->enum('jenis_kegiatan', ['akademik', 'non_akademik', 'sosial', 'olahraga', 'seni', 'keagamaan']);
            $table->enum('status', ['perencanaan', 'persiapan', 'berlangsung', 'selesai', 'dibatalkan']);
            $table->string('tempat_kegiatan');
            $table->text('tujuan_kegiatan');
            $table->text('sasaran_kegiatan');
            $table->decimal('anggaran', 15, 2)->nullable();
            $table->text('sumber_dana')->nullable();
            $table->text('penanggung_jawab');
            $table->json('peserta')->nullable();
            $table->json('panitia')->nullable();
            $table->text('keterangan')->nullable();
            $table->string('foto_kegiatan')->nullable();
            $table->boolean('is_aktif')->default(true);
            $table->boolean('is_urgent')->default(false);
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            
            // Indexes
            $table->index(['tanggal_mulai', 'tanggal_selesai']);
            $table->index('jenis_kegiatan');
            $table->index('status');
            $table->index('is_aktif');
            $table->index('is_urgent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('osis_kegiatan');
    }
};