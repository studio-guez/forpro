<script lang="ts">
	import type { Snippet } from 'svelte';
	import Img from '$lib/components/ui/Img.svelte';
	import type { CmsImage } from '$lib/interfaces/page';

	interface Props {
		title: string;
		subtitle: string;
		shortDesc: string;
		cover: CmsImage | null;
		meta?: Snippet;
	}

	let { title, subtitle, shortDesc, cover, meta }: Props = $props();
</script>

<header class="px-card">
	{#if cover}
		<Img
			image={cover}
			alt={cover.alt ?? title}
			loading="eager"
			fetchpriority="high"
			sizes="100vw"
			class="w-full h-auto max-h-[60vh] object-cover rounded-2xl"
		/>
	{/if}

	<div class="mt-10 grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-12">
		<div class="lg:col-span-2">
			<p class="text-label text-teal">{subtitle}</p>
			<h1 class="text-h1 text-blue mt-2">{title}</h1>
			<div class="text-body-1 prose">{@html shortDesc}</div>
		</div>

		{#if meta}
			<aside class="lg:pt-1 space-y-6">
				{@render meta()}
			</aside>
		{/if}
	</div>
</header>
