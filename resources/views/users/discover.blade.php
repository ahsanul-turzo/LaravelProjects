<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 py-6">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-4">Discover People</h1>

            {{-- Search Bar --}}
            <form method="GET" action="{{ route('users.discover') }}" class="mb-6">
                <div class="flex gap-3">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search users by name or username..."
                           class="flex-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500">
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                        Search
                    </button>
                    @if(request('search'))
                        <a href="{{ route('users.discover') }}" class="px-6 py-2 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                            Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Suggested Users / Search Results --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-4 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ request('search') ? 'Search Results' : 'Suggested Users' }}
                </h2>
            </div>

            @forelse($users as $user)
                <div class="p-4 border-b border-gray-200 dark:border-gray-700 last:border-b-0 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <div class="flex items-center justify-between">
                        <a href="{{ route('profile.show', $user) }}" class="flex items-center flex-1">
                            <img src="{{ $user->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}"
                                 alt="{{ $user->name }}"
                                 class="w-16 h-16 rounded-full">
                            <div class="ml-4">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100 flex items-center">
                                    {{ $user->name }}
                                    @if($user->hasVerifiedBadge())
                                        <svg class="w-5 h-5 ml-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    @elseif($user->hasPurpleBadge())
                                        <svg class="w-5 h-5 ml-1 text-purple-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                    @endif
                                </h3>
                                @if($user->username)
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ '@'.$user->username }}</p>
                                @endif
                                @if($user->bio)
                                    <p class="text-sm text-gray-600 dark:text-gray-300 mt-1">{{ Str::limit($user->bio, 100) }}</p>
                                @endif
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $user->followers()->count() }} followers · {{ $user->posts()->count() }} posts
                                </p>
                            </div>
                        </a>

                        <div class="flex gap-2">
                            @if($user->id !== auth()->id())
                                <form action="{{ auth()->user()->isFollowing($user) ? route('follow.destroy', $user) : route('follow.store', $user) }}"
                                      method="POST">
                                    @csrf
                                    @if(auth()->user()->isFollowing($user))
                                        @method('DELETE')
                                        <button type="submit" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600 transition">
                                            Following
                                        </button>
                                    @else
                                        <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                                            Follow
                                        </button>
                                    @endif
                                </form>

                                <a href="{{ route('profile.show', $user) }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                    View Profile
                                </a>
                            @else
                                <a href="{{ route('profile.show', $user) }}" class="px-4 py-2 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded-lg hover:bg-gray-300 dark:hover:bg-gray-600 transition">
                                    View My Profile
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-gray-100">No users found</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">
                        {{ request('search') ? 'Try a different search term.' : 'No users to suggest at the moment.' }}
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
