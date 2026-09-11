<script lang="ts">
	import type { ModuleCasesContent, Theme } from '$lib/interfaces/page';
	import { getThemeColors } from '$lib/utils/themeColors';
	import { getThemeShapes } from '$lib/utils/themeShapes';
	import Card from '$lib/components/ui/Card.svelte';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';
	import VideoPlayer from '$lib/components/ui/VideoPlayer.svelte';
	import Img from '$lib/components/ui/Img.svelte';
	import { CARD, cell, toSizes } from '$lib/utils/imgSizes';

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
	const singleMediaSpan = (index: number) => [2, 2, 1, 1][index % 4];

	const mediaSizes = (index: number) =>
		toSizes(cell(CARD, { 0: 1, 1024: 3 }, 1.5, singleMediaSpan(index)));

	const [[ShapeLeft, shapeLeftClasses], [ShapeRight, shapeRightClasses]] = $derived(
		getThemeShapes(theme)
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
					<div
						class="overflow-hidden rounded-2xl min-h-75"
						class:[contain:size]={media.type !== 'video'}
						class:lg:col-span-2={singleMediaSpan(m) === 2}
					>
						{#if media.type === 'video'}
							<VideoPlayer src={media.url} />
						{:else}
							<Img image={media} sizes={mediaSizes(m)} class="w-full h-full object-cover" />
						{/if}
					</div>
				{/each}
				<div
					class="flex flex-col justify-center lg:items-start max-lg:group-even:text-right max-lg:text-balance"
					class:lg:order-first={!isMediaLeft(i)}
				>
					{#if row.title}
						<h3 class="text-h4 mb-3 lg:mb-6">{row.title}</h3>
					{/if}
					<div class="prose text-body-2">
						{@html row.description}
					</div>
					{#if row.cta}
						<CtaLink
							cta={row.cta}
							color={colors.accent}
							inverted={colors.onDark}
							class="mt-4 lg:mt-8 max-lg:self-start max-lg:group-even:self-end"
						/>
					{/if}
				</div>
			</div>
		{/each}
	</div>

	{#if content.cta}
		<div class="flex justify-center mt-12">
			<CtaLink cta={content.cta} color={colors.accent} inverted={colors.onDark} />
		</div>
	{/if}
</Card>
