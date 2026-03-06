<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Course extends Model
{
    protected $fillable = [
        'category_id', 'created_by', 'title', 'slug', 'description', 'whatyoulearn',
        'thumbnail', 'preview_video_url', 'instructor_name', 'instructor_avatar',
        'level', 'duration_hours', 'total_lessons', 'language', 'status',
        'has_certificate', 'enrolled_count',
        'certificate_bg_image', 'certificate_name_x', 'certificate_name_y',
        'certificate_name_font_size', 'certificate_name_color', 'certificate_name_font',
    ];

    protected function casts(): array
    {
        return [
            'has_certificate' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class);
    }

    public function getThumbnailUrlAttribute(): string
    {
        return $this->thumbnail
            ? asset('storage/' . $this->thumbnail)
            : asset('images/course-placeholder.png');
    }
}
