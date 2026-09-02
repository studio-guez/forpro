<script lang="ts">
	import Img from '$lib/components/ui/Img.svelte';
	import VideoPlayer from '$lib/components/ui/VideoPlayer.svelte';
	import YoutubeEmbed from '$lib/components/ui/YoutubeEmbed.svelte';
	import { PAGE_CARD, cell, toSizes } from '$lib/utils/imgSizes';
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

	// Alternate the media column span in a 2/2/1/1 pattern, like `BlockModuleCases`.
	const mediaSpan = (index: number) => [2, 1, 1, 2][index % 4];

	const mediaSizes = (index: number) =>
		toSizes(cell(PAGE_CARD, { 0: 1, 1024: 3 }, 1.5, mediaSpan(index)));
</script>

{#if hasMedia}
	<section class={['px-card', className]} aria-label="Médias — {title}">
		<div class="grid grid-cols-1 lg:grid-cols-3 max-lg:gap-y-2.5 gap-6 items-stretch">
			{#each medias as media, index (index)}
				<figure class="m-0 flex flex-col" class:lg:col-span-2={mediaSpan(index) === 2}>
					<div
						class="grow overflow-hidden rounded-2xl min-h-50 md:min-h-80 lg:min-h-100"
						class:[contain:size]={media.type !== 'video'}
					>
						{#if media.type === 'video'}
							<VideoPlayer src={media.url} />
						{:else}
							<Img image={media} sizes={mediaSizes(index)} class="w-full h-full object-cover" />
						{/if}
					</div>
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
