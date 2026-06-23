<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Flashcard extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_id',
        'question',
        'answer',
        'order',
    ];

    public function content(): BelongsTo
    {
        return $this->belongsTo(Content::class);
    }
}
