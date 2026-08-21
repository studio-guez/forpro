<script lang="ts">
	import type { ModuleAgendaContent } from '$lib/interfaces/page';
	import Card from '$lib/components/ui/Card.svelte';
	import Carousel from '$lib/components/ui/Carousel.svelte';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';
	import EventCard from '$lib/components/ui/EventCard.svelte';
	import ShapeAgenda1 from '$lib/components/svg/ShapeAgenda1.svelte';
	import ShapeAgenda2 from '$lib/components/svg/ShapeAgenda2.svelte';

	interface Props {
		content: ModuleAgendaContent;
	}

	let { content }: Props = $props();

	// The block is always blue; the default variant is filled (blue bg), inverted is light.
	const colors = { main: 'var(--color-blue)', deco: 'var(--color-blue-light)' };

	const filled = $derived(content.variant !== 'inverted');
</script>

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
	{#if content.events.length > 0}
		<Carousel
			items={content.events}
			label={content.title}
			color={colors.main}
			inverted={filled}
			itemClass="w-4/5 md:w-[calc((100%-3rem)/3)] aspect-3/4"
			class="mt-12"
		>
			{#snippet item(event)}
				<EventCard {event} color={colors.main} />
			{/snippet}
		</Carousel>
	{/if}

	{#if content.cta}
		<div class="flex justify-center md:justify-end mt-8">
			<CtaLink cta={content.cta} color={colors.main} inverted={filled} />
		</div>
	{/if}
</Card>
