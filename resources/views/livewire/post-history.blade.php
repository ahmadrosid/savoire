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
                <div class="inline-flex gap-6">
                    <button wire:click="delete({{$post->id}})" wire:confirm="Are you sure to delete this post?" class="flex gap-1 items-center text-rose-500 hover:text-rose-700 text-sm">
                        <span>Delete</span>
                    </button>
                    <button class="flex gap-1 items-center hover:opacity-80 text-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" class="size-3">
                            <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                            <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" />
                        </svg>
                        <span>Copy</span>
                    </button>
                </div>
            </div>
            <div class="prose prose-sm max-w-none p-3">
                {{ Str::limit($post->output, 300) }}
            </div>
            <p class="px-3 pb-3 text-sm inline-flex gap-2 items-center">
                <a class="hover:underline" href="{{ route('post.edit', $post) }}">Edit</a>
                <span class="h-4 w-px bg-neutral-200"></span>
                <a class="hover:underline" href="{{ route('post.detail', $post) }}">View</a>
            </p>
        </div>
    @endforeach
    {{ $posts->links() }}
</div>
