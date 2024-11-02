<div x-data="{
    isSaving: false,
}">
<div class="flex justify-between items-center pb-3 px-2">
    <a href="{{ route('history') }}" class="hover:underline">
        Back
    </a>
    <a href="#" wire:click="delete" wire:confirm="Are you sure?" class="hover:underline text-rose-600">
        Delete
    </a>
</div>
    <div class="overflow-hidden bg-white border border-neutral-200 rounded-lg p-6 space-y-6">
        <div class="mb-3 font-bold">Output</div>
        <x-trix-input id="post" name="post" wire:model="output" />

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
