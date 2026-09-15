<script lang="ts">
	import Img from '$lib/components/ui/Img.svelte';
	import VideoPlayer from '$lib/components/ui/VideoPlayer.svelte';
	import { PAGE_CARD, cell, toSizes } from '$lib/utils/imgSizes';
	import type { CmsMedia } from '$lib/interfaces/page';

	interface Props {
		medias?: CmsMedia[];
		title: string;
		class?: string;
	}

	let { medias = [], title, class: className = '' }: Props = $props();

	const isSingle = $derived(medias.length === 1);
	const mediaSpan = (index: number) => (isSingle ? 3 : [2, 1, 1, 2][index % 4]);

	const mediaSizes = (index: number) =>
		toSizes(cell(PAGE_CARD, { 0: 1, 1024: 3 }, 1.5, mediaSpan(index)));
</script>

{#if medias.length > 0}
	<section class={['px-base', className]} aria-label="Médias — {title}">
		<div class="grid grid-cols-1 lg:grid-cols-3 max-lg:gap-y-2.5 gap-6 items-stretch">
			{#each medias as media, index (index)}
				<figure
					class="m-0 flex flex-col"
					class:lg:col-span-2={mediaSpan(index) === 2}
					class:lg:col-span-3={mediaSpan(index) === 3}
				>
					<div
						class={[
							'grow overflow-hidden rounded-2xl',
							isSingle ? 'aspect-video' : 'min-h-50 md:min-h-80 lg:min-h-100'
						]}
						class:[contain:size]={media.type !== 'video' && !isSingle}
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
		</div>
	</section>
{/if}
