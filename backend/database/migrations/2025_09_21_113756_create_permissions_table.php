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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique()->comment('Permission name (e.g., siswa.create)');
            $table->string('display_name', 100)->comment('Display name (e.g., Create Student)');
            $table->string('description')->nullable()->comment('Permission description');
            $table->string('module', 50)->comment('Module name (e.g., siswa, presensi)');
            $table->string('action', 50)->comment('Action name (e.g., create, read, update, delete)');
            $table->string('resource', 50)->nullable()->comment('Resource name (e.g., siswa, kelas)');
            $table->boolean('is_system')->default(false)->comment('System permission (cannot be deleted)');
            $table->boolean('is_active')->default(true)->comment('Permission is active');
            $table->integer('sort_order')->default(0)->comment('Sort order for display');
            $table->timestamps();

            // Indexes
            $table->index(['module', 'action']);
            $table->index(['resource', 'action']);
            $table->index('is_active');
            $table->index('sort_order');
        });

        Schema::create('role_permissions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id');
            $table->unsignedBigInteger('permission_id');
            $table->boolean('granted')->default(true)->comment('Permission granted or denied');
            $table->timestamps();

            // Foreign keys
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
            $table->foreign('permission_id')->references('id')->on('permissions')->onDelete('cascade');

            // Unique constraint
            $table->unique(['role_id', 'permission_id']);

            // Indexes
            $table->index(['role_id', 'granted']);
            $table->index(['permission_id', 'granted']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_permissions');
        Schema::dropIfExists('permissions');
    }
};