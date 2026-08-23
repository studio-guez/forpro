<script lang="ts">
	import type { ModuleGrilleImagesContent, Theme } from '$lib/interfaces/page';
	import { cardThemeColors } from '$lib/utils/themeColors';
	import Card from '$lib/components/ui/Card.svelte';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';
	import Img from '$lib/components/ui/Img.svelte';
	import ShapeCasesDefault1 from '$lib/components/svg/ShapeCasesDefault1.svelte';
	import ShapeCasesDefault2 from '$lib/components/svg/ShapeCasesDefault2.svelte';

	interface Props {
		content: ModuleGrilleImagesContent;
		theme: Theme;
	}

	let { content, theme }: Props = $props();

	const colors = $derived(cardThemeColors[theme][content.variant]);

	// White text means the block is drawn on a coloured background: the cta is inverted onto it.
	const ctaInverted = $derived(colors.text === 'var(--color-white)');
	const ctaColor = $derived(ctaInverted ? colors.bg : colors.text);

	// 5 images on a 6-column grid: 2 on the first row, 3 on the second.
	const span = (index: number) => (index < 2 ? 'lg:col-span-3' : 'lg:col-span-2');
</script>

<Card
	background={colors.bg}
	color={colors.text}
	shapeLeft={ShapeCasesDefault1}
	shapeRight={ShapeCasesDefault2}
	shapeColor={colors.bgContrast}
	title={content.title}
	shortDesc={content.shortDesc}
	titleBackground="var(--color-white)"
	titleColor={colors.title}
>
	<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-6 mt-12">
		{#each content.images as item, i (i)}
			<figure class={span(i)}>
				<div class="overflow-hidden rounded-2xl aspect-4/3">
					<Img image={item.image} class="w-full h-full object-cover" />
				</div>
				<figcaption class="text-h4 mt-4">{item.title}</figcaption>
			</figure>
		{/each}
	</div>

	{#if content.cta}
		<div class="flex justify-center md:justify-end mt-8">
			<CtaLink cta={content.cta} color={ctaColor} inverted={ctaInverted} />
		</div>
	{/if}
</Card>
