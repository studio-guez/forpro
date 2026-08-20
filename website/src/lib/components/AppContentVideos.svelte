{#if embeds.length > 0}
	<div class="app-content-videos">
		{#each embeds as embed (embed.id)}
			<div
				class="app-content-videos__item"
				class:app-content-videos__item--short={embed.isShort}
			>
				<iframe
					class="app-content-videos__item__frame"
					src={embed.embedUrl}
					title={embed.title ?? 'Vidéo Youtube'}
					loading="lazy"
					referrerpolicy="strict-origin-when-cross-origin"
					allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
					allowfullscreen
				></iframe>
				{#if embed.title}
					<div class="app-content-videos__item__title">{embed.title}</div>
				{/if}
			</div>
		{/each}
	</div>
{/if}

<script lang="ts">
	import type { IEmbedVideo } from '$lib/interfaces/cmsApiResponse';
	import { parseYoutubeUrl } from '$lib/utils/youtube';

	export let videos: IEmbedVideo[] = [];

	/**
	 * Only youtube videos and shorts are supported.
	 * The embed url is computed by the CMS, but it is recomputed here as a
	 * fallback so that any raw youtube url stays displayable.
	 */
	$: embeds = videos
		.map((video) => {
			if (video?.embedUrl && video?.id) return video;

			const parsed = parseYoutubeUrl(video?.url);

			return parsed ? { ...video, ...parsed } : null;
		})
		.filter((video): video is IEmbedVideo => video !== null);
</script>

<style lang="scss">
	.app-content-videos {
		display: flex;
		flex-wrap: wrap;
		gap: 1rem;
		width: 100%;
	}

	.app-content-videos__item {
		flex: 1 1 20rem;
		max-width: 100%;
	}

	.app-content-videos__item--short {
		flex: 0 1 15rem;
	}

	.app-content-videos__item__frame {
		display: block;
		width: 100%;
		aspect-ratio: 16 / 9;
		border: 0;
		border-radius: 0.5rem;
	}

	.app-content-videos__item--short .app-content-videos__item__frame {
		aspect-ratio: 9 / 16;
	}

	.app-content-videos__item__title {
		margin-top: 0.25rem;
		font-size: 0.75rem;
	}
</style>
