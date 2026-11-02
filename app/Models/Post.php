<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',

        'title',
        'title_Kh',

        'content',
        'content_Kh',

        'website_department',
        'youtube_slideshow_path',

        'status',
        'scheduled_at',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function media()
    {
        return $this->hasMany(
            PostMedia::class
        )->orderBy(
            'sort_order'
        );
    }

    public function targets()
    {
        return $this->hasMany(
            PostTarget::class
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }
}
