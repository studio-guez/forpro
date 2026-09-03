<script lang="ts">
	import type { ModuleResourcesContent } from '$lib/interfaces/page';
	import Card from '$lib/components/ui/Card.svelte';
	import Carousel from '$lib/components/ui/Carousel.svelte';
	import ResourceCard from '$lib/components/ui/ResourceCard.svelte';
	import { CARD, cell, toSizes } from '$lib/utils/imgSizes';
	import ShapeCasesDefault1 from '$lib/components/svg/ShapeCasesDefault1.svelte';
	import ShapeCasesDefault2 from '$lib/components/svg/ShapeCasesDefault2.svelte';

	interface Props {
		content: ModuleResourcesContent;
	}

	let { content }: Props = $props();

	// The block is always purple; the default variant is filled (purple bg), inverted is light.
	const colors = { main: 'var(--color-purple-light)', deco: 'var(--color-purple-pale)' };

	const filled = $derived(content.variant !== 'inverted');

	const cardSizes = toSizes(cell(CARD, { 0: 1.25, 768: 3 }, 1.5));
</script>

{#if content.resources.length > 0}
	<Card
		title={content.title}
		shortDesc={content.shortDesc}
		titleBackground={filled ? 'var(--color-white)' : colors.main}
		titleColor={filled ? colors.main : 'var(--color-white)'}
		background={filled ? colors.main : null}
		color={filled ? 'var(--color-white)' : colors.main}
		shapeLeft={filled ? ShapeCasesDefault1 : null}
		shapeRight={filled ? ShapeCasesDefault2 : null}
		shapeRightClasses="absolute max-lg:bottom-0 lg:top-0 right-0 translate-x-1/6 translate-y-1/10 lg:-translate-y-1/10 w-2/5 max-lg:rotate-180 max-lg:-scale-x-100"
		shapeColor={colors.deco}
	>
		<Carousel
			items={content.resources}
			label={content.title}
			color={colors.main}
			inverted={filled}
			itemClass="w-4/5 md:w-[calc((100%-3rem)/3)]"
			class="mt-12"
		>
			{#snippet item(resource)}
				<ResourceCard {resource} sizes={cardSizes} />
			{/snippet}
		</Carousel>
	</Card>
{/if}
