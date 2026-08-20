{#if content}
	<article class="app-content-page">
		<header class="app-content-page__header">
			<h1 class="app-content-page__header__title fp-heading-h3">{content.title}</h1>
			<p class="app-content-page__header__subtitle">{content.subtitle}</p>

			<slot name="meta" />

			{#if content.shortDesc}
				<p class="app-content-page__header__short-desc">{content.shortDesc}</p>
			{/if}
		</header>

		<AppContentCover cover={content.cover} alt={content.title} />

		<slot name="details" />

		<AppContentBlocks blocks={content.blocks} />

		<AppContentMedias medias={content.medias} />

		<AppContentVideos videos={content.embedVideos} />

		<AppContentLinks links={content.externalLinks} />
	</article>
{/if}

<script lang="ts">
	import type { IContentBase } from '$lib/interfaces/cmsApiResponse';
	import AppContentBlocks from '$lib/components/AppContentBlocks.svelte';
	import AppContentCover from '$lib/components/AppContentCover.svelte';
	import AppContentLinks from '$lib/components/AppContentLinks.svelte';
	import AppContentMedias from '$lib/components/AppContentMedias.svelte';
	import AppContentVideos from '$lib/components/AppContentVideos.svelte';

	export let content: IContentBase | null = null;
</script>

<style lang="scss">
	.app-content-page {
		display: flex;
		flex-direction: column;
		gap: 2.5rem;
		box-sizing: border-box;
		width: 100%;
		max-width: 62.5rem;
		margin: 0 auto 5rem;
		padding: 2rem 1rem;
	}

	.app-content-page__header {
		display: flex;
		flex-direction: column;
		gap: 1rem;
		align-items: center;
		text-align: center;
	}

	.app-content-page__header__title {
		margin: 0;
		font-weight: 600;
		color: var(--app-color--pink);
	}

	.app-content-page__header__subtitle {
		margin: 0;
		font-weight: 600;
	}

	.app-content-page__header__short-desc {
		margin: 0;
		max-width: 45rem;
	}
</style>
