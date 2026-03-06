<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            // Link quiz to a lesson (null = standalone / between-lessons quiz)
            $table->foreignId('lesson_id')->nullable()->after('course_id')
                  ->constrained('lessons')->nullOnDelete();

            // Position in the course curriculum (used for standalone quizzes)
            $table->unsignedInteger('order')->default(0)->after('lesson_id');

            // Type: attached to a lesson OR placed between lessons
            $table->enum('quiz_type', ['lesson_quiz', 'between_lessons'])
                  ->default('between_lessons')->after('order');
        });
    }

    public function down(): void
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropForeign(['lesson_id']);
            $table->dropColumn(['lesson_id', 'order', 'quiz_type']);
        });
    }
};
