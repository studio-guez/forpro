<script lang="ts">
	import type { ContentBlock } from '$lib/interfaces/eventProject';

	interface Props {
		blocks: ContentBlock[];
		class?: string;
	}

	let { blocks, class: className = '' }: Props = $props();
</script>

{#if blocks.length > 0}
	<section class={['px-card space-y-12 lg:space-y-16', className]}>
		{#each blocks as block, index (index)}
			<article class="grid grid-cols-1 lg:grid-cols-4 gap-y-3 gap-x-6">
				<h2 class="text-body-2 font-bold">{block.title}</h2>
				<div class="lg:col-span-3 prose">
					<!-- eslint-disable-next-line svelte/no-at-html-tags -- rich text comes from the trusted CMS writer field -->
					{@html block.description}
				</div>
			</article>
		{/each}
	</section>
{/if}
