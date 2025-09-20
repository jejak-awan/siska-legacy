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
        Schema::connection('public')->create('general_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            $table->string('category'); // osis, ekskul, guru, wali_kelas, admin
            $table->string('subcategory'); // news, announcement, event, achievement
            $table->string('status')->default('draft'); // draft, review, approved, published
            $table->json('tags')->nullable();
            $table->json('attachments')->nullable();
            $table->string('author_role');
            $table->unsignedBigInteger('author_id');
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_pinned')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            
            $table->index(['category', 'status']);
            $table->index(['author_role', 'author_id']);
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('public')->dropIfExists('general_posts');
    }
};
