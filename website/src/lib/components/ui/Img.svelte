<script lang="ts">
	import type { CmsImage } from '$lib/interfaces/page';
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
