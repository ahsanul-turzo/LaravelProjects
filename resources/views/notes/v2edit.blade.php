<x-layout>
    <h1 class="text-2xl font-bold mb-4 dark:text-gray-400">Edit Note</h1>
    <form action="{{ route('v2.update', $v2) }}" method="POST" class="space-y-4 dark:text-gray-400">
        @csrf
        @method('PUT') <!-- Spoofs PUT request for Resource Controller -->

        <div>
            <label class="block font-medium">Title</label>
            <input type="text" name="title" value="{{ old('title', $v2->title) }}"
                   class="w-full border p-2 rounded-md">
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium">Description</label>
            <textarea name="description" rows="4"
                      class="w-full border p-2 rounded-md">{{ old('description', $v2->description) }}</textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center space-x-3">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Update Note
            </button>
            <a href="{{ route('v2.index') }}" class="text-gray-500 hover:underline">Cancel</a>
        </div>
    </form>
</x-layout>
