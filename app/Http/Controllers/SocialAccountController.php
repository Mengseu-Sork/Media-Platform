<?php

namespace App\Http\Controllers;

use App\Enums\SocialPlatform;
use App\Models\SocialAccount;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\View\View;
use RuntimeException;
use Google\Client;
use Google\Service\YouTube;


class SocialAccountController extends Controller
{
    protected function sharedAccountUserId(): int
    {
        return (int) config('services.social.shared_account_user_id');
    }

    protected function ensureSharedAccountManager(): void
    {
        abort_unless(
            (int) auth()->id() === $this->sharedAccountUserId(),
            403,
            'You are not allowed to manage shared social accounts.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | INDEX
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        $socialAccounts = SocialAccount::query()
            ->latest()
            ->paginate(10);

        return view(
            'social-accounts.index',
            compact('socialAccounts')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CREATE
    |--------------------------------------------------------------------------
    */

    public function create(): View
    {
        $platforms =
            SocialPlatform::cases();

        return view(
            'social-accounts.create',
            compact('platforms')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | STORE
    |--------------------------------------------------------------------------
    */

    public function store(
        Request $request
    ): RedirectResponse {

        $platform =
            $request->validate([
                'platform' => [
                    'required',
                    'in:facebook,instagram,youtube,tiktok,linkedin,twitter,website',
                ],
            ])['platform'];

        return match ($platform) {

            'facebook' =>
            $this->storeFacebookFromConfig(),

            'instagram' =>
            $this->storeInstagramFromConfig(),

            'youtube' =>
            redirect()->route(
                'social-accounts.youtube.connect'
            ),

            'tiktok' =>
            redirect()->route(
                'social-accounts.tiktok.connect'
            ),

            'linkedin' =>
            redirect()->route(
                'social-accounts.linkedin.connect'
            ),

            'twitter' =>
            redirect()->route(
                'social-accounts.twitter.connect'
            ),

            'website' =>
            $this->storeWebsiteFromConfig(),
        };
    }


    /*
    |--------------------------------------------------------------------------
    | WEBSITE
    |--------------------------------------------------------------------------
    */

    protected function storeWebsiteFromConfig(): RedirectResponse
    {
        $endpoint = config('services.website.endpoint');
        $token = config('services.website.token');

        if (!$endpoint || !$token) {
            return back()->with(
                'error',
                'Website API endpoint or token is not configured.'
            );
        }

        SocialAccount::updateOrCreate(
            [
                'user_id' => $this->sharedAccountUserId(),
                'platform' => 'website',
                'account_id' => 'website',
            ],
            [
                'account_type' => 'website',
                'username' => null,
                'display_name' => 'The NGO Forum on Cambodia',
                'access_token' => null,
                'refresh_token' => null,
                'token_expires_at' => null,

                'metadata' => [
                    'endpoint' => $endpoint,
                    'source' => 'configuration',
                    'authentication' => 'sanctum',
                ],

                'is_active' => true,
                'is_shared' => true,
            ]
        );

        return redirect()
            ->route('social-accounts.index')
            ->with(
                'success',
                'NGO Forum Website connected successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | FACEBOOK
    |--------------------------------------------------------------------------
    */

    protected function storeFacebookFromConfig(): RedirectResponse
    {
        $pageId =
            config(
                'services.facebook.page_id'
            );

        $token =
            config(
                'services.facebook.page_token'
            );

        if (! $pageId || ! $token) {

            return back()->with(
                'error',
                'Facebook Page ID or Page Token is not configured.'
            );
        }

        $version =
            config(
                'services.meta.graph_version',
                'v24.0'
            );

        $response =
            Http::withToken($token)
            ->acceptJson()
            ->get(
                "https://graph.facebook.com/{$version}/{$pageId}",
                [
                    'fields' =>
                    'id,name,category,picture',
                ]
            );

        if ($response->failed()) {

            return back()->with(
                'error',
                'Facebook Page verification failed: ' .
                    (
                        $response->json(
                            'error.message'
                        )
                        ?? 'Unknown error.'
                    )
            );
        }

        $page =
            $response->json();

        SocialAccount::updateOrCreate(
            [
                'user_id' => $this->sharedAccountUserId(),

                'platform' =>
                'facebook',

                'account_id' =>
                $pageId,
            ],
            [
                'account_type' =>
                'page',

                'username' =>
                null,

                'display_name' =>
                $page['name']
                    ?? 'Facebook Page',

                'access_token' =>
                $token,

                'refresh_token' =>
                null,

                'token_expires_at' =>
                null,

                'metadata' => [
                    'category' =>
                    $page['category']
                        ?? null,

                    'picture' =>
                    $page['picture']['data']['url']
                        ?? null,

                    'source' =>
                    'environment',
                ],

                'is_active' =>
                true,

                'is_shared' => true,
            ]
        );

        return redirect()
            ->route(
                'social-accounts.index'
            )
            ->with(
                'success',
                'Facebook Page connected successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | INSTAGRAM
    |--------------------------------------------------------------------------
    */

    protected function storeInstagramFromConfig(): RedirectResponse
    {
        $instagramId =
            config(
                'services.instagram.business_id'
            );

        $token =
            config(
                'services.instagram.access_token'
            );

        if (! $instagramId || ! $token) {

            return back()->with(
                'error',
                'Instagram Business ID or Meta Page Token is not configured.'
            );
        }

        $version =
            config(
                'services.meta.graph_version',
                'v24.0'
            );

        $response =
            Http::withToken($token)
            ->acceptJson()
            ->get(
                "https://graph.facebook.com/{$version}/{$instagramId}",
                [
                    'fields' =>
                    'id,username,name,profile_picture_url',
                ]
            );

        if ($response->failed()) {

            return back()->with(
                'error',
                'Instagram verification failed: ' .
                    (
                        $response->json(
                            'error.message'
                        )
                        ?? 'Unknown error.'
                    )
            );
        }

        $profile =
            $response->json();

        SocialAccount::updateOrCreate(
            [
                'user_id' => $this->sharedAccountUserId(),

                'platform' =>
                'instagram',

                'account_id' =>
                $instagramId,
            ],
            [
                'account_type' =>
                'business',

                'username' =>
                $profile['username']
                    ?? null,

                'display_name' =>
                $profile['name']
                    ?? $profile['username']
                    ?? 'Instagram Business',

                'access_token' =>
                $token,

                'refresh_token' =>
                null,

                'token_expires_at' =>
                null,

                'metadata' => [
                    'profile_picture_url' =>
                    $profile['profile_picture_url']
                        ?? null,

                    'source' =>
                    'environment',
                ],

                'is_active' =>
                true,

                'is_shared' => true,
            ]
        );

        return redirect()
            ->route(
                'social-accounts.index'
            )
            ->with(
                'success',
                'Instagram Business account connected successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | YOUTUBE CONNECT
    |--------------------------------------------------------------------------
    */

    public function youtubeConnect(): RedirectResponse
    {
        $clientId = config(
            'services.google.client_id'
        );

        $clientSecret = config(
            'services.google.client_secret'
        );

        $redirectUri = config(
            'services.google.redirect'
        );

        if (
            !$clientId ||
            !$clientSecret ||
            !$redirectUri
        ) {
            return redirect()
                ->route('social-accounts.index')
                ->with(
                    'error',
                    'Google YouTube OAuth configuration is missing.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | State
        |--------------------------------------------------------------------------
        */

        $state = bin2hex(
            random_bytes(32)
        );

        session([
            'youtube_oauth_state' => $state,
        ]);

        /*
        |--------------------------------------------------------------------------
        | OAuth scopes
        |--------------------------------------------------------------------------
        */

        $scope = implode(' ', [
            'https://www.googleapis.com/auth/youtube.readonly',
            'https://www.googleapis.com/auth/youtube.upload',
        ]);

        /*
        |--------------------------------------------------------------------------
        | OAuth URL
        |--------------------------------------------------------------------------
        */

        $query = http_build_query([
            'client_id' => $clientId,
            'redirect_uri' => $redirectUri,
            'response_type' => 'code',

            /*
        | This is what enables background/long-lived access.
        */
            'access_type' => 'offline',

            /*
        | Make Google return consent and a refresh token
        | during the initial connection/reconnection.
        */
            'prompt' => 'consent',

            'include_granted_scopes' => 'true',

            'state' => $state,

            'scope' => $scope,
        ]);

        return redirect(
            'https://accounts.google.com/o/oauth2/v2/auth?' . $query
        );
    }


    /*
    |--------------------------------------------------------------------------
    | YOUTUBE CALLBACK
    |--------------------------------------------------------------------------
    */

    public function youtubeCallback(
        Request $request
    ): RedirectResponse {

        $expectedState = $request->session()->pull(
            'youtube_oauth_state'
        );

        if (
            !$expectedState ||
            !hash_equals(
                $expectedState,
                (string) $request->query('state')
            )
        ) {
            abort(
                403,
                'Invalid YouTube OAuth state.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Google error
        |--------------------------------------------------------------------------
        */

        if ($request->query('error')) {
            return redirect()
                ->route('social-accounts.index')
                ->with(
                    'error',
                    'YouTube authorization was cancelled.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Authorization code
        |--------------------------------------------------------------------------
        */

        $code = $request->query('code');

        if (!$code) {
            return redirect()
                ->route('social-accounts.index')
                ->with(
                    'error',
                    'YouTube authorization code is missing.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Google Client
        |--------------------------------------------------------------------------
        */

        $client = new Client();

        $client->setClientId(
            config('services.google.client_id')
        );

        $client->setClientSecret(
            config('services.google.client_secret')
        );

        $client->setRedirectUri(
            config('services.google.redirect')
        );

        $client->setScopes([
            'https://www.googleapis.com/auth/youtube.readonly',
            'https://www.googleapis.com/auth/youtube.upload',
        ]);

        $client->setAccessType('offline');

        /*
        |--------------------------------------------------------------------------
        | Exchange code
        |--------------------------------------------------------------------------
        */

        $token = $client->fetchAccessTokenWithAuthCode(
            $code
        );

        if (
            isset($token['error']) ||
            empty($token['access_token'])
        ) {
            return redirect()
                ->route('social-accounts.index')
                ->with(
                    'error',
                    'YouTube token exchange failed: '
                        . json_encode($token)
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Temporary token
        |--------------------------------------------------------------------------
        */

        $client->setAccessToken($token);

        /*
        |--------------------------------------------------------------------------
        | Get YouTube channel
        |--------------------------------------------------------------------------
        */

        $youtube = new YouTube($client);

        $response = $youtube->channels->listChannels(
            'snippet,contentDetails',
            [
                'mine' => true,
            ]
        );

        $channel = $response->getItems()[0] ?? null;

        if (!$channel) {
            return redirect()
                ->route('social-accounts.index')
                ->with(
                    'error',
                    'No YouTube channel was found for this Google account.'
                );
        }

        $channelId = $channel->getId();

        $snippet = $channel->getSnippet();

        $channelTitle = $snippet
            ? $snippet->getTitle()
            : 'YouTube Channel';

        /*
        |--------------------------------------------------------------------------
        | Find existing account by REAL YouTube channel ID
        |--------------------------------------------------------------------------
        */

        $sharedUserId = $this->sharedAccountUserId();

        $existingAccount = SocialAccount::query()
            ->where('user_id', $sharedUserId)
            ->where('platform', 'youtube')
            ->where('account_id', $channelId)
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Access token
        |--------------------------------------------------------------------------
        */

        $accessToken = $token['access_token'];

        /*
        |--------------------------------------------------------------------------
        | Refresh token
        |--------------------------------------------------------------------------
        |
        | Google may not return refresh_token when the account
        | has already granted permission.
        |
        | Preserve the existing refresh token.
        |
        */

        $refreshToken =
            $token['refresh_token']
            ?? $existingAccount?->refresh_token;

        if (!$refreshToken) {
            return redirect()
                ->route('social-accounts.index')
                ->with(
                    'error',
                    'YouTube did not provide a refresh token. Please reconnect YouTube with offline access.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Expiration
        |--------------------------------------------------------------------------
        */

        $expiresAt = !empty($token['expires_in'])
            ? now()->addSeconds(
                (int) $token['expires_in']
            )
            : null;

        /*
        |--------------------------------------------------------------------------
        | Save account
        |--------------------------------------------------------------------------
        */

        SocialAccount::updateOrCreate(
            [
                'user_id' => $sharedUserId,
                'platform' => 'youtube',
                'account_id' => $channelId,
            ],
            [
                'account_type' => 'channel',

                'username' => $channelTitle,

                'display_name' => $channelTitle,

                'access_token' => $accessToken,

                /*
            | IMPORTANT:
            | Keep refresh token.
            */
                'refresh_token' => $refreshToken,

                'token_expires_at' => $expiresAt,

                'metadata' => [
                    'channel_id' => $channelId,
                    'channel_title' => $channelTitle,
                    'source' => 'google-oauth',
                ],

                'is_active' => true,

                'is_shared' => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | Done
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route('social-accounts.index')
            ->with(
                'success',
                'YouTube connected successfully. Automatic token refresh is enabled.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | TIKTOK CONNECT
    |--------------------------------------------------------------------------
    */

    public function tiktokConnect(): RedirectResponse
    {
        $clientKey =
            config(
                'services.tiktok.client_key'
            );

        $redirectUri =
            config(
                'services.tiktok.redirect'
            );

        if (! $clientKey || ! $redirectUri) {

            return redirect()
                ->route(
                    'social-accounts.index'
                )
                ->with(
                    'error',
                    'TikTok OAuth configuration is missing.'
                );
        }

        $state =
            bin2hex(
                random_bytes(32)
            );

        session([
            'tiktok_oauth_state' =>
            $state,
        ]);

        $query =
            http_build_query([
                'client_key' =>
                $clientKey,

                'response_type' =>
                'code',

                'scope' =>
                'user.info.basic,video.upload,video.publish',

                'redirect_uri' =>
                $redirectUri,

                'state' =>
                $state,
            ]);

        return redirect(
            'https://www.tiktok.com/v2/auth/authorize/?' .
                $query
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TIKTOK CALLBACK
    |--------------------------------------------------------------------------
    */

    public function tiktokCallback(
        Request $request
    ): RedirectResponse {

        if (
            ! $this->verifyState(
                $request->state,
                session(
                    'tiktok_oauth_state'
                )
            )
        ) {

            session()->forget(
                'tiktok_oauth_state'
            );

            return redirect()
                ->route(
                    'social-accounts.index'
                )
                ->with(
                    'error',
                    'Invalid TikTok OAuth state.'
                );
        }

        if ($request->filled('error')) {

            $message =
                $request->input(
                    'error_description'
                )
                ?? $request->input('error')
                ?? 'TikTok authorization failed.';

            session()->forget(
                'tiktok_oauth_state'
            );

            return redirect()
                ->route(
                    'social-accounts.index'
                )
                ->with(
                    'error',
                    'TikTok authorization failed: ' .
                        $message
                );
        }

        if (! $request->filled('code')) {

            return redirect()
                ->route(
                    'social-accounts.index'
                )
                ->with(
                    'error',
                    'TikTok authorization was not completed.'
                );
        }

        $tokenResponse =
            Http::asForm()
            ->acceptJson()
            ->post(
                'https://open.tiktokapis.com/v2/oauth/token/',
                [
                    'client_key' =>
                    config(
                        'services.tiktok.client_key'
                    ),

                    'client_secret' =>
                    config(
                        'services.tiktok.client_secret'
                    ),

                    'code' =>
                    $request->code,

                    'grant_type' =>
                    'authorization_code',

                    'redirect_uri' =>
                    config(
                        'services.tiktok.redirect'
                    ),
                ]
            );

        if (
            $tokenResponse->failed() ||
            ! $tokenResponse->json(
                'access_token'
            )
        ) {

            report(
                new RuntimeException(
                    'TikTok token exchange failed: ' .
                        $tokenResponse->body()
                )
            );

            return redirect()
                ->route(
                    'social-accounts.index'
                )
                ->with(
                    'error',
                    'TikTok token exchange failed.'
                );
        }

        $accessToken =
            $tokenResponse->json(
                'access_token'
            );

        $refreshToken =
            $tokenResponse->json(
                'refresh_token'
            );

        $openId =
            $tokenResponse->json(
                'open_id'
            );

        $expiresIn =
            $tokenResponse->json(
                'expires_in'
            );

        if (! $openId) {

            return redirect()
                ->route(
                    'social-accounts.index'
                )
                ->with(
                    'error',
                    'TikTok did not return an Open ID.'
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Creator information
        |--------------------------------------------------------------------------
        */

        $creatorResponse =
            Http::withToken(
                $accessToken
            )
            ->acceptJson()
            ->post(
                'https://open.tiktokapis.com/v2/post/publish/creator_info/query/',
                []
            );

        $creatorData =
            $creatorResponse->json(
                'data'
            );

        SocialAccount::updateOrCreate(
            [
                'user_id' => $this->sharedAccountUserId(),

                'platform' =>
                'tiktok',

                'account_id' =>
                $openId,
            ],
            [
                'account_type' =>
                'account',

                'username' =>
                data_get(
                    $creatorData,
                    'creator_username'
                ),

                'display_name' =>
                data_get(
                    $creatorData,
                    'creator_nickname'
                )
                    ?? 'TikTok Account',

                'access_token' =>
                $accessToken,

                'refresh_token' =>
                $refreshToken,

                'token_expires_at' =>
                $expiresIn
                    ? now()->addSeconds(
                        (int) $expiresIn
                    )
                    : null,

                'metadata' => [
                    'open_id' =>
                    $openId,

                    'scope' =>
                    $tokenResponse->json(
                        'scope'
                    ),

                    'refresh_expires_in' =>
                    $tokenResponse->json(
                        'refresh_expires_in'
                    ),

                    'creator_info' =>
                    $creatorData,

                    'source' =>
                    'oauth',
                ],

                'is_active' =>
                true,

                'is_shared' => true,
            ]
        );

        session()->forget(
            'tiktok_oauth_state'
        );

        return redirect()
            ->route(
                'social-accounts.index'
            )
            ->with(
                'success',
                'TikTok account connected successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | LINKEDIN CONNECT
    |--------------------------------------------------------------------------
    */

    public function linkedinConnect(): RedirectResponse
    {
        $this->ensureSharedAccountManager();

        $state = bin2hex(
            random_bytes(32)
        );

        session([
            'linkedin_oauth_state' => $state,
        ]);

        $query = http_build_query([
            'response_type' => 'code',

            'client_id' => config(
                'services.linkedin.client_id'
            ),

            'redirect_uri' => config(
                'services.linkedin.redirect'
            ),

            'state' => $state,

            /*
        |--------------------------------------------------------------------------
        | Organization publishing permission
        |--------------------------------------------------------------------------
        |
        | IMPORTANT:
        | This must be available to your LinkedIn application.
        |
        */
            'scope' => 'w_organization_social',
        ]);

        return redirect()->away(
            'https://www.linkedin.com/oauth/v2/authorization?' .
                $query
        );
    }


    /*
    |--------------------------------------------------------------------------
    | LINKEDIN CALLBACK
    |--------------------------------------------------------------------------
    */

    public function linkedinCallback(
        Request $request
    ): RedirectResponse {

        if (
            ! $this->verifyState(
                $request->state,
                session(
                    'linkedin_oauth_state'
                )
            )
        ) {

            session()->forget(
                'linkedin_oauth_state'
            );

            return redirect()
                ->route(
                    'social-accounts.index'
                )
                ->with(
                    'error',
                    'Invalid LinkedIn OAuth state.'
                );
        }

        if ($request->filled('error')) {

            return redirect()
                ->route(
                    'social-accounts.index'
                )
                ->with(
                    'error',
                    'LinkedIn authorization failed: ' .
                        (
                            $request->input(
                                'error_description'
                            )
                            ?? $request->input('error')
                        )
                );
        }

        if (! $request->filled('code')) {

            return redirect()
                ->route(
                    'social-accounts.index'
                )
                ->with(
                    'error',
                    'LinkedIn authorization was not completed.'
                );
        }

        $tokenResponse =
            Http::asForm()
            ->acceptJson()
            ->post(
                'https://www.linkedin.com/oauth/v2/accessToken',
                [
                    'grant_type' =>
                    'authorization_code',

                    'code' =>
                    $request->code,

                    'redirect_uri' =>
                    config(
                        'services.linkedin.redirect'
                    ),

                    'client_id' =>
                    config(
                        'services.linkedin.client_id'
                    ),

                    'client_secret' =>
                    config(
                        'services.linkedin.client_secret'
                    ),
                ]
            );

        if (
            $tokenResponse->failed() ||
            ! $tokenResponse->json(
                'access_token'
            )
        ) {

            report(
                new RuntimeException(
                    'LinkedIn token exchange failed: ' .
                        $tokenResponse->body()
                )
            );

            return redirect()
                ->route(
                    'social-accounts.index'
                )
                ->with(
                    'error',
                    'LinkedIn token exchange failed.'
                );
        }

        $accessToken =
            $tokenResponse->json(
                'access_token'
            );

        $profileResponse =
            Http::withToken(
                $accessToken
            )
            ->acceptJson()
            ->get(
                'https://api.linkedin.com/v2/userinfo'
            );

        $profile =
            $profileResponse->json();

        if (! is_array($profile)) {
            $profile = [];
        }

        $organizationId =
            config(
                'services.linkedin.organization_id'
            );

        if (! $organizationId) {

            return redirect()
                ->route(
                    'social-accounts.index'
                )
                ->with(
                    'error',
                    'LinkedIn organization ID is not configured.'
                );
        }

        SocialAccount::updateOrCreate(
            [
                'user_id' => $this->sharedAccountUserId(),

                'platform' =>
                'linkedin',

                'account_id' =>
                $organizationId,
            ],
            [
                'account_type' =>
                'organization',

                'username' =>
                $profile['email']
                    ?? null,

                'display_name' =>
                'The NGO Forum on Cambodia',

                'access_token' =>
                $accessToken,

                'refresh_token' =>
                $tokenResponse->json(
                    'refresh_token'
                ),

                'token_expires_at' =>
                $tokenResponse->json(
                    'expires_in'
                )
                    ? now()->addSeconds(
                        (int) $tokenResponse->json(
                            'expires_in'
                        )
                    )
                    : null,

                'metadata' => [
                    'organization_id' =>
                    $organizationId,

                    'member_id' =>
                    $profile['sub']
                        ?? null,

                    'email' =>
                    $profile['email']
                        ?? null,

                    'source' =>
                    'oauth',
                ],

                'is_active' =>
                true,

                'is_shared' => true,
            ]
        );

        session()->forget(
            'linkedin_oauth_state'
        );

        return redirect()
            ->route(
                'social-accounts.index'
            )
            ->with(
                'success',
                'LinkedIn organization connected successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | X CONNECT
    |--------------------------------------------------------------------------
    */

    public function twitterConnect(): RedirectResponse
    {
        $clientId =
            config(
                'services.twitter.client_id'
            );

        $redirectUri =
            config(
                'services.twitter.redirect'
            );

        if (! $clientId || ! $redirectUri) {

            return redirect()
                ->route(
                    'social-accounts.index'
                )
                ->with(
                    'error',
                    'X OAuth configuration is missing.'
                );
        }

        $state =
            bin2hex(
                random_bytes(32)
            );

        $verifier =
            $this->base64Url(
                random_bytes(48)
            );

        session([
            'twitter_oauth_state' =>
            $state,

            'twitter_code_verifier' =>
            $verifier,
        ]);

        $challenge =
            $this->base64Url(
                hash(
                    'sha256',
                    $verifier,
                    true
                )
            );

        $query =
            http_build_query([
                'response_type' =>
                'code',

                'client_id' =>
                $clientId,

                'redirect_uri' =>
                $redirectUri,

                'scope' =>
                'tweet.read tweet.write users.read offline.access',

                'state' =>
                $state,

                'code_challenge' =>
                $challenge,

                'code_challenge_method' =>
                'S256',
            ]);

        return redirect(
            'https://twitter.com/i/oauth2/authorize?' .
                $query
        );
    }


    /*
    |--------------------------------------------------------------------------
    | X CALLBACK
    |--------------------------------------------------------------------------
    */

    public function twitterCallback(
        Request $request
    ): RedirectResponse {

        if (
            ! $this->verifyState(
                $request->state,
                session(
                    'twitter_oauth_state'
                )
            )
        ) {

            session()->forget([
                'twitter_oauth_state',
                'twitter_code_verifier',
            ]);

            return redirect()
                ->route(
                    'social-accounts.index'
                )
                ->with(
                    'error',
                    'Invalid X OAuth state.'
                );
        }

        $verifier =
            session(
                'twitter_code_verifier'
            );

        if (
            ! $verifier ||
            ! $request->filled('code')
        ) {

            return redirect()
                ->route(
                    'social-accounts.index'
                )
                ->with(
                    'error',
                    'X authorization was not completed.'
                );
        }

        $tokenResponse =
            Http::asForm()
            ->acceptJson()
            ->post(
                'https://api.x.com/2/oauth2/token',
                [
                    'code' =>
                    $request->code,

                    'grant_type' =>
                    'authorization_code',

                    'client_id' =>
                    config(
                        'services.twitter.client_id'
                    ),

                    'redirect_uri' =>
                    config(
                        'services.twitter.redirect'
                    ),

                    'code_verifier' =>
                    $verifier,
                ]
            );

        if (
            $tokenResponse->failed() ||
            ! $tokenResponse->json(
                'access_token'
            )
        ) {

            return redirect()
                ->route(
                    'social-accounts.index'
                )
                ->with(
                    'error',
                    'X token exchange failed.'
                );
        }

        $accessToken =
            $tokenResponse->json(
                'access_token'
            );

        $profileResponse =
            Http::withToken(
                $accessToken
            )
            ->acceptJson()
            ->get(
                'https://api.x.com/2/users/me',
                [
                    'user.fields' =>
                    'id,name,username,profile_image_url',
                ]
            );

        if ($profileResponse->failed()) {

            return redirect()
                ->route(
                    'social-accounts.index'
                )
                ->with(
                    'error',
                    'Unable to retrieve X account information.'
                );
        }

        $profile =
            $profileResponse->json(
                'data',
                []
            );

        if (empty($profile['id'])) {

            return redirect()
                ->route(
                    'social-accounts.index'
                )
                ->with(
                    'error',
                    'X account ID was not returned.'
                );
        }

        SocialAccount::updateOrCreate(
            [
                'user_id' => $this->sharedAccountUserId(),

                'platform' =>
                'twitter',

                'account_id' =>
                $profile['id'],
            ],
            [
                'account_type' =>
                'account',

                'username' =>
                $profile['username']
                    ?? null,

                'display_name' =>
                $profile['name']
                    ?? 'X Account',

                'access_token' =>
                $accessToken,

                'refresh_token' =>
                $tokenResponse->json(
                    'refresh_token'
                ),

                'token_expires_at' =>
                $tokenResponse->json(
                    'expires_in'
                )
                    ? now()->addSeconds(
                        (int) $tokenResponse->json(
                            'expires_in'
                        )
                    )
                    : null,

                'metadata' => [
                    'username' =>
                    $profile['username']
                        ?? null,

                    'profile_image_url' =>
                    $profile['profile_image_url']
                        ?? null,

                    'source' =>
                    'oauth',
                ],

                'is_active' =>
                true,

                'is_shared' => true,
            ]
        );

        session()->forget([
            'twitter_oauth_state',
            'twitter_code_verifier',
        ]);

        return redirect()
            ->route(
                'social-accounts.index'
            )
            ->with(
                'success',
                'X account connected successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    public function edit(
        SocialAccount $socialAccount
    ): View {

        $this->ensureOwner(
            $socialAccount
        );

        return view(
            'social-accounts.edit',
            compact('socialAccount')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        SocialAccount $socialAccount
    ): RedirectResponse {

        $this->ensureOwner(
            $socialAccount
        );

        $request->validate([
            'is_active' =>
            ['nullable', 'boolean'],
        ]);

        $socialAccount->update([
            'is_active' =>
            $request->boolean(
                'is_active'
            ),
        ]);

        return redirect()
            ->route(
                'social-accounts.index'
            )
            ->with(
                'success',
                'Media account updated successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | TOGGLE
    |--------------------------------------------------------------------------
    */

    public function toggle(
        SocialAccount $socialAccount
    ): RedirectResponse {

        $this->ensureOwner(
            $socialAccount
        );

        $socialAccount->update([
            'is_active' =>
            ! $socialAccount->is_active,
        ]);

        return back()->with(
            'success',
            'Account status updated.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    public function destroy(
        SocialAccount $socialAccount
    ): RedirectResponse {

        $this->ensureOwner(
            $socialAccount
        );

        $socialAccount->delete();

        return redirect()
            ->route(
                'social-accounts.index'
            )
            ->with(
                'success',
                'Media account disconnected successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    protected function ensureOwner(
        SocialAccount $socialAccount
    ): void {
        abort_unless(
            (int) $socialAccount->user_id ===
                $this->sharedAccountUserId(),
            403
        );
    }


    protected function verifyState(
        ?string $state,
        ?string $expected
    ): bool {

        if (
            ! $state ||
            ! $expected
        ) {
            return false;
        }

        return hash_equals(
            $expected,
            $state
        );
    }


    protected function base64Url(
        string $value
    ): string {

        return rtrim(
            strtr(
                base64_encode($value),
                '+/',
                '-_'
            ),
            '='
        );
    }
}
