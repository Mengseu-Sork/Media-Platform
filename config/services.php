<?php

return [

    'meta' => [
        'graph_version' => env('META_GRAPH_VERSION', 'v24.0'),
    ],

    'facebook' => [
        'page_id' => env('META_PAGE_ID'),
        'page_token' => env('META_PAGE_TOKEN'),
    ],

    'instagram' => [
        'business_id' => env('META_INSTAGRAM_BUSINESS_ID'),
        'access_token' => env('META_PAGE_TOKEN'),

        'public_media_base_url' => env(
            'INSTAGRAM_PUBLIC_MEDIA_BASE_URL',
            env('APP_URL')
        ),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
        'youtube_category' => env('YOUTUBE_CATEGORY_ID', '22'),
        'youtube_privacy' => env('YOUTUBE_PRIVACY', 'public'),
    ],

    'tiktok' => [
        'client_key' => env('TIKTOK_CLIENT_KEY'),
        'client_secret' => env('TIKTOK_CLIENT_SECRET'),
        'redirect' => env('TIKTOK_REDIRECT_URI'),
    ],

    'linkedin' => [

        'client_id' => env(
            'LINKEDIN_CLIENT_ID'
        ),

        'client_secret' => env(
            'LINKEDIN_CLIENT_SECRET'
        ),

        'redirect' => env(
            'LINKEDIN_REDIRECT_URI'
        ),

        'organization_id' => env(
            'LINKEDIN_ORGANIZATION_ID'
        ),

        'version' => env(
            'LINKEDIN_VERSION',
            '202608'
        ),

        'max_post_characters' => (int) env(
            'LINKEDIN_MAX_POST_CHARACTERS',
            3000
        ),
    ],

    'twitter' => [
        'client_id' => env('TWITTER_CLIENT_ID'),
        'client_secret' => env('TWITTER_CLIENT_SECRET'),
        'redirect' => env('TWITTER_REDIRECT_URI'),
    ],

    'website' => [
        'endpoint' => env('API_WEBSITE_ENDPOINT'),
        'token' => env('API_WEBSITE_TOKEN'),
    ],

    'social' => [
        'shared_account_user_id' => env(
            'SOCIAL_SHARED_ACCOUNT_USER_ID'
        ),
    ],

    // Keep your normal Laravel mail/aws/etc. entries below.
    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
];
