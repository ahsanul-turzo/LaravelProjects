@props(['note'])
<!-- Loop Card -->
<div>
    <div class="bg-gray-50 dark:bg-gray-800/50 rounded-xl px-4 py-2 border border-gray-200/80 dark:border-gray-800">
        <div class="space-y-1">
            <h2 class="text-lg font-bold">{{ $note['title'] }}</h2>
            <p class="dark:text-slate-400">{{ $note['content'] }}</p>
        </div>
    </div>
    @if(!empty($note['children']))
       <!-- Loop Inner Card -->
        <div class="border-l-2 ml-4 pl-4 space-y-4 mt-4 border-dashed border-slate-200 dark:border-slate-600">
            @foreach($note['children'] as $child)
                <x-indefinite-childrens :note="$child"/>
            @endforeach
        </div>
    @endif
</div>
