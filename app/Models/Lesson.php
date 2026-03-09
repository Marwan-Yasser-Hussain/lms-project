<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lesson extends Model
{
    protected $fillable = [
        'course_id', 'title', 'video_url', 'video_embed_type',
        'description', 'resource_url', 'duration_minutes', 'order', 'is_free_preview',
    ];

    protected function casts(): array
    {
        return ['is_free_preview' => 'boolean'];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function completions(): HasMany
    {
        return $this->hasMany(LessonCompletion::class);
    }

    public function quiz(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Quiz::class)->where('quiz_type', 'lesson_quiz');
    }

    /**
     * Convert any supported video URL to embeddable format.
     */
    public function getEmbedUrlAttribute(): ?string
    {
        if (!$this->video_url) return null;

        $url = $this->video_url;

        // YouTube
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $m)) {
            return 'https://www.youtube.com/embed/' . $m[1] . '?modestbranding=1&rel=0&iv_load_policy=3&showinfo=0&controls=0&disablekb=1';
        }

        // Vimeo
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $m)) {
            return 'https://player.vimeo.com/video/' . $m[1];
        }

        // Dailymotion
        if (preg_match('/dailymotion\.com\/video\/([a-zA-Z0-9]+)/', $url, $m)) {
            // Added flags to try and minimize UI: ui-logo=0, ui-start-screen-info=0
            return 'https://www.dailymotion.com/embed/video/' . $m[1] . '?ui-logo=0&ui-start-screen-info=0&sharing-enable=0';
        }

        // Google Drive
        if (preg_match('/drive\.google\.com\/file\/d\/([a-zA-Z0-9_-]+)/', $url, $m)) {
            return 'https://drive.google.com/file/d/' . $m[1] . '/preview';
        }

        return $url; // fallback — direct embed
    }

    /**
     * Get the video provider (youtube or vimeo) for Plyr.
     */
    public function getVideoProviderAttribute(): ?string
    {
        if (!$this->video_url) return null;

        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)[a-zA-Z0-9_-]+/', $this->video_url)) {
            return 'youtube';
        }

        if (preg_match('/vimeo\.com\/\d+/', $this->video_url)) {
            return 'vimeo';
        }

        if (preg_match('/dailymotion\.com\/video\/[a-zA-Z0-9]+/', $this->video_url)) {
            return 'dailymotion';
        }

        return null;
    }

    /**
     * Get the extracted video ID for Plyr.
     */
    public function getVideoIdAttribute(): ?string
    {
        if (!$this->video_url) return null;

        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $this->video_url, $m)) {
            return $m[1];
        }

        if (preg_match('/vimeo\.com\/(\d+)/', $this->video_url, $m)) {
            return $m[1];
        }

        if (preg_match('/dailymotion\.com\/video\/([a-zA-Z0-9]+)/', $this->video_url, $m)) {
            return $m[1];
        }

        return null;
    }
}

