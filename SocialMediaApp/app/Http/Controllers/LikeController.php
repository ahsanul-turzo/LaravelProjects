<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function togglePostLike(Post $post)
    {
        $user = auth()->user();

        if ($post->isLikedBy($user)) {
            // Unlike
            $post->likes()->where('user_id', $user->id)->delete();
            $post->decrementLikes();
            $liked = false;
        } else {
            // Like
            $post->likes()->create(['user_id' => $user->id]);
            $post->incrementLikes();
            $liked = true;
        }

        if (request()->wantsJson()) {
            return response()->json([
                'liked' => $liked,
                'likes_count' => $post->fresh()->likes_count,
            ]);
        }

        return redirect()->back();
    }

    public function toggleCommentLike(Comment $comment)
    {
        $user = auth()->user();

        if ($comment->isLikedBy($user)) {
            // Unlike
            $comment->likes()->where('user_id', $user->id)->delete();
            $comment->decrementLikes();
            $liked = false;
        } else {
            // Like
            $comment->likes()->create(['user_id' => $user->id]);
            $comment->incrementLikes();
            $liked = true;
        }

        if (request()->wantsJson()) {
            return response()->json([
                'liked' => $liked,
                'likes_count' => $comment->fresh()->likes_count,
            ]);
        }

        return redirect()->back();
    }
}
