<script lang="ts">
	import type { ModuleResourcesContent } from '$lib/interfaces/page';
	import Card from '$lib/components/ui/Card.svelte';
	import Carousel from '$lib/components/ui/Carousel.svelte';
	import ResourceCard from '$lib/components/ui/ResourceCard.svelte';
	import ShapeAgenda1 from '$lib/components/svg/ShapeAgenda1.svelte';
	import ShapeAgenda2 from '$lib/components/svg/ShapeAgenda2.svelte';

	interface Props {
		content: ModuleResourcesContent;
	}

	let { content }: Props = $props();

	// The block is always purple; the default variant is filled (purple bg), inverted is light.
	const colors = { main: 'var(--color-purple-light)', deco: 'var(--color-purple-pale)' };

	const filled = $derived(content.variant !== 'inverted');
</script>

{#if content.resources.length > 0}
	<Card
		title={content.title}
		shortDesc={content.shortDesc}
		titleBackground={filled ? 'var(--color-white)' : colors.main}
		titleColor={filled ? colors.main : 'var(--color-white)'}
		background={filled ? colors.main : null}
		color={filled ? 'var(--color-white)' : colors.main}
		shapeLeft={filled ? ShapeAgenda1 : null}
		shapeRight={filled ? ShapeAgenda2 : null}
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
				<ResourceCard {resource} />
			{/snippet}
		</Carousel>
	</Card>
{/if}
