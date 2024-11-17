<script>
    let { wire, dataset } = $props();
    let posts = $state(dataset.posts.data);
    let links = $state(dataset.posts.links);
    let currentPage = $state(dataset.posts.current_page);
    let meta = $state(dataset.posts);
    let isLoading = $state(false);

    async function handleDelete(id) {
        if (confirm("Are you sure to delete this post?")) {
            await wire.delete(id);
            posts = posts.filter(post => post.id !== id);
        }
    }

    async function handlePageChange(link) {
        if (!link.url || isLoading) return;
        
        // Extract page number from URL
        const url = new URL(link.url);
        const page = url.searchParams.get('page');
        
        try {
            isLoading = true;
            const result = await wire.getPage(page);
            if (result.success && result.data) {
                posts = result.data.data;
                links = result.data.links;
                currentPage = result.data.current_page;
                meta = result.data;
            }
        } catch (error) {
            console.error('Failed to fetch page:', error);
        } finally {
            isLoading = false;
        }
    }
</script>

<div class="space-y-6 px-6">
    {#if posts?.length === 0}
        <div class="space-y-3">
            <p class="opacity-60">No post history yet!</p>
            <a href="/create"
                class="inline-flex items-center gap-2 px-4 py-2 bg-sky-600 text-white rounded-lg hover:opacity-80 transition-colors">
                Let's create
            </a>
        </div>
    {/if}

    {#if posts}
        {#each posts as post}
            <div class="overflow-hidden bg-white border border-gray-200 rounded-lg">
                <div class="flex justify-between gap-3 items-center bg-gray-100 p-3">
                    <p class="text-sm opacity-80">{post.created_at_human}</p>
                </div>
                <div class="prose prose-sm max-w-none p-3">
                    {post.output.slice(0, 300)}{post.output.length > 300 ? '...' : ''}
                </div>
                <p class="px-3 pb-3 text-sm inline-flex gap-2 items-center">
                    <a class="hover:underline" href={`/post/${post.id}/edit`}>Edit</a>
                    <span class="h-4 w-px bg-gray-200"></span>
                    <a class="hover:underline" href={`/post/${post.id}`}>View</a>
                    <span class="h-4 w-px bg-gray-200"></span>
                    <button 
                        onclick={() => handleDelete(post.id)}
                        class="flex gap-1 items-center text-rose-500 hover:text-rose-700 text-sm">
                        <span>Delete</span>
                    </button>
                </p>
            </div>
        {/each}
    {/if}

    {#if links && links.length > 3}
        <nav role="navigation" aria-label="Pagination Navigation" class="flex items-center justify-between">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700 leading-5">
                        Showing
                        <span class="font-medium">{meta.from}</span>
                        to
                        <span class="font-medium">{meta.to}</span>
                        of
                        <span class="font-medium">{meta.total}</span>
                        results
                    </p>
                </div>

                <div>
                    <span class="relative z-0 inline-flex rtl:flex-row-reverse shadow-sm rounded-md">
                        {#each links as link}
                            {#if link.url}
                                <button
                                    onclick={() => handlePageChange(link)}
                                    class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium {link.active ? 'bg-sky-50' : 'text-gray-500 bg-white hover:bg-gray-50'} border border-gray-300 leading-5 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-sky-300 transition ease-in-out duration-150 {link.label === 'Previous' ? 'rounded-l-md' : ''} {link.label === 'Next' ? 'rounded-r-md' : ''} {isLoading ? 'opacity-50 cursor-not-allowed' : ''}"
                                    disabled={link.active || isLoading}>
                                    {@html link.label}
                                </button>
                            {:else}
                                <span class="relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-gray-700 bg-white border border-gray-300 cursor-default leading-5">
                                    {@html link.label}
                                </span>
                            {/if}
                        {/each}
                    </span>
                </div>
            </div>
        </nav>
    {/if}
</div>
