<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PublishingLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_target_id',
        'action',
        'http_status',
        'response',
        'error',
    ];

    public function postTarget(): BelongsTo
    {
        return $this->belongsTo(
            PostTarget::class
        );
    }
}