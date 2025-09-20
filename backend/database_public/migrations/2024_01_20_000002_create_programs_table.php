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
        Schema::connection('public')->create('programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('category'); // kesiswaan, akademik, karakter, ekskul
            $table->string('status')->default('planning'); // planning, active, completed, cancelled
            $table->date('start_date');
            $table->date('end_date');
            $table->json('objectives');
            $table->json('target_audience');
            $table->string('responsible_role');
            $table->unsignedBigInteger('responsible_id');
            $table->json('components')->nullable();
            $table->json('completion_status')->nullable();
            $table->integer('completion_percentage')->default(0);
            $table->timestamps();
            
            $table->index(['category', 'status']);
            $table->index(['responsible_role', 'responsible_id']);
            $table->index('start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('public')->dropIfExists('programs');
    }
};
