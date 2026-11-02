<?php

namespace App\Enums;

enum SocialPlatform: string
{
    case FACEBOOK = 'facebook';
    case INSTAGRAM = 'instagram';
    case YOUTUBE = 'youtube';
    case TIKTOK = 'tiktok';
    case LINKEDIN = 'linkedin';
    case TWITTER = 'twitter';
    case WEBSITE = 'website';

    public function label(): string
    {
        return match ($this) {
            self::FACEBOOK => 'Facebook',
            self::INSTAGRAM => 'Instagram',
            self::YOUTUBE => 'YouTube',
            self::TIKTOK => 'TikTok',
            self::LINKEDIN => 'LinkedIn',
            self::TWITTER => 'X / Twitter',
            self::WEBSITE => 'Website',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::FACEBOOK => 'Facebook Page',
            self::INSTAGRAM => 'Instagram Business',
            self::YOUTUBE => 'YouTube Channel',
            self::TIKTOK => 'TikTok Account',
            self::LINKEDIN => 'LinkedIn Organization',
            self::TWITTER => 'X / Twitter Account',
            self::WEBSITE => 'NGO Forum Website',
        };
    }

    public function connectionMethod(): string
    {
        return match ($this) {
            self::FACEBOOK => 'Server configuration',
            self::INSTAGRAM => 'Server configuration',
            self::YOUTUBE => 'Google OAuth',
            self::TIKTOK => 'TikTok OAuth',
            self::LINKEDIN => 'LinkedIn OAuth',
            self::TWITTER => 'X OAuth 2.0',
            self::WEBSITE => 'Website API',
        };
    }
}