<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-6">
        {{-- Profile Header --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
            <div class="flex items-center">
                <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}"
                     alt="{{ $user->name }}"
                     class="w-20 h-20 rounded-full">
                <div class="ml-6">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 flex items-center">
                        {{ $user->name }}
                        @if($user->hasVerifiedBadge())
                            <svg class="w-6 h-6 ml-2 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        @elseif($user->hasPurpleBadge())
                            <svg class="w-6 h-6 ml-2 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                    </h1>
                    @if($user->username)
                        <p class="text-gray-500 dark:text-gray-400">{{ '@'.$user->username }}</p>
                    @endif
                    @if($user->bio)
                        <p class="mt-2 text-gray-700 dark:text-gray-300">{{ $user->bio }}</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
            <div class="border-b border-gray-200 dark:border-gray-700">
                <nav class="flex -mb-px">
                    <a href="{{ route('profile.followers', $user) }}"
                       class="px-6 py-3 border-b-2 border-indigo-500 text-indigo-600 dark:text-indigo-400 font-medium">
                        Followers ({{ $followers->total() }})
                    </a>
                    <a href="{{ route('profile.following', $user) }}"
                       class="px-6 py-3 border-b-2 border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-600">
                        Following ({{ $user->following()->count() }})
                    </a>
                </nav>
            </div>
        </div>

        {{-- Followers List --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            @forelse($followers as $follower)
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 last:border-b-0">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <img src="{{ $follower->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($follower->name) }}"
                                 alt="{{ $follower->name }}"
                                 class="w-12 h-12 rounded-full">
                            <div class="ml-4">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                                    {{ $follower->name }}
                                    @if($follower->hasVerifiedBadge())
                                        <svg class="w-5 h-5 ml-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    @elseif($follower->hasPurpleBadge())
                                        <svg class="w-5 h-5 ml-1 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </h3>
                                @if($follower->username)
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ '@'.$follower->username }}</p>
                                @endif
                                @if($follower->bio)
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ Str::limit($follower->bio, 100) }}</p>
                                @endif
                            </div>
                        </div>

                        @if($follower->id !== auth()->id())
                            <form action="{{ auth()->user()->isFollowing($follower) ? route('follow.destroy', $follower) : route('follow.store', $follower) }}"
                                  method="POST">
                                @csrf
                                @if(auth()->user()->isFollowing($follower))
                                    @method('DELETE')
                                    <button type="submit" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                        Following
                                    </button>
                                @else
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                        Follow
                                    </button>
                                @endif
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-gray-100">No followers yet</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">This user doesn't have any followers yet.</p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($followers->hasPages())
            <div class="mt-6">
                {{ $followers->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
