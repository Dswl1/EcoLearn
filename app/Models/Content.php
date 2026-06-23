<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'body',
        'sdg_category',
        'tags',
        'status',
        'difficulty',
        'thumbnail',
        'ai_summary',
        'public_access',
        'published_at',
        'rejection_reason',
        'submitted_at',
    ];

    protected function casts(): array
    {
        return [
            'ai_summary' => 'boolean',
            'public_access' => 'boolean',
            'published_at' => 'datetime',
            'submitted_at' => 'datetime',
        ];
    }

    public function scopePendingReview($query)
    {
        return $query->where('status', 'pending_review');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function flashcards(): HasMany
    {
        return $this->hasMany(Flashcard::class);
    }

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function userProgress(): HasMany
    {
        return $this->hasMany(UserProgress::class);
    }

    public function getBodyHtmlAttribute(): string
    {
        $body = $this->body ?? '';

        if ($body === '') {
            return '';
        }

        return $body === strip_tags($body) ? nl2br(e($body)) : $body;
    }

    public static function sanitizeBody(string $body): string
    {
        $allowed = '<p><br><b><strong><i><em><u><s><ul><ol><li><a><img><code><pre><blockquote><h1><h2><h3><h4><h5><h6><span><div><br><hr><sub><sup>';

        return strip_tags($body, $allowed);
    }

    protected static function booted(): void
    {
        static::creating(function (Content $content) {
            if (empty($content->slug)) {
                $content->slug = Str::slug($content->title).'-'.Str::random(6);
            }
        });
    }
}
