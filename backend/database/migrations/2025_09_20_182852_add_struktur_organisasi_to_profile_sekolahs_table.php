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
        Schema::table('profile_sekolahs', function (Blueprint $table) {
            $table->json('struktur_organisasi')->nullable()->after('prestasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('profile_sekolahs', function (Blueprint $table) {
            $table->dropColumn('struktur_organisasi');
        });
    }
};