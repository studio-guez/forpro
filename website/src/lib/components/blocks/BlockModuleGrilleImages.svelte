<script lang="ts">
	import type { ModuleGrilleImagesContent, Theme } from '$lib/interfaces/page';
	import { getThemeColors } from '$lib/utils/themeColors';
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

	const colors = $derived(getThemeColors(theme, content.variant));

	// 5 images on a 6-column grid: 2 on the first row, 3 on the second.
	const span = (index: number) => (index < 2 ? 'lg:col-span-3' : 'lg:col-span-2');
</script>

<Card
	background={colors.bg}
	color={colors.text}
	shapeLeft={colors.showShapes ? ShapeCasesDefault1 : null}
	shapeRight={colors.showShapes ? ShapeCasesDefault2 : null}
	shapeColor={colors.bgContrast}
	title={content.title}
	shortDesc={content.shortDesc}
	titleBackground={colors.titleBackground}
	titleColor={colors.title}
>
	<div class="grid grid-cols-1 sm:grid-cols-1 lg:grid-cols-6 max-lg:gap-y-9 gap-6 mt-12">
		{#each content.images as item, i (i)}
			<figure class={span(i)}>
				<div class="overflow-hidden rounded-2xl aspect-16/9 lg:aspect-4/3">
					<Img image={item.image} class="w-full h-full object-cover" />
				</div>
				<figcaption class="text-h4 mt-3 max-lg:text-center">{item.title}</figcaption>
			</figure>
		{/each}
	</div>

	{#if content.cta}
		<div class="flex justify-center lg:justify-end mt-8">
			<CtaLink cta={content.cta} color={colors.accent} inverted={colors.onDark} size="lg" />
		</div>
	{/if}
</Card>
