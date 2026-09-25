<script lang="ts">
	import type { ContentBlock } from '$lib/interfaces/eventProject';

	interface Props {
		block: ContentBlock;
		class?: string;
	}

	let { block, class: className = '' }: Props = $props();
</script>

<section class={['px-base', className]}>
	<article class="grid grid-cols-1 lg:grid-cols-3 gap-y-3 gap-x-6">
		<h2 class="text-body-2 font-bold">{block.title}</h2>
		<div class="text-body-2 lg:col-span-2 prose">
			<!-- eslint-disable-next-line svelte/no-at-html-tags -- rich text comes from the trusted CMS writer field -->
			{@html block.description}
		</div>
	</article>
</section>
