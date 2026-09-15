<script lang="ts">
	import EventProjectText from '$lib/components/blocks/EventProjectText.svelte';
	import type { ContentBlock } from '$lib/interfaces/eventProject';

	interface Props {
		blocks: ContentBlock[];
		class?: string;
	}

	let { blocks, class: className = '' }: Props = $props();
</script>

{#if blocks.length > 0}
	<div class={['space-y-12 lg:space-y-16', className]}>
		{#each blocks as block, index (index)}
			<EventProjectText {block} />
		{/each}
	</div>
{/if}
