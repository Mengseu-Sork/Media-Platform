<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PostTarget extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_id',
        'social_account_id',
        'status',
        'error_message',
        'external_post_id',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(
            Post::class
        );
    }

    public function socialAccount(): BelongsTo
    {
        return $this->belongsTo(
            SocialAccount::class
        );
    }

    public function logs(): HasMany
    {
        return $this->hasMany(
            PublishingLog::class
        );
    }
}
