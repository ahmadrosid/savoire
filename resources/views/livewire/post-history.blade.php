<div class="space-y-6">
    @if ($posts->isEmpty())
        <div class="space-y-3">
            <p class="opacity-60">No post history yet!</p>
            <a href="{{ route('create') }}"
                class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:opacity-80 transition-colors">Let's
                create</a>
        </div>
    @endif

    @foreach ($posts as $post)
        <div class="overflow-hidden bg-white border border-neutral-200 rounded-lg">
            <div class="flex justify-between gap-3 items-center bg-neutral-100 p-3">
                <p class="text-sm opacity-80">{{ $post->created_at->diffForHumans() }}</p>
            </div>
            <div class="prose prose-sm max-w-none p-3">
                {{ Str::limit($post->output, 300) }}
            </div>
            <p class="px-3 pb-3 text-sm inline-flex gap-2 items-center">
                <a class="hover:underline" href="{{ route('post.edit', $post) }}">Edit</a>
                <span class="h-4 w-px bg-neutral-200"></span>
                <a class="hover:underline" href="{{ route('post.detail', $post) }}">View</a>
                <span class="h-4 w-px bg-neutral-200"></span>
                <button wire:click="delete({{$post->id}})" wire:confirm="Are you sure to delete this post?" class="flex gap-1 items-center text-rose-500 hover:text-rose-700 text-sm">
                    <span>Delete</span>
                </button>
            </p>
        </div>
    @endforeach
    {{ $posts->links() }}
</div>
