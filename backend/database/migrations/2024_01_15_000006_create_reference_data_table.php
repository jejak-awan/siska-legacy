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
        Schema::create('reference_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('reference_categories')->onDelete('cascade');
            $table->string('code')->nullable(); // Kode referensi (optional)
            $table->string('name'); // Nama referensi
            $table->text('description')->nullable();
            $table->json('custom_fields')->nullable(); // Field dinamis sesuai konfigurasi kategori
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            
            $table->index(['category_id', 'is_active', 'sort_order']);
            $table->unique(['category_id', 'code']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reference_data');
    }
};
