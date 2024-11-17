<script>
    import OpenAI from "openai";
    import { Button } from "bits-ui";
    import SparkleIcon from "lucide-svelte/icons/sparkle";
    import TrashIcon from "lucide-svelte/icons/trash";

    let { wire, dataset } = $props();
    let isGenerating = $state(false);
    let errorMessage = $state("");

    let input = $state({
        text: dataset.post.content || "",
        documents: [],
        mode: "generate",
    });

    let output = $state(dataset.post.output || "");

    async function handleClickGenerate() {
        errorMessage = "";
        output = "Thinking...";
        isGenerating = true;
        await generate();
        isGenerating = false;
        await wire.updatePost({ idea: input.text, output });
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

            output = "";

            for await (const event of stream) {
                switch (event.type) {
                    case "token":
                        output += event.data;
                        break;
                    default:
                        break;
                }
            }
        } catch (error) {
            console.error(error);
            errorMessage =
                error.message ||
                "Failed to generate content. Please try again.";
            output = "";
        }
    }

    async function handleSave() {
        await wire.updatePost({ idea: input.text, output });
    }

    async function handleDelete() {
        if (confirm("Are you sure you want to delete this post?")) {
            await wire.call("delete");
        }
    }
</script>

<div class="px-6">
    <div class="space-y-2 mb-3">
        <h1 class="text-3xl font-bold">Edit LinkedIn Post</h1>
        <p class="text-gray-500">
            Edit and regenerate your LinkedIn content with AI assistance
        </p>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-6">
        {#if errorMessage}
            <div class="p-4 mb-4 text-red-700 bg-red-100 rounded-lg">
                {errorMessage}
            </div>
        {/if}

        <div class="space-y-4">
            <div>
                <div class="block text-sm font-medium text-gray-700 mb-1">
                    Your Idea
                </div>
                <textarea
                    bind:value={input.text}
                    placeholder="Write your idea here..."
                    class="w-full h-32 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                ></textarea>
            </div>

            <div>
                <div class="block text-sm font-medium text-gray-700 mb-1">
                    Generated Content
                </div>
                <textarea
                    bind:value={output}
                    placeholder="Generated content will appear here..."
                    class="w-full h-48 p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-sky-500 focus:border-sky-500"
                ></textarea>
            </div>

            <div class="flex flex-wrap gap-3">
                <Button.Root
                    class="inline-flex gap-2 h-10 items-center justify-center rounded-input bg-sky-600
                px-4 text-[15px] font-semibold text-background rounded-md text-white
                hover:bg-sky-700 active:scale-98 active:transition-all"
                    onclick={handleClickGenerate}
                    disabled={isGenerating}
                >
                    <SparkleIcon
                        class={`size-4 ${isGenerating ? "animate-spin" : ""}`}
                    />
                    <span>{isGenerating ? "Generating..." : "Regenerate"}</span>
                </Button.Root>

                <Button.Root
                    class="inline-flex gap-2 h-10 items-center justify-center rounded-input bg-green-600
                px-4 text-[15px] font-semibold text-background rounded-md text-white
                hover:bg-green-700 active:scale-98 active:transition-all"
                    onclick={handleSave}
                >
                    <span>Save</span>
                </Button.Root>

                <button
                    onclick={handleDelete}
                    class="flex items-center gap-2 px-4 py-2 bg-red-100 text-red-600 rounded-lg hover:bg-red-200 transition-colors"
                >
                    <TrashIcon class="size-4" />
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>
