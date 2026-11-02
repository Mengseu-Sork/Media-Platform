<?php

namespace App\Services\Social;

use App\Models\SocialAccount;
use Google\Client as GoogleClient;
use RuntimeException;

class YouTubeTokenService
{
    /**
     * Get a valid YouTube access token.
     *
     * - Uses current access token while valid.
     * - Automatically refreshes when expired / nearly expired.
     * - Saves the new access token.
     * - Preserves the existing refresh token.
     */
    public function getAccessToken(SocialAccount $account): string
    {
        if (strtolower((string) $account->platform) !== 'youtube') {
            throw new RuntimeException(
                'YouTubeTokenService can only be used with YouTube accounts.'
            );
        }

        if (!$account->is_active) {
            throw new RuntimeException(
                'The YouTube account is inactive.'
            );
        }

        $refreshToken = $account->refresh_token;

        if (empty($refreshToken)) {
            throw new RuntimeException(
                'No YouTube refresh token is stored. Please connect YouTube again.'
            );
        }

        $client = $this->makeClient();

        /*
        |--------------------------------------------------------------------------
        | Give Google the stored credentials
        |--------------------------------------------------------------------------
        */
        $client->setAccessToken([
            'access_token' => $account->access_token,
            'refresh_token' => $refreshToken,
            'expires_at' => $account->token_expires_at
                ? $account->token_expires_at->timestamp
                : 0,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Access token is still valid
        |--------------------------------------------------------------------------
        |
        | Refresh 2 minutes before expiry so a long upload does not start
        | with a token that is about to expire.
        |
        */
        if (
            !empty($account->access_token) &&
            $account->token_expires_at &&
            $account->token_expires_at->gt(now()->addMinutes(2))
        ) {
            return (string) $account->access_token;
        }

        /*
        |--------------------------------------------------------------------------
        | Refresh automatically
        |--------------------------------------------------------------------------
        */
        $newToken = $client->fetchAccessTokenWithRefreshToken(
            $refreshToken
        );

        /*
        |--------------------------------------------------------------------------
        | Check refresh response
        |--------------------------------------------------------------------------
        */
        if (!empty($newToken['error'])) {
            $message =
                $newToken['error_description']
                ?? $newToken['error']
                ?? 'Unable to refresh the YouTube access token.';

            throw new RuntimeException($message);
        }

        if (empty($newToken['access_token'])) {
            throw new RuntimeException(
                'Google did not return a new YouTube access token.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Save new access token
        |--------------------------------------------------------------------------
        */
        $account->access_token = $newToken['access_token'];

        $account->token_expires_at = now()->addSeconds(
            (int) ($newToken['expires_in'] ?? 3600)
        );

        /*
        |--------------------------------------------------------------------------
        | Preserve refresh token
        |--------------------------------------------------------------------------
        |
        | Google does not necessarily return a refresh token when refreshing.
        | Never replace a valid stored refresh token with null.
        |
        */
        if (!empty($newToken['refresh_token'])) {
            $account->refresh_token = $newToken['refresh_token'];
        }

        $account->save();

        return (string) $account->access_token;
    }

    /**
     * Create Google OAuth client.
     */
    protected function makeClient(): GoogleClient
    {
        $client = new GoogleClient();

        $client->setClientId(
            config('services.google.client_id')
        );

        $client->setClientSecret(
            config('services.google.client_secret')
        );

        $client->setAccessType('offline');

        return $client;
    }
}
