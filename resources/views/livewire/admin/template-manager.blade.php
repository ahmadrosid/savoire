<div class="p-6">
    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-4">Create New Category</h2>
        <form wire:submit="createCategory" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Category Name</label>
                <input type="text" wire:model="newCategoryName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                @error('newCategoryName') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700">Description</label>
                <textarea wire:model="newCategoryDescription" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                @error('newCategoryDescription') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="bg-sky-500 text-white px-4 py-2 rounded-md hover:bg-sky-600">
                Create Category
            </button>
        </form>
    </div>

    <div class="mb-8">
        <h2 class="text-2xl font-bold mb-4">Create New Template</h2>
        <form wire:submit="createTemplate" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Category</label>
                <select wire:model="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="">Select a category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                @error('category_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">JSON Template</label>
                <textarea 
                    wire:model="jsonContent" 
                    rows="10" 
                    placeholder="Paste your JSON template here"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm font-mono"
                ></textarea>
                @error('jsonContent') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div class="flex space-x-4">
                <button type="submit" class="bg-sky-500 text-white px-4 py-2 rounded-md hover:bg-sky-600">
                    Create Template
                </button>
            </div>

            <!-- Preview Section -->
            @if($name)
            <div class="mt-4 p-4 bg-gray-50 rounded-lg">
                <h3 class="font-bold text-lg mb-2">Preview</h3>
                <div class="space-y-2">
                    <p><span class="font-semibold">Name:</span> {{ $name }}</p>
                    <p><span class="font-semibold">Avatar:</span> <img src="{{ $avatar }}" alt="Avatar" class="w-10 h-10 rounded-full inline-block"></p>
                    <p><span class="font-semibold">Content:</span><br>{{ $content }}</p>
                    <p><span class="font-semibold">Reactions:</span> {{ number_format($total_reactions) }}</p>
                    <p><span class="font-semibold">Comments:</span> {{ number_format($total_comments) }}</p>
                    <p><span class="font-semibold">Reposts:</span> {{ number_format($total_reposts) }}</p>
                </div>
            </div>
            @endif
        </form>
    </div>

    <div>
        <h2 class="text-2xl font-bold mb-4">Existing Templates</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 py-4">
            @foreach($categories as $category)
                <div class="space-y-2">
                    <h3 class="font-medium">{{ $category->name }}</h3>
                    <div class="space-y-2">
                        @foreach($templates->where('category_id', $category->id) as $template)
                            <div class="w-full p-4 border bg-white rounded-lg hover:bg-gray-50 text-left space-y-2">
                                <div class="flex items-center gap-2">
                                    @if(Str::startsWith($template->avatar, 'http'))
                                        <img src="{{ $template->avatar }}" alt="{{ $template->name }}" class="w-8 h-8 rounded-full object-cover" />
                                    @else
                                        <img src="{{ Storage::url($template->avatar) }}" alt="{{ $template->name }}" class="w-8 h-8 rounded-full object-cover" />
                                    @endif
                                    <div class="flex-1">
                                        <h4 class="font-medium">{{ $template->name }}</h4>
                                    </div>
                                    <button wire:click="deleteTemplate({{ $template->id }})" wire:confirm="Are you sure you want to delete this template?" class="text-red-600 hover:text-red-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-sm text-gray-600 line-clamp-2">{{ \Illuminate\Support\Str::limit($template->content, 100) }}</p>
                                <div class="mt-2 text-sm text-gray-500">
                                    <span class="mr-4">👍 {{ $template->total_reactions }}</span>
                                    <span class="mr-4">💬 {{ $template->total_comments }}</span>
                                    <span>🔄 {{ $template->total_reposts }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
