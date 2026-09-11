<script lang="ts">
	import YoutubeEmbed from '$lib/components/ui/YoutubeEmbed.svelte';
	import type { YoutubeEmbedData } from '$lib/interfaces/eventProject';

	interface Props {
		embedVideos?: YoutubeEmbedData[];
		title: string;
		class?: string;
	}

	let { embedVideos = [], title, class: className = '' }: Props = $props();
</script>

{#if embedVideos.length > 0}
	<section class={['px-base', className]} aria-label="Vidéos — {title}">
		<div class="flex flex-col gap-6">
			{#each embedVideos as embed (embed.id)}
				<div class={embed.type === 'short' ? 'mx-auto w-full max-w-xs' : ''}>
					<YoutubeEmbed {embed} title="Vidéo — {title}" class="rounded-2xl" />
				</div>
			{/each}
		</div>
	</section>
{/if}
