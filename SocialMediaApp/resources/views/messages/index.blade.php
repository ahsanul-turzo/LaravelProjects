<x-app-layout>
    <div class="max-w-6xl mx-auto px-4 py-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Messages</h1>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            @forelse($conversations as $conversation)
                <a href="{{ route('messages.show', $conversation['user']) }}"
                   class="flex items-center p-4 border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    <div class="relative">
                        <img src="{{ $conversation['user']->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($conversation['user']->name) }}"
                             alt="{{ $conversation['user']->name }}"
                             class="w-12 h-12 rounded-full">
                        @if($conversation['unread_count'] > 0)
                            <span class="absolute top-0 right-0 block h-3 w-3 rounded-full bg-red-500 ring-2 ring-white dark:ring-gray-800"></span>
                        @endif
                    </div>

                    <div class="ml-4 flex-1">
                        <div class="flex items-center justify-between">
                            <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $conversation['user']->name }}</h3>
                            @if($conversation['last_message'])
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $conversation['last_message']->created_at->diffForHumans() }}
                                </span>
                            @endif
                        </div>

                        @if($conversation['last_message'])
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1 truncate">
                                @if($conversation['last_message']->sender_id === auth()->id())
                                    <span class="font-medium">You:</span>
                                @endif
                                {{ $conversation['last_message']->content }}
                            </p>
                        @else
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">No messages yet</p>
                        @endif
                    </div>

                    @if($conversation['unread_count'] > 0)
                        <div class="ml-4">
                            <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full">
                                {{ $conversation['unread_count'] }}
                            </span>
                        </div>
                    @endif
                </a>
            @empty
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-semibold text-gray-900 dark:text-gray-100">No conversations yet</h3>
                    <p class="mt-2 text-gray-500 dark:text-gray-400">Start a conversation by visiting a user's profile!</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
