<script lang="ts">
	import type { Component } from 'svelte';
	import type { ModuleCasesContent, Theme } from '$lib/interfaces/page';
	import { getThemeColors } from '$lib/utils/themeColors';
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

	const colors = $derived(getThemeColors(theme, content.variant));

	const isMediaLeft = (index: number) => {
		if (content.layout === 'images-left') return true;
		if (content.layout === 'images-right') return false;
		return !(index % 2 === 0);
	};

	// For single-media rows, alternate the media column span in a 2/1/1/2 pattern.
	const singleMediaSpan = (index: number) => [2, 2 ,1, 1][index % 4];

	// Decorative background shapes (left, right) per theme. Each shape carries its own
	// positioning classes: the SVGs range from 0.52 to 1.56 in aspect ratio, so a single
	// width would make the tall ones overflow and the wide ones look undersized.
	type ShapeSpec = [component: Component<{ class?: string }>, classes: string];

	const defaultShapes: [ShapeSpec, ShapeSpec] = [
		[ShapeCasesDefault1, 'absolute top-0 left-0 -translate-1/6 w-2/5'],
		[ShapeCasesDefault2, 'absolute top-0 right-0 -translate-y-1/6 translate-x-1/6 w-2/5'],
	];

	const shapePairs: Partial<Record<Theme, [ShapeSpec, ShapeSpec]>> = {
		learninglab: [
			[ShapeCasesLearninglab1, 'absolute top-0 left-0 -translate-x-1/6 -translate-y-1/8 w-1/3'],
			[ShapeCasesLearninglab2, 'absolute top-0 right-0 translate-x-1/6 -translate-y-1/4 w-1/2'],
		],
		foodlab: [
			[ShapeCasesFoodlab1, 'absolute top-0 left-0 -translate-x-1/5 -translate-y-1/12 w-1/3'],
			[ShapeCasesFoodlab2, 'absolute top-0 right-0 translate-x-1/6 -translate-y-1/5 w-2/5'],
		],
		grandlab: [
			[ShapeCasesGrandlab1, 'absolute top-0 left-0 -translate-x-1/5 -translate-y-1/12 w-1/4'],
			[ShapeCasesGrandlab2, 'absolute top-0 right-0 translate-x-1/6 -translate-y-1/6 w-2/5'],
		],
		makerlab: [
			[ShapeCasesMakerlab1, 'absolute top-0 left-0 -translate-x-1/6 -translate-y-1/10 w-1/3'],
			[ShapeCasesMakerlab2, 'absolute top-0 right-0 translate-x-1/6 -translate-y-1/6 w-2/5'],
		],
	};

	const [[ShapeLeft, shapeLeftClasses], [ShapeRight, shapeRightClasses]] = $derived(
		shapePairs[theme] ?? defaultShapes
	);
</script>

<Card
	background={colors.bg}
	color={colors.text}
	shapeLeft={colors.showShapes ? ShapeLeft : null}
	shapeRight={colors.showShapes ? ShapeRight : null}
	{shapeLeftClasses}
	{shapeRightClasses}
	shapeColor={colors.bgContrast}
	title={content.title}
	hideTitle={content.hideTitle}
	shortDesc={content.intro}
	titleBackground={colors.titleBackground}
	titleColor={colors.title}
>
	<div class="flex flex-col gap-y-9 lg:gap-y-16 mt-12">
		{#each content.rows as row, i (i)}
			<div class="grid grid-cols-1 lg:grid-cols-3 gap-y-3 gap-x-6 items-stretch group">
                {#each row.media as media, m (m)}
                    <div class="overflow-hidden rounded-2xl min-h-75" class:[contain:size]={media.type !== 'video'} class:lg:col-span-2={singleMediaSpan(m) === 2}>
                        {#if media.type === 'video'}
                            <VideoPlayer src={media.url} />
                        {:else}
                            <Img image={media} class="w-full h-full object-cover" />
                        {/if}
                    </div>
                {/each}
				<div class="flex flex-col justify-center lg:items-start max-lg:group-even:text-right max-lg:text-balance" class:lg:order-first={!isMediaLeft(i)}>
					{#if row.title}
						<h3 class="text-h4 mb-3 lg:mb-6">{row.title}</h3>
					{/if}
					<div class="prose text-body-2">
						{@html row.description}
					</div>
					{#if row.cta}
						<CtaLink cta={row.cta} color={colors.accent} inverted={colors.onDark} class="mt-4 lg:mt-8 max-lg:self-start max-lg:group-even:self-end" />
					{/if}
				</div>
			</div>
		{/each}
	</div>

	{#if content.cta}
		<div class="flex justify-center mt-12">
			<CtaLink cta={content.cta} color={colors.accent} inverted={colors.onDark} size="lg" />
		</div>
	{/if}
</Card>
