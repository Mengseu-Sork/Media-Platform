<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SocialAccountController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

Route::middleware('auth')->group(function () {

    Route::get(
        '/dashboard',
        [DashboardController::class, 'index']
    )->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Posts
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/posts',
        [PostController::class, 'index']
    )->name('posts.index');

    Route::get(
        '/posts/create',
        [PostController::class, 'create']
    )->name('posts.create');

    Route::post(
        '/posts',
        [PostController::class, 'store']
    )->name('posts.store');

    Route::get(
        '/posts/{post}',
        [PostController::class, 'show']
    )->name('posts.show');

    Route::get(
        '/posts/media/{media}',
        [PostController::class, 'media']
    )->name('posts.media');

    Route::post(
        '/posts/{post}/publish',
        [PostController::class, 'publish']
    )->name('posts.publish');

    Route::get(
        '/posts/export/excel',
        [PostController::class, 'exportExcel']
    )->name('posts.export.excel');

    Route::delete(
        '/posts/{post}',
        [PostController::class, 'destroy']
    )->name('posts.destroy');

    /*
    |--------------------------------------------------------------------------
    | Media Accounts
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/social-accounts',
        [SocialAccountController::class, 'index']
    )->name('social-accounts.index');

    Route::get(
        '/social-accounts/create',
        [SocialAccountController::class, 'create']
    )->name('social-accounts.create');

    Route::post(
        '/social-accounts',
        [SocialAccountController::class, 'store']
    )->name('social-accounts.store');

    Route::get(
        '/social-accounts/{socialAccount}/edit',
        [SocialAccountController::class, 'edit']
    )->name('social-accounts.edit');

    Route::patch(
        '/social-accounts/{socialAccount}',
        [SocialAccountController::class, 'update']
    )->name('social-accounts.update');

    Route::patch(
        '/social-accounts/{socialAccount}/toggle',
        [SocialAccountController::class, 'toggle']
    )->name('social-accounts.toggle');

    Route::delete(
        '/social-accounts/{socialAccount}',
        [SocialAccountController::class, 'destroy']
    )->name('social-accounts.destroy');

    /*
    |--------------------------------------------------------------------------
    | OAuth
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/youtube/connect',
        [SocialAccountController::class, 'youtubeConnect']
    )->name('social-accounts.youtube.connect');

    Route::get(
        '/youtube/callback',
        [SocialAccountController::class, 'youtubeCallback']
    )->name('social-accounts.youtube.callback');

    Route::get(
        '/tiktok/connect',
        [SocialAccountController::class, 'tiktokConnect']
    )->name('social-accounts.tiktok.connect');

    Route::get(
        '/tiktok/callback',
        [SocialAccountController::class, 'tiktokCallback']
    )->name('social-accounts.tiktok.callback');

    Route::get(
        '/linkedin/connect',
        [SocialAccountController::class, 'linkedinConnect']
    )->name('social-accounts.linkedin.connect');

    Route::get(
        '/linkedin/callback',
        [SocialAccountController::class, 'linkedinCallback']
    )->name('social-accounts.linkedin.callback');

    Route::get(
        '/twitter/connect',
        [SocialAccountController::class, 'twitterConnect']
    )->name('social-accounts.twitter.connect');

    Route::get(
        '/twitter/callback',
        [SocialAccountController::class, 'twitterCallback']
    )->name('social-accounts.twitter.callback');

    /*
    |--------------------------------------------------------------------------
    | Existing user CRUD
    |--------------------------------------------------------------------------
    |
    | Keep this only if your UserController is intentionally available
    | to all authenticated users. For admin-only user management, move
    | these routes into an admin middleware group.
    |
    */

    Route::resource('users', UserController::class);
});

require __DIR__ . '/auth.php';
