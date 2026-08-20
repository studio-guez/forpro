<script lang="ts">
	import type { CmsImage } from '$lib/interfaces/page';

	interface Props {
		image: CmsImage;
		alt?: string | null;
		sizes?: string | undefined;
		class?: string;
		loading?: 'lazy' | 'eager';
		fetchpriority?: 'auto' | 'high' | 'low';
	}

	let {
		image,
		alt = null,
		sizes = undefined,
		class: className = '',
		loading = 'lazy',
		fetchpriority = 'auto',
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
