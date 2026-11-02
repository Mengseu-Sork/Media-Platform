<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialAccount extends Model
{
    use HasFactory;

    protected $table = 'social_accounts';

    protected $fillable = [
        'user_id',
        'platform',
        'account_type',
        'account_id',
        'username',
        'display_name',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'metadata',
        'is_active',
        'is_shared',
    ];

    protected $hidden = [
        'access_token',
        'refresh_token',
    ];

    protected $casts = [
        'metadata' => 'array',
        'access_token' => 'encrypted',
        'refresh_token' => 'encrypted',
        'token_expires_at' => 'datetime',
        'is_active' => 'boolean',
        'is_shared' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }

    public function getPlatformNameAttribute(): string
    {
        return match (strtolower((string) $this->platform)) {
            'facebook' => 'Facebook',
            'instagram' => 'Instagram',
            'youtube' => 'YouTube',
            'tiktok' => 'TikTok',
            'linkedin' => 'LinkedIn',
            'twitter' => 'X / Twitter',
            'website' => 'Website',
            default => ucfirst(
                (string) $this->platform
            ),
        };
    }

    public function getConnectionStatusAttribute(): string
    {
        if (! $this->is_active) {
            return 'inactive';
        }

        /*
     * A refresh token means the OAuth connection can continue
     * even when the current access token has expired.
     */
        if ($this->hasRefreshConnection()) {
            return 'connected';
        }

        if (
            $this->token_expires_at &&
            $this->token_expires_at->isPast()
        ) {
            return 'expired';
        }

        return 'connected';
    }

    public function postTargets()
    {
        return $this->hasMany(
            PostTarget::class
        );
    }

    public function hasRefreshConnection(): bool
    {
        return $this->is_active === true
            && filled($this->refresh_token);
    }
}
