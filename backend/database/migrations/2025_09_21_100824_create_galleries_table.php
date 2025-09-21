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
        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('category');
            $table->string('subcategory')->nullable();
            $table->string('image_path');
            $table->string('thumbnail_path')->nullable();
            $table->string('original_filename');
            $table->string('file_size');
            $table->string('mime_type');
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->json('metadata')->nullable();
            $table->string('uploaded_by_role');
            $table->unsignedBigInteger('uploaded_by_id');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_public')->default(true);
            $table->enum('status', ['draft', 'published', 'archived'])->default('draft');
            $table->json('tags')->nullable();
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['category', 'subcategory']);
            $table->index(['status', 'published_at']);
            $table->index(['is_featured']);
            $table->index(['is_public']);
            $table->index(['uploaded_by_role', 'uploaded_by_id']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('galleries');
    }
};