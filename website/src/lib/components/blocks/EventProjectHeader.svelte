<script lang="ts">
	import type { Snippet } from 'svelte';
	import Img from '$lib/components/ui/Img.svelte';
	import { PAGE_CARD, toSizes } from '$lib/utils/imgSizes';
	import type { CmsImage } from '$lib/interfaces/page';

	interface Props {
		title: string;
		subtitle: string;
		shortDesc: string;
		cover: CmsImage | null;
		variant: 'event' | 'project';
		meta?: Snippet;
	}

	let { title, subtitle, shortDesc, cover, variant, meta }: Props = $props();

	const textColor = $derived(variant === 'project' ? 'text-orange' : 'text-blue');
</script>

<section class="px-card">
	<div class={textColor}>
		<h1 class="text-h1 mt-2">{title}</h1>
		{#if meta}
			<div class="lg:pt-1.5">
				{@render meta()}
			</div>
		{/if}
	</div>

	<div class="mt-18 grid grid-cols-1 lg:grid-cols-3 gap-y-3 gap-6 items-end">
		<div>
			<h2 class="text-h4">{subtitle}</h2>
			<div class="text-body-2 prose mt-3 lg:mt-6">{@html shortDesc}</div>
		</div>
		{#if cover}
			<Img
				image={cover}
				alt={cover.alt ?? title}
				loading="eager"
				fetchpriority="high"
				sizes={toSizes(PAGE_CARD)}
				class="w-full h-50 md:h-70 lg:h-100 object-cover rounded-2xl lg:col-span-2 max-lg:order-first"
			/>
		{/if}
	</div>
</section>
