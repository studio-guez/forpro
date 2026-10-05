<script lang="ts">
	import EventProjectMedia from '$lib/components/blocks/EventProjectMedia.svelte';
	import EventProjectVideo from '$lib/components/blocks/EventProjectVideo.svelte';
	import EventProjectText from '$lib/components/blocks/EventProjectText.svelte';
	import EventProjectLinks from '$lib/components/blocks/EventProjectLinks.svelte';
	import type { Block } from '$lib/interfaces/page';
	import type {
		ContentLinksContent,
		ContentMediasContent,
		ContentTextContent,
		ContentVideoContent
	} from '$lib/interfaces/eventProject';

	interface Props {
		blocks: Block[];
		/** Page title, used for the media / video accessible labels. */
		title: string;
	}

	let { blocks, title }: Props = $props();
</script>

{#each blocks as block (block.id)}
	{#if !block.isHidden}
		{#if block.type === 'content-medias'}
			<EventProjectMedia
				medias={(block.content as unknown as ContentMediasContent).medias}
				{title}
			/>
		{:else if block.type === 'content-video'}
			<EventProjectVideo video={(block.content as unknown as ContentVideoContent).video} {title} />
		{:else if block.type === 'content-text'}
			<EventProjectText block={block.content as unknown as ContentTextContent} />
		{:else if block.type === 'content-links'}
			{@const links = block.content as unknown as ContentLinksContent}
			<EventProjectLinks links={links.links} title={links.title} />
		{/if}
	{/if}
{/each}
