<x-layouts.app>
    <div class="max-w-4xl mx-auto px-4 py-6">
        {{-- Create Post --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
            <form action="{{ route('posts.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <textarea
                    name="content"
                    rows="3"
                    class="w-full rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="What's on your mind?"
                    required
                ></textarea>

                <div class="mt-4 flex items-center justify-between">
                    <div>
                        <label class="cursor-pointer inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Photo/Video
                            <input type="file" name="media[]" multiple accept="image/*,video/*" class="hidden">
                        </label>
                    </div>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Post
                    </button>
                </div>
            </form>
        </div>

        {{-- Posts Feed --}}
        @forelse($posts as $post)
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
                {{-- Post Header --}}
                <div class="p-4 flex items-center justify-between">
                    <div class="flex items-center">
                        <img src="{{ $post->user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($post->user->name) }}"
                             alt="{{ $post->user->name }}"
                             class="w-10 h-10 rounded-full">
                        <div class="ml-3">
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
                </div>

                {{-- Post Content --}}
                <div class="px-4 pb-4">
                    <p class="text-gray-900 dark:text-gray-100 whitespace-pre-wrap">{{ $post->content }}</p>

                    @if($post->media)
                        <div class="mt-4 grid grid-cols-2 gap-2">
                            @foreach($post->media as $mediaUrl)
                                <img src="{{ $mediaUrl }}" alt="Post media" class="rounded-lg w-full">
                            @endforeach
                        </div>
                    @endif
                </div>

                {{-- Post Actions --}}
                <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-around text-gray-500 dark:text-gray-400">
                        <form action="{{ route('posts.like', $post) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center space-x-2 hover:text-red-500 transition">
                                <svg class="w-5 h-5" fill="{{ $post->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                                </svg>
                                <span>{{ $post->likes_count }}</span>
                            </button>
                        </form>

                        <a href="{{ route('posts.show', $post) }}" class="flex items-center space-x-2 hover:text-blue-500 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                            <span>{{ $post->comments_count }}</span>
                        </a>

                        <form action="{{ route('posts.share', $post) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" class="flex items-center space-x-2 hover:text-green-500 transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path>
                                </svg>
                                <span>{{ $post->shares_count }}</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-12 text-center">
                <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path>
                </svg>
                <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-gray-100">No posts yet</h3>
                <p class="mt-2 text-gray-500 dark:text-gray-400">Follow users to see their posts in your feed!</p>
            </div>
        @endforelse

        {{-- Pagination --}}
        <div class="mt-6">
            {{ $posts->links() }}
        </div>
    </div>
</x-layouts.app>
