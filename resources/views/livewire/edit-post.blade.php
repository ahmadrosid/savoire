<div x-data="{
    isSaving: false,
    isRegenerating: false,
}">
<div class="flex justify-between items-center pb-3 px-2">
    <a href="{{ route('history') }}" class="hover:underline">
        Back
    </a>
    <a href="#" wire:click="delete" wire:confirm="Are you sure?" class="hover:underline text-rose-600">
        Delete
    </a>
</div>
    <div class="overflow-hidden bg-white border border-gray-200 rounded-lg p-6 space-y-6">
        <div>
            <div class="mb-3 font-bold">Tone</div>
            <select wire:model="tone"
                class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors border-0 w-36">
                <option value="professional">Professional</option>
                <option value="casual">Casual</option>
                <option value="enthusiastic">Enthusiastic</option>
                <option value="thoughtful">Thoughtful</option>
                <option value="joking">Joking</option>
            </select>
        </div>
        <div>
            <div class="mb-3 font-bold">Idea</div>
            <x-trix-input :id="'idea'" :name="'idea'" wire:model="idea" />
        </div>

        <div x-show="!isRegenerating">
            <div class="mb-3 font-bold">Output</div>
            <x-trix-input id="post" name="post" wire:model="output" />
        </div>

        <div
            x-cloack
            x-show="isRegenerating"
            class="prose prose-sm max-w-none rounded my-3 bg-gray-100 p-3 border border-gray-200" 
            wire:stream="output">
        </div>

        <div class="flex gap-3">
            <button
                @click="
                    isRegenerating = true;
                    await $wire.regeneratePost();
                    isRegenerating = false;
                "
                class="flex items-center gap-2 px-3 py-2 bg-gray-700 text-white rounded-lg"
                x-bind:class="{ 'opacity-50 cursor-not-allowed': isRegenerating }" 
                x-bind:class="{ 'hover:opacity-90 transition-colors': !isRegenerating }" 
                :disabled="isRegenerating">
                <x-refresh-icon
                    class="size-4" 
                    x-bind:class="{ 'animate-spin': isRegenerating }"></x-refresh-icon>
                <span x-text="isRegenerating ? 'Regenerating...' : 'Regenerate'"></span>
            </button>
            <button
                @click="
                    isSaving = true;
                    await $wire.updatePost();
                    isSaving = false;
                "
                class="flex items-center gap-2 px-6 py-2 bg-[#0A66C2] text-white rounded-lg hover:opacity-90 transition-colors"
                :class="{ 'opacity-50 cursor-not-allowed': isSaving }" :disabled="isSaving">
                <span x-text="isSaving ? 'Saving...' : 'Save'"></span>
            </button>
        </div>
    </div>
</div>
