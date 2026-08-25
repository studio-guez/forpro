<script lang="ts">
	import type { CmsImage } from '$lib/interfaces/page';
	import Img from '$lib/components/ui/Img.svelte';

	interface Props {
		title: string;
		cover?: CmsImage | null;
		intro?: string | null;
	}

	let { title, cover = null, intro = null }: Props = $props();
</script>

<section aria-labelledby="page-title">
	{#if cover}
		<Img
			image={cover}
			alt={cover.alt ?? title}
			sizes="100vw"
			loading="eager"
			fetchpriority="high"
			class="w-full aspect-video md:aspect-3/1 object-cover rounded-3xl mb-18"
		/>
	{/if}

	<h1 id="page-title" class="text-h1 text-blue text-center">{title}</h1>

	{#if intro}
		<div class="text-body-2 prose text-center max-w-3xl mx-auto mt-6">{@html intro}</div>
	{/if}
</section>
