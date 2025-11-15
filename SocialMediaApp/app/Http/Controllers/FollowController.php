<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function store(User $user)
    {
        $currentUser = auth()->user();

        if ($currentUser->id === $user->id) {
            return redirect()->back()->with('error', 'You cannot follow yourself!');
        }

        if (!$currentUser->isFollowing($user)) {
            $currentUser->follow($user);
        }

        if (request()->wantsJson()) {
            return response()->json([
                'following' => true,
                'followers_count' => $user->followers()->count(),
            ]);
        }

        return redirect()->back()->with('success', "Following {$user->name}");
    }

    public function destroy(User $user)
    {
        $currentUser = auth()->user();

        if ($currentUser->isFollowing($user)) {
            $currentUser->unfollow($user);
        }

        if (request()->wantsJson()) {
            return response()->json([
                'following' => false,
                'followers_count' => $user->followers()->count(),
            ]);
        }

        return redirect()->back()->with('success', "Unfollowed {$user->name}");
    }
}
