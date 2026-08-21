<script lang="ts">
	import type { ModuleAgendaContent } from '$lib/interfaces/page';
	import Carousel from '$lib/components/ui/Carousel.svelte';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';
	import EventCard from '$lib/components/ui/EventCard.svelte';
	import ShapeCasesDefault1 from '$lib/components/svg/ShapeCasesDefault1.svelte';
	import ShapeCasesDefault2 from '$lib/components/svg/ShapeCasesDefault2.svelte';

	interface Props {
		content: ModuleAgendaContent;
	}

	let { content }: Props = $props();

	// The block is always blue; the default variant is filled (blue bg), inverted is light.
	const colors = { main: 'var(--color-blue)', deco: 'var(--color-blue-light)' };

	const filled = $derived(content.variant !== 'inverted');
	const textColor = $derived(filled ? 'var(--color-white)' : colors.main);

	const uid = $props.id();
	const titleId = `module-agenda-${uid}`;
</script>

<section
	class="px-card rounded-3xl relative overflow-hidden"
	class:pt-12={filled}
	class:pb-18={filled}
	style={filled
		? `background-color: ${colors.main}; color: var(--color-white)`
		: `color: ${colors.main}`}
	aria-labelledby={titleId}
>
	{#if filled}
		<div class="absolute top-0 left-0 -translate-1/6 w-2/5" style="color: {colors.deco}">
			<ShapeCasesDefault1 class="w-full h-auto" />
		</div>
		<div
			class="absolute top-0 right-0 -translate-y-1/6 translate-x-1/6 w-2/5"
			style="color: {colors.deco}"
		>
			<ShapeCasesDefault2 class="w-full h-auto" />
		</div>
	{/if}

	<div class="relative z-1">
		<div class="text-center">
			<h2
				id={titleId}
				class="inline-block text-h3 pt-2 pb-3.5 px-8 rounded-2xl"
				style={filled
					? `background-color: var(--color-white); color: ${colors.main}`
					: `background-color: ${colors.main}; color: var(--color-white)`}
			>
				{content.title}
			</h2>
			{#if content.shortDesc}
				<div class="prose text-body-1 font-bold mt-9" style="color: {textColor}">
					{@html content.shortDesc}
				</div>
			{/if}
		</div>

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
	</div>
</section>
