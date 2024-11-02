<div>
    <x-slot name="title">Create LinkedIn Post</x-slot>
    <!-- Header -->
    <div class="space-y-2 mb-3">
        <h1 class="text-3xl font-bold">Create LinkedIn Post</h1>
        <p class="text-neutral-500">
            Transform your ideas into engaging LinkedIn content with AI assistance
        </p>
    </div>
    <div class="space-y-6" x-data="{
        output: false,
        isGenerating: false,
    }">
        <!-- Main Editor Section -->
        <div class="bg-white border border-neutral-200 rounded-lg p-6">
            <!-- Writing Tools -->
            <div class="flex flex-wrap gap-3 mb-3">
                <select wire:model="tone"
                    class="px-4 py-2 rounded-lg bg-neutral-100 hover:bg-neutral-200 transition-colors border-0 w-36">
                    <option value="professional">Professional</option>
                    <option value="casual">Casual</option>
                    <option value="enthusiastic">Enthusiastic</option>
                    <option value="thoughtful">Thoughtful</option>
                    <option value="joking">Joking</option>
                </select>

                <button
                    @click="
                        output = 'Thinking...'; 
                        isGenerating = true; 
                        $wire.output = '';
                        await $wire.generatePost();
                        isGenerating = false;
                    "
                    class="flex items-center gap-2 px-4 py-2 bg-[#0A66C2] text-white rounded-lg hover:opacity-90 transition-colors"
                    :class="{ 'opacity-50 cursor-not-allowed': isGenerating }" :disabled="isGenerating">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="size-4">
                        <path
                            d="M9.937 15.5A2 2 0 0 0 8.5 14.063l-6.135-1.582a.5.5 0 0 1 0-.962L8.5 9.936A2 2 0 0 0 9.937 8.5l1.582-6.135a.5.5 0 0 1 .963 0L14.063 8.5A2 2 0 0 0 15.5 9.937l6.135 1.581a.5.5 0 0 1 0 .964L15.5 14.063a2 2 0 0 0-1.437 1.437l-1.582 6.135a.5.5 0 0 1-.963 0z" />
                        <path d="M20 3v4" />
                        <path d="M22 5h-4" />
                        <path d="M4 17v2" />
                        <path d="M5 18H3" />
                    </svg>
                    <span x-text="isGenerating ? 'Generating...' : 'Generate'"></span>
                </button>

                <button
                    @click="
                        await $wire.resetAll();
                        output = false;
                    "
                    class="flex items-center gap-2 px-4 py-2 bg-neutral-100 rounded-lg hover:bg-neutral-200 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" class="size-4">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8" />
                        <path d="M3 3v5h5" />
                    </svg>
                    Reset
                </button>
            </div>

            <!-- Editor -->
            <div class="space-y-4 bg-white mt-6 rounded-xl">
                <x-trix-input id="post" name="post" wire:model="post" />
            </div>
        </div>

        <div class="bg-white border border-neutral-200 rounded-lg overflow-hidden">
            <!-- Tips Section -->
            <div x-show='output' x-cloak>
                <div class="flex justify-between items-center px-3 py-2">
                    <h2 class="text-lg font-semibold">Output</h2>
                    <div class="flex gap-3 items-center text-neutral-500 px-3">
                        <span wire:key="char-count" class="text-xs">{{ strlen($post) }}/3000 characters</span>
                        <div class="w-px bg-neutral-100 h-6"></div>
                        <button @click="navigator.clipboard.writeText($wire.output);"
                            class="flex items-center gap-1 text-neutral-600 text-xs hover:text-neutral-700">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="size-3">
                                <rect width="14" height="14" x="8" y="8" rx="2" ry="2" />
                                <path d="M4 16c-1.1 0-2-.9-2-2V4c0-1.1.9-2 2-2h10c1.1 0 2 .9 2 2" />
                            </svg>
                            Copy
                        </button>
                    </div>
                </div>
                <div class="prose prose-sm max-w-none p-3 rounded mb-3 bg-neutral-100 mx-3" wire:stream="output">
                    {!! $this->rendered_output() !!}
                </div>
            </div>
            <div class="px-3 my-3" x-show='!output' x-cloak>
                <div class="prose prose-sm max-w-none p-3 rounded">
                    <h2>Tips for Great LinkedIn Posts</h2>
                    <ul>
                        <li>Start with a hook that grabs attention</li>
                        <li>Break up text into readable paragraphs</li>
                        <li>Include relevant hashtags (3-5 recommended)</li>
                        <li>End with a clear call-to-action</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
