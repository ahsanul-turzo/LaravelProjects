<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class ShareController extends Controller
{
    public function store(Post $post)
    {
        $user = auth()->user();

        if ($post->isSharedBy($user)) {
            // Unshare
            $post->shares()->where('user_id', $user->id)->delete();
            $post->decrementShares();
            $shared = false;
        } else {
            // Share
            $post->shares()->create(['user_id' => $user->id]);
            $post->incrementShares();
            $shared = true;
        }

        if (request()->wantsJson()) {
            return response()->json([
                'shared' => $shared,
                'shares_count' => $post->fresh()->shares_count,
            ]);
        }

        return redirect()->back()->with('success', $shared ? 'Post shared!' : 'Share removed!');
    }
}
