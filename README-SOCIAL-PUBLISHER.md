# NGO Forum Social Publisher - Corrected Laravel Version

This package is the original Laravel project with the social-publishing integration corrected and completed around the uploaded project.

## Main corrections

1. The media-account create flow no longer asks users to paste access/refresh tokens.
2. Facebook and Instagram follow the original Python configuration style:
   - META_PAGE_ID
   - META_PAGE_TOKEN
   - META_INSTAGRAM_BUSINESS_ID
3. YouTube uses Google OAuth and stores access/refresh tokens in `social_accounts`.
4. TikTok uses TikTok OAuth and stores access/refresh tokens and expiry.
5. LinkedIn uses OAuth and an organization ID from configuration.
6. X/Twitter uses OAuth 2.0 PKCE and stores access/refresh tokens.
7. Added provider classes for all six platforms.
8. Fixed PostController field names to match the `posts` migration (`title_EN`, `content_EN`, `title_Kh`, `content_Kh`).
9. Added post create/index views because the controller routes require them.
10. Fixed queue job import for Laravel 10 and added database queue support.
11. Scheduled posts are queued every minute and post status is synchronized from publishing targets.
12. OAuth tokens are encrypted by the `SocialAccount` model.

## Install

From the project folder:

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan storage:link
php artisan migrate
npm install
npm run build
```

## Queue

The `.env.example` uses:

```env
QUEUE_CONNECTION=database
```

Start a worker:

```bash
php artisan queue:work
```

## Scheduler

For Linux production cron:

```cron
* * * * * cd /path/to/social-publisher && php artisan schedule:run >> /dev/null 2>&1
```

## Provider configuration

Fill the provider credentials in `.env`.

### Facebook / Instagram

```env
META_GRAPH_VERSION=v24.0
META_PAGE_ID=
META_PAGE_TOKEN=
META_INSTAGRAM_BUSINESS_ID=
```

### YouTube

```env
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URI=https://your-domain.com/social-accounts/youtube/callback
YOUTUBE_CATEGORY_ID=22
YOUTUBE_PRIVACY=public
```

### TikTok

```env
TIKTOK_CLIENT_KEY=
TIKTOK_CLIENT_SECRET=
TIKTOK_REDIRECT_URI=https://your-domain.com/social-accounts/tiktok/callback
```

### LinkedIn

```env
LINKEDIN_CLIENT_ID=
LINKEDIN_CLIENT_SECRET=
LINKEDIN_REDIRECT_URI=https://your-domain.com/social-accounts/linkedin/callback
LINKEDIN_ORGANIZATION_ID=
LINKEDIN_VERSION=202608
```

### X

```env
TWITTER_CLIENT_ID=
TWITTER_CLIENT_SECRET=
TWITTER_REDIRECT_URI=https://your-domain.com/social-accounts/twitter/callback
```

The callback URLs must be registered in the corresponding developer consoles.

## Important media URL requirement

Instagram and TikTok `PULL_FROM_URL` style publishing requires the platform to be able to reach the media URL. In production use HTTPS and a public URL for the application's storage media. For TikTok photo/video direct posting, the media URL/domain must satisfy TikTok's verification requirements.

## Test order

1. Run migrations.
2. Connect Facebook.
3. Connect Instagram.
4. Connect YouTube.
5. Connect TikTok.
6. Connect LinkedIn.
7. Connect X.
8. Create a draft post.
9. Publish manually.
10. Create a scheduled post.
11. Run `php artisan queue:work`.
12. Run `php artisan schedule:work` during development or configure cron in production.

## Provider implementation

The provider classes are:

```text
app/Services/Social/FacebookProvider.php
app/Services/Social/InstagramProvider.php
app/Services/Social/YouTubeProvider.php
app/Services/Social/TikTokProvider.php
app/Services/Social/LinkedInProvider.php
app/Services/Social/TwitterProvider.php
```

The common publisher selects the provider from `SocialAccount::platform`.

