<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['user', 'comments.user', 'likes', 'shares'])
            ->where('is_published', true)
            ->latest()
            ->paginate(20);

        return view('posts.index', compact('posts'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string|max:5000',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,mov|max:20480',
        ]);

        $post = auth()->user()->posts()->create([
            'content' => $validated['content'],
            'is_published' => true,
        ]);

        // Handle media uploads
        if ($request->hasFile('media')) {
            $mediaUrls = [];
            foreach ($request->file('media') as $file) {
                $path = $file->store('posts', 'public');
                $mediaUrls[] = Storage::url($path);
            }
            $post->update(['media' => $mediaUrls]);
        }

        return redirect()->back()->with('success', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        $post->load(['user', 'comments.user', 'comments.replies.user', 'likes', 'shares']);

        return view('posts.show', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'content' => 'required|string|max:5000',
        ]);

        $post->update($validated);

        return redirect()->back()->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        $this->authorize('delete', $post);

        // Delete media files
        if ($post->media) {
            foreach ($post->media as $mediaUrl) {
                $path = str_replace('/storage/', '', $mediaUrl);
                Storage::disk('public')->delete($path);
            }
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
    }

    public function feed()
    {
        $user = auth()->user();

        // Get posts from users the current user follows + own posts
        $posts = Post::with(['user', 'comments.user', 'likes', 'shares'])
            ->where('is_published', true)
            ->where(function($query) use ($user) {
                $query->where('user_id', $user->id)
                    ->orWhereIn('user_id', $user->following()->pluck('following_id'));
            })
            ->latest()
            ->paginate(20);

        return view('feed', compact('posts'));
    }
}
