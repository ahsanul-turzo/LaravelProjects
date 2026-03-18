<x-layouts.app>
    <div class="max-w-2xl mx-auto space-y-6">
        {{-- Header --}}
        <header class="text-center">
            <h1 class="text-3xl font-bold tracking-tight text-white">
                <a href="{{ route('greeting') }}">My Todos</a>
            </h1>
            <p class="text-gray-400">Stay productive — one task at a time.</p>
            <a href="{{ route('greeting') }}">
                Go to greeting route
            </a>
        </header>

        {{-- Add Todo --}}
        <form class="flex items-center gap-0 shadow-lg">
            <input
                class="custom-input w-full !rounded-r-none"
                type="text" placeholder="Enter a new task...">
            <button
                class="custom-button !rounded-l-none">
                Add
            </button>
        </form>

        {{-- Todo List --}}
        <div class="space-y-3" id="todo-list">
            {{-- Todo Item --}}
            <div class="flex items-center justify-between bg-zinc-700 px-4 py-3 rounded-xl shadow-md">
                <div class="flex items-center gap-3">
                    <input type="checkbox" class="accent-indigo-500 h-5 w-5">
                    <span class="text-lg">Buy groceries</span>
                </div>
                <div class="flex items-center gap-2">
                    <button class="custom-button">Edit</button>
                    <button class="custom-button-danger">Delete</button>
                </div>
            </div>

            <div class="flex items-center justify-between bg-zinc-700 px-4 py-3 rounded-xl shadow-md">
                <div class="flex items-center gap-3">
                    <input type="checkbox" class="accent-indigo-500 h-5 w-5" checked>
                    <span class="text-lg line-through text-gray-400">Finish homework</span>
                </div>
                <div class="flex items-center gap-2">
                    <button class="custom-button">Edit</button>
                    <button class="custom-button-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
