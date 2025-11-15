<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 py-6">
        {{-- Chat Header --}}
        <div class="bg-white dark:bg-gray-800 rounded-t-lg shadow p-4 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <a href="{{ route('messages.index') }}" class="mr-4 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                        </svg>
                    </a>
                    <img src="{{ $receiver->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($receiver->name) }}"
                         alt="{{ $receiver->name }}"
                         class="w-10 h-10 rounded-full">
                    <div class="ml-3">
                        <h3 class="font-semibold text-gray-900 dark:text-gray-100">{{ $receiver->name }}</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">@if($receiver->username){{ '@'.$receiver->username }}@endif</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Messages Container --}}
        <div class="bg-gray-50 dark:bg-gray-900 p-4 overflow-y-auto" style="height: 500px;" id="messages-container">
            @forelse($messages as $message)
                <div class="mb-4 flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    @if($message->sender_id !== auth()->id())
                        <img src="{{ $message->sender->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($message->sender->name) }}"
                             alt="{{ $message->sender->name }}"
                             class="w-8 h-8 rounded-full mr-2">
                    @endif

                    <div class="max-w-xs lg:max-w-md">
                        <div class="px-4 py-2 rounded-lg {{ $message->sender_id === auth()->id() ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100' }}">
                            <p class="break-words">{{ $message->content }}</p>
                        </div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 {{ $message->sender_id === auth()->id() ? 'text-right' : 'text-left' }}">
                            {{ $message->created_at->format('g:i A') }}
                            @if($message->sender_id === auth()->id() && $message->is_read)
                                <span class="ml-1">✓✓</span>
                            @endif
                        </p>
                    </div>

                    @if($message->sender_id === auth()->id())
                        <img src="{{ $message->sender->avatar ?? 'https://ui-avatars.com/api/?name='.urlencode($message->sender->name) }}"
                             alt="{{ $message->sender->name }}"
                             class="w-8 h-8 rounded-full ml-2">
                    @endif
                </div>
            @empty
                <div class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <svg class="w-16 h-16 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="mt-4 text-gray-500 dark:text-gray-400">No messages yet. Start the conversation!</p>
                    </div>
                </div>
            @endforelse
        </div>

        {{-- Message Input --}}
        <div class="bg-white dark:bg-gray-800 rounded-b-lg shadow p-4">
            <form action="{{ route('messages.store', $receiver) }}" method="POST" id="message-form" class="flex space-x-3">
                @csrf
                <input type="text"
                       name="content"
                       id="message-input"
                       placeholder="Type a message..."
                       class="flex-1 rounded-lg border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                       required
                       autocomplete="off">
                <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                    </svg>
                </button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Auto-scroll to bottom on page load
        const messagesContainer = document.getElementById('messages-container');
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Handle form submission
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');

        if (messageForm) {
            messageForm.addEventListener('submit', async (e) => {
                e.preventDefault();

                const formData = new FormData(messageForm);
                const content = messageInput.value.trim();

                if (!content) return;

                try {
                    const response = await fetch(messageForm.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    });

                    if (response.ok) {
                        const data = await response.json();
                        messageInput.value = '';

                        // Add message to UI
                        appendMessage(data.message, true);
                    }
                } catch (error) {
                    console.error('Error sending message:', error);
                }
            });
        }

        // Listen for new messages via Laravel Echo (if configured)
        if (typeof Echo !== 'undefined') {
            Echo.private('chat.{{ auth()->id() }}')
                .listen('.message.sent', (e) => {
                    if (e.sender_id === {{ $receiver->id }}) {
                        appendMessage(e, false);

                        // Mark as read
                        fetch(`/messages/${e.id}/read`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Content-Type': 'application/json',
                            }
                        });
                    }
                });
        }

        function appendMessage(message, isOwn) {
            const container = document.getElementById('messages-container');
            const messageDiv = document.createElement('div');
            messageDiv.className = `mb-4 flex ${isOwn ? 'justify-end' : 'justify-start'}`;

            const avatar = isOwn
                ? '{{ auth()->user()->avatar ?? "https://ui-avatars.com/api/?name=".urlencode(auth()->user()->name) }}'
                : (message.sender?.avatar || `https://ui-avatars.com/api/?name=${encodeURIComponent(message.sender?.name || 'User')}`);

            const time = new Date().toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' });

            messageDiv.innerHTML = `
                ${!isOwn ? `<img src="${avatar}" alt="Avatar" class="w-8 h-8 rounded-full mr-2">` : ''}
                <div class="max-w-xs lg:max-w-md">
                    <div class="px-4 py-2 rounded-lg ${isOwn ? 'bg-indigo-600 text-white' : 'bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100'}">
                        <p class="break-words">${message.content}</p>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 ${isOwn ? 'text-right' : 'text-left'}">
                        ${time}
                    </p>
                </div>
                ${isOwn ? `<img src="${avatar}" alt="Avatar" class="w-8 h-8 rounded-full ml-2">` : ''}
            `;

            container.appendChild(messageDiv);
            container.scrollTop = container.scrollHeight;
        }
    </script>
    @endpush
</x-app-layout>
