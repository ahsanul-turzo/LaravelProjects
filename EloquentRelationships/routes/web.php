<?php

use App\Models\Post;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-one-to-one', function () {

    // Get user with profile
    $user = User::with('profile')->find(1);
    return view('test.test-one-to-one', ['user' => $user]);
})->name('test-one-to-one');

// Test One-to-Many relationship
Route::get('/test-one-to-many', function () {
    // Get user with all their posts
    $user = User::with('posts')->find(1);
    // Get a post with its author (reverse)
    $post = Post::with('user')->find(1);
    $status = $post->published ? '✅ Published' : '❌ Draft';

    return view('test.test-one-to-many', ['user' => $user, 'posts' => $post, 'status' => $status]);
})->name('test-one-to-many');

Route::get('/test-comments', function () {
    $post = Post::with(['comments.user', 'user'])->find(1);

    return view('test.test-comments', ['post' => $post]);
})->name('test-comments');

Route::get('/test-many-to-many', function () {

    // Get post with tags
    $post = Post::with('tags')->find(1);
    // Get all posts with a specific tag
    $postTag = \App\Models\Tag::with('posts')->where('slug', 'laravel')->first();

    return view('test.test-many-to-many', ['post' => $post, 'postTag' => $postTag]);
})->name('test-many-to-many');

Route::get('/dashboard', function () {
    $users = \App\Models\User::with(['profile', 'posts.tags', 'posts.comments'])->get();

    return view('test.dashboard', ['users' => $users]);

})->name('dashboard');
