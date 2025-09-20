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
        Schema::create('profile_sekolahs', function (Blueprint $table) {
            $table->id();
            $table->string('nama')->nullable();
            $table->string('npsn')->nullable();
            $table->text('alamat')->nullable();
            $table->string('telepon')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('jenjang')->nullable(); // SD, SMP, SMA, SMK
            $table->string('status')->nullable(); // Negeri, Swasta
            $table->string('akreditasi')->nullable(); // A, B, C, TT
            $table->string('kepala_sekolah')->nullable();
            $table->text('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('tujuan')->nullable();
            $table->string('logo')->nullable();
            $table->string('foto_kepala_sekolah')->nullable();
            $table->json('kontak_lain')->nullable(); // JSON untuk kontak tambahan
            $table->json('sosial_media')->nullable(); // JSON untuk sosial media
            $table->text('sejarah')->nullable();
            $table->text('prestasi')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profile_sekolahs');
    }
};
