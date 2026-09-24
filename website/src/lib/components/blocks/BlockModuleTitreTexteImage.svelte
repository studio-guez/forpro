<script lang="ts">
	import type { ModuleTitreTexteImageContent, Theme } from '$lib/interfaces/page';
	import { getThemeColors } from '$lib/utils/themeColors';
	import Card from '$lib/components/ui/Card.svelte';
	import CtaLink from '$lib/components/ui/CtaLink.svelte';
	import Img from '$lib/components/ui/Img.svelte';
	import { CARD, cell, toSizes } from '$lib/utils/imgSizes';

	interface Props {
		content: ModuleTitreTexteImageContent;
		theme: Theme;
	}

	let { content, theme }: Props = $props();

	const colors = $derived(getThemeColors(theme, content.variant));
	const isImageLeft = $derived(content.imagePosition === 'left');
	const imageFitClass = $derived(
		content.imageFit === 'contain' ? 'object-contain lg:h-8/10' : 'object-cover h-full'
	);

	const imageSizes = toSizes(cell(CARD, { 0: 1, 1280: 2 }, 1.5));
</script>

<Card
	title={content.title}
	titleVariant="plain"
	titleColor={colors.text}
	background={colors.bg}
	color={colors.text}
>
	<div class="grid grid-cols-1 xl:grid-cols-2 gap-6 lg:gap-y-12">
		{#if content.image}
			<div
				class="overflow-hidden rounded-2xl [contain:size] min-h-75 flex items-center"
				class:xl:order-last={!isImageLeft}
			>
				<Img image={content.image} sizes={imageSizes} class="w-full {imageFitClass}" />
			</div>
		{/if}
		<div class="flex flex-col justify-center">
			<div class="prose text-body-2">
				{@html content.description}
			</div>
			{#if content.cta}
				<div class="max-xl:text-right mt-6 lg:mt-12">
					<CtaLink cta={content.cta} color={colors.accent} inverted={colors.onDark} />
				</div>
			{/if}
		</div>
	</div>
</Card>
