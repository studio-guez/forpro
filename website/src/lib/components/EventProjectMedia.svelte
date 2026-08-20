<script lang="ts">
	import Img from '$lib/components/ui/Img.svelte';
	import VideoPlayer from '$lib/components/ui/VideoPlayer.svelte';
	import YoutubeEmbed from '$lib/components/ui/YoutubeEmbed.svelte';
	import type { CmsMedia } from '$lib/interfaces/page';
	import type { YoutubeEmbedData } from '$lib/interfaces/eventProject';

	interface Props {
		medias?: CmsMedia[];
		embedVideos?: YoutubeEmbedData[];
		title: string;
		class?: string;
	}

	let { medias = [], embedVideos = [], title, class: className = '' }: Props = $props();

	const hasMedia = $derived(medias.length > 0 || embedVideos.length > 0);
</script>

{#if hasMedia}
	<section class={['px-card', className]} aria-label="Médias — {title}">
		<div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-9">
			{#each medias as media, index (index)}
				<figure class="m-0">
					{#if media.type === 'video'}
						<VideoPlayer src={media.url} class="rounded-2xl" />
					{:else}
						<Img
							image={media}
							sizes="(min-width: 768px) 50vw, 100vw"
							class="w-full h-auto rounded-2xl"
						/>
					{/if}
					{#if media.caption}
						<figcaption class="text-caption text-grey-dark mt-2">{media.caption}</figcaption>
					{/if}
				</figure>
			{/each}

			{#each embedVideos as embed (embed.id)}
				<div class={embed.type === 'short' ? 'mx-auto w-full max-w-xs' : ''}>
					<YoutubeEmbed {embed} title="Vidéo — {title}" class="rounded-2xl" />
				</div>
			{/each}
		</div>
	</section>
{/if}
