<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('video_url')->nullable(); // YouTube/Vimeo/Google Drive link
            $table->string('video_embed_type')->default('youtube'); // youtube, vimeo, other
            $table->text('description')->nullable();
            $table->string('resource_url')->nullable(); // downloadable resource link
            $table->integer('duration_minutes')->default(0);
            $table->unsignedInteger('order')->default(0);
            $table->boolean('is_free_preview')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lessons');
    }
};
