<script lang="ts">
	import type { ContentBlock } from '$lib/interfaces/eventProject';

	interface Props {
		blocks: ContentBlock[];
		class?: string;
	}

	let { blocks, class: className = '' }: Props = $props();
</script>

{#if blocks.length > 0}
	<section class={['px-card space-y-12 md:space-y-16', className]}>
		{#each blocks as block, index (index)}
			<article class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-12">
				<h2 class="text-h4 text-teal">{block.title}</h2>
				<div class="lg:col-span-2 prose text-grey-dark">
					<!-- eslint-disable-next-line svelte/no-at-html-tags -- rich text comes from the trusted CMS writer field -->
					{@html block.description}
				</div>
			</article>
		{/each}
	</section>
{/if}
