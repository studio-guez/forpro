<script lang="ts">
	import type { Component } from 'svelte';
	import type { ModuleCasesContent, Theme, Variant } from '$lib/interfaces/page';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';
	import VideoPlayer from '$lib/components/ui/VideoPlayer.svelte';
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

	type ThemeColors = { bg: string; bgContrast: string; text: string; title: string };

	const themeConfig: Record<Theme, Record<Variant, ThemeColors>> = {
		default: {
			default: {
				bg: 'var(--color-purple)',
				bgContrast: 'var(--color-purple-light)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--bg-grey-dark)',
				bgContrast: 'var(--color-blue)',
				text: 'var(--bg-grey-dark)',
				title: 'var(--color-blue)',
			},
		},
		campus: {
			default: {
				bg: 'var(--color-blue)',
				bgContrast: 'var(--color-blue-light)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--color-white)',
				bgContrast: 'var(--color-blue)',
				text: 'var(--color-blue)',
				title: 'var(--color-blue)',
			},
		},
		entreprendre: {
			default: {
				bg: 'var(--color-purple-light)',
				bgContrast: 'var(--color-purple-pale)',
				text: 'var(--color-white)',
				title: 'var(--color-purple-light)',
			},
			inverted: {
				bg: 'var(--color-purple-pale)',
				bgContrast: 'var(--color-purple-light)',
				text: 'var(--color-white)',
				title: 'var(--color-purple-pale)',
			},
		},
		projets_jeunes: {
			default: {
				bg: 'var(--color-orange)',
				bgContrast: 'var(--color-orange-light)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--color-orange-light)',
				bgContrast: 'var(--color-orange)',
				text: 'var(--color-orange)',
				title: 'var(--color-orange)',
			},
		},
		tremplin_jobs: {
			default: {
				bg: 'var(--color-purple-light)',
				bgContrast: 'var(--color-purple)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--color-purple)',
				bgContrast: 'var(--color-purple-light)',
				text: 'var(--color-purple-light)',
				title: 'var(--color-purple-light)',
			},
		},
		soutiens: {
			default: {
				bg: 'var(--color-pink)',
				bgContrast: 'var(--color-purple)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--color-purple)',
				bgContrast: 'var(--color-pink)',
				text: 'var(--color-pink)',
				title: 'var(--color-pink)',
			},
		},
		cekale: {
			default: {
				bg: 'var(--color-purple)',
				bgContrast: 'var(--color-purple-light)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--color-purple-pale)',
				bgContrast: 'var(--color-purple)',
				text: 'var(--color-purple)',
				title: 'var(--color-purple)',
			},
		},
		la_ref: {
			default: {
				bg: 'var(--color-pink)',
				bgContrast: 'var(--color-purple)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--color-purple)',
				bgContrast: 'var(--color-pink)',
				text: 'var(--color-pink)',
				title: 'var(--color-pink)',
			},
		},
		learninglab: {
			default: {
				bg: 'var(--color-teal)',
				bgContrast: 'var(--color-teal-light)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--color-teal-light)',
				bgContrast: 'var(--color-teal)',
				text: 'var(--color-teal)',
				title: 'var(--color-teal)',
			},
		},
		foodlab: {
			default: {
				bg: 'var(--color-orange)',
				bgContrast: 'var(--color-orange-light)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--color-orange-light)',
				bgContrast: 'var(--color-orange)',
				text: 'var(--color-orange)',
				title: 'var(--color-orange)',
			},
		},
		grandlab: {
			default: {
				bg: 'var(--color-red)',
				bgContrast: 'var(--color-orange)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--color-orange-light)',
				bgContrast: 'var(--color-red)',
				text: 'var(--color-red)',
				title: 'var(--color-red)',
			},
		},
		makerlab: {
			default: {
				bg: 'var(--color-grey-dark)',
				bgContrast: 'var(--color-grey-light)',
				text: 'var(--color-white)',
				title: 'var(--color-white)',
			},
			inverted: {
				bg: 'var(--color-grey-light)',
				bgContrast: 'var(--color-grey-dark)',
				text: 'var(--color-grey-dark)',
				title: 'var(--color-grey-dark)',
			},
		},
	};

	const colors = $derived(themeConfig[theme][content.variant]);
	const hasBackground = $derived(!!colors.bg && colors.bg !== 'transparent' && colors.bg !== 'var(--color-white)');

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

<section
	class="px-card rounded-3xl relative overflow-hidden"
	class:pt-12={hasBackground}
	class:pb-18={hasBackground}
	style={[colors.bg && `background-color: ${colors.bg}`, colors.text && `color: ${colors.text}`].filter(Boolean).join('; ')}
	aria-label={content.hideTitle ? content.title : undefined}
	aria-labelledby={content.hideTitle ? undefined : 'module-cases-title'}
>
	<div class="mb-12 text-center relative z-1">
		{#if content.hideTitle}
			<h2 id="module-cases-title" class="sr-only">{content.title}</h2>
		{:else}
			<h2 id="module-cases-title" class="inline-block text-h3 pt-2 pb-3.5 px-8 bg-white rounded-2xl" style={colors.title && `color: ${colors.title}`}>{content.title}</h2>
		{/if}
		{#if content.intro}
			<div class="prose font-bold" class:mt-9={!content.hideTitle}>
				{@html content.intro}
			</div>
		{/if}
	</div>

	<div class="flex flex-col gap-16 relative z-1">
		{#each content.rows as row, i (i)}
			<article class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-stretch" aria-label={row.hideTitle ? row.title : undefined}>
                {#each row.media as media, m (m)}
                    <div class="overflow-hidden rounded-2xl min-h-75" class:[contain:size]={media.type !== 'video'} class:lg:col-span-2={singleMediaSpan(m) === 2}>
                        {#if media.type === 'video'}
                            <VideoPlayer src={media.url} />
                        {:else}
                            <img
                                src={media.url}
                                srcset={media.srcset}
                                width={media.width}
                                height={media.height}
                                alt={media.alt ?? ''}
                                style:object-position={media.focus ?? 'center'}
                                class="w-full h-full object-cover"
                            />
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
	<div class="absolute top-0 left-0 -translate-1/6 w-2/5" style:color={colors.bgContrast}>
		<ShapeLeft class="w-full h-auto" />
	</div>
	<div class="absolute top-0 right-0 -translate-y-1/6 translate-x-1/6 w-2/5" style:color={colors.bgContrast}>
		<ShapeRight class="w-full h-auto" />
	</div>
</section>
