<script lang="ts">
	import { browser } from '$app/environment';
	import { page } from '$app/state';
	import type { CmsImage } from '$lib/interfaces/page';
	import { contrast, highContrastMedia } from '$lib/utils/contrast.svelte';
	import { PAGE, toSizes } from '$lib/utils/imgSizes';

	// No image reaches past the content column, so this is the safe default `sizes` (far tighter than 100vw).
	const DEFAULT_SIZES = toSizes(PAGE);

	interface Props {
		image: CmsImage;
		alt?: string | null;
		/** Rendered slot width — build it with `$lib/utils/imgSizes` rather than guessing. */
		sizes?: string;
		class?: string;
		loading?: 'lazy' | 'eager';
		fetchpriority?: 'auto' | 'high' | 'low';
	}

	let {
		image,
		alt = null,
		sizes = DEFAULT_SIZES,
		class: className = '',
		loading = 'lazy',
		fetchpriority = 'auto'
	}: Props = $props();

	const highContrastSourceMedia = $derived(
		highContrastMedia(browser ? contrast.preference : (page.data.contrast ?? 'auto'))
	);
</script>

{#snippet img()}
	<img
		src={image.url}
		srcset={image.srcset}
		{sizes}
		width={image.width}
		height={image.height}
		alt={alt ?? image.alt ?? ''}
		style="object-position: {image.focus ?? 'center'}"
		class={className}
		{loading}
		{fetchpriority}
	/>
{/snippet}

{#if image.highContrast}
	<!-- The `<source>` matches exactly when the `hc:` variant applies, so the browser fetches only the version it shows, with the caller's priority, and swaps it live when the mode changes. `display: contents` keeps the callers' layout classes working on the `<img>`. -->
	<picture class="contents">
		<source
			media={highContrastSourceMedia}
			srcset={image.highContrast.srcset}
			{sizes}
			width={image.highContrast.width}
			height={image.highContrast.height}
		/>
		{@render img()}
	</picture>
{:else}
	{@render img()}
{/if}
