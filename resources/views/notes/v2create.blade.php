<x-layout>
    <h1 class="text-2xl font-bold mb-4">Create Note</h1>

    <form action="{{ route('v2.store') }}" method="POST" class="space-y-4">
        @csrf
        <div>
            <label class="block font-medium">Title</label>
            <input type="text" name="title" value="{{ old('title') }}"
                   class="w-full border p-2 rounded-md @error('title') border-red-500 @enderror">
            @error('title') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block font-medium">Description</label>
            <textarea name="description" rows="4"
                      class="w-full border p-2 rounded-md @error('description') border-red-500 @enderror">
                {{ old('description') }}
            </textarea>
            @error('description') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="flex items-center space-x-3">
            <button type="submit"
                    class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">
                Save Note
            </button>
            <a href="{{ route('v2.index') }}" class="text-gray-500 hover:underline">Cancel</a>
        </div>
    </form>
</x-layout>
