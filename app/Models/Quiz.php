<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Quiz extends Model
{
    protected $fillable = [
        'course_id', 'lesson_id', 'title', 'description', 'passing_score',
        'time_limit_minutes', 'randomize_questions', 'show_result_immediately',
        'max_attempts', 'is_active', 'order', 'quiz_type',
    ];

    protected function casts(): array
    {
        return [
            'randomize_questions'    => 'boolean',
            'show_result_immediately'=> 'boolean',
            'is_active'              => 'boolean',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(QuizQuestion::class)->orderBy('order');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(QuizAttempt::class);
    }
}
