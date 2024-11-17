<script>
    import { Dialog } from "bits-ui";
    import { Button } from "bits-ui";
    import { fade } from 'svelte/transition';

    export let templates = [];
    export let categories = [];
    export let onSelect = () => {};
    export let open = false;

    function handleTemplateSelect(template) {
        onSelect(template);
        open = false;
    }

    function handleClose() {
        open = false;
    }
</script>

<Dialog.Root bind:open>
    <Dialog.Portal>
        <Dialog.Overlay 
            class="fixed inset-0 z-50 bg-black/50" 
            transition={fade}
            transitionConfig={{ duration: 150 }}
        />
        <Dialog.Content 
            class="fixed left-[50%] top-[50%] z-50 w-full max-w-[94%] translate-x-[-50%] translate-y-[-50%] rounded-lg border bg-white p-6 shadow-lg focus:outline-none sm:max-w-[600px]"
        >
            <Dialog.Title class="text-lg font-semibold mb-2">Select Template</Dialog.Title>
            <Dialog.Description class="text-sm text-gray-500 mb-4">
                Choose a template to get started with your post
            </Dialog.Description>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 py-4">
                {#each categories as category}
                    <div class="space-y-2">
                        <h3 class="font-medium">{category.name}</h3>
                        <div class="space-y-2">
                            {#each templates.filter(t => t.category_id === category.id) as template}
                                <button
                                    class="w-full p-4 border rounded-lg hover:bg-gray-50 text-left space-y-2"
                                    onclick={() => handleTemplateSelect(template)}
                                >
                                    <div class="flex items-center gap-2">
                                        {#if template.avatar}
                                            <img src={template.avatar} alt={template.name} class="w-8 h-8 rounded-full" />
                                        {/if}
                                        <h4 class="font-medium">{template.name}</h4>
                                    </div>
                                    <p class="text-sm text-gray-600 line-clamp-2">{template.content}</p>
                                </button>
                            {/each}
                        </div>
                    </div>
                {/each}
            </div>

            <div class="mt-6 flex justify-end">
                <Dialog.Close asChild let:props>
                    <Button.Root variant="outline" onclick={handleClose}>Close</Button.Root>
                </Dialog.Close>
            </div>

            <Dialog.Close class="absolute right-4 top-4 rounded-sm opacity-70 ring-offset-white transition-opacity hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-gray-950 focus:ring-offset-2">
                <Button.Root variant="ghost" class="h-8 w-8 p-0" onclick={handleClose}>
                    <span class="sr-only">Close</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                </Button.Root>
            </Dialog.Close>
        </Dialog.Content>
    </Dialog.Portal>
</Dialog.Root>
