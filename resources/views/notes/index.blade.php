<x-layout>
    <header class="flex items-center justify-end gap-2 w-full">
        <a href="/">
            <button
                class="flex gap-2 items-center cursor-pointer bg-slate-200 dark:bg-slate-800 dark:text-slate-100 h-8 px-2 rounded-full">
                <svg class="w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 16 16" fill="none">
                    <path d="M6 0H5L0 5L5 10H6V6H12V15H14V4H6V0Z" fill="currentColor"/>
                </svg>
                <span>Back</span>
            </button>
        </a>
        <x-toggle-theme/>
    </header>

    {{-- Main Content --}}

    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6 flex items-center gap-2">
        📝 Infinitely Nested Notes
    </h1>
    <div
        class="max-w-3xl mx-auto bg-white dark:bg-slate-900 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-800">
        {{-- Loop Outer Shell --}}
        <div class="dark:text-slate-200 p-4 border border-gray-200 dark:border-slate-700 rounded-xl space-y-4">
            @forelse($notes as $note)
                {{-- Inner Cards --}}
                <x-indefinite-childrens :note="$note" />
            @empty
                <p class="text-gray-500 dark:text-gray-400 italic text-sm">
                    No notes found. Create one to get started!
                </p>
            @endforelse
        </div>
    </div>
</x-layout>
