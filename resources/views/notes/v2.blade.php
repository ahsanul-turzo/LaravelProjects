<x-layout>
    <!-- An unexamined life is not worth living. - Socrates -->

    <div class="flex flex-col gap-2 items-start justify-start">
        @foreach($notes as $note)
            <div class="flex items-center gap-2">
                <p class="dark:text-gray-400">
                    <span>•</span>{{ $note->title }}
                </p>
                <div class="flex items-center justify-center gap-2">
                    <a href="{{ route('v2.edit', ['v2' => $note->id ]) }}">
                        <button class="button">Edit</button>
                    </a>
                    <button class="buttonDanger">Delete</button>
                </div>
            </div>
        @endforeach
    </div>
</x-layout>
