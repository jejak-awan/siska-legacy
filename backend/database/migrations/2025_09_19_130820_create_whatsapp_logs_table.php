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
        Schema::create('whatsapp_logs', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number', 20);
            $table->text('message');
            $table->enum('status', ['sent', 'delivered', 'read', 'failed'])->default('sent');
            $table->string('message_id')->nullable(); // WhatsApp message ID
            $table->json('response_data')->nullable(); // Full response from WhatsApp API
            $table->text('error_message')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('template_name')->nullable(); // WhatsApp template name
            $table->json('template_params')->nullable(); // Template parameters
            $table->timestamps();

            // Indexes for performance
            $table->index('phone_number');
            $table->index('status');
            $table->index('message_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_logs');
    }
};
