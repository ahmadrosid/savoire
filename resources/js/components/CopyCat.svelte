<script>
    import OpenAI from "openai";
    import { Button } from "bits-ui";
    import SparkleIcon from "lucide-svelte/icons/sparkle";
    import CopyIcon from "lucide-svelte/icons/copy";
    import CheckIcon from "lucide-svelte/icons/check";
    import RotateCwIcon from 'lucide-svelte/icons/rotate-cw';
    import Markdown from "../ui/Markdown.svelte";
    import TemplateSelector from "../ui/TemplateSelector.svelte";

    let { wire, dataset } = $props();
    let isGenerating = $state(false);
    let errorMessage = $state("");
    let copied = $state(false);

    let input = $state({
        post: "",
        template: "",
        documents: [],
        mode: "generate",
    });

    let output = $state("");
    let showTemplateDialog = $state(false);

    // Parse templates data from data attribute
    let templatesData = dataset.templates;
    let templates = templatesData.templates;
    let categories = templatesData.categories;

    function selectTemplate(template) {
        input.template = template.content;
        showTemplateDialog = false;
    }

    async function handleGenerate() {
        errorMessage = "";
        output = "Analyzing template...";
        isGenerating = true;

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

            // First analyze the template
            output = "";
            const analyzeStream = await client.chat.completions.create(
                {
                    stream: true,
                    text: input.template,
                    mode: "analyze",
                },
                { path: "/chat/stream" },
            );

            let analysisResult = "";
            for await (const event of analyzeStream) {
                if (event.type === "token") {
                    analysisResult += event.data;
                    output = 'Analyzing...';
                }
            }

            // Then generate the post with the analyzed style
            output = "Generating post...";
            const generateStream = await client.chat.completions.create(
                {
                    stream: true,
                    text: input.post,
                    analysis: analysisResult,
                    mode: "copy",
                },
                { path: "/chat/stream" },
            );

            output = "";
            for await (const event of generateStream) {
                if (event.type === "token") {
                    output += event.data;
                }
            }

            // Save the result
            await wire.call("performCopyStyle", {
                post: input.post,
                output,
            });
        } catch (error) {
            console.error(error);
            errorMessage =
                error.message ||
                "Failed to generate content. Please try again.";
            output = "";
        } finally {
            isGenerating = false;
        }
    }

    function handleReset() {
        input.post = "";
        input.template = "";
        output = "";
        wire.call("resetAll");
    }
</script>

<div class="px-6">
    <div class="space-y-2 mb-3">
        <h1 class="text-3xl font-bold">Copy Cat - Style Mimic</h1>
        <p class="text-gray-500">
            Generate content by mimicking the style of an existing post
        </p>
    </div>

    <div class="bg-white border border-gray-200 rounded-lg p-6">
        {#if errorMessage}
            <div class="p-4 mb-4 text-red-700 bg-red-100 rounded-lg">
                {errorMessage}
            </div>
        {/if}

        <div class="space-y-4">
            <div class="flex justify-between items-center">
                <h2 class="text-lg font-semibold">Copy Cat</h2>
                <button 
                    class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                    onclick={() => showTemplateDialog = true}
                >
                    <SparkleIcon class="w-4 h-4" />
                    <span>Select Template</span>
                </button>
            </div>

            <TemplateSelector
                {templates}
                {categories}
                onSelect={selectTemplate}
                bind:open={showTemplateDialog}
            />

            <div>
                <div class="block text-sm font-medium text-gray-700 mb-1">
                    Template Post (Style to Copy)
                </div>
                <textarea
                    bind:value={input.template}
                    placeholder="Paste the post whose style you want to copy..."
                    class="w-full h-32 px-3 py-2 text-base text-gray-700 placeholder-gray-400 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                ></textarea>
            </div>

            <div>
                <div class="block text-sm font-medium text-gray-700 mb-1">
                    Your Content
                </div>
                <textarea
                    bind:value={input.post}
                    placeholder="Write your content here..."
                    class="w-full h-32 px-3 py-2 text-base text-gray-700 placeholder-gray-400 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent"
                ></textarea>
            </div>

            <div class="flex space-x-2">
                <Button.Root
                    class="inline-flex gap-2 h-10 items-center justify-center rounded-input bg-sky-600
                    px-4 text-[15px] font-semibold text-background rounded-md text-white
                    hover:bg-sky-700 active:scale-98 active:transition-all"
                    disabled={isGenerating || !input.post || !input.template}
                    onclick={handleGenerate}
                >
                    <div class="flex items-center space-x-2">
                        <SparkleIcon
                            class={`size-4 ${isGenerating ? "animate-spin" : ""}`}
                        />
                        <span>{isGenerating ? "Analyzing..." : "Generate"}</span
                        >
                    </div>
                </Button.Root>

                <button
                    disabled={isGenerating}
                    onclick={handleReset}
                    class="flex items-center gap-2 px-4 py-2 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors"
                >
                    <div class="flex items-center space-x-2">
                        <RotateCwIcon class="w-4 h-4" />
                        <span>Reset</span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    {#if output}
        <div class="bg-white border border-gray-200 rounded-lg mt-8">
            <div class="flex justify-between items-center mb-2 p-4 border-b border-gray-200">
                <div>
                    <div class="block text-sm font-medium text-gray-700 mb-1">
                        Generated Output
                    </div>
                    <div class="text-xs text-gray-500">
                        {output.length} characters
                    </div>
                </div>
                <button
                    class="flex items-center gap-1 px-2 py-1 text-xs text-gray-600 hover:text-gray-900"
                    onclick={async () => {
                        await navigator.clipboard.writeText(output);
                        copied = true;
                        setTimeout(() => (copied = false), 2000);
                    }}
                >
                    {#if copied}
                        <CheckIcon class="w-4 h-4" />
                        <span>Copied!</span>
                    {:else}
                        <CopyIcon class="w-4 h-4" />
                        <span>Copy</span>
                    {/if}
                </button>
            </div>
            <div class="w-full min-h-[8rem] mt-2">
                <Markdown content={output} />
            </div>
        </div>
    {/if}
</div>
