<x-layout>
    <div class="mb-4">
        <a href="{{ route('v2.index') }}" class="text-blue-500 hover:underline">← Back to Notes</a>
    </div>

    <h1 class="text-3xl font-bold mb-2">{{ $note->title }}</h1>
    <p class="text-gray-400 text-sm mb-6">Created {{ $note->created_at->diffForHumans() }}</p>

    <div class="text-gray-800 whitespace-pre-wrap leading-relaxed">
        {{ $note->description }}
    </div>
</x-layout>
