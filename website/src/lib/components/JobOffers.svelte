<script lang="ts">
	import Img from '$lib/components/ui/Img.svelte';
	import Blocks from '$lib/components/Blocks.svelte';
	import type { JobOffersPage } from '$lib/interfaces/jobOffers';

	let { page }: { page: JobOffersPage } = $props();
</script>

{#if page.cover}
	<Img
		image={page.cover}
		alt={page.cover.alt ?? page.title}
		sizes="100vw"
		loading="eager"
		fetchpriority="high"
		class="w-full aspect-video md:aspect-3/1 object-cover rounded-3xl"
	/>
{/if}

<section aria-labelledby="job-offers-title">
	<h1 id="job-offers-title" class="text-h1 text-blue text-center">{page.title}</h1>
	{#if page.shortDesc}
		<div class="text-body-2 prose text-center max-w-3xl mx-auto mt-6">{@html page.shortDesc}</div>
	{/if}
</section>

<Blocks blocks={page.body} />
