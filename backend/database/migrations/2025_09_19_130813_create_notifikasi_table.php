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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('judul', 255);
            $table->text('pesan');
            $table->enum('tipe', ['info', 'warning', 'error', 'success'])->default('info');
            $table->enum('status', ['unread', 'read', 'archived'])->default('unread');
            $table->json('data')->nullable(); // Additional data for the notification
            $table->string('action_url')->nullable(); // URL for action button
            $table->string('action_text')->nullable(); // Text for action button
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'status']);
            $table->index('tipe');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
