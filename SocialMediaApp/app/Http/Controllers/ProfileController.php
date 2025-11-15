<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        // Get user's posts with eager loading
        $posts = $user->posts()
            ->with(['user', 'likes'])
            ->where('is_published', true)
            ->latest()
            ->paginate(10);

        return view('profile.show', compact('user', 'posts'));
    }

    public function followers(User $user)
    {
        $followers = $user->followers()->paginate(20);

        return view('profile.followers', compact('user', 'followers'));
    }

    public function following(User $user)
    {
        $following = $user->following()->paginate(20);

        return view('profile.following', compact('user', 'following'));
    }
}
