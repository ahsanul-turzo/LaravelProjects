<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class FollowController extends Controller
{
    public function toggle(User $user)
    {
        $currentUser = auth()->user();

        if ($currentUser->id === $user->id) {
            return redirect()->back()->with('error', 'You cannot follow yourself!');
        }

        if ($currentUser->isFollowing($user)) {
            $currentUser->unfollow($user);
            $following = false;
            $message = "Unfollowed {$user->name}";
        } else {
            $currentUser->follow($user);
            $following = true;
            $message = "Following {$user->name}";
        }

        if (request()->wantsJson()) {
            return response()->json([
                'following' => $following,
                'followers_count' => $user->followers()->count(),
            ]);
        }

        return redirect()->back()->with('success', $message);
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
