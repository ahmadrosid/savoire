<script>
    import { Button } from "bits-ui";
    import SparkleIcon from "lucide-svelte/icons/sparkle";

    let { wire, snapshot } = $props();
    let output = $state();
    let isGenerating = $state(false);

    async function handleClickGenerate() {
        output = "Thinking...";
        isGenerating = true;
        try {
            await wire.generatePost();
        } finally {
            isGenerating = false;
            output = '';
        }
    }

    async function handleReset() {
        await wire.resetPost();
    }
</script>

<div>
    <div class="space-y-2 mb-3">
        <h1 class="text-3xl font-bold">Create LinkedIn Post</h1>
        <p class="text-gray-500">
            Transform your ideas into engaging LinkedIn content with AI
            assistance
        </p>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <!-- Writing Tools -->
        <div class="flex flex-wrap gap-3 mb-3">
            <select
                class="px-4 py-2 rounded-lg bg-gray-100 hover:bg-gray-200 transition-colors border-0 w-36"
            >
                <option value="professional">Professional</option>
                <option value="casual">Casual</option>
                <option value="enthusiastic">Enthusiastic</option>
                <option value="thoughtful">Thoughtful</option>
                <option value="joking">Joking</option>
            </select>

            <Button.Root
                class="inline-flex gap-2 h-10 items-center justify-center rounded-input bg-sky-600
                px-4 text-[15px] font-semibold text-background rounded-md text-white
                hover:bg-sky-700 active:scale-98 active:transition-all"
                onclick={handleClickGenerate}
                disabled={isGenerating}
            >
                <SparkleIcon class={`size-4 ${isGenerating ? "animate-spin" : ''}`} />
                <span>{isGenerating ? "Generating..." : "Generate"}</span>
            </Button.Root>

            <button
                onclick={handleReset}
                class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="24"
                    height="24"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    class="size-4"
                >
                    <path
                        d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"
                    />
                    <path d="M3 3v5h5" />
                </svg>
                Reset
            </button>
        </div>

        <!-- Editor -->
        <div class="space-y-4 bg-white mt-6 mb-6 rounded-xl">
            <textarea
                class="p-2 w-full resize-none border border-gray-200 rounded-md focus:outline-none active:outline-none"
                rows={4}
            ></textarea>
        </div>
    </div>

    <div
        class="bg-white border border-gray-200 rounded-lg overflow-hidden mt-8"
    >
        {#if output}
            <div class="flex justify-between items-center px-3 py-2">
                <h2 class="text-lg font-semibold">Output</h2>
                <div class="flex gap-3 items-center text-gray-500 px-3">
                    <span class="text-xs"
                        >{wire.post.length}/3000 characters</span
                    >
                    <div class="w-px bg-gray-100 h-6"></div>
                    <button
                        onclick={() => navigator.clipboard.writeText(output)}
                        aria-label="Copy to clipboard"
                    >
                        <copy-icon></copy-icon>
                    </button>
                </div>
            </div>
            <div
                class="prose prose-sm max-w-none p-3 rounded mb-3 bg-gray-100 mx-3"
            >
                {output}
            </div>
        {:else}
            <div class="px-3 my-3">
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
        {/if}
    </div>
</div>
