<?php

use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\ShareController;
use App\Http\Controllers\FollowController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SubscriptionController;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// OAuth Routes
Route::get('auth/google', [SocialAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('auth/google/callback', [SocialAuthController::class, 'handleGoogleCallback']);
Route::get('auth/apple', [SocialAuthController::class, 'redirectToApple'])->name('auth.apple');
Route::get('auth/apple/callback', [SocialAuthController::class, 'handleAppleCallback']);

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - Redirect to feed
    Route::get('dashboard', function() {
        return redirect()->route('feed');
    })->name('dashboard');

    // Feed (Home Timeline)
    Route::get('feed', [PostController::class, 'feed'])->name('feed');

    // Posts
    Route::get('posts', [PostController::class, 'index'])->name('posts.index');
    Route::post('posts', [PostController::class, 'store'])->name('posts.store');
    Route::get('posts/{post}', [PostController::class, 'show'])->name('posts.show');
    Route::put('posts/{post}', [PostController::class, 'update'])->name('posts.update');
    Route::delete('posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Comments
    Route::post('posts/{post}/comments', [CommentController::class, 'store'])->name('comments.store');
    Route::put('comments/{comment}', [CommentController::class, 'update'])->name('comments.update');
    Route::delete('comments/{comment}', [CommentController::class, 'destroy'])->name('comments.destroy');

    // Likes
    Route::post('posts/{post}/like', [LikeController::class, 'togglePostLike'])->name('posts.like');
    Route::post('comments/{comment}/like', [LikeController::class, 'toggleCommentLike'])->name('comments.like');

    // Shares
    Route::post('posts/{post}/share', [ShareController::class, 'store'])->name('posts.share');

    // Follow
    Route::post('users/{user}/follow', [FollowController::class, 'toggle'])->name('users.follow');
    Route::get('users/{user}/followers', [FollowController::class, 'followers'])->name('users.followers');
    Route::get('users/{user}/following', [FollowController::class, 'following'])->name('users.following');

    // Messages
    Route::get('messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('messages/{user}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('messages/{receiver}', [MessageController::class, 'store'])->name('messages.store');
    Route::post('messages/{message}/read', [MessageController::class, 'markAsRead'])->name('messages.read');
    Route::get('messages/unread/count', [MessageController::class, 'unreadCount'])->name('messages.unread');

    // Subscriptions
    Route::get('subscriptions', [SubscriptionController::class, 'index'])->name('subscriptions.index');
    Route::post('subscriptions/checkout', [SubscriptionController::class, 'checkout'])->name('subscriptions.checkout');
    Route::get('subscriptions/success', [SubscriptionController::class, 'success'])->name('subscriptions.success');
    Route::delete('subscriptions/{subscription}/cancel', [SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');

    // Settings
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});

// Stripe Webhook (outside auth middleware)
Route::post('webhook/stripe', [SubscriptionController::class, 'webhook'])->name('webhook.stripe');

require __DIR__.'/auth.php';
