<script lang="ts">
	import type { Component } from 'svelte';
	import type { ModuleCasesContent, Theme } from '$lib/interfaces/page';
	import { cardThemeColors } from '$lib/utils/themeColors';
	import Card from '$lib/components/ui/Card.svelte';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';
	import VideoPlayer from '$lib/components/ui/VideoPlayer.svelte';
	import Img from '$lib/components/ui/Img.svelte';
	import ShapeCasesDefault1 from '$lib/components/svg/ShapeCasesDefault1.svelte';
	import ShapeCasesDefault2 from '$lib/components/svg/ShapeCasesDefault2.svelte';
	import ShapeCasesLearninglab1 from '$lib/components/svg/ShapeCasesLearninglab1.svelte';
	import ShapeCasesLearninglab2 from '$lib/components/svg/ShapeCasesLearninglab2.svelte';
	import ShapeCasesFoodlab1 from '$lib/components/svg/ShapeCasesFoodlab1.svelte';
	import ShapeCasesFoodlab2 from '$lib/components/svg/ShapeCasesFoodlab2.svelte';
	import ShapeCasesGrandlab1 from '$lib/components/svg/ShapeCasesGrandlab1.svelte';
	import ShapeCasesGrandlab2 from '$lib/components/svg/ShapeCasesGrandlab2.svelte';
	import ShapeCasesMakerlab1 from '$lib/components/svg/ShapeCasesMakerlab1.svelte';
	import ShapeCasesMakerlab2 from '$lib/components/svg/ShapeCasesMakerlab2.svelte';

	interface Props {
		content: ModuleCasesContent;
		theme: Theme;
	}

	let { content, theme }: Props = $props();

	const colors = $derived(cardThemeColors[theme][content.variant]);

	// White text means the block is drawn on a coloured background: the cta is inverted onto it.
	const ctaInverted = $derived(colors.text === 'var(--color-white)');
	const ctaColor = $derived(ctaInverted ? colors.bg : colors.text);

	const isMediaLeft = (index: number) => {
		if (content.layout === 'images-left') return true;
		if (content.layout === 'images-right') return false;
		return !(index % 2 === 0);
	};

	// For single-media rows, alternate the media column span in a 2/1/1/2 pattern.
	const singleMediaSpan = (index: number) => [2, 2 ,1, 1][index % 4];

	// Decorative background shapes (left, right) per theme.
	const shapePairs: Partial<Record<Theme, [Component, Component]>> = {
		learninglab: [ShapeCasesLearninglab1, ShapeCasesLearninglab2],
		foodlab: [ShapeCasesFoodlab1, ShapeCasesFoodlab2],
		grandlab: [ShapeCasesGrandlab1, ShapeCasesGrandlab2],
		makerlab: [ShapeCasesMakerlab1, ShapeCasesMakerlab2],
	};
	const [ShapeLeft, ShapeRight] = $derived(shapePairs[theme] ?? [ShapeCasesDefault1, ShapeCasesDefault2]);
</script>

<Card
	background={colors.bg}
	color={colors.text}
	shapeLeft={ShapeLeft}
	shapeRight={ShapeRight}
	shapeColor={colors.bgContrast}
	title={content.title}
	hideTitle={content.hideTitle}
	shortDesc={content.intro}
	titleBackground="var(--color-white)"
	titleColor={colors.title}
>
	<div class="flex flex-col gap-16 mt-12">
		{#each content.rows as row, i (i)}
			<article class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch" aria-label={row.hideTitle ? row.title : undefined}>
                {#each row.media as media, m (m)}
                    <div class="overflow-hidden rounded-2xl min-h-75" class:[contain:size]={media.type !== 'video'} class:lg:col-span-2={singleMediaSpan(m) === 2}>
                        {#if media.type === 'video'}
                            <VideoPlayer src={media.url} />
                        {:else}
                            <Img image={media} class="w-full h-full object-cover" />
                        {/if}
                    </div>
                {/each}
				<div class="min-h-75 flex flex-col justify-center" class:lg:order-first={!isMediaLeft(i)}>
					{#if row.hideTitle}
						<h3 class="sr-only">{row.title}</h3>
					{:else}
						<h3 class="text-h4 mb-6">{row.title}</h3>
					{/if}
					<div class="prose text-body-2">
						{@html row.description}
					</div>
					{#if row.cta}
						<CtaLink cta={row.cta} color={ctaColor} inverted={ctaInverted} class="mt-8" />
					{/if}
				</div>
			</article>
		{/each}
	</div>
</Card>
