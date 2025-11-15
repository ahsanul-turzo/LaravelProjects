<x-layouts.app>
    <div class="max-w-4xl mx-auto px-4 py-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            {{-- Post Header --}}
            <div class="p-6 flex items-center justify-between border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <img src="{{ $post->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($post->user->name) }}"
                         alt="{{ $post->user->name }}"
                         class="w-12 h-12 rounded-full">
                    <div class="ml-4">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                            {{ $post->user->name }}
                            @if($post->user->hasVerifiedBadge())
                                <svg class="w-5 h-5 ml-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            @elseif($post->user->hasPurpleBadge())
                                <svg class="w-5 h-5 ml-1 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                        </h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">{{ $post->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                @can('delete', $post)
                    <form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                        </button>
                    </form>
                @endcan
            </div>

            {{-- Post Content --}}
            <div class="p-6">
                <p class="text-gray-900 dark:text-gray-100 text-lg whitespace-pre-wrap">{{ $post->content }}</p>

                @if($post->media)
                    <div class="mt-6 grid grid-cols-2 gap-2">
                        @foreach($post->media as $mediaUrl)
                            <img src="{{ $mediaUrl }}" alt="Post media" class="rounded-lg w-full">
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- Post Stats & Actions --}}
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-around text-gray-500 dark:text-gray-400">
                    <form action="{{ route('posts.like', $post) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <svg class="w-6 h-6" fill="{{ $post->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                            <span class="font-medium">{{ $post->likes_count }} Likes</span>
                        </button>
                    </form>

                    <form action="{{ route('posts.share', $post) }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center space-x-2 px-4 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                            </svg>
                            <span class="font-medium">{{ $post->shares_count }} Shares</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Comments Section --}}
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                <h3 class="font-semibold text-gray-900 dark:text-gray-100 mb-4">Comments ({{ $post->comments_count }})</h3>

                {{-- Add Comment --}}
                <form action="{{ route('comments.store', $post) }}" method="POST" class="mb-6">
                    @csrf
                    <div class="flex space-x-3">
                        <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode(auth()->user()->name) }}"
                             alt="{{ auth()->user()->name }}"
                             class="w-10 h-10 rounded-full">
                        <div class="flex-1">
                            <input type="text"
                                   name="content"
                                   placeholder="Write a comment..."
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                   required>
                        </div>
                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                            Post
                        </button>
                    </div>
                </form>

                {{-- Comments List --}}
                @foreach($post->comments->where('parent_id', null) as $comment)
                    <div class="mb-4">
                        <div class="flex space-x-3">
                            <img src="{{ $comment->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($comment->user->name) }}"
                                 alt="{{ $comment->user->name }}"
                                 class="w-10 h-10 rounded-full">
                            <div class="flex-1">
                                <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3">
                                    <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $comment->user->name }}</h4>
                                    <p class="text-gray-700 dark:text-gray-300">{{ $comment->content }}</p>
                                </div>
                                <div class="mt-2 flex items-center space-x-4 text-sm text-gray-500 dark:text-gray-400">
                                    <form action="{{ route('comments.like', $comment) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="hover:text-red-500">
                                            Like ({{ $comment->likes_count }})
                                        </button>
                                    </form>
                                    <span>{{ $comment->created_at->diffForHumans() }}</span>
                                </div>

                                {{-- Replies --}}
                                @foreach($comment->replies as $reply)
                                    <div class="mt-3 ml-8 flex space-x-3">
                                        <img src="{{ $reply->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($reply->user->name) }}"
                                             alt="{{ $reply->user->name }}"
                                             class="w-8 h-8 rounded-full">
                                        <div class="flex-1">
                                            <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-3">
                                                <h4 class="font-semibold text-sm text-gray-900 dark:text-gray-100">{{ $reply->user->name }}</h4>
                                                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $reply->content }}</p>
                                            </div>
                                            <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500 dark:text-gray-400">
                                                <form action="{{ route('comments.like', $reply) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="hover:text-red-500">
                                                        Like ({{ $reply->likes_count }})
                                                    </button>
                                                </form>
                                                <span>{{ $reply->created_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('feed') }}" class="text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300">
                ← Back to Feed
            </a>
        </div>
    </div>
</x-layouts.app>
