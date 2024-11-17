<script>
    import Markdown from 'svelte-exmarkdown';
	import { gfmPlugin } from 'svelte-exmarkdown/gfm';
    import remarkBreaks from 'remark-breaks';
    const remarkBreakPlugin = (options = {}) => ({ 
        remarkPlugin: [remarkBreaks, options] 
    });

	const plugins = [gfmPlugin(), remarkBreakPlugin()];
    let { content = $bindable() } = $props();
</script>

<div  class="prose prose-sm max-w-none p-3">
    <Markdown md={content} {plugins} />
</div>
