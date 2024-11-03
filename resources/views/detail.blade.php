<x-main-layout>
    <x-slot name="content">
        <div class="flex justify-between items-center pb-3 px-2">
            <a href="{{ url()->previous() }}" class="hover:underline">
                Back
            </a>
            <a href="{{ route('post.edit', $post) }}" class="hover:underline">
                Edit
            </a>
        </div>
        <div class="overflow-hidden bg-white border border-neutral-200 rounded-lg relative">
            <copy-icon onclick="navigator.clipboard.writeText(@js($post->output))" class="size-4 absolute top-2 right-6"></copy-icon>
            <div class="p-6 prose prose-sm max-w-none">
                {!! Str::markdown($post->output, ['renderer' => ['soft_break' => "<br />\n"]]) !!}
            </div>
        </div>
    </x-slot>
</x-main-layout>
