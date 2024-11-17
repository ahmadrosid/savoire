<script>
    import OpenAI from "openai";
    import { Button } from "bits-ui";
    import SparkleIcon from "lucide-svelte/icons/sparkle";
    import RotateCwIcon from 'lucide-svelte/icons/rotate-cw';
    import Markdown from '../ui/Markdown.svelte';

    let { dataset } = $props();
    let output = $state();
    let isGenerating = $state(false);
    let errorMessage = $state('');

    let input = $state({
        text: '',
        documents: [],
        mode: 'generate',
    });

    async function handleClickGenerate() {
        errorMessage = '';
        output = 'Thinking...';
        isGenerating = true;
        await generate();
        isGenerating = false;
    }

    async function generate() {
        try {
            const client = new OpenAI({
                baseURL: location.origin,
                apiKey: "none",
                dangerouslyAllowBrowser: true,
                defaultHeaders: {
                    "X-CSRF-TOKEN": dataset.csrf,
                },
                maxRetries: 0,
            });

            const stream = await client.chat.completions.create(
                {
                    stream: true,
                    ...input,
                },
                { path: "/chat/stream" },
            );

            handleReset();
            output = 'Generating...';

            for await (const event of stream) {
                switch (event.type) {
                    case "token":
                        if (output === 'Generating...') {
                            output = '';
                        }
                        output += event.data;
                        break;
                    default:
                        break;
                }
            }
        } catch (error) {
            console.error(error);
            errorMessage = error.message || 'Failed to generate content. Please try again.';
            output = '';
        }
    }

    async function handleReset() {
        input.content = '';
        input.documents = [];
        output = '';
        errorMessage = '';
    }
</script>

<div class="px-6">
    <div class="space-y-2 mb-3">
        <h1 class="text-3xl font-bold">Create LinkedIn Post</h1>
        <p class="text-gray-500">
            Transform your ideas into engaging LinkedIn content with AI
            assistance
        </p>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-6">
        <div class="flex flex-wrap gap-3 mb-3">
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
                <RotateCwIcon class="size-4" />
                Reset
            </button>
        </div>

        {#if errorMessage}
            <div class="p-4 mb-4 text-red-800 bg-red-50 rounded-lg">
                {errorMessage}
            </div>
        {/if}

        <div class="space-y-4 bg-white mt-6 mb-6 rounded-xl">
            <textarea
                bind:value={input.text}
                class="p-2 w-full resize-none border border-gray-200 rounded-md focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                rows={4}
            ></textarea>
        </div>
    </div>

    <div
        class="bg-white border border-gray-200 rounded-lg overflow-hidden mt-8"
    >
        {#if output}
            <div class="flex justify-between items-center p-3 border-b">
                <h2 class="text-lg font-semibold">Output</h2>
                <div class="flex gap-3 items-center text-gray-500 px-3">
                    <span class="text-xs"
                        >{output.length}/3000 characters</span
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
            
            <Markdown content={output} />
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
