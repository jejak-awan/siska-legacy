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
        Schema::create('mata_pelajaran', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20)->unique()->comment('Kode mata pelajaran (e.g., MAT-001)');
            $table->string('nama', 100)->comment('Nama mata pelajaran');
            $table->text('deskripsi')->nullable()->comment('Deskripsi mata pelajaran');
            $table->enum('kelompok', ['A', 'B', 'C'])->comment('Kelompok mata pelajaran (A: Wajib, B: Wajib, C: Peminatan)');
            $table->string('sub_kelompok', 50)->nullable()->comment('Sub kelompok (e.g., IPA, IPS, Bahasa)');
            $table->integer('jam_per_minggu')->default(2)->comment('Jam pelajaran per minggu');
            $table->integer('jam_per_semester')->nullable()->comment('Total jam per semester');
            $table->integer('kkm')->default(75)->comment('Kriteria Ketuntasan Minimal');
            $table->boolean('is_wajib')->default(true)->comment('Apakah mata pelajaran wajib');
            $table->boolean('is_peminatan')->default(false)->comment('Apakah mata pelajaran peminatan');
            $table->json('tingkat_kelas')->nullable()->comment('Array tingkat kelas yang diajarkan (e.g., [10, 11, 12])');
            $table->json('prasyarat')->nullable()->comment('Mata pelajaran prasyarat');
            $table->string('guru_id', 20)->nullable()->comment('ID guru pengampu utama');
            $table->json('guru_pengampu')->nullable()->comment('Array ID guru pengampu');
            $table->string('kurikulum', 50)->default('2013')->comment('Kurikulum yang digunakan');
            $table->text('tujuan_pembelajaran')->nullable()->comment('Tujuan pembelajaran mata pelajaran');
            $table->text('materi_pokok')->nullable()->comment('Materi pokok yang diajarkan');
            $table->json('metode_pembelajaran')->nullable()->comment('Array metode pembelajaran');
            $table->json('media_pembelajaran')->nullable()->comment('Array media pembelajaran');
            $table->json('sumber_belajar')->nullable()->comment('Array sumber belajar');
            $table->text('penilaian')->nullable()->comment('Sistem penilaian');
            $table->boolean('status_aktif')->default(true)->comment('Status aktif mata pelajaran');
            $table->string('created_by', 20)->nullable()->comment('ID user yang membuat');
            $table->string('updated_by', 20)->nullable()->comment('ID user yang terakhir update');
            $table->timestamps();

            // Indexes
            $table->index(['kelompok', 'status_aktif']);
            $table->index(['guru_id', 'status_aktif']);
            $table->index(['is_wajib', 'is_peminatan']);
            $table->index('kode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mata_pelajaran');
    }
};