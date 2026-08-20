{#if medias.length > 0}
	<div class="app-content-medias">
		{#each medias as media (media.url)}
			<figure class="app-content-medias__item">
				<img
					class="app-content-medias__item__image"
					src={media.resize.small}
					srcset="{media.resize.small} 500w, {media.resize.reg} 1280w, {media.resize.large} 1920w"
					sizes="(max-width: 40rem) 100vw, 33vw"
					alt={media.alt ?? ''}
					width={media.width}
					height={media.height}
					loading="lazy"
				/>
				{#if media.caption}
					<figcaption class="app-content-medias__item__caption">{media.caption}</figcaption>
				{/if}
			</figure>
		{/each}
	</div>
{/if}

<script lang="ts">
	import type { IImage } from '$lib/interfaces/cmsApiResponse';

	export let medias: IImage[] = [];
</script>

<style lang="scss">
	.app-content-medias {
		display: grid;
		grid-template-columns: repeat(auto-fill, minmax(15rem, 1fr));
		gap: 1rem;
		width: 100%;
	}

	.app-content-medias__item {
		margin: 0;
	}

	.app-content-medias__item__image {
		display: block;
		width: 100%;
		height: auto;
		border-radius: 0.5rem;
	}

	.app-content-medias__item__caption {
		margin-top: 0.25rem;
		font-size: 0.75rem;
	}
</style>
