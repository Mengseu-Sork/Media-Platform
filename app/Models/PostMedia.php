<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostMedia extends Model
{
    use HasFactory;

    protected $table = 'post_media';

    protected $fillable = [
        'post_id',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'sort_order',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(
            Post::class
        );
    }

    public function getUrlAttribute(): string
    {
        return asset(
            'storage/' .
                ltrim(
                    $this->file_path,
                    '/'
                )
        );
    }
}
