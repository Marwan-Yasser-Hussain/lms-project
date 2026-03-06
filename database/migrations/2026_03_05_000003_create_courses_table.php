<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('whatyoulearn')->nullable(); // JSON-like text for bullet points
            $table->string('thumbnail')->nullable();
            $table->string('preview_video_url')->nullable(); // Link (YouTube/Vimeo/etc.)
            $table->string('instructor_name');
            $table->string('instructor_avatar')->nullable();
            $table->enum('level', ['beginner', 'intermediate', 'advanced'])->default('beginner');
            $table->integer('duration_hours')->default(0);
            $table->integer('total_lessons')->default(0);
            $table->string('language')->default('English');
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->boolean('has_certificate')->default(true);
            $table->unsignedInteger('enrolled_count')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
