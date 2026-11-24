<script lang="ts">
	import type { CmsImage, CmsImageRendition } from '$lib/interfaces/page';
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
</script>

{#snippet picture(
	rendition: CmsImageRendition,
	renditionLoading: Props['loading'],
	renditionPriority: Props['fetchpriority']
)}
	<img
		src={rendition.url}
		srcset={rendition.srcset}
		{sizes}
		width={rendition.width}
		height={rendition.height}
		alt={alt ?? image.alt ?? ''}
		style="object-position: {image.focus ?? 'center'}"
		class={className}
		loading={renditionLoading}
		fetchpriority={renditionPriority}
	/>
{/snippet}

{#if image.highContrast}
	<!-- Both versions are in the markup so the `hc:` variant swaps them live, whichever way the mode was set. `display: contents` keeps the callers' layout classes working on the `<img>`, and a lazy image under `display: none` is never fetched. -->
	<span class="contents hc:hidden">{@render picture(image, loading, fetchpriority)}</span>
	<span class="hidden hc:contents">{@render picture(image.highContrast, 'lazy', 'auto')}</span>
{:else}
	{@render picture(image, loading, fetchpriority)}
{/if}
