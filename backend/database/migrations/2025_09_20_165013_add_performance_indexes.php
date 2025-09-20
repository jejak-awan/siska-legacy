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
        // Add indexes for users table
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->index(['status', 'role_type']);
                $table->index('username');
            });
        } catch (\Exception $e) {
            // Index might already exist, continue
        }

        // Add indexes for siswa table
        try {
            Schema::table('siswa', function (Blueprint $table) {
                $table->index(['status_aktif', 'kelas_id']);
                $table->index('nisn');
                $table->index('nis');
                $table->index('angkatan');
            });
        } catch (\Exception $e) {
            // Index might already exist, continue
        }

        // Add indexes for guru table
        try {
            Schema::table('guru', function (Blueprint $table) {
                $table->index(['status_aktif', 'is_wali_kelas']);
                $table->index(['status_aktif', 'is_konselor_bk']);
                $table->index('nip');
            });
        } catch (\Exception $e) {
            // Index might already exist, continue
        }

        // Add indexes for presensi table
        try {
            Schema::table('presensi', function (Blueprint $table) {
                $table->index(['tanggal', 'status']);
                $table->index(['user_id', 'tanggal']);
            });
        } catch (\Exception $e) {
            // Index might already exist, continue
        }

        // Add indexes for kredit_poin table
        try {
            Schema::table('kredit_poin', function (Blueprint $table) {
                $table->index(['status', 'created_at']);
                $table->index(['siswa_id', 'status']);
                $table->index(['kategori_id', 'status']);
                $table->index('tanggal');
            });
        } catch (\Exception $e) {
            // Index might already exist, continue
        }

        // Add indexes for notifikasi table
        try {
            Schema::table('notifikasi', function (Blueprint $table) {
                $table->index(['user_id', 'status']);
                $table->index(['status', 'created_at']);
            });
        } catch (\Exception $e) {
            // Index might already exist, continue
        }

        // Add indexes for konseling table
        try {
            Schema::table('konseling', function (Blueprint $table) {
                $table->index(['siswa_id', 'status']);
                $table->index(['guru_id', 'status']);
                $table->index(['status', 'created_at']);
            });
        } catch (\Exception $e) {
            // Index might already exist, continue
        }

        // Add indexes for ekstrakurikuler table
        try {
            Schema::table('ekstrakurikuler', function (Blueprint $table) {
                $table->index(['status', 'created_at']);
                $table->index('nama');
            });
        } catch (\Exception $e) {
            // Index might already exist, continue
        }

        // Add indexes for osis table
        try {
            Schema::table('osis', function (Blueprint $table) {
                $table->index(['status', 'created_at']);
                $table->index('jabatan');
            });
        } catch (\Exception $e) {
            // Index might already exist, continue
        }
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove indexes for users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['status', 'role_type']);
            $table->dropIndex(['email']);
            $table->dropIndex(['username']);
        });

        // Remove indexes for siswa table
        Schema::table('siswa', function (Blueprint $table) {
            $table->dropIndex(['status_aktif', 'kelas_id']);
            $table->dropIndex(['nisn']);
            $table->dropIndex(['nis']);
            $table->dropIndex(['angkatan']);
        });

        // Remove indexes for guru table
        Schema::table('guru', function (Blueprint $table) {
            $table->dropIndex(['status_aktif', 'is_wali_kelas']);
            $table->dropIndex(['status_aktif', 'is_konselor_bk']);
            $table->dropIndex(['nip']);
        });

        // Remove indexes for presensi table
        Schema::table('presensi', function (Blueprint $table) {
            $table->dropIndex(['tanggal', 'status']);
            $table->dropIndex(['user_id', 'tanggal']);
            $table->dropIndex(['tanggal']);
        });

        // Remove indexes for kredit_poin table
        Schema::table('kredit_poin', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['siswa_id', 'status']);
            $table->dropIndex(['kategori_id', 'status']);
            $table->dropIndex(['tanggal']);
        });

        // Remove indexes for notifikasi table
        Schema::table('notifikasi', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['created_at']);
        });

        // Remove indexes for konseling table
        Schema::table('konseling', function (Blueprint $table) {
            $table->dropIndex(['siswa_id', 'status']);
            $table->dropIndex(['guru_id', 'status']);
            $table->dropIndex(['status', 'created_at']);
        });

        // Remove indexes for ekstrakurikuler table
        Schema::table('ekstrakurikuler', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['nama']);
        });

        // Remove indexes for osis table
        Schema::table('osis', function (Blueprint $table) {
            $table->dropIndex(['status', 'created_at']);
            $table->dropIndex(['jabatan']);
        });
    }
};
