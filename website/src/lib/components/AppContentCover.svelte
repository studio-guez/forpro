{#if cover}
	<figure class="app-content-cover">
		<img
			class="app-content-cover__image"
			src={cover.resize.reg}
			alt={cover.alt ?? alt}
			width={cover.width}
			height={cover.height}
			loading="lazy"
		/>
		{#if cover.caption}
			<figcaption class="app-content-cover__caption">{cover.caption}</figcaption>
		{/if}
	</figure>
{/if}

<script lang="ts">
	import type { IImage } from '$lib/interfaces/cmsApiResponse';

	export let cover: IImage | null = null;
	export let alt = '';
</script>

<style lang="scss">
	.app-content-cover {
		margin: 0;
		width: 100%;
	}

	.app-content-cover__image {
		display: block;
		width: 100%;
		height: auto;
		border-radius: 1rem;
		object-fit: cover;
	}

	.app-content-cover__caption {
		margin-top: 0.5rem;
		font-size: 0.75rem;
	}
</style>
