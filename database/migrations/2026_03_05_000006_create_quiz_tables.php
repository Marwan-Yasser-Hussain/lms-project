<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Quizzes — attached to a course
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('passing_score')->default(70); // percentage
            $table->integer('time_limit_minutes')->nullable(); // null = no limit
            $table->boolean('randomize_questions')->default(false);
            $table->boolean('show_result_immediately')->default(true);
            $table->integer('max_attempts')->default(3);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Quiz Questions
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->text('question');
            $table->enum('type', [
                'multiple_choice',   // one correct answer
                'multiple_select',   // multiple correct answers
                'true_false',        // true or false
                'short_answer',      // text input
                'fill_in_the_blank', // fill the blank
                'matching',          // match pairs
            ])->default('multiple_choice');
            $table->text('explanation')->nullable(); // shown after answering
            $table->integer('points')->default(1);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        // Quiz Question Options (for multiple choice, true/false, matching)
        Schema::create('quiz_question_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('quiz_questions')->cascadeOnDelete();
            $table->text('option_text');
            $table->text('match_text')->nullable(); // for matching type: the right side
            $table->boolean('is_correct')->default(false);
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });

        // Student Quiz Attempts
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('quiz_id')->constrained()->cascadeOnDelete();
            $table->decimal('score', 5, 2)->default(0);
            $table->boolean('passed')->default(false);
            $table->integer('attempt_number')->default(1);
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });

        // Student Quiz Answers
        Schema::create('quiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('quiz_attempts')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('quiz_questions')->cascadeOnDelete();
            $table->text('answer_text')->nullable(); // for short_answer / fill_blank
            $table->json('selected_option_ids')->nullable(); // for MC, MS, TF, matching
            $table->boolean('is_correct')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quiz_answers');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_question_options');
        Schema::dropIfExists('quiz_questions');
        Schema::dropIfExists('quizzes');
    }
};
